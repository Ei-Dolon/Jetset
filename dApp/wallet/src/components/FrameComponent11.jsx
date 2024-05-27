import styles from "./FrameComponent11.module.css";

const FrameComponent1 = () => {
  return (
    <div className={styles.buttonInstanceParent}>
      <header className={styles.buttonInstance}>
        <img className={styles.buttonIcon} alt="" src="/button.svg" />
        <div className={styles.notificationsWrapper}>
          <img className={styles.notificationsIcon} loading="lazy" alt="" src="/notifications.svg" />
        </div>
      </header>
      <div className={styles.frameWrapper}>
        <div className={styles.availableBalanceParent}>
          <div className={styles.availableBalance}>Wallet Total:</div>
          <div className={styles.sendButton}>
            <div className={styles.receiveButton}>$ 11,128.34</div>
          </div>
          <div className={styles.wallet1Wrapper}>
            <div className={styles.wallet1}>
              <div className={styles.wallet}>
                <div className={styles.rectangle} />
                <div className={styles.vectorWrapper}>
                  <img className={styles.vectorIcon} alt="" src="/vector.svg" />
                </div>
                <div className={styles.metamask}>BNB Chain</div>
                <div className={styles.expandMoreWrapper}>
                  <img className={styles.expandMoreIcon} loading="lazy" alt="" src="/expand-more.svg" />
                </div>
                <img className={styles.vectorIcon1} loading="lazy" alt="" src="/vector-1.svg" />
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default FrameComponent1;
