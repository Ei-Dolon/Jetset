<?php
/**
 * Customizer active callbacks
 *
 * @package Theme Palace
 * @subpackage Swingpress
 * @since Swingpress 1.0.0
 */

if ( ! function_exists( 'swingpress_is_loader_enable' ) ) :
	/**
	 * Check if loader is enabled.
	 *
	 * @since Swingpress 1.0.0
	 * @param WP_Customize_Control $control WP_Customize_Control instance.
	 * @return bool Whether the control is active to the current preview.
	 */
	function swingpress_is_loader_enable( $control ) {
		return $control->manager->get_setting( 'swingpress_theme_options[loader_enable]' )->value();
	}
endif;

if ( ! function_exists( 'swingpress_is_breadcrumb_enable' ) ) :
	/**
	 * Check if breadcrumb is enabled.
	 *
	 * @since Swingpress 1.0.0
	 * @param WP_Customize_Control $control WP_Customize_Control instance.
	 * @return bool Whether the control is active to the current preview.
	 */
	function swingpress_is_breadcrumb_enable( $control ) {
		return $control->manager->get_setting( 'swingpress_theme_options[breadcrumb_enable]' )->value();
	}
endif;

if ( ! function_exists( 'swingpress_is_pagination_enable' ) ) :
	/**
	 * Check if pagination is enabled.
	 *
	 * @since Swingpress 1.0.0
	 * @param WP_Customize_Control $control WP_Customize_Control instance.
	 * @return bool Whether the control is active to the current preview.
	 */
	function swingpress_is_pagination_enable( $control ) {
		return $control->manager->get_setting( 'swingpress_theme_options[pagination_enable]' )->value();
	}
endif;

if ( ! function_exists( 'swingpress_is_static_homepage_enable' ) ) :
	/**
	 * Check if static homepage is enabled.
	 *
	 * @since Swingpress 1.0.0
	 * @param WP_Customize_Control $control WP_Customize_Control instance.
	 * @return bool Whether the control is active to the current preview.
	 */
	function swingpress_is_static_homepage_enable( $control ) {
		return ( 'page' == $control->manager->get_setting( 'show_on_front' )->value() );
	}
endif;

/**
 * Front Page Active Callbacks
 */

/**
 * Check if slider section is enabled.
 *
 * @since Swingpress 1.0.0
 * @param WP_Customize_Control $control WP_Customize_Control instance.
 * @return bool Whether the control is active to the current preview.
 */
function swingpress_is_slider_section_enable( $control ) {
	return ( $control->manager->get_setting( 'swingpress_theme_options[slider_section_enable]' )->value() );
}

/**
 * Check if slider section content type is category.
 *
 * @since Swingpress 1.0.0
 * @param WP_Customize_Control $control WP_Customize_Control instance.
 * @return bool Whether the control is active to the current preview.
 */
function swingpress_is_slider_section_content_category_enable( $control ) {
	$content_type = $control->manager->get_setting( 'swingpress_theme_options[slider_content_type]' )->value();
	return swingpress_is_slider_section_enable( $control ) && ( 'category' == $content_type );
}

/**
 * Check if slider section content type is page.
 *
 * @since Swingpress 1.0.0
 * @param WP_Customize_Control $control WP_Customize_Control instance.
 * @return bool Whether the control is active to the current preview.
 */
function swingpress_is_slider_section_content_page_enable( $control ) {
	$content_type = $control->manager->get_setting( 'swingpress_theme_options[slider_content_type]' )->value();
	return swingpress_is_slider_section_enable( $control ) && ( 'page' == $content_type );
}

function swingpress_is_slider_section_content_trip_enable( $control ) {
	$content_type = $control->manager->get_setting( 'swingpress_theme_options[slider_content_type]' )->value();
	return swingpress_is_slider_section_enable( $control ) && ( 'trip' == $content_type );
}



/**
 * Check if featured section is enabled.
 *
 * @since Swingpress 1.0.0
 * @param WP_Customize_Control $control WP_Customize_Control instance.
 * @return bool Whether the control is active to the current preview.
 */
function swingpress_is_featured_section_enable( $control ) {
	return ( $control->manager->get_setting( 'swingpress_theme_options[featured_section_enable]' )->value() );
}

/**
 * Check if featured section content type is category.
 *
 * @since Swingpress 1.0.0
 * @param WP_Customize_Control $control WP_Customize_Control instance.
 * @return bool Whether the control is active to the current preview.
 */
function swingpress_is_featured_section_content_category_enable( $control ) {
	$content_type = $control->manager->get_setting( 'swingpress_theme_options[featured_content_type]' )->value();
	return swingpress_is_featured_section_enable( $control ) && ( 'category' == $content_type );
}

/**
 * Check if featured section content type is trip types.
 *
 * @since Swingpress 1.0.0
 * @param WP_Customize_Control $control WP_Customize_Control instance.
 * @return bool Whether the control is active to the current preview.
 */
function swingpress_is_featured_section_content_trip_types_enable( $control ) {
	$content_type = $control->manager->get_setting( 'swingpress_theme_options[featured_content_type]' )->value();
	return swingpress_is_featured_section_enable( $control ) && ( 'trip-types' == $content_type );
}


/**
 * Check if about section is enabled.
 *
 * @since Swingpress 1.0.0
 * @param WP_Customize_Control $control WP_Customize_Control instance.
 * @return bool Whether the control is active to the current preview.
 */
function swingpress_is_about_section_enable( $control ) {
	return ( $control->manager->get_setting( 'swingpress_theme_options[about_section_enable]' )->value() );
}


/**
 * Check if popular destination section is enabled.
 *
 * @since Swingpress 1.0.0
 * @param WP_Customize_Control $control WP_Customize_Control instance.
 * @return bool Whether the control is active to the current preview.
 */
function swingpress_is_popular_destination_section_enable( $control ) {
	return ( $control->manager->get_setting( 'swingpress_theme_options[popular_destination_section_enable]' )->value() );
}

/**
 * Check if popular destination section content type is post.
 *
 * @since Swingpress 1.0.0
 * @param WP_Customize_Control $control WP_Customize_Control instance.
 * @return bool Whether the control is active to the current preview.
 */
function swingpress_is_popular_destination_section_content_post_enable( $control ) {
	$content_type = $control->manager->get_setting( 'swingpress_theme_options[popular_destination_content_type]' )->value();
	return swingpress_is_popular_destination_section_enable( $control ) && ( 'post' == $content_type );
}

/**
 * Check if popular destination section content type is trip.
 *
 * @since Swingpress 1.0.0
 * @param WP_Customize_Control $control WP_Customize_Control instance.
 * @return bool Whether the control is active to the current preview.
 */
function swingpress_is_popular_destination_section_content_trip_enable( $control ) {
	$content_type = $control->manager->get_setting( 'swingpress_theme_options[popular_destination_content_type]' )->value();
	return swingpress_is_popular_destination_section_enable( $control ) && ( 'trip' == $content_type );
}


/**
 * Check if service section is enabled.
 *
 * @since Swingpress 1.0.0
 * @param WP_Customize_Control $control WP_Customize_Control instance.
 * @return bool Whether the control is active to the current preview.
 */
function swingpress_is_service_section_enable( $control ) {
	return ( $control->manager->get_setting( 'swingpress_theme_options[service_section_enable]' )->value() );
}

/**
 * Check if blog section is enabled.
 *
 * @since Swingpress 1.0.0
 * @param WP_Customize_Control $control WP_Customize_Control instance.
 * @return bool Whether the control is active to the current preview.
 */
function swingpress_is_blog_section_enable( $control ) {
	return ( $control->manager->get_setting( 'swingpress_theme_options[blog_section_enable]' )->value() );
}

/**
 * Check if blog section content type is post.
 *
 * @since Swingpress 1.0.0
 * @param WP_Customize_Control $control WP_Customize_Control instance.
 * @return bool Whether the control is active to the current preview.
 */
function swingpress_is_blog_section_content_post_enable( $control ) {
	$content_type = $control->manager->get_setting( 'swingpress_theme_options[blog_content_type]' )->value();
	return swingpress_is_blog_section_enable( $control ) && ( 'post' == $content_type );
}

/**
 * Check if blog section content type is page.
 *
 * @since Swingpress 1.0.0
 * @param WP_Customize_Control $control WP_Customize_Control instance.
 * @return bool Whether the control is active to the current preview.
 */
function swingpress_is_blog_section_content_page_enable( $control ) {
	$content_type = $control->manager->get_setting( 'swingpress_theme_options[blog_content_type]' )->value();
	return swingpress_is_blog_section_enable( $control ) && ( 'page' == $content_type );
}

/**
 * Check if blog section content type is category.
 *
 * @since Swingpress 1.0.0
 * @param WP_Customize_Control $control WP_Customize_Control instance.
 * @return bool Whether the control is active to the current preview.
 */
function swingpress_is_blog_section_content_category_enable( $control ) {
	$content_type = $control->manager->get_setting( 'swingpress_theme_options[blog_content_type]' )->value();
	return swingpress_is_blog_section_enable( $control ) && ( 'category' == $content_type );
}

/**
 * Check if testimonial section is enabled.
 *
 * @since Swingpress 1.0.0
 * @param WP_Customize_Control $control WP_Customize_Control instance.
 * @return bool Whether the control is active to the current preview.
 */
function swingpress_is_testimonial_section_enable( $control ) {
	return ( $control->manager->get_setting( 'swingpress_theme_options[testimonial_section_enable]' )->value() );
}

