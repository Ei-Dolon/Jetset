<?php
/**
 * About Section options
 *
 * @package Theme Palace
 * @subpackage Next Travel
 * @since Next Travel 1.0.0
 */

// Add About section
$wp_customize->add_section( 'next_travel_about_section', array(
	'title'             => esc_html__( 'About Us','next-travel' ),
	'description'       => esc_html__( 'About Us Section options.', 'next-travel' ),
	'panel'             => 'next_travel_front_page_panel',
) );

// About Section enable control and setting
$wp_customize->add_setting( 'next_travel_theme_options[about_section_enable]', array(
	'default'			=> 	$options['about_section_enable'],
	'sanitize_callback' => 'next_travel_sanitize_switch_control',
) );

$wp_customize->add_control( new Next_Travel_Switch_Control( $wp_customize, 'next_travel_theme_options[about_section_enable]', array(
	'label'             => esc_html__( 'About Section Enable', 'next-travel' ),
	'section'           => 'next_travel_about_section',
	'on_off_label' 		=> next_travel_switch_options(),
) ) );

if ( isset( $wp_customize->selective_refresh ) ) {
    $wp_customize->selective_refresh->add_partial( 'next_travel_theme_options[about_section_enable]', array(
		'selector'            => '#about-us div.wrapper',
		'settings'            => 'next_travel_theme_options[about_section_enable]',
    ) );
}

// About sub title setting and control
$wp_customize->add_setting( 'next_travel_theme_options[about_sub_title]', array(
	'sanitize_callback' => 'sanitize_text_field',
	'default'			=> $options['about_sub_title'],
	'transport'			=> 'postMessage',
) );

$wp_customize->add_control( 'next_travel_theme_options[about_sub_title]', array(
	'label'           	=> esc_html__( 'Sub Title', 'next-travel' ),
	'section'        	=> 'next_travel_about_section',
	'active_callback' 	=> 'next_travel_is_about_section_enable',
	'type'				=> 'text',
) );

// Abort if selective refresh is not available.
if ( isset( $wp_customize->selective_refresh ) ) {
    $wp_customize->selective_refresh->add_partial( 'next_travel_theme_options[about_sub_title]', array(
		'selector'            => '#about-us h3.section-subtitle',
		'settings'            => 'next_travel_theme_options[about_sub_title]',
		'container_inclusive' => false,
		'fallback_refresh'    => true,
		'render_callback'     => 'next_travel_about_sub_title_partial',
    ) );
}

// About pages drop down chooser control and setting
$wp_customize->add_setting( 'next_travel_theme_options[about_content_page]', array(
	'sanitize_callback' => 'next_travel_sanitize_page',
) );

$wp_customize->add_control( new Next_Travel_Dropdown_Chooser( $wp_customize, 'next_travel_theme_options[about_content_page]', array(
	'label'             => esc_html__( 'Select Page', 'next-travel' ),
	'section'           => 'next_travel_about_section',
	'choices'			=> next_travel_page_choices(),
	'active_callback'	=> 'next_travel_is_about_section_enable',
) ) );

// about btn title setting and control
$wp_customize->add_setting( 'next_travel_theme_options[about_btn_title]', array(
	'sanitize_callback' => 'sanitize_text_field',
	'default'			=> $options['about_btn_title'],
	'transport'			=> 'postMessage',
	) );
	
	$wp_customize->add_control( 'next_travel_theme_options[about_btn_title]', array(
	'label'           	=> esc_html__( 'Button Label', 'next-travel' ),
	'section'        	=> 'next_travel_about_section',
	'active_callback' 	=> 'next_travel_is_about_section_enable',
	'type'				=> 'text',
	) );

// Abort if selective refresh is not available.
if ( isset( $wp_customize->selective_refresh ) ) {
    $wp_customize->selective_refresh->add_partial( 'next_travel_theme_options[about_btn_title]', array(
		'selector'            => '#about-us div.read-more a',
		'settings'            => 'next_travel_theme_options[about_btn_title]',
		'container_inclusive' => false,
		'fallback_refresh'    => true,
		'render_callback'     => 'next_travel_about_btn_title_partial',
		) );
	}