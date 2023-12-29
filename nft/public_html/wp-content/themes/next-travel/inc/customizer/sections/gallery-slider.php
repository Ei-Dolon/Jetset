<?php
/**
 * Gallery Slider Section options
 *
 * @package Theme Palace
 * @subpackage Next Travel
 * @since Next Travel 1.0.0
 */

// Add Gallery Slider section
$wp_customize->add_section( 'next_travel_gallery_slider_section', array(
	'title'             => esc_html__( 'Gallery Slider','next-travel' ),
	'description'       => esc_html__( 'Gallery Slider Section options.', 'next-travel' ),
	'panel'             => 'next_travel_front_page_panel',
) );

// Gallery Slider section enable control and setting
$wp_customize->add_setting( 'next_travel_theme_options[gallery_slider_section_enable]', array(
	'default'			=> 	$options['gallery_slider_section_enable'],
	'sanitize_callback' => 'next_travel_sanitize_switch_control',
) );

$wp_customize->add_control( new Next_Travel_Switch_Control( $wp_customize, 'next_travel_theme_options[gallery_slider_section_enable]', array(
	'label'             => esc_html__( 'Gallery Slider Section Enable', 'next-travel' ),
	'section'           => 'next_travel_gallery_slider_section',
	'on_off_label' 		=> next_travel_switch_options(),
) ) );

if ( isset( $wp_customize->selective_refresh ) ) {
    $wp_customize->selective_refresh->add_partial( 'next_travel_theme_options[gallery_slider_section_enable]', array(
		'selector'            => '#gallery-slider-section div.wrapper',
		'settings'            => 'next_travel_theme_options[gallery_slider_section_enable]',
    ) );
}

// Gallery Slider content type control and setting
$wp_customize->add_setting( 'next_travel_theme_options[gallery_slider_content_type]', array(
	'default'          	=> $options['gallery_slider_content_type'],
	'sanitize_callback' => 'next_travel_sanitize_select',
) );

$wp_customize->add_control( 'next_travel_theme_options[gallery_slider_content_type]', array(
	'label'             => esc_html__( 'Content Type', 'next-travel' ),
	'section'           => 'next_travel_gallery_slider_section',
	'type'				=> 'select',
	'active_callback' 	=> 'next_travel_is_gallery_slider_section_enable',
	'choices'			=> next_travel_gallery_slider_content_type(),
) );

for ( $i = 1; $i <= 4; $i++ ) :

	// testimonial position setting and control
	$wp_customize->add_setting( 'next_travel_theme_options[gallery_slider_subtitle_' . $i . ']', array(
		'sanitize_callback' => 'sanitize_text_field',
	) );

	$wp_customize->add_control( 'next_travel_theme_options[gallery_slider_subtitle_' . $i . ']', array(
		'label'           	=> sprintf( esc_html__( 'Sub Title %d', 'next-travel' ), $i ),
		'section'        	=> 'next_travel_gallery_slider_section',
		'active_callback' 	=> 'next_travel_is_gallery_slider_section_enable',
		'type'				=> 'text',
	) );

	// gallery_slider pages drop down chooser control and setting
	$wp_customize->add_setting( 'next_travel_theme_options[gallery_slider_content_page_' . $i . ']', array(
		'sanitize_callback' => 'next_travel_sanitize_page',
	) );

	$wp_customize->add_control( new Next_Travel_Dropdown_Chooser( $wp_customize, 'next_travel_theme_options[gallery_slider_content_page_' . $i . ']', array(
		'label'             => sprintf( esc_html__( 'Select Page %d', 'next-travel' ), $i ),
		'section'           => 'next_travel_gallery_slider_section',
		'choices'			=> next_travel_page_choices(),
		'active_callback'	=> 'next_travel_is_gallery_slider_section_content_page_enable',
	) ) );

endfor;

// Add dropdown category setting and control.
$wp_customize->add_setting(  'next_travel_theme_options[gallery_slider_content_trip_types]', array(
	'sanitize_callback' => 'absint',
) ) ;

$wp_customize->add_control( new Next_Travel_Dropdown_Taxonomies_Control( $wp_customize,'next_travel_theme_options[gallery_slider_content_trip_types]', array(
	'label'             => esc_html__( 'Select Trip Types', 'next-travel' ),
	'section'           => 'next_travel_gallery_slider_section',
	'taxonomy'			=> 'itinerary_types',
	'type'              => 'dropdown-taxonomies',
	'active_callback'	=> 'next_travel_is_gallery_slider_section_content_trip_types_enable'
) ) );


// gallery_slider btn title setting and control
$wp_customize->add_setting( 'next_travel_theme_options[gallery_slider_btn_title]', array(
	'sanitize_callback' => 'sanitize_text_field',
	'default'			=> $options['gallery_slider_btn_title'],
	'transport'			=> 'postMessage',
	) );
	
	$wp_customize->add_control( 'next_travel_theme_options[gallery_slider_btn_title]', array(
	'label'           	=> esc_html__( 'Button Label', 'next-travel' ),
	'section'        	=> 'next_travel_gallery_slider_section',
	'active_callback' 	=> 'next_travel_is_gallery_slider_section_enable',
	'type'				=> 'text',
	) );

// Abort if selective refresh is not available.
if ( isset( $wp_customize->selective_refresh ) ) {
    $wp_customize->selective_refresh->add_partial( 'next_travel_theme_options[gallery_slider_btn_title]', array(
		'selector'            => '#gallery-slider-section div.read-more a',
		'settings'            => 'next_travel_theme_options[gallery_slider_btn_title]',
		'container_inclusive' => false,
		'fallback_refresh'    => true,
		'render_callback'     => 'next_travel_gallery_slider_btn_title_partial',
		) );
	}