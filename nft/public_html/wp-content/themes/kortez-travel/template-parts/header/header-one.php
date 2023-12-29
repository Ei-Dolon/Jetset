<?php
/**
 * Template part for displaying header one
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 * @since Kortez Travel 1.0.0
 */
?>

<header id="masthead" class="site-header header-one">
	<div class="bottom-header header-image-wrap">
		<div class="container">
			<div class="row align-items-center">
				<div class="col-lg-3">
					<?php get_template_part( 'template-parts/site', 'branding' ); ?>
				</div>
				<div class="col-lg-9">
					<div class="main-navigation-wrap">
						<nav id="site-navigation" class="main-navigation d-none d-lg-block">
							<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false"><?php esc_html_e( 'Primary Menu', 'kortez-travel' ); ?></button>
							<?php if ( has_nav_menu( 'menu-1' ) ) :
								wp_nav_menu( 
									array(
										'container'      => '',
										'theme_location' => 'menu-1',
										'menu_id'        => 'primary-menu',
										'menu_class'     => 'menu nav-menu',
									)
								);
							?>
							<?php else :
								wp_page_menu(
									array(
										'menu_class' => 'menu-wrap',
					                    'before'     => '<ul id="primary-menu" class="menu nav-menu">',
					                    'after'      => '</ul>',
									)
								);
							?>
							<?php endif; ?>
						</nav><!-- #site-navigation -->	
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="mobile-menu-container"></div>
</header><!-- #masthead -->