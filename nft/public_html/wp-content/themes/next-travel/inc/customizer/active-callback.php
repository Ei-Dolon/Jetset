<?php
/**
 * Customizer active callbacks
 *
 * @package Theme Palace
 * @subpackage Next Travel
 * @since Next Travel 1.0.0
 */

if ( ! function_exists( 'next_travel_is_breadcrumb_enable' ) ) :
	/**
	 * Check if breadcrumb is enabled.
	 *
	 * @since Next Travel 1.0.0
	 * @param WP_Customize_Control $control WP_Customize_Control instance.
	 * @return bool Whether the control is active to the current preview.
	 */
	function next_travel_is_breadcrumb_enable( $control ) {
		return $control->manager->get_setting( 'next_travel_theme_options[breadcrumb_enable]' )->value();
	}
endif;

if ( ! function_exists( 'next_travel_is_pagination_enable' ) ) :
	/**
	 * Check if pagination is enabled.
	 *
	 * @since Next Travel 1.0.0
	 * @param WP_Customize_Control $control WP_Customize_Control instance.
	 * @return bool Whether the control is active to the current preview.
	 */
	function next_travel_is_pagination_enable( $control ) {
		return $control->manager->get_setting( 'next_travel_theme_options[pagination_enable]' )->value();
	}
endif;

if ( ! function_exists( 'next_travel_is_static_homepage_enable' ) ) :
	/**
	 * Check if static homepage is enabled.
	 *
	 * @since Next Travel 1.0.0
	 * @param WP_Customize_Control $control WP_Customize_Control instance.
	 * @return bool Whether the control is active to the current preview.
	 */
	function next_travel_is_static_homepage_enable( $control ) {
		return ( 'page' == $control->manager->get_setting( 'show_on_front' )->value() );
	}
endif;



/**
 * Front Page Active Callbacks
 */

/*=================Slider Section===================*/

/**
 * Check if slider section is enabled.
 *
 * @since Next Travel 1.0.0
 * @param WP_Customize_Control $control WP_Customize_Control instance.
 * @return bool Whether the control is active to the current preview.
 */
function next_travel_is_slider_section_enable( $control ) {
	return ( $control->manager->get_setting( 'next_travel_theme_options[slider_section_enable]' )->value() );
}




/*=================About Us section=======================*/

/**
 * Check if about section is enabled.
 *
 * @since Next Travel 1.0.0
 * @param WP_Customize_Control $control WP_Customize_Control instance.
 * @return bool Whether the control is active to the current preview.
 */
function next_travel_is_about_section_enable( $control ) {
	return ( $control->manager->get_setting( 'next_travel_theme_options[about_section_enable]' )->value() );
}

/*=================Gallery Slider Section===================*/

/**
 * Check if gallery_slider section is enabled.
 *
 * @since Next Travel 1.0.0
 * @param WP_Customize_Control $control WP_Customize_Control instance.
 * @return bool Whether the control is active to the current preview.
 */
function next_travel_is_gallery_slider_section_enable( $control ) {
	return ( $control->manager->get_setting( 'next_travel_theme_options[gallery_slider_section_enable]' )->value() );
}


/**
 * Check if gallery_slider section content type is page.
 *
 * @since Next Travel 1.0.0
 * @param WP_Customize_Control $control WP_Customize_Control instance.
 * @return bool Whether the control is active to the current preview.
 */
function next_travel_is_gallery_slider_section_content_page_enable( $control ) {
	$content_type = $control->manager->get_setting( 'next_travel_theme_options[gallery_slider_content_type]' )->value();
	return next_travel_is_gallery_slider_section_enable( $control ) && ( 'page' == $content_type );
}

/**
 * Check if Gallery Slider section content type is trip-types.
 *
 * @since Next Travel 1.0.0
 * @param WP_Customize_Control $control WP_Customize_Control instance.
 * @return bool Whether the control is active to the current preview.
 */
function next_travel_is_gallery_slider_section_content_trip_types_enable( $control ) {
	$content_type = $control->manager->get_setting( 'next_travel_theme_options[gallery_slider_content_type]' )->value();
	return next_travel_is_gallery_slider_section_enable( $control ) && ( 'trip-types' == $content_type );
}

/*====================recommended Destination========================*/
/**
 * Check if recommended destination section is enabled.
 *
 * @since Next Travel 1.0.0
 * @param WP_Customize_Control $control WP_Customize_Control instance.
 * @return bool Whether the control is active to the current preview.
 */
function next_travel_is_recommended_destination_section_enable( $control ) {
	return ( $control->manager->get_setting( 'next_travel_theme_options[recommended_destination_section_enable]' )->value() );
}

/**
 * Check if recommended destination section content type is category.
 *
 * @since Next Travel 1.0.0
 * @param WP_Customize_Control $control WP_Customize_Control instance.
 * @return bool Whether the control is active to the current preview.
 */
function next_travel_is_recommended_destination_section_content_category_enable( $control ) {
	$content_type = $control->manager->get_setting( 'next_travel_theme_options[recommended_destination_content_type]' )->value();
	return next_travel_is_recommended_destination_section_enable( $control ) && ( 'category' == $content_type );
}

/**
 * Check if Recommended Destination section content type is trip-types.
 *
 * @since Next Travel 1.0.0
 * @param WP_Customize_Control $control WP_Customize_Control instance.
 * @return bool Whether the control is active to the current preview.
 */
function next_travel_is_recommended_destination_section_content_trip_types_enable( $control ) {
	$content_type = $control->manager->get_setting( 'next_travel_theme_options[recommended_destination_content_type]' )->value();
	return next_travel_is_recommended_destination_section_enable( $control ) && ( 'trip-types' == $content_type );
}

/*=======================Blog=======================*/
/**
 * Check if blog section is enabled.
 *
 * @since Next Travel 1.0.0
 * @param WP_Customize_Control $control WP_Customize_Control instance.
 * @return bool Whether the control is active to the current preview.
 */
function next_travel_is_blog_section_enable( $control ) {
	return ( $control->manager->get_setting( 'next_travel_theme_options[blog_section_enable]' )->value() );
}

/**
 * Check if blog section content type is category.
 *
 * @since Next Travel 1.0.0
 * @param WP_Customize_Control $control WP_Customize_Control instance.
 * @return bool Whether the control is active to the current preview.
 */
function next_travel_is_blog_section_content_category_enable( $control ) {
	$content_type = $control->manager->get_setting( 'next_travel_theme_options[blog_content_type]' )->value();
	return next_travel_is_blog_section_enable( $control ) && ( 'category' == $content_type );
}

/**
 * Check if blog section content type is recent.
 *
 * @since Next Travel 1.0.0
 * @param WP_Customize_Control $control WP_Customize_Control instance.
 * @return bool Whether the control is active to the current preview.
 */
function next_travel_is_blog_section_content_recent_enable( $control ) {
	$content_type = $control->manager->get_setting( 'next_travel_theme_options[blog_content_type]' )->value();
	return next_travel_is_blog_section_enable( $control ) && ( 'recent' == $content_type );
}