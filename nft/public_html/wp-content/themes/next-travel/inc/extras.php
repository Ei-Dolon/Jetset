<?php
/**
 * Custom functions that act independently of the theme templates.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Theme Palace
 * @subpackage Next Travel
 * @since Next Travel 1.0.0
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function next_travel_body_classes( $classes ) {
	$options = next_travel_get_theme_options();

	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	// Add a class for typography
	$typography = (  $options['theme_typography'] == 'default' ) ? '' :  $options['theme_typography'];
	$classes[] = esc_attr( $typography );

	$body_typography = (  $options['body_theme_typography'] == 'default' ) ? '' :  $options['body_theme_typography'];
	$classes[] = esc_attr( $body_typography );

	// Add a class for layout
	$classes[] = esc_attr( $options['site_layout'] );

	// Add a class for sidebar
	$sidebar_position = next_travel_layout();
	$sidebar = 'sidebar-1';
	if ( is_singular() || is_home() ) {
		$id = ( is_home() && ! is_front_page() ) ? get_option( 'page_for_posts' ) : get_the_id();
	  	$sidebar = get_post_meta( $id, 'next-travel-selected-sidebar', true );
	  	$sidebar = ! empty( $sidebar ) ? $sidebar : 'sidebar-1';
	} 
	

	if ( is_active_sidebar('sidebar-1') && !is_post_type_archive( 'itineraries' ) ) {
		$classes[] = esc_attr( $sidebar_position );
	} elseif ( class_exists( 'WooCommerce' ) && is_product() ) {
		$classes[] = 'no-sidebar';
	}  elseif ( class_exists( 'WooCommerce' ) && ( is_shop() || is_product_category() || is_product_tag()) && ! is_active_sidebar('woocommerce-sidebar') ) {
		$classes[] = 'no-sidebar';
	} elseif ( class_exists( 'WP_Travel' ) && ( is_single() && 'itineraries' == get_post_type() ) && is_active_sidebar('trip-sidebar-single') ) {
		$classes[] = esc_attr( $sidebar_position );
	} elseif ( class_exists( 'WP_Travel' ) && ( is_post_type_archive( 'itineraries' ) || is_tax( 'itinerary_types' ) || is_tax( 'activity' ) || is_tax( 'travel_locations' ) || is_tax( 'travel_keywords' ) ) && is_active_sidebar('wp-travel-archive-sidebar') ) {
		$classes[] = esc_attr( $sidebar_position );
	}else {
		$classes[] = 'no-sidebar';
	}
	
	$classes[]= 'header-image-enabled';
	
	if(!class_exists('WP_Travel')){
		$classes[]     = 'wp-travel-disable';
	}
	
	$classes[]		= 'absolute-header';

	return $classes;
}
add_filter( 'body_class', 'next_travel_body_classes' );
