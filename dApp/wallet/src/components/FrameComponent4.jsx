import { useState, useCallback } from "react";
import Flooz from "./Flooz";
import PortalPopup from "./PortalPopup";
import { useNavigate } from "react-router-dom";
import styles from "./FrameComponent4.module.css";


const FrameComponent = () => {
  const [isFloozOpen, setFloozOpen] = useState(null);
  const navigate = useNavigate();

  const onSendContainerClick = useCallback(() => {
    navigate("/tiptap");
  }, [navigate]);

  const openFlooz = useCallback(() => {
    setFloozOpen(true);
  }, []);

  const closeFlooz = useCallback(() => {
    setFloozOpen(false);
  }, []);

  return (
    <>
      <div className={styles.frameWrapper}>
        <div className={styles.sendParent}>
          <div className={styles.send} onClick={onSendContainerClick}>
            <div className={styles.sendInner}>
              <div className={styles.ellipseParent}>
                <div className={styles.frameChild} />
                <img className={styles.sendIcon} loading="lazy" alt="" src="/send.svg" />
              </div>
            </div>
            <div className={styles.tipTap}>TIP TAP</div>
          </div>
          <div className={styles.frameParent}>
            <div className={styles.frameContainer}>
              <div className={styles.ellipseGroup}>
                <div className={styles.frameItem} />
                <img className={styles.callReceivedIcon} loading="lazy" alt="" src="/call-received.svg" />
              </div>
            </div>
            <div className={styles.receive}>Receive</div>
          </div>
          <div className={styles.buy} onClick={openFlooz}>
            <div className={styles.ellipseContainer}>
              <div className={styles.frameInner} />
              <img className={styles.attachMoneyIcon} loading="lazy" alt="" src="/attach-money.svg" />
            </div>
            <div className={styles.buyWrapper}>
              <div className={styles.buy1}>Buy</div>
            </div>
          </div>
        </div>
      </div>
      {isFloozOpen && (
        <PortalPopup
          overlayColor="rgba(113, 113, 113, 0.3)"
          placement="Centered"
          onOutsideClick={closeFlooz}
        >
          <Flooz onClose={closeFlooz} />
        </PortalPopup>
      )}
    </>
  );
};

export default FrameComponent;
