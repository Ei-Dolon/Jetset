<?php
/**
 * Kortez Travel functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Kortez Travel
 */

if ( ! function_exists( 'kortez_travel_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function kortez_travel_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on kortez_travel, use a find and replace
		 * to change 'kortez-travel' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'kortez-travel', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( array(
			'menu-1' => esc_html__( 'Primary', 'kortez-travel' ),
		) );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		/**
		 * Add support for Post Formats.
		 *
		 * @link https://codex.wordpress.org/Post_Formats
		 */
		add_theme_support( 'post-formats' , array( 'aside', 'gallery' , 'standard', 'link', 'image' , 'quote', 'status', 'video', 'audio' , 'chat' ));
		
		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support( 'custom-logo', array(
			'width'       => 270,
			'height'      => 80,
			'flex-height' => true,
			'flex-width'  => true,
		) );

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		// Add support for woocommerce.
		add_theme_support( 'woocommerce' );

		// Add custom image size.
		add_image_size( 'kortez-travel-1920-550', 1920, 550, true );
		add_image_size( 'kortez-travel-1370-550', 1370, 550, true );
		add_image_size( 'kortez-travel-590-310', 590, 310, true );
		add_image_size( 'kortez-travel-420-380', 420, 380, true );
		add_image_size( 'kortez-travel-420-300', 420, 300, true );
		add_image_size( 'kortez-travel-420-200', 420, 200, true );
		add_image_size( 'kortez-travel-290-150', 290, 150, true );
		add_image_size( 'kortez-travel-80-60', 80, 60, true );

		/*
		* This theme styles the visual editor to resemble the theme style,
		* specifically font, colors, icons, and column width.
		*/
		
		add_editor_style( array( '/assets/css/editor-style.min.css') );

		// Gutenberg support
		add_theme_support( 'editor-color-palette', array(
	       	array(
				'name'  => esc_html__( 'Tan', 'kortez-travel' ),
				'slug'  => 'tan',
				'color' => '#D2B48C',
	       	),
	       	array(
	           	'name'  => esc_html__( 'Yellow', 'kortez-travel' ),
	           	'slug'  => 'yellow',
	           	'color' => '#FDE64B',
	       	),
	       	array(
	           	'name'  => esc_html__( 'Orange', 'kortez-travel' ),
	           	'slug'  => 'orange',
	           	'color' => '#ED7014',
	       	),
	       	array(
	           	'name'  => esc_html__( 'Red', 'kortez-travel' ),
	           	'slug'  => 'red',
	           	'color' => '#D0312D',
	       	),
	       	array(
	           	'name'  => esc_html__( 'Pink', 'kortez-travel' ),
	           	'slug'  => 'pink',
	           	'color' => '#b565a7',
	       	),
	       	array(
	           	'name'  => esc_html__( 'Purple', 'kortez-travel' ),
	           	'slug'  => 'purple',
	           	'color' => '#A32CC4',
	       	),
	       	array(
	           	'name'  => esc_html__( 'Blue', 'kortez-travel' ),
	           	'slug'  => 'blue',
	           	'color' => '#4E97D8',
	       	),
	       	array(
	           	'name'  => esc_html__( 'Green', 'kortez-travel' ),
	           	'slug'  => 'green',
	           	'color' => '#00B294',
	       	),
	       	array(
	           	'name'  => esc_html__( 'Brown', 'kortez-travel' ),
	           	'slug'  => 'brown',
	           	'color' => '#231709',
	       	),
	       	array(
	           	'name'  => esc_html__( 'Grey', 'kortez-travel' ),
	           	'slug'  => 'grey',
	           	'color' => '#7D7D7D',
	       	),
	       	array(
	           	'name'  => esc_html__( 'Black', 'kortez-travel' ),
	           	'slug'  => 'black',
	           	'color' => '#000000',
	       	),
	   	));

		add_theme_support( 'align-wide' );
		add_theme_support( 'editor-font-sizes', array(
		   	array(
		       	'name'      => esc_html__( 'small', 'kortez-travel' ),
		       	'shortName' => esc_html__( 'S', 'kortez-travel' ),
		       	'size'      => 12,
		       	'slug'      => 'small'
		   	),
		   	array(
		       	'name'      => esc_html__( 'regular', 'kortez-travel' ),
		       	'shortName' => esc_html__( 'M', 'kortez-travel' ),
		       	'size'      => 16,
		       	'slug'      => 'regular'
		   	),
		   	array(
		       	'name'      => esc_html__( 'larger', 'kortez-travel' ),
		       	'shortName' => esc_html__( 'L', 'kortez-travel' ),
		       	'size'      => 36,
		       	'slug'      => 'larger'
		   	),
		   	array(
		       	'name'      => esc_html__( 'huge', 'kortez-travel' ),
		       	'shortName' => esc_html__( 'XL', 'kortez-travel' ),
		       	'size'      => 48,
		       	'slug'      => 'huge'
		   	)
		));
		add_theme_support( 'editor-styles' );
		add_theme_support( 'wp-block-styles' );

		/* woocommerce support */
		add_theme_support( 'wc-product-gallery-zoom' );
	    add_theme_support( 'wc-product-gallery-lightbox' );
	    add_theme_support( 'wc-product-gallery-slider' );

	    add_theme_support( 'responsive-embeds' );
	}
endif;
add_action( 'after_setup_theme', 'kortez_travel_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function kortez_travel_content_width() {
	// This variable is intended to be overruled from themes.
	// Open WPCS issue: {@link https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/issues/1043}.
	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
	$GLOBALS['content_width'] = apply_filters( 'kortez_travel_content_width', 720 );
}
add_action( 'after_setup_theme', 'kortez_travel_content_width', 0 );