<?php
/**
 * Slider Section options
 *
 * @package Theme Palace
 * @subpackage Travel Life
 * @since Travel Life 1.0.0
 */

// Add Slider section
$wp_customize->add_section( 'travel_life_slider_section', array(
	'title'             => esc_html__( 'Main Slider','travel-life' ),
	'description'       => esc_html__( 'Slider Section options.', 'travel-life' ),
	'panel'             => 'travel_life_front_page_panel',
) );

// Slider content enable control and setting
$wp_customize->add_setting( 'travel_life_theme_options[slider_section_enable]', array(
	'default'			=> 	$options['slider_section_enable'],
	'sanitize_callback' => 'travel_life_sanitize_switch_control',
) );

$wp_customize->add_control( new Travel_Life_Switch_Control( $wp_customize, 'travel_life_theme_options[slider_section_enable]', array(
	'label'             => esc_html__( 'Slider Section Enable', 'travel-life' ),
	'section'           => 'travel_life_slider_section',
	'on_off_label' 		=> travel_life_switch_options(),
) ) );

if ( isset( $wp_customize->selective_refresh ) ) {
    $wp_customize->selective_refresh->add_partial( 'travel_life_theme_options[slider_section_enable]', array(
		'selector'            => '#featured-slider-section .tooltiptext',
		'settings'            => 'travel_life_theme_options[slider_section_enable]',
    ) );
}

// Slider content enable control and setting
$wp_customize->add_setting( 'travel_life_theme_options[slider_section_enable_clould]', array(
	'default'			=> 	$options['slider_section_enable_clould'],
	'sanitize_callback' => 'travel_life_sanitize_switch_control',
) );

$wp_customize->add_control( new Travel_Life_Switch_Control( $wp_customize, 'travel_life_theme_options[slider_section_enable_clould]', array(
	'label'             => esc_html__( 'Slider Section Cloud Enable', 'travel-life' ),
	'section'           => 'travel_life_slider_section',
	'on_off_label' 		=> travel_life_switch_options(),
) ) );


// Slider content enable control and setting
$wp_customize->add_setting( 'travel_life_theme_options[slider_autoplay]', array(
	'default'			=> 	$options['slider_autoplay'],
	'sanitize_callback' => 'travel_life_sanitize_switch_control',
) );

$wp_customize->add_control( new Travel_Life_Switch_Control( $wp_customize, 'travel_life_theme_options[slider_autoplay]', array(
	'label'             => esc_html__( 'Auto Play Enable', 'travel-life' ),
	'section'           => 'travel_life_slider_section',
	'on_off_label' 		=> travel_life_switch_options(),
	'active_callback' 	=> 'travel_life_is_slider_section_enable',
) ) );

// Slider btn label setting and control
$wp_customize->add_setting( 'travel_life_theme_options[slider_btn_label]', array(
	'sanitize_callback' => 'sanitize_text_field',
	'default'			=> $options['slider_btn_label'],
) );

$wp_customize->add_control( 'travel_life_theme_options[slider_btn_label]', array(
	'label'           	=> esc_html__( 'Slider Button Label', 'travel-life' ),
	'section'        	=> 'travel_life_slider_section',
	'active_callback' 	=> 'travel_life_is_slider_section_enable',
	'type'				=> 'text',
) );


for ( $i = 1; $i <= 3; $i++ ) :
	// slider pages drop down chooser control and setting
	$wp_customize->add_setting( 'travel_life_theme_options[slider_content_page_' . $i . ']', array(
		'sanitize_callback' => 'travel_life_sanitize_page',
	) );

	$wp_customize->add_control( new Travel_Life_Dropdown_Chooser( $wp_customize, 'travel_life_theme_options[slider_content_page_' . $i . ']', array(
		'label'             => sprintf( esc_html__( 'Select Page %d', 'travel-life' ), $i ),
		'section'           => 'travel_life_slider_section',
		'choices'			=> travel_life_page_choices(),
		'active_callback'	=> 'travel_life_is_slider_section_enable',
	) ) );

endfor;
