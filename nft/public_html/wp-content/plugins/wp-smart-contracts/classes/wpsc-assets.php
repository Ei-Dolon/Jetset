<?php

if( ! defined( 'ABSPATH' ) ) die;

/**
 * Include semantic ui JS + CSS + Functions
 */

new WPSC_assets();

function wpsc_add_type_attribute($tag, $handle, $src) {
    if ( 'model-viewer' !== $handle ) {
        return $tag;
    }
    $tag = '<script type="module" src="' . esc_url( $src ) . '"></script>';
    return $tag;
}

class WPSC_assets {

    function __construct() {

        // Load JS Web3 Library in admin
        add_action( 'admin_enqueue_scripts' , [$this, 'loadAssets'], 10, 2 );

        // Load JS Web3 Library in FE
        add_action( 'wp_enqueue_scripts' , [$this, 'loadAssetsFrontEnd'], 10, 2 );

        $wpsc_role = WPSC_helpers::getRole();
        if ($wpsc_role and $wpsc_role!="deactivated") {
            // enqueue scripts in the login page
            add_action( 'login_enqueue_scripts', [$this, 'loadAssetsLogin'], 1 );
        }
  
    }

    public static function localizeWPSC($is_a_smart_contract, $is_deployer=false) {

        $option = get_option('etherscan_api_key_option');

        if (WPSC_helpers::valArrElement($option, 'api_key')) {
            $arr["etherscan_api_key"] = $option['api_key'];
        }

        $arr['is_a_smart_contract'] = $is_a_smart_contract;
        $arr['endpoint_url'] = get_rest_url();
        $arr['nonce'] = self::get_rest_nonce();

        if ($is_deployer) {
            $arr['is_deployer'] = true;
        }

        wp_localize_script( 'wp-smart-contracts', 'localize_wpsc',  $arr);

        // add translations for JS
        wp_localize_script('wp-smart-contracts', WPSC_Mustache::createJSObjectNameFromTag('global'), [
            'MANDATORY_FIELD' => __("Mandatory Field", 'wp-smart-contracts'),
            'SELECT_SOCIAL_NET' => __("Please select a Social Network", 'wp-smart-contracts'),
            'SELECT_APPROVERS_PERCENT' => __("Please select approvers percentage ", 'wp-smart-contracts'),
            'PROFILE_LINK' => __("Please write your profile link", 'wp-smart-contracts'),
            'ERC20_RECEIVE_TOKEN'  => __("Please write the address of all tokens, or remove the row", 'wp-smart-contracts'),
            'ERC20_RECEIVE_RATE'  => __("Please write the rate for all tokens, or remove the row", 'wp-smart-contracts'),
            'ERC20_RECEIVE_RATE_INT'  => __("The rate must be a positive integer for all tokens", 'wp-smart-contracts'),
            'CONFIRM_REMOVE_SOCIAL' => __("Are you sure you want to delete this social network?", 'wp-smart-contracts'),
            'CODE_COPIED' => __("Code copied to clipboard!", 'wp-smart-contracts'),
            'WRITE_ADDRESS' => __("Please write the address of the Smart Contract you want to load", 'wp-smart-contracts'),
        ]);

    }

    static public function localizeNetworkJson($handle) {
        $arrJSON = [];
        foreach(WPSC_helpers::flavors() as $flavor) {
            $arrJSON[$flavor] = WPSC_helpers::getNetworkInfoJSON($flavor);
        }
        wp_localize_script($handle, 'wpsc_network_json', $arrJSON);
    }

    static public function getRPC($network) {
        $rpcs = self::localizeRPC(null, true);
        if (isset($rpcs[$network])) return $rpcs[$network];
        return false;
    }

    static public function localizeRPC($handle, $ret = false) {

        $rpcUrls = [
            "1" => "https://eth.llamarpc.com", // "https://endpoints.omniatech.io/v1/eth/mainnet/public",
            "5" => "https://ethereum-goerli.publicnode.com",
            "11155111" => "https://ethereum-sepolia.blockpi.network/v1/rpc/public", // "https://endpoints.omniatech.io/v1/eth/sepolia/public", // "https://ethereum-sepolia.publicnode.com",
            "97" => "https://bsc-testnet.blockpi.network/v1/rpc/public", // "https://bsc-testnet.publicnode.com",
            "56" => "https://bsc.blockpi.network/v1/rpc/public", // "https://binance.llamarpc.com", // "https://bsc.publicnode.com",
            "80001" => "https://rpc-mumbai.maticvigil.com/",
            "137" => "https://polygon.llamarpc.com",
            "4002" => "https://rpc.testnet.fantom.network/",
            "250" => "https://rpc.ftm.tools/",
            "43113" => "https://api.avax-test.network/ext/bc/C/rpc",
            "43114" => "https://api.avax.network/ext/bc/C/rpc",
            "61" => "https://www.ethercluster.com/etc",
            "42161" => "https://arb1.arbitrum.io/rpc"
        ];

        if ($ret) return $rpcUrls;
        else {

            wp_localize_script( $handle, 'wpsc_rpc', $rpcUrls );

            // get dynamic gas fees, 
            $transient_name = WPSC_Endpoints::transientPrefix . "gas_json";
            $json = false;
            if ($t = get_transient($transient_name)) {
                $json = $t;
            } else {
                $url = "https://api.wpsmartcontracts.com/gas-2.0.json";
                $response = wp_remote_get($url);
                if ( is_array( $response ) && ! is_wp_error( $response ) ) {
                    $json = $response['body'];
                    if ($json) {
                        json_decode($json);
                        if (json_last_error() === JSON_ERROR_NONE) {
                            set_transient($transient_name, $json, 300); // every 5 minutes
                        }
                    }
                }
            }
            if ($json) {
                $json = json_decode($json, true);
            }
            wp_localize_script( $handle, 'wpsc_gas_fees', $json );

        }

    }

    public function loadAssets($hook) {

        // creatting or editing a coin flag
        $is_a_smart_contract = "false";
        $is_edit = false;

        if ( ('edit.php' == $hook) or
             ('post.php' == $hook and $post_id = WPSC_Metabox::cleanUpText($_GET["post"])) or
             ('post-new.php' == $hook) or 
             ('upload.php' == $hook)
        ) {
            $is_edit = true;
        }

        wp_enqueue_script( 'wpsc-notices', dirname( plugin_dir_url( __FILE__ ) ) . '/assets/js/wpic-notice.js' );

        // check if we are editing or adding a smart contract
        if (
                (
                    'post.php' == $hook and $post_id = WPSC_Metabox::cleanUpText($_GET["post"]) and (
                        get_post_type($post_id) == "coin" or
                        get_post_type($post_id) == "crowdfunding"
                    )
                ) or
                (
                    'post-new.php' == $hook and (
                        WPSC_Metabox::cleanUpText($_GET["post_type"]) == "coin" OR 
                        WPSC_Metabox::cleanUpText($_GET["post_type"]) == "crowdfunding" OR 
                        WPSC_Metabox::cleanUpText($_GET["post_type"]) == "ico"
                    )
                )
        ) {
            $is_a_smart_contract = "true";
        }

        if (
            ('post.php' == $hook and $post_id = WPSC_Metabox::cleanUpText($_GET["post"]) and (get_post_type($post_id) == "nft-collection")) or
            ('post-new.php' == $hook and (WPSC_Metabox::cleanUpText($_GET["post_type"]) == "nft-collection"))
        ) {
            wp_enqueue_style( 'wp-color-picker' );
            $wpsc_deps = ['wp-color-picker'];
        } else {
            $wpsc_deps = [];
        }

        // enqueue profile / user galleries assets	
        if ('profile.php' == $hook or 'user-edit.php' == $hook) {	
            wp_enqueue_script('wpsc-galleries-js', dirname( plugin_dir_url( __FILE__ ) ) . '/assets/js/wpsc-galleries.js');	
            wp_enqueue_style( 'wpsc-galleries-css', dirname( plugin_dir_url( __FILE__ ) ) . '/assets/css/wpsc-galleries.css');	
        }

        // queue for all admin area        
        wp_enqueue_script(  'web3', dirname( plugin_dir_url( __FILE__ ) ) . '/assets/js/web3.js' );
        wp_enqueue_script(  'wp-smart-contracts', dirname( plugin_dir_url( __FILE__ ) ) . '/assets/js/wpsc.js', $wpsc_deps );
        wp_localize_script( 'wp-smart-contracts', 'wpsc_cpt_post_new', ["wpsc_cpt_post_new" => admin_url("post-new.php?post_type=")] );

        if ($is_edit and isset($post_id) and $version = self::getContractVersion($post_id)) {
            wp_localize_script( 'wp-smart-contracts', 'wpsc_version', ["wpsc_version" => $version] );
        }

        self::localizeRPC("wp-smart-contracts");

        self::localizeNetworkJson("wp-smart-contracts");

        self::localizeWPSC($is_a_smart_contract);

        // enqueue it only if we are creating or editing a coin
        if ($is_edit) {

            if (
                ('post.php' == $hook and $post_id = WPSC_Metabox::cleanUpText($_GET["post"]) and (get_post_type($post_id) == "nft")) or
                ('post-new.php' == $hook and (WPSC_Metabox::cleanUpText($_GET["post_type"]) == "nft")) or
                ('upload.php' == $hook)
            ) {
                wp_enqueue_media();
                wp_register_script( 'nft-js', dirname( plugin_dir_url( __FILE__ )) . '/assets/js/nft.js' );
                wp_enqueue_script( 'nft-js' );
                wp_localize_script( 'nft-js', 'model_viewer', [ "js" => dirname( plugin_dir_url( __FILE__ ) ) . '/assets/js/model-viewer.min.js' ] );
            }
            wp_enqueue_script( 'bops', dirname( plugin_dir_url( __FILE__ ) ) . '/assets/js/bops.js' );
            wp_enqueue_script( 'blockies', dirname( plugin_dir_url( __FILE__ ) ) . '/assets/js/blockies.min.js' );
            wp_enqueue_script( 'copytoclipboard', dirname( plugin_dir_url( __FILE__ ) ) . '/assets/js/copytoclipboard.js' );
            wp_localize_script( 'copytoclipboard', 'copied', ["str" => __('Copied!', 'wp-smart-contracts')] );
            wp_enqueue_script( 'wp-smart-contracts-semantic', dirname( plugin_dir_url( __FILE__ ) ) . '/assets/semantic/dist/semantic.min.js', ['jquery'] );
            wp_enqueue_script( 'zoom-qr', dirname( plugin_dir_url( __FILE__ ) ) . '/assets/js/zoom-qr.js' );
            wp_enqueue_script( 'wpsc-google-prettify', dirname( plugin_dir_url( __FILE__ ) ) . '/assets/js/prettify/run_prettify.js?autoload=true&skin=desert' );
            wp_localize_script( 'wpsc-google-prettify', 'wpsc_plugin_path', ["wpsc_plugin_path" => dirname( plugin_dir_url( __FILE__ ) )] );

            wp_localize_script( 'nft-js', 'wpscApiSettings', array(
                'root' => esc_url_raw( rest_url() ),
                'nonce' => wp_create_nonce( 'wp_rest' )
            ));

            wp_enqueue_style( 'wp-smart-contracts-semantic', dirname( plugin_dir_url( __FILE__ ) ) . '/assets/semantic/dist/semantic.min.css');
            wp_enqueue_style( 'wp-smart-contracts-styles', dirname( plugin_dir_url( __FILE__ ) ) . '/assets/css/styles.css', ['wp-smart-contracts-semantic']);

        }

        if ($hook == "nft_page_nft-batch-mint") {
            wp_enqueue_script( 'blockies', dirname( plugin_dir_url( __FILE__ ) ) . '/assets/js/blockies.min.js' );
            wp_enqueue_media();
            wp_register_script( 'wpsc-bulk-mint-js', dirname( plugin_dir_url( __FILE__ )) . '/assets/js/wpsc-bulk-mint.js' );
            wp_enqueue_script( 'wpsc-bulk-mint-js' );
            wp_localize_script( 'wpsc-bulk-mint-js', 'arr', [ 
                "model_viewer" => dirname( plugin_dir_url( __FILE__ ) ) . '/assets/js/model-viewer.min.js',
                "php" => admin_url("admin.php?page=nft-batch-mint"),
                "taxonomies" => WPSC_Queries::getTaxonomy('nft-taxonomy')
            ] );

        }

        // echo $hook;

        if ('settings_page_etherscan-api-key-setting-admin' == $hook or 
            'smart-contracts-dashboard_page_wpsc-dashboard-affp'==$hook or 
            'smart-contracts-dashboard_page_wpsc-admin-setup' == $hook or

            // localization dependent strings

            // spanish
            'panel-de-contratos-inteligentes_page_wpsc-admin-setup' == $hook or
            'panel-de-contratos-inteligentes_page_wpsc-dashboard-affp'==$hook or 

            // portuguese
            'painel-de-controle-de-contratos-inteligentes_page_wpsc-admin-setup' == $hook or
            'painel-de-controle-de-contratos-inteligentes_page_wpsc-dashboard-affp'==$hook or 

            // japanese
            '%e3%82%b9%e3%83%9e%e3%83%bc%e3%83%88%e3%82%b3%e3%83%b3%e3%83%88%e3%83%a9%e3%82%af%e3%83%88%e3%83%80%e3%83%83%e3%82%b7%e3%83%a5%e3%83%9c%e3%83%bc%e3%83%89_page_wpsc-admin-setup' == $hook or
            '%e3%82%b9%e3%83%9e%e3%83%bc%e3%83%88%e3%82%b3%e3%83%b3%e3%83%88%e3%83%a9%e3%82%af%e3%83%88%e3%83%80%e3%83%83%e3%82%b7%e3%83%a5%e3%83%9c%e3%83%bc%e3%83%89_page_wpsc-dashboard-affp' == $hook or

            // french
            'tableau-de-bord-des-contrats-intelligents_page_wpsc-admin-setup' == $hook or
            'tableau-de-bord-des-contrats-intelligents_page_wpsc-dashboard-affp' == $hook or

            // italian
            'pannello-di-controllo-contratti-intelligenti_page_wpsc-admin-setup' == $hook or
            'pannello-di-controllo-contratti-intelligenti_page_wpsc-dashboard-affp' == $hook or

            // german
            'dashboard-fuer-smart-contracts_page_wpsc-admin-setup' == $hook or
            'dashboard-fuer-smart-contracts_page_wpsc-dashboard-affp' == $hook or

            // russian
            '%d0%bf%d0%b0%d0%bd%d0%b5%d0%bb%d1%8c-%d1%83%d0%bf%d1%80%d0%b0%d0%b2%d0%bb%d0%b5%d0%bd%d0%b8%d1%8f-%d1%81%d0%bc%d0%b0%d1%80%d1%82-%d0%ba%d0%be%d0%bd%d1%82%d1%80%d0%b0%d0%ba%d1%82%d0%b0%d0%bc%d0%b8_page_wpsc-admin-setup' == $hook or
            '%d0%bf%d0%b0%d0%bd%d0%b5%d0%bb%d1%8c-%d1%83%d0%bf%d1%80%d0%b0%d0%b2%d0%bb%d0%b5%d0%bd%d0%b8%d1%8f-%d1%81%d0%bc%d0%b0%d1%80%d1%82-%d0%ba%d0%be%d0%bd%d1%82%d1%80%d0%b0%d0%ba%d1%82%d0%b0%d0%bc%d0%b8_page_wpsc-dashboard-affp' == $hook or

            // chinese
            '%e6%99%ba%e8%83%bd%e5%90%88%e7%ba%a6%e4%bb%aa%e8%a1%a8%e6%9d%bf_page_wpsc-admin-setup' == $hook or
            '%e6%99%ba%e8%83%bd%e5%90%88%e7%ba%a6%e4%bb%aa%e8%a1%a8%e6%9d%bf_page_wpsc-dashboard-affp' == $hook or

            // arabic
            '%d9%84%d9%88%d8%ad%d8%a9-%d8%aa%d8%ad%d9%83%d9%85-%d8%a7%d9%84%d8%b9%d9%82%d9%88%d8%af-%d8%a7%d9%84%d8%b0%d9%83%d9%8a%d8%a9_page_wpsc-admin-setup' == $hook or
            '%d9%84%d9%88%d8%ad%d8%a9-%d8%aa%d8%ad%d9%83%d9%85-%d8%a7%d9%84%d8%b9%d9%82%d9%88%d8%af-%d8%a7%d9%84%d8%b0%d9%83%d9%8a%d8%a9_page_wpsc-dashboard-affp' == $hook or

            'toplevel_page_wpsc_dashboard'==$hook or 
            'nft_page_nft-batch-mint'==$hook or
            'wp-smart-contracts_page_wpsc-admin-setup'==$hook or
            'profile.php' == $hook or
            'user-edit.php' == $hook) {
            wp_enqueue_media();
            wp_enqueue_script( 'wp-smart-contracts-semantic', dirname( plugin_dir_url( __FILE__ ) ) . '/assets/semantic/dist/semantic.min.js', ['jquery'] );
            wp_enqueue_style( 'wp-smart-contracts-semantic', dirname( plugin_dir_url( __FILE__ ) ) . '/assets/semantic/dist/semantic.min.css');
            wp_enqueue_style( 'wp-smart-contracts-styles', dirname( plugin_dir_url( __FILE__ ) ) . '/assets/css/styles.css', ['wp-smart-contracts-semantic']);
        }

        // enqueue in all admin pages
        wp_enqueue_style( 'wp-smart-contracts-admin-bar', dirname( plugin_dir_url( __FILE__ ) ) . '/assets/css/wp-admin-bar.css');

        // Load wp admin bar
        add_action('admin_bar_menu', [$this, 'addToolbar'], 999);

    }

    public function loadAssetsLogin() {
        wp_enqueue_script( 'blockies', dirname( plugin_dir_url( __FILE__ ) ) . '/assets/js/blockies.min.js' );
        wp_enqueue_style( 'launcher-1', 'https://fonts.googleapis.com');
        wp_enqueue_style( 'launcher-2', 'https://fonts.gstatic.com');
        wp_enqueue_style( 'launcher-3', 'https://fonts.googleapis.com/css2?family=Roboto:wght@100;400;500;700;900&display=swap');
        wp_enqueue_style( 'launcher-4', dirname( plugin_dir_url( __FILE__ ) ) . '/skins/2.0/css/main.css');
        self::loadSkin();
        self::enqueueDashboard();
    }

    // load wp admin toolbar with metamask info
    public function addToolbar($wp_admin_bar) {
        $wp_admin_bar->add_node( [
            'id'    => 'wp-smart-contracts',
            'title' => 'WPSmartContracts'
        ]);
    }

    public static function isEthereumNetwork($net) {
        return ($net==1 || $net==3 || $net==4 || $net==5 || $net==42 || $net==11155111);
    }

    static public function getCollectionID() {

        if (WPSC_helpers::valArrElement($_GET, 'id') and $id_url = (int) $_GET["id"] and get_post_type($id_url)=="nft-collection") {
            return $id_url;
        }

        $id = get_the_ID();
        if (self::isNFT()) return get_post_meta($id, 'wpsc_item_collection', true);;
        if (get_post_type($id)=="nft-collection") return $id;

        return false;
    }
    

    static public function isNFT() {
        $id = get_the_ID();
        $content = get_post_field('post_content', $id);
        if (get_post_type($id)=="nft" or has_shortcode($content, 'wpsc_nft') or has_shortcode($content, 'wpsc_nft_mint')) {
            return true;
        } else {
            return false;
        }
    }

    static public function isNFTCollection() {
        // is a NFT Collection?
        $id = get_the_ID();
        $the_content = get_post_field('post_content', $id);
        if (isset($id) and (get_post_type($id)=="nft-collection" or has_shortcode($the_content, 'wpsc_nft_collection')) or has_shortcode($the_content, 'wpsc_nft_my_items')) {
            return true;
        } else {
            return false;
        }
    }

    static public function isNFTTax() {
        if (is_tax( 'nft-gallery') or is_tax( 'nft-taxonomy') or is_tax( 'nft-tag')) {
            return true;
        } else {
            return false;
        }
    }

    private static function addRegistrationData($arr) {
        $m = new Mustache_Engine;
        $arr['ajaxurl'] = admin_url('admin-ajax.php');
        $arr['form_html'] = $m->render(WPSC_Mustache::getTemplate('register-form'), [
            "email-registration" => __("Email Registration:", 'wp-smart-contracts'),
            "register-with-your-email" => __("Register with your email to access additional features like NFT Minting and more", 'wp-smart-contracts'),
            "write-your-email-address" => __("Write your email address", 'wp-smart-contracts'),
        ]);
        $arr['rest_api_url'] = get_rest_url(null, 'wpsc/v1/');

        $options = get_option('etherscan_api_key_option');
        $login_redirection = (WPSC_helpers::valArrElement($options, "login_redirection") and !empty($options["login_redirection"]))?$options["login_redirection"]:false;
        if (filter_var($login_redirection, FILTER_VALIDATE_URL)) {
            $arr['admin_url'] = $login_redirection;
        } else {
            $arr['admin_url'] = get_admin_url();
        }
        return $arr;
    }

    private static function enqueueDashboard() {
        wp_enqueue_script( 'web3-dashboard', dirname( plugin_dir_url( __FILE__ ) ) . '/launcher/js/wpsc-dashboard.js', ["jquery"] );
        self::localizeRPC("web3-dashboard");
        self::localizeNetworkJson("web3-dashboard");
        if ($page = self::getPage("wpsc_is_scanner")) {
            $arr_dashboard['qr_scanner_page'] = $page;
        }
        $arr_dashboard = self::addRegistrationData($arr_dashboard);
        wp_localize_script( 'web3-dashboard', 'localize_var', $arr_dashboard);
        WPSC_Mustache::loadTranslationsForTag("wpsc-dashboard");
    }

    private function getShortcodeID($the_content, $shortcode) {

        $regex_pattern = get_shortcode_regex();
        preg_match ('/'.$regex_pattern.'/s', $the_content, $regex_matches);
        if ($regex_matches[2] == $shortcode) {
            $attribureStr = str_replace (" ", "&", trim ($regex_matches[3]));
            $attribureStr = str_replace ('"', '', $attribureStr);
            $defaults = array (
                'preview' => '1',
            );
            $attributes = wp_parse_args ($attribureStr, $defaults);
            if (isset ($attributes["id"])) {
                return $attributes["id"];
            }
        }

    }

    public static function loadSkin() {
        switch (WPSCSettingsPage::nftSkin()) {
            case '20red':
                wp_enqueue_style( 'wpsc-fe-nft-theme-color-css', dirname( plugin_dir_url( __FILE__ ) ) . '/skins/2.0/css/red.css');
                break;
            case '20green':
                wp_enqueue_style( 'wpsc-fe-nft-theme-color-css', dirname( plugin_dir_url( __FILE__ ) ) . '/skins/2.0/css/green.css');
                break;
            case '20purple':
                wp_enqueue_style( 'wpsc-fe-nft-theme-color-css', dirname( plugin_dir_url( __FILE__ ) ) . '/skins/2.0/css/purple.css');
                break;
            case '20black':
                wp_enqueue_style( 'wpsc-fe-nft-theme-color-css', dirname( plugin_dir_url( __FILE__ ) ) . '/skins/2.0/css/black.css');
                break;
            case '20cream':
                wp_enqueue_style( 'wpsc-fe-nft-theme-color-css', dirname( plugin_dir_url( __FILE__ ) ) . '/skins/2.0/css/cream.css');
                break;
            case '20orange':
                wp_enqueue_style( 'wpsc-fe-nft-theme-color-css', dirname( plugin_dir_url( __FILE__ ) ) . '/skins/2.0/css/orange.css');
                break;
            case '20pink':
                wp_enqueue_style( 'wpsc-fe-nft-theme-color-css', dirname( plugin_dir_url( __FILE__ ) ) . '/skins/2.0/css/pink.css');
                break;
            case '20white':
                wp_enqueue_style( 'wpsc-fe-nft-theme-color-css', dirname( plugin_dir_url( __FILE__ ) ) . '/skins/2.0/css/white.css');
                break;
            case '20white2':
                wp_enqueue_style( 'wpsc-fe-nft-theme-color-css', dirname( plugin_dir_url( __FILE__ ) ) . '/skins/2.0/css/white2.css');
                break;
            case '20white3':
                wp_enqueue_style( 'wpsc-fe-nft-theme-color-css', dirname( plugin_dir_url( __FILE__ ) ) . '/skins/2.0/css/white3.css');
                break;
            case '20white4':
                wp_enqueue_style( 'wpsc-fe-nft-theme-color-css', dirname( plugin_dir_url( __FILE__ ) ) . '/skins/2.0/css/white4.css');
                break;
            default: 
                wp_enqueue_style( 'wpsc-fe-nft-theme-color-css', dirname( plugin_dir_url( __FILE__ ) ) . '/skins/2.0/css/blue.css');
                break;
        }    
    }

    public function loadAssetsFrontEnd() {
        
        // flag for is a contract
        $is_a_token = false;
        $is_a_crowd = false;
        $is_a_ico = false;
        $is_a_scanner = false;
        $is_a_stake = false;
        $is_a_author = false;

        $id = get_the_ID();

        $the_content = get_post_field('post_content', $id);

        if (has_shortcode($the_content, 'wpsc_nft_author')) {
            $is_a_author = true;
        }

        // is a coin?
        if (get_post_type($id)=="coin") {
            $is_a_token = true;
        } elseif (has_shortcode($the_content, 'wpsc_coin')) {
            $is_a_token = true;
            $id = self::getShortcodeID($the_content, "wpsc_coin");
        }

        // is a crowd?
        if (get_post_type($id)=="crowdfunding") {
            $is_a_crowd = true;
        } elseif (has_shortcode($the_content, 'wpsc_crowdfunding')) {
            $is_a_crowd = true;
            $id = self::getShortcodeID($the_content, "wpsc_crowdfunding");
        }

        $is_a_gallery = false;
        if (has_shortcode($the_content, 'wpsc_nft_my_galleries')) {
            $is_a_gallery = true;
        }

        // is an ico?
        if (get_post_type($id)=="ico") {
            $is_a_ico = true;
        } elseif (has_shortcode($the_content, 'wpsc_ico')) {
            $id = self::getShortcodeID($the_content, "wpsc_ico");
            $is_a_ico = true;
        }

        $is_a_nft = self::isNFT();

        $is_a_nft_collection = self::isNFTCollection();

        // is a stake?
        if (get_post_type($id)=="staking") {
            $is_a_stake = true;
        } elseif (has_shortcode($the_content, 'wpsc_staking')) {
            $is_a_stake = true;
            $id = self::getShortcodeID($the_content, "wpsc_staking");
        }
        
        // is a QR Scanner?
        if (has_shortcode($the_content, 'wpsc_qr_scanner')) {
            $is_a_scanner = true;
        }

        if (has_shortcode($the_content, 'wpsc_launcher') or 
            has_shortcode($the_content, 'wpsc_activate_user') or 
            has_shortcode($the_content, 'wpsc_wizard') or 
            has_shortcode($the_content, 'wpsc_affiliate_program') or
            has_shortcode($the_content, 'wpsc_login_user')
        ) {

            wp_enqueue_script(  'wpsc-web3modal', dirname( plugin_dir_url( __FILE__ ) ) . '/assets/js/web3modal-1.9.0.js' );
            wp_enqueue_script(  'wpsc-wallet-connect', dirname( plugin_dir_url( __FILE__ ) ) . '/assets/js/walletconnect.js' );

            wp_enqueue_style( 'launcher-1', 'https://fonts.googleapis.com');
            wp_enqueue_style( 'launcher-2', 'https://fonts.gstatic.com');
            wp_enqueue_style( 'launcher-3', 'https://fonts.googleapis.com/css2?family=Roboto:wght@100;400;500;700;900&display=swap');
            wp_enqueue_style( 'launcher-4', dirname( plugin_dir_url( __FILE__ ) ) . '/skins/2.0/css/main.css');
            
            self::loadSkin();

            wp_enqueue_script( 'copytoclipboard', dirname( plugin_dir_url( __FILE__ ) ) . '/assets/js/copytoclipboard.js' );
            wp_localize_script( 'copytoclipboard', 'copied', ["str" => __('Copied!', 'wp-smart-contracts')] );

            wp_enqueue_script( 'blockies', dirname( plugin_dir_url( __FILE__ ) ) . '/assets/js/blockies.min.js' );
            wp_enqueue_script( 'accordeon', dirname( plugin_dir_url( __FILE__ ) ) . '/launcher/js/accordeon.js', ["jquery"] );
            wp_enqueue_script( 'launcher', dirname( plugin_dir_url( __FILE__ ) ) . '/launcher/js/launcher.js', ["jquery"] );
            self::enqueueDashboard();
            wp_enqueue_script( 'chart', 'https://cdn.jsdelivr.net/npm/chart.js', ["jquery"] );
            wp_enqueue_script( 'font', 'https://kit.fontawesome.com/e5ae307094.js', ["jquery"] );

            $is_a_scanner = false;

        }
        
        if ($is_a_nft) {
            wp_enqueue_media();
        }

        // load global assets for all contracts
        if ($is_a_author or $is_a_token or $is_a_crowd or $is_a_ico or $is_a_scanner or $is_a_nft or $is_a_stake or $is_a_nft_collection or $is_a_gallery) {

            wp_enqueue_script( 'copytoclipboard', dirname( plugin_dir_url( __FILE__ ) ) . '/assets/js/copytoclipboard.js' );
            wp_localize_script( 'copytoclipboard', 'copied', ["str" => __('Copied!', 'wp-smart-contracts')] );
            wp_enqueue_script( 'wp-smart-contracts-semantic', dirname( plugin_dir_url( __FILE__ ) ) . '/assets/semantic/dist/semantic.min.js', ['jquery'] );
            wp_enqueue_script( 'zoom-qr', dirname( plugin_dir_url( __FILE__ ) ) . '/assets/js/zoom-qr.js' );
            wp_enqueue_script( 'blockies', dirname( plugin_dir_url( __FILE__ ) ) . '/assets/js/blockies.min.js' );
            wp_enqueue_script( 'wpsc-google-prettify', dirname( plugin_dir_url( __FILE__ ) ) . '/assets/js/prettify/run_prettify.js?autoload=true&skin=desert' );
            wp_localize_script( 'wpsc-google-prettify', 'wpsc_plugin_path', ["wpsc_plugin_path" => dirname( plugin_dir_url( __FILE__ ) )] );

            // token specific assets
            if ($is_a_token) {
                
                wp_enqueue_script(  'wpsc-web3modal', dirname( plugin_dir_url( __FILE__ ) ) . '/assets/js/web3modal-1.9.0.js' );
                wp_enqueue_script(  'wpsc-wallet-connect', dirname( plugin_dir_url( __FILE__ ) ) . '/assets/js/walletconnect.js' );
                
                $wpsc_fe_deps[] = 'wpsc-web3modal';
                $wpsc_fe_deps[] = 'wpsc-wallet-connect';
                
                wp_enqueue_script( 'wpsc-fe', dirname( plugin_dir_url( __FILE__ ) ) . '/assets/js/wpsc-fe.js', $wpsc_fe_deps );

                WPSC_Mustache::loadTranslationsForTag("registration");

                self::localizeRPC("wpsc-fe");

                $arr_wpsc_fe["endpoint_url"] = get_rest_url();
                $arr_wpsc_fe["nonce"] = self::get_rest_nonce();
                $arr_wpsc_fe["is_block_explorer"] = $is_a_token?"true":"false";

                $arr_wpsc_fe["fox_img"] = plugins_url( "assets/img/metamask-fox.svg", dirname(__FILE__) );
                $arr_wpsc_fe["wc_img"] = plugins_url( "assets/img/wallet-connect-logo.png", dirname(__FILE__) );
                $arr_wpsc_fe["other_img"] = plugins_url( "assets/img/other-wallets.png", dirname(__FILE__) );
                $arr_wpsc_fe["binance_logo"] = plugins_url( "assets/img/binance-wallet.png", dirname(__FILE__) );
                
                $wpsc_adv_burn = get_post_meta($id, 'wpsc_adv_burn', true);
                $wpsc_adv_pause = get_post_meta($id, 'wpsc_adv_pause', true);
                $wpsc_adv_mint = get_post_meta($id, 'wpsc_adv_mint', true);

                if ($wpsc_adv_burn) $arr_wpsc_fe["wpsc_adv_burn"] = $wpsc_adv_burn;
                if ($wpsc_adv_pause) $arr_wpsc_fe["wpsc_adv_pause"] = $wpsc_adv_pause;
                if ($wpsc_adv_mint) $arr_wpsc_fe['wpsc_adv_mint'] = $wpsc_adv_mint;

                // get the first page defined as qr-scanner
                if ($page = self::getPage("wpsc_is_scanner")) {
                    $arr_wpsc_fe['qr_scanner_page'] = $page;
                }

                $arr_wpsc_fe = self::addRegistrationData($arr_wpsc_fe);
                wp_localize_script( 'wpsc-fe', 'localize_var', $arr_wpsc_fe );

            }

            if ($network_id = get_post_meta($id, 'wpsc_network', true) and $network_array = WPSC_helpers::getNetworks() and WPSC_helpers::valArrElement($network_array, $network_id)) {
                $network_name = $network_array[$network_id]["name"];
                $coin_symbol = $network_array[$network_id]["coin-symbol"];
            }

            // crowd specific assets
            if ($is_a_crowd) {

                wp_enqueue_script(  'wpsc-web3modal', dirname( plugin_dir_url( __FILE__ ) ) . '/assets/js/web3modal-1.9.0.js' );
                wp_enqueue_script(  'wpsc-wallet-connect', dirname( plugin_dir_url( __FILE__ ) ) . '/assets/js/walletconnect.js' );
                
                $wpsc_fe_deps_crowd[] = 'wpsc-web3modal';
                $wpsc_fe_deps_crowd[] = 'wpsc-wallet-connect';

                wp_enqueue_script( 'wpsc-fe-crowd', dirname( plugin_dir_url( __FILE__ ) ) . '/assets/js/wpsc-fe-crowd.js', $wpsc_fe_deps_crowd );

                WPSC_Mustache::loadTranslationsForTag("registration");

                self::localizeRPC("wpsc-fe-crowd");

                if (isset($network_name) and $network_name) {
                    $arr_wpsc_fe_crowd['wpsc_network_name'] = $network_name;
                }

                $arr_wpsc_fe_crowd["wc_img"] = plugins_url( "assets/img/wallet-connect-logo.png", dirname(__FILE__) );
                $arr_wpsc_fe_crowd["other_img"] = plugins_url( "assets/img/other-wallets.png", dirname(__FILE__) );
                $arr_wpsc_fe_crowd["binance_logo"] = plugins_url( "assets/img/binance-wallet.png", dirname(__FILE__) );
                $arr_wpsc_fe_crowd["fox_img"] = plugins_url( "assets/img/metamask-fox.svg", dirname(__FILE__) );
                
                $arr_wpsc_fe_crowd["endpoint_url"] = get_rest_url();
                $arr_wpsc_fe_crowd["nonce"] = self::get_rest_nonce();
                $arr_wpsc_fe_crowd["is_crowd"] = $is_a_crowd?"true":"false";
                if ($page = self::getPage("wpsc_is_scanner")) {
                    $arr_wpsc_fe_crowd['qr_scanner_page'] = $page;
                }

                $arr_wpsc_fe_crowd = self::addRegistrationData($arr_wpsc_fe_crowd);

                wp_localize_script( 'wpsc-fe-crowd', 'localize_var', $arr_wpsc_fe_crowd );

            }

            // ICO specific assets
            if ($is_a_ico) {

                wp_enqueue_script( 'wpsc-fe-ico', dirname( plugin_dir_url( __FILE__ ) ) . '/assets/js/wpsc-fe-ico.js' );

                $arr_wpsc_fe_ico['wpsc_adv_hard'] = ($id and get_post_meta($id, 'wpsc_adv_hard', true))?get_post_meta($id, 'wpsc_adv_hard', true):'0';
                $arr_wpsc_fe_ico['wpsc_adv_cap'] = ($id and get_post_meta($id, 'wpsc_adv_cap', true))?get_post_meta($id, 'wpsc_adv_cap', true):'0';
                $arr_wpsc_fe_ico['endpoint_url'] = get_rest_url();
        
                if (isset($network_name) and $network_name) {
                    $arr_wpsc_fe_ico['wpsc_network_name'] = $network_name;
                }
                if ($page = self::getPage("wpsc_is_scanner")) {
                    $arr_wpsc_fe_ico['qr_scanner_page'] = $page;
                }

                $arr_wpsc_fe_ico = self::addRegistrationData($arr_wpsc_fe_ico);

                WPSC_Mustache::loadTranslationsForTag("registration");

                self::localizeRPC("wpsc-fe-ico");

                wp_localize_script( 'wpsc-fe-ico', 'localize_var', $arr_wpsc_fe_ico );

            }

            if (isset($is_a_stake) and $is_a_stake) {

                wp_enqueue_script(  'wpsc-web3modal', dirname( plugin_dir_url( __FILE__ ) ) . '/assets/js/web3modal-1.9.0.js' );
                wp_enqueue_script(  'wpsc-wallet-connect', dirname( plugin_dir_url( __FILE__ ) ) . '/assets/js/walletconnect.js' );
                
                $wpsc_fe_deps_stake[] = 'wpsc-web3modal';
                $wpsc_fe_deps_stake[] = 'wpsc-wallet-connect';

                $arr_wpsc_fe_stake["wc_img"] = plugins_url( "assets/img/wallet-connect-logo.png", dirname(__FILE__) );
                $arr_wpsc_fe_stake["other_img"] = plugins_url( "assets/img/other-wallets.png", dirname(__FILE__) );
                $arr_wpsc_fe_stake["binance_logo"] = plugins_url( "assets/img/binance-wallet.png", dirname(__FILE__) );
                $arr_wpsc_fe_stake["fox_img"] = plugins_url( "assets/img/metamask-fox.svg", dirname(__FILE__) );
                
                wp_enqueue_script( 'wpsc-fe-stake', dirname( plugin_dir_url( __FILE__ ) ) . '/assets/js/wpsc-fe-stake.js', $wpsc_fe_deps_stake );

                WPSC_Mustache::loadTranslationsForTag("registration");

                self::localizeRPC("wpsc-fe-stake");

                if (isset($network_name) and $network_name) {
                    $arr_wpsc_fe_stake['wpsc_network_name'] = $network_name;
                }

                $arr_wpsc_fe_stake['is_ethereum'] = self::isEthereumNetwork($network_id);

                $arr_wpsc_fe_stake = self::addRegistrationData($arr_wpsc_fe_stake);

                wp_localize_script( 'wpsc-fe-stake', 'localize_var', $arr_wpsc_fe_stake );

            }

            self::loadNFTTheme();

            // NFT specific assets
            if ($is_a_nft) {

                wp_enqueue_script(  'wpsc-web3modal', dirname( plugin_dir_url( __FILE__ ) ) . '/assets/js/web3modal-1.9.0.js' );
                wp_enqueue_script(  'wpsc-wallet-connect', dirname( plugin_dir_url( __FILE__ ) ) . '/assets/js/walletconnect.js' );

                $wpsc_fe_deps_nft[] = 'wpsc-web3modal';
                $wpsc_fe_deps_nft[] = 'wpsc-wallet-connect';
                $wpsc_fe_deps_nft[] = 'jquery';
                $wpsc_fe_deps_nft[] = 'wp-smart-contracts-semantic';

                $arr_wpsc_fe_nft["wc_img"] = plugins_url( "assets/img/wallet-connect-logo.png", dirname(__FILE__) );
                $arr_wpsc_fe_nft["other_img"] = plugins_url( "assets/img/other-wallets.png", dirname(__FILE__) );
                $arr_wpsc_fe_nft["binance_logo"] = plugins_url( "assets/img/binance-wallet.png", dirname(__FILE__) );
                $arr_wpsc_fe_nft["fox_img"] = plugins_url( "assets/img/metamask-fox.svg", dirname(__FILE__) );
                
                wp_enqueue_script( 'wpsc-fe-nft', dirname( plugin_dir_url( __FILE__ ) ) . '/assets/js/wpsc-fe-nft.js', $wpsc_fe_deps_nft );

                WPSC_Mustache::loadTranslationsForTag("registration");

                self::localizeRPC("wpsc-fe-nft");

                wp_localize_script( 'wpsc-fe-nft', 'model_viewer', [ "js" => dirname( plugin_dir_url( __FILE__ ) ) . '/assets/js/model-viewer.min.js' ] );

                $arr_wpsc_fe_nft['endpoint_url'] = get_rest_url();
                $arr_wpsc_fe_nft['nonce'] = self::get_rest_nonce();
                
                if (WPSC_helpers::valArrElement($_GET, 'nft_id') and get_post_meta($id, "wpsc_is_nft_minter", true)) {
                    $nft_id = (int) $_GET["nft_id"];
                    if ($nft_id) {
                        $arr_wpsc_fe_nft['the_permalink_nft'] = get_the_permalink($nft_id);
                    }
                }
                if (isset($network_name) and $network_name) {
                    $arr_wpsc_fe_nft['wpsc_network_name'] = $network_name;
                    $arr_wpsc_fe_nft['wpsc_coin_symbol'] = $coin_symbol;
                }

                if ($collection_id = self::getCollectionID()) {

                    if (!isset($coin_symbol) or !$coin_symbol) {
                        if ((!isset($network_id) or !$network_id)) $network_id = get_post_meta($collection_id, 'wpsc_network', true);
                        if (!isset($network_array) or !$network_array) $network_array = WPSC_helpers::getNetworks();
                        if (isset($network_id) and isset($network_array) and $network_array) {
                            $coin_symbol = (
                                WPSC_helpers::valArrElement($network_array, $network_id) and
                                WPSC_helpers::valArrElement($network_array[$network_id], 'coin-symbol')
                            )?$network_array[$network_id]["coin-symbol"]:"";
                            $arr_wpsc_fe_nft['wpsc_coin_symbol'] = $coin_symbol;
                        }
                    }
                }
        
                $arr_wpsc_fe_nft['is_ethereum'] = self::isEthereumNetwork($network_id);
                $arr_wpsc_fe_nft['fox'] = plugins_url( "assets/img/metamask-fox.svg", dirname(__FILE__) );

                // get the first page defined as qr-scanner
                if ($page = self::getPage("wpsc_is_scanner")) {
                    $arr_wpsc_fe_nft['qr_scanner_page'] = $page;
                }

                $arr_wpsc_fe_nft["contract_version"] = self::getContractVersion($collection_id);
                $arr_wpsc_fe_nft = self::addRegistrationData($arr_wpsc_fe_nft);

                wp_localize_script( 'wpsc-fe-nft', 'localize_var', $arr_wpsc_fe_nft);

                wp_localize_script( 'wpsc-fe-nft', 'wpscApiSettings', array(
                    'root' => esc_url_raw( rest_url() ),
                    'nonce' => wp_create_nonce( 'wp_rest' ),
                    'post_id' => $id
                ));

            }

            if ($is_a_nft_collection or self::isNFTTax()) {
                if (isset($network_name) and isset($coin_symbol)) {
                    self::loadNFTMy($network_name, $coin_symbol);
                } else {
                    self::loadNFTMy();
                }
            }

            wp_enqueue_style( 'wp-smart-contracts-semantic', dirname( plugin_dir_url( __FILE__ ) ) . '/assets/semantic/dist/semantic.min.css');
            wp_enqueue_style( 'wp-smart-contracts-styles', dirname( plugin_dir_url( __FILE__ ) ) . '/assets/css/styles.css', ['wp-smart-contracts-semantic']);

            // enqueue profile / user galleries assets	
            if (has_shortcode($the_content, 'wpsc_nft_my_galleries')) {	
                wp_enqueue_script('wpsc-galleries-js', dirname( plugin_dir_url( __FILE__ ) ) . '/assets/js/wpsc-galleries.js');	
                wp_enqueue_style( 'wpsc-galleries-css', dirname( plugin_dir_url( __FILE__ ) ) . '/assets/css/wpsc-galleries.css');
            }
            
        }

    }

    static public function getContractVersion($id) {
        $factory = get_post_meta($id, "wpsc_factory", true);
        if ($factory) {
            $factory = json_decode($factory, true);
            if (WPSC_helpers::valArrElement($factory, 'version')) {
                return $factory["version"];
            }    
        }
        return null;
    }

    static public function loadNFTMy($network_name=false, $coin_symbol = false) {
        
        $collection_id = false;

        wp_enqueue_script(  'wpsc-web3modal', dirname( plugin_dir_url( __FILE__ ) ) . '/assets/js/web3modal-1.9.0.js' );
        wp_enqueue_script(  'wpsc-wallet-connect', dirname( plugin_dir_url( __FILE__ ) ) . '/assets/js/walletconnect.js' );
        $wpsc_fe_deps_nft[] = 'wpsc-web3modal';
        $wpsc_fe_deps_nft[] = 'wpsc-wallet-connect';
        $wpsc_fe_deps_nft[] = 'jquery';
        $arr_nft_my["wc_img"] = plugins_url( "assets/img/wallet-connect-logo.png", dirname(__FILE__) );
        $arr_nft_my["other_img"] = plugins_url( "assets/img/other-wallets.png", dirname(__FILE__) );
        $arr_nft_my["binance_logo"] = plugins_url( "assets/img/binance-wallet.png", dirname(__FILE__) );
        $arr_nft_my["fox_img"] = plugins_url( "assets/img/metamask-fox.svg", dirname(__FILE__) );

        wp_register_script( 'model-viewer', dirname( plugin_dir_url( __FILE__ ) ) . '/assets/js/model-viewer.min.js', ['jquery'] );
        wp_enqueue_script( 'model-viewer' ); 

        add_filter('script_loader_tag', 'wpsc_add_type_attribute' , 10, 3);

        wp_register_script( 'nft-js-my', dirname( plugin_dir_url( __FILE__ )) . '/assets/js/wpsc-fe-nft-my.js', $wpsc_fe_deps_nft );
        wp_enqueue_script( 'nft-js-my' );

        WPSC_Mustache::loadTranslationsForTag("registration");

        self::localizeRPC("nft-js-my");

        self::loadNFTTheme();

        if (!isset($network_name) or !$network_name) {
            if (WPSC_helpers::valArrElement($_GET, 'id')) {
                $collection_id = (int) $_GET["id"];
                if ($network_id = get_post_meta($collection_id, 'wpsc_network', true) and $network_array = WPSC_helpers::getNetworks()) {
                    $network_name = $network_array[$network_id]["name"];
                }
            }
        }

        if (!isset($coin_symbol) or !$coin_symbol) {
            if ((!isset($collection_id) or !$collection_id) and WPSC_helpers::valArrElement($_GET, 'id')) $collection_id = (int) $_GET["id"];
            if (isset($collection_id) and $collection_id) {
                if ((!isset($network_id) or !$network_id)) $network_id = get_post_meta($collection_id, 'wpsc_network', true);
            }
            if (!isset($network_array) or !$network_array) $network_array = WPSC_helpers::getNetworks();
            if (isset($network_id) and isset($network_array) and $network_array and WPSC_helpers::valArrElement($network_array, $network_id)) {
                $coin_symbol = $network_array[$network_id]["coin-symbol"];
            }
        }

        $arr_nft_my['wpsc_network_name'] = $network_name;
        $arr_nft_my['wpsc_coin_symbol'] = $coin_symbol;
        $arr_nft_my['fox'] = plugins_url( "assets/img/metamask-fox.svg", dirname(__FILE__) );
        
        wp_localize_script( 'nft-js-my', 'wpscApiSettings', [
            'root' => esc_url_raw( rest_url() ),
            'nonce' => wp_create_nonce( 'wp_rest' ),
            'path_to_launcher' => plugins_url( "launcher/", dirname(__FILE__))
        ]);

        $collection_id = self::getCollectionID();
         
        if ($collection_id) {

            $arr_nft_my["contract_version"] = self::getContractVersion($collection_id);
            $arr_nft_my = self::addRegistrationData($arr_nft_my);

            wp_localize_script( 'nft-js-my', 'localize_var', $arr_nft_my);
    
            $wpsc_pixelated_images = get_post_meta($collection_id, 'wpsc_pixelated_images', true);
            if ($wpsc_pixelated_images) {
                wp_localize_script( 'nft-js-my', 'images', [
                    'wpsc_pixelated_images' => $wpsc_pixelated_images
                ]);    
            }
        }

    }

    static private function loadNFTTheme() {
        
        if ($path = WPSC_Mustache::getThemePath(true)) {

            wp_enqueue_style( 'wpsc-fe-nft-theme-css', $path . 'css/main.css');

            switch (WPSCSettingsPage::nftSkin()) {
                case '20':
                    wp_enqueue_style( 'wpsc-fe-nft-theme-color-css', $path . 'css/blue.css');
                    break;
                case '20red':
                    wp_enqueue_style( 'wpsc-fe-nft-theme-color-css', $path . 'css/red.css');
                    break;
                case '20green':
                    wp_enqueue_style( 'wpsc-fe-nft-theme-color-css', $path . 'css/green.css');
                    break;
                case '20purple':
                    wp_enqueue_style( 'wpsc-fe-nft-theme-color-css', $path . 'css/purple.css');
                    break;
                case '20pink':
                    wp_enqueue_style( 'wpsc-fe-nft-theme-color-css', $path . 'css/pink.css');
                    break;
                case '20orange':
                    wp_enqueue_style( 'wpsc-fe-nft-theme-color-css', $path . 'css/orange.css');
                    break;
                case '20black':
                    wp_enqueue_style( 'wpsc-fe-nft-theme-color-css', $path . 'css/black.css');
                    break;
                case '20cream':
                    wp_enqueue_style( 'wpsc-fe-nft-theme-color-css', $path . 'css/cream.css');
                    break;
                case '20white':
                    wp_enqueue_style( 'wpsc-fe-nft-theme-color-css', $path . 'css/white.css');
                    break;
                case '20white2':
                    wp_enqueue_style( 'wpsc-fe-nft-theme-color-css', $path . 'css/white2.css');
                    break;
                case '20white3':
                    wp_enqueue_style( 'wpsc-fe-nft-theme-color-css', $path . 'css/white3.css');
                    break;
                case '20white4':
                    wp_enqueue_style( 'wpsc-fe-nft-theme-color-css', $path . 'css/white4.css');
                    break;
            }
            
            if (file_exists($path . 'js/main.js')) {
                wp_enqueue_script( 'wpsc-fe-nft-theme', $path . 'js/main.js' );
            }

        }

    }

    // get the wp rest nonce with the proper separator & or ?
    private static function get_rest_nonce() {

        $nonce = wp_create_nonce('wp_rest');
        
        if (strpos(get_rest_url(), '?')===false) {
            return urlencode("?_wpnonce=" . $nonce);
        } else {
            return urlencode("&_wpnonce=" . $nonce);
        }

    }

    static public function getOldPageID($meta) {
        $pages = get_pages([
            'meta_key' => '_wp_page_template',
            'meta_value' => 'wpsc-clean-template.php'
        ]);
        if (is_array($pages)) {
            foreach($pages as $page) {
                if (is_object($page) and get_post_meta($page->ID, $meta, true)) {
                    return $page->ID;
                }    
            }
        }
        return false;
    }

    static public function getNFTMyGalleriesPage() {	
        return self::getPage("wpsc_is_nft_my_galleries");	
    }

    static public function getPage($meta, $return_id=false) {

        $pages = get_pages([
            'meta_key' => 'is_wpsc_page',
            'meta_value' => true
        ]);
        
        if (is_array($pages)) {
            foreach($pages as $page) {
                if (is_object($page) and get_post_meta($page->ID, $meta, true)) {
                    if ($return_id) return $page->ID;
                    return get_permalink($page->ID);
                }    
            }
        }

        return false;

    }

}

