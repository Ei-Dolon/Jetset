<?php
/**
* Partial functions
*
* @package Theme Palace
* @subpackage Next Travel
* @since Next Travel 1.0.0
*/

if ( ! function_exists( 'next_travel_slider_btn_label_partial' ) ) :
    // slider_btn_label
    function next_travel_slider_btn_label_partial() {
        $options = next_travel_get_theme_options();
        return esc_html( $options['slider_btn_label'] );
    }
endif;

if ( ! function_exists( 'next_travel_about_sub_title_partial' ) ) :
    /**
     * About us section Sub Title partial Refresh
     * @return String
     */
    function next_travel_about_sub_title_partial() {
        $options = next_travel_get_theme_options();
        return esc_html( $options['about_sub_title'] );
    }
endif;

if ( ! function_exists( 'next_travel_about_btn_title_partial' ) ) :
    // about btn title
    function next_travel_about_btn_title_partial() {
        $options = next_travel_get_theme_options();
        return esc_html( $options['about_btn_title'] );
    }
endif;

if ( ! function_exists( 'next_travel_gallery_slider_btn_title_partial' ) ) :
    // gallery_slider_btn_title
    function next_travel_gallery_slider_btn_title_partial($i) {
        $options = next_travel_get_theme_options();
        return esc_html( $options['gallery_slider_btn_title'] );
    }
endif;

if ( ! function_exists( 'next_travel_recommended_destination_sub_title_partial' ) ) :
    // recommended_destination_sub_title
    function next_travel_recommended_destination_sub_title_partial() {
        $options = next_travel_get_theme_options();
        return esc_html( $options['recommended_destination_sub_title'] );
    }
endif;

if ( ! function_exists( 'next_travel_recommended_destination_title_partial' ) ) :
    // recommended_destination_title
    function next_travel_recommended_destination_title_partial() {
        $options = next_travel_get_theme_options();
        return esc_html( $options['recommended_destination_title'] );
    }
endif;

if ( ! function_exists( 'next_travel_recommended_destination_description_partial' ) ) :
    // recommended destination description
    function next_travel_recommended_destination_description_partial() {
        $options = next_travel_get_theme_options();
        return esc_html( $options['recommended_destination_description'] );
    }
endif;

if ( ! function_exists( 'next_travel_recommended_destination_post_btn_label_partial' ) ) :
    function next_travel_recommended_destination_post_btn_label_partial() {
        $options = next_travel_get_theme_options();
        return esc_html( $options['recommended_destination_post_btn_label'] );
    }
endif;

if ( ! function_exists( 'next_travel_recommended_destination_btn_label_partial' ) ) :
    function next_travel_recommended_destination_btn_label_partial() {
        $options = next_travel_get_theme_options();
        return esc_html( $options['recommended_destination_btn_label'] );
    }
endif;

if ( ! function_exists( 'next_travel_blog_sub_title_partial' ) ) :
    // blog_sub_title
    function next_travel_blog_sub_title_partial() {
        $options = next_travel_get_theme_options();
        return esc_html( $options['blog_sub_title'] );
    }
endif;

if ( ! function_exists( 'next_travel_blog_title_partial' ) ) :
    // blog btn title
    function next_travel_blog_title_partial() {
        $options = next_travel_get_theme_options();
        return esc_html( $options['blog_title'] );
    }
endif;

if ( ! function_exists( 'next_travel_copyright_text_partial' ) ) :
    // copyright text
    function next_travel_copyright_text_partial() {
        $options = next_travel_get_theme_options();
        return esc_html( $options['copyright_text'] );
    }
endif;

if ( ! function_exists( 'next_travel_trip_search_title_partial' ) ) :
    // powered by text
    function next_travel_trip_search_title_partial() {
        $options = next_travel_get_theme_options();
        return esc_html( $options['trip_search_title'] );
    }
endif;
