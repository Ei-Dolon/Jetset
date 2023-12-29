<?php
/**
 * Trip Search section
 *
 * This is the template for the content of trip_search section
 *
 * @package Theme Palace
 * @subpackage Swingpress
 * @since Swingpress 1.0.0
 */
if ( ! function_exists( 'swingpress_add_trip_search_section' ) ) :
    /**
    * Add trip_search section
    *
    *@since Swingpress 1.0.0
    */
    function swingpress_add_trip_search_section() {
    	$options = swingpress_get_theme_options();
        // Check if trip_search is enabled on frontpage
        $trip_search_enable = apply_filters( 'swingpress_section_status', true, 'trip_search_section_enable' );

        if ( true !== $trip_search_enable || ! class_exists( 'WP_Travel' ) ) {
            return false;
        }

        // Render trip_search section now.
        swingpress_render_trip_search_section();
    }
endif;

if ( ! function_exists( 'swingpress_render_trip_search_section' ) ) :
  /**
   * Start trip_search section
   *
   * @return string trip_search content
   * @since Swingpress 1.0.0
   *
   */
   function swingpress_render_trip_search_section() {
        if ( ! class_exists('WP_Travel') ) 
            return;
                
        $options = swingpress_get_theme_options();
        ?>
        <div id="swingpress_trip_search_section" class="relative">
           <div id="travel-search-section" >
              <div class="wrapper">
                  <div class="travel-search-wrapper">
                      <?php wp_travel_search_form(); ?> 
                  </div><!-- wp-travel-filter -->
              </div><!-- .wrapper -->      
          </div><!-- #travel-search-section -->
        </div>
       

    <?php }
endif;