import { Link } from "react-router-dom";
import styles from "./Tokens.module.css";

const Tokens = () => {
  return (
    <div className={styles.tokens}>
      <div className={styles.tokensChild} />
      <img className={styles.textureIcon} alt="" src="/texture@2x.png" />

      <div className={styles.buttonEnabledSet}>
        <div className={styles.confirmationButtonParent}>
          <div className={styles.confirmationButton}>
            <div className={styles.buttonbuttonenableyesnono}>
              <div className={styles.text}>
                <div className={styles.text1}>$5</div>
              </div>
              <img className={styles.roundIcon} alt="" src="/round.svg" />
            </div>
            <div className={styles.buttonbuttonenableyesnono1}>
              <div className={styles.text2}>
                <div className={styles.text3}>$10</div>
              </div>
              <img className={styles.roundIcon1} alt="" src="/round.svg" />
            </div>
            <div className={styles.buttonbuttonenableyesnono2}>
              <div className={styles.text4}>
                <div className={styles.text5}>$15</div>
              </div>
              <img className={styles.roundIcon2} alt="" src="/round.svg" />
            </div>
            <div className={styles.buttonbuttonenableyesnono3}>
              <div className={styles.text6}>
                <div className={styles.text7}>$20</div>
              </div>
              <img className={styles.roundIcon3} alt="" src="/round.svg" />
            </div>
            <div className={styles.buttonbuttonenableyesnono4}>
              <div className={styles.text8}>
                <div className={styles.text9}>$25</div>
              </div>
              <img className={styles.roundIcon4} alt="" src="/round.svg" />
            </div>
          </div>

          <div className={styles.parentButtonSet}>
            <div className={styles.enabledButton}>
              <div className={styles.buttonbuttonenableyesnono5}>
                <div className={styles.text10}>
                  <div className={styles.text11}>$50</div>
                </div>
                <img className={styles.roundIcon5} alt="" src="/round.svg" />
              </div>
              <div className={styles.buttonbuttonenableyesnono6}>
                <div className={styles.text12}>
                  <div className={styles.text13}>$75</div>
                </div>
                <img className={styles.roundIcon6} alt="" src="/round.svg" />
              </div>
              <div className={styles.buttonbuttonenableyesnono7}>
                <div className={styles.text14}>
                  <div className={styles.text15}>$100</div>
                </div>
                <img className={styles.roundIcon7} alt="" src="/round.svg" />
              </div>
            </div>
            <div className={styles.clickableButton}>
              <div className={styles.buttonbuttonenableyesnono8}>
                <div className={styles.text16}>
                  <div className={styles.text17}>CUSTOM</div>
                </div>
                <img className={styles.roundIcon8} alt="" src="/round1.svg" />
              </div>
            </div>
          </div>
        </div>
      </div>
      <div className={styles.clickableButtonNav}>
        <Link to="/">
          <img className={styles.barNavigationIcon} alt="" src="/bar-navigation.svg" />
        </Link>
      </div>
    </div>
  );
};

export default Tokens;
