<?php
/**
 * Blog Section options
 *
 * @package Theme Palace
 * @subpackage Next Travel
 * @since Next Travel 1.0.0
 */

// Add Blog section
$wp_customize->add_section( 'next_travel_blog_section', array(
	'title'             => esc_html__( 'Blog','next-travel' ),
	'description'       => esc_html__( 'Blog Section options.', 'next-travel' ),
	'panel'             => 'next_travel_front_page_panel',
) );

// Blog content enable control and setting
$wp_customize->add_setting( 'next_travel_theme_options[blog_section_enable]', array(
	'default'			=> 	$options['blog_section_enable'],
	'sanitize_callback' => 'next_travel_sanitize_switch_control',
) );

$wp_customize->add_control( new Next_Travel_Switch_Control( $wp_customize, 'next_travel_theme_options[blog_section_enable]', array(
	'label'             => esc_html__( 'Blog Section Enable', 'next-travel' ),
	'section'           => 'next_travel_blog_section',
	'on_off_label' 		=> next_travel_switch_options(),
) ) );

if ( isset( $wp_customize->selective_refresh ) ) {
    $wp_customize->selective_refresh->add_partial( 'next_travel_theme_options[blog_section_enable]', array(
		'selector'            => '#latest-posts div.post-wrapper',
		'settings'            => 'next_travel_theme_options[blog_section_enable]',
    ) );
}

// blog sub title setting and control
$wp_customize->add_setting( 'next_travel_theme_options[blog_sub_title]', array(
	'sanitize_callback' => 'sanitize_text_field',
	'default'			=> $options['blog_sub_title'],
	'transport'			=> 'postMessage',
) );

$wp_customize->add_control( 'next_travel_theme_options[blog_sub_title]', array(
	'label'           	=> esc_html__( 'Sub Title', 'next-travel' ),
	'section'        	=> 'next_travel_blog_section',
	'type'				=> 'text',
	'active_callback' 	=> 'next_travel_is_blog_section_enable',
) );

// Abort if selective refresh is not available.
if ( isset( $wp_customize->selective_refresh ) ) {
    $wp_customize->selective_refresh->add_partial( 'next_travel_theme_options[blog_sub_title]', array(
		'selector'            => '#latest-posts h3.section-subtitle',
		'settings'            => 'next_travel_theme_options[blog_sub_title]',
		'container_inclusive' => false,
		'fallback_refresh'    => true,
		'render_callback'     => 'next_travel_blog_sub_title_partial',
    ) );
}

// blog title setting and control
$wp_customize->add_setting( 'next_travel_theme_options[blog_title]', array(
	'sanitize_callback' => 'sanitize_text_field',
	'default'			=> $options['blog_title'],
	'transport'			=> 'postMessage',
) );

$wp_customize->add_control( 'next_travel_theme_options[blog_title]', array(
	'label'           	=> esc_html__( 'Title', 'next-travel' ),
	'section'        	=> 'next_travel_blog_section',
	'active_callback' 	=> 'next_travel_is_blog_section_enable',
	'type'				=> 'text',
) );

// Abort if selective refresh is not available.
if ( isset( $wp_customize->selective_refresh ) ) {
    $wp_customize->selective_refresh->add_partial( 'next_travel_theme_options[blog_title]', array(
		'selector'            => '#latest-posts h2.section-title',
		'settings'            => 'next_travel_theme_options[blog_title]',
		'container_inclusive' => false,
		'fallback_refresh'    => true,
		'render_callback'     => 'next_travel_blog_title_partial',
    ) );
}

// Blog content type control and setting
$wp_customize->add_setting( 'next_travel_theme_options[blog_content_type]', array(
	'default'          	=> $options['blog_content_type'],
	'sanitize_callback' => 'next_travel_sanitize_select',
) );

$wp_customize->add_control( 'next_travel_theme_options[blog_content_type]', array(
	'label'             => esc_html__( 'Content Type', 'next-travel' ),
	'section'           => 'next_travel_blog_section',
	'type'				=> 'select',
	'active_callback' 	=> 'next_travel_is_blog_section_enable',
	'choices'			=> array(
	        'category'  => esc_html__( 'Category', 'next-travel' ),
            'recent' 	=> esc_html__( 'Recent', 'next-travel' ),
        ),
) );

// Add dropdown category setting and control.
$wp_customize->add_setting(  'next_travel_theme_options[blog_content_category]', array(
	'sanitize_callback' => 'next_travel_sanitize_single_category',
) ) ;

$wp_customize->add_control( new Next_Travel_Dropdown_Taxonomies_Control( $wp_customize,'next_travel_theme_options[blog_content_category]', array(
	'label'             => esc_html__( 'Select Category', 'next-travel' ),
	'description'      	=> esc_html__( 'Note: Latest selected no of posts will be shown from selected category', 'next-travel' ),
	'section'           => 'next_travel_blog_section',
	'type'              => 'dropdown-taxonomies',
	'active_callback'	=> 'next_travel_is_blog_section_content_category_enable'
) ) );

// Add dropdown categories setting and control.
$wp_customize->add_setting( 'next_travel_theme_options[blog_category_exclude]', array(
	'sanitize_callback' => 'next_travel_sanitize_category_list',
) ) ;

$wp_customize->add_control( new Next_Travel_Dropdown_Category_Control( $wp_customize,'next_travel_theme_options[blog_category_exclude]', array(
	'label'             => esc_html__( 'Select Excluding Categories', 'next-travel' ),
	'description'      	=> esc_html__( 'Note: Select categories to exclude. Press Shift key select multilple categories.', 'next-travel' ),
	'section'           => 'next_travel_blog_section',
	'type'              => 'dropdown-categories',
	'active_callback'	=> 'next_travel_is_blog_section_content_recent_enable'
) ) );
