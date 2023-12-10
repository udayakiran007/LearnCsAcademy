import { ExecStatusDisplay } from "@/components/elements/ExecStatusDisplay";
import { Card } from "@/components/layouts/Card";
import useCreateSecretCodesApi from "@/hooks/useCreateSecretCodesApi";
import useExecStatus from "@/hooks/useExecStatus";
import { parseEther } from "ethers/lib/utils";
import React, { useState } from "react";

const GenerateCodes: React.FC = () => {
  const [numberOfCodes, setNumberOfCodes] = useState("");
  const [amount, setAmount] = useState<string>("");
  const [execStatus, updateExecStatus, clearExecStatus] = useExecStatus();

  const { sendRequest } = useCreateSecretCodesApi(updateExecStatus);

  const handleSubmit = async (event: React.FormEvent<HTMLFormElement>) => {
    event.preventDefault();
    if (!amount || !numberOfCodes) {
      updateExecStatus({
        message: "Specify the required parameters",
        pending: false,
        success: false,
      });
      return;
    }

    clearExecStatus();
    // eslint-disable-next-line @typescript-eslint/ban-ts-comment
    // @ts-ignore
    await sendRequest(parseEther(amount), numberOfCodes);
  };

  return (
    <Card>
      <h2 className="text-xl font-semibold mb-4">Generate Secret Codes</h2>
      <form onSubmit={handleSubmit}>
        <div className="mb-4">
          <label
            htmlFor="numOfCodes"
            className="block text-sm font-medium mb-2"
          >
            Number of Secret Codes
          </label>
          <input
            type="number"
            id="numOfCodes"
            value={numberOfCodes}
            onChange={(e) => setNumberOfCodes(e.target.value)}
            className="w-full p-2"
          />
        </div>
        <div className="mb-4">
          <label htmlFor="amount" className="block text-sm font-medium mb-2">
            Amount of tokens per Code
          </label>
          <input
            type="number"
            id="amount"
            onChange={(e) => setAmount(e.target.value)}
            className="w-full p-2"
          />
        </div>

        <button disabled={!amount || !numberOfCodes}>
          Generate Secret Codes
        </button>
        <ExecStatusDisplay execStatus={execStatus} />
      </form>
    </Card>
  );
};

export { GenerateCodes };
