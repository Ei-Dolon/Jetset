<?php
/**
* Partial functions
*
* @package Theme Palace
* @subpackage Swingpress
* @since Swingpress 1.0.0
*/

//Featured Slider 
if ( ! function_exists( 'swingpress_slider_btn_label_partial' ) ) :
    // slider sub label
    function swingpress_slider_btn_label_partial() {
        $options = swingpress_get_theme_options();
        return esc_html( $options['slider_btn_label'] );
    }
endif;

//service
if ( ! function_exists( 'swingpress_service_title_partial' ) ) :
    // slider sub label
    function swingpress_service_title_partial() {
        $options = swingpress_get_theme_options();
        return esc_html( $options['service_title'] );
    }
endif;

if ( ! function_exists( 'swingpress_service_sub_title_partial' ) ) :
    // slider sub label
    function swingpress_service_sub_title_partial() {
        $options = swingpress_get_theme_options();
        return esc_html( $options['service_sub_title'] );
    }
endif;


//featured
if ( ! function_exists( 'swingpress_featured_title_partial' ) ) :
    // featured title
    function swingpress_featured_title_partial() {
        $options = swingpress_get_theme_options();
        return swingpress_santize_allow_tag( $options['featured_title'] );
    }
endif;

if ( ! function_exists( 'swingpress_featured_sub_title_partial' ) ) :
    // featured title
    function swingpress_featured_sub_title_partial() {
        $options = swingpress_get_theme_options();
        return swingpress_santize_allow_tag( $options['featured_sub_title'] );
    }
endif;

//package 

if ( ! function_exists( 'swingpress_package_title_partial' ) ) :
    // package title
    function swingpress_package_title_partial() {
        $options = swingpress_get_theme_options();
        return esc_html( $options['package_title'] );
    }
endif;

if ( ! function_exists( 'swingpress_package_sub_title_partial' ) ) :
    // package sub title
    function swingpress_package_sub_title_partial() {
        $options = swingpress_get_theme_options();
        return esc_html( $options['package_sub_title'] );
    }
endif;

//about 
if ( ! function_exists( 'swingpress_about_btn_title_partial' ) ) :
    // about btn title
    function swingpress_about_btn_title_partial() {
        $options = swingpress_get_theme_options();
        return esc_html( $options['about_btn_title'] );
    }
endif;

// popular destination
if ( ! function_exists( 'swingpress_popular_destination_title_partial' ) ) :
    // popular destination title
    function swingpress_popular_destination_title_partial() {
        $options = swingpress_get_theme_options();
        return esc_html( $options['popular_destination_title'] );
    }
endif;

if ( ! function_exists( 'swingpress_popular_destination_sub_title_partial' ) ) :
    // popular destination sub title
    function swingpress_popular_destination_sub_title_partial() {
        $options = swingpress_get_theme_options();
        return esc_html( $options['popular_destination_sub_title'] );
    }
endif;

//blog 


if ( ! function_exists( 'swingpress_blog_title_partial' ) ) :
    // blog title
    function swingpress_blog_title_partial() {
        $options = swingpress_get_theme_options();
        return esc_html( $options['blog_title'] );
    }
endif;

if ( ! function_exists( 'swingpress_blog_sub_title_partial' ) ) :
    // blog title
    function swingpress_blog_sub_title_partial() {
        $options = swingpress_get_theme_options();
        return esc_html( $options['blog_sub_title'] );
    }
endif;

if ( ! function_exists( 'swingpress_blog_btn_title_partial' ) ) :
    // blog btn title
    function swingpress_blog_btn_title_partial() {
        $options = swingpress_get_theme_options();
        return esc_html( $options['blog_btn_title'] );
    }
endif;

if ( ! function_exists( 'swingpress_blog_alt_btn_title_partial' ) ) :
    // blog btn title
    function swingpress_blog_alt_btn_title_partial() {
        $options = swingpress_get_theme_options();
        return esc_html( $options['blog_alt_btn_title'] );
    }
endif;

if ( ! function_exists( 'swingpress_copyright_text_partial' ) ) :
    // copyright text
    function swingpress_copyright_text_partial() {
        $options = swingpress_get_theme_options();
        return esc_html( $options['copyright_text'] );
    }
endif;

if ( ! function_exists( 'swingpress_powered_by_text_partial' ) ) :
    // powered by text
    function swingpress_powered_by_text_partial() {
        $options = swingpress_get_theme_options();
        return swingpress_santize_allow_tag( $options['powered_by_text'] );
    }
endif;
