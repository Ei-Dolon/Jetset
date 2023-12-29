<?php
/**
* Homepage (Static ) options
*
* @package Theme Palace
* @subpackage Swingpress
* @since Swingpress 1.0.0
*/

// Homepage (Static ) setting and control.
$wp_customize->add_setting( 'swingpress_theme_options[enable_frontpage_content]', array(
	'sanitize_callback'   => 'swingpress_sanitize_checkbox',
	'default'             => $options['enable_frontpage_content'],
) );

$wp_customize->add_control( 'swingpress_theme_options[enable_frontpage_content]', array(
	'label'       	=> esc_html__( 'Enable Content', 'swingpress' ),
	'description' 	=> esc_html__( 'Check to enable content on static front page only.', 'swingpress' ),
	'section'     	=> 'static_front_page',
	'type'        	=> 'checkbox',
	'active_callback' => 'swingpress_is_static_homepage_enable',
) );