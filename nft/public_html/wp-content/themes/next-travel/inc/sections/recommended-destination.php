<?php
/**
 * Recommended Destination section
 *
 * This is the template for the content of popular Destination section
 *
 * @package Theme Palace
 * @subpackage Next Travel
 * @since Next Travel 1.0.0
 */
if ( ! function_exists( 'next_travel_add_recommended_destination_section' ) ) :
	/**
	* Add popular Destination section
	*
	*@since Next Travel 1.0.0
	*/
	function next_travel_add_recommended_destination_section() {
		$options = next_travel_get_theme_options();

			// Check if Destination is enabled on frontpage
			$recommended_destination_enable = apply_filters( 'next_travel_section_status', true, 'recommended_destination_section_enable' );
			
			if ( true !== $recommended_destination_enable ) {
				return false;
			}
			// Get Destination section details
			$section_details = array();
			$section_details = apply_filters( 'next_travel_filter_recommended_destination_section_details', $section_details );
			if ( empty( $section_details ) ) {
				return;
			}

			// Render Destination section now.
			next_travel_render_recommended_destination_section( $section_details );
		}
endif;

if ( ! function_exists( 'next_travel_get_recommended_destination_section_details' ) ) :
	/**
	* Recommended Destination section details.
	*
	* @since Next Travel 1.0.0
	* @param array $input popular destination section details.
	*/
	function next_travel_get_recommended_destination_section_details( $input ) {
		$options = next_travel_get_theme_options();

		// Content type.
		$recommended_destination_content_type  = $options['recommended_destination_content_type'];
		
		$content 		= array();
		$args 			= array();
        $content['cats'] = array();
		switch ( $recommended_destination_content_type ) {
		
			case 'category':
				$cat_id = ! empty( $options['recommended_destination_content_category'] ) ? $options['recommended_destination_content_category'] : '';
				$args = array(
					'post_type'         	=> 'post',
					'posts_per_page'    	=> absint( 4 ),
					'cat'               	=> absint( $cat_id ),
					'ignore_sticky_posts'   => true,
					);                    
			break;

			case 'trip-types': // trip-types
				if ( ! class_exists( 'WP_Travel' ) )
					return;

				$cat_id = ! empty( $options['recommended_destination_content_trip_types'] ) ? $options['recommended_destination_content_trip_types'] : array();

				$args = array(
					'post_type' => 'itineraries',
					'tax_query' => array(
						array(
							'taxonomy' => 'itinerary_types',
							'field'    => 'id',
							'terms'    => $cat_id,
						),
					),
					'posts_per_page'  => absint( 4 ),
					);

                $content['taxonomy'] = 'itinerary_types';
			break;

			default:
			break;
		}

		$content['details'] = array();
		$query = new WP_Query( $args );
		if ( $query->have_posts() ) : 
			while ( $query->have_posts() ) : $query->the_post();
				$page_post['id']        = get_the_id();
				$page_post['title']     = get_the_title();
				$page_post['url']       = get_the_permalink();
				$page_post['image']  	= has_post_thumbnail() ? get_the_post_thumbnail_url( get_the_id(), 'post-thumbnail' ) : get_template_directory_uri() . '/assets/uploads/no-featured-image-590x650.jpg';
				// Push to the main array.
				array_push( $content['details'], $page_post );
			endwhile;
		endif;

		wp_reset_postdata();
		if ( ! empty( $content ) ) {
				$input = $content;
		}
		return $input;
	}
endif;

// Destination section content details.
add_filter( 'next_travel_filter_recommended_destination_section_details', 'next_travel_get_recommended_destination_section_details' );


if ( ! function_exists( 'next_travel_render_recommended_destination_section' ) ) :
	/**
	 * Start Destination section
	 *
	 * @return string Destination content
	 * @since Next Travel 1.0.0
	 *
	 */
	 function next_travel_render_recommended_destination_section( $content_details = array() ) {
		$options = next_travel_get_theme_options();

		$recommended_destination_content_type  	= $options['recommended_destination_content_type'];
		$recommended_destination_title 		= !empty($options['recommended_destination_title']) ? $options['recommended_destination_title'] : '';
		$recommended_destination_sub_title 		= !empty($options['recommended_destination_sub_title']) ? $options['recommended_destination_sub_title'] : '';
		$recommended_destination_description 	= !empty($options['recommended_destination_description']) ? $options['recommended_destination_description'] : '';
		$recommended_destination_post_btn_label 		= !empty($options['recommended_destination_post_btn_label']) ? $options['recommended_destination_post_btn_label'] : '';
		$recommended_destination_btn_label 		= !empty($options['recommended_destination_btn_label']) ? $options['recommended_destination_btn_label'] : '';
		$recommended_destination_btn_url 		= !empty($options['recommended_destination_btn_url']) ? $options['recommended_destination_btn_url'] : '';


		if ( empty( $content_details ) ) {
				return;
		} ?>
		<div id="next_travel_recommended_destination_section">
			<div id="recommended-destinations" class="relative page-section same-background">
				<div class="wrapper">
					<div class="recommended-destination-info">
						<div class="section-header">
							<h3 class="section-subtitle"><?php echo esc_html($recommended_destination_sub_title); ?></h3>
							<h2 class="section-title"><?php echo esc_html($recommended_destination_title); ?></h2>
							<span class="separator"></span>
						</div><!-- .section-header -->

						<div class="entry-content">
							<p><?php echo next_travel_santize_allow_tag( $recommended_destination_description ); ?></p>
						</div><!-- .entry-content -->

						<?php if( !empty( $recommended_destination_btn_label ) && !empty( $recommended_destination_btn_url ) ): ?>
							<div class="read-more">
								<a href="<?php echo esc_url( $recommended_destination_btn_url ); ?>" class="btn"><?php echo esc_html( $recommended_destination_btn_label ); ?></a>
							</div><!-- .read-more -->
						<?php endif; ?>
					</div><!-- .recommended-destination-info -->

					<div class="recommended-destination-packages col-2 clear">

						<?php foreach ($content_details['details'] as $contents): 
						$taxonomy = isset($content_details['taxonomy']) ? $content_details['taxonomy'] : 'category';
						$terms =  get_the_terms( $contents['id'], $taxonomy);
						?>

						<article>
							<div class="recommended-destination-item">
								<div class="featured-image" style="background-image: url('<?php echo esc_url( $contents['image'] ); ?>');"></div>
								<div class="entry-container">
									<header class="entry-header">
										<h2 class="entry-title"><a href="<?php echo esc_url($contents['url']); ?>"><?php echo esc_html($contents['title']); ?></a></h2>
										<span class="destination-location"><?php echo esc_html($terms[0]->name); ?></span>
									</header>
									<div class="price-wrapper">
									<?php if( $recommended_destination_content_type == 'trip' ): ?>
											<span class="trip-price">                       
												<?php
												$trip_price = WP_Travel_Helpers_Pricings::get_price( array('trip_id'=>$contents['id']) );
												echo wptravel_get_formated_price_currency( $trip_price );
												?>
											</span><!-- .trip-price -->
										<?php endif;
										if( !empty( $recommended_destination_post_btn_label ) ): ?>
										<a href="<?php echo esc_url($contents['url']); ?>" class="add-to-list"><?php echo esc_html( $recommended_destination_post_btn_label ); ?></a>
									<?php endif; ?>
								</div><!-- .price-wrapper -->
							</div><!-- .entry-container -->
						</div><!-- .recommended-destination-item -->
					</article>

				<?php endforeach; ?>

			</div><!-- .recommended-destination-packages -->
		</div><!-- .wrapper -->
	</div><!-- #recommended-destinations -->
		</div>	
		

<?php }
endif;