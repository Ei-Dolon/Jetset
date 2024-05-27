import styles from "./FrameComponent5.module.css";

const FrameComponent = () => {
   return (
    <div className={styles.wallet1Wrapper}>
      <div className={styles.wallet1}>
        <div className={styles.wallet}>
          <div className={styles.rectangle} />
          <div className={styles.wrapperVectorWrapper}>
            <div className={styles.wrapperVector}>
              <img className={styles.vectorIcon} alt="" src="/vector1.svg" />
            </div>
          </div>
          <div className={styles.metamask}>Receiver</div>
          <div className={styles.barNavigationInstance}>
            <img className={styles.expandMoreIcon} loading="lazy" alt="" src="/expand-more1.svg" />
          </div>
          <img className={styles.vectorIcon1} loading="lazy" alt="" src="/vector-11.svg" />
        </div>
      </div>
    </div>
  );
};

export default FrameComponent;
