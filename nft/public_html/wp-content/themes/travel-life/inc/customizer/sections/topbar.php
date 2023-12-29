<?php
/**
 * Secondary options Section options
 *
 * @package Theme Palace
 * @subpackage Travel_Life Pro
 * @since Travel_Life Pro 1.0.0
 */

// Add Secondary options section
$wp_customize->add_section( 'travel_life_topbar_section', array(
	'title'             => esc_html__( 'Top Bar options','travel-life' ),
	'description'       => esc_html__( 'Top Bar options Section options.', 'travel-life' ),
	'panel'             => 'travel_life_front_page_panel',
) );

// Secondary options content enable control and setting
$wp_customize->add_setting( 'travel_life_theme_options[top_bar_contact_enable]', array(
	'default'			=> 	$options['top_bar_contact_enable'],
	'sanitize_callback' => 'travel_life_sanitize_switch_control',
) );

$wp_customize->add_control( new Travel_Life_Switch_Control( $wp_customize, 'travel_life_theme_options[top_bar_contact_enable]', array(
	'label'             => esc_html__( 'Top Bar Contact Information Enable', 'travel-life' ),
	'section'           => 'travel_life_topbar_section',
	'on_off_label' 		=> travel_life_switch_options(),
) ) );


// Topbar contact number control and setting
$wp_customize->add_setting( 'travel_life_theme_options[topbar_contact_number]', array(
    'sanitize_callback' => 'sanitize_text_field',
) );
    
$wp_customize->add_control( 'travel_life_theme_options[topbar_contact_number]', array(
    'label'           	=> esc_html__( 'Contact Number', 'travel-life' ),
    'section'        	=> 'travel_life_topbar_section',
    'active_callback' 	=> 'travel_life_is_topbar_contact_enable',
    'type'				=> 'text',
) );

// Topbar contact number control and setting
$wp_customize->add_setting( 'travel_life_theme_options[topbar_opening_time]', array(
    'sanitize_callback' => 'sanitize_text_field',
) );
    
$wp_customize->add_control( 'travel_life_theme_options[topbar_opening_time]', array(
    'label'           	=> esc_html__( 'Opening Time', 'travel-life' ),
    'section'        	=> 'travel_life_topbar_section',
    'active_callback' 	=> 'travel_life_is_topbar_contact_enable',
    'type'				=> 'text',
) );

// Topbar contact email control and setting
$wp_customize->add_setting( 'travel_life_theme_options[topbar_address]', array(
    'sanitize_callback' => 'sanitize_text_field',
    ) );
    
$wp_customize->add_control( 'travel_life_theme_options[topbar_address]', array(
    'label'           	=> esc_html__( 'Address', 'travel-life' ),
    'section'        	=> 'travel_life_topbar_section',
    'active_callback' 	=> 'travel_life_is_topbar_contact_enable',
    'type'				=> 'text',
) );

// Topbar contact email control and setting
$wp_customize->add_setting( 'travel_life_theme_options[topbar_secondary_address]', array(
    'sanitize_callback' => 'sanitize_text_field',
    ) );
    
$wp_customize->add_control( 'travel_life_theme_options[topbar_secondary_address]', array(
    'label'           	=> esc_html__( 'Secondary Address', 'travel-life' ),
    'section'        	=> 'travel_life_topbar_section',
    'active_callback' 	=> 'travel_life_is_topbar_contact_enable',
    'type'				=> 'text',
) );

// Topbar Social menu  enable control and setting
$wp_customize->add_setting( 'travel_life_theme_options[topbar_button_enable]', array(
	'default'			=> 	$options['topbar_button_enable'],
	'sanitize_callback' => 'travel_life_sanitize_switch_control',
) );

$wp_customize->add_control( new Travel_Life_Switch_Control( $wp_customize, 'travel_life_theme_options[topbar_button_enable]', array(
	'label'             => esc_html__( 'Enable Header Button', 'travel-life' ),
	'section'           => 'travel_life_topbar_section',
	'on_off_label' 		=> travel_life_switch_options(),
) ) );

// Topbar contact number control and setting
$wp_customize->add_setting( 'travel_life_theme_options[topbar_btn_text]', array(
    'sanitize_callback' => 'sanitize_text_field',
    'default'			=> 	$options['topbar_btn_text'],
) );
    
$wp_customize->add_control( 'travel_life_theme_options[topbar_btn_text]', array(
    'label'           	=> esc_html__( 'Button Text', 'travel-life' ),
    'section'        	=> 'travel_life_topbar_section',
    'active_callback' 	=> 'travel_life_is_topbar_button_enable',
    'type'				=> 'text',
) );
// Topbar contact number control and setting
$wp_customize->add_setting( 'travel_life_theme_options[topbar_btn_url]', array(
    'sanitize_callback' => 'esc_url_raw',
) );
    
$wp_customize->add_control( 'travel_life_theme_options[topbar_btn_url]', array(
    'label'           	=> esc_html__( 'Button Link', 'travel-life' ),
    'section'        	=> 'travel_life_topbar_section',
    'active_callback' 	=> 'travel_life_is_topbar_button_enable',
    'type'				=> 'url',
) );