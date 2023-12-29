<?php

if( ! defined( 'ABSPATH' ) ) die;

/**
 * Create Metaboxes for CPT NFT
 */

new WPSC_MetaboxStaking();

class WPSC_MetaboxStaking {

  function __construct() {

    // load all custom fields
    add_action('admin_init', [$this, 'loadMetaboxes'], 2);

    // save repeatable fields
    add_action('save_post', [$this, 'saveRepeatableFields'], 10, 3);

  }

  public function loadMetaboxes() {

    $post_id = WPSC_helpers::valArrElement($_GET, "post")?sanitize_text_field($_GET["post"]):false;

    add_meta_box(
      'wpsc_nft_metabox', 
      'WPSmartContracts: Staking Specification', 
      [$this, 'wpscSmartontractSpecification'], 
      'staking', 
      'normal', 
      'default'
    );
    
    add_meta_box(
      'wpsc_smart_contract', 
      'WPSmartContracts: Smart Contract', 
      [$this, 'wpscSmartContract'], 
      'staking', 
      'normal', 
      'default'
    );
    
    add_meta_box(
      'wpsc_sidebar', 
      'WPSmartContracts: Tutorials & Tools', 
      [$this, 'wpscSidebar'], 
      'staking', 
      'side', 
      'default'
    );

    add_meta_box(
      'wpsc_code_crowd', 
      'WPSmartContracts: Source Code', 
      [$this, 'wpscSourceCode'], 
      'staking', 
      'normal', 
      'default'
    );

    add_meta_box(
      'wpsc_reminder_crowd', 
      'WPSmartContracts: Friendly Reminder', 
      [__CLASS__, 'wpscReminder'], 
      'staking', 
      'normal', 
      'default'
    );

  }

  public function saveRepeatableFields($post_id, $post, $update) {

    if ($post->post_type == "staking") {

      if ( ! isset( $_POST['wpsc_repeatable_meta_box_nonce'] ) ||
      ! wp_verify_nonce( $_POST['wpsc_repeatable_meta_box_nonce'], 'wpsc_repeatable_meta_box_nonce' ) )
          return;

      if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
          return;

      if (!current_user_can('edit_post', $post_id))
          return;

      // if the contract was not deployed yet, save the nft definitions
      self::saveNFTMetaData($post_id, $_POST);

    }

  }

  public static function saveNFTMetaData($post_id, $arr) {

    // get clean and update all inputs
    if (!WPSC_helpers::valArrElement($arr, "wpsc-readonly") || !$arr["wpsc-readonly"]) {
      $wpsc_flavor = WPSC_helpers::updatePostMeta("wpsc-flavor", "wpsc_flavor", $arr, $post_id);
    }

    error_log("saveNFTMetaData " . print_r($arr, true));
    
    $wpsc_token = WPSC_helpers::updatePostMeta("wpsc-token", "wpsc_token", $arr, $post_id);
    $wpsc_name = WPSC_helpers::updatePostMeta("wpsc-name", "wpsc_name", $arr, $post_id);
    $wpsc_symbol = WPSC_helpers::updatePostMeta("wpsc-symbol", "wpsc_symbol", $arr, $post_id);
    $wpsc_decimals = WPSC_helpers::updatePostMeta("wpsc-decimals", "wpsc_decimals", $arr, $post_id);
    $wpsc_token2 = WPSC_helpers::updatePostMeta("wpsc-token2", "wpsc_token2", $arr, $post_id);
    $wpsc_name2 = WPSC_helpers::updatePostMeta("wpsc-name2", "wpsc_name2", $arr, $post_id);
    $wpsc_symbol2 = WPSC_helpers::updatePostMeta("wpsc-symbol2", "wpsc_symbol2", $arr, $post_id);
    $wpsc_decimals2 = WPSC_helpers::updatePostMeta("wpsc-decimals2", "wpsc_decimals2", $arr, $post_id);
    $wpsc_apy = WPSC_helpers::updatePostMeta("wpsc-apy", "wpsc_apy", $arr, $post_id);
    $wpsc_mst = WPSC_helpers::updatePostMeta("wpsc-mst", "wpsc_mst", $arr, $post_id);
    $wpsc_penalty = WPSC_helpers::updatePostMeta("wpsc-penalty", "wpsc_penalty", $arr, $post_id);
    $wpsc_minimum = WPSC_helpers::updatePostMeta("wpsc-minimum", "wpsc_minimum", $arr, $post_id);
    $wpsc_limit = WPSC_helpers::updatePostMeta("wpsc-limit", "wpsc_limit", $arr, $post_id);
    $wpsc_apy2 = WPSC_helpers::updatePostMeta("wpsc-apy2", "wpsc_apy2", $arr, $post_id);
    $wpsc_ratio1 = WPSC_helpers::updatePostMeta("wpsc-ratio1", "wpsc_ratio1", $arr, $post_id);
    $wpsc_ratio2 = WPSC_helpers::updatePostMeta("wpsc-ratio2", "wpsc_ratio2", $arr, $post_id);

    if (!WPSC_helpers::valArrElement($arr, "wpsc-readonly") || !$arr["wpsc-readonly"]) {

      $wpsc_network = WPSC_Metabox::cleanUpText($arr["wpsc-network"]);
      $wpsc_txid = WPSC_Metabox::cleanUpText($arr["wpsc-txid"]);
      $wpsc_owner = WPSC_Metabox::cleanUpText($arr["wpsc-owner"]);
      $wpsc_contract_address = WPSC_Metabox::cleanUpText($arr["wpsc-contract-address"]);
      $wpsc_token_contract_address = WPSC_Metabox::cleanUpText($arr["wpsc-token-contract-address"]);
      $wpsc_factory = $arr["wpsc-factory"];
      $wpsc_encoded_parameters = WPSC_Metabox::cleanUpText($arr["wpsc-encoded-parameters"]);

      $wpsc_blockie = WPSC_Metabox::cleanUpText($arr["wpsc-blockie"]);
      $wpsc_blockie_token = WPSC_Metabox::cleanUpText($arr["wpsc-blockie-token"]);
      $wpsc_blockie_owner = WPSC_Metabox::cleanUpText($arr["wpsc-blockie-owner"]);
      $wpsc_qr_code = WPSC_Metabox::cleanUpText($arr["wpsc-qr-code"]);
      $wpsc_token_qr_code = WPSC_Metabox::cleanUpText($arr["wpsc-token-qr-code"]);

      // if set, save the contract info meta
      if ($wpsc_network) update_post_meta($post_id, 'wpsc_network', $wpsc_network);
      if ($wpsc_txid) update_post_meta($post_id, 'wpsc_txid', $wpsc_txid);
      if ($wpsc_owner) update_post_meta($post_id, 'wpsc_owner', $wpsc_owner);
      if ($wpsc_contract_address) update_post_meta($post_id, 'wpsc_contract_address', $wpsc_contract_address);
      if ($wpsc_token_contract_address) update_post_meta($post_id, 'wpsc_token_contract_address', $wpsc_token_contract_address);
      if ($wpsc_factory) update_post_meta($post_id, 'wpsc_factory', $wpsc_factory);
      if ($wpsc_encoded_parameters) update_post_meta($post_id, 'wpsc_encoded_parameters', $wpsc_encoded_parameters);
      if ($wpsc_blockie) update_post_meta($post_id, 'wpsc_blockie', $wpsc_blockie);
      if ($wpsc_blockie_token) update_post_meta($post_id, 'wpsc_blockie_token', $wpsc_blockie_token);
      if ($wpsc_blockie_owner) update_post_meta($post_id, 'wpsc_blockie_owner', $wpsc_blockie_owner);
      if ($wpsc_qr_code) update_post_meta($post_id, 'wpsc_qr_code', $wpsc_qr_code);
      if ($wpsc_token_qr_code) update_post_meta($post_id, 'wpsc_token_qr_code', $wpsc_token_qr_code);

    }

  }

  public function wpscSmartontractSpecification() {

    wp_nonce_field( 'wpsc_repeatable_meta_box_nonce', 'wpsc_repeatable_meta_box_nonce' );

    $m = new Mustache_Engine;

    $args =  self::getMetaboxStakingArgs();

    echo $m->render(
      WPSC_Mustache::getTemplate('metabox-staking'),
      $args
    );

  }

  static public function getMetaboxStakingArgs() {
    
    global $pagenow;

    $m = new Mustache_Engine;

    $id = get_the_ID();

    $wpsc_flavor = get_post_meta($id, 'wpsc_flavor', true);
    $wpsc_adv_hard = get_post_meta($id, 'wpsc_adv_hard', true);
    $wpsc_adv_cap = get_post_meta($id, 'wpsc_adv_cap', true);
    $wpsc_adv_white = get_post_meta($id, 'wpsc_adv_white', true);
    $wpsc_adv_pause = get_post_meta($id, 'wpsc_adv_pause', true);
    $wpsc_adv_timed = get_post_meta($id, 'wpsc_adv_timed', true);
    $wpsc_adv_pause_nft = get_post_meta($id, 'wpsc_adv_pause_nft', true);

    $wpsc_adv_opening = get_post_meta($id, 'wpsc_adv_opening', true);
    $wpsc_adv_closing = get_post_meta($id, 'wpsc_adv_closing', true);
    
    $wpsc_anyone_can_mint_tmp = get_post_meta($id, 'wpsc_anyone_can_mint', true);    

    if (!$wpsc_anyone_can_mint_tmp or $wpsc_anyone_can_mint_tmp=="false") {
      $wpsc_anyone_can_mint=false;
    } else {
      $wpsc_anyone_can_mint=true;
    }

    $wpsc_token = get_post_meta($id, 'wpsc_token', true);
    $wpsc_name = get_post_meta($id, 'wpsc_name', true);
    $wpsc_symbol = get_post_meta($id, 'wpsc_symbol', true);
    $wpsc_decimals = get_post_meta($id, 'wpsc_decimals', true);

    $wpsc_apy = get_post_meta($id, 'wpsc_apy', true);
    $wpsc_mst = get_post_meta($id, 'wpsc_mst', true);
    $wpsc_penalty = get_post_meta($id, 'wpsc_penalty', true);
    $wpsc_minimum = get_post_meta($id, 'wpsc_minimum', true);
    if (!$wpsc_minimum) $wpsc_minimum=0;
    $wpsc_limit = get_post_meta($id, 'wpsc_limit', true);

    // almond variables
    $wpsc_token2 = get_post_meta($id, 'wpsc_token2', true);
    $wpsc_name2 = get_post_meta($id, 'wpsc_name2', true);
    $wpsc_symbol2 = get_post_meta($id, 'wpsc_symbol2', true);
    $wpsc_decimals2 = get_post_meta($id, 'wpsc_decimals2', true);

    $wpsc_apy2 = get_post_meta($id, 'wpsc_apy2', true);
    $wpsc_ratio1 = get_post_meta($id, 'wpsc_ratio1', true);
    $wpsc_ratio2 = get_post_meta($id, 'wpsc_ratio2', true);

    if ($pagenow=="post-new.php") {
      $wpsc_show_header = "on";
      $wpsc_show_breadcrumb = "on";
      $wpsc_show_category = "on";
      $wpsc_show_id = "on";
      $wpsc_show_tags = "on";
      $wpsc_show_owners = "on";
    }

    $args = [
      'smart-contract' => __('Smart Contract', 'wp-smart-contracts'),
      'learn-more' =>  __('Learn More', 'wp-smart-contracts'),

      'crowdsale-with-tokens-desc' =>  __('A Crowdsale that allows you to sell an existing token, and also receive payments in any ERC-20 Token', 'wp-smart-contracts'),
      'custom-token' =>  __('You can sell an existing token of yours'),
      'dynamic-cap' =>  __('The maximum cap will be determined by the number of tokens you approve to sell'),
      'payments-in-token' =>  __('You can receive contributions in ERC-20 tokens'),

      'custom-token-tooltip' =>  __('The rest of the NFT contracts create the token for you. This one works with existing tokens you own.'),
      'dynamic-cap-tooltip' =>  __('You sell only the tokens you approve to the NFT'),
      'payments-in-token-tooltip' =>  __('Your NFT can sell tokens in Ether and in ERC-20 Tokens'),

      'flavor' =>  __('Flavor', 'wp-smart-contracts'),
      'nft-spec' =>  __('Staking Specification', 'wp-smart-contracts'), 
      'nft-spec-desc' =>  __('ERC-20 Stakes Smart Contract', 'wp-smart-contracts'),
      'features' =>  __('Features', 'wp-smart-contracts'),

      'nft-marketplace-auction' =>  __('ERC-20 / BEP-20 Stakes', 'wp-smart-contracts'),
      'nft-marketplace-auction-desc' =>  __('Allow your users to accrue interest', 'wp-smart-contracts'),

      'adv-stakes-title' =>  __('ERC-20 / BEP-20 Advanced Stakes', 'wp-smart-contracts'),
      'adv-stakes-title-desc' =>  __('Allow your users to stake one token and accrue interest in other', 'wp-smart-contracts'),

      "wpsc_flavor" => $wpsc_flavor,
      "wpsc_adv_hard" => $wpsc_adv_hard,
      "wpsc_adv_cap" => $wpsc_adv_cap,
      "wpsc_adv_white" => $wpsc_adv_white,
      "wpsc_adv_pause" => $wpsc_adv_pause,
      "wpsc_adv_timed" => $wpsc_adv_timed,
      "wpsc_adv_pause_nft" => $wpsc_adv_pause_nft,

      'img-custom' => dirname(plugin_dir_url( __FILE__ )) . '/assets/img/custom-card.png',
      'erc-20-custom' => __('Looking for something else?', 'wp-smart-contracts'),
      'custom-message' => __('If you need to create a smart contract with custom features we can help', 'wp-smart-contracts'),
      'contact-us' => __('Contact us', 'wp-smart-contracts'),

      "wpsc_token" => $wpsc_token,
      "wpsc_name" => $wpsc_name,
      "wpsc_symbol" => $wpsc_symbol,
      "wpsc_decimals" => $wpsc_decimals,
      "wpsc_apy" => $wpsc_apy,
      "wpsc_mst" => $wpsc_mst,
      "wpsc_penalty" => $wpsc_penalty,
      "wpsc_minimum" => $wpsc_minimum,
      "wpsc_limit" => $wpsc_limit,

      // almond variables
      "wpsc_token2" => $wpsc_token2,
      "wpsc_name2" => $wpsc_name2,
      "wpsc_symbol2" => $wpsc_symbol2,
      "wpsc_decimals2" => $wpsc_decimals2,
      "wpsc_apy2" => $wpsc_apy2,
      "wpsc_ratio1" => $wpsc_ratio1,
      "wpsc_ratio2" => $wpsc_ratio2,

      'ownable-nft' =>  __('Annual interest rate, calculated per second', 'wp-smart-contracts'),
      'ownable-nft-tooltip' => __('Define the annual interest rate', 'wp-smart-contracts'),

      'transferable-nft' =>  __('Maturity time in days', 'wp-smart-contracts'),
      'transferable-nft-tooltip' => __('Your users can claim rewards only if they remain staked for at least this number of days', 'wp-smart-contracts'),

      'nft-mintable' =>  __('Minimum deposit', 'wp-smart-contracts'),
      'nft-mintable-tooltip' => __('Stakings has to be created with this minimum amount of tokens', 'wp-smart-contracts'),

      'adv-stake-multiple-tokens' =>  __('Stake Token X and get interest in token X and/or Y', 'wp-smart-contracts'),
      'adv-stake-multiple-tokens-tooltip' => __('Dual token stake. Incentivize your users to stake one token and get interest in both or only one of the tokens', 'wp-smart-contracts'),

      'stake-options' =>  __('Smart Contracts Options', 'wp-smart-contracts'),
      'stake-options-desc' =>  __('', 'wp-smart-contracts'),
      
      'token' =>  __('Token Address', 'wp-smart-contracts'),
      'token-desc' =>  __('ERC-20 or BEP-20 token address to stake', 'wp-smart-contracts'),
      'token-desc-tooltip' =>  __('This is the token that holders can stake', 'wp-smart-contracts'),

      'apy' =>  __('Annual interest rate', 'wp-smart-contracts'),
      'apy-desc' =>  __('Annual Payment Yield', 'wp-smart-contracts'),
      'apy-desc-tooltip' =>  __('This is the annual interest rate', 'wp-smart-contracts'),
      
      'mst' =>  __('Minimum Stake Time (in days)', 'wp-smart-contracts'),
      'mst-desc' =>  __('Minimum time for the Stake to avoid penalties', 'wp-smart-contracts'),
      'mst-desc-tooltip' =>  __('If you define a penalty, then this is the minimum time the stake should be active. The time is specified in days', 'wp-smart-contracts'),
      
      'penalty' =>  __('Penalization (optional)', 'wp-smart-contracts'),
      'penalty-desc' =>  __('Percentage of penalization to charge to users that withdraw early', 'wp-smart-contracts'),
      'penalty-desc-tooltip' =>  __('If your users end the stake before the minimum stake time, this percentage will be deducted from total withdraw', 'wp-smart-contracts'),
      
      'minimum' =>  __('Minimum Stake Amount', 'wp-smart-contracts'),
      'minimum-desc' =>  __('This is the minimum number of tokens to create a Stake', 'wp-smart-contracts'),
      
      'img-grape' => dirname(plugin_dir_url( __FILE__ )) . '/assets/img/ube-card.png',
      'img-almond' => dirname(plugin_dir_url( __FILE__ )) . '/assets/img/almond-card.png',

      'warning' =>  __('Warning!', 'wp-smart-contracts'),
      'warning-1' =>  __('You must use a valid ERC-20 or BEP-20 compliant token or equivalent on the network of your choice', 'wp-smart-contracts'),
      'warning-2' =>  __('Are you using a reflection token? If so, you must exclude the stake contract in your reflection token', 'wp-smart-contracts'),
      'warning-3' =>  __('This is a one time setting, you will not be able to change this setting later', 'wp-smart-contracts'),
      'secondary-token' =>  __('Secondary Token', 'wp-smart-contracts'),
      'erc-20-or-bep-20' =>  __('ERC-20 or BEP-20 token to gain interest', 'wp-smart-contracts'),
      'apy-staking-token' =>  __('APY Staking Token', 'wp-smart-contracts'),
      'apy1-optional' =>  __('Annual interest rate for the first token (optional)', 'wp-smart-contracts'),
      'apy-staking-token-2' =>  __('APY Secondary Token', 'wp-smart-contracts'),
      'apy2' =>  __('Annual interest rate for the second token', 'wp-smart-contracts'),
      'smart-contract-update' =>  __('Smart Contract Update</div>', 'wp-smart-contracts'),
      'smart-contract-update-1' =>  __('If you agree and wish to proceed, please click "CONFIRM" transaction in your wallet, otherwise click "REJECT".</p>', 'wp-smart-contracts'),
      'smart-contract-update-2' =>  __('Please be patient. It can take several minutes. Don\'t close or reload this window.</p>', 'wp-smart-contracts'),

      'wp-admin-url' => get_admin_url()
    ];

    if ($wpsc_flavor=="almond") $args["is_almond"]=true;
    else $args["is_ube"]=true;

    $wpsc_contract_address = get_post_meta($id, 'wpsc_contract_address', true);

    // show contract definition
    if ($wpsc_contract_address) {
      $args["readonly"] = true;
    }

    return $args;

  }

  static public function getNetworkInfo($wpsc_network) {

    if ($wpsc_network and $arr = WPSC_helpers::getNetworks()) {

      return [
        $arr[$wpsc_network]["color"],
        $arr[$wpsc_network]["nftn"],
        $arr[$wpsc_network]["url2"],
        __($arr[$wpsc_network]["title"], 'wp-smart-contracts')
      ];

    }

    return ["", "", "", ""];

  }

  public function wpscSmartContract() {

    global $pagenow;

    $id = get_the_ID();

    $wpsc_network = get_post_meta($id, 'wpsc_network', true);
    $wpsc_txid = get_post_meta($id, 'wpsc_txid', true);
    $wpsc_owner = get_post_meta($id, 'wpsc_owner', true);
    $wpsc_contract_address = get_post_meta($id, 'wpsc_contract_address', true);
    $wpsc_encoded_parameters = get_post_meta($id, 'wpsc_encoded_parameters', true);
    $wpsc_token_contract_address = get_post_meta($id, 'wpsc_token_contract_address', true);
    $wpsc_blockie = get_post_meta($id, 'wpsc_blockie', true);
    $wpsc_blockie_owner = get_post_meta($id, 'wpsc_blockie_owner', true);
    $wpsc_qr_code = get_post_meta($id, 'wpsc_qr_code', true);
    $token_id = get_post_meta($id, 'token_id', true);
    $wpsc_token_qr_code = get_post_meta($id, 'wpsc_token_qr_code', true);

    list($color, $network, $etherscan, $network_val) = WPSC_Metabox::getNetworkInfo($wpsc_network);

    $m = new Mustache_Engine;

    // show contract
    if ($wpsc_contract_address) {

      $wpsc_flavor = get_post_meta($id, 'wpsc_flavor', true);

      $wpsc_token = get_post_meta($id, 'wpsc_token', true);
      $wpsc_name = get_post_meta($id, 'wpsc_name', true);
      $wpsc_symbol = get_post_meta($id, 'wpsc_symbol', true);
      $wpsc_decimals = get_post_meta($id, 'wpsc_decimals', true);
      $wpsc_apy = get_post_meta($id, 'wpsc_apy', true);
      $wpsc_mst = get_post_meta($id, 'wpsc_mst', true);
      $wpsc_penalty = get_post_meta($id, 'wpsc_penalty', true);
      $wpsc_minimum = get_post_meta($id, 'wpsc_minimum', true);
      if (!$wpsc_minimum) $wpsc_minimum = 0;

      $wpsc_token2 = get_post_meta($id, 'wpsc_token2', true);
      $wpsc_name2 = get_post_meta($id, 'wpsc_name2', true);
      $wpsc_symbol2 = get_post_meta($id, 'wpsc_symbol2', true);
      $wpsc_decimals2 = get_post_meta($id, 'wpsc_decimals2', true);
      $wpsc_apy2 = get_post_meta($id, 'wpsc_apy2', true);
      $wpsc_ratio1 = get_post_meta($id, 'wpsc_ratio1', true);
      $wpsc_ratio2 = get_post_meta($id, 'wpsc_ratio2', true);

      $stakeInfo = [
        "token_label" => __('Token', 'wp-smart-contracts'),
        "apy_label" => __('Annual Interest Rate', 'wp-smart-contracts'),
        "mst_label" => __('Maturity time', 'wp-smart-contracts'),
        "penalty_label" => __('Penalization', 'wp-smart-contracts'),
        "minimum_label" => __('Minimum value', 'wp-smart-contracts'),
        "type" => $wpsc_flavor,
        "wpsc_token" => $wpsc_token,
        "wpsc_apy" => $wpsc_apy,
        "wpsc_mst" => $wpsc_mst,
        "wpsc_penalty" => $wpsc_penalty,
        "wpsc_minimum" => $wpsc_minimum,

        "wpsc_token2" => $wpsc_token2,
        "wpsc_name2" => $wpsc_name2,
        "wpsc_symbol2" => $wpsc_symbol2,
        "wpsc_decimals2" => $wpsc_decimals2,
        "wpsc_apy2" => $wpsc_apy2,
        "wpsc_ratio1" => $wpsc_ratio1,
        "wpsc_ratio2" => $wpsc_ratio2,
      ];

      if ($wpsc_flavor=="almond") {
        $stakeInfo["is_almond"]=true;
        $stakeInfo["color"] = "almond";
      } else {
        $stakeInfo["color"] = "purple";
      }

      $stakeInfo["imgUrl"] = dirname( plugin_dir_url( __FILE__ ) ) . '/assets/img/';

      $atts = [
          'wpsc_flavor' => $wpsc_flavor,
          'smart-contract' => __('Smart Contract', 'wp-smart-contracts'),
          'learn-more' => __('Learn More', 'wp-smart-contracts'),
          'smart-contract-desc' => __('Go live with your Staking contract. You can publish your contract in a test net or in the main Ethereum network.', 'wp-smart-contracts'),
          'nft-deployed-smart-contract' => __('Stakes Smart Contract', 'wp-smart-contracts'),
          'token-deployed-smart-contract' => __('Token Smart Contract', 'wp-smart-contracts'),
          'ethereum-network' => $network_val,
          'ethereum-color' => $color,
          'contract-address' => $wpsc_contract_address,
          'wpsc_encoded_parameters' => $wpsc_encoded_parameters,
          'etherscan' => $etherscan,
          'contract-address-text' => __('Contract Address', 'wp-smart-contracts'),
          'contract-address-desc' => __('The Smart Contract Address of your nft', 'wp-smart-contracts'),
          'txid-text' => __('Transaction ID', 'wp-smart-contracts'),
          'owner-text' => __('Owner Account', 'wp-smart-contracts'),

          'blockie' => $m->render(WPSC_Mustache::getTemplate('blockies'), ['blockie' => $wpsc_blockie]),
          'blockie-owner' => $m->render(WPSC_Mustache::getTemplate('blockies'), ['blockie' => $wpsc_blockie_owner]),
          'stake-info' => $m->render(WPSC_Mustache::getTemplate('stake-info'), $stakeInfo),
          'txid' => $wpsc_txid,
          'txid-short' => WPSC_helpers::shortify($wpsc_txid),
          'owner' => $wpsc_owner,
          'owner-short' => WPSC_helpers::shortify($wpsc_owner),
          'wpsc_anyone_can_mint' => get_post_meta($id, 'wpsc_anyone_can_mint', true),
          'wpsc_commission' => get_post_meta($id, 'wpsc_commission', true),
          'wpsc_wallet' => get_post_meta($id, 'wpsc_wallet', true),
          'wpsc_name' => get_post_meta($id, 'wpsc_name', true),
          'wpsc_symbol' => get_post_meta($id, 'wpsc_symbol', true),
          'wpsc_name2' => get_post_meta($id, 'wpsc_name2', true),
          'wpsc_symbol2' => get_post_meta($id, 'wpsc_symbol2', true),
          'wpsc_show_header' => get_post_meta($id, 'wpsc_show_header', true),
          'wpsc_anyone_can_author' => get_post_meta($id, 'wpsc_anyone_can_author', true),
          'wpsc_show_breadcrumb' => get_post_meta($id, 'wpsc_show_breadcrumb', true),
          'wpsc_show_category' => get_post_meta($id, 'wpsc_show_category', true),
          'wpsc_show_id' => get_post_meta($id, 'wpsc_show_id', true),
          'wpsc_show_tags' => get_post_meta($id, 'wpsc_show_tags', true),
          'wpsc_show_owners' => get_post_meta($id, 'wpsc_show_owners', true),
          'wpsc_columns_n' => get_post_meta($id, 'wpsc_columns_n', true),
          'wpsc_font_main_color' => get_post_meta($id, 'wpsc_font_main_color', true),
          "wpsc_tag_bg_color" => get_post_meta($id, "wpsc_tag_bg_color", true),
          "wpsc_tag_color" => get_post_meta($id, "wpsc_tag_color", true),
          "wpsc_cat_bg_color" => get_post_meta($id, "wpsc_cat_bg_color", true),
          "wpsc_cat_color" => get_post_meta($id, "wpsc_cat_color", true),
          "wpsc_graph_bg_color" => get_post_meta($id, "wpsc_graph_bg_color", true),
          "wpsc_graph_line_color" => get_post_meta($id, "wpsc_graph_line_color", true),
          'wpsc_font_secondary_color' => get_post_meta($id, 'wpsc_font_secondary_color', true),
          'wpsc_background_color' => get_post_meta($id, 'wpsc_background_color', true),

          'qr-code-data' => $wpsc_qr_code,
          'fox' => plugins_url( "assets/img/metamask-fox.svg", dirname(__FILE__) ),
          'connect-to-metamask' => __('Connect to Metamask', 'wp-smart-contracts'),

      ];

      if ($wpsc_txid) {
          $atts["txid_exists"] = true;
      }

      echo $m->render(WPSC_Mustache::getTemplate('metabox-smart-contract-staking'), $atts);

    // show buttons to load or create a contract
    } else {

      echo $m->render(
          WPSC_Mustache::getTemplate('metabox-smart-contract-buttons-staking'),
          self::getSmartContractButtons()
      );

    }

  }

  static public function getSmartContractButtons($show_load=true) {

    $m = new Mustache_Engine;
    
    return [
      'smart-contract' => __('Smart Contract', 'wp-smart-contracts'),
      'learn-more' => __('Learn More', 'wp-smart-contracts'),
      'smart-contract-desc' => __('Go live with your NFT. You can publish your NFT in a test net or in the main Ethereum network.', 'wp-smart-contracts'),
      'new-smart-contract' => __('New Smart Contract', 'wp-smart-contracts'),
      'text' => __('To deploy your Smart Contracts you need to be connected to a Network. Please install and connect to Metamask.', 'wp-smart-contracts'),
      'fox' => plugins_url( "assets/img/metamask-fox.svg", dirname(__FILE__) ),
      'connect-to-metamask' => __('Connect to Metamask', 'wp-smart-contracts'),
      'deploy-with-wpst' => $m->render(
        WPSC_Mustache::getTemplate('metabox-smart-contract-buttons-wpst'),
        [
          'show_load' => $show_load,
          'deploy-desc-wpic-disabled' => __('WPIC Deployment for this flavor is deactivated until March 1, 2022.', 'wp-smart-contracts'),
          'load' => __('Load', 'wp-smart-contracts'),
          'load-desc' => __('Load an existing Smart Contract', 'wp-smart-contracts'),
          'deploy' => __('Deploy', 'wp-smart-contracts'),
          'deploy-desc' => __('Deploy your Smart Contract to the Blockchain using Ether', 'wp-smart-contracts'),
          'deploy-desc-token' => __('Deploy your Smart Contract to the Blockchain using WPIC is a two step process:', 'wp-smart-contracts'),
          'deploy-desc-token-1' => __('First you need to authorize the factory to use the WPIC funds', 'wp-smart-contracts'),
          'deploy-desc-token-2' => __('Then you can deploy your contract using WPIC', 'wp-smart-contracts'),
          'no-wpst' => __('No WPIC found', 'wp-smart-contracts'),
          'not-enough-wpst' => __('Not enough WPIC found', 'wp-smart-contracts'),
          'authorize' => __('Authorize', 'wp-smart-contracts'),
          'authorize-complete' => __('Authorization was successful, click "Deploy" to proceed', 'wp-smart-contracts'),
          'deploy-token' => __('Deploy using WP Ice Cream (WPIC)', 'wp-smart-contracts'),
          'deploy-token-image' => dirname( plugin_dir_url( __FILE__ ) ) . '/assets/img/wp-smart-token.png',
          'deploy-using-ether' => __('Deploy to the selected network', 'wp-smart-contracts'),
          'switch-networks' => __('Ethereum fees too expensive?', 'wp-smart-contracts'),
          'switch-explain' => __('Deploy your contracts to Layer 2 Solutions', 'wp-smart-contracts'),
          'switch-explain-2' => __('Deploy your contracts with lower fees', 'wp-smart-contracts'),
          'switch-explain-3' => __('You can use different blockchains, like Binance Smart Chain, xDai or Polygon (Matic)', 'wp-smart-contracts'),
          'switch-explain-4' => __('Choose the network below and click on Switch button', 'wp-smart-contracts'),
          'learn-how-to-get-wpst' => __('Learn how to get WPIC', 'wp-smart-contracts'),
          'learn-how-to-get-ether' => __('Learn how to get Ether', 'wp-smart-contracts'),
          'learn-how-to-get-coins' => __('Learn how to get coins for other blockchains', 'wp-smart-contracts'),
          'do-you-have-an-erc20-address' => __('Do you already have a Stake contract address?', 'wp-smart-contracts'),
          'wpst-balance' => __('WPIC Balance', 'wp-smart-contracts'),
          'button-id' => "wpsc-deploy-contract-button-stake",
          'authorize-button-id' => "wpsc-deploy-contract-button-wpst-authorize-stake",
          'deploy-button-wpst' => "wpsc-deploy-contract-button-wpst-deploy-stake",

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
      ),
      'ethereum-address' => __('Ethereum Network Contract Address', 'wp-smart-contracts'),
      'ethereum-address-desc' => __('Please fill out the contract address you want to import', 'wp-smart-contracts'),
      'ethereum-address-important' => __('Important', 'wp-smart-contracts'),
      'ethereum-address-important-message' => __('Keep in mind that the contract is going to be loaded using the current network and current account as owner', 'wp-smart-contracts'),
      'active-net-account' => __('Currently active Ethereum Network and account:', 'wp-smart-contracts'),
      'smart-contract-address' => __('Smart Contract Address'),
      'load' => __('Load', 'wp-smart-contracts'),
      'ethereum-deploy' => __('Network Deploy', 'wp-smart-contracts'),
      'ethereum-deploy-desc' => __('Are you ready to deploy your NFT to the currently active Ethereum Network?', 'wp-smart-contracts'),
      'cancel' => __('Cancel', 'wp-smart-contracts'),
      'yes-proceed' => __('Yes, please proceed', 'wp-smart-contracts'),
      'deployed-smart-contract' => __('Deployed Smart Contract', 'wp-smart-contracts'),
    ];

  }

  public function wpscSourceCode() {

      // load the contract technical atts
      $atts = WPSC_Metabox::wpscGetMetaSourceCodeAtts();

      if (!empty($atts)) {

        $m = new Mustache_Engine;
        echo $m->render(
          WPSC_Mustache::getTemplate('metabox-source-code'),
          $atts
        );

      }

  }

  // with great powers... 
  public static function wpscReminder() {
    echo WPSC_Metabox::wpscReminder();
  }

  public function wpscSidebar() {

    $m = new Mustache_Engine;
    echo $m->render(
      WPSC_Mustache::getTemplate('metabox-sidebar-staking'),
      [
        'white-logo' => dirname( plugin_dir_url( __FILE__ ) ) . '/assets/img/wpsc-white-logo.png',
        'tutorials-tools' => __('Tutorials', 'wp-smart-contracts'),
        'tutorials-tools-desc' => __('Here you can find a few tutorials that might be useful to deploy, test and use your Smart Contracts.', 'wp-smart-contracts'),
        'screencasts' => __('Screencasts'),
        'deploy' => __('Staking Documentation'),
        'wpic_info' => WPSC_helpers::renderWPICInfo(),
        'learn-more' => __('Learn More', 'wp-smart-contracts'),
        'docs' => __('Documentation', 'wp-smart-contracts'),
        'doc' => "https://wpsmartcontracts.com/doc-tools-ube.php",
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

      ]
    );

  }

}
