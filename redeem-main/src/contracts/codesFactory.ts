// import { CodesFactory } from "../../../backend/types/contracts/CodesFactory";
import CodesFactoryArtifact from "./CodesFactory.json";

const codesFactoryContractAddress =
  process.env.NEXT_PUBLIC_CODES_CONTRACT_ADDRESS || "";
const codesFactoryContractAbi = CodesFactoryArtifact.abi;

// eslint-disable-next-line @typescript-eslint/no-explicit-any
export type CodesFactoryContractType = any;
export { codesFactoryContractAddress, codesFactoryContractAbi };
