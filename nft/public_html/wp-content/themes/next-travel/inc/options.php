<?php
/**
 * Theme Palace options
 *
 * @package Theme Palace
 * @subpackage Next Travel
 * @since Next Travel 1.0.0
 */

/**
 * List of pages for page choices.
 * @return Array Array of page ids and name.
 */
function next_travel_page_choices() {
    $pages = get_pages();
    $choices = array();
    $choices[0] = esc_html__( '--Select--', 'next-travel' );
    foreach ( $pages as $page ) {
        $choices[ $page->ID ] = $page->post_title;
    }
    return  $choices;
}

/**
 * List of posts for post choices.
 * @return Array Array of post ids and name.
 */
function next_travel_post_choices() {
    $posts = get_posts( array( 'numberposts' => -1 ) );
    $choices = array();
    $choices[0] = esc_html__( '--Select--', 'next-travel' );
    foreach ( $posts as $post ) {
        $choices[ $post->ID ] = $post->post_title;
    }
    return  $choices;
}

/**
 * List of trips for post choices.
 * @return Array Array of post ids and name.
 */
function next_travel_trip_choices() {
    $posts = get_posts( array( 'post_type' => 'itineraries', 'numberposts' => -1 ) );
    $choices = array();
    $choices[0] = esc_html__( '--Select--', 'next-travel' );
    foreach ( $posts as $post ) {
        $choices[ $post->ID ] = $post->post_title;
    }
    return  $choices;
}

function next_travelduct_choices() {
    $full_product_list = array();
    $product_id = array();
    $loop = new WP_Query(array('post_type' => array('product', 'product_variation'), 'posts_per_page' => -1));
    while ($loop->have_posts()) : $loop->the_post();
        $product_id[] = get_the_id();
    endwhile;
    wp_reset_postdata();
    $choices = array();
    $choices[0] = esc_html__( '--Select--', 'next-travel' );
    foreach ( $product_id  as $id ) {
        $choices[ $id ] = get_the_title( $id );
    }
    return  $choices;
}


if ( ! function_exists( 'next_travel_selected_sidebar' ) ) :
    /**
     * Sidebars options
     * @return array Sidbar positions
     */
    function next_travel_selected_sidebar() {
        $next_travel_selected_sidebar = array(
            'sidebar-1'             => esc_html__( 'Default Sidebar', 'next-travel' ),
            'optional-sidebar'      => esc_html__( 'Optional Sidebar 1', 'next-travel' ),
            'optional-sidebar-2'    => esc_html__( 'Optional Sidebar 2', 'next-travel' ),
            'optional-sidebar-3'    => esc_html__( 'Optional Sidebar 3', 'next-travel' ),
            'optional-sidebar-4'    => esc_html__( 'Optional Sidebar 4', 'next-travel' ),
        );

        $output = apply_filters( 'next_travel_selected_sidebar', $next_travel_selected_sidebar );

        return $output;
    }
endif;

if ( ! function_exists( 'next_travel_site_layout' ) ) :
    /**
     * Site Layout
     * @return array site layout options
     */
    function next_travel_site_layout() {
        $next_travel_site_layout = array(
            'wide-layout'  => get_template_directory_uri() . '/assets/images/full.png',
            'boxed-layout' => get_template_directory_uri() . '/assets/images/boxed.png',
            //'frame-layout' => get_template_directory_uri() . '/assets/images/framed.png',
        );

        $output = apply_filters( 'next_travel_site_layout', $next_travel_site_layout );
        return $output;
    }
endif;


if ( ! function_exists( 'next_travel_global_sidebar_position' ) ) :
    /**
     * Global Sidebar position
     * @return array Global Sidebar positions
     */
    function next_travel_global_sidebar_position() {
        $next_travel_global_sidebar_position = array(
            'right-sidebar' => get_template_directory_uri() . '/assets/images/right.png',
            'no-sidebar'    => get_template_directory_uri() . '/assets/images/full.png',
        );

        $output = apply_filters( 'next_travel_global_sidebar_position', $next_travel_global_sidebar_position );

        return $output;
    }
endif;


if ( ! function_exists( 'next_travel_sidebar_position' ) ) :
    /**
     * Sidebar position
     * @return array Sidbar positions
     */
    function next_travel_sidebar_position() {
        $next_travel_sidebar_position = array(
            'right-sidebar' => get_template_directory_uri() . '/assets/images/right.png',
            'no-sidebar'    => get_template_directory_uri() . '/assets/images/full.png',
        );

        $output = apply_filters( 'next_travel_sidebar_position', $next_travel_sidebar_position );

        return $output;
    }
endif;


if ( ! function_exists( 'next_travel_pagination_options' ) ) :
    /**
     * Pagination
     * @return array site pagination options
     */
    function next_travel_pagination_options() {
        $next_travel_pagination_options = array(
            'numeric'   => esc_html__( 'Numeric', 'next-travel' ),
            'default'   => esc_html__( 'Default(Older/Newer)', 'next-travel' ),
        );

        $output = apply_filters( 'next_travel_pagination_options', $next_travel_pagination_options );

        return $output;
    }
endif;

if ( ! function_exists( 'next_travel_switch_options' ) ) :
    /**
     * List of custom Switch Control options
     * @return array List of switch control options.
     */
    function next_travel_switch_options() {
        $arr = array(
            'on'        => esc_html__( 'Enable', 'next-travel' ),
            'off'       => esc_html__( 'Disable', 'next-travel' )
        );
        return apply_filters( 'next_travel_switch_options', $arr );
    }
endif;

if ( ! function_exists( 'next_travel_hide_options' ) ) :
    /**
     * List of custom Switch Control options
     * @return array List of switch control options.
     */
    function next_travel_hide_options() {
        $arr = array(
            'on'        => esc_html__( 'Yes', 'next-travel' ),
            'off'       => esc_html__( 'No', 'next-travel' )
        );
        return apply_filters( 'next_travel_hide_options', $arr );
    }
endif;


if ( ! function_exists( 'next_travel_gallery_slider_content_type' ) ) :
    /**
     * gallery_slider Options
     * @return array site gallery_slider options
     */
    function next_travel_gallery_slider_content_type() {
      
        $next_travel_gallery_slider_content_type = array(
            'page'      => esc_html__( 'Page', 'next-travel' ),
        );

        if ( class_exists( 'WP_Travel' )) {
            $next_travel_gallery_slider_content_type = array_merge( $next_travel_gallery_slider_content_type, array(
                'trip-types'    => esc_html__( 'Trip Types', 'next-travel' ),
                ) );
        }

        $output = apply_filters( 'next_travel_gallery_slider_content_type', $next_travel_gallery_slider_content_type );

        return $output;
    }
endif;


if ( ! function_exists( 'next_travel_recommended_destination_content_type' ) ) :
    /**
     * Destination Options
     * @return array site gallery options
     */
    function next_travel_recommended_destination_content_type() {
        $next_travel_recommended_destination_content_type = array(
            'category'  => esc_html__( 'Category', 'next-travel' ),
        );

        if ( class_exists( 'WP_Travel' ) ) {
            $next_travel_recommended_destination_content_type = array_merge( $next_travel_recommended_destination_content_type, array(
                'trip-types'          => esc_html__( 'Trip Types', 'next-travel' ),
                ) );
        }

        $output = apply_filters( 'next_travel_recommended_destination_content_type', $next_travel_recommended_destination_content_type );

        return $output;
    }
endif;

if ( ! function_exists( 'next_travel_package_content_type' ) ) :
    /**
     * Package Options
     * @return array site gallery options
     */
    function next_travel_package_content_type() {
        $next_travel_package_content_type = array(
            'page'      => esc_html__( 'Page', 'next-travel' ),
            'post'      => esc_html__( 'Post', 'next-travel' ),
            'category'  => esc_html__( 'Category', 'next-travel' ),
        );

        if ( class_exists( 'WP_Travel' ) ) {
            $next_travel_package_content_type = array_merge( $next_travel_package_content_type, array(
                'trip-types'    => esc_html__( 'Trip Types', 'next-travel' ),
                'destination'   => esc_html__( 'Destination', 'next-travel' ),
                'activity'      => esc_html__( 'Activity', 'next-travel' ),
                ) );
        }

        $output = apply_filters( 'next_travel_package_content_type', $next_travel_package_content_type );


        return $output;
    }
endif;
