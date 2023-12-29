<?php
/**
 * About section
 *
 * This is the template for the content of about section
 *
 * @package Theme Palace
 * @subpackage Next Travel
 * @since Next Travel 1.0.0
 */
if ( ! function_exists( 'next_travel_add_about_section' ) ) :
    /**
    * Add about section
    *
    *@since Next Travel 1.0.0
    */
    function next_travel_add_about_section() {
        $options = next_travel_get_theme_options();
        
        // Check if About is enabled on frontpage
        $about_enable = apply_filters( 'next_travel_section_status', true, 'about_section_enable' );

        if ( true !== $about_enable ) {
            return false;
        }
        $section_details = array();

        // Get About section details
        $section_details = apply_filters( 'next_travel_filter_about_section_details', $section_details );

        if ( empty( $section_details ) ) {
            return;
        }
        
        next_travel_render_about_section( $section_details );

    }
endif;

if ( ! function_exists( 'next_travel_get_about_section_details' ) ) :
    /**
    * About section details.
    *
    * @since Next Travel 1.0.0
    * @param array $input about section details.
    */
    function next_travel_get_about_section_details( $input ) {
        $options = next_travel_get_theme_options();
        
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
                    $page_post['excerpt']   = next_travel_trim_content( 30 );
                    $page_post['url']       = get_the_permalink();
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
// About section content details.
add_filter( 'next_travel_filter_about_section_details', 'next_travel_get_about_section_details' );


if ( ! function_exists( 'next_travel_render_about_section' ) ) :
  /**
   * Start About section
   *
   * @return string About content
   * @since Next Travel 1.0.0
   *
   */
   function next_travel_render_about_section( $content_details = array() ) {
        $options = next_travel_get_theme_options();
        $about_sub_title = ! empty( $options['about_sub_title'] ) ? $options['about_sub_title'] : '';
        $about_btn_title = ! empty( $options['about_btn_title'] ) ? $options['about_btn_title'] : '';

        $content = $content_details[0];
        if ( empty( $content_details ) ) {
            return;
        } 
        ?>
        <div id="next_travel_about_section">
            <div id="about-us" class="relative page-section same-background">
                <div class="wrapper">
                    <article class="has-post-thumbnail">
                        <?php if( !empty( $content['image'] ) ): ?>
                            <div class="featured-image" style="background-image: url('<?php echo esc_url($content['image']);?>');">
                                <a href="<?php echo esc_url($content['url']); ?>" class="post-thumbnail-link"></a>
                            </div><!-- .featured-image -->
                        <?php endif; ?>

                        <div class="entry-container">
                            <div class="section-header">
                                <?php if( !empty( $about_sub_title ) ): ?>
                                    <h3 class="section-subtitle"><?php echo esc_html($about_sub_title); ?></h3>
                                <?php endif; ?>
                                <h2 class="section-title"><a href="<?php echo esc_url($content['url']); ?>"><?php echo esc_html($content['title']); ?></a></h2>
                                <span class="separator"></span>
                            </div><!-- .section-header -->

                            <div class="entry-content">
                                <p><?php echo wp_kses_post($content['excerpt']); ?></p>
                            </div><!-- .entry-content -->

                            <?php if( !empty( $content['url'] ) && !empty( $about_btn_title ) ): ?>
                                <div class="read-more">
                                    <a href="<?php echo esc_url($content['url']); ?>" class="btn"><?php echo esc_html($about_btn_title); ?></a>
                                </div><!-- .read-more -->
                            <?php endif; ?>
                        </div><!-- .entry-container -->
                    </article>
                </div><!-- .wrapper -->
            </div><!-- #about-us -->
        </div>
      

        <?php 
    }
endif;