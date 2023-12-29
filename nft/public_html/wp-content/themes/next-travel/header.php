<?php
	/**
	 * The header for our theme.
	 *
	 * This is the template that displays all of the <head> section and everything up until <div id="content">
	 *
	 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
	 *
	 * @package Theme Palace
	 * @subpackage Next Travel
	 * @since Next Travel 1.0.0
	 */

	/**
	 * next_travel_doctype hook
	 *
	 * @hooked next_travel_doctype -  10
	 *
	 */
	do_action( 'next_travel_doctype' );

?>
<head>
<?php
	/**
	 * next_travel_before_wp_head hook
	 *
	 * @hooked next_travel_head -  10
	 *
	 */
	do_action( 'next_travel_before_wp_head' );

	wp_head(); 
?>
</head>

<body <?php body_class(); ?>>

<?php 
	if ( function_exists( 'wp_body_open' ) ) {
		wp_body_open();
	}
?>

<?php
	/**
	 * next_travel_page_start_action hook
	 *
	 * @hooked next_travel_page_start -  10
	 *
	 */
	do_action( 'next_travel_page_start_action' ); 

	/**
	 * next_travel_loader_action hook
	 *
	 * @hooked next_travel_loader -  10
	 *
	 */
	do_action( 'next_travel_before_header' );

	/**
	 * next_travel_header_action hook
	 *
	 * @hooked next_travel_header_start -  10
	 * @hooked next_travel_site_branding -  20
	 * @hooked next_travel_site_navigation -  30
	 * @hooked next_travel_header_end -  50
	 *
	 */
	do_action( 'next_travel_header_action' );

	/**
	 * next_travel_content_start_action hook
	 *
	 * @hooked next_travel_content_start -  10
	 *
	 */
	do_action( 'next_travel_content_start_action' );

	/**
	 * next_travel_header_image_action hook
	 *
	 * @hooked next_travel_header_image -  10
	 *
	 */
	do_action( 'next_travel_header_image_action' );

    if ( next_travel_is_frontpage() ) {
    	$options = next_travel_get_theme_options();
    	$sorted = array();
    	if ( ! empty( $options['sortable'] ) ) {
			$sorted = explode( ',' , $options['sortable'] );
		}
		
		foreach ( $sorted as $section ) {
			add_action( 'next_travel_primary_content', 'next_travel_add_'. $section .'_section' );
		}
		do_action( 'next_travel_primary_content' );
	}