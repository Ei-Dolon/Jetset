<?php
/**
* Load widget components
*
* @since Kortez Travel 1.0.0
*/
require_once get_parent_theme_file_path( '/inc/widgets/class-base-widget.php' );
require_once get_parent_theme_file_path( '/inc/widgets/latest-posts.php' );
require_once get_parent_theme_file_path( '/inc/widgets/author.php' );
/**
 * Register widgets
 *
 * @since Kortez Travel 1.0.0
 */
/**
* Load all the widgets
* @since Kortez Travel 1.0.0
*/
function kortez_travel_register_widget() {

	$widgets = array(
		'kortez_travel_Latest_Posts_Widget',
		'kortez_travel_Author_Widget',
	);

	foreach ( $widgets as $key => $value) {
    	register_widget( $value );
	}
}
add_action( 'widgets_init', 'kortez_travel_register_widget' );