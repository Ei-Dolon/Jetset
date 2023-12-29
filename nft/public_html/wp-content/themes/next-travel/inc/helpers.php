<?php
/**
 * Theme Palace custom helper funtions
 *
 * This is the template that includes all the other files for core featured of Photo Fusion Pro
 *
 * @package Theme Palace
 * @subpackage Next Travel
 * @since Next Travel 1.0.0
 */

if( ! function_exists( 'next_travel_check_enable_status' ) ):
	/**
	 * Check status of content.
	 *
	 * @since Next Travel 1.0.0
	 */
  	function next_travel_check_enable_status( $input, $content_enable ){
		$options = next_travel_get_theme_options();

		// Content status.
		$content_status = $options[ $content_enable ];

		if ( ( ! is_home() && is_front_page() ) && $content_status ) {
			$input = true;
		}
		else {
			$input = false;
		}
		
		return $input;
  	}
endif;
add_filter( 'next_travel_section_status', 'next_travel_check_enable_status', 10, 2 );


if ( ! function_exists( 'next_travel_is_sidebar_enable' ) ) :
	/**
	 * Check if sidebar is enabled in meta box first then in customizer
	 *
	 * @since Next Travel 1.0.0
	 */
	function next_travel_is_sidebar_enable() {
		$options               = next_travel_get_theme_options();
		$sidebar_position      = $options['sidebar_position'];

		if ( is_home() ) {
			$post_id = get_option( 'page_for_posts' );
			if ( ! empty( $post_id ) )
				$post_sidebar_position = get_post_meta( $post_id, 'next-travel-sidebar-position', true );
			else
				$post_sidebar_position = '';
		} elseif ( is_archive() || is_search() ) {
			$post_sidebar_position = '';
		} else {
			$post_sidebar_position = get_post_meta( get_the_id(), 'next-travel-sidebar-position', true );
			if ( is_single() ) {
				$post_sidebar_position = ! empty( $post_sidebar_position ) ? $post_sidebar_position : $options['post_sidebar_position'];
			} elseif ( is_page() ) {
				$post_sidebar_position = ! empty( $post_sidebar_position ) ? $post_sidebar_position : $options['page_sidebar_position'];
			}
		}
		if ( ( in_array( $sidebar_position, array( 'no-sidebar', 'no-sidebar-content' ) ) && $post_sidebar_position == "" ) || in_array( $post_sidebar_position, array( 'no-sidebar', 'no-sidebar-content' ) ) ) {
			return false;
		} else {
			return true;
		}

	}
endif;


if ( ! function_exists( 'next_travel_is_frontpage_content_enable' ) ) :
	/**
	 * Check home page ( static ) content status.
	 *
	 * @param bool $status Home page content status.
	 * @return bool Modified home page content status.
	 */
	function next_travel_is_frontpage_content_enable( $status ) {
		if ( is_front_page() ) {
			$options = next_travel_get_theme_options();
			$front_page_content_status = $options['enable_frontpage_content'];
			if ( false === $front_page_content_status ) {
				$status = false;
			}
		}
		return $status;
	}

endif;

add_filter( 'next_travel_filter_frontpage_content_enable', 'next_travel_is_frontpage_content_enable' );

add_action( 'next_travel_action_pagination', 'next_travel_pagination', 10 );
if ( ! function_exists( 'next_travel_pagination' ) ) :

	/**
	 * pagination.
	 *
	 * @since Next Travel 1.0.0
	 */
	function next_travel_pagination() {
		$options = next_travel_get_theme_options();
		if ( true == $options['pagination_enable'] ) {
			$pagination = $options['pagination_type'];
			if ( $pagination == 'default' ) :
				the_posts_navigation( array(
			'prev_text'	=> next_travel_get_svg( array( 'icon' => 'up' ) ) .  '<span>' . esc_html__( 'Older', 'next-travel' ) . '</span>',
            'next_text' => '<span>' . esc_html__( 'Next', 'next-travel' ) . '</span>' . next_travel_get_svg( array( 'icon' => 'up' ) ),
		) );
			elseif ( in_array( $pagination, array( 'infinite', 'numeric' ) ) ) :
				the_posts_pagination( array(
				    'mid_size' => 4,
				    'prev_text' => next_travel_get_svg( array( 'icon' => 'up' ) ),
				    'next_text' => next_travel_get_svg( array( 'icon' => 'up' ) ) ,
				) );
			endif;
		}
	}

endif;


add_action( 'next_travel_action_post_pagination', 'next_travel_post_pagination', 10 );
if ( ! function_exists( 'next_travel_post_pagination' ) ) :

	/**
	 * post pagination.
	 *
	 * @since Next Travel 1.0.0
	 */
	function next_travel_post_pagination() {
		the_post_navigation( array(
			'prev_text'	=> next_travel_get_svg( array( 'icon' => 'up' ) ) .  '<span>%title</span>',
            'next_text' => '<span>%title</span>' . next_travel_get_svg( array( 'icon' => 'up' ) ),
		) );
	}
endif;


if ( ! function_exists( 'next_travel_excerpt_length' ) ) :
	/**
	 * long excerpt
	 * 
	 * @since Next Travel 1.0.0
	 * @return long excerpt value
	 */
	function next_travel_excerpt_length( $length ){
		if ( is_admin() ) {
			return $length;
		}

		$options = next_travel_get_theme_options();
		$length = $options['long_excerpt_length'];
		return $length;
	}
endif;
add_filter( 'excerpt_length', 'next_travel_excerpt_length', 999 );


if ( ! function_exists( 'next_travel_excerpt_more' ) ) :
	// Read more
	function next_travel_excerpt_more( $more ){
		if ( is_admin() ) {
			return $more;
		}

		return '&hellip;';
	}
endif;
add_filter( 'excerpt_more', 'next_travel_excerpt_more' );


if ( ! function_exists( 'next_travel_trim_content' ) ) :
	/**
	 * custom excerpt function
	 * 
	 * @since Next Travel 1.0.0
	 * @return  no of words to display
	 */
	function next_travel_trim_content( $length = 40, $post_obj = null ) {
		global $post;
		if ( is_null( $post_obj ) ) {
			$post_obj = $post;
		}

		$length = absint( $length );
		if ( $length < 1 ) {
			$length = 40;
		}

		$source_content = $post_obj->post_content;
		if ( ! empty( $post_obj->post_excerpt ) ) {
			$source_content = $post_obj->post_excerpt;
		}

		$source_content = preg_replace( '`\[[^\]]*\]`', '', $source_content );
		$trimmed_content = wp_trim_words( $source_content, $length, '...' );

	   return apply_filters( 'next_travel_trim_content', $trimmed_content );
	}
endif;


if ( ! function_exists( 'next_travel_layout' ) ) :
	/**
	 * Check home page layout option
	 *
	 * @since Next Travel 1.0.0
	 *
	 * @return string Next Travel layout value
	 */
	function next_travel_layout() {
		$options = next_travel_get_theme_options();

		$sidebar_position = $options['sidebar_position'];
		$sidebar_position_post = $options['post_sidebar_position'];
		$sidebar_position_page = $options['page_sidebar_position'];
		$sidebar_position = apply_filters( 'next_travel_sidebar_position', $sidebar_position );
		// Check if single and static blog page
		if ( is_singular() || is_home() ) {
			if ( is_home() ) {
				$post_sidebar_position = get_post_meta( get_option( 'page_for_posts' ), 'next-travel-sidebar-position', true );
			} else {
				$post_sidebar_position = get_post_meta( get_the_ID(), 'next-travel-sidebar-position', true );
			}
			if ( isset( $post_sidebar_position ) && ! empty( $post_sidebar_position ) ) {
				$sidebar_position = $post_sidebar_position;
			} elseif ( is_single() ) {
				$sidebar_position = $sidebar_position_post;
			} elseif ( is_page() ) {
				$sidebar_position = $sidebar_position_page;
			}
		} 
		return $sidebar_position;
	}
endif;

/**
 * Add SVG definitions to the footer.
 */
function next_travel_include_svg_icons() {
	// Define SVG sprite file.
	$svg_icons = get_template_directory() . '/assets/images/svg-icons.svg';

	// If it exists, include it.
	if ( file_exists( $svg_icons ) ) {
		require $svg_icons;
	}
}
add_action( 'wp_footer', 'next_travel_include_svg_icons', 9999 );

/**
 * Return SVG markup.
 *
 * @param array $args {
 *     Parameters needed to display an SVG.
 *
 *     @type string $icon  Required SVG icon filename.
 *     @type string $title Optional SVG title.
 *     @type string $desc  Optional SVG description.
 * }
 * @return string SVG markup.
 */
function next_travel_get_svg( $args = array() ) {
	// Make sure $args are an array.
	if ( empty( $args ) ) {
		return esc_html__( 'Please define default parameters in the form of an array.', 'next-travel' );
	}

	// Define an icon.
	if ( false === array_key_exists( 'icon', $args ) ) {
		return esc_html__( 'Please define an SVG icon filename.', 'next-travel' );
	}

	// Set defaults.
	$defaults = array(
		'icon'        => '',
		'title'       => '',
		'desc'        => '',
		'class'        => '',
		'fallback'    => false,
	);

	// Parse args.
	$args = wp_parse_args( $args, $defaults );

	// Set aria hidden.
	$aria_hidden = ' aria-hidden="true"';

	// Set ARIA.
	$aria_labelledby = '';

	/*
	 * Theme Palace doesn't use the SVG title or description attributes; non-decorative icons are described with .screen-reader-text.
	 *
	 * However, child themes can use the title and description to add information to non-decorative SVG icons to improve accessibility.
	 *
	 * Example 1 with title: <?php echo next_travel_get_svg( array( 'icon' => 'arrow-right', 'title' => __( 'This is the title', 'textdomain' ) ) ); ?>
	 *
	 * Example 2 with title and description: <?php echo next_travel_get_svg( array( 'icon' => 'arrow-right', 'title' => __( 'This is the title', 'textdomain' ), 'desc' => __( 'This is the description', 'textdomain' ) ) ); ?>
	 *
	 * See https://www.paciellogroup.com/blog/2013/12/using-aria-enhance-svg-accessibility/.
	 */
	if ( $args['title'] ) {
		$aria_hidden     = '';
		$unique_id    	 = uniqid();
		$aria_labelledby = ' aria-labelledby="title-' . esc_attr( $unique_id ) . '"';

		if ( $args['desc'] ) {
			$aria_labelledby = ' aria-labelledby="title-' . esc_attr( $unique_id ) . ' desc-' . esc_attr( $unique_id ) . '"';
		}
	}

	// Begin SVG markup.
	$svg = '<svg class="icon-' . esc_attr( $args['icon'] ) . ' ' . esc_attr( $args['class'] ) . '"' . $aria_hidden . $aria_labelledby . ' role="img">';

	// Display the title.
	if ( $args['title'] ) {
		$svg .= '<title id="title-' . esc_attr( $unique_id ) . '">' . esc_html( $args['title'] ) . '</title>';

		// Display the desc only if the title is already set.
		if ( $args['desc'] ) {
			$svg .= '<desc id="desc-' . esc_attr( $unique_id ) . '">' . esc_html( $args['desc'] ) . '</desc>';
		}
	}

	/*
	 * Display the icon.
	 *
	 * The whitespace around `<use>` is intentional - it is a work around to a keyboard navigation bug in Safari 10.
	 *
	 * See https://core.trac.wordpress.org/ticket/38387.
	 */
	$svg .= ' <use href="#icon-' . esc_html( $args['icon'] ) . '" xlink:href="#icon-' . esc_html( $args['icon'] ) . '"></use> ';

	// Add some markup to use as a fallback for browsers that do not support SVGs.
	if ( $args['fallback'] ) {
		$svg .= '<span class="svg-fallback icon-' . esc_attr( $args['icon'] ) . '"></span>';
	}

	$svg .= '</svg>';

	return $svg;
}

/**
 * Add dropdown icon if menu item has children.
 *
 * @param  string $title The menu item's title.
 * @param  object $item  The current menu item.
 * @param  array  $args  An array of wp_nav_menu() arguments.
 * @param  int    $depth Depth of menu item. Used for padding.
 * @return string $title The menu item's title with dropdown icon.
 */
function next_travel_dropdown_icon_to_menu_link( $title, $item, $args, $depth ) {
	if ( 'primary' === $args->theme_location ) {
		foreach ( $item->classes as $value ) {
			if ( 'menu-item-has-children' === $value || 'page_item_has_children' === $value ) {
				$title = $title . next_travel_get_svg( array( 'icon' => 'down' ) );
			}
		}
	}

	return $title;
}
add_filter( 'nav_menu_item_title', 'next_travel_dropdown_icon_to_menu_link', 10, 4 );

/**
 * Returns an array of supported social links (URL and icon name).
 *
 * @return array $social_links_icons
 */
function next_travel_social_links_icons() {
	// Supported social links icons.
	$social_links_icons = array(
		'behance.net'     => 'behance',
		'codepen.io'      => 'codepen',
		'deviantart.com'  => 'deviantart',
		'digg.com'        => 'digg',
		'dribbble.com'    => 'dribbble',
		'dropbox.com'     => 'dropbox',
		'facebook.com'    => 'facebook',
		'flickr.com'      => 'flickr',
		'foursquare.com'  => 'foursquare',
		'plus.google.com' => 'google-plus',
		'github.com'      => 'github',
		'instagram.com'   => 'instagram',
		'linkedin.com'    => 'linkedin',
		'mailto:'         => 'envelope-o',
		'medium.com'      => 'medium',
		'pinterest.com'   => 'pinterest-p',
		'getpocket.com'   => 'get-pocket',
		'reddit.com'      => 'reddit-alien',
		'skype.com'       => 'skype',
		'skype:'          => 'skype',
		'slideshare.net'  => 'slideshare',
		'snapchat.com'    => 'snapchat-ghost',
		'soundcloud.com'  => 'soundcloud',
		'spotify.com'     => 'spotify',
		'stumbleupon.com' => 'stumbleupon',
		'tumblr.com'      => 'tumblr',
		'twitch.tv'       => 'twitch',
		'twitter.com'     => 'twitter',
		'vimeo.com'       => 'vimeo',
		'vine.co'         => 'vine',
		'vk.com'          => 'vk',
		'wordpress.org'   => 'wordpress',
		'wordpress.com'   => 'wordpress',
		'yelp.com'        => 'yelp',
		'youtube.com'     => 'youtube',
	);

	/**
	 * Filter Next Travel social links icons.
	 *
	 * @since Next Travel 1.0.0
	 *
	 * @param array $social_links_icons Array of social links icons.
	 */
	return apply_filters( 'next_travel_social_links_icons', $social_links_icons );
}

/**
 * Display SVG icons in social links menu.
 *
 * @param  string  $item_output The menu item output.
 * @param  WP_Post $item        Menu item object.
 * @param  int     $depth       Depth of the menu.
 * @param  array   $args        wp_nav_menu() arguments.
 * @return string  $item_output The menu item output with social icon.
 */
function next_travel_nav_menu_social_icons( $item_output, $item, $depth, $args ) {
	// Get supported social icons.
	$social_icons = next_travel_social_links_icons();

	// Change SVG icon inside social links menu if there is supported URL.
	if ( 'social' === $args->theme_location ) {
		foreach ( $social_icons as $attr => $value ) {
			if ( false !== strpos( $item_output, $attr ) ) {
				$item_output = str_replace( $args->link_after, '</span>' . next_travel_get_svg( array( 'icon' => esc_attr( $value ) ) ), $item_output );
			}
		}
	}

	return $item_output;
}
add_filter( 'walker_nav_menu_start_el', 'next_travel_nav_menu_social_icons', 10, 4 );

/**
 * Display SVG icons as per the link.
 *
 * @param  string   $social_link        Theme mod value rendered
 * @return string  SVG icon HTML
 */
function next_travel_return_social_icon( $social_link ) {
	// Get supported social icons.
	$social_icons = next_travel_social_links_icons();

	// Check in the URL for the url in the array.
	foreach ( $social_icons as $attr => $value ) {
		if ( false !== strpos( $social_link, $attr ) ) {
			return next_travel_get_svg( array( 'icon' => esc_attr( $value ) ) );
		}
	}
}

/**
 * Fallback function call for menu
 * @param  Mixed $args Menu arguments
 * @return String $output Return or echo the add menu link.       
 */
function next_travel_menu_fallback_cb( $args ){
    if ( ! current_user_can( 'edit_theme_options' ) ){
	    return;
   	}
    // see wp-includes/nav-menu-template.php for available arguments
    $link = $args['link_before']
        	. '<a href="' .esc_url( admin_url( 'nav-menus.php' ) ) . '">' . $args['before'] . esc_html__( 'Add a menu','next-travel' ) . $args['after'] . '</a>'
        	. $args['link_after'];

   	if ( FALSE !== stripos( $args['items_wrap'], '<ul' ) || FALSE !== stripos( $args['items_wrap'], '<ol' )
	){
		$link = "<li>$link</li>";
	}
	$output = sprintf( $args['items_wrap'], $args['menu_id'], $args['menu_class'], $link );
	if ( ! empty ( $args['container'] ) ){
		$output = sprintf( '<%1$s class="%2$s" id="%3$s">%4$s</%1$s>', $args['container'], $args['container_class'], $args['container_id'], $output );
	}
	if ( $args['echo'] ){
		echo $output;
	}
	return $output;
}

/**
 * Function to detect SCRIPT_DEBUG on and off.
 * @return string If on, empty else return .min string.
 */
function next_travel_min() {
	return defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
}

/**
 * Checks to see if we're on the homepage or not.
 */
function next_travel_is_frontpage() {
	return ( is_front_page() && ! is_home() );
}

/**
 * Checks to see if Static Front Page is set to "Your latest posts".
 */
function next_travel_is_latest_posts() {
	return ( is_front_page() && is_home() );
}

/**
 * Checks to see if blog Page
 */
function next_travel_is_blog_page() {
	return ( ! is_front_page() && is_home() );
}

if ( ! function_exists( 'next_travel_simple_breadcrumb' ) ) :
	/**
	 * Simple breadcrumb.
	 *
	 * @param  array $args Arguments
	 */
	function next_travel_simple_breadcrumb( $args = array() ) {
		/**
		 * Add breadcrumb.
		 *
		 */
		$options = next_travel_get_theme_options();
		
		// Bail if Breadcrumb disabled.
		if ( ! $options['breadcrumb_enable'] ) {
			return;
		}

		$args = array(
			'show_on_front'   => false,
			'show_title'      => true,
			'show_browse'     => false,
		);
		breadcrumb_trail( $args );      

		return;
	}

endif;
add_action( 'next_travel_simple_breadcrumb', 'next_travel_simple_breadcrumb' , 10 );

/**
 * Display custom header title in frontpage and blog
 */
function next_travel_custom_header_banner_title() {	
	$options = next_travel_get_theme_options();
	if ( next_travel_is_latest_posts() ) : 
		$title = ! empty( $options['your_latest_posts_title'] ) ? $options['your_latest_posts_title'] : esc_html_e( 'Blog', 'next-travel' ); ?>
		<h2 class="page-title"><?php echo esc_html( $title ); ?></h2>
	<?php elseif ( next_travel_is_blog_page() || is_singular() ): ?>
		<h1 class="page-title"><?php single_post_title(); ?></h1>
	<?php elseif ( is_archive() ) : 
		the_archive_title( '<h1 class="page-title">', '</h1>' );
	elseif ( is_search() ) : ?>
		<h1 class="page-title"><?php printf( esc_html__( 'Search Results for: %s', 'next-travel' ), get_search_query() ); ?></h1>
	<?php elseif ( is_404() ) :
		echo '<h1 class="page-title">' . esc_html__( 'Oops! That page can&#39;t be found.', 'next-travel' ) . '</h1>';
	endif;
}


/**
 * Rating for trip types
 */
function next_travel_average_post_rating($id ='', $taxonomy='') {	
	$args = array(
		'post_type'      => 'itineraries',
		'tax_query' => array(
			array(
				'taxonomy' => $taxonomy,
				'field'    => 'id',
				'terms'    => $id,
			),
		),
		'posts_per_page'  => -1,
	);
	$query = new WP_Query( $args );
	$average_rating = 0;
	$post_count = $query->post_count;
	while($query->have_posts()) :
		if ( $query->have_posts() ) : $query->the_post();
			if ( next_travel_wptravel_tab_show_in_menu( 'reviews' ) )
				$average_rating += next_travel_wptravel_get_average_rating( get_the_ID() ); 
		endif;
	endwhile;
	wp_reset_postdata();
	if($post_count == 0){
		$post_count = 1;
	}
	return $average_rating/$post_count;
}
// }
