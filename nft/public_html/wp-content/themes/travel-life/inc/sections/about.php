<?php
/**
 * About section
 *
 * This is the template for the content of about section
 *
 * @package Theme Palace
 * @subpackage Travel Life
 * @since Travel Life 1.0.0
 */
if ( ! function_exists( 'travel_life_add_about_section' ) ) :
    /**
    * Add about section
    *
    *@since Travel Life 1.0.0
    */
    function travel_life_add_about_section() {
    	$options = travel_life_get_theme_options();
        // Check if about is enabled on frontpage
        $about_enable = apply_filters( 'travel_life_section_status', true, 'about_section_enable' );

        if ( true !== $about_enable ) {
            return false;
        }
        // Get about section details
        $section_details = array();
        $section_details = apply_filters( 'travel_life_filter_about_section_details', $section_details );

        if ( empty( $section_details ) ) {
            return;
        }

        // Render about section now.
        travel_life_render_about_section( $section_details );
    }
endif;

if ( ! function_exists( 'travel_life_get_about_section_details' ) ) :
    /**
    * about section details.
    *
    * @since Travel Life 1.0.0
    * @param array $input about section details.
    */
    function travel_life_get_about_section_details( $input ) {
        $options = travel_life_get_theme_options();
        
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
                $page_post['excerpt']   = travel_life_trim_content( 35 );
                $page_post['url']       = get_the_permalink();
                $page_post['image']     = has_post_thumbnail() ? get_the_post_thumbnail_url( get_the_id(), 'medium_large' ) : get_template_directory_uri().'/assets/uploads/no-featured-image-590x650.jpg';

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
add_filter( 'travel_life_filter_about_section_details', 'travel_life_get_about_section_details' );


if ( ! function_exists( 'travel_life_render_about_section' ) ) :
  /**
   * Start about section
   *
   * @return string about content
   * @since Travel Life 1.0.0
   *
   */
   function travel_life_render_about_section( $content_details = array() ) {
        $options = travel_life_get_theme_options();
        $about_content_type  = $options['about_content_type'];

        if ( empty( $content_details ) ) {
            return;
        } 
        ?>

        <?php foreach ( $content_details as $content ) : ?>
            <div id="travel_life_about_section" class="relative page-section same-background">
                 <div id="about-us">
                    <div class="wrapper">
                        <?php if ( is_customize_preview()):
                            travel_life_section_tooltip( 'about-us' );
                        endif; ?>
                        <article class="<?php echo ! empty( $content['image'] ) ? 'has' : 'no'; ?>-post-thumbnail">
                            <?php if (! empty( $content['image'] ) && $about_content_type != 'custom'): ?>
                                <div class="featured-image">
                                    <a href="<?php echo esc_url($content['url']); ?>"><img src="<?php echo esc_url($content['image']); ?>"></a>
                                </div><!-- .featured-image -->
                            <?php endif ?> 
                          
                            <div class="entry-container">
                                <?php if (! empty( $content['title'] ) && $about_content_type != 'custom'): ?>
                                    <div class="section-header">
                                        <h2 class="section-title"><a href="<?php echo esc_url($content['url']); ?>"><?php echo esc_html($content['title']); ?></a></h2>
                                    </div><!-- .section-header -->
                                <?php endif; ?>

                                <?php if (!empty($content['excerpt'])): ?>
                                    <div class="entry-content">
                                        <p><?php echo wp_kses_post( $content['excerpt'] ); ?></p>
                                    </div><!-- .entry-content -->
                                <?php endif; ?>
                                <?php if (!empty($options['about_btn_link']) && !empty($options['about_btn_title'])): ?>
                                    <div class="read-more">
                                        <a href="<?php echo esc_url( $options['about_btn_link'] ); ?>" class="btn"><?php echo esc_html( $options['about_btn_title'] ); ?></a>
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

