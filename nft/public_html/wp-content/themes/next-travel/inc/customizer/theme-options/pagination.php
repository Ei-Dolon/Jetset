<?php
/**
 * pagination options
 *
 * @package Theme Palace
 * @subpackage Next Travel
 * @since Next Travel 1.0.0
 */

// Add sidebar section
$wp_customize->add_section( 'next_travel_pagination', array(
	'title'               => esc_html__('Pagination','next-travel'),
	'description'         => esc_html__( 'Pagination section options.', 'next-travel' ),
	'panel'               => 'next_travel_theme_options_panel',
) );

// Sidebar position setting and control.
$wp_customize->add_setting( 'next_travel_theme_options[pagination_enable]', array(
	'sanitize_callback' => 'next_travel_sanitize_switch_control',
	'default'             => $options['pagination_enable'],
) );

$wp_customize->add_control( new Next_Travel_Switch_Control( $wp_customize, 'next_travel_theme_options[pagination_enable]', array(
	'label'               => esc_html__( 'Pagination Enable', 'next-travel' ),
	'section'             => 'next_travel_pagination',
	'on_off_label' 		=> next_travel_switch_options(),
) ) );

// Site layout setting and control.
$wp_customize->add_setting( 'next_travel_theme_options[pagination_type]', array(
	'sanitize_callback'   => 'next_travel_sanitize_select',
	'default'             => $options['pagination_type'],
) );

$wp_customize->add_control( 'next_travel_theme_options[pagination_type]', array(
	'label'               => esc_html__( 'Pagination Type', 'next-travel' ),
	'section'             => 'next_travel_pagination',
	'type'                => 'select',
	'choices'			  => next_travel_pagination_options(),
	'active_callback'	  => 'next_travel_is_pagination_enable',
) );
