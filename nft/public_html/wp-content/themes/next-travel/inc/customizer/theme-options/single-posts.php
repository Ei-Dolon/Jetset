<?php
/**
 * Excerpt options
 *
 * @package Theme Palace
 * @subpackage Next Travel
 * @since Next Travel 1.0.0
 */

// Add excerpt section
$wp_customize->add_section( 'next_travel_single_post_section', array(
	'title'             => esc_html__( 'Single Post','next-travel' ),
	'description'       => esc_html__( 'Options to change the single posts globally.', 'next-travel' ),
	'panel'             => 'next_travel_theme_options_panel',
) );

// Tourableve date meta setting and control.
$wp_customize->add_setting( 'next_travel_theme_options[single_post_hide_date]', array(
	'default'           => $options['single_post_hide_date'],
	'sanitize_callback' => 'next_travel_sanitize_switch_control',
) );

$wp_customize->add_control( new Next_Travel_Switch_Control( $wp_customize, 'next_travel_theme_options[single_post_hide_date]', array(
	'label'             => esc_html__( 'Hide Date', 'next-travel' ),
	'section'           => 'next_travel_single_post_section',
	'on_off_label' 		=> next_travel_hide_options(),
) ) );

// Tourableve author meta setting and control.
$wp_customize->add_setting( 'next_travel_theme_options[single_post_hide_author]', array(
	'default'           => $options['single_post_hide_author'],
	'sanitize_callback' => 'next_travel_sanitize_switch_control',
) );

$wp_customize->add_control( new Next_Travel_Switch_Control( $wp_customize, 'next_travel_theme_options[single_post_hide_author]', array(
	'label'             => esc_html__( 'Hide Author', 'next-travel' ),
	'section'           => 'next_travel_single_post_section',
	'on_off_label' 		=> next_travel_hide_options(),
) ) );

// Tourableve author category setting and control.
$wp_customize->add_setting( 'next_travel_theme_options[single_post_hide_category]', array(
	'default'           => $options['single_post_hide_category'],
	'sanitize_callback' => 'next_travel_sanitize_switch_control',
) );

$wp_customize->add_control( new Next_Travel_Switch_Control( $wp_customize, 'next_travel_theme_options[single_post_hide_category]', array(
	'label'             => esc_html__( 'Hide Category', 'next-travel' ),
	'section'           => 'next_travel_single_post_section',
	'on_off_label' 		=> next_travel_hide_options(),
) ) );

// Tourableve tag category setting and control.
$wp_customize->add_setting( 'next_travel_theme_options[single_post_hide_tags]', array(
	'default'           => $options['single_post_hide_tags'],
	'sanitize_callback' => 'next_travel_sanitize_switch_control',
) );

$wp_customize->add_control( new Next_Travel_Switch_Control( $wp_customize, 'next_travel_theme_options[single_post_hide_tags]', array(
	'label'             => esc_html__( 'Hide Tag', 'next-travel' ),
	'section'           => 'next_travel_single_post_section',
	'on_off_label' 		=> next_travel_hide_options(),
) ) );
