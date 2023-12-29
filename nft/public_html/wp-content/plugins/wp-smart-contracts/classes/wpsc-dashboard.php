<?php

if( ! defined( 'ABSPATH' ) ) die;

add_action('admin_menu', function() {
    add_menu_page(__('WPSmartContracts Dashboard'), __('Smart Contracts Dashboard', "wp-smart-contracts"), 'publish_posts', 'wpsc_dashboard', 'wpsc_dashboard', plugin_dir_url( dirname( __FILE__ ) ) . 'assets/img/icon-wpsc.png', 2); 
    add_submenu_page(
        'wpsc_dashboard',
        __( 'Affiliate Program Banners', 'wp-smart-contracts' ),
        __( 'Affiliate Program Banners', 'wp-smart-contracts' ),
        'publish_posts',
        'wpsc-dashboard-affp',
        'wpsc_dashboard_affp'
    );
    add_submenu_page(
        'wpsc_dashboard',
        __( 'Batch Mint NFTs', 'wp-smart-contracts' ),
        __( 'Batch Mint NFTs', 'wp-smart-contracts' ),
        'publish_posts',
        'nft-batch-mint',
        'wpsc_nft_batch_mint'
    );
});

function wpsc_dashboard() {
    
    if (isset($_POST["wpsc-go-wallet"])) {

        $affp_wallet_1 = (isset($_POST["affp_wallet_1"]))?sanitize_text_field($_POST["affp_wallet_1"]):"";
        update_option("wpsc_affp_wallet_1", $affp_wallet_1);

        $affp_wallet_42161 = (isset($_POST["affp_wallet_42161"]))?sanitize_text_field($_POST["affp_wallet_42161"]):"";
        update_option("wpsc_affp_wallet_42161", $affp_wallet_42161);
        
        $affp_wallet_56 = (isset($_POST["affp_wallet_56"]))?sanitize_text_field($_POST["affp_wallet_56"]):"";
        update_option("wpsc_affp_wallet_56", $affp_wallet_56);
    
        $affp_wallet_137 = (isset($_POST["affp_wallet_137"]))?sanitize_text_field($_POST["affp_wallet_137"]):"";
        update_option("wpsc_affp_wallet_137", $affp_wallet_137);
    
        $affp_wallet_43114 = (isset($_POST["affp_wallet_43114"]))?sanitize_text_field($_POST["affp_wallet_43114"]):"";
        update_option("wpsc_affp_wallet_43114", $affp_wallet_43114);

        $affp_wallet_250 = (isset($_POST["affp_wallet_250"]))?sanitize_text_field($_POST["affp_wallet_250"]):"";
        update_option("wpsc_affp_wallet_250", $affp_wallet_250);

    }

    $m = new Mustache_Engine;
    $atts["logo"] = plugin_dir_url( dirname( __FILE__ ) ) . 'assets/img/wpsmartcontracts.png';
    $atts["40"] = plugin_dir_url( dirname( __FILE__ ) ) . 'assets/img/40.png';
    $atts["wizard01"] = plugin_dir_url( dirname( __FILE__ ) ) . 'assets/img/wizard01.png';
    $atts["wizard02"] = plugin_dir_url( dirname( __FILE__ ) ) . 'assets/img/wizard02.png';
    $atts["wizard01_link"] = WPSC_assets::getPage('wpsc_is_launcher');
    $atts["wizard02_link"] = admin_url('admin.php?page=nft-batch-mint');
    $atts["wizard03_link"] = admin_url('admin.php?page=wpsc-admin-setup');
    $atts["affp_link"] = admin_url('admin.php?page=wpsc-dashboard-affp');
    $atts["settings-page"] = admin_url('admin.php?page=wpsc_dashboard');
    $atts["link-launcher"] = WPSC_assets::getPage('wpsc_is_launcher');

    $atts["affp_wallet_1"] = get_option("wpsc_affp_wallet_1");
    $atts["affp_wallet_42161"] = get_option("wpsc_affp_wallet_42161");
    $atts["affp_wallet_56"] = get_option("wpsc_affp_wallet_56");
    $atts["affp_wallet_137"] = get_option("wpsc_affp_wallet_137");
    $atts["affp_wallet_43114"] = get_option("wpsc_affp_wallet_43114");
    $atts["affp_wallet_250"] = get_option("wpsc_affp_wallet_250");

    if (isset($_GET["welcome"])) {
        $atts["welcome"] = __( 'Setup done! Welcome to WP Smart Contracts', 'wp-smart-contracts' );
    }

    $atts["rest_url"] = get_rest_url(null, 'wpsc/v1/');
    
    $atts["banners-path"] = plugin_dir_url( dirname(__FILE__) ) . 'assets/banners/';
    switch(rand(1,3)) {
        case 1:
            $atts["html-show"] = '<a href="' . $atts["link-launcher"] . '" target="_blank"><img src="'.$atts["banners-path"].'set-01/medium-300x250.png" class="wps-shadow" style="max-width:100%"></a>';
            $atts["html"] = '<a href="' . $atts["link-launcher"] . '" target="_blank"><img src="'.$atts["banners-path"].'set-01/medium-300x250.png" style="max-width:100%"></a>';
            break;
        case 2:
            $atts["html-show"] = '<a href="' . $atts["link-launcher"] . '" target="_blank"><img src="'.$atts["banners-path"].'set-02/medium-300x250.png" class="wps-shadow" style="max-width:100%"></a>';
            $atts["html"] = '<a href="' . $atts["link-launcher"] . '" target="_blank"><img src="'.$atts["banners-path"].'set-02/medium-300x250.png" style="max-width:100%"></a>';
            break;
        default:
            $atts["html-show"] = '<a href="' . $atts["link-launcher"] . '" target="_blank"><img src="'.$atts["banners-path"].'set-03/medium-300x250.png" class="wps-shadow" style="max-width:100%"></a>';
            $atts["html"] = '<a href="' . $atts["link-launcher"] . '" target="_blank"><img src="'.$atts["banners-path"].'set-03/medium-300x250.png" style="max-width:100%"></a>';
            break;
    }

    if (!$atts["link-launcher"]) {
        $atts["warning-launcher"] = true;
    }

    $options = get_option( 'etherscan_api_key_option' );
    if ($atts["link-launcher"] && !isset($options["wpsc_activate_launcher"])) {
        $atts["warning-remove-pages"] = true;
    }

    if (
        $atts["link-launcher"] && isset($options["wpsc_activate_launcher"]) &&
        (
            !$atts["affp_wallet_1"] or
            !$atts["affp_wallet_42161"] or
            !$atts["affp_wallet_56"] or
            !$atts["affp_wallet_137"] or
            !$atts["affp_wallet_43114"] or
            !$atts["affp_wallet_250"]
        )
    ) {
        $atts["no-wallets"] = true;
    }

    $atts["wizards"] = __('Wizards', 'wp-smart-contracts');
    $atts["launcher-is-not-activated"] = __('The Launcher is not activated', 'wp-smart-contracts');
    $atts["to-get-the-most"] = __('To get the most of the WP Smart Contracts 2.0 plugin please take a couple of minutes to run the setup wizard and activate the Launcher', 'wp-smart-contracts');
    $atts["run-the-setup-wizard"] = __('Run the Setup Wizard', 'wp-smart-contracts');
    $atts["launcher-deactivated-but-pages-active"] = __('The Launcher has been deactivated, but the Launcher and Wizard pages may still be active.', 'wp-smart-contracts');
    $atts["keep-in-mind-if-previously-activated"] = __('Please keep in mind that if the Launcher was previously activated, the Launcher and Wizard pages might still be available. Please go to the pages section and remove them accordingly.', 'wp-smart-contracts');
    $atts["smart-contracts-wizard"] = __('Smart Contracts Wizard', 'wp-smart-contracts');
    $atts["create-smart-contracts-in-four-steps"] = __('Create your Smart Contracts in four easy steps', 'wp-smart-contracts');
    $atts["choose-contract-and-deploy"] = __('Choose the contract that best suits your needs and deploy it in one of the multiple Blockchain networks available.', 'wp-smart-contracts');
    $atts["create-a-smart-contract"] = __('Create a Smart Contract', 'wp-smart-contracts');
    $atts["batch-mint-nft-wizard"] = __('Batch Mint NFT Wizard', 'wp-smart-contracts');
    $atts["bulk-minting-in-four-steps"] = __('Bulk Minting in four easy steps', 'wp-smart-contracts');
    $atts["create-multiple-nfts-in-one-process"] = __('You can create multiple NFTs in one single and intuitive process. Available only for Yuzu and Ikasumi flavor.', 'wp-smart-contracts');
    $atts["bulk-mint-nft-items"] = __('Bulk Mint NFT Items', 'wp-smart-contracts');
    $atts["setup-wizard"] = __('Setup Wizard', 'wp-smart-contracts');
    $atts["set-up-website-ui-affiliate-program"] = __('Set up your website, UI, and affiliate program', 'wp-smart-contracts');
    $atts["run-the-wizard-again"] = __('Run the wizard again to guide you through the setup process', 'wp-smart-contracts');
    $atts["setup-wizard"] = __('Setup Wizard', 'wp-smart-contracts');
    $atts["affiliate-program"] = __('Affiliate Program', 'wp-smart-contracts');
    $atts["generate-cryptocurrency-income"] = __('Partner through the WPSmartContracts Affiliate Program to generate cryptocurrency income by helping developers and entrepreneurs grow their business on Blockchain', 'wp-smart-contracts');
    $atts["affiliate-program-not-activated"] = __('The Affiliate Program is not activated', 'wp-smart-contracts');
    $atts["activate-program-and-setup"] = __('If you want to have the option to generate income with the WP Smart Contracts 2.0 plugin, please take a couple of minutes to run the setup wizard and activate the Launcher/Affiliate Program.', 'wp-smart-contracts');
    $atts["run-the-setup-wizard"] = __('Run the Setup Wizard', 'wp-smart-contracts');
    $atts["affiliate-program-not-set-up"] = __('The Affiliate Program is not properly set up', 'wp-smart-contracts');
    $atts["activate-program-set-up-wallet"] = __('You have activated the Affiliate Program, but you haven\'t set up any wallet. You cannot receive payments if you don\'t set up a wallet for all the networks. Please paste your wallet address in the wallet fields below, for all networks, and click save.', 'wp-smart-contracts');
    $atts["run-the-setup-wizard"] = __('Run the Setup Wizard', 'wp-smart-contracts');
    $atts["wallets-payments"] = __('Wallets & Payments', 'wp-smart-contracts');
    $atts["enter-wallet-receive-commissions"] = __('Enter the wallet to receive commissions in each network. Track the total accumulated payments for each network', 'wp-smart-contracts');
    $atts["network"] = __('Network', 'wp-smart-contracts');
    $atts["wallet"] = __('Wallet', 'wp-smart-contracts');
    $atts["payments"] = __('Payments', 'wp-smart-contracts');
    $atts["ethereum"] = __('Ethereum', 'wp-smart-contracts');
    $atts["arbitrum"] = __('Arbitrum', 'wp-smart-contracts');
    $atts["binance-smart-chain"] = __('Binance Smart Chain', 'wp-smart-contracts');
    $atts["polygon-matic"] = __('Polygon (Matic)', 'wp-smart-contracts');
    $atts["avalanche"] = __('Avalanche', 'wp-smart-contracts');
    $atts["fantom"] = __('Fantom', 'wp-smart-contracts');
    $atts["save"] = __('Save', 'wp-smart-contracts');
    $atts["promote-your-site"] = __('Promote your site', 'wp-smart-contracts');
    $atts["get-promotional-materials"] = __('Get a set of promotional materials to enhance your sales, including banners, tweets, and email messages', 'wp-smart-contracts');
    $atts["use-link-direct-affiliates"] = __('Use the link below to direct your affiliates:', 'wp-smart-contracts');
    $atts["use-this-banner"] = __('Use this banner', 'wp-smart-contracts');
    $atts["see-more"] = __('See more', 'wp-smart-contracts');
    $atts["dashboard"] = __('Dashboard', 'wp-smart-contracts');

    echo $m->render(WPSC_Mustache::getTemplate('wpsc-dashboard-home'), $atts);

}

function wpsc_dashboard_affp() {

    $m = new Mustache_Engine;
    $atts["logo"] = plugin_dir_url( dirname( __FILE__ ) ) . 'assets/img/wpsmartcontracts.png';
    $atts["40"] = plugin_dir_url( dirname( __FILE__ ) ) . 'assets/img/40.png';
    $atts["settings-page"] = admin_url('admin.php?page=wpsc-dashboard-affp');
    $atts["link-launcher"] = WPSC_assets::getPage('wpsc_is_launcher');

    $atts["affp_wallet_1"] = get_option("wpsc_affp_wallet_1");
    $atts["affp_wallet_42161"] = get_option("wpsc_affp_wallet_42161");
    $atts["affp_wallet_56"] = get_option("wpsc_affp_wallet_56");
    $atts["affp_wallet_137"] = get_option("wpsc_affp_wallet_137");
    $atts["affp_wallet_43114"] = get_option("wpsc_affp_wallet_43114");
    $atts["affp_wallet_250"] = get_option("wpsc_affp_wallet_250");

    $logo_data = WPSC_helpers::getLogoAffP();
    $atts["wps_logo_id"] = $logo_data["id"];
    $atts["the-logo"] = $logo_data["logo"];
    
    $atts["banners-path"] = plugin_dir_url( dirname(__FILE__) ) . 'assets/banners/';

    $atts["set1"] = $atts["banners-path"].'set-01/medium-300x250.png';

    $atts["html1-show"] = '<a href="' . $atts["link-launcher"] . '" target="_blank"><img src="'.$atts["banners-path"].'set-01/large-leaderboard-970x90.png" class="wps-shadow" style="max-width:100%"></a>';
    $atts["html1"] = '<a href="' . $atts["link-launcher"] . '" target="_blank"><img src="'.$atts["banners-path"].'set-01/large-leaderboard-970x90.png" style="max-width:100%"></a>';
    
    $atts["html2-show"] = '<a href="' . $atts["link-launcher"] . '" target="_blank"><img src="'.$atts["banners-path"].'set-01/leaderboard-728x90.png" class="wps-shadow" style="max-width:100%"></a>';
    $atts["html2"] = '<a href="' . $atts["link-launcher"] . '" target="_blank"><img src="'.$atts["banners-path"].'set-01/leaderboard-728x90.png" style="max-width:100%"></a>';
    
    $atts["html3-show"] = '<a href="' . $atts["link-launcher"] . '" target="_blank"><img src="'.$atts["banners-path"].'set-01/half-page-300x600.png" class="wps-shadow" style="max-width:100%"></a>';
    $atts["html3"] = '<a href="' . $atts["link-launcher"] . '" target="_blank"><img src="'.$atts["banners-path"].'set-01/half-page-300x600.png" style="max-width:100%"></a>';
    
    $atts["html4-show"] = '<a href="' . $atts["link-launcher"] . '" target="_blank"><img src="'.$atts["banners-path"].'set-01/wide-sky-scraper-160x600.png" class="wps-shadow" style="max-width:100%"></a>';
    $atts["html4"] = '<a href="' . $atts["link-launcher"] . '" target="_blank"><img src="'.$atts["banners-path"].'set-01/wide-sky-scraper-160x600.png" style="max-width:100%"></a>';
    
    $atts["html5-show"] = '<a href="' . $atts["link-launcher"] . '" target="_blank"><img src="'.$atts["banners-path"].'set-01/medium-300x250.png" class="wps-shadow" style="max-width:100%"></a>';
    $atts["html5"] = '<a href="' . $atts["link-launcher"] . '" target="_blank"><img src="'.$atts["banners-path"].'set-01/medium-300x250.png" style="max-width:100%"></a>';
    
    $atts["set2"] = $atts["banners-path"].'set-02/medium-300x250.png';

    $atts["html1b-show"] = '<a href="' . $atts["link-launcher"] . '" target="_blank"><img src="'.$atts["banners-path"].'set-02/large-leaderboard-970x90.png" class="wps-shadow" style="max-width:100%"></a>';
    $atts["html1b"] = '<a href="' . $atts["link-launcher"] . '" target="_blank"><img src="'.$atts["banners-path"].'set-02/large-leaderboard-970x90.png" style="max-width:100%"></a>';

    $atts["html2b-show"] = '<a href="' . $atts["link-launcher"] . '" target="_blank"><img src="'.$atts["banners-path"].'set-02/leaderboard-728x90.png" class="wps-shadow" style="max-width:100%"></a>';
    $atts["html2b"] = '<a href="' . $atts["link-launcher"] . '" target="_blank"><img src="'.$atts["banners-path"].'set-02/leaderboard-728x90.png" style="max-width:100%"></a>';

    $atts["html3b-show"] = '<a href="' . $atts["link-launcher"] . '" target="_blank"><img src="'.$atts["banners-path"].'set-02/half-page-300x600.png" class="wps-shadow" style="max-width:100%"></a>';
    $atts["html3b"] = '<a href="' . $atts["link-launcher"] . '" target="_blank"><img src="'.$atts["banners-path"].'set-02/half-page-300x600.png" style="max-width:100%"></a>';

    $atts["html4b-show"] = '<a href="' . $atts["link-launcher"] . '" target="_blank"><img src="'.$atts["banners-path"].'set-02/wide-sky-scraper-160x600.png" class="wps-shadow" style="max-width:100%"></a>';
    $atts["html4b"] = '<a href="' . $atts["link-launcher"] . '" target="_blank"><img src="'.$atts["banners-path"].'set-02/wide-sky-scraper-160x600.png" style="max-width:100%"></a>';

    $atts["html5b-show"] = '<a href="' . $atts["link-launcher"] . '" target="_blank"><img src="'.$atts["banners-path"].'set-02/medium-300x250.png" class="wps-shadow" style="max-width:100%"></a>';
    $atts["html5b"] = '<a href="' . $atts["link-launcher"] . '" target="_blank"><img src="'.$atts["banners-path"].'set-02/medium-300x250.png" style="max-width:100%"></a>';

    $atts["set3"] = $atts["banners-path"].'set-03/medium-300x250.png';

    $atts["html1c-show"] = '<a href="' . $atts["link-launcher"] . '" target="_blank"><img src="'.$atts["banners-path"].'set-03/large-leaderboard-970x90.png" class="wps-shadow" style="max-width:100%"></a>';
    $atts["html1c"] = '<a href="' . $atts["link-launcher"] . '" target="_blank"><img src="'.$atts["banners-path"].'set-03/large-leaderboard-970x90.png" style="max-width:100%"></a>';

    $atts["html2c-show"] = '<a href="' . $atts["link-launcher"] . '" target="_blank"><img src="'.$atts["banners-path"].'set-03/leaderboard-728x90.png" class="wps-shadow" style="max-width:100%"></a>';
    $atts["html2c"] = '<a href="' . $atts["link-launcher"] . '" target="_blank"><img src="'.$atts["banners-path"].'set-03/leaderboard-728x90.png" style="max-width:100%"></a>';

    $atts["html3c-show"] = '<a href="' . $atts["link-launcher"] . '" target="_blank"><img src="'.$atts["banners-path"].'set-03/half-page-300x600.png" class="wps-shadow" style="max-width:100%"></a>';
    $atts["html3c"] = '<a href="' . $atts["link-launcher"] . '" target="_blank"><img src="'.$atts["banners-path"].'set-03/half-page-300x600.png" style="max-width:100%"></a>';

    $atts["html4c-show"] = '<a href="' . $atts["link-launcher"] . '" target="_blank"><img src="'.$atts["banners-path"].'set-03/wide-sky-scraper-160x600.png" class="wps-shadow" style="max-width:100%"></a>';
    $atts["html4c"] = '<a href="' . $atts["link-launcher"] . '" target="_blank"><img src="'.$atts["banners-path"].'set-03/wide-sky-scraper-160x600.png" style="max-width:100%"></a>';

    $atts["html5c-show"] = '<a href="' . $atts["link-launcher"] . '" target="_blank"><img src="'.$atts["banners-path"].'set-03/medium-300x250.png" class="wps-shadow" style="max-width:100%"></a>';
    $atts["html5c"] = '<a href="' . $atts["link-launcher"] . '" target="_blank"><img src="'.$atts["banners-path"].'set-03/medium-300x250.png" style="max-width:100%"></a>';

    $atts["logo-ethereum"] = plugin_dir_url( dirname( __FILE__ ) ) . "launcher/img/ethereum-network.png";
    $atts["logo-arbitrum"] = plugin_dir_url( dirname( __FILE__ ) ) . "launcher/img/arbitrum-network.png";
    $atts["logo-bsc"] = plugin_dir_url( dirname( __FILE__ ) ) . "launcher/img/bsc-network.png";
    $atts["logo-matic"] = plugin_dir_url( dirname( __FILE__ ) ) . "launcher/img/matic-network.png";
    $atts["logo-avax"] = plugin_dir_url( dirname( __FILE__ ) ) . "launcher/img/avax-network.png";
    $atts["logo-fantom"] = plugin_dir_url( dirname( __FILE__ ) ) . "launcher/img/fantom-network.png";

    $atts["dashboard_link"] = admin_url('admin.php?page=wpsc_dashboard');

    echo $m->render(WPSC_Mustache::getTemplate('wpsc-dashboard-affp'), $atts);

}
