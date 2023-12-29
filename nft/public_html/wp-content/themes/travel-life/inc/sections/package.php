<?php
/**
 * Package section
 *
 * This is the template for the content of package section
 *
 * @package Theme Palace
 * @subpackage Travel Life
 * @since Travel Life 1.0.0
 */
if ( ! function_exists( 'travel_life_add_package_section' ) ) :
    /**
    * Add package section
    *
    *@since Travel Life 1.0.0
    */
    function travel_life_add_package_section() {
    	$options = travel_life_get_theme_options();
        // Check if destination is enabled on frontpage
        $package_enable = apply_filters( 'travel_life_section_status', true, 'package_section_enable' );

        if ( true !== $package_enable ) {
            return false;
        }
        // Get destination section details
        $section_details = array();
        $section_details = apply_filters( 'travel_life_filter_package_section_details', $section_details );

        if ( empty( $section_details ) ) {
            return;
        }

        // Render destination section now.
        travel_life_render_package_section( $section_details );
    }
endif;

if ( ! function_exists( 'travel_life_get_package_section_details' ) ) :
    /**
    * package section details.
    *
    * @since Travel Life 1.0.0
    * @param array $input package section details.
    */
    function travel_life_get_package_section_details( $input ) {
        $options = travel_life_get_theme_options();

        // Content type.
        $package_content_type  = $options['package_content_type'];
        $package_count = 6;
        
        $content = array();
        switch ( $package_content_type ) {
        

            case 'page':
                $page_ids = array();

                for ( $i = 1; $i <= $package_count; $i++ ) {
                    if ( ! empty( $options['package_content_page_' . $i] ) )
                        $page_ids[] = $options['package_content_page_' . $i];
                }
                
                $args = array(
                    'post_type'         => 'page',
                    'post__in'          => ( array ) $page_ids,
                    'posts_per_page'    => absint( $package_count ),
                    'orderby'           => 'post__in',
                    );                    
            break;

            case 'trip':
                if ( ! class_exists( 'WP_Travel' ) )
                    return;

                $page_ids = array();

                for ( $i = 1; $i <= $package_count; $i++ ) {
                    if ( ! empty( $options['package_content_trip_' . $i] ) )
                        $page_ids[] = $options['package_content_trip_' . $i];
                }
                
                $args = array(
                    'post_type'         => 'itineraries',
                    'post__in'          => ( array ) $page_ids,
                    'posts_per_page'    => absint( $package_count ),
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
                $page_post['excerpt']   = travel_life_trim_content( 20 );
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
// destination section content details.
add_filter( 'travel_life_filter_package_section_details', 'travel_life_get_package_section_details' );


if ( ! function_exists( 'travel_life_render_package_section' ) ) :
  /**
   * Start destination section
   *
   * @return string destination content
   * @since Travel Life 1.0.0
   *
   */
   function travel_life_render_package_section( $content_details = array() ) {
        $options = travel_life_get_theme_options();
        $package_content_type  = $options['package_content_type'];

        if ( empty( $content_details ) ) {
            return;
        } ?> 
        <div id="travel_life_package_section" class="package-section relative page-section">
               <div id="top-destinations">
                <div class="wrapper">
                    <?php if ( is_customize_preview()):
                        travel_life_section_tooltip( 'package-section-class' );
                    endif; ?>
                    <div class="section-header-wrapper clear">
                        <?php if ( ! empty( $options['package_title'] ) ) : ?>
                            <div class="section-header">
                                <h2 class="section-title"><?php echo esc_html( $options['package_title'] ); ?></h2>
                            </div><!-- .section-header -->
                        <?php endif; ?>
                        <div class="destination-slider" data-slick='{"slidesToShow": 2, "slidesToScroll": 1, "infinite": true, "speed": 1000, "dots": false, "arrows":true, "autoplay": false, "draggable": true, "fade": false }'>
                            <?php foreach ( $content_details as $content ) :  ?>
                                <article class="has-post-thumbnail">

                                    <div class="featured-image" style="background-image: url('<?php echo esc_url(  $content['image'] ) ?>');">
                                        <a href="<?php echo esc_url( $content['url'] ); ?>" class="post-thumbnail-link"></a>
                                    </div><!-- .featured-image -->
                              
                                    <div class="entry-container">
                                        <header class="entry-header">
                                            <h2 class="entry-title"><a href="<?php echo esc_url( $content['url'] ); ?>"> 
                                                <a href="<?php echo esc_url( $content['url'] ); ?>">
                                                    <?php echo esc_html( $content['title'] ); ?>
                                                </a>
                                        </header>
                                        <div class="entry-content">
                                            <p><?php echo esc_html( $content['excerpt'] ); ?></p>
                                        </div><!-- .entry-content -->
                                       <?php if ( ! empty( $options['package_btn_title'] ) ) : ?>
                                            <div class="read-more">
                                                <a href="<?php echo esc_url( $content['url'] ); ?>" class="btn"><?php echo esc_html( $options['package_btn_title'] ); ?></a>
                                            </div><!-- .read-more -->
                                        <?php endif; ?>
                                    </div><!-- .entry-container -->
                                </article>

                            <?php endforeach; ?>
                        </div><!-- .section-content -->
                        <div class="slick-controls"><span></span></div>
                    </div><!-- .section-header-wrapper -->
                    
                </div><!-- .wrapper -->
            </div><!-- #recommended-hotels -->
        
        </div>
     
    <?php }
endif;