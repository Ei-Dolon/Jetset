<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Kortez Travel
 */
?>

<?php
	$stickyClass = "col-12";
	$layout_class = '';
	if( get_theme_mod( 'sidebar_settings', 'right' ) == 'right' ) {
		if ( get_theme_mod( 'archive_post_layout', 'list' ) == 'grid' ){
			$stickyClass = "col-sm-6 grid-post";
			if( !is_active_sidebar( 'right-sidebar') ){
				$stickyClass = "col-sm-6 col-lg-4 grid-post";
			}
		}
	}elseif( get_theme_mod( 'sidebar_settings', 'right' ) == 'left' ) {
		if ( get_theme_mod( 'archive_post_layout', 'list' ) == 'grid' ){
			$stickyClass = "col-sm-6 grid-post";
			if( !is_active_sidebar( 'left-sidebar') ){
				$stickyClass = "col-sm-6 col-lg-4 grid-post";
			}
		}
	}elseif( get_theme_mod( 'sidebar_settings', 'right' ) == 'no-sidebar' ) {
		if ( get_theme_mod( 'archive_post_layout', 'list' ) == 'grid' ){
			$stickyClass = "col-sm-6 col-lg-4 grid-post";
		}
	}elseif( get_theme_mod( 'sidebar_settings', 'right' ) == 'right-left' ) {
		if ( get_theme_mod( 'archive_post_layout', 'list' ) == 'grid' ){
			$stickyClass = "col-sm-6 col-lg-6 grid-post";
			if( !is_active_sidebar( 'left-sidebar') && !is_active_sidebar( 'right-sidebar') ){
				$stickyClass = "col-sm-6 col-lg-4 grid-post";
			}
		}
	}
	if( get_theme_mod( 'disable_sidebar_blog_page', false ) && get_theme_mod( 'archive_post_layout', 'list' ) == 'grid' ){
		$stickyClass = "col-sm-6 col-lg-4 grid-post";
	}

	if( !is_sticky() && get_theme_mod( 'archive_post_layout', 'list' ) == 'list' ){
		$layout_class = 'list-post';
	}elseif( !is_sticky() && get_theme_mod( 'archive_post_layout', 'list' ) == 'single' ){
		$layout_class = 'single-post';
	}elseif( is_archive() && is_sticky() && get_theme_mod( 'archive_post_layout', 'list' ) == 'list' ){
		$layout_class = 'list-post';
	}elseif( is_archive() && is_sticky() && get_theme_mod( 'archive_post_layout', 'list' ) == 'single' ){
		$layout_class = 'single-post';
	}

	$class = '';
	if(!has_post_thumbnail()){
		$class = 'no-thumbnail';
	}

?>
<div class="<?php echo esc_attr( $stickyClass );?>">
	<article id="post-<?php the_ID(); ?>" <?php post_class( $class . ' ' . $layout_class ) ?> >
		<?php 
		
		if ( has_post_thumbnail() ) : ?>
	        <figure class="featured-image">
	            <a href="<?php the_permalink(); ?>">
	                <?php
	                $grid_list_size = 'kortez-travel-420-300';
	                $single_size 	= 'kortez-travel-1370-550';
	                $render_post_image_size = get_theme_mod( 'render_post_image_size', '' );
	                if ( !empty( $render_post_image_size ) ){
	                	$grid_list_size = $render_post_image_size;
	                	$single_size 	= $render_post_image_size;
	                }
	                if( get_theme_mod( 'sidebar_settings', 'right' ) == 'right' ) {
	                	if ( get_theme_mod( 'archive_post_layout', 'list' ) == 'grid' || get_theme_mod( 'archive_post_layout', 'list' ) == 'list' ){
	                		kortez_travel_image_size( $grid_list_size );
	                	}elseif( get_theme_mod( 'archive_post_layout', 'list' ) == 'single' ){
	                		kortez_travel_image_size( $single_size );
	                	}
	                }elseif( get_theme_mod( 'sidebar_settings', 'right' ) == 'left' ) {
	                	if ( get_theme_mod( 'archive_post_layout', 'list' ) == 'grid' || get_theme_mod( 'archive_post_layout', 'list' ) == 'list' ){
	                		kortez_travel_image_size( $grid_list_size );
	                	}elseif( get_theme_mod( 'archive_post_layout', 'list' ) == 'single' ){
	                		kortez_travel_image_size( $single_size );
	                	}
	                }elseif( get_theme_mod( 'sidebar_settings', 'right' ) == 'no-sidebar' ) {
	                	if ( get_theme_mod( 'archive_post_layout', 'list' ) == 'grid' || get_theme_mod( 'archive_post_layout', 'list' ) == 'list' ){
	                		kortez_travel_image_size( $grid_list_size );
	                	}elseif( get_theme_mod( 'archive_post_layout', 'list' ) == 'single' ){
	                		kortez_travel_image_size( $single_size );
	                	}
	                }elseif( get_theme_mod( 'sidebar_settings', 'right' ) == 'right-left' ) {
	                	if ( get_theme_mod( 'archive_post_layout', 'list' ) == 'grid' || get_theme_mod( 'archive_post_layout', 'list' ) == 'list' ){
	                		kortez_travel_image_size( $grid_list_size );
	                	}elseif( get_theme_mod( 'archive_post_layout', 'list' ) == 'single' ){
	                		kortez_travel_image_size( $single_size );
	                	}
	                }
	                ?>
	            </a>
	        </figure><!-- .recent-image -->
		<?php
	    endif;
		?>
	    <div class="entry-content">
	    	<header class="entry-header">
				<?php 
					if( !get_theme_mod( 'hide_category', false ) ){
						kortez_travel_entry_header();
					}
					if( !get_theme_mod( 'hide_post_title', false ) ){
						the_title( '<h3 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h3>' );
					}
				?>

			</header><!-- .entry-header -->
			<div class="entry-meta">
	           <?php kortez_travel_entry_footer(); ?>
	        </div><!-- .entry-meta -->
			
			<?php if ( !get_theme_mod( 'hide_blog_page_excerpt', false ) ){ ?>
		        <div class="entry-text">
					<?php
						if ( !get_theme_mod( 'hide_blog_page_excerpt', false ) ){
							$excerpt_length = get_theme_mod( 'post_excerpt_length', 15 );
							kortez_travel_excerpt( $excerpt_length , true );
						}
					?>	
				</div>
			<?php } ?>
		</div><!-- .entry-content -->
	</article><!-- #post-->
</div>