<?php
/**
 * Popular Destination section
 *
 * This is the template for the content of popular destination section
 *
 * @package Theme Palace
 * @subpackage Swingpress
 * @since Swingpress 1.0.0
 */
if ( ! function_exists( 'swingpress_add_popular_destination_section' ) ) :
    /**
    * Add popular destination section
    *
    *@since Swingpress 1.0.0
    */
    function swingpress_add_popular_destination_section() {
    	$options = swingpress_get_theme_options();
        // Check if destination is enabled on frontpage
        $popular_destination_enable = apply_filters( 'swingpress_section_status', true, 'popular_destination_section_enable' );

        if ( true !== $popular_destination_enable ) {
            return false;
        }
        // Get destination section details
        $section_details = array();
        $section_details = apply_filters( 'swingpress_filter_popular_destination_section_details', $section_details );

        if ( empty( $section_details ) ) {
            return;
        }

        // Render destination section now.
        swingpress_render_popular_destination_section( $section_details );
    }
endif;

if ( ! function_exists( 'swingpress_get_popular_destination_section_details' ) ) :
    /**
    * popular destination section details.
    *
    * @since Swingpress 1.0.0
    * @param array $input popular destination section details.
    */
    function swingpress_get_popular_destination_section_details( $input ) {
        $options = swingpress_get_theme_options();

        // Content type.
        $popular_destination_content_type  = $options['popular_destination_content_type'];
        $popular_destination_count = 10;
        
        $content = array();
        switch ( $popular_destination_content_type ) {
        	

            case 'post':
                $post_ids = array();

                for ( $i = 1; $i <= $popular_destination_count; $i++ ) {
                    if ( ! empty( $options['popular_destination_content_post_' . $i] ) )
                        $post_ids[] = $options['popular_destination_content_post_' . $i];
                }
                
                $args = array(
                    'post_type'         => 'post',
                    'post__in'          => ( array ) $post_ids,
                    'posts_per_page'    => absint( $popular_destination_count ),
                    'orderby'           => 'post__in',
                    'ignore_sticky_posts'   => true,
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
        }


            // Run The Loop.
            $query = new WP_Query( $args );
            if ( $query->have_posts() ) : 
                while ( $query->have_posts() ) : $query->the_post();
                    $page_post['id']        = get_the_id();
                    $page_post['title']     = get_the_title();
                    $page_post['url']       = get_the_permalink();
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
add_filter( 'swingpress_filter_popular_destination_section_details', 'swingpress_get_popular_destination_section_details' );


if ( ! function_exists( 'swingpress_render_popular_destination_section' ) ) :
  /**
   * Start destination section
   *
   * @return string destination content
   * @since Swingpress 1.0.0
   *
   */
   function swingpress_render_popular_destination_section( $content_details = array() ) {
        $options = swingpress_get_theme_options();
        $popular_destination_content_type  = $options['popular_destination_content_type'];

        if ( empty( $content_details ) ) {
            return;
        } ?>
        <div id="swingpress_popular_destination_section" class="relative page-section same-background">
            <div id="interest">
                <div class="wrapper">
                    <div class="section-header">
                        <?php if ( ! empty( $options['popular_destination_title'] ) ) : ?>
                            <h2 class="section-title"><?php echo esc_html( $options['popular_destination_title'] ); ?></h2>
                        <?php endif; ?>
                        <?php if ( ! empty( $options['popular_destination_sub_title'] ) ) : ?>
                            <p class="section-subtitle"><?php echo esc_html( $options['popular_destination_sub_title'] ); ?></p>
                        <?php endif; ?>
                    </div><!-- .section-header -->

                    <div class="interest-slider" data-slick='{"slidesToShow": 4, "slidesToScroll": 1, "infinite": false, "speed": 1000, "dots": false, "arrows":true, "autoplay": false, "draggable": true, "fade": false }'>
                        <?php foreach ( $content_details as $content ) : ?>
                            <article>
                                <div class="featured-image" style="background-image: url('<?php echo esc_url( $content['image'] ); ?>');">
                                    <?php if ( ! in_array( $popular_destination_content_type, array( 'category', 'page', 'post' ) ) ) : 
                                        $average_rating = wp_travel_get_average_rating( $content['id'] ); 
                                    ?>
                                        <div class="wp-travel-average-review" title="<?php printf( esc_attr__( 'Rated %s out of 5', 'swingpress' ), $average_rating ); ?>">
                                            <span style="width:<?php echo esc_attr( ( $average_rating / 5 ) * 100 ); ?>%">
                                                <strong itemprop="ratingValue" class="rating"><?php echo esc_html( $average_rating ); ?></strong> <?php printf( esc_html__( 'out of %1$s5%2$s', 'swingpress' ), '<span itemprop="bestRating">', '</span>' ); ?>
                                            </span>
                                        </div>
                                    <?php endif; ?>                                   
                                </div><!-- .featured-image -->

                                <div class="entry-container">
                                    <header class="entry-header">
                                        <h2 class="entry-title"><a href="<?php echo esc_url( $content['url'] ); ?>"><?php echo esc_html( $content['title'] ); ?></a></h2>
                                        <?php if ( ! in_array( $popular_destination_content_type, array( 'category', 'page', 'post' ) ) ) : ?>
                                            <span class="trip-price">                       
                                                <?php 
                                                    $trip_price = WP_Travel_Helpers_Pricings::get_price( array('trip_id'=>$content['id']) );
                                                    echo wp_travel_get_formated_price_currency( $trip_price ); 
                                                ?>
                                            </span><!-- .trip-price -->
                                        <?php endif; ?>
                                    </header><!-- .entry-header -->
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                </div><!-- .wrapper -->
            </div><!-- #interest -->
            
        </div>
       
    <?php }
endif;