<?php
/**
 * Template part for displaying latest posts section
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 * @since Kortez Travel 1.0.0
 */
$latest_posts_category = get_theme_mod( 'latest_posts_category', '' );
$archive_post_per_page = get_theme_mod( 'archive_post_per_page', 10 );
$query = new WP_Query( apply_filters( 'kortez_travel_blog_args', array(
	'post_type'           => 'post',
	'post_status'         => 'publish',
	'cat'                 => $latest_posts_category,
	'paged'          	  => get_query_var( 'paged', 1 ), 
	'posts_per_page'      => $archive_post_per_page,
)));
$posts_array = $query->get_posts();
$show_latest_posts = count( $posts_array ) > 0;
if( !get_theme_mod( 'disable_latest_posts_section', false ) && $show_latest_posts ){
	$latest_title_desc_align = get_theme_mod( 'latest_posts_section_title_desc_alignment', 'text-left' );
	?>
	<section class="section-post-area">
		<div class="row">
			<?php
				$sidebarClass = 'col-lg-8';
				$sidebarColumnClass = 'col-lg-4';
				$masonry_class = '';

				if( get_theme_mod( 'archive_post_layout', 'list' ) == 'grid'){
					$masonry_class = 'masonry-wrapper';
				}
				if( get_theme_mod( 'archive_post_layout', 'list' ) == 'grid' ){
					$layout_class = 'grid-post-wrap';
				}elseif( get_theme_mod( 'archive_post_layout', 'list' ) == 'single' ){
					$layout_class = 'single-post';
				}
				if ( get_theme_mod( 'sidebar_settings', 'right' ) == 'right' ){
					if( get_theme_mod( 'archive_post_layout', 'list' ) == 'grid'){
						if( !is_active_sidebar( 'right-sidebar') ){
							$sidebarClass = "col-12";
						}	
					}else{
						if( !is_active_sidebar( 'right-sidebar') ){
							$sidebarClass = "col-lg-8 offset-lg-2";
						}
					}
				}elseif ( get_theme_mod( 'sidebar_settings', 'right' ) == 'left' ){
					if( get_theme_mod( 'archive_post_layout', 'list' ) == 'grid'){
						if( !is_active_sidebar( 'left-sidebar') ){
							$sidebarClass = "col-12";
						}	
					}else{
						if( !is_active_sidebar( 'left-sidebar') ){
							$sidebarClass = "col-lg-8 offset-lg-2";
						}
					}
				}elseif ( get_theme_mod( 'sidebar_settings', 'right' ) == 'right-left' ){
					$sidebarClass = 'col-lg-6';
					$sidebarColumnClass = 'col-lg-3';
					if( get_theme_mod( 'archive_post_layout', 'list' ) == 'grid'){
						if( !is_active_sidebar( 'left-sidebar') && !is_active_sidebar( 'right-sidebar') ){
							$sidebarClass = "col-12";
						}	
					}else{
						if(!is_active_sidebar( 'left-sidebar') && !is_active_sidebar( 'right-sidebar') ){
							$sidebarClass = "col-lg-8 offset-lg-2";
						}
					}
				}
				if ( get_theme_mod( 'sidebar_settings', 'right' ) == 'no-sidebar' || get_theme_mod( 'disable_sidebar_blog_page', false ) ){
					if( get_theme_mod( 'archive_post_layout', 'list' ) == 'grid'){
						$sidebarClass = "col-12";	
					}else{
						$sidebarClass = 'col-lg-8 offset-lg-2';
					}
				}
				if( !get_theme_mod( 'disable_sidebar_blog_page', false ) ){
					if ( get_theme_mod( 'sidebar_settings', 'right' ) == 'left' ){ 
						if( is_active_sidebar( 'left-sidebar') ){ ?>
							<div id="secondary" class="sidebar left-sidebar <?php echo esc_attr( $sidebarColumnClass ); ?>">
								<?php dynamic_sidebar( 'left-sidebar' ); ?>
							</div>
					<?php }
					}elseif ( get_theme_mod( 'sidebar_settings', 'right' ) == 'right-left' ){
						if( is_active_sidebar( 'left-sidebar') || is_active_sidebar( 'right-sidebar') ){ ?>
							<div id="secondary" class="sidebar left-sidebar <?php echo esc_attr( $sidebarColumnClass ); ?>">
								<?php dynamic_sidebar( 'left-sidebar' ); ?>
							</div>
						<?php
						}
					}
				} ?>
			
			<div id="primary" class="content-area <?php echo esc_attr( $sidebarClass ); ?>">
				<?php if( ( !get_theme_mod( 'disable_latest_posts_section_title', true ) && get_theme_mod( 'latest_posts_section_title', '' ) ) || ( !get_theme_mod( 'disable_latest_posts_section_description', true ) && get_theme_mod( 'latest_posts_section_description', '' ) ) ){ ?>
					<div class="section-title-wrap <?php echo esc_attr( $latest_title_desc_align ); ?>">
						<?php if( !get_theme_mod( 'disable_latest_posts_section_title', true ) && get_theme_mod( 'latest_posts_section_title', '' ) ){ ?>
							<h2 class="section-title"><?php echo esc_html( get_theme_mod( 'latest_posts_section_title', '' ) ); ?></h2>
						<?php } 
						if( !get_theme_mod( 'disable_latest_posts_section_description', true ) && get_theme_mod( 'latest_posts_section_description', '' ) ){ ?>
							<p><?php echo esc_html( get_theme_mod( 'latest_posts_section_description', '' ) ); ?></p>
						<?php } ?>
					</div>
				<?php } ?>
				<div class="row <?php echo esc_attr( $masonry_class ); ?>">
				<?php
				if ( $query->have_posts() ) :

					if ( is_home() && !is_front_page() ) :
						?>
						<header>
							<h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
						</header>
						<?php
					endif;

					/* Start the Loop */
					while ( $query->have_posts() ) :
						$query->the_post();

						/*
						 * Include the Post-Type-specific template for the content.
						 * If you want to override this in a child theme, then include a file
						 * called content-___.php (where ___ is the Post Type name) and that will be used instead.
						 */
						get_template_part( 'template-parts/content', get_post_type() );

					endwhile;

				elseif ( !is_sticky() && ! $query->have_posts() ):
					get_template_part( 'template-parts/content', 'none' );
				endif;
				?>
				</div><!-- #main -->
				<?php
					if( !get_theme_mod( 'disable_pagination', false ) ):
						the_posts_pagination( array(
							'total'        => $query->max_num_pages,
							'next_text' => '<span>'.esc_html__( 'Next', 'kortez-travel' ) .'</span><span class="screen-reader-text">' . esc_html__( 'Next page', 'kortez-travel' ) . '</span>',
							'prev_text' => '<span>'.esc_html__( 'Prev', 'kortez-travel' ) .'</span><span class="screen-reader-text">' . esc_html__( 'Previous page', 'kortez-travel' ) . '</span>',
							'before_page_number' => '<span class="meta-nav screen-reader-text">' . esc_html__( 'Page', 'kortez-travel' ) . ' </span>',
						));
					endif;
					wp_reset_postdata();
				?>
			</div><!-- #primary -->
			<?php
				if( !get_theme_mod( 'disable_sidebar_blog_page', false ) ){
					if ( get_theme_mod( 'sidebar_settings', 'right' ) == 'right' ){ 
						if( is_active_sidebar( 'right-sidebar') ){ ?>
							<div id="secondary" class="sidebar right-sidebar <?php echo esc_attr( $sidebarColumnClass ); ?>">
								<?php dynamic_sidebar( 'right-sidebar' ); ?>
							</div>
					<?php }
					}elseif ( get_theme_mod( 'sidebar_settings', 'right' ) == 'right-left' ){
						if( is_active_sidebar( 'left-sidebar') || is_active_sidebar( 'right-sidebar') ){ ?>
							<div id="secondary-sidebar" class="sidebar right-sidebar <?php echo esc_attr( $sidebarColumnClass ); ?>">
								<?php dynamic_sidebar( 'right-sidebar' ); ?>
							</div>
						<?php
						}
					}
				}
			?>
		</div>
	</section>
<?php } ?>