<?php
/**
 * Gallery Slider section
 *
 * This is the template for the content of gallery_slider section
 *
 * @package Theme Palace
 * @subpackage Next Travel
 * @since Next Travel 1.0.0
 */
if ( ! function_exists( 'next_travel_add_gallery_slider_section' ) ) :
    /**
    * Add gallery_slider section
    *
    *@since Next Travel 1.0.0
    */
function next_travel_add_gallery_slider_section() {
    $options = next_travel_get_theme_options();

        // Check if gallery_slider is enabled on frontpage
    $gallery_slider_enable = apply_filters( 'next_travel_section_status', true, 'gallery_slider_section_enable' );

    if ( true !== $gallery_slider_enable ) {
        return false;
    }
        // Get gallery_slider section details
    $section_details = array();
    $section_details = apply_filters( 'next_travel_filter_gallery_slider_section_details', $section_details );
    if ( empty( $section_details ) ) {
        return;
    }
    next_travel_render_gallery_slider_section( $section_details );
}
endif;

if ( ! function_exists( 'next_travel_get_gallery_slider_section_details' ) ) :
    /**
    * gallery_slider section details.
    *
    * @since Next Travel 1.0.0
    * @param array $input gallery_slider section details.
    */
    function next_travel_get_gallery_slider_section_details( $input ) {
        $options = next_travel_get_theme_options();

        
        $gallery_slider_content_type  = $options['gallery_slider_content_type']; // Gallery Slider Content type.Gallery Slider Post Count
        
        $content = array();
        switch ( $gallery_slider_content_type ) {
        	
            case 'page':
                $page_ids = array();

                for ( $i = 1; $i <= 4; $i++ ) {
                    if ( ! empty( $options['gallery_slider_content_page_' . $i] ) )
                        $page_ids[] = $options['gallery_slider_content_page_' . $i];
                }
                
                $args = array(
                    'post_type'         => 'page',
                    'post__in'          => ( array ) $page_ids,
                    'posts_per_page'    => absint( 4 ),
                    'orderby'           => 'post__in',
                    );                    
            break;
            
            case 'trip-types': // trip-types
            if ( ! class_exists( 'WP_Travel' ) )
                return;

            $cat_id = ! empty( $options['gallery_slider_content_trip_types'] ) ? $options['gallery_slider_content_trip_types'] : array();

            $args = array(
                'post_type'      => 'itineraries',
                'tax_query' => array(
                    array(
                        'taxonomy' => 'itinerary_types',
                        'field'    => 'id',
                        'terms'    => $cat_id,
                        ),
                    ),
                'posts_per_page'  => absint( 4 ),
                );

            $content['taxonomy'] = 'itinerary_types';
            break;

            default:
            break;
        }

            $content['details'] = array();
            // Run The Loop.
            $query = new WP_Query( $args );
            $i = 1;
            if ( $query->have_posts() ) : 
                while ( $query->have_posts() ) : $query->the_post();
                    $page_post['id']        = get_the_id();
                    $page_post['title']     = get_the_title();
                    $page_post['url']       = get_the_permalink();
                    $page_post['image']  	= has_post_thumbnail() ? get_the_post_thumbnail_url( get_the_id(), 'full' ) : '';
                    $page_post['subtitle']    = ! empty( $options['gallery_slider_subtitle_' . $i] ) ? $options['gallery_slider_subtitle_' . $i] : '';

                    // Push to the main array.
                    array_push( $content['details'], $page_post );
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

// gallery_slider section content details.
add_filter( 'next_travel_filter_gallery_slider_section_details', 'next_travel_get_gallery_slider_section_details' );


if ( ! function_exists( 'next_travel_render_gallery_slider_section' ) ) :
  /**
   * Start gallery_slider section
   *
   * @return string gallery_slider content
   * @since Next Travel 1.0.0
   *
   */
   function next_travel_render_gallery_slider_section( $content_details = array() ) {
        $options = next_travel_get_theme_options();

        if ( empty( $content_details ) ) {
            return;
        } ?>
        <div id="next_travel_gallery_slider_section">
            <div id="gallery-slider-section" class="relative page-section same-background">
                <div class="wrapper">
                <div class="gallery-slider" data-slick='{"slidesToShow": 3, "slidesToScroll": 1, "infinite": true, "speed": 1000, "dots": false, "arrows":true, "autoplay": true, "draggable": true, "fade": false }'>

                    <?php $i = 1; foreach ($content_details['details'] as $content) : ?>

                        <article style="background-image: url('<?php echo esc_url($content['image'])?>');">
                            <div class="entry-container">
                                <div class="section-header">
                                <?php if( !empty( $content['subtitle'] ) ): ?>
                                    <h3 class="section-subtitle"><?php echo esc_html($content['subtitle']); ?></h3>
                                <?php endif; ?>
                                    <h2 class="section-title"><?php echo esc_html($content['title']); ?></h2>
                                </div><!-- .section-header -->

                                <?php if( !empty( $options['gallery_slider_btn_title'] ) ): ?>
                                <div class="read-more">
                                    <a href="<?php echo esc_url($content['url']); ?>" class="btn"><?php echo esc_html( $options['gallery_slider_btn_title'] ); ?></a>
                                </div><!-- .read-more -->
                            <?php endif; ?>
                            </div><!-- .entry-container -->
                        </article>

                    <?php $i++; endforeach; ?>

                    </div><!-- .gallery-slider -->
                </div><!-- .wrapper -->
            </div><!-- #gallery-slider-section -->

        </div>
        
    <?php }
endif;   