<?php
/**
 * Customizer default options
 *
 * @package Theme Palace
 * @subpackage Next Travel
 * @since Next Travel 1.0.0
 * @return array An array of default values
 */

function next_travel_get_default_theme_options() {
	$theme_data = wp_get_theme();
	$next_travel_default_options = array(
		// Color Options
		'header_title_color'			=> '#fff',
		'header_tagline_color'			=> '#fff',
		'header_txt_logo_extra'			=> 'show-all',
		'colorscheme_hue'				=> '#F38625',
		'colorscheme'					=> 'default',
		'home_layout'					=> 'default-design',
		
		// typography Options
		'theme_typography' 				=> 'default',
		'body_theme_typography' 		=> 'default',
		

		// breadcrumb
		'breadcrumb_enable'				=> true,
		'breadcrumb_separator'			=> '/',
		
		// layout 
		'site_layout'         			=> 'wide-layout',
		'sidebar_position'         		=> 'right-sidebar',
		'post_sidebar_position' 		=> 'right-sidebar',
		'page_sidebar_position' 		=> 'right-sidebar',
		'social_nav_enable'				=> true,

		// excerpt options
		'long_excerpt_length'           => 25,
		
		// pagination options
		'pagination_enable'         	=> true,
		'pagination_type'         		=> 'default',

		// footer options
		'copyright_text'           		=> sprintf( esc_html_x( 'Copyright &copy; %1$s %2$s. All Rights Reserved', '1: Year, 2: Site Title with home URL', 'next-travel' ), '[the-year]', '[site-link]' ),

		// reset options
		'reset_options'      			=> false,
		
		// homepage options
		'enable_frontpage_content' 		=> false,

		// homepage sections sortable
		'sortable' 						=> 'slider,about,gallery_slider,recommended_destination,blog',

		// blog/archive options
		'your_latest_posts_title' 		=> esc_html__( 'Blogs', 'next-travel' ),
		'hide_date' 					=> false,
		'hide_category'					=> false,
		'blog_column'					=> 'col-2',

		// single post theme options
		'single_post_hide_date' 		=> false,
		'single_post_hide_author'		=> false,
		'single_post_hide_category'		=> false,
		'single_post_hide_tags'			=> false,

		/* Front Page */

		// Slider
		'slider_section_enable'			=> false,
		'slider_content_type'			=> 'category',

		// about
		'about_section_enable'			=> false,
		'about_content_type'			=> 'page',
		'about_sub_title'				=> esc_html__( 'About', 'next-travel' ),
		'about_btn_title'				=> esc_html__( 'LEARN ABOUT US', 'next-travel' ),

		// Gallery Slider
		'gallery_slider_section_enable'			=> false,
		'gallery_slider_btn_title'				=> esc_html__( 'Explore More', 'next-travel' ),
		'gallery_slider_content_type'			=> 'trip-types',

		// recommended destination
		'recommended_destination_section_enable'	=> false,
		'recommended_destination_content_type'		=> 'category',
		'recommended_destination_description'		=> esc_html__( 'Lots of people are traveling the entire world. They have got their own purposes. You can also travel the world. We will tell you how to travel the river, sea, hilly areas, mountain & so on...', 'next-travel' ),
		'recommended_destination_title'				=> esc_html__('HOTELS & LODGES','next-travel'),
		'recommended_destination_sub_title'			=> esc_html__('Recommended','next-travel'),
		'recommended_destination_post_btn_label'	=> esc_html__( 'Read More', 'next-travel' ),
		'recommended_destination_btn_label'			=> esc_html__( 'VIEW ALL HOTELS', 'next-travel' ),

		// blog
		'blog_section_enable'			=> false,
		'blog_sub_title'				=> esc_html__('Featured', 'next-travel'),
		'blog_title'					=> esc_html__( 'NEWS & ARTICLES', 'next-travel' ),
		'blog_content_type'				=> 'recent',

	);

	$output = apply_filters( 'next_travel_default_theme_options', $next_travel_default_options );

	// Sort array in ascending order, according to the key:
	if ( ! empty( $output ) ) {
		ksort( $output );
	}

	return $output;
}