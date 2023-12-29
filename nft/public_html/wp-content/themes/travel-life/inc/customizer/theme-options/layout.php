<?php
/**
 * Layout options
 *
 * @package Theme Palace
 * @subpackage Travel Life
 * @since Travel Life 1.0.0
 */

// Add sidebar section
$wp_customize->add_section( 'travel_life_layout', array(
	'title'               => esc_html__('Layout','travel-life'),
	'description'         => esc_html__( 'Layout section options.', 'travel-life' ),
	'panel'               => 'travel_life_theme_options_panel',
) );

// Site layout setting and control.
$wp_customize->add_setting( 'travel_life_theme_options[site_layout]', array(
	'sanitize_callback'   => 'travel_life_sanitize_select',
	'default'             => $options['site_layout'],
) );

$wp_customize->add_control(  new Travel_Life_Custom_Radio_Image_Control ( $wp_customize, 'travel_life_theme_options[site_layout]', array(
	'label'               => esc_html__( 'Site Layout', 'travel-life' ),
	'section'             => 'travel_life_layout',
	'choices'			  => travel_life_site_layout(),
) ) );

// Sidebar position setting and control.
$wp_customize->add_setting( 'travel_life_theme_options[sidebar_position]', array(
	'sanitize_callback'   => 'travel_life_sanitize_select',
	'default'             => $options['sidebar_position'],
) );

$wp_customize->add_control(  new Travel_Life_Custom_Radio_Image_Control ( $wp_customize, 'travel_life_theme_options[sidebar_position]', array(
	'label'               => esc_html__( 'Global Sidebar Position', 'travel-life' ),
	'section'             => 'travel_life_layout',
	'choices'			  => travel_life_global_sidebar_position(),
) ) );

// Post sidebar position setting and control.
$wp_customize->add_setting( 'travel_life_theme_options[post_sidebar_position]', array(
	'sanitize_callback'   => 'travel_life_sanitize_select',
	'default'             => $options['post_sidebar_position'],
) );

$wp_customize->add_control(  new Travel_Life_Custom_Radio_Image_Control ( $wp_customize, 'travel_life_theme_options[post_sidebar_position]', array(
	'label'               => esc_html__( 'Posts Sidebar Position', 'travel-life' ),
	'section'             => 'travel_life_layout',
	'choices'			  => travel_life_sidebar_position(),
) ) );

// Post sidebar position setting and control.
$wp_customize->add_setting( 'travel_life_theme_options[page_sidebar_position]', array(
	'sanitize_callback'   => 'travel_life_sanitize_select',
	'default'             => $options['page_sidebar_position'],
) );

$wp_customize->add_control( new Travel_Life_Custom_Radio_Image_Control( $wp_customize, 'travel_life_theme_options[page_sidebar_position]', array(
	'label'               => esc_html__( 'Pages Sidebar Position', 'travel-life' ),
	'section'             => 'travel_life_layout',
	'choices'			  => travel_life_sidebar_position(),
) ) );