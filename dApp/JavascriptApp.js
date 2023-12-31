<script src="/node_modules/@metamask/sdk/dist/browser/iife/metamask-sdk.js"></script>
<script>
  const sdk = new MetaMaskSDK.MetaMaskSDK({
    dappMetadata: {
      name: "Pure JS example",
      url: window.location.host,
    },
    logging: {
      sdk: false,
    }
  });
</script>
<script>
  function connect() {
    ethereum
      .request({
        method: 'eth_requestAccounts',
        params: [],
      })
      .then((res) => console.log('request accounts', res))
      .catch((e) => console.log('request accounts ERR', e));
  }

  function addEthereumChain() {
    ethereum
      .request({
        method: 'wallet_addEthereumChain',
        params: [
          {
            chainId: '0x38',
            chainName: 'Binance Smart Chain',
            blockExplorerUrls: ['https://bscscan.com'],
            nativeCurrency: { symbol: 'BNB', decimals: 18 },
            rpcUrls: ['https://bsc-dataseed.binance.org/'],
          },
        ],
      })
      .then((res) => console.log('add', res))
      .catch((e) => console.log('ADD ERR', e));
  }
</script>
<button onclick="connect()">Connect</button>

<button onclick="addEthereumChain()">ADD ETHEREUM CHAIN</button>
