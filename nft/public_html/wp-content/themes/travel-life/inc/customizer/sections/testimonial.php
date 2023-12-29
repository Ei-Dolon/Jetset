<?php
/**
 * Testimonial Section options
 *
 * @package Theme Palace
 * @subpackage Travel Life
 * @since Travel Life 1.0.0
 */

// Add Testimonial section
$wp_customize->add_section( 'travel_life_testimonial_section', array(
	'title'             => esc_html__( 'Testimonial','travel-life' ),
	'description'       => esc_html__( 'Testimonial Section options.', 'travel-life' ),
	'panel'             => 'travel_life_front_page_panel',
) );

// Testimonial content enable control and setting
$wp_customize->add_setting( 'travel_life_theme_options[testimonial_section_enable]', array(
	'default'			=> 	$options['testimonial_section_enable'],
	'sanitize_callback' => 'travel_life_sanitize_switch_control',
) );

$wp_customize->add_control( new Travel_Life_Switch_Control( $wp_customize, 'travel_life_theme_options[testimonial_section_enable]', array(
	'label'             => esc_html__( 'Testimonial Section Enable', 'travel-life' ),
	'section'           => 'travel_life_testimonial_section',
	'on_off_label' 		=> travel_life_switch_options(),
) ) );

if ( isset( $wp_customize->selective_refresh ) ) {
    $wp_customize->selective_refresh->add_partial( 'travel_life_theme_options[testimonial_section_enable]', array(
		'selector'            => '#testimonial-section .tooltiptext',
		'settings'            => 'travel_life_theme_options[testimonial_section_enable]',
    ) );
}

// Testimonial auto play enable control and setting
$wp_customize->add_setting( 'travel_life_theme_options[testimonial_auto_play]', array(
	'default'			=> 	$options['testimonial_auto_play'],
	'sanitize_callback' => 'travel_life_sanitize_switch_control',
) );

$wp_customize->add_control( new Travel_Life_Switch_Control( $wp_customize, 'travel_life_theme_options[testimonial_auto_play]', array(
	'label'             => esc_html__( 'Auto Play Enable', 'travel-life' ),
	'section'           => 'travel_life_testimonial_section',
	'on_off_label' 		=> travel_life_switch_options(),
) ) );

$wp_customize->add_setting( 'travel_life_theme_options[testimonial_section_title]', array(
	'default'          	=> $options['testimonial_section_title'],
	'sanitize_callback' => 'sanitize_text_field',
	'transport'			=> 'postMessage',
) );

$wp_customize->add_control( 'travel_life_theme_options[testimonial_section_title]', array(
	'label'             => esc_html__( 'Section Title', 'travel-life' ),
	'section'           => 'travel_life_testimonial_section',
	'type'				=> 'text',
	'active_callback' 	=> 'travel_life_is_testimonial_section_enable',
) );



$wp_customize->add_setting( 'travel_life_theme_options[testimonial_section_btn_text]', array(
	'default'          	=> $options['testimonial_section_btn_text'],
	'sanitize_callback' => 'sanitize_text_field',
	'transport'			=> 'postMessage',
) );

$wp_customize->add_control( 'travel_life_theme_options[testimonial_section_btn_text]', array(
	'label'             => esc_html__( 'Button text', 'travel-life' ),
	'section'           => 'travel_life_testimonial_section',
	'type'				=> 'text',
	'active_callback' 	=> 'travel_life_is_testimonial_section_enable',
) );

// testimonial btn link setting and control
$wp_customize->add_setting( 'travel_life_theme_options[testimonial_btn_link]', array(
	'sanitize_callback' => 'esc_url_raw',
) );

$wp_customize->add_control( 'travel_life_theme_options[testimonial_btn_link]', array(
	'label'           	=> esc_html__( 'Button Link', 'travel-life' ),
	'section'        	=> 'travel_life_testimonial_section',
	'active_callback' 	=> 'travel_life_is_testimonial_section_enable',
	'type'				=> 'url',
) );



for ( $i = 1; $i <= 5; $i++ ) :
	// testimonial pages drop down chooser control and setting
	$wp_customize->add_setting( 'travel_life_theme_options[testimonial_content_page_' . $i . ']', array(
		'sanitize_callback' => 'travel_life_sanitize_page',
	) );

	$wp_customize->add_control( new Travel_Life_Dropdown_Chooser( $wp_customize, 'travel_life_theme_options[testimonial_content_page_' . $i . ']', array(
		'label'             => sprintf( esc_html__( 'Select Page %d', 'travel-life' ), $i ),
		'section'           => 'travel_life_testimonial_section',
		'choices'			=> travel_life_page_choices(),
		'active_callback'	=> 'travel_life_is_testimonial_section_enable',
	) ) );

endfor;

