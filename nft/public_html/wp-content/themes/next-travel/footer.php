<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Theme Palace
 * @subpackage Next Travel
 * @since Next Travel 1.0.0
 */

/**
 * next_travel_footer_primary_content hook
 *
 * @hooked next_travel_add_contact_section -  10
 *
 */
do_action( 'next_travel_footer_primary_content' );

/**
 * next_travel_content_end_action hook
 *
 * @hooked next_travel_content_end -  10
 *
 */
do_action( 'next_travel_content_end_action' );

/**
 * next_travel_content_end_action hook
 *
 * @hooked next_travel_footer_start -  10
 * @hooked Next_Travel_Footer_Widgets->add_footer_widgets -  20
 * @hooked next_travel_footer_site_info -  40
 * @hooked next_travel_footer_end -  100
 *
 */
do_action( 'next_travel_footer' );

/**
 * next_travel_page_end_action hook
 *
 * @hooked next_travel_page_end -  10
 *
 */
do_action( 'next_travel_page_end_action' ); 

?>

<?php wp_footer(); ?>

</body>
</html>
