<?php
/**
 * Excerpt options
 *
 * @package Theme Palace
 * @subpackage Travel Life
 * @since Travel Life 1.0.0
 */

// Add excerpt section
$wp_customize->add_section( 'travel_life_single_post_section', array(
	'title'             => esc_html__( 'Single Post','travel-life' ),
	'description'       => esc_html__( 'Options to change the single posts globally.', 'travel-life' ),
	'panel'             => 'travel_life_theme_options_panel',
) );

// Tourableve date meta setting and control.
$wp_customize->add_setting( 'travel_life_theme_options[single_post_hide_date]', array(
	'default'           => $options['single_post_hide_date'],
	'sanitize_callback' => 'travel_life_sanitize_switch_control',
) );

$wp_customize->add_control( new Travel_Life_Switch_Control( $wp_customize, 'travel_life_theme_options[single_post_hide_date]', array(
	'label'             => esc_html__( 'Hide Date', 'travel-life' ),
	'section'           => 'travel_life_single_post_section',
	'on_off_label' 		=> travel_life_hide_options(),
) ) );

// Tourableve author meta setting and control.
$wp_customize->add_setting( 'travel_life_theme_options[single_post_hide_author]', array(
	'default'           => $options['single_post_hide_author'],
	'sanitize_callback' => 'travel_life_sanitize_switch_control',
) );

$wp_customize->add_control( new Travel_Life_Switch_Control( $wp_customize, 'travel_life_theme_options[single_post_hide_author]', array(
	'label'             => esc_html__( 'Hide Author', 'travel-life' ),
	'section'           => 'travel_life_single_post_section',
	'on_off_label' 		=> travel_life_hide_options(),
) ) );

// Tourableve author category setting and control.
$wp_customize->add_setting( 'travel_life_theme_options[single_post_hide_category]', array(
	'default'           => $options['single_post_hide_category'],
	'sanitize_callback' => 'travel_life_sanitize_switch_control',
) );

$wp_customize->add_control( new Travel_Life_Switch_Control( $wp_customize, 'travel_life_theme_options[single_post_hide_category]', array(
	'label'             => esc_html__( 'Hide Category', 'travel-life' ),
	'section'           => 'travel_life_single_post_section',
	'on_off_label' 		=> travel_life_hide_options(),
) ) );

// Tourableve tag category setting and control.
$wp_customize->add_setting( 'travel_life_theme_options[single_post_hide_tags]', array(
	'default'           => $options['single_post_hide_tags'],
	'sanitize_callback' => 'travel_life_sanitize_switch_control',
) );

$wp_customize->add_control( new Travel_Life_Switch_Control( $wp_customize, 'travel_life_theme_options[single_post_hide_tags]', array(
	'label'             => esc_html__( 'Hide Tag', 'travel-life' ),
	'section'           => 'travel_life_single_post_section',
	'on_off_label' 		=> travel_life_hide_options(),
) ) );
