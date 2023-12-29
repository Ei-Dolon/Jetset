<?php
/**
 * Popular Destination section
 *
 * This is the template for the content of popular destination section
 *
 * @package Theme Palace
 * @subpackage Travel Life
 * @since Travel Life 1.0.0
 */
if ( ! function_exists( 'travel_life_add_popular_destination_section' ) ) :
    /**
    * Add popular destination section
    *
    *@since Travel Life 1.0.0
    */
    function travel_life_add_popular_destination_section() {
    	$options = travel_life_get_theme_options();
        // Check if destination is enabled on frontpage
        $popular_destination_enable = apply_filters( 'travel_life_section_status', true, 'popular_destination_section_enable' );

        if ( true !== $popular_destination_enable ) {
            return false;
        }
        // Get destination section details
        $section_details = array();
        $section_details = apply_filters( 'travel_life_filter_popular_destination_section_details', $section_details );

        if ( empty( $section_details ) ) {
            return;
        }

        // Render destination section now.
        travel_life_render_popular_destination_section( $section_details );
    }
endif;

if ( ! function_exists( 'travel_life_get_popular_destination_section_details' ) ) :
    /**
    * popular destination section details.
    *
    * @since Travel Life 1.0.0
    * @param array $input popular destination section details.
    */
    function travel_life_get_popular_destination_section_details( $input ) {
        $options = travel_life_get_theme_options();

        // Content type.
        $popular_destination_content_type  = $options['popular_destination_content_type'];
        $popular_destination_count = 6;
        
        $content = array();
        switch ( $popular_destination_content_type ) {
        	

            case 'page':
                $page_ids = array();

                for ( $i = 1; $i <= $popular_destination_count; $i++ ) {
                    if ( ! empty( $options['popular_destination_content_page_' . $i] ) )
                        $page_ids[] = $options['popular_destination_content_page_' . $i];
                }
                
                $args = array(
                    'post_type'         => 'page',
                    'post__in'          => ( array ) $page_ids,
                    'posts_per_page'    => absint( $popular_destination_count ),
                    'orderby'           => 'post__in',
                    );                    
            break;

            case 'trip':
                if ( ! class_exists( 'WP_Travel' ) )
                    return;

                $page_ids = array();

                for ( $i = 1; $i <= $popular_destination_count; $i++ ) {
                    if ( ! empty( $options['popular_destination_content_trip_' . $i] ) )
                        $page_ids[] = $options['popular_destination_content_trip_' . $i];
                }
                
                $args = array(
                    'post_type'         => 'itineraries',
                    'post__in'          => ( array ) $page_ids,
                    'posts_per_page'    => absint( $popular_destination_count ),
                    'orderby'           => 'post__in',
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
                    $page_post['excerpt']   = travel_life_trim_content( 30 );
                    $page_post['image']  	= has_post_thumbnail() ? get_the_post_thumbnail_url( get_the_id(), 'post-thumbnail' ) : get_template_directory_uri() . '/assets/uploads/no-featured-image-590x650.jpg';

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
// destination section content details.
add_filter( 'travel_life_filter_popular_destination_section_details', 'travel_life_get_popular_destination_section_details' );


if ( ! function_exists( 'travel_life_render_popular_destination_section' ) ) :
  /**
   * Start destination section
   *
   * @return string destination content
   * @since Travel Life 1.0.0
   *
   */
   function travel_life_render_popular_destination_section( $content_details = array() ) {
        $options = travel_life_get_theme_options();
        $popular_destination_content_type  = $options['popular_destination_content_type'];

        if ( empty( $content_details ) ) {
            return;
        } ?>
        <div id="travel_life_popular_destination_section" class="popular-destination relative page-section">
             <div id="best-destinations">
                <div class="wrapper">
                    <?php if ( is_customize_preview()):
                        travel_life_section_tooltip( 'popular-destination-class' );
                    endif; ?>
                    <?php if (! empty( $options['popular_destination_title'] ) ): ?>
                        <div class="section-header">
                            <h2 class="section-title"><?php echo esc_html($options['popular_destination_title']); ?></h2>
                        </div><!-- .section-header -->
                    <?php endif ?>
                    <div class="section-content" data-slick='{"slidesToShow": 3, "slidesToScroll": 1, "infinite": true, "speed": 1000, "dots": false, "arrows":true, "autoplay": false, "draggable": true, "fade": false }'>
                        <?php foreach ( $content_details as $content ) : ?>
                            <article>
                                <div class="featured-image" style="background-image: url('<?php echo esc_url($content['image']) ?>');">
                                </div><!-- .featured-image -->

                                <div class="entry-container">
                                    <header class="entry-header">
                                        <h2 class="entry-title"><a href="<?php echo esc_url( $content['url']) ?>"><?php echo esc_html( $content['title'] ); ?></a></h2>
                                    </header>

                                    <div class="entry-content">
                                        <p><?php echo esc_html($content['excerpt']); ?></p>
                                    </div><!-- .entry-content -->
                                    <?php if (!empty($options['popular_destination_read_more'])): ?>
                                        <div class="more-link">
                                            <a href="<?php echo esc_url($content['url']); ?>"><?php echo esc_html($options['popular_destination_read_more']) ?>
                                                <svg viewBox="0 0 292.359 292.359">
                                                    <path d="M222.979,133.331L95.073,5.424C91.456,1.807,87.178,0,82.226,0c-4.952,0-9.233,1.807-12.85,5.424
                                                    c-3.617,3.617-5.424,7.898-5.424,12.847v255.813c0,4.948,1.807,9.232,5.424,12.847c3.621,3.617,7.902,5.428,12.85,5.428
                                                    c4.949,0,9.23-1.811,12.847-5.428l127.906-127.907c3.614-3.613,5.428-7.897,5.428-12.847
                                                    C228.407,141.229,226.594,136.948,222.979,133.331z"/>
                                                </svg>
                                            </a>
                                        </div><!-- .more-link -->
                                    <?php endif; ?>
                                </div><!-- .entry-container -->
                            </article>
                        <?php endforeach; ?>
                    </div><!-- .section-content -->
                    
                    <div class="read-more">
                        <?php if (!empty($options['popular_destination_btn_title']) && !empty($options['popular_destination_btn_url'])): ?>
                            <a href="<?php echo esc_url($options['popular_destination_btn_url']) ?>" class="btn"><?php echo esc_html($options['popular_destination_btn_title']) ?></a>
                        <?php endif; ?>
                    </div><!-- .read-more -->
                    
                </div><!-- .wrapper -->
            </div><!-- #best-destinations -->        
        </div>
       
    <?php }
endif;