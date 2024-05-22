import FrameComponent2 from "../components/FrameComponent21";
import FrameComponent1 from "../components/FrameComponent11";
import FrameComponent from "../components/FrameComponent4";
import Tokens from "../components/Tokens";
import styles from "./TipTap.module.css";

const TipTap = () => {
  return (
    <div className={styles.tipTap}>
      <main className={styles.homeScreen}>
        <FrameComponent2 />
        <section className={styles.frameParent}>
          <FrameComponent1 />
          <FrameComponent />
        </section>
        <section className={styles.tokensWrapper}>
          <Tokens />
        </section>
        <img className={styles.jBlue1} alt="" src="/j-blue-1@2x.png" />
        <section className={styles.tiptapText1Parent}>
          <img
            className={styles.tiptapText1}
            alt=""
            src="/tiptap-text-1@2x.png"
          />
        </section>
      </main>
    </div>
  );
};

export default TipTap;
