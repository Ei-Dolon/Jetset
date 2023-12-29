<?php
/**
* Homepage (Static ) options
*
* @package Theme Palace
* @subpackage Travel Life
* @since Travel Life 1.0.0
*/

// Homepage (Static ) setting and control.
$wp_customize->add_setting( 'travel_life_theme_options[enable_frontpage_content]', array(
	'sanitize_callback'   => 'travel_life_sanitize_checkbox',
	'default'             => $options['enable_frontpage_content'],
) );

$wp_customize->add_control( 'travel_life_theme_options[enable_frontpage_content]', array(
	'label'       	=> esc_html__( 'Enable Content', 'travel-life' ),
	'description' 	=> esc_html__( 'Check to enable content on static front page only.', 'travel-life' ),
	'section'     	=> 'static_front_page',
	'type'        	=> 'checkbox',
	'active_callback' => 'travel_life_is_static_homepage_enable',
) );