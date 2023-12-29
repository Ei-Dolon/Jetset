<?php
/**
 * Recommended Destination Section options
 *
 * @package Theme Palace
 * @subpackage Next Travel
 * @since Next Travel 1.0.0
 */

// Add Recommended Destination section
$wp_customize->add_section( 'next_travel_recommended_destination_section', array(
	'title'             => esc_html__( 'Recommended Destination','next-travel' ),
	'description'       => esc_html__( 'Recommended Destination Section options.', 'next-travel' ),
	'panel'             => 'next_travel_front_page_panel',
) );

// Recommended Destination content enable control and setting
$wp_customize->add_setting( 'next_travel_theme_options[recommended_destination_section_enable]', array(
	'default'			=> 	$options['recommended_destination_section_enable'],
	'sanitize_callback' => 'next_travel_sanitize_switch_control',
) );

$wp_customize->add_control( new Next_Travel_Switch_Control( $wp_customize, 'next_travel_theme_options[recommended_destination_section_enable]', array(
	'label'             => esc_html__( 'Recommended Destination Section Enable', 'next-travel' ),
	'section'           => 'next_travel_recommended_destination_section',
	'on_off_label' 		=> next_travel_switch_options(),
) ) );

if ( isset( $wp_customize->selective_refresh ) ) {
    $wp_customize->selective_refresh->add_partial( 'next_travel_theme_options[recommended_destination_section_enable]', array(
		'selector'            => '#recommended-destinations div.wrapper',
		'settings'            => 'next_travel_theme_options[recommended_destination_section_enable]',
    ) );
}

// popular destination sub title setting and control
$wp_customize->add_setting( 'next_travel_theme_options[recommended_destination_sub_title]', array(
	'default'			=> $options['recommended_destination_sub_title'],
	'sanitize_callback' => 'sanitize_text_field',
	'transport'			=> 'postMessage',
) );

$wp_customize->add_control( 'next_travel_theme_options[recommended_destination_sub_title]', array(
	'label'           	=> esc_html__( 'Sub Title', 'next-travel' ),
	'section'        	=> 'next_travel_recommended_destination_section',
	'active_callback' 	=> 'next_travel_is_recommended_destination_section_enable',
	'type'				=> 'text',
) );

// Abort if selective refresh is not available.
if ( isset( $wp_customize->selective_refresh ) ) {
    $wp_customize->selective_refresh->add_partial( 'next_travel_theme_options[recommended_destination_sub_title]', array(
		'selector'            => '#recommended-destinations h3.section-subtitle',
		'settings'            => 'next_travel_theme_options[recommended_destination_sub_title]',
		'container_inclusive' => false,
		'fallback_refresh'    => true,
		'render_callback'     => 'next_travel_recommended_destination_sub_title_partial',
    ) );
}

// popular destination sub title setting and control
$wp_customize->add_setting( 'next_travel_theme_options[recommended_destination_title]', array(
	'default'			=> $options['recommended_destination_title'],
	'sanitize_callback' => 'sanitize_text_field',
	'transport'			=> 'postMessage',
) );

$wp_customize->add_control( 'next_travel_theme_options[recommended_destination_title]', array(
	'label'           	=> esc_html__( 'Title', 'next-travel' ),
	'section'        	=> 'next_travel_recommended_destination_section',
	'active_callback' 	=> 'next_travel_is_recommended_destination_section_enable',
	'type'				=> 'text',
) );

// Abort if selective refresh is not available.
if ( isset( $wp_customize->selective_refresh ) ) {
    $wp_customize->selective_refresh->add_partial( 'next_travel_theme_options[recommended_destination_title]', array(
		'selector'            => '#recommended-destinations h2.section-title',
		'settings'            => 'next_travel_theme_options[recommended_destination_title]',
		'container_inclusive' => false,
		'fallback_refresh'    => true,
		'render_callback'     => 'next_travel_recommended_destination_title_partial',
    ) );
}

// popular destination title setting and control
$wp_customize->add_setting( 'next_travel_theme_options[recommended_destination_description]', array(
	'default'			=> $options['recommended_destination_description'],
	'sanitize_callback' => 'next_travel_santize_allow_tag',
	'transport'			=> 'postMessage',
) );

$wp_customize->add_control( 'next_travel_theme_options[recommended_destination_description]', array(
	'label'           	=> esc_html__( 'Description', 'next-travel' ),
	'section'        	=> 'next_travel_recommended_destination_section',
	'active_callback' 	=> 'next_travel_is_recommended_destination_section_enable',
	'type'				=> 'textarea',
) );

// Abort if selective refresh is not available.
if ( isset( $wp_customize->selective_refresh ) ) {
    $wp_customize->selective_refresh->add_partial( 'next_travel_theme_options[recommended_destination_description]', array(
		'selector'            => '#recommended-destinations div.entry-content',
		'settings'            => 'next_travel_theme_options[recommended_destination_description]',
		'container_inclusive' => false,
		'fallback_refresh'    => true,
		'render_callback'     => 'next_travel_recommended_destination_description_partial',
    ) );
}

// Popular deatination btn label setting and control
$wp_customize->add_setting( 'next_travel_theme_options[recommended_destination_post_btn_label]', array(
	'sanitize_callback' => 'sanitize_text_field',
	'default'			=> $options['recommended_destination_post_btn_label'],
	'transport'			=> 'postMessage',
) );

$wp_customize->add_control( 'next_travel_theme_options[recommended_destination_post_btn_label]', array(
	'label'           	=> esc_html__( 'Post Button Label', 'next-travel' ),
	'section'        	=> 'next_travel_recommended_destination_section',
	'active_callback' 	=> 'next_travel_is_recommended_destination_section_enable',
	'type'				=> 'text',
) );

// Abort if selective refresh is not available.
if ( isset( $wp_customize->selective_refresh ) ) {
    $wp_customize->selective_refresh->add_partial( 'next_travel_theme_options[recommended_destination_post_btn_label]', array(
		'selector'            => '#recommended-destinations div.recommended-destination-item a.add-to-list',
		'settings'            => 'next_travel_theme_options[recommended_destination_post_btn_label]',
		'container_inclusive' => false,
		'fallback_refresh'    => true,
		'render_callback'     => 'next_travel_recommended_destination_post_btn_label_partial',
    ) );
}


// Recommended Destination content type control and setting
$wp_customize->add_setting( 'next_travel_theme_options[recommended_destination_content_type]', array(
	'default'          	=> $options['recommended_destination_content_type'],
	'sanitize_callback' => 'next_travel_sanitize_select',
) );

$wp_customize->add_control( 'next_travel_theme_options[recommended_destination_content_type]', array(
	'label'             => esc_html__( 'Content Type', 'next-travel' ),
	'section'           => 'next_travel_recommended_destination_section',
	'type'				=> 'select',
	'active_callback' 	=> 'next_travel_is_recommended_destination_section_enable',
	'choices'			=> next_travel_recommended_destination_content_type(),
) );

// Add dropdown category setting and control.
$wp_customize->add_setting(  'next_travel_theme_options[recommended_destination_content_category]', array(
	'sanitize_callback' => 'next_travel_sanitize_single_category',
) ) ;

$wp_customize->add_control( new Next_Travel_Dropdown_Taxonomies_Control( $wp_customize,'next_travel_theme_options[recommended_destination_content_category]', array(
	'label'             => esc_html__( 'Select Category', 'next-travel' ),
	'description'      	=> esc_html__( 'Note: Latest selected no of posts will be shown from selected category', 'next-travel' ),
	'section'           => 'next_travel_recommended_destination_section',
	'type'              => 'dropdown-taxonomies',
	'active_callback'	=> 'next_travel_is_recommended_destination_section_content_category_enable',
) ) );

// Add dropdown category setting and control.
$wp_customize->add_setting(  'next_travel_theme_options[recommended_destination_content_trip_types]', array(
	'sanitize_callback' => 'absint',
) ) ;

$wp_customize->add_control( new Next_Travel_Dropdown_Taxonomies_Control( $wp_customize,'next_travel_theme_options[recommended_destination_content_trip_types]', array(
	'label'             => esc_html__( 'Select Trip Types', 'next-travel' ),
	'section'           => 'next_travel_recommended_destination_section',
	'taxonomy'			=> 'itinerary_types',
	'type'              => 'dropdown-taxonomies',
	'active_callback'	=> 'next_travel_is_recommended_destination_section_content_trip_types_enable'
) ) );

// Popular deatination btn label setting and control
$wp_customize->add_setting( 'next_travel_theme_options[recommended_destination_btn_label]', array(
	'sanitize_callback' => 'sanitize_text_field',
	'default'			=> $options['recommended_destination_btn_label'],
	'transport'			=> 'postMessage',
) );

$wp_customize->add_control( 'next_travel_theme_options[recommended_destination_btn_label]', array(
	'label'           	=> esc_html__( 'Section Button Label', 'next-travel' ),
	'section'        	=> 'next_travel_recommended_destination_section',
	'active_callback' 	=> 'next_travel_is_recommended_destination_section_enable',
	'type'				=> 'text',
) );

// Abort if selective refresh is not available.
if ( isset( $wp_customize->selective_refresh ) ) {
    $wp_customize->selective_refresh->add_partial( 'next_travel_theme_options[recommended_destination_btn_label]', array(
		'selector'            => '#recommended-destinations div.read-more a',
		'settings'            => 'next_travel_theme_options[recommended_destination_btn_label]',
		'container_inclusive' => false,
		'fallback_refresh'    => true,
		'render_callback'     => 'next_travel_recommended_destination_btn_label_partial',
    ) );
}

// Popular deatination btn label setting and control
$wp_customize->add_setting( 'next_travel_theme_options[recommended_destination_btn_url]', array(
	'sanitize_callback' => 'esc_url_raw',
) );

$wp_customize->add_control( 'next_travel_theme_options[recommended_destination_btn_url]', array(
	'label'           	=> esc_html__( 'Section Button URL', 'next-travel' ),
	'section'        	=> 'next_travel_recommended_destination_section',
	'active_callback' 	=> 'next_travel_is_recommended_destination_section_enable',
	'type'				=> 'url',
) );
