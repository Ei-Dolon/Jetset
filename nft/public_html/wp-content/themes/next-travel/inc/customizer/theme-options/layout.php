<?php
/**
 * Layout options
 *
 * @package Theme Palace
 * @subpackage Next Travel
 * @since Next Travel 1.0.0
 */

// Add sidebar section
$wp_customize->add_section( 'next_travel_layout', array(
	'title'               => esc_html__('Layout','next-travel'),
	'description'         => esc_html__( 'Layout section options.', 'next-travel' ),
	'panel'               => 'next_travel_theme_options_panel',
) );

// Site layout setting and control.
$wp_customize->add_setting( 'next_travel_theme_options[site_layout]', array(
	'sanitize_callback'   => 'next_travel_sanitize_select',
	'default'             => $options['site_layout'],
) );

$wp_customize->add_control(  new Next_Travel_Custom_Radio_Image_Control ( $wp_customize, 'next_travel_theme_options[site_layout]', array(
	'label'               => esc_html__( 'Site Layout', 'next-travel' ),
	'section'             => 'next_travel_layout',
	'choices'			  => next_travel_site_layout(),
) ) );

// Sidebar position setting and control.
$wp_customize->add_setting( 'next_travel_theme_options[sidebar_position]', array(
	'sanitize_callback'   => 'next_travel_sanitize_select',
	'default'             => $options['sidebar_position'],
) );

$wp_customize->add_control(  new Next_Travel_Custom_Radio_Image_Control ( $wp_customize, 'next_travel_theme_options[sidebar_position]', array(
	'label'               => esc_html__( 'Global Sidebar Position', 'next-travel' ),
	'section'             => 'next_travel_layout',
	'choices'			  => next_travel_global_sidebar_position(),
) ) );

// Post sidebar position setting and control.
$wp_customize->add_setting( 'next_travel_theme_options[post_sidebar_position]', array(
	'sanitize_callback'   => 'next_travel_sanitize_select',
	'default'             => $options['post_sidebar_position'],
) );

$wp_customize->add_control(  new Next_Travel_Custom_Radio_Image_Control ( $wp_customize, 'next_travel_theme_options[post_sidebar_position]', array(
	'label'               => esc_html__( 'Posts Sidebar Position', 'next-travel' ),
	'section'             => 'next_travel_layout',
	'choices'			  => next_travel_sidebar_position(),
) ) );

// Post sidebar position setting and control.
$wp_customize->add_setting( 'next_travel_theme_options[page_sidebar_position]', array(
	'sanitize_callback'   => 'next_travel_sanitize_select',
	'default'             => $options['page_sidebar_position'],
) );

$wp_customize->add_control( new Next_Travel_Custom_Radio_Image_Control( $wp_customize, 'next_travel_theme_options[page_sidebar_position]', array(
	'label'               => esc_html__( 'Pages Sidebar Position', 'next-travel' ),
	'section'             => 'next_travel_layout',
	'choices'			  => next_travel_sidebar_position(),
) ) );