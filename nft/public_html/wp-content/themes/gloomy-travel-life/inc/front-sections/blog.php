<?php
/**
 * Blog section
 *
 * This is the template for the content of team section
 *
 * @package Theme Palace
 * @subpackage  gloomy_travel_life
 * @since  gloomy_travel_life 1.0.0
 */
if ( ! function_exists( 'gloomy_travel_life_add_blog_section' ) ) :
    /**
    * Add team section
    *
    *@since  gloomy_travel_life 1.0.0
    */
    function gloomy_travel_life_add_blog_section() {

        // Check if team is enabled on frontpage
        $event_enable = get_theme_mod('gloomy_travel_life_blog_section_enable', false );

        if ( true !== $event_enable ) {
            return false;
        }
        // Get team section details
        $section_details = array();
        $section_details = apply_filters( 'gloomy_travel_life_filter_blog_section_details', $section_details );

        if ( empty( $section_details ) ) {
            return;
        }

        // Render team section now.
        gloomy_travel_life_render_blog_section( $section_details );
    }
endif;

if ( ! function_exists( 'gloomy_travel_life_get_blog_section_details' ) ) :
    /**
    * team section details.
    *
    * @since  gloomy_travel_life 1.0.0
    * @param array $input team section details.
    */
    function gloomy_travel_life_get_blog_section_details( $input ) {
        
        $post_count = 4;
        $content = array();
        $post_ids = array();
        $position = array();

        for ( $i = 1; $i <= absint($post_count); $i++ ) {
            if ( ! empty(  get_theme_mod('gloomy_travel_life_blog_content_post_'.$i, '' ) ) ) :
                $post_ids[] =  get_theme_mod('gloomy_travel_life_blog_content_post_'.$i, '' );
            endif;
        }
        
        $args = array(
            'post_type'         => 'post',
            'post__in'          => ( array ) $post_ids,
            'posts_per_page'    => absint($post_count),
            'orderby'           => 'post__in',
        );                    
           
        $query = new WP_Query( $args );
        $i = 1;
        if ( $query->have_posts() ) : 
            while ( $query->have_posts() ) : $query->the_post();
                $post_content['id']         = get_the_id();
                $post_content['title']      = get_the_title();
                $post_content['url']        = get_the_permalink();
                $post_content['excerpt']    = travel_life_trim_content( 20 );
                $post_content['image']  	= has_post_thumbnail() ? get_the_post_thumbnail_url( get_the_id(), 'post-thumbnails' ) : get_template_directory_uri() . '/assets/uploads/no-featured-image-600x450.jpg';

                // Push to the main array.
                array_push( $content, $post_content );
                $i++;
            endwhile;
        endif;
        wp_reset_postdata();
            
        if ( ! empty( $content ) ) {
            $input = $content;
        }
        return $input;
    }
endif;
// team section content details.
add_filter( 'gloomy_travel_life_filter_blog_section_details', 'gloomy_travel_life_get_blog_section_details' );


if ( ! function_exists( 'gloomy_travel_life_render_blog_section' ) ) :
  /**
   * Start team section
   *
   * @return string team content
   * @since  gloomy_travel_life 1.0.0
   *
   */
   function gloomy_travel_life_render_blog_section( $content_details = array()     ) {

    ?>
    <div id="travel_life_blog_section" class="relative page-section same-background">
        <div id="latest-posts">
            <div class="wrapper">
                <?php if ( is_customize_preview()):
                    travel_life_section_tooltip( 'latest-posts' );
                endif; ?>
                <div class="section-header">
                    <?php if ( !empty( get_theme_mod('gloomy_travel_life_blog_title', '' ) ) ): ?>
                        <h2 class="section-title"><?php echo esc_html( get_theme_mod('gloomy_travel_life_blog_title', '' ) ); ?></h2>
                    <?php endif ?>  
                </div><!-- .section-header -->

                <div class="section-content archive-blog-wrapper col-2 clear">
                    <?php foreach ( $content_details as $content ) : ?>
                    <article class="has-post-thumbnail">
                        <div class="featured-image" style="background-image: url('<?php echo esc_url($content['image']) ?>');">
                            <a href="<?php echo esc_url($content['url']) ?>" class="post-thumbnail-link"></a>
                        </div><!-- .featured-image -->                   

                        <div class="entry-container">
                            <header class="entry-header">
                                <h2 class="entry-title"><a href="<?php echo esc_url($content['url']) ?>"><?php echo esc_html($content['title']) ?></a></h2>
                            </header>

                            <div class="entry-meta">
                                <span class="cat-links">                 
                                    <span class="cat-links">
                                        <?php the_category('','', $content['id']); ?>
                                    </span><!-- .cat-links -->                                                           
                                </span><!-- .cat-links -->

                                <?php travel_life_posted_on( $content['id'] ); ?>
                            </div><!-- .entry-meta -->                       

                            <div class="entry-content">
                                <p><?php echo esc_html($content['excerpt']) ?></p>
                            </div><!-- .entry-content -->
                
                            <div class="more-link">
                                <a href="<?php echo esc_url($content['url']) ?>"><?php echo esc_html__( 'Read More', 'gloomy-travel-life' ) ?>
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
                    <?php endforeach; ?>
                </div><!-- .section-content -->
            </div><!-- .wrapper -->
        </div><!-- #latest-posts -->  
    </div>
    

<?php }
endif;