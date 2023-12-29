<?php
/**
 * Testimonial section
 *
 * This is the template for the content of testimonial section
 *
 * @package Theme Palace
 * @subpackage Swingpress
 * @since Swingpress 1.0.0
 */
if ( ! function_exists( 'swingpress_add_testimonial_section' ) ) :
    /**
    * Add testimonial section
    *
    *@since Swingpress 1.0.0
    */
    function swingpress_add_testimonial_section() {
        $options = swingpress_get_theme_options();
        // Check if testimonial is enabled on frontpage
        $testimonial_enable = apply_filters( 'swingpress_section_status', true, 'testimonial_section_enable' );

        if ( true !== $testimonial_enable ) {
            return false;
        }
        // Get testimonial section details
        $section_details = array();
        $section_details = apply_filters( 'swingpress_filter_testimonial_section_details', $section_details );

        if ( empty( $section_details ) ) {
            return;
        }

        // Render testimonial section now.
        swingpress_render_testimonial_section( $section_details );
    }
endif;

if ( ! function_exists( 'swingpress_get_testimonial_section_details' ) ) :
    /**
    * testimonial section details.
    *
    * @since Swingpress 1.0.0
    * @param array $input testimonial section details.
    */
    function swingpress_get_testimonial_section_details( $input ) {
        $options = swingpress_get_theme_options();

        $testimonial_count = 4;
        $content = array();

        $page_ids = array();
        $author = array();

        for ( $i = 1; $i <= $testimonial_count; $i++ ) {
            if ( ! empty( $options['testimonial_content_page_' . $i] ) ) :
                $page_ids[] = $options['testimonial_content_page_' . $i];
                $author[] = ! empty( $options['testimonial_author_' . $i] ) ? $options['testimonial_author_' . $i] : '';
            endif;
        }
        
        $args = array(
            'post_type'         => 'page',
            'post__in'          => ( array ) $page_ids,
            'posts_per_page'    => absint( $testimonial_count ),
            'orderby'           => 'post__in',
            );                    

        $query = new WP_Query( $args );
        $i = 0;
        if ( $query->have_posts() ) : 
            while ( $query->have_posts() ) : $query->the_post();
                $page_post['id']        = get_the_id();
                $page_post['title']     = get_the_title();
                $page_post['author']    = !empty( $author[$i] )  ? $author[$i] : '';
                $page_post['url']       = get_the_permalink();
                $page_post['excerpt']   = swingpress_trim_content( 35 );
                $page_post['image']     = has_post_thumbnail() ? get_the_post_thumbnail_url( get_the_id(), 'large' ) : get_template_directory_uri() . '/assets/uploads/no-featured-image-590x650.jpg';

                // Push to the main array.
                array_push( $content, $page_post );
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
// testimonial section content details.
add_filter( 'swingpress_filter_testimonial_section_details', 'swingpress_get_testimonial_section_details' );


if ( ! function_exists( 'swingpress_render_testimonial_section' ) ) :
  /**
   * Start testimonial section
   *
   * @return string testimonial content
   * @since Swingpress 1.0.0
   *
   */
   function swingpress_render_testimonial_section( $content_details = array() ) {
        $options = swingpress_get_theme_options();

        if ( empty( $content_details ) ) {
            return;
        } ?>
        <div id="swingpress_testimonial_section" class="relative page-section same-background">
            <div id="testimonial-section" >
                <div class="wrapper">
                    <?php if ( !empty( $options['testimonial_image'] ) ): ?>
                        <div class="testimonial-featured-image" style="background-image: url('<?php echo esc_url( $options['testimonial_image'] ); ?>');">
                        </div>
                    <?php endif ?>
                   
                    <div class="testimonial-content">
                        <div class="testimonial-slider"  data-slick='{"slidesToShow": 1, "slidesToScroll": 1, "infinite": false, "speed": 1000, "dots": false, "arrows":true, "autoplay": <?php echo wp_kses_post( $content['excerpt'] ); ?>, "draggable": true, "fade": false }'>
                            <?php $i=1; foreach ( $content_details as $content ) : ?>
                                <article>
                                    <div class="entry-container">
                                        <div class="featured-image">
                                            <a href="<?php echo esc_url( $content['url'] ); ?>"><img src="<?php echo esc_url( $content['image'] ); ?>" alt="testimonial"></a>
                                            <header class="entry-header">
                                                <h2 class="entry-title"><a href="<?php echo esc_url( $content['url'] ); ?>"><?php echo esc_html( $content['title'] ); ?></a></h2>
                                                <?php if ( !empty( $options['testimonial_position_'.$i] ) ): ?>
                                                    <span class="testimonial-position"><?php echo esc_html( $options['testimonial_position_'.$i] ); ?></span>
                                                <?php endif ?>                                            
                                            </header>
                                        </div><!-- .featured-image -->

                                        <div class="entry-content">
                                            <p><?php echo esc_html( $content['excerpt'] ); ?></p>
                                        </div><!-- .entry-content -->
                                    </div><!-- .entry-container -->
                                </article>
                            <?php $i=1; endforeach; ?>
                        </div><!-- .section-content -->
                    </div><!-- .testimonial-content -->
                </div><!-- .wrapper -->
            </div><!-- .client-testimonial -->

        </div>
        
    <?php }
endif;
