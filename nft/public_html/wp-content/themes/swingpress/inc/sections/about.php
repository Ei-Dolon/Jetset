<?php
/**
 * About section
 *
 * This is the template for the content of about section
 *
 * @package Theme Palace
 * @subpackage Swingpress
 * @since Swingpress 1.0.0
 */
if ( ! function_exists( 'swingpress_add_about_section' ) ) :
    /**
    * Add about section
    *
    *@since Swingpress 1.0.0
    */
    function swingpress_add_about_section() {
    	$options = swingpress_get_theme_options();
        // Check if about is enabled on frontpage
        $about_enable = apply_filters( 'swingpress_section_status', true, 'about_section_enable' );

        if ( true !== $about_enable ) {
            return false;
        }
        // Get about section details
        $section_details = array();
        $section_details = apply_filters( 'swingpress_filter_about_section_details', $section_details );

        if ( empty( $section_details ) ) {
            return;
        }

        // Render about section now.
        swingpress_render_about_section( $section_details );
    }
endif;

if ( ! function_exists( 'swingpress_get_about_section_details' ) ) :
    /**
    * about section details.
    *
    * @since Swingpress 1.0.0
    * @param array $input about section details.
    */
    function swingpress_get_about_section_details( $input ) {
        $options = swingpress_get_theme_options();
        
        $content = array();        	


        $page_id = ! empty( $options['about_content_page'] ) ? $options['about_content_page'] : '';
        $args = array(
            'post_type'         => 'page',
            'page_id'           => $page_id,
            'posts_per_page'    => 1,
            );                    
         

        // Run The Loop.
        $query = new WP_Query( $args );
        if ( $query->have_posts() ) : 
            while ( $query->have_posts() ) : $query->the_post();
                $page_post['title']     = get_the_title();
                $page_post['excerpt']   = swingpress_trim_content( 35 );
                $page_post['url']       = get_the_permalink();
                $page_post['image']     = has_post_thumbnail() ? get_the_post_thumbnail_url( get_the_id(), 'medium_large' ) : get_template_directory_uri().'./assets/uploads/no-featured-image-590x650.jpg';

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
// about section content details.
add_filter( 'swingpress_filter_about_section_details', 'swingpress_get_about_section_details' );


if ( ! function_exists( 'swingpress_render_about_section' ) ) :
  /**
   * Start about section
   *
   * @return string about content
   * @since Swingpress 1.0.0
   *
   */
   function swingpress_render_about_section( $content_details = array() ) {
        $options = swingpress_get_theme_options();
        $destinations = ! empty( $options['about_content_destination'] ) ? $options['about_content_destination'] : array();
        $product_cats = ! empty( $options['about_content_product_category'] ) ? $options['about_content_product_category'] : array();

        if ( empty( $content_details ) ) {
            return;
        } 

        foreach ( $content_details as $content ) : ?>
            <div id="swingpress_about_section" class="relative page-section same-background">
                <div id="about-section" >
                    <div class="wrapper">
                        <article class="<?php echo ! empty( $content['image'] ) ? 'has' : 'no'; ?>-post-thumbnail">
                            <div class="featured-image">
                                <?php if ( ! empty( $content['image'] ) ) : ?>
                                <img src="<?php echo esc_url( $content['image'] ); ?>">
                                <?php endif; ?>
                                <?php if ( $options['about_video_url'] ): ?>
                                    <div class="video-button">
                                        <a href="<?php echo esc_url( $options['about_video_url'] ); ?>" class="popup-youtube">
                                            <svg viewBox="0 0 511.449 511.449">
                                                <path d="m436.508 74.941c-99.913-99.913-261.639-99.927-361.566 0-99.914 99.912-99.93 261.64 0 361.567 99.913 99.913 261.639 99.927 361.566 0 99.914-99.911 99.929-261.64 0-361.567zm-103.891 197.36-96 74.667c-13.642 10.609-33.893.986-33.893-16.577v-149.333c0-17.439 20.119-27.292 33.893-16.577l96 74.667c10.809 8.408 10.796 24.755 0 33.153z"></path>
                                           </svg>
                                        </a>
                                    </div>
                                <?php endif ?>
                                
                            </div><!-- .featured-image -->
                          
                            <div class="entry-container">
                                <header class="section-header">                              
                                    <h2 class="entry-title"><?php echo esc_html( $content['title'] ); ?></h2>
                                </header>

                                <div class="section-content">
                                    <p><?php echo wp_kses_post( $content['excerpt'] ); ?></p>
                                </div><!-- .entry-content -->
                                
                                <div class="counter-wrapper clear">
                                    <?php for( $i=1; $i <= 4 ; $i++ ): ?>
                                        <?php if ( !empty( $options['about_counter_count_'.$i] ) ): ?>
                                            <div class="counter-item">
                                                <h3 class="counter-value"><?php echo esc_html( $options['about_counter_count_'.$i] ); ?></h3>
                                                <span class="counter-title"><?php echo esc_html( $options['about_counter_label_'.$i] ); ?></span>
                                            </div><!-- .counter-item -->
                                        <?php endif ?>
                                    <?php endfor; ?>
                                </div>

                                <?php if ( ! empty( $content['url'] ) && ! empty( $options['about_btn_title'] ) ) : ?>
                                    <div class="read-more">
                                        <a href="<?php echo esc_url( $content['url'] ); ?>" class="btn">
                                            <span class="screen-reader-text"><?php echo esc_html( $content['title'] ); ?></span>
                                            <?php echo esc_html( $options['about_btn_title'] ); ?>
                                        </a>
                                    </div><!-- .read-more -->
                                <?php endif; ?>
                            </div><!-- .entry-container -->
                        </article>
                    </div><!-- .wrapper -->
                </div><!-- #about-us -->

            </div>
          
        <?php endforeach;
    }
endif;

