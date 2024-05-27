import { Link } from "react-router-dom";
import styles from "./FrameComponent12.module.css";

const FrameComponent1 = () => {
 
  return (
    <div className={styles.buttonInstanceParent}>
      <div className={styles.buttonInstance}>
        <Link to="/">
          <img className={styles.buttonIcon} alt="" src="/button.svg" />
        </Link>         
      </div>
      <div className={styles.frameParent}>
        <div className={styles.vectorParent}>
          <img className={styles.frameChild} alt="" src="/vector-14.svg" />
          <img className={styles.parentNotificationsIcon} alt="" src="/vector-17-2.svg" />
        </div>
        <img className={styles.notificationsIcon} loading="lazy" alt="" src="/notifications.svg" />
      </div>
    </div>
  );
};

export default FrameComponent1;
