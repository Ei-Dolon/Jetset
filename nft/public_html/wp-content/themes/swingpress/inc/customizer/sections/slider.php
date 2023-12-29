<?php
/**
 * Slider Section options
 *
 * @package Theme Palace
 * @subpackage Swingpress
 * @since Swingpress 1.0.0
 */

// Add Slider section
$wp_customize->add_section( 'swingpress_slider_section', array(
	'title'             => esc_html__( 'Main Slider','swingpress' ),
	'description'       => esc_html__( 'Slider Section options.', 'swingpress' ),
	'panel'             => 'swingpress_front_page_panel',
) );

// Slider content enable control and setting
$wp_customize->add_setting( 'swingpress_theme_options[slider_section_enable]', array(
	'default'			=> 	$options['slider_section_enable'],
	'sanitize_callback' => 'swingpress_sanitize_switch_control',
) );

$wp_customize->add_control( new Swingpress_Switch_Control( $wp_customize, 'swingpress_theme_options[slider_section_enable]', array(
	'label'             => esc_html__( 'Slider Section Enable', 'swingpress' ),
	'section'           => 'swingpress_slider_section',
	'on_off_label' 		=> swingpress_switch_options(),
) ) );

// Slider content enable control and setting
$wp_customize->add_setting( 'swingpress_theme_options[slider_autoplay]', array(
	'default'			=> 	$options['slider_autoplay'],
	'sanitize_callback' => 'swingpress_sanitize_switch_control',
) );

$wp_customize->add_control( new Swingpress_Switch_Control( $wp_customize, 'swingpress_theme_options[slider_autoplay]', array(
	'label'             => esc_html__( 'Auto Play Enable', 'swingpress' ),
	'section'           => 'swingpress_slider_section',
	'on_off_label' 		=> swingpress_switch_options(),
	'active_callback' 	=> 'swingpress_is_slider_section_enable',
) ) );

$wp_customize->add_setting( 'swingpress_theme_options[slider_design]', array(
	'sanitize_callback' => 'swingpress_sanitize_select',
	'default'			=> $options['slider_design'],
) );

$wp_customize->add_control( 'swingpress_theme_options[slider_design]', array(
	'label'           	=> esc_html__( 'Slider Design', 'swingpress' ),
	'section'        	=> 'swingpress_slider_section',
	'active_callback' 	=> 'swingpress_is_slider_section_enable',
	'type'				=> 'select',
	'choices'				=> array(
		'first-design'		=> 'first-design',
		'second-design'		=> 'second-design'
	),
) );

$wp_customize->add_setting( 'swingpress_theme_options[slider_content_color]', array(
	'sanitize_callback' => 'sanitize_hex_color', // The hue is stored as a positive integer.
) );

$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'swingpress_theme_options[slider_content_color]', array(
	'label'           	=> esc_html__( 'Slider Contet Color', 'swingpress' ),
	'section'  => 'swingpress_slider_section',
) ) );


// Slider btn label setting and control
$wp_customize->add_setting( 'swingpress_theme_options[slider_btn_label]', array(
	'sanitize_callback' => 'sanitize_text_field',
	'default'			=> $options['slider_btn_label'],
) );

$wp_customize->add_control( 'swingpress_theme_options[slider_btn_label]', array(
	'label'           	=> esc_html__( 'Slider Button Label', 'swingpress' ),
	'section'        	=> 'swingpress_slider_section',
	'active_callback' 	=> 'swingpress_is_slider_section_enable',
	'type'				=> 'text',
) );

if ( isset( $wp_customize->selective_refresh ) ) {
    $wp_customize->selective_refresh->add_partial( 'swingpress_theme_options[slider_btn_label]', array(
		'selector'            => '#featured-slider-section .read-more a',
		'settings'            => 'swingpress_theme_options[slider_btn_label]',
		'container_inclusive' => false,
		'fallback_refresh'    => true,
		'render_callback'     => 'swingpress_slider_btn_label_partial',
    ) );
}


// Slider content type control and setting
$wp_customize->add_setting( 'swingpress_theme_options[slider_content_type]', array(
	'default'          	=> $options['slider_content_type'],
	'sanitize_callback' => 'swingpress_sanitize_select',
) );

$wp_customize->add_control( 'swingpress_theme_options[slider_content_type]', array(
	'label'             => esc_html__( 'Content Type', 'swingpress' ),
	'section'           => 'swingpress_slider_section',
	'type'				=> 'select',
	'active_callback' 	=> 'swingpress_is_slider_section_enable',
	'choices'			=> swingpress_main_slider_content_type(),
) );

for ( $i = 1; $i <= 3; $i++ ) :

	$wp_customize->add_setting( 'swingpress_theme_options[slider_label_'.$i.']', array(
		'sanitize_callback' => 'sanitize_text_field',
	) );

	$wp_customize->add_control( 'swingpress_theme_options[slider_label_'.$i.']', array(
		'label'           	=> esc_html__( 'Slider Label ', 'swingpress' ).$i,
		'section'        	=> 'swingpress_slider_section',
		'active_callback' 	=> 'swingpress_is_slider_section_enable',
		'type'				=> 'text',
	) );

	// slider pages drop down chooser control and setting
	$wp_customize->add_setting( 'swingpress_theme_options[slider_content_page_' . $i . ']', array(
		'sanitize_callback' => 'swingpress_sanitize_page',
	) );

	$wp_customize->add_control( new Swingpress_Dropdown_Chooser( $wp_customize, 'swingpress_theme_options[slider_content_page_' . $i . ']', array(
		'label'             => sprintf( esc_html__( 'Select Page %d', 'swingpress' ), $i ),
		'section'           => 'swingpress_slider_section',
		'choices'			=> swingpress_page_choices(),
		'active_callback'	=> 'swingpress_is_slider_section_content_page_enable',
	) ) );


	$wp_customize->add_setting( 'swingpress_theme_options[slider_content_trip_' . $i . ']', array(
		'sanitize_callback' => 'swingpress_sanitize_page',
	) );

	$wp_customize->add_control( new Swingpress_Dropdown_Chooser( $wp_customize, 'swingpress_theme_options[slider_content_trip_' . $i . ']', array(
		'label'             => sprintf ( esc_html__( 'Select Trip %d', 'swingpress' ), $i ),
		'section'           => 'swingpress_slider_section',
		'choices'			=> swingpress_trip_choices(),
		'active_callback'	=> 'swingpress_is_slider_section_content_trip_enable',
	) ) );
endfor;
