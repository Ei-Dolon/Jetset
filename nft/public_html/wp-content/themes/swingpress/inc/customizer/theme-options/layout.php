<?php
/**
 * Layout options
 *
 * @package Theme Palace
 * @subpackage Swingpress
 * @since Swingpress 1.0.0
 */

// Add sidebar section
$wp_customize->add_section( 'swingpress_layout', array(
	'title'               => esc_html__('Layout','swingpress'),
	'description'         => esc_html__( 'Layout section options.', 'swingpress' ),
	'panel'               => 'swingpress_theme_options_panel',
) );

// Site layout setting and control.
$wp_customize->add_setting( 'swingpress_theme_options[site_layout]', array(
	'sanitize_callback'   => 'swingpress_sanitize_select',
	'default'             => $options['site_layout'],
) );

$wp_customize->add_control(  new Swingpress_Custom_Radio_Image_Control ( $wp_customize, 'swingpress_theme_options[site_layout]', array(
	'label'               => esc_html__( 'Site Layout', 'swingpress' ),
	'section'             => 'swingpress_layout',
	'choices'			  => swingpress_site_layout(),
) ) );

// Sidebar position setting and control.
$wp_customize->add_setting( 'swingpress_theme_options[sidebar_position]', array(
	'sanitize_callback'   => 'swingpress_sanitize_select',
	'default'             => $options['sidebar_position'],
) );

$wp_customize->add_control(  new Swingpress_Custom_Radio_Image_Control ( $wp_customize, 'swingpress_theme_options[sidebar_position]', array(
	'label'               => esc_html__( 'Global Sidebar Position', 'swingpress' ),
	'section'             => 'swingpress_layout',
	'choices'			  => swingpress_global_sidebar_position(),
) ) );

// Post sidebar position setting and control.
$wp_customize->add_setting( 'swingpress_theme_options[post_sidebar_position]', array(
	'sanitize_callback'   => 'swingpress_sanitize_select',
	'default'             => $options['post_sidebar_position'],
) );

$wp_customize->add_control(  new Swingpress_Custom_Radio_Image_Control ( $wp_customize, 'swingpress_theme_options[post_sidebar_position]', array(
	'label'               => esc_html__( 'Posts Sidebar Position', 'swingpress' ),
	'section'             => 'swingpress_layout',
	'choices'			  => swingpress_sidebar_position(),
) ) );

// Post sidebar position setting and control.
$wp_customize->add_setting( 'swingpress_theme_options[page_sidebar_position]', array(
	'sanitize_callback'   => 'swingpress_sanitize_select',
	'default'             => $options['page_sidebar_position'],
) );

$wp_customize->add_control( new Swingpress_Custom_Radio_Image_Control( $wp_customize, 'swingpress_theme_options[page_sidebar_position]', array(
	'label'               => esc_html__( 'Pages Sidebar Position', 'swingpress' ),
	'section'             => 'swingpress_layout',
	'choices'			  => swingpress_sidebar_position(),
) ) );