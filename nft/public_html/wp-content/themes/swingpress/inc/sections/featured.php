<?php
/**
 * Featured section
 *
 * This is the template for the content of featured section
 *
 * @package Theme Palace
 * @subpackage Swingpress
 * @since Swingpress 1.0.0
 */
if ( ! function_exists( 'swingpress_add_featured_section' ) ) :
    /**
    * Add featured section
    *
    *@since Swingpress 1.0.0
    */
    function swingpress_add_featured_section() {
    	$options = swingpress_get_theme_options();
        // Check if destination is enabled on frontpage
        $featured_enable = apply_filters( 'swingpress_section_status', true, 'featured_section_enable' );

        if ( true !== $featured_enable ) {
            return false;
        }
        // Get destination section details
        $section_details = array();
        $section_details = apply_filters( 'swingpress_filter_featured_section_details', $section_details );

        if ( empty( $section_details ) ) {
            return;
        }
        
        // Render destination section now.
        swingpress_render_featured_section( $section_details );
    }
endif;

if ( ! function_exists( 'swingpress_get_featured_section_details' ) ) :
    /**
    * featured section details.
    *
    * @since Swingpress 1.0.0
    * @param array $input featured section details.
    */
    function swingpress_get_featured_section_details( $input ) {
        $options = swingpress_get_theme_options();

        // Content type.
        $featured_content_type  = $options['featured_content_type'];
        $featured_count = 3;
        
        $content = array();
        switch ( $featured_content_type ) {
        	
            case 'category':
                for ( $i = 1; $i <= $featured_count; $i++ ) {

                    if ( ! empty( $options['featured_content_category_' . $i] ) ){
                        $page_post['id']      = $options['featured_content_category_' . $i];
                        $terms = get_term_by( 'id',  $page_post['id'], 'category' );

                        if ( ! empty( $terms ) && !is_wp_error($terms)) :
                            $page_post['title']   = $terms->name;
                            $page_post['url']     = get_category_link( $page_post['id'] );
                            $page_post['count']   = $terms->count;
                            $page_post['image'] = ! empty( $options['featured_background_image_' . $i] ) ? $options['featured_background_image_' . $i] : get_template_directory_uri() . '/assets/uploads/no-featured-image-600x450.jpg';
                        endif;
                    }
                    if ( ! empty(  $options['featured_content_category_' . $i] ) )
                        array_push( $content, $page_post );
                }                     
            break;

            case 'trip-types': // trip-types
                if ( ! class_exists( 'WP_Travel' ) )
                    return;

                for ( $i = 1; $i <= $featured_count; $i++ ) {
                    if ( ! empty( $options['featured_content_trip_types_' . $i] ) ){
                        $page_post['id']      = $options['featured_content_trip_types_' . $i];
                        $cat_image = get_term_meta( $page_post['id'], 'wp_travel_trip_type_image_id', true);
                        
                        $terms = get_term_by( 'id',  $page_post['id'], 'itinerary_types' );
                        
                        if ( ! empty( $terms ) && !is_wp_error($terms)) :
                            $page_post['title']   = $terms->name;
                            $page_post['url']     = get_term_link( $page_post['id'], 'itinerary_types' );
                            $page_post['count']   = $terms->count;
                            $page_post['image'] = ! empty( $cat_image ) ? wp_get_attachment_url( $cat_image ) : get_template_directory_uri() . '/assets/uploads/no-featured-image-600x450.jpg';
                        endif;
                    }
                    if ( ! empty(  $options['featured_content_trip_types_' . $i] ) )
                        array_push( $content, $page_post );
                }
                     
            break;
        }

            
        if ( ! empty( $content ) ) {
            $input = $content;
        }
        return $input;
    }
endif;
// destination section content details.
add_filter( 'swingpress_filter_featured_section_details', 'swingpress_get_featured_section_details' );


if ( ! function_exists( 'swingpress_render_featured_section' ) ) :
  /**
   * Start destination section
   *
   * @return string destination content
   * @since Swingpress 1.0.0
   *
   */
   function swingpress_render_featured_section( $content_details = array() ) {
        $options = swingpress_get_theme_options();
        $featured_content_type  = $options['featured_content_type'];

        if ( empty( $content_details ) ) {
            return;
        } ?>
        <div id="swingpress_featured_section" class="page-section same-background">
            <div id="featured">
                <div class="wrapper">
                    <div class="section-header">
                        <h2 class="section-title">Travel anywhere</h2>
                        <p class="section-subtitle">Featured activities</p>
                    </div><!-- .section-header -->
                    <div class="section-content col-3 clear">
                        <?php foreach ( $content_details as $content ) : ?>
                            <article>
                                <div class="featured-image" style="background-image: url('<?php echo esc_url( $content['image'] ); ?>');">
                                </div>

                                <div class="entry-container">
                                    <header class="entry-header">
                                        <h2 class="entry-title"><a href="<?php echo esc_url($content['url']); ?>"><?php echo esc_html($content['title']); ?></a></h2>
                                        <?php if (  $options['featured_content_type'] !== 'category' ): ?>
                                            <p><?php  echo sprintf('<p>%d&nbsp;%s</p>',esc_html($content['count']), __('Packages','swingpress')); ?></p>
                                            <?php else: ?>
                                                <p><?php  echo sprintf('<p>%d&nbsp;%s</p>',esc_html($content['count']), __('Posts','swingpress')); ?></p>
                                        <?php endif ?>
                                    </header><!-- .entry-header -->
                                </div><!-- .entry-container -->
                            </article>
                        <?php endforeach; ?>
                    </div><!-- .section-content -->
                </div><!-- .wrapper -->
            </div><!-- #featured-destinations -->
        </div>
    <?php }
endif;