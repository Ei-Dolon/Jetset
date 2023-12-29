<?php 
/**
 * Itinerary Archive Template
 *
 * This template can be overridden by copying it to yourtheme/wp-travel/archive-itineraries.php.
 *
 * HOWEVER, on occasion wp-travel will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see 	    http://docs.wensolutions.com/document/template-structure/
 * @author      WenSolutions
 * @package     wp-travel/Templates
 * @since       1.0.0
 */

get_header( 'itinerary' ); ?>
<div id="inner-content-wrapper" class="wrapper page-section <?php echo is_active_sidebar( 'wp-travel-archive-sidebar' ) ? 'secondary-active' : 'secondary-inactive'; ?>">
	<main id="main" class="site-main" role="main">
        <?php do_action( 'wp_travel_before_main_content' ); ?>
		<?php if ( have_posts() ) : ?>
			<?php while ( have_posts() ) : the_post(); ?>
				<?php wptravel_get_template_part( 'content', 'archive-itineraries' ); ?>
			<?php endwhile; // end of the loop. ?>
		<?php else : ?>
			<?php wptravel_get_template_part( 'content', 'archive-itineraries-none' ); ?>
		<?php endif; ?>
		<?php do_action( 'travel_life_action_pagination' );  ?>
		</div>
	<?php do_action( 'wp_travel_archive_listing_sidebar' ); ?>
	</main>	
</div>
<?php get_footer( 'itinerary' ); ?>
