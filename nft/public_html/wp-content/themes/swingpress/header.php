<?php
	/**
	 * The header for our theme.
	 *
	 * This is the template that displays all of the <head> section and everything up until <div id="content">
	 *
	 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
	 *
	 * @package Theme Palace
	 * @subpackage Swingpress
	 * @since Swingpress 1.0.0
	 */

	/**
	 * swingpress_doctype hook
	 *
	 * @hooked swingpress_doctype -  10
	 *
	 */
	do_action( 'swingpress_doctype' );

?>
<head>
<?php
	/**
	 * swingpress_before_wp_head hook
	 *
	 * @hooked swingpress_head -  10
	 *
	 */
	do_action( 'swingpress_before_wp_head' );

	wp_head(); 
?>
</head>

<body <?php body_class(); ?>>

<?php do_action( 'wp_body_open' ); ?>

<?php
	/**
	 * swingpress_page_start_action hook
	 *
	 * @hooked swingpress_page_start -  10
	 *
	 */
	do_action( 'swingpress_page_start_action' ); 

	/**
	 * swingpress_loader_action hook
	 *
	 * @hooked swingpress_loader -  10
	 *
	 */
	do_action( 'swingpress_before_header' );

	/**
	 * swingpress_header_action hook
	 *
	 * @hooked swingpress_header_start -  10
	 * @hooked swingpress_site_branding -  20
	 * @hooked swingpress_site_navigation -  30
	 * @hooked swingpress_header_end -  50
	 *
	 */
	do_action( 'swingpress_header_action' );

	/**
	 * swingpress_content_start_action hook
	 *
	 * @hooked swingpress_content_start -  10
	 *
	 */
	do_action( 'swingpress_content_start_action' );

	/**
	 * swingpress_header_image_action hook
	 *
	 * @hooked swingpress_header_image -  10
	 *
	 */
	do_action( 'swingpress_header_image_action' );

	if ( swingpress_is_frontpage() ) {
    	$options = swingpress_get_theme_options();

    	$sorted = explode( ',', $options['all_sortable']);
		
		foreach ( $sorted as $section ) {
			add_action( 'swingpress_primary_content', 'swingpress_add_'. $section .'_section' );
		}

		do_action( 'swingpress_primary_content' );
	}