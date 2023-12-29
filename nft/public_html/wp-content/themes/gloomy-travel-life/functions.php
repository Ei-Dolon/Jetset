<?php

if ( ! function_exists( 'gloomy_travel_life_enqueue_styles' ) ) :

	function gloomy_travel_life_enqueue_styles() {
		wp_enqueue_style( 'gloomy-travel-life-style-parent', get_template_directory_uri() . '/style.css' );

		wp_enqueue_style( 'gloomy-travel-life-style', get_stylesheet_directory_uri() . '/style.css', array( 'gloomy-travel-life-style-parent' ), '1.0.0' );

		wp_enqueue_style( 'gloomy-travel-life-fonts', gloomy_travel_life_fonts_url(), array(), null );
	}
endif;
add_action( 'wp_enqueue_scripts', 'gloomy_travel_life_enqueue_styles', 99 );

function gloomy_travel_life_customize_control_style() {
	
	wp_enqueue_style( 'gloomy_travel_life_customizer_control', get_theme_file_uri() . '/customizer-control.css' );

}
add_action( 'customize_controls_enqueue_scripts', 'gloomy_travel_life_customize_control_style' );


if ( !function_exists( 'gloomy_travel_life_block_editor_styles' ) ):

	function gloomy_travel_life_block_editor_styles() {
		wp_enqueue_style( 'gloomy-travel-life-fonts', gloomy_travel_life_fonts_url(), array(), null );
	}

endif;

add_action( 'enqueue_block_editor_assets', 'gloomy_travel_life_block_editor_styles' );


if ( ! function_exists( 'gloomy_travel_life_fonts_url' ) ) :

function gloomy_travel_life_fonts_url() {
	
	$fonts_url = '';
	$fonts     = array();
	$subsets   = 'latin,latin-ext';

	if ( 'off' !== _x( 'on', 'Poppins font: on or off', 'gloomy-travel-life' ) ) {
		$fonts[] = 'Poppins:400,500,600,700';
	}

	$query_args = array(
		'family' => urlencode( implode( '|', $fonts ) ),
		'subset' => urlencode( $subsets ),
	);

	if ( $fonts ) {
		$fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
	}

	return esc_url_raw( $fonts_url );
}

endif;


if ( ! function_exists( 'gloomy_travel_life_body_classes' ) ) :

	function gloomy_travel_life_body_classes( $classes ) {

		$body_class[] = 'dark-version';

		if ( travel_life_is_frontpage() ) {
			$body_class[] = 'home-page';
		}

		return $body_class;

	}

endif;


add_filter( 'body_class', 'gloomy_travel_life_body_classes' );


require get_theme_file_path() . '/inc/customizer.php';

require get_theme_file_path() . '/inc/front-sections/trip-search.php';

require get_theme_file_path() . '/inc/front-sections/blog.php';
