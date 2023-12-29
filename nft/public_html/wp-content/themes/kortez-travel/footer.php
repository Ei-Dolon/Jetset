<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Kortez Travel
 */

?>
	<footer id="colophon" class="site-footer">
		<div class="site-footer-inner">
			<?php if( kortez_travel_is_active_footer_sidebar() ): ?>
				<div class="top-footer">
					<div class="wrap-footer-sidebar">
						<div class="container">
							<div class="footer-widget-wrap">
								<div class="row">
									<?php if( get_theme_mod( 'footer_widget_layout', 'footer_widget_layout_one' ) == '' || get_theme_mod( 'footer_widget_layout', 'footer_widget_layout_one' ) == 'footer_widget_layout_one' ){
										get_template_part( 'template-parts/footer/footer-widget', 'one' );
									}elseif( get_theme_mod( 'footer_widget_layout', 'footer_widget_layout_one' ) == 'footer_widget_layout_two' ){
										get_template_part( 'template-parts/footer/footer-widget', 'two' );
									}elseif( get_theme_mod( 'footer_widget_layout', 'footer_widget_layout_one' ) == 'footer_widget_layout_three' ){
										get_template_part( 'template-parts/footer/footer-widget', 'three' );
									}elseif( get_theme_mod( 'footer_widget_layout', 'footer_widget_layout_one' ) == 'footer_widget_layout_four' ){
										get_template_part( 'template-parts/footer/footer-widget', 'four' );
									} ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			<?php endif; ?>

			<div class="bottom-footer">
				<div class="container">
					<?php get_template_part( 'template-parts/site', 'info' ); ?>
				</div> 
			</div>
		</div>
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
