<?php
/**
 * Trip Search section
 *
 * This is the template for the content of trip_search section
 *
 * @package Theme Palace
 * @subpackage gloomy_travel_life
 * @since gloomy_travel_life 1.0.0
 */
if ( ! function_exists( 'gloomy_travel_life_add_trip_search_section' ) ) :
    /**
    * Add trip_search section
    *
    *@since gloomy_travel_life 1.0.0
    */
    function gloomy_travel_life_add_trip_search_section() {

        $options = travel_life_get_theme_options();
        // Check if slider is enabled on frontpage
        $slider_enable = apply_filters( 'travel_life_section_status', true, 'slider_section_enable' );

        if ( true !== $slider_enable ) {
            return false;
        }

        gloomy_travel_life_render_trip_search_section();
    }
endif;

if ( ! function_exists( 'gloomy_travel_life_render_trip_search_section' ) ) :
  /**
   * Start trip_search section
   *
   * @return string trip_search content
   * @since gloomy_travel_life 1.0.0
   *
   */
   function gloomy_travel_life_render_trip_search_section() {
        if ( ! class_exists('WP_Travel') ) 
            return;
        ?>
        <div id="travel-search-section" class="relative same-background">
            <div class="wrapper">
                <?php wptravel_search_form(); ?>
            </div><!-- .wrapper -->      
        </div><!-- #travel-search-section -->

    <?php }
endif;