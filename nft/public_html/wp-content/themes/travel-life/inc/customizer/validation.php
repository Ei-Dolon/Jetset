<?php
/**
* Customizer validation functions
*
* @package Theme Palace
* @subpackage Travel Life
* @since Travel Life 1.0.0
*/

if ( ! function_exists( 'travel_life_validate_long_excerpt' ) ) :
  function travel_life_validate_long_excerpt( $validity, $value ){
		 $value = intval( $value );
	 if ( empty( $value ) || ! is_numeric( $value ) ) {
		 $validity->add( 'required', esc_html__( 'You must supply a valid number.', 'travel-life' ) );
	 } elseif ( $value < 5 ) {
		 $validity->add( 'min_no_of_words', esc_html__( 'Minimum no of words is 5', 'travel-life' ) );
	 } elseif ( $value > 100 ) {
		 $validity->add( 'max_no_of_words', esc_html__( 'Maximum no of words is 100', 'travel-life' ) );
	 }
	 return $validity;
  }
endif;

if ( ! function_exists( 'travel_life_validate_slider_count' ) ) :
  function travel_life_validate_slider_count( $validity, $value ){
		 $value = intval( $value );
	 if ( empty( $value ) || ! is_numeric( $value ) ) {
		 $validity->add( 'required', esc_html__( 'You must supply a valid number.', 'travel-life' ) );
	 } elseif ( $value < 1 ) {
		 $validity->add( 'min_no_of_posts', esc_html__( 'Minimum no of posts is 1', 'travel-life' ) );
	 } elseif ( $value > 10 ) {
		 $validity->add( 'max_no_of_posts', esc_html__( 'Maximum no of posts is 10', 'travel-life' ) );
	 }
	 return $validity;
  }
endif;

if ( ! function_exists( 'travel_life_validate_featured_count' ) ) :
  function travel_life_validate_featured_count( $validity, $value ){
		 $value = intval( $value );
	 if ( empty( $value ) || ! is_numeric( $value ) ) {
		 $validity->add( 'required', esc_html__( 'You must supply a valid number.', 'travel-life' ) );
	 } elseif ( $value < 4 ) {
		 $validity->add( 'min_no_of_posts', esc_html__( 'Minimum no of posts is 4', 'travel-life' ) );
	 } elseif ( $value > 12 ) {
		 $validity->add( 'max_no_of_posts', esc_html__( 'Maximum no of posts is 12', 'travel-life' ) );
	 }
	 return $validity;
  }
endif;

if ( ! function_exists( 'travel_life_validate_popular_destination_count' ) ) :
  function travel_life_validate_popular_destination_count( $validity, $value ){
		 $value = intval( $value );
	 if ( empty( $value ) || ! is_numeric( $value ) ) {
		 $validity->add( 'required', esc_html__( 'You must supply a valid number.', 'travel-life' ) );
	 } elseif ( $value < 3 ) {
		 $validity->add( 'min_no_of_posts', esc_html__( 'Minimum no of posts is 3', 'travel-life' ) );
	 } elseif ( $value > 12 ) {
		 $validity->add( 'max_no_of_posts', esc_html__( 'Maximum no of posts is 12', 'travel-life' ) );
	 }
	 return $validity;
  }
endif;

if ( ! function_exists( 'travel_life_validate_traveler_choice_count' ) ) :
  function travel_life_validate_traveler_choice_count( $validity, $value ){
		 $value = intval( $value );
	 if ( empty( $value ) || ! is_numeric( $value ) ) {
		 $validity->add( 'required', esc_html__( 'You must supply a valid number.', 'travel-life' ) );
	 } elseif ( $value < 1 ) {
		 $validity->add( 'min_no_of_posts', esc_html__( 'Minimum no of posts is 1', 'travel-life' ) );
	 } elseif ( $value > 10 ) {
		 $validity->add( 'max_no_of_posts', esc_html__( 'Maximum no of posts is 10', 'travel-life' ) );
	 }
	 return $validity;
  }
endif;

if ( ! function_exists( 'travel_life_validate_service_count' ) ) :
  function travel_life_validate_service_count( $validity, $value ){
		 $value = intval( $value );
	 if ( empty( $value ) || ! is_numeric( $value ) ) {
		 $validity->add( 'required', esc_html__( 'You must supply a valid number.', 'travel-life' ) );
	 } elseif ( $value < 3 ) {
		 $validity->add( 'min_no_of_posts', esc_html__( 'Minimum no of posts is 3', 'travel-life' ) );
	 } elseif ( $value > 12 ) {
		 $validity->add( 'max_no_of_posts', esc_html__( 'Maximum no of posts is 12', 'travel-life' ) );
	 }
	 return $validity;
  }
endif;

if ( ! function_exists( 'travel_life_validate_latest_post_count' ) ) :
  function travel_life_validate_latest_post_count( $validity, $value ){
		 $value = intval( $value );
	 if ( empty( $value ) || ! is_numeric( $value ) ) {
		 $validity->add( 'required', esc_html__( 'You must supply a valid number.', 'travel-life' ) );
	 } elseif ( $value < 2 ) {
		 $validity->add( 'min_no_of_posts', esc_html__( 'Minimum no of posts is 2', 'travel-life' ) );
	 } elseif ( $value > 12 ) {
		 $validity->add( 'max_no_of_posts', esc_html__( 'Maximum no of posts is 12', 'travel-life' ) );
	 }
	 return $validity;
  }
endif;

if ( ! function_exists( 'travel_life_validate_blog_count' ) ) :
  function travel_life_validate_blog_count( $validity, $value ){
		 $value = intval( $value );
	 if ( empty( $value ) || ! is_numeric( $value ) ) {
		 $validity->add( 'required', esc_html__( 'You must supply a valid number.', 'travel-life' ) );
	 } elseif ( $value < 2 ) {
		 $validity->add( 'min_no_of_posts', esc_html__( 'Minimum no of posts is 2', 'travel-life' ) );
	 } elseif ( $value > 12 ) {
		 $validity->add( 'max_no_of_posts', esc_html__( 'Maximum no of posts is 12', 'travel-life' ) );
	 }
	 return $validity;
  }
endif;

if ( ! function_exists( 'travel_life_validate_discover_places_count' ) ) :
  function travel_life_validate_discover_places_count( $validity, $value ){
		 $value = intval( $value );
	 if ( empty( $value ) || ! is_numeric( $value ) ) {
		 $validity->add( 'required', esc_html__( 'You must supply a valid number.', 'travel-life' ) );
	 } elseif ( $value < 1 ) {
		 $validity->add( 'min_no_of_posts', esc_html__( 'Minimum no of posts is 1', 'travel-life' ) );
	 } elseif ( $value > 10 ) {
		 $validity->add( 'max_no_of_posts', esc_html__( 'Maximum no of posts is 10', 'travel-life' ) );
	 }
	 return $validity;
  }
endif;

if ( ! function_exists( 'travel_life_validate_testimonial_count' ) ) :
  function travel_life_validate_testimonial_count( $validity, $value ){
		 $value = intval( $value );
	 if ( empty( $value ) || ! is_numeric( $value ) ) {
		 $validity->add( 'required', esc_html__( 'You must supply a valid number.', 'travel-life' ) );
	 } elseif ( $value < 1 ) {
		 $validity->add( 'min_no_of_posts', esc_html__( 'Minimum no of posts is 1', 'travel-life' ) );
	 } elseif ( $value > 10 ) {
		 $validity->add( 'max_no_of_posts', esc_html__( 'Maximum no of posts is 10', 'travel-life' ) );
	 }
	 return $validity;
  }
endif;

if ( ! function_exists( 'travel_life_validate_popular_count' ) ) :
  function travel_life_validate_popular_count( $validity, $value ){
		 $value = intval( $value );
	 if ( empty( $value ) || ! is_numeric( $value ) ) {
		 $validity->add( 'required', esc_html__( 'You must supply a valid number.', 'travel-life' ) );
	 } elseif ( $value < 3 ) {
		 $validity->add( 'min_no_of_posts', esc_html__( 'Minimum no of posts is 3', 'travel-life' ) );
	 } elseif ( $value > 12 ) {
		 $validity->add( 'max_no_of_posts', esc_html__( 'Maximum no of posts is 12', 'travel-life' ) );
	 }
	 return $validity;
  }
endif;

if ( ! function_exists( 'travel_life_validate_recent_count' ) ) :
  function travel_life_validate_recent_count( $validity, $value ){
		 $value = intval( $value );
	 if ( empty( $value ) || ! is_numeric( $value ) ) {
		 $validity->add( 'required', esc_html__( 'You must supply a valid number.', 'travel-life' ) );
	 } elseif ( $value < 3 ) {
		 $validity->add( 'min_no_of_posts', esc_html__( 'Minimum no of posts is 3', 'travel-life' ) );
	 } elseif ( $value > 12 ) {
		 $validity->add( 'max_no_of_posts', esc_html__( 'Maximum no of posts is 12', 'travel-life' ) );
	 }
	 return $validity;
  }
endif;

if ( ! function_exists( 'travel_life_validate_most_view_count' ) ) :
  function travel_life_validate_most_view_count( $validity, $value ){
		 $value = intval( $value );
	 if ( empty( $value ) || ! is_numeric( $value ) ) {
		 $validity->add( 'required', esc_html__( 'You must supply a valid number.', 'travel-life' ) );
	 } elseif ( $value < 2 ) {
		 $validity->add( 'min_no_of_posts', esc_html__( 'Minimum no of posts is 2', 'travel-life' ) );
	 } elseif ( $value > 12 ) {
		 $validity->add( 'max_no_of_posts', esc_html__( 'Maximum no of posts is 12', 'travel-life' ) );
	 }
	 return $validity;
  }
endif;

if ( ! function_exists( 'travel_life_validate_popular_product_count' ) ) :
  function travel_life_validate_popular_product_count( $validity, $value ){
		 $value = intval( $value );
	 if ( empty( $value ) || ! is_numeric( $value ) ) {
		 $validity->add( 'required', esc_html__( 'You must supply a valid number.', 'travel-life' ) );
	 } elseif ( $value < 4 ) {
		 $validity->add( 'min_no_of_posts', esc_html__( 'Minimum no of posts is 4', 'travel-life' ) );
	 } elseif ( $value > 12 ) {
		 $validity->add( 'max_no_of_posts', esc_html__( 'Maximum no of posts is 12', 'travel-life' ) );
	 }
	 return $validity;
  }
endif;
