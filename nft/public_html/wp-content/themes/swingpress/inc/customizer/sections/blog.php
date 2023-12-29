<?php
/**
 * Blog Section options
 *
 * @package Theme Palace
 * @subpackage Swingpress
 * @since Swingpress 1.0.0
 */

// Add Blog section
$wp_customize->add_section( 'swingpress_blog_section', array(
	'title'             => esc_html__( 'Blog','swingpress' ),
	'description'       => esc_html__( 'Blog Section options.', 'swingpress' ),
	'panel'             => 'swingpress_front_page_panel',
) );

// Blog content enable control and setting
$wp_customize->add_setting( 'swingpress_theme_options[blog_section_enable]', array(
	'default'			=> 	$options['blog_section_enable'],
	'sanitize_callback' => 'swingpress_sanitize_switch_control',
) );

$wp_customize->add_control( new Swingpress_Switch_Control( $wp_customize, 'swingpress_theme_options[blog_section_enable]', array(
	'label'             => esc_html__( 'Blog Section Enable', 'swingpress' ),
	'section'           => 'swingpress_blog_section',
	'on_off_label' 		=> swingpress_switch_options(),
) ) );

// blog title setting and control
$wp_customize->add_setting( 'swingpress_theme_options[blog_title]', array(
	'sanitize_callback' => 'sanitize_text_field',
	'default'			=> $options['blog_title'],
	'transport'			=> 'postMessage',
) );

$wp_customize->add_control( 'swingpress_theme_options[blog_title]', array(
	'label'           	=> esc_html__( 'Title', 'swingpress' ),
	'section'        	=> 'swingpress_blog_section',
	'active_callback' 	=> 'swingpress_is_blog_section_enable',
	'type'				=> 'text',
) );

// Abort if selective refresh is not available.
if ( isset( $wp_customize->selective_refresh ) ) {
    $wp_customize->selective_refresh->add_partial( 'swingpress_theme_options[blog_title]', array(
		'selector'            => '#latest-posts-section .section-header h2.section-title',
		'settings'            => 'swingpress_theme_options[blog_title]',
		'container_inclusive' => false,
		'fallback_refresh'    => true,
		'render_callback'     => 'swingpress_blog_title_partial',
    ) );
}

// blog title setting and control
$wp_customize->add_setting( 'swingpress_theme_options[blog_sub_title]', array(
	'sanitize_callback' => 'sanitize_text_field',
	'default'			=> $options['blog_sub_title'],
	'transport'			=> 'postMessage',
) );

$wp_customize->add_control( 'swingpress_theme_options[blog_sub_title]', array(
	'label'           	=> esc_html__( 'Title', 'swingpress' ),
	'section'        	=> 'swingpress_blog_section',
	'active_callback' 	=> 'swingpress_is_blog_section_enable',
	'type'				=> 'text',
) );

// Abort if selective refresh is not available.
if ( isset( $wp_customize->selective_refresh ) ) {
    $wp_customize->selective_refresh->add_partial( 'swingpress_theme_options[blog_sub_title]', array(
		'selector'            => '#latest-posts-section .section-header p',
		'settings'            => 'swingpress_theme_options[blog_sub_title]',
		'container_inclusive' => false,
		'fallback_refresh'    => true,
		'render_callback'     => 'swingpress_blog_sub_title_partial',
    ) );
}

// blog btn title setting and control
$wp_customize->add_setting( 'swingpress_theme_options[blog_btn_title]', array(
	'sanitize_callback' => 'sanitize_text_field',
	'default'			=> $options['blog_btn_title'],
	'transport'			=> 'postMessage',
) );

$wp_customize->add_control( 'swingpress_theme_options[blog_btn_title]', array(
	'label'           	=> esc_html__( 'Button Label', 'swingpress' ),
	'section'        	=> 'swingpress_blog_section',
	'active_callback' 	=> 'swingpress_is_blog_section_enable',
	'type'				=> 'text',
) );

// Abort if selective refresh is not available.
if ( isset( $wp_customize->selective_refresh ) ) {
    $wp_customize->selective_refresh->add_partial( 'swingpress_theme_options[blog_btn_title]', array(
		'selector'            => '#latest-posts-section .more-link a',
		'settings'            => 'swingpress_theme_options[blog_btn_title]',
		'container_inclusive' => false,
		'fallback_refresh'    => true,
		'render_callback'     => 'swingpress_blog_btn_title_partial',
    ) );
}



// blog btn title setting and control
$wp_customize->add_setting( 'swingpress_theme_options[blog_alt_btn_title]', array(
	'sanitize_callback' => 'sanitize_text_field',
	'default'			=> $options['blog_alt_btn_title'],
	'transport'			=> 'postMessage',
) );

$wp_customize->add_control( 'swingpress_theme_options[blog_alt_btn_title]', array(
	'label'           	=> esc_html__( 'Button Label', 'swingpress' ),
	'section'        	=> 'swingpress_blog_section',
	'active_callback' 	=> 'swingpress_is_blog_section_enable',
	'type'				=> 'text',
) );

// Abort if selective refresh is not available.
if ( isset( $wp_customize->selective_refresh ) ) {
    $wp_customize->selective_refresh->add_partial( 'swingpress_theme_options[blog_alt_btn_title]', array(
		'selector'            => '#latest-posts-section .read-more a',
		'settings'            => 'swingpress_theme_options[blog_alt_btn_title]',
		'container_inclusive' => false,
		'fallback_refresh'    => true,
		'render_callback'     => 'swingpress_blog_alt_btn_title_partial',
    ) );
}

// blog btn title setting and control
$wp_customize->add_setting( 'swingpress_theme_options[blog_alt_btn_url]', array(
	'sanitize_callback' => 'esc_url_raw',
) );

$wp_customize->add_control( 'swingpress_theme_options[blog_alt_btn_url]', array(
	'label'           	=> esc_html__( 'Button Link', 'swingpress' ),
	'section'        	=> 'swingpress_blog_section',
	'active_callback' 	=> 'swingpress_is_blog_section_enable',
	'type'				=> 'url',
) );


// Blog content type control and setting
$wp_customize->add_setting( 'swingpress_theme_options[blog_content_type]', array(
	'default'          	=> $options['blog_content_type'],
	'sanitize_callback' => 'swingpress_sanitize_select',
) );

$wp_customize->add_control( 'swingpress_theme_options[blog_content_type]', array(
	'label'             => esc_html__( 'Content Type', 'swingpress' ),
	'section'           => 'swingpress_blog_section',
	'type'				=> 'select',
	'active_callback' 	=> 'swingpress_is_blog_section_enable',
	'choices'			=> array(
            'page'      => esc_html__( 'Page', 'swingpress' ),
            'post'      => esc_html__( 'Post', 'swingpress' ),
            'category'  => esc_html__( 'Category', 'swingpress' ),
        ),
) );


for ( $i = 1; $i <= 4; $i++ ) :
	// blog pages drop down chooser control and setting
	$wp_customize->add_setting( 'swingpress_theme_options[blog_content_page_' . $i . ']', array(
		'sanitize_callback' => 'swingpress_sanitize_page',
	) );

	$wp_customize->add_control( new Swingpress_Dropdown_Chooser( $wp_customize, 'swingpress_theme_options[blog_content_page_' . $i . ']', array(
		'label'             => sprintf( esc_html__( 'Select Page %d', 'swingpress' ), $i ),
		'section'           => 'swingpress_blog_section',
		'choices'			=> swingpress_page_choices(),
		'active_callback'	=> 'swingpress_is_blog_section_content_page_enable',
	) ) );

	// blog posts drop down chooser control and setting
	$wp_customize->add_setting( 'swingpress_theme_options[blog_content_post_' . $i . ']', array(
		'sanitize_callback' => 'swingpress_sanitize_page',
	) );

	$wp_customize->add_control( new Swingpress_Dropdown_Chooser( $wp_customize, 'swingpress_theme_options[blog_content_post_' . $i . ']', array(
		'label'             => sprintf( esc_html__( 'Select Post %d', 'swingpress' ), $i ),
		'section'           => 'swingpress_blog_section',
		'choices'			=> swingpress_post_choices(),
		'active_callback'	=> 'swingpress_is_blog_section_content_post_enable',
	) ) );

endfor;

// Add dropdown category setting and control.
$wp_customize->add_setting(  'swingpress_theme_options[blog_content_category]', array(
	'sanitize_callback' => 'swingpress_sanitize_single_category',
) ) ;

$wp_customize->add_control( new Swingpress_Dropdown_Taxonomies_Control( $wp_customize,'swingpress_theme_options[blog_content_category]', array(
	'label'             => esc_html__( 'Select Category', 'swingpress' ),
	'description'      	=> esc_html__( 'Note: Latest selected no of posts will be shown from selected category', 'swingpress' ),
	'section'           => 'swingpress_blog_section',
	'type'              => 'dropdown-taxonomies',
	'active_callback'	=> 'swingpress_is_blog_section_content_category_enable'
) ) );
