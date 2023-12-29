<?php

if( ! defined( 'ABSPATH' ) ) die;

/**
 * Include Mustache Lib into PHP and JS
 */

// Load PHP Mustache
require_once("vendor/autoload.php");


// JS Mustache Logic

new WPSC_Mustache();

class WPSC_Mustache {

    function __construct() {

        // Load admin scripts
        add_action('admin_enqueue_scripts' , [$this, 'loadMustacheJS'], 10, 2 );
        add_action('in_admin_footer', [$this, 'loadTemplatesForCurrentPage']);

        // Load FE scripts
        add_action('wp_enqueue_scripts' , [$this, 'loadMustacheJS'], 10, 2 );
        add_action('wp_footer', [$this, 'loadTemplatesForCurrentPageFE']);

    }

    // Enqueue Mustache JS
    public function loadMustacheJS($hook) {
        
        // enqueue mustache library
        wp_enqueue_script( 'wp-smart-contracts-mustache', dirname( plugin_dir_url( __FILE__ ) ) . '/assets/js/mustache.min.js' );

    }

    // this will load the templates used in admin
    public function loadTemplatesForCurrentPage($hook) {

        // post edition and creation
        if ( 'post.php' != $hook and 'post-new.php' != $hook) {
            $this->showMustacheTemplates();
        } else {
            $this->showTemplate("wp-admin-bar");
        }

    }

    public function showMustacheTemplates() {
        // messages and warning
        $this->showTemplate("msg-box");
        // disclaimers and deploy conditions
        $this->showTemplate("deploy-warning-message");
        // Token Confirmation modal
        $this->showTemplate("token-info");
        // Crowdfunding Confirmation modal
        $this->showTemplate("crowdfunding-info");
        // ICO Confirmation modal
        $this->showTemplate("stake-info");
        $this->showTemplate("nft-info");
        $this->showTemplate("ico-info");
        $this->showTemplate("ico-info-ico");
        $this->showTemplate("ico-info-token");
        // Deploy animation
        $this->showTemplate("deploy-animation");
        // Connect Metamask button for Msg box 
        $this->showTemplate("wp-admin-bar-connect-ethereum");        
        // admin bar
        $this->showTemplate("wp-admin-bar");
        // switch to other networks on deployer
        $this->showTemplate("other-networks");
    }

    // this will load the templates used in frontend
    public function loadTemplatesForCurrentPageFE() {

        $id = get_the_ID();

        if (get_post_field( 'post_type', $id ) == "coin" or 
            get_post_field( 'post_type', $id ) == "crowdfunding" or 
            get_post_field( 'post_type', $id ) == "ico" or 
            has_shortcode( get_post_field('post_content', $id), 'wpsc_coin' ) or 
            has_shortcode( get_post_field('post_content', $id), 'wpsc_crowdfunding' ) or 
            has_shortcode( get_post_field('post_content', $id), 'wpsc_ico' ) or
            has_shortcode( get_post_field('post_content', $id), 'wpsc_nft_mint' )
        ) {
            $this->showTemplate("coin-view-block-explorer-rows");
            $this->showTemplate("coin-view-block-explorer-loader");
            $this->showTemplate("coin-view-block-explorer-balance");
            $this->showTemplate("coin-view-block-explorer-role");
            $this->showTemplate("coin-view-block-explorer-txid");
            $this->showTemplate("coin-view-block-explorer-txid-detail");
            $this->showTemplate("msg-box");
            $this->showTemplate("metamask-button-view");
            $this->showTemplate("crowdfunding-info");
            $this->showTemplate("ico-info");
            $this->showTemplate("ico-info-ico");
            $this->showTemplate("ico-info-token");
            $this->showTemplate("crowd-view-request");
            $this->showTemplate("crowd-view-request-finalized");
        }

        if (get_post_field( 'post_type', $id ) == "nft") {
            $this->showTemplate("nft-view-history-row");
            $this->showTemplate("msg-box");
            $this->showTemplate("metamask-button-view");
            $this->showTemplate("nft-view-menu-token");
            $this->showTemplate("nft-view-all-table-row");
            $this->showTemplate("nft-erc-1155-listing");
        }

        if (
            (has_shortcode( get_post_field('post_content', $id), 'wpsc_nft_my_items' )) or
            (has_shortcode( get_post_field('post_content', $id), 'wpsc_nft_my_bids' )) or
            (has_shortcode( get_post_field('post_content', $id), 'wpsc_nft_author' )) or
            (get_post_field( 'post_type', $id ) == "nft-collection") or 
            is_tax('nft-taxonomy') or 
            is_tax('nft-gallery') or
            is_tax('nft-tag')
        ) {
            $this->showTemplate("nft-my-items-card");
            $this->showTemplate("msg-box");
            $this->showTemplate("metamask-button-view");
            $this->showTemplate("nft-view-menu-token");
        }

        if (
            (has_shortcode( get_post_field('post_content', $id), 'wpsc_staking' )) or
            (get_post_field( 'post_type', $id ) == "staking")
        ) {
            $this->showTemplate("msg-box");
            $this->showTemplate("metamask-button-view");
            $this->showTemplate("stake-row");
        }

    }

    // load the translation texts for JS
    public static function loadTranslationsForTag($tag) {

        $json = false;

        $network = get_post_meta(WPSC_helpers::getIdFromShortcodes(), "wpsc_network", true);

        $native_coin = WPSC_helpers::nativeCoinName($network);

        switch ($tag) {

            case 'other-networks':
                $json = json_encode([
                    'SWITCH' => __('Switch', 'wp-smart-contracts'),
                    'SELECTED' => __('Selected', 'wp-smart-contracts'),
                ]);
                break;

            case 'wpsc-dashboard':
                $json = json_encode([
                    'NETWORK' => __('Network', 'wp-smart-contracts'),
                    'FLAVOR' => __('Flavor', 'wp-smart-contracts'),
                    'SMART_CONTRACT' => __('Smart Contract', 'wp-smart-contracts'),
                    'VIEW' => __('View', 'wp-smart-contracts'),
                    'APY_2' => __('APY2', 'wp-smart-contracts'),
                    'APY' => __('APY', 'wp-smart-contracts'),
                    'DECIMALS' => __('Decimals', 'wp-smart-contracts'),
                    'MINIMUM_AMOUNT' => __('Minimum amount', 'wp-smart-contracts'),
                    'MINIMUM_DAYS' => __('Minimum Days', 'wp-smart-contracts'),
                    'MINIMUM' => __('Minimum', 'wp-smart-contracts'),
                    'NAME' => __('Name', 'wp-smart-contracts'),
                    'NETWORK' => __('Network', 'wp-smart-contracts'),
                    'PENALIZATION' => __('Penalization', 'wp-smart-contracts'),
                    'PIXELATED' => __('Pixelated', 'wp-smart-contracts'),
                    'REFLECTION' => __('Reflection', 'wp-smart-contracts'),
                    'ROYALTY' => __('Royalty', 'wp-smart-contracts'),
                    'SALES_COMMISSION' => __('Sales Commission', 'wp-smart-contracts'),
                    'SUPPLY' => __('Supply', 'wp-smart-contracts'),
                    'SYMBOL' => __('Symbol', 'wp-smart-contracts'),
                    'TOKEN_2' => __('Token 2', 'wp-smart-contracts'),
                    'TOKEN' => __('Token', 'wp-smart-contracts'),
                    'WALLET' => __('Wallet', 'wp-smart-contracts'),
                    'WALLET_DISTRIBUTION' => __('Distribution Wallet', 'wp-smart-contracts'),
                    'WHO_CAN_MINT' => __('Who can mint', 'wp-smart-contracts'),
                    'EDIT' => __('Edit', 'wp-smart-contracts'),
                    'BURNABLE' => __('Burnable', 'wp-smart-contracts'),
                    'CAP' => __('Cap', 'wp-smart-contracts'),
                    'ACCEPT_TERMS' => __('You cannot deploy if you do not accept all the terms and conditions', 'wp-smart-contracts'),
                    'BAD_NETWORK'           => __('The selected network doesn\'t match the network you\'re currently connected to. Please switch the network on your wallet or go back and choose the correct network', 'wp-smart-contracts'),
                    'ACCEPT_TERMS'          => __('You cannot deploy if you do not accept all the terms and conditions', 'wp-smart-contracts'),
                    'BAD_TOKEN'             => __('There was an error loading the token, are you sure the address you are using corresponds to a valid ERC-20 / BEP-20 token address on the selected network?', 'wp-smart-contracts'),
                    'BLOCK_HASH_INCORRECT'  => __('Please enter a valid block hash (0x...)', 'wp-smart-contracts'),
                    'CONNECT_YOUR_WALLET'   => __('You need to connect your wallet to update the post', 'wp-smart-contracts'),
                    'CONTRACT_INCORRECT'    => __('Please enter a valid contract address (0x...)', 'wp-smart-contracts'),
                    'ERROR'                 => __('Error', 'wp-smart-contracts'),
                    'ERROR_UPDATING_POST'   => __('An error occurred updating the post', 'wp-smart-contracts'),
                    'FLAVOR_ERROR_1'        => __('Please enter the symbol of the cryptocurrency', 'wp-smart-contracts'),
                    'FLAVOR_ERROR_10'       => __('Please enter the name of the cryptocurrency', 'wp-smart-contracts'),
                    'FLAVOR_ERROR_11'       => __('Please enter a valid number of decimals (a number between 0 and 18)', 'wp-smart-contracts'),
                    'FLAVOR_ERROR_12'       => __('Please enter a valid supply', 'wp-smart-contracts'),
                    'FLAVOR_ERROR_13'       => __('Please enter a valid cap (0: unlimited, or &gt; 0 for a limited cap)', 'wp-smart-contracts'),
                    'FLAVOR_ERROR_14'       => __('The maximum cap has to be greater than the supply, specify a greater cap or set it to zero for unlimited cap', 'wp-smart-contracts'),
                    'FLAVOR_ERROR_15'       => __('Please enter the symbol of the cryptocurrency', 'wp-smart-contracts'),
                    'FLAVOR_ERROR_16'       => __('Please enter the name of the cryptocurrency', 'wp-smart-contracts'),
                    'FLAVOR_ERROR_17'       => __('Please enter a valid number of decimals (a number between 0 and 18)', 'wp-smart-contracts'),
                    'FLAVOR_ERROR_18'       => __('Please enter a valid supply', 'wp-smart-contracts'),
                    'FLAVOR_ERROR_19'       => __('An error occurred with the reflected fee selected, please try selecting a reflection fee again', 'wp-smart-contracts'),
                    'FLAVOR_ERROR_2'        => __('Please enter the name of the cryptocurrency', 'wp-smart-contracts'),
                    'FLAVOR_ERROR_20'       => __('Please enter the minimum contribution. It should be equal or greater than zero', 'wp-smart-contracts'),
                    'FLAVOR_ERROR_21'       => __('An error occurred with the percentage of approvers selected, please try selecting one again', 'wp-smart-contracts'),
                    'FLAVOR_ERROR_22'       => __('Please enter a name for the campaign', 'wp-smart-contracts'),
                    'FLAVOR_ERROR_23'       => __('Please enter a valid token address', 'wp-smart-contracts'),
                    'FLAVOR_ERROR_24'       => __('Please enter a valid APY number (between 0 and 65535)', 'wp-smart-contracts'),
                    'FLAVOR_ERROR_25'       => __('Please enter a minimum time equal or greater than zero', 'wp-smart-contracts'),
                    'FLAVOR_ERROR_26'       => __('Please enter a valid penalization percentage (between 0 and 100)', 'wp-smart-contracts'),
                    'FLAVOR_ERROR_27'       => __('Please enter a minimum amount equal or greater than zero', 'wp-smart-contracts'),
                    'FLAVOR_ERROR_28'       => __('Please enter a valid token address', 'wp-smart-contracts'),
                    'FLAVOR_ERROR_29'       => __('Please enter a valid Secondary Token address', 'wp-smart-contracts'),
                    'FLAVOR_ERROR_3'        => __('Please enter a valid number of decimals (a number between 0 and 18)', 'wp-smart-contracts'),
                    'FLAVOR_ERROR_30'       => __('Please enter a valid APY number (between 0 and 65535)', 'wp-smart-contracts'),
                    'FLAVOR_ERROR_31'       => __('Please enter a valid secondary APY number (between 0 and 65535)', 'wp-smart-contracts'),
                    'FLAVOR_ERROR_32'       => __('Please enter a minimum time equal or greater than zero', 'wp-smart-contracts'),
                    'FLAVOR_ERROR_33'       => __('Please enter a valid penalization percentage (between 0 and 100)', 'wp-smart-contracts'),
                    'FLAVOR_ERROR_34'       => __('Please enter a minimum amount equal or greater than zero', 'wp-smart-contracts'),
                    'FLAVOR_ERROR_35'       => __('Please enter a symbol', 'wp-smart-contracts'),
                    'FLAVOR_ERROR_36'       => __('Please enter a name', 'wp-smart-contracts'),
                    'FLAVOR_ERROR_37'       => __('Please enter a valid minting permissions', 'wp-smart-contracts'),
                    'FLAVOR_ERROR_38'       => __('Please enter a symbol', 'wp-smart-contracts'),
                    'FLAVOR_ERROR_39'       => __('Please enter a name', 'wp-smart-contracts'),
                    'FLAVOR_ERROR_4'        => __('Please enter a valid supply', 'wp-smart-contracts'),
                    'FLAVOR_ERROR_40'       => __('Please enter a valid minting permissions', 'wp-smart-contracts'),
                    'FLAVOR_ERROR_41'       => __('Please enter a valid sales commissions', 'wp-smart-contracts'),
                    'FLAVOR_ERROR_42'       => __('Please enter a valid wallet address', 'wp-smart-contracts'),
                    'FLAVOR_ERROR_43'       => __('Please enter a symbol', 'wp-smart-contracts'),
                    'FLAVOR_ERROR_44'       => __('Please enter a name', 'wp-smart-contracts'),
                    'FLAVOR_ERROR_45'       => __('Please enter a valid minting permissions', 'wp-smart-contracts'),
                    'FLAVOR_ERROR_46'       => __('Please enter a valid sales commissions', 'wp-smart-contracts'),
                    'FLAVOR_ERROR_47'       => __('Please enter a valid royalty commission', 'wp-smart-contracts'),
                    'FLAVOR_ERROR_48'       => __('Please enter a valid wallet address', 'wp-smart-contracts'),
                    'FLAVOR_ERROR_49'       => __('Please enter a valid token address', 'wp-smart-contracts'),
                    'FLAVOR_ERROR_5'        => __('Please enter the symbol of the cryptocurrency', 'wp-smart-contracts'),
                    'FLAVOR_ERROR_50'       => __('Please enter a symbol', 'wp-smart-contracts'),
                    'FLAVOR_ERROR_51'       => __('Please enter a name', 'wp-smart-contracts'),
                    'FLAVOR_ERROR_52'       => __('Please enter a valid minting permissions', 'wp-smart-contracts'),
                    'FLAVOR_ERROR_53'       => __('Please enter a symbol', 'wp-smart-contracts'),
                    'FLAVOR_ERROR_54'       => __('Please enter a name', 'wp-smart-contracts'),
                    'FLAVOR_ERROR_55'       => __('Please enter a valid minting permissions', 'wp-smart-contracts'),
                    'FLAVOR_ERROR_56'       => __('Please enter a valid sales commissions', 'wp-smart-contracts'),
                    'FLAVOR_ERROR_57'       => __('Please enter a valid royalty commission', 'wp-smart-contracts'),
                    'FLAVOR_ERROR_58'       => __('Please enter a valid wallet address', 'wp-smart-contracts'),
                    'FLAVOR_ERROR_59'       => __('Please enter a symbol', 'wp-smart-contracts'),
                    'FLAVOR_ERROR_6'        => __('Please enter the name of the cryptocurrency', 'wp-smart-contracts'),
                    'FLAVOR_ERROR_60'       => __('Please enter a name', 'wp-smart-contracts'),
                    'FLAVOR_ERROR_61'       => __('Please enter a valid minting permissions', 'wp-smart-contracts'),
                    'FLAVOR_ERROR_62'       => __('Please enter a valid sales commissions', 'wp-smart-contracts'),
                    'FLAVOR_ERROR_63'       => __('Please enter a valid royalty commission', 'wp-smart-contracts'),
                    'FLAVOR_ERROR_64'       => __('Please enter a valid wallet address', 'wp-smart-contracts'),
                    'FLAVOR_ERROR_65'       => __('Please enter a valid token address', 'wp-smart-contracts'),
                    'FLAVOR_ERROR_66'       => __('Please enter a positive integer as distribution rate', 'wp-smart-contracts'),
                    'FLAVOR_ERROR_67'       => __('Please enter valid Ethereum address as wallet', 'wp-smart-contracts'),
                    'FLAVOR_ERROR_68'       => __('Please enter valid Ethereum address as a distribution wallet', 'wp-smart-contracts'),
                    'FLAVOR_ERROR_7'        => __('Please enter a valid number of decimals (a number between 0 and 18)', 'wp-smart-contracts'),
                    'FLAVOR_ERROR_8'        => __('Please enter a valid supply', 'wp-smart-contracts'),
                    'FLAVOR_ERROR_9'        => __('Please enter the symbol of the cryptocurrency', 'wp-smart-contracts'),
                    'LOGIN_MESSAGE_1'       => __('Login failed. Is this email address already associated with an account? If so, please try logging in with that account.', 'wp-smart-contracts'),
                    'LOGIN_MESSAGE_3'       => __('Login failed. Is this email address already associated with an account? If so, please try logging in with that account.', 'wp-smart-contracts'),
                    'LOGIN_MESSAGE_4'       => __('Login failed', 'wp-smart-contracts'),
                    'METAMASK_NOT_FOUND'    => __('Metamask extension could not be found. Please make sure you have Metamask properly installed', 'wp-smart-contracts'),
                    'SIGN_UP_FAILED'        => __('Sign up failed. The email address is not valid. To register, try again and provide a valid email.', 'wp-smart-contracts'),
                    'TOKEN_INCORRECT'       => __('The token was not properly loaded. If you are the administrator of the site, please verify that you are using a compatible ERC-20 or BEP-20 Token for payments.', 'wp-smart-contracts'),
                    'TX_HASH_INCORRECT'     => __('Please enter a valid transaction hash (0x...)', 'wp-smart-contracts'),
                    'UNSUPPORTED_NET'       => __('It seems you are connected to an unsupported network, please restart the Wizard or choose a supported network', 'wp-smart-contracts'),
                    'YOU_ARE_NOT_OWNER'     => __('It appears that you are not the owner of the contract. Are you logged in with the admin account in your wallet?', 'wp-smart-contracts'),
                    'CONNECT_WALLET'        => __('Connect Wallet', 'wp-smart-contracts'),
                    'RATE'                  => __('Distribution Rate', 'wp-smart-contracts'),
                    'ERROR_SWITCH'          => __('An error has occurred while switching the network. Switch your network manually in your wallet and click continue', 'wp-smart-contracts'),
                ]);
                break;

            case 'wp-admin-bar':
                $json = json_encode([
                    'METAMASK_NO' => __('Unable to connect to the Ethereum Network. Please install and run MetaMask.', 'wp-smart-contracts'),
                    'METAMASK_NO_SHORT' => __('MetaMask not found.', 'wp-smart-contracts'),
                    'NETWORK_ETHEREUM' => __('Main Ethereum Network', 'wp-smart-contracts'),
                    'NETWORK_ROPSTEN' => __('Ropsten Test Network', 'wp-smart-contracts'),
                    'NETWORK_KOVAN' => __('Kovan Test Network', 'wp-smart-contracts'),
                    'NETWORK_RINKEBY' => __('Rinkeby Test Network', 'wp-smart-contracts'),
                    'METAMASK_YOU_SELECTED' => __('You have selected', 'wp-smart-contracts'),
                    'METAMASK_NOT_LOGGED_IN' => __('but looks like you are not logged in with MetaMask, please Log In if you want to deploy Smart Contracts', 'wp-smart-contracts'),
                    'METAMASK_YOU_ARE_CONNECTED' => __('You are connected to', 'wp-smart-contracts'),
                    'METAMASK_WITH_ACCOUNT' => __('with the account', 'wp-smart-contracts'),
                    'ERROR' => __('ERROR, PLEASE CONNECT TO METAMASK', 'wp-smart-contracts'),
                    'METAMASK_NOT_LOGGED_IN' => __('Do you see a "Connect to Ehereum Network" in WP admin bar? if so, click there to connect. Are you logged in to Metamask? if not, please log in. If the error persist try reloading this page.', 'wp-smart-contracts'), 
                    'NETWORK_ERROR' => __("Network error", 'wp-smart-contracts'),
                    'UNKNOWN_NETWORK' => __("Unknown network selected", 'wp-smart-contracts'),
                    'TRANSIENT_CACHE' => __("Transient Cache", 'wp-smart-contracts'),
                    'TRANSIENT_CACHE_FLUSHED' => __("Transient Cache flushed", 'wp-smart-contracts'),
                    'FOX' => plugins_url( "assets/img/metamask-fox.svg", dirname(__FILE__) ),
                    'CONNECT_WITH_METAMASK' => __('Connect to Ethereum Network', 'wp-smart-contracts'),
                    'WPST_LOGO' => plugins_url( "assets/img/wp-smart-token.png", dirname(__FILE__) ),
                    'AUTHORIZE_WPST' => __("Authorize the use of WPIC funds", 'wp-smart-contracts'),
                    'DEPLOY_USING_WPST' => __("Deploy using WP Ice Cream", 'wp-smart-contracts'),
                    'ETHEREUM_DEPLOY' => __('Network Deploy', 'wp-smart-contracts'),
                    'ETHEREUM_DEPLOY_DESC' => __('Are you ready to deploy your Coin to the currently active Network?', 'wp-smart-contracts'),
                ]);
                break;
            case 'deploy-warning-message':
                
                $tos = WPSC_helpers::tos();

                $json = json_encode([
                    'TOS' => $tos,
                    'ARE_YOU_USING_MAINNET' => __('Terms of Service (TOS)<p><a href="https://wpsmartcontracts.com/terms/" target="_blank"><strong>Open the TOS Agreement in a new window</strong></a></p>', 'wp-smart-contracts'),
                    'CONFIRM' => __('By ticking this box I confirm that I have read, consent and agree to the Terms of Service (TOS)', 'wp-smart-contracts'), 
                    'CONFIRM2' => __('By ticking this box I confirm that I am of legal age', 'wp-smart-contracts'), 
                    'DEPLOY_COST_MONEY' => __('I understand that deploying a contract in the Main Ethereum Network can cost me real money (in Ether).', 'wp-smart-contracts'),
                    'FEES_AND_GAS_EXPENSES' => __('I understand what are the fees and gas expenses for deploying a contract.', 'wp-smart-contracts'),
                    'WPST_DISCLAIMER' => __("I understand that I am going to Authorize WP Smart Contracts factory to spend my WPIC tokens deploying contracts", 'wp-smart-contracts'),
                    'FEES_AND_GAS_EXPENSES_WPST' => __("I understand what are the fees and gas expenses of this operation.", 'wp-smart-contracts'),
                    'ACTION_IRREVERSIBLE' => __('I understand that this action is irreversible.', 'wp-smart-contracts'),
                    'ACCEPT_TERMS' => __('I accept the terms of service explained here.', 'wp-smart-contracts'),
                    'DEPLOY_TAKES_TIME' => __('Deploy can take several minutes (in any network used). Please don\'t cancel this operation once initiated.', 'wp-smart-contracts'),
                    'FOOTER' => __('If you are not sure of what you are doing click <strong>Cancel</strong> and learn more in the tutorials section before proceeding', 'wp-smart-contracts'),
                    'MANDATORY_FIELD' => __("Mandatory Field", 'wp-smart-contracts'),
                    'WRITE_CONTRACT_ADDRESS' => __("Please write the contract address", 'wp-smart-contracts'),
                    'CONTRACT_ADDRESS_BAD_FORMAT' => __("Invalid address", 'wp-smart-contracts'),
                    'WRONG_TYPE' => __("Wrong Type or Value", 'wp-smart-contracts'),
                    'WRONG_ADD_TITLE' => __("Wrong address", 'wp-smart-contracts'),
                    'WRONG_ADD_DESC' => __("This is not a valid EVM address", 'wp-smart-contracts'),
                    'WRONG_ERC20_DESC' => __("Seems like the token is not a valid ERC-20 Token Address in the selected network. Please verify the token address and the network selected.", 'wp-smart-contracts'),
                    'WRONG_RANGE_COMMISSION' => __("Wrong value", 'wp-smart-contracts'),
                    'WRONG_RANGE_COMMISSION2' => __("Commission should be strict lower than 100", 'wp-smart-contracts'),
                    'WRONG_RANGE_ROYALTIES' => __("Royalty should be strict lower than 100", 'wp-smart-contracts'),
                    'WRONG_RANGE_SUM' => __("The sum of commission plus royalty should be strict lower than 100", 'wp-smart-contracts'),
                    'WALLET_NOT_EMTPY' => __("If commission is set wallet cannot be empty", 'wp-smart-contracts'),
                    'TOKEN_NOT_EMTPY' => __("Token address cannot be empty", 'wp-smart-contracts'),
                    'FILL_SYMBOL' => __("Please fill the symbol of the Token ", 'wp-smart-contracts'),
                    'FILL_INTEGER' => __("This field has to be a positive integer", 'wp-smart-contracts'),
                    'FILL_INTEGER_ZERO' => __("This field has to be an integer greater or equal than zero", 'wp-smart-contracts'),
                    'FILL_INTEGER8' => __("This field has to be equal or lower than 255", 'wp-smart-contracts'),
                    'FILL_INTEGER16' => __("This field has to be equal or lower than 65535", 'wp-smart-contracts'),
                    'FILL_NUMBER' => __("This field has to be a positive number", 'wp-smart-contracts'),
                    'FILL_PERCENTAGE' => __("This field has to be an integer between 0 and 100", 'wp-smart-contracts'),
                    'FILL_NAME' => __("Please fill the name of the Token ", 'wp-smart-contracts'),
                    'PLEASE_ACCEPT_TERMS' => __('To deploy please accept all the terms and conditions, otherwise click "Cancel"', 'wp-smart-contracts'), 
                    'PLEASE_SELECT_FLAVOR' => __('Please select a flavor.', 'wp-smart-contracts'),
                    'PLEASE_SELECT_COLLECTION' => __('Please select a collection (NFT Collection Smart Contract). If you haven`t created one yet please go to NFT Collection tab and create at least one collection.', 'wp-smart-contracts'),
                    'PLEASE_SET_OWNER' => __('Please fill the owner address', 'wp-smart-contracts'),
                    'FILL_DEFINITION' => __('Please fill in all definition fields (name, symbol, decimals and supply)', "wp-smart-contracts"), 
                    'FILL_DEFINITION2' => __('Please fill in all definition fields (name, symbol and decimals)', "wp-smart-contracts"), 
                    'FILL_RATE' => __('Please fill in the rate with an Integer number', "wp-smart-contracts"), 
                    'FILL_TOKEN2SELL' => __('Please fill in the address of the token you want to sell', "wp-smart-contracts"), 
                    'FILL_WALLET' =>  __('Please fill in the wallet address', 'wp-smart-contracts'), 
                    'BLUEMOON_UNSTOP' =>  __('It is strongly recommended that you set a way of finalizing the ICO, it can be as a hardcapped or timed ICO. Are you sure you want continue without setting a hardcap or a timed option?', 'wp-smart-contracts'), 
                    'POSITIVE_NUMBER' => __('It must be a positive number', 'wp-smart-contracts'), 
                    'TIMED_DATES' => __('Error in Timed Options', 'wp-smart-contracts'),
                    'TIMED_DATES_WRONG' => __('Please check that opening and closing dates are set and that closing date is greater than the opening date', 'wp-smart-contracts'),
                    'TIMED_DATES_TOMM' => __('Opening date has to start tomorrow (in GMT time). It cannot be set for today GMT time.', 'wp-smart-contracts'),
                    'DECIMAL_NUMBER' => __('Decimals must be a number, greater than or equal than 0 or less than 18.', 'wp-smart-contracts'), 
                    'INITIAL_SUPPLY_NUMBER' => __('Initial supply must be a number', 'wp-smart-contracts'), 
                    'HARD_CAP_NUMBER' => __('Hard cap must be a number greater than zero', 'wp-smart-contracts'), 
                    'RATE_NUMBER' => __('Rate must be a positive Integer number', 'wp-smart-contracts'), 
                    'CAP_TOKEN_NUMBER' => __('Mintable Cap must be 0 for unlimited cap or positive number for a limited cap', 'wp-smart-contracts'), 
                    'TOTAL_SUPPLY_NUMBER' => __('Total supply must be a number.', 'wp-smart-contracts'), 
                    'TOTAL_SUPPLY_NUMBER_NON_ZERO' => __('Total supply must be a number, greater than zero.', 'wp-smart-contracts'), 
                    'MINTABLE_CAP_NUMBER' => __('The mintable cap must be a number greater or equal than 0', 'wp-smart-contracts'),
                ]);
                break;
            case 'deploy-animation':
                $json = json_encode([
                    'DEPLOY_IN_PROGRESS' => __('Transaction in progress', 'wp-smart-contracts'), 
                    'CLICK_CONFIRM' => __('If you agree and wish to proceed, please click "CONFIRM" transaction in your wallet, otherwise click "REJECT".', 'wp-smart-contracts'), 
                    'PLEASE_PATIENCE' => __('Please be patient. It can take several minutes. Don\'t close or reload this window.', 'wp-smart-contracts'),
                    'ANIMATED_GIF' => dirname( plugin_dir_url( __FILE__ ) ) . '/assets/img/deploy-identicon.gif',
                ]);
                break;
            case "token-info":
                $json = json_encode([
                    'IMG_URL' => dirname( plugin_dir_url( __FILE__ ) ) . '/assets/img/',
                    'SYMBOL'  => __('Symbol', 'wp-smart-contracts'), 
                    'NAME'  => __('Name', 'wp-smart-contracts'), 
                    'DECIMALS'  => __('Decimals', 'wp-smart-contracts'), 
                    'INITIAL_SUPPLY'  => __('Initial Supply', 'wp-smart-contracts'), 
                    'BURNABLE'  => __('Burnable', 'wp-smart-contracts'), 
                    'MINTABLE'  => __('Mintable', 'wp-smart-contracts'), 
                    'MAX_CAP'  => __('Max. cap', 'wp-smart-contracts'), 
                    'PAUSABLE'  => __('Pausable', 'wp-smart-contracts'),
                ]);
                break;
            case "crowdfunding-info":
            case "ico-info":
            case "ico-info-ico":
            case "ico-info-token":
                $json = json_encode([
                    'CANNOT_BUY_ZERO' => __('Zero tokens cannot be purchased; Increase the contribution amount to purchase a positive amount of tokens.', 'wp-smart-contracts'),
                    'WRITE_BENEFICIARY' => __('Please write the beneficiary address', 'wp-smart-contracts'),
                    'IMG_URL' => dirname( plugin_dir_url( __FILE__ ) ) . '/assets/img/',
                    'ERROR2' => __('Error', 'wp-smart-contracts'),
                    "BAD_TOKEN_SALE" => __("The token was not properly loaded. If you are the administrator of the site, please verify that you are using a compatible ERC-20 or BEP-20 Token for sale.", 'wp-smart-contracts'),
                    'NO_CONTRACT' => __('No contract found', 'wp-smart-contracts'),
                    'NATIVE_COIN' => $native_coin,
                    'POSITIVE_INTEGER' => __('Funds has to be a positive integer', 'wp-smart-contracts'),
                    'WRITE_AMOUNT' => __("Please specify a valid amount greater than zero", 'wp-smart-contracts'),
                    'WRITE_ADDRESS' => __("Please specify a valid address in the \"Destination address\" field", 'wp-smart-contracts'),
                    'WRITE_DESC' => __("Please specify a description in the \"Add a description\" field", 'wp-smart-contracts'),
                    'METAMASK_ERROR' => __('Unable to connect to the Ethereum Network. Please install and run MetaMask.', 'wp-smart-contracts'),
                    'WRONG_NETWORK' => __('Looks like You are connected to a different network, or contract not deployed.', 'wp-smart-contracts'),
                    'ACCOUNT_NOT_FOUND' => __('Do you see a "Connect to Ehereum Network" in WP admin bar? if so, click there to connect. Are you logged in to Metamask? if not, please log in. If the error persist try reloading this page.', 'wp-smart-contracts'), 
                    'THANKS' => __('Thanks for your contribution!', 'wp-smart-contracts'),
                    'CONTRIBUTION_DONE' => __('Your contribution was completed', 'wp-smart-contracts'),
                    'CLOSE' => __('Close', 'wp-smart-contracts'),
                    'APPROVAL_DONE' => __('Your approval was successfully completed.', 'wp-smart-contracts'),
                    'FINALIZATION_DONE' => __('Your request was successfully finalized', 'wp-smart-contracts'),
                    'TO' => __('To', 'wp-smart-contracts'),
                    'APPROVE' => __('Approve', 'wp-smart-contracts'),
                    'FINALIZE' => __('Finalize', 'wp-smart-contracts'),
                    'TRANSFERRED' => __('Transferred', 'wp-smart-contracts'),
                    'APPROVERS' => __('Approvers', 'wp-smart-contracts'),
                    'APPROVERS_LABEL' => __('Approvers Percentage', 'wp-smart-contracts'),
                    'MINIMUM_LABEL' => __('Minimum', 'wp-smart-contracts'),

                    'HARD_CAPPED_LABEL' => __('Hard Capped', 'wp-smart-contracts'),
                    'PAUSABLE_LABEL' => __('Pausable', 'wp-smart-contracts'),
                    'TIMED_LABEL' => __('Timed', 'wp-smart-contracts'),
                    'SYMBOL_LABEL' => __('Symbol', 'wp-smart-contracts'),
                    'NAME_LABEL' => __('Name', 'wp-smart-contracts'),
                    'SUPPLY_LABEL' => __('Initial Supply', 'wp-smart-contracts'),
                    'RATE_LABEL' => __('Rate', 'wp-smart-contracts'),
                    'WALLET_LABEL' => __('Wallet', 'wp-smart-contracts'),
                    'DIST_WALLET_LABEL' => __('Distribution Wallet', 'wp-smart-contracts'),
                    'PER_EACH' => __('per each', 'wp-smart-contracts'),
                    'WAIT_TX'  => __('Please confirm the transaction in your wallet and wait for it to finish. The reload the page to see the changes.', 'wp-smart-contracts'),
                    'WAIT'  => __('Confirm the Transaction', 'wp-smart-contracts'),
                    'BAD_ADDRESS'  => __('Please write a valid address', 'wp-smart-contracts'),
                    'TX_FINISHED' => __('Transaction finished', 'wp-smart-contracts'),
                    'FINISHED' => __('Done!', 'wp-smart-contracts'),
                    'TOKEN_LABEL' => __('Token', 'wp-smart-contracts'),
                    'STAKE_APY' => __('Annual Interest Rate', 'wp-smart-contracts'),
                    'STAKE_MST' => __('Maturity time', 'wp-smart-contracts'),
                    'STAKE_PENALTY' => __('Penalization', 'wp-smart-contracts'),
                    'STAKE_MINIMUM' => __('Minimum value', 'wp-smart-contracts'),
                    'ROYALTIES_LABEL' => __("Royalties", "wp-smart-contracts"),
                    'COMMISSION_LABEL' => __("Commission", "wp-smart-contracts"),
                    'ANYONE_LABEL' => __("Anyone can mint", "wp-smart-contracts"),
                    'NAME_LABEL' => __("Name", "wp-smart-contracts"),
                    'SYMBOL_LABEL' => __("Symbol", "wp-smart-contracts"),

                    'TOKEN2SELL' =>  __('Token to sell', 'wp-smart-contracts'),

                ]);
                break;
            case "crowd-view-request":
            case "crowd-view-request-finalized":
            case "nft-view-all":
            case "nft-my-items":
            case "nft-my-items-card":
            case "nft-view-history-row":
                $json = json_encode([
                    'TO' => __('To', 'wp-smart-contracts'),
                    'APPROVE' => __('Approve', 'wp-smart-contracts'),
                    'FINALIZE' => __('Finalize', 'wp-smart-contracts'),
                    'TRANSFERRED' => __('Transferred', 'wp-smart-contracts'),
                    'APPROVERS' => __('Approvers', 'wp-smart-contracts'),
                    'NATIVE_COIN' => $native_coin,
                    'WRITE_ADDRESS' => __('Please fill the address', 'wp-smart-contracts'),
                    'ERROR' => __('Error!', 'wp-smart-contracts'),
                    'WRITE_SELL_AMOUNT' => __('Please fill the sell amount in ' . $native_coin , 'wp-smart-contracts'),
                    'WRITE_RESERVE_AMOUNT' => __('Please fill the reserve amount in ' . $native_coin , 'wp-smart-contracts'),
                    'WRITE_END_DATE' => __('Please fill the end date' , 'wp-smart-contracts'),
                    'ANIMATED_GIF' => dirname( plugin_dir_url( __FILE__ ) ) . '/assets/img/deploy-identicon.gif',
                    'BID_HIGHER' => __('Your bid has to be higher than the current bid' , 'wp-smart-contracts'),
                    'BID_HIGHER_THAN_RESERVE' => __('Your bid has to be higher or equal than the reserve price' , 'wp-smart-contracts'),
                    'VIEW' => __('View' , 'wp-smart-contracts'),
                    'LOAD_MORE' => __('Load more' , 'wp-smart-contracts'),
                    'REQUESTED' => __('Requested' , 'wp-smart-contracts'),
                ]);
                break;
            case "coin-view-block-explorer-balance":
                $json = json_encode([
                    'BALANCE' => __('Balance', 'wp-smart-contracts'),
                    'ADD_MINTER' => __('Add Minter Role', 'wp-smart-contracts'),
                    'ADD_PAUSER' => __('Add Pauser Role', 'wp-smart-contracts'),
                    'PAUSE' => __('Pause', 'wp-smart-contracts'),
                    'CANCEL' => __('Cancel', 'wp-smart-contracts'),
                    'FILTERED' => __('Filtered by', 'wp-smart-contracts'),
                    'ERROR' => __('Error search data field', 'wp-smart-contracts'),
                    'NOT_VALID' => __('It doesn\'t look as a valid address or transaction ID', 'wp-smart-contracts'),
                    'ERROR2' => __('Error', 'wp-smart-contracts'),
                    'NOT_VALID_ETH_ADDRESS' => __('This is not a valid Ethereum address', 'wp-smart-contracts'),
                    'NO_RESULTS' => __('No transactions found', 'wp-smart-contracts'),
                    'FAILED' => __('FAILED', 'wp-smart-contracts'),
                    'CONFIRMED' => __('CONFIRMED', 'wp-smart-contracts'),
                    'TRANSFER' => __('TRANSFER', 'wp-smart-contracts'),
                    'TRANSFER_FROM' => __('TRANSFER FROM', 'wp-smart-contracts'),
                    'MINT' => __('MINT', 'wp-smart-contracts'),
                    'BURN' => __('BURN', 'wp-smart-contracts'),
                    'BURN_FROM' => __('BURN FROM', 'wp-smart-contracts'),
                    'APPROVE' => __('APPROVE', 'wp-smart-contracts'),
                    'RESUME' => __('RESUME', 'wp-smart-contracts'),
                    'PAUSE' => __('PAUSE', 'wp-smart-contracts'),
                    'CLICK_TO_FILTER' => __('Click to filter', 'wp-smart-contracts'),
                    'ALLOWANCE_TO_SPEND' => __("Your account has an allowance to spend from this account of:", 'wp-smart-contracts'),
                    'LATEST' => __("Latest account transactions", 'wp-smart-contracts'),
                    'ALL' => __("All transactions", 'wp-smart-contracts'),
                    'DETAIL' => __("Transaction detail", 'wp-smart-contracts'),
                    'WRITE_ADDRESS' => __("Please specify a valid address in the \"To address\" field", 'wp-smart-contracts'),
                    'WRITE_ADDRESS_FROM' => __("Please specify a valid address in the \"From address\" field", 'wp-smart-contracts'),
                    'WRITE_AMOUNT' => __("Please specify a valid amount greater than zero", 'wp-smart-contracts'),
                    'METAMASK_ERROR' => __('Unable to connect to the Ethereum Network. Please install and run MetaMask.', 'wp-smart-contracts'),
                    'WRONG_NETWORK' => __('Looks like You are connected to a different network, or contract not deployed.', 'wp-smart-contracts'),
                    'ACCOUNT_NOT_FOUND' => __('Do you see a "Connect to Ehereum Network" in WP admin bar? if so, click there to connect. Are you logged in to Metamask? if not, please log in. If the error persist try reloading this page.', 'wp-smart-contracts'), 
                    'NO_CONTRACT' => __('No contract found', 'wp-smart-contracts'),
                    'TRANSFER_CONFIRM' => __('Are you sure you want to transfer the specified amount to this address?', 'wp-smart-contracts'),
                    'APPROVE_CONFIRM' => __('Are you sure you want to approve the specified amount to this address?', 'wp-smart-contracts'),
                    'MINT_CONFIRM' => __('Are you sure you want to create the specified amount of tokens and add them to this address?', 'wp-smart-contracts'),
                    'BURN_CONFIRM' => __('Are you sure you want to destroy the specified amount of tokens from your account?', 'wp-smart-contracts'),
                    'TXID_ERROR_MESSAGE' =>  __('An error occurred processing this transaction', 'wp-smart-contracts'),
                    'IS_MINTER' =>  __('Minter', 'wp-smart-contracts'),
                    'IS_PAUSER' =>  __('Pauser', 'wp-smart-contracts'),
                    'BURN_FROM_CONFIRM' => __('Are you sure you want to destroy previously approved from this account?', 'wp-smart-contracts'),
                    'PAUSE_CONFIRM' => __('Are you sure you want to pause all the token activity?', 'wp-smart-contracts'),
                    'RESUME_CONFIRM' => __('Are you sure you want to resume all the token activity?', 'wp-smart-contracts'),
                    'ADD_PAUSER_CONFIRM' => __('Are you sure you give this account the privilege to pause token activity?', 'wp-smart-contracts'),
                    'ADD_MINTER_CONFIRM' => __('Are you sure you give this account the privilege to create new tokens?', 'wp-smart-contracts'),
                    'RENOUNCE_PAUSER_CONFIRM' => __('Are you sure you want to remove your pauser privilege from your account?', 'wp-smart-contracts'),
                    'RENOUNCE_MINTER_CONFIRM' => __('Are you sure you want to remove your minter privilege from your account?', 'wp-smart-contracts'),
                    'RENOUNCE_PAUSER' => __('Renounce Pauser', 'wp-smart-contracts'),
                    'CONTRACT_CREATION' => __('Contract Creation', 'wp-smart-contracts'),
                    'ICO_BUY_TOKENS' => __('ICO Buy Tokens', 'wp-smart-contracts'),
                    'ICO_DIRECT_TRANSFER' => __('ICO Direct Transfer', 'wp-smart-contracts'),
                    'EXCLUDE' => __('Exclude Account', 'wp-smart-contracts'),
                    'INCLUDE' => __('Include Account', 'wp-smart-contracts'),
                    'RENOUNCE_MINTER' => __('Renounce Minter', 'wp-smart-contracts'),
                ]);
                break;

            case "registration":
                $json = json_encode([
                    'ERROR' => __('Error', 'wp-smart-contracts'),
                    'CONNECT_WALLET' => __('Connect Wallet', 'wp-smart-contracts'),
                    'SIGN_UP_FAILED' => __('Sign up failed. The email address is not valid. To register, try again and provide a valid email.', 'wp-smart-contracts'),
                    'LOGIN_FAILED' => __('Login failed. Is this email address already associated with an account? If so, please try logging in with that account.', 'wp-smart-contracts'),
                    'REQUIRED_ACTIVACION' => __('Required Activation', 'wp-smart-contracts'),
                    'EMAIL_SENT' => __('An email has been sent to the registered email address, please click on the link provided to confirm your registration', 'wp-smart-contracts'),
                    'LOGIN_FAILED_SHORT' => __('Login failed', 'wp-smart-contracts'),
                    'WARNING' => __('Warning', 'wp-smart-contracts'),
                    'ERROR_SWITCHING' => __('An error occurred trying to switch the network. Try manually switching to the right network.', 'wp-smart-contracts'),
                    "VALID_EVM_ADDRESS" => __("Did you enter a valid EVM address?", 'wp-smart-contracts'),
                    "BAD_TOKEN" => __("The token was not properly loaded. If you are the administrator of the site, please verify that you are using a compatible ERC-20 or BEP-20 Token for payments.", 'wp-smart-contracts'),
                    'PLEASE_SWITCH' => __('Please switch to the correct network', 'wp-smart-contracts'),
                    'LOGIN_TO_UPLOAD' => __('To be able to upload files you need to log in', 'wp-smart-contracts'),
                    'CHOOSE_MEDIA_TYPE' => __('Please choose media type', 'wp-smart-contracts'),
                    'SWITCH_TO' => __('Switch to', 'wp-smart-contracts'),
                    'AUTHOR' => __('author', 'wp-smart-contracts'),
                    'FUNDS_APPROVED' => __('Funds Approved', 'wp-smart-contracts'),
                    'OPENED' => __('Opened', 'wp-smart-contracts'),
                    'END_STAKE_LABEL' => __('End Stake', 'wp-smart-contracts'),
                    'ENDED_LABEL' => __('Ended', 'wp-smart-contracts'),
                    'EMPTY_RESULT' => __('Empty Results', 'wp-smart-contracts'),
                    'NO_ITEMS_FOUND' => __('No items found on this collection', 'wp-smart-contracts'),
                    'SALE_PRICE' => __('Sale Price', 'wp-smart-contracts'),
                    'CURRENT_BID' => __('Current Bid', 'wp-smart-contracts'),
                    'SOLD' => __('Sold', 'wp-smart-contracts'),
                    'YOU' => __('You', 'wp-smart-contracts'),
                    'CANCEL' => __('Cancel', 'wp-smart-contracts'),
                    'BUY' => __('Buy', 'wp-smart-contracts'),
                    'ACCEPT' => __('Accept', 'wp-smart-contracts'),
                    'NOT_ENOUGH_BALANCE' => __('Not Enough Balance', 'wp-smart-contracts'),
                    'YOUR_BALANCE_IS' => __('Your balance is: ', 'wp-smart-contracts'),
                ]);
                break;

        }

        if ($json) {

            $wpsc_js_object_name = self::createJSObjectNameFromTag($tag);

            ?>
            <script type='text/javascript'>
            /* <![CDATA[ */
            var <?=$wpsc_js_object_name?> = <?=$json?>;
            /* ]]> */
            </script>
            <?php 

        }
    }

    // create a JS object name,
    // i.e. turns wp-admin-bar into WPSC_WP_ADMIN_BAR
    static public function createJSObjectNameFromTag($tag) {
        if ($tag == "registration") {
            return 'WPSC_REGISTRATION';
        } else {
            return 'WPSC_' . strtoupper(str_replace('-', '_', $tag));
        }
    }

    // print the JS Mustache template
    private function showTemplate($tempid) {
        self::loadTranslationsForTag($tempid);
        echo '<script id="'.$tempid.'" type="x-tmpl-mustache">';
        echo WPSC_Mustache::getTemplate($tempid);
        echo '</script>';
    }

    // get template content
    public static function getTemplate($tempid) {

        // set template file name
        $template = $tempid . '.mustache';
        // if template exists locally in the theme, replace it
        $template_path = get_stylesheet_directory() . '/wpsc-views/' . $template;
        if (!file_exists($template_path)) {
            $template_path = self::isSkinFile($template);
            if (!$template_path) { 
                $template_path = dirname(dirname(__FILE__)) . '/views/' . $template;
            }
        }

        return file_get_contents($template_path);

    }

    static public function getThemePath($url=false) {

        switch (WPSCSettingsPage::nftSkin()) {
            case 'light1':
                if ($url) {
                    return plugins_url( '/skins/wpsc-theme-light-1/', dirname(__FILE__) );
                } else {
                    return dirname(dirname(__FILE__)) . '/skins/wpsc-theme-light-1/';
                }
                break;
            case 'light2':
                if ($url) {
                    return plugins_url( '/skins/wpsc-theme-light-2/', dirname(__FILE__) );
                } else {
                    return dirname(dirname(__FILE__)) . '/skins/wpsc-theme-light-2/';
                }
                break;
            case '20':
            case '20red':
            case '20green':
            case '20pink':
            case '20black':
            case '20cream':
            case '20orange':
            case '20purple':
            case '20white':
            case '20white2':
            case '20white3':
            case '20white4':
                if ($url) {
                    return plugins_url( '/skins/2.0/', dirname(__FILE__) );
                } else {
                    return dirname(dirname(__FILE__)) . '/skins/2.0/';
                }
                break;
            case 'dark':
                if ($url) {
                    return plugins_url( '/skins/wpsc-theme-light-3/', dirname(__FILE__) );
                } else {
                    return dirname(dirname(__FILE__)) . '/skins/wpsc-theme-light-3/';
                }
                break;
        }

    }

    static private function isSkinFile($template) {
        // ignore skin in wp-admin
        if (is_admin()) return false;
        $skin_path = self::getThemePath();
        $skin_file = $skin_path . 'wpsc-views/' . $template;
        if ($skin_path and file_exists($skin_file)) {
            return $skin_file;
        }
    }

}
