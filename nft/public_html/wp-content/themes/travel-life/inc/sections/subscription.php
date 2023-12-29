<?php
/**
 * Subscription section
 *
 * This is the template for the content of subscription section
 *
 * @package Theme Palace
 * @subpackage Travel Life
 * @since Travel Life 1.0.0
 */
if ( ! function_exists( 'travel_life_add_subscription_section' ) ) :
    /**
    * Add subscription section
    *
    *@since Travel Life 1.0.0
    */
    function travel_life_add_subscription_section() {
        $options = travel_life_get_theme_options();
        // Check if subscription is enabled on frontpage
        $subscription_enable = apply_filters( 'travel_life_section_status', true, 'subscription_section_enable' );

        if ( true !== $subscription_enable ) {
            return false;
        }

        // Render subscription section now.
        travel_life_render_subscription_section();
    }
endif;

if ( ! function_exists( 'travel_life_render_subscription_section' ) ) :
  /**
   * Start subscription section
   *
   * @return string subscription content
   * @since Travel Life 1.0.0
   *
   */
   function travel_life_render_subscription_section() {
        if ( ! class_exists( 'Jetpack' ) ) {
            return;
        } elseif ( class_exists( 'Jetpack' ) ) {
            if ( ! Jetpack::is_module_active( 'subscriptions' ) )
                return;
        }

        $options = travel_life_get_theme_options();
        $btn_label = ! empty( $options['subscription_btn_title'] ) ? $options['subscription_btn_title'] : esc_html__( 'Subscribe Now', 'travel-life' );

        ?>
        <div id="travel_life_subscription_section" class="relative page-section" <?php if (!empty($options['subscription_image'])){ ?> style="background-image: url('<?php echo esc_url($options['subscription_image']) ?>');" <?php } ?>>
            <div id="subscribe-now">
                <div class="overlay"></div>
                <div class="wrapper">
                    <?php if ( is_customize_preview()):
                        travel_life_section_tooltip( 'subscribe-now' );
                    endif; ?>
                    <?php if ( ! empty( $options['subscription_title'] ) ) : ?>
                        <div class="section-header">
                            <?php if ( ! empty( $options['subscription_title'] ) ) : ?>
                                <h2 class="section-title"><?php echo esc_html( $options['subscription_title'] ); ?></h2>
                            <?php endif; ?>
                        </div><!-- .section-header -->
                    <?php endif; ?>

                    <div class="subscribe-form-wrapper">
                        <?php 
                            $subscription_shortcode = '[jetpack_subscription_form title="" subscribe_text="" subscribe_button="' . esc_html( $btn_label ) . '" show_subscribers_total="0"]';
                            echo do_shortcode( wp_kses_post( $subscription_shortcode ) ); 
                        ?>
                    </div><!-- .subscribe-form-wrapper -->
                </div><!-- .wrapper -->
            </div><!-- #subscribe-now -->
        </div>
        

    <?php }
endif;
