<?php
/**
 * Testimonial Section options
 *
 * @package Theme Palace
 * @subpackage Swingpress
 * @since Swingpress 1.0.0
 */

// Add Testimonial section
$wp_customize->add_section( 'swingpress_testimonial_section', array(
	'title'             => esc_html__( 'Testimonial','swingpress' ),
	'description'       => esc_html__( 'Testimonial Section options.', 'swingpress' ),
	'panel'             => 'swingpress_front_page_panel',
) );

// Testimonial content enable control and setting
$wp_customize->add_setting( 'swingpress_theme_options[testimonial_section_enable]', array(
	'default'			=> 	$options['testimonial_section_enable'],
	'sanitize_callback' => 'swingpress_sanitize_switch_control',
) );

$wp_customize->add_control( new Swingpress_Switch_Control( $wp_customize, 'swingpress_theme_options[testimonial_section_enable]', array(
	'label'             => esc_html__( 'Testimonial Section Enable', 'swingpress' ),
	'section'           => 'swingpress_testimonial_section',
	'on_off_label' 		=> swingpress_switch_options(),
) ) );

// Testimonial auto play enable control and setting
$wp_customize->add_setting( 'swingpress_theme_options[testimonial_auto_play]', array(
	'default'			=> 	$options['testimonial_auto_play'],
	'sanitize_callback' => 'swingpress_sanitize_switch_control',
) );

$wp_customize->add_control( new Swingpress_Switch_Control( $wp_customize, 'swingpress_theme_options[testimonial_auto_play]', array(
	'label'             => esc_html__( 'Auto Play Enable', 'swingpress' ),
	'section'           => 'swingpress_testimonial_section',
	'on_off_label' 		=> swingpress_switch_options(),
	'active_callback' 	=> 'swingpress_is_testimonial_section_enable',
) ) );

$wp_customize->add_setting( 'swingpress_theme_options[testimonial_image]', array(
	'sanitize_callback' => 'swingpress_sanitize_image'
) );

$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'swingpress_theme_options[testimonial_image]',
		array(
		'label'       		=> esc_html__( 'Testimonial Image', 'swingpress' ),
		'section'     		=> 'swingpress_testimonial_section',
		'active_callback' 	=> 'swingpress_is_testimonial_section_enable',
) ) );


for ( $i = 1; $i <= 5; $i++ ) :
	// testimonial pages drop down chooser control and setting
	$wp_customize->add_setting( 'swingpress_theme_options[testimonial_content_page_' . $i . ']', array(
		'sanitize_callback' => 'swingpress_sanitize_page',
	) );

	$wp_customize->add_control( new Swingpress_Dropdown_Chooser( $wp_customize, 'swingpress_theme_options[testimonial_content_page_' . $i . ']', array(
		'label'             => sprintf( esc_html__( 'Select Page %d', 'swingpress' ), $i ),
		'section'           => 'swingpress_testimonial_section',
		'choices'			=> swingpress_page_choices(),
		'active_callback'	=> 'swingpress_is_testimonial_section_enable',
	) ) );
endfor;

