<?php

if( ! defined( 'ABSPATH' ) ) die;

/**
 * Include semantic ui JS + CSS + Functions
 */

class WPSC_helpers {

	static public function getRole() {
		$options = get_option('etherscan_api_key_option');
		return (WPSC_helpers::valArrElement($options, "wpsc_role") and !empty($options["wpsc_role"]))?$options["wpsc_role"]:false;
	}

	// use to avoid notices when accesing an array element
	static public function valArrElement($arr=null, $elem=null) {
		if (!is_array($arr)) return false;
		if (!$elem and $elem!=="0" and $elem!==0) return false;
		if (!array_key_exists($elem, $arr)) return false;
		return true;
	}

	// get network information from json file
	static function getNetworks() {
	    if (file_exists($json_file = dirname(dirname(__FILE__)).'/assets/json/networks.json') and 
	    	is_array( $arr = json_decode( file_get_contents($json_file), true ) ) ) {
	    	return $arr;
		} else {
			return false;
		}
	}

	static public function tos() {
		$m = new Mustache_Engine;
		return $m->render(WPSC_Mustache::getTemplate('tos'));	
	}

	static public function tosaffp() {
		$m = new Mustache_Engine;
		return $m->render(WPSC_Mustache::getTemplate('tos-affp'));	
	}
	
	static public function getLogoAffP() {
		$ret["id"] = get_option("wps_logo_id");
		$ret["logo"] = "";
		if ($ret["id"]) {
			$img = wp_get_attachment_image_src($ret["id"], "medium");
			if ($img) {
				$ret["logo"] = '<img src="'.$img[0].'" style="max-height: 50px">';
			}
		}
		return $ret;
	}

	static public function getFlavorColor($flavor) {
		if ($flavor=="vanilla") return "yellow";
		if ($flavor=="pistachio") return "olive";
		if ($flavor=="chocolate") return "brown";
		if ($flavor=="bubblegum") return "blue";
		if ($flavor=="mango") return "orange";
		if ($flavor=="mochi") return "purple";
		if ($flavor=="suika") return "red";
		if ($flavor=="matcha") return "green";
		if ($flavor=="yuzu") return "yellow";
		if ($flavor=="ube") return "purple";
		if ($flavor=="almond") return "almond";
		if ($flavor=="ikasumi") return "black";
		if ($flavor=="azuki") return "brown";
		if ($flavor=="macadamia") return "beige";
	}

	// format a number with 18 decimals and the proper number separators
    static public function formatNumber($num) {

    	// convert to float
    	$num = floatval($num);

    	// validate number of decimals to show, use 4 as default
    	if (!is_numeric($ndts = WPSCSettingsPage::numberOfDecimalsToShow())) {
    		$ndts = 4;
    	}

    	// add thousands and decimals separators
        $nf = number_format($num, $ndts, WPSCSettingsPage::numberFormatDecimals(), WPSCSettingsPage::numberFormatThousands());

        return $nf;

    }

	// format a number with 18 decimals and the proper number separators
    static public function formatNumber2($num, $ndts) {

    	// convert to float
    	$num = floatval($num);

    	// add thousands and decimals separators
        $nf = number_format($num, $ndts, WPSCSettingsPage::numberFormatDecimals(), WPSCSettingsPage::numberFormatThousands());

        return $nf;

	}

	static private function createPage($flag, $title, $shortcode, $parent_flag=false, $add_clean_template=true) {

		$get = WPSC_assets::getPage($flag);

		if (!$get) {

			$old_page_id = WPSC_assets::getOldPageID($flag);

			// remove the page with the old logic, if exists
			if ($old_page_id) {
				wp_update_post(['ID' => $old_page_id, 'post_status' => 'trash']);
			}

			$parameters = array(
	            'post_title'     => $title,
	            'post_type'      => 'page',
	            'comment_status' => 'closed',
	            'ping_status'    => 'closed',
	            'post_content'   => $shortcode,
	            'post_status'    => 'publish',
	            'post_author'    => get_user_by( 'id', 1 )->user_id
	        );

			if ($parent_flag) {
				$parent_id = WPSC_assets::getPage($parent_flag, true);
				if ($parent_id) {
					$parameters["post_parent"] = $parent_id;
				}
			}

			$new_page_id = wp_insert_post($parameters);

			if ( $new_page_id && ! is_wp_error( $new_page_id ) ) {
	            update_post_meta( $new_page_id, $flag, true );
				if ($add_clean_template) {
					update_post_meta( $new_page_id, '_wp_page_template', 'wpsc-clean-template.php' );
				}
	            update_post_meta( $new_page_id, "is_wpsc_page", true );
	        }
		}

	}

    // create a page with QR Scanner and NFT minter if not already created
    static public function createPluginPages() {
    	self::createPage('wpsc_is_scanner', 		 __('WPSC QR Scanner', "wp-smart-contracts"), '[wpsc_qr_scanner]');
    	self::createPage('wpsc_is_nft_minter', 		 __('NFT Mint', "wp-smart-contracts"), '[wpsc_nft_mint]');
    	self::createPage('wpsc_is_nft_my_items', 	 __('NFT My Items', "wp-smart-contracts"), '[wpsc_nft_my_items]');
    	self::createPage('wpsc_is_nft_author', 		 __('NFT Authors', "wp-smart-contracts"), '[wpsc_nft_author]');
    	self::createPage('wpsc_is_nft_my_bids', 	 __('NFT My Bids', "wp-smart-contracts"), '[wpsc_nft_my_bids]');
    	self::createPage('wpsc_is_nft_my_galleries', __('NFT My Galleries', "wp-smart-contracts"), '[wpsc_nft_my_galleries]');
    	self::createPage('wpsc_activate_user', 		 __('User Activation', "wp-smart-contracts"), '[wpsc_activate_user]');

		$activate = WPSCSettingsPage::get('wpsc_activate_launcher');

		if ($activate) {
			self::createPage('wpsc_is_launcher', 				__('Launcher', "wp-smart-contracts"), '[wpsc_launcher]', false, false);
			self::createPage('wpsc_is_launcher_nft', 			__('Launcher NFT', "wp-smart-contracts"), '[wpsc_launcher section="nft"]', "wpsc_is_launcher", false, false);
			self::createPage('wpsc_is_launcher_stakes', 		__('Launcher Stakes', "wp-smart-contracts"), '[wpsc_launcher section="stakes"]', "wpsc_is_launcher", false, false);
			self::createPage('wpsc_is_launcher_crowd', 			__('Launcher Crowdfunding', "wp-smart-contracts"), '[wpsc_launcher section="crowd"]', "wpsc_is_launcher", false, false);
			self::createPage('wpsc_is_launcher_coin', 			__('Launcher Coins', "wp-smart-contracts"), '[wpsc_launcher section="coin"]', "wpsc_is_launcher", false, false);
			self::createPage('wpsc_is_launcher_services', 		__('Launcher Services', "wp-smart-contracts"), '[wpsc_launcher section="services"]', "wpsc_is_launcher", false, false);
			self::createPage('wpsc_is_wizard', 					__('Wizard', "wp-smart-contracts"), '[wpsc_wizard]', false, false);
			self::createPage('wpsc_is_wizard_nft', 				__('Wizard NFT', "wp-smart-contracts"), '[wpsc_wizard section="nft"]', "wpsc_is_wizard", false, false);
			self::createPage('wpsc_is_wizard_stakes', 			__('Wizard Stakes', "wp-smart-contracts"), '[wpsc_wizard section="stakes"]', "wpsc_is_wizard", false, false);
			self::createPage('wpsc_is_wizard_services', 		__('Wizard Services', "wp-smart-contracts"), '[wpsc_wizard section="services"]', "wpsc_is_wizard", false, false);
			self::createPage('wpsc_is_wizard_coin', 			__('Wizard Coins', "wp-smart-contracts"), '[wpsc_wizard section="coin"]', "wpsc_is_wizard", false, false);
			self::createPage('wpsc_is_wizard_network', 			__('Wizard Networks', "wp-smart-contracts"), '[wpsc_wizard section="network"]', "wpsc_is_wizard", false, false);
			self::createPage('wpsc_is_wizard_deploy_macadamia', __('Wizard Deploy Macadamia', "wp-smart-contracts"), '[wpsc_wizard section="deploy-macadamia"]', "wpsc_is_wizard", false, false);
			self::createPage('wpsc_is_wizard_deploy_chocolate', __('Wizard Deploy Chocolate', "wp-smart-contracts"), '[wpsc_wizard section="deploy-chocolate"]', "wpsc_is_wizard", false, false);
			self::createPage('wpsc_is_wizard_deploy_pistachio', __('Wizard Deploy Pistachio', "wp-smart-contracts"), '[wpsc_wizard section="deploy-pistachio"]', "wpsc_is_wizard", false, false);
			self::createPage('wpsc_is_wizard_deploy_vanilla', 	__('Wizard Deploy Vanilla', "wp-smart-contracts"), '[wpsc_wizard section="deploy-vanilla"]', "wpsc_is_wizard", false, false);
			self::createPage('wpsc_is_wizard_deploy_ube', 		__('Wizard Deploy Ube', "wp-smart-contracts"), '[wpsc_wizard section="deploy-ube"]', "wpsc_is_wizard", false, false);
			self::createPage('wpsc_is_wizard_deploy_almond', 	__('Wizard Deploy Almond', "wp-smart-contracts"), '[wpsc_wizard section="deploy-almond"]', "wpsc_is_wizard", false, false);
			self::createPage('wpsc_is_wizard_deploy_ikasumi', 	__('Wizard Deploy Ikasumi', "wp-smart-contracts"), '[wpsc_wizard section="deploy-ikasumi"]', "wpsc_is_wizard", false, false);
			self::createPage('wpsc_is_wizard_deploy_azuki', 	__('Wizard Deploy Azuki', "wp-smart-contracts"), '[wpsc_wizard section="deploy-azuki"]', "wpsc_is_wizard", false, false);
			self::createPage('wpsc_is_wizard_deploy_yuzu', 		__('Wizard Deploy Yuzu', "wp-smart-contracts"), '[wpsc_wizard section="deploy-yuzu"]', "wpsc_is_wizard", false, false);
			self::createPage('wpsc_is_wizard_deploy_suika', 	__('Wizard Deploy Suika', "wp-smart-contracts"), '[wpsc_wizard section="deploy-suika"]', "wpsc_is_wizard", false, false);
			self::createPage('wpsc_is_wizard_deploy_matcha', 	__('Wizard Deploy Matcha', "wp-smart-contracts"), '[wpsc_wizard section="deploy-matcha"]', "wpsc_is_wizard", false, false);
			self::createPage('wpsc_is_wizard_deploy_mochi', 	__('Wizard Deploy Mochi', "wp-smart-contracts"), '[wpsc_wizard section="deploy-mochi"]', "wpsc_is_wizard", false, false);
			self::createPage('wpsc_is_wizard_deploy_bubblegum', __('Wizard Deploy Bubblegum', "wp-smart-contracts"), '[wpsc_wizard section="deploy-bubblegum"]', "wpsc_is_wizard", false, false);
		}
    }

    // return the short version of an Ethereum address
	public static function shortify($address, $ultra=false) {
		if ($address) {
			if ($ultra) {
				return substr($address, 0, 4) . '…' . substr($address, -2);
			} else {
				return substr($address, 0, 6) . '...' . substr($address, -4);
			}	
		}
	}

	public static function languages() {
		load_plugin_textdomain( 'wp-smart-contracts', false, basename( dirname( __DIR__ ) ) . '/languages/' );
	}

	public static function renderWPICInfo() {
	    $m = new Mustache_Engine;
	    return $m->render(
	      WPSC_Mustache::getTemplate('metabox-wpic-info'), 
	      [
	        "title" => __('Scale Ethereum', 'wp-smart-contracts'),
			"logo" => dirname( plugin_dir_url( __FILE__ )) . '/assets/img/xdai.png',
	        "description_1" => __('Layer 2 Solutions supported by WPSmartContracts', 'wp-smart-contracts'),
	        "description_2" => __('Now you can deploy all smart contracts flavors in four blockchains.', 'wp-smart-contracts'),
	        "feature_1" => __('Ethereum', 'wp-smart-contracts'),
	        "feature_2" => __('xDai Chain', 'wp-smart-contracts'),
	        "feature_3" => __('Binance Smart Chain', 'wp-smart-contracts'),
	        "feature_4" => __('Polygon (previously Matic)', 'wp-smart-contracts'),
	        "feature_5" => __('Easy deploy with lower fees', 'wp-smart-contracts'),
			"blockchain_img" => dirname( plugin_dir_url( __FILE__ )) . '/assets/img/blocks.png',
	        "claim" => __('Claim 5,000 WPIC', 'wp-smart-contracts'),
	        "claim-note" => __('0.1 Ξ', 'wp-smart-contracts'),
	        "learn" => __('Learn more', 'wp-smart-contracts'),
	      ]
	    );
	}

	static public function nativeCoinName($net) {
		$networks = self::getNetworks();
		if (WPSC_helpers::valArrElement($networks, $net) and 
			WPSC_helpers::valArrElement($networks[$net], "coin-symbol") and
			$networks[$net]["coin-symbol"]) {
			return $networks[$net]["coin-symbol"];
		} else {
            return "Ether";
		}
	}

	static public function getIdFromShortcodes() {

		$the_id = get_the_ID();

		$data = get_post_field("post_content", $the_id);

		foreach(['wpsc_coin', 'wpsc_crowdfunding', 'wpsc_ico', 'wpsc_staking'] as $shortcode) {

			preg_match("/\[$shortcode (.+?)\]/", $data, $dat);
		
			$dat = array_pop($dat);
			$dat= explode(" ", $dat);
			$params = array();
			foreach ($dat as $d){
				if ($d) {
					list($opt, $val) = explode("=", $d);
					$params[$opt] = trim($val, '"');	
				}
			}
		
			if (WPSC_helpers::valArrElement($params, "id") and $params["id"]) {
				$the_id = $params["id"];
				break;
			}
		
		}
		
		return $the_id;
	}

	static public function flavors() {
		return ["vanilla", "pistachio", "chocolate", "macadamia", "mango", "bubblegum", "matcha", "mochi", "suika", "yuzu", "azuki", "ikasumi", "ube", "almond"];
	}

	static public function getNetworkInfoJSON($flavor) {

		$refresh_cache = false;
		$option_name = "wpsc-1-3-6";
		if (!get_option($option_name)) {
			$refresh_cache = true;
			update_option($option_name, true);
		}
		// store in transient for 6 hours
		$transient_name = WPSC_Endpoints::transientPrefix . "net_info_json";
		$json = false;
		if (!$refresh_cache and $t = get_transient($transient_name)) {
			$json = $t;
		} else {
			if (defined("WPSC_NETINFO_LOCAL_PATH")) {
				$response = wp_remote_get(WPSC_NETINFO_LOCAL_PATH);
			} else {
				$response = wp_remote_get("https://api.wpsmartcontracts.com/netinfo-2.0.json");
			}
			if ( is_array( $response ) && ! is_wp_error( $response ) ) {
				$json = $response['body'];
				if ($json) {
					json_decode($json);
					if (json_last_error() === JSON_ERROR_NONE) {
						set_transient($transient_name, $json, 21600);
					}
				}
			}
		}
		if ($json) {
			$json = json_decode($json, true);
			$json_ret = [];
			if (WPSC_helpers::valArrElement($json, "data")) {
				foreach($json["data"] as $i => $j) {
					if (WPSC_helpers::valArrElement($json["data"][$i], "discontinued")) continue;

					if (WPSC_helpers::valArrElement($json["data"][$i], $flavor) and $json["data"][$i][$flavor]) {
						if ($json["data"][$i]["type"]=="Mainnet") {
							$json["data"][$i]["is_mainnet"]=true;
						}
						if (WPSC_helpers::valArrElement($json["data"][$i], "blockchain")) {
							switch($json["data"][$i]["blockchain"]) {
							case "Ethereum":
								$json["data"][$i]["is_ethereum"]=true;
								break;
							case "Bitcoin":
								$json["data"][$i]["is_bitcoin"]=true;
								break;
							}		
						}
						$json["data"][$i]["show"]=true;
						if ($json["data"][$i][$flavor][0]=="Free") {
							$json["data"][$i]["free"]=true;
						} else {
							$json["data"][$i]["fee"]=$json["data"][$i][$flavor][0];
							$json["data"][$i]["fee_usd"]=$json["data"][$i][$flavor][1];
						}
						$json_ret[] = $json["data"][$i];
					}
				}
				return ["date_time" => $json["date_time"], "data"=>$json_ret, "asset_path"=>plugin_dir_url( dirname( __FILE__ ) ) . 'assets/img/'];    
			}
	   }

	}

	static function userGalleriesMessage() {	
		if (WPSC_helpers::valArrElement($_COOKIE, "wpscGalleriesMessageDismissed") and $_COOKIE["wpscGalleriesMessageDismissed"]) {	
			return '';	
		} else {	
			return '<div id="wpsc-galleries-message" class="notice notice-info is-dismissible"><p>' . __('If you want to assign galleries to users, go to the users section, edit the user\'s profile and assign galleries accordingly.', "wp-smart-contracts") . '</p></div>';	
		}	
	}

	static function getGalleryIdCookie($collection_id) {	
			
		$wpsc_sub_collections = get_post_meta($collection_id, 'wpsc_sub_collections', true);	
        if (!$wpsc_sub_collections) return false;	
		if (WPSC_helpers::valArrElement($_GET, "gal_id")) {	
			$gal_id = (int) $_GET["gal_id"];	
			if ($gal_id) {	
				return $gal_id;	
			}	
		}	
		$cookie_name = "gal_id_" . $collection_id;	
		if (WPSC_helpers::valArrElement($_COOKIE, $cookie_name)) {	
			return $_COOKIE[$cookie_name];	
		} else {	
			return false;	
		}	
	}	

    static function processGalleryCookies() {	
		$collection_id = self::getCollectionID();	
		if (!$collection_id) return;	
		// If we get the gallery id parameter	
        if (self::valArrElement($_GET, 'gal_id')) {	
			$cookie_name = "gal_id_" . $collection_id;	
            // process reset	
            if ($_GET['gal_id']=="reset") {	
				// unset cookie	
                if (WPSC_helpers::valArrElement($_COOKIE, $cookie_name)) {	
					unset($_COOKIE[$cookie_name]);	
                }	
                setcookie($cookie_name, '', time() - 3600, '/');	
            // process numeric gallery id	
            } else {	
                $current_gal_id = (int) $_GET["gal_id"];	
                // store it in cookies	
                if ($current_gal_id) {	
                    setcookie($cookie_name, $current_gal_id, time()+3600, '/');	
                }	
            }	
        }	
	}	

	static public function walkQuery(&$item, $key, $params) {	
			
		$term_id_int = (int) $item["term_id"];	
		if (!$term_id_int) return;	
		$url = get_term_link($term_id_int, "nft-gallery");	
			
		if (is_wp_error($url)) return;	
        $url = add_query_arg("gal_id", $item["term_id"], $url);	
        $url = add_query_arg("id", $params["collection_id"], $url);	
		$item["url"] = $url;	
        if ($params["current_gal_id"] == $item["term_id"]) {	
            $item["selected"] = true;	
        }	
    }

	static public function currentGalleryLink($collection_id) {	
        $url = remove_query_arg("gal_id", self::actualURL());	
        $current_gal_id = 0;	
        // If we get the gallery id parameter	
        if (self::valArrElement($_GET, 'gal_id')) {	
            $current_gal_id = (int) $_GET["gal_id"];	
        }	
        $cookie_name = 'gal_id_'.$collection_id;	
        // try to get the gallery id from cookies if it is not passed as get parameter	
        if (!$current_gal_id and self::valArrElement($_COOKIE, $cookie_name)) {	
            $current_gal_id = (int) $_COOKIE[$cookie_name];	
        }	
		if (!$current_gal_id) {	
			return add_query_arg("gal_id", "reset", get_permalink($collection_id));	
		} else {	
			$url = get_term_link($current_gal_id, "nft-gallery");			
			if (is_wp_error($url)) return;	
			$url = add_query_arg("gal_id", $current_gal_id, $url);	
			$url = add_query_arg("id", $collection_id, $url);	
			return $url;	
		}	
    }	
	
	static public function getGalleriesOfCollection($collection_id) {	
        $url = remove_query_arg("gal_id", self::actualURL());	
        $current_gal_id = 0;	
        // If we get the gallery id parameter	
        if (self::valArrElement($_GET, 'gal_id')) {	
            $current_gal_id = (int) $_GET["gal_id"];	
        }	
        $cookie_name = 'gal_id_'.$collection_id;	
        // try to get the gallery id from cookies if it is not passed as get parameter	
        if (!$current_gal_id and self::valArrElement($_COOKIE, $cookie_name)) {	
            $current_gal_id = (int) $_COOKIE[$cookie_name];	
        }	
		$res = WPSC_Queries::queryGalleriesOfCollection($collection_id);	
        array_walk($res, ["WPSC_helpers", "walkQuery"], ["url"=>$url, "current_gal_id"=>$current_gal_id, "collection_id"=>$collection_id]);	
        $first = ["term_id"=>0, "name" => __("All Galleries", "wp-smart-contracts"), "url" => add_query_arg("gal_id", "reset", get_permalink($collection_id))];	
        if (!$current_gal_id) {	
            $first["selected"]=true;	
        }	
        array_unshift($res, $first);	
        	
        return $res;	
    }


	static function getCollectionID() {

		$collection_id = 0;
		if (WPSC_helpers::valArrElement($_GET, 'id')) {
            $collection_id = (int) $_GET["id"];
			if ($collection_id and get_post_type($collection_id)=="nft-collection") {
				return $collection_id;
			}
		}

		$post_id = get_the_ID();
		$post_type = get_post_type($post_id);

		if ($post_type == "nft-collection") {
			$collection_id = $post_id;
		} elseif($post_type=="nft") {
			$collection_id = get_post_meta($post_id, "wpsc_item_collection", true);
        }

		return $collection_id;

	}

	static public function actualURL() {
        return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	}

	public static function updatePostMeta($field, $field_meta, $arr, $post_id) {
		if (WPSC_helpers::valArrElement($arr, $field)) {
			$val = WPSC_Metabox::cleanUpText($arr[$field]);
			update_post_meta($post_id, $field_meta, $val);
			return $val;
		} else {
			update_post_meta($post_id, $field_meta, null);
		}
	}	

}

/**
 * Warnings in wp-admin
 */

add_action('admin_notices', function () {

	// check that rest api is alive

	$endpoint = get_rest_url(null, 'wpsc/v1/ping');

	$response = wp_remote_get( $endpoint, null );

	if (!WPSC_helpers::valArrElement($response, "response") or !WPSC_helpers::valArrElement($response["response"], "code") or $response["response"]["code"] != "200") {
		
		$get_rest_url = get_rest_url(null, '');

		echo <<<HELP
		<div class="notice notice-error is-dismissible">
			<h3>WP Smart Contracts alert - Action needed!</h3>
			<p>Looks like the <a href="https://developer.wordpress.org/rest-api/" target="_blank">WP Rest API</a> is not working properly in your WordPress installation</p>
			<p>Please check that the URL: <a href="$get_rest_url" target="_blank">$get_rest_url</a> is not failing</p>
			<p>For more information please visit: <a href="https://wordpress.org/support/topic/404-error-on-coin-page-already-created-the-coin-on-mainnet/" target="_blank">our support page</a></p>
		</div>
HELP;
	}

	// NEWS

	if (
		(!isset($_GET["fs"]) or $_GET["fs"]!="1") and 
		(!isset($_GET["page"]) or $_GET["page"]!="wpsc-admin-setup") and 
		!WPSC_helpers::valArrElement($_COOKIE, "notice_hide_2_0_9")
	) {

		$wpsc_logo = dirname( plugin_dir_url( __FILE__ )) . '/assets/img/wpsc-red-logo.png';
		$bubblegum = dirname( plugin_dir_url( __FILE__ )) . '/assets/img/flavor-bubblegum.png';
		$light = dirname( plugin_dir_url( __FILE__ )) . '/assets/img/light.png';
		$admin_setup = admin_url("admin.php?page=wpsc-admin-setup");

		echo <<<BLOCKCHAIN
		<style>
			.wps-notice-column{float:left;width: 33.33%}.wps-notice-column-2a{float:left;width:30%}.wps-notice-column-2b{float:left;width:70%}.wps-notice-column-container{padding:0 20px}.wps-notice-row:after{content: "";display: table;clear: both}
			.wps-notice-logo{max-width: 90px; float: left; margin-right: 25px}
			.wps-notice-row h1 {color: #ff017b; font-size: 30px;font-weight: 700;line-height: 1;margin:15px;display:block}
			.wps-notice-row h2 {color: #ff017b; font-size: 20px;line-height: 1;display:inline}
			.wps-notice-row h3 {color: #ff017b; font-size: 20px;line-height: 1}
			@media screen and (max-width:600px){.wps-notice-column{width: 100%}}
			@media screen and (max-width:1440px) {
				.wps-notice-column-2a, .wps-notice-column-2b{width: 100%}.wps-notice-logo{margin:auto;float:none;display:block}
				.wps-notice-row h1 {font-size: 30px;text-align:center}
				.wps-notice-row h2 {font-size: 20px;text-align:center}
			}
		</style>
		<div id="wpic-notification" style="margin-top: 40px; margin-bottom: 40px; max-width:1400px; margin-left:auto; margin-right:auto">
			<div class="wps-notice-row" style="background-image: radial-gradient(circle at 1.7% 12%, #f0daff 0, #bff7f5 43%, #ffe1f2 87%, #ffffff 127%);padding: 20px;border-top-right-radius: 40px;border-top-left-radius: 40px;">
				<img src="$wpsc_logo" class="wps-notice-logo">
				<h1>What's New on WP Smart Contracts 2.0.9</h1>
				<h2>The Ultimate Blockchain Integration for WordPress</h2>
			</div>
			<div style="background-color: white;border-bottom-left-radius: 40px;border-bottom-right-radius: 40px;margin-bottom: 20px; padding: 0 40px 40px;">
				<div class="wps-notice-row">
					<div class="wps-notice-column">
						<div class="wps-notice-column-container">
							<h3 style="color: #ff017b;">🚀 The WPIC Crowdsale Has Begun! 🚀</h3>
							<p>Exciting news! The WPIC Crowdsale is now live, offering you a golden opportunity to dive into the world of WP Smart Contracts.</p>
							<p>Head over to <a href="https://wpsmartcontracts.com/wpic-crowdsale/" target="_blank">WPIC Crowdsale Page</a> to secure your WPIC tokens across various networks: Ethereum, Arbitrum, Binance Smart Chain, Polygon, Avalanche, and Fantom.</p>
							<p style="margin: 25px 0"><a href="https://wpsmartcontracts.com/wpic-crowdsale/" target="_blank" style="background: #ff017b; border-radius: 25px; color: white; text-decoration: none; padding: 10px 20px">Learn more</a></p>
						</div>
					</div>
					<div class="wps-notice-column">
						<div class="wps-notice-column-container">
							<h3 style="color: #ff017b;">🌟 Pre-Sale Phase - Act Now!</h3>
							<h3 style="color: #ff017b;font-size: 15px;">Get a 75% discount on smart contract deployments</h3>
							<p>From November 10, 2023 until November 23 or sold-out, you can seize the Pre-Sale advantage, obtaining WPIC at an incredible price.</p>
							<p style="margin: 25px 0"><a href="https://wpsmartcontracts.com/wpic-crowdsale/" target="_blank" style="background: #ff017b; border-radius: 25px; color: white; text-decoration: none; padding: 10px 20px">Learn more</a></p>
						</div>
					</div>
					<div class="wps-notice-column">
						<div class "wps-notice-column-container">
							<h3 style="color: #ff017b;">What's new in 2.0.9?</h3>
							<ul>
								<li>&raquo; Enable WPIC deployment on Azuki and Ikasumi</li>
								<li>&raquo; Restore connection dialogs in skins 1.0</li>
								<li>&raquo; Fix ICO view parameters for users not connected to Metamask</li>
								<li>&raquo; Fix loading issue with Stakes</li>
								<li>&raquo; Fix tag display in Launcher</li>
								<li>&raquo; Fix mobile menu on Launcher</li>
								<li>&raquo; Tested up to WordPress 6.4.1</li>
							</ul>
						</div>
					</div>
				</div>
				<div class="wps-notice-column-container" style="padding-bottom:10px">
					<hr>
					Thank you for choosing <a href="https://wpsmartcontracts.com" target="_blank">WPSmartContracts</a>!
					<span style="color: #777;float: right"><input type="checkbox" id="wpic-no-show"> Got it, Thanks!. Please don't show this again.</span>
					<button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>	
				</div>
			</div>
		</div>
BLOCKCHAIN;
	}

});

/**
 * Load i18n files
 */
add_action( 'plugins_loaded', ['WPSC_helpers', 'languages'] );

