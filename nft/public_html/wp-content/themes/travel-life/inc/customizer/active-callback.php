<?php
/**
 * Customizer active callbacks
 *
 * @package Theme Palace
 * @subpackage Travel Life
 * @since Travel Life 1.0.0
 */



if ( ! function_exists( 'travel_life_is_breadcrumb_enable' ) ) :
	/**
	 * Check if breadcrumb is enabled.
	 *
	 * @since Travel Life 1.0.0
	 * @param WP_Customize_Control $control WP_Customize_Control instance.
	 * @return bool Whether the control is active to the current preview.
	 */
	function travel_life_is_breadcrumb_enable( $control ) {
		return $control->manager->get_setting( 'travel_life_theme_options[breadcrumb_enable]' )->value();
	}
endif;

if ( ! function_exists( 'travel_life_is_pagination_enable' ) ) :
	/**
	 * Check if pagination is enabled.
	 *
	 * @since Travel Life 1.0.0
	 * @param WP_Customize_Control $control WP_Customize_Control instance.
	 * @return bool Whether the control is active to the current preview.
	 */
	function travel_life_is_pagination_enable( $control ) {
		return $control->manager->get_setting( 'travel_life_theme_options[pagination_enable]' )->value();
	}
endif;

if ( ! function_exists( 'travel_life_is_static_homepage_enable' ) ) :
	/**
	 * Check if static homepage is enabled.
	 *
	 * @since Travel Life 1.0.0
	 * @param WP_Customize_Control $control WP_Customize_Control instance.
	 * @return bool Whether the control is active to the current preview.
	 */
	function travel_life_is_static_homepage_enable( $control ) {
		return ( 'page' == $control->manager->get_setting( 'show_on_front' )->value() );
	}
endif;

/**
 * Check if topbar section is enabled.
 *
 * @since Travel Life 1.0.0
 * @param WP_Customize_Control $control WP_Customize_Control instance.
 * @return bool Whether the control is active to the current preview.
 */
function travel_life_is_topbar_contact_enable( $control ) {
	return ( $control->manager->get_setting( 'travel_life_theme_options[top_bar_contact_enable]' )->value() );
}
/**
 * Check if topbar section is enabled.
 *
 * @since Travel Life 1.0.0
 * @param WP_Customize_Control $control WP_Customize_Control instance.
 * @return bool Whether the control is active to the current preview.
 */
function travel_life_is_topbar_button_enable( $control ) {
	return ( $control->manager->get_setting( 'travel_life_theme_options[topbar_button_enable]' )->value() );
}


/**
 * Front Page Active Callbacks
 */

/**
 * Check if slider section is enabled.
 *
 * @since Travel Life 1.0.0
 * @param WP_Customize_Control $control WP_Customize_Control instance.
 * @return bool Whether the control is active to the current preview.
 */
function travel_life_is_slider_section_enable( $control ) {
	return ( $control->manager->get_setting( 'travel_life_theme_options[slider_section_enable]' )->value() );
}



/**
 * Check if popular destination section is enabled.
 *
 * @since Travel Life 1.0.0
 * @param WP_Customize_Control $control WP_Customize_Control instance.
 * @return bool Whether the control is active to the current preview.
 */
function travel_life_is_popular_destination_section_enable( $control ) {
	return ( $control->manager->get_setting( 'travel_life_theme_options[popular_destination_section_enable]' )->value() );
}

/**
 * Check if popular destination section content type is page.
 *
 * @since Travel Life 1.0.0
 * @param WP_Customize_Control $control WP_Customize_Control instance.
 * @return bool Whether the control is active to the current preview.
 */
function travel_life_is_popular_destination_section_content_page_enable( $control ) {
	$content_type = $control->manager->get_setting( 'travel_life_theme_options[popular_destination_content_type]' )->value();
	return travel_life_is_popular_destination_section_enable( $control ) && ( 'page' == $content_type );
}

/**
 * Check if popular destination section content type is trip.
 *
 * @since Travel Life 1.0.0
 * @param WP_Customize_Control $control WP_Customize_Control instance.
 * @return bool Whether the control is active to the current preview.
 */
function travel_life_is_popular_destination_section_content_trip_enable( $control ) {
	$content_type = $control->manager->get_setting( 'travel_life_theme_options[popular_destination_content_type]' )->value();
	return travel_life_is_popular_destination_section_enable( $control ) && ( 'trip' == $content_type );
}

/**
 * Check if about section is enabled.
 *
 * @since Travel Life 1.0.0
 * @param WP_Customize_Control $control WP_Customize_Control instance.
 * @return bool Whether the control is active to the current preview.
 */
function travel_life_is_about_section_enable( $control ) {
	return ( $control->manager->get_setting( 'travel_life_theme_options[about_section_enable]' )->value() );
}


/**
 * Check if package section is enabled.
 *
 * @since Travel Life 1.0.0
 * @param WP_Customize_Control $control WP_Customize_Control instance.
 * @return bool Whether the control is active to the current preview.
 */
function travel_life_is_package_section_enable( $control ) {
	return ( $control->manager->get_setting( 'travel_life_theme_options[package_section_enable]' )->value() );
}

function travel_life_is_package_section_content_trip_enable( $control ) {
	$content_type = $control->manager->get_setting( 'travel_life_theme_options[package_content_type]' )->value();
	return travel_life_is_package_section_enable( $control ) && ( 'trip' == $content_type );
}


/**
 * Check if package section content type is category.
 *
 * @since Travel Life 1.0.0
 * @param WP_Customize_Control $control WP_Customize_Control instance.
 * @return bool Whether the control is active to the current preview.
 */
function travel_life_is_package_section_content_page_enable( $control ) {
	$content_type = $control->manager->get_setting( 'travel_life_theme_options[package_content_type]' )->value();
	return travel_life_is_package_section_enable( $control ) && ( 'page' == $content_type );
}


/**
 * Check if testimonial section is enabled.
 *
 * @since Travel Life 1.0.0
 * @param WP_Customize_Control $control WP_Customize_Control instance.
 * @return bool Whether the control is active to the current preview.
 */
function travel_life_is_testimonial_section_enable( $control ) {
	return ( $control->manager->get_setting( 'travel_life_theme_options[testimonial_section_enable]' )->value() );
}

/**
 * Check if subscription section is enabled.
 *
 * @since Travel Life 1.0.0
 * @param WP_Customize_Control $control WP_Customize_Control instance.
 * @return bool Whether the control is active to the current preview.
 */
function travel_life_is_subscription_section_enable( $control ) {
	return ( $control->manager->get_setting( 'travel_life_theme_options[subscription_section_enable]' )->value() );
}



/**
 * Check if counter section is enabled.
 *
 * @since Travel Life 1.0.0
 * @param WP_Customize_Control $control WP_Customize_Control instance.
 * @return bool Whether the control is active to the current preview.
 */
function travel_life_is_counter_section_enable( $control ) {
	return ( $control->manager->get_setting( 'travel_life_theme_options[counter_section_enable]' )->value() );
}

