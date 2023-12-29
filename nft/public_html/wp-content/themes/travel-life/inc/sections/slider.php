<?php
/**
 * Slider section
 *
 * This is the template for the content of slider section
 *
 * @package Theme Palace
 * @subpackage Travel Life
 * @since Travel Life 1.0.0
 */
if ( ! function_exists( 'travel_life_add_slider_section' ) ) :
    /**
    * Add slider section
    *
    *@since Travel Life 1.0.0
    */
    function travel_life_add_slider_section() {
    	$options = travel_life_get_theme_options();
        // Check if slider is enabled on frontpage
        $slider_enable = apply_filters( 'travel_life_section_status', true, 'slider_section_enable' );

        if ( true !== $slider_enable ) {
            return false;
        }
        // Get slider section details
        $section_details = array();
        $section_details = apply_filters( 'travel_life_filter_slider_section_details', $section_details );

        if ( empty( $section_details ) ) {
            return;
        }

        // Render slider section now.
        travel_life_render_slider_section( $section_details );
    }
endif;

if ( ! function_exists( 'travel_life_get_slider_section_details' ) ) :
    /**
    * slider section details.
    *
    * @since Travel Life 1.0.0
    * @param array $input slider section details.
    */
    function travel_life_get_slider_section_details( $input ) {
        $options = travel_life_get_theme_options();

        $slider_count = 3;
        
        $content = array();
     
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
  
        // Run The Loop.
        $query = new WP_Query( $args );
        if ( $query->have_posts() ) : 
            while ( $query->have_posts() ) : $query->the_post();
                $page_post['id']        = get_the_id();
                $page_post['title']     = get_the_title();
                $page_post['url']       = get_the_permalink();
                $page_post['excerpt']   = travel_life_trim_content( 15 );
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
add_filter( 'travel_life_filter_slider_section_details', 'travel_life_get_slider_section_details' );


if ( ! function_exists( 'travel_life_render_slider_section' ) ) :
  /**
   * Start slider section
   *
   * @return string slider content
   * @since Travel Life 1.0.0
   *
   */
   function travel_life_render_slider_section( $content_details = array() ) {
        $options = travel_life_get_theme_options();
        $btn_label = ! empty( $options['slider_btn_label'] ) ? $options['slider_btn_label'] : esc_html__( 'Learn More', 'travel-life' );
        // Content type.
        $slider_content_type  = $options['slider_content_type'];


        if ( empty( $content_details ) ) {
            return;
        } ?>
        <div id="travel_life_slider_section" class="slider-section">
             <div id="featured-slider-section">
                <div class="featured-slider "  data-slick='{"slidesToShow": 1, "slidesToScroll": 1, "infinite": true, "speed": 1000, "dots": true, "arrows":false, "autoplay": <?php echo $options['slider_autoplay'] ? 'true' : 'false'; ?>, "draggable": true, "fade": true, "adaptiveHeight": true }'>
                    <?php foreach ( $content_details as $content ) : ?>
                        <article style="background-image:url('<?php echo esc_url( $content['image'] ); ?>');">
                            <div class="overlay"></div>
                            <div class="wrapper">
                                <?php if ( is_customize_preview()):
                                    travel_life_section_tooltip( 'featured-slider-section' );
                                endif; ?>
                                <div class="featured-content-wrapper">
                                    <header class="entry-header">
                                        <h2 class="entry-title"><a href="<?php echo esc_url( $content['url'] ); ?>"><?php echo esc_html( $content['title'] ); ?></a></h2>
                                    </header>

                                    <div class="entry-content">
                                        <p><?php echo esc_html( $content['excerpt'] ); ?></p>
                                    </div><!-- .entry-content-->

                                    <div class="read-more">
                                        <a href="<?php echo esc_url( $content['url'] ); ?>" class="btn btn-fill">
                                            <span class="screen-reader-text"><?php echo esc_html( $content['title'] ); ?></span>
                                            <?php echo esc_html( $btn_label ); ?>
                                        </a>
                                    </div><!-- .read-more -->
                                </div><!-- .featured-content-wrapper -->
                            </div><!-- .wrapper -->
                            <?php if ( $options['slider_section_enable_clould'] == true ): ?>
                                <img src="<?php echo esc_url( get_template_directory_uri().'/assets/uploads/cloud.png' ); ?>" class="cloud-image" >
                            <?php endif ?>
                            
                        </article>
                    <?php endforeach; ?>
                </div><!-- .featured-slider -->  
            </div><!-- #featured-slider-section -->     
        </div>
           
       
    <?php }
endif;