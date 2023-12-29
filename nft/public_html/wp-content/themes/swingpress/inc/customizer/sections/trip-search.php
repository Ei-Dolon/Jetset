<?php
/**
 * Trip Search Section options
 *
 * @package Theme Palace
 * @subpackage Swingpress
 * @since Swingpress 1.0.0
 */

if ( !class_exists( 'WP_Travel' ) ) {
	return;
}

// Add Trip Search section
$wp_customize->add_section( 'swingpress_trip_search_section', array(
	'title'             => esc_html__( 'Trip Search','swingpress' ),
	'panel'             => 'swingpress_front_page_panel',
) );

// Trip Search content enable control and setting
$wp_customize->add_setting( 'swingpress_theme_options[trip_search_section_enable]', array(
	'default'			=> 	$options['trip_search_section_enable'],
	'sanitize_callback' => 'swingpress_sanitize_switch_control',
) );

$wp_customize->add_control( new Swingpress_Switch_Control( $wp_customize, 'swingpress_theme_options[trip_search_section_enable]', array(
	'label'             => esc_html__( 'Trip Search Section Enable', 'swingpress' ),
	'section'           => 'swingpress_trip_search_section',
	'on_off_label' 		=> swingpress_switch_options(),
) ) );

