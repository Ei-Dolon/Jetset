<?php
/**
 * Service section
 *
 * This is the template for the content of service section
 *
 * @package Theme Palace
 * @subpackage Swingpress
 * @since Swingpress 1.0.0
 */
if ( ! function_exists( 'swingpress_add_service_section' ) ) :
    /**
    * Add service section
    *
    *@since Swingpress 1.0.0
    */
    function swingpress_add_service_section() {
        $options = swingpress_get_theme_options();
        // Check if service is enabled on frontpage
        $service_enable = apply_filters( 'swingpress_section_status', true, 'service_section_enable' );

        if ( true !== $service_enable ) {
            return false;
        }
        // Get service section details
        $section_details = array();
        $section_details = apply_filters( 'swingpress_filter_service_section_details', $section_details );

        if ( empty( $section_details ) ) {
            return;
        }

        // Render service section now.
        swingpress_render_service_section( $section_details );
    }
endif;

if ( ! function_exists( 'swingpress_get_service_section_details' ) ) :
    /**
    * service section details.
    *
    * @since Swingpress 1.0.0
    * @param array $input service section details.
    */
    function swingpress_get_service_section_details( $input ) {
        $options = swingpress_get_theme_options();

        $service_count  = 3;
        
        $content = array();
            
        $page_ids = array();

        for ( $i = 1; $i <= $service_count; $i++ ) {
            if ( ! empty( $options['service_content_page_' . $i] ) )
                $page_ids[] = $options['service_content_page_' . $i];
        }
        
        $args = array(
            'post_type'         => 'page',
            'post__in'          => ( array ) $page_ids,
            'posts_per_page'    => $service_count,
            'orderby'           => 'post__in',
            );                    

        // Run The Loop.
        $query = new WP_Query( $args );
        if ( $query->have_posts() ) : 
            while ( $query->have_posts() ) : $query->the_post();
                $page_post['title']     = get_the_title();
                $page_post['url']       = get_the_permalink();
                $page_post['excerpt']   = swingpress_trim_content( 15 );
                $page_post['image']     = has_post_thumbnail() ? get_the_post_thumbnail_url( get_the_id(), 'full' ) : '';

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
// service section content details.
add_filter( 'swingpress_filter_service_section_details', 'swingpress_get_service_section_details' );


if ( ! function_exists( 'swingpress_render_service_section' ) ) :
  /**
   * Start service section
   *
   * @return string service content
   * @since Swingpress 1.0.0
   *
   */
   function swingpress_render_service_section( $content_details = array() ) {
        $options = swingpress_get_theme_options();
        $i = 1;        

        if ( empty( $content_details ) ) {
            return;
        } 
        ?>
        <div id="swingpress_service_section" class="relative page-section">
            <div id="service-section" >
                <div class="wrapper">
                    <div class="section-header">
                        <?php if ( !empty( $options['service_title'] ) ): ?>
                            <h2 class="section-title"><?php echo esc_html( $options['service_title'] ); ?></h2>
                        <?php endif ?>
                       
                        <?php if ( !empty( $options['service_sub_title'] ) ): ?>
                            <p class="section-subtitle"><?php echo esc_html( $options['service_sub_title'] ); ?></p>
                        <?php endif ?>
                        
                    </div><!-- .section-header -->

                    <div class="section-content clear col-3">
                    <?php foreach ( $content_details as $i => $content ) : ?>

                        <article>
                            <div class="service-item">
                               
                                <div class="entry-container">
                                    <?php if ( $options['service_section_icon_enable'] ): ?>
                                        <div class="icon-container">
                                            <a href="<?php echo esc_url( $content['url'] ); ?>">
                                                <i class="fa <?php echo ! empty( $options['service_content_icon_' . ($i+1)] ) ? esc_attr( $options['service_content_icon_' . ($i+1)] ) : 'fa-cogs'; ?>"></i>
                                            </a>
                                        </div><!-- .service-icon -->
                                    <?php endif ?>                                

                                    <header class="entry-header">
                                        <h2 class="entry-title"><a href="<?php echo esc_url( $content['url'] ); ?>"><?php echo esc_html( $content['title'] ); ?></a></h2>
                                    </header>

                                    <div class="entry-content">
                                        <p><?php echo wp_kses_post( $content['excerpt'] ); ?></p>
                                    </div><!-- .entry-content -->
                                </div><!-- .entry-container -->
                            </div><!-- .service-item -->
                        </article>
                        <?php endforeach; ?>

                    </div><!-- .section-content -->
                </div><!-- .wrapper -->
            </div><!-- #services -->  
        </div> 
        
    <?php }
endif;