<?php
/**
 * Customizer default options
 *
 * @package Theme Palace
 * @subpackage Swingpress
 * @since Swingpress 1.0.0
 * @return array An array of default values
 */

function swingpress_get_default_theme_options() {
	$theme_data = wp_get_theme();
	$swingpress_default_options = array(
		// Color Options
		'header_title_color'			=> '#000',
		'header_tagline_color'			=> '#000',
		'header_txt_logo_extra'			=> 'show-all',
		'colorscheme_hue'				=> '#00afe9',
		'colorscheme'					=> 'default',
		'theme_version'					=> 'lite-version',
		'home_layout'					=> 'default-design',
		
		// typography Options
		'theme_typography' 				=> 'default',
		'body_theme_typography' 		=> 'default',


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
		'menu_sticky'					=> true,
		'social_nav_enable'				=> true,
		'search_nav_enable'				=> true,
		'cart_nav_enable'				=> true,


		// excerpt options
		'long_excerpt_length'           => 25,
		
		// pagination options
		'pagination_enable'         	=> true,
		'pagination_type'         		=> 'default',

		// footer options
		'copyright_text'           		=> sprintf( esc_html_x( 'Copyright &copy; %1$s %2$s', '1: Year, 2: Site Title with home URL', 'swingpress' ), '[the-year]', '[site-link]' ),
		'powered_by_text'           	=> esc_html__( 'All Rights Reserved | ', 'swingpress' ) . esc_html( $theme_data->get( 'Name') ) . '&nbsp;' . esc_html__( 'by', 'swingpress' ). '&nbsp;<a target="_blank" href="'. esc_url( $theme_data->get( 'AuthorURI' ) ) .'">'. esc_html( ucwords( $theme_data->get( 'Author' ) ) ) .'</a>',
		'scroll_top_visible'        	=> true,

		// reset options
		'reset_options'      			=> false,
		
		// homepage options
		'enable_frontpage_content' 		=> false,

		'all_sortable'					=> 'slider,trip_search,service,featured,about,popular_destination,testimonial,blog',
    


		// blog/archive options
		'your_latest_posts_title' 		=> esc_html__( 'Blogs', 'swingpress' ),
		'hide_btn' 						=> false,
		'hide_category'					=> false,
		'hide_content'					=> false,
		'hide_image'					=> false,

		// single post theme options
		'single_post_hide_date' 		=> false,
		'single_post_hide_author'		=> false,
		'single_post_hide_category'		=> false,
		'single_post_hide_tags'			=> false,

		/* Front Page */

		// Slider
		'slider_section_enable'			=> false,
		'slider_autoplay'				=> false,
		'slider_content_type'			=> 'page',
		'slider_design'					=> "first-design",
		'slider_btn_label'				=> esc_html__( 'Discover More', 'swingpress' ),
		'slider_video_label'			=> esc_html__( 'Watch the video', 'swingpress' ),


		// trip search
		'trip_search_section_enable'	=> false,
		'trip_search_placeholder_1'		=> esc_html__( 'Ex: Trekking', 'swingpress' ),
		'trip_search_placeholder_2'		=> esc_html__( 'Trip Type', 'swingpress' ),
		'trip_search_placeholder_3'		=> esc_html__( 'Destination', 'swingpress' ),

		// featured
		'featured_sub_title'				=> esc_html__( 'Featured activities', 'swingpress' ),
		'featured_title'				=> esc_html__( 'Travel anywhere', 'swingpress' ),
		'featured_section_enable'		=> false,
		'featured_content_type'			=> 'category',

		// popular destination
		'popular_destination_section_enable'	=> false,
		'popular_destination_auto_slide_enable'	=> true,
		'popular_destination_content_type'		=> 'post',
		'popular_destination_title'				=> esc_html__( 'Popular Trips', 'swingpress' ),
		'popular_destination_sub_title'			=> esc_html__( 'More than 25+ tour types for you', 'swingpress' ),

		// about
		'about_section_enable'			=> false,
		'about_btn_title'				=> esc_html__( 'Explore More', 'swingpress' ),

		// service
		'service_section_enable'		=> false,
		'service_section_icon_enable'	=> true,
		'service_title'					=> esc_html__( 'How we help you', 'swingpress' ),
		'service_sub_title'				=> esc_html__( 'Let’s go on an adventure', 'swingpress' ),
	
		// blog
		'blog_section_enable'			=> false,
		'blog_content_type'				=> 'category',
		'blog_title'					=> esc_html__( 'Our recent articles', 'swingpress' ),
		'blog_sub_title'				=> esc_html__( 'Know what’s happening', 'swingpress' ),
		'blog_btn_title'				=> esc_html__( 'Read More', 'swingpress' ),
		'blog_alt_btn_title'			=> esc_html__( 'Show All Aricle', 'swingpress' ),

		// testimonial
		'testimonial_section_enable'	=> false,
		'testimonial_auto_play'			=> false,
		'testimonial_section_title'		=> esc_html__( 'What Our Clients Says', 'swingpress' ),

	);

	$output = apply_filters( 'swingpress_default_theme_options', $swingpress_default_options );

	// Sort array in ascending order, according to the key:
	if ( ! empty( $output ) ) {
		ksort( $output );
	}

	return $output;
}