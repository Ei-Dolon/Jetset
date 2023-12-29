<?php

if( ! defined( 'ABSPATH' ) ) die;

new WPSC_Queries();

/**
 * Handle etherscan api queries for block explorer view
 */

class WPSC_Queries {

    static public function nftAuthors() {

        global $wpdb;

        $authors = $wpdb->get_results("SELECT DISTINCT post_author FROM $wpdb->posts WHERE post_status=\"publish\" AND post_type=\"nft\"", ARRAY_A);
    
        if (!empty($authors)) {
            $authors = array_column($authors, 'post_author');
            return $wpdb->get_results("SELECT user_nicename FROM $wpdb->users WHERE ID in (".implode(",", $authors).")", ARRAY_A);
        }

        return [];

    }

    static public function queryGalleriesOfCollection($collection_id) {

        global $wpdb;
        return $wpdb->get_results("
        SELECT  DISTINCT t.term_id, t.name 
        FROM    $wpdb->term_taxonomy tax, 
                $wpdb->term_relationships r, 
                $wpdb->terms t 
        WHERE   tax.term_id=t.term_id AND 
                r.term_taxonomy_id=tax.term_taxonomy_id AND 
                tax.taxonomy=\"nft-gallery\" AND 
                object_id IN (
                    SELECT DISTINCT post_id 
                    FROM $wpdb->postmeta m 
                    WHERE   meta_key=\"wpsc_item_collection\" AND 
                            meta_value=\"$collection_id\"
                )
        ORDER BY t.name
        ", ARRAY_A);
    
    }

    /**
     * Get deployed ERC-1155 Collections
     */
    static public function nftERC1155Collections($id=false) {
        $tmp = self::nftCollections(false, true, $id);
        $res = [];
        $networks = WPSC_helpers::getNetworks();
        if (is_array($tmp)) {
            foreach($tmp as $rec) {
                if (WPSC_helpers::valArrElement($rec, 'erc1155') and $rec["erc1155"] and 
                    WPSC_helpers::valArrElement($rec, 'deployed') and $rec["deployed"]) {
                    if (isset($networks[$rec["network"]]["title"])) $rec["network_name"] = $networks[$rec["network"]]["title"];
                    if (isset($networks[$rec["network"]]["color"])) $rec["network_color"] = $networks[$rec["network"]]["color"];
                    $res[] = $rec;
                }
            }
        }
        return $res;
    }

    static private function getDashboardPosts($account, $start, $len, $type) {

        global $wpdb;

        // get users
        $query = $wpdb->prepare('SELECT p.ID FROM ' . $wpdb->posts . ' p, ' . $wpdb->postmeta . ' m WHERE p.ID = m.post_id AND p.post_type = "' . $type . '" AND meta_key = "wpsc_owner" AND meta_value = %s ORDER BY p.ID DESC LIMIT %d, %d', $account, $start, $len);

        $res = $wpdb->get_results($query, ARRAY_A);

        $ids = wp_list_pluck($res, "ID");

        if (!is_array($ids) or !sizeof($ids)) return false;

        return $ids;

    }

    static public function getCollectionIDByReference($collection_id) {

        global $wpdb;

        $query = $wpdb->prepare('SELECT post_id FROM `' . $wpdb->postmeta . '` WHERE meta_key="wpsc_nft_redirect_url_1155" AND meta_value=%s ORDER BY post_id DESC LIMIT 1', $collection_id);

        $res = $wpdb->get_results($query, ARRAY_A);

        if (is_array($res) and isset($res[0]["post_id"])) {
            return $res[0]["post_id"];
        }
        
        return false;

    }

    static public function getDashboardCoins($account, $start, $len) {

        global $wpdb;

        $ids = self::getDashboardPosts($account, $start, $len, "coin");

        if (!$ids) return false;

        $query = $wpdb->prepare('SELECT post_id, meta_key, meta_value FROM ' . $wpdb->postmeta .' WHERE post_id IN (' . implode( ',', $ids ) . ') AND meta_key IN ("wpsc_coin_symbol", "wpsc_coin_name", "wpsc_contract_address", "wpsc_network", "wpsc_flavor", "wpsc_qr_code") ORDER BY post_id DESC');

        $res = $wpdb->get_results($query, ARRAY_A);

        $ret = [];
        if (is_array($res)) {

            $networks = WPSC_helpers::getNetworks();

            foreach ($res as $key => $value) {
                if (isset($value["post_id"]) and isset($value["meta_key"]) and isset($value["meta_value"])) {
                    $ret[$value["post_id"]][$value["meta_key"]] = ucfirst($value["meta_value"]);

                    if (isset($value["post_id"]) and isset($value["meta_key"]) and isset($value["meta_value"])) {
                        if ($value["meta_key"] == "wpsc_network") {
                            @$ret[$value["post_id"]][$value["meta_key"]] = strtoupper($networks[$value["meta_value"]]["name"]);
                            @$ret[$value["post_id"]]["color_network"] = $networks[$value["meta_value"]]["color-html"];
                            @$ret[$value["post_id"]]["class_network"] = $networks[$value["meta_value"]]["class"];
                            @$ret[$value["post_id"]]["etherscan"] = $networks[$value["meta_value"]]["url2"];
                        } else {
                            $ret[$value["post_id"]][$value["meta_key"]] = ucfirst($value["meta_value"]);
                            if ($value["meta_key"] == "wpsc_flavor") {
                                $ret[$value["post_id"]]["color_flavor"] = WPSC_helpers::getFlavorColor($value["meta_value"]);
                            }
                        }
                    }

                    $ret[$value["post_id"]]["link"] = get_permalink($value["post_id"]);

                }
            }    
        }
        return $ret;

    }
    
    static public function getIDByContract($contract, $network) {
        global $wpdb;
        $res = $wpdb->get_col( $wpdb->prepare( "SELECT post_id FROM " . $wpdb->postmeta . " where meta_key ='wpsc_contract_address' and meta_value = %s", $contract ) );
        switch (sizeof($res)) {
            case 0:
                return false;
                break;
            case 1:
                if (isset($res[0])) return $res[0];
                break;
            default:
                // search for the network
                $sql = $wpdb->prepare( "SELECT post_id FROM " . $wpdb->postmeta . " where post_id IN(" . implode(",", $res) . ") and meta_key = \"wpsc_network\" and meta_value = %s", $network );
                $res = $wpdb->get_col($sql);
                if (isset($res[0])) return $res[0];
                else return false;
                break;
        }
    }

    static private function getDashboardGeneric($account, $start, $len, $type) {

        global $wpdb;

        $ids = self::getDashboardPosts($account, $start, $len, $type);

        if (!$ids) return false;

        $query = $wpdb->prepare('SELECT p.ID, p.post_title, m.meta_key, m.meta_value FROM ' . $wpdb->posts .' p, ' . $wpdb->postmeta .' m WHERE p.ID = m.post_id AND p.ID IN (' . implode( ',', $ids ) . ') AND meta_key IN ("wpsc_contract_address", "wpsc_network", "wpsc_flavor", "wpsc_qr_code") ORDER BY p.ID DESC');

        $res = $wpdb->get_results($query, ARRAY_A);

        $networks = WPSC_helpers::getNetworks();
        
        $ret = [];
        if (is_array($res)) {
            foreach ($res as $key => $value) {
                @$ret[$value["ID"]]["post_title"] = $value["post_title"];
                if (isset($value["ID"]) and isset($value["meta_key"]) and isset($value["meta_value"])) {
                    if ($value["meta_key"] == "wpsc_network") {
                        @$ret[$value["ID"]][$value["meta_key"]] = strtoupper($networks[$value["meta_value"]]["name"]);
                        @$ret[$value["ID"]]["color_network"] = $networks[$value["meta_value"]]["color-html"];
                        @$ret[$value["ID"]]["class_network"] = $networks[$value["meta_value"]]["class"];
                        @$ret[$value["ID"]]["etherscan"] = $networks[$value["meta_value"]]["url2"];
                    } else {
                        @$ret[$value["ID"]][$value["meta_key"]] = ucfirst($value["meta_value"]);
                        if ($value["meta_key"] == "wpsc_flavor") {
                            @$ret[$value["ID"]]["color_flavor"] = WPSC_helpers::getFlavorColor($value["meta_value"]);
                        }
                    }
                }
                @$ret[$value["ID"]]["link"] = get_permalink($value["ID"]);
            }    
        }
        return $ret;

    }

    static public function getDashboardStakes($account, $start, $len) {
        return self::getDashboardGeneric($account, $start, $len, "staking");
    }

    static public function getDashboardCrowdfunding($account, $start, $len) {
        return self::getDashboardGeneric($account, $start, $len, "crowdfunding");
    }

    static public function getDashboardNfts($account, $start, $len) {
        return self::getDashboardGeneric($account, $start, $len, "nft-collection");
    }

    static public function getDashboardFunds($account, $start, $len) {
        return self::getDashboardGeneric($account, $start, $len, "ico"); // TODO: in the future when airdrops and fund vaults are in place this need to be an array
    }

    static public function nftCollections($selected=false, $get_deployed=false, $id=false) {
        global $wpdb;
        $cond = "";
        if ($id) {
            $query = $wpdb->prepare('SELECT ID, post_title FROM '.$wpdb->posts.' WHERE post_status="publish" AND post_type="nft-collection" AND ID=%d ORDER BY post_title', $id);
            $res = $wpdb->get_results($query, ARRAY_A);
        } else {
            $res = $wpdb->get_results("SELECT ID, post_title FROM $wpdb->posts WHERE post_status=\"publish\" AND post_type=\"nft-collection\" ORDER BY post_title", ARRAY_A);
        }

        if (is_array($res) and ($selected or $get_deployed)) {
            foreach($res as $i => $row) {
                if ($row["ID"]==$selected) {
                    $res[$i]["selected"] = true;
                    if (!$get_deployed) {
                        return $res;
                    }
                }
                if (empty($res[$i]["post_title"])) {
                    $res[$i]["post_title"] = "Untitled";
                }
                if ($get_deployed) {
                    if ($contract = get_post_meta($row["ID"], 'wpsc_contract_address', true)) {
                        $res[$i]["deployed"] = $contract;
                    }
                    if ($net = get_post_meta($row["ID"], 'wpsc_network', true)) {
                        $res[$i]["network"] = $net;
                    }
                    if ($flavor = get_post_meta($row["ID"], 'wpsc_flavor', true) and ($flavor=="yuzu" or $flavor=="ikasumi" or $flavor=="azuki")) {
                        $res[$i]["erc1155"] = true;
                        $res[$i]["flavor"] = $flavor;
                    }
                }
            }
        }
        return $res;
    }

    static public function getTaxonomy($tax) {
        global $wpdb;
        return $wpdb->get_results("SELECT t.term_id, t.name FROM $wpdb->term_taxonomy tax, $wpdb->terms t WHERE tax.term_id=t.term_id AND taxonomy=\"".$tax."\" ORDER BY t.name", ARRAY_A);
    }

    static public function getNFTGalleriesByName($name) {
        global $wpdb;
        return $wpdb->get_results("
            SELECT $wpdb->terms.term_id 
            FROM $wpdb->terms, 
                 $wpdb->term_taxonomy 
            WHERE 
                $wpdb->terms.term_id=$wpdb->term_taxonomy.term_id AND 
                taxonomy=\"nft-gallery\" AND name=\"$name\"
        ", ARRAY_A);
    }

    static public function getMediaByHash($content) {

        global $wpdb;

        $sql = "
            SELECT p.ID 
            FROM $wpdb->posts p, $wpdb->postmeta m 
            WHERE p.post_type=\"attachment\" AND 
                  p.ID=m.post_id AND 
                  m.meta_key=\"wpsc_hash\" AND 
                  m.meta_value=\"".md5($content)."\"
        ";

        $res = $wpdb->get_results($sql, ARRAY_A);

        if (is_array($res)) {
            return @$res[0]["ID"];
        }

        return false;

    }

    static public function getNFTsByID($mixed_ids, $contract, $network) {

        // separate the numeric NFT ids from post ids marked as p[id]
        $mixed_array = explode(",", $mixed_ids);

        if (!is_array($mixed_array)) return;

        $ids = [];
        $post_ids = [];
        
        foreach($mixed_array as $elem) {
            if (is_numeric($elem)) {
                $ids[] = $elem;
            } else {
                $post_ids[] = substr($elem, 1);
            }
        }

        $ids = implode(",", $ids);

        // continue with the regular process
        $str = str_replace(',', '', $ids);

        if (!empty($str) and !ctype_digit($str)) return;

        if (!preg_match('/^(0x)?[0-9a-fA-F]{40}$/', $contract, $output_array)) return;

        global $wpdb;

        $order = '';
        if (WPSCSettingsPage::nftReverse()) {
            $order = "ORDER BY p.ID DESC";
        } else {
            $order = "ORDER BY p.ID";
        }

        if ($ids) {
            $sql = "
            SELECT p.ID
            FROM $wpdb->posts p, $wpdb->postmeta m, $wpdb->postmeta m2, $wpdb->postmeta m3
            WHERE 
                p.post_type=\"nft\" AND 
                p.post_status=\"publish\" AND 
                p.ID=m.post_id AND 
                p.ID=m2.post_id AND 
                p.ID=m3.post_id AND 
                m.meta_key=\"wpsc_nft_id\" AND 
                m.meta_value IN ($ids) AND 
                m2.meta_key=\"wpsc_collection_contract\" AND 
                m2.meta_value=\"$contract\" AND
                m3.meta_key=\"wpsc_network\" AND 
                m3.meta_value=\"$network\" 
            $order";
            $res = $wpdb->get_results($sql, ARRAY_A);    
        } else {
            $res = [];
        }
        
        if (!empty($post_ids)) {
            foreach($post_ids as $id) {
                $res[] = ["ID" => $id];
            }
        }

        return $res;

    }

    // Get NFT WP ID by getting post_id filtering wpsc_item_collection = collection_id and wpsc_nft_id = wpsc_nft_id
    static function getPostIDByNFTID($collection_id, $wpsc_nft_id) {

        global $wpdb;

        $collection_id = (int) $collection_id;
        $wpsc_nft_id = (int) $wpsc_nft_id;
        
        $sql = "
        SELECT  nft.post_id 
        FROM    $wpdb->postmeta collection, 
                $wpdb->postmeta nft 
        WHERE   collection.post_id=nft.post_id AND 
                collection.meta_key=\"wpsc_item_collection\" AND 
                collection.meta_value=\"$collection_id\" AND 
                nft.meta_key=\"wpsc_nft_id\" AND 
                nft.meta_value=\"$wpsc_nft_id\"
        ";

        $res = $wpdb->get_results($sql, ARRAY_A);
        return $res;

    }

    static public function getNFTIdsByAuthor($collection_id, $author_id) {

        $collection_id = (int) $collection_id;
        if ($collection_id==0) return [];

        $author_id = (int) $author_id;
        if ($author_id==0) return [];

        global $wpdb;
        $query = "
        SELECT  p.ID, m2.meta_value as nft_id 
        FROM    $wpdb->postmeta m, 
                $wpdb->postmeta m2,
                $wpdb->posts p
        WHERE   m.post_id=m2.post_id AND 
                m.meta_value=$collection_id AND 
                p.ID=m.post_id AND
                m.meta_key=\"wpsc_item_collection\" AND 
                m2.meta_key=\"wpsc_nft_id\" AND
                p.post_author=$author_id AND
                p.post_status=\"publish\"";

        return self::checkNotMintedItems($wpdb->get_results($query, ARRAY_A));
        
    }

    static private function checkNotMintedItems($res) {

        // check not minted items, and return p[post_id] in those cases
        $ret = [];
        if ($res and is_array($res)) {
            foreach ($res as $key => $value) {
                $val = $value["nft_id"];
                if (!$val) $val = "p" . $value["ID"];
                $ret[] = ["nft_id" => $val];
            }
        }

        return $ret;
    }

    static public function getNFTIds($collection_id) {

        $collection_id = (int) $collection_id;

        if ($collection_id==0) return [];

        global $wpdb;

        $query = "
        SELECT  p.ID, m2.meta_value as nft_id 
        FROM    $wpdb->postmeta m, 
                $wpdb->postmeta m2,
                $wpdb->posts p
        WHERE   m.post_id=m2.post_id AND 
                p.ID=m.post_id AND
                m.meta_value=$collection_id AND 
                m.meta_key=\"wpsc_item_collection\" AND 
                m2.meta_key=\"wpsc_nft_id\" AND
                p.post_status=\"publish\"";

        return self::checkNotMintedItems($wpdb->get_results($query, ARRAY_A));

    }

    static public function clearNFTTokenURI() {
        global $wpdb;
        $wpdb->query("DELETE FROM `$wpdb->options` WHERE option_name LIKE '%_transient_wpsc_nft_%'");
    }

    static public function getPostIDByMediaAndCollection($collection_id, $media_id) {
        global $wpdb;
        $res = $wpdb->get_results("
        SELECT  p.ID 
        FROM    $wpdb->postmeta col, 
                $wpdb->postmeta json, 
                $wpdb->posts p 
        WHERE   p.ID = col.post_id AND 
                p.post_status = \"publish\" AND 
                col.post_id = json.post_id AND 
                col.meta_key = \"wpsc_item_collection\" AND 
                col.meta_value = \"$collection_id\" AND 
                json.meta_key = \"wpsc_nft_media_json\" AND 
                json.meta_value LIKE '%\"id\": $media_id%'", ARRAY_A);
        
        if (is_array($res)) {
            return @$res[0]["ID"];
        }

        return false;

    }

    static public function validateLazyMinting($nft_id, $sign) {

        $nft_id = (int) $nft_id;
        if (!ctype_alnum($sign)) return false;

        global $wpdb;
        $query = "SELECT * FROM $wpdb->postmeta WHERE post_id = $nft_id AND meta_key = 'wpsc_nft_voucher_sign' AND meta_value = '$sign'";
        $res = $wpdb->get_results($query, ARRAY_A);
        if (empty($res)) return false;
        return true;

    }

    static public function getNextNFT($collection_id, $nft_id) {

        global $wpdb;

        // find next
        $query = $wpdb->prepare('SELECT post_id FROM ' . $wpdb->postmeta . ' WHERE meta_key = "wpsc_item_collection" and meta_value = %s and post_id > %d LIMIT 1', $collection_id, $nft_id);
        $res = $wpdb->get_row($query, ARRAY_A);
        
        if(is_array($res) and isset($res["post_id"])) return $res["post_id"];
        
        // find first
        $query = $wpdb->prepare('SELECT post_id FROM ' . $wpdb->postmeta . ' WHERE meta_key = "wpsc_item_collection" and meta_value = %s ORDER BY post_id LIMIT 1', $collection_id);
        $res = $wpdb->get_row($query, ARRAY_A);
        
        if(is_array($res) and isset($res["post_id"])) return $res["post_id"];
        
        return false;

    }

}
