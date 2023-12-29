<?php
/**
 * Popular Destination Section options
 *
 * @package Theme Palace
 * @subpackage Travel Life
 * @since Travel Life 1.0.0
 */

// Add Popular Destination section
$wp_customize->add_section( 'travel_life_popular_destination_section', array(
	'title'             => esc_html__( 'Popular Destination','travel-life' ),
	'description'       => esc_html__( 'Popular Destination Section options.', 'travel-life' ),
	'panel'             => 'travel_life_front_page_panel',
) );

// Popular Destination content enable control and setting
$wp_customize->add_setting( 'travel_life_theme_options[popular_destination_section_enable]', array(
	'default'			=> 	$options['popular_destination_section_enable'],
	'sanitize_callback' => 'travel_life_sanitize_switch_control',
) );

$wp_customize->add_control( new Travel_Life_Switch_Control( $wp_customize, 'travel_life_theme_options[popular_destination_section_enable]', array(
	'label'             => esc_html__( 'Popular Destination Section Enable', 'travel-life' ),
	'section'           => 'travel_life_popular_destination_section',
	'on_off_label' 		=> travel_life_switch_options(),
) ) );

if ( isset( $wp_customize->selective_refresh ) ) {
    $wp_customize->selective_refresh->add_partial( 'travel_life_theme_options[popular_destination_section_enable]', array(
		'selector'            => '.popular-destination .tooltiptext',
		'settings'            => 'travel_life_theme_options[popular_destination_section_enable]',
    ) );
}

// popular destination title setting and control
$wp_customize->add_setting( 'travel_life_theme_options[popular_destination_title]', array(
	'default'			=> $options['popular_destination_title'],
	'sanitize_callback' => 'sanitize_text_field',
	'transport'			=> 'postMessage',
) );

$wp_customize->add_control( 'travel_life_theme_options[popular_destination_title]', array(
	'label'           	=> esc_html__( 'Title', 'travel-life' ),
	'section'        	=> 'travel_life_popular_destination_section',
	'active_callback' 	=> 'travel_life_is_popular_destination_section_enable',
	'type'				=> 'text',
) );

// popular destination title setting and control
$wp_customize->add_setting( 'travel_life_theme_options[popular_destination_read_more]', array(
	'default'			=> $options['popular_destination_read_more'],
	'sanitize_callback' => 'sanitize_text_field',
) );

$wp_customize->add_control( 'travel_life_theme_options[popular_destination_read_more]', array(
	'label'           	=> esc_html__( 'Read More Text', 'travel-life' ),
	'section'        	=> 'travel_life_popular_destination_section',
	'active_callback' 	=> 'travel_life_is_popular_destination_section_enable',
	'type'				=> 'text',
) );

// Popular Destination content type control and setting
$wp_customize->add_setting( 'travel_life_theme_options[popular_destination_content_type]', array(
	'default'          	=> $options['popular_destination_content_type'],
	'sanitize_callback' => 'travel_life_sanitize_select',
) );

$wp_customize->add_control( 'travel_life_theme_options[popular_destination_content_type]', array(
	'label'             => esc_html__( 'Content Type', 'travel-life' ),
	'section'           => 'travel_life_popular_destination_section',
	'type'				=> 'select',
	'active_callback' 	=> 'travel_life_is_popular_destination_section_enable',
	'choices'			=> travel_life_popular_destination_content_type(),
) );

for ( $i=1; $i <= 6; $i++ ) { 
	// popular_destination pages drop down chooser control and setting
	$wp_customize->add_setting( 'travel_life_theme_options[popular_destination_content_page_' . $i . ']', array(
		'sanitize_callback' => 'travel_life_sanitize_page',
	) );

	$wp_customize->add_control( new Travel_Life_Dropdown_Chooser( $wp_customize, 'travel_life_theme_options[popular_destination_content_page_' . $i . ']', array(
		'label'             => sprintf ( esc_html__( 'Select Page %d', 'travel-life' ), $i ),
		'section'           => 'travel_life_popular_destination_section',
		'choices'			=> travel_life_page_choices(),
		'active_callback'	=> 'travel_life_is_popular_destination_section_content_page_enable',
	) ) );


	// popular_destination trips drop down chooser control and setting
	$wp_customize->add_setting( 'travel_life_theme_options[popular_destination_content_trip_' . $i . ']', array(
		'sanitize_callback' => 'travel_life_sanitize_page',
	) );

	$wp_customize->add_control( new Travel_Life_Dropdown_Chooser( $wp_customize, 'travel_life_theme_options[popular_destination_content_trip_' . $i . ']', array(
		'label'             => sprintf ( esc_html__( 'Select Trip %d', 'travel-life' ), $i ),
		'section'           => 'travel_life_popular_destination_section',
		'choices'			=> travel_life_trip_choices(),
		'active_callback'	=> 'travel_life_is_popular_destination_section_content_trip_enable',
	) ) );
}


// destination btn title setting and control
$wp_customize->add_setting( 'travel_life_theme_options[popular_destination_btn_title]', array(
	'default'			=> $options['popular_destination_btn_title'],
	'sanitize_callback' => 'sanitize_text_field',
	'transport'			=> 'postMessage',
) );

$wp_customize->add_control( 'travel_life_theme_options[popular_destination_btn_title]', array(
	'label'           	=> esc_html__( 'Button Label', 'travel-life' ),
	'section'        	=> 'travel_life_popular_destination_section',
	'active_callback' 	=> 'travel_life_is_popular_destination_section_enable',
	'type'				=> 'text',
) );


// destination btn url setting and control
$wp_customize->add_setting( 'travel_life_theme_options[popular_destination_btn_url]', array(
	'sanitize_callback' => 'esc_url_raw',
) );

$wp_customize->add_control( 'travel_life_theme_options[popular_destination_btn_url]', array(
	'label'           	=> esc_html__( 'Button Link', 'travel-life' ),
	'section'        	=> 'travel_life_popular_destination_section',
	'active_callback' 	=> 'travel_life_is_popular_destination_section_enable',
	'type'				=> 'url',
) );