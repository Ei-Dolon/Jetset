<?php
/**
 * Template part for displaying site branding.
 *
 * @since Kortez Travel 1.0.0
 */

?>

<div class="site-branding">
	<?php
		$the_custom_logo_url = kortez_travel_get_custom_logo_url();
		if ( $the_custom_logo_url !== '' ) { ?>
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
				<img src="<?php echo esc_url(  $the_custom_logo_url ); ?>" id="headerLogo">
			</a>
		<?php
		}
		if( !get_theme_mod( 'disable_site_title', false ) ){
			if ( is_front_page() && is_home() ) :
				?>
				<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
				<?php
			else :
				?>
				<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
				<?php
			endif;
		}
		$kortez_travel_description = get_bloginfo( 'description', 'display' );
		if( !get_theme_mod( 'disable_site_tagline', false ) ){
			if ( $kortez_travel_description || is_customize_preview() ) :
				?>
				<p class="site-description"><?php echo esc_html($kortez_travel_description); ?></p>
			<?php endif;
		}
	?>
</div><!-- .site-branding -->