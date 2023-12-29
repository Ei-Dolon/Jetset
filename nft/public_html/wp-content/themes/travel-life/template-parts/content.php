<?php
/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Theme Palace
 * @subpackage Travel Life
 * @since Travel Life 1.0.0
 */
$image = has_post_thumbnail() ? get_the_post_thumbnail_url( get_the_ID() ) : get_template_directory_uri().'/assets/uploads/no-featured-image-590x650.jpg';
$options = travel_life_get_theme_options();
?>
<article id="post-<?php the_ID(); ?>" class="has-post-thumbnail">
    <div class="featured-image" style="background-image: url('<?php echo esc_url( $image ); ?>');">
        <a href="<?php the_permalink(); ?>" class="post-thumbnail-link"></a>
    </div><!-- .featured-image -->

    <div class="entry-container">
        <header class="entry-header">
            <h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
        </header>

        <div class="entry-meta">
            <?php echo travel_life_article_header_meta(); ?>

            <?php travel_life_posted_on();  ?>
        </div><!-- .entry-meta -->

        <div class="entry-content">
            <p><?php the_excerpt(); ?></p>
        </div><!-- .entry-content -->

        <div class="more-link">
            <a href="#">
                <?php echo esc_html__( 'Read More', 'travel-life' ); ?>
                <svg viewBox="0 0 292.359 292.359">
                    <path d="M222.979,133.331L95.073,5.424C91.456,1.807,87.178,0,82.226,0c-4.952,0-9.233,1.807-12.85,5.424
                    c-3.617,3.617-5.424,7.898-5.424,12.847v255.813c0,4.948,1.807,9.232,5.424,12.847c3.621,3.617,7.902,5.428,12.85,5.428
                    c4.949,0,9.23-1.811,12.847-5.428l127.906-127.907c3.614-3.613,5.428-7.897,5.428-12.847
                    C228.407,141.229,226.594,136.948,222.979,133.331z"/>
                </svg>
            </a>
        </div><!-- .more-link -->
    </div><!-- .entry-container -->
</article>

