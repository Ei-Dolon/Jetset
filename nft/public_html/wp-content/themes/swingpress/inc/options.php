<?php
/**
 * Theme Palace options
 *
 * @package Theme Palace
 * @subpackage Swingpress
 * @since Swingpress 1.0.0
 */

/**
 * List of pages for page choices.
 * @return Array Array of page ids and name.
 */
function swingpress_page_choices() {
    $pages = get_pages();
    $choices = array();
    $choices[0] = esc_html__( '--Select--', 'swingpress' );
    foreach ( $pages as $page ) {
        $choices[ $page->ID ] = $page->post_title;
    }
    return  $choices;
}

/**
 * List of posts for post choices.
 * @return Array Array of post ids and name.
 */
function swingpress_post_choices() {
    $posts = get_posts( array( 'numberposts' => -1 ) );
    $choices = array();
    $choices[0] = esc_html__( '--Select--', 'swingpress' );
    foreach ( $posts as $post ) {
        $choices[ $post->ID ] = $post->post_title;
    }
    return  $choices;
}

/**
 * List of trips for post choices.
 * @return Array Array of post ids and name.
 */
function swingpress_trip_choices() {
    $posts = get_posts( array( 'post_type' => 'itineraries', 'numberposts' => -1 ) );
    $choices = array();
    $choices[0] = esc_html__( '--Select--', 'swingpress' );
    foreach ( $posts as $post ) {
        $choices[ $post->ID ] = $post->post_title;
    }
    return  $choices;
}

function swingpress_product_choices() {
    $full_product_list = array();
    $product_id = array();
    $loop = new WP_Query(array('post_type' => array('product', 'product_variation'), 'posts_per_page' => -1));
    while ($loop->have_posts()) : $loop->the_post();
        $product_id[] = get_the_id();
    endwhile;
    wp_reset_postdata();
    $choices = array();
    $choices[0] = esc_html__( '--Select--', 'swingpress' );
    foreach ( $product_id  as $id ) {
        $choices[ $id ] = get_the_title( $id );
    }
    return  $choices;
}


if ( ! function_exists( 'swingpress_selected_sidebar' ) ) :
    /**
     * Sidebars options
     * @return array Sidbar positions
     */
    function swingpress_selected_sidebar() {
        $swingpress_selected_sidebar = array(
            'sidebar-1'             => esc_html__( 'Default Sidebar', 'swingpress' ),
            'optional-sidebar'      => esc_html__( 'Optional Sidebar 1', 'swingpress' ),
            'optional-sidebar-2'    => esc_html__( 'Optional Sidebar 2', 'swingpress' ),
            'optional-sidebar-3'    => esc_html__( 'Optional Sidebar 3', 'swingpress' ),
            'optional-sidebar-4'    => esc_html__( 'Optional Sidebar 4', 'swingpress' ),
        );

        $output = apply_filters( 'swingpress_selected_sidebar', $swingpress_selected_sidebar );

        return $output;
    }
endif;

if ( ! function_exists( 'swingpress_site_layout' ) ) :
    /**
     * Site Layout
     * @return array site layout options
     */
    function swingpress_site_layout() {
        $swingpress_site_layout = array(
            'wide-layout'  => get_template_directory_uri() . '/assets/images/full.png',
            'boxed-layout' => get_template_directory_uri() . '/assets/images/boxed.png',
            'frame-layout' => get_template_directory_uri() . '/assets/images/framed.png',
        );

        $output = apply_filters( 'swingpress_site_layout', $swingpress_site_layout );
        return $output;
    }
endif;


if ( ! function_exists( 'swingpress_global_sidebar_position' ) ) :
    /**
     * Global Sidebar position
     * @return array Global Sidebar positions
     */
    function swingpress_global_sidebar_position() {
        $swingpress_global_sidebar_position = array(
            'right-sidebar' => get_template_directory_uri() . '/assets/images/right.png',
            'no-sidebar'    => get_template_directory_uri() . '/assets/images/full.png',
        );

        $output = apply_filters( 'swingpress_global_sidebar_position', $swingpress_global_sidebar_position );

        return $output;
    }
endif;


if ( ! function_exists( 'swingpress_sidebar_position' ) ) :
    /**
     * Sidebar position
     * @return array Sidbar positions
     */
    function swingpress_sidebar_position() {
        $swingpress_sidebar_position = array(
            'right-sidebar' => get_template_directory_uri() . '/assets/images/right.png',
            'no-sidebar'    => get_template_directory_uri() . '/assets/images/full.png',
            'no-sidebar-content'   => get_template_directory_uri() . '/assets/images/boxed.png',
        );

        $output = apply_filters( 'swingpress_sidebar_position', $swingpress_sidebar_position );

        return $output;
    }
endif;


if ( ! function_exists( 'swingpress_pagination_options' ) ) :
    /**
     * Pagination
     * @return array site pagination options
     */
    function swingpress_pagination_options() {
        $swingpress_pagination_options = array(
            'numeric'   => esc_html__( 'Numeric', 'swingpress' ),
            'default'   => esc_html__( 'Default(Older/Newer)', 'swingpress' ),
        );

        $output = apply_filters( 'swingpress_pagination_options', $swingpress_pagination_options );

        return $output;
    }
endif;

if ( ! function_exists( 'swingpress_switch_options' ) ) :
    /**
     * List of custom Switch Control options
     * @return array List of switch control options.
     */
    function swingpress_switch_options() {
        $arr = array(
            'on'        => esc_html__( 'Enable', 'swingpress' ),
            'off'       => esc_html__( 'Disable', 'swingpress' )
        );
        return apply_filters( 'swingpress_switch_options', $arr );
    }
endif;

if ( ! function_exists( 'swingpress_hide_options' ) ) :
    /**
     * List of custom Switch Control options
     * @return array List of switch control options.
     */
    function swingpress_hide_options() {
        $arr = array(
            'on'        => esc_html__( 'Yes', 'swingpress' ),
            'off'       => esc_html__( 'No', 'swingpress' )
        );
        return apply_filters( 'swingpress_hide_options', $arr );
    }
endif;

if ( ! function_exists( 'swingpress_sortable_sections' ) ) :
    /**
     * List of sections Control options
     * @return array List of Sections control options.
     */
    function swingpress_sortable_sections() {
        $sections = array(
            'slider'                    => esc_html__( 'Main Slider', 'swingpress' ),
            'trip_search'               => esc_html__( 'Trip Search', 'swingpress' ),
            'service'                   => esc_html__( 'Services', 'swingpress' ),
            'featured'                  => esc_html__( 'Featured', 'swingpress' ),
            'about'                     => esc_html__( 'About Us', 'swingpress' ),
            'popular_destination'       => esc_html__( 'Popular Destination', 'swingpress' ),
            'testimonial'               => esc_html__( 'Testimonial', 'swingpress' ),
            'blog'                      => esc_html__( 'Blog', 'swingpress' ),
            'subscription'              => esc_html__( 'Subscription', 'swingpress' ),
        );
        return apply_filters( 'swingpress_sortable_sections', $sections );
    }
endif;

if ( ! function_exists( 'swingpress_main_slider_content_type' ) ) :
    /**
     * main slider Options
     * @return array site main slider options
     */
    function swingpress_main_slider_content_type() {
        $swingpress_main_slider_content_type = array(
            'page'      => esc_html__( 'Page', 'swingpress' ),
        );

        if ( class_exists( 'WP_Travel' ) ) {
            $swingpress_main_slider_content_type = array_merge( $swingpress_main_slider_content_type, array(
                'trip'          => esc_html__( 'Trip', 'swingpress' ),
                ) );
        }

        $output = apply_filters( 'swingpress_main_slider_content_type', $swingpress_main_slider_content_type );


        return $output;
    }
endif;

if ( ! function_exists( 'swingpress_service_content_type' ) ) :
    /**
     * service options
     * @return array site service options
     */
    function swingpress_service_content_type() {
        $swingpress_service_content_type = array(
            'page'      => esc_html__( 'Page', 'swingpress' ),
            'post'      => esc_html__( 'Post', 'swingpress' ),
            'category'  => esc_html__( 'Category', 'swingpress' ),
        );

        if ( class_exists( 'WP_Travel' ) ) {
            $swingpress_service_content_type = array_merge( $swingpress_service_content_type, array(
                'trip'          => esc_html__( 'Trip', 'swingpress' ),
                'trip-types'    => esc_html__( 'Trip Types', 'swingpress' ),
                'destination'   => esc_html__( 'Destination', 'swingpress' ),
                'activity'      => esc_html__( 'Activity', 'swingpress' ),
                ) );
        }

        $output = apply_filters( 'swingpress_service_content_type', $swingpress_service_content_type );


        return $output;
    }
endif;

if ( ! function_exists( 'swingpress_blog_content_type' ) ) :
    /**
     * blog options
     * @return array site blog options
     */
    function swingpress_blog_content_type() {
        $swingpress_blog_content_type = array(
            'page'      => esc_html__( 'Page', 'swingpress' ),
            'post'      => esc_html__( 'Post', 'swingpress' ),
            'category'  => esc_html__( 'Category', 'swingpress' ),
            'recent'    => esc_html__( 'Recent', 'swingpress' ),
        );

        if ( class_exists( 'WP_Travel' ) ) {
            $swingpress_blog_content_type = array_merge( $swingpress_blog_content_type, array(
                'trip'          => esc_html__( 'Trip', 'swingpress' ),
                'trip-types'    => esc_html__( 'Trip Types', 'swingpress' ),
                'destination'   => esc_html__( 'Destination', 'swingpress' ),
                'activity'      => esc_html__( 'Activity', 'swingpress' ),
                ) );
        }

        $output = apply_filters( 'swingpress_blog_content_type', $swingpress_blog_content_type );


        return $output;
    }
endif;



if ( ! function_exists( 'swingpress_featured_content_type' ) ) :
    /**
     * featured Options
     * @return array site featured options
     */
    function swingpress_featured_content_type() {
        $swingpress_featured_content_type = array(
            'category'  => esc_html__( 'Category', 'swingpress' ),
        );

        if ( class_exists( 'WP_Travel' ) ) {
            $swingpress_featured_content_type = array_merge( $swingpress_featured_content_type, array(
                'trip-types'    => esc_html__( 'Trip Types', 'swingpress' ),
                ) );
        }

        $output = apply_filters( 'swingpress_featured_content_type', $swingpress_featured_content_type );


        return $output;
    }
endif;

if ( ! function_exists( 'swingpress_popular_destination_content_type' ) ) :
    /**
     * Destination Options
     * @return array site gallery options
     */
    function swingpress_popular_destination_content_type() {
        $swingpress_popular_destination_content_type = array(
            'post'      => esc_html__( 'Post', 'swingpress' ),
        );

        if ( class_exists( 'WP_Travel' ) ) {
            $swingpress_popular_destination_content_type = array_merge( $swingpress_popular_destination_content_type, array(
                'trip'          => esc_html__( 'Trip', 'swingpress' ),
                ) );
        }

        $output = apply_filters( 'swingpress_popular_destination_content_type', $swingpress_popular_destination_content_type );


        return $output;
    }
endif;

if ( ! function_exists( 'swingpress_package_content_type' ) ) :
    /**
     * Package Options
     * @return array site gallery options
     */
    function swingpress_package_content_type() {
        $swingpress_package_content_type = array(
            'category'  => esc_html__( 'Category', 'swingpress' ),
        );

        if ( class_exists( 'WP_Travel' ) ) {
            $swingpress_package_content_type = array_merge( $swingpress_package_content_type, array(
                'trip-types'    => esc_html__( 'Trip Types', 'swingpress' ),
                'destination'   => esc_html__( 'Destination', 'swingpress' ),
                'activity'      => esc_html__( 'Activity', 'swingpress' ),
                ) );
        }

        $output = apply_filters( 'swingpress_package_content_type', $swingpress_package_content_type );


        return $output;
    }
endif;

if ( ! function_exists( 'swingpress_traveler_choice_content_type' ) ) :
    /**
     * Destination Options
     * @return array site gallery options
     */
    function swingpress_traveler_choice_content_type() {
        $swingpress_traveler_choice_content_type = array(
            'page'      => esc_html__( 'Page', 'swingpress' ),
            'post'      => esc_html__( 'Post', 'swingpress' ),
            'category'  => esc_html__( 'Category', 'swingpress' ),
        );

        if ( class_exists( 'WP_Travel' ) ) {
            $swingpress_traveler_choice_content_type = array_merge( $swingpress_traveler_choice_content_type, array(
                'trip'          => esc_html__( 'Trip', 'swingpress' ),
                'trip-types'    => esc_html__( 'Trip Types', 'swingpress' ),
                'destination'   => esc_html__( 'Destination', 'swingpress' ),
                'activity'      => esc_html__( 'Activity', 'swingpress' ),
                ) );
        }

        $output = apply_filters( 'swingpress_traveler_choice_content_type', $swingpress_traveler_choice_content_type );


        return $output;
    }
endif;

if ( ! function_exists( 'swingpress_discover_places_content_type' ) ) :
    /**
     * Destination Options
     * @return array site gallery options
     */
    function swingpress_discover_places_content_type() {
        $swingpress_discover_places_content_type = array(
            'page'      => esc_html__( 'Page', 'swingpress' ),
            'post'      => esc_html__( 'Post', 'swingpress' ),
            'category'  => esc_html__( 'Category', 'swingpress' ),
        );

        if ( class_exists( 'WP_Travel' ) ) {
            $swingpress_discover_places_content_type = array_merge( $swingpress_discover_places_content_type, array(
                'trip'          => esc_html__( 'Trip', 'swingpress' ),
                'trip-types'    => esc_html__( 'Trip Types', 'swingpress' ),
                'destination'   => esc_html__( 'Destination', 'swingpress' ),
                'activity'      => esc_html__( 'Activity', 'swingpress' ),
                ) );
        }

        $output = apply_filters( 'swingpress_discover_places_content_type', $swingpress_discover_places_content_type );


        return $output;
    }
endif;

if ( ! function_exists( 'swingpress_heading_tags' ) ) :
    /**
     * Destination Options
     * @return array site gallery options
     */
    function swingpress_heading_tags() {
        
        $swingpress_heading_tags = array(
			'h1'	=> esc_html__('H1', 'swingpress'),
			'h2'	=> esc_html__('H2', 'swingpress'),
			'h3'	=> esc_html__('H3', 'swingpress'),
			'h4'	=> esc_html__('H4', 'swingpress'),
			'h5'	=> esc_html__('H5', 'swingpress'),
			'h6'	=> esc_html__('H6', 'swingpress'),
			'p'		=> esc_html__('Paragraph', 'swingpress'),
		);

        $output = apply_filters( 'swingpress_heading_tags', $swingpress_heading_tags );


        return $output;
    }
endif;


if ( ! function_exists( 'swingpress_popular_product_content_type' ) ) :
    /**
     * List of product_layout_similar_product options
     * @return array List of product_layout_similar_product options.
     */
    function swingpress_popular_product_content_type() {
    
        if ( class_exists( 'WooCommerce' ) ) {
                $arr = array(
                'product'           => esc_html__( 'Product', 'swingpress' ),
                'product_cat'       => esc_html__( 'Product Category', 'swingpress' ),
                'recent_product'    => esc_html__( 'Recent Product', 'swingpress' ),
            );
        }

        return apply_filters( 'swingpress_popular_product_content_type', $arr );
    }
endif;

if ( ! function_exists( 'swingpress_latest_product_content_type' ) ) :
    /**
     * List of product_layout_similar_product options
     * @return array List of product_layout_similar_product options.
     */
    function swingpress_latest_product_content_type() {
    
        if ( class_exists( 'WooCommerce' ) ) {
                $arr = array(
                'product'           => esc_html__( 'Product', 'swingpress' ),
                'product_cat'       => esc_html__( 'Product Category', 'swingpress' ),
            );
        }

        return apply_filters( 'swingpress_latest_product_content_type', $arr );
    }
endif;

if ( ! function_exists( 'swingpress_featured_product_content_type' ) ) :
    /**
     * List of product_layout_similar_product options
     * @return array List of product_layout_similar_product options.
     */
    function swingpress_product_content_type() {
        $arr = array(
            'page'      => esc_html__( 'Page', 'swingpress' ),
            'post'      => esc_html__( 'Post', 'swingpress' ),
            'category'  => esc_html__( 'Category', 'swingpress' ),
        );
    
        if ( class_exists( 'WooCommerce' ) ) {
            $arr = array_merge( $arr, array(
                'product'           => esc_html__( 'Product', 'swingpress' ),
                'product-cat'           => esc_html__( 'Product', 'swingpress' ),
                ) );
        }

        return apply_filters( 'swingpress_featured_product_content_type', $arr );
    }
endif;