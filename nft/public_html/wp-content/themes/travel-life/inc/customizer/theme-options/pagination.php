<?php
/**
 * pagination options
 *
 * @package Theme Palace
 * @subpackage Travel Life
 * @since Travel Life 1.0.0
 */

// Add sidebar section
$wp_customize->add_section( 'travel_life_pagination', array(
	'title'               => esc_html__('Pagination','travel-life'),
	'description'         => esc_html__( 'Pagination section options.', 'travel-life' ),
	'panel'               => 'travel_life_theme_options_panel',
) );

// Sidebar position setting and control.
$wp_customize->add_setting( 'travel_life_theme_options[pagination_enable]', array(
	'sanitize_callback' => 'travel_life_sanitize_switch_control',
	'default'             => $options['pagination_enable'],
) );

$wp_customize->add_control( new Travel_Life_Switch_Control( $wp_customize, 'travel_life_theme_options[pagination_enable]', array(
	'label'               => esc_html__( 'Pagination Enable', 'travel-life' ),
	'section'             => 'travel_life_pagination',
	'on_off_label' 		=> travel_life_switch_options(),
) ) );

// Site layout setting and control.
$wp_customize->add_setting( 'travel_life_theme_options[pagination_type]', array(
	'sanitize_callback'   => 'travel_life_sanitize_select',
	'default'             => $options['pagination_type'],
) );

$wp_customize->add_control( 'travel_life_theme_options[pagination_type]', array(
	'label'               => esc_html__( 'Pagination Type', 'travel-life' ),
	'section'             => 'travel_life_pagination',
	'type'                => 'select',
	'choices'			  => travel_life_pagination_options(),
	'active_callback'	  => 'travel_life_is_pagination_enable',
) );
