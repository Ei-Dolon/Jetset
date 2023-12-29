<?php
/**
 * Theme Palace options
 *
 * @package Theme Palace
 * @subpackage Travel Life
 * @since Travel Life 1.0.0
 */

/**
 * List of pages for page choices.
 * @return Array Array of page ids and name.
 */
function travel_life_page_choices() {
    $pages = get_pages();
    $choices = array();
    $choices[0] = esc_html__( '--Select--', 'travel-life' );
    foreach ( $pages as $page ) {
        $choices[ $page->ID ] = $page->post_title;
    }
    return  $choices;
}

/**
 * List of posts for post choices.
 * @return Array Array of post ids and name.
 */
function travel_life_post_choices() {
    $posts = get_posts( array( 'numberposts' => -1 ) );
    $choices = array();
    $choices[0] = esc_html__( '--Select--', 'travel-life' );
    foreach ( $posts as $post ) {
        $choices[ $post->ID ] = $post->post_title;
    }
    return  $choices;
}

/**
 * List of trips for post choices.
 * @return Array Array of post ids and name.
 */
function travel_life_trip_choices() {
    $posts = get_posts( array( 'post_type' => 'itineraries', 'numberposts' => -1 ) );
    $choices = array();
    $choices[0] = esc_html__( '--Select--', 'travel-life' );
    foreach ( $posts as $post ) {
        $choices[ $post->ID ] = $post->post_title;
    }
    return  $choices;
}

function travel_life_product_choices() {
    $full_product_list = array();
    $product_id = array();
    $loop = new WP_Query(array('post_type' => array('product', 'product_variation'), 'posts_per_page' => -1));
    while ($loop->have_posts()) : $loop->the_post();
        $product_id[] = get_the_id();
    endwhile;
    wp_reset_postdata();
    $choices = array();
    $choices[0] = esc_html__( '--Select--', 'travel-life' );
    foreach ( $product_id  as $id ) {
        $choices[ $id ] = get_the_title( $id );
    }
    return  $choices;
}



if ( ! function_exists( 'travel_life_selected_sidebar' ) ) :
    /**
     * Sidebars options
     * @return array Sidbar positions
     */
    function travel_life_selected_sidebar() {
        $travel_life_selected_sidebar = array(
            'sidebar-1'             => esc_html__( 'Default Sidebar', 'travel-life' ),
            'optional-sidebar'      => esc_html__( 'Optional Sidebar 1', 'travel-life' ),
            'optional-sidebar-2'    => esc_html__( 'Optional Sidebar 2', 'travel-life' ),
            'optional-sidebar-3'    => esc_html__( 'Optional Sidebar 3', 'travel-life' ),
            'optional-sidebar-4'    => esc_html__( 'Optional Sidebar 4', 'travel-life' ),
        );

        $output = apply_filters( 'travel_life_selected_sidebar', $travel_life_selected_sidebar );

        return $output;
    }
endif;

if ( ! function_exists( 'travel_life_site_layout' ) ) :
    /**
     * Site Layout
     * @return array site layout options
     */
    function travel_life_site_layout() {
        $travel_life_site_layout = array(
            'wide-layout'  => get_template_directory_uri() . '/assets/images/full.png',
            'boxed-layout' => get_template_directory_uri() . '/assets/images/boxed.png',
        );

        $output = apply_filters( 'travel_life_site_layout', $travel_life_site_layout );
        return $output;
    }
endif;


if ( ! function_exists( 'travel_life_global_sidebar_position' ) ) :
    /**
     * Global Sidebar position
     * @return array Global Sidebar positions
     */
    function travel_life_global_sidebar_position() {
        $travel_life_global_sidebar_position = array(
            'right-sidebar' => get_template_directory_uri() . '/assets/images/right.png',
            'no-sidebar'    => get_template_directory_uri() . '/assets/images/full.png',
        );

        $output = apply_filters( 'travel_life_global_sidebar_position', $travel_life_global_sidebar_position );

        return $output;
    }
endif;


if ( ! function_exists( 'travel_life_sidebar_position' ) ) :
    /**
     * Sidebar position
     * @return array Sidbar positions
     */
    function travel_life_sidebar_position() {
        $travel_life_sidebar_position = array(
            'right-sidebar' => get_template_directory_uri() . '/assets/images/right.png',
            'no-sidebar'    => get_template_directory_uri() . '/assets/images/full.png',
        );

        $output = apply_filters( 'travel_life_sidebar_position', $travel_life_sidebar_position );

        return $output;
    }
endif;


if ( ! function_exists( 'travel_life_pagination_options' ) ) :
    /**
     * Pagination
     * @return array site pagination options
     */
    function travel_life_pagination_options() {
        $travel_life_pagination_options = array(
            'numeric'   => esc_html__( 'Numeric', 'travel-life' ),
            'default'   => esc_html__( 'Default(Older/Newer)', 'travel-life' ),
        );

        $output = apply_filters( 'travel_life_pagination_options', $travel_life_pagination_options );

        return $output;
    }
endif;


if ( ! function_exists( 'travel_life_switch_options' ) ) :
    /**
     * List of custom Switch Control options
     * @return array List of switch control options.
     */
    function travel_life_switch_options() {
        $arr = array(
            'on'        => esc_html__( 'Enable', 'travel-life' ),
            'off'       => esc_html__( 'Disable', 'travel-life' )
        );
        return apply_filters( 'travel_life_switch_options', $arr );
    }
endif;

if ( ! function_exists( 'travel_life_hide_options' ) ) :
    /**
     * List of custom Switch Control options
     * @return array List of switch control options.
     */
    function travel_life_hide_options() {
        $arr = array(
            'on'        => esc_html__( 'Yes', 'travel-life' ),
            'off'       => esc_html__( 'No', 'travel-life' )
        );
        return apply_filters( 'travel_life_hide_options', $arr );
    }
endif;

if ( ! function_exists( 'travel_life_main_slider_content_type' ) ) :
    /**
     * main slider Options
     * @return array site main slider options
     */
    function travel_life_main_slider_content_type() {
        $travel_life_main_slider_content_type = array(
            'page'      => esc_html__( 'Page', 'travel-life' ),
            'post'      => esc_html__( 'Post', 'travel-life' ),
            'category'  => esc_html__( 'Category', 'travel-life' ),
        );

        if ( class_exists( 'WP_Travel' ) ) {
            $travel_life_main_slider_content_type = array_merge( $travel_life_main_slider_content_type, array(
                'trip'          => esc_html__( 'Trip', 'travel-life' ),
                'trip-types'    => esc_html__( 'Trip Types', 'travel-life' ),
                'destination'   => esc_html__( 'Destination', 'travel-life' ),
                'activity'      => esc_html__( 'Activity', 'travel-life' ),
                ) );
        }

        $output = apply_filters( 'travel_life_main_slider_content_type', $travel_life_main_slider_content_type );


        return $output;
    }
endif;


if ( ! function_exists( 'travel_life_popular_destination_content_type' ) ) :
    /**
     * Destination Options
     * @return array site gallery options
     */
    function travel_life_popular_destination_content_type() {
        $travel_life_popular_destination_content_type = array(
            'page'      => esc_html__( 'Page', 'travel-life' ),
        );

        if ( class_exists( 'WP_Travel' ) ) {
            $travel_life_popular_destination_content_type = array_merge( $travel_life_popular_destination_content_type, array(
                'trip'          => esc_html__( 'Trip', 'travel-life' ),
                ) );
        }

        $output = apply_filters( 'travel_life_popular_destination_content_type', $travel_life_popular_destination_content_type );


        return $output;
    }
endif;

if ( ! function_exists( 'travel_life_package_content_type' ) ) :
    /**
     * Package Options
     * @return array site gallery options
     */
    function travel_life_package_content_type() {
        $travel_life_package_content_type = array(
            'page'      => esc_html__( 'Page', 'travel-life' ),
        );

        if ( class_exists( 'WP_Travel' ) ) {
            $travel_life_package_content_type = array_merge( $travel_life_package_content_type, array(
                'trip'          => esc_html__( 'Trip', 'travel-life' ),
                ) );
        }

        $output = apply_filters( 'travel_life_package_content_type', $travel_life_package_content_type );


        return $output;
    }
endif;
