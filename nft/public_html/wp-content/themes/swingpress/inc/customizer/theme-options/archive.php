<?php
/**
 * Archive options
 *
 * @package Theme Palace
 * @subpackage Swingpress
 * @since Swingpress 1.0.0
 */

// Add archive section
$wp_customize->add_section( 'swingpress_archive_section', array(
	'title'             => esc_html__( 'Blog/Archive','swingpress' ),
	'description'       => esc_html__( 'Archive section options.', 'swingpress' ),
	'panel'             => 'swingpress_theme_options_panel',
) );

// Your latest posts title setting and control.
$wp_customize->add_setting( 'swingpress_theme_options[your_latest_posts_title]', array(
	'default'           => $options['your_latest_posts_title'],
	'sanitize_callback' => 'sanitize_text_field',
) );

$wp_customize->add_control( 'swingpress_theme_options[your_latest_posts_title]', array(
	'label'             => esc_html__( 'Your Latest Posts Title', 'swingpress' ),
	'description'       => esc_html__( 'This option only works if Static Front Page is set to "Your latest posts."', 'swingpress' ),
	'section'           => 'swingpress_archive_section',
	'type'				=> 'text',
	'active_callback'   => 'swingpress_is_latest_posts'
) );

// Archive date meta setting and control.
$wp_customize->add_setting( 'swingpress_theme_options[hide_btn]', array(
	'default'           => $options['hide_btn'],
	'sanitize_callback' => 'swingpress_sanitize_switch_control',
) );

$wp_customize->add_control( new Swingpress_Switch_Control( $wp_customize, 'swingpress_theme_options[hide_btn]', array(
	'label'             => esc_html__( 'Hide Button', 'swingpress' ),
	'section'           => 'swingpress_archive_section',
	'on_off_label' 		=> swingpress_hide_options(),
) ) );

// Archive category setting and control.
$wp_customize->add_setting( 'swingpress_theme_options[hide_category]', array(
	'default'           => $options['hide_category'],
	'sanitize_callback' => 'swingpress_sanitize_switch_control',
) );

$wp_customize->add_control( new Swingpress_Switch_Control( $wp_customize, 'swingpress_theme_options[hide_category]', array(
	'label'             => esc_html__( 'Hide Category', 'swingpress' ),
	'section'           => 'swingpress_archive_section',
	'on_off_label' 		=> swingpress_hide_options(),
) ) );

// Archive category setting and control.
$wp_customize->add_setting( 'swingpress_theme_options[hide_content]', array(
	'default'           => $options['hide_content'],
	'sanitize_callback' => 'swingpress_sanitize_switch_control',
) );

$wp_customize->add_control( new Swingpress_Switch_Control( $wp_customize, 'swingpress_theme_options[hide_content]', array(
	'label'             => esc_html__( 'Hide Conetnt', 'swingpress' ),
	'section'           => 'swingpress_archive_section',
	'on_off_label' 		=> swingpress_hide_options(),
) ) );

$wp_customize->add_setting( 'swingpress_theme_options[hide_image]', array(
	'default'           => $options['hide_image'],
	'sanitize_callback' => 'swingpress_sanitize_switch_control',
) );

$wp_customize->add_control( new Swingpress_Switch_Control( $wp_customize, 'swingpress_theme_options[hide_image]', array(
	'label'             => esc_html__( 'Hide Featured Image', 'swingpress' ),
	'section'           => 'swingpress_archive_section',
	'on_off_label' 		=> swingpress_hide_options(),
) ) );
