<?php
/**
 * Slider section
 *
 * This is the template for the content of slider section
 *
 * @package Theme Palace
 * @subpackage Next Travel
 * @since Next Travel 1.0.0
 */
if ( ! function_exists( 'next_travel_add_slider_section' ) ) :
    /**
    * Add slider section
    *
    *@since Next Travel 1.0.0
    */
function next_travel_add_slider_section() {
    $options = next_travel_get_theme_options();

        // Check if slider is enabled on frontpage
    $slider_enable = apply_filters( 'next_travel_section_status', true, 'slider_section_enable' );

    if ( true !== $slider_enable ) {
        return false;
    }
        // Get slider section details
    $section_details = array();
    $section_details = apply_filters( 'next_travel_filter_slider_section_details', $section_details );
    if ( empty( $section_details ) ) {
        return;
    }
    next_travel_render_slider_section( $section_details );
}
endif;

if ( ! function_exists( 'next_travel_get_slider_section_details' ) ) :
    /**
    * slider section details.
    *
    * @since Next Travel 1.0.0
    * @param array $input slider section details.
    */
    function next_travel_get_slider_section_details( $input ) {
        $options = next_travel_get_theme_options();
        
        $content = array();
        $page_ids = array();

        for ( $i = 1; $i <= 3; $i++ ) {
            if ( ! empty( $options['slider_content_page_' . $i] ) )
                $page_ids[] = $options['slider_content_page_' . $i];
        }

        $args = array(
            'post_type'         => 'page',
            'post__in'          => ( array ) $page_ids,
            'posts_per_page'    => absint( 3 ),
            'orderby'           => 'post__in',
            );

            // Run The Loop.
            $query = new WP_Query( $args );
            if ( $query->have_posts() ) : 
                while ( $query->have_posts() ) : $query->the_post();
                    $page_post['id']        = get_the_id();
                    $page_post['title']     = get_the_title();
                    $page_post['url']       = get_the_permalink();
                    $page_post['excerpt']   = next_travel_trim_content( 30 );
                    $page_post['image']     = has_post_thumbnail() ? get_the_post_thumbnail_url( get_the_id(), 'full' ) : get_template_directory_uri() . '/assets/uploads/no-featured-image-590x650.jpg';

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
add_filter( 'next_travel_filter_slider_section_details', 'next_travel_get_slider_section_details' );


if ( ! function_exists( 'next_travel_render_slider_section' ) ) :
  /**
   * Start slider section
   *
   * @return string slider content
   * @since Next Travel 1.0.0
   *
   */
   function next_travel_render_slider_section( $content_details = array() ) {
        $options = next_travel_get_theme_options();

        if ( empty( $content_details ) ) {
            return;
        } ?>
        <div id="next_travel_slider_section">
            <div id="featured-slider-section" class="slider-section">
                <div class="featured-slider" data-slick='{"slidesToShow": 1, "slidesToScroll": 1, "infinite": true, "speed": 1000, "dots": true, "arrows":false, "autoplay": true, "draggable": true, "fade": true, "adaptiveHeight": true }'>

                    <?php foreach ($content_details as $content) : ?>

                        <article style="background-image:url('<?php echo esc_url($content['image'])?>');">
                            <div class="overlay"></div>
                            <div class="wrapper">
                                <div class="featured-content-wrapper">
                                    <div class="entry-container">
                                        <header class="entry-header">
                                            <h2 class="entry-title"><a href="<?php echo esc_url($content['url']); ?>"><?php echo esc_html($content['title']); ?></a></h2>
                                        </header>

                                        <div class="entry-content">
                                            <p><?php echo esc_html($content['excerpt']); ?></p>
                                        </div><!-- .entry-content -->
                                    </div><!-- .entry-container -->
                                    <?php if ( class_exists('WP_Travel') ): ?>
                                        <div class="travel-search-section">
                                            <div class="wp-travel-search">
                                                <?php wptravel_search_form(); ?>
                                            </div>
                                        </div><!-- .travel-search-section -->
                                    <?php endif ?>
                                    
                                </div><!-- .featured-content-wrapper -->
                            </div><!-- .wrapper -->
                        </article>

                    <?php endforeach; ?>

                </div><!-- .featured-slider -->
            </div><!-- #featured-slider-section -->  
        </div>
            

    <?php }
endif;   