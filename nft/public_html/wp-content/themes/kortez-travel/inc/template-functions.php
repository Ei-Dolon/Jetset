<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package Kortez Travel
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function kortez_travel_body_classes( $classes ) {
	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	// Adds a class of no-sidebar when there is no sidebar present.
	if ( get_theme_mod( 'sidebar_settings', 'right' ) == 'right' ){
		if( kortez_travel_wooCom_is_shop() ){
			if ( !is_active_sidebar( 'woocommerce-right-sidebar' ) ) {
				$classes[] = 'no-sidebar';
			}
		}else{
			if ( !is_active_sidebar( 'right-sidebar' ) ) {
				$classes[] = 'no-sidebar';
			}
		}
	}elseif ( get_theme_mod( 'sidebar_settings', 'right' ) == 'left' ){
		if( kortez_travel_wooCom_is_shop() ){
			if ( !is_active_sidebar( 'woocommerce-left-sidebar' ) ) {
				$classes[] = 'no-sidebar';
			}
		}else{
			if ( !is_active_sidebar( 'left-sidebar' ) ) {
				$classes[] = 'no-sidebar';
			}
		}
	}elseif ( get_theme_mod( 'sidebar_settings', 'right' ) == 'right-left' ){
		if( kortez_travel_wooCom_is_shop() ){
			if ( !is_active_sidebar( 'woocommerce-left-sidebar' ) && !is_active_sidebar( 'woocommerce-right-sidebar' ) ) {
				$classes[] = 'no-sidebar';
			}
		}else{
			if ( !is_active_sidebar( 'left-sidebar' ) && !is_active_sidebar( 'right-sidebar' ) ) {
				$classes[] = 'no-sidebar';
			}
		}
	}else{
		$classes[] = 'content-no-sidebar';
	}
	if ( ( is_home() || ( is_archive() && !kortez_travel_wooCom_is_shop() ) ) && get_theme_mod( 'disable_sidebar_blog_page', false ) ){
		$classes[] = 'no-sidebar';
	}
	if ( is_page() && get_theme_mod( 'disable_sidebar_page', true ) ){
		$classes[] = 'no-sidebar';
	}
	if ( is_single() && get_theme_mod( 'disable_sidebar_single_post', false ) ){
		$classes[] = 'no-sidebar';
	}
	if ( kortez_travel_wooCom_is_shop() && ( get_theme_mod( 'disable_sidebar_woocommerce_page', false ) || get_theme_mod( 'disable_sidebar_woocommerce_shop', false ) ) ){
		$classes[] = 'no-sidebar';
	}
	if( kortez_travel_wooCom_is_cart() || kortez_travel_wooCom_is_checkout() || kortez_travel_wooCom_is_account_page() ){
		$classes[] = 'no-sidebar';
	}

	return $classes;
}
add_filter( 'body_class', 'kortez_travel_body_classes' );

if( !function_exists( 'kortez_travel_get_icon_by_post_format' ) ):
/**
* Gives a css class for post format icon
*
* @return string
* @since Kortez Travel 1.0.0
*/
function kortez_travel_get_icon_by_post_format(){
    $icons = array(
        'standard' => 'fas fa-thumbtack',
        'sticky'   => 'fas fa-thumbtack',
        'aside'    => 'fas fa-file-alt',
        'image'    => 'fas fa-image',
        'video'    => 'far fa-play-circle',
        'quote'    => 'fas fa-quote-right',
        'link'     => 'fas fa-link',
        'gallery'  => 'fas fa-images',
        'status'   => 'fas fa-comment',
        'audio'    => 'fas fa-volume-up',
        'chat'     => 'fas fa-comments',
    );
    $format = get_post_format();
    if( empty( $format ) ){
        $format = 'standard';
    }
    return apply_filters( 'kortez_travel_post_format_icon', $icons[ $format ] );
}
endif;

/**
* Adds a submit button in search form
* 
* @since Kortez Travel 1.0.0
* @param string $form
* @return string
*/
function kortez_travel_modify_search_form( $form ){
	return str_replace( '</form>', '<button type="submit" class="search-button"><span class="fas fa-search"></span></button></form>', $form );
}
add_filter( 'get_search_form', 'kortez_travel_modify_search_form' );

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function kortez_travel_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}
add_action( 'wp_head', 'kortez_travel_pingback_header' );

/**
 * To disable archive prefix title.
 * @since Kortez Travel 1.0.0
 */

function kortez_travel_modify_archive_title( $title ) {
	if( is_category() ) {
		$title = single_cat_title( '', false );
	} elseif ( is_tag() ) {
		$title = single_tag_title( '', false );
    } elseif ( is_author() ) {
		$title = '<span class="vcard">' . get_the_author() . '</span>' ;
    } elseif ( is_year() ) {
        $title = get_the_date( _x( 'Y', 'yearly archives date format', 'kortez-travel' ) );
    } elseif ( is_month() ) {
        $title = get_the_date( _x( 'F Y', 'monthly archives date format', 'kortez-travel' ) );
    } elseif ( is_day() ) {
        $title = get_the_date( _x( 'F j, Y', 'daily archives date format', 'kortez-travel' ) );
     } elseif ( is_post_type_archive() ) {
        $title = post_type_archive_title( '', false );
    } elseif ( is_tax() ) {
        $title = single_term_title( '', false );
    }

	return $title;
}

add_filter( 'get_the_archive_title', 'kortez_travel_modify_archive_title' );

/**
 * Descriptions on Primary Menu
 */
function kortez_travel_header_menu_desc($item_output, $item, $depth, $args){
    if ( 'menu-1' == $args->theme_location && $item->description )
        $item_output = str_replace( '</a>', '<span class="menu-description">' . $item->description . '</span></a>', $item_output );
    return $item_output;
}
add_filter( 'walker_nav_menu_start_el', 'kortez_travel_header_menu_desc', 10, 4 );