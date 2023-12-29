<?php

if( ! defined( 'ABSPATH' ) ) die;

/**
 * Create Metaboxes for CPT NFT
 */

new WPSC_MetaboxNFT();

class WPSC_MetaboxNFT {

  function __construct() {

    // load all custom fields
    add_action('admin_init', [$this, 'loadMetaboxes'], 2);

    // save repeatable fields
    add_action('save_post', [$this, 'saveRepeatableFields'], 10, 3);

  }

  public function loadMetaboxes() {

    // check if we need to load specifications of the contract

    $load_spec = true;

    $post_id = WPSC_helpers::valArrElement($_GET, "post")?sanitize_text_field($_GET["post"]):false;

    if (is_numeric($post_id) and get_post_meta($post_id, 'wpsc_contract_address', true)) {
      $load_spec = false;
    }

    if ($load_spec) {

      add_meta_box(
        'wpsc_nft_metabox', 
        'WPSmartContracts: NFT Item', 
        [$this, 'wpscSmartContractSpecification'], 
        'nft', 
        'normal', 
        'default'
      );

      add_meta_box(
        'wpsc_smart_contract', 
        'WPSmartContracts: Smart Contract', 
        [$this, 'wpscSmartContract'], 
        'nft', 
        'normal', 
        'default'
      );

      add_meta_box(
        'wpsc_sidebar', 
        'WPSmartContracts: Tutorials & Tools', 
        [$this, 'wpscSidebar'], 
        'nft', 
        'side', 
        'default'
      );  

    }
    
  }

  public function wpscSidebar() {

    $m = new Mustache_Engine;
    echo $m->render(
      WPSC_Mustache::getTemplate('metabox-sidebar-nft-item'),
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
        'switch-networks' => __('Ethereum fees too expensive?', 'wp-smart-contracts'),
        'learn-how-to-get-wpst' => __('Learn how to get WPIC', 'wp-smart-contracts'),
        'learn-how-to-get-ether' => __('Learn how to get Ether', 'wp-smart-contracts'),
        'learn-how-to-get-coins' => __('Learn how to get coins for other blockchains', 'wp-smart-contracts'),
        'switch-explain' => __('Deploy your contracts to Layer 2 Solutions', 'wp-smart-contracts'),
        'switch-explain-2' => __('Deploy your contracts with lower fees', 'wp-smart-contracts'),
        'switch-explain-3' => __('You can use different blockchains, like Binance Smart Chain, xDai or Polygon (Matic)', 'wp-smart-contracts'),
        'switch-explain-4' => __('Choose the network below and click on Switch button', 'wp-smart-contracts'),
        'documentation' => __('Documentation', 'wp-smart-contracts'),
        'nft-marketplace' => __('NFT Marketplace', 'wp-smart-contracts'),
        'nft-items' => __('NFT Items', 'wp-smart-contracts'),
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


  public function saveRepeatableFields($post_id, $post, $update) {

    if ($post->post_type == "nft") {

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

    // $wpsc_background_color = WPSC_helpers::updatePostMeta("wpsc-background-color", 'wpsc_background_color', $arr, $post_id);

    $wpsc_media_type = WPSC_helpers::updatePostMeta("wpsc-media-type", "wpsc_media_type", $arr, $post_id);
    $wpsc_nft_media_json = $arr["wpsc-media-json"];

    if (is_null(json_decode($arr["wpsc-media-json"]))) {
      $arr["wpsc-media-json"]='';
    }

    update_post_meta($post_id, 'wpsc_nft_media_json', $wpsc_nft_media_json);

    $wpsc_nft_voucher_price_human = WPSC_helpers::updatePostMeta("wpsc-nft-voucher-price-human", "wpsc_nft_voucher_price_human", $arr, $post_id);
    $wpsc_nft_voucher_sign = WPSC_helpers::updatePostMeta("wpsc-nft-voucher-sign", "wpsc_nft_voucher_sign", $arr, $post_id);
    $wpsc_nft_voucher_salt = WPSC_helpers::updatePostMeta("wpsc-nft-voucher-salt", "wpsc_nft_voucher_salt", $arr, $post_id);
    $wpsc_nft_voucher_id = WPSC_helpers::updatePostMeta("wpsc-nft-voucher-id", "wpsc_nft_voucher_id", $arr, $post_id);
    $wpsc_nft_voucher_price = WPSC_helpers::updatePostMeta("wpsc-nft-voucher-price", "wpsc_nft_voucher_price", $arr, $post_id);
    $wpsc_nft_voucher_qty = WPSC_helpers::updatePostMeta("wpsc-nft-voucher-qty", "wpsc_nft_voucher_qty", $arr, $post_id);
    $wpsc_nft_voucher_author = WPSC_helpers::updatePostMeta("wpsc-nft-voucher-author", "wpsc_nft_voucher_author", $arr, $post_id);

    if (!WPSC_helpers::valArrElement($arr, "wpsc-readonly") || !$arr["wpsc-readonly"]) {

      $wpsc_collection_contract = WPSC_helpers::updatePostMeta("wpsc-collection-contract", "wpsc_collection_contract", $arr, $post_id);
      $wpsc_nft_owner = WPSC_helpers::updatePostMeta("wpsc-nft-owner", "wpsc_nft_owner", $arr, $post_id);
      $wpsc_nft_supply = WPSC_helpers::updatePostMeta("wpsc-nft-supply", "wpsc_nft_supply", $arr, $post_id);
      $wpsc_item_collection = WPSC_helpers::updatePostMeta("wpsc-item-collection", "wpsc_item_collection", $arr, $post_id);
      $wpsc_network = WPSC_helpers::updatePostMeta("wpsc-network", "wpsc_network", $arr, $post_id);
      $wpsc_txid = WPSC_helpers::updatePostMeta("wpsc-txid", "wpsc_txid", $arr, $post_id);
      $wpsc_creator = WPSC_helpers::updatePostMeta("wpsc-creator", "wpsc_creator", $arr, $post_id);
      $wpsc_creator_blockie = WPSC_helpers::updatePostMeta("wpsc-creator-blockie", "wpsc_creator_blockie", $arr, $post_id);
      $wpsc_nft_id = WPSC_helpers::updatePostMeta("wpsc-nft-id", "wpsc_nft_id", $arr, $post_id);
      $wpsc_nft_id_blockie = WPSC_helpers::updatePostMeta("wpsc-nft-id-blockie", "wpsc_nft_id_blockie", $arr, $post_id);
      $wpsc_nft_url = WPSC_helpers::updatePostMeta("wpsc-nft-url", "wpsc_nft_url", $arr, $post_id);

      $user = wp_get_current_user();
      $nickname = get_the_author_meta("nickname", $user->ID);
      update_post_meta($post_id, "original_author", $nickname);
      update_post_meta($post_id, "original_author_id", $user->ID);

    }

  }

  public function wpscSmartContractSpecification() {

    wp_nonce_field( 'wpsc_repeatable_meta_box_nonce', 'wpsc_repeatable_meta_box_nonce' );

    $m = new Mustache_Engine;

    $args =  self::getMetaboxNFTArgs();

    echo $m->render(
      WPSC_Mustache::getTemplate('metabox-nft'),
      $args
    );

  }

  static public function getMetaboxNFTArgs() {
    
    $m = new Mustache_Engine;

    $id = get_the_ID();

    $wpsc_media_type = get_post_meta($id, 'wpsc_media_type', true);
    $wpsc_collection_contract = get_post_meta($id, 'wpsc_collection_contract', true);
    $wpsc_nft_owner = get_post_meta($id, 'wpsc_nft_owner', true);
    $wpsc_nft_supply = get_post_meta($id, 'wpsc_nft_supply', true);
    $wpsc_nft_voucher_price = get_post_meta($id, 'wpsc_nft_voucher_price', true);
    $wpsc_nft_voucher_price_human = get_post_meta($id, 'wpsc_nft_voucher_price_human', true);
    $wpsc_nft_voucher_qty = get_post_meta($id, 'wpsc_nft_voucher_qty', true);
    $wpsc_nft_voucher_author = get_post_meta($id, 'wpsc_nft_voucher_author', true);
    $wpsc_nft_voucher_sign = get_post_meta($id, 'wpsc_nft_voucher_sign', true);
    $wpsc_nft_voucher_salt = get_post_meta($id, 'wpsc_nft_voucher_salt', true);
    $wpsc_nft_voucher_id = get_post_meta($id, 'wpsc_nft_voucher_id', true);
    $wpsc_nft_media_json = get_post_meta($id, 'wpsc_nft_media_json', true);
    $wpsc_item_collection = get_post_meta($id, 'wpsc_item_collection', true);

    $wpsc_flavor = get_post_meta($wpsc_item_collection, 'wpsc_flavor', true);
    
    $wpsc_network = get_post_meta($id, 'wpsc_network', true);
    $wpsc_txid = get_post_meta($id, 'wpsc_txid', true);
    $wpsc_creator = get_post_meta($id, 'wpsc_creator', true);
    $wpsc_creator_blockie = get_post_meta($id, 'wpsc_creator_blockie', true);
    $wpsc_nft_id = get_post_meta($id, 'wpsc_nft_id', true);
    $wpsc_nft_id_blockie = get_post_meta($id, 'wpsc_nft_id_blockie', true);
    $wpsc_nft_url = get_post_meta($id, 'wpsc_nft_url', true);

    $args = [
      'smart-contract' => __('Smart Contract', 'wp-smart-contracts'),
      'learn-more' =>  __('Learn More', 'wp-smart-contracts'),
      'nft-standard' =>  __('Standard ERC-721 NFT Collection', 'wp-smart-contracts'),
      'nft-standard-desc' =>  __('A simple ERC-721 Standard Token. You can create and transfer collectibles.', 'wp-smart-contracts'),
      'creature-png' => dirname( plugin_dir_url( __FILE__ ) ) . '/assets/img/creature.png',
      'nft-media-desc-1' => __('IPFS Support', 'wp-smart-contracts'),
      'nft-media-desc-2' => __('Store your assets on a decentralized and unstoppable network', 'wp-smart-contracts'),
      'nft-media-desc-3' => __('An image is a unique file like a piece of art, a picture, profile, etc. Formats are .png, .jpg or .gif', 'wp-smart-contracts'),
      'wpsc_nft_media_json' => $wpsc_nft_media_json,
      'wpsc_network' => $wpsc_network,
      'wpsc_txid' => $wpsc_txid,
      'wpsc_creator' => $wpsc_creator,
      'wpsc_creator_blockie' => $wpsc_creator_blockie,
      'wpsc_nft_id' => $wpsc_nft_id,
      'wpsc_nft_id_blockie' => $wpsc_nft_id_blockie,
      'wpsc_nft_url' => $wpsc_nft_url,
      'wpsc_nft_supply' => $wpsc_nft_supply,
      'wpsc_nft_voucher_price' => $wpsc_nft_voucher_price,
      'wpsc_nft_voucher_price_human' => $wpsc_nft_voucher_price_human,
      'wpsc_nft_voucher_qty' => $wpsc_nft_voucher_qty,
      'wpsc_nft_voucher_author' => $wpsc_nft_voucher_author,
      'wpsc_nft_voucher_sign' => $wpsc_nft_voucher_sign,
      'wpsc_nft_voucher_salt' => $wpsc_nft_voucher_salt,
      'wpsc_nft_voucher_id' => $wpsc_nft_voucher_id,
      'wpsc_collection_contract' => $wpsc_collection_contract
    ];

    if ($wpsc_flavor == "ikasumi" || $wpsc_flavor == "azuki") {
      $args["is-lazy-minted"] = true;
      $args["wpsc_flavor"] = $wpsc_flavor;
      $args["wpsc_item_collection"] = $wpsc_item_collection;
    }

    switch ($wpsc_media_type) {
      case 'image':
        $args["wpsc_media_type_image"]=true;
        break;
      case 'video':
        $args["wpsc_media_type_video"]=true;
        break;
      case 'audio':
        $args["wpsc_media_type_audio"]=true;
        break;
      case 'document':
        $args["wpsc_media_type_document"]=true;
        break;
      case '3dmodel':
        $args["wpsc_media_type_3dmodel"]=true;
        break;
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

    $id = get_the_ID();

    $wpsc_network = get_post_meta($id, 'wpsc_network', true);
    $wpsc_txid = get_post_meta($id, 'wpsc_txid', true);
    $wpsc_collection_contract = get_post_meta($id, 'wpsc_collection_contract', true);
    $wpsc_creator = get_post_meta($id, 'wpsc_creator', true);
    $wpsc_creator_blockie = get_post_meta($id, 'wpsc_creator_blockie', true);
    $wpsc_nft_id = get_post_meta($id, 'wpsc_nft_id', true);
    $wpsc_nft_id_blockie = get_post_meta($id, 'wpsc_nft_id_blockie', true);
    $wpsc_nft_url = get_post_meta($id, 'wpsc_nft_url', true);
    $wpsc_nft_supply = get_post_meta($id, 'wpsc_nft_supply', true);
    $wpsc_nft_voucher_price = get_post_meta($id, 'wpsc_nft_voucher_price', true);
    $wpsc_nft_voucher_price_human = get_post_meta($id, 'wpsc_nft_voucher_price_human', true);
    $wpsc_nft_voucher_qty = get_post_meta($id, 'wpsc_nft_voucher_qty', true);
    $wpsc_nft_voucher_author = get_post_meta($id, 'wpsc_nft_voucher_author', true);
    $wpsc_nft_voucher_sign = get_post_meta($id, 'wpsc_nft_voucher_sign', true);
    $wpsc_nft_voucher_salt = get_post_meta($id, 'wpsc_nft_voucher_salt', true);
    $wpsc_nft_voucher_id = get_post_meta($id, 'wpsc_nft_voucher_id', true);

    $wpsc_blockie = get_post_meta($id, 'wpsc_blockie', true);
    $wpsc_blockie_owner = get_post_meta($id, 'wpsc_blockie_owner', true);
    $wpsc_item_collection = get_post_meta($id, 'wpsc_item_collection', true);

    list($color, $nftn, $etherscan, $network_val) = WPSC_Metabox::getNetworkInfo($wpsc_network);

    $m = new Mustache_Engine;

    // show contract
    if ($wpsc_nft_id) {

      $wpsc_media_type = get_post_meta($id, 'wpsc_media_type', true);
      $wpsc_nft_media_json = get_post_meta($id, 'wpsc_nft_media_json', true);

      list($color, $icon, $etherscan, $network_val) = WPSC_Metabox::getNetworkInfo($wpsc_network);

      $endpoint = get_rest_url(null, 'wpsc/v1/nft/'.$id);

      if ($id) {
        $response = wp_remote_get($endpoint);
        if ( is_array( $response ) && ! is_wp_error( $response ) ) {
          $body = $response['body']; // use the content
        }
        $json_prettified = json_encode(json_decode($body), JSON_PRETTY_PRINT);
      }

      $coltitle = get_the_title($wpsc_item_collection);

      $atts = [

        'smart-contract' => __('Mint your Item', 'wp-smart-contracts'),
        'learn-more' => __('Learn More', 'wp-smart-contracts'),
        'smart-contract-desc' => __('Mint your NFT in the selected Collection', 'wp-smart-contracts'),
        'token-id' => __('Item ID', 'wp-smart-contracts'),
        'token-data' => __('Item Data', 'wp-smart-contracts'),
        'item-creator' => __('Item Creator', 'wp-smart-contracts'),
        'minted-supply' => __('Original Minted Supply', 'wp-smart-contracts'),
        'token-uri-endpoint' => __('Item URI endpoint', 'wp-smart-contracts'),
        'collection' => __('Collection', 'wp-smart-contracts'),
        'collection-link' => get_edit_post_link($wpsc_item_collection),
        'collection-name' => $coltitle?$coltitle:"Untitled Collection",
        'collection-icon' => plugin_dir_url( dirname( __FILE__ ) ) . 'assets/img/icon-nft-collection-dark.png',

        "wpsc_media_type" => $wpsc_media_type,
        "wpsc_collection_contract" => $wpsc_collection_contract,
        "wpsc_collection_contract_short" => WPSC_helpers::shortify($wpsc_collection_contract),
        "wpsc_creator-short" => WPSC_helpers::shortify($wpsc_creator),
        "owner-text" => __('Creator', 'wp-smart-contracts'),
        "wpsc_nft_media_json" => $wpsc_nft_media_json,
        "wpsc_creator" => $wpsc_creator,
        "wpsc_creator_blockie" => $wpsc_creator_blockie,
        "wpsc_nft_id" => $wpsc_nft_id,
        "wpsc_nft_id_blockie" => $wpsc_nft_id_blockie,
        "wpsc_nft_url" => $wpsc_nft_url,
        "wpsc_nft_supply" => $wpsc_nft_supply,
        "wpsc_nft_voucher_price" => $wpsc_nft_voucher_price,
        "wpsc_nft_voucher_price_human" => $wpsc_nft_voucher_price_human,
        "wpsc_nft_voucher_qty" => $wpsc_nft_voucher_qty,
        "wpsc_nft_voucher_author" => $wpsc_nft_voucher_author,
        "wpsc_nft_voucher_sign" => $wpsc_nft_voucher_sign,
        "wpsc_nft_voucher_salt" => $wpsc_nft_voucher_salt,
        "wpsc_nft_voucher_id" => $wpsc_nft_voucher_id,
        "wpsc_blockie" => $wpsc_blockie,
        "wpsc_blockie_owner" => $wpsc_blockie_owner,
        "wpsc_txid" => $wpsc_txid,
        "txid-short" => WPSC_helpers::shortify($wpsc_txid),
        "txid-text" => __("Transaction ID", "wp-smart-contracts"),

        "endpoint" => $endpoint,
        "endpoint-json" => $json_prettified,
        "endpoint-label" => __("Endpoint URL", "wp-smart-contracts"),
        "endpoint-view" => __("View endpoint", "wp-smart-contracts"),

        "color" => $color,
        "icon" => $icon,
        "etherscan" => $etherscan,
        "network_val" => $network_val,
        
      ];

      echo $m->render(WPSC_Mustache::getTemplate('metabox-smart-contract-nft'), $atts);

    // show buttons to load or create a contract
    } else {

      echo $m->render(
        WPSC_Mustache::getTemplate('metabox-smart-contract-buttons-nft'),
        self::getSmartContractButtons()
      );

    }

    echo $m->render(
      WPSC_Mustache::getTemplate('metabox-smart-contract-buttons-nft-vouchers'),
      array_merge(
        self::getSmartContractButtons(),
        [
          'unit-price' => __("Unit Price", "wp-smart-contracts"),
          'unit-price-desc' => __("The price per each unit of the item", "wp-smart-contracts"),
          'unit-price-desc-tooltip' => $m->render(WPSC_Mustache::getTemplate('tooltip'), ['tip' => __("The price is specified in the form of payment of the selected collection", 'wp-smart-contracts')]),
          'qty' => __("Maximum quantity", "wp-smart-contracts"),
          'qty-desc' => __("The maximum units to mint for this item", "wp-smart-contracts"),
          'qty-desc-tooltip' => $m->render(WPSC_Mustache::getTemplate('tooltip'), ['tip' => __("Any user can mint one or more units, up to this limit", 'wp-smart-contracts')]),  
          'author' => __("Author address", "wp-smart-contracts"),
          'author-desc' => __("Address to receive royalties for sales and auctions", "wp-smart-contracts"),
          'author-desc-tooltip' => $m->render(WPSC_Mustache::getTemplate('tooltip'), ['tip' => __("Your smart contract has royalties? this is the address of the creator", 'wp-smart-contracts')]),
          'lazy-minting-is-supported' => __("Lazy minting is supported only on Ikasumi and Azuki flavors", "wp-smart-contracts"),
          'sign' => __("Sign", "wp-smart-contracts"),
          'sign-lazy-minting' => __("Sign a Lazy Minting Voucher", "wp-smart-contracts"),
          'lazy-minting' => __("Lazy Minting", "wp-smart-contracts"),
          'learn-more' => __("Learn More", "wp-smart-contracts"),
          'sign-a-voucher' => __("Sign a voucher for Lazy Minting. Signatures doesn't require gas fee.", "wp-smart-contracts"),
        ]
      )
    );

  }

  static public function getSmartContractButtons() {

    $m = new Mustache_Engine;
    
    $id = get_the_ID();

    $supply = get_post_meta($id, 'wpsc_nft_supply', true);
    if (!$supply) $supply = 1;

    return [
      'wpsc_nft_owner' => get_post_meta($id, 'wpsc_nft_owner', true),
      'wpsc_nft_supply' => $supply,
      'wpsc_nft_voucher_price' => get_post_meta($id, 'wpsc_nft_voucher_price', true),
      'wpsc_nft_voucher_price_human' => get_post_meta($id, 'wpsc_nft_voucher_price_human', true),
      'wpsc_nft_voucher_qty' => get_post_meta($id, 'wpsc_nft_voucher_qty', true),
      'wpsc_nft_voucher_author' => get_post_meta($id, 'wpsc_nft_voucher_author', true),
      'wpsc_nft_voucher_sign' => get_post_meta($id, 'wpsc_nft_voucher_sign', true),
      'wpsc_nft_voucher_salt' => get_post_meta($id, 'wpsc_nft_voucher_salt', true),
      'wpsc_nft_voucher_id' => get_post_meta($id, 'wpsc_nft_voucher_id', true),
      'collection-smart-contract' => __("Collection (Smart Contract)", "wp-smart-contracts"),
      'select-collection' => __("Select the collection to which your item belongs", "wp-smart-contracts"),

      'supply' => __("Supply", "wp-smart-contracts"),
      'supply-desc' => __("The total amount of this item", "wp-smart-contracts"),
      'supply-desc-tooltip' => $m->render(WPSC_Mustache::getTemplate('tooltip'), ['tip' => __("The total quantity of items for this NFT item", 'wp-smart-contracts')]),

      'choose-collection' => __("Choose a collection", "wp-smart-contracts"),
      'recipient' => __("Recipient", "wp-smart-contracts"),
      'recipient-tooltip' =>  $m->render(WPSC_Mustache::getTemplate('tooltip'), ['tip' => __("This will be the owner of the item created", 'wp-smart-contracts')]),
      'wpsc-nft-endpoint' => get_rest_url(null, 'wpsc/v1/nft/'),
      'wpsc-nft-endpoint1155' => get_rest_url(null, 'wpsc/v1/nft1155/'),
      'wpsc-nft-post-id' => $id,
      'select-owner' => __("Owner address", "wp-smart-contracts"),
      'choose-collection-tooltip' =>  $m->render(WPSC_Mustache::getTemplate('tooltip'), ['tip' => __("This is the smart conytract were your item will be minted", 'wp-smart-contracts')]),

      'collections' => WPSC_Queries::nftCollections(get_post_meta($id, 'wpsc_item_collection', true), true),
      'smart-contract' => __('Mint your Item', 'wp-smart-contracts'),
      'learn-more' => __('Learn More', 'wp-smart-contracts'),
      'text' => __('To mint your NFT Item you need to be connected to a Network. Please connect to Metamask and choose the right network to continue.', 'wp-smart-contracts'),
      'smart-contract-desc' => __('Mint your NFT on the selected Collection', 'wp-smart-contracts'),
      'fox' => plugins_url( "assets/img/metamask-fox.svg", dirname(__FILE__) ),
      'connect-to-metamask' => __('Connect to Metamask', 'wp-smart-contracts'),
      'deploy' => __('Mint', 'wp-smart-contracts'),
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
      'deploy-using-ether' => __('Deploy using Ether', 'wp-smart-contracts'),
      'switch-networks' => __('Ethereum fees too expensive?', 'wp-smart-contracts'),
      'switch-explain' => __('Deploy your contracts to Layer 2 Solutions', 'wp-smart-contracts'),
      'switch-explain-2' => __('Deploy your contracts with lower fees', 'wp-smart-contracts'),
      'switch-explain-3' => __('You can use different blockchains, like Binance Smart Chain, xDai or Polygon (Matic)', 'wp-smart-contracts'),
      'switch-explain-4' => __('Choose the network below and click on Switch button', 'wp-smart-contracts'),
      'learn-how-to-get-wpst' => __('Learn how to get WPIC', 'wp-smart-contracts'),
      'learn-how-to-get-coins' => __('Learn how to get coins for other blockchains', 'wp-smart-contracts'),
      'do-you-have-an-erc20-address' => __('Do you already have an ERC20 token address?', 'wp-smart-contracts'),
      'wpst-balance' => __('WPIC Balance', 'wp-smart-contracts'),
      'button-id' => "wpsc-deploy-contract-button-nft",
      'authorize-button-id' => "wpsc-deploy-contract-button-wpst-authorize-nft",
      'deploy-button-wpst' => "wpsc-deploy-contract-button-wpst-deploy-nft",
      
      'ethereum-address' => __('Ethereum Network Contract Address', 'wp-smart-contracts'),
      'ethereum-address-desc' => __('Please fill out the contract address you want to import', 'wp-smart-contracts'),
      'ethereum-address-important' => __('Important', 'wp-smart-contracts'),
      'ethereum-address-important-message' => __('Keep in mind that the contract is going to be loaded using the current network and current account as owner', 'wp-smart-contracts'),
      'active-net-account' => __('Currently active Ethereum Network and account:', 'wp-smart-contracts'),
      'smart-contract-address' => __('Smart Contract Address'),
      'load' => __('Load', 'wp-smart-contracts'),
      'ethereum-deploy' => __('Network Mint', 'wp-smart-contracts'),
      'warning-1' => __('You are going to mint the current item with the selected attributes and media, to the selected Smart Contract Collection', 'wp-smart-contracts'),
      'warning-2' => __('This action is irreversible', 'wp-smart-contracts'),
      'warning-3' => __('Are you sure you want to Mint your Item?', 'wp-smart-contracts'),
      'no-letme-check' => __('Cancel', 'wp-smart-contracts'),
  
      'ethereum-deploy-desc' => __('Are you ready to deploy your NFT to the currently active Ethereum Network?', 'wp-smart-contracts'),
      'cancel' => __('Cancel', 'wp-smart-contracts'),
      'yes-proceed' => __('Yes, please proceed', 'wp-smart-contracts'),
      'deployed-smart-contract' => __('Deployed Smart Contract', 'wp-smart-contracts'),

      'mint-your-items' => __('Mint your Item to the Blockchain', 'wp-smart-contracts'),
      'mint' => __('Mint', 'wp-smart-contracts'),
      'manually-load' => __('Manually load an existing item', 'wp-smart-contracts'),
      'item-id' => __('Item ID', 'wp-smart-contracts'),
      'if-you-already-minted' => __('If you already minted your items, add the token ID here', 'wp-smart-contracts'),
      'load' => __('Load', 'wp-smart-contracts'),

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

}
