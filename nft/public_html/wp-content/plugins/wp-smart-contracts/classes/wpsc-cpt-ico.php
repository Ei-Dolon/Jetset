<?php

if( ! defined( 'ABSPATH' ) ) die;

/**
 * Flush permalinks after first ICO save
 */ 
add_action( 'save_post', function ( $post_id, $post, $update ) {

    $post_type = get_post_type($post_id);

    // If this isn't a 'coin' post, don't update it.
    if ( "ico" != $post_type ) return;

    $option_name = "ICO_permalinks_flushed";

    if (!get_option($option_name)) {

        // clean up permalink settings
        flush_rewrite_rules();

        // flush only once
        update_option($option_name, true);

    }

}, 10, 3 );

/**
 * Load help metaboxes for ICO cpt
 */
require_once("wpsc-metabox-ico.php");

/**
 * Create ICO Post Type
 */

new WPSC_ICOCPT();

class WPSC_ICOCPT {

    // Define the ICO CPT
    function __construct() {

        // create ICO
        add_action( 'init', [$this, 'initialize'] );
    
        // add extra columns to ICO view
        add_filter( 'manage_ico_posts_columns', [$this, 'setCustomEditICOColumns'] );
        add_action( 'manage_ico_posts_custom_column' , [$this, 'customICOColumn'], 10, 2 );

        // add column styles
        add_action('admin_head', [$this, 'myThemeAdminHead']);

        // Create a template view for the new CPT
        add_filter('single_template', [$this, 'setTemplateICO'] );

    }

    // Create ICOs CPT
    public function initialize() {

        $labels = array(
            'name'               => _x( 'ICOs', 'post type general name', 'wp-smart-contracts' ),
            'singular_name'      => _x( 'ICO', 'post type singular name', 'wp-smart-contracts' ),
            'menu_name'          => _x( 'ICOs', 'admin menu', 'wp-smart-contracts' ),
            'name_admin_bar'     => _x( 'ICO', 'add new on admin bar', 'wp-smart-contracts' ),
            'add_new'            => _x( 'Add New ICO', 'ICO', 'wp-smart-contracts' ),
            'add_new_item'       => __( 'Add New ICO', 'wp-smart-contracts' ),
            'new_item'           => __( 'New ICO', 'wp-smart-contracts' ),
            'edit_item'          => __( 'Edit ICO', 'wp-smart-contracts' ),
            'view_item'          => __( 'View ICO', 'wp-smart-contracts' ),
            'all_items'          => __( 'All ICO', 'wp-smart-contracts' ),
            'search_items'       => __( 'Search ICO', 'wp-smart-contracts' ),
            'parent_item_colon'  => __( 'Parent ICO:', 'wp-smart-contracts' ),
            'not_found'          => __( 'No ICOs found.', 'wp-smart-contracts' ),
            'not_found_in_trash' => __( 'No ICOs found in Trash.', 'wp-smart-contracts' )
        );

        $args = array(
            'labels'             => $labels,
            'description'        => __( 'Description.', 'wp-smart-contracts' ),
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'menu_icon'          => plugin_dir_url( dirname( __FILE__ ) ) . 'assets/img/icon-ico.png',
            'query_var'          => true,
            'rewrite'            => array( 'slug' => 'icos' ),
            'capability_type'    => 'page',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => null,
            'supports'           => array( 'title', 'editor', 'thumbnail' )
        );

        register_post_type( 'ico', $args );

    }

    public function setTemplateICO ($template) {
        global $post;
        if ( $post->post_type == 'ico' ) {
            return self::generateCustomTemplate('ico.php');
        }
        return $template;
    }

    // Define column headers in the CPT list
    public function setCustomEditICOColumns($columns) {
    
        unset($columns['date']);   // remove date from the columns list

        $columns['token'] = __( 'Token', 'wp-smart-contracts' );
        $columns['smart-contract'] = __( 'Smart Contract', 'wp-smart-contracts' );
        $columns['network'] = __( 'Network', 'wp-smart-contracts' );
        $columns['flavor'] = __( 'Flavor', 'wp-smart-contracts' );
        $columns['date'] = __( 'Date', 'wp-smart-contracts' ); // now add date to the end

        return $columns;

    }

    // Define the values of each column in the CPT list
    public function customICOColumn( $column, $post_id ) {

        $m = new Mustache_Engine;

        switch ( $column ) {
            case 'token' :

                if ( $wpsc_network = get_post_meta(get_the_ID(), 'wpsc_network', true) ) {
                    list($color, $icon, $etherscan, $network_val) = WPSC_Metabox::getNetworkInfo($wpsc_network);
                }

                if ($contract = get_post_meta($post_id, 'wpsc_token', true)) {

                    $atts['contract'] = $contract;
                    $atts['short-contract'] = substr($contract, 0, 8) . '...' . substr($contract, -6);
                    $atts['id'] = $post_id;

                    if ($blockie = get_post_meta($post_id, 'wpsc_blockie_token', true)) {
                        $atts['blockie'] = $blockie;
                    }
                    if ($qr = get_post_meta($post_id, 'wpsc_qr_code_token', true)) {
                        $atts['qr-code'] = $qr;
                    }
                    $atts["etherscan"] = isset($etherscan)?$etherscan:"";
                    echo $m->render(WPSC_Mustache::getTemplate('contract-identicons-be'), $atts);
                } else {
                    echo '';
                }
                break;

            case 'smart-contract' :

                if ( $wpsc_network = get_post_meta(get_the_ID(), 'wpsc_network', true) ) {
                    list($color, $icon, $etherscan, $network_val) = WPSC_Metabox::getNetworkInfo($wpsc_network);
                }

                if ($contract = get_post_meta($post_id, 'wpsc_contract_address', true)) {

                    $atts['contract'] = $contract;
                    $atts['short-contract'] = substr($contract, 0, 8) . '...' . substr($contract, -6);
                    $atts['id'] = $post_id;

                    if ($blockie = get_post_meta($post_id, 'wpsc_blockie', true)) {
                        $atts['blockie'] = $blockie;
                    }
                    if ($qr = get_post_meta($post_id, 'wpsc_qr_code', true)) {
                        $atts['qr-code'] = $qr;
                    }
                    $atts["etherscan"] = $etherscan;
                    echo $m->render(WPSC_Mustache::getTemplate('contract-identicons-be'), $atts);
                } else {
                    echo '';
                }
                break;

            case 'network':
                if ( $wpsc_network = get_post_meta(get_the_ID(), 'wpsc_network', true) ) {
                    list($color, $icon, $etherscan, $network_val) = WPSC_Metabox::getNetworkInfo($wpsc_network);
                    echo $m->render(WPSC_Mustache::getTemplate('network'), 
                        [
                            "color" => $color,
                            "network_val" => $network_val,
                        ]
                    );
                } else {
                    echo "";
                }

                break;

            case 'flavor' :

                if ( $wpsc_flavor = get_post_meta(get_the_ID(), 'wpsc_flavor', true) ) {

                    $color = WPSC_helpers::getFlavorColor($wpsc_flavor);

                    echo $m->render('<a class="ui {{color}} label"><span class="wpsc-capitalize">{{flavor}}</span></a>', 
                    [
                        'color' => $color,
                        'flavor' => $wpsc_flavor,
                    ]);

                } else {
                    echo "";
                }
                break;

        }

    }

    // define the size of specific columns in the CPT list
    public function myThemeAdminHead() {
        global $post_type;
        if ( 'ico' == $post_type ) {
            ?>
            <style type="text/css">
                .column-smart-contract, .column-token { width: 25%; } 
            </style>
            <?php
        }
    }

    public static function generateCustomTemplate($php_file) {
        $the_file = dirname(__FILE__);
        $wpsc_plugin_path = plugin_dir_path($the_file, basename(dirname($the_file)) . '/' . basename($the_file)) . $php_file;
        if ( file_exists( $wpsc_plugin_path ) ) {
            return $wpsc_plugin_path;
        }
    }

}

