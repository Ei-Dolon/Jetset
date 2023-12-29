<?php
	/**
	 * The header for our theme.
	 *
	 * This is the template that displays all of the <head> section and everything up until <div id="content">
	 *
	 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
	 *
	 * @package Theme Palace
	 * @subpackage gloomy_travel_life
	 * @since gloomy_travel_life 1.0.0
	 */

	/**
	 * travel_life_doctype hook
	 *
	 * @hooked travel_life_doctype -  10
	 *
	 */
	do_action( 'travel_life_doctype' );

?>
<head>
<?php
	/**
	 * travel_life_before_wp_head hook
	 *
	 * @hooked travel_life_head -  10
	 *
	 */
	do_action( 'travel_life_before_wp_head' );

	wp_head(); 
?>
</head>

<body <?php body_class(); ?>>

<?php do_action( 'wp_body_open' ); ?>

<?php
	/**
	 * travel_life_page_start_action hook
	 *
	 * @hooked travel_life_page_start -  10
	 *
	 */
	do_action( 'travel_life_page_start_action' ); 

	/**
	 * travel_life_loader_action hook
	 *
	 * @hooked travel_life_loader -  10
	 *
	 */
	do_action( 'travel_life_before_header' );

	/**
	 * travel_life_header_action hook
	 *
	 * @hooked travel_life_header_start -  10
	 * @hooked travel_life_site_branding -  20
	 * @hooked travel_life_site_navigation -  30
	 * @hooked travel_life_header_end -  50
	 *
	 */
	do_action( 'travel_life_header_action' );

	/**
	 * travel_life_content_start_action hook
	 *
	 * @hooked travel_life_content_start -  10
	 *
	 */
	do_action( 'travel_life_content_start_action' );

	/**
	 * travel_life_header_image_action hook
	 *
	 * @hooked travel_life_header_image -  10
	 *
	 */
	do_action( 'travel_life_header_image_action' );

	if ( travel_life_is_frontpage() ) {
    	$options = travel_life_get_theme_options();

		$sorted = array( 'slider', 'trip_search', 'package', 'counter', 'about', 'popular_destination', 'testimonial', 'blog', 'subscription' );
	
		foreach ( $sorted as $section ) {

			if ( $section == 'trip_search' || $section == 'blog' ) {
				add_action( 'gloomy_travel_life_primary_content', 'gloomy_travel_life_add_'. $section .'_section' );
			}else{
				add_action( 'gloomy_travel_life_primary_content', 'travel_life_add_'. $section .'_section' );
			}
			
		}

		do_action( 'gloomy_travel_life_primary_content' );
	}