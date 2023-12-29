<?php
/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Theme Palace
 * @subpackage Next Travel
 * @since Next Travel 1.0.0
 */
$class = has_post_thumbnail() ? 'has-post-thumbnail' : 'no-post-thumbnail';
$options = next_travel_get_theme_options();
?>

<article class="<?php post_class( $class ); ?>">

   <?php if ( has_post_thumbnail() ) : ?>
    <div class="featured-image" style="background-image: url('<?php the_post_thumbnail_url( 'full' ); ?>');">
        <a href="<?php the_permalink(); ?>" class="post-thumbnail-link"></a>
    </div><!-- .featured-image -->
<?php endif; ?>

<div class="entry-container">
    <header class="entry-header">
        <h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
    </header>

    <div class="entry-meta">
        <?php echo next_travel_article_header_meta(); 
        next_travel_posted_on();
        ?>

    </div><!-- .entry-meta -->

    <div class="entry-content">
        <p><?php the_excerpt(); ?></p>
    </div><!-- .entry-content -->

        <div class="read-more">
            <a href="<?php the_permalink(); ?>" class="btn"><?php echo esc_html( 'Read More', 'next-travel' ); ?></a>
        </div><!-- .read-more -->
</div><!-- .entry-container -->
</article>