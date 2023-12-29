<?php
/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Theme Palace
 * @subpackage Swingpress
 * @since Swingpress 1.0.0
 */
$class = has_post_thumbnail() ? '' : 'no-post-thumbnail';
$options = swingpress_get_theme_options();
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( $class ); ?>>

    <div class="post-wrapper clear">
        
        <?php if ( has_post_thumbnail() && !$options['hide_image'] ) : ?>
            <div class="featured-image matchheight" style="background-image: url('<?php the_post_thumbnail_url( 'post-thumbnail' ); ?>');">
                <a href="<?php the_permalink(); ?>" class="post-thumbnail-link"></a>
            </div><!-- .featured-image -->
        <?php endif; ?>
        <div class="entry-container">
            <div class="entry-meta">
                <?php echo swingpress_article_header_meta(); ?>
            </div>            

            <header class="entry-header">
                <h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
            </header>

            <?php if ( !$options['hide_content'] ): ?>
                <div class="entry-content">
                    <p><?php the_excerpt(); ?></p>
                </div><!-- .entry-content -->
            <?php endif ?>
            
            <?php if ( !$options['hide_btn'] ): ?>
                <div class="more-link">
                    <a href="<?php the_permalink(); ?>">
                        <?php echo __( 'Read More', 'swingpress' ); ?>
                        <svg viewBox="0 0 31.49 31.49">
                            <path d="M21.205,5.007c-0.429-0.444-1.143-0.444-1.587,0c-0.429,0.429-0.429,1.143,0,1.571l8.047,8.047H1.111
                            C0.492,14.626,0,15.118,0,15.737c0,0.619,0.492,1.127,1.111,1.127h26.554l-8.047,8.032c-0.429,0.444-0.429,1.159,0,1.587
                            c0.444,0.444,1.159,0.444,1.587,0l9.952-9.952c0.444-0.429,0.444-1.143,0-1.571L21.205,5.007z"/>
                        </svg>
                    </a>
                </div><!-- .more-link -->
            <?php endif ?>
            
        </div><!-- .entry-container -->
    </div><!-- .post-item-wrapper -->
</article><!-- #post-## -->
