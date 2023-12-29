<?php
/**
 * Testimonial section
 *
 * This is the template for the content of testimonial section
 *
 * @package Theme Palace
 * @subpackage Travel Life
 * @since Travel Life 1.0.0
 */
if ( ! function_exists( 'travel_life_add_testimonial_section' ) ) :
    /**
    * Add testimonial section
    *
    *@since Travel Life 1.0.0
    */
    function travel_life_add_testimonial_section() {
        $options = travel_life_get_theme_options();
        // Check if testimonial is enabled on frontpage
        $testimonial_enable = apply_filters( 'travel_life_section_status', true, 'testimonial_section_enable' );

        if ( true !== $testimonial_enable ) {
            return false;
        }
        // Get testimonial section details
        $section_details = array();
        $section_details = apply_filters( 'travel_life_filter_testimonial_section_details', $section_details );

        if ( empty( $section_details ) ) {
            return;
        }

        // Render testimonial section now.
        travel_life_render_testimonial_section( $section_details );
    }
endif;

if ( ! function_exists( 'travel_life_get_testimonial_section_details' ) ) :
    /**
    * testimonial section details.
    *
    * @since Travel Life 1.0.0
    * @param array $input testimonial section details.
    */
    function travel_life_get_testimonial_section_details( $input ) {
        $options = travel_life_get_theme_options();

        $testimonial_count = 5;
        $content = array();
 
        $page_ids = array();

        for ( $i = 1; $i <= $testimonial_count; $i++ ) {
            if ( ! empty( $options['testimonial_content_page_' . $i] ) ) :
                $page_ids[] = $options['testimonial_content_page_' . $i];
            endif;
        }
        
        $args = array(
            'post_type'         => 'page',
            'post__in'          => ( array ) $page_ids,
            'posts_per_page'    => absint( $testimonial_count ),
            'orderby'           => 'post__in',
            );                    
          
        // Run The Loop.
        $query = new WP_Query( $args );
        $i = 1;
        if ( $query->have_posts() ) : 
            while ( $query->have_posts() ) : $query->the_post();
                $page_post['id']        = get_the_id();
                $page_post['title']     = get_the_title();
                $page_post['url']       = get_the_permalink();
                $page_post['excerpt']   = travel_life_trim_content( 35 );
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
add_filter( 'travel_life_filter_testimonial_section_details', 'travel_life_get_testimonial_section_details' );


if ( ! function_exists( 'travel_life_render_testimonial_section' ) ) :
  /**
   * Start testimonial section
   *
   * @return string testimonial content
   * @since Travel Life 1.0.0
   *
   */
   function travel_life_render_testimonial_section( $content_details = array() ) {
        $options = travel_life_get_theme_options();

        if ( empty( $content_details ) ) {
            return;
        } ?>
        <div id="travel_life_testimonial_section" class="relative page-section">
              <div id="testimonial-section">
                <div class="wrapper">
                    <?php if ( is_customize_preview()):
                        travel_life_section_tooltip( 'testimonial-section' );
                    endif; ?>
                    <?php if ( !empty($options['testimonial_section_title']) ): ?>
                        <div class="section-header">
                            <h2 class="section-title">
                                <?php echo esc_html( $options['testimonial_section_title'] ); ?>
                            </h2>
                            <?php if (!empty($options['testimonial_section_sub_title'])): ?>
                            <p class="section-subtitle"><?php echo esc_html( $options['testimonial_section_sub_title'] ) ; ?></p>
                        <?php endif ?>
                        </div>
                    <?php endif ?>
                    
                    <!-- use class "classic-slider" for 3 items and use class "modern-slider" for more than 3 items -->
                    <div class="testimonial-slider" data-slick='{"slidesToShow": 3, "slidesToScroll": 1, "infinite": true, "speed": 1500, "dots": false, "arrows":true, "autoplay": <?php echo $options['testimonial_auto_play'] ? 'true' : 'false'; ?>, "draggable": true, "fade": false }'>
                        <?php foreach ( $content_details as $content ) : ?>
                            <article class="<?php echo ! empty( $content['image'] ) ? 'has' : 'no'; ?>-post-thumbnail">
                                <div class="testimonial-item">
                                    <?php if (!empty($content['image'])) { ?>
                                        <div class="featured-image">
                                            <a href="<?php echo esc_url( $content['url'] ); ?>"><img src="<?php echo esc_url( $content['image'] ); ?>"></a>
                                        </div><!-- .featured-image -->
                                    <?php } ?>
                                    <div class="entry-container">
                                        <div class="entry-content">
                                            <p><?php echo esc_html($content['excerpt']); ?></p>
                                        </div><!-- .entry-content -->

                                        <div class="separator"></div>

                                        <header class="entry-header">
                                            <h2 class="entry-title"><a href="<?php echo esc_url( $content['url'] ); ?>"><?php echo esc_html($content['title']) ?></a></h2>
                                        </header>
                                    </div><!-- .entry-container -->
                                </div><!-- .testimonial-item -->
                            </article>
                        <?php endforeach; ?>
                    </div><!-- .clients-feedback -->
                    
                    <div class="read-more">
                        <?php if (!empty($options['testimonial_section_btn_text']) && !empty($options['testimonial_btn_link'])): ?>
                        <a href="<?php echo esc_url($options['testimonial_btn_link']); ?>" class="btn"><?php echo esc_html($options['testimonial_section_btn_text']); ?></a>
                        <?php endif; ?>
                    </div><!-- .read-more -->
                    
                </div><!-- .wrapper -->
            </div><!-- .client-testimonial -->
        </div>
      

    <?php }
endif;
