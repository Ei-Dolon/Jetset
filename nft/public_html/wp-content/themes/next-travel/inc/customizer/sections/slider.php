<?php
/**
 * Slider Section options
 *
 * @package Theme Palace
 * @subpackage Next Travel
 * @since Next Travel 1.0.0
 */

// Add Slider section
$wp_customize->add_section( 'next_travel_slider_section', array(
	'title'             => esc_html__( 'Main Slider','next-travel' ),
	'description'       => esc_html__( 'Slider Section options.', 'next-travel' ),
	'panel'             => 'next_travel_front_page_panel',
) );

// Slider section enable control and setting
$wp_customize->add_setting( 'next_travel_theme_options[slider_section_enable]', array(
	'default'			=> 	$options['slider_section_enable'],
	'sanitize_callback' => 'next_travel_sanitize_switch_control',
) );

$wp_customize->add_control( new Next_Travel_Switch_Control( $wp_customize, 'next_travel_theme_options[slider_section_enable]', array(
	'label'             => esc_html__( 'Slider Section Enable', 'next-travel' ),
	'section'           => 'next_travel_slider_section',
	'on_off_label' 		=> next_travel_switch_options(),
) ) );

if ( isset( $wp_customize->selective_refresh ) ) {
    $wp_customize->selective_refresh->add_partial( 'next_travel_theme_options[slider_section_enable]', array(
		'selector'            => '#featured-slider-section div.wrapper',
		'settings'            => 'next_travel_theme_options[slider_section_enable]',
    ) );
}


for ( $i = 1; $i <= 3; $i++ ) :
	// slider pages drop down chooser control and setting
	$wp_customize->add_setting( 'next_travel_theme_options[slider_content_page_' . $i . ']', array(
		'sanitize_callback' => 'next_travel_sanitize_page',
	) );

	$wp_customize->add_control( new Next_Travel_Dropdown_Chooser( $wp_customize, 'next_travel_theme_options[slider_content_page_' . $i . ']', array(
		'label'             => sprintf( esc_html__( 'Select Page %d', 'next-travel' ), $i ),
		'section'           => 'next_travel_slider_section',
		'choices'			=> next_travel_page_choices(),
		'active_callback'	=> 'next_travel_is_slider_section_enable',
	) ) );

endfor;


