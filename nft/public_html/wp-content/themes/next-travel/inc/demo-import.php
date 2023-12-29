<?php
/**
 * Demo Import.
 *
 * This is the template that includes all the other files for core featured of Theme Palace
 *
 * @package Theme Palace
 * @subpackage next-travel 
 * @since next-travel  1.0.0
 */

function next_travel_ctdi_plugin_page_setup( $default_settings ) {
    $default_settings['menu_title']  = esc_html__( 'Theme Palace Demo Import' , 'next-travel' );

    return $default_settings;
}
add_filter( 'cp-ctdi/plugin_page_setup', 'next_travel_ctdi_plugin_page_setup' );


function next_travel_ctdi_plugin_intro_text( $default_text ) {
    $default_text .= sprintf( '<p class="about-description">%1$s <a href="%2$s">%3$s</a></p>', esc_html__( 'Demo content files for next-travel Theme.', 'next-travel' ),
    esc_url( 'https://themepalace.com/instructions/themes/next-travel' ), esc_html__( 'Click here for Demo File download', 'next-travel' ) );
    return $default_text;
}
add_filter( 'cp-ctdi/plugin_intro_text', 'next_travel_ctdi_plugin_intro_text' );