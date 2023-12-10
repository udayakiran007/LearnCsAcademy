import {
  ApiGenerateCodesResponseData,
  GenerateCodesRequestBody,
} from "@/components/modules/GenerateCodes";
import {
  CodesFactoryContractType,
  codesFactoryContractAbi,
  codesFactoryContractAddress,
} from "@/contracts/codesFactory";
import CodesTreeModel from "@/models/CodesTreeModel";
import { ICodesTree } from "@/models/CodesTreeModel.types";
import connectToDatabase from "@/services/mongoose";
import { uploadMerkleLeavesToWeb3Storage } from "@/services/web3.storage";
import { stringifyBigIntValue } from "@/utils/converters";
import { generateMerkleTree } from "@/utils/merkleTree";
import {
  generateSecretCodes,
  getMessageToSign,
  verifySignature,
} from "@/utils/secretCodes";
import { JsonRpcProvider } from "@ethersproject/providers";
import { ethers } from "ethers";
import { parseEther } from "ethers/lib/utils";
import type { NextApiRequest, NextApiResponse } from "next";

const DEPLOYER_PRIVATE_KEY = process.env.DEPLOYER_PRIVATE_KEY;

const getCodesFactoryContract = () => {
  if (!DEPLOYER_PRIVATE_KEY) {
    throw new Error("DEPLOYER_PRIVATE_KEY is not defined.");
  }

  const provider = new JsonRpcProvider(process.env.RPC_URL);
  const signer = new ethers.Wallet(DEPLOYER_PRIVATE_KEY, provider);

  const codesFactoryContractWrite: CodesFactoryContractType =
    new ethers.BaseContract(
      codesFactoryContractAddress,
      codesFactoryContractAbi,
      signer
    );

  return codesFactoryContractWrite;
};

const generateCodes = async (
  req: NextApiRequest,
  res: NextApiResponse<ApiGenerateCodesResponseData>
) => {
  await connectToDatabase();

  try {
    if (req.method !== "POST") {
      res.status(405).json({ message: "Method not allowed." });
      return;
    }

    const {
      amount: amountStr,
      numberOfCodes,
      signature,
      timestamp,
    } = req.body as GenerateCodesRequestBody;

    // Check if the request has all the required fields
    if (!amountStr || !numberOfCodes || !signature || !timestamp) {
      res.status(400).json({ message: "Missing required fields." });
      return;
    }

    // Check if the timestamp is recent (e.g., within the last 5 minutes)
    const currentTime = Date.now();
    if (currentTime - timestamp > 5 * 60 * 1000) {
      res.status(400).json({ message: "Timestamp is too old." });
      return;
    }

    // Create the message to verify
    const amount = parseEther(amountStr);
    // eslint-disable-next-line @typescript-eslint/ban-ts-comment
    // @ts-ignore
    const message = getMessageToSign(amount, numberOfCodes, timestamp);

    // Verify the signature
    const isValid = await verifySignature(message, signature);

    if (!isValid) {
      res.status(403).json({ message: "Invalid signature." });
      return;
    }

    // Generate secret codes and Merkle tree
    const secretCodes = generateSecretCodes(parseInt(numberOfCodes));
    // eslint-disable-next-line @typescript-eslint/ban-ts-comment
    // @ts-ignore
    const merkleTree = generateMerkleTree(secretCodes, amount);
    const merkleTreeDump = merkleTree.dump();
    const merkleTreeDumpStr = JSON.stringify(
      merkleTreeDump,
      stringifyBigIntValue
    );

    // Store secret codes and associated data in MongoDB
    const codesTreeToInsert: Partial<ICodesTree> = {
      merkleRoot: merkleTree.root,
      amount: amountStr,
      merkleDump: merkleTreeDumpStr,
      secretCodes,
    };

    // Call the addMerkleRoot function from the CodesFactory contract
    const codesFactoryContractWrite = getCodesFactoryContract();
    const addMerkleRootTx = await codesFactoryContractWrite.addMerkleRoot(
      merkleTree.root,
      numberOfCodes,
      amount
    );
    const txReceipt = await addMerkleRootTx.wait();

    if (txReceipt.status !== 1) {
      res
        .status(500)
        .json({ message: "Failed to add Merkle root to the contract." });
      return;
    }

    const merkleRootIndex = String(txReceipt.events[1].args[0]);
    codesTreeToInsert.merkleRootIndex = merkleRootIndex;

    const merkleDumpIpfsCid = await uploadMerkleLeavesToWeb3Storage(
      merkleTreeDump.tree,
      merkleRootIndex
    );
    codesTreeToInsert.ipfsCid = merkleDumpIpfsCid;

    // Save the Merkle tree root and codes in the database
    const insertedCodesTree = await CodesTreeModel.create(codesTreeToInsert);
    res.status(201).json({
      merkleRootIndex: insertedCodesTree.merkleRootIndex,
      ipfsCid: insertedCodesTree.ipfsCid,
    });
  } catch (error) {
    res.status(500).json({ message: "Error processing request." });
  }
};

export default generateCodes;
