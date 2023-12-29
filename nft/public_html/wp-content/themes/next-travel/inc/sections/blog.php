<?php
/**
 * Blog section
 *
 * This is the template for the content of Blog section
 *
 * @package Theme Palace
 * @subpackage Next Travel
 * @since Next Travel 1.0.0
 */
if ( ! function_exists( 'next_travel_add_blog_section' ) ) :
    /**
    * Add Blog section
    *
    *@since Next Travel 1.0.0
    */
    function next_travel_add_blog_section() {
        $options = next_travel_get_theme_options();
        
        // Check if blog is enabled on frontpage
        $blog_enable = apply_filters( 'next_travel_section_status', true, 'blog_section_enable' );

        if ( true !== $blog_enable ) {
            return false;
        }
        // Get Blog section details
        $section_details = array();
        $section_details = apply_filters( 'next_travel_filter_blog_section_details', $section_details );
        if ( empty( $section_details ) ) {
            return;
        }
        // Render Default blog section now.
        next_travel_render_blog_section( $section_details );
    }
endif;

if ( ! function_exists( 'next_travel_get_blog_section_details' ) ) :
    /**
    * Blog section details.
    *
    * @since Next Travel 1.0.0
    * @param array $input Blog section details.
    */
    function next_travel_get_blog_section_details( $input ) {
        $options = next_travel_get_theme_options();

        $blog_content_type  = $options['blog_content_type']; // Content type.
        
        $content = array();
        switch ( $blog_content_type ) {

            case 'category':
                $cat_id = ! empty( $options['blog_content_category'] ) ? $options['blog_content_category'] : '';
                $args = array(
                    'post_type'         => 'post',
                    'posts_per_page'    => absint( 4 ),
                    'cat'               => absint( $cat_id ),
                    'ignore_sticky_posts'   => true,
                    );                    
            break;

            case 'recent':
                $cat_ids = ! empty( $options['blog_category_exclude'] ) ? $options['blog_category_exclude'] : array();
                $args = array(
                    'post_type'         => 'post',
                    'posts_per_page'    => absint( 4 ),
                    'category__not_in'  => ( array ) $cat_ids,
                    'ignore_sticky_posts'   => true,
                    );            
            break;

            default:
            break;
        }

        $content['details'] = array();
        // Run The Loop.
        $query = new WP_Query( $args );
        if ( $query->have_posts() ) : 
            while ( $query->have_posts() ) : $query->the_post();
                $page_post['id']            = get_the_id();
                $page_post['title']         = get_the_title();
                $page_post['url']           = get_the_permalink();
                $page_post['excerpt']       = next_travel_trim_content( 30 );
                $page_post['image']         = has_post_thumbnail() ? get_the_post_thumbnail_url( get_the_id(), 'full' ) : get_template_directory_uri().'/assets/uploads/no-featured-image-590x650.jpg';

                // Push to the main array.
                array_push( $content['details'], $page_post );
            endwhile;
        endif;
        wp_reset_postdata();

            
        if ( ! empty( $content ) ) {
            $input = $content;
        }
        return $input;
    }
endif;
// Blog section content details.
add_filter( 'next_travel_filter_blog_section_details', 'next_travel_get_blog_section_details' );


if ( ! function_exists( 'next_travel_render_blog_section' ) ) :
  /**
   * Start Blog section
   *
   * @return string Blog content
   * @since Next Travel 1.0.0
   *
   */
   function next_travel_render_blog_section( $content_details = array() ) {
        $options            = next_travel_get_theme_options();
        $blog_content_type  = $options['blog_content_type'];
        $blog_title         = ! empty( $options['blog_title'] ) ? $options['blog_title'] : '';
        $blog_sub_title     = ! empty( $options['blog_sub_title'] ) ? $options['blog_sub_title'] : '';
        if ( empty( $content_details ) ) {
            return;
        } ?>
        <div id="next_travel_blog_section">
            <div id="latest-posts" class="relative">
                    <div class="blog-posts-wrapper clear">

                    <?php $i =1; foreach($content_details['details'] as $content):
                        if ( $i == 1 ) {
                     ?>
                        <div class="sticky-post-wrapper" style="background-image: url('<?php echo esc_url($content['image']); ?>');">

                            <article>
                                <div class="entry-container">
                                    <header class="entry-header">
                                        <h2 class="entry-title"><a href="<?php echo esc_url($content['url']); ?>"><?php echo esc_html($content['title']); ?></a></h2>
                                    </header>   

                                    <div class="entry-content">
                                        <p><?php echo esc_html($content['excerpt']); ?></p>
                                    </div><!-- .entry-content -->

                                    <div class="entry-meta">
                                        <span class="cat-links">
                                            <?php the_category( '', '', $content['id'] ); ?>
                                        </span><!-- .cat-links -->

                                        <span class="posted-on">
                                            <?php next_travel_posted_on( $content['id'] ); ?>
                                        </span><!-- .posted-on -->
                                    </div><!-- .entry-meta -->
                                </div><!-- .entry-container -->
                            </article>

                        </div><!-- .sticky-post-wrapper -->
                        <?php }
                        break;

                        $i++; endforeach; ?>

                        <div class="post-wrapper">
                            <div class="section-header">
                                <?php if( !empty( $blog_sub_title ) ): ?>
                                    <h3 class="section-subtitle"><?php echo esc_html( $blog_sub_title ); ?></h3>
                                <?php endif;
                                if( !empty( $blog_title ) ):
                                 ?>
                             <h2 class="section-title"><?php echo esc_html( $blog_title ); ?></h2>
                         <?php endif; ?>
                         <span class="separator"></span>
                     </div><!-- .section-header -->

                     <div class="section-content clear">

                     <?php $j = 1; foreach($content_details['details'] as $content):
                        if ( $j !== 1 ) {
                     ?>

                        <article class="has-post-thumbnail">
                            <div class="featured-image">
                                <a href="<?php echo esc_url($content['url']); ?>"><img src="<?php echo esc_url($content['image']); ?>" alt="post-<?php echo esc_attr( $j + 1 ); ?>"></a>
                            </div><!-- .featured-image -->

                            <div class="entry-container">
                                <header class="entry-header">
                                    <h2 class="entry-title"><a href="<?php echo esc_url($content['url']); ?>"><?php echo esc_html( $content['title']); ?></a></h2>
                                </header>

                                <div class="entry-meta">
                                    <span class="cat-links">
                                        <?php the_category( '', '', $content['id'] ); ?>
                                    </span><!-- .cat-links -->

                                    <?php next_travel_posted_on( $content['id'] ); ?>
                                </div><!-- .entry-meta -->
                            </div><!-- .entry-container -->
                        </article>
                        <?php }
                        $j++; endforeach; ?>

                    </div><!-- .section-content -->
                </div><!-- .post-wrapper -->
            </div>
        </div><!-- #latest-posts -->
        </div>
      
    <?php }
endif;