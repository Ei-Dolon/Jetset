import React, { useState } from 'react';
import { EthersProviderWrapper } from '@web3modal/ethers';
import WalletConnectQRCodeModal from 'walletconnect-qrcode-modal';

const WalletConnect = () => {
  const [provider, setProvider] = useState(null);
  const [account, setAccount] = useState(null);

  const connectWallet = async () => {
    try {
      const walletConnectProvider = new EthersProviderWrapper({
        rpc: {
          56: import.meta.env.VITE_PROJECT_ID,
        },
      });

      await walletConnectProvider.enable();

      const web3Provider = new ethers.providers.Web3Provider(walletConnectProvider);
      const signer = web3Provider.getSigner();
      const address = await signer.getAddress();

      setProvider(walletConnectProvider);
      setAccount(address);
    } catch (error) {
      console.error('Error connecting wallet:', error);
    }
  };

  const disconnectWallet = () => {
    provider.disconnect();
    setProvider(null);
    setAccount(null);
  };

  return (
    <div>
      {!account ? (
        <button onClick={connectWallet}>Connect Wallet</button>
      ) : (
        <div>
          <p>Connected Account: {account}</p>
          <button onClick={disconnectWallet}>Disconnect</button>
        </div>
      )}
      <WalletConnectQRCodeModal open={!!provider} />
    </div>
  );
};

export default WalletConnect;
