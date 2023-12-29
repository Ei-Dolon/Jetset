<?php
/**
 * Excerpt options
 *
 * @package Theme Palace
 * @subpackage Swingpress
 * @since Swingpress 1.0.0
 */

// Add excerpt section
$wp_customize->add_section( 'swingpress_single_post_section', array(
	'title'             => esc_html__( 'Single Post','swingpress' ),
	'description'       => esc_html__( 'Options to change the single posts globally.', 'swingpress' ),
	'panel'             => 'swingpress_theme_options_panel',
) );

// Tourableve date meta setting and control.
$wp_customize->add_setting( 'swingpress_theme_options[single_post_hide_date]', array(
	'default'           => $options['single_post_hide_date'],
	'sanitize_callback' => 'swingpress_sanitize_switch_control',
) );

$wp_customize->add_control( new Swingpress_Switch_Control( $wp_customize, 'swingpress_theme_options[single_post_hide_date]', array(
	'label'             => esc_html__( 'Hide Date', 'swingpress' ),
	'section'           => 'swingpress_single_post_section',
	'on_off_label' 		=> swingpress_hide_options(),
) ) );

// Tourableve author meta setting and control.
$wp_customize->add_setting( 'swingpress_theme_options[single_post_hide_author]', array(
	'default'           => $options['single_post_hide_author'],
	'sanitize_callback' => 'swingpress_sanitize_switch_control',
) );

$wp_customize->add_control( new Swingpress_Switch_Control( $wp_customize, 'swingpress_theme_options[single_post_hide_author]', array(
	'label'             => esc_html__( 'Hide Author', 'swingpress' ),
	'section'           => 'swingpress_single_post_section',
	'on_off_label' 		=> swingpress_hide_options(),
) ) );

// Tourableve author category setting and control.
$wp_customize->add_setting( 'swingpress_theme_options[single_post_hide_category]', array(
	'default'           => $options['single_post_hide_category'],
	'sanitize_callback' => 'swingpress_sanitize_switch_control',
) );

$wp_customize->add_control( new Swingpress_Switch_Control( $wp_customize, 'swingpress_theme_options[single_post_hide_category]', array(
	'label'             => esc_html__( 'Hide Category', 'swingpress' ),
	'section'           => 'swingpress_single_post_section',
	'on_off_label' 		=> swingpress_hide_options(),
) ) );

// Tourableve tag category setting and control.
$wp_customize->add_setting( 'swingpress_theme_options[single_post_hide_tags]', array(
	'default'           => $options['single_post_hide_tags'],
	'sanitize_callback' => 'swingpress_sanitize_switch_control',
) );

$wp_customize->add_control( new Swingpress_Switch_Control( $wp_customize, 'swingpress_theme_options[single_post_hide_tags]', array(
	'label'             => esc_html__( 'Hide Tag', 'swingpress' ),
	'section'           => 'swingpress_single_post_section',
	'on_off_label' 		=> swingpress_hide_options(),
) ) );
