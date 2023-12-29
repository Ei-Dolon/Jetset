<?php
/**
 * Theme Palace basic theme structure hooks
 *
 * This file contains structural hooks.
 *
 * @package Theme Palace
 * @subpackage Travel Life
 * @since Travel Life 1.0.0
 */

$options = travel_life_get_theme_options();


if ( ! function_exists( 'travel_life_doctype' ) ) :
	/**
	 * Doctype Declaration.
	 *
	 * @since Travel Life 1.0.0
	 */
	function travel_life_doctype() {
	?>
		<!DOCTYPE html>
			<html <?php language_attributes(); ?>>
	<?php
	}
endif;

add_action( 'travel_life_doctype', 'travel_life_doctype', 10 );


if ( ! function_exists( 'travel_life_head' ) ) :
	/**
	 * Header Codes
	 *
	 * @since Travel Life 1.0.0
	 *
	 */
	function travel_life_head() {
		?>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<link rel="profile" href="http://gmpg.org/xfn/11">
		<?php if ( is_singular() && pings_open( get_queried_object() ) ) : ?>
			<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
		<?php endif;
	}
endif;
add_action( 'travel_life_before_wp_head', 'travel_life_head', 10 );

if ( ! function_exists( 'travel_life_page_start' ) ) :
	/**
	 * Page starts html codes
	 *
	 * @since Travel Life 1.0.0
	 *
	 */
	function travel_life_page_start() {
		?>
		<div id="page" class="site">
			<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'travel-life' ); ?></a>

		<?php
	}
endif;
add_action( 'travel_life_page_start_action', 'travel_life_page_start', 10 );

if ( ! function_exists( 'travel_life_header_start' ) ) :
	/**
	 * Header start html codes
	 *
	 * @since Travel Life 1.0.0
	 *
	 */
	function travel_life_header_start() {
		?>
		<div class="menu-overlay"></div>
		<header id="masthead" class="site-header" role="banner">
		<?php
	}
endif;
add_action( 'travel_life_header_action', 'travel_life_header_start', 20 );

if ( ! function_exists( 'travel_life_page_end' ) ) :
	/**
	 * Page end html codes
	 *
	 * @since Travel Life 1.0.0
	 *
	 */
	function travel_life_page_end() {
		?>
		</div><!-- #page -->
		<?php
	}
endif;
add_action( 'travel_life_page_end_action', 'travel_life_page_end', 10 );

if ( ! function_exists( 'travel_life_site_branding' ) ) :
	/**
	 * Site branding codes
	 *
	 * @since Travel Life 1.0.0
	 *
	 */
	function travel_life_site_branding() {
		$options  = travel_life_get_theme_options();
		$header_txt_logo_extra = $options['header_txt_logo_extra'];		
		?>
		<div class="site-branding-wrapper">
			<div class="wrapper">
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
								if ( travel_life_is_latest_posts() ) : ?>
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
				<?php if ($options['top_bar_contact_enable']): ?>
					<ul class="contact-info">
					<?php if( !empty( $options['topbar_contact_number'] ) ): ?>					
	                    <li>
	                        <?php echo travel_life_get_svg( array( 'icon' => 'phone' ) ); ?>
	                        <span>
	                        	<?php if( !empty($options['topbar_contact_number'])): ?>
		                            <a href="tel: <?php echo esc_url($options['topbar_contact_number']); ?>"><?php echo esc_html($options['topbar_contact_number']); ?></a>
		                        <?php endif; ?>
		                        <?php if (!empty($options['topbar_opening_time'])) : ?>
		                            <span><?php echo esc_html($options['topbar_opening_time']) ?></span>
		                        <?php endif; ?>
	                        </span>
	                    </li>
	                    <?php endif;

	                    	if( !empty( $options['topbar_address'] ) ):
	                     ?>		             
	                    <li>
	                    	<?php echo travel_life_get_svg( array( 'icon' => 'location' ) ); ?>
	                        <span>
	                        	<?php if (!empty($options['topbar_address'])): ?>
		                            <span class="location"><?php echo esc_html($options['topbar_address']) ?></span>
		                        <?php  endif; ?>
		                        <?php if (!empty($options['topbar_secondary_address'])): ?>
		                            <span><?php echo esc_html($options['topbar_secondary_address']) ?></span>
		                        <?php endif; ?>
	                        </span>
	                    </li>
	                <?php endif; ?>
	                </ul><!-- .contact-info -->
	            <?php endif; ?>
                <?php if( $options['topbar_button_enable']==true && !empty($options['topbar_btn_url']) && !empty($options['topbar_btn_text'])) : ?>
	                <div class="enquiry-button">
	                    <a href="<?php echo esc_url($options['topbar_btn_url']); ?>" class="btn"><?php echo esc_html($options['topbar_btn_text']); ?></a>
	                </div><!-- .enquiry-button -->
	            <?php endif; ?>

			</div>
		</div>

		<?php
	}
endif;
add_action( 'travel_life_header_action', 'travel_life_site_branding', 20 );

if ( ! function_exists( 'travel_life_site_navigation' ) ) :
	/**
	 * Site navigation codes
	 *
	 * @since Travel Life 1.0.0
	 *
	 */
	function travel_life_site_navigation() {
		$options = travel_life_get_theme_options();
		?>
		<nav id="site-navigation" class="main-navigation" role="navigation">
			<div class="wrapper">
				<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
					<?php
					echo travel_life_get_svg( array( 'icon' => 'menu' ) );
					echo travel_life_get_svg( array( 'icon' => 'close' ) );
					?>					
					<span class="menu-label"><?php esc_html_e( 'Menu', 'travel-life' ); ?></span>
				</button>


				<?php 
					$social = '';
					$search_html = '';

					if ( has_nav_menu( 'social' ) ) {

					$search_html = '<li class="search-menu">
						<a href="#">' . 
							travel_life_get_svg( array( 'icon' => 'search' ) ) .
							travel_life_get_svg( array( 'icon' => 'close' ) ) .
						'</a>
						<div id="search">' .
							get_search_form( $echo = false ) .
						'</div><!-- #search --> 
					</li>';

					            	
						$social .= '<li class="social-menu"><div class="social-icons">';
						$social .= wp_nav_menu( array(
	            			'theme_location' => 'social',
	            			'container' => "false",
	            			'menu_class' => '',
	            			'echo' => false,
	            			'fallback_cb' => 'travel_life_menu_fallback_cb',
	            			'depth' => 1,
	            			'link_before' => '<span class="screen-reader-text">',
	            			'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s' . $search_html . '</ul>',
							'link_after' => '</span>',
	            		) );
						$social .= '</div></li>';
	               
					}

	        		$defaults = array(
	        			'theme_location' => 'primary',
	        			'container' => false,
	        			'menu_class' => 'menu nav-menu',
	        			'menu_id' => 'primary-menu',
	        			'echo' => true,
	        			'fallback_cb' => 'travel_life_menu_fallback_cb',
	        			'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s' . $social . '</ul>',
	        		);
	        	
	        		wp_nav_menu( $defaults );
	        	?>
	        </div>
		</nav><!-- #site-navigation -->
		<?php
	}
endif;
add_action( 'travel_life_header_action', 'travel_life_site_navigation', 30 );


if ( ! function_exists( 'travel_life_header_end' ) ) :
	/**
	 * Header end html codes
	 *
	 * @since Travel Life 1.0.0
	 *
	 */
	function travel_life_header_end() {
		?>
		</header><!-- #masthead -->
		<?php
	}
endif;

add_action( 'travel_life_header_action', 'travel_life_header_end', 50 );

if ( ! function_exists( 'travel_life_content_start' ) ) :
	/**
	 * Site content codes
	 *
	 * @since Travel Life 1.0.0
	 *
	 */
	function travel_life_content_start() {
		?>
		<div id="content" class="site-content">
		<?php
	}
endif;
add_action( 'travel_life_content_start_action', 'travel_life_content_start', 10 );

if ( ! function_exists( 'travel_life_header_image' ) ) :
	/**
	 * Header Image codes
	 *
	 * @since Travel Life 1.0.0
	 *
	 */
	function travel_life_header_image() {
		if ( travel_life_is_frontpage() )
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
    		<?php if ( class_exists( 'WP_Travel' ) && is_singular( 'itineraries' ) ) : 
    			$gallery_ids = get_post_meta( get_the_id(), 'wp_travel_itinerary_gallery_ids', true );
    			if ( ! empty( $gallery_ids ) ) :
					$gallery_ids = array_slice( $gallery_ids, 0, 4);
	    			?>
		    		<div class="header-gallery">
		    			<?php foreach ( $gallery_ids as $gallery ) :
		    				$image = wp_get_attachment_image_src( $gallery, 'full' ); ?>
			    			<div class="featured-image" style="background-image: url('<?php echo esc_url( $image[0] );  ?>');"></div>
			    		<?php endforeach; ?>
		    		</div>
		    	<?php endif; 
	    	endif; ?>
            <div class="overlay"></div>
            <div class="header-wrapper">
	            <div class="wrapper">
	                <header class="page-header">
	                    <?php echo travel_life_custom_header_banner_title(); ?>
	                </header>

	                <?php travel_life_add_breadcrumb(); ?>
	            </div><!-- .wrapper -->
            </div><!-- .header-wrapper -->
        </div><!-- #page-site-header -->

	<?php
	}
endif;
add_action( 'travel_life_header_image_action', 'travel_life_header_image', 10 );

if ( ! function_exists( 'travel_life_add_breadcrumb' ) ) :
	/**
	 * Add breadcrumb.
	 *
	 * @since Travel Life 1.0.0
	 */
	function travel_life_add_breadcrumb() {
		$options = travel_life_get_theme_options();
		// Bail if Breadcrumb disabled.
		$breadcrumb = $options['breadcrumb_enable'];
		if ( false === $breadcrumb ) {
			return;
		}
		
		// Bail if Home Page.
		if ( travel_life_is_frontpage() ) {
			return;
		}

		echo '<div id="breadcrumb-list" >';
				/**
				 * travel_life_simple_breadcrumb hook
				 *
				 * @hooked travel_life_simple_breadcrumb -  10
				 *
				 */
				do_action( 'travel_life_simple_breadcrumb' );
		echo '</div><!-- #breadcrumb-list -->';
		return;
	}
endif;

if ( ! function_exists( 'travel_life_content_end' ) ) :
	/**
	 * Site content codes
	 *
	 * @since Travel Life 1.0.0
	 *
	 */
	function travel_life_content_end() {
		?>
		</div><!-- #content -->
		<?php
	}
endif;
add_action( 'travel_life_content_end_action', 'travel_life_content_end', 10 );

if ( ! function_exists( 'travel_life_footer_start' ) ) :
	/**
	 * Footer starts
	 *
	 * @since Travel Life 1.0.0
	 *
	 */
	function travel_life_footer_start() {
		?>
		<footer id="colophon" class="site-footer" role="contentinfo">
		<?php
	}
endif;
add_action( 'travel_life_footer', 'travel_life_footer_start', 10 );

if ( ! function_exists( 'travel_life_footer_site_info' ) ) :
	/**
	 * Footer starts
	 *
	 * @since Travel Life 1.0.0
	 *
	 */
	function travel_life_footer_site_info() {
		$options = travel_life_get_theme_options();
		$search = array( '[the-year]', '[site-link]' );

        $replace = array( date( 'Y' ), '<a href="'. esc_url( home_url( '/' ) ) .'">'. esc_attr( get_bloginfo( 'name', 'display' ) ) . '</a>' );

        $options['copyright_text'] = str_replace( $search, $replace, $options['copyright_text'] );

		$copyright_text = $options['copyright_text']; 
		$theme_data = wp_get_theme();
		?>
		<div class="site-info col-2">
                <div class="wrapper">
                    <span>
                    	<?php 
                    	echo travel_life_santize_allow_tag( $copyright_text ); 
                    	if ( function_exists( 'the_privacy_policy_link' ) ) {
							the_privacy_policy_link( ' | ' );
						}
                    	?>
                	</span>
                    <span><?php echo esc_html__( 'All Rights Reserved | ', 'travel-life' ) . esc_html( $theme_data->get( 'Name') ) . '&nbsp;' . esc_html__( 'by', 'travel-life' ). '&nbsp;<a target="_blank" href="'. esc_url( $theme_data->get( 'AuthorURI' ) ) .'">'. esc_html( ucwords( $theme_data->get( 'Author' ) ) ) .'</a>' ?></span>
                </div><!-- .wrapper -->    
            </div><!-- .site-info -->

		<?php
	}
endif;
add_action( 'travel_life_footer', 'travel_life_footer_site_info', 40 );

if ( ! function_exists( 'travel_life_footer_scroll_to_top' ) ) :
	/**
	 * Footer starts
	 *
	 * @since Travel Life 1.0.0
	 *
	 */
	function travel_life_footer_scroll_to_top() { ?>
			<div class="backtotop"><?php echo travel_life_get_svg( array( 'icon' => 'up' ) ); ?></div>
		<?php 
	}
endif;
add_action( 'travel_life_footer', 'travel_life_footer_scroll_to_top', 40 );

if ( ! function_exists( 'travel_life_footer_end' ) ) :
	/**
	 * Footer starts
	 *
	 * @since Travel Life 1.0.0
	 *
	 */
	function travel_life_footer_end() {
		?>
		</footer>
		<div class="popup-overlay"></div>
		<?php
	}
endif;
add_action( 'travel_life_footer', 'travel_life_footer_end', 100 );

if ( ! function_exists( 'travel_life_loader' ) ) :
	/**
	 * Start div id #loader
	 *
	 * @since Travel Life 1.0.0
	 *
	 */
	function travel_life_loader() {
		$options = travel_life_get_theme_options();
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
	            	echo travel_life_get_svg( array( 'icon' => esc_attr( $options['loader_icon'] ) ) );
	            endif; ?>
            </div>
        </div><!-- #loader -->
		<?php }
	}
endif;
add_action( 'travel_life_before_header', 'travel_life_loader', 10 );

if ( ! function_exists( 'travel_life_infinite_loader_spinner' ) ) :
	/**
	 *
	 * @since Travel Life 1.0.0
	 *
	 */
	function travel_life_infinite_loader_spinner() { 
		global $post;
		$options = travel_life_get_theme_options();
		if ( $options['pagination_type'] == 'infinite' ) :
			if ( count( $post ) > 0 ) {
				echo '<div class="blog-loader">' . travel_life_get_svg( array( 'icon' => 'spinner-umbrella' ) ) . '</div>';
			}
		endif;
	}
endif;
add_action( 'travel_life_infinite_loader_spinner_action', 'travel_life_infinite_loader_spinner', 10 );