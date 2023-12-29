<?php
/**
 * Theme Palace basic theme structure hooks
 *
 * This file contains structural hooks.
 *
 * @package Theme Palace
 * @subpackage Next Travel
 * @since Next Travel 1.0.0
 */

$options = next_travel_get_theme_options();


if ( ! function_exists( 'next_travel_doctype' ) ) :
	/**
	 * Doctype Declaration.
	 *
	 * @since Next Travel 1.0.0
	 */
	function next_travel_doctype() {
	?>
		<!DOCTYPE html>
			<html <?php language_attributes(); ?>>
	<?php
	}
endif;

add_action( 'next_travel_doctype', 'next_travel_doctype', 10 );


if ( ! function_exists( 'next_travel_head' ) ) :
	/**
	 * Header Codes
	 *
	 * @since Next Travel 1.0.0
	 *
	 */
	function next_travel_head() {
		?>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<link rel="profile" href="https://gmpg.org/xfn/11">
		<?php if ( is_singular() && pings_open( get_queried_object() ) ) : ?>
			<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
		<?php endif;
	}
endif;
add_action( 'next_travel_before_wp_head', 'next_travel_head', 10 );

if ( ! function_exists( 'next_travel_page_start' ) ) :
	/**
	 * Page starts html codes
	 *
	 * @since Next Travel 1.0.0
	 *
	 */
	function next_travel_page_start() {
		?>
		<div id="page" class="site">
			<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'next-travel' ); ?></a>

		<?php
	}
endif;
add_action( 'next_travel_page_start_action', 'next_travel_page_start', 10 );

if ( ! function_exists( 'next_travel_header_start' ) ) :
	/**
	 * Header start html codes
	 *
	 * @since Next Travel 1.0.0
	 *
	 */
	function next_travel_header_start() {
		?>
		<div id="loader">
            <div class="loader-container">
                <div id="preloader">
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>
        </div><!-- #loader -->
		<div class="menu-overlay"></div>
		<header id="masthead" class="site-header" role="banner">
		<?php
	}
endif;
add_action( 'next_travel_header_action', 'next_travel_header_start', 20 );

if ( ! function_exists( 'next_travel_page_end' ) ) :
	/**
	 * Page end html codes
	 *
	 * @since Next Travel 1.0.0
	 *
	 */
	function next_travel_page_end() {
		?>
		</div><!-- #page -->
		<?php
	}
endif;
add_action( 'next_travel_page_end_action', 'next_travel_page_end', 10 );

if ( ! function_exists( 'next_travel_site_branding' ) ) :
	/**
	 * Site branding codes
	 *
	 * @since Next Travel 1.0.0
	 *
	 */
	function next_travel_site_branding() {
		$options  = next_travel_get_theme_options();
		$header_txt_logo_extra = $options['header_txt_logo_extra'];		
		?>
		<div class="wrapper">
		<div class="site-branding">
			<?php if ( in_array( $header_txt_logo_extra, array( 'show-all', 'logo-title', 'logo-tagline' ) ) && has_custom_logo() ) { ?>
				<div class="site-logo">
					<?php the_custom_logo(); ?>
				</div>
			<?php } 
			if ( in_array( $header_txt_logo_extra, array( 'show-all', 'title-only', 'logo-title', 'show-all', 'tagline-only', 'logo-tagline' ) ) ) : ?>
				<div id="site-identity">
					<?php
					if( in_array( $header_txt_logo_extra, array( 'show-all', 'title-only', 'logo-title' ) )  ) {
						if ( next_travel_is_latest_posts() || next_travel_is_frontpage() ) : ?>
							<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name','display' ); ?></a></h1>
						<?php else : ?>
							<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name','display' ); ?></a></p>
						<?php
						endif;
					} 
					if ( in_array( $header_txt_logo_extra, array( 'show-all', 'tagline-only', 'logo-tagline' ) ) ) {
						$description = get_bloginfo( 'description', 'display' );
						if ( $description || is_customize_preview() ) : ?>
							<p class="site-description"><?php echo esc_html( $description ); /* WPCS: xss ok. */ ?></p>
						<?php
						endif; 
					}?>
				</div>
			<?php endif; ?>
		</div><!-- .site-branding -->
		<?php
	}
endif;
add_action( 'next_travel_header_action', 'next_travel_site_branding', 20 );

if ( ! function_exists( 'next_travel_site_navigation' ) ) :
	/**
	 * Site navigation codes
	 *
	 * @since Next Travel 1.0.0
	 *
	 */
	function next_travel_site_navigation() {
		$options = next_travel_get_theme_options();
		?>
		<nav id="site-navigation" class="main-navigation" role="navigation" aria-label="Primary Menu">
			<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
				<?php
				echo next_travel_get_svg( array( 'icon' => 'menu' ) );
				echo next_travel_get_svg( array( 'icon' => 'close' ) );
				?>					
				<span class="menu-label"><?php esc_html_e( 'Menu', 'next-travel' ); ?></span>
			</button>

			<?php  
				$social = '';
				            	
					$social .= '<li class="social-menu"><div class="social-icons">';
					$social .= wp_nav_menu( 
						$social_defaults = array(
            			'theme_location' => 'social',
            			'container' => false,
            			'menu_class' => '',
            			'echo' => false,
            			'fallback_cb' => 'next_travel_menu_fallback_cb',
            			'depth' => 1,
            			'link_before' => '<span class="screen-reader-text">',
						'link_after' => '</span>',
            		)
					 );
					$social .= '</div></li>';

                $search = '';
                
                	$search = '<li class="search-menu">';
					$search .= '<a href="#" class="">';
					$search .= next_travel_get_svg( array( 'icon' => 'search' ) );
					$search .= next_travel_get_svg( array( 'icon' => 'close' ) );
					$search .= '</a><div id="search" style="display: none;">';
					$search .= get_search_form( $echo = false );
	                $search .= '</div></li>';               
        	
                wp_nav_menu( 
                	array(
                		'theme_location' => 'primary',
                		'container' => 'div',
                		'menu_class' => 'menu nav-menu',
                		'menu_id' => 'primary-menu',
                		'echo' => true,
                		'fallback_cb' => 'next_travel_menu_fallback_cb',
                		'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s' . $search . $social . '</ul>',
                		)
                	);
        	?>
		</nav><!-- #site-navigation -->
		</div><!-- .wrapper -->
		<?php
	}
endif;
add_action( 'next_travel_header_action', 'next_travel_site_navigation', 30 );


if ( ! function_exists( 'next_travel_header_end' ) ) :
	/**
	 * Header end html codes
	 *
	 * @since Next Travel 1.0.0
	 *
	 */
	function next_travel_header_end() {
		?>
		</header><!-- #masthead -->
		<?php
	}
endif;

add_action( 'next_travel_header_action', 'next_travel_header_end', 50 );

if ( ! function_exists( 'next_travel_content_start' ) ) :
	/**
	 * Site content codes
	 *
	 * @since Next Travel 1.0.0
	 *
	 */
	function next_travel_content_start() {
		?>
		<div id="content" class="site-content">
		<?php
	}
endif;
add_action( 'next_travel_content_start_action', 'next_travel_content_start', 10 );

if ( ! function_exists( 'next_travel_header_image' ) ) :
	/**
	 * Header Image codes
	 *
	 * @since Next Travel 1.0.0
	 *
	 */
	function next_travel_header_image() {
		if ( next_travel_is_frontpage() )
			return;
		$header_image = get_header_image();
		$class = '';
		if ( is_singular() ) :
			$class = ( has_post_thumbnail() || ! empty( $header_image ) ) ? '' : 'header-media-disabled';
		else :
			$class = ! empty( $header_image ) ? '' : 'header-media-disabled';
		endif;
		
		if ( is_singular() && has_post_thumbnail() ) : 
			$header_image = get_the_post_thumbnail_url( get_the_id(), 'full' );
    	endif; ?>

    	<div id="page-site-header" class="relative <?php echo esc_attr( $class ); ?>" style="background-image: url('<?php echo esc_url( $header_image ); ?>');">
            <div class="overlay"></div>
			<div class="wrapper">
				<header class="page-header">
					<?php next_travel_custom_header_banner_title(); ?>
				</header>
				<?php next_travel_add_breadcrumb(); ?>
			</div><!-- .wrapper -->
        </div><!-- #page-site-header -->

	<?php
	}
endif;
add_action( 'next_travel_header_image_action', 'next_travel_header_image', 10 );

if ( ! function_exists( 'next_travel_add_breadcrumb' ) ) :
	/**
	 * Add breadcrumb.
	 *
	 * @since Next Travel 1.0.0
	 */
	function next_travel_add_breadcrumb() {
		$options = next_travel_get_theme_options();
		// Bail if Breadcrumb disabled.
		$breadcrumb = $options['breadcrumb_enable'];
		if ( false === $breadcrumb ) {
			return;
		}
		
		// Bail if Home Page.
		if ( next_travel_is_frontpage() ) {
			return;
		}

		echo '<div id="breadcrumb-list" >';
				/**
				 * next_travel_simple_breadcrumb hook
				 *
				 * @hooked next_travel_simple_breadcrumb -  10
				 *
				 */
				do_action( 'next_travel_simple_breadcrumb' );
		echo '</div><!-- #breadcrumb-list -->';
		return;
	}
endif;

if ( ! function_exists( 'next_travel_content_end' ) ) :
	/**
	 * Site content codes
	 *
	 * @since Next Travel 1.0.0
	 *
	 */
	function next_travel_content_end() {
		?>
		</div><!-- #content -->
		<?php
	}
endif;
add_action( 'next_travel_content_end_action', 'next_travel_content_end', 10 );

if ( ! function_exists( 'next_travel_footer_start' ) ) :
	/**
	 * Footer starts
	 *
	 * @since Next Travel 1.0.0
	 *
	 */
	function next_travel_footer_start() {
		?>
		<footer id="colophon" class="site-footer" role="contentinfo">
		<?php
	}
endif;
add_action( 'next_travel_footer', 'next_travel_footer_start', 10 );

if ( ! function_exists( 'next_travel_footer_site_info' ) ) :
	/**
	 * Footer starts
	 *
	 * @since Next Travel 1.0.0
	 *
	 */
	function next_travel_footer_site_info() {
		$options = next_travel_get_theme_options();
		$theme_data = wp_get_theme();
		$search = array( '[the-year]', '[site-link]' );

        $replace = array( date( 'Y' ), '<a href="'. esc_url( home_url( '/' ) ) .'">'.  get_bloginfo( 'name', 'display' ). '</a>' );

        $options['copyright_text'] = str_replace( $search, $replace, $options['copyright_text'] );

		$copyright_text = $options['copyright_text']; 

		?>
		<div class="site-info col-2">
			<div class="wrapper">
			<?php if( !empty( $options['footer_logo'] ) ): ?>
					<span><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><img src="<?php echo esc_url( $options['footer_logo'] ); ?>" alt="<?php bloginfo( 'name' ); ?>"></a></span>
					<span>
					<?php endif;
					echo next_travel_santize_allow_tag( $copyright_text ); ?>					
				</span>
				<span>
					<?php echo esc_html__( 'All Rights Reserved | ', 'next-travel' ) . esc_html( $theme_data->get( 'Name') ) . '&nbsp;' . esc_html__( 'by', 'next-travel' ). '&nbsp;<a target="_blank" href="'. esc_url( $theme_data->get( 'AuthorURI' ) ) .'">'. esc_html( ucwords( $theme_data->get( 'Author' ) ) ) .'</a>' ?>
				</span>
			</div><!-- .wrapper -->    
		</div><!-- .site-info -->

		<?php
	}
endif;
add_action( 'next_travel_footer', 'next_travel_footer_site_info', 40 );


if ( ! function_exists( 'next_travel_footer_end' ) ) :
	/**
	 * Footer starts
	 *
	 * @since Next Travel 1.0.0
	 *
	 */
	function next_travel_footer_end() {
		?>
		</footer>
		<div class="popup-overlay"></div>
		<?php
	}
endif;
add_action( 'next_travel_footer', 'next_travel_footer_end', 100 );
