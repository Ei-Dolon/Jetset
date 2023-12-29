<?php
/**
 * Service Section options
 *
 * @package Theme Palace
 * @subpackage Swingpress
 * @since Swingpress 1.0.0
 */

// Add Service section
$wp_customize->add_section( 'swingpress_service_section', array(
	'title'             => esc_html__( 'Services','swingpress' ),
	'description'       => esc_html__( 'Services Section options.', 'swingpress' ),
	'panel'             => 'swingpress_front_page_panel',
) );

// Service content enable control and setting
$wp_customize->add_setting( 'swingpress_theme_options[service_section_enable]', array(
	'default'			=> 	$options['service_section_enable'],
	'sanitize_callback' => 'swingpress_sanitize_switch_control',
) );

$wp_customize->add_control( new Swingpress_Switch_Control( $wp_customize, 'swingpress_theme_options[service_section_enable]', array(
	'label'             => esc_html__( 'Service Section Enable', 'swingpress' ),
	'section'           => 'swingpress_service_section',
	'on_off_label' 		=> swingpress_switch_options(),
) ) );


// service title setting and control
$wp_customize->add_setting( 'swingpress_theme_options[service_title]', array(
	'sanitize_callback' => 'sanitize_text_field',
	'default'			=> $options['service_title'],
	'transport'			=> 'postMessage',
) );

$wp_customize->add_control( 'swingpress_theme_options[service_title]', array(
	'label'           	=> esc_html__( 'Title', 'swingpress' ),
	'section'        	=> 'swingpress_service_section',
	'active_callback' 	=> 'swingpress_is_service_section_enable',
	'type'				=> 'text',
) );

// Abort if selective refresh is not available.
if ( isset( $wp_customize->selective_refresh ) ) {
    $wp_customize->selective_refresh->add_partial( 'swingpress_theme_options[service_title]', array(
		'selector'            => '#service-section .section-header h2',
		'settings'            => 'swingpress_theme_options[service_title]',
		'container_inclusive' => false,
		'fallback_refresh'    => true,
		'render_callback'     => 'swingpress_service_title_partial',
    ) );
}

// service title setting and control
$wp_customize->add_setting( 'swingpress_theme_options[service_sub_title]', array(
	'sanitize_callback' => 'sanitize_text_field',
	'default'			=> $options['service_sub_title'],
	'transport'			=> 'postMessage',
) );

$wp_customize->add_control( 'swingpress_theme_options[service_sub_title]', array(
	'label'           	=> esc_html__( 'Title', 'swingpress' ),
	'section'        	=> 'swingpress_service_section',
	'active_callback' 	=> 'swingpress_is_service_section_enable',
	'type'				=> 'text',
) );

// Abort if selective refresh is not available.
if ( isset( $wp_customize->selective_refresh ) ) {
    $wp_customize->selective_refresh->add_partial( 'swingpress_theme_options[service_sub_title]', array(
		'selector'            => '#service-section .section-header p',
		'settings'            => 'swingpress_theme_options[service_sub_title]',
		'container_inclusive' => false,
		'fallback_refresh'    => true,
		'render_callback'     => 'swingpress_service_sub_title_partial',
    ) );
}


$wp_customize->add_setting( 'swingpress_theme_options[service_section_icon_enable]', array(
	'default'			=> 	$options['service_section_icon_enable'],
	'sanitize_callback' => 'swingpress_sanitize_switch_control',
) );

$wp_customize->add_control( new Swingpress_Switch_Control( $wp_customize, 'swingpress_theme_options[service_section_icon_enable]', array(
	'label'             => esc_html__( 'Service Section Icon Enable', 'swingpress' ),
	'section'           => 'swingpress_service_section',
	'active_callback'   => 'swingpress_is_service_section_enable',
	'on_off_label' 		=> swingpress_switch_options(),
) ) );


for ( $i = 1; $i <= 3; $i++ ) :

	// service note control and setting
	$wp_customize->add_setting( 'swingpress_theme_options[service_content_icon_' . $i . ']', array(
		'sanitize_callback' => 'sanitize_text_field',
	) );

	$wp_customize->add_control( new Swingpress_Icon_Picker( $wp_customize, 'swingpress_theme_options[service_content_icon_' . $i . ']', array(
		'label'             => sprintf( esc_html__( 'Select Icon %d', 'swingpress' ), $i ),
		'section'           => 'swingpress_service_section',
		'active_callback'	=> 'swingpress_is_service_section_enable',
	) ) );

	// service pages drop down chooser control and setting
	$wp_customize->add_setting( 'swingpress_theme_options[service_content_page_' . $i . ']', array(
		'sanitize_callback' => 'swingpress_sanitize_page',
	) );

	$wp_customize->add_control( new Swingpress_Dropdown_Chooser( $wp_customize, 'swingpress_theme_options[service_content_page_' . $i . ']', array(
		'label'             => sprintf( esc_html__( 'Select Page %d', 'swingpress' ), $i ),
		'section'           => 'swingpress_service_section',
		'choices'			=> swingpress_page_choices(),
		'active_callback'	=> 'swingpress_is_service_section_enable',
	) ) );


	// service hr setting and control
	$wp_customize->add_setting( 'swingpress_theme_options[service_hr_'. $i .']', array(
		'sanitize_callback' => 'sanitize_text_field'
	) );

	$wp_customize->add_control( new Swingpress_Customize_Horizontal_Line( $wp_customize, 'swingpress_theme_options[service_hr_'. $i .']',
		array(
			'section'         => 'swingpress_service_section',
			'active_callback' => 'swingpress_is_service_section_enable',
			'type'			  => 'hr'
	) ) );

endfor;
