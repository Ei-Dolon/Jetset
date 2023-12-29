<?php

if( ! defined( 'ABSPATH' ) ) die;

/**
 * Create Metaboxes for CPT NFT
 */

new WPSC_MetaboxNFTCollection();

class WPSC_MetaboxNFTCollection {

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
      'WPSmartContracts: NFT Specification', 
      [$this, 'wpscSmartContractSpecification'], 
      'nft-collection', 
      'normal', 
      'default'
    );
    
    add_meta_box(
      'wpsc_smart_contract', 
      'WPSmartContracts: Smart Contract', 
      [$this, 'wpscSmartContract'], 
      'nft-collection', 
      'normal', 
      'default'
    );
    
    add_meta_box(
      'wpsc_sidebar', 
      'WPSmartContracts: Tutorials & Tools', 
      [$this, 'wpscSidebar'], 
      'nft-collection', 
      'side', 
      'default'
    );

    add_meta_box(
      'wpsc_code_crowd', 
      'WPSmartContracts: Source Code', 
      [$this, 'wpscSourceCode'], 
      'nft-collection', 
      'normal', 
      'default'
    );

    add_meta_box(
      'wpsc_reminder_crowd', 
      'WPSmartContracts: Friendly Reminder', 
      [__CLASS__, 'wpscReminder'], 
      'nft-collection', 
      'normal', 
      'default'
    );

  }

  public function saveRepeatableFields($post_id, $post, $update) {

    if ($post->post_type == "nft-collection") {

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
      $wpsc_symbol = WPSC_helpers::updatePostMeta("wpsc-symbol", "wpsc_symbol", $arr, $post_id);      
      $wpsc_name = WPSC_helpers::updatePostMeta("wpsc-name", "wpsc_name", $arr, $post_id);    
    }

    $wpsc_sub_collections = WPSC_helpers::updatePostMeta("wpsc-sub-collections", "wpsc_sub_collections", $arr, $post_id);    
    $wpsc_anyone_can_mint = WPSC_helpers::updatePostMeta("wpsc-anyone-can-mint", "wpsc_anyone_can_mint", $arr, $post_id);
    $wpsc_anyone_can_author = WPSC_helpers::updatePostMeta("wpsc-anyone-can-author", "wpsc_anyone_can_author", $arr, $post_id);
    $wpsc_pixelated_images = WPSC_helpers::updatePostMeta("wpsc-pixelated-images", "wpsc_pixelated_images", $arr, $post_id);
    $wpsc_list_on_opensea = WPSC_helpers::updatePostMeta("wpsc-list-on-opensea", "wpsc_list_on_opensea", $arr, $post_id);
    $wpsc_commission = WPSC_helpers::updatePostMeta("wpsc-commission", "wpsc_commission", $arr, $post_id);
    $wpsc_royalties = WPSC_helpers::updatePostMeta("wpsc-royalties", "wpsc_royalties", $arr, $post_id);
    $wpsc_wallet = WPSC_helpers::updatePostMeta("wpsc-wallet", 'wpsc_wallet', $arr, $post_id);
    $wpsc_token = WPSC_helpers::updatePostMeta("wpsc-token", 'wpsc_token', $arr, $post_id);
    $wpsc_show_header = WPSC_helpers::updatePostMeta("wpsc-show-header", 'wpsc_show_header', $arr, $post_id);    
    $wpsc_show_breadcrumb = WPSC_helpers::updatePostMeta("wpsc-show-breadcrumb", 'wpsc_show_breadcrumb', $arr, $post_id);    
    $wpsc_show_category = WPSC_helpers::updatePostMeta("wpsc-show-category", 'wpsc_show_category', $arr, $post_id);    
    $wpsc_show_id = WPSC_helpers::updatePostMeta("wpsc-show-id", 'wpsc_show_id', $arr, $post_id);    
    $wpsc_show_tags = WPSC_helpers::updatePostMeta("wpsc-show-tags", 'wpsc_show_tags', $arr, $post_id);    
    $wpsc_show_owners = WPSC_helpers::updatePostMeta("wpsc-show-owners", 'wpsc_show_owners', $arr, $post_id);    
    $wpsc_columns_n = WPSC_helpers::updatePostMeta("wpsc-columns-n", 'wpsc_columns_n', $arr, $post_id);    
    $wpsc_font_main_color = WPSC_helpers::updatePostMeta("wpsc-font-main-color", 'wpsc_font_main_color', $arr, $post_id);
    $wpsc_tag_bg_color = WPSC_helpers::updatePostMeta("wpsc-tag-bg-color", 'wpsc_tag_bg_color', $arr, $post_id);    
    $wpsc_tag_color = WPSC_helpers::updatePostMeta("wpsc-tag-color", 'wpsc_tag_color', $arr, $post_id);    
    $wpsc_cat_bg_color = WPSC_helpers::updatePostMeta("wpsc-cat-bg-color", 'wpsc_cat_bg_color', $arr, $post_id);    
    $wpsc_cat_color = WPSC_helpers::updatePostMeta("wpsc-cat-color", 'wpsc_cat_color', $arr, $post_id);    
    $wpsc_graph_bg_color = WPSC_helpers::updatePostMeta("wpsc-graph-bg-color", 'wpsc_graph_bg_color', $arr, $post_id);    
    $wpsc_graph_line_color = WPSC_helpers::updatePostMeta("wpsc-graph-line-color", 'wpsc_graph_line_color', $arr, $post_id);    
    $wpsc_font_secondary_color = WPSC_helpers::updatePostMeta("wpsc-font-secondary-color", 'wpsc_font_secondary_color', $arr, $post_id);    
    $wpsc_background_color = WPSC_helpers::updatePostMeta("wpsc-background-color", 'wpsc_background_color', $arr, $post_id);

    if (!WPSC_helpers::valArrElement($arr, "wpsc-readonly") || !$arr["wpsc-readonly"]) {

      $wpsc_network = WPSC_Metabox::cleanUpText($arr["wpsc-network"]);
      $wpsc_txid = WPSC_Metabox::cleanUpText($arr["wpsc-txid"]);
      $wpsc_owner = WPSC_Metabox::cleanUpText($arr["wpsc-owner"]);
      $wpsc_contract_address = WPSC_Metabox::cleanUpText($arr["wpsc-contract-address"]);
      if (WPSC_helpers::valArrElement($arr, "wpsc-token-contract-address")) {
        $wpsc_token_contract_address = WPSC_Metabox::cleanUpText($arr["wpsc-token-contract-address"]);
      } else {
        $wpsc_token_contract_address = null;
      }
      $wpsc_factory = $arr["wpsc-factory"];
      $wpsc_encoded_parameters = $_POST["wpsc-encoded-parameters"];

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

  public function wpscSmartContractSpecification() {

    wp_nonce_field( 'wpsc_repeatable_meta_box_nonce', 'wpsc_repeatable_meta_box_nonce' );

    $m = new Mustache_Engine;

    $args =  self::getMetaboxNFTArgs();

    echo $m->render(
      WPSC_Mustache::getTemplate('metabox-nft-collection'),
      $args
    );

  }

  static public function getMetaboxNFTArgs() {
    
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

    $wpsc_who_can_mint_0 = false;
    $wpsc_who_can_mint_1 = false;
    $wpsc_who_can_mint_2 = false;
    
    switch ($wpsc_anyone_can_mint_tmp) {
      case '2':
        $wpsc_who_can_mint_2 = true;
        break;      
      case '1':
        $wpsc_who_can_mint_1 = true;
        break;      
      default:
        $wpsc_who_can_mint_0 = true;
        break;
    }

    $wpsc_sub_collections = get_post_meta($id, 'wpsc_sub_collections', true);	
    $wpsc_commission = get_post_meta($id, 'wpsc_commission', true);
    $wpsc_royalties = get_post_meta($id, 'wpsc_royalties', true);
    $wpsc_wallet = get_post_meta($id, 'wpsc_wallet', true);
    $wpsc_token = get_post_meta($id, 'wpsc_token', true);
    $wpsc_name = get_post_meta($id, 'wpsc_name', true);    
    $wpsc_symbol = get_post_meta($id, 'wpsc_symbol', true);    
    $wpsc_show_header = get_post_meta($id, 'wpsc_show_header', true);
    $wpsc_anyone_can_author = get_post_meta($id, 'wpsc_anyone_can_author', true);
    $wpsc_pixelated_images = get_post_meta($id, 'wpsc_pixelated_images', true);
    $wpsc_list_on_opensea = get_post_meta($id, 'wpsc_list_on_opensea', true);
    $wpsc_show_breadcrumb = get_post_meta($id, 'wpsc_show_breadcrumb', true);    
    $wpsc_show_category = get_post_meta($id, 'wpsc_show_category', true);    
    $wpsc_show_id = get_post_meta($id, 'wpsc_show_id', true);    
    $wpsc_show_tags = get_post_meta($id, 'wpsc_show_tags', true);    
    $wpsc_show_owners = get_post_meta($id, 'wpsc_show_owners', true);    
    $wpsc_columns_n = get_post_meta($id, 'wpsc_columns_n', true);
    $wpsc_font_main_color = get_post_meta($id, 'wpsc_font_main_color', true);    
    $wpsc_font_secondary_color = get_post_meta($id, 'wpsc_font_secondary_color', true);    
    $wpsc_background_color = get_post_meta($id, 'wpsc_background_color', true);
    $wpsc_tag_bg_color = get_post_meta($id, 'wpsc_tag_bg_color', true);
    $wpsc_tag_color = get_post_meta($id, 'wpsc_tag_color', true);
    $wpsc_cat_bg_color = get_post_meta($id, 'wpsc_cat_bg_color', true);
    $wpsc_cat_color = get_post_meta($id, 'wpsc_cat_color', true);
    $wpsc_graph_bg_color = get_post_meta($id, 'wpsc_graph_bg_color', true);
    $wpsc_graph_line_color = get_post_meta($id, 'wpsc_graph_line_color', true);
    
    if ($pagenow=="post-new.php") {
      $wpsc_show_header = "on";
      $wpsc_show_breadcrumb = "on";
      $wpsc_show_category = "on";
      $wpsc_show_id = "on";
      $wpsc_show_tags = "on";
      $wpsc_show_owners = "on";
    }

    if (!$wpsc_font_main_color) {
      $wpsc_font_main_color="#000000";
    }
    if (!$wpsc_font_secondary_color) {
      $wpsc_font_secondary_color="#666666";
    }
    if (!$wpsc_background_color) {
      $wpsc_background_color="#FFFFFF";
    }

    if (!$wpsc_tag_bg_color) {
      $wpsc_tag_bg_color = "#cccccc";
    }
    if (!$wpsc_tag_color) {
      $wpsc_tag_color = "#655e5e";
    }
    if (!$wpsc_cat_bg_color) {
      $wpsc_cat_bg_color = "#00b5ad";
    }
    if (!$wpsc_cat_color) {
      $wpsc_cat_color = "#ffffff";
    }
    if (!$wpsc_graph_bg_color) {
      $wpsc_graph_bg_color = "#b3fef7";
    }
    if (!$wpsc_graph_line_color) {
      $wpsc_graph_line_color = "#07c2b2";
    }

    $args = [
      'smart-contract' => __('Smart Contract', 'wp-smart-contracts'),
      'learn-more' =>  __('Learn More', 'wp-smart-contracts'),
      'nft-standard' =>  __('Standard ERC-721 NFT Collection', 'wp-smart-contracts'),
      'nft-standard-desc' =>  __('A simple ERC-721 Standard Token. You can create and transfer collectibles.', 'wp-smart-contracts'),

      'nft-marketplace' =>  __('ERC-721 NFT Marketplace', 'wp-smart-contracts'),
      'nft-marketplace-desc' =>  __('A marketplace to buy & sell ERC-721 NFT.', 'wp-smart-contracts'),
      'crowdsale-with-tokens' =>  __('NFT with payments in Tokens', 'wp-smart-contracts'),
      'crowdsale-with-tokens-desc' =>  __('A Crowdsale that allows you to sell an existing token, and also receive payments in any ERC-20 Token', 'wp-smart-contracts'),
      'custom-token' =>  __('You can sell an existing token of yours'),
      'dynamic-cap' =>  __('The maximum cap will be determined by the number of tokens you approve to sell'),
      'payments-in-token' =>  __('You can receive contributions in ERC-20 tokens'),

      'custom-token-tooltip' =>  __('The rest of the NFT contracts create the token for you. This one works with existing tokens you own.'),
      'dynamic-cap-tooltip' =>  __('You sell only the tokens you approve to the NFT'),
      'payments-in-token-tooltip' =>  __('Your NFT can sell tokens in Ether and in ERC-20 Tokens'),

      'flavor' =>  __('Flavor', 'wp-smart-contracts'),
      'nft-spec' =>  __('NFT Specification', 'wp-smart-contracts'), 
      'nft-spec-desc' =>  __('Non Fungible Tokens Smart Contract including a Marketplace to Buy&Sell and Auction system.', 'wp-smart-contracts'),
      'features' =>  __('Features', 'wp-smart-contracts'),

      'nft-marketplace-auction' =>  __('ERC-721 NFT Marketplace', 'wp-smart-contracts'),
      'nft-marketplace-auction-desc' =>  __('Fully featured ERC-721 NFT Marketplace', 'wp-smart-contracts'),

      'nft-marketplace-token' =>  __('ERC-721 NFT Token Marketplace with Royalties', 'wp-smart-contracts'),
      'nft-marketplace-token-desc' =>  __('Fully featured ERC-721 NFT Marketplace with auction, selling and royalties in ERC-20 / BEP20 Token.', 'wp-smart-contracts'),

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

      "wpsc_who_can_mint_0" => $wpsc_who_can_mint_0,
      "wpsc_who_can_mint_1" => $wpsc_who_can_mint_1,
      "wpsc_who_can_mint_2" => $wpsc_who_can_mint_2,
      "wpsc_sub_collections" => $wpsc_sub_collections,
      "wpsc_commission" => $wpsc_commission,
      "wpsc_royalties" => $wpsc_royalties,
      "wpsc_wallet" => $wpsc_wallet,
      "wpsc_token" => $wpsc_token,
      "wpsc_name" => $wpsc_name,
      "wpsc_symbol" => $wpsc_symbol,
      "wpsc_show_header" => $wpsc_show_header,
      "wpsc_anyone_can_author" => $wpsc_anyone_can_author,
      "wpsc_pixelated_images" => $wpsc_pixelated_images,
      "wpsc_list_on_opensea" => $wpsc_list_on_opensea,
      "wpsc_show_breadcrumb" => $wpsc_show_breadcrumb,
      "wpsc_show_category" => $wpsc_show_category,
      "wpsc_show_id" => $wpsc_show_id,
      "wpsc_show_tags" => $wpsc_show_tags,
      "wpsc_show_owners" => $wpsc_show_owners,
      "wpsc_columns_n" => $wpsc_columns_n,
      "wpsc_font_main_color" => $wpsc_font_main_color,
      "wpsc_tag_bg_color" => $wpsc_tag_bg_color,
      "wpsc_tag_color" => $wpsc_tag_color,
      "wpsc_cat_bg_color" => $wpsc_cat_bg_color,
      "wpsc_cat_color" => $wpsc_cat_color,
      "wpsc_graph_bg_color" => $wpsc_graph_bg_color,
      "wpsc_graph_line_color" => $wpsc_graph_line_color,
      "wpsc_font_secondary_color" => $wpsc_font_secondary_color,
      "wpsc_background_color" => $wpsc_background_color,

      "wpsc-skin-0" => plugins_url( "assets/img/skin0.png", dirname(__FILE__) ),
      "wpsc-skin-1" => plugins_url( "assets/img/skin1.png", dirname(__FILE__) ),
      "wpsc-skin-2" => plugins_url( "assets/img/skin1.png", dirname(__FILE__) ),
      "wpsc-skin-3" => plugins_url( "assets/img/skin1.png", dirname(__FILE__) ),

      'erc721-nft' =>  __('ERC-721 NFT Implementation', 'wp-smart-contracts'),
      'erc721-nft-tooltip' => __('This is the ERC-721 non-fungible token original implementation with single items', 'wp-smart-contracts'),

      'nft-1155-standard' =>  __('ERC-1155 NFT Multi Token Standard', 'wp-smart-contracts'),
      'nft-1155-standard-desc' =>  __('A standard interface for contracts that manage multiple token types.', 'wp-smart-contracts'),

      'nft-1155-advanced2' =>  __('ERC-1155 NFT Advanced Token Marketplace', 'wp-smart-contracts'),
      'nft-1155-advanced2-desc' =>  __('Our most advanced NFT Marketplace, featuring an ERC-1155 NFT Multitoken Marketplace with multiple quantities, selling, royalties and lazy minting with payments in ERC-20 / BEP20 Token.', 'wp-smart-contracts'),

      'nft-1155-advanced' =>  __('ERC-1155 NFT Advanced Marketplace', 'wp-smart-contracts'),
      'nft-1155-advanced-desc' =>  __('A cutting edge NFT Marketplace, featuring an ERC-1155 NFT Multitoken Marketplace with multiple quantities, selling, royalties and lazy minting with payments native coin.', 'wp-smart-contracts'),

      'nft-1155-staking' =>  __('Lazy Minting Feature', 'wp-smart-contracts'),
      'nft-1155-staking-desc' =>  __('Lazy Minting Support', 'wp-smart-contracts'),

      'nft-1155-muti-token' =>  __('Multiple Tokens Support', 'wp-smart-contracts'),
      'nft-1155-muti-token-desc' =>  __('A single deployed contract may include any combination of fungible tokens and/or non-fungible tokens', 'wp-smart-contracts'),

      'erc1155-nft' =>  __('ERC-1155 Multi Token NFT Implementation', 'wp-smart-contracts'),
      'erc1155-nft-tooltip' => __('This is the ERC-1155 Multi Token NFT implementation with items with multiple quantities', 'wp-smart-contracts'),

      'erc1155-nft-batch' =>  __('Batch Minting Support', 'wp-smart-contracts'),
      'erc1155-nft-batch-tooltip' => __('This contract has the ability to create multiple items in one transaction', 'wp-smart-contracts'),

      'ownable-nft' =>  __('Individual owners can hold unique items', 'wp-smart-contracts'),
      'ownable-nft-tooltip' => __('Non Fungible Tokens (NFT) are ownable by only one user', 'wp-smart-contracts'),

      'ownable-nft-1155' =>  __('Multiple owners can have one or multiple items', 'wp-smart-contracts'),
      'ownable-nft-1155-tooltip' => __('Non Fungible Tokens (NFT) are ownable by multiple users', 'wp-smart-contracts'),

      'transferable-nft' =>  __('Owners can transfer NFTs to any account', 'wp-smart-contracts'),
      'transferable-nft-tooltip' => __('Accounts owning an item can transfer them to any other account', 'wp-smart-contracts'),

      'nft-mintable' =>  __('Authorized accounts can create (mint) new items', 'wp-smart-contracts'),
      'nft-mintable-tooltip' => __('Depending on the setting only contract owners or anyone can mint new NFT items', 'wp-smart-contracts'),

      'nft-metadata' =>  __('Metadata support for name, symbol and attributes', 'wp-smart-contracts'),
      'nft-metadata-tooltip' => __('The metadata extension includes name, symbol and a TokenURI with all the attributes of the NFT', 'wp-smart-contracts'),

      'nft-enumerable' =>  __('Enumerable support. Your NFTs are discoverable', 'wp-smart-contracts'),
      'nft-enumerable-tooltip' => __('This allows your contract to publish its full list of NFTs', 'wp-smart-contracts'),

      'nft-media' =>  __('Image and video support for NFT', 'wp-smart-contracts'),
      'nft-media-tooltip' => __('You can add images or videos as NFTs', 'wp-smart-contracts'),

      'nft-buy-sell' =>  __('Buy and Sell support', 'wp-smart-contracts'),
      'nft-buy-sell-tooltip' => __('Owners of NFT can sell their tokens in the Marketplace, and any interested user can buy', 'wp-smart-contracts'),

      'nft-burn' =>  __('Burn support', 'wp-smart-contracts'),
      'nft-burn-tooltip' => __('Owners of NFT can burn their NFT items', 'wp-smart-contracts'),
      
      'nft-auction' =>  __('Auctions supported', 'wp-smart-contracts'),
      'nft-auction-tooltip' => __('Owners of NFT can auction their NFTs in the Marketplace, and any interested user can buy', 'wp-smart-contracts'),

      'nft-buy-native' =>  __('Sales are done in Ether or Blockchain native coin', 'wp-smart-contracts'),
      'nft-buy-native-tooltip' => __('Ether, BNB, xDai and Matic is supported for corresponding Blockchains', 'wp-smart-contracts'),

      'nft-buy-token' =>  __('Sales are done in any ERC-20 or BEP20 Standard token defined', 'wp-smart-contracts'),
      'nft-buy-token-tooltip' => __('The payments are done with a predefined token defined by the smart contract creator. Only one token is allowed.', 'wp-smart-contracts'),

      'nft-buy-native' =>  __('Sales are done in the native coin of the chain', 'wp-smart-contracts'),
      'nft-buy-native-tooltip' => __('Payments are done in Ether, BNB, Matic or the native coin of the correspondent chain', 'wp-smart-contracts'),

      'nft-royalties' =>  __('Supports the ability to distribute royalties to the creators from resales', 'wp-smart-contracts'),
      'nft-royalties-tooltip' => __('A predefined percentage goes to creators on every sale', 'wp-smart-contracts'),

      'nft-vanities' =>  __('Support attributes and categories', 'wp-smart-contracts'),
      'nft-vanities-tooltip' => __('Include tags and categories', 'wp-smart-contracts'),

      'marketplace-options' =>  __('Smart Contracts Options', 'wp-smart-contracts'),
      'marketplace-options-desc' =>  __('', 'wp-smart-contracts'),
      
      'pixelate-images-nft' => __('Use pixelated images', 'wp-smart-contracts'),
      'pixelate-images-nft-tooltip' =>  $m->render(WPSC_Mustache::getTemplate('tooltip'), ['tip' => __("Set this to use a pixelated image in the image gallery", 'wp-smart-contracts')]),
      'pixelate-images-nft-desc' => __('By default, each browser will render images using aliasing to a scaled image in order to prevent distortion. Check this if you want the image to preserve its original pixelated form.', 'wp-smart-contracts'),
      
      'list-on-opensea' => __('Show OpenSea link', 'wp-smart-contracts'),
      'list-on-opensea-desc' => __('If your contract is deployed to Ethereum mainnet or Polygon mainnet, an auto-generated link to OpenSea will be displayed in the item view', 'wp-smart-contracts'),

      'name' =>  __('Name', 'wp-smart-contracts'),
      'name-desc' =>  __('The name of the collection', 'wp-smart-contracts'),
      'name-desc-tooltip' =>  $m->render(WPSC_Mustache::getTemplate('tooltip'), ['tip' => __("Just like ERC-20 has symbol and names, ERC-721 tokens has a symbol and name as well", 'wp-smart-contracts')]),

      'symbol' =>  __('Symbol', 'wp-smart-contracts'),
      'symbol-desc' =>  __('The symbol of the collection. Keep it short - e.g. "HIX"', 'wp-smart-contracts'),
      'symbol-desc-tooltip' =>  $m->render(WPSC_Mustache::getTemplate('tooltip'), ['tip' => __("Just like ERC-20 has symbol and names, ERC-721 tokens has a symbol and name as well", 'wp-smart-contracts')]),

      'anyone-can-mint' => __('Who can mint?', 'wp-smart-contracts'),
      'anyone-can-mint-desc' => __('This is a Smart Contract setting. Minting permissions', 'wp-smart-contracts'),
      'anyone-can-mint-tooltip' => $m->render(WPSC_Mustache::getTemplate('tooltip'), ['tip' => __('"Only minter can mint" is the most restrictive. "Only original creator" allows anyone to mint, but once minted only creator can mint its own items. And "Anyone can arbitrarily mint" is the most open option (not recommended)', 'wp-smart-contracts')]),
      'anyone-can-mint-warning' => __('Please be aware that this is a one-time setting on Yuzu and Mochi.', 'wp-smart-contracts'),

      'sub-collections' => __('Enable Sub-Collections?', 'wp-smart-contracts'),	
      'sub-collections-desc' => __('Do you want to handle multiple collections with one Smart Contract?', 'wp-smart-contracts'),	
      'sub-collections-tooltip' => $m->render(WPSC_Mustache::getTemplate('tooltip'), ['tip' => __("Enable this option to allow your users to handle multiple collections in one smart contract.", 'wp-smart-contracts')]),	
      'sub-collections-option-1' => __('Disabled', 'wp-smart-contracts'),	
      'sub-collections-option-2' => __('Enabled - show galleries as Tabs', 'wp-smart-contracts'),	
      'sub-collections-option-3' => __('Enabled - show galleries as Dropdown', 'wp-smart-contracts'),

      'royalties' => __('Royalty percentage for creators', 'wp-smart-contracts'),
      'royalties-desc' => __('Percentage royalty, ranging from 0 to 100', 'wp-smart-contracts'),
      'royalties-tooltip' => $m->render(WPSC_Mustache::getTemplate('tooltip'), ['tip' => __("Royalties to the creators from resales. 0 means no commission, 100 means 100% of the sale as commission.", 'wp-smart-contracts')]),

      'commission' => __('Sales commissions', 'wp-smart-contracts'),
      'commission-desc' => __('Percentage commission, ranging from 0 to 100', 'wp-smart-contracts'),
      'commission-tooltip' => $m->render(WPSC_Mustache::getTemplate('tooltip'), ['tip' => __("Commission that you are going to get from Sales. 0 means no commission, 100 means 100% of the sale as commission.", 'wp-smart-contracts')]),

      'wallet' =>  __('Wallet', 'wp-smart-contracts'),
      'wallet-desc' =>  __('Ethereum address or EVM compatible wallet address to receive funds', 'wp-smart-contracts'),
      'wallet-desc-tooltip' =>  __('The beneficiary account that will receive the Marketplace commissions in Ether, BNB, xDai or Matic', 'wp-smart-contracts'),

      'token' =>  __('Token for payments', 'wp-smart-contracts'),
      'token-desc' =>  __('Standard ERC-20 or BEP20 token to be used for payment of sales', 'wp-smart-contracts'),
      'token-desc2' =>  __('The <strong>Token for payments</strong> has to be the 42-character hexadecimal address (like 0x0123456789abcde0123456789abcde0123456789).', 'wp-smart-contracts'),
      'token-desc3' =>  __('For more information please click here', 'wp-smart-contracts'),
      'token-desc3-link' =>  __('https://wpsmartcontracts.com/docs/doc-nft-collection-suika.php#tokens', 'wp-smart-contracts'),
      'token-desc-tooltip' =>  __('Token used for all payments, commissions and royalties', 'wp-smart-contracts'),

      'nft-options' =>  __('NFT Options', 'wp-smart-contracts'),
      'nft-options-desc' =>  __('What type of media do you want to include in your collectible?', 'wp-smart-contracts'),

      'graph-bg-color' => __('Graph background color', 'wp-smart-contracts'),
      'graph-bg-color-desc' => __('Graph background color for the price history of NFT ', 'wp-smart-contracts'),
      'graph-bg-color-tooltip' => $m->render(WPSC_Mustache::getTemplate('tooltip'), ['tip' => 
        "<p>".__('Graph background color ', 'wp-smart-contracts')."</p>"."<img src=\"".dirname(plugin_dir_url( __FILE__ )) . '/assets/img/nft-color-8.jpg' ."\" class=\"nft-help-img\">"
      ]),

      'graph-line-color' => __('Graph line color', 'wp-smart-contracts'),
      'graph-line-color-desc' => __('Graph line color for the price history of NFT ', 'wp-smart-contracts'),
      'graph-line-color-tooltip' => $m->render(WPSC_Mustache::getTemplate('tooltip'), ['tip' => 
        "<p>".__('Graph line color ', 'wp-smart-contracts')."</p>"."<img src=\"".dirname(plugin_dir_url( __FILE__ )) . '/assets/img/nft-color-9.jpg' ."\" class=\"nft-help-img\">"
      ]),

      'need-more-help' => __("Need more help?", 'wp-smart-contracts'),
      'warning' => __("Warning!", 'wp-smart-contracts'),
      'reflection-token-warning' => __("Are you using a reflection token? If so, you must exclude the NFT contract in your reflection token", 'wp-smart-contracts'),

      'only-minter' => __("Only those who have the privilege can create NFTs", "wp-smart-contracts"),
      'only-original' => __("Only NFT creators can create NFTs.", "wp-smart-contracts"),
      'anyone-can' => __("Anyone can create new NFTs", "wp-smart-contracts"),
      'could-not-create-table' => __('Could not create table in the database', 'wp-smart-contracts'),
      'please-check-your-database' => __('Please check that your database user has "CREATE TABLE" permissions', 'wp-smart-contracts'),

      'smart-contract-update' => __('Smart Contract Update', 'wp-smart-contracts'),
      'smart-contract-agree' => __('If you agree and wish to proceed, please click "CONFIRM" transaction in your wallet, otherwise click "REJECT".', 'wp-smart-contracts'),
      'smart-contract-patient' => __('Please be patient. It can take several minutes. Don\'t close or reload this window.', 'wp-smart-contracts'),

      'nft-sections-help' => dirname(plugin_dir_url( __FILE__ )) . '/assets/img/nft-sections.png',

      'img-matcha' => dirname(plugin_dir_url( __FILE__ )) . '/assets/img/matcha-card.png',
      'img-mochi' => dirname(plugin_dir_url( __FILE__ )) . '/assets/img/mochi-card.png',
      'img-yuzu' => dirname(plugin_dir_url( __FILE__ )) . '/assets/img/yuzu-card.png',
      'img-ikasumi' => dirname(plugin_dir_url( __FILE__ )) . '/assets/img/ikasumi-card.png',
      'img-azuki' => dirname(plugin_dir_url( __FILE__ )) . '/assets/img/azuki-card.png',
      'img-suika' => dirname(plugin_dir_url( __FILE__ )) . '/assets/img/suika-card.png',

      'wp-admin-url' => get_admin_url(),

    ];

    if (($wpsc_flavor == "ikasumi" or $wpsc_flavor == "azuki") and WPSC_assets::getContractVersion($id) == "1.0") {
      $args["remove-original-creator-option"] = true;
    }

    if (WPSC_NFTGallery::db_check()) {	
      $args['galleries-table-exists'] = true;	
    } else {	
      $args['galleries-table-name'] = WPSC_NFTGallery::db_table_name();	
    }	
    if (!$wpsc_sub_collections) $args["wpsc_sub_collections_1"] = true;	
    if ($wpsc_sub_collections=="1") $args["wpsc_sub_collections_2"] = true;	
    if ($wpsc_sub_collections=="2") $args["wpsc_sub_collections_3"] = true;

    if ($wpsc_columns_n==1) $args["wpsc_columns_n_1"] = true;
    if ($wpsc_columns_n==2) $args["wpsc_columns_n_2"] = true;
    if ($wpsc_columns_n==3 or !$wpsc_columns_n) $args["wpsc_columns_n_3"] = true;
    if ($wpsc_columns_n==4) $args["wpsc_columns_n_4"] = true;

    if ($wpsc_flavor=="mochi") $args["is-mochi"] = true;
    if ($wpsc_flavor=="matcha") $args["is-matcha"] = true;
    if ($wpsc_flavor=="suika") $args["is-suika"] = true;
    if ($wpsc_flavor=="yuzu") $args["is-yuzu"] = true;
    if ($wpsc_flavor=="ikasumi") $args["is-ikasumi"] = true;
    if ($wpsc_flavor=="azuki") $args["is-azuki"] = true;

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
    $wpsc_token_contract_address = get_post_meta($id, 'wpsc_token_contract_address', true);
    $wpsc_encoded_parameters = get_post_meta($id, 'wpsc_encoded_parameters', true);
    $wpsc_blockie = get_post_meta($id, 'wpsc_blockie', true);
    $wpsc_blockie_owner = get_post_meta($id, 'wpsc_blockie_owner', true);
    $wpsc_qr_code = get_post_meta($id, 'wpsc_qr_code', true);
    $token_id = get_post_meta($id, 'token_id', true);
    $wpsc_token_qr_code = get_post_meta($id, 'wpsc_token_qr_code', true);

    list($color, $nftn, $etherscan, $network_val) = WPSC_Metabox::getNetworkInfo($wpsc_network);

    $m = new Mustache_Engine;

    // show contract
    if ($wpsc_contract_address) {

      $wpsc_flavor          = get_post_meta($id, 'wpsc_flavor', true);
      $wpsc_symbol          = get_post_meta($id, 'wpsc_symbol', true);
      $wpsc_name            = get_post_meta($id, 'wpsc_name', true);
      $wpsc_commission      = get_post_meta($id, 'wpsc_commission', true);
      $wpsc_royalties       = get_post_meta($id, 'wpsc_royalties', true);
      $wpsc_wallet          = get_post_meta($id, 'wpsc_wallet', true);
      $wpsc_token           = get_post_meta($id, 'wpsc_token', true);
      $wpsc_anyone_can_mint_temp = get_post_meta($id, 'wpsc_anyone_can_mint', true);

      $wpsc_who_can_mint_0 = false;
      $wpsc_who_can_mint_1 = false;
      $wpsc_who_can_mint_2 = false;
      
      switch ($wpsc_anyone_can_mint_temp) {
        case '2':
          $wpsc_who_can_mint_2 = true;
          break;      
        case '1':
          $wpsc_who_can_mint_1 = true;
          break;      
        default:
          $wpsc_who_can_mint_0 = true;
          break;
      }

      if ($wpsc_flavor=="mochi") $the_color = "purple";
      else if ($wpsc_flavor=="matcha") $the_color = "green";
      else if ($wpsc_flavor=="suika") $the_color = "red";
      else if ($wpsc_flavor=="yuzu") $the_color = "yellow";
      else if ($wpsc_flavor=="ikasumi") $the_color = "black";
      else if ($wpsc_flavor=="azuki") $the_color = "brown";

      $nftInfo = [
          "type" => $wpsc_flavor,
          "wpsc_symbol" => $wpsc_symbol,
          "wpsc_name" => $wpsc_name,
          "wpsc_commission" => $wpsc_commission,
          "wpsc_royalties" => $wpsc_royalties,
          "wpsc_wallet" => $wpsc_wallet,
          "wpsc_token" => $wpsc_token,
          "wpsc_who_can_mint_0" => $wpsc_who_can_mint_0,
          "wpsc_who_can_mint_1" => $wpsc_who_can_mint_1,
          "wpsc_who_can_mint_2" => $wpsc_who_can_mint_2,
          "symbol_label" => __("Symbol", "wp-smart-contracts"),
          "name_label" => __("Name", "wp-smart-contracts"),
          "commission_label" => __("Commission", "wp-smart-contracts"),
          "royalties_label" => __("Royalty", "wp-smart-contracts"),
          "anyone_label" => __("Anyone can mint", "wp-smart-contracts"),
          "wallet_label" => __("Wallet", "wp-smart-contracts"),
          "token_label" => __("Token", "wp-smart-contracts"),
          "color" => $the_color
      ];

      $nftInfo["imgUrl"] = dirname( plugin_dir_url( __FILE__ ) ) . '/assets/img/';

      $atts = [
          'smart-contract' => __('Smart Contract', 'wp-smart-contracts'),
          'learn-more' => __('Learn More', 'wp-smart-contracts'),
          'smart-contract-desc' => __('Go live with your NFT. You can publish your NFT in any available network.', 'wp-smart-contracts'),
          'nft-deployed-smart-contract' => __('NFT Smart Contract', 'wp-smart-contracts'),
          'token-deployed-smart-contract' => __('Token Smart Contract', 'wp-smart-contracts'),
          'ethereum-network' => $network_val,
          'ethereum-color' => $color,
          'ethereum-nftn' => $nftn,
          'contract-address' => $wpsc_contract_address,
          'wpsc_encoded_parameters' => $wpsc_encoded_parameters,
          'etherscan' => $etherscan,
          'contract-address-text' => __('Contract Address', 'wp-smart-contracts'),
          'contract-address-desc' => __('The Smart Contract Address of your nft', 'wp-smart-contracts'),
          'txid-text' => __('Transaction ID', 'wp-smart-contracts'),
          'owner-text' => __('Owner Account', 'wp-smart-contracts'),

          'blockie' => $m->render(WPSC_Mustache::getTemplate('blockies'), ['blockie' => $wpsc_blockie]),
          'blockie-token' => isset($wpsc_blockie_token) ? $m->render(WPSC_Mustache::getTemplate('blockies'), ['blockie' => $wpsc_blockie_token]): "",
          'blockie-owner' => $m->render(WPSC_Mustache::getTemplate('blockies'), ['blockie' => $wpsc_blockie_owner]),
          'nft-info-nft' => $m->render(WPSC_Mustache::getTemplate('nft-info'), $nftInfo),
          'txid' => $wpsc_txid,
          'txid-short' => WPSC_helpers::shortify($wpsc_txid),
          'owner' => $wpsc_owner,
          'owner-short' => WPSC_helpers::shortify($wpsc_owner),

          "wpsc_who_can_mint_0" => $wpsc_who_can_mint_0,
          "wpsc_who_can_mint_1" => $wpsc_who_can_mint_1,
          "wpsc_who_can_mint_2" => $wpsc_who_can_mint_2,

          'wpsc_commission' => get_post_meta($id, 'wpsc_commission', true),
          'wpsc_royalties' => get_post_meta($id, 'wpsc_royalties', true),
          'wpsc_wallet' => get_post_meta($id, 'wpsc_wallet', true),
          'wpsc_token' => get_post_meta($id, 'wpsc_token', true),
          'wpsc_name' => get_post_meta($id, 'wpsc_name', true),
          'wpsc_symbol' => get_post_meta($id, 'wpsc_symbol', true),
          'wpsc_show_header' => get_post_meta($id, 'wpsc_show_header', true),
          'wpsc_anyone_can_author' => get_post_meta($id, 'wpsc_anyone_can_author', true),
          'wpsc_pixelated_images' => get_post_meta($id, 'wpsc_pixelated_images', true),
          'wpsc_list_on_opensea' => get_post_meta($id, 'wpsc_list_on_opensea', true),
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

      if ($wpsc_flavor=="mochi") $atts["is-mochi"] = true;
      if ($wpsc_flavor=="matcha") $atts["is-matcha"] = true;
      if ($wpsc_flavor=="suika") $atts["is-suika"] = true;
      if ($wpsc_flavor=="yuzu") $atts["is-yuzu"] = true;
      if ($wpsc_flavor=="azuki") $atts["is-azuki"] = true;
      if ($wpsc_flavor=="ikasumi") $atts["is-ikasumi"] = true;

      echo $m->render(WPSC_Mustache::getTemplate('metabox-smart-contract-nft-collection'), $atts);

    // show buttons to load or create a contract
    } else {

      echo $m->render(
          WPSC_Mustache::getTemplate('metabox-smart-contract-buttons-nft-collection'),
          self::getSmartContractButtons()
      );

    }

  }

  static public function getSmartContractButtons($show_load=true) {

    $m = new Mustache_Engine;

    return [
      'smart-contract' => __('Smart Contract', 'wp-smart-contracts'),
      'learn-more' => __('Learn More', 'wp-smart-contracts'),
      'smart-contract-desc' => __('Go live with your NFT. You can publish your NFT in any available network', 'wp-smart-contracts'),
      'new-smart-contract' => __('New Smart Contract', 'wp-smart-contracts'),
      'text' => __('To deploy your Smart Contracts you need to be connected to a Network. Please install and connect to Metamask.', 'wp-smart-contracts'),
      'fox' => plugins_url( "assets/img/metamask-fox.svg", dirname(__FILE__) ),
      'connect-to-metamask' => __('Connect to Metamask', 'wp-smart-contracts'),
      'deploy-with-wpst' => $m->render(
        WPSC_Mustache::getTemplate('metabox-smart-contract-buttons-wpst'),
        [
          'show_load' => $show_load,
          'load' => __('Load', 'wp-smart-contracts'),
          'load-desc' => __('Load an existing Smart Contract', 'wp-smart-contracts'),
          'deploy' => __('Deploy', 'wp-smart-contracts'),
          'deploy-desc' => __('Deploy your Smart Contract to the Blockchain using Ether', 'wp-smart-contracts'),
          'deploy-desc-wpic-disabled' => __('WPIC Deployment for this flavor is deactivated until March 1, 2022.', 'wp-smart-contracts'),
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
          'do-you-have-an-erc20-address' => __('Do you already have an NFT contract address?', 'wp-smart-contracts'),
          'wpst-balance' => __('WPIC Balance', 'wp-smart-contracts'),
          'button-id' => "wpsc-deploy-contract-button-nft",
          'authorize-button-id' => "wpsc-deploy-contract-button-wpst-authorize-nft",
          'deploy-button-wpst' => "wpsc-deploy-contract-button-wpst-deploy-nft",
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
      WPSC_Mustache::getTemplate('metabox-sidebar-nft'),
      [
        'white-logo' => dirname( plugin_dir_url( __FILE__ ) ) . '/assets/img/wpsc-white-logo.png',
        'tutorials-tools' => __('Tutorials', 'wp-smart-contracts'),
        'tutorials-tools-desc' => __('Here you can find a few tutorials that might be useful to deploy, test and use your Smart Contracts.', 'wp-smart-contracts'),
        'screencasts' => __('Screencasts'),
        'deploy' => __('NFT Marketplace Documentation'),
        'wpic_info' => WPSC_helpers::renderWPICInfo(),
        'learn-more' => __('Learn More', 'wp-smart-contracts'),
        'docs' => __('Documentation', 'wp-smart-contracts'),
        'doc' => "https://wpsmartcontracts.com/doc-nft.php",
        'wpsc-logo' => dirname( plugin_dir_url( __FILE__ )) . '/assets/img/wpsc-logo.png',
        'choose-network' => $m->render(WPSC_Mustache::getTemplate('choose-network'), [
          "switch" => __('Switch', 'wp-smart-contracts'),
          "choose-network" => __('Choose network', 'wp-smart-contracts')
        ]),
        'documentation' => __('Documentation', 'wp-smart-contracts'),
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
