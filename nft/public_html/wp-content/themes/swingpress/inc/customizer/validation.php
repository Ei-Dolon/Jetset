<?php
/**
* Customizer validation functions
*
* @package Theme Palace
* @subpackage Swingpress
* @since Swingpress 1.0.0
*/

if ( ! function_exists( 'swingpress_validate_long_excerpt' ) ) :
  function swingpress_validate_long_excerpt( $validity, $value ){
		 $value = intval( $value );
	 if ( empty( $value ) || ! is_numeric( $value ) ) {
		 $validity->add( 'required', esc_html__( 'You must supply a valid number.', 'swingpress' ) );
	 } elseif ( $value < 5 ) {
		 $validity->add( 'min_no_of_words', esc_html__( 'Minimum no of words is 5', 'swingpress' ) );
	 } elseif ( $value > 100 ) {
		 $validity->add( 'max_no_of_words', esc_html__( 'Maximum no of words is 100', 'swingpress' ) );
	 }
	 return $validity;
  }
endif;