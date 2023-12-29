<?php
/**
 * Kortez Travel back compat functionality
 *
 * Prevents Kortez Travel from running on WordPress versions prior to 4.7,
 * since this theme is not meant to be backward compatible beyond that and
 * relies on many newer functions and markup changes introduced in 4.7.
 *
 * @since Kortez Travel 1.0.0
 */

/**
 * Prevent switching to Kortez Travel on old versions of WordPress.
 *
 * Switches to the default theme.
 *
 * @since Kortez Travel 1.0.0
 */
function kortez_travel_switch_theme() {
	switch_theme( WP_DEFAULT_THEME );
	unset( $_GET['activated'] );
	add_action( 'admin_notices', 'kortez_travel_upgrade_notice' );
}
add_action( 'after_switch_theme', 'kortez_travel_switch_theme' );

/**
 * Adds a message for unsuccessful theme switch.
 *
 * Prints an update nag after an unsuccessful attempt to switch to
 * Kortez Travel on WordPress versions prior to 4.7.
 *
 * @since Kortez Travel 1.0.0
 * @global string $wp_version WordPress version.
 */
function kortez_travel_upgrade_notice() {
    /* translators: %s - WordPress version*/
	$message = sprintf( esc_html__( 'Kortez Travel requires at least WordPress version 4.7. You are running version %s. Please upgrade and try again.', 'kortez-travel' ), $GLOBALS['wp_version'] );
	printf( '<div class="error"><p>%s</p></div>', esc_html( $message ) );
}

/**
 * Prevents the Customizer from being loaded on WordPress versions prior to 4.7.
 *
 * @since Kortez Travel 1.0.0
 *
 * @global string $wp_version WordPress version.
 */
function kortez_travel_customize() {
    /* translators: %s - WordPress version*/
	wp_die( sprintf( esc_html__( 'Kortez Travel requires at least WordPress version 4.7. You are running version %s. Please upgrade and try again.', 'kortez-travel' ), esc_html( $GLOBALS['wp_version'] ) ), '', array(
		'back_link' => true,
	) );
}
add_action( 'load-customize.php', 'kortez_travel_customize' );

/**
 * Prevents the Theme Preview from being loaded on WordPress versions prior to 4.7.
 *
 * @since Kortez Travel 1.0.0
 * @global string $wp_version WordPress version.
 */
function kortez_travel_preview() {
	if ( isset( $_GET['preview'] ) ) {
        /* translators: %s - WordPress version*/
		wp_die( sprintf( esc_html__( 'Kortez Travel requires at least WordPress version 4.7. You are running version %s. Please upgrade and try again.', 'kortez-travel' ), esc_html( $GLOBALS['wp_version'] ) ) );
	}
}
add_action( 'template_redirect', 'kortez_travel_preview' );
