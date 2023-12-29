<?php
/**
 * Popular Destination Section options
 *
 * @package Theme Palace
 * @subpackage Swingpress
 * @since Swingpress 1.0.0
 */

// Add Popular Destination section
$wp_customize->add_section( 'swingpress_popular_destination_section', array(
	'title'             => esc_html__( 'Popular Destination','swingpress' ),
	'description'       => esc_html__( 'Popular Destination Section options.', 'swingpress' ),
	'panel'             => 'swingpress_front_page_panel',
) );

// Popular Destination content enable control and setting
$wp_customize->add_setting( 'swingpress_theme_options[popular_destination_section_enable]', array(
	'default'			=> 	$options['popular_destination_section_enable'],
	'sanitize_callback' => 'swingpress_sanitize_switch_control',
) );

$wp_customize->add_control( new Swingpress_Switch_Control( $wp_customize, 'swingpress_theme_options[popular_destination_section_enable]', array(
	'label'             => esc_html__( 'Popular Destination Section Enable', 'swingpress' ),
	'section'           => 'swingpress_popular_destination_section',
	'on_off_label' 		=> swingpress_switch_options(),
) ) );

$wp_customize->add_setting( 'swingpress_theme_options[popular_destination_auto_slide_enable]', array(
	'default'			=> 	$options['popular_destination_auto_slide_enable'],
	'sanitize_callback' => 'swingpress_sanitize_switch_control',
) );

$wp_customize->add_control( new Swingpress_Switch_Control( $wp_customize, 'swingpress_theme_options[popular_destination_auto_slide_enable]', array(
	'label'             => esc_html__( 'Popular Destination Auto Slide Enable', 'swingpress' ),
	'section'           => 'swingpress_popular_destination_section',
	'on_off_label' 		=> swingpress_switch_options(),
	'active_callback' 	=> 'swingpress_is_popular_destination_section_enable',
) ) );


// popular destination title setting and control
$wp_customize->add_setting( 'swingpress_theme_options[popular_destination_title]', array(
	'default'			=> $options['popular_destination_title'],
	'sanitize_callback' => 'sanitize_text_field',
	'transport'			=> 'postMessage',
) );

$wp_customize->add_control( 'swingpress_theme_options[popular_destination_title]', array(
	'label'           	=> esc_html__( 'Title', 'swingpress' ),
	'section'        	=> 'swingpress_popular_destination_section',
	'active_callback' 	=> 'swingpress_is_popular_destination_section_enable',
	'type'				=> 'text',
) );

// Abort if selective refresh is not available.
if ( isset( $wp_customize->selective_refresh ) ) {
    $wp_customize->selective_refresh->add_partial( 'swingpress_theme_options[popular_destination_title]', array(
		'selector'            => '#interest .section-header h2.section-title',
		'settings'            => 'swingpress_theme_options[popular_destination_title]',
		'container_inclusive' => false,
		'fallback_refresh'    => true,
		'render_callback'     => 'swingpress_popular_destination_title_partial',
    ) );
}

// popular destination title setting and control
$wp_customize->add_setting( 'swingpress_theme_options[popular_destination_sub_title]', array(
	'default'			=> $options['popular_destination_sub_title'],
	'sanitize_callback' => 'sanitize_text_field',
	'transport'			=> 'postMessage',
) );

$wp_customize->add_control( 'swingpress_theme_options[popular_destination_sub_title]', array(
	'label'           	=> esc_html__( 'Title', 'swingpress' ),
	'section'        	=> 'swingpress_popular_destination_section',
	'active_callback' 	=> 'swingpress_is_popular_destination_section_enable',
	'type'				=> 'text',
) );

// Abort if selective refresh is not available.
if ( isset( $wp_customize->selective_refresh ) ) {
    $wp_customize->selective_refresh->add_partial( 'swingpress_theme_options[popular_destination_sub_title]', array(
		'selector'            => '#interest .section-header p',
		'settings'            => 'swingpress_theme_options[popular_destination_sub_title]',
		'container_inclusive' => false,
		'fallback_refresh'    => true,
		'render_callback'     => 'swingpress_popular_destination_sub_title_partial',
    ) );
}

// Popular Destination content type control and setting
$wp_customize->add_setting( 'swingpress_theme_options[popular_destination_content_type]', array(
	'default'          	=> $options['popular_destination_content_type'],
	'sanitize_callback' => 'swingpress_sanitize_select',
) );

$wp_customize->add_control( 'swingpress_theme_options[popular_destination_content_type]', array(
	'label'             => esc_html__( 'Content Type', 'swingpress' ),
	'section'           => 'swingpress_popular_destination_section',
	'type'				=> 'select',
	'active_callback' 	=> 'swingpress_is_popular_destination_section_enable',
	'choices'			=> swingpress_popular_destination_content_type(),
) );

for ( $i=1; $i <= 10; $i++ ) { 

	// popular_destination posts drop down chooser control and setting
	$wp_customize->add_setting( 'swingpress_theme_options[popular_destination_content_post_' . $i . ']', array(
		'sanitize_callback' => 'swingpress_sanitize_page',
	) );

	$wp_customize->add_control( new Swingpress_Dropdown_Chooser( $wp_customize, 'swingpress_theme_options[popular_destination_content_post_' . $i . ']', array(
		'label'             => sprintf ( esc_html__( 'Select posts %d', 'swingpress' ), $i ),
		'section'           => 'swingpress_popular_destination_section',
		'choices'			=> swingpress_post_choices(),
		'active_callback'	=> 'swingpress_is_popular_destination_section_content_post_enable',
	) ) );

	// popular_destination trips drop down chooser control and setting
	$wp_customize->add_setting( 'swingpress_theme_options[popular_destination_content_trip_' . $i . ']', array(
		'sanitize_callback' => 'swingpress_sanitize_page',
	) );

	$wp_customize->add_control( new Swingpress_Dropdown_Chooser( $wp_customize, 'swingpress_theme_options[popular_destination_content_trip_' . $i . ']', array(
		'label'             => sprintf ( esc_html__( 'Select Trip %d', 'swingpress' ), $i ),
		'section'           => 'swingpress_popular_destination_section',
		'choices'			=> swingpress_trip_choices(),
		'active_callback'	=> 'swingpress_is_popular_destination_section_content_trip_enable',
	) ) );
}
