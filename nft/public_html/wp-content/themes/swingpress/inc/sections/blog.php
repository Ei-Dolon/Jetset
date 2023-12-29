<?php
/**
 * Blog section
 *
 * This is the template for the content of blog section
 *
 * @package Theme Palace
 * @subpackage Swingpress
 * @since Swingpress 1.0.0
 */
if ( ! function_exists( 'swingpress_add_blog_section' ) ) :
    /**
    * Add blog section
    *
    *@since Swingpress 1.0.0
    */
    function swingpress_add_blog_section() {
    	$options = swingpress_get_theme_options();
        // Check if blog is enabled on frontpage
        $blog_enable = apply_filters( 'swingpress_section_status', true, 'blog_section_enable' );

        if ( true !== $blog_enable ) {
            return false;
        }
        // Get blog section details
        $section_details = array();
        $section_details = apply_filters( 'swingpress_filter_blog_section_details', $section_details );

        if ( empty( $section_details ) ) {
            return;
        }

        // Render blog section now.
        swingpress_render_blog_section( $section_details );
    }
endif;

if ( ! function_exists( 'swingpress_get_blog_section_details' ) ) :
    /**
    * blog section details.
    *
    * @since Swingpress 1.0.0
    * @param array $input blog section details.
    */
    function swingpress_get_blog_section_details( $input ) {
        $options = swingpress_get_theme_options();

        // Content type.
        $blog_content_type  = $options['blog_content_type'];
        $blog_count = 4;
        
        $content = array();
        switch ( $blog_content_type ) {
        	
            case 'page':
                $page_ids = array();

                for ( $i = 1; $i <= $blog_count; $i++ ) {
                    if ( ! empty( $options['blog_content_page_' . $i] ) )
                        $page_ids[] = $options['blog_content_page_' . $i];
                }
                
                $args = array(
                    'post_type'         => 'page',
                    'post__in'          => ( array ) $page_ids,
                    'posts_per_page'    => absint( $blog_count ),
                    'orderby'           => 'post__in',
                    );                    
            break;

            case 'post':
                $post_ids = array();

                for ( $i = 1; $i <= $blog_count; $i++ ) {
                    if ( ! empty( $options['blog_content_post_' . $i] ) )
                        $post_ids[] = $options['blog_content_post_' . $i];
                }
                
                $args = array(
                    'post_type'         => 'post',
                    'post__in'          => ( array ) $post_ids,
                    'posts_per_page'    => absint( $blog_count ),
                    'orderby'           => 'post__in',
                    'ignore_sticky_posts'   => true,
                    );                    
            break;

            case 'category':
                $cat_id = ! empty( $options['blog_content_category'] ) ? $options['blog_content_category'] : '';
                $args = array(
                    'post_type'         => 'post',
                    'posts_per_page'    => absint( $blog_count ),
                    'cat'               => absint( $cat_id ),
                    'ignore_sticky_posts'   => true,
                    );                    
            break;

            case 'recent':
                $cat_ids = ! empty( $options['blog_category_exclude'] ) ? $options['blog_category_exclude'] : array();
                $args = array(
                    'post_type'         => 'post',
                    'posts_per_page'    => absint( $blog_count ),
                    'category__not_in'  => ( array ) $cat_ids,
                    'ignore_sticky_posts'   => true,
                    );                    
            break;


            default:
            break;
        }


        // Run The Loop.
        $query = new WP_Query( $args );
        if ( $query->have_posts() ) : 
            while ( $query->have_posts() ) : $query->the_post();
                $page_post['id']        = get_the_id();
                $page_post['title']     = get_the_title();
                $page_post['url']       = get_the_permalink();
                $page_post['excerpt']   = swingpress_trim_content( 20 );
                $page_post['image']     = has_post_thumbnail() ? get_the_post_thumbnail_url( get_the_id(), 'post-thumbnail' ) : get_template_directory_uri() . '/assets/uploads/no-featured-image-590x650.jpg'; 

                // Push to the main array.
                array_push( $content, $page_post );
            endwhile;
        endif;
        wp_reset_postdata();

            
        if ( ! empty( $content ) ) {
            $input = $content;
        }
        return $input;
    }
endif;
// blog section content details.
add_filter( 'swingpress_filter_blog_section_details', 'swingpress_get_blog_section_details' );


if ( ! function_exists( 'swingpress_render_blog_section' ) ) :
  /**
   * Start blog section
   *
   * @return string blog content
   * @since Swingpress 1.0.0
   *
   */
   function swingpress_render_blog_section( $content_details = array() ) {
        $options = swingpress_get_theme_options();
        $blog_content_type  = $options['blog_content_type'];

        if ( empty( $content_details ) ) {
            return;
        } ?>
        <div id="swingpress_blog_section" class="relative page-section">
            <div id="latest-posts-section">
                <div class="wrapper">
                    <div class="section-header">
                        <?php if ( ! empty( $options['blog_title'] ) ) : ?>
                            <h2 class="section-title"><?php echo esc_html( $options['blog_title'] ); ?></h2>
                        <?php endif; ?>
                        <?php if ( ! empty( $options['blog_sub_title'] ) ) : ?>
                            <p class="section-subtitle"><?php echo esc_html( $options['blog_sub_title'] ); ?></p>
                        <?php endif; ?>
                    </div><!-- .section-header -->

                    <div class="archive-blog-wrapper list-view col-2 clear">
                        <?php foreach ( $content_details as $content ) : ?>
                            <article class="<?php echo ! empty( $content['image'] ) ? 'has' : 'no'; ?>-post-thumbnail">
                                <div class="post-wrapper clear">
                                    <div class="featured-image" style="background-image: url('<?php echo esc_url( $content['image'] ); ?>');">
                                        <a href="<?php echo esc_url( $content['url'] ); ?>" class="post-thumbnail-link"></a>
                                    </div><!-- .featured-image -->

                                    <div class="entry-container">
                                        <div class="entry-meta">
                                            <span class="cat-links">                                          
                                                <?php the_category( '', '', $content['id'] ); ?>           
                                            </span><!-- .cat-links -->
                                        </div><!-- .entry-meta -->

                                        <header class="entry-header">
                                            <h2 class="entry-title"><a href="<?php echo esc_url( $content['url'] ); ?>"><?php echo esc_html( $content['title'] ); ?></a></h2>
                                        </header>

                                        <div class="entry-content">
                                            <p><?php echo esc_html( $content['excerpt'] ); ?></p>
                                        </div><!-- .entry-content -->

                                         <?php if ( ! empty( $options['blog_btn_title'] ) ) : ?>
                                            <div class="more-link">
                                                <a href="<?php echo esc_url( $content['url'] ); ?>">
                                                    <?php echo esc_html( $options['blog_btn_title'] ); ?>
                                                    <svg viewBox="0 0 31.49 31.49">
                                                        <path d="M21.205,5.007c-0.429-0.444-1.143-0.444-1.587,0c-0.429,0.429-0.429,1.143,0,1.571l8.047,8.047H1.111
                                                        C0.492,14.626,0,15.118,0,15.737c0,0.619,0.492,1.127,1.111,1.127h26.554l-8.047,8.032c-0.429,0.444-0.429,1.159,0,1.587
                                                        c0.444,0.444,1.159,0.444,1.587,0l9.952-9.952c0.444-0.429,0.444-1.143,0-1.571L21.205,5.007z"/>
                                                    </svg>
                                                </a>
                                            </div><!-- .more-link -->
                                        <?php endif; ?>
                                        
                                    </div><!-- .entry-container -->
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div><!-- .archive-blog-wrapper -->
                    <?php if ( ! empty( $options['blog_alt_btn_title'] ) && ! empty( $options['blog_alt_btn_url'] ) ) : ?>
                         <div class="read-more">
                            <a href="<?php echo esc_url( $options['blog_alt_btn_url'] ) ; ?>" class="btn"> <?php echo esc_html( $options['blog_alt_btn_title'] ) ; ?></a>
                        </div><!-- .read-more -->
                    <?php endif; ?>       

                </div><!-- .wrapper -->
            </div><!-- #latest-posts -->
        </div>
      
    <?php }
endif;