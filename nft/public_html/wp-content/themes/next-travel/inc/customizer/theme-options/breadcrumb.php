<?php
/**
 * Breadcrumb options
 *
 * @package Theme Palace
 * @subpackage Next Travel
 * @since Next Travel 1.0.0
 */

$wp_customize->add_section( 'next_travel_breadcrumb', array(
	'title'             => esc_html__( 'Breadcrumb','next-travel' ),
	'description'       => esc_html__( 'Breadcrumb section options.', 'next-travel' ),
	'panel'             => 'next_travel_theme_options_panel',
) );

// Breadcrumb enable setting and control.
$wp_customize->add_setting( 'next_travel_theme_options[breadcrumb_enable]', array(
	'sanitize_callback' => 'next_travel_sanitize_switch_control',
	'default'          	=> $options['breadcrumb_enable'],
) );

$wp_customize->add_control( new Next_Travel_Switch_Control( $wp_customize, 'next_travel_theme_options[breadcrumb_enable]', array(
	'label'            	=> esc_html__( 'Enable Breadcrumb', 'next-travel' ),
	'section'          	=> 'next_travel_breadcrumb',
	'on_off_label' 		=> next_travel_switch_options(),
) ) );

// Breadcrumb separator setting and control.
$wp_customize->add_setting( 'next_travel_theme_options[breadcrumb_separator]', array(
	'sanitize_callback'	=> 'sanitize_text_field',
	'default'          	=> $options['breadcrumb_separator'],
) );

$wp_customize->add_control( 'next_travel_theme_options[breadcrumb_separator]', array(
	'label'            	=> esc_html__( 'Separator', 'next-travel' ),
	'active_callback' 	=> 'next_travel_is_breadcrumb_enable',
	'section'          	=> 'next_travel_breadcrumb',
) );
