<?php
/**
 * Breadcrumb options
 *
 * @package Theme Palace
 * @subpackage Swingpress
 * @since Swingpress 1.0.0
 */

$wp_customize->add_section( 'swingpress_breadcrumb', array(
	'title'             => esc_html__( 'Breadcrumb','swingpress' ),
	'description'       => esc_html__( 'Breadcrumb section options.', 'swingpress' ),
	'panel'             => 'swingpress_theme_options_panel',
) );

// Breadcrumb enable setting and control.
$wp_customize->add_setting( 'swingpress_theme_options[breadcrumb_enable]', array(
	'sanitize_callback' => 'swingpress_sanitize_switch_control',
	'default'          	=> $options['breadcrumb_enable'],
) );

$wp_customize->add_control( new Swingpress_Switch_Control( $wp_customize, 'swingpress_theme_options[breadcrumb_enable]', array(
	'label'            	=> esc_html__( 'Enable Breadcrumb', 'swingpress' ),
	'section'          	=> 'swingpress_breadcrumb',
	'on_off_label' 		=> swingpress_switch_options(),
) ) );

// Breadcrumb separator setting and control.
$wp_customize->add_setting( 'swingpress_theme_options[breadcrumb_separator]', array(
	'sanitize_callback'	=> 'sanitize_text_field',
	'default'          	=> $options['breadcrumb_separator'],
) );

$wp_customize->add_control( 'swingpress_theme_options[breadcrumb_separator]', array(
	'label'            	=> esc_html__( 'Separator', 'swingpress' ),
	'active_callback' 	=> 'swingpress_is_breadcrumb_enable',
	'section'          	=> 'swingpress_breadcrumb',
) );
