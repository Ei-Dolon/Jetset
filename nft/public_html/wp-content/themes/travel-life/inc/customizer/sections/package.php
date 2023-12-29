<?php
/**
 * Package Section options
 *
 * @package Theme Palace
 * @subpackage Travel Life
 * @since Travel Life 1.0.0
 */

// Add Package section
$wp_customize->add_section( 'travel_life_package_section', array(
	'title'             => esc_html__( 'Package','travel-life' ),
	'description'       => esc_html__( 'Package Section options.', 'travel-life' ),
	'panel'             => 'travel_life_front_page_panel',
) );

// Package content enable control and setting
$wp_customize->add_setting( 'travel_life_theme_options[package_section_enable]', array(
	'default'			=> 	$options['package_section_enable'],
	'sanitize_callback' => 'travel_life_sanitize_switch_control',
) );

$wp_customize->add_control( new Travel_Life_Switch_Control( $wp_customize, 'travel_life_theme_options[package_section_enable]', array(
	'label'             => esc_html__( 'Package Section Enable', 'travel-life' ),
	'section'           => 'travel_life_package_section',
	'on_off_label' 		=> travel_life_switch_options(),
) ) );

if ( isset( $wp_customize->selective_refresh ) ) {
    $wp_customize->selective_refresh->add_partial( 'travel_life_theme_options[package_section_enable]', array(
		'selector'            => '.package-section .tooltiptext',
		'settings'            => 'travel_life_theme_options[package_section_enable]',
    ) );
}

// package title setting and control
$wp_customize->add_setting( 'travel_life_theme_options[package_title]', array(
	'sanitize_callback' => 'sanitize_text_field',
	'default'			=> $options['package_title'],
	'transport'			=> 'postMessage',
) );

$wp_customize->add_control( 'travel_life_theme_options[package_title]', array(
	'label'           	=> esc_html__( 'Title', 'travel-life' ),
	'section'        	=> 'travel_life_package_section',
	'active_callback' 	=> 'travel_life_is_package_section_enable',
	'type'				=> 'text',
) );



// Package content type control and setting
$wp_customize->add_setting( 'travel_life_theme_options[package_content_type]', array(
	'default'          	=> $options['package_content_type'],
	'sanitize_callback' => 'travel_life_sanitize_select',
) );

$wp_customize->add_control( 'travel_life_theme_options[package_content_type]', array(
	'label'             => esc_html__( 'Content Type', 'travel-life' ),
	'section'           => 'travel_life_package_section',
	'type'				=> 'select',
	'active_callback' 	=> 'travel_life_is_package_section_enable',
	'choices'			=> travel_life_package_content_type(),
) );



for ( $i=1; $i <= 6; $i++ ) { 
	// package pages drop down chooser control and setting
	$wp_customize->add_setting( 'travel_life_theme_options[package_content_page_' . $i . ']', array(
		'sanitize_callback' => 'travel_life_sanitize_page',
	) );

	$wp_customize->add_control( new Travel_Life_Dropdown_Chooser( $wp_customize, 'travel_life_theme_options[package_content_page_' . $i . ']', array(
		'label'             => sprintf ( esc_html__( 'Select Page %d', 'travel-life' ), $i ),
		'section'           => 'travel_life_package_section',
		'choices'			=> travel_life_page_choices(),
		'active_callback'	=> 'travel_life_is_package_section_content_page_enable',
	) ) );



	// package trips drop down chooser control and setting
	$wp_customize->add_setting( 'travel_life_theme_options[package_content_trip_' . $i . ']', array(
		'sanitize_callback' => 'travel_life_sanitize_page',
	) );

	$wp_customize->add_control( new Travel_Life_Dropdown_Chooser( $wp_customize, 'travel_life_theme_options[package_content_trip_' . $i . ']', array(
		'label'             => sprintf ( esc_html__( 'Select Trip %d', 'travel-life' ), $i ),
		'section'           => 'travel_life_package_section',
		'choices'			=> travel_life_trip_choices(),
		'active_callback'	=> 'travel_life_is_package_section_content_trip_enable',
	) ) );
}

// package title setting and control
$wp_customize->add_setting( 'travel_life_theme_options[package_btn_title]', array(
	'sanitize_callback' => 'sanitize_text_field',
	'default'			=> $options['package_btn_title'],
	'transport'			=> 'postMessage',
) );

$wp_customize->add_control( 'travel_life_theme_options[package_btn_title]', array(
	'label'           	=> esc_html__( 'Read More Text', 'travel-life' ),
	'section'        	=> 'travel_life_package_section',
	'active_callback' 	=> 'travel_life_is_package_section_enable',
	'type'				=> 'text',
) );


 