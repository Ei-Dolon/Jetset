<?php
/**
 * Archive options
 *
 * @package Theme Palace
 * @subpackage Next Travel
 * @since Next Travel 1.0.0
 */

// Add archive section
$wp_customize->add_section( 'next_travel_archive_section', array(
	'title'             => esc_html__( 'Blog/Archive','next-travel' ),
	'description'       => esc_html__( 'Archive section options.', 'next-travel' ),
	'panel'             => 'next_travel_theme_options_panel',
) );

// Your latest posts title setting and control.
$wp_customize->add_setting( 'next_travel_theme_options[your_latest_posts_title]', array(
	'default'           => $options['your_latest_posts_title'],
	'sanitize_callback' => 'sanitize_text_field',
) );

$wp_customize->add_control( 'next_travel_theme_options[your_latest_posts_title]', array(
	'label'             => esc_html__( 'Your Latest Posts Title', 'next-travel' ),
	'description'       => esc_html__( 'This option only works if Static Front Page is set to "Your latest posts."', 'next-travel' ),
	'section'           => 'next_travel_archive_section',
	'type'				=> 'text',
	'active_callback'   => 'next_travel_is_latest_posts'
) );

// Archive date meta setting and control.
$wp_customize->add_setting( 'next_travel_theme_options[hide_date]', array(
	'default'           => $options['hide_date'],
	'sanitize_callback' => 'next_travel_sanitize_switch_control',
) );

$wp_customize->add_control( new Next_Travel_Switch_Control( $wp_customize, 'next_travel_theme_options[hide_date]', array(
	'label'             => esc_html__( 'Hide Date', 'next-travel' ),
	'section'           => 'next_travel_archive_section',
	'on_off_label' 		=> next_travel_hide_options(),
) ) );

// Archive category setting and control.
$wp_customize->add_setting( 'next_travel_theme_options[hide_category]', array(
	'default'           => $options['hide_category'],
	'sanitize_callback' => 'next_travel_sanitize_switch_control',
) );

$wp_customize->add_control( new Next_Travel_Switch_Control( $wp_customize, 'next_travel_theme_options[hide_category]', array(
	'label'             => esc_html__( 'Hide Category', 'next-travel' ),
	'section'           => 'next_travel_archive_section',
	'on_off_label' 		=> next_travel_hide_options(),
) ) );

// features content type control and setting
$wp_customize->add_setting( 'next_travel_theme_options[blog_column]', array(
	'default'          	=> $options['blog_column'],
	'sanitize_callback' => 'next_travel_sanitize_select',
	'transport'			=> 'refresh',
) );

$wp_customize->add_control( 'next_travel_theme_options[blog_column]', array(
	'label'             => esc_html__( 'Column Layout', 'next-travel' ),
	'section'           => 'next_travel_archive_section',
	'type'				=> 'select',
	'choices'			=> array( 
		'col-1'		=> esc_html__( 'One Column', 'next-travel' ),
		'col-2'		=> esc_html__( 'Two Column', 'next-travel' ),
		'col-3'		=> esc_html__( 'Three Column', 'next-travel' ),
	),
) );