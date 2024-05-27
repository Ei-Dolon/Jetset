import styles from "./Flooz.module.css";

const Flooz = ({ onClose }) => {
  return (
    <div className={styles.flooz}>
      <div className={styles.floozChild} />
      <iframe width="400" height="720" frameborder="0"
	        allow="clipboard-read *; clipboard-write *; web-share *; accelerometer *; autoplay *; camera *; gyroscope *; payment *; geolocation *"
	        src="https://flooz.xyz/embed/trade?swapDisabled=false&swapNetwork=bsc&swapToTokenAddress=0x405BE90996e7F995A08C2FBD8d8822EF5b03466C&swapLockToToken=false&onRampDisabled=false&onRampNetwork=bsc&onRampAsDefault=true&onRampTokenAddress=bnb&network=eth&lightMode=false&backgroundColor=transparent">
        </iframe>
    </div>
  );
};

export default Flooz;
