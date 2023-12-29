<?php

if( ! defined( 'ABSPATH' ) ) die;

/**
 * Create NFT Galleries
 */

new WPSC_NFTGallery();

class WPSC_NFTGallery {

    function __construct() {

        add_action( 'show_user_profile', [$this, 'extra_user_profile_fields'] );
        add_action( 'edit_user_profile', [$this, 'extra_user_profile_fields'] );

        add_action( 'personal_options_update', [$this, 'save_extra_user_profile_fields'] );
        add_action( 'edit_user_profile_update', [$this, 'save_extra_user_profile_fields'] );

        add_action( 'admin_init', function(){
            add_action("nft-gallery_pre_add_form", [$this, 'tax_message1']);
            add_action("nft-gallery_term_edit_form_top", [$this, 'tax_message1']);
            add_action('admin_notices', [$this, 'tax_message2']);
        });

        add_action( "delete_nft-gallery", [$this, 'delete_gallery_hook'] );

    }

    static public function db_table_name() {
        global $wpdb;    
        return $wpdb->prefix . 'wpsc_galleries';
    }

    static public function db_check() {
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        $tablename = self::db_table_name(); 
        $sql = "CREATE TABLE $tablename (`user_id` int NOT NULL, `data` longtext NOT NULL, PRIMARY KEY (`user_id`) ) $charset_collate";
        @$result = maybe_create_table( $tablename, $sql );
        return $result;
    }
    
    static public function get_user_record($user_id) {
        global $wpdb;
        $user_id = (int) $user_id;
        if (!$user_id) return false;
        $data = $wpdb->get_results("SELECT data FROM `".self::db_table_name()."` WHERE user_id=" . $user_id, ARRAY_A);
        @$ret = json_decode($data[0]["data"], true);
        return $ret;
    } 

    public function tax_message1() {
        $screen = get_current_screen();
        if ($screen->base == 'edit-tags' || $screen->base == 'term') {
            echo WPSC_helpers::userGalleriesMessage();
        }
    }

    public function tax_message2() {
        global $pagenow;
        if ( $pagenow == 'options-general.php' and WPSC_helpers::valArrElement($_GET, "page") and $_GET["page"] == "etherscan-api-key-setting-admin" ) {
            echo WPSC_helpers::userGalleriesMessage();
        }
    }

    public function delete_gallery_hook( $term ) { 
        self::removeAllGalleriesFromUser($term);
    }

    public function extra_user_profile_fields( $user ) { ?>

        <div class="ui compact segment">

        <h3><?=__("NFT User Galleries", "wp-smart-contracts")?></h3>

        <div class="ui bottom attached info message">
            <p><i class="info circle icon"></i> <a href="<?=admin_url("edit-tags.php?taxonomy=nft-gallery&post_type=nft")?>"><?=__("Click here to add, change or remove galleries", "wp-smart-contracts")?></a></p>
        </div>

        <?php

            $galleries = self::get($user->ID);

            if (is_array($galleries)) {
                $the_value = implode(',', $galleries);
            } else {
                $the_value = '';
            }

            $m = new Mustache_Engine;
            echo $m->render(WPSC_Mustache::getTemplate('wpsc-table-galleries'), [
                "the-user-has-no-galleries" => __('The user has no galleries.', 'wp-smart-contracts'),
                "add-new-galleries-comma-separated" => __('Add New Galleries (comma separated)', 'wp-smart-contracts'),
                "do-you-want-to-add-new-galleries" => __('Do you want to add new galleries?', 'wp-smart-contracts'),
                "add-them-on-a-comma-separated-list-here" => __('Add them on a comma-separated list here.', 'wp-smart-contracts'),
                "title"=>__("User Galleries", "wp-smart-contracts"),
                "id"=>"wpsc-table-in",
                "id_term"=>"wpsc-term-in",
                "galleries"=>self::get($user->ID, true),
                "title2"=>__("Add gallery to the user", "wp-smart-contracts"),
                "id2"=>"wpsc-table-out",
                "id_term2"=>"wpsc-term-out",
                "galleries2"=>get_terms(['taxonomy' => "nft-gallery", 'hide_empty' => false, "exclude" => $galleries]),
                "the-hidden-value"=>$the_value
            ]);
        ?>

        </div>

        <?php

    }

    public function save_extra_user_profile_fields( $user_id ) {

        if ( empty( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'update-user_' . $user_id ) ) {
            return;
        }
        
        if ( !current_user_can( 'edit_user', $user_id ) ) { 
            return false; 
        }
        
        WPSC_NFTGallery::removeAll($user_id);

        if ($wpsc_input_galleries = $_POST["wpsc-galleries"]) {
            $wpsc_input_galleries_arr = explode(",", $wpsc_input_galleries);
            if (is_array($wpsc_input_galleries_arr)) {
                foreach($wpsc_input_galleries_arr as $gallery_id) {
                    $gallery_id = (int) $gallery_id;
                    if ($gallery_id) {
                        WPSC_NFTGallery::add($user_id, $gallery_id);
                    }
                }
            }
        }

    }

    /**
     * get: return the galleries of a user
     * @param: user_id: the numeric user id
     * @param: $terms: a boolean that indicates if the full terms data of the galleries will be returned
     * @param: $nft_id: an optional NFT post id to mark galleries of this user as selected for an  nft_id, it is ignored if 0 is passed
     */
    static public function get($user_id, $terms=false, $nft_id=0) {
        $galleries = self::get_user_record($user_id);
        if ($nft_id) {
            $nft_galleries = wp_get_post_terms( $nft_id, 'nft-gallery', array( 'fields' => 'ids' ) );
        } else {
            $nft_galleries = [];
        }
        if ($terms) {
            if (is_array($galleries)) {
                $ret = [];
                foreach($galleries as $g) {
                    $term = get_term_by('id', $g, "nft-gallery");
                    if ($term) {
                        $record = (array) $term;
                        if (array_search($term->term_id, $nft_galleries)!==false) {
                            $record["selected"]=true;
                        }
                        $record["base-link"] = get_term_link($g);
                        $ret[] = $record;
                    }
                }
                return $ret;
            }    
        } else {
            return $galleries;
        }
    }

    static public function set($user_id, $galleries) {
        global $wpdb;
        $wpdb->query( $wpdb->prepare( "REPLACE `".self::db_table_name()."` (`user_id`, `data`) VALUES (%d, %s)", $user_id, json_encode($galleries) ) );
    }

    static public function add($user_id, $gallery_id) {

        $term = term_exists( $gallery_id, 'nft-gallery' );
        if ( $term !== 0 && $term !== null ) {
            $galleries = self::get($user_id, null, null);
            $galleries[] = $gallery_id;
            self::set($user_id, $galleries); 
        }

    }

    static public function remove($user_id, $gallery_id) {
        $galleries = self::get($user_id);
        $pos = array_search($gallery_id, $galleries);
        if ($pos!==false) {
            unset($galleries[$pos]);
            $galleries = array_values($galleries);    
            self::set($user_id, $galleries); 
        }
    }

    static public function removeAllGalleriesFromUser($gallery_id) {
        $gallery_id = (int) $gallery_id;
        if (!$gallery_id) return;
        $user_ids = WPSC_Queries::getUsersWithGallery($gallery_id);
        if (!is_array($user_ids)) return;
        $removed = false;
        foreach($user_ids as $uid) {
            if (WPSC_helpers::valArrElement($uid, "user_id")) {
                self::remove($uid["user_id"], $gallery_id);
                $removed = true;
            }
        }
        if ($removed) {
            WPSC_Endpoints::clearCacheForNFTEndpoints();
        }
    }

    static public function removeAll($user_id) {
        $galleries = self::get($user_id);
        self::set($user_id, []); 
    }

    static public function createGalleryForUserID($user_id, $gallery_name) {

        // calculate the slug
        // do not add trailing numbers if not neccesary
        $base_slug = get_user_by('id', $user_id)->user_nicename . "-" . sanitize_text_field($gallery_name);
        // loop until a free slug is found
        $i=1;
        $slug = $base_slug;
        while ($term = get_term_by('slug', $slug, "nft-gallery")) {
            $i++;
            $slug = $base_slug . "-" . $i;
        }
        // create a new gallery item
        $res = wp_insert_term($gallery_name, "nft-gallery", ["slug"=>$slug]);
        if (!is_wp_error($res) and $term_id = $res["term_id"]) {
        
            // assign the new gallery to the user
            WPSC_NFTGallery::add($user_id, $term_id);
            return $term_id;
        }
        
    }

}