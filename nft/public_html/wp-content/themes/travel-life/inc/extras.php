<?php
/**
 * Custom functions that act independently of the theme templates.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Theme Palace
 * @subpackage Travel Life
 * @since Travel Life 1.0.0
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function travel_life_body_classes( $classes ) {
	$options = travel_life_get_theme_options();

	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}
	$classes[] = 'relative-header';

	// Add a class for sidebar
	$sidebar_position = travel_life_layout();
	$sidebar = 'sidebar-1';

	if ( is_post_type_archive( 'itineraries' ) || is_tax( 'itinerary_types' ) || is_tax( 'activity' ) || is_tax( 'travel_locations' ) || is_tax( 'travel_keywords' ) ) {
		if ( is_active_sidebar( 'wp-travel-archive-sidebar' ) ) {
			$classes[] = $sidebar_position;
		} else {
			$classes[] = 'no-sidebar';
		}
	} else {

		if( is_singular() ) {
			$sidebar = get_post_meta( get_the_id(), 'travel-life-selected-sidebar', true );
			$post_sidebar = ! empty( $sidebar ) ? $sidebar : 'sidebar-1';
		} else {
			$post_sidebar = 'sidebar-1';
		}

		if ( is_archive( 'itinerary' ) && is_active_sidebar( 'wp-travel-archive-sidebar' ) ) {
			$classes[] = esc_attr( $sidebar_position );
		} elseif ( is_active_sidebar( $post_sidebar ) && !is_archive( 'itinerary' ) ) {
			$classes[] = esc_attr( $sidebar_position );
		}else {
			$classes[] = 'no-sidebar';
		}
	}

	if ( is_singular( 'itineraries' ) ) {
		$classes[] = 'no-sidebar';
	}


	return $classes;
}
add_filter( 'body_class', 'travel_life_body_classes' );
