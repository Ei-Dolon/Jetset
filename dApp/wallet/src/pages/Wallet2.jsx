import { ethers } from "ethers";
import { useState, useEffect } from 'react';
import FrameComponent1 from "../components/FrameComponent11";
import FrameComponent from "../components/FrameComponent4";
import styles from "./Wallet2.module.css";

const Wallet = () => {
  
  const rpcUrl = "https://bsc-dataseed.binance.org";
 // const tokenAddress = "0x405be90996e7f995a08c2fbd8d8822ef5b03466c";
 // const tokenAbi = [{"inputs":[{"internalType":"address","name":"account_","type":"address"}],"name":"balanceOf","outputs":[{"internalType":"uint256","name":"","type":"uint256"}],"stateMutability":"view","type":"function"}];
 // const tokenSymbol = "JTS";
 // const tokenDecimals = 18;
 // const tokenImage = "./JTS512.svg";

  

 

useEffect(() => {
   // connect user wallet account addresses
   const provider = new ethers.providers.JsonRpcProvider(rpcUrl);
   const walletAddress = provider.getSigner().getAddress();
   const balanceBNB = provider.getBalance(walletAddress,"latest");
   console.log(balanceBNB);

  const getWalletBalance = async (walletAddress) => {
    try {
      const balanceBNB = await provider.getBalance(walletAddress);
      return ethers.utils.formatEther(balanceBNB);
    } catch (error) {
      return "Error fetching balance";
    }
  };
  getWalletBalance();
}, []);


const [priceData, setPriceData] = useState(null);

  useEffect(() => {
    
    const fetchPriceData = async () => {
      try {
        const response = await fetch("https://api.coingecko.com/api/v3/simple/price?ids=jetset&vs_currencies=usd,eur,gbp");
        const data = await response.json();
        setPriceData(data);
      } catch (error) {
        console.error('Error fetching price data:', error);
      }
    };

    fetchPriceData();
  }, []);

  return (
    <div className={styles.wallet}>
      <main className={styles.homeScreen}>
        <section className={styles.frameParent}>
          <FrameComponent1 />
          <FrameComponent />
          <div className={styles.tokens}>
            <div>{priceData ? ( <pre>{JSON.stringify(priceData, null, 2)}</pre> ) : ( <p>Loading...</p> )}</div>
            <div className={styles.tokensChild} />
            <img className={styles.textureIcon} alt="" src="/texture@2x.png" />
             <div className={styles.cardTokenParent}>
              <div className={styles.cardToken}>
                <div className={styles.rectangle} />
                <div className={styles.cardTokenInner}>
                  <div className={styles.frameGroup}>
                    <div className={styles.frameContainer}>
                      <div className={styles.ellipseWrapper}>
                        <div className={styles.ellipse} />
                      </div>
                      <div className={styles.usdCoinParent}>
                        <div className={styles.usdCoin}>USD Coin</div>
                        <img
                          className={styles.graficIcon}
                          loading="lazy"
                          alt=""
                          src="/grafic.svg"
                        />
                      </div>
                    </div>
                    <div className={styles.in30m}>
                      <span>+22.4%</span>
                      <span className={styles.span}>{` `}</span>
                      <span className={styles.in30m1}>in 30m</span>
                    </div>
                  </div>
                </div>
                <div className={styles.walletContainerParent}>
                  <div className={styles.walletContainer}>$45,875.98</div>
                  <div className={styles.walletContainer1}>-12.77(%20)</div>
                  <img
                    className={styles.lineIcon}
                    loading="lazy"
                    alt=""
                    src="/line.svg"
                  />
                </div>
                <div className={styles.cardTokenWrapper}>
                  <div className={styles.cardToken1}>
                    <div className={styles.m}>
                      <div className={styles.rectangle1} />
                      <div className={styles.m1}>30m</div>
                    </div>
                    <div className={styles.h}>
                      <div className={styles.rectangle2} />
                      <div className={styles.h1}>1h</div>
                    </div>
                    <div className={styles.d}>
                      <div className={styles.rectangle3} />
                      <div className={styles.d1}>1d</div>
                    </div>
                  </div>
                </div>
              </div>
              <div className={styles.mMHDD}>
                <div className={styles.usdCoinWrapper}>
                  <div className={styles.usdCoin1}>USD Coin</div>
                </div>
                <div className={styles.in30mWrapper}>
                  <div className={styles.in30m2}>
                    <span>+22.4%</span>
                    <span className={styles.span1}>{` `}</span>
                    <span className={styles.in30m3}>in 30m</span>
                  </div>
                </div>
                <div className={styles.navigationBarParent}>
                  <div className={styles.navigationBar}>
                    <div className={styles.background}>$45,875.98</div>
                    <div className={styles.background1}>-12.77(%20)</div>
                    <img
                      className={styles.lineIcon1}
                      loading="lazy"
                      alt=""
                      src="/line.svg"
                    />
                  </div>
                  <div className={styles.frameWrapper}>
                    <div className={styles.mParent}>
                      <div className={styles.m2}>
                        <div className={styles.rectangle4} />
                        <div className={styles.m3}>30m</div>
                      </div>
                      <div className={styles.mhD}>
                        <div className={styles.rectangle5} />
                        <div className={styles.h2}>1h</div>
                      </div>
                      <div className={styles.mhD1}>
                        <div className={styles.rectangle6} />
                        <div className={styles.d2}>1d</div>
                      </div>
                    </div>
                  </div>
                </div>
                <div className={styles.availableBalance}>
                  <div className={styles.notificationsInstance} />
                  <div className={styles.ellipse1} />
                  <img
                    className={styles.graficIcon1}
                    loading="lazy"
                    alt=""
                    src="/grafic.svg"
                  />
                </div>
              </div>
            </div>
           <img
              className={styles.barNavigationIcon}
              alt=""
              src="/bar-navigation.svg"
            />
          </div>
        </section>
        <img className={styles.jBlue1} alt="" src="/j-blue-1@2x.png" />
	  	<img
			className={styles.tiptapTextJ1}
			alt="Jetset Logo - electrified"
			src="/wallet-text-j-1@2x.png"
         />
        <section className={styles.backgroundContainer}>
          <div className={styles.wrapperVector}>
            <img className={styles.vectorIcon} alt="" src="/vector-2.svg" />
          </div>
          <img
            className={styles.jBlue2}
            loading="lazy"
            alt=""
            src="/j-blue-2@2x.png"
          />
        </section>
      </main>
    </div>
  );
};
export default Wallet;
