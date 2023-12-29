<?php
/**
 * About Section options
 *
 * @package Theme Palace
 * @subpackage Travel Life
 * @since Travel Life 1.0.0
 */
 // Add About section
$wp_customize->add_section( 'travel_life_about_section', array(
	'title'             => esc_html__( 'About Us','travel-life' ),
	'description'       => esc_html__( 'About Us Section options.', 'travel-life' ),
	'panel'             => 'travel_life_front_page_panel',
) );

// About content enable control and setting
$wp_customize->add_setting( 'travel_life_theme_options[about_section_enable]', array(
	'default'			=> 	$options['about_section_enable'],
	'sanitize_callback' => 'travel_life_sanitize_switch_control',
) );

$wp_customize->add_control( new Travel_Life_Switch_Control( $wp_customize, 'travel_life_theme_options[about_section_enable]', array(
	'label'             => esc_html__( 'About Section Enable', 'travel-life' ),
	'section'           => 'travel_life_about_section',
	'on_off_label' 		=> travel_life_switch_options(),
) ) );

if ( isset( $wp_customize->selective_refresh ) ) {
    $wp_customize->selective_refresh->add_partial( 'travel_life_theme_options[about_section_enable]', array(
		'selector'            => '#about-us .tooltiptext',
		'settings'            => 'travel_life_theme_options[about_section_enable]',
    ) );
}


// about pages drop down chooser control and setting
$wp_customize->add_setting( 'travel_life_theme_options[about_content_page]', array(
	'sanitize_callback' => 'travel_life_sanitize_page',
) );

$wp_customize->add_control( new Travel_Life_Dropdown_Chooser( $wp_customize, 'travel_life_theme_options[about_content_page]', array(
	'label'             => esc_html__( 'Select Page', 'travel-life' ),
	'section'           => 'travel_life_about_section',
	'choices'			=> travel_life_page_choices(),
	'active_callback'	=> 'travel_life_is_about_section_enable',
) ) );


// about btn title setting and control
$wp_customize->add_setting( 'travel_life_theme_options[about_btn_title]', array(
	'sanitize_callback' => 'sanitize_text_field',
	'default'			=> $options['about_btn_title'],
	'transport'			=> 'postMessage',
) );

$wp_customize->add_control( 'travel_life_theme_options[about_btn_title]', array(
	'label'           	=> esc_html__( 'Button Label', 'travel-life' ),
	'section'        	=> 'travel_life_about_section',
	'active_callback' 	=> 'travel_life_is_about_section_enable',
	'type'				=> 'text',
) );



// about btn link setting and control
$wp_customize->add_setting( 'travel_life_theme_options[about_btn_link]', array(
	'sanitize_callback' => 'esc_url_raw',
) );

$wp_customize->add_control( 'travel_life_theme_options[about_btn_link]', array(
	'label'           	=> esc_html__( 'Button Link', 'travel-life' ),
	'section'        	=> 'travel_life_about_section',
	'active_callback' 	=> 'travel_life_is_about_section_enable',
	'type'				=> 'url',
) );