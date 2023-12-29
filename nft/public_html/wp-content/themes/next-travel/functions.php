<?php
/**
 * Theme Palace functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Theme Palace
 * @subpackage Next Travel
 * @since Next Travel 1.0.0
 */

if ( ! function_exists( 'next_travel_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function next_travel_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on Theme Palace, use a find and replace
		 * to change 'next-travel' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'next-travel' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		add_theme_support( "responsive-embeds" );

		add_theme_support( 'register_block_pattern' ); 

		add_theme_support( 'register_block_style' ); 

		// Enable support for footer widgets.
		add_theme_support( 'footer-widgets', 4 );

		// Enable excerpt for page.
		add_post_type_support( 'page', 'excerpt' );

		// Load Footer Widget Support.
		require_if_theme_supports( 'footer-widgets', get_template_directory() . '/inc/footer-widgets.php' );
		
		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );
		set_post_thumbnail_size( 600, 600, true );

		// Set the default content width.
		$GLOBALS['content_width'] = 525;
		
		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( array(
			'primary' 	=> esc_html__( 'Primary', 'next-travel' ),
			'social' 	=> esc_html__( 'Social', 'next-travel' ),
		) );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		// Set up the WordPress core custom background feature.
		add_theme_support( 'custom-background', apply_filters( 'next_travel_custom_background_args', array(
			'default-color' => 'ffffff',
			'default-image' => '',
		) ) );

		// This setup supports logo, site-title & site-description
		add_theme_support( 'custom-logo', array(
			'height'      => 70,
			'width'       => 120,
			'flex-height' => true,
			'flex-width'  => true,
			'header-text' => array( 'site-title', 'site-description' ),
		) );

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		// add woocommerce support
		add_theme_support( 'woocommerce' );
		if ( class_exists( 'WooCommerce' ) ) {
			global $woocommerce;

			if( version_compare( $woocommerce->version, '3.0.0', ">=" ) ) {
				add_theme_support( 'wc-product-gallery-zoom' );
				add_theme_support( 'wc-product-gallery-lightbox' );
				add_theme_support( 'wc-product-gallery-slider' );
			}
		}

		/*
		 * This theme styles the visual editor to resemble the theme style,
		 * specifically font, colors, icons, and column width.
		 */
		add_editor_style( array( '/assets/css/editor-style' . next_travel_min() . '.css', next_travel_fonts_url() ) );

		// Gutenberg support
		add_theme_support( 'editor-color-palette', array(
	       	array(
				'name' => esc_html__( 'Blue', 'next-travel' ),
				'slug' => 'blue',
				'color' => '#2c7dfa',
	       	),
	       	array(
	           	'name' => esc_html__( 'Green', 'next-travel' ),
	           	'slug' => 'green',
	           	'color' => '#07d79c',
	       	),
	       	array(
	           	'name' => esc_html__( 'Orange', 'next-travel' ),
	           	'slug' => 'orange',
	           	'color' => '#ff8737',
	       	),
	       	array(
	           	'name' => esc_html__( 'Black', 'next-travel' ),
	           	'slug' => 'black',
	           	'color' => '#2f3633',
	       	),
	       	array(
	           	'name' => esc_html__( 'Grey', 'next-travel' ),
	           	'slug' => 'grey',
	           	'color' => '#82868b',
	       	),
	   	));

		add_theme_support( 'align-wide' );
		add_theme_support( 'editor-font-sizes', array(
		   	array(
		       	'name' => esc_html__( 'small', 'next-travel' ),
		       	'shortName' => esc_html__( 'S', 'next-travel' ),
		       	'size' => 12,
		       	'slug' => 'small'
		   	),
		   	array(
		       	'name' => esc_html__( 'regular', 'next-travel' ),
		       	'shortName' => esc_html__( 'M', 'next-travel' ),
		       	'size' => 16,
		       	'slug' => 'regular'
		   	),
		   	array(
		       	'name' => esc_html__( 'larger', 'next-travel' ),
		       	'shortName' => esc_html__( 'L', 'next-travel' ),
		       	'size' => 36,
		       	'slug' => 'larger'
		   	),
		   	array(
		       	'name' => esc_html__( 'huge', 'next-travel' ),
		       	'shortName' => esc_html__( 'XL', 'next-travel' ),
		       	'size' => 48,
		       	'slug' => 'huge'
		   	)
		));
		add_theme_support('editor-styles');
		add_theme_support( 'wp-block-styles' );
	}
endif;
add_action( 'after_setup_theme', 'next_travel_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function next_travel_content_width() {

	$content_width = $GLOBALS['content_width'];


	$sidebar_position = next_travel_layout();
	switch ( $sidebar_position ) {

	  case 'no-sidebar':
	    $content_width = 1170;
	    break;

	  case 'left-sidebar':
	  case 'right-sidebar':
	    $content_width = 819;
	    break;

	  default:
	    break;
	}

	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$content_width = 1170;
	}

	/**
	 * Filter Next Travel content width of the theme.
	 *
	 * @since Next Travel 1.0.0
	 *
	 * @param int $content_width Content width in pixels.
	 */
	$GLOBALS['content_width'] = apply_filters( 'next_travel_content_width', $content_width );
}
add_action( 'template_redirect', 'next_travel_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function next_travel_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'next-travel' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.', 'next-travel' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'WooCommerce Sidebar', 'next-travel' ),
		'id'            => 'woocommerce-sidebar',
		'description'   => esc_html__( 'Add widgets here.', 'next-travel' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	register_sidebars( 4, array(
		'name'          => esc_html__( 'Optional Sidebar %d', 'next-travel' ),
		'id'            => 'optional-sidebar',
		'description'   => esc_html__( 'Add widgets here.', 'next-travel' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'next_travel_widgets_init' );


if ( ! function_exists( 'next_travel_fonts_url' ) ) :
/**
 * Register Google fonts
 *
 * @return string Google fonts URL for the theme.
 */
function next_travel_fonts_url() {
	$fonts_url = '';
	$fonts     = array();
	$subsets   = 'latin,latin-ext';

	/* translators: If there are characters in your language that are not supported by Lato, translate this to 'off'. Do not translate into your own language. */
	if ( 'off' !== _x( 'on', 'Lato font: on or off', 'next-travel' ) ) {
		$fonts[] = 'Lato:400,700';
	}

	/* translators: If there are characters in your language that are not supported by Kaushan Script, translate this to 'off'. Do not translate into your own language. */
	if ( 'off' !== _x( 'on', 'Kaushan Script font: on or off', 'next-travel' ) ) {
		$fonts[] = 'Kaushan Script:400,700';
	}

	/* translators: If there are characters in your language that are not supported by Exo+2, translate this to 'off'. Do not translate into your own language. */
	if ( 'off' !== _x( 'on', 'Exo 2 font: on or off', 'next-travel' ) ) {
		$fonts[] = 'Exo 2:300,400,500,700,800,900,600';
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

/**
 * Add preconnect for Google Fonts.
 *
 * @since Next Travel 1.0.0
 *
 * @param array  $urls           URLs to print for resource hints.
 * @param string $relation_type  The relation type the URLs are printed.
 * @return array $urls           URLs to print for resource hints.
 */
function next_travel_resource_hints( $urls, $relation_type ) {
	if ( wp_style_is( 'next-travel-fonts', 'queue' ) && 'preconnect' === $relation_type ) {
		$urls[] = array(
			'href' => 'https://fonts.gstatic.com',
			'crossorigin',
		);
	}

	return $urls;
}
add_filter( 'wp_resource_hints', 'next_travel_resource_hints', 10, 2 );

/**
 * Enqueue scripts and styles.
 */
function next_travel_scripts() {
	$options = next_travel_get_theme_options();
	// Add custom fonts, used in the main stylesheet.
	wp_enqueue_style( 'next-travel-fonts', wptt_get_webfont_url(next_travel_fonts_url()), array(), '1.0' );

	// font-awesome
	wp_enqueue_style( 'font-awesome', get_template_directory_uri() . '/assets/font-awesome/css/font-awesome' . next_travel_min() . '.css', array(), '4.7.0' );

	// wp-travel-front-end
	wp_enqueue_style( 'wp-travel-front-end', get_template_directory_uri() . '/assets/css/wp-travel-front-end.css' );

	// blocks
	wp_enqueue_style( 'next-travel-blocks', get_template_directory_uri() . '/assets/css/blocks' . next_travel_min() . '.css' );

	// slick
	wp_enqueue_style( 'slick', get_template_directory_uri() . '/assets/css/slick' . next_travel_min() . '.css' );

	// slick theme
	wp_enqueue_style( 'slick-theme', get_template_directory_uri() . '/assets/css/slick-theme' . next_travel_min() . '.css' );

	wp_enqueue_style( 'next-travel-style', get_stylesheet_uri() );

	// Load the colorscheme.
	$color_scheme = $options['colorscheme'];
	if ( ! in_array( $color_scheme, array( 'custom', 'default' ) ) ) {
		wp_enqueue_style( 'next-travel-colors', get_template_directory_uri() . '/assets/css/' . esc_attr( $color_scheme ) . next_travel_min() .'.css', array( 'next-travel-style' ), '1.0' );
	}
	
	//skip-link-focus-fix
	wp_enqueue_script( 'next-travel-skip-link-focus-fix', get_template_directory_uri() . '/assets/js/skip-link-focus-fix' . next_travel_min() . '.js', array(), '20160412', true );

	wp_enqueue_script( 'next-travel-navigation', get_template_directory_uri() . '/assets/js/navigation' . next_travel_min() . '.js', array(), '20151215', true );
	
	$next_travel_l10n = array(
		'quote'          => next_travel_get_svg( array( 'icon' => 'quote-right' ) ),
		'expand'         => esc_html__( 'Expand child menu', 'next-travel' ),
		'collapse'       => esc_html__( 'Collapse child menu', 'next-travel' ),
		'icon'           => next_travel_get_svg( array( 'icon' => 'down', 'fallback' => true ) ),
	);
	
	wp_localize_script( 'next-travel-navigation', 'next_travel_l10n', $next_travel_l10n );

	if ( !is_front_page() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	//slick.js
	wp_enqueue_script( 'jquery-slick', get_template_directory_uri() . '/assets/js/slick.js', array( 'jquery' ), '1.6.0', true );

	// imagesloaded
	wp_enqueue_script( 'imagesloaded' );

	//packery
	wp_enqueue_script( 'packery-pkgd', get_template_directory_uri() . '/assets/js/packery.pkgd' . next_travel_min() . '.js', array('jquery'), '2.1.2', true );

	// matchHeight
	wp_enqueue_script( 'jquery-matchHeight', get_template_directory_uri() . '/assets/js/jquery.matchHeight.js', array( 'jquery' ), true );

	wp_enqueue_script( 'next-travel-custom', get_template_directory_uri() . '/assets/js/custom' . next_travel_min() . '.js', array( 'jquery' ), '20151215', true );

	if ( 'infinite' == $options['pagination_type']) {
		// infinite scroll js
		wp_enqueue_script( 'next-travel-infinite-scroll', get_template_directory_uri() . '/assets/js/infinite-scroll' . next_travel_min() . '.js', array( 'jquery' ), '', true );
	}
}
add_action( 'wp_enqueue_scripts', 'next_travel_scripts' );

/**
 * Enqueue editor styles for Gutenberg
 *
 * @since Next Travel 1.0.0
 */
function next_travel_block_editor_styles() {
	// Block styles.
	wp_enqueue_style( 'next-travel-block-editor-style', get_theme_file_uri( '/assets/css/editor-blocks' . next_travel_min() . '.css' ) );
	// Add custom fonts.
	wp_enqueue_style( 'next-travel-fonts', wptt_get_webfont_url(next_travel_fonts_url()), array(), '1.0' );
}
add_action( 'enqueue_block_editor_assets', 'next_travel_block_editor_styles' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

/**
 * Load core file
 */
require get_template_directory() . '/inc/core.php';

/**
* Add widget
*/
require get_template_directory() . '/inc/widgets/widgets.php';
