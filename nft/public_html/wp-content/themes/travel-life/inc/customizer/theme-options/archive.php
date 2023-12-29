<?php
/**
 * Archive options
 *
 * @package Theme Palace
 * @subpackage Travel Life
 * @since Travel Life 1.0.0
 */

// Add archive section
$wp_customize->add_section( 'travel_life_archive_section', array(
	'title'             => esc_html__( 'Blog/Archive','travel-life' ),
	'description'       => esc_html__( 'Archive section options.', 'travel-life' ),
	'panel'             => 'travel_life_theme_options_panel',
) );

// Your latest posts title setting and control.
$wp_customize->add_setting( 'travel_life_theme_options[your_latest_posts_title]', array(
	'default'           => $options['your_latest_posts_title'],
	'sanitize_callback' => 'sanitize_text_field',
) );

$wp_customize->add_control( 'travel_life_theme_options[your_latest_posts_title]', array(
	'label'             => esc_html__( 'Your Latest Posts Title', 'travel-life' ),
	'description'       => esc_html__( 'This option only works if Static Front Page is set to "Your latest posts."', 'travel-life' ),
	'section'           => 'travel_life_archive_section',
	'type'				=> 'text',
	'active_callback'   => 'travel_life_is_latest_posts'
) );

// Archive date meta setting and control.
$wp_customize->add_setting( 'travel_life_theme_options[hide_date]', array(
	'default'           => $options['hide_date'],
	'sanitize_callback' => 'travel_life_sanitize_switch_control',
) );

$wp_customize->add_control( new Travel_Life_Switch_Control( $wp_customize, 'travel_life_theme_options[hide_date]', array(
	'label'             => esc_html__( 'Hide Date', 'travel-life' ),
	'section'           => 'travel_life_archive_section',
	'on_off_label' 		=> travel_life_hide_options(),
) ) );

// Archive category setting and control.
$wp_customize->add_setting( 'travel_life_theme_options[hide_category]', array(
	'default'           => $options['hide_category'],
	'sanitize_callback' => 'travel_life_sanitize_switch_control',
) );

$wp_customize->add_control( new Travel_Life_Switch_Control( $wp_customize, 'travel_life_theme_options[hide_category]', array(
	'label'             => esc_html__( 'Hide Category', 'travel-life' ),
	'section'           => 'travel_life_archive_section',
	'on_off_label' 		=> travel_life_hide_options(),
) ) );
