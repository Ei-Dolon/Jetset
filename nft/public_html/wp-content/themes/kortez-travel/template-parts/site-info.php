<?php
/**
 * Template part for displaying site info
 *
 * @package Kortez Travel
 */

?>

<div class="site-info">
	<?php echo wp_kses_post( html_entity_decode( esc_html__( 'Copyright &copy; ' , 'kortez-travel' ) ) );
		echo esc_html( date( 'Y' ) . ' ' . get_bloginfo( 'name' ) );
		printf( esc_html__( '. Powered by', 'kortez-travel' ) );
	?>
	<a href="<?php echo esc_url( __( 'https://kortezthemes.com/', 'kortez-travel' ) ); ?>" target="_blank">
		<?php
			printf( esc_html__( 'Kortez Themes', 'kortez-travel' ) );
		?>
	</a>
</div><!-- .site-info -->