<?php

if( ! defined( 'ABSPATH' ) ) die;

/**
 * Define and render shortcodes
 */

new WPSC_Shortcodes();

class WPSC_Shortcodes {

    private $templates;

    function __construct() {
        add_shortcode( 'wpsc_coin', [$this, "coin"] );
        add_shortcode( 'wpsc_qr_scanner', [$this, "qrScanner"] );
        add_shortcode( 'wpsc_crowdfunding', [$this, "crowdfunding"] );
        add_shortcode( 'wpsc_nft_collection', [$this, "nftCollection"] );
        add_shortcode( 'wpsc_nft_taxonomy', [$this, "nftTaxonomy"] );
        add_shortcode( 'wpsc_nft', [$this, "nft"] );
        add_shortcode( 'wpsc_nft_my_galleries', [$this, "nftMyGalleries"] );
        add_shortcode( 'wpsc_nft_mint', [$this, "nftMint"] );
        add_shortcode( 'wpsc_nft_my_items', [$this, "nftMyItems"] );
        add_shortcode( 'wpsc_nft_my_bids', [$this, "nftMyBids"] );
        add_shortcode( 'wpsc_nft_author', [$this, "nftAuthor"] );
        add_shortcode( 'wpsc_staking', [$this, "staking"] );
        add_shortcode( 'wpsc_launcher', [$this, "launcher"] );
        add_shortcode( 'wpsc_wizard', [$this, "wizard"] );
        add_shortcode( 'wpsc_activate_user', [$this, "activateUser"] );
        add_shortcode( 'wpsc_login_user', [$this, "loginUser"] );
        add_shortcode( 'wpsc_ico', [$this, "ico"] );

        $wpsc_role = WPSC_helpers::getRole();
        if ($wpsc_role and $wpsc_role!="deactivated") {
            add_action('login_form', [ $this, "simpleLoginUser" ]);
            add_action('register_form', [ $this, "simpleLoginUser" ]);    
        }

    }

    public function ico($params) {
        $the_id = self::getPostID($params);
        $wpsc_thumbnail = get_the_post_thumbnail_url($the_id);
        $wpsc_title = get_the_title($the_id);
        $wpsc_content = get_post_field('post_content', $the_id);
        $wpsc_network = get_post_meta($the_id, 'wpsc_network', true);
        $wpsc_txid = get_post_meta($the_id, 'wpsc_txid', true);
        $wpsc_owner = get_post_meta($the_id, 'wpsc_owner', true);
        $wpsc_contract_address = get_post_meta($the_id, 'wpsc_contract_address', true);
        $wpsc_blockie = get_post_meta($the_id, 'wpsc_blockie', true);
        $wpsc_blockie_owner = get_post_meta($the_id, 'wpsc_blockie_owner', true);
        $wpsc_qr_code = get_post_meta($the_id, 'wpsc_qr_code', true);
        $wpsc_flavor        = null;
        $native_coin = WPSC_helpers::nativeCoinName($wpsc_network);
        // show contract
        if ($wpsc_contract_address) {
            $wpsc_flavor        = get_post_meta($the_id, 'wpsc_flavor', true);
        }
        $timed = get_post_meta($the_id, 'wpsc_adv_timed', true);
        if ($timed==="false") $timed=false;
        $wpsc_hardcap = get_post_meta($the_id, 'wpsc_adv_hard', true);
        if ($wpsc_hardcap==="false") $wpsc_hardcap=false;
        if ($timed) {
            $utc_now = gmdate('Y-m-d');
            $now = self::utc_timestamp($utc_now);
            $opening_string = get_post_meta($the_id, 'wpsc_adv_opening', true) . " 00:00:00";
            $closing_string = get_post_meta($the_id, 'wpsc_adv_closing', true) . " 23:59:59";
            $opening = self::utc_timestamp($opening_string);
            $closing = self::utc_timestamp($closing_string);
            $opening_human = date("F j, Y, g:i a", $opening) . " GMT";
            $closing_human = date("F j, Y, g:i a", $closing) . " GMT";
            $opening_human_short = date("M j, Y", $opening) . " GMT";
            $closing_human_short = date("M j, Y", $closing) . " GMT";
            if ($now>=$opening and $now<=$closing) {
                $is_open = true;
                // if timed can contribute only if open
                $can_contribute = true;
            }
            if ($utc_now>$closing_string) {
                $is_closed = true;
            }
            // if timed and open or closed show how much is raised
            if ((isset($is_open) and $is_open) or (isset($is_closed) and $is_closed)) {
                $show_raised = true;
            }
        } else {
            // if not timed can contribute any time and always shows raised
            $can_contribute = true;
            $show_raised = true;
        }
        list($color, $icon, $etherscan, $network_val) = WPSC_Metabox::getNetworkInfo(get_post_meta($the_id, 'wpsc_network', true));
        $is_bubblegum = false;
        if ($wpsc_flavor=="bubblegum") {
            $is_bubblegum = true;
        }
        $m = new Mustache_Engine;
        $the_symbol = strtoupper(get_post_meta($the_id, 'wpsc_symbol', true));
        $the_rate = get_post_meta($the_id, 'wpsc_rate', true);
        $atts_ico_view_brand = [
            "wpsc_thumbnail" => $wpsc_thumbnail,
            "wpsc_title" => $wpsc_title,
            "wpsc_content" => $wpsc_content,
            "etherscan" => $etherscan,
            "color" => $color,
            "icon" => $icon,
            "network_val" => $network_val,
            "flavor-color" => "blue",
            "smart-contract-label" => __('Smart Contract', 'wp-smart-contracts'),
            "ico-label" => __('ICO', 'wp-smart-contracts'),
            "admin-label" => __('Control Panel', 'wp-smart-contracts'),
            "update" => __('Update', 'wp-smart-contracts'),
            "token-name" => __('Token name', 'wp-smart-contracts'),
            "token-symbol" => __('Token Symbol', 'wp-smart-contracts'),
            "initial-supply" => __('Initial supply', 'wp-smart-contracts'),
            "hard-cap" => __('Hard cap', 'wp-smart-contracts'),
            "rate-label" => __('Token distribution rate', 'wp-smart-contracts'),
            "rate-detail" => sprintf( __( '%s %s per each %s', 'wp-smart-contracts' ), $the_rate, $the_symbol, $native_coin),
            "pause-resume-label" => __('Pause or Resume ICO Activity', 'wp-smart-contracts'),
            "pause-resume-desc" => __('Here, you can pause or resume the ICO, either for finalization or a temporary pause.', 'wp-smart-contracts'),
            "calendar" => __('Calendar', 'wp-smart-contracts'),
            "raised" => __('Raised', 'wp-smart-contracts'),
            "sold" => __('sold!', 'wp-smart-contracts'),
            "hard-cap-reached" => __('Hardcap reached', 'wp-smart-contracts'),
            "send-ether" => sprintf( __( 'Contribute by transferring %s', 'wp-smart-contracts' ), $native_coin),
            "send-ether-address" => __('Send contributions directly to the Contract.', 'wp-smart-contracts'),
            "send-only-ether" => sprintf( __( 'Send only %s, do not send ERC-20 tokens, otherwise you will lose your funds.', 'wp-smart-contracts' ), $native_coin),
            "erc20-wallet" => __('You will receive your tokens in the same address you use to send the contribution. Please make sure you are using an ERC20 Token compatible wallet.', 'wp-smart-contracts'),
            "no-exchange" => __('Do not send contributions from an exchange', 'wp-smart-contracts'),
            "copied" => __('Copied!', 'wp-smart-contracts'),
            "buy-tokens" => __('Buy Tokens', 'wp-smart-contracts'),
            "buy-desc" => __('Buy tokens using your wallet.', 'wp-smart-contracts'),
            "buy" => __('Buy', 'wp-smart-contracts'),
            "remaining-tokens" => __('Remaining Tokens', 'wp-smart-contracts'),
            "no-contributions" => __('Contributions are not enabled', 'wp-smart-contracts'),
            "approved-funds" => __('Approved funds', 'wp-smart-contracts'),
            "approve" => __('Approve', 'wp-smart-contracts'),
            "approve-funds-ico" => __("Approve funds for your ICO Contract", "wp-smart-contracts"),
            "pause-alert-detail" => __('The ICO has been halted.', 'wp-smart-contracts'),
            "no-contributions-detail" => __('The ICO has no funds to sell at this time. Try again later.', 'wp-smart-contracts'),
            "ico-icon" => plugins_url( "assets/img/ico.png", dirname(__FILE__) ),
            "wpsc-add-token-to-metamask" => $m->render(
                WPSC_Mustache::getTemplate('add-token-to-metamask'), [
                    "network" => $network_val,
                    "contract-address" => get_post_meta($the_id, 'wpsc_token_contract_address', true),
                    "token-symbol" => $the_symbol,
                    "fox" => plugins_url( "assets/img/metamask-fox.svg", dirname(__FILE__) )
                ]
            ),
            "wpsc_adv_hard" => $wpsc_hardcap,
            "wpsc_adv_cap" => WPSC_helpers::formatNumber(get_post_meta($the_id, 'wpsc_adv_cap', true)),
            "wpsc_adv_white" => get_post_meta($the_id, 'wpsc_adv_white', true),
            "wpsc_adv_pause" => get_post_meta($the_id, 'wpsc_adv_pause', true),

            //wpsc_dist_wallet
            //wpsc_wallet
            "dist-wallet" => __('Distribution Wallet', 'wp-smart-contracts'),
            "wallet" => __('Wallet', 'wp-smart-contracts'),

            "wpsc_adv_timed" => $timed,
            "wpsc_adv_opening" => isset($opening)?$opening:null,
            "wpsc_adv_closing" => isset($closing)?$closing:null,
            "wpsc_coin_name" => get_post_meta($the_id, 'wpsc_coin_name', true),
            "wpsc_coin_symbol" => $the_symbol,
            "wpsc_total_supply" => WPSC_helpers::formatNumber(get_post_meta($the_id, 'wpsc_total_supply', true)),
            "wpsc_rate" => WPSC_helpers::formatNumber(get_post_meta($the_id, 'wpsc_rate', true)),
            "wpsc_native_coin" => $native_coin,
            "timed" => __('Timed', 'wp-smart-contracts'),
            "from" => __('From', 'wp-smart-contracts'),
            "to" => __('to', 'wp-smart-contracts'),
            "token-address" => __('Token Address', 'wp-smart-contracts'),
            "or" => __('OR', 'wp-smart-contracts'),
            "wpsc_contract_address" => $wpsc_contract_address,
            "wpsc_contract_address_short" => WPSC_helpers::shortify($wpsc_contract_address, true),
            "wpsc_blockie" => get_post_meta($the_id, 'wpsc_blockie', true),
            "wpsc_blockie_token" => get_post_meta($the_id, 'wpsc_blockie_token', true),
            "token-for-sale" => __('Token for sale', 'wp-smart-contracts'),
            "raised-label" => __('Raised', 'wp-smart-contracts'),
            "rate" => $the_rate,
            "sold-label" => __( 'Sold', 'wp-smart-contracts' ),
            "fox" => dirname( plugin_dir_url( __FILE__ ) ) . '/assets/img/metamask-fox.svg',
            "wpsc_name" => get_post_meta($the_id, 'wpsc_name', true),
            "wpsc_symbol" => get_post_meta($the_id, 'wpsc_symbol', true),
            "wpsc_decimals" => get_post_meta($the_id, 'wpsc_decimals', true),
            "wpsc_token_contract_address" => get_post_meta($the_id, 'wpsc_token', true),
            "wpsc_token_contract_address_short" => WPSC_helpers::shortify(get_post_meta($the_id, 'wpsc_token', true), true),
            "wpsc_qr_code" => get_post_meta($the_id, 'wpsc_qr_code', true),
            "wpsc_token_qr_code" => get_post_meta($the_id, 'wpsc_token_qr_code', true),
            "opening_human_short" => isset($opening_human_short)?$opening_human_short:null,
            "closing_human_short" => isset($closing_human_short)?$closing_human_short:null,
            "block-explorer" => __('Block Explorer', 'wp-smart-contracts'),
            "block-explorer-link" => get_permalink(get_post_meta($the_id, 'token_id', true)),
            "resume" => __('Resume', 'wp-smart-contracts'),
            "pause" => __('Pause', 'wp-smart-contracts'),
            "cancel" => __('Cancel', 'wp-smart-contracts'),
            "tx-in-progress" => __('Transaction in progress', 'wp-smart-contracts'),
            "deploy-icon" => plugin_dir_url( dirname(__FILE__) ) . '/assets/img/deploy-identicon.gif',
            "click-confirm" => __('If you agree and wish to proceed, please click "CONFIRM" transaction in the Metamask Window, otherwise click "REJECT".', 'wp-smart-contracts'),
            "please-patience" => __('Please be patient. It can take several minutes. Don\'t close or reload this window.', 'wp-smart-contracts'),
            "is_bubblegum" => $is_bubblegum,
            "tokens-to-sell" => __('Total tokens to sell', 'wp-smart-contracts'),
        ];
        $atts_ico_view_panel = [
            "is_open" => isset($is_open)?$is_open:null,
            "is_closed" => isset($is_closed)?$is_closed:null,
            "can_contribute" => isset($can_contribute)?$can_contribute:null,
            "show_raised" => isset($show_raised)?$show_raised:null,
            "opening_human" => isset($opening_human)?$opening_human:null,
            "closing_human" => isset($closing_human)?$closing_human:null,
            "opening" => isset($opening)?$opening * 1000:null,
            "closing" => isset($closing)?$closing * 1000:null,
            'network' => $wpsc_network,
            'wpsc-contract-address' => $wpsc_contract_address,
            'wpsc-flavor' => $wpsc_flavor,
    
            "ico-will-open" => __('ICO Coming Soon', 'wp-smart-contracts'),
            "ico-is-open" => __('ICO Is Open', 'wp-smart-contracts'),
            "ico-is-closed" => __('ICO Is Closed', 'wp-smart-contracts'),
            "ico-closed" => __('Closed on', 'wp-smart-contracts'),
            "ico-contribute" => dirname( plugin_dir_url( __FILE__ ) ) . '/assets/img/ico.png',
            "contribute-tooltip" => __('Purchase tokens directly from ICO contract.', 'wp-smart-contracts'),
            "contribute-help" => __('This is the address where you are going to receive the tokens. The beneficiary account has to be a valid ERC20 token compatible address.', 'wp-smart-contracts'),
            "what-is" => __('What\'s this?', 'wp-smart-contracts'),
            "amount-ether" => __('Amount to spend', 'wp-smart-contracts'),
            "send" => __('Send', 'wp-smart-contracts'),
            "cancel" => __('Cancel', 'wp-smart-contracts'),
            "scan" => __('Scan', 'wp-smart-contracts'),
            "beneficiary" => __('Beneficiary account', 'wp-smart-contracts'),
            "tx-in-progress" => __('Transaction in progress', 'wp-smart-contracts'),
            "deploy-icon" => plugin_dir_url( dirname(__FILE__) ) . '/assets/img/deploy-identicon.gif',
            "click-confirm" => __('If you agree and wish to proceed, please click "CONFIRM" transaction in the Metamask Window, otherwise click "REJECT".', 'wp-smart-contracts'),
            "please-patience" => __('Please be patient. It can take several minutes. Don\'t close or reload this window.', 'wp-smart-contracts'),
            
        ] + $atts_ico_view_brand;
        if ($wpsc_contract_address) {
            $atts_ico_view_panel["contract-exists"] = true;
        }
        $atts_source_code = WPSC_Metabox::wpscGetMetaSourceCodeAtts($the_id);
        $msg_box = "";
        if (WPSC_helpers::valArrElement($_GET, 'welcome')) {
            $actual_link = strtok("https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]", '?');
            $msg_box = $m->render(
                WPSC_Mustache::getTemplate('msg-box'), 
                [
                    'type' => 'info',
                    'icon' => 'info',
                    'title' => __('Important Information', 'wp-smart-contracts'),
                    'msg' => "<p>Your contract was successfully deployed to the address: " . $wpsc_contract_address . "</p>" .
                        "<p>The URL of your ICO is: " . $actual_link . "</p>" .
                        "<p>Please store this information for future reference.</p>"
                ]
            );
        }
        return $m->render(WPSC_Mustache::getTemplate('ico-view'), array_merge(
            $atts_ico_view_brand,
            $atts_ico_view_panel,
            [
                'skins-1-deprecated' => $m->render(
                    WPSC_Mustache::getTemplate('msg-box'), 
                    [
                        'type' => 'warning',
                        'icon' => 'warning',
                        'title' => __("Please select a 2.0 Skin for full support.", "wp-smart-contracts"),
                        'msg' => __("Are you the site administrator?. The selected skin is deprecated, meaning it is no longer maintained. As a result, new features like the Launcher and new smart contracts are not supported under this skins.", "wp-smart-contracts")
                    ]
                ),

                'view-metamask' => self::viewMetamask($m),

                "main-nav" => $m->render(WPSC_Mustache::getTemplate('launcher-main-nav'), self::addLauncherMainNavAtts([
                    "section-2" => __("ICO", "wp-smart-contracts")
                ])),    

                'msg-box' => $msg_box
            ])
        );
    }
    
    public static function addRegistrationOptions($atts) {

        $options = get_option('etherscan_api_key_option');

        $wpsc_role = (WPSC_helpers::valArrElement($options, "wpsc_role") and !empty($options["wpsc_role"]))?$options["wpsc_role"]:false;
        if ($wpsc_role != "deactivated") {
            $atts["registration_enabled"] = true;
        }
        
        if (WPSC_helpers::valArrElement($options, "wpsc_email_registration") and !empty($options["wpsc_email_registration"])) {
            $atts["email_required"] = true;
        }  

        return $atts;

    }

    public function loginUser() {
        $m = new Mustache_Engine;
        $atts = ['msg' => __("Login with your wallet", "wp-smart-contracts")];
        $atts = self::addRegistrationOptions($atts);
        $current_user = wp_get_current_user();
        if ( $current_user instanceof WP_User ) {
            $atts["current_user"] = $current_user->user_login;
        }
        $atts["login-with-your-wallet"] = __("Login with your wallet", "wp-smart-contracts");
        $atts["login"] = __("Login", "wp-smart-contracts");
        $atts["logout"] = __("Logout", "wp-smart-contracts");
        return $m->render(WPSC_Mustache::getTemplate('login-user'), $atts);
    }

    public function simpleLoginUser() {
        echo do_shortcode("[wpsc_login_user]");
    }

    public function activateUser($params) {

        $user_id = filter_input( INPUT_GET, 'wpsc_user', FILTER_VALIDATE_INT, array( 'options' => array( 'min_range' => 1 ) ) );
        $m = new Mustache_Engine;
        if ( $user_id ) {
            $code = get_user_meta( $user_id, 'wpsc_has_to_be_activated', true );
            $code_input = filter_input( INPUT_GET, 'wpsc_key' );
            if ( $code == $code_input) {
                delete_user_meta( $user_id, 'wpsc_has_to_be_activated' );

                // get the user role
                $options = get_option('etherscan_api_key_option');
                $wpsc_role = (WPSC_helpers::valArrElement($options, "wpsc_role") and !empty($options["wpsc_role"]))?$options["wpsc_role"]:false;
                if (!$wpsc_role) $wpsc_role = "subscriber";

                // change the user role
                $u = new WP_User( $user_id );
                $u->set_role($wpsc_role);
                
                return $m->render(
                    WPSC_Mustache::getTemplate('msg-box2'), 
                    [
                        'type' => 'info',
                        'icon' => 'info',
                        'title' => __('Activation successful', "wp-smart-contracts"),
                        'msg' => __("Your activation was successful, thanks for confirming, now you can go back to the page and log in", "wp-smart-contracts")
                    ]
                );

            } else {
                return $m->render(
                        WPSC_Mustache::getTemplate('msg-box2'), 
                        [
                            'type' => 'warning',
                            'icon' => 'warning',
                            'title' => __("An error has occurred", "wp-smart-contracts"),
                            'msg' => __("User activation failed, was it already activated?", "wp-smart-contracts")                            
                        ]
                );
            }
        } else {
            return $m->render(
                    WPSC_Mustache::getTemplate('msg-box2'), 
                    [
                        'type' => 'warning',
                        'icon' => 'warning',
                        'title' => __("An error has occurred", "wp-smart-contracts"),
                        'msg' => __("User activation failed, was it already activated?", "wp-smart-contracts")                            
                ]
            );
        }        
    }

    public function qrScanner($params) {
        
        $atts = [
            "qr-scanner" => plugins_url( "assets/js/qr-scanner.min.js", dirname(__FILE__) ),
            "qr-scanner-worker" => plugins_url( "assets/js/qr-scanner-worker.min.js", dirname(__FILE__) ),
            "align-camera" => __('Align the QR code with the camera', 'wp-smart-contracts')
        ];

        if (array_key_exists('input', $_GET) and $input = $_GET['input'] and $input_sanitized = sanitize_text_field( $input )) {
            $atts["input-name"] = $input_sanitized;
        }

        $m = new Mustache_Engine;
        return $m->render(WPSC_Mustache::getTemplate('qr-scanner'), $atts);

    }

    static private function addLauncherMainNavAtts($atts) {

        if (WPSCSettingsPage::get('wpsc_activate_launcher')) {
            $atts["dashboard_link"] = WPSC_assets::getPage('wpsc_is_launcher');
            $atts["mobile-menu"] = true;
        } else {
            $atts["dashboard_link"] = get_home_url();
        }
        $atts["path-to-launcher"] = plugins_url( "launcher/", dirname(__FILE__));
        $atts["connect-wallet"] = __("Connect your Wallet", "wp-smart-contracts");
        $atts["fox"] = dirname( plugin_dir_url( __FILE__ ) ) . '/assets/img/metamask-fox.svg';
        $atts["deploy-identicon"] = dirname( plugin_dir_url( __FILE__ ) ) . '/assets/img/deploy-identicon.gif';
        $atts["wc"] = dirname( plugin_dir_url( __FILE__ ) ) . '/assets/img/wc.png';
        $atts["transaction-in-progress"] = __("Transaction in progress", "wp-smart-contracts");
        $atts["confirm-tx"] = __('If you agree and wish to proceed, please click "CONFIRM" transaction in your wallet, otherwise click "REJECT".<br>Please be patient. It can take several minutes. Don\'t close or reload this window.', "wp-smart-contracts");
        $atts["dashboard"] = __("Dashboard", "wp-smart-contracts");
        $atts["nft"] = __("NFT", "wp-smart-contracts");
        $atts["create-a-collection"] = __("Create a Collection", "wp-smart-contracts");
        $atts["crowdfunding"] = __("Crowdfunding", "wp-smart-contracts");
        $atts["manage-sc"] = __("Manage Smart Contracts", "wp-smart-contracts");
        $atts["staking"] = __("Staking", "wp-smart-contracts");
        $atts["connect"] = __("Connect", "wp-smart-contracts");
        $atts["create-service"] = __("Create a service", "wp-smart-contracts"); 
        $atts["token-services"] = __("Token Services", "wp-smart-contracts"); 
        $atts["create-staking"] = __("Create a staking", "wp-smart-contracts"); 
        $atts["create-crowd"] = __("Create a Crowdfunding", "wp-smart-contracts"); 
        $atts["create-token"] = __("Create a Token", "wp-smart-contracts");
        $atts["tokens"] = __("Tokens", "wp-smart-contracts");
        $atts["edit"] = __("Edit", "wp-smart-contracts");

        $current_user = wp_get_current_user();
        if ( $current_user instanceof WP_User ) {
            $atts["current_user"] = $current_user->user_login;
        }

        $atts["wps-link-edit"] = json_encode([
            "mochi"=>WPSC_assets::getPage('wpsc_is_wizard_deploy_mochi'),
            "matcha"=>WPSC_assets::getPage('wpsc_is_wizard_deploy_matcha'),
            "suika"=>WPSC_assets::getPage('wpsc_is_wizard_deploy_suika'),
            "yuzu"=>WPSC_assets::getPage('wpsc_is_wizard_deploy_yuzu'),
            "azuki"=>WPSC_assets::getPage('wpsc_is_wizard_deploy_azuki'),
            "ikasumi"=>WPSC_assets::getPage('wpsc_is_wizard_deploy_ikasumi'),
            "ube"=>WPSC_assets::getPage('wpsc_is_wizard_deploy_ube'),
            "almond"=>WPSC_assets::getPage('wpsc_is_wizard_deploy_almond'),
            "mango"=>WPSC_assets::getPage('wpsc_is_wizard_deploy_mango'),
            "bubblegum"=>WPSC_assets::getPage('wpsc_is_wizard_deploy_bubblegum')
        ]);

        $logo_data = WPSC_helpers::getLogoAffP();

        if (isset($_GET["network_id"])) {
            $get_networkid = (int) sanitize_text_field($_GET["network_id"]);
            if ($get_networkid) {
                $atts["suggested_network_id"] = $get_networkid;
            }
        }
        
        $atts["customer-logo"] = "";
        if (isset($logo_data["logo"])) {
            $atts["customer-logo"] = $logo_data["logo"];
        }
        $atts["home_url"] = get_home_url();

        $atts["dashboard_link"] = WPSC_assets::getPage('wpsc_is_launcher');
        $atts["nft_manage_link"] = WPSC_assets::getPage('wpsc_is_launcher_nft');
        $atts["crowd_manage_link"] = WPSC_assets::getPage('wpsc_is_launcher_crowd');
        $atts["token_manage_link"] = WPSC_assets::getPage('wpsc_is_launcher_coin');        
        $atts["services_manage_link"] = WPSC_assets::getPage('wpsc_is_launcher_services');

        $atts["nft_link"] = WPSC_assets::getPage('wpsc_is_wizard_nft');
        $atts["staking_link"] = WPSC_assets::getPage('wpsc_is_wizard_stakes');
        $atts["staking_manage_link"] = WPSC_assets::getPage('wpsc_is_launcher_stakes');
        $atts["services_link"] = WPSC_assets::getPage('wpsc_is_wizard_services');
        $atts["crowd_link"] = WPSC_assets::getPage('wpsc_is_wizard_crowd');
        $atts["token_link"] = WPSC_assets::getPage('wpsc_is_wizard_coin');

        $atts = self::addRegistrationOptions($atts);

        $atts["smart-contract"] = __("Smart Contract", "wp-smart-contracts");
        $atts["use-trust-wallet"] = __("Use Trust Wallet or any other compatible one to connect using Wallet Connect", "wp-smart-contracts");
        $atts["login"] = __("Login", "wp-smart-contracts");
        $atts["logout"] = __("Logout", "wp-smart-contracts");
        $atts["network"] = __("Network", "wp-smart-contracts");
        $atts["flavor"] = __("Flavor", "wp-smart-contracts");
        $atts["login-to-unlock"] = __("Login to unlock all the features of your account", "wp-smart-contracts");
        $atts["view"] = __("View", "wp-smart-contracts");
        
        return $atts;        
    }

    static private function sidebarAtts($atts) {

        $atts = self::addLauncherMainNavAtts($atts);

        $permalink = get_permalink();

        if ($permalink == $atts["nft_manage_link"]) $atts["active_nft_manage"] = true;
        if ($permalink == $atts["staking_manage_link"]) $atts["active_staking_manage"] = true;
        if ($permalink == $atts["services_manage_link"]) $atts["active_service_manage"] = true;
        if ($permalink == $atts["token_manage_link"]) $atts["active_token_manage"] = true;

        if ($permalink == $atts["dashboard_link"]) $atts["active_dashboard"] = true;

        if (
            $permalink == $atts["nft_link"] or 
            $permalink == WPSC_assets::getPage('wpsc_is_wizard_deploy_mochi') or
            $permalink == WPSC_assets::getPage('wpsc_is_wizard_deploy_matcha') or
            $permalink == WPSC_assets::getPage('wpsc_is_wizard_deploy_suika') or
            $permalink == WPSC_assets::getPage('wpsc_is_wizard_deploy_yuzu') or
            $permalink == WPSC_assets::getPage('wpsc_is_wizard_deploy_azuki') or
            $permalink == WPSC_assets::getPage('wpsc_is_wizard_deploy_ikasumi')
        ) $atts["active_nft"] = true;

        if (
            $permalink == $atts["staking_link"] or
            $permalink == WPSC_assets::getPage('wpsc_is_wizard_deploy_ube') or
            $permalink == WPSC_assets::getPage('wpsc_is_wizard_deploy_almond')
        ) $atts["active_staking"] = true;

        if (
            $permalink == $atts["services_link"] or
            $permalink == WPSC_assets::getPage('wpsc_is_wizard_deploy_bubblegum')
        ) $atts["active_services"] = true;

        if (
            $permalink == $atts["token_link"] or
            $permalink == WPSC_assets::getPage('wpsc_is_wizard_deploy_vanilla') or
            $permalink == WPSC_assets::getPage('wpsc_is_wizard_deploy_pistachio') or
            $permalink == WPSC_assets::getPage('wpsc_is_wizard_deploy_chocolate') or
            $permalink == WPSC_assets::getPage('wpsc_is_wizard_deploy_macadamia')
        ) $atts["active_token"] = true;

        $logo_data = WPSC_helpers::getLogoAffP();
        $atts["customer-logo"] = $logo_data["logo"];
        $atts["home_url"] = get_home_url();

        return $atts;

    }

    public function launcher($params) {

        $m = new Mustache_Engine;
        $atts["path-to-launcher"] = plugins_url( "launcher/", dirname(__FILE__));

        $section = false;
        if (WPSC_helpers::valArrElement($params, 'section')) {
            $section = $params['section'];
        }

        $atts["dashboard"] = WPSC_assets::getPage('wpsc_is_launcher');
        $atts = self::sidebarAtts($atts);
        $atts["sidebar"] = $m->render(WPSC_Mustache::getTemplate('launcher-sidebar'), $atts);

        $atts["text-smart-contracts"] = __("Discover the power of Smart Contracts", "wp-smart-contracts");
        $atts["text-smart-contracts-type"] = __("Smart Contracts", "wp-smart-contracts");
        $atts["text-smart-contracts-description"] = __("Whether you're looking to create NFTs, implement staking mechanisms, launch crowdfunding campaigns, or develop your own cryptocurrency, our platform offers a variety of flavors and solutions to suit your needs.", "wp-smart-contracts");
        $atts["text-nfts"] = __("NFTs", "wp-smart-contracts");
        $atts["text-gross-sales"] = __("Gross sales", "wp-smart-contracts");
        $atts["text-period"] = __("Period", "wp-smart-contracts");
        $atts["text-see-details"] = __("See details", "wp-smart-contracts");
        $atts["text-see-all"] = __("See all", "wp-smart-contracts");
        $atts["no-smart-contract-found"] = __("No smart contracts found", "wp-smart-contracts");
        $atts["load-more"] = __("Load more", "wp-smart-contracts");
        $atts["text-nft-stats"] = __("NFT Stats", "wp-smart-contracts");
        $atts["text-sales"] = __("Sales", "wp-smart-contracts");
        $atts["text-items"] = __("Items", "wp-smart-contracts");
        $atts["text-customers"] = __("Customers", "wp-smart-contracts");
        $atts["text-latest-activity"] = __("Latest activity", "wp-smart-contracts");
        $atts["text-stakings"] = __("Stakings", "wp-smart-contracts");
        $atts["text-funds-locked"] = __("Funds Locked", "wp-smart-contracts");
        $atts["text-staking-stats"] = __("Staking Stats", "wp-smart-contracts");
        $atts["text-locked"] = __("Locked", "wp-smart-contracts");
        $atts["text-stakers"] = __("Stakers", "wp-smart-contracts");
        $atts["text-staking"] = __("Staking", "wp-smart-contracts");
        $atts["text-nft-collections"] = __("NFT Collections", "wp-smart-contracts");
        $atts["text-period"] = __("Period", "wp-smart-contracts");
        $atts["text-nft-stats"] = __("NFT Stats", "wp-smart-contracts");
        $atts["text-activity"] = __("Activity", "wp-smart-contracts");
        $atts["text-name-symbol"] = __("Name / Symbol", "wp-smart-contracts");
        $atts["text-name"] = __("Name", "wp-smart-contracts");
        $atts["text-network"] = __("Network", "wp-smart-contracts");
        $atts["text-smart-contract"] = __("Smart Contract", "wp-smart-contracts");
        $atts["text-flavor"] = __("Flavor", "wp-smart-contracts");
        $atts["text-switch-to"] = __("Switch to", "wp-smart-contracts");
        $atts["text-stats"] = __("Stats", "wp-smart-contracts");
        $atts["text-crowdfundings"] = __("Crowdfundings", "wp-smart-contracts");
        $atts["text-crowdfunding-stats"] = __("Crowdfunding Stats", "wp-smart-contracts");
        $atts["text-raised"] = __("Raised", "wp-smart-contracts");
        $atts["text-contributors"] = __("Contributors", "wp-smart-contracts");
        $atts["text-tokens"] = __("Tokens", "wp-smart-contracts");
        $atts["text-token"] = __("Token", "wp-smart-contracts");
        $atts["text-funds"] = __("Token Services", "wp-smart-contracts");
        $atts["create-funds"] = __("New Service", "wp-smart-contracts");
        $atts["text-market-cap"] = __("Market Capitalization", "wp-smart-contracts");
        $atts["text-token-stats"] = __("Token Stats", "wp-smart-contracts");
        $atts["text-supply"] = __("Supply", "wp-smart-contracts");
        $atts["text-holders"] = __("Holders", "wp-smart-contracts");
        $atts["text-cta"] = __("Get Started", "wp-smart-contracts");
        $atts["text-cta-link"] = WPSC_assets::getPage('wpsc_is_wizard');
        $atts["list-view"] = __("List view", 'wp-smart-contracts');
        $atts["grid-view"] = __("Grid view", 'wp-smart-contracts');

        switch ($section) {
            case 'nft':
                $atts["icon"] = plugins_url( "launcher/img/smart-contracts-wizard-nft.png", dirname(__FILE__));
                $atts['section-2'] = __("NFT Collections", "wp-smart-contracts");
                $atts["text-smart-contracts-type"] = __("NFT Collections", "wp-smart-contracts");
                $atts["text-cta"] = __("Get Started", "wp-smart-contracts");
                $atts["main-nav"] = $m->render(WPSC_Mustache::getTemplate('launcher-main-nav'), self::addLauncherMainNavAtts($atts));
                $atts["call-to-action"] = $m->render(WPSC_Mustache::getTemplate('launcher-header-cta'), $atts);
                $atts["text"] = __("NFTs", "wp-smart-contracts");
                $atts["smart-contract"] = __("Smart Contract", "wp-smart-contracts");
                $atts["logo"] = plugins_url( "launcher/img/nfts-logo.svg", dirname(__FILE__));
                $atts["launcher-id"] = "launcher-nft-see-all";
                return $m->render(WPSC_Mustache::getTemplate('launcher-tables'), $atts);
                break;
            case 'services':
                $atts["icon"] = plugins_url( "launcher/img/smart-contracts-wizard-services.png", dirname(__FILE__));
                $atts['section-2'] = __("Token Services", "wp-smart-contracts");
                $atts["text-smart-contracts-type"] = __("Token Services", "wp-smart-contracts");
                $atts["text-cta"] = __("Get Started", "wp-smart-contracts");
                $atts["text-smart-contracts-description"] = __("See your recent token services, manage your contracts and check the latest activity", "wp-smart-contracts");
                $atts["main-nav"] = $m->render(WPSC_Mustache::getTemplate('launcher-main-nav'), self::addLauncherMainNavAtts($atts));
                $atts["call-to-action"] = $m->render(WPSC_Mustache::getTemplate('launcher-header-cta'), $atts);
                $atts["text"] = __("Token Services", "wp-smart-contracts");
                $atts["logo"] = plugins_url( "launcher/img/services.png", dirname(__FILE__));
                $atts["launcher-id"] = "launcher-services-see-all";
                return $m->render(WPSC_Mustache::getTemplate('launcher-tables'), $atts);
                break;            
            case 'stakes':
                $atts["icon"] = plugins_url( "launcher/img/smart-contracts-wizard-staking.png", dirname(__FILE__));
                $atts['section-2'] = __("Stakes", "wp-smart-contracts");
                $atts["text-smart-contracts-type"] = __("Stakes", "wp-smart-contracts");
                $atts["text-cta"] = __("Get Started", "wp-smart-contracts");
                $atts["text-smart-contracts-description"] = __("See your recent stakes, manage your contracts and check the latest activity", "wp-smart-contracts");
                $atts["main-nav"] = $m->render(WPSC_Mustache::getTemplate('launcher-main-nav'), self::addLauncherMainNavAtts($atts));
                $atts["call-to-action"] = $m->render(WPSC_Mustache::getTemplate('launcher-header-cta'), $atts);
                $atts["text"] = __("Staking", "wp-smart-contracts");
                $atts["logo"] = plugins_url( "launcher/img/stakings-logo.svg", dirname(__FILE__));
                $atts["launcher-id"] = "launcher-stakes-see-all";
                return $m->render(WPSC_Mustache::getTemplate('launcher-tables'), $atts);
                break;            
            case 'crowd':
                $atts["icon"] = plugins_url( "launcher/img/smart-contracts-wizard-crowd.png", dirname(__FILE__));
                $atts['section-2'] = __("Crowdfunding", "wp-smart-contracts");
                $atts["text-smart-contracts-type"] = __("Crowdfunding", "wp-smart-contracts");
                $atts["text-cta"] = __("Get Started", "wp-smart-contracts");
                $atts["text-smart-contracts-description"] = __("See your recent contributions, manage your contracts and check the latest activity", "wp-smart-contracts");
                $atts["main-nav"] = $m->render(WPSC_Mustache::getTemplate('launcher-main-nav'), self::addLauncherMainNavAtts($atts));
                $atts["call-to-action"] = $m->render(WPSC_Mustache::getTemplate('launcher-header-cta'), $atts);
                $atts["text"] = __("Crowdfundings", "wp-smart-contracts");
                $atts["logo"] = plugins_url( "launcher/img/crowfundings-logo.svg", dirname(__FILE__));
                $atts["launcher-id"] = "launcher-crowd-see-all";
                return $m->render(WPSC_Mustache::getTemplate('launcher-tables'), $atts);
                break;            
            case 'coin':
                $atts["icon"] = plugins_url( "launcher/img/smart-contracts-wizard-tokens.png", dirname(__FILE__));
                $atts['section-2'] = __("Tokens", "wp-smart-contracts");
                $atts["text-smart-contracts-type"] = __("Tokens", "wp-smart-contracts");
                $atts["text-cta"] = __("Get Started", "wp-smart-contracts");
                $atts["text-smart-contracts-description"] = __("Manage your contracts and check the latest activity", "wp-smart-contracts");
                $atts["hide-edit-name"] = true;
                $atts["main-nav"] = $m->render(WPSC_Mustache::getTemplate('launcher-main-nav'), self::addLauncherMainNavAtts($atts));
                $atts["call-to-action"] = $m->render(WPSC_Mustache::getTemplate('launcher-header-cta'), $atts);
                $atts["text"] = __("Tokens", "wp-smart-contracts");
                $atts["logo"] = plugins_url( "launcher/img/tokens-logo.svg", dirname(__FILE__));
                $atts["launcher-id"] = "launcher-coin-see-all";
                return $m->render(WPSC_Mustache::getTemplate('launcher-tables'), $atts);
                break;            
            default:
                $atts["icon"] = plugins_url( "launcher/img/smart-contracts.png", dirname(__FILE__));
                $atts["call-to-action"] = $m->render(WPSC_Mustache::getTemplate('launcher-header-cta'), $atts);
                $atts["main-nav"] = $m->render(WPSC_Mustache::getTemplate('launcher-main-nav'), self::addLauncherMainNavAtts($atts));
                $atts["dashboard-nft"] = WPSC_assets::getPage('wpsc_is_launcher_nft');
                $atts["dashboard-stakes"] = WPSC_assets::getPage('wpsc_is_launcher_stakes');
                $atts["dashboard-crowd"] = WPSC_assets::getPage('wpsc_is_launcher_crowd');
                $atts["dashboard-coin"] = WPSC_assets::getPage('wpsc_is_launcher_coin');
                $atts["dashboard-funds"] = WPSC_assets::getPage('wpsc_is_launcher_services');
                $atts["create-nft"] = __("New Collection", "wp-smart-contracts");
                $atts["create-stake"] = __("New Staking", "wp-smart-contracts");
                $atts["create-coin"] = __("New Token", "wp-smart-contracts");
                $atts["nft_manage_link"] = WPSC_assets::getPage('wpsc_is_wizard_nft');
                $atts["staking_manage_link"] = WPSC_assets::getPage('wpsc_is_wizard_stakes');
                $atts["crowd_manage_link"] = WPSC_assets::getPage('wpsc_is_wizard_crowd');
                $atts["token_manage_link"] = WPSC_assets::getPage('wpsc_is_wizard_coin');
                $atts["services_manage_link"] = WPSC_assets::getPage('wpsc_is_wizard_services');
                $atts["view"] = __("View", "wp-smart-contracts");
                $atts["smart-contract"] = __("Smart Contracts", "wp-smart-contracts");
                $atts["network"] = __("Network", "wp-smart-contracts");
                $atts["flavor"] = __("Flavor", "wp-smart-contracts");
                return $m->render(WPSC_Mustache::getTemplate('launcher'), $atts);
                break;
        }
    }

    private static function addFlavorLinks($atts) {
        foreach(WPSC_helpers::flavors() as $flavor) {
            $atts["network-page-".$flavor] = add_query_arg(["flavor"=>$flavor], $atts["network-page"]);
        }
        return $atts;    
    }

    private static function addLabels($atts) {
        $flavor = $_GET["flavor"];
        if (in_array($flavor, WPSC_helpers::flavors())===false) return $atts;
        $atts["deploy-page"] = WPSC_assets::getPage('wpsc_is_wizard_deploy_' . $flavor);
        switch ($flavor) {
            case 'macadamia':
            case 'chocolate':
            case 'pistachio':
            case 'vanilla':
                $atts["step1-selected"] = __("Cryptocurrency", "wp-smart-contracts");
                break;
            case 'ube':
            case 'almond':
                $atts["step1-selected"] = __("Stakings", "wp-smart-contracts");
                break;
            case 'mango':
                $atts["step1-selected"] = __("Raise funds", "wp-smart-contracts");
                break;
            default:
                $atts["step1-selected"] = __("NFTs", "wp-smart-contracts");
                break;
        }
        $atts["flavor"] = $flavor;
        $atts["Flavor"] = ucfirst($flavor);
        return $atts;
    }

    private static function addDeployAtts($atts) {
        $atts["new-smart-contract"] = __('New', "wp-smart-contracts");
        $atts["load-existing-contract"] = __('Load', "wp-smart-contracts");
        $atts["load-existing-contract-desc"] = __('If you deployed a smart contract, but some error occurred in between, you can manually upload your smart contract. Make sure:', "wp-smart-contracts");
        $atts["load-existing-contract-bullet-1"] = __('You are logged in with the correct administrator account', "wp-smart-contracts");
        $atts["load-existing-contract-bullet-2"] = __('To fill in all the contract details correctly', "wp-smart-contracts");
        $atts["load-existing-contract-bullet-3"] = __('Select the correct network', "wp-smart-contracts");
        $atts["load-existing-contract-bullet-4"] = __('The transaction ID and block hash corresponding to the creation of the contract', "wp-smart-contracts");
        $atts["load"] = __('Load', "wp-smart-contracts");
        $atts["smart-contract-address"] = __('Smart contract address', "wp-smart-contracts");
        $atts["deploy-selected-net"] = __('Deploy to the selected network', "wp-smart-contracts");
        $atts["beta"] = __('Terms of Service (TOS)', "wp-smart-contracts");
        $atts["accept-tos"] = __('By ticking this box I confirm that I have read, consent and agree to the Terms of Service (TOS)', "wp-smart-contracts");
        $atts["accept-legal-age"] = __('By ticking this box I confirm that I am of legal age', "wp-smart-contracts");
        $atts["deploy"] = __('DEPLOY', "wp-smart-contracts");
        $atts["help"] = __('Need help?', "wp-smart-contracts");
        $atts["docs"] = __('Read the documentation', "wp-smart-contracts");
        $atts["smart-contract-address"] = __('Smart contract address', "wp-smart-contracts");
        $atts["transaction-id"] = __('Transaction Hash', "wp-smart-contracts");
        $atts["block-hash"] = __('Block hash', "wp-smart-contracts");
        $atts["tos"] = WPSC_helpers::tos();
        return $atts;
    }

    private static function addStakingAtts($atts) {
        $atts["step1"] = __("Stakings", "wp-smart-contracts");
        $atts["crypto-options"] = __("Staking options", "wp-smart-contracts");
        $atts["white-cog"] = plugins_url( "launcher/", dirname(__FILE__)) . "img/white-cog.png";
        $atts["deploy"] = __('DEPLOY', "wp-smart-contracts");
        $atts["help"] = __('Need help?', "wp-smart-contracts");
        $atts["docs"] = __('Read the documentation', "wp-smart-contracts");
        $atts["token"] = __('Token Address', "wp-smart-contracts");
        $atts["token-desc"] = __('ERC-20 or BEP-20 token address to stake', "wp-smart-contracts");
        $atts["token-tip"] = __('This is the token that holders can stake', "wp-smart-contracts");
        $atts["token2"] = __('Secondary Token', 'wp-smart-contracts');
        $atts["token2-desc"] = __('ERC-20 or BEP-20 token to gain interest', 'wp-smart-contracts');
        $atts["token2-tip"] = __('Your users can stake the first token and get interest in the first token (optional) and interest in the second token', 'wp-smart-contracts');
        $atts["apy2"] = __('APY Secondary Token', 'wp-smart-contracts');
        $atts["apy2-desc"] = __('Annual interest rate for the second token', 'wp-smart-contracts');
        $atts["apy2-tip"] = __('You can offer an annual interest rate in both or in one of the tokens', 'wp-smart-contracts');
        $atts["apy"] = __('APY Staking Token', "wp-smart-contracts");
        $atts["apy-desc"] = __('Annual interest rate', "wp-smart-contracts");
        $atts["minimum"] = __('Minimum Stake Time (in days)', "wp-smart-contracts");
        $atts["minimum-desc"] = __('Minimum time for the Stake to avoid penalties', "wp-smart-contracts");
        $atts["minimum-tip"] = __('If you define a penalty, then this is the minimum time the stake should be active. The time is specified in days', "wp-smart-contracts");
        $atts["penalization"] = __('Penalization (optional)', "wp-smart-contracts");
        $atts["penalization-desc"] = __('Percentage of penalization to charge to users that withdraw early', "wp-smart-contracts");
        $atts["penalization-tip"] = __('If your users end the stake before the minimum stake time, this percentage will be deducted from total withdraw', "wp-smart-contracts");
        $atts["min-amount"] = __('Minimum Stake Amount', "wp-smart-contracts");
        $atts["min-amount-desc"] = __('This is the minimum number of tokens to create a Stake', "wp-smart-contracts");
        $atts["namestake"] = __('Name', "wp-smart-contracts");
        $atts["namestake-desc"] = __('Assign a name to your Stake (will be visible to your users)', "wp-smart-contracts");
        $atts["load"] = __("Load", "wp-smart-contracts");
        $atts = self::addDeployAtts($atts);
        return $atts;
    }

    private static function addICOAtts($atts) {

        $atts["step1"] = __("Token Services", "wp-smart-contracts");
        $atts["crypto-options"] = __("ICO Options", "wp-smart-contracts");
        $atts["white-cog"] = plugins_url( "launcher/", dirname(__FILE__)) . "img/white-cog.png";
        $atts["deploy"] = __('DEPLOY', "wp-smart-contracts");
        $atts["help"] = __('Need help?', "wp-smart-contracts");
        $atts["docs"] = __('Read the documentation', "wp-smart-contracts");
        $atts["token"] = __('Token Address', "wp-smart-contracts");
        $atts["token-desc"] = __('Address of the token you want to sell. It has to be an ERC-20 Token address. Otherwise it will fail.', "wp-smart-contracts");
        
        $atts["token-tip"] = __('If you have a token of your own, you can run an ICO with it.', "wp-smart-contracts");
        
        $atts["rate"] = __('Token distribution rate', "wp-smart-contracts");
        $atts["rate-desc"] = __('How many tokens you want to give for every coin received.', "wp-smart-contracts");
        $atts["rate-tip"] = __('For example, if you want to sell 1000 tokens for each Ether received, then the rate is 1000', "wp-smart-contracts");
        $atts["wallet"] = __('Wallet', "wp-smart-contracts");
        $atts["wallet-desc"] = __('Ethereum address or EVM compatible wallet address to receive funds', "wp-smart-contracts");
        $atts["wallet-dist"] = __('Distribution Wallet', "wp-smart-contracts");
        $atts["wallet-dist-desc"] = __('Address holding the tokens, which has approved allowance to the crowdsale', "wp-smart-contracts");

        $atts["namestake"] = __('Name', "wp-smart-contracts");
        $atts["namestake-desc"] = __('Assign a name to your ICO (will be visible to your users)', "wp-smart-contracts");
        $atts["load"] = __("Load", "wp-smart-contracts");
        $atts = self::addDeployAtts($atts);

        return $atts;

    }

    private static function addCrowdAtts($atts) {
        $atts["step1"] = __("Crowdfundings", "wp-smart-contracts");
        $atts["crypto-options"] = __("Crowdfunding options", "wp-smart-contracts");
        $atts["white-cog"] = plugins_url( "launcher/", dirname(__FILE__)) . "img/white-cog.png";
        $atts["deploy"] = __('DEPLOY', "wp-smart-contracts");
        $atts["help"] = __('Need help?', "wp-smart-contracts");
        $atts["docs"] = __('Read the documentation', "wp-smart-contracts");
        $atts["token"] = __('Token Address', "wp-smart-contracts");
        $atts["mincon"] = __('Minimum contribution', "wp-smart-contracts");
        $atts["mincon-desc"] = __('The crowdfunding will not accept contributions for less than this minimum', "wp-smart-contracts");
        $atts["mincon-tip"] = __('Minimum contributions are set in Ethers or BNB or xDai, etc, depending og the selected network.', "wp-smart-contracts");
        $atts["namecamp"] = __('Name of the Campaign', "wp-smart-contracts");
        $atts["namecamp-desc"] = __('Descriptive name of the campaign.', "wp-smart-contracts");
        $atts["perc"] = __('Percentage of approvers required', "wp-smart-contracts");
        $atts["perc-desc"] = __('% of approvers required to release a request of the owner', "wp-smart-contracts");
        $atts["perc-tip"] = __('Owner can\'t release the funds unless this minimum percentage of contributors approves the transfer.', "wp-smart-contracts");
        $atts["load"] = __("Load", "wp-smart-contracts");
        $atts = self::addDeployAtts($atts);
        return $atts;

    }

    private static function addCryptoAtts($atts) {
        $atts["step1"] = __("Cryptocurrency", "wp-smart-contracts");
        $atts["crypto-options"] = __("Cryptocurrency options", "wp-smart-contracts");
        $atts["white-cog"] = plugins_url( "launcher/", dirname(__FILE__)) . "img/white-cog.png";
        $atts["supply"] = __("Initial Supply*", "wp-smart-contracts");
        $atts["supply-desc"] = __('The initial amount of coins for your contract.', "wp-smart-contracts");
        $atts["symbol"] = __('Symbol*', "wp-smart-contracts");
        $atts["symbol-desc"] = __('The symbol of the coin. Keep it short - e.g. "HIX"', "wp-smart-contracts");
        $atts["decimals"] = __('Decimals*', "wp-smart-contracts");
        $atts["decimals-desc"] = __('The number of decimals the coin uses', "wp-smart-contracts");
        $atts["decimals-fixed"] = __('For this flavor is fixed to 18', "wp-smart-contracts");
        $atts["reflection"] = __('Reflection percentage fee', "wp-smart-contracts");
        $atts["reflection-desc"] = __('The fee that every user will pay when transfering tokens', "wp-smart-contracts");
        $atts["reflection-tip"] = __('The fee can range from 1% to 20% for every transfer. All users will pay this fee, and every -not excluded- holder will receive a commission automatically.', "wp-smart-contracts");
        $atts["name"] = __('Name*', "wp-smart-contracts");
        $atts["name-desc"] = __('The name of the coin', "wp-smart-contracts");
        $atts["name-tip"] = __('By default the post title will be used if not defined here. Once the contract is deployed this name will be frozen', "wp-smart-contracts");
        $atts["supply-tip"] = __("The initial supply is the total amount of coins that your contract will have at the moment of creation. The amount is integer, do not include decimal representation or wei like numbers. This is going to be also the initial balance of the creator's account.", "wp-smart-contracts");
        $atts["load"] = __("Load", "wp-smart-contracts");
        $atts = self::addDeployAtts($atts);
        return $atts;
    }

    private static function addNFTAtts($atts) {
        $atts["step1"] = __("NFT Collections", "wp-smart-contracts");
        $atts["crypto-options"] = __("NFT collection options", "wp-smart-contracts");
        $atts["white-cog"] = plugins_url( "launcher/", dirname(__FILE__)) . "img/white-cog.png";
        $atts["symbol"] = __('Symbol*', "wp-smart-contracts");
        $atts["symbol-desc"] = __('The symbol of the collection. Keep it short - e.g. "HIX"', "wp-smart-contracts");
        $atts["name"] = __('Name*', "wp-smart-contracts");
        $atts["name-desc"] = __('The name of the coin', "wp-smart-contracts");
        $atts["name-tip"] = __('By default the post title will be used if not defined here. Once the contract is deployed this name will be frozen', "wp-smart-contracts");
        $atts["only-minter"] = __('Only minter can mint', "wp-smart-contracts");
        $atts["only-creator"] = __('Only original creator of the Item can mint', "wp-smart-contracts");
        $atts["anyone-can-mint"] = __('Anyone can arbitrarily mint', "wp-smart-contracts");
        $atts["mint"] = __('Who can mint?', "wp-smart-contracts");
        $atts["mint-desc"] = __('Minting permissions', "wp-smart-contracts");
        $atts["mint-tip"] = __('Only minter can mint" is the most restrictive. "Only original creator" allows anyone to mint, but once minted only creator can mint its own items. And "Anyone can arbitrarily mint" is the most open option (not recommended)', "wp-smart-contracts");
        $atts["pixel"] = __('Use pixelated images', "wp-smart-contracts");
        $atts["pixel-desc"] = __('Set this to use a pixelated image in the image gallery', "wp-smart-contracts");
        $atts["pixel-tip"] = __('By default, each browser will render images using aliasing to a scaled image in order to prevent distortion. Check this if you want the image to preserve its original pixelated form.', "wp-smart-contracts");
        $atts["sales"]  = __('Sales commissions', 'wp-smart-contracts');
        $atts["sales-desc"]  = __('Percentage commission, ranging from 0 to 100 ', 'wp-smart-contracts');
        $atts["sales-tip"]  = __('Commission that you are going to get from Sales. 0 means no commission, 100 means 100% of the sale as commission.', 'wp-smart-contracts');
        $atts["wallet"]  = __('Wallet', 'wp-smart-contracts');
        $atts["wallet-desc"]  = __('Ethereum address or EVM compatible wallet address to receive funds', 'wp-smart-contracts');
        $atts["wallet-tip"]  = __('The beneficiary account that will receive the Marketplace commissions in Ether, BNB, xDai or Matic', 'wp-smart-contracts');
        $atts["royalty"]  = __('Royalty percentage for creators', 'wp-smart-contracts');
        $atts["royalty-desc"]  = __('Percentage royalty, ranging from 0 to 100', 'wp-smart-contracts');
        $atts["royalty-tip"]  = __('Royalties to the creators from resales. 0 means no commission, 100 means 100% of the sale as commission.', 'wp-smart-contracts');
        $atts["payments"]  = __('Token for payments', 'wp-smart-contracts');
        $atts["payments-desc"]  = __('Standard ERC-20 or BEP20 token to be used for payment of sales', 'wp-smart-contracts');
        $atts["payments-tip"] = __('Token used for all payments, commissions and royalties', 'wp-smart-contracts');        
        $atts["load"] = __("Load", "wp-smart-contracts");
        $atts["tos"] = WPSC_helpers::tos();
        $atts = self::addDeployAtts($atts);
        return $atts;
    }

    // check if the user is trying to edit a post on the launcher
    private function checkEdition($atts, $meta) {

        $id = isset($_GET["id"])?(int) $_GET["id"]:false;
        $account = isset($_GET["add"])?sanitize_text_field($_GET["add"]):false;
    
        if (!$id or !$account) return $atts;
    
        $atts["permalink_post"] = get_permalink($id);
        
        $owner = get_post_meta($id, "wpsc_owner", true);
        if ($owner!=$account) return $atts;

        // the owner and the ID corresponds
        $atts["is_edit"] = true;
        $atts["title"] = get_the_title($id);
        $atts["id"] = $id;
        $atts["account"] = $account;
        $atts["wpsc_network"] = get_post_meta($id, "wpsc_network", true);
        $atts["wpsc_contract_address"] = get_post_meta($id, "wpsc_contract_address", true);
        
        foreach($meta as $meta_key) {
            $atts[$meta_key] = get_post_meta($id, $meta_key, true);
            if ($meta_key == "wpsc_pixelated_images") {
                $atts["wps-wallet-dist"] = get_post_meta($id, $meta_key, true);
            }
            if ($meta_key == "wpsc_pixelated_images") {
                if ($atts[$meta_key]) {
                    $atts[$meta_key] = "on";
                } else {
                    $atts[$meta_key] = "";
                }
            }
            if ($meta_key == "wpsc_anyone_can_mint") {
                if ($atts["wpsc_anyone_can_mint"]=="0") $atts["wpsc_anyone_can_mint_0"] = true;
                if ($atts["wpsc_anyone_can_mint"]=="1") $atts["wpsc_anyone_can_mint_1"] = true;
                if ($atts["wpsc_anyone_can_mint"]=="2") $atts["wpsc_anyone_can_mint_2"] = true;    
            }
        }

        return $atts;
    
    }

    public function wizard($params) {

        $m = new Mustache_Engine;

        $atts["path-to-launcher"] = plugins_url( "launcher/", dirname(__FILE__));
        $atts["dashboard"] = WPSC_assets::getPage('wpsc_is_launcher');
        $atts["wizard"] = WPSC_assets::getPage('wpsc_is_wizard');
        $atts = self::sidebarAtts($atts);
        $atts["sidebar"] = $m->render(WPSC_Mustache::getTemplate('launcher-sidebar'), $atts);
        $atts["path-to-launcher"] = plugins_url( "launcher/", dirname(__FILE__));
        $atts["dashboard-page"] = WPSC_assets::getPage('wpsc_is_launcher');
        $atts["text-smart-contracts"] = __("Smart Contracts", "wp-smart-contracts");

        $atts["what"] = __("What?", "wp-smart-contracts");
        $atts["choose-sc"] = __("Choose a Smart Contract", "wp-smart-contracts");
        $atts["how"] = __("How?", "wp-smart-contracts");
        $atts["choose-flavor"] = __("Choose a Flavor", "wp-smart-contracts");
        $atts["where"] = __("Where?", "wp-smart-contracts");
        $atts["choose-network"] = __("Choose a Network", "wp-smart-contracts");
        $atts["choose-type-sc"] = __("Choose the type of Smart Contract you want", "wp-smart-contracts");
        $atts["choose-flavor-title"] = __("Choose the flavor of the Smart Contract that better suits your needs", "wp-smart-contracts");
        $atts["need-help"] = __("Need help?", "wp-smart-contracts");
        $atts["read-docs"] = __("Read the documentation", "wp-smart-contracts");

        $atts["nft-button"] = __("Create a Collection", "wp-smart-contracts");
        $atts["nft-img"] = dirname( plugin_dir_url( __FILE__ ) ) . '/assets/img/nfts.png';

        $atts["staking-button"] = __("Create a Staking", "wp-smart-contracts");
        $atts["staking-img"] = dirname( plugin_dir_url( __FILE__ ) ) . '/assets/img/stakings.png';

        $atts["crypto-button"] = __("Create a Coin", "wp-smart-contracts");
        $atts["crypto-img"] = dirname( plugin_dir_url( __FILE__ ) ) . '/assets/img/coins.png';
        
        $atts["crowd-button"] = __("Raise funds", "wp-smart-contracts");
        $atts["crowd-img"] = dirname( plugin_dir_url( __FILE__ ) ) . '/assets/img/icos.png';

        $atts["more-info"] = __("More Info", "wp-smart-contracts");
        $atts["select"] = __("Select", "wp-smart-contracts");
        $atts["network-page"] = WPSC_assets::getPage("wpsc_is_wizard_network");

        $atts["deploy"] = __("Deploy", "wp-smart-contracts");
        $atts["deploy-desc"] = __("Finish & Deploy", "wp-smart-contracts");

        $section = false;
        if (WPSC_helpers::valArrElement($params, 'section')) {
            $section = $params['section'];
        }

        $atts["banner-wizard-subtitle"] = __("Easily create Smart Contracts in just four simple steps", "wp-smart-contracts");

        $path = plugins_url( "launcher/", dirname(__FILE__));
        $atts["cards"] = $m->render(WPSC_Mustache::getTemplate('wizard-cards'), 
            [
                "select" => __("Select", "wp-smart-contracts"),
                "path-to-launcher" => $path,
                "cards" => [
                    [
                        "logo" => $path . "img/nfts-logo.svg",
                        "title" => __("NFT", "wp-smart-contracts"),
                        "desc" => __("Create an NFT collection of your own collectibles or allow your users to create and trade NFT items", "wp-smart-contracts"),
                        "tags" => [
                            "ERC-721", "ERC-1155"
                        ],
                        "link" => WPSC_assets::getPage('wpsc_is_wizard_nft')
                    ],
                    [
                        "logo" => $path . "img/stakings-logo.svg",
                        "title" => __("Stakings", "wp-smart-contracts"),
                        "desc" => __("Create a Staking contract for ERC-20 or BEP-20 compliant tokens. Let token holders earn interest by Staking", "wp-smart-contracts"),
                        "tags" => [__("Single token", "wp-smart-contracts"), __("Multi-token", "wp-smart-contracts")],
                        "link" => WPSC_assets::getPage('wpsc_is_wizard_stakes')
                    ],
                    [
                        "logo" => $path . "img/services.png",
                        "title" => __("Token Services", "wp-smart-contracts"),
                        "desc" => __("Launch ICOs, establish secure funds in a Vault, and effortlessly manage Airdrop distributions", "wp-smart-contracts"),
                        "tags" => [__("ICO", "wp-smart-contracts"), __("Safe Vault", "wp-smart-contracts"), __("Airdrop", "wp-smart-contracts")],
                        "link" => WPSC_assets::getPage('wpsc_is_wizard_services')
                    ],
                    [
                        "logo" => $path . "img/tokens-logo.svg",
                        "title" => __("Cryptocurrency", "wp-smart-contracts"),
                        "desc" => __("Create your own ERC-20 or BEP-20 compliant token for your business or personal projects", "wp-smart-contracts"),
                        "tags" => [
                            __("ERC-20", "wp-smart-contracts"),
                            __("BEP-20", "wp-smart-contracts"),
                            __("Reflection", "wp-smart-contracts"),
                        ],
                        "link" => WPSC_assets::getPage('wpsc_is_wizard_coin')
                    ]
                ]
            ]
        );

        $network_page = WPSC_assets::getPage("wpsc_is_wizard_network");

        $atts['wps-step-text'] = __("Please complete all the required fields in the form according to your needs", "wp-smart-contracts");
        $atts['load'] = __("Load", "wp-smart-contracts");
        $atts['symbol-label'] = __("Symbol", "wp-smart-contracts");
        $atts['name-label'] = __("Name", "wp-smart-contracts");

        switch ($section) {
            // step 4
            case 'deploy-vanilla':
                $atts["step1"] = __("Cryptocurrency", "wp-smart-contracts");
                $atts['text-three-steps'] = __("Vanilla: Gas-Saving ERC-20/BEP-20 Token", "wp-smart-contracts");
                $atts['text-three-steps-desc'] = __("Create a digital asset with simplifed transactions and save on gas fees with Vanilla, an ERC-20/BEP-20 compliant smart contract.", "wp-smart-contracts");
                $atts["icon"] = plugins_url("launcher/img/smart-contracts-wizard-ice-cream.png", dirname(__FILE__));
                $atts["banner-wizard"] = $m->render(WPSC_Mustache::getTemplate('wizard-banner'), $atts);
                $atts["step2"] = __("Vanilla", "wp-smart-contracts");
                $atts["subheader-step4"] = $m->render(WPSC_Mustache::getTemplate('subheader-step4'), $atts);
                $atts["load"] = __("Load", "wp-smart-contracts");
                $atts = self::addCryptoAtts($atts);
                $atts["deploy-section"] = $m->render(WPSC_Mustache::getTemplate('deploy-section'), $atts);
                $atts['section-2'] = __("Wizard", "wp-smart-contracts");
                $atts['section-3'] = __("Cryptocurrencies", "wp-smart-contracts");
                $atts["main-nav"] = $m->render(WPSC_Mustache::getTemplate('launcher-main-nav'), self::addLauncherMainNavAtts($atts));
                return $m->render(
                    WPSC_Mustache::getTemplate('wizard-deploy'), 
                    array_merge(
                        $atts,
                        ["flavor"=>"vanilla"],
                        ["form-fields" => $m->render(
                            WPSC_Mustache::getTemplate('wizard-deploy-vanilla'),
                            $atts
                        )]
                    )
                );
                break;            
            case 'deploy-pistachio':
                $atts["step1"] = __("Cryptocurrency", "wp-smart-contracts");
                $atts['text-three-steps'] = __("Pistachio: Enhanced Security for ERC-20/BEP-20 Tokens", "wp-smart-contracts");
                $atts['text-three-steps-desc'] = __("Elevate the security of your ERC-20/BEP-20 tokens with Pistachio. Based on Open Zeppelin implementation with standard methods, and extra validations for peace of mind.", "wp-smart-contracts");
                $atts["icon"] = plugins_url("launcher/img/smart-contracts-wizard-ice-cream.png", dirname(__FILE__));
                $atts["banner-wizard"] = $m->render(WPSC_Mustache::getTemplate('wizard-banner'), $atts);
                $atts["step2"] = __("Pistachio", "wp-smart-contracts");
                $atts["subheader-step4"] = $m->render(WPSC_Mustache::getTemplate('subheader-step4'), $atts);
                $atts = self::addCryptoAtts($atts);
                $atts["load"] = __("Load", "wp-smart-contracts");
                $atts["deploy-section"] = $m->render(WPSC_Mustache::getTemplate('deploy-section'), $atts);
                $atts['section-2'] = __("Wizard", "wp-smart-contracts");
                $atts['section-3'] = __("Cryptocurrencies", "wp-smart-contracts");
                $atts["main-nav"] = $m->render(WPSC_Mustache::getTemplate('launcher-main-nav'), self::addLauncherMainNavAtts($atts));
                return $m->render(
                    WPSC_Mustache::getTemplate('wizard-deploy'), 
                    array_merge(
                        $atts,
                        ["flavor"=>"pistachio"],
                        ["form-fields" => $m->render(
                            WPSC_Mustache::getTemplate('wizard-deploy-pistachio'),
                            $atts
                        )]
                    )
                );
                break;
            case 'deploy-chocolate':
                $atts["step1"] = __("Cryptocurrency", "wp-smart-contracts");
                $atts['text-three-steps'] = __("Chocolate: Advanced Features for Secure Tokens", "wp-smart-contracts");
                $atts['text-three-steps-desc'] = __("Enhance your ERC-20/BEP-20 tokens with Chocolate. It provides advanced functionalities such as burning, minting, and pausing/unpausing token activities for added flexibility and security.", "wp-smart-contracts");
                $atts["icon"] = plugins_url("launcher/img/smart-contracts-wizard-ice-cream.png", dirname(__FILE__));
                $atts["banner-wizard"] = $m->render(WPSC_Mustache::getTemplate('wizard-banner'), $atts);
                $atts["step2"] = __("Chocolate", "wp-smart-contracts");
                $atts["subheader-step4"] = $m->render(WPSC_Mustache::getTemplate('subheader-step4'), $atts);
                $atts["burnable"] = __("Burnable", "wp-smart-contracts");
                $atts["burnable-desc"] = __("Ability to irreversibly burn (destroy) coins you own. ", "wp-smart-contracts");
                $atts["mintable"] = "Mintable Cap";
                $atts["mintable-desc"] = "This will be the maximum supply that the coin can reach when minting";
                $atts["mintable-tip"] = "The maximum capitalization your coin can have. This is an integer number, do not include decimal representation or wei like numbers. 0 cap means unlimited capitalization.";
                $atts = self::addCryptoAtts($atts);
                $atts["deploy-section"] = $m->render(WPSC_Mustache::getTemplate('deploy-section'), $atts);
                $atts['section-2'] = __("Wizard", "wp-smart-contracts");
                $atts['section-3'] = __("Cryptocurrencies", "wp-smart-contracts");
                $atts["main-nav"] = $m->render(WPSC_Mustache::getTemplate('launcher-main-nav'), self::addLauncherMainNavAtts($atts));
                return $m->render(
                    WPSC_Mustache::getTemplate('wizard-deploy'), 
                    array_merge(
                        $atts,
                        ["flavor"=>"chocolate"],
                        ["form-fields" => $m->render(
                            WPSC_Mustache::getTemplate('wizard-deploy-chocolate'),
                            $atts
                        )]
                    )
                );
                break;            
            case 'deploy-macadamia':
                $atts["step1"] = __("Cryptocurrency", "wp-smart-contracts");
                $atts['text-three-steps'] = __("Macadamia: Advanced Reflection Token for DeFi Efficiency", "wp-smart-contracts");
                $atts['text-three-steps-desc'] = __("Macadamia is an innovative ERC-20 smart contract, offering reflection mechanics and advanced features for efficient and secure token transfers in the DeFi ecosystem.", "wp-smart-contracts");
                $atts["icon"] = plugins_url("launcher/img/smart-contracts-wizard-ice-cream.png", dirname(__FILE__));
                $atts["banner-wizard"] = $m->render(WPSC_Mustache::getTemplate('wizard-banner'), $atts);
                $atts["step2"] = __("Macadamia", "wp-smart-contracts");
                $atts["subheader-step4"] = $m->render(WPSC_Mustache::getTemplate('subheader-step4'), $atts);
                $atts = self::addCryptoAtts($atts);
                $atts["deploy-section"] = $m->render(WPSC_Mustache::getTemplate('deploy-section'), $atts);
                $atts['section-2'] = __("Wizard", "wp-smart-contracts");
                $atts['section-3'] = __("Cryptocurrencies", "wp-smart-contracts");
                $atts["main-nav"] = $m->render(WPSC_Mustache::getTemplate('launcher-main-nav'), self::addLauncherMainNavAtts($atts));
                return $m->render(
                    WPSC_Mustache::getTemplate('wizard-deploy'), 
                    array_merge(
                        $atts,
                        ["flavor"=>"macadamia"],
                        ["form-fields" => $m->render(
                            WPSC_Mustache::getTemplate('wizard-deploy-macadamia'),
                            $atts
                        )]
                    )
                );
                break;
            case 'deploy-ube':
                $atts['text-three-steps'] = __("Ube: Reward your community with Staking Smart Contracts", "wp-smart-contracts");
                $atts['text-three-steps-desc'] = __("Ube is an advanced ERC-20/BEP-20 staking smart contract that allows token holders to earn interest by staking their tokens.", "wp-smart-contracts");
                $atts["icon"] = plugins_url("launcher/img/smart-contracts-wizard-ice-cream.png", dirname(__FILE__));
                $atts["banner-wizard"] = $m->render(WPSC_Mustache::getTemplate('wizard-banner'), $atts);
                $atts["step2"] = __("Ube", "wp-smart-contracts");
                $atts = self::addStakingAtts($atts);
                $atts["deploy-section"] = $m->render(WPSC_Mustache::getTemplate('deploy-section'), $atts);
                $atts["subheader-step4"] = $m->render(WPSC_Mustache::getTemplate('subheader-step4'), $atts);
                $atts['section-2'] = __("Wizard", "wp-smart-contracts");
                $atts['section-3'] = __("Stakings", "wp-smart-contracts");
                $atts["main-nav"] = $m->render(WPSC_Mustache::getTemplate('launcher-main-nav'), self::addLauncherMainNavAtts($atts));
                $atts = self::checkEdition($atts, ["wpsc_token", "wpsc_apy", "wpsc_mst", "wpsc_penalty", "wpsc_minimum"]);
                return $m->render(
                    WPSC_Mustache::getTemplate('wizard-deploy'), 
                    array_merge(
                        $atts,
                        ["flavor"=>"ube"],
                        ["form-fields" => $m->render(
                            WPSC_Mustache::getTemplate('wizard-deploy-ube'),
                            $atts
                        )]
                    )
                );
                break;            
            case 'deploy-bubblegum':
                $atts['text-three-steps'] = __("Bubblegum: Run an ICO using your own ERC-20 compatible token", "wp-smart-contracts");
                $atts['text-three-steps-desc'] = __("Bubblegum Crowdsale is a robust and versatile smart contract designed to facilitate token sales in a secure and transparent manner.", "wp-smart-contracts");
                $atts["icon"] = plugins_url("launcher/img/smart-contracts-wizard-ice-cream.png", dirname(__FILE__));
                $atts["banner-wizard"] = $m->render(WPSC_Mustache::getTemplate('wizard-banner'), $atts);
                $atts["step2"] = __("Bubblegum", "wp-smart-contracts");
                $atts = self::addICOAtts($atts);
                $atts["deploy-section"] = $m->render(WPSC_Mustache::getTemplate('deploy-section'), $atts);
                $atts["subheader-step4"] = $m->render(WPSC_Mustache::getTemplate('subheader-step4'), $atts);
                $atts['section-2'] = __("Wizard", "wp-smart-contracts");
                $atts['section-3'] = __("Services", "wp-smart-contracts");
                $atts["main-nav"] = $m->render(WPSC_Mustache::getTemplate('launcher-main-nav'), self::addLauncherMainNavAtts($atts));
                $atts = self::checkEdition($atts, ["wpsc_token", "wpsc_rate", "wpsc_wallet", "wpsc_distribution_wallet"]);
                return $m->render(
                    WPSC_Mustache::getTemplate('wizard-deploy'), 
                    array_merge(
                        $atts,
                        ["flavor"=>"bubblegum"],
                        ["form-fields" => $m->render(
                            WPSC_Mustache::getTemplate('wizard-deploy-bubblegum'),
                            $atts
                        )]
                    )
                );
                break;            
            case 'deploy-almond':
                $atts['text-three-steps'] = __("Almond: Advanced Staking with Dual Token Rewards", "wp-smart-contracts");
                $atts['text-three-steps-desc'] = __("Experience advanced staking with Almond, an ERC-20/BEP-20 smart contract that allows your community to earn interest on both, the staked token and a secondary token.", "wp-smart-contracts");
                $atts["icon"] = plugins_url("launcher/img/smart-contracts-wizard-ice-cream.png", dirname(__FILE__));
                $atts["banner-wizard"] = $m->render(WPSC_Mustache::getTemplate('wizard-banner'), $atts);
                $atts["step2"] = __("Almond", "wp-smart-contracts");
                $atts = self::addStakingAtts($atts);
                $atts["deploy-section"] = $m->render(WPSC_Mustache::getTemplate('deploy-section'), $atts);
                $atts["subheader-step4"] = $m->render(WPSC_Mustache::getTemplate('subheader-step4'), $atts);
                $atts['section-2'] = __("Wizard", "wp-smart-contracts");
                $atts['section-3'] = __("Stakings", "wp-smart-contracts");
                $atts["main-nav"] = $m->render(WPSC_Mustache::getTemplate('launcher-main-nav'), self::addLauncherMainNavAtts($atts));
                $atts = self::checkEdition($atts, ["wpsc_token", "wpsc_apy", "wpsc_token2", "wpsc_apy2", "wpsc_mst", "wpsc_penalty", "wpsc_minimum"]);
                return $m->render(
                    WPSC_Mustache::getTemplate('wizard-deploy'), 
                    array_merge(
                        $atts,
                        ["flavor"=>"almond"],
                        ["form-fields" => $m->render(
                            WPSC_Mustache::getTemplate('wizard-deploy-almond'),
                            $atts
                        )]
                    )
                );
                break;            
            case 'deploy-mango':
                $atts['text-three-steps'] = __("Mango: Empower Your Ideas with Crowdfunding", "wp-smart-contracts");
                $atts['text-three-steps-desc'] = __("Transform your dreams into reality using Crowdfunding smart contracts. Get started with the Mango flavor and raise funds for your projects or ventures.", "wp-smart-contracts");
                $atts["icon"] = plugins_url("launcher/img/smart-contracts-wizard-ice-cream.png", dirname(__FILE__));
                $atts["banner-wizard"] = $m->render(WPSC_Mustache::getTemplate('wizard-banner'), $atts);
                $atts["step2"] = __("Mango", "wp-smart-contracts");
                $atts = self::addCrowdAtts($atts);
                $atts["deploy-section"] = $m->render(WPSC_Mustache::getTemplate('deploy-section'), $atts);
                $atts["subheader-step4"] = $m->render(WPSC_Mustache::getTemplate('subheader-step4'), $atts);
                $atts['section-2'] = __("Wizard", "wp-smart-contracts");
                $atts['section-3'] = __("Crowdfundings", "wp-smart-contracts");
                $atts["main-nav"] = $m->render(WPSC_Mustache::getTemplate('launcher-main-nav'), self::addLauncherMainNavAtts($atts));
                $atts = self::checkEdition($atts, ["wpsc_minimum", "wpsc_approvers"]);
                if (isset($atts["wpsc_approvers"])) {
                    $atts["is_".$atts["wpsc_approvers"]] = true;
                }
                return $m->render(
                    WPSC_Mustache::getTemplate('wizard-deploy'), 
                    array_merge(
                        $atts,
                        ["flavor"=>"mango"],
                        ["form-fields" => $m->render(
                            WPSC_Mustache::getTemplate('wizard-deploy-mango'),
                            $atts
                        )]
                    )
                );
                break;            
            case 'deploy-ikasumi':
                $atts['text-three-steps'] = __("Ikasumi Advanced NFT ERC-1155 Marketplace", "wp-smart-contracts");                
                $atts['text-three-steps-desc'] = __("Ikasumi is an ERC-1155 smart contract with advanced features like lazy minting, token payments, and a full-featured marketplace, empowering creators to manage and trade diverse NFT collections.", "wp-smart-contracts");
                $atts["icon"] = plugins_url("launcher/img/smart-contracts-wizard-ice-cream.png", dirname(__FILE__));
                $atts["tags"] = [
                    "ERC-1155",
                    __("Marketplace ", "wp-smart-contracts"),
                    __("Royalties ", "wp-smart-contracts"),
                    __("Lazy Minting", "wp-smart-contracts"),
                    __("Token Payments", "wp-smart-contracts"),
                ];
                $atts["banner-wizard"] = $m->render(WPSC_Mustache::getTemplate('wizard-banner'), $atts);
                $atts["step2"] = __("Ikasumi", "wp-smart-contracts");
                $atts = self::addNFTAtts($atts);
                $atts["deploy-section"] = $m->render(WPSC_Mustache::getTemplate('deploy-section'), $atts);
                $atts["subheader-step4"] = $m->render(WPSC_Mustache::getTemplate('subheader-step4'), $atts);
                $atts['section-2'] = __("Wizard", "wp-smart-contracts");
                $atts['section-3'] = __("NFT Collections", "wp-smart-contracts");
                $atts["main-nav"] = $m->render(WPSC_Mustache::getTemplate('launcher-main-nav'), self::addLauncherMainNavAtts($atts));
                $atts = self::checkEdition($atts, ["wpsc_name", "wpsc_symbol", "wpsc_anyone_can_mint", "wpsc_pixelated_images", "wpsc_royalties", "wpsc_token", "wpsc_commission", "wpsc_wallet"]);
                return $m->render(
                    WPSC_Mustache::getTemplate('wizard-deploy'), 
                    array_merge(
                        $atts,
                        ["flavor"=>"ikasumi"],
                        ["form-fields" => $m->render(
                            WPSC_Mustache::getTemplate('wizard-deploy-ikasumi'),
                            $atts
                        )]
                    )
                );
                break;            
            case 'deploy-azuki':
                $atts['text-three-steps'] = __("Azuki: NFT ERC-1155 Advanced Marketplace", "wp-smart-contracts");                
                $atts['text-three-steps-desc'] = __("Azuki is an ERC-1155 smart contract with advanced features like lazy minting, royalties, and a full-featured marketplace, empowering creators to manage and trade diverse NFT collections.", "wp-smart-contracts");
                $atts["icon"] = plugins_url("launcher/img/smart-contracts-wizard-ice-cream.png", dirname(__FILE__));
                $atts["tags"] = [
                    "ERC-1155",
                    __("Marketplace ", "wp-smart-contracts"),
                    __("Royalties ", "wp-smart-contracts"),
                    __("Lazy Minting", "wp-smart-contracts"),
                ];
                $atts["banner-wizard"] = $m->render(WPSC_Mustache::getTemplate('wizard-banner'), $atts);
                $atts["step2"] = __("Azuki", "wp-smart-contracts");
                $atts = self::addNFTAtts($atts);
                $atts["deploy-section"] = $m->render(WPSC_Mustache::getTemplate('deploy-section'), $atts);
                $atts["subheader-step4"] = $m->render(WPSC_Mustache::getTemplate('subheader-step4'), $atts);
                $atts['section-2'] = __("Wizard", "wp-smart-contracts");
                $atts['section-3'] = __("NFT Collections", "wp-smart-contracts");
                $atts["main-nav"] = $m->render(WPSC_Mustache::getTemplate('launcher-main-nav'), self::addLauncherMainNavAtts($atts));
                $atts = self::checkEdition($atts, ["wpsc_name", "wpsc_symbol", "wpsc_anyone_can_mint", "wpsc_pixelated_images", "wpsc_royalties", "wpsc_commission", "wpsc_wallet"]);
                return $m->render(
                    WPSC_Mustache::getTemplate('wizard-deploy'), 
                    array_merge(
                        $atts,
                        ["flavor"=>"azuki"],
                        ["form-fields" => $m->render(
                            WPSC_Mustache::getTemplate('wizard-deploy-azuki'),
                            $atts
                        )]
                    )
                );
                break;            
            case 'deploy-yuzu':
                $atts['text-three-steps'] = __("Yuzu: NFT ERC-1155 Multitoken Solution", "wp-smart-contracts");
                $atts['text-three-steps-desc'] = __("Yuzu is a NFT versatile Multitoken ERC-1155 smart contract enabling the creation of multiple quantity NFT items, or multiple token types, batch minting, and IPFS support, empowering diverse NFT collections.", "wp-smart-contracts");
                $atts["icon"] = plugins_url("launcher/img/smart-contracts-wizard-ice-cream.png", dirname(__FILE__));
                $atts["tags"] = ["ERC-1155"];
                $atts["banner-wizard"] = $m->render(WPSC_Mustache::getTemplate('wizard-banner'), $atts);
                $atts["step2"] = __("Yuzu", "wp-smart-contracts");
                $atts = self::addNFTAtts($atts);
                $atts["deploy-section"] = $m->render(WPSC_Mustache::getTemplate('deploy-section'), $atts);
                $atts["subheader-step4"] = $m->render(WPSC_Mustache::getTemplate('subheader-step4'), $atts);
                $atts['section-2'] = __("Wizard", "wp-smart-contracts");
                $atts['section-3'] = __("NFT Collections", "wp-smart-contracts");
                $atts["main-nav"] = $m->render(WPSC_Mustache::getTemplate('launcher-main-nav'), self::addLauncherMainNavAtts($atts));
                $atts = self::checkEdition($atts, ["wpsc_name", "wpsc_symbol", "wpsc_anyone_can_mint", "wpsc_pixelated_images"]);
                return $m->render(
                    WPSC_Mustache::getTemplate('wizard-deploy'), 
                    array_merge(
                        $atts,
                        ["flavor"=>"yuzu"],
                        ["form-fields" => $m->render(
                            WPSC_Mustache::getTemplate('wizard-deploy-yuzu'),
                            $atts
                        )]
                    )
                );
                break;            
            case 'deploy-suika':
                $atts['text-three-steps'] = __("Suika: Your Advanced ERC-721 Marketplace Solution", "wp-smart-contracts");
                $atts['text-three-steps-desc'] = __("Advanced ERC-721 Marketplace with buy, sell, auction, royalties, and token payments for digital assets. IPFS support included.", "wp-smart-contracts");
                $atts["icon"] = plugins_url("launcher/img/smart-contracts-wizard-ice-cream.png", dirname(__FILE__));
                $atts["tags"] = [
                    "ERC-721",
                    __("Marketplace ", "wp-smart-contracts"),
                    __("Royalties ", "wp-smart-contracts"),
                    __("Token Payments", "wp-smart-contracts"),
                ];
                $atts["banner-wizard"] = $m->render(WPSC_Mustache::getTemplate('wizard-banner'), $atts);
                $atts["step2"] = __("Suika", "wp-smart-contracts");
                $atts = self::addNFTAtts($atts);
                $atts["deploy-section"] = $m->render(WPSC_Mustache::getTemplate('deploy-section'), $atts);
                $atts["subheader-step4"] = $m->render(WPSC_Mustache::getTemplate('subheader-step4'), $atts);
                $atts['section-2'] = __("Wizard", "wp-smart-contracts");
                $atts['section-3'] = __("NFT Collections", "wp-smart-contracts");
                $atts["main-nav"] = $m->render(WPSC_Mustache::getTemplate('launcher-main-nav'), self::addLauncherMainNavAtts($atts));
                $atts = self::checkEdition($atts, ["wpsc_name", "wpsc_symbol", "wpsc_anyone_can_mint", "wpsc_pixelated_images", "wpsc_royalties", "wpsc_token", "wpsc_commission", "wpsc_wallet"]);
                return $m->render(
                    WPSC_Mustache::getTemplate('wizard-deploy'), 
                    array_merge(
                        $atts,
                        ["flavor"=>"suika"],
                        ["form-fields" => $m->render(
                            WPSC_Mustache::getTemplate('wizard-deploy-suika'),
                            $atts
                        )]
                    )
                );
                break;            
            case 'deploy-matcha':
                $atts['text-three-steps'] = __("Matcha: Your ERC-721 Marketplace Solution", "wp-smart-contracts");
                $atts['text-three-steps-desc'] = __("Unlock the power of non-fungible tokens (NFTs) with Matcha, a feature-rich ERC-721 token that brings buy, sell, and auction capabilities to your digital assets.", "wp-smart-contracts");
                $atts["icon"] = plugins_url("launcher/img/smart-contracts-wizard-ice-cream.png", dirname(__FILE__));
                $atts["tags"] = ["ERC-721", __("Marketplace ", "wp-smart-contracts")];
                $atts["banner-wizard"] = $m->render(WPSC_Mustache::getTemplate('wizard-banner'), $atts);
                $atts["step2"] = __("Matcha", "wp-smart-contracts");
                $atts = self::addNFTAtts($atts);
                $atts["deploy-section"] = $m->render(WPSC_Mustache::getTemplate('deploy-section'), $atts);
                $atts["subheader-step4"] = $m->render(WPSC_Mustache::getTemplate('subheader-step4'), $atts);
                $atts['section-2'] = __("Wizard", "wp-smart-contracts");
                $atts['section-3'] = __("NFT Collections", "wp-smart-contracts");
                $atts["main-nav"] = $m->render(WPSC_Mustache::getTemplate('launcher-main-nav'), self::addLauncherMainNavAtts($atts));
                $atts = self::checkEdition($atts, ["wpsc_name", "wpsc_symbol", "wpsc_anyone_can_mint", "wpsc_commission", "wpsc_wallet", "wpsc_pixelated_images"]);
                return $m->render(
                    WPSC_Mustache::getTemplate('wizard-deploy'), 
                    array_merge(
                        $atts,
                        ["flavor"=>"matcha"],
                        ["form-fields" => $m->render(
                            WPSC_Mustache::getTemplate('wizard-deploy-matcha'),
                            $atts
                        )]
                    )
                );
                break;            
            case 'deploy-mochi':
                $atts['text-three-steps'] = __("Mochi: Deploy Your Own NFT Collection", "wp-smart-contracts");
                $atts['text-three-steps-desc'] = __("Create your own unique ERC-721 NFT collection with Mochi. Experience the power of NFTs and IPFS support to showcase your digital or physical assets in various formats.", "wp-smart-contracts");
                $atts["icon"] = plugins_url("launcher/img/smart-contracts-wizard-ice-cream.png", dirname(__FILE__));
                $atts["tags"] = ["ERC-721"];
                $atts["banner-wizard"] = $m->render(WPSC_Mustache::getTemplate('wizard-banner'), $atts);
                $atts["step2"] = __("Mochi", "wp-smart-contracts");
                $atts = self::addNFTAtts($atts);
                $atts["deploy-section"] = $m->render(WPSC_Mustache::getTemplate('deploy-section'), $atts);
                $atts["subheader-step4"] = $m->render(WPSC_Mustache::getTemplate('subheader-step4'), $atts);
                $atts['section-2'] = __("Wizard", "wp-smart-contracts");
                $atts['section-3'] = __("NFT Collections", "wp-smart-contracts");
                $atts["main-nav"] = $m->render(WPSC_Mustache::getTemplate('launcher-main-nav'), self::addLauncherMainNavAtts($atts));
                $atts = self::checkEdition($atts, ["wpsc_name", "wpsc_symbol", "wpsc_anyone_can_mint", "wpsc_pixelated_images"]);
                return $m->render(
                    WPSC_Mustache::getTemplate('wizard-deploy'), 
                    array_merge(
                        $atts,
                        ["flavor"=>"mochi"],
                        ["form-fields" => $m->render(
                            WPSC_Mustache::getTemplate('wizard-deploy-mochi'),
                            $atts
                        )]
                    )
                );
                break;
            // setp 3
            case 'network':
                $atts['text-three-steps'] = __("Choose the ideal network for your project", "wp-smart-contracts");
                $atts['text-three-steps-desc'] = __("Explore a vast selection of Blockchain networks, including both test networks and main networks, and deploy your Smart Contracts with confidence. ", "wp-smart-contracts");
                $atts["icon"] = plugins_url("launcher/img/smart-contracts-wizard-cog.png", dirname(__FILE__));
                $atts["banner-wizard"] = $m->render(WPSC_Mustache::getTemplate('wizard-banner'), $atts);
                $atts = self::addLabels($atts);
                $networks = WPSC_helpers::getNetworkInfoJSON($atts["flavor"]);

                $json_factories = false;
                if (file_exists($json_filename = dirname(dirname(__FILE__)).'/assets/json/factories.json')) {
                    $json_factories = json_decode(file_get_contents($json_filename), true);
                }
                $networks_json = false;
                if (file_exists($json_filename = dirname(dirname(__FILE__)).'/assets/json/networks.json')) {
                    $networks_json = json_decode(file_get_contents($json_filename), true);
                }
    
                if (WPSC_helpers::valArrElement($networks, 'data')) {

                    $disabled_test = false;
                    $disabled = [];

                    if (get_option("disabled_ethereum")) {
                        $disabled[] = 1;
                        $disabled[] = 5;
                        $disabled[] = 11155111;
                    }
                    if (get_option("disabled_arbitrum")) {
                        $disabled[] = 42161;
                    }
                    if (get_option("disabled_bsc")) {
                        $disabled[] = 56;
                        $disabled[] = 97;
                    }
                    if (get_option("disabled_polygon")) {
                        $disabled[] = 137;
                        $disabled[] = 80001;
                    }
                    if (get_option("disabled_avax")) {
                        $disabled[] = 43114;
                        $disabled[] = 43113;
                    }
                    if (get_option("disabled_fantom")) {
                        $disabled[] = 250;
                        $disabled[] = 4002;
                    }
                    if (get_option("disabled_test")) {
                        $disabled_test = true;
                    }

                    $cards = [];
                    foreach ($networks["data"] as $key => $net) {

                        // skip disabled networks
                        if ($disabled_test and $networks["data"][$key]["type"] == "Testnet") continue;
                        if (in_array($networks["data"][$key]["id"], $disabled)) continue;

                        $networks["data"][$key]["fee"] = $networks["data"][$key][$atts["flavor"]];

                        $network_name = $networks_json[$networks["data"][$key]["id"]]["name"];

                        if (!isset($json_factories[$atts["flavor"]]["networks"][$network_name]["coffee"]) and $networks["data"][$key][$atts["flavor"]][0]!="Free") {
                            continue;
                        }

                        if (isset($networks["data"][$key][$atts["flavor"]][1])) {
                            $fee = __("Fee: ", "wp-smart-contracts") . $networks["data"][$key][$atts["flavor"]][0] . " (".$networks["data"][$key][$atts["flavor"]][1].")";
                        } else {
                            $fee = __("Free", "wp-smart-contracts");
                        }
                        $cards[] = [
                            "logo" => $path . "img/white-" . $net["asset"],
                            "title" => $net["name"],
                            "desc" => $net["description"],
                            "tags" => [
                                $networks["data"][$key]["type"],
                                $fee
                            ],
                            "title-class" => "wps-medium-text",
                            "button-data-class" => "wpsc-switch",
                            "button-data-id" => $networks["data"][$key]["id"],
                            "button-data-url" => add_query_arg("network_id", $networks["data"][$key]["id"], $atts["deploy-page"]),
                            "hidden" => true,
                            "card-id" => "net-" . $networks["data"][$key]["id"]
                        ];
                    }

                    if(!sizeof($cards)) {
                        $atts["no-networks"] = true;
                    }

                    $atts["cards"] = $m->render(WPSC_Mustache::getTemplate('wizard-cards'), [
                        "select" => __("Select", "wp-smart-contracts"),
                        "path-to-launcher" => plugins_url( "launcher/", dirname(__FILE__)),
                        "cards-id" => "wps-network-page",
                        "cards" => $cards
                    ]);
                }
                $atts['section-2'] = __("Wizard", "wp-smart-contracts");
                $atts['section-3'] = __("Networks", "wp-smart-contracts");
                $atts["main-nav"] = $m->render(WPSC_Mustache::getTemplate('launcher-main-nav'), self::addLauncherMainNavAtts($atts));

                $atts['available'] = __("The contract you chose is available on the following Networks:", "wp-smart-contracts");
                $atts['not-available'] = __("There is no network available for the selected flavor", "wp-smart-contracts");
                $atts['not-supported'] = __("It appears that you are connected to a network that is not supported.", "wp-smart-contracts");
                $atts['are-you-sure'] = __("Are you sure you are connected to the correct network in your wallet?", "wp-smart-contracts");
                $atts['option'] = __("smart contract options", "wp-smart-contracts");

                return $m->render(WPSC_Mustache::getTemplate('wizard-network'), $atts);
                break;            
            // setp 2
            case 'nft':
                $atts['text-three-steps'] = __("Explore the possibilities of NFT Smart Contracts", "wp-smart-contracts");
                $atts['text-three-steps-desc'] = __("Create an NFT smart contract tailored to your vision. Choose from an array of flavors, including Mochi, Matcha, Suika, Yuzu, Azuki, and Ikasumi.", "wp-smart-contracts");
                $atts["icon"] = plugins_url("launcher/img/smart-contracts-wizard-nft.png", dirname(__FILE__));
                $atts["banner-wizard"] = $m->render(WPSC_Mustache::getTemplate('wizard-banner'), $atts);
                $atts = self::addFlavorLinks($atts);
                $atts["step1-selected"] = __("NFTs", "wp-smart-contracts");
                $atts["subheader-step2"] = $m->render(WPSC_Mustache::getTemplate('subheader-step2'), $atts);
                $atts["nft-collection"] = __("NFT Collection", "wp-smart-contracts");
                $atts["mochi"] = __("Standard ERC-721 NFT Collection", "wp-smart-contracts");
                $atts["mochi-img"] = dirname( plugin_dir_url( __FILE__ ) ) . '/assets/img/mochi-flavor.png';
                $atts["matcha"] = __("ERC-721 NFT Marketplace", "wp-smart-contracts");
                $atts["matcha-img"] = dirname( plugin_dir_url( __FILE__ ) ) . '/assets/img/matcha-flavor.png';
                $atts["suika"] = __("ERC-721 NFT Token Marketplace with Royalties", "wp-smart-contracts");
                $atts["suika-img"] = dirname( plugin_dir_url( __FILE__ ) ) . '/assets/img/suika-flavor.png';
                $atts["yuzu"] = __("ERC-1155 NFT Standard Token", "wp-smart-contracts");
                $atts["yuzu-img"] = dirname( plugin_dir_url( __FILE__ ) ) . '/assets/img/yuzu-flavor.png';
                $atts["azuki"] = __("ERC-1155 NFT Advanced Marketplace", "wp-smart-contracts");
                $atts["azuki-img"] = dirname( plugin_dir_url( __FILE__ ) ) . '/assets/img/chocolate-flavor.png';
                $atts["ikasumi"] = __("ERC-1155 NFT Advanced Marketplace with Token Payments", "wp-smart-contracts");
                $atts["ikasumi-img"] = dirname( plugin_dir_url( __FILE__ ) ) . '/assets/img/ikasumi-flavor.png';
                $atts['section-2'] = __("Wizard", "wp-smart-contracts");
                $atts['section-3'] = __("NFT Collections", "wp-smart-contracts");
                $atts["main-nav"] = $m->render(WPSC_Mustache::getTemplate('launcher-main-nav'), self::addLauncherMainNavAtts($atts));
                $path = plugins_url( "launcher/", dirname(__FILE__));
                $atts["cards"] = $m->render(WPSC_Mustache::getTemplate('wizard-cards'), 
                    [
                        "select" => __("Select", "wp-smart-contracts"),
                        "path-to-launcher" => plugins_url( "launcher/", dirname(__FILE__)),
                        "cards" => [
                            [
                                "logo" => $path . "img/ice-cream.png",
                                "title" => "Ikasumi",
                                "desc" => __("Our most advanced NFT Marketplace, featuring an ERC-1155 NFT Multitoken Marketplace with multiple quantities, auction, selling, royalties and lazy minting in ERC-20 / BEP20 Token.", "wp-smart-contracts"),
                                "tags" => [
                                    "ERC-1155",
                                    __("Marketplace ", "wp-smart-contracts"),
                                    __("Royalties ", "wp-smart-contracts"),
                                    __("Lazy Minting", "wp-smart-contracts"),
                                    __("Token Payments", "wp-smart-contracts"),
                                ],
                                "link" => add_query_arg(["flavor"=>"ikasumi"], $network_page)
                            ],
                            [
                                "logo" => $path . "img/ice-cream.png",
                                "title" => "Azuki",
                                "desc" => __("Our most advanced NFT Marketplace, featuring an ERC-1155 NFT Multitoken Marketplace with multiple quantities, auction, selling, royalties and lazy minting in native currency.", "wp-smart-contracts"),
                                "tags" => [
                                    "ERC-1155",
                                    __("Marketplace ", "wp-smart-contracts"),
                                    __("Royalties ", "wp-smart-contracts"),
                                    __("Lazy Minting", "wp-smart-contracts"),
                                ],
                                "link" => add_query_arg(["flavor"=>"azuki"], $network_page)
                            ],
                            [
                                "logo" => $path . "img/ice-cream.png",
                                "title" => "Yuzu",
                                "desc" => __("A ERC-1155 Multi Token. You can create collectible with multiple quantities and mass minting.", "wp-smart-contracts"),
                                "tags" => ["ERC-1155"],
                                "link" => add_query_arg(["flavor"=>"yuzu"], $network_page)
                            ],
                            [
                                "logo" => $path . "img/ice-cream.png",
                                "title" => "Suika",
                                "desc" => __("Fully featured NFT ERC-20 / BEP20 Token Marketplace with auction, selling and royalties.", "wp-smart-contracts"),
                                "tags" => [
                                    "ERC-721",
                                    __("Marketplace ", "wp-smart-contracts"),
                                    __("Royalties ", "wp-smart-contracts"),
                                    __("Token Payments", "wp-smart-contracts"),
                                ],
                                "link" => add_query_arg(["flavor"=>"suika"], $network_page)
                            ],
                            [
                                "logo" => $path . "img/ice-cream.png",
                                "network-page" => WPSC_assets::getPage("wpsc_is_wizard_network"),
                                "title" => "Matcha",
                                "desc" => __("Fully featured ERC-721 NFT Marketplace", "wp-smart-contracts"),
                                "tags" => [
                                    "ERC-721",
                                    __("Marketplace ", "wp-smart-contracts")
                                ],
                                "link" => add_query_arg(["flavor"=>"matcha"], $network_page)
                            ],
                            [
                                "logo" => $path . "img/ice-cream.png",
                                "network-page" => WPSC_assets::getPage("wpsc_is_wizard_network"),
                                "title" => "Mochi",
                                "desc" => __("A simple ERC-721 Standard Token. You can create and transfer collectibles.", "wp-smart-contracts"),
                                "tags" => ["ERC-721"],
                                "link" => add_query_arg(["flavor"=>"mochi"], $network_page)
                            ]
                        ]
                    ]
                );
                return $m->render(WPSC_Mustache::getTemplate('wizard-nft'), $atts);
                break;
            case 'services':
                $atts['text-three-steps'] = __("Empower your project with token services", "wp-smart-contracts");
                $atts['text-three-steps-desc'] = __("Unlock a world of token possibilities with our user-friendly interface. Seamlessly deploy ICOs, manage secure Funds Vault, and distribute Airdrops with ease.", "wp-smart-contracts");
                $atts["icon"] = plugins_url("launcher/img/smart-contracts-wizard-staking.png", dirname(__FILE__));
                $atts["banner-wizard"] = $m->render(WPSC_Mustache::getTemplate('wizard-banner'), $atts);
                $atts = self::addFlavorLinks($atts);
                $atts["step1-selected"] = __("Token Services", "wp-smart-contracts");
                $atts["subheader-step2"] = $m->render(WPSC_Mustache::getTemplate('subheader-step2'), $atts);
                $atts["services"] = __("Token Services", "wp-smart-contracts");
                $atts["bubblegum"] = __("ERC-20 / BEP-20 Stakings", "wp-smart-contracts");
                $atts["bubblegum-img"] = dirname( plugin_dir_url( __FILE__ ) ) . '/assets/img/bubblegum-flavor.png';
                $atts['section-2'] = __("Wizard", "wp-smart-contracts");
                $atts['section-3'] = __("ICOs", "wp-smart-contracts");
                $atts["main-nav"] = $m->render(WPSC_Mustache::getTemplate('launcher-main-nav'), self::addLauncherMainNavAtts($atts));
                $atts["cards"] = $m->render(WPSC_Mustache::getTemplate('wizard-cards'), 
                    [
                        "select" => __("Select", "wp-smart-contracts"),
                        "path-to-launcher" => plugins_url( "launcher/", dirname(__FILE__)),
                        "cards" => [
                            [
                                "logo" => $path . "img/ice-cream.png",
                                "title" => "Bubblegum",
                                "desc" => __("Empower your users to engage in ICOs, launch ICO campaigns, and manage token services effortlessly.", "wp-smart-contracts"),
                                "tags" => [
                                    __("ICO", "wp-smart-contracts"),
                                ],
                                "link" => add_query_arg(["flavor"=>"bubblegum"], $network_page)
                            ]
                        ]
                    ]
                );
                return $m->render(WPSC_Mustache::getTemplate('wizard-services'), $atts);
                break;            
            case 'stakes':
                $atts['text-three-steps'] = __("Enhance your project's tokenomics with Staking", "wp-smart-contracts");
                $atts['text-three-steps-desc'] = __("Enter the world of Staking with ease. Our wizard lets you create a Staking smart contract effortlessly. Pick from our flavorful options like Ube and Almond", "wp-smart-contracts");
                $atts["icon"] = plugins_url("launcher/img/smart-contracts-wizard-staking.png", dirname(__FILE__));
                $atts["banner-wizard"] = $m->render(WPSC_Mustache::getTemplate('wizard-banner'), $atts);
                $atts = self::addFlavorLinks($atts);
                $atts["step1-selected"] = __("Stakings", "wp-smart-contracts");
                $atts["subheader-step2"] = $m->render(WPSC_Mustache::getTemplate('subheader-step2'), $atts);
                $atts["staking"] = __("Staking", "wp-smart-contracts");
                $atts["ube"] = __("ERC-20 / BEP-20 Stakings", "wp-smart-contracts");
                $atts["ube-img"] = dirname( plugin_dir_url( __FILE__ ) ) . '/assets/img/ube-flavor.png';
                $atts["almond"] = __("ERC-20 / BEP-20 Advanced Stakings", "wp-smart-contracts");
                $atts["almond-img"] = dirname( plugin_dir_url( __FILE__ ) ) . '/assets/img/almond-flavor.png';
                $atts['section-2'] = __("Wizard", "wp-smart-contracts");
                $atts['section-3'] = __("Stakings", "wp-smart-contracts");
                $atts["main-nav"] = $m->render(WPSC_Mustache::getTemplate('launcher-main-nav'), self::addLauncherMainNavAtts($atts));
                $atts["cards"] = $m->render(WPSC_Mustache::getTemplate('wizard-cards'), 
                    [
                        "select" => __("Select", "wp-smart-contracts"),
                        "path-to-launcher" => plugins_url( "launcher/", dirname(__FILE__)),
                        "cards" => [
                            [
                                "logo" => $path . "img/ice-cream.png",
                                "title" => "Ube",
                                "desc" => __("Allow your users to accrue interest. Annual interest rate, calculated per second in the same token staked", "wp-smart-contracts"),
                                "tags" => [
                                    __("Single Token", "wp-smart-contracts"),
                                ],
                                "link" => add_query_arg(["flavor"=>"ube"], $network_page)
                            ],
                            [
                                "logo" => $path . "img/ice-cream.png",
                                "title" => "Almond",
                                "desc" => __("Allow your users to stake one token and accrue interest in other. Stake Token X and get interest in token X and/or Y", "wp-smart-contracts"),
                                "tags" => [
                                    __("Multiple Token", "wp-smart-contracts"),
                                ],
                                "link" => add_query_arg(["flavor"=>"almond"], $network_page)
                            ]
                        ]
                    ]
                );
                return $m->render(WPSC_Mustache::getTemplate('wizard-stakes'), $atts);
                break;            
            case 'coin':
                $atts['text-three-steps'] = __("Create your own digital assets", "wp-smart-contracts");
                $atts['text-three-steps-desc'] = __("Become a part of the digital revolution by designing your own cryptocurrency, token or asset. With flavors like Macadamia, Chocolate, Vanilla, and Pistachio", "wp-smart-contracts");
                $atts["icon"] = plugins_url("launcher/img/smart-contracts-wizard-tokens.png", dirname(__FILE__));
                $atts["banner-wizard"] = $m->render(WPSC_Mustache::getTemplate('wizard-banner'), $atts);
                $atts = self::addFlavorLinks($atts);
                $atts["step1-selected"] = __("Cryptocurrency", "wp-smart-contracts");
                $atts["subheader-step2"] = $m->render(WPSC_Mustache::getTemplate('subheader-step2'), $atts);
                $atts["vanilla"] = __("Gas Saving Token", "wp-smart-contracts");
                $atts["vanilla-img"] = dirname( plugin_dir_url( __FILE__ ) ) . '/assets/img/vanilla-flavor.png';
                $atts["pistachio"] = __("Improved Security Token", "wp-smart-contracts");
                $atts["pistachio-img"] = dirname( plugin_dir_url( __FILE__ ) ) . '/assets/img/pistachio-flavor.png';
                $atts["chocolate"] = __("Advanced Token", "wp-smart-contracts");
                $atts["chocolate-img"] = dirname( plugin_dir_url( __FILE__ ) ) . '/assets/img/chocolate-flavor.png';
                $atts["macadamia"] = __("Reflection Token", "wp-smart-contracts");
                $atts["macadamia-img"] = dirname( plugin_dir_url( __FILE__ ) ) . '/assets/img/macadamia-flavor.png';
                $atts['section-2'] = __("Wizard", "wp-smart-contracts");
                $atts['section-3'] = __("Cryptocurrencies", "wp-smart-contracts");
                $atts["main-nav"] = $m->render(WPSC_Mustache::getTemplate('launcher-main-nav'), self::addLauncherMainNavAtts($atts));
                $atts["cards"] = $m->render(WPSC_Mustache::getTemplate('wizard-cards'), 
                    [
                        "select" => __("Select", "wp-smart-contracts"),
                        "path-to-launcher" => plugins_url( "launcher/", dirname(__FILE__)),
                        "cards" => [
                            [
                                "logo" => $path . "img/ice-cream.png",
                                "title" => "Macadamia",
                                "desc" => __("An ERC-20 Token with Hold & Earn feature", "wp-smart-contracts"),
                                "tags" => [
                                    "ERC-20", "BEP-20",
                                    __("Reflection", "wp-smart-contracts"),
                                    __("Improved Security", "wp-smart-contracts")
                                ],
                                "link" => add_query_arg(["flavor"=>"macadamia"], $network_page)
                            ],
                            [
                                "logo" => $path . "img/ice-cream.png",
                                "title" => "Chocolate",
                                "desc" => __("An Standard ERC-20 Token, secure with advanced features", "wp-smart-contracts"),
                                "tags" => [
                                    "ERC-20", "BEP-20",
                                    __("Mint", "wp-smart-contracts"),
                                    __("Burn", "wp-smart-contracts"),
                                    __("Pause", "wp-smart-contracts"),
                                    __("Improved Security", "wp-smart-contracts")
                                ],
                                "link" => add_query_arg(["flavor"=>"chocolate"], $network_page)
                            ],
                            [
                                "logo" => $path . "img/ice-cream.png",
                                "title" => "Pistachio",
                                "desc" => __("A Standard Ethereum Token, focused on security", "wp-smart-contracts"),
                                "tags" => [
                                    "ERC-20", "BEP-20",
                                    __("Improved Security", "wp-smart-contracts")
                                ],
                                "link" => add_query_arg(["flavor"=>"pistachio"], $network_page)
                            ],
                            [
                                "logo" => $path . "img/ice-cream.png",
                                "title" => "Vanilla",
                                "desc" => __("A Standard ERC-20 Token, focused on Gas Saving transactions", "wp-smart-contracts"),
                                "tags" => [
                                    "ERC-20", "BEP-20", __("Gas Saving", "wp-smart-contracts")
                                ],
                                "link" => add_query_arg(["flavor"=>"vanilla"], $network_page)
                            ]
                        ]
                    ]
                );
                return $m->render(WPSC_Mustache::getTemplate('wizard-coin'), $atts);
                break;    
            case 'crowd':
                $atts['text-three-steps'] = __("Empower your ideas with Crowdfunding", "wp-smart-contracts");
                $atts['text-three-steps-desc'] = __("Transform your dreams into reality using Crowdfunding smart contracts. Choose the Mango flavor and raise funds for your projects or ventures", "wp-smart-contracts");
                $atts["icon"] = plugins_url("launcher/img/smart-contracts-wizard-crowd.png", dirname(__FILE__));
                $atts["banner-wizard"] = $m->render(WPSC_Mustache::getTemplate('wizard-banner'), $atts);
                $atts = self::addFlavorLinks($atts);
                $atts["step1-selected"] = __("Raise funds", "wp-smart-contracts");
                $atts["subheader-step2"] = $m->render(WPSC_Mustache::getTemplate('subheader-step2'), $atts);
                $atts["mango"] = __("Safe Crowdfunding", "wp-smart-contracts");
                $atts["mango-img"] = dirname( plugin_dir_url( __FILE__ ) ) . '/assets/img/mango-flavor.png';
                $atts['section-2'] = __("Wizard", "wp-smart-contracts");
                $atts["main-nav"] = $m->render(WPSC_Mustache::getTemplate('launcher-main-nav'), self::addLauncherMainNavAtts($atts));
                $atts["cards"] = $m->render(WPSC_Mustache::getTemplate('wizard-cards'), 
                    [
                        "select" => __("Select", "wp-smart-contracts"),
                        "path-to-launcher" => plugins_url( "launcher/", dirname(__FILE__)),
                        "cards" => [
                            [
                                "logo" => $path . "img/ice-cream.png",
                                "title" => "Mango",
                                "desc" => __("A simple crowdfunding campaign that can receive contributions in Ether or the native coin of the blockchain. The owner can spend the donations only on contributors approval.", "wp-smart-contracts"),
                                "tags" => [
                                    __("Crowdfundings", "wp-smart-contracts"),
                                    __("Voting", "wp-smart-contracts")
                                ],
                                "link" => add_query_arg(["flavor"=>"mango"], $network_page)
                            ]
                        ]
                    ]
                );
                return $m->render(WPSC_Mustache::getTemplate('wizard-crowd'), $atts);
                break;
            // setp 1       
            default:
                $atts['text-three-steps'] = __("Easily create Smart Contracts in just four simple steps", "wp-smart-contracts");
                $atts['text-three-steps-desc'] = __("Select the perfect contract for your requirements and deploy it on a range of available Blockchain networks. Launch your blockchain business effortlessly with a few clicks.", "wp-smart-contracts");
                $atts["icon"] = plugins_url("launcher/img/smart-contracts-wizard.png", dirname(__FILE__));
                $atts["no-banner-title"] = true;
                $atts["banner-wizard"] = $m->render(WPSC_Mustache::getTemplate('wizard-banner'), $atts);
                $atts['section-2'] = __("Wizard", "wp-smart-contracts");
                $atts["main-nav"] = $m->render(WPSC_Mustache::getTemplate('launcher-main-nav'), self::addLauncherMainNavAtts($atts));
                return $m->render(WPSC_Mustache::getTemplate('wizard'), $atts);
                break;
        }

    }
    
    public function coin($params) {

        $xdai_block_explorer = $xdai = null;

        $the_id = self::getPostID($params);

        if (is_array($params) and array_key_exists('id', $params) and $params["id"]) {
            $title = get_the_title(absint($params["id"]));
            $the_id = $params['id'];
        }

        if (!$the_id) {
            $the_id = get_the_ID();
        }

        $wpsc_thumbnail = get_the_post_thumbnail_url($the_id);
        $wpsc_title = get_the_title($the_id);
        $wpsc_content = get_post_field('post_content', $the_id);

        $wpsc_network = get_post_meta($the_id, 'wpsc_network', true);
        $wpsc_txid = get_post_meta($the_id, 'wpsc_txid', true);
        $wpsc_owner = get_post_meta($the_id, 'wpsc_owner', true);
        $wpsc_contract_address = get_post_meta($the_id, 'wpsc_contract_address', true);
        $wpsc_coin_decimals = get_post_meta($the_id, 'wpsc_coin_decimals', true);
        $wpsc_blockie = get_post_meta($the_id, 'wpsc_blockie', true);
        $wpsc_blockie_owner = get_post_meta($the_id, 'wpsc_blockie_owner', true);
        $wpsc_qr_code = get_post_meta($the_id, 'wpsc_qr_code', true);

        list($color, $icon, $etherscan, $network_val) = WPSC_Metabox::getNetworkInfo($wpsc_network);

        if ($wpsc_network!=1 and 
            $wpsc_network!=3 and 
            $wpsc_network!=4 and 
            $wpsc_network!=42 and 
            $wpsc_network!=5 and 
            $wpsc_network!=97 and 
            $wpsc_network!=56 and 
            $wpsc_network!=80001 and 
            $wpsc_network!=137 and
            $wpsc_network!=43113 and
            $wpsc_network!=43114 and
            $wpsc_network!=4002 and
            $wpsc_network!=250 and
            $wpsc_network!=11155111 and
            $wpsc_network!=42161) {

            $networks = WPSC_helpers::getNetworks();
            if (isset($networks[$wpsc_network]["url2"])) {
                $xdai_block_explorer = $networks[$wpsc_network]["url2"]."address/".$wpsc_contract_address;
                $xdai=true;
            }
            
        }

        $wpsc_social_icon = get_post_meta($the_id, 'wpsc_social_icon', true);
        $wpsc_social_link = get_post_meta($the_id, 'wpsc_social_link', true);
        $wpsc_social_name = get_post_meta($the_id, 'wpsc_social_name', true);

        $m = new Mustache_Engine;

        // initialization
        $wpsc_flavor        = null;
        $wpsc_adv_burn      = null;
        $wpsc_adv_pause     = null;
        $wpsc_adv_mint      = null;
        $wpsc_coin_name     = null;
        $wpsc_coin_symbol   = null;
        $wpsc_coin_decimals = null;
        $wpsc_total_supply  = null;
        $wpsc_adv_cap       = null;
        $atts = [];

        // show contract
        if ($wpsc_contract_address) {

            $wpsc_flavor        = get_post_meta($the_id, 'wpsc_flavor', true);

            $wpsc_adv_burn       = get_post_meta($the_id, 'wpsc_adv_burn', true);
            $wpsc_adv_pause      = get_post_meta($the_id, 'wpsc_adv_pause', true);
            $wpsc_adv_mint       = get_post_meta($the_id, 'wpsc_adv_mint', true);
            $wpsc_coin_name      = get_post_meta($the_id, 'wpsc_coin_name', true);
            $wpsc_coin_symbol    = get_post_meta($the_id, 'wpsc_coin_symbol', true);
            $wpsc_coin_decimals  = get_post_meta($the_id, 'wpsc_coin_decimals', true);

            $wpsc_reflection_fee = get_post_meta($the_id, 'wpsc_reflection_fee', true);

            switch($wpsc_reflection_fee) {
              case 5:
                $wpsc_reflection_fee = "20%";
                break;
              case 10:
                $wpsc_reflection_fee = "10%";
                break;
              case 20:
                $wpsc_reflection_fee = "5%";
                break;
              case 33:
                $wpsc_reflection_fee = "3%";
                break;
              case 50:
                $wpsc_reflection_fee = "2%";
                break;
              default:
                $wpsc_reflection_fee = "1%"; // 1% by default
                break;
            }
  

            $wpsc_total_supply  = WPSC_helpers::formatNumber(get_post_meta($the_id, 'wpsc_total_supply', true));

            $the_cap = get_post_meta($the_id, 'wpsc_adv_cap', true);
            if ($the_cap) {
                $wpsc_adv_cap = WPSC_helpers::formatNumber($the_cap);
            } else {
                $wpsc_adv_cap = __('Unlimited', 'wp-smart-contracts');
            }

            $tokenInfo = [
                "type" => $wpsc_flavor,
                "symbol" => $wpsc_coin_symbol,
                "name" => $wpsc_coin_name,
                "decimals" => $wpsc_coin_decimals,
                "supply" => $wpsc_total_supply,
                "size" => "mini",
                "symbol_label" => __('Symbol', 'wp-smart-contracts'),
                "name_label" => __('Name', 'wp-smart-contracts'),
                "decimals_label" => __('Decimals', 'wp-smart-contracts'),
                "initial_label" => __('Initial Supply', 'wp-smart-contracts'),
                "reflection_label" => __('Reflection fee', 'wp-smart-contracts'),
                "burnable_label" => __('Burnable', 'wp-smart-contracts'),
                "mintable_label" => __('Mintable', 'wp-smart-contracts'),
                "max_label" => __('Max. cap', 'wp-smart-contracts'),
                "pausable_label" => __('Pausable', 'wp-smart-contracts'),    
            ];
            if ($wpsc_flavor=="chocolate") {
                $tokenInfo["color"] = "brown";
                $tokenInfo["cap"] = $wpsc_adv_cap;
                if ($wpsc_adv_burn) $tokenInfo["burnable"] = true;
                if ($wpsc_adv_mint) $tokenInfo["mintable"] = true;
                if ($wpsc_adv_pause) $tokenInfo["pausable"] = true;
            }
            if ($wpsc_flavor=="macadamia") {
                $tokenInfo["wpsc_reflection_fee"] = $wpsc_reflection_fee;
                $tokenInfo["color"] = "beige";
            }
            if ($wpsc_flavor=="vanilla") $tokenInfo["color"] = "yellow";
            if ($wpsc_flavor=="pistachio") $tokenInfo["color"] = "olive";
            $tokenInfo["imgUrl"] = dirname( plugin_dir_url( __FILE__ ) ) . '/assets/img/';

            $atts = [
                'ethereum-network' => $network_val,
                'ethereum-color' => $color,
                'ethereum-icon' => $icon,
                'contract-address' => $wpsc_contract_address,
                'etherscan' => $etherscan,
                'contract-address-text' => __('Contract Address', 'wp-smart-contracts'),
                'txid-text' => __('Transaction ID', 'wp-smart-contracts'),
                'owner-text' => __('Owner Account', 'wp-smart-contracts'),
                'token-name' => ucwords(get_post_meta($the_id, 'wpsc_coin_name', true)),
                'token-symbol' => strtoupper(get_post_meta($the_id, 'wpsc_coin_symbol', true)), 
                'qr-code' => $wpsc_qr_code,
                'blockie' => $wpsc_blockie,
                'blockie-owner' => $wpsc_blockie_owner,
                'token-info' => $m->render(WPSC_Mustache::getTemplate('token-info'), $tokenInfo),
                'token-logo' => get_the_post_thumbnail_url($the_id),
                'contract-address-short' => WPSC_helpers::shortify($wpsc_contract_address),
                'txid' => $wpsc_txid,
                'txid-short' => WPSC_helpers::shortify($wpsc_txid),
                'owner' => $wpsc_owner,
                'owner-short' => WPSC_helpers::shortify($wpsc_owner)
            ];

            if ($wpsc_txid) {
                $atts["txid-exists"] = true;
            }

        }

        $social_networks = '';
        if (is_array($wpsc_social_link)) {
            foreach ($wpsc_social_link as $sn_i => $social_link) {
                $social_networks .= $m->render(WPSC_Mustache::getTemplate('coin-view-social-networks'), [
                    'link' => $social_link,
                    'icon' => $wpsc_social_icon[$sn_i]
                ]);
            }                   
        }

        $block_explorer_atts = [
            "xdai" => $xdai,
            "xdai_block_explorer" => $xdai_block_explorer,
            "xdai_block_explorer_label" => __('Go to Block Explorer', 'wp-smart-contracts'),
            'block-explorer' => __('Block Explorer', 'wp-smart-contracts'),
            'search-placeholder' => __('Search by Address or Txhash', 'wp-smart-contracts'),
            'transfers' => __('Transfers', 'wp-smart-contracts'),
            'holders' => __('Holders', 'wp-smart-contracts'),
            'page' => __('Page', 'wp-smart-contracts'),
            'date' => __('Date', 'wp-smart-contracts'),
            'from' => __('From', 'wp-smart-contracts'),
            'to' => __('To', 'wp-smart-contracts'),
            'amount_tx' => __('Amount and Transaction', 'wp-smart-contracts'),
            'value' => __('Value', 'wp-smart-contracts'),
            'previous' => __('Previous', 'wp-smart-contracts'),
            'next' => __('Next', 'wp-smart-contracts'),
            'updated' => __('Synced with blockchain every minute', 'wp-smart-contracts'),
            'account-url' => str_replace('acc-add-here', '', home_url() . esc_url( add_query_arg( 'acc', 'acc-add-here' ) ) ),
            'url' => get_permalink(),
            'etherscan' => $etherscan,
            'domain' => WPSC_Endpoints::getNetworkDomain($wpsc_network, '', 'url2'),
            'contract' => $wpsc_contract_address,
            'decimals' => $wpsc_coin_decimals,
            'network' => $wpsc_network,
            'total_supply' => __('Total supply', 'wp-smart-contracts'),
            'symbol' => $wpsc_coin_symbol,
            'internal-transactions' => __('Internal Transactions', 'wp-smart-contracts'),
            'transactions' => __('Transactions', 'wp-smart-contracts'),
        ];

        $the_token_symbol = WPSC_helpers::valArrElement($atts, 'token-symbol')?$atts["token-symbol"]:null;

        $atts_coin_view_token = [
            'blockie' => WPSC_helpers::valArrElement($atts, 'blockie')?$atts["blockie"]:null,
            'token-name' => WPSC_helpers::valArrElement($atts, 'token-name')?$atts["token-name"]:null,
            'token-symbol' => $the_token_symbol,
            'color' => $color,
            'icon' => $icon,
            'ethereum-network' => WPSC_helpers::valArrElement($atts, 'ethereum-network')?$atts["ethereum-network"]:null,
            'token-info' => WPSC_helpers::valArrElement($atts, 'token-info')?$atts["token-info"]:null,
        ];

        $atts_coin_view_addresses = [
            "addresses" => __('Addresses', 'wp-smart-contracts'),
            "contract-address" => WPSC_helpers::valArrElement($atts, 'contract-address')?$atts["contract-address"]:null,
            "token-symbol" => $the_token_symbol,
            "wpsc-add-token-to-metamask" => $m->render(
                WPSC_Mustache::getTemplate('add-token-to-metamask'), [
                    "network" => $network_val,
                    "contract-address" => WPSC_helpers::valArrElement($atts, 'contract-address')?$atts["contract-address"]:null,
                    "token-symbol" => $the_token_symbol,
                    "fox" => plugins_url( "assets/img/metamask-fox.svg", dirname(__FILE__) ),
                    "add-token-metamask" => __('Add token to Metamask', 'wp-smart-contracts')
                ]
            ),
            "blockie"                   => WPSC_helpers::valArrElement($atts, 'blockie')?$atts["blockie"]:null,
            "contract-address-text"     => WPSC_helpers::valArrElement($atts, 'contract-address-text')?$atts["contract-address-text"]:null,
            "contract-address-short"    => WPSC_helpers::valArrElement($atts, 'contract-address-short')?$atts["contract-address-short"]:null,
            "qr-code"                   => WPSC_helpers::valArrElement($atts, 'qr-code')?$atts["qr-code"]:null,
            "blockie-owner"             => WPSC_helpers::valArrElement($atts, 'blockie-owner')?$atts["blockie-owner"]:null,
            "owner-text"                => WPSC_helpers::valArrElement($atts, 'owner-text')?$atts["owner-text"]:null,
            "owner"                     => WPSC_helpers::valArrElement($atts, 'owner')?$atts["owner"]:null,
            "etherscan"                 => WPSC_helpers::valArrElement($atts, 'etherscan')?$atts["etherscan"]:null,
            "owner-short"               => WPSC_helpers::valArrElement($atts, 'owner-short')?$atts["owner-short"]:null,
            "txid"                      => WPSC_helpers::valArrElement($atts, 'txid')?$atts["txid"]:null,
            "genesis"                   => __('Genesis', 'wp-smart-contracts'),
            "txid-short"                => WPSC_helpers::valArrElement($atts, 'txid-short')?$atts["txid-short"]:null
        ];

        $atts_coin_view_wallet = [
            "xdai" => $xdai,
            "wallet" => __('Wallet', 'wp-smart-contracts'),
            "wallet-icon" => plugin_dir_url( dirname(__FILE__) ) . '/assets/img/wallet.svg',
            "wallet-icon-white" => plugin_dir_url( dirname(__FILE__) ) . '/assets/img/wallet-white.svg',
            "balance" => __('Balance', 'wp-smart-contracts'),
            "the-balance" => '',
            "balance-tooltip" => __('Check the balance of specific accounts', 'wp-smart-contracts'),
            "transfer" => __('Transfer', 'wp-smart-contracts'),
            "transfer-tooltip" => __('Transfer an amount of tokens from your account to another', 'wp-smart-contracts'),
            "transfer-from" => __('Transfer from', 'wp-smart-contracts'),
            "transfer-from-tooltip" => __('Expend tokens previously approved from an account', 'wp-smart-contracts'),
            "approve" => __('Approve', 'wp-smart-contracts'),
            "approve-tooltip" => __('Authorize an account to withdraw your tokens up to a specified amount', 'wp-smart-contracts'),
            "burn" => __('Burn', 'wp-smart-contracts'),
            "burn-tooltip" => __('Destroy (burn) an specific amount of tokens from your account', 'wp-smart-contracts'),
            "burn-from" => __('Burn from', 'wp-smart-contracts'),
            "burn-from-tooltip" => __('Burn tokens previously approved from an account', 'wp-smart-contracts'),
            "mint" => __('Mint', 'wp-smart-contracts'),
            "mint-tooltip" => __('Create new tokens and assign them to an account', 'wp-smart-contracts'),
            'add-minter' => __('Add Minter Role', 'wp-smart-contracts'),
            'tooltip-minter' => __('Allow this account to create tokens', 'wp-smart-contracts'),
            'exclude' => __('Exclude account', 'wp-smart-contracts'),
            'tooltip-exclude' => __('Exclude this account from the benefits of reflection', 'wp-smart-contracts'),
            'include' => __('Include account', 'wp-smart-contracts'),
            'tooltip-include' => __('Include this account back to the benefits of reflection', 'wp-smart-contracts'),
            'add-pauser' => __('Add Pauser Role', 'wp-smart-contracts'),
            'tooltip-pauser' => __('Allow this account to pause all activity in this contract', 'wp-smart-contracts'),
            "pause" => __('Pause', 'wp-smart-contracts'),
            "pause-tooltip" => __('Pause token activity', 'wp-smart-contracts'),
            "resume" => __('Resume', 'wp-smart-contracts'),
            "resume-tooltip" => __('Resume token activity', 'wp-smart-contracts'),
            "address-from" => __('From address', 'wp-smart-contracts'),
            "address-to" => __('To address', 'wp-smart-contracts'),
            "amount" => __('Amount', 'wp-smart-contracts'),
            "scan" => __('Scan', 'wp-smart-contracts'),
            "deploy-icon" => plugin_dir_url( dirname(__FILE__) ) . '/assets/img/deploy-identicon.gif',
            "flavor" => $wpsc_flavor,
            "cancel" => __('Cancel', 'wp-smart-contracts'),
            "tx-in-progress" => __('Transaction in progress', 'wp-smart-contracts'),
            "click-confirm" => __('If you agree and wish to proceed, please click "CONFIRM" transaction in your wallet, otherwise click "REJECT".', 'wp-smart-contracts'),
            "please-patience" => __('Please be patient. It can take several minutes. Don\'t close or reload this window.', 'wp-smart-contracts'),
            "renounce-pauser" => __('Renounce Pauser', 'wp-smart-contracts'),
            'tooltip-renounce-pauser' => __('Remove the pauser Role from your account', 'wp-smart-contracts'),
            "renounce-minter" => __('Renounce Minter', 'wp-smart-contracts'),
            'tooltip-renounce-minter' => __('Remove the minter Role from your account', 'wp-smart-contracts'),
        ];

        if ($wpsc_flavor == "chocolate") {
            $atts_coin_view_wallet["is_chocolate"] = true;
        }

        if ($wpsc_flavor == "macadamia") {
            $atts_coin_view_wallet["is_macadamia"] = true;
        }

        if ($wpsc_txid) {
            $atts_coin_view_addresses["txid-exists"] = true;
        }

        if ($wpsc_contract_address) {
            $atts_coin_view_wallet["contract-exists"] = true;
            $atts_coin_view_token["contract-exists"] = true;
            $atts_coin_view_addresses["contract-exists"] = true;
            $block_explorer_atts["contract-exists"] = true;
        }

        $atts_source_code = WPSC_Metabox::wpscGetMetaSourceCodeAtts($the_id);

        $msg_box = "";

        if (WPSC_helpers::valArrElement($_GET, 'welcome')) {
            $actual_link = strtok("https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]", '?');
            $msg_box = $m->render(
                WPSC_Mustache::getTemplate('msg-box'), 
                [
                    'type' => 'info',
                    'icon' => 'info',
                    'title' => __('Important Information', 'wp-smart-contracts'),
                    'msg' => "<p>" . __("Your contract was successfully deployed to the address: ", "wp-smart-contracts") . $atts["contract-address"] . "</p>" .
                        "<p>" . __("The URL of your block explorer is: ", "wp-smart-contracts") . $actual_link . "</p>" .
                        "<p>".__("Please store this information for future reference", "wp-smart-contracts")."</p>"
                    ]
                );
        }

        return $m->render(WPSC_Mustache::getTemplate('coin-view'), [

            'msg-box' => $msg_box,

            "main-nav" => $m->render(WPSC_Mustache::getTemplate('launcher-main-nav'), self::addLauncherMainNavAtts(["section-2"=>__("Cryptocurrencies", "wp-smart-contracts")])),

            'view-metamask' => self::viewMetamask($m),

            'coin-view-brand' => (WPSC_helpers::valArrElement($params, 'hide-brand') and $params['hide-brand'] and $params['hide-brand']=="true")?'':
                $m->render(
                    WPSC_Mustache::getTemplate('coin-view-brand'), 
                    [
                        'title' => $wpsc_title,
                        'title2' => $wpsc_coin_name . " (" . $wpsc_coin_symbol . ")",
                        'social-networks' => $social_networks,
                        'content' => $wpsc_content,
                        'thumbnail' => $wpsc_thumbnail,
                        'blockie' => get_post_meta($the_id, 'wpsc_blockie', true),
                        'page-thumbnail' => get_the_post_thumbnail_url($the_id),
                    ]
                ),

            'coin-view-token' => (WPSC_helpers::valArrElement($params, 'hide-token') and $params['hide-token'] and $params['hide-token']=="true")?'':
                $m->render(
                    WPSC_Mustache::getTemplate('coin-view-token'), 
                    $atts_coin_view_token
                ),
            
            'coin-view-addresses' => (WPSC_helpers::valArrElement($params, 'hide-address') and $params['hide-address'] and $params['hide-address']=="true")?'':
                $m->render(
                    WPSC_Mustache::getTemplate('coin-view-addresses'), 
                    $atts_coin_view_addresses
                ),
            
            'coin-view-wallet' => (WPSC_helpers::valArrElement($params, 'hide-wallet') and $params['hide-wallet'] and $params['hide-wallet']=="true")?'':
                $m->render(
                    WPSC_Mustache::getTemplate('coin-view-wallet'),
                    $atts_coin_view_wallet
                ),
            
            'coin-view-block-explorer' =>  (WPSC_helpers::valArrElement($params, 'hide-block') and $params['hide-block'] and $params['hide-block']=="true")?'':
                $m->render(
                    WPSC_Mustache::getTemplate('coin-view-block-explorer'), 
                    $block_explorer_atts
                ),

            'coin-view-audit' =>   (WPSC_helpers::valArrElement($params, 'hide-audit') and $params['hide-audit'] and $params['hide-audit']=="true")?'':
                $m->render(
                  WPSC_Mustache::getTemplate('coin-view-audit'),
                  $atts_source_code
                ),

        ]);

    }

    public function crowdfunding($params) {

        $the_id = self::getPostID($params);

        $wpsc_thumbnail = get_the_post_thumbnail_url($the_id);
        $wpsc_title = get_the_title($the_id);
        $wpsc_content = get_post_field('post_content', $the_id);

        $wpsc_network = get_post_meta($the_id, 'wpsc_network', true);
        $wpsc_txid = get_post_meta($the_id, 'wpsc_txid', true);
        $wpsc_owner = get_post_meta($the_id, 'wpsc_owner', true);
        $wpsc_contract_address = get_post_meta($the_id, 'wpsc_contract_address', true);
        $wpsc_blockie = get_post_meta($the_id, 'wpsc_blockie', true);
        $wpsc_blockie_owner = get_post_meta($the_id, 'wpsc_blockie_owner', true);
        $wpsc_qr_code = get_post_meta($the_id, 'wpsc_qr_code', true);

        list($color, $icon, $etherscan, $network_val) = WPSC_Metabox::getNetworkInfo($wpsc_network);

        $m = new Mustache_Engine;

        // initialization
        $wpsc_flavor        = null;
        $wpsc_minimum       = null;
        $wpsc_approvers     = null;
        $atts = [];

        // show contract
        if ($wpsc_contract_address) {

            $wpsc_flavor        = get_post_meta($the_id, 'wpsc_flavor', true);
            $wpsc_minimum       = get_post_meta($the_id, 'wpsc_minimum', true);
            $wpsc_approvers     = get_post_meta($the_id, 'wpsc_approvers', true);

            $crowdInfo = [
                "type" => $wpsc_flavor,
                "factor" => $wpsc_approvers,
                "minimum" => $wpsc_minimum,
                "size" => "mini",
                "approvers_label" => __("Approvers Percentage", "wp-smart-contracts"),
                "minimum_label" => __("Minimum", "wp-smart-contracts")
            ];
            if ($wpsc_flavor=="mango") $crowdInfo["color"] = "orange";
            if ($wpsc_flavor=="bluemoon") $crowdInfo["color"] = "teal";
            if ($wpsc_flavor=="bubblegum") $crowdInfo["color"] = "blue";
            $crowdInfo["imgUrl"] = dirname( plugin_dir_url( __FILE__ ) ) . '/assets/img/';

            $networks = WPSC_helpers::getNetworks();
            
            $crowdInfo["coin"] = $networks[$wpsc_network]["coin-symbol"];

            $atts = [
                'ethereum-network' => $network_val,
                'ethereum-color' => $color,
                'ethereum-icon' => $icon,
                'contract-address' => $wpsc_contract_address,
                'etherscan' => $etherscan,
                'contract-address-text' => __('Contract Address', 'wp-smart-contracts'),
                'txid-text' => __('Transaction ID', 'wp-smart-contracts'),
                'owner-text' => __('Owner Account', 'wp-smart-contracts'),
                'qr-code' => $wpsc_qr_code,
                'blockie' => $wpsc_blockie,
                'blockie-owner' => $wpsc_blockie_owner,
                'crowd-info' => $m->render(WPSC_Mustache::getTemplate('crowdfunding-info'), $crowdInfo),
                'crowd-logo' => get_the_post_thumbnail_url($the_id),
                'contract-address-short' => WPSC_helpers::shortify($wpsc_contract_address),
                'txid' => $wpsc_txid,
                'txid-short' => WPSC_helpers::shortify($wpsc_txid),
                'owner' => $wpsc_owner,
                'owner-short' => WPSC_helpers::shortify($wpsc_owner)
            ];

            if ($wpsc_txid) {
                $atts["txid-exists"] = true;
            }

        }

        $atts_crowd_view_contract = [
            'blockie' => WPSC_helpers::valArrElement($atts, 'blockie')?$atts["blockie"]:null,
            'contract-name' => __('Crowdfunding', 'wp-smart-contracts'),
            'color' => $color,
            'icon' => $icon,
            'ethereum-network' => WPSC_helpers::valArrElement($atts, 'ethereum-network')?$atts["ethereum-network"]:null,
            'crowd-info' => WPSC_helpers::valArrElement($atts, 'crowd-info')?$atts["crowd-info"]:null,
            'title' => $wpsc_title,
            'content' => $wpsc_content,
            'thumbnail' => $wpsc_thumbnail
        ];

        $atts_crowd_view_panel = [
            'network' => $wpsc_network,
            'minimum' => $wpsc_minimum,
            'minimum-contribution' => __('Minimum contribution', 'wp-smart-contracts'),
            'panel' => __('Contributions', 'wp-smart-contracts'),
            'requests' => __('Requests', 'wp-smart-contracts'),
            'balance' => __('Balance', 'wp-smart-contracts'),
            'contribute' => __('Contribute', 'wp-smart-contracts'),
            'contribute-tooltip' => __('Amount to donate to the campaign', 'wp-smart-contracts'),
            'send' => __('Send', 'wp-smart-contracts'),
            'cancel' => __('Cancel', 'wp-smart-contracts'),
            'amount' => __('Amount', 'wp-smart-contracts'),
            'contributors' => __('Contributors', 'wp-smart-contracts'),
            'approve' => __('Approve', 'wp-smart-contracts'),
            'create-request' => __('Create Request', 'wp-smart-contracts'),
            'request' => __('Create request', 'wp-smart-contracts'),
            'description' => __('Add a description', 'wp-smart-contracts'),
            'create-request-tooltip' => __('A request to withdraw funds from the contract. Requests must be approved by approvers', 'wp-smart-contracts'),
            'finalize-request' => __('Finalize Request', 'wp-smart-contracts'),
            'scan' => __('Scan', 'wp-smart-contracts'),
            'address-to' => __('Destination address', 'wp-smart-contracts'),
            'wpsc-contract-address' => $wpsc_contract_address,
            'wpsc-flavor' => $wpsc_flavor,
            "tx-in-progress" => __('Transaction in progress', 'wp-smart-contracts'),
            "click-confirm" => __('If you agree and wish to proceed, please click "CONFIRM" transaction in your wallet, otherwise click "REJECT".', 'wp-smart-contracts'),
            "please-patience" => __('Please be patient. It can take several minutes. Don\'t close or reload this window.', 'wp-smart-contracts'),
            "deploy-icon" => plugin_dir_url( dirname(__FILE__) ) . '/assets/img/deploy-identicon.gif',
            "check-contribution" => __('Check contribution', 'wp-smart-contracts'),
        ];

        if ($wpsc_txid) {
            $atts_crowd_view_addresses["txid-exists"] = true;
        }

        if ($wpsc_contract_address) {
            $atts_crowd_view_contract["contract-exists"] = true;
            $atts_crowd_view_addresses["contract-exists"] = true;
            $atts_crowd_view_panel["contract-exists"] = true;
        }

        $atts_source_code = WPSC_Metabox::wpscGetMetaSourceCodeAtts($the_id);

        $msg_box = "";

        if (WPSC_helpers::valArrElement($_GET, 'welcome')) {
            $actual_link = strtok("https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]", '?');
            $msg_box = $m->render(
                WPSC_Mustache::getTemplate('msg-box'), 
                [
                    'type' => 'info',
                    'icon' => 'info',
                    'title' => __('Important Information', 'wp-smart-contracts'),
                    'msg' => "<p>" . __('Your contract was successfully deployed to the address: ', 'wp-smart-contracts') . $atts["contract-address"] . "</p>" .
                        "<p>" . __('The URL of your Crowdfunding is: ', 'wp-smart-contracts') . $actual_link . "</p>" .
                        "<p>" . __('Please store this information for future reference.', 'wp-smart-contracts') . "</p>"
                    ]
                );
        }

        return $m->render(WPSC_Mustache::getTemplate('crowd-view'), [

            'msg-box' => $msg_box,
            'view-metamask' => self::viewMetamask($m),
            "main-nav" => $m->render(WPSC_Mustache::getTemplate('launcher-main-nav'), self::addLauncherMainNavAtts(["section-2"=>__("Crowdfundings", "wp-smart-contracts")])),
            'crowd-view-brand' => (WPSC_helpers::valArrElement($params, 'hide-brand') and $params['hide-brand'] and $params['hide-brand']=="true")?'':
                $m->render(
                    WPSC_Mustache::getTemplate('crowd-view-brand'), 
                    $atts_crowd_view_contract
                ),
            
            'crowd-view-panel' => (WPSC_helpers::valArrElement($params, 'hide-panel') and $params['hide-panel'] and $params['hide-panel']=="true")?'':
                $m->render(
                    WPSC_Mustache::getTemplate('crowd-view-panel'), 
                    $atts_crowd_view_panel
                ),

            'crowd-view-audit' =>   (WPSC_helpers::valArrElement($params, 'hide-audit') and $params['hide-audit'] and $params['hide-audit']=="true")?'':
                $m->render(
                  WPSC_Mustache::getTemplate('crowd-view-audit'),
                  $atts_source_code
                ),

        ]);

    }

    // return a timestamp using UTC time
    static public function utc_timestamp($input) {
        $utc_time_zone = new DateTimeZone("UTC");
        $date = new DateTime( $input, $utc_time_zone );            
        return $date->format('U');
    }

    static private function drawCollections() {

        $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";

        $const = "THEIDGOESHERE";

        $link_with_arg = str_replace($const, "", add_query_arg("id", $const, $actual_link));

        $posts = get_posts([
            'post_type' => 'nft-collection',
            'post_status' => 'publish',
            'numberposts' => -1
        ]);
        foreach($posts as $i=>$p) {
            $posts[$i]->img=get_the_post_thumbnail_url($p->ID);
            $posts[$i]->permalink=get_permalink($p->ID);
            if (!$p->post_title) {
                $p->post_title = "Untitled Collection (" . $p->ID . ")";
            }
        }
        $m = new Mustache_Engine;
        return $m->render(WPSC_Mustache::getTemplate('nft-collections'), [
            "cards"=>$posts, 
            'nft-view-menu' => self::getViewMenu($m, 0, null),
            "link_with_arg" => $link_with_arg,
            "nft-collections" => __("NFT Collections", 'wp-smart-contracts'),
            "choose-the-collection" => __("Choose the collection", 'wp-smart-contracts'),
        ]);
    }

    static public function validateNFTFE($collection_id, $nft_id=0) {

		if (!$collection_id) return __("Invalid collection ID", 'wp-smart-contracts');
		
        if ($nft_id) {
            $post_type = get_post_field ('post_type', $nft_id);
            if ($post_type != "nft") return __("Unexpected error, wrong post type.", 'wp-smart-contracts');
            $wpsc_item_collection = get_post_field ('wpsc_item_collection', $nft_id);
            if ($wpsc_item_collection != $collection_id) return __("Unexpected error, invalid collection id or nft id.", 'wp-smart-contracts');
        }
		
		if (get_post_type($collection_id)!="nft-collection") return __("Invalid collection ID", 'wp-smart-contracts');
	
		return false;
	
	}

    public function nftAuthor($params) {

        $collection_id = false;
        $author_id = false;

        if (WPSC_helpers::valArrElement($_GET, 'id')) {
            $collection_id = (int) $_GET["id"];
        }
        if (WPSC_helpers::valArrElement($_GET, 'a')) {
            $author_id = (int) $_GET["a"];
        }

        if (!$collection_id) return self::drawCollections();
        if (!$author_id) return "Invalid Author ID";

        $collection = get_post($collection_id);

        $data["wpsc_nft_token_uri"] = get_rest_url(null, 'wpsc/v1/nft/');

        $data["wpsc-contract-flavor"] = get_post_meta($collection_id, "wpsc_flavor", true);
        $data["wpsc-nft-marketplace-contract"] = get_post_meta($collection_id, "wpsc_contract_address", true);
        $data["wpsc-nft-network"] = get_post_meta($collection_id, "wpsc_network", true);
        $data['fox'] = plugins_url( "assets/img/metamask-fox.svg", dirname(__FILE__) );
        
        $data['text'] = __('You are not connected', 'wp-smart-contracts');
        $data['connect-to-metamask'] = __('Connect to Metamask', 'wp-smart-contracts');
        $data['text-wrong-net'] = __('You are connected to a different network, or contract not deployed', 'wp-smart-contracts');
        $data['collection-id'] = $collection_id;
        $data['collection-name'] = get_the_title($collection_id);
        $data["collection-link"] = get_permalink($collection_id);

        $m = new Mustache_Engine;

        $data['nft-view-menu'] = self::getViewMenu($m, $collection_id, null);

        list($data["color"], $data["icon"], $data["etherscan"], $data["network_val"]) = WPSC_Metabox::getNetworkInfo(get_post_meta($collection_id, 'wpsc_network', true));

        $data['wpsc-nft-option'] = "collection";
        
        $nft_ids = WPSC_Queries::getNFTIdsByAuthor($collection_id, $author_id, WPSC_helpers::getGalleryIdCookie($collection_id));

        $user_data = get_userdata($author_id);
        $author["avatar"] = get_avatar_url($author_id);
        $author["description"] = get_the_author_meta("description", $author_id);
        $data['breadcrumb-level2'] = $author["display_name"] = $user_data->display_name;
        $author["user_url"] = $user_data->user_url;
        $author["user_registered"] = date("F jS, Y", strtotime($user_data->user_registered));
        $author["joined-in"] = __('Joined in', 'wp-smart-contracts');
        
        $data["custom-title"] = $m->render(WPSC_Mustache::getTemplate('nft-author'), $author);

        if (!empty($nft_ids)) {
            $data['wpsc-nft-params-nft-ids'] = json_encode(array_column($nft_ids, "nft_id"));
        }

        $data["nft-items-per-page"] = WPSCSettingsPage::nftItemsPerPage();

        $data['view-metamask'] = self::viewMetamask($m);

        $data["blockie"] = get_post_meta($collection_id, 'wpsc_blockie', true);
        $data['page-thumbnail'] = get_the_post_thumbnail_url($collection_id);
        $data["section-2"] = __("NFT Collections", "wp-smart-contracts");
        $data["main-nav"] = $m->render(WPSC_Mustache::getTemplate('launcher-main-nav'), self::addLauncherMainNavAtts($data));

        if ($wpsc_sub_collections = get_post_meta($collection_id, 'wpsc_sub_collections', true)) {	
            $data["show-sub-collections"]=true;	
            $data["galleries-menu"] = $m->render(	
                WPSC_Mustache::getTemplate('galleries-menu'), 	
                self::getGalleriesData($collection_id, $wpsc_sub_collections)	
            );	
            $data["galleries"]=WPSC_helpers::getGalleriesOfCollection($collection_id);	
        }

        return $m->render(WPSC_Mustache::getTemplate('nft-my-items'), $data);

    }

    public function nftMyGalleries($params) {	
        $collection_id = 0;	
        if (WPSC_helpers::valArrElement($_GET, 'id')) {	
            $collection_id = (int) $_GET["id"];	
        } elseif (WPSC_helpers::valArrElement($_POST, 'id')) {	
            $collection_id = (int) $_POST["id"];	
        }	
        if (!$collection_id) return self::drawCollections();	
		if (get_post_type($collection_id)!="nft-collection") return self::drawCollections();	
        $m = new Mustache_Engine;	
        if ( is_user_logged_in() ) {	
            $data["wpsc-contract-flavor"] = get_post_meta($collection_id, "wpsc_flavor", true);	
            $data["wpsc-nft-marketplace-contract"] = get_post_meta($collection_id, "wpsc_contract_address", true);	
            $data["wpsc-nft-network"] = get_post_meta($collection_id, "wpsc_network", true);	
            $data['fox'] = plugins_url( "assets/img/metamask-fox.svg", dirname(__FILE__) );        	
            $data['text'] = __('You are not connected', 'wp-smart-contracts');	
            $data['connect-to-metamask'] = __('Connect to Metamask', 'wp-smart-contracts');	
            $data['text-wrong-net'] = __('You are connected to a different network, or contract not deployed', 'wp-smart-contracts');	
            $data['collection-id'] = $collection_id;	
            $data['collection-name'] = get_the_title($collection_id);	
            $data["collection-link"] = get_permalink($collection_id);	
            $data['nft-view-menu'] = self::getViewMenu($m, $collection_id, "my-galleries");	
            list($data["color"], $data["icon"], $data["etherscan"], $data["network_val"]) = WPSC_Metabox::getNetworkInfo(get_post_meta($collection_id, 'wpsc_network', true));	
            $data['page-title'] = "My Galleries";	
            $data['wpsc-nft-option'] = "my-galleries";	
            $data['view-metamask'] = $m->render(	
                WPSC_Mustache::getTemplate('crowd-view-metamask'), 	
                [	
                    "metamask-not-found" => __('Metamask not found', 'wp-smart-contracts'),
                    "click-to-install" => __('Click to install', 'wp-smart-contracts'),
                    "connect-to-metamask" => __('Connect to Metamask', 'wp-smart-contracts'),
                    "text-wrong-net" => __('The wrong network is selected', 'wp-smart-contracts'),
                    "wrong-network" => __('Wrong network', 'wp-smart-contracts'),
                    "please-disconnect-and-connect-wallet-to" => __('Please disconnect and connect your wallet to', 'wp-smart-contracts'),
                    "no-web3-provider-options-found-for-wallet-connect" => __('No web3 provider options found for Wallet Connect', 'wp-smart-contracts'),
                    "disconnect" => __('Disconnect', 'wp-smart-contracts'),
                    "network-name" => __('Network Name', 'wp-smart-contracts'),
                    'fox' => plugins_url( "assets/img/metamask-fox.svg", dirname(__FILE__) ), 	
                    'wallet-connect-logo' => plugins_url( "assets/img/wallet-connect-logo.png", dirname(__FILE__) ),	
                    'other-wallets-logo' => plugins_url( "assets/img/other-wallets.png", dirname(__FILE__) ),	
                    'text' => __('You are not connected', 'wp-smart-contracts'),	
                    'text-wrong-net' => __('Wrong network', 'wp-smart-contracts'),	
                    'network-name' => $data["network_val"],
                    'metamask-not-found' => __('Metamask not found', 'wp-smart-contracts'),
                    'click-to-install' => __('Click to install', 'wp-smart-contracts'),
                    'connect-to-metamask' => __('Connect to Metamask', 'wp-smart-contracts'),
                    'wrong-network' => __('Wrong network. Please disconnect and connect your wallet to', 'wp-smart-contracts'),
                    'no-web3-provider' => __('No web3 provider options found for Wallet Connect', 'wp-smart-contracts'),
                    'disconnect' => __('Disconnect', 'wp-smart-contracts')
                ]	
            );	
            $user = get_current_user_id();	
            if (WPSC_helpers::valArrElement($_POST, "wpsc-go") and $_POST["wpsc-go"]=="1") {	
                WPSC_NFTGallery::removeAll($user);	
            }	
            if (WPSC_helpers::valArrElement($_POST, "wpsc-galleries") and $wpsc_input_galleries = $_POST["wpsc-galleries"]) {	
                $wpsc_input_galleries_arr = explode(",", $wpsc_input_galleries);	
                if (is_array($wpsc_input_galleries_arr)) {	
                    foreach($wpsc_input_galleries_arr as $gallery_id) {	
                        $gallery_id = (int) $gallery_id;	
                        if ($gallery_id) {	
                            WPSC_NFTGallery::add($user, $gallery_id);	
                        }	
                    }	
                }	
            }	
            if (WPSC_helpers::valArrElement($_POST, "wpsc-new-galleries") and $wpsc_new_galleries = $_POST["wpsc-new-galleries"]) {	
                $wpsc_new_galleries_arr = explode(",", $wpsc_new_galleries);	
                if (is_array($wpsc_new_galleries_arr)) {	
                    foreach($wpsc_new_galleries_arr as $gallery_name) {	
                        // get the galleries with this name from terms	
                        $terms = WPSC_Queries::getNFTGalleriesByName($gallery_name);	
                        $belongs_to_user = false;	
                        $gals = WPSC_NFTGallery::get($user);	
                        // loop them	
                        if (is_array($terms) and !empty($gals)) {	
                            foreach($terms as $t) {	
                                // it belongs to the user?	
                                if (array_search($t["term_id"], $gals)!==false) {	
                                    // set a flag to true	
                                    $belongs_to_user = true;	
                                    break;	
                                }	
                            }	
                        }	
                        // if there is no gallery that belongs to the user	
                        if (!$belongs_to_user) {	
                            WPSC_NFTGallery::createGalleryForUserID($user, $gallery_name);	
                        }	
                        	
                    }	
                }	
            }	
            	
            $galleries = WPSC_NFTGallery::get($user);	
            if (is_array($galleries)) {	
                $the_value = implode(',', $galleries);	
            } else {	
                $the_value = '';	
            }	
            $data['page-title'] = "My Galleries";
            $data['save'] = "Save";
            $data['wpsc-nft-option'] = "my-galleries";	
            $data["html"] = $m->render(WPSC_Mustache::getTemplate('wpsc-table-galleries'), [	
                "the-user-has-no-galleries" => __('The user has no galleries.', 'wp-smart-contracts'),
                "add-new-galleries-comma-separated" => __('Add New Galleries (comma separated)', 'wp-smart-contracts'),
                "do-you-want-to-add-new-galleries" => __('Do you want to add new galleries?', 'wp-smart-contracts'),
                "add-them-on-a-comma-separated-list-here" => __('Add them on a comma-separated list here.', 'wp-smart-contracts'),
                "title"=>"",	
                "id"=>"wpsc-table-in",	
                "id_term"=>"wpsc-term-in",
                "galleries"=>WPSC_NFTGallery::get($user, true),	
                "confirm"=>true,	
                "add"=>true,	
                "the-hidden-value"=>$the_value,	
                "collection-id"=>$collection_id	
            ]);	
            if (WPSC_helpers::valArrElement($_POST, "wpsc-go") and $_POST["wpsc-go"]=="1" and $collection_id) {	
                $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";	
                echo "<script>location.href=\"" . $actual_link . "\";</script>";	
            }	
            
            return $m->render(WPSC_Mustache::getTemplate('nft-my-galleries'), $data);	
        } else {	
            $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";	
            return $m->render(WPSC_Mustache::getTemplate('wpsc-message'), ["title"=>"Only registered users", "message"=>__("This option is available only for logged-in users, please <a href=\"".wp_login_url($actual_link)."\">login here</a>")]);	
        }	
    }

    public function nftMyBids($params) {

        $collection_id = (int) $_GET["id"];

        if (!$collection_id) return self::drawCollections();
		if (get_post_type($collection_id)!="nft-collection") self::drawCollections();

        $data["wpsc_nft_token_uri"] = get_rest_url(null, 'wpsc/v1/nft/');

        $data["wpsc-contract-flavor"] = get_post_meta($collection_id, "wpsc_flavor", true);
        $data["wpsc-nft-marketplace-contract"] = get_post_meta($collection_id, "wpsc_contract_address", true);
        $data["wpsc-nft-network"] = get_post_meta($collection_id, "wpsc_network", true);
        $data['fox'] = plugins_url( "assets/img/metamask-fox.svg", dirname(__FILE__) );
        
        $data['text'] = __('You are not connected', 'wp-smart-contracts');
        $data['connect-to-metamask'] = __('Connect to Metamask', 'wp-smart-contracts');
        $data['text-wrong-net'] = __('You are connected to a different network, or contract not deployed', 'wp-smart-contracts');
        $data['collection-id'] = $collection_id;
        $data['collection-name'] = get_the_title($collection_id);
        $data["collection-link"] = get_permalink($collection_id);

        $m = new Mustache_Engine;

        $data['nft-view-menu'] = self::getViewMenu($m, $collection_id, "my-bids");

        $data['page-title'] = "My Bids";
        $data['wpsc-nft-option'] = "my-bids";
        list($data["color"], $data["icon"], $data["etherscan"], $data["network_val"]) = WPSC_Metabox::getNetworkInfo(get_post_meta($collection_id, 'wpsc_network', true));

        $data["nft-items-per-page"] = WPSCSettingsPage::nftItemsPerPage();

        $data['view-metamask'] = self::viewMetamask($m);
        
        $nft_ids = WPSC_Queries::getNFTIds($collection_id, WPSC_helpers::getGalleryIdCookie($collection_id));

        if (!empty($nft_ids)) {
            $data['wpsc-nft-params-nft-ids'] = json_encode(array_column($nft_ids, "nft_id"));
        }

        if ($wpsc_sub_collections = get_post_meta($collection_id, 'wpsc_sub_collections', true)) {	
            $data["show-sub-collections"]=true;	
            $data["galleries-menu"] = $m->render(	
                WPSC_Mustache::getTemplate('galleries-menu'), 	
                self::getGalleriesData($collection_id, $wpsc_sub_collections)	
            );	
            $data["galleries"]=WPSC_helpers::getGalleriesOfCollection($collection_id);	
        }

        $data["blockie"] = get_post_meta($collection_id, 'wpsc_blockie', true);
        $data['page-thumbnail'] = get_the_post_thumbnail_url($collection_id);
        $data["section-2"] = __("NFT Collections", "wp-smart-contracts");
        $data["main-nav"] = $m->render(WPSC_Mustache::getTemplate('launcher-main-nav'), self::addLauncherMainNavAtts($data));

        return $m->render(WPSC_Mustache::getTemplate('nft-my-items'), $data);

    }
    
    public function nftMyItems($params) {

        $collection_id = 0;
        if (WPSC_helpers::valArrElement($_GET, 'id')) {
            $collection_id = (int) $_GET["id"];
        }

        if (!$collection_id) return self::drawCollections();
		if (get_post_type($collection_id)!="nft-collection") return self::drawCollections();

        $data["wpsc_nft_token_uri"] = get_rest_url(null, 'wpsc/v1/nft/');

        $data["wpsc-contract-flavor"] = get_post_meta($collection_id, "wpsc_flavor", true);
        $data["wpsc-nft-marketplace-contract"] = get_post_meta($collection_id, "wpsc_contract_address", true);
        $data["wpsc-nft-network"] = get_post_meta($collection_id, "wpsc_network", true);
        $data['fox'] = plugins_url( "assets/img/metamask-fox.svg", dirname(__FILE__) );
        
        $data['text'] = __('You are not connected', 'wp-smart-contracts');
        $data['connect-to-metamask'] = __('Connect to Metamask', 'wp-smart-contracts');
        $data['text-wrong-net'] = __('You are connected to a different network, or contract not deployed', 'wp-smart-contracts');
        $data['collection-id'] = $collection_id;
        $data['collection-name'] = get_the_title($collection_id);
        $data["collection-link"] = get_permalink($collection_id);

        $m = new Mustache_Engine;

        $data['nft-view-menu'] = self::getViewMenu($m, $collection_id, "my-items");

        list($data["color"], $data["icon"], $data["etherscan"], $data["network_val"]) = WPSC_Metabox::getNetworkInfo(get_post_meta($collection_id, 'wpsc_network', true));

        $data['page-title'] = __("My Items", "wp-smart-contracts");
        $data['wpsc-nft-option'] = "my-items";

        $data["nft-items-per-page"] = WPSCSettingsPage::nftItemsPerPage();

        $data['view-metamask'] = self::viewMetamask($m);

        $nft_ids = WPSC_Queries::getNFTIds($collection_id);
        if (!empty($nft_ids)) {
            $data['wpsc-nft-params-nft-ids'] = json_encode(array_column($nft_ids, "nft_id"));
        }

        $data["blockie"] = get_post_meta($collection_id, 'wpsc_blockie', true);
        $data['page-thumbnail'] = get_the_post_thumbnail_url($collection_id);
        $data["section-2"] = __("NFT Collections", "wp-smart-contracts");
        $data["main-nav"] = $m->render(WPSC_Mustache::getTemplate('launcher-main-nav'), self::addLauncherMainNavAtts($data));
        $data["load-more"] = __("Load more", "wp-smart-contracts");

        return $m->render(WPSC_Mustache::getTemplate('nft-my-items'), $data);
    }

    public function nftMint($params) {

        $collection_id = $nft_id = null;
        
        if (WPSC_helpers::valArrElement($_GET, 'id')) {
            $collection_id = (int) $_GET["id"];
        }
        if (WPSC_helpers::valArrElement($_GET, 'nft_id')) {
            $nft_id = (int) $_GET["nft_id"];
        }

        if (!$collection_id) return self::drawCollections();

        $taxs = self::getTaxonomy('nft-taxonomy', $nft_id);
        $tags = self::getTaxonomy('nft-tag', $nft_id);
        $gals = WPSC_NFTGallery::get(get_current_user_id(), true, $nft_id);

        $m = new Mustache_Engine;

        if ($error = self::validateNFTFE($collection_id, $nft_id)) {
            return $m->render(WPSC_Mustache::getTemplate('wpsc-message'), ["error"=>true, "title" => __("An error has occurred", "wp-smart-contracts"), "message"=>$error]);
        } else {

            $data = [
                "taxs"=>$taxs,
                "tags"=>$tags, 
                "gals"=>$gals,
                "collection-id"=>$collection_id, 
                "nft-id"=>$nft_id
            ];

            if ($nft_id) {

                $nft_item = get_post($nft_id);

                if ($nft_item->post_type=="nft") {

                    $data["post_content"]    = $nft_item->post_content;
                    $data["post_title"]      = $nft_item->post_title;
                    $data["wpsc_nft_owner"]  = get_post_meta($nft_id, "wpsc_nft_owner", true);
                    $data["wpsc_nft_supply"] = get_post_meta($nft_id, "wpsc_nft_supply", true);
                    $data["wpsc_txid"]       = get_post_meta($nft_id, "wpsc_txid", true);
                    
                    $wpsc_media_type = get_post_meta($nft_id, "wpsc_media_type", true);

                    switch($wpsc_media_type) {
                    case "image":
                        $data["wpsc_media_type_image"] = true;
                        break;
                    case "video":
                        $data["wpsc_media_type_video"] = true;
                        break;
                    case "audio":
                        $data["wpsc_media_type_audio"] = true;
                        break;
                    case "document":
                        $data["wpsc_media_type_document"] = true;
                        break;
                    case '3dmodel':
                        $data["wpsc_media_type_3dmodel"]=true;
                        break;
                    }

                    $data["wpsc_nft_media_json"] = get_post_meta($nft_id, "wpsc_nft_media_json", true);

                }
        
            }

            $data["wpsc_nft_token_uri"] = get_rest_url(null, 'wpsc/v1/nft/');

            $data["wpsc-contract-flavor"] = get_post_meta($collection_id, "wpsc_flavor", true);
            if ($data["wpsc-contract-flavor"]=="yuzu" or $data["wpsc-contract-flavor"]=="ikasumi" or $data["wpsc-contract-flavor"]=="azuki") {
                $data["is-erc-1155"] = true;
                if (WPSC_helpers::valArrElement($data, 'wpsc_txid') and $data['wpsc_txid']) {
                    $data["is-erc-1155-deployed"] = true;
                }
            } else {
                $data["is-erc-721"] = true;
            }

            $data["wpsc-nft-marketplace-contract"] = get_post_meta($collection_id, "wpsc_contract_address", true);
            $data["wpsc-nft-network"] = get_post_meta($collection_id, "wpsc_network", true);
            $data['fox'] = plugins_url( "assets/img/metamask-fox.svg", dirname(__FILE__) );
            
            list($data["color"], $data["icon"], $data["etherscan"], $data["network_val"]) = WPSC_Metabox::getNetworkInfo(get_post_meta($collection_id, 'wpsc_network', true));
            
            $data['text'] = __('You are not connected', 'wp-smart-contracts');
            $data['connect-to-metamask'] = __('Connect to Metamask', 'wp-smart-contracts');
            $data['text-wrong-net'] = __('You are connected to a different network, or contract not deployed', 'wp-smart-contracts');

            $data["mint-icon"] = dirname( plugin_dir_url( __FILE__ ) ) . '/assets/img/creature.png';
            $data["mint"] = __("Mint", "wp-smart-contracts");
            $data["mint-tooltip"] = __("Create the NFT Item on the Blockchain", "wp-smart-contracts");
            $data["scan"] = __("Scan", "wp-smart-contracts");
            $data["address-to"] = __("Beneficiary address", "wp-smart-contracts");
            $data["cancel"] = __("Cancel", "wp-smart-contracts");
            $data["click-confirm"] = __('If you agree and wish to proceed, please click "CONFIRM" transaction in your wallet, otherwise click "REJECT".', 'wp-smart-contracts');
            $data["please-patience"] = __('Please be patient. It can take several minutes. Don\'t close or reload this window.', 'wp-smart-contracts');
            $data["tx-in-progress"] = __('Transaction in progress', 'wp-smart-contracts');
            $data["deploy-icon"] = plugin_dir_url( dirname(__FILE__) ) . '/assets/img/animated.gif';
            
            $data["nft-view-menu"] = self::getViewMenu($m, $collection_id, "mint");
            $data["collection-name"] = get_the_title($collection_id);
            $data["collection-link"] = get_permalink($collection_id);

            $data['view-metamask'] = self::viewMetamask($m);
            $data["section-2"] = __("NFT Collections", "wp-smart-contracts");
            $data["main-nav"] = $m->render(WPSC_Mustache::getTemplate('launcher-main-nav'), self::addLauncherMainNavAtts($data));
            $data["item-editor"] = __("Item editor", "wp-smart-contracts");
            $data["mint"] = __("Mint", "wp-smart-contracts");

            $data["title"] = __("Title", "wp-smart-contracts");
            $data["description"] = __("Description", "wp-smart-contracts");
            $data["galleries"] = __("Galleries", "wp-smart-contracts");
            $data["categories"] = __("Categories", "wp-smart-contracts");
            $data["attributes"] = __("Attributes", "wp-smart-contracts");
            $data["supply"] = __("Supply supply", "wp-smart-contracts");
            $data["custom-attributes"] = __("Custom attributes (comma separated)", "wp-smart-contracts");
            $data["not-seeing-the"] = __("Not seeing the attributes you are looking for?", "wp-smart-contracts");
            $data["add-them-on"] = __("Add them on a comma separated list here. You can add up to 10 attributes.", "wp-smart-contracts");
            $data["media"] = __("Media", "wp-smart-contracts");
            $data["add-your-media"] = __("Add your media file here.", "wp-smart-contracts");
            $data["image"] = __("Image", "wp-smart-contracts");
            $data["an-image-is"] = __("An image is a unique file like a piece of art, a picture, profile, etc. Formats are .png, .jpg or .gif", "wp-smart-contracts");
            $data["video"] = __("Video", "wp-smart-contracts");
            $data["is-a-recording"] = __("Is a recording, animation, short movie, etc. For videos you can choose multiple files, but all files should correspond to same asset with different format, i.e. .mp4 and .ogv. This is because some video formats are more suitable than others on different devices.", "wp-smart-contracts");
            $data["audio"] = __("Audio", "wp-smart-contracts");
            $data["a-song-podcast"] = __("A song, podcast, effects, etc. You can choose multiple files, but all files should correspond to same asset, i.e. .mp3 and .ogg.", "wp-smart-contracts");
            $data["documents"] = __("Documents", "wp-smart-contracts");
            $data["this-is-a-unique-file"] = __("This is a unique file that corresponds to a property, license, loan, debt or any other document you need.", "wp-smart-contracts");
            $data["3d-model"] = __("3D Model", "wp-smart-contracts");
            $data["glb-interactive"] = __("GLB Interactive 3D models are supported", "wp-smart-contracts");
            $data["choose-your-media"] = __("Choose your media type", "wp-smart-contracts");
            $data["image"] = __("Image", "wp-smart-contracts");
            $data["video"] = __("Video", "wp-smart-contracts");
            $data["audio"] = __("Audio", "wp-smart-contracts");
            $data["document"] = __("Document", "wp-smart-contracts");
            $data["3d-model"] = __("3D Model", "wp-smart-contracts");
            $data["recipient"] = __("Recipient            ", "wp-smart-contracts");
            $data["this-will-be-owner"] = __("This will be the owner of the item created", "wp-smart-contracts");
            $data["scan"] = __("Scan", "wp-smart-contracts");
            $data["amount-minted"] = __("The amount/quantity to be minted", "wp-smart-contracts");
            $data["save-mint"] = __("Save & Mint", "wp-smart-contracts");

            $res = get_post_meta($collection_id, 'wpsc_sub_collections', true);
                
            if ($res) {
                $data["galleries-enabled"] = true;
            }

            $data["item-editor"] = __('Item editor', 'wp-smart-contracts');
            $data["item-editor-title"] = __('Item editor', 'wp-smart-contracts');
            $data["title"] = __('Title', 'wp-smart-contracts');
            $data["description"] = __('Description', 'wp-smart-contracts');
            $data["categories"] = __('Categories', 'wp-smart-contracts');
            $data["attributes"] = __('Attributes', 'wp-smart-contracts');
            $data["custom-attributes"] = __('Custom attributes', 'wp-smart-contracts');
            $data["comma-separated"] = __('comma separated', 'wp-smart-contracts');
            $data["not-seeing-attributes"] = __('Not seeing the attributes you are looking for?', 'wp-smart-contracts');
            $data["add-them-comma-separated-list"] = __('Add them on a comma separated list here. You can add up to 10 attributes.', 'wp-smart-contracts');
            $data["add-media"] = __('Add Media', 'wp-smart-contracts');
            $data["media"] = __('Media', 'wp-smart-contracts');
            $data["add-your-media-file-here"] = __('Add your media file here.', 'wp-smart-contracts');
            $data["image"] = __('Image', 'wp-smart-contracts');
            $data["image-description"] = __('An image is a unique file like a piece of art, a picture, profile, etc. Formats are .png, .jpg or .gif', 'wp-smart-contracts');
            $data["video"] = __('Video', 'wp-smart-contracts');
            $data["video-description"] = __('Is a recording, animation, short movie, etc. For videos you can choose multiple files, but all files should correspond to same asset with different format, i.e. .mp4 and .ogv. This is because some video formats are more suitable than others on different devices.', 'wp-smart-contracts');
            $data["audio"] = __('Audio', 'wp-smart-contracts');
            $data["audio-description"] = __('A song, podcast, effects, etc. You can choose multiple files, but all files should correspond to same asset, i.e. .mp3 and .ogg.', 'wp-smart-contracts');
            $data["documents"] = __('Documents', 'wp-smart-contracts');
            $data["documents-description"] = __('This is a unique file that corresponds to a property, license, loan, debt or any other document you need.', 'wp-smart-contracts');
            $data["3d-model"] = __('3D Model', 'wp-smart-contracts');
            $data["3d-model-description"] = __('GLB Interactive 3D models are supported', 'wp-smart-contracts');
            $data["choose-your-media-type"] = __('Choose your media type', 'wp-smart-contracts');
            $data["save-and-mint"] = __('Save & Mint', 'wp-smart-contracts');
            $data["recipient"] = __('Recipient', 'wp-smart-contracts');
            $data["owner-of-the-item-created"] = __('This will be the owner of the item created', 'wp-smart-contracts');
            $data["scan"] = __('Scan', 'wp-smart-contracts');
            $data["supply"] = __('Supply', 'wp-smart-contracts');
            $data["amount-quantity-to-be-minted"] = __('The amount/quantity to be minted', 'wp-smart-contracts');
            
            return $m->render(WPSC_Mustache::getTemplate('nft-collection-mint'), $data);
        }

    }

    private function integerify($arr_obj) {
        $ret = null;
        if (is_array($arr_obj)) {
            foreach($arr_obj as $obj) {
                $ret[] = $obj->term_id;
            }
        }
        return $ret;
    }

    private function getTaxonomy($taxonomy_slug, $nft_id) {
        $taxs = WPSC_Queries::getTaxonomy($taxonomy_slug);
        $tax_terms = self::integerify(wp_get_object_terms($nft_id, $taxonomy_slug));
        foreach ($taxs as $tax_id => $tax) {
            if (!empty($tax_terms) and array_search($tax["term_id"], $tax_terms)!==false) {
                $taxs[$tax_id]["selected"]=true;
            }
        }
        return $taxs;
    }
    
    public function nftTaxonomy($params) {

        $tax_id = get_queried_object()->term_id;

        @$collection_id = (int) $_GET['id'];

        $m = new Mustache_Engine;

        if ($params["taxonomy"]=="nft-gallery" and $collection_id) {	
            if (!get_post_meta($collection_id, 'wpsc_sub_collections', true)) {	
                return $m->render(WPSC_Mustache::getTemplate('wpsc-message'), 
                [
                    "error"=>true, 
                    "title"=>__("An error has occurred", "wp-smart-contracts"), 
                    "message"=>__("Galleries are not enabled in this collection", "wp-smart-contracts")
                ]);	
            }	
        }

        if (!$collection_id) return self::drawCollections();
        if (!$tax_id) return "Invalid Taxonomy ID";

        $collection = get_post($collection_id);

        $data["wpsc_nft_token_uri"] = get_rest_url(null, 'wpsc/v1/nft/');

        $data["wpsc-contract-flavor"] = get_post_meta($collection_id, "wpsc_flavor", true);
        $data["wpsc-nft-marketplace-contract"] = get_post_meta($collection_id, "wpsc_contract_address", true);
        $data["wpsc-nft-network"] = get_post_meta($collection_id, "wpsc_network", true);
        $data['fox'] = plugins_url( "assets/img/metamask-fox.svg", dirname(__FILE__) );
        
        $data['text'] = __('You are not connected', 'wp-smart-contracts');
        $data['connect-to-metamask'] = __('Connect to Metamask', 'wp-smart-contracts');
        $data['text-wrong-net'] = __('You are connected to a different network, or contract not deployed', 'wp-smart-contracts');
        $data['collection-id'] = $collection_id;
        $data['collection-name'] = get_the_title($collection_id);
        $data["collection-link"] = get_permalink($collection_id);

        $data['nft-view-menu'] = self::getViewMenu($m, $collection_id, null);

        list($data["color"], $data["icon"], $data["etherscan"], $data["network_val"]) = WPSC_Metabox::getNetworkInfo(get_post_meta($collection_id, 'wpsc_network', true));

        $data['breadcrumb-level2'] = $data['page-title'] = $term_name = get_term( $tax_id )->name;
        $data['page-content'] = wpautop(apply_filters('the_content', $collection->post_content));
        $data['wpsc-nft-option'] = "collection";

        $args = [
            'posts_per_page' => -1,
            'post_type' => 'nft',
            'fields' => 'ids',
            'tax_query' => [
                [
                    'taxonomy' => $params["taxonomy"],
                    'field' => 'term_id',
                    'terms' => $tax_id,
                ]
            ],
            'meta_query' => [
                [
                    'key'   => 'wpsc_item_collection',
                    'value' => $collection_id,
                ]
            ]
        ];

        $gallery_id = WPSC_helpers::getGalleryIdCookie($collection_id);	
        if ($gallery_id) {	
            $args["tax_query"][] = [	
                'taxonomy' => 'nft-gallery',	
                'field'    => 'id',	
                'terms'    => $gallery_id,	
            ];	
        }

        $nft_ids = self::getJSONNFTIds($args);

        $data['wpsc-nft-params-nft-ids'] = $nft_ids;

        $data["nft-items-per-page"] = WPSCSettingsPage::nftItemsPerPage();

        $data['view-metamask'] = self::viewMetamask($m);

        $data["blockie"] = get_post_meta($collection_id, 'wpsc_blockie', true);
        $data['page-thumbnail'] = get_the_post_thumbnail_url($collection_id);
        $data["section-2"] = __("NFT Collections", "wp-smart-contracts");
        $data["main-nav"] = $m->render(WPSC_Mustache::getTemplate('launcher-main-nav'), self::addLauncherMainNavAtts($data));
        $data["load-more"] = __("Load more", "wp-smart-contracts");

        if ($wpsc_sub_collections = get_post_meta($collection_id, 'wpsc_sub_collections', true)) {	
            $data["show-sub-collections"]=true;	
            $data["galleries-menu"] = $m->render(	
                WPSC_Mustache::getTemplate('galleries-menu'), 	
                self::getGalleriesData($collection_id, $wpsc_sub_collections)	
            );	
            $data["galleries"]=WPSC_helpers::getGalleriesOfCollection($collection_id);	
        }

        return $m->render(WPSC_Mustache::getTemplate('nft-my-items'), $data);

    }

    private static function getJSONNFTIds($args) {
        
        $posts_array = get_posts($args);

        $nft_ids = false;

        if (is_array($posts_array)) {
            foreach($posts_array as $post_id) {
                $wpsc_nft_id = get_post_meta($post_id, "wpsc_nft_id", true);
                if ($wpsc_nft_id) {
                    $nft_ids[] = $wpsc_nft_id;
                } else {
                    $nft_ids[] = "p" . $post_id;
                }
            }
            if ($nft_ids) {
                $nft_ids = json_encode($nft_ids);
            }
        }

        return $nft_ids;

    }

    public function nftCollection($params) {

        $collection_id = self::getPostID($params);

        if (!$collection_id) return self::drawCollections();
		if (get_post_type($collection_id)!="nft-collection") return self::drawCollections();

        $collection = get_post($collection_id);

        $data["wpsc_nft_token_uri"] = get_rest_url(null, 'wpsc/v1/nft/');

        $data["wpsc-contract-flavor"] = get_post_meta($collection_id, "wpsc_flavor", true);
        $data["wpsc-nft-marketplace-contract"] = get_post_meta($collection_id, "wpsc_contract_address", true);
        $data["wpsc-nft-network"] = get_post_meta($collection_id, "wpsc_network", true);
        $data['fox'] = plugins_url( "assets/img/metamask-fox.svg", dirname(__FILE__) );
        
        $data['text'] = __('You are not connected', 'wp-smart-contracts');
        $data['connect-to-metamask'] = __('Connect to Metamask', 'wp-smart-contracts');
        $data['text-wrong-net'] = __('You are connected to a different network, or contract not deployed', 'wp-smart-contracts');
        $data['collection-id'] = $collection_id;
        $data['collection-name'] = get_the_title($collection_id);
        $data["collection-link"] = get_permalink($collection_id);

        $m = new Mustache_Engine;

        $data['nft-view-menu'] = self::getViewMenu($m, $collection_id, null);

        list($data["color"], $data["icon"], $data["etherscan"], $data["network_val"]) = WPSC_Metabox::getNetworkInfo(get_post_meta($collection_id, 'wpsc_network', true));

        $data['page-title'] = $collection->post_title;
        $data['page-content'] = wpautop(apply_filters('the_content', $collection->post_content));
        $data['wpsc-nft-option'] = "collection";

        $nft_ids = self::getJSONNFTIds([
            'posts_per_page' => -1,
            'post_type' => 'nft',
            'fields' => 'ids',
            'meta_query' => [
                [
                    'key'   => 'wpsc_item_collection',
                    'value' => $collection_id,
                ]
            ]
        ]);

        $data['wpsc-nft-params-nft-ids'] = $nft_ids;

        $nft_ids = WPSC_Queries::getNFTIds($collection_id, WPSC_helpers::getGalleryIdCookie($collection_id));	
        if (!empty($nft_ids)) {	
            $data['wpsc-nft-params-nft-ids'] = json_encode(array_column($nft_ids, "nft_id"));	
        }

        $data["nft-items-per-page"] = WPSCSettingsPage::nftItemsPerPage();

        $data['view-metamask'] = self::viewMetamask($m);

        $data["blockie"] = get_post_meta($collection_id, 'wpsc_blockie', true);
        $data['page-thumbnail'] = get_the_post_thumbnail_url($collection_id);
        $data["section-2"] = __("NFT Collections", "wp-smart-contracts");
        $data["main-nav"] = $m->render(WPSC_Mustache::getTemplate('launcher-main-nav'), self::addLauncherMainNavAtts($data));
        $data["load-more"] = __("Load more", "wp-smart-contracts");

        if ($wpsc_sub_collections = get_post_meta($collection_id, 'wpsc_sub_collections', true)) {	
            $data["show-sub-collections"]=true;	
            $data["galleries-menu"] = $m->render(	
                WPSC_Mustache::getTemplate('galleries-menu'), 	
                self::getGalleriesData($collection_id, $wpsc_sub_collections)	
            );	
            $data["galleries"]=WPSC_helpers::getGalleriesOfCollection($collection_id);	
        }

        return $m->render(WPSC_Mustache::getTemplate('nft-my-items'), $data);

    }

    public function staking($params) {

        $staking_id = self::getPostID($params);
    
        if (!$staking_id) return;
        if (get_post_type($staking_id)!="staking") return;
    
        $staking = get_post($staking_id);
    
        $data["wpsc-contract-flavor-beautified"] = ucfirst(get_post_meta($staking_id, "wpsc_flavor", true));
        $data["wpsc-contract-flavor"] = get_post_meta($staking_id, "wpsc_flavor", true);
        $data["wpsc-contract"] = get_post_meta($staking_id, "wpsc_contract_address", true);
        $data["wpsc-contract-short"] = WPSC_helpers::shortify($data["wpsc-contract"]);
        $data["blockie"] = get_post_meta($staking_id, 'wpsc_blockie', true);

        $data["wpsc-token"] = get_post_meta($staking_id, 'wpsc_token', true);
        $data["wpsc-token-short"] = WPSC_helpers::shortify($data["wpsc-token"]);
        $data["wpsc-symbol"] = get_post_meta($staking_id, 'wpsc_symbol', true);
        $data["wpsc-name"] = get_post_meta($staking_id, 'wpsc_name', true);
        $data["wpsc-decimals"] = get_post_meta($staking_id, 'wpsc_decimals', true);
        
        $data["wpsc_minimum"] = get_post_meta($staking_id, 'wpsc_minimum', true);
        if (!$data["wpsc_minimum"]) $data["wpsc_minimum"] = 0;
        $data["wpsc_penalty"] = get_post_meta($staking_id, 'wpsc_penalty', true);
        $data["wpsc_mst"] = get_post_meta($staking_id, 'wpsc_mst', true);
        $data["wpsc_apy"] = get_post_meta($staking_id, 'wpsc_apy', true);
        
        // almond variables
        $data["wpsc-token2"] = get_post_meta($staking_id, 'wpsc_token2', true);
        $data["wpsc-token-short2"] = WPSC_helpers::shortify($data["wpsc-token2"]);
        $data["wpsc-apy2"] = get_post_meta($staking_id, 'wpsc_apy2', true);    
        $data["wpsc-ratio1"] = get_post_meta($staking_id, 'wpsc_ratio1', true);    
        $data["wpsc-ratio2"] = get_post_meta($staking_id, 'wpsc_ratio2', true); 
        $data["wpsc-symbol2"] = get_post_meta($staking_id, 'wpsc_symbol2', true);
        $data["wpsc-name2"] = get_post_meta($staking_id, 'wpsc_name2', true);
        $data["wpsc-decimals2"] = get_post_meta($staking_id, 'wpsc_decimals2', true);
        if ($data["wpsc-contract-flavor"]=="almond") {
            $data["is-almond"]=true;
        }
           
        $data["wpsc-network"] = get_post_meta($staking_id, "wpsc_network", true);
        $data['fox'] = plugins_url( "assets/img/metamask-fox.svg", dirname(__FILE__) );

        list($data["color"], $data["icon"], $data["etherscan"], $data["network_val"]) = WPSC_Metabox::getNetworkInfo(get_post_meta($staking_id, 'wpsc_network', true));

        $data['text'] = __('You are not connected', 'wp-smart-contracts');
        $data['connect-to-metamask'] = __('Connect to Metamask', 'wp-smart-contracts');
        $data['text-wrong-net'] = __('You are connected to a different network, or contract not deployed', 'wp-smart-contracts');
        $data['staking-id'] = $staking_id;
        $data["staking-link"] = get_permalink($staking_id);
        $data["cancel"] = __("Cancel", "wp-smart-contracts");
        $data["click-confirm"] = __('If you agree and wish to proceed, please click "CONFIRM" transaction in your wallet, otherwise click "REJECT".', 'wp-smart-contracts');
        $data["please-patience"] = __('Please be patient. It can take several minutes. Don\'t close or reload this window.', 'wp-smart-contracts');
        $data["tx-in-progress"] = __('Transaction in progress', 'wp-smart-contracts');
        $data["deploy-icon"] = plugin_dir_url( dirname(__FILE__) ) . '/assets/img/deploy-identicon.gif';

        $data['page-title'] = $staking->post_title;
        $data['page-content'] = wpautop(apply_filters('the_content', $staking->post_content));
        $data['page-thumbnail'] = get_the_post_thumbnail_url($staking_id);

        $m = new Mustache_Engine;
    
        $data['view-metamask'] = self::viewMetamask($m);
    
        if (WPSC_helpers::valArrElement($params, 'hide-brand') and $params['hide-brand'] and $params['hide-brand']=="true") {
            $data["hide-brand"] = true;
        }
        if (WPSC_helpers::valArrElement($params, 'hide-stakes') and $params['hide-stakes'] and $params['hide-stakes']=="true") {
            $data["hide-stakes"] = true;
        }

        $wpsc_flavor = get_post_meta($staking_id, 'wpsc_flavor', true); 
        if ($wpsc_flavor=="ube") $data["flavor-color"] = "purple";
        else $data["flavor-color"] = "almond";
        
        $data["path-to-launcher"] = plugins_url( "launcher/", dirname(__FILE__));
        $data["no-stakings-found"] = __("No stakes found", "wp-smart-contracts");
        $data["section-2"] = __("Stakings", "wp-smart-contracts");
        $data["main-nav"] = $m->render(WPSC_Mustache::getTemplate('launcher-main-nav'), self::addLauncherMainNavAtts($data));
        
        $data["smart-contract"] = __("Smart Contract", "wp-smart-contracts");
        $data["copied"] = __("Copied!", "wp-smart-contracts");
        $data["locked-balance"] = __("Locked Balance", "wp-smart-contracts");
        $data["approved-funds"] = __("Approved funds", "wp-smart-contracts");
        $data["approve"] = __("Approve", "wp-smart-contracts");
        $data["approved-funds-2"] = __("Approved funds for token 2", "wp-smart-contracts");
        $data["apy"] = __("APY", "wp-smart-contracts");
        $data["apy2"] = __("APY 2", "wp-smart-contracts");
        $data["penalization"] = __("Penalization", "wp-smart-contracts");
        $data["maturity"] = __("Maturity", "wp-smart-contracts");
        $data["days"] = __("days", "wp-smart-contracts");
        $data["minimum"] = __("Minimum", "wp-smart-contracts");
        $data["stakes"] = __("Stakes", "wp-smart-contracts");
        $data["create-stake"] = __("Create Stake", "wp-smart-contracts");
        $data["dates"] = __("Dates", "wp-smart-contracts");
        $data["stake-amounts"] = __("Stake amounts", "wp-smart-contracts");
        $data["status"] = __("Status", "wp-smart-contracts");
        $data["actions"] = __("Actions", "wp-smart-contracts");
        $data["step-1"] = __("Step 1", "wp-smart-contracts");
        $data["step-2"] = __("Step 2", "wp-smart-contracts");
        $data["create-stake"] = __("Create Stake", "wp-smart-contracts");
        $data["amount-to-stake"] = __("Amount to stake", "wp-smart-contracts");
        $data["minimum-amount"] = __("Minimum amount", "wp-smart-contracts");
        $data["approve-funds"] = __("Approve", "wp-smart-contracts");
        $data["create-stake"] = __("Create Stake", "wp-smart-contracts");
        $data["cancel"] = __("Cancel", "wp-smart-contracts");
        $data["stake"] = __("Stake", "wp-smart-contracts");
        $data["warning"] = __("Warning!", "wp-smart-contracts");
        $data["ending-early"] = __("You are ending your stake early, which means you could pay a penalty for this action.", "wp-smart-contracts");
        $data["warning-risk"] = __("Warning. Potencial Risk!", "wp-smart-contracts");
        $data["warning-1"] = __("The stake smart contract has no liquidity to pay interest!. You must contact the website owner to resolve this situation.", "wp-smart-contracts");
        $data["warning-2"] = __("If you want to make an emergency withdrawal of your funds, you can continue, but note that no interest will be paid to you, you will only recover the deposited funds and the stake will be closed.", "wp-smart-contracts");
        $data["warning-3"] = __("Are you completely sure you want to continue with the withdraw?", "wp-smart-contracts");
        $data["are-you-sure"] = __("Are you sure you want to end your Stake?", "wp-smart-contracts");
        $data["end-the-stake"] = __("End the stake now", "wp-smart-contracts");
        $data["stake"] = __("Stake", "wp-smart-contracts");
        $data["approve-funds-stake"] = __("Approve funds for your Stake Contract", "wp-smart-contracts");
        $data["approve"] = __("Approve", "wp-smart-contracts");
        $data["approve-msg-1"] = __("Approved funds will not be deducted from your account until they are needed to pay interest.", "wp-smart-contracts");
        $data["approve-msg-2"] = __("If you have approved funds previously, it will be replaced by the amount that you approve now.", "wp-smart-contracts");
        $data["approve-msg-3"] = __("If you want to remove the previously approved amount, just approve 0 funds.", "wp-smart-contracts");
        $data["approve-funds-secondary"] = __("Approve funds for your secondary token", "wp-smart-contracts");
        $data["staking"] = __("Staking", "wp-smart-contracts");
        $data["staked-token"] = __("Staked Token", "wp-smart-contracts");
        $data["interest-token"] = __("Interest Token", "wp-smart-contracts");
        $data["account-to-search"] = __("Account to search", "wp-smart-contracts");

        return $m->render(WPSC_Mustache::getTemplate('stake-view'), $data);
    
    }
    
    static private function getViewMenu($m, $wpsc_item_collection, $active, $edit_link=false) {
        $ret = [
            "my-items-link" => add_query_arg("id", $wpsc_item_collection, WPSC_assets::getPage("wpsc_is_nft_my_items")),
            "my-galleries-link" => add_query_arg("id", $wpsc_item_collection, WPSC_assets::getNFTMyGalleriesPage()),
            "my-bids-link" => add_query_arg("id", $wpsc_item_collection, WPSC_assets::getPage("wpsc_is_nft_my_bids")),
            $active => "true",
            "mint-link" => add_query_arg("id", $wpsc_item_collection, WPSC_assets::getPage("wpsc_is_nft_minter")),
            "edit-label" => __('Edit', 'wp-smart-contracts'),
            "my-galleries" => __('My Galleries', 'wp-smart-contracts'),
            "my-items" => __('My Items', 'wp-smart-contracts'),
            "mint-a-new-item" => __('Mint', 'wp-smart-contracts'),    
        ];

        if ($edit_link) {
            $ret["edit-link"] = add_query_arg(
                [
                    "id" => $wpsc_item_collection,
                    "nft_id" => get_the_ID()
                ], 
                WPSC_assets::getPage("wpsc_is_nft_minter")
            );
        }

        return $m->render(WPSC_Mustache::getTemplate('nft-view-menu'), $ret);

    }

    private static function getOrLoadTransient($the_id) {
        if ($the_id) {
            // generate transient using the endpoint
            wp_remote_get(get_rest_url(null, 'wpsc/v1/nft/'.$the_id));
            // return transient
            $transient = get_transient("wpsc_nft_" . $the_id);
            if ($transient) {
                // return the data from the endpoint
                return $transient;
            } else {
                // return the data from the database if the endpoint is not available yet (not minted)
                return WPSC_Endpoints::getNFTData($the_id);
            }
        }
    }

    private static function viewMetamask($m) {
        $atts = [
            'fox' => plugins_url( "assets/img/metamask-fox.svg", dirname(__FILE__) ), 
            'wallet-connect-logo' => plugins_url( "assets/img/wallet-connect-logo.png", dirname(__FILE__) ),
            'other-wallets-logo' => plugins_url( "assets/img/other-wallets.png", dirname(__FILE__) ),
            'text' => __('You are not connected', 'wp-smart-contracts'),
            'text-wrong-net' => __('It seems that you are currently connected to the wrong network. Please switch your wallet to the correct network.', 'wp-smart-contracts'),
            'metamask-not-found' => __('Metamask not found', 'wp-smart-contracts'),
            'click-to-install' => __('Click to install', 'wp-smart-contracts'),
            'connect-to-metamask' => __('Connect to Metamask', 'wp-smart-contracts'),
            'wrong-network' => __('Wrong network. Please disconnect and connect your wallet to', 'wp-smart-contracts'),
            'no-web3-provider' => __('No web3 provider options found for Wallet Connect', 'wp-smart-contracts'),
            'disconnect' => __('Disconnect', 'wp-smart-contracts')
        ];
        if (WPSCSettingsPage::get('wpsc_activate_launcher')) {
            $atts['home'] = plugins_url( "launcher/img/home-logo.png", dirname(__FILE__) );
            $atts['launcher'] = __("Launcher", 'wp-smart-contracts');
            $atts['launcher-link'] = WPSC_assets::getPage('wpsc_is_launcher');
        }
        $atts["metamask-not-found"] = __('Metamask not found', 'wp-smart-contracts');
        $atts["click-to-install"] = __('Click to install', 'wp-smart-contracts');
        $atts["connect-to-metamask"] = __('Connect to Metamask', 'wp-smart-contracts');
        $atts["text-wrong-net"] = __('The wrong network is selected', 'wp-smart-contracts');
        $atts["wrong-network"] = __('Wrong network', 'wp-smart-contracts');
        $atts["please-disconnect-and-connect-wallet-to"] = __('Please disconnect and connect your wallet to', 'wp-smart-contracts');
        $atts["no-web3-provider-options-found-for-wallet-connect"] = __('No web3 provider options found for Wallet Connect', 'wp-smart-contracts');
        $atts["disconnect"] = __('Disconnect', 'wp-smart-contracts');
        $atts["network-name"] = __('Network Name', 'wp-smart-contracts');

        return $m->render(WPSC_Mustache::getTemplate('crowd-view-metamask'), $atts);
    }

    public function nft($params) {

        $cats_terms = $tax_terms = $gal_terms = null;

        $the_id = self::getPostID($params);

        $transient_data = self::getOrLoadTransient($the_id);
        
        $wpsc_title = (WPSC_helpers::valArrElement($transient_data, 'name'))?$transient_data["name"]:"";
        $wpsc_content = (WPSC_helpers::valArrElement($transient_data, 'description'))?$transient_data["description"]:"";
        $wpsc_item_collection = get_post_meta($the_id, "wpsc_item_collection", true);

        $gallery_id = WPSC_helpers::getGalleryIdCookie($wpsc_item_collection);

        if (WPSC_helpers::valArrElement($transient_data, 'attributes') and is_array($transient_data["attributes"])) {
            foreach($transient_data["attributes"] as $att) {
                if (WPSC_helpers::valArrElement($att, 'trait_type') and strpos($att["trait_type"], "Category")===0 ) {
                    $cats_terms[] = $att;
                } elseif (WPSC_helpers::valArrElement($att, 'trait_type') and strpos($att["trait_type"], "Gallery")===0 ) {	
                    $gal_terms[] = $att;
                } elseif (empty(WPSC_helpers::valArrElement($att, 'trait_type'))) {
                    $tax_terms[] = $att;
                }
            }    
        }

        $original_author_id = (WPSC_helpers::valArrElement($transient_data, 'author_id'))?$transient_data["author_id"]:"";
        $original_author_avatar = (WPSC_helpers::valArrElement($transient_data, 'author_avatar'))?$transient_data["author_avatar"]:"";
        $wpsc_author_url = (WPSC_helpers::valArrElement($transient_data, 'author_external_url'))?$transient_data["author_external_url"]:"";

        $wpsc_collection_contract = get_post_meta($wpsc_item_collection, "wpsc_contract_address", true);

        $wpsc_nft_id = get_post_meta($the_id, "wpsc_nft_id", true);
        $wpsc_nft_id_blockie = get_post_meta($the_id, "wpsc_nft_id_blockie", true);

        $wpsc_collection_title = get_the_title($wpsc_item_collection);
        $wpsc_collection_link = get_permalink($wpsc_item_collection);

        $post_meta = get_post_meta($wpsc_item_collection);
        $wpsc_tag_bg_color =     (WPSC_helpers::valArrElement($post_meta, "wpsc_tag_bg_color"))?$post_meta["wpsc_tag_bg_color"][0]:null;
        $wpsc_tag_color =        (WPSC_helpers::valArrElement($post_meta, "wpsc_tag_color"))?$post_meta["wpsc_tag_color"][0]:null;
        $wpsc_cat_bg_color =     (WPSC_helpers::valArrElement($post_meta, "wpsc_cat_bg_color"))?$post_meta["wpsc_cat_bg_color"][0]:null;
        $wpsc_cat_color =        (WPSC_helpers::valArrElement($post_meta, "wpsc_cat_color"))?$post_meta["wpsc_cat_color"][0]:null;
        $wpsc_graph_line_color = (WPSC_helpers::valArrElement($post_meta, "wpsc_graph_line_color"))?$post_meta["wpsc_graph_line_color"][0]:null;
        $wpsc_graph_bg_color =   (WPSC_helpers::valArrElement($post_meta, "wpsc_graph_bg_color"))?$post_meta["wpsc_graph_bg_color"][0]:null;
        $wpsc_pixelated_images = (WPSC_helpers::valArrElement($post_meta, "wpsc_pixelated_images"))?$post_meta['wpsc_pixelated_images'][0]:null;
        $wpsc_network =          (WPSC_helpers::valArrElement($post_meta, "wpsc_network"))?$post_meta['wpsc_network'][0]:null;
        
        $wpsc_sub_collections = get_post_meta($wpsc_item_collection, 'wpsc_sub_collections', true);

        $post_meta = get_post_meta($the_id);
        $wpsc_txid =                    (WPSC_helpers::valArrElement($post_meta, "wpsc_txid"))?$post_meta['wpsc_txid'][0]:null;
        $wpsc_owner =                   (WPSC_helpers::valArrElement($post_meta, "wpsc_owner"))?$post_meta['wpsc_owner'][0]:null;
        $wpsc_contract_address =        (WPSC_helpers::valArrElement($post_meta, "wpsc_contract_address"))?$post_meta['wpsc_contract_address'][0]:null;
        $wpsc_blockie =                 (WPSC_helpers::valArrElement($post_meta, "wpsc_blockie"))?$post_meta['wpsc_blockie'][0]:null;
        $wpsc_blockie_owner =           (WPSC_helpers::valArrElement($post_meta, "wpsc_blockie_owner"))?$post_meta['wpsc_blockie_owner'][0]:null;
        $wpsc_qr_code =                 (WPSC_helpers::valArrElement($post_meta, "wpsc_qr_code"))?$post_meta['wpsc_qr_code'][0]:null;
        $wpsc_nft_media_json =          (WPSC_helpers::valArrElement($post_meta, "wpsc_nft_media_json"))?$post_meta["wpsc_nft_media_json"][0]:null;
        $wpsc_media_type =              (WPSC_helpers::valArrElement($post_meta, "wpsc_media_type"))?$post_meta["wpsc_media_type"][0]:null;
        $wpsc_creator =                 (WPSC_helpers::valArrElement($post_meta, "wpsc_creator"))?$post_meta["wpsc_creator"][0]:null;
        $original_author =              (WPSC_helpers::valArrElement($post_meta, "original_author"))?$post_meta["original_author"][0]:null;
        $wpsc_creator_blockie =         (WPSC_helpers::valArrElement($post_meta, "wpsc_creator_blockie"))?$post_meta["wpsc_creator_blockie"][0]:null;
        $wpsc_nft_voucher_price_human = (WPSC_helpers::valArrElement($post_meta, "wpsc_nft_voucher_price_human"))?$post_meta["wpsc_nft_voucher_price_human"][0]:null;
        $wpsc_nft_voucher_qty =         (WPSC_helpers::valArrElement($post_meta, "wpsc_nft_voucher_qty"))?$post_meta["wpsc_nft_voucher_qty"][0]:null;
        $wpsc_nft_voucher_minted =      (WPSC_helpers::valArrElement($post_meta, "wpsc_nft_voucher_minted"))?$post_meta["wpsc_nft_voucher_minted"][0]:null;
        $wpsc_nft_voucher_price =       (WPSC_helpers::valArrElement($post_meta, "wpsc_nft_voucher_price"))?$post_meta["wpsc_nft_voucher_price"][0]:null;
        $wpsc_nft_voucher_author =      (WPSC_helpers::valArrElement($post_meta, "wpsc_nft_voucher_author"))?$post_meta["wpsc_nft_voucher_author"][0]:null;
        $wpsc_nft_voucher_salt =        (WPSC_helpers::valArrElement($post_meta, "wpsc_nft_voucher_salt"))?$post_meta["wpsc_nft_voucher_salt"][0]:null;
        $wpsc_nft_voucher_id =          (WPSC_helpers::valArrElement($post_meta, "wpsc_nft_voucher_id"))?$post_meta["wpsc_nft_voucher_id"][0]:null;
        $wpsc_nft_voucher_sign =        (WPSC_helpers::valArrElement($post_meta, "wpsc_nft_voucher_sign"))?$post_meta["wpsc_nft_voucher_sign"][0]:null;

        $wpsc_blockie_collection = get_post_meta($wpsc_item_collection, 'wpsc_blockie', true);

        $wpsc_flavor = get_post_meta($wpsc_item_collection, 'wpsc_flavor', true);;

        $native_coin = WPSC_helpers::nativeCoinName($wpsc_network);

        list($color, $icon, $etherscan, $network_val) = WPSC_Metabox::getNetworkInfo($wpsc_network);

        $m = new Mustache_Engine;

        $show_edit_link = !self::validateNFTFE($wpsc_item_collection, $the_id);

        $wpsc_log_history = get_post_meta($the_id, "wpsc_log_history", true);

        $the_params = [
            'wpsc_nft_network' => $wpsc_network,
            'wpsc_contract_flavor' => $wpsc_flavor,
            'wpsc_nft_marketplace_contract' => $wpsc_collection_contract,
            'nft_token_id' => $wpsc_nft_id,
            "etherscan" => $etherscan,
            'view-metamask' => self::viewMetamask($m),
            "wpsc_show_header" => get_post_meta($wpsc_item_collection, "wpsc_show_header", true),
            'nft-view-menu' => self::getViewMenu($m, $wpsc_item_collection, "none", $show_edit_link),
            "collection-title"=>$wpsc_collection_title, 
            "collection-link"=>$wpsc_collection_link, 
            "nft-title"=>$wpsc_title,
            "collection-title"=>$wpsc_collection_title, 
            "collection-link"=>$wpsc_collection_link, 
            "cats"=>$cats_terms,
            "gals"=>$gal_terms,
            "wpsc_cat_bg_color"=>$wpsc_cat_bg_color,
            "wpsc_cat_color"=>$wpsc_cat_color,
            "tags"=>$tax_terms, 
            "wpsc_tag_bg_color"=>$wpsc_tag_bg_color, 
            "wpsc_tag_color"=>$wpsc_tag_color,
            "wpsc-nft-media-json" => $wpsc_nft_media_json,
            "wpsc-media-type" => $wpsc_media_type, 
            "author" => $original_author,
            "author_link" => get_author_posts_url($original_author_id),
            "wpsc_creator" => $wpsc_creator,
            "wpsc_creator_short" => WPSC_helpers::shortify($wpsc_creator),
            "etherscan" => $etherscan,
            "content" => wpautop($wpsc_content),
            "original_author_avatar" => $original_author_avatar,
            "wpsc_creator_blockie" => $wpsc_creator_blockie,
            "wpsc_collection_contract" => $wpsc_collection_contract,
            "wpsc_collection_contract_short" => WPSC_helpers::shortify($wpsc_collection_contract),
            "wpsc_blockie_collection" => $wpsc_blockie_collection,
            "etherscan" => $etherscan,
            "color" => $color, 
            "icon" => $icon, 
            "network_val" => $network_val,
            "wpsc_nft_id" => $wpsc_nft_id,
            "wpsc_nft_id_blockie" => $wpsc_nft_id_blockie,
            "wpsc_creator" => $wpsc_creator,
            "wpsc_creator_short" => WPSC_helpers::shortify($wpsc_creator, true),
            "wpsc_creator_link" => $etherscan . "address/" . $wpsc_creator,
            "wpsc_txid" => $wpsc_txid,
            "wpsc_txid_short" => WPSC_helpers::shortify($wpsc_txid, true),
            "wpsc_txid_link" => $etherscan . "tx/" . $wpsc_txid,
            "original_author" => $original_author,
            "original_author_id" => $original_author_id,
            "original_author_avatar" => $original_author_avatar,
            "wpsc_creator_blockie" => $wpsc_creator_blockie,
            "wpsc_author_url" => $wpsc_author_url,
            "wpsc_graph_bg_color" => $wpsc_graph_bg_color,
            "wpsc_pixelated_images" => $wpsc_pixelated_images,
            "wpsc_graph_line_color" => $wpsc_graph_line_color,
            "endpoint" => get_post_meta($the_id, "wpsc_nft_url", true), // get_rest_url(null, 'wpsc/v1/nft/') . $the_id,
            "wpsc_log_history" => $wpsc_log_history,
            "wpsc_nft_voucher_price_human" => $wpsc_nft_voucher_price_human,
            "wpsc_nft_voucher_qty" => $wpsc_nft_voucher_qty,
            "wpsc_nft_voucher_minted" => $wpsc_nft_voucher_minted,
            "wpsc_nft_voucher_price" => $wpsc_nft_voucher_price,
            "wpsc_nft_voucher_author" => $wpsc_nft_voucher_author,
            "wpsc_nft_voucher_salt" => $wpsc_nft_voucher_salt,
            "wpsc_nft_voucher_id" => $wpsc_nft_voucher_id,
            "wpsc_nft_voucher_sign" => $wpsc_nft_voucher_sign
        ];

        if ($wpsc_nft_voucher_sign and $wpsc_nft_voucher_minted < $wpsc_nft_voucher_qty) {
            $the_params["wpsc_nft_voucher_show"] = true;
            $the_params["wpsc_nft_voucher_remaining"] = $wpsc_nft_voucher_qty - $wpsc_nft_voucher_minted;
            $the_params["wpsc_nft_voucher_post_id"] = $the_id;
            $the_params["wpsc_nft_voucher_collection_id"] = $wpsc_item_collection;
        }

        $wpsc_list_on_opensea = get_post_meta($wpsc_item_collection, "wpsc_list_on_opensea", true);

        if ($wpsc_list_on_opensea and ($wpsc_network==137 or $wpsc_network==1)) {
            $the_params["show-opensea"] = true;
            if ($wpsc_network==137) {
                $the_params["opensea-link"] = "https://opensea.io/assets/matic/".$wpsc_collection_contract."/".$wpsc_nft_id;
            }
            if ($wpsc_network==1) {
                $the_params["opensea-link"] = "https://opensea.io/assets/".$wpsc_collection_contract."/".$wpsc_nft_id;
            }
            $the_params["opensea-icon"] = plugins_url( "assets/img/opensea.png", dirname(__FILE__) );
        }

        if ($wpsc_flavor=="suika") {
            $the_params["is-suika"]=true;
            $the_params["wpsc-erc-721"]=true;
            $the_params["wpsc_flavor_color"]="red";
        } elseif ($wpsc_flavor=="mochi") {
            $the_params["wpsc_flavor_color"]="violet";
            $the_params["wpsc-erc-721"]=true;
        } elseif ($wpsc_flavor=="matcha") {
            $the_params["wpsc_flavor_color"]="green";
            $the_params["wpsc-erc-721"]=true;
        } elseif ($wpsc_flavor=="yuzu") {
            $the_params["wpsc_flavor_color"]="yellow";
            $the_params["wpsc-erc-1155"]=true;
        } elseif ($wpsc_flavor=="ikasumi") {
            $the_params["wpsc_flavor_color"]="black";
            $the_params["wpsc-erc-1155"]=true;
        } elseif ($wpsc_flavor=="azuki") {
            $the_params["wpsc_flavor_color"]="brown";
            $the_params["wpsc-erc-1155"]=true;
        }
        
        if (WPSC_helpers::valArrElement($the_params, "wpsc-erc-1155")) {
            $options = get_option('etherscan_api_key_option');
            $nft_moralis_key = 
                (WPSC_helpers::valArrElement($options, "nft_moralis_key") and !empty($options["nft_moralis_key"]))?
                    $options["nft_moralis_key"]:
                    false;
            if (!$nft_moralis_key) {
                $the_params["show-moralis-warning"]=true;
                $w1 = __("Are you the site administrator?", 'wp-smart-contracts');
                $w2 = __("You must configure the Moralis API key for the ERC-1155 token UI to work properly", 'wp-smart-contracts');
                $w3 = __('Go to "Smart Contracts Dashboard" - "Admin Setup Wizard" and follow the instructions to setup the Moralis API Key', 'wp-smart-contracts');
                $the_params["moralis-warning"] = <<<WARNING
                <div class="ui warning message">
                    <div class="header">
                        $w1
                    </div>
                    <p>$w2</p>
                    <p>$w3</p>
                </div>
WARNING;
            }
        }

        $wpsc_nft_media_json_arr = json_decode($wpsc_nft_media_json, true);

        if ($media_mime = WPSC_IPFS_MEDIA::getMimeFromArr($wpsc_nft_media_json_arr)) {
            $the_params["media_files"] = WPSC_IPFS_MEDIA::getIpfsFromArr($wpsc_nft_media_json_arr);
            $the_params[$media_mime] = true;
            if ($media_mime=="doc") {
                $the_params["meter_clase_de_video"] = "documentClass";
            } elseif ($media_mime=="video" or $media_mime=="model") {
                $the_params["meter_clase_de_video"] = "videoClass";
            }
        }

        $the_params["model-viewer"] = dirname( plugin_dir_url( __FILE__ ) ) . '/assets/js/model-viewer.min.js';

        if ($wpsc_sub_collections) {	
            $galleries_data = self::getGalleriesData($wpsc_item_collection, $wpsc_sub_collections);	
            $the_params["galleries-menu"] = $m->render(WPSC_Mustache::getTemplate('galleries-menu'), $galleries_data);	
            $the_params = array_merge($the_params, $galleries_data);	
        }	
        if ($gallery_id and !has_term($gallery_id, "nft-gallery", $the_id)) {	
            $the_params["message"] = $m->render(WPSC_Mustache::getTemplate('wpsc-message'), [	
                "error"=>true, 	
                "title"=>"", 	
                "extra" => "mini compact",	
                "message"=>__("You are viewing an item outside the selected gallery", 'wp-smart-contracts')	
            ]);	
        }	

        if ($cats_terms and is_array($cats_terms) and sizeof($cats_terms)) {
            $the_params["there_are_cats"] = true;
        }

        if ($wpsc_flavor=="ikasumi") $the_params["is_ikasumi"] = true;
        if ($wpsc_flavor=="azuki") $the_params["is_azuki"] = true;

        $the_params["modals"] = $m->render(WPSC_Mustache::getTemplate('nft-view-all-modals'), [

            'burn' => __("Burn", 'wp-smart-contracts'),
            'destroy-forever' => __("Are you sure you want to destroy this item forever?", 'wp-smart-contracts'),
            'qty-burn' => __("Quantity to Burn", 'wp-smart-contracts'),
            'irreversible' => __("This is an irreversible action, you will not be able to recover this item after burning it.", 'wp-smart-contracts'),
            'cancel' => __("Cancel", 'wp-smart-contracts'),
            'mint-more-items' => __("Mint more items", 'wp-smart-contracts'),
            'confirm-the-number' => __("Confirm the number of new items to mint", 'wp-smart-contracts'),
            'scan' => __("Scan", 'wp-smart-contracts'),
            'mint-qty' => __("Quantity to mint", 'wp-smart-contracts'),
            'mint' => __("Mint", 'wp-smart-contracts'),
            'transfer' => __("Transfer", 'wp-smart-contracts'),
            'transfer-confirm' => __("Are you sure you want to transfer the ownership of this Item to another account?", 'wp-smart-contracts'),
            'transfer-qty' => __("Quantity to Transfer", 'wp-smart-contracts'),
            'sell' => __("Sell", 'wp-smart-contracts'),
            'sale-confirm' => __("Are you sure you want to put your NFTs up for sale?", 'wp-smart-contracts'),
            'price' => __("Price", 'wp-smart-contracts'),
            'total-price' => __("This is the total price of the item", 'wp-smart-contracts'),
            'commission-warning' => __("The marketplace may charge commission.", 'wp-smart-contracts'),
            'sale-price' => __("Sale Price", 'wp-smart-contracts'),
            'wallet' => __("Wallet", 'wp-smart-contracts'),
            'wallet-funds' => __("This is the wallet where you will receive funds.", 'wp-smart-contracts'),
            'qty' => __("Qty", 'wp-smart-contracts'),
            'total-amount' => __("This is the total amount of items you want to sell.", 'wp-smart-contracts'),
            'sell' => __("Sell", 'wp-smart-contracts'),
            'buy' => __("Buy", 'wp-smart-contracts'),
            'buy-confirm' => __("Are you sure you want to buy the items?", 'wp-smart-contracts'),
            'unit-price' => __("This is the unit price of the item.", 'wp-smart-contracts'),
            'amount-items-buy' => __("This is the total amount of items you want to buy.", 'wp-smart-contracts'),
            'step-1' => __("Step 1", 'wp-smart-contracts'),
            'approve-funds' => __("Approve funds", 'wp-smart-contracts'),
            'step-2' => __("Step 2", 'wp-smart-contracts'),
            'buy' => __("Buy", 'wp-smart-contracts'),
            'nft-units-buy' => __("This is the amount of NFT units to buy", 'wp-smart-contracts'),
            'total-to-pay' => __("The total to pay will be the price multiplied by the quantity", 'wp-smart-contracts'),
            'unit-price-to-pay' => __("This is the unit price you are going to pay", 'wp-smart-contracts'),
            'transaction-in-progress' => __("Transaction in progress", 'wp-smart-contracts'),
            'if-you-agree' => __("If you agree and wish to proceed, please click \"CONFIRM\" transaction in your wallet, otherwise click \"REJECT\". Please be patient. It can take several minutes. Don't close or reload this window.", 'wp-smart-contracts'),
            'mint-confirm' => __("Are you sure you want to mint the items?", 'wp-smart-contracts'),
            'accept-bid' => __("Accept Bid", 'wp-smart-contracts'),
            'sell-confirm' => __("Are you sure you want to sell the items?", 'wp-smart-contracts'),
            'accept-offer' => __("Accept Offer", 'wp-smart-contracts'),
            'make-an-offer' => __("Make an Offer", 'wp-smart-contracts'),
            'make-an-offer-confirm' => __("Are you sure you want to make an offer to buy?", 'wp-smart-contracts'),
            'buy-amount-items' => __("This is the amount of items you want to buy.", 'wp-smart-contracts'),
            'unit-price' => __("Unit Price", 'wp-smart-contracts'),
            'offer' => __("Offer", 'wp-smart-contracts'),
            'auction' => __("Auction", 'wp-smart-contracts'),
            'auction-confirm' => __("Are you sure you want to put your NFT up for auction?", 'wp-smart-contracts'),
            'minimum-price' => __("This is the minimum price you are willing to accept for your NFT", 'wp-smart-contracts'),
            'commission-warning' => __("The marketplace may charge commission.", 'wp-smart-contracts'),
            'auction-end-date-help' => __("This is the end date of your auction. It will be at midnight GMT time.", 'wp-smart-contracts'),
            'irreversible-2' => __("This action is irreversible, and once you put your item up for auction, it will be blocked from being transferred or sold until the auction ends", 'wp-smart-contracts'),
            'wallet' => __("Wallet", 'wp-smart-contracts'),
            'bid' => __("Bid", 'wp-smart-contracts'),
            'amount' => __("Amount", 'wp-smart-contracts'),
            'close-auction}' => __("Close Auction", 'wp-smart-contracts'),
            'finalize' => __("Finalize", 'wp-smart-contracts'),
            'close-auction-withdraw' => __("Close Auction & Withdraw", 'wp-smart-contracts'),
            'withdraw' => __("Withdraw", 'wp-smart-contracts'),
            'address-to' => __("To address", 'wp-smart-contracts'),
            'nft-transfer-progress' => $m->render(WPSC_Mustache::getTemplate('nft-transfer-progress'), [
                'animated-gif' => dirname( plugin_dir_url( __FILE__ ) ) . '/assets/img/animated.gif',
                'transaction-in-progress' =>__("Transaction in progress", 'wp-smart-contracts'),
                'if-you-agree' =>__('If you agree and wish to proceed, please click "CONFIRM" transaction in your wallet, otherwise click "REJECT". Please be patient. It can take several minutes. Don\'t close or reload this window.', 'wp-smart-contracts')
            ])
        ]);

        $the_params["wpsc_nft_token_uri"] = get_rest_url(null, 'wpsc/v1/nft/');

        $the_params["blockie"] = get_post_meta($the_id, 'wpsc_nft_id_blockie', true);
        $the_params['page-thumbnail'] = get_the_post_thumbnail_url($the_id);
        $the_params["section-2"] = __("NFT Collections", "wp-smart-contracts");
        $the_params["main-nav"] = $m->render(WPSC_Mustache::getTemplate('launcher-main-nav'), self::addLauncherMainNavAtts($the_params));

        $collection = get_post($wpsc_item_collection);
        $the_params['page-content'] = wpautop(apply_filters('the_content', $collection->post_content));

        $next_id = WPSC_Queries::getNextNFT($wpsc_item_collection, $the_id);

        if ($next_id) $the_params["next_nft"] = get_permalink($next_id);

        $the_params["smart-contract"] = __("Smart Contract", "wp-smart-contracts");
        $the_params["view-in-openSea"] = __("View in OpenSea", "wp-smart-contracts");
        $the_params["owned-by"] = __("Owned by ", "wp-smart-contracts");
        $the_params["owners"] = __("owners ", "wp-smart-contracts");
        $the_params["total"] = __("total ", "wp-smart-contracts");
        $the_params["you-own"] = __("You own ", "wp-smart-contracts");
        $the_params["owned-by"] = __("Owned by", "wp-smart-contracts");
        $the_params["n-items"] = __("# Items", "wp-smart-contracts");
        $the_params["place-higher-bid"] = __("Place Higher Bid", "wp-smart-contracts");
        $the_params["place-bid"] = __("Place Bid", "wp-smart-contracts");
        $the_params["buy"] = __("Buy", "wp-smart-contracts");
        $the_params["finalize"] = __("Finalize", "wp-smart-contracts");
        $the_params["burn"] = __("Burn", "wp-smart-contracts");
        $the_params["mint"] = __("Mint", "wp-smart-contracts");
        $the_params["transfer"] = __("Transfer", "wp-smart-contracts");
        $the_params["mint"] = __("Mint", "wp-smart-contracts");
        $the_params["make-an-offer"] = __("Make an Offer", "wp-smart-contracts");
        $the_params["make-an-offer"] = __("Make an Offer", "wp-smart-contracts");
        $the_params["sell"] = __("Sell", "wp-smart-contracts");
        $the_params["next"] = __("Next", "wp-smart-contracts");
        $the_params["author-label"] = __("Author", "wp-smart-contracts");
        $the_params["genesis"] = __("Genesis", "wp-smart-contracts");
        $the_params["network"] = __("Network", "wp-smart-contracts");
        $the_params["flavor"] = __("Flavor", "wp-smart-contracts");
        $the_params["token-id"] = __("Token ID", "wp-smart-contracts");
        $the_params["view-token-uri"] = __("View Token URI", "wp-smart-contracts");
        $the_params["auction"] = __("Subastar", "wp-smart-contracts");
        $the_params["withdraw"] = __("Withdraw", "wp-smart-contracts");
        $the_params["auction-ends-in"] = __("Auction ends in", "wp-smart-contracts");
        $the_params["days}"] = __("days", "wp-smart-contracts");
        $the_params["hrs"] = __("hrs", "wp-smart-contracts");
        $the_params["min"] = __("min", "wp-smart-contracts");
        $the_params["sec"] = __("sec", "wp-smart-contracts");
        $the_params["buy"] = __("Buy", "wp-smart-contracts");
        $the_params["quantity"] = __("Quantity", "wp-smart-contracts");
        $the_params["unit-price"] = __("Unit price", "wp-smart-contracts");
        $the_params["total"] = __("Total", "wp-smart-contracts");
        $the_params["account"] = __("Account", "wp-smart-contracts");
        $the_params["action"] = __("Action", "wp-smart-contracts");
        $the_params["sell"] = __("Sell", "wp-smart-contracts");
        $the_params["graph"] = __("Graph", "wp-smart-contracts");
        $the_params["history"] = __("History", "wp-smart-contracts");
        $the_params["mint"] = __("Mint", "wp-smart-contracts");

        return $m->render(WPSC_Mustache::getTemplate('nft-view-all'), $the_params);

    }

    private static function getGalleriesData($collection_id, $galleries_setting) {	
        $params = [];	
        $params["show-sub-collections"] = true;	
        $params["galleries"] = WPSC_helpers::getGalleriesOfCollection($collection_id);	
        $params["actual-url"] = WPSC_helpers::currentGalleryLink($collection_id);
        $params["filter-by-gallery"] = __("Filter by Gallery", "wp-smart-contracts");
        $params["choose-gallery"] = __("Choose Gallery", "wp-smart-contracts");
        if ($galleries_setting==2) {	
            $params["show-galleries-as-dropdown"] = true;	
        }	
        return $params;	
    }
    
    // return the post id from environment or from shortcode
    public static function getPostID($params) {

        $the_id = 0;

        if (is_array($params) and array_key_exists('id', $params) and $params["id"]) {
            $the_id = (int) $params['id'];
        }

        if (!$the_id) {
            $the_id = (int) get_the_ID();
        }

        return $the_id;

    }

}

