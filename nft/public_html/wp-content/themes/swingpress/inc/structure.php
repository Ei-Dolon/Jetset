<?php
/**
 * Theme Palace basic theme structure hooks
 *
 * This file contains structural hooks.
 *
 * @package Theme Palace
 * @subpackage Swingpress
 * @since Swingpress 1.0.0
 */

$options = swingpress_get_theme_options();


if ( ! function_exists( 'swingpress_doctype' ) ) :
	/**
	 * Doctype Declaration.
	 *
	 * @since Swingpress 1.0.0
	 */
	function swingpress_doctype() {
	?>
		<!DOCTYPE html>
			<html <?php language_attributes(); ?>>
	<?php
	}
endif;

add_action( 'swingpress_doctype', 'swingpress_doctype', 10 );


if ( ! function_exists( 'swingpress_head' ) ) :
	/**
	 * Header Codes
	 *
	 * @since Swingpress 1.0.0
	 *
	 */
	function swingpress_head() {
		?>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<link rel="profile" href="https://gmpg.org/xfn/11">
		<?php if ( is_singular() && pings_open( get_queried_object() ) ) : ?>
			<link rel="pingback" href="<?php echo esc_url( get_bloginfo( 'pingback_url' ) ); ?>">
		<?php endif;
	}
endif;
add_action( 'swingpress_before_wp_head', 'swingpress_head', 10 );

if ( ! function_exists( 'swingpress_page_start' ) ) :
	/**
	 * Page starts html codes
	 *
	 * @since Swingpress 1.0.0
	 *
	 */
	function swingpress_page_start() {
		?>
		<div id="page" class="site">
			<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'swingpress' ); ?></a>

		<?php
	}
endif;
add_action( 'swingpress_page_start_action', 'swingpress_page_start', 10 );

if ( ! function_exists( 'swingpress_header_start' ) ) :
	/**
	 * Header start html codes
	 *
	 * @since Swingpress 1.0.0
	 *
	 */
	function swingpress_header_start() {
		?>
		<div class="menu-overlay"></div>
		<header id="masthead" class="site-header" role="banner">
		<?php
	}
endif;
add_action( 'swingpress_header_action', 'swingpress_header_start', 20 );

if ( ! function_exists( 'swingpress_page_end' ) ) :
	/**
	 * Page end html codes
	 *
	 * @since Swingpress 1.0.0
	 *
	 */
	function swingpress_page_end() {
		?>
		</div><!-- #page -->
		<?php
	}
endif;
add_action( 'swingpress_page_end_action', 'swingpress_page_end', 10 );

if ( ! function_exists( 'swingpress_site_branding' ) ) :
	/**
	 * Site branding codes
	 *
	 * @since Swingpress 1.0.0
	 *
	 */
	function swingpress_site_branding() {
		$options  = swingpress_get_theme_options();
		$header_txt_logo_extra = $options['header_txt_logo_extra'];		
		?>
		<div class="wrapper">
		<?php if ( $options['home_layout'] !== 'fourth-design' ): ?>
		<div class="site-branding">
			<?php if ( in_array( $header_txt_logo_extra, array( 'show-all', 'logo-title', 'logo-tagline' ) )  ) { ?>
				<div class="site-logo">
					<?php the_custom_logo(); ?>
				</div>
			<?php } 
			if ( in_array( $header_txt_logo_extra, array( 'show-all', 'title-only', 'logo-title', 'show-all', 'tagline-only', 'logo-tagline' ) ) ) : ?>
				<div id="site-identity">
					<?php
					if( in_array( $header_txt_logo_extra, array( 'show-all', 'title-only', 'logo-title' ) )  ) {
						if ( swingpress_is_latest_posts() || swingpress_is_frontpage() ) : ?>
							<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
						<?php else : ?>
							<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
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
	<?php endif; ?>

	<?php if ( $options['home_layout'] == 'fourth-design' ): ?>
		<div class="site-branding">
			<?php if ( in_array( $header_txt_logo_extra, array( 'show-all', 'logo-title', 'logo-tagline' ) )  ) { ?>
				<div class="site-logo">
					<?php the_custom_logo(); ?>
				</div>
			<?php } 
			if ( in_array( $header_txt_logo_extra, array( 'show-all', 'title-only', 'logo-title', 'show-all', 'tagline-only', 'logo-tagline' ) ) ) : ?>
				<div id="site-identity">
					<?php
					if( in_array( $header_txt_logo_extra, array( 'show-all', 'title-only', 'logo-title' ) )  ) {
						if ( swingpress_is_latest_posts() ) : ?>
							<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
						<?php else : ?>
							<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
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
			
			<?php if( !empty( $options['header_advertisement_image'] ) && !empty( $options['header_advertisement_link'] ) ): ?>
				<div class="site-advertisement">
					<a href="<?php echo esc_url( $options['header_advertisement_link'] ); ?>"><img src="<?php echo esc_url( $options['header_advertisement_image'] ); ?>"></a>
				</div><!-- .site-advertisement -->

			<?php endif; ?>
		</div><!-- .site-branding -->

	<?php endif; ?>

		<?php
	}
endif;
add_action( 'swingpress_header_action', 'swingpress_site_branding', 20 );

if ( ! function_exists( 'swingpress_site_navigation' ) ) :
	/**
	 * Site navigation codes
	 *
	 * @since Swingpress 1.0.0
	 *
	 */
	function swingpress_site_navigation() {
		$options = swingpress_get_theme_options();
		?>
		<nav id="site-navigation" class="main-navigation" role="navigation">
			<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
				<?php
				echo swingpress_get_svg( array( 'icon' => 'menu' ) );
				echo swingpress_get_svg( array( 'icon' => 'close' ) );
				?>					
			</button>

			<?php  
				$social = '';
				if ( $options['social_nav_enable'] ) :            	
					$social .= '<li class="social-menu"><div class="social-icons">';
					$social .= wp_nav_menu( array(
            			'theme_location' => 'social',
            			'container' => false,
            			'menu_class' => '',
            			'echo' => false,
            			'fallback_cb' => 'swingpress_menu_fallback_cb',
            			'depth' => 1,
            			'link_before' => '<span class="screen-reader-text">',
						'link_after' => '</span>',
            		) );
					$social .= '</div></li>';
                endif;

         

                 $search = '';
				if ( $options['search_nav_enable'] ) :            	
					$search .= sprintf(
					'<li class="search-menu"><a href="#" title="%1$s">%2$s%3$s</a><div id="search">%4$s</div>',
					esc_attr__('search','swingpress'),
					swingpress_get_svg( array( 'icon' => 'search' ) ), 
					swingpress_get_svg( array( 'icon' => 'close' ) ), 
					get_search_form( $echo = false )
				);
                endif;

        		$defaults = array(
        			'theme_location' => 'primary',
        			'container' => 'div',
        			'menu_class' => 'menu nav-menu',
        			'menu_id' => 'primary-menu',
        			'echo' => true,
        			'fallback_cb' => 'swingpress_menu_fallback_cb',
        			'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s' . $search  . $social . '</ul>',
        		);
        	
        		wp_nav_menu( $defaults );
        	?>
		</nav><!-- #site-navigation -->
		</div><!-- .wrapper -->
		<?php
	}
endif;
add_action( 'swingpress_header_action', 'swingpress_site_navigation', 30 );


if ( ! function_exists( 'swingpress_header_end' ) ) :
	/**
	 * Header end html codes
	 *
	 * @since Swingpress 1.0.0
	 *
	 */
	function swingpress_header_end() {
		?>
		</header><!-- #masthead -->
		<?php
	}
endif;

add_action( 'swingpress_header_action', 'swingpress_header_end', 50 );

if ( ! function_exists( 'swingpress_content_start' ) ) :
	/**
	 * Site content codes
	 *
	 * @since Swingpress 1.0.0
	 *
	 */
	function swingpress_content_start() {
		?>
		<div id="content" class="site-content">
		<?php
	}
endif;
add_action( 'swingpress_content_start_action', 'swingpress_content_start', 10 );

if ( ! function_exists( 'swingpress_header_image' ) ) :
	/**
	 * Header Image codes
	 *
	 * @since Swingpress 1.0.0
	 *
	 */
	function swingpress_header_image() {
		if ( swingpress_is_frontpage() )
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
            <div class="header-wrapper">
	            <div class="wrapper">
	                <header class="page-header">
	                    <?php swingpress_custom_header_banner_title(); ?>
	                </header>

	                <?php swingpress_add_breadcrumb(); ?>
	            </div><!-- .wrapper -->
            </div><!-- .header-wrapper -->
        </div><!-- #page-site-header -->

	<?php
	}
endif;
add_action( 'swingpress_header_image_action', 'swingpress_header_image', 10 );

if ( ! function_exists( 'swingpress_add_breadcrumb' ) ) :
	/**
	 * Add breadcrumb.
	 *
	 * @since Swingpress 1.0.0
	 */
	function swingpress_add_breadcrumb() {
		$options = swingpress_get_theme_options();
		// Bail if Breadcrumb disabled.
		$breadcrumb = $options['breadcrumb_enable'];
		if ( false === $breadcrumb ) {
			return;
		}
		
		// Bail if Home Page.
		if ( swingpress_is_frontpage() ) {
			return;
		}

		echo '<div id="breadcrumb-list" >';
				/**
				 * swingpress_simple_breadcrumb hook
				 *
				 * @hooked swingpress_simple_breadcrumb -  10
				 *
				 */
				do_action( 'swingpress_simple_breadcrumb' );
		echo '</div><!-- #breadcrumb-list -->';
		return;
	}
endif;

if ( ! function_exists( 'swingpress_content_end' ) ) :
	/**
	 * Site content codes
	 *
	 * @since Swingpress 1.0.0
	 *
	 */
	function swingpress_content_end() {
		?>
		</div><!-- #content -->
		<?php
	}
endif;
add_action( 'swingpress_content_end_action', 'swingpress_content_end', 10 );

if ( ! function_exists( 'swingpress_footer_start' ) ) :
	/**
	 * Footer starts
	 *
	 * @since Swingpress 1.0.0
	 *
	 */
	function swingpress_footer_start() {
		?>
		<footer id="colophon" class="site-footer" role="contentinfo">
		<?php
	}
endif;
add_action( 'swingpress_footer', 'swingpress_footer_start', 10 );

if ( ! function_exists( 'swingpress_footer_site_info' ) ) :
	/**
	 * Footer starts
	 *
	 * @since Swingpress 1.0.0
	 *
	 */
	function swingpress_footer_site_info() {
		$options = swingpress_get_theme_options();
		$search = array( '[the-year]', '[site-link]' );

        $replace = array( date( 'Y' ), '<a href="'. esc_url( home_url( '/' ) ) .'">'. esc_attr( get_bloginfo( 'name', 'display' ) ) . '</a>' );

        $options['copyright_text'] = str_replace( $search, $replace, $options['copyright_text'] );
        $theme_data = wp_get_theme();
		$copyright_text = $options['copyright_text']; 
		?>
		<div class="site-info col-2">
                <div class="wrapper">
                    <span>
                    	<?php 
                    	echo swingpress_santize_allow_tag( $copyright_text ); 
                    	?>
                    	
						<?php echo esc_html__( ' | All Rights Reserved | ', 'swingpress' ) . esc_html( $theme_data->get( 'Name') ) . '&nbsp;' . esc_html__( 'by', 'swingpress' ). '&nbsp;<a target="_blank" href="'. esc_url( $theme_data->get( 'AuthorURI' ) ) .'">'. esc_html( ucwords( $theme_data->get( 'Author' ) ) ) .'</a>' ?>
                	</span>
                    <span>
                    	<?php  
                    		wp_nav_menu(  array(
								'theme_location' => 'social',
								'container' => 'div',
								'container_class' => 'social-icons',
								'echo' => true,
								'depth' => 1,
								'link_before' => '<span class="screen-reader-text">',
								'link_after' => '</span>',
								'fallback_cb' => false,
							) );
                    	?>
                    </span>
                </div><!-- .wrapper -->    
            </div><!-- .site-info -->

		<?php
	}
endif;
add_action( 'swingpress_footer', 'swingpress_footer_site_info', 40 );

if ( ! function_exists( 'swingpress_footer_scroll_to_top' ) ) :
	/**
	 * Footer starts
	 *
	 * @since Swingpress 1.0.0
	 *
	 */
	function swingpress_footer_scroll_to_top() {
		$options  = swingpress_get_theme_options();
		if ( true === $options['scroll_top_visible'] ) : ?>
			<div class="backtotop"><?php echo swingpress_get_svg( array( 'icon' => 'up' ) ); ?></div>
		<?php endif;
	}
endif;
add_action( 'swingpress_footer', 'swingpress_footer_scroll_to_top', 40 );

if ( ! function_exists( 'swingpress_footer_end' ) ) :
	/**
	 * Footer starts
	 *
	 * @since Swingpress 1.0.0
	 *
	 */
	function swingpress_footer_end() {
		?>
		</footer>
		<div class="popup-overlay"></div>
		<?php
	}
endif;
add_action( 'swingpress_footer', 'swingpress_footer_end', 100 );

if ( ! function_exists( 'swingpress_loader' ) ) :
	/**
	 * Start div id #loader
	 *
	 * @since Swingpress 1.0.0
	 *
	 */
	function swingpress_loader() {
		$options = swingpress_get_theme_options();
		if ( $options['loader_enable'] ) { ?>

			<div id="loader">
            <div class="loader-container">
            	<?php if ( 'default' == $options['loader_icon'] ) : ?>
	                <div id="preloader">
	                    <span></span>
	                    <span></span>
	                    <span></span>
	                    <span></span>
	                    <span></span>
	                </div>
	            <?php else :
	            	echo swingpress_get_svg( array( 'icon' => esc_attr( $options['loader_icon'] ) ) );
	            endif; ?>
            </div>
        </div><!-- #loader -->
		<?php }
	}
endif;
add_action( 'swingpress_before_header', 'swingpress_loader', 10 );

if ( ! function_exists( 'swingpress_infinite_loader_spinner' ) ) :
	/**
	 *
	 * @since Swingpress 1.0.0
	 *
	 */
	function swingpress_infinite_loader_spinner() { 
		global $post;
		$options = swingpress_get_theme_options();
		if ( $options['pagination_type'] == 'infinite' ) :
			if ( count( $post ) > 0 ) {
				echo '<div class="blog-loader">' . swingpress_get_svg( array( 'icon' => 'spinner-umbrella' ) ) . '</div>';
			}
		endif;
	}
endif;
add_action( 'swingpress_infinite_loader_spinner_action', 'swingpress_infinite_loader_spinner', 10 );
