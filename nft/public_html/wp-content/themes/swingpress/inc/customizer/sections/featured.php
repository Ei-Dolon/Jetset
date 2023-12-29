<?php
/**
 * Featured Section options
 *
 * @package Theme Palace
 * @subpackage Swingpress
 * @since Swingpress 1.0.0
 */

// Add Featured section
$wp_customize->add_section( 'swingpress_featured_section', array(
	'title'             => esc_html__( 'Featured','swingpress' ),
	'description'       => esc_html__( 'Featured Section options.', 'swingpress' ),
	'panel'             => 'swingpress_front_page_panel',
) );

// Featured content enable control and setting
$wp_customize->add_setting( 'swingpress_theme_options[featured_section_enable]', array(
	'default'			=> 	$options['featured_section_enable'],
	'sanitize_callback' => 'swingpress_sanitize_switch_control',
) );

$wp_customize->add_control( new Swingpress_Switch_Control( $wp_customize, 'swingpress_theme_options[featured_section_enable]', array(
	'label'             => esc_html__( 'Featured Section Enable', 'swingpress' ),
	'section'           => 'swingpress_featured_section',
	'on_off_label' 		=> swingpress_switch_options(),
) ) );


// popular destination read more setting and control
$wp_customize->add_setting( 'swingpress_theme_options[featured_title]', array(
	'default'			=> $options['featured_title'],
	'sanitize_callback' => 'sanitize_text_field',
	'transport'			=> 'postMessage',
) );

$wp_customize->add_control( 'swingpress_theme_options[featured_title]', array(
	'label'           	=> esc_html__( 'Title', 'swingpress' ),
	'section'        	=> 'swingpress_featured_section',
	'active_callback' 	=> 'swingpress_is_featured_section_enable',
	'type'				=> 'text',
) );

// Abort if selective refresh is not available.
if ( isset( $wp_customize->selective_refresh ) ) {
    $wp_customize->selective_refresh->add_partial( 'swingpress_theme_options[featured_title]', array(
		'selector'            => '#featured .section-header h2.section-title',
		'settings'            => 'swingpress_theme_options[featured_title]',
		'container_inclusive' => false,
		'fallback_refresh'    => true,
		'render_callback'     => 'swingpress_featured_title_partial',
    ) );
}


$wp_customize->add_setting( 'swingpress_theme_options[featured_sub_title]', array(
	'default'			=> $options['featured_sub_title'],
	'sanitize_callback' => 'sanitize_text_field',
	'transport'			=> 'postMessage',
) );

$wp_customize->add_control( 'swingpress_theme_options[featured_sub_title]', array(
	'label'           	=> esc_html__( 'Title', 'swingpress' ),
	'section'        	=> 'swingpress_featured_section',
	'active_callback' 	=> 'swingpress_is_featured_section_enable',
	'type'				=> 'text',
) );

// Abort if selective refresh is not available.
if ( isset( $wp_customize->selective_refresh ) ) {
    $wp_customize->selective_refresh->add_partial( 'swingpress_theme_options[featured_sub_title]', array(
		'selector'            => '#featured .section-header p',
		'settings'            => 'swingpress_theme_options[featured_sub_title]',
		'container_inclusive' => false,
		'fallback_refresh'    => true,
		'render_callback'     => 'swingpress_featured_sub_title_partial',
    ) );
}


// Featured content type control and setting
$wp_customize->add_setting( 'swingpress_theme_options[featured_content_type]', array(
	'default'          	=> $options['featured_content_type'],
	'sanitize_callback' => 'swingpress_sanitize_select',
) );

$wp_customize->add_control( 'swingpress_theme_options[featured_content_type]', array(
	'label'             => esc_html__( 'Content Type', 'swingpress' ),
	'section'           => 'swingpress_featured_section',
	'type'				=> 'select',
	'active_callback' 	=> 'swingpress_is_featured_section_enable',
	'choices'			=> swingpress_featured_content_type(),
) );

for ( $i=1; $i <= 3; $i++ ) { 
	// Add dropdown category setting and control.
	$wp_customize->add_setting(  'swingpress_theme_options[featured_content_category_' . $i . ']', array(
		'sanitize_callback' => 'swingpress_sanitize_single_category',
	) ) ;

	$wp_customize->add_control( new Swingpress_Dropdown_Taxonomies_Control( $wp_customize,'swingpress_theme_options[featured_content_category_' . $i . ']', array(
		'label'             => sprintf( esc_html__( 'Select Category %d', 'swingpress'), $i ),
		'description'      	=> esc_html__( 'Note: Latest selected no of posts will be shown from selected category', 'swingpress' ),
		'section'           => 'swingpress_featured_section',
		'type'              => 'dropdown-taxonomies',
		'active_callback'	=> 'swingpress_is_featured_section_content_category_enable'
	) ) );

	// Add dropdown category setting and control.
	$wp_customize->add_setting(  'swingpress_theme_options[featured_content_trip_types_' . $i . ']', array(
		'sanitize_callback' => 'absint',
	) ) ;

	$wp_customize->add_control( new Swingpress_Dropdown_Taxonomies_Control( $wp_customize,'swingpress_theme_options[featured_content_trip_types_' . $i . ']', array(
		'label'             => sprintf( esc_html__( 'Select Trip Types %d', 'swingpress'), $i ),
		'section'           => 'swingpress_featured_section',
		'taxonomy'			=> 'itinerary_types',
		'type'              => 'dropdown-taxonomies',
		'active_callback'	=> 'swingpress_is_featured_section_content_trip_types_enable'
	) ) );

	// Featured image enable control and setting
	$wp_customize->add_setting( 'swingpress_theme_options[featured_background_image_' . $i . ']', array(
		'sanitize_callback' => 'swingpress_sanitize_image',
	) );

	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'swingpress_theme_options[featured_background_image_' . $i . ']',
			array(
			'label'       		=> sprintf( esc_html__( 'Category Image %d', 'swingpress' ), $i ),
			'description' 		=> sprintf( esc_html__( 'Recommended size: %1$dpx x %2$dpx ', 'swingpress' ), 500, 500 ),
			'section'     		=> 'swingpress_featured_section',
			'active_callback'	=> 'swingpress_is_featured_section_content_category_enable',
	) ) );
}


