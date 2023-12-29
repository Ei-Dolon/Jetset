<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Theme Palace
 * @subpackage Swingpress
 * @since Swingpress 1.0.0
 */

/**
 * swingpress_footer_primary_content hook
 *
 * @hooked swingpress_add_contact_section -  10
 *
 */
do_action( 'swingpress_footer_primary_content' );

/**
 * swingpress_content_end_action hook
 *
 * @hooked swingpress_content_end -  10
 *
 */
do_action( 'swingpress_content_end_action' );

/**
 * swingpress_content_end_action hook
 *
 * @hooked swingpress_footer_start -  10
 * @hooked swingpress_Footer_Widgets->add_footer_widgets -  20
 * @hooked swingpress_footer_site_info -  40
 * @hooked swingpress_footer_end -  100
 *
 */
do_action( 'swingpress_footer' );

/**
 * swingpress_page_end_action hook
 *
 * @hooked swingpress_page_end -  10
 *
 */
do_action( 'swingpress_page_end_action' ); 

?>

<?php wp_footer(); ?>

</body>
</html>
