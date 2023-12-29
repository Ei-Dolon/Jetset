<?php
/**
 * Menu options
 *
 * @package Theme Palace
 * @subpackage Swingpress
 * @since Swingpress 1.0.0
 */

// Add sidebar section
$wp_customize->add_section( 'swingpress_menu', array(
	'title'             => esc_html__('Header Menu','swingpress'),
	'description'       => esc_html__( 'Header Menu options.', 'swingpress' ),
	'panel'             => 'nav_menus',
) );

// Menu sticky setting and control.
$wp_customize->add_setting( 'swingpress_theme_options[menu_sticky]', array(
	'sanitize_callback' => 'swingpress_sanitize_switch_control',
	'default'           => $options['menu_sticky'],
) );

$wp_customize->add_control( new Swingpress_Switch_Control( $wp_customize, 'swingpress_theme_options[menu_sticky]', array(
	'label'             => esc_html__( 'Make Menu Sticky', 'swingpress' ),
	'section'           => 'swingpress_menu',
	'on_off_label' 		=> swingpress_switch_options(),
) ) );

// search enable setting and control.
$wp_customize->add_setting( 'swingpress_theme_options[social_nav_enable]', array(
	'sanitize_callback' => 'swingpress_sanitize_switch_control',
	'default'           => $options['social_nav_enable'],
) );

$wp_customize->add_control( new Swingpress_Switch_Control( $wp_customize, 'swingpress_theme_options[social_nav_enable]', array(
	'label'             => esc_html__( 'Enable Social Links', 'swingpress' ),
	'description'       => sprintf( '%1$s <a class="topbar-menu-trigger" href="#"> %2$s </a> %3$s', esc_html__( 'Note: To show social menu.', 'swingpress' ), esc_html__( 'Click Here', 'swingpress' ), esc_html__( 'to create menu', 'swingpress' ) ),
	'section'           => 'swingpress_menu',
	'on_off_label' 		=> swingpress_switch_options(),
) ) );


// search enable setting and control.
$wp_customize->add_setting( 'swingpress_theme_options[search_nav_enable]', array(
	'sanitize_callback' => 'swingpress_sanitize_switch_control',
	'default'           => $options['search_nav_enable'],
) );

$wp_customize->add_control( new Swingpress_Switch_Control( $wp_customize, 'swingpress_theme_options[search_nav_enable]', array(
	'label'             => esc_html__( 'Enable Search Icon', 'swingpress' ),
	'section'           => 'swingpress_menu',
	'on_off_label' 		=> swingpress_switch_options(),
) ) );
