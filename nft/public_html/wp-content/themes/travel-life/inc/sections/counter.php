<?php
/**
 * Counter section
 *
 * This is the template for the content of counter section
 *
 * @package Theme Palace
 * @subpackage Travel Life
 * @since Travel Life 1.0.0
 */
if ( ! function_exists( 'travel_life_add_counter_section' ) ) :
    /**
    * Add counter section
    *
    *@since Travel Life 1.0.0
    */
    function travel_life_add_counter_section() {
    	$options = travel_life_get_theme_options();
        // Check if counter is enabled on frontpage
        $counter_enable = apply_filters( 'travel_life_section_status', true, 'counter_section_enable' );

        // if( !($options['home_layout'] == 'fifth-design')    ){
        //     $counter_enable = false;
        // }

        if ( ! $counter_enable ) {
            return false;
        }

        // Render counter section now.
        travel_life_render_counter_section();
    }
endif;

if ( ! function_exists( 'travel_life_render_counter_section' ) ) :
  /**
   * Start counter section
   *
   * @return string counter content
   * @since Travel Life 1.0.0
   *
   */
   function travel_life_render_counter_section() {
        $options = travel_life_get_theme_options();
        $image = ( ! empty( $options['counter_image'] ) ) ? $options['counter_image'] : '' ;
       
        ?>
        <div id="travel_life_counter_section" class="relative page-section">
              <div id="counter-section">
                <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/uploads/counter-layer.png');  ?>" class="counter-layer" >
                <div class="wrapper">
                    <?php if ( is_customize_preview()):
                        travel_life_section_tooltip( 'counter-section' );
                    endif; ?>
                    <div class="clear col-5">

                        <?php for ( $i=1; $i <= 5; $i++ ) :  

                            if ( isset( $options['counter_subtitle_' . $i] ) || isset( $options['counter_value_' . $i] ) || isset( $options['counter_text_' . $i] ) ) :
                            ?> 
                            <article >
                                <div class="counter-item">
                                    <?php if ( !empty( $options['counter_value_' . $i] ) ) { ?>
                                        <h2 class="counter-value"><?php echo esc_html( $options['counter_value_' . $i ] ); ?></h2>
                                    <?php } ?>
                                    <?php if ( !empty( $options['counter_text_' . $i] ) ) { ?>
                                        <h3 class="counter-title"><?php echo esc_html( $options['counter_text_' . $i ] ); ?></h3>
                                    <?php } ?>
                                </div><!-- .counter-item -->
                            </article>
                            <?php endif; 
                        endfor; ?>

                    </div>
                </div><!-- .wrapper -->
            </div><!-- #counter -->
        </div>
          
        <?php
    }
endif;