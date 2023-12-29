<?php
/**
 * pagination options
 *
 * @package Theme Palace
 * @subpackage Swingpress
 * @since Swingpress 1.0.0
 */

// Add sidebar section
$wp_customize->add_section( 'swingpress_pagination', array(
	'title'               => esc_html__('Pagination','swingpress'),
	'description'         => esc_html__( 'Pagination section options.', 'swingpress' ),
	'panel'               => 'swingpress_theme_options_panel',
) );

// Sidebar position setting and control.
$wp_customize->add_setting( 'swingpress_theme_options[pagination_enable]', array(
	'sanitize_callback' => 'swingpress_sanitize_switch_control',
	'default'             => $options['pagination_enable'],
) );

$wp_customize->add_control( new Swingpress_Switch_Control( $wp_customize, 'swingpress_theme_options[pagination_enable]', array(
	'label'               => esc_html__( 'Pagination Enable', 'swingpress' ),
	'section'             => 'swingpress_pagination',
	'on_off_label' 		=> swingpress_switch_options(),
) ) );

// Site layout setting and control.
$wp_customize->add_setting( 'swingpress_theme_options[pagination_type]', array(
	'sanitize_callback'   => 'swingpress_sanitize_select',
	'default'             => $options['pagination_type'],
) );

$wp_customize->add_control( 'swingpress_theme_options[pagination_type]', array(
	'label'               => esc_html__( 'Pagination Type', 'swingpress' ),
	'section'             => 'swingpress_pagination',
	'type'                => 'select',
	'choices'			  => swingpress_pagination_options(),
	'active_callback'	  => 'swingpress_is_pagination_enable',
) );
