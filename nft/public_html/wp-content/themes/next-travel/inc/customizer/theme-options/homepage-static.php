<?php
/**
* Homepage (Static ) options
*
* @package Theme Palace
* @subpackage Next Travel
* @since Next Travel 1.0.0
*/

// Homepage (Static ) setting and control.
$wp_customize->add_setting( 'next_travel_theme_options[enable_frontpage_content]', array(
	'sanitize_callback'   => 'next_travel_sanitize_checkbox',
	'default'             => $options['enable_frontpage_content'],
) );

$wp_customize->add_control( 'next_travel_theme_options[enable_frontpage_content]', array(
	'label'       	=> esc_html__( 'Enable Content', 'next-travel' ),
	'description' 	=> esc_html__( 'Check to enable content on static front page only.', 'next-travel' ),
	'section'     	=> 'static_front_page',
	'type'        	=> 'checkbox',
	'active_callback' => 'next_travel_is_static_homepage_enable',
) );