<?php

if( ! defined( 'ABSPATH' ) ) die;

/**
 * Create Metaboxes for CPT Crypto
 */

new WPSC_Metabox();

class WPSC_Metabox {

  function __construct() {

    // load all custom fields
    add_action('admin_init', [$this, 'loadMetaboxes'], 2);

    // save repeatable fields
    add_action('save_post', [$this, 'saveRepeatableFields'], 10, 3);

  }

  public function loadMetaboxes() {

    add_meta_box(
      'wpsc_cryptocurrency_metabox', 
      'WPSmartContracts: Coin Specification', 
      [$this, 'wpscTokenSpecification'], 
      'coin', 
      'normal', 
      'default'
    );
    
    add_meta_box(
      'wpsc_smart_contract', 
      'WPSmartContracts: Smart Contract', 
      [$this, 'wpscSmartContract'], 
      'coin', 
      'normal', 
      'default'
    );
    
    add_meta_box(
      'wpsc_code', 
      'WPSmartContracts: Source Code', 
      [$this, 'wpscSourceCode'], 
      'coin', 
      'normal', 
      'default'
    );

    add_meta_box(
      'wpsc_reminder', 
      'WPSmartContracts: Friendly Reminder', 
      [__CLASS__, 'wpscReminder'], 
      'coin', 
      'normal', 
      'default'
    );

    add_meta_box(
      'wpsc_sidebar', 
      'WPSmartContracts: Tutorials & Tools', 
      [$this, 'wpscSidebar'], 
      'coin', 
      'side', 
      'default'
    );

  }

  // sanitize an input field
  static public function cleanUpText($text) {
    return htmlspecialchars(sanitize_text_field($text));
  }

  public function saveRepeatableFields($post_id, $post, $update) {

      if ($post->post_type == "coin") {

        if ( ! isset( $_POST['wpsc_repeatable_meta_box_nonce'] ) ||
        ! wp_verify_nonce( $_POST['wpsc_repeatable_meta_box_nonce'], 'wpsc_repeatable_meta_box_nonce' ) )
            return;

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
            return;

        if (!current_user_can('edit_post', $post_id))
            return;

        // if the contract was not deployed yet, save the token definitions
        if (!array_key_exists('wpsc-readonly', $_POST)) {

          // get and clean all inputs
          $wpsc_flavor = WPSC_Metabox::cleanUpText($_POST["wpsc-flavor"]);
          $wpsc_adv_burn = WPSC_Metabox::cleanUpText($_POST["wpsc-adv-burn"]);
          $wpsc_adv_pause = WPSC_Metabox::cleanUpText($_POST["wpsc-adv-pause"]);
          $wpsc_adv_mint = WPSC_Metabox::cleanUpText($_POST["wpsc-adv-mint"]);
          $wpsc_coin_name = WPSC_Metabox::cleanUpText($_POST["wpsc-coin-name"]);
          $wpsc_coin_symbol = WPSC_Metabox::cleanUpText($_POST["wpsc-coin-symbol"]);
          $wpsc_coin_decimals = WPSC_Metabox::cleanUpText($_POST["wpsc-coin-decimals"]);
          $wpsc_total_supply = WPSC_Metabox::cleanUpText($_POST["wpsc-total-supply"]);
          $wpsc_adv_cap = WPSC_Metabox::cleanUpText($_POST["wpsc-adv-cap"]);

          switch($_POST["wpsc-reflection-fee"]) {
            case "20%":
              $wpsc_reflection_fee = 5;
              break;
            case "10%":
              $wpsc_reflection_fee = 10;
              break;
            case "5%":
              $wpsc_reflection_fee = 20;
              break;
            case "3%":
              $wpsc_reflection_fee = 33;
              break;
            case "2%":
              $wpsc_reflection_fee = 50;
              break;
            default:
              $wpsc_reflection_fee = 100; // 1% by default
              break;
          }

          update_post_meta($post_id, 'wpsc_reflection_fee', $wpsc_reflection_fee);

          $wpsc_network = WPSC_Metabox::cleanUpText($_POST["wpsc-network"]);
          $wpsc_txid = WPSC_Metabox::cleanUpText($_POST["wpsc-txid"]);
          $wpsc_owner = WPSC_Metabox::cleanUpText($_POST["wpsc-owner"]);
          $wpsc_contract_address = WPSC_Metabox::cleanUpText($_POST["wpsc-contract-address"]);
          $wpsc_factory = $_POST["wpsc-factory"];
          $wpsc_encoded_parameters = $_POST["wpsc-encoded-parameters"];
          
          $wpsc_blockie = WPSC_Metabox::cleanUpText($_POST["wpsc-blockie"]);
          $wpsc_blockie_owner = WPSC_Metabox::cleanUpText($_POST["wpsc-blockie-owner"]);
          $wpsc_qr_code = WPSC_Metabox::cleanUpText($_POST["wpsc-qr-code"]);

          update_post_meta($post_id, 'wpsc_flavor', $wpsc_flavor);
          update_post_meta($post_id, 'wpsc_adv_burn', $wpsc_adv_burn);
          update_post_meta($post_id, 'wpsc_adv_pause', $wpsc_adv_pause);
          update_post_meta($post_id, 'wpsc_adv_mint', $wpsc_adv_mint);
          update_post_meta($post_id, 'wpsc_coin_name', $wpsc_coin_name);
          update_post_meta($post_id, 'wpsc_coin_symbol', $wpsc_coin_symbol);
          update_post_meta($post_id, 'wpsc_coin_decimals', $wpsc_coin_decimals);
          update_post_meta($post_id, 'wpsc_total_supply', $wpsc_total_supply);
          update_post_meta($post_id, 'wpsc_adv_cap', $wpsc_adv_cap);

          // if set, save the contract info meta
          if ($wpsc_network) update_post_meta($post_id, 'wpsc_network', $wpsc_network);
          if ($wpsc_txid) update_post_meta($post_id, 'wpsc_txid', $wpsc_txid);
          if ($wpsc_owner) update_post_meta($post_id, 'wpsc_owner', $wpsc_owner);
          if ($wpsc_contract_address) update_post_meta($post_id, 'wpsc_contract_address', $wpsc_contract_address);
          if ($wpsc_factory) update_post_meta($post_id, 'wpsc_factory', $wpsc_factory);
          if ($wpsc_encoded_parameters) update_post_meta($post_id, 'wpsc_encoded_parameters', $wpsc_encoded_parameters);
          if ($wpsc_blockie) update_post_meta($post_id, 'wpsc_blockie', $wpsc_blockie);
          if ($wpsc_blockie_owner) update_post_meta($post_id, 'wpsc_blockie_owner', $wpsc_blockie_owner);
          if ($wpsc_qr_code) update_post_meta($post_id, 'wpsc_qr_code', $wpsc_qr_code);

        } 

        // remove the first element of the social network array, this is a base network used to clone
        if (WPSC_helpers::valArrElement($_POST, "wpsc-social-icon")) {
          array_shift($_POST["wpsc-social-icon"]);
          $wpsc_social_icon = array_map( ['WPSC_Metabox', 'cleanUpText'], $_POST["wpsc-social-icon"] );
          update_post_meta($post_id, 'wpsc_social_icon', $wpsc_social_icon);
        }
        if (WPSC_helpers::valArrElement($_POST, "wpsc-social-link")) {
          array_shift($_POST["wpsc-social-link"]);
          $wpsc_social_link = array_map( ['WPSC_Metabox', 'cleanUpText'], $_POST["wpsc-social-link"] );
          update_post_meta($post_id, 'wpsc_social_link', $wpsc_social_link);
        }
        if (WPSC_helpers::valArrElement($_POST, "wpsc-social-name")) {
          array_shift($_POST["wpsc-social-name"]);
          $wpsc_social_name = array_map( ['WPSC_Metabox', 'cleanUpText'], $_POST["wpsc-social-name"] );
          update_post_meta($post_id, 'wpsc_social_name', $wpsc_social_name);
        }

      }

  }

  public function wpscTokenSpecification() {

    $m = new Mustache_Engine;

    wp_nonce_field( 'wpsc_repeatable_meta_box_nonce', 'wpsc_repeatable_meta_box_nonce' );

    echo $m->render(
      WPSC_Mustache::getTemplate('metabox-token'),
      self::getMetaboxTokenArgs()
    );
  }

  public static function getMetaboxTokenArgs($show_social=true) {

    global $post;

    $m = new Mustache_Engine;

    $wpsc_flavor          = get_post_meta(get_the_ID(), 'wpsc_flavor', true);
    $wpsc_adv_burn        = get_post_meta(get_the_ID(), 'wpsc_adv_burn', true);
    $wpsc_adv_pause       = get_post_meta(get_the_ID(), 'wpsc_adv_pause', true);
    $wpsc_adv_mint        = get_post_meta(get_the_ID(), 'wpsc_adv_mint', true);
    $wpsc_coin_name       = get_post_meta(get_the_ID(), 'wpsc_coin_name', true);
    $wpsc_coin_symbol     = get_post_meta(get_the_ID(), 'wpsc_coin_symbol', true);
    $wpsc_coin_decimals   = get_post_meta(get_the_ID(), 'wpsc_coin_decimals', true);
    $wpsc_total_supply    = get_post_meta(get_the_ID(), 'wpsc_total_supply', true);
    $wpsc_adv_cap         = get_post_meta(get_the_ID(), 'wpsc_adv_cap', true);
    $wpsc_reflection_fee  = get_post_meta(get_the_ID(), 'wpsc_reflection_fee', true);

    $wpsc_social_icon   = get_post_meta(get_the_ID(), 'wpsc_social_icon', true);
    $wpsc_social_link   = get_post_meta(get_the_ID(), 'wpsc_social_link', true);
    $wpsc_social_name   = get_post_meta(get_the_ID(), 'wpsc_social_name', true);

    $args = [
      'smart-contract' => __('Smart Contract', 'wp-smart-contracts'),
      'coin-spec' =>  __('Coin Specification', 'wp-smart-contracts'), 
      'learn-more' =>  __('Learn More', 'wp-smart-contracts'),
      'coin-spec-desc' =>  __('Choose the type of ERC-20 Contracts that better suit your needs.', 'wp-smart-contracts'),
      'flavor' =>  __('Flavor', 'wp-smart-contracts'),
      'erc-20-gas-saving' =>  __('ERC-20 Gas Saving Token', 'wp-smart-contracts'),
      'erc-20-gas-saving-desc' =>  __('A Standard ERC-20 Token, focused on Gas Saving transactions', 'wp-smart-contracts'),
      'erc-20-reflection' =>  __('ERC-20 Reflection Token', 'wp-smart-contracts'),
      'erc-20-reflection-desc' =>  __('An ERC-20 Token with Hold & Earn feature', 'wp-smart-contracts'),
      'erc-20-reflection-1' =>  __('Rewards on every transaction', 'wp-smart-contracts'),
      'erc-20-reflection-1-tooltip' =>  __('Apply a fee to each transaction', 'wp-smart-contracts'),
      'erc-20-reflection-2' =>  __('Balance updated instantly', 'wp-smart-contracts'),
      'erc-20-reflection-2-tooltip' =>  __('Automatically split the fees between holders', 'wp-smart-contracts'),
      'erc-20-reflection-3' =>  __('No need to stake', 'wp-smart-contracts'),
      'erc-20-reflection-3-tooltip' =>  __('Users do not need to stake to get rewards, just hold tokens', 'wp-smart-contracts'),
      'Features' =>  __('Features', 'wp-smart-contracts'),
      'erc-20-compliant' =>  __('ERC-20 compliant', 'wp-smart-contracts'),
      'balance' =>  __('Balance', 'wp-smart-contracts'),
      'transfer' =>  __('Transfer', 'wp-smart-contracts'),
      'approve' =>  __('Approve', 'wp-smart-contracts'),
      'erc-20-imp-sec' =>  __('ERC-20 Improved Security Token', 'wp-smart-contracts'),
      'erc-20-imp-sec-desc' =>  __('A Standard Ethereum Token, focused on security', 'wp-smart-contracts'),
      'features' =>  __('Features', 'wp-smart-contracts'),
      'erc-20-compliant' =>  __('ERC-20 compliant', 'wp-smart-contracts'),
      'balance' =>  __('Balance', 'wp-smart-contracts'),
      'transfer' =>  __('Transfer', 'wp-smart-contracts'),
      'approve' =>  __('Approve', 'wp-smart-contracts'),
      'erc-20-advanced' =>  __('ERC-20 Advanced Token', 'wp-smart-contracts'),
      'erc-20-advanced-tooltip' =>  __('An Standard ERC-20 Token, secure with advanced features.', 'wp-smart-contracts'),
      'features' =>  __('Features', 'wp-smart-contracts'),
      'erc-20-compliant' =>  __('ERC-20 compliant', 'wp-smart-contracts'),
      'balance' =>  __('Balance', 'wp-smart-contracts'),
      'transfer' =>  __('Transfer', 'wp-smart-contracts'),
      'approve' =>  __('Approve', 'wp-smart-contracts'),
      'burn' =>  __('Burn', 'wp-smart-contracts'), 
      'mint' =>  __('Mint', 'wp-smart-contracts'),
      'pause' =>  __('Pause', 'wp-smart-contracts'),
      'definition' =>  __('Definition', 'wp-smart-contracts'),
      'name' =>  __('Name', 'wp-smart-contracts'),
      'name-desc' =>  __('The name of the coin', 'wp-smart-contracts'),
      'symbol' =>  __('Symbol', 'wp-smart-contracts'),
      'symbol-desc' =>  __('The symbol of the coin. Keep it short - e.g. "HIX"', 'wp-smart-contracts'),
      'decimals' =>  __('Decimals', 'wp-smart-contracts'),
      'decimals-desc' =>  __('The number of decimals the coin uses', 'wp-smart-contracts'),
      'total-supply' =>  __('Initial Supply', 'wp-smart-contracts'),
      'total-supply-desc' =>  __('The initial amount of coins for your contract.', 'wp-smart-contracts'),
      'advanced-options' =>  __('Advanced Options', 'wp-smart-contracts'),
      'burnable' =>  __('Burnable', 'wp-smart-contracts'),
      'burnable-desc' =>  __('Ability to irreversibly burn (destroy) coins you own.', 'wp-smart-contracts'),

      'reflection-fee' => __('Reflection percentage fee', 'wp-smart-contracts'),
      'reflection-fee-desc' =>  __('The fee that every user will pay when transfering tokens', 'wp-smart-contracts'),
      'reflection-fee-tooltip' => $m->render(WPSC_Mustache::getTemplate('tooltip'), ['tip' => __('The fee can range from 1% to 20% for every transfer. All users will pay this fee, and every -not excluded- holder will receive a commission automatically.' , 'wp-smart-contracts')]),
      'percent' => __('Percent fee', 'wp-smart-contracts'),

      'pausable' =>  __('Pausable', 'wp-smart-contracts'),
      'pausable-desc' =>  __('Ability to pause all the activity of the coins.', 'wp-smart-contracts'),
      'mintable' =>  __('Mintable', 'wp-smart-contracts'),
      'mitable-desc' =>  __('Ability to create (mint) new coins for any account.', 'wp-smart-contracts'),
      'mintable-cap' =>  __('Mintable Cap', 'wp-smart-contracts'),
      'mintable-desc' =>  __('This will be the maximum supply that the coin can reach when minting', 'wp-smart-contracts'),
      'erc-20-compliant-tooltip' => __('Ethereum Token compatible with any ERC-20 standard wallet', 'wp-smart-contracts'),
      'balance-tooltip' => __('Check the balance of specific accounts', 'wp-smart-contracts'),
      'transfer-tooltip' => __('Transfer an amount of tokens between accounts', 'wp-smart-contracts'),
      'approve-tooltip' => __('Allows an authorized spender to withdraw your tokens up to a specified amount', 'wp-smart-contracts'),
      'erc-20-compliant-tooltip' => __('Ethereum Token compatible with any ERC-20 standard wallet', 'wp-smart-contracts'),
      'balance-tooltip' => __('Check the balance of specific accounts', 'wp-smart-contracts'),
      'approve-tooltip' => __('Allows an authorized spender to withdraw your tokens up to a specified amount', 'wp-smart-contracts'),
      'erc-20-compliant-tooltip' => __('Ethereum Token compatible with any ERC-20 standard wallet', 'wp-smart-contracts'),
      'balance-tooltip' => __('Check the balance of specific accounts', 'wp-smart-contracts'),
      'transfer-tooltip' => __('Transfer an amount of tokens between accounts', 'wp-smart-contracts'),
      'approve-tooltip' => __('Allows an authorized spender to withdraw your tokens up to a specified amount', 'wp-smart-contracts'),
      'burn-tooltip' => __('Holders can destroy (burn) their tokens', 'wp-smart-contracts'),
      'mint-tooltip' => __('Authorized accounts can create (mint) new tokens', 'wp-smart-contracts'),
      'pause-tooltip' => __('Authorized accounts can pause all the token activity', 'wp-smart-contracts'),
      'name-tooltip' => $m->render(WPSC_Mustache::getTemplate('tooltip'), ['tip' => __('By default the post title will be used if not defined here. Once the contract is deployed this name will be frozen.', 'wp-smart-contracts')]),
      'decimals-tooltip' => $m->render(WPSC_Mustache::getTemplate('tooltip'), ['tip' => __('This is fixed to 18 decimals', 'wp-smart-contracts')]),
      'total-supply-tooltip' => $m->render(WPSC_Mustache::getTemplate('tooltip'), ['tip' => __("The initial supply is the total amount of coins that your contract will have at the moment of creation. The amount is integer, do not include decimal representation or wei like numbers. This is going to be also the initial balance of the creator's account.", 'wp-smart-contracts')]),
      'burnable-tooltip' => $m->render(WPSC_Mustache::getTemplate('tooltip'), ['tip' => __('Holders will have the ability to burn a specific amount of tokens. This will reduce the total supply of the coin', 'wp-smart-contracts')]),
      'pausable-tooltip' => $m->render(WPSC_Mustache::getTemplate('tooltip'), ['tip' => __('Authorized accounts will have the ability to pause/unpause all transfer and all activity of the coins.', 'wp-smart-contracts')]),
      'mintable-tooltip' => $m->render(WPSC_Mustache::getTemplate('tooltip'), ['tip' => __('Authorized accounts will have the ability to create or mint a specific amount of coins. This will increment the total supply of the coins.', 'wp-smart-contracts')]),
      'mintable-tooltip-cap' => $m->render(WPSC_Mustache::getTemplate('tooltip'), ['tip' => __('The maximum capitalization your coin can have. This is an integer number, do not include decimal representation or wei like numbers. 0 cap means unlimited capitalization.', 'wp-smart-contracts')]),
      'social-networks-tooltip' => __('This social networks will be shown in the block explorer section of your coin', 'wp-smart-contracts'),
      'social-networks-html' => (isset($social_networks))?$social_networks:null,
      'img-vanilla' => dirname(plugin_dir_url( __FILE__ )) . '/assets/img/vanilla-card.png',
      'img-pistachio' => dirname(plugin_dir_url( __FILE__ )) . '/assets/img/pistachio-card.png',
      'img-chocolate' => dirname(plugin_dir_url( __FILE__ )) . '/assets/img/chocolate-card.png',
      'img-macadamia' => dirname(plugin_dir_url( __FILE__ )) . '/assets/img/macadamia-card.png',
      'img-custom' => dirname(plugin_dir_url( __FILE__ )) . '/assets/img/custom-card.png',
      'erc-20-custom' => __('Looking for something else?', 'wp-smart-contracts'),
      'custom-message' => __('If you need to create a smart contract with custom features we can help', 'wp-smart-contracts'),
      'contact-us' => __('Contact us', 'wp-smart-contracts'),

      'wpsc-coin-name' => $wpsc_coin_name,
      'wpsc-coin-symbol' => $wpsc_coin_symbol,
      'wpsc-coin-decimals' => $wpsc_coin_decimals,
      'wpsc-total-supply' => $wpsc_total_supply,
      'wpsc-adv-cap' => $wpsc_adv_cap,
      'show_social' => $show_social
    ];

    switch($wpsc_reflection_fee) {
      case 5:
        $args["percent-20"] = true;
        $args["wpsc_reflection_fee"] = "20%";
        break;
      case 10:
        $args["percent-10"] = true;
        $args["wpsc_reflection_fee"] = "10%";
        break;
      case 20:
        $args["percent-5"] = true;
        $args["wpsc_reflection_fee"] = "5%";
        break;
      case 33:
        $args["percent-3"] = true;
        $args["wpsc_reflection_fee"] = "3%";
        break;
      case 50:
        $args["percent-2"] = true;
        $args["wpsc_reflection_fee"] = "2%";
        break;
      default:
        $args["percent-1"]   = true;
        $args["wpsc_reflection_fee"] = "1%"; // 1% by default
        break;
    }

    if ($wpsc_flavor=="vanilla") $args["is-vanilla"] = true;
    if ($wpsc_flavor=="pistachio") $args["is-pistachio"] = true;
    if ($wpsc_flavor=="chocolate") $args["is-chocolate"] = true;
    if ($wpsc_flavor=="macadamia") $args["is-macadamia"] = true;
    if ($wpsc_adv_burn=="burnable") $args["is-burnable"] = true;
    if ($wpsc_adv_pause=="pausable") $args["is-pausable"] = true;
    if ($wpsc_adv_mint=="mintable") $args["is-mintable"] = true;

    $wpsc_contract_address = get_post_meta(get_the_ID(), 'wpsc_contract_address', true);

    // show contract definition
    if ($wpsc_contract_address) {
      $args["readonly"] = true;
    }

    return $args;

  }

  static public function getNetworkInfo($wpsc_network) {

    if ($wpsc_network and  $arr = WPSC_helpers::getNetworks() ) {

      return [
        WPSC_helpers::valArrElement($arr, $wpsc_network)?$arr[$wpsc_network]["color"]:"",
        WPSC_helpers::valArrElement($arr, $wpsc_network)?$arr[$wpsc_network]["icon"]:"",
        WPSC_helpers::valArrElement($arr, $wpsc_network)?$arr[$wpsc_network]["url2"]:"",
        WPSC_helpers::valArrElement($arr, $wpsc_network)?__($arr[$wpsc_network]["title"], 'wp-smart-contracts'):""
      ];

    }

    return ["", "", "", ""];

  }

  public function wpscSmartContract($show_load=true) {

    $wpsc_network = get_post_meta(get_the_ID(), 'wpsc_network', true);
    $wpsc_txid = get_post_meta(get_the_ID(), 'wpsc_txid', true);
    $wpsc_owner = get_post_meta(get_the_ID(), 'wpsc_owner', true);
    $wpsc_contract_address = get_post_meta(get_the_ID(), 'wpsc_contract_address', true);
    $wpsc_blockie = get_post_meta(get_the_ID(), 'wpsc_blockie', true);
    $wpsc_blockie_owner = get_post_meta(get_the_ID(), 'wpsc_blockie_owner', true);
    $wpsc_qr_code = get_post_meta(get_the_ID(), 'wpsc_qr_code', true);

    list($color, $icon, $etherscan, $network_val) = WPSC_Metabox::getNetworkInfo($wpsc_network);

    $m = new Mustache_Engine;

    // show contract
    if ($wpsc_contract_address) {

      $wpsc_flavor        = get_post_meta(get_the_ID(), 'wpsc_flavor', true);
      $wpsc_adv_burn      = get_post_meta(get_the_ID(), 'wpsc_adv_burn', true);
      $wpsc_adv_pause     = get_post_meta(get_the_ID(), 'wpsc_adv_pause', true);
      $wpsc_adv_mint      = get_post_meta(get_the_ID(), 'wpsc_adv_mint', true);
      $wpsc_coin_name     = get_post_meta(get_the_ID(), 'wpsc_coin_name', true);
      $wpsc_coin_symbol   = get_post_meta(get_the_ID(), 'wpsc_coin_symbol', true);
      $wpsc_coin_decimals = get_post_meta(get_the_ID(), 'wpsc_coin_decimals', true);
      $wpsc_total_supply  = get_post_meta(get_the_ID(), 'wpsc_total_supply', true);
      $wpsc_adv_cap       = get_post_meta(get_the_ID(), 'wpsc_adv_cap', true);

      $tokenInfo = [
        "type" => $wpsc_flavor,
        "symbol" => $wpsc_coin_symbol,
        "name" => $wpsc_coin_name,
        "decimals" => $wpsc_coin_decimals,
        "supply" => $wpsc_total_supply,
        "symbol_label" => __('Symbol', 'wp-smart-contracts'),
        "name_label" => __('Name', 'wp-smart-contracts'),
        "decimals_label" => __('Decimals', 'wp-smart-contracts'),
        "initial_label" => __('Initial Supply', 'wp-smart-contracts'),
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
      if ($wpsc_flavor=="vanilla") $tokenInfo["color"] = "yellow";
      if ($wpsc_flavor=="pistachio") $tokenInfo["color"] = "olive";
      if ($wpsc_flavor=="macadamia") $tokenInfo["color"] = "beige";

      $tokenInfo["imgUrl"] = dirname( plugin_dir_url( __FILE__ ) ) . '/assets/img/';

       $atts = [
        'smart-contract' => __('Smart Contract', 'wp-smart-contracts'),
        'learn-more' => __('Learn More', 'wp-smart-contracts'),
        'smart-contract-desc' => __('Go live with your Coin. You can publish your ERC-20 Token in a test net or in the main network.', 'wp-smart-contracts'),
        'deployed-smart-contract' => __('Deployed Smart Contract', 'wp-smart-contracts'),
        'ethereum-network' => $network_val,
        'ethereum-color' => $color,
        'ethereum-icon' => $icon,
        'contract-address' => $wpsc_contract_address,
        'etherscan' => $etherscan,
        'contract-address-text' => __('Contract Address', 'wp-smart-contracts'),
        'contract-address-desc' => __('The Smart Contract Address of your coin', 'wp-smart-contracts'),
        'txid-text' => __('Transaction ID', 'wp-smart-contracts'),
        'owner-text' => __('Owner Account', 'wp-smart-contracts'),
        'token-name' => ucwords(get_post_meta(get_the_ID(), 'wpsc_coin_name', true)),
        'token-symbol' => strtoupper(get_post_meta(get_the_ID(), 'wpsc_coin_symbol', true)), 
        'qr-code-data' => $wpsc_qr_code,
        'blockie' => $m->render(WPSC_Mustache::getTemplate('blockies'), ['blockie' => $wpsc_blockie]),
        'blockie-owner' => $m->render(WPSC_Mustache::getTemplate('blockies'), ['blockie' => $wpsc_blockie_owner]),
        'token-info' => $m->render(WPSC_Mustache::getTemplate('token-info'), $tokenInfo),
        'token-logo' => get_the_post_thumbnail_url(get_the_ID()),
        'contract-address-short' => WPSC_helpers::shortify($wpsc_contract_address),
        'txid' => $wpsc_txid,
        'txid-short' => WPSC_helpers::shortify($wpsc_txid),
        'owner' => $wpsc_owner,
        'owner-short' => WPSC_helpers::shortify($wpsc_owner),
        'block-explorer' => __('Block Explorer', 'wp-smart-contracts'),
      ];

      if ($wpsc_txid) {
        $atts["txid_exists"] = true;
      }

      echo $m->render(WPSC_Mustache::getTemplate('metabox-smart-contract'), $atts);

    // show buttons to load or create a contract
    } else {

      echo $m->render(
        WPSC_Mustache::getTemplate('metabox-smart-contract-buttons'),
        self::getSmartContractButtons($show_load)
      );

    }

  }

  public static function getSmartContractButtons($show_load=true) {

    $m = new Mustache_Engine;

    return [
      'smart-contract' => __('Smart Contract', 'wp-smart-contracts'),
      'learn-more' => __('Learn More', 'wp-smart-contracts'),
      'smart-contract-desc' => __('Go live with your Coin. You can publish your ERC-20 Token in a test net or in the main network.', 'wp-smart-contracts'),
      'new-smart-contract' => __('New Smart Contract', 'wp-smart-contracts'),
      'text' => __('To deploy your Smart Contracts you need to be connected to a Network. Please install and connect to Metamask.', 'wp-smart-contracts'),
      'fox' => plugins_url( "assets/img/metamask-fox.svg", dirname(__FILE__) ),
      'connect-to-metamask' => __('Connect to Metamask', 'wp-smart-contracts'),
      'deploy-with-wpst' => $m->render(
        WPSC_Mustache::getTemplate('metabox-smart-contract-buttons-wpst'),
        [
          'show_load' => $show_load,
          'deploy' => __('Deploy', 'wp-smart-contracts'),
          'deploy-desc' => __('Deploy to the selected EVM', 'wp-smart-contracts'),
          'deploy-desc-token' => __('Deploy your Smart Contract to the Blockchain using WPIC is a two step process:', 'wp-smart-contracts'),
          'deploy-desc-token-1' => __('First you need to authorize the factory to use the WPIC funds', 'wp-smart-contracts'),
          'deploy-desc-token-2' => __('Then you can deploy your contract using WPIC', 'wp-smart-contracts'),
          'no-wpst' => __('No WPIC found', 'wp-smart-contracts'),
          'not-enough-wpst' => __('Not enough WPIC found', 'wp-smart-contracts'),
          'authorize' => __('Authorize', 'wp-smart-contracts'),
          'authorize-complete' => __('Authorization was successful, click "Deploy" to proceed', 'wp-smart-contracts'),
          'deploy-token' => __('Deploy using WP Ice Cream (WPIC)', 'wp-smart-contracts'),
          'deploy-token-image' => dirname( plugin_dir_url( __FILE__ ) ) . '/assets/img/wp-smart-token.png',
          'deploy-using-ether' => __('Deploy', 'wp-smart-contracts'),
          'switch-networks' => __('Ethereum fees too expensive?', 'wp-smart-contracts'),
          'switch-explain' => __('Deploy your contracts to Layer 2 Solutions', 'wp-smart-contracts'),
          'switch-explain-2' => __('Deploy your contracts with lower fees', 'wp-smart-contracts'),
          'switch-explain-3' => __('You can use different blockchains, like Binance Smart Chain, xDai or Polygon (Matic)', 'wp-smart-contracts'),
          'switch-explain-4' => __('Choose the network below and click on Switch button', 'wp-smart-contracts'),
          'learn-how-to-get-wpst' => __('Learn how to get WPIC', 'wp-smart-contracts'),
          'learn-how-to-get-coins' => __('Learn how to get coins for other blockchains', 'wp-smart-contracts'),
          'do-you-have-an-erc20-address' => __('Do you already have an ERC20 token address?', 'wp-smart-contracts'),
          'wpst-balance' => __('WPIC Balance', 'wp-smart-contracts'),
          'load' => __('Load', 'wp-smart-contracts'),
          'load-desc' => __('Load an existing Smart Contract', 'wp-smart-contracts'),
          'button-id' => "wpsc-deploy-contract-button",
          'authorize-button-id' => "wpsc-deploy-contract-button-wpst-authorize",
          'deploy-button-wpst' => "wpsc-deploy-contract-button-wpst-deploy",

          'one-time-deployment-fee}' => __('* This is a one-time deployment fee. The gas fee is not included in this price.', 'wp-smart-contracts'),
          'prices-in-use-are-estimates' => __('Prices in USD are estimates and may change, check with your favorite exchange for the latest prices', 'wp-smart-contracts'),
          'DYOR' => __('DYOR', 'wp-smart-contracts'),
          'we-recommend-dyor' => __('We recommend that you <strong>Do Your Own Research</strong>, some networks shown here are in the early stages.', 'wp-smart-contracts'),
          'use-at-your-risk' => __('Use them at your own discretion and risk.', 'wp-smart-contracts'),
          'deploy-selected-network' => __('Deploy to the selected network', 'wp-smart-contracts'),
          'other-networks' => __('Other networks available', 'wp-smart-contracts'),
          'network' => __('Network', 'wp-smart-contracts'),
          'type' => __('Type', 'wp-smart-contracts'),
          'pricing' => __('Pricing', 'wp-smart-contracts'),
          'expand-all' => __('Expand all', 'wp-smart-contracts'),
          'please-enable-more-options' => __('Please enable more options in the filter to get some results', 'wp-smart-contracts'),
          'reset-the-filter' => __('Reset the filter', 'wp-smart-contracts'),
          'filter-by' => __('Filter by', 'wp-smart-contracts'),
          'mainnet' => __('Mainnet', 'wp-smart-contracts'),
          'testnet' => __('Testnet', 'wp-smart-contracts'),
          'others' => __('Others', 'wp-smart-contracts'),
          'collapse-all' => __('Collapse all', 'wp-smart-contracts'),
          'learn-more-networks' => __('Learn more about available networks', 'wp-smart-contracts'),
          'wpic-not-available' => __('WPIC Not Available', 'wp-smart-contracts'),
          'wpic-learn-more' => __('Learn more about WP Ice Cream (WPIC)', 'wp-smart-contracts'),

        ]
      ),

      'ethereum-address' => __('Ethereum Network Contract Address', 'wp-smart-contracts'),
      'ethereum-address-desc' => __('Please fill out the contract address you want to import', 'wp-smart-contracts'),
      'ethereum-address-important' => __('Important', 'wp-smart-contracts'),
      'ethereum-address-important-message' => __('Keep in mind that the contract is going to be loaded using the current network and current account as owner', 'wp-smart-contracts'),
      'active-net-account' => __('Currently active Ethereum Network and account:', 'wp-smart-contracts'),
      'smart-contract-address' => __('Smart Contract Address'),
      'load' => __('Load', 'wp-smart-contracts'),
      'ethereum-deploy' => __('Network Deploy', 'wp-smart-contracts'),
      'ethereum-deploy-desc' => __('Are you ready to deploy your Coin to the currently active Ethereum Network?', 'wp-smart-contracts'),
      'cancel' => __('Cancel', 'wp-smart-contracts'),
      'yes-proceed' => __('Yes, please proceed', 'wp-smart-contracts'),
      'deployed-smart-contract' => __('Deployed Smart Contract', 'wp-smart-contracts'),
    ];

  }

  public static function wpscGetMetaSourceCodeAtts($the_id=null) {

    if (!$the_id) $the_id = get_the_ID();

    // load the contract technical atts
    $wpsc_factory = get_post_meta($the_id, 'wpsc_factory', true);

    $atts = [];

    if ($wpsc_factory) {

      $atts['wpsc-encoded-parameters'] = get_post_meta($the_id, 'wpsc_encoded_parameters', true);

      $arr_factory = json_decode($wpsc_factory, true);

      // ERC20 contract and compiler name

      if (WPSC_helpers::valArrElement($arr_factory, "tokenContractName"))
        $atts['tokenContractName'] = $arr_factory["tokenContractName"];

      if (WPSC_helpers::valArrElement($arr_factory, "tokenCompilerVersion"))
        $atts['tokenCompilerVersion'] = $arr_factory["tokenCompilerVersion"];

      // new contracts contract and compiler name

      if (WPSC_helpers::valArrElement($arr_factory, "contractName"))
        $atts['contractName'] = $arr_factory["contractName"];

      if (WPSC_helpers::valArrElement($arr_factory, "compilerVersion"))
        $atts['compilerVersion'] = $arr_factory["compilerVersion"];

      if (WPSC_helpers::valArrElement($arr_factory, "source")) {
        $atts['source'] = $arr_factory["source"];
      }

      if (WPSC_helpers::valArrElement($arr_factory, "abi"))
        $atts['abi'] = json_encode($arr_factory["abi"], JSON_PRETTY_PRINT);

      if (WPSC_helpers::valArrElement($arr_factory, "version"))
        $atts['version'] = $arr_factory["version"];

      $atts['contrac-source-code'] = __('Contract Source Code', 'wp-smart-contracts');
      $atts['copy-source-code'] = __('Copy Source Code', 'wp-smart-contracts');
      $atts['open-source-code'] = __('View Contract Source Code', 'wp-smart-contracts');
      $atts['source-code-etherscan'] = __('Solidity Source Code verified in Explorer', 'wp-smart-contracts');
      $atts['copy-contract-abi'] = __('Copy Contract ABI', 'wp-smart-contracts');
      $atts['copy-encoded-arguments'] = __('Copy Constructor Arguments', 'wp-smart-contracts');
      $atts['open-contract-abi'] = __('View Contract ABI', 'wp-smart-contracts');
      $atts['contract-abi-etherscan'] = __('Contract ABI verified in Explorer', 'wp-smart-contracts');
      $atts['contract-name'] = __('Contract Name', 'wp-smart-contracts');
      $atts['solidity-version'] = __('Solidity version', 'wp-smart-contracts');
      $atts['contract-abi'] = __('Contract ABI', 'wp-smart-contracts');

      $wpsc_network = get_post_meta($the_id, 'wpsc_network', true);
      list($color, $icon, $etherscan, $network_val) = WPSC_Metabox::getNetworkInfo($wpsc_network);

      $atts['etherscan'] = $etherscan;
      $atts['contract-address'] = get_post_meta($the_id, 'wpsc_contract_address', true);

    }

    return $atts;

  }

  public function wpscSourceCode() {

      // load the contract technical atts
      $atts = self::wpscGetMetaSourceCodeAtts();

      if (!empty($atts)) {

        $m = new Mustache_Engine;
        echo $m->render(
          WPSC_Mustache::getTemplate('metabox-source-code'),
          $atts
        );

      }

  }

  public static function wpscReminder() {

      $m = new Mustache_Engine;
      echo $m->render(
        WPSC_Mustache::getTemplate('metabox-reminder'),
        [
          'reminders' => [
            __('Be cautious with your cryptocurrencies.', 'wp-smart-contracts'),
            __("We at WPSmartContracts are not responsible for your actions using this plugin, it is completely forbidden to use this software to scam or harm in any way. We truly hope that you do good and feel empowered by the potential that this technology puts in the hands of the people.", 'wp-smart-contracts'),
            __('The Smart Contracts we use are developed following high standards for code quality and security, most of them provide tested and community-audited code, but please use common sense when doing anything that deals with real money! If you intend to do a real project, you have to test the contracts in Testnets and do a proper quality assurance tailored to your specs.', 'wp-smart-contracts'),
            __('Although WPSmartContracts makes its best effort to create the best tool to deploy and use Smart Contracts we have no liability resulting from the use of this software in the case of any interruption, malfunction, downtime, loss or damage of any kind.', 'wp-smart-contracts'),
            __('WP Smart Contracts is licensed under the <a href="https://wpsmartcontracts.com/mit-license/" target="_blank">MIT License</a>', 'wp-smart-contracts')
          ]
        ]
      );

  }

  public function wpscSidebar() {

    $m = new Mustache_Engine;
    echo $m->render(
      WPSC_Mustache::getTemplate('metabox-sidebar'),
      [
        'white-logo' => dirname( plugin_dir_url( __FILE__ ) ) . '/assets/img/wpsc-white-logo.png',
        'tutorials-tools' => __('Tutorials', 'wp-smart-contracts'),
        'tutorials-tools-desc' => __('Here you can find a few tutorials that might be useful to deploy, test and use your Smart Contracts.', 'wp-smart-contracts'),
        'screencasts' => __('Screencasts'),
        'deploy' => __('How to deploy coins using Ether?'),
        'deploy_vanilla' => '9COzCduWl3s',
        'deploy_pistachio' => 'kmWGVFB_RZk',
        'deploy_chocolate' => 'M2ZWw_HDu1g',
        'wpic_info' => WPSC_helpers::renderWPICInfo(),
        'learn-more' => __('Learn More', 'wp-smart-contracts'),
        'docs' => __('Documentation', 'wp-smart-contracts'),
        'doc_vanilla' => "https://wpsmartcontracts.com/doc-coins-flavor-vanilla.php",
        'doc_pistachio' => "https://wpsmartcontracts.com/doc-coins-flavor-pistachio.php",
        'doc_chocolate' => "https://wpsmartcontracts.com/doc-coins-flavor-chocolate.php",
        'wpsc-logo' => dirname( plugin_dir_url( __FILE__ )) . '/assets/img/wpsc-logo.png',
        'choose-network' => $m->render(WPSC_Mustache::getTemplate('choose-network'), [
          "switch" => __('Switch', 'wp-smart-contracts'),
          "choose-network" => __('Choose network', 'wp-smart-contracts')
        ]),
        'switch-networks' => __('Ethereum fees too expensive?', 'wp-smart-contracts'),
        'learn-how-to-get-wpst' => __('Learn how to get WPIC', 'wp-smart-contracts'),
        'learn-how-to-get-ether' => __('Learn how to get Ether', 'wp-smart-contracts'),
        'learn-how-to-get-coins' => __('Learn how to get coins for other blockchains', 'wp-smart-contracts'),
        'switch-explain' => __('Deploy your contracts to Layer 2 Solutions', 'wp-smart-contracts'),
        'switch-explain-2' => __('Deploy your contracts with lower fees', 'wp-smart-contracts'),
        'switch-explain-3' => __('You can use different blockchains, like Binance Smart Chain, xDai or Polygon (Matic)', 'wp-smart-contracts'),
        'switch-explain-4' => __('Choose the network below and click on Switch button', 'wp-smart-contracts'),
        'help' => $m->render(
          WPSC_Mustache::getTemplate('metabox-nft-help'),
          [
            'need-help' => __('Need help?', 'wp-smart-contracts'),
            'need-help-desc' => __('Deploy your NFT contract to Ethereum', 'wp-smart-contracts'),
            'deploy-ethereum' => __('How to deploy a NFT Marketplace to Ethereum', 'wp-smart-contracts'),
            'need-help-desc2' => __('Deploy your NFT contract to Binance Smart Chain', 'wp-smart-contracts'),
            'deploy-bsc' => __('How to deploy a NFT Marketplace to Binance Smart Chain', 'wp-smart-contracts'),
            'screencast' => __('Screencast', 'wp-smart-contracts'),
          ]
        )

      ]
    );


  }

}
