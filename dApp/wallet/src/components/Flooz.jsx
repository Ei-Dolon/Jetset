import styles from "./Flooz.module.css";

const Flooz = ({ onClose }) => {
  return (
    <div className={styles.flooz}>
      <div className={styles.floozChild} />
    </div>
  );
};

export default Flooz;
