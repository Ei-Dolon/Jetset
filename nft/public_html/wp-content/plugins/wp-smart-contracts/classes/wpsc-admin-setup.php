<?php

if( ! defined( 'ABSPATH' ) ) die;

/*
delete_option('wpsc_plugin_redirected');
die;
*/

add_action('admin_menu', 'wpsc_register_admin_setup');

/**
 * Add setup wizard function
 */
function wpsc_register_admin_setup() {
    add_submenu_page(
        'wpsc_dashboard',
        __( 'Admin Setup Wizard', 'wp-smart-contracts' ),
        __( 'Admin Setup Wizard', 'wp-smart-contracts' ),
        'publish_posts',
        'wpsc-admin-setup',
        'wpsc_admin_setup'
    );
}

function wpscAddSkinAttrs($atts) {

    $options = get_option( 'etherscan_api_key_option' );

    $wpsc_skin = WPSC_helpers::valArrElement($options, "wpsc-skin")?$options["wpsc-skin"]:'';
      
    switch ($wpsc_skin) {
      case 'light1':
        $wpsc_skin_selected = "wpsc_skin_1";
        break;
      case 'light2':
        $wpsc_skin_selected = "wpsc_skin_2";
        break;
      case 'dark':
        $wpsc_skin_selected = "wpsc_skin_3";
        break;
      case '20':
        $wpsc_skin_selected = "wpsc_skin_4";
        break;
      case '20red':
        $wpsc_skin_selected = "wpsc_skin_5";
        break;
      case '20green':
        $wpsc_skin_selected = "wpsc_skin_6";
        break;
      case '20black':
        $wpsc_skin_selected = "wpsc_skin_7";
        break;
      case '20cream':
        $wpsc_skin_selected = "wpsc_skin_11";
        break;
      case '20white':
        $wpsc_skin_selected = "wpsc_skin_12";
        break;
      case '20white2':
        $wpsc_skin_selected = "wpsc_skin_13";
        break;
      case '20white3':
        $wpsc_skin_selected = "wpsc_skin_14";
        break;
      case '20white4':
        $wpsc_skin_selected = "wpsc_skin_15";
        break;
      case '20pink':
        $wpsc_skin_selected = "wpsc_skin_9";
        break;
      case '20orange':
        $wpsc_skin_selected = "wpsc_skin_10";
        break;
      case '20purple':
        $wpsc_skin_selected = "wpsc_skin_8";
        break;
      case 'default':
        $wpsc_skin_selected = "wpsc_skin_0";
        break;
      default:
        $wpsc_skin_selected = "wpsc_skin_4";
        break;
    }

    $atts["default"] = plugins_url( "assets/img/default.png", dirname(__FILE__) );
    $atts["light1"] = plugins_url( "assets/img/light1.png", dirname(__FILE__) );
    $atts["light2"] = plugins_url( "assets/img/light2.png", dirname(__FILE__) );
    $atts["cream"] = plugins_url( "assets/img/cream.png", dirname(__FILE__) );
    $atts["dark"] = plugins_url( "assets/img/dark.png", dirname(__FILE__) );
    $atts["red"] = plugins_url( "assets/img/red.png", dirname(__FILE__) );
    $atts["purple"] = plugins_url( "assets/img/purple.png", dirname(__FILE__) );
    $atts["pink"] = plugins_url( "assets/img/pink.png", dirname(__FILE__) );
    $atts["orange"] = plugins_url( "assets/img/orange.png", dirname(__FILE__) );
    $atts["blue"] = plugins_url( "assets/img/blue.png", dirname(__FILE__) );
    $atts["green"] = plugins_url( "assets/img/green.png", dirname(__FILE__) );
    $atts["dark2"] = plugins_url( "assets/img/dark2.png", dirname(__FILE__) );
    $atts["white"] = plugins_url( "assets/img/white.png", dirname(__FILE__) );
    $atts["white2"] = plugins_url( "assets/img/white2.png", dirname(__FILE__) );
    $atts["white3"] = plugins_url( "assets/img/white3.png", dirname(__FILE__) );
    $atts["white4"] = plugins_url( "assets/img/white4.png", dirname(__FILE__) );

    $atts[$wpsc_skin_selected] = true;

    $atts["wpsc-skin"] = $wpsc_skin;

    return $atts;

}

function wpsc_setup_translations($atts) {
  $atts['welcome-to-wp-smart-contracts'] = __('Welcome to WP Smart Contracts 2.0', 'wp-smart-contracts');
  $atts['in-this-wizard-we-will-guide-you'] = __('In this wizard, we will guide you through the setup steps', 'wp-smart-contracts');
  $atts['click-here-to-see-a-demo-of-all-skins'] = __('Click here to see a demo of all skins', 'wp-smart-contracts');
  $atts['thank-you-i-will-setup-this-later-on'] = __('Thank you, I will setup this later on.', 'wp-smart-contracts');
  $atts['please-close-the-wizard'] = __('Please close the wizard', 'wp-smart-contracts');
  $atts['activate-the-launcher'] = __('Activate the Launcher', 'wp-smart-contracts');
  $atts['if-you-activate-the-launcher-you-will-have-access'] = __('If you activate the Launcher, you will have access to the following pages and options:', 'wp-smart-contracts');
  $atts['launcher-pages'] = __('Launcher Pages', 'wp-smart-contracts');
  $atts['affiliate-program'] = __('Affiliate Program', 'wp-smart-contracts');
  $atts['smart-contract-wizard'] = __('Smart Contract Wizard', 'wp-smart-contracts');
  $atts['the-wizard-and-launcher-pages-will-be-available-for-you-to-use'] = __('The Wizard and Launcher pages will be available for you to use.', 'wp-smart-contracts');
  $atts['important-notice'] = __('Important Notice', 'wp-smart-contracts');
  $atts['when-the-launcher-is-activated-several-pages-titled-wizard-and-launcher-will-be-created'] = __('When the launcher is activated, several pages titled "Wizard..." and "Launcher..." will be created to facilitate the functionality of the launcher and wizard. Please note that these pages will be recreated persistently unless you deactivate the launcher and manually remove them.', 'wp-smart-contracts');
  $atts['check-the-box-to-activate-the-launcher-pages-affiliate-program-and-smart-contracts-wizard'] = __('Check the box to activate the Launcher Pages, Affiliate Program, and Smart Contracts Wizard', 'wp-smart-contracts');
  $atts['ethereum'] = __('Ethereum', 'wp-smart-contracts');
  $atts['disable-ethereum'] = __('Disable Ethereum', 'wp-smart-contracts');
  $atts['enter-the-wallet-to-receive-commissions-in-each-network'] = __('Enter the wallet to receive commissions in each network', 'wp-smart-contracts');
  $atts['this-is-the-wallet-where-you-are-going-to-receive-your-earnings-from-the-affiliate-program'] = __('This is the wallet where you are going to receive your earnings from the affiliate program', 'wp-smart-contracts');
  $atts['click-here-if-you-want-to-remove-the-logo'] = __('Click here if you want to remove the logo', 'wp-smart-contracts');
  $atts['by-ticking-this-box-i-confirm'] = __('By ticking this box I confirm that I have read, consent and agree to the Terms of Use of WPSmartContracts Affiliate Program', 'wp-smart-contracts');
  $atts['warning'] = __('Warning', 'wp-smart-contracts');
  $atts['acceptance-of-the-terms'] = __('Acceptance of the terms is necessary to participate in the program and ensure a clear understanding of the rights and responsibilities involved. You cannot continue if you do not accept the terms. If you want to proceed with the setup wizard, you need to disable the Launcher.', 'wp-smart-contracts');
  $atts['next'] = __('Next', 'wp-smart-contracts');
  $atts['thank-you-i-will-setup-this-later-on'] = __('Thank you, I will set up this later on.', 'wp-smart-contracts');
  $atts['please-close-the-wizard'] = __('Please close the wizard', 'wp-smart-contracts');
  $atts['nft'] = __('NFT', 'wp-smart-contracts');
  $atts['enable-wallet-user-registration-in-front-end'] = __('Enable wallet user registration in the front end', 'wp-smart-contracts');
  $atts['choose-the-role'] = __('Choose the role that the newly registered users will have.', 'wp-smart-contracts');
  $atts['by-choosing-one-role'] = __('By choosing one role you are enabling this option, and therefore users with this role will be able to;', 'wp-smart-contracts');
  $atts['log-in-using-the-web3-wallet'] = __('Log in using the web3 wallet', 'wp-smart-contracts');
  $atts['will-have-the-capacity-to-upload-files'] = __('Will have the capacity to upload files to the media library', 'wp-smart-contracts');
  $atts['this-is-particularly-useful-for-NFT-Minting-in-the-frontend'] = __('This is particularly useful for NFT Minting in the frontend', 'wp-smart-contracts');
  $atts['request-the-email-to-register'] = __('Request the email to register', 'wp-smart-contracts');
  $atts['choose-whether-or-not-you-want-the-email'] = __('Choose whether or not you want the email to be required to register the user', 'wp-smart-contracts');
  $atts['add-media-upload-capabilities-to-the-selected-role'] = __('Add media upload capabilities to the selected role', 'wp-smart-contracts');
  $atts['choose-whether-or-not-you-want-users-in-the-selected-role'] = __('Choose whether or not you want users in the selected role to be able to upload files to your media library', 'wp-smart-contracts');
  $atts['leave-capabilities-unchanged'] = __('Leave Capabilities Unchanged', 'wp-smart-contracts');
  $atts['add-upload-capabilities'] = __('Add Upload Capabilities', 'wp-smart-contracts');
  $atts['remove-upload-capabilities'] = __('Remove Upload Capabilities', 'wp-smart-contracts');
  $atts['always-redirect-user-to-this-URL-after-login'] = __('Always redirect user to this URL after login', 'wp-smart-contracts');
  $atts['leave-blank-to-remove-custom-redirection'] = __('Leave blank to remove custom redirection', 'wp-smart-contracts');
  $atts['do-you-need-IPFS-Storage'] = __('Do you need IPFS Storage?', 'wp-smart-contracts');
  $atts['register-to-get-a-free-storage-for-IPFS'] = __('Register to get a free storage for IPFS', 'wp-smart-contracts');
  $atts['do-you-need-an-IPFS-NFT-Storage-API-key'] = __('Do you need an IPFS NFT Storage API key?', 'wp-smart-contracts');
  $atts['an-NFT-Storage-API-key-is-required'] = __('An NFT Storage API key is required if you are using an NFT Smart Contract and you want to store media files in the InterPlanetary File System (IPFS).', 'wp-smart-contracts');
  $atts['The-API-key-is-necessary-for-decentralized'] = __('The API key is necessary for decentralized storage of media files using IPFS. If you don\'t have these requirements, you can disregard this setting.', 'wp-smart-contracts');
  $atts['do-you-need-a-Moralis-Web3-API-Key'] = __('Do you need a Moralis Web3 API Key?', 'wp-smart-contracts');
  $atts['Register-to-get-a-Moralis-account'] = __('Register to get a Moralis account', 'wp-smart-contracts');
  $atts['You-only-need-a-Moralis-API-key-if'] = __('You only need a Moralis API key if you\'re using an ERC-1155 NFT Smart Contract.', 'wp-smart-contracts');
  $atts['The-Moralis-API-key-is-specifically-required'] = __('The Moralis API key is specifically required for managing complex functions and multiple ownership of items in ERC-1155 Smart Contracts like Yuzu or Ikazumi. If you\'re using ERC-721 Smart Contracts such as Matcha, Mochi, or Suika, or if you are not using NFTs at all, then a Moralis account is not necessary.', 'wp-smart-contracts');
  $atts['Select-a-WEB3-API-key-from-Moralis'] = __('Select a WEB3 API key from Moralis for ERC-1155 Smart Contract interactions. The Moralis API is free up to a certain request limit, after which a paid plan is needed. Please note that WP Smart Contracts does not endorse or approve this service. Conduct your own research and use it at your own discretion and risk', 'wp-smart-contracts');
  $atts['NFT-Items-per-page'] = __('NFT Items per page', 'wp-smart-contracts');
  $atts['thank-you-i-will-set-this-up-later-on'] = __('Thank you, I will set this up later on.', 'wp-smart-contracts');
  $atts['please-close-the-wizard'] = __('Please close the wizard', 'wp-smart-contracts');
  $atts['this-is-optional-and-its-needed-only-if'] = __('This is optional and its needed only if you are planning to create and manage your own cryptocurrencies', 'wp-smart-contracts');
  $atts['do-you-need-block-explorer-api-keys'] = __('Do you need Block Explorer API keys?', 'wp-smart-contracts');
  $atts['block-explorer-apis-are-only-required'] = __('Block explorer APIs are only required if you are planning to create your own cryptocurrencies and want to display them in the Block Explorer on your site. Additionally, you don\'t need to create API keys for all networks, only the ones you are planning to use.', 'wp-smart-contracts');
  $atts['register-to-get-a'] = __('Register to get a free API key', 'wp-smart-contracts');
  return $atts;
}

function wpsc_admin_setup() {
    
    $m = new Mustache_Engine;
    $step = 1;
    if (WPSC_helpers::valArrElement($_POST, 'step')) {
        $step = (int) $_POST["step"];
    }    

    if (isset($_GET["fs"]) or isset($_POST["fs"])) {
        $atts["full_screen"] = true;
        echo "<style>div#wpcontent {margin-left: 0;}#wpadminbar, #adminmenumain {display: none}</style>";
    }

    $atts = wpsc_setup_translations([]);

    $atts["step".$step] = true;

    $atts["skin"] = __("Skin", "wp-smart-contracts");
    $atts["skin-desc"] = __("Appearance of your user interface", "wp-smart-contracts");
    $atts["launcher"] = __("Launcher", "wp-smart-contracts");
    $atts["launcher-desc"] = __("Launcher & Affiliate Program", "wp-smart-contracts");
    $atts["nft"] = __("NFT Settings", "wp-smart-contracts");
    $atts["nft-desc"] = __("Configure your NFT Marketplace", "wp-smart-contracts");
    $atts["coin"] = __("Are you planning to create your own cryptocurrencies?", "wp-smart-contracts");
    $atts["coin-desc"] = __("Configure your Cryptocurrencies settings", "wp-smart-contracts");
    $atts["other"] = __("Other Settings", "wp-smart-contracts");
    $atts["other-desc"] = __("APIs and general settings", "wp-smart-contracts");
    $atts["forward-url"] = get_admin_url() . "admin.php?page=wpsc_dashboard";
    $atts["test-networks"] = __("Test Networks", "wp-smart-contracts");
    $atts["disable-testnetworks"] = __("Disable Testnetworks", "wp-smart-contracts");
    $atts["launcher-page-logo"] = __('Launcher page logo', 'wp-smart-contracts');
    $atts["use-image-below-as-logo"] = __('Use the image below as logo for your Launcher page (transparent logo recommended)', 'wp-smart-contracts');
    $atts["the-logo"] = __('the logo', 'wp-smart-contracts');
    $atts["load-logo-image"] = __('Load Logo Image', 'wp-smart-contracts');
    $atts["wps_logo_id"] = __('wps_logo_id', 'wp-smart-contracts');
    $atts["click-here-if-you-want-to-remove-the-logo"] = __('Click here if you want to remove the logo', 'wp-smart-contracts');
    $atts['next'] = __('Next', 'wp-smart-contracts');
    $atts['congratulations'] = __('Congratulations!', 'wp-smart-contracts');
    $atts['you-have-finished-setting-up'] = __('You have finished setting up WP Smart Contracts, and you will be redirected to the dashboard in a few seconds.', 'wp-smart-contracts');
    
    if ($step == 1) {

        $atts["img"] = plugins_url( "launcher/img/welcome-01.jpg", dirname(__FILE__) );
        $atts = wpscAddSkinAttrs($atts);
        $atts["n"] = "four";

        $atts["skins"] = __("Skins", "wp-smart-contracts");
        $atts["select-the-ui"] = __("Select the User Interface (Skin) you want for your Launcher and Smart Contracts", "wp-smart-contracts");
        $atts["legacyskins"] = __("Legacy Skins (deprecated / not recommended)", "wp-smart-contracts");
        $atts["if-selected"] = __("These skins are deprecated, meaning they are no longer maintained. As a result, new features like the Launcher and new smart contracts are not supported under these skins.", "wp-smart-contracts");

        $atts["skins"] = $m->render(WPSC_Mustache::getTemplate('settings-skin'), $atts);
        echo $m->render(WPSC_Mustache::getTemplate('wpsc-setup-wizard'), $atts);

    }

    if ($step == 2) {
        
      // update skin
      $options = get_option( 'etherscan_api_key_option' );
      $option = sanitize_text_field($_POST["wpsc_skin_radio"]);
      if (!in_array($option, ['light1', 'light2', 'dark', '20', '20red', '20green', '20black', '20cream', '20pink', '20orange', '20purple', '20white', '20white2', '20white3', '20white4', 'default'])) {
          $option = "20";
      }
      $options["wpsc-skin"] = $option;
      update_option("etherscan_api_key_option", $options);

      $options = get_option( 'etherscan_api_key_option' );
      if (isset($options["wpsc_activate_launcher"]) and $options["wpsc_activate_launcher"] == "on") {
          $atts["launcher-checked"] = true;
      }

      $logo_data = WPSC_helpers::getLogoAffP();
      $atts["wps_logo_id"] = $logo_data["id"];
      $atts["the-logo"] = $logo_data["logo"];
  
      $atts["tosaffp"] = WPSC_helpers::tosaffp();

      $atts["img"] = plugins_url( "launcher/img/welcome-02.jpg", dirname(__FILE__) );
      $atts["affp_wallet_1"] = get_option("wpsc_affp_wallet_1");
      $atts["affp_wallet_42161"] = get_option("wpsc_affp_wallet_42161");
      $atts["affp_wallet_56"] = get_option("wpsc_affp_wallet_56");
      $atts["affp_wallet_137"] = get_option("wpsc_affp_wallet_137");
      $atts["affp_wallet_43114"] = get_option("wpsc_affp_wallet_43114");
      $atts["affp_wallet_250"] = get_option("wpsc_affp_wallet_250");

      $disabled_ethereum = get_option("disabled_ethereum");
      if ($disabled_ethereum) $atts["disabled_ethereum"] = true;

      $disabled_arbitrum = get_option("disabled_arbitrum");
      if ($disabled_arbitrum) $atts["disabled_arbitrum"] = true;

      $disabled_bsc = get_option("disabled_bsc");
      if ($disabled_bsc) $atts["disabled_bsc"] = true;

      $disabled_polygon = get_option("disabled_polygon");
      if ($disabled_polygon) $atts["disabled_polygon"] = true;

      $disabled_avax = get_option("disabled_avax");
      if ($disabled_avax) $atts["disabled_avax"] = true;

      $disabled_fantom = get_option("disabled_fantom");
      if ($disabled_fantom) $atts["disabled_fantom"] = true;

      $disabled_test = get_option("disabled_test");
      if ($disabled_test) $atts["disabled_test"] = true;

      echo $m->render(WPSC_Mustache::getTemplate('wpsc-setup-wizard'), $atts);

    }

    if ($step == 3) {

      $disabled_ethereum = isset($_POST["disabled-ethereum"])?true:false;
      update_option("disabled_ethereum", $disabled_ethereum);

      $disabled_arbitrum = isset($_POST["disabled-arbitrum"])?true:false;
      update_option("disabled_arbitrum", $disabled_arbitrum);

      $disabled_bsc = isset($_POST["disabled-bsc"])?true:false;
      update_option("disabled_bsc", $disabled_bsc);

      $disabled_polygon = isset($_POST["disabled-polygon"])?true:false;
      update_option("disabled_polygon", $disabled_polygon);

      $disabled_avax = isset($_POST["disabled-avax"])?true:false;
      update_option("disabled_avax", $disabled_avax);

      $disabled_fantom = isset($_POST["disabled-fantom"])?true:false;
      update_option("disabled_fantom", $disabled_fantom);

      $disabled_test = isset($_POST["disabled-test"])?true:false;
      update_option("disabled_test", $disabled_test);

      $options = get_option( 'etherscan_api_key_option' );

      if (isset($_POST["wpsc_activate_launcher"])) {
          $options["wpsc_activate_launcher"] = "on";
      } else {
          unset($options["wpsc_activate_launcher"]);
      }

      $wps_logo_id = (isset($_POST["wps-logo-id"]))?sanitize_text_field($_POST["wps-logo-id"]):"";
      if ($wps_logo_id) {
          update_option("wps_logo_id", $wps_logo_id);
      }
      if (isset($_POST["wpsc-remove-logo"])) {
          update_option("wps_logo_id", 0);
      }

      update_option("etherscan_api_key_option", $options);

      $affp_wallet_1 = sanitize_text_field($_POST["affp_wallet_1"]);
      $affp_wallet_42161 = sanitize_text_field($_POST["affp_wallet_42161"]);
      $affp_wallet_56 = sanitize_text_field($_POST["affp_wallet_56"]);
      $affp_wallet_137 = sanitize_text_field($_POST["affp_wallet_137"]);
      $affp_wallet_43114 = sanitize_text_field($_POST["affp_wallet_43114"]);
      $affp_wallet_250 = sanitize_text_field($_POST["affp_wallet_250"]);

      update_option("wpsc_affp_wallet_1", $affp_wallet_1);
      update_option("wpsc_affp_wallet_42161", $affp_wallet_42161);
      update_option("wpsc_affp_wallet_56", $affp_wallet_56);
      update_option("wpsc_affp_wallet_137", $affp_wallet_137);
      update_option("wpsc_affp_wallet_43114", $affp_wallet_43114);
      update_option("wpsc_affp_wallet_250", $affp_wallet_250);    

      $atts["img"] = plugins_url( "launcher/img/welcome-03.jpg", dirname(__FILE__) );
      if (isset($options["login_redirection"])) $atts["login_redirection"] = $options["login_redirection"];
      if (isset($options["nft_storage_key"])) $atts["nft_storage_key"] = $options["nft_storage_key"];
      if (isset($options["nft_moralis_key"])) $atts["nft_moralis_key"] = $options["nft_moralis_key"];
      if (isset($options["nft_items_per_page"])) $atts["nft_items_per_page"] = $options["nft_items_per_page"];
      if (!isset($atts["nft_items_per_page"])) $atts["nft_items_per_page"] = 12;

      $options = get_option('etherscan_api_key_option');
      $wpsc_role = (WPSC_helpers::valArrElement($options, "wpsc_role") and !empty($options["wpsc_role"]))?$options["wpsc_role"]:false;

      if (!$wpsc_role or $wpsc_role=="deactivated") {
        $roles[] = ["role"=>"deactivated", "name"=>__("Deactivate Web3 User Registration and Login", "wp-smart-contracts"), "checked"=>true];
      } else {
        $roles[] = ["role"=>"deactivated", "name"=>__("Deactivate Web3 User Registration and Login", "wp-smart-contracts")];
      }

      foreach(get_editable_roles() as $role => $data) {
        $pre = "<span style=\"color: #ccc\">";
        $pos = " (" . __("Not recommended", "wp-smart-contracts") . ")</span>";
        if ($role == "subscriber") {
          $pre = "";
          $pos = " <span style=\"color: #ccc\">(" . __("This is the recommended setting", "wp-smart-contracts") . ")</span>";
        }
        if ($wpsc_role and $wpsc_role==$role) {
          $roles[] = ["role"=>$role, "name" => $pre . $data["name"] . $pos, "checked"=>true];
        } else {
          $roles[] = ["role"=>$role, "name" => $pre . $data["name"] . $pos];
        }
      }

      $atts["roles"] = $roles;

      if (WPSC_helpers::valArrElement($options, "wpsc_email_registration") and !empty($options["wpsc_email_registration"])) {
        $atts["wpsc_email_registration_checked"] = true;
      }

      if (WPSC_helpers::valArrElement($options, "wpsc_add_upload") and !empty($options["wpsc_add_upload"])) {
        $atts["wpsc_add_upload_".$options["wpsc_add_upload"]."_checked"] = true;
      } else {
        $atts["wpsc_add_upload__checked"] = true;
      }
      
      echo $m->render(WPSC_Mustache::getTemplate('wpsc-setup-wizard'), $atts);

    }

    if ($step == 4) {

        $options = get_option( 'etherscan_api_key_option' );
        $options["nft_storage_key"] = sanitize_text_field($_POST["nft_storage_key"]);
        $options["login_redirection"] = sanitize_text_field($_POST["login_redirection"]);        
        $options["nft_moralis_key"] = sanitize_text_field($_POST["nft_moralis_key"]);
        $options["wpsc_role"] = sanitize_text_field($_POST["wpsc_role"]);
        $wpsc_email_registration = WPSC_helpers::valArrElement($_POST, "wpsc_email_registration")?'yes':'';
        $options["wpsc_email_registration"] = $wpsc_email_registration;
        
        $wpsc_add_upload = WPSC_helpers::valArrElement($_POST, "wpsc_add_upload")?sanitize_text_field($_POST["wpsc_add_upload"]):'';
        $options["wpsc_add_upload"] = $wpsc_add_upload;

        $options["nft_items_per_page"] = sanitize_text_field($_POST["nft_items_per_page"]);
        update_option("etherscan_api_key_option", $options);

        $options = get_option( 'etherscan_api_key_option' );

        $atts["img"] = plugins_url( "launcher/img/welcome-04.jpg", dirname(__FILE__) );

        if (isset($options["api_key"])) $atts["api_etherscan"] = $options["api_key"];
        if (isset($options["arbiscan_api_key"])) $atts["api_arbiscan"] = $options["arbiscan_api_key"];
        if (isset($options["polygonscan_api_key"])) $atts["api_polygonscan"] = $options["polygonscan_api_key"];
        if (isset($options["bscscan_api_key"])) $atts["api_bscscan"] = $options["bscscan_api_key"];
        if (isset($options["avax_api_key"])) $atts["api_snowtrace"] = $options["avax_api_key"];
        if (isset($options["fantom_api_key"])) $atts["api_fantom"] = $options["fantom_api_key"];

        echo $m->render(WPSC_Mustache::getTemplate('wpsc-setup-wizard'), $atts);

    }

    if ($step == 5) {
        $options = get_option( 'etherscan_api_key_option' );
        $options["api_key"] = sanitize_text_field($_POST["api_etherscan"]);
        $options["arbiscan_api_key"] = sanitize_text_field($_POST["api_arbiscan"]);
        $options["polygonscan_api_key"] = sanitize_text_field($_POST["api_polygonscan"]);
        $options["bscscan_api_key"] = sanitize_text_field($_POST["api_bscscan"]);
        $options["avax_api_key"] = sanitize_text_field($_POST["api_snowtrace"]);
        $options["fantom_api_key"] = sanitize_text_field($_POST["api_fantom"]);
        update_option("etherscan_api_key_option", $options);
        echo $m->render(WPSC_Mustache::getTemplate('wpsc-setup-wizard'), $atts);
    }

}

/**
 * Call the setup on plugin activation
 */
function wpsc_can_redirect_on_activation() {
	if ( is_network_admin() ) {
		return false;
	}
	if ( filter_input( INPUT_GET, 'activate-multi', FILTER_VALIDATE_BOOLEAN ) ) {
		return false;
	}
	return true;
}

add_action( 'admin_init', function() {
	if ( wpsc_can_redirect_on_activation() && is_admin() && !get_option('wpsc_plugin_redirected')) {
        update_option('wpsc_plugin_redirected', true);
        $redirect_url = admin_url('admin.php') . "?page=wpsc-admin-setup&fs=1";
        wp_safe_redirect($redirect_url);
        exit;
    }
});
