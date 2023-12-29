<?php
/**
 * Customizer default options
 *
 * @package Theme Palace
 * @subpackage Travel Life
 * @since Travel Life 1.0.0
 * @return array An array of default values
 */

function travel_life_get_default_theme_options() {

	$travel_life_default_options = array(
		// Color Options
		'header_title_color'			=> '#000',
		'header_tagline_color'			=> '#000',
		'header_txt_logo_extra'			=> 'show-all',

		//button
		'button_background_color'		=> '#e5f8fc',
		'button_text_color'		=> '#00afe9',
		
		// loader
		'loader_enable'         		=> false,
		'loader_icon'         			=> 'default',

		// breadcrumb
		'breadcrumb_enable'				=> true,
		'breadcrumb_separator'			=> '/',
		
		// layout 
		'site_layout'         			=> 'wide-layout',
		'sidebar_position'         		=> 'right-sidebar',
		'post_sidebar_position' 		=> 'right-sidebar',
		'page_sidebar_position' 		=> 'right-sidebar',


		// excerpt options
		'long_excerpt_length'           => 25,
		
		// pagination options
		'pagination_enable'         	=> true,
		'pagination_type'         		=> 'default',

		// footer options
		'copyright_text'           		=> sprintf( esc_html_x( 'Copyright &copy; %1$s %2$s', '1: Year, 2: Site Title with home URL', 'travel-life' ), '[the-year]', '[site-link]' ),

		// reset options
		'reset_options'      			=> false,
		
		// homepage options
		'enable_frontpage_content' 		=> false,


		// blog/archive options
		'your_latest_posts_title' 		=> esc_html__( 'Blogs', 'travel-life' ),
		'hide_date' 					=> false,
		'hide_category'					=> false,
		'hide_author'					=> false,

		// single post theme options
		'single_post_hide_date' 		=> false,
		'single_post_hide_author'		=> false,
		'single_post_hide_category'		=> false,
		'single_post_hide_tags'			=> false,

		/* Front Page */

		// topbar
		'top_bar_contact_enable'			=> false,
		'top_advertisement_enable'			=> true,
		'topbar_button_enable'			=> true,
		'topbar_btn_text'				=> esc_html__( 'Travel Enquiries', 'travel-life' ),

		// Slider
		'slider_section_enable'			=> false,
		'slider_section_enable_clould' 	=> true,
		'slider_autoplay'				=> false,
		'slider_content_type'			=> 'page',
		'slider_count'					=> 3,
		'slider_btn_label'				=> esc_html__( 'Discover More', 'travel-life' ),
		'slider_video_label'			=> esc_html__( 'Watch the video', 'travel-life' ),

		// counter
		'counter_section_enable'			=> false,
		'counter_count'					=> 5,

		// popular destination
		'popular_destination_section_enable'	=> false,
		'popular_destination_content_type'		=> 'page',
		'popular_destination_count'				=> 4,
		'popular_destination_title'				=> esc_html__( 'Popular Trips', 'travel-life' ),
		'popular_destination_read_more'			=> esc_html__( 'Book Now', 'travel-life' ),
		'popular_destination_btn_title'			=> esc_html__( 'Show All', 'travel-life' ),

		// about
		'about_section_enable'			=> false,
		'about_content_type'			=> 'page',
		'about_btn_title'				=> esc_html__( 'Explore More', 'travel-life' ),

		// package
		'package_section_enable'		=> false,
		'package_count'					=> 4,
		'package_content_type'			=> 'page',
		'package_title'					=> esc_html__( 'Recommended Packages', 'travel-life' ),
		'package_btn_title'				=> esc_html__( 'Explore Now', 'travel-life' ),

		// testimonial
		'testimonial_section_enable'	=> false,
		'testimonial_auto_play'			=> false,
		'testimonial_content_type'		=> 'page',
		'testimonial_count'				=> 4,
		'testimonial_section_title'		=> esc_html__( 'What Our Clients Says', 'travel-life' ),
		'testimonial_section_btn_text'		=> esc_html__( 'Show All', 'travel-life' ),

		// subscription
		'subscription_section_enable'	=> false,
		'subscription_sub_title'		=> esc_html__( 'Keep in touch', 'travel-life' ),
		'subscription_title'			=> esc_html__( 'Travel with us!', 'travel-life' ),
		'subscription_btn_title'		=> esc_html__( 'Subscribe Now', 'travel-life' ),

	);

	$output = apply_filters( 'travel_life_default_theme_options', $travel_life_default_options );

	// Sort array in ascending order, according to the key:
	if ( ! empty( $output ) ) {
		ksort( $output );
	}

	return $output;
}