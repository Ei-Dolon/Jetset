import React, { useEffect } from "react";
import { Routes, Route, useNavigationType, useLocation, } from "react-router-dom";
import Wallet from "./pages/Wallet2";
import TipTap from "./pages/TipTap";


function App() {
  const action = useNavigationType();
  const location = useLocation();
  const pathname = location.pathname;

  useEffect(() => {
    if (action !== "POP") {
      window.scrollTo(0, 0);
    }
  }, [action, pathname]);

  useEffect(() => {
    let title = "";
    let metaDescription = "";

    switch (pathname) {
      case "/":
        title = "Jetset Wallet";
        metaDescription = "Jetset Wallet and DApp frontend";
        break;
      case "/tiptap":
        title = "Tip Tap";
        metaDescription = "Tip Tap - Jetset DApp";
        break;
    }

    if (title) {
      document.title = title;
    }

    if (metaDescription) {
      const metaDescriptionTag = document.querySelector(
        'head > meta[name="description"]'
      ) as HTMLMetaElement;
      if (metaDescriptionTag) {
        metaDescriptionTag.content = metaDescription;
      }
    }
  }, [pathname]);


 
  return (
      <Routes>
        <Route path="/" element={<Wallet />} />
        <Route path="/tiptap" element={<TipTap />} />
      </Routes>
  );
}
export default App;
