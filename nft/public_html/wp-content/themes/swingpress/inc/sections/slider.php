<?php
/**
 * Slider section
 *
 * This is the template for the content of slider section
 *
 * @package Theme Palace
 * @subpackage Swingpress
 * @since Swingpress 1.0.0
 */
if ( ! function_exists( 'swingpress_add_slider_section' ) ) :
    /**
    * Add slider section
    *
    *@since Swingpress 1.0.0
    */
    function swingpress_add_slider_section() {
    	$options = swingpress_get_theme_options();
        // Check if slider is enabled on frontpage
        $slider_enable = apply_filters( 'swingpress_section_status', true, 'slider_section_enable' );

        if ( true !== $slider_enable ) {
            return false;
        }
        // Get slider section details
        $section_details = array();
        $section_details = apply_filters( 'swingpress_filter_slider_section_details', $section_details );

        if ( empty( $section_details ) ) {
            return;
        }

        // Render slider section now.
        swingpress_render_slider_section( $section_details );
    }
endif;

if ( ! function_exists( 'swingpress_get_slider_section_details' ) ) :
    /**
    * slider section details.
    *
    * @since Swingpress 1.0.0
    * @param array $input slider section details.
    */
    function swingpress_get_slider_section_details( $input ) {
        $options = swingpress_get_theme_options();

        // Content type.
        $slider_content_type  = $options['slider_content_type'];
        $slider_count = 3;
        
        $content = array();
        switch ( $slider_content_type ) {
        	
            case 'page':
                $page_ids = array();

                for ( $i = 1; $i <= $slider_count; $i++ ) {
                    if ( ! empty( $options['slider_content_page_' . $i] ) )
                        $page_ids[] = $options['slider_content_page_' . $i];
                }
                
                $args = array(
                    'post_type'         => 'page',
                    'post__in'          => ( array ) $page_ids,
                    'posts_per_page'    => absint( $slider_count ),
                    'orderby'           => 'post__in',
                    );                    
            break;

            case 'trip':
                if ( ! class_exists( 'WP_Travel' ) )
                    return;

                $page_ids = array();

                for ( $i = 1; $i <= $slider_count; $i++ ) {
                    if ( ! empty( $options['slider_content_trip_' . $i] ) )
                        $page_ids[] = $options['slider_content_trip_' . $i];
                }
                
                $args = array(
                    'post_type'         => 'itineraries',
                    'post__in'          => ( array ) $page_ids,
                    'posts_per_page'    => absint( $slider_count ),
                    'orderby'           => 'post__in',
                    );                    
            break;

          }


            // Run The Loop.
            $query = new WP_Query( $args );
            if ( $query->have_posts() ) : 
                while ( $query->have_posts() ) : $query->the_post();
                    $page_post['id']        = get_the_id();
                    $page_post['title']     = get_the_title();
                    $page_post['url']       = get_the_permalink();
                    $page_post['excerpt']   = swingpress_trim_content( 15 );
                    $page_post['image']  	= has_post_thumbnail() ? get_the_post_thumbnail_url( get_the_id(), 'full' ) : '';

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
// slider section content details.
add_filter( 'swingpress_filter_slider_section_details', 'swingpress_get_slider_section_details' );


if ( ! function_exists( 'swingpress_render_slider_section' ) ) :
  /**
   * Start slider section
   *
   * @return string slider content
   * @since Swingpress 1.0.0
   *
   */
   function swingpress_render_slider_section( $content_details = array() ) {
        $options = swingpress_get_theme_options();
        $btn_label = ! empty( $options['slider_btn_label'] ) ? $options['slider_btn_label'] : esc_html__( 'Learn More', 'swingpress' );

        if ( empty( $content_details ) ) {
            return;
        } ?>
        <div id="swingpress_slider_section">
            <?php if ( $options['slider_design'] === 'first-design' ): ?>
                <div id="featured-slider-section" class="slider-section">
                    <div class="featured-slider" data-slick='{"slidesToShow": 1, "slidesToScroll": 1, "infinite": true, "speed": 800, "dots": true, "arrows": false, "autoplay": <?php echo $options['slider_autoplay'] ? 'true' : 'false'; ?>, "draggable": true, "fade": false }'>
                        <?php $i=1; foreach ( $content_details as $content ) : ?>
                            <article style="background-image:url('<?php echo esc_url( $content['image'] ); ?>');">
                                <div class="wrapper">
                                    <div class="featured-content-wrapper">
                                        <div class="entry-container">
                                            <header class="entry-header">
                                                <?php if ( !empty( $options['slider_label_'.$i] ) ): ?>
                                                     <span><?php echo esc_html(  $options['slider_label_'.$i] ); ?></span>
                                                <?php endif ?>
                                               
                                                <h2 class="entry-title"><a href="<?php echo esc_url( $content['url'] ); ?>"><?php echo esc_html( $content['title'] ); ?></a></h2>
                                            </header>

                                            <div class="entry-content">
                                                <p><?php echo esc_html( $content['excerpt'] ); ?></p>
                                            </div><!-- .entry-content -->

                                            <div class="read-more">
                                                 <a href="<?php echo esc_url( $content['url'] ); ?>" class="btn btn-fill"><?php echo esc_html( $btn_label ); ?></a>
                                            </div><!-- .read-more -->
                                        </div><!-- .entry-container -->
                                    </div><!-- .featured-content-wrapper -->
                                </div><!-- .wrapper -->
                            </article>
                        <?php $i++; endforeach; ?>
                    </div><!-- .featured-slider -->
                </div><!-- #featured-slider-section -->
                <?php else: ?>
                <div id="blog-slider-section" class="slider-section">
                    <div class="blog-slider" data-slick='{"slidesToShow": 1, "slidesToScroll": 1, "infinite": true, "speed": 800, "dots": true, "arrows":false, "autoplay": <?php echo $options['slider_autoplay'] ? 'true' : 'false'; ?>, "draggable": true, "fade": false, "adaptiveHeight": true }'>
                        <?php $i=1; foreach ( $content_details as $content ) : ?>
                            <article style="background-image:url('<?php echo esc_url( $content['image'] ); ?>');">
                                <div class="wrapper">
                                    <div class="blog-content-wrapper">
                                        <div class="entry-container">
                                            <div class="entry-meta">
                                                <span class="cat-links">
                                                    <?php the_category( '', '', $content['id'] ) ?>
                                                </span><!-- .cat-links -->

                                               <?php swingpress_posted_on( $content['id'] ); ?>
                                            </div><!-- .entry-meta -->

                                            <header class="entry-header">
                                                <h2 class="entry-title"><a href="<?php echo esc_url( $content['url'] ); ?>"><?php echo esc_html( $content['title'] ); ?></a></h2>
                                            </header>

                                            <div class="read-more">
                                                <a href="<?php echo esc_url( $content['url'] ); ?>" class="btn"><?php echo esc_html( $btn_label ); ?></a>
                                            </div><!-- .read-more -->
                                        </div><!-- .entry-container -->
                                    </div><!-- .blog-content-wrapper -->
                                </div><!-- .wrapper -->
                            </article>
                        <?php $i++; endforeach; ?>
                    </div><!-- .blog-slider -->
                </div><!-- #blog-slider-section -->
            <?php endif ?>
        </div>
            
           
    <?php }
endif;