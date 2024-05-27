import { useState, useEffect } from 'react';
import FrameComponent1 from "../components/FrameComponent11";
import FrameComponent from "../components/FrameComponent4";
import styles from "./Wallet2.module.css";

export default function Wallet () {
  
const fetchPriceData = async (url1, url2) => {
  try {
    const response1 = await fetch(url1);
    const data1 = await response1.json();

    const response2 = await fetch(url2);
    const data2 = await response2.json();

    setPriceData({
      binancecoin: data1.binancecoin,
      jetset: data2.jetset
    });
  } catch (error) {
    console.error('Error fetching price data:', error);
  }
};

const [priceData, setPriceData] = useState(null);

// Call the function with the appropriate API URLs
fetchPriceData(
    "https://api.coingecko.com/api/v3/simple/price?ids=binancecoin&vs_currencies=usd,eur,gbp&api=" + import.meta.env.COINGECKO_API_KEY,
    "https://api.coingecko.com/api/v3/simple/price?ids=jetset&vs_currencies=usd,eur,gbp&api=" + import.meta.env.COINGECKO_API_KEY
);

//const valBNB = priceData.binancecoin.usd * 0.886451;
//const valJTS = priceData.jetset.usd * 5378919.949955;
//<div className={styles.cardToken}><img src="./BNB.svg" width="16px" height="16px" alt="BNB icon" /> BNB 0.886451 BNB ${priceData ? ( priceData.binancecoin.usd ) : ( <p>Loading...</p> )} ${valBNB.toFixed(2)}</div>
 //       <div className={styles.cardToken}><img src="./JTS64.png" width="16px" height="16px" alt="Jetset icon" /> Jetset 5,378,919.949955 JTS  ${priceData ? ( priceData.jetset.usd ) : ( <p>Loading...</p> )} ${valJTS.toFixed(2)}</div>

  return (
    <div className={styles.wallet}>
      <main className={styles.homeScreen}>
        <section className={styles.frameParent}>
        <FrameComponent1 />
        <div className={styles.cardToken}>BNB 0.886451 BNB $ {priceData ? ( priceData.binancecoin.usd ) : ( <p>Loading...</p> )}</div>
        <div className={styles.cardToken}>Jetset 5,378,919.949955 JTS  ${priceData ? ( priceData.jetset.usd ) : ( <p>Loading...</p> )}</div>
        <FrameComponent />
          <div className={styles.tokens}>
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
                        <img className={styles.graficIcon} loading="lazy" alt="" src="/grafic.svg" />
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
                  <img className={styles.lineIcon} loading="lazy" alt="" src="/line.svg" />
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
                    <img className={styles.lineIcon1} loading="lazy" alt="" src="/line.svg" />
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
                  <img className={styles.graficIcon1} loading="lazy" alt="" src="/grafic.svg" />
                </div>
              </div>
            </div>
           <img className={styles.barNavigationIcon} alt="" src="/bar-navigation.svg" />
          </div>
        </section>
        <img className={styles.jBlue1} alt="" src="/j-blue-1@2x.png" />
        <section className={styles.backgroundContainer}>
          <div className={styles.wrapperVector}>
            <img className={styles.vectorIcon} alt="" src="/vector-2.svg" />
          </div>
          <img className={styles.jBlue2} loading="lazy" alt="" src="/wallet-text-j-1@2x.png" />
        </section>
      </main>
    </div>
  );
};

