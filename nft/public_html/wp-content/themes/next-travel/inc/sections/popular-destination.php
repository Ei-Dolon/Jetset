<?php
/**
 * Popular Destination section
 *
 * This is the template for the content of popular Destination section
 *
 * @package Theme Palace
 * @subpackage Next Travel
 * @since Next Travel 1.0.0
 */
if ( ! function_exists( 'next_travel_add_popular_destination_section' ) ) :
	/**
	* Add popular Destination section
	*
	*@since Next Travel 1.0.0
	*/
	function next_travel_add_popular_destination_section() {
		$options = next_travel_get_theme_options();

			// Check if Destination is enabled on frontpage
			$popular_destination_enable = apply_filters( 'next_travel_section_status', true, 'popular_destination_section_enable' );
					
			if ( true !== $popular_destination_enable ) {
				return false;
			}
			// Get Destination section details
			$section_details = array();
			$section_details = apply_filters( 'next_travel_filter_popular_destination_section_details', $section_details );
			if ( empty( $section_details ) ) {
				return;
			}

			// Render Destination section now.
			next_travel_render_popular_destination_section( $section_details );
		}
endif;

if ( ! function_exists( 'next_travel_get_popular_destination_section_details' ) ) :
	/**
	* Popular Destination section details.
	*
	* @since Next Travel 1.0.0
	* @param array $input popular destination section details.
	*/
	function next_travel_get_popular_destination_section_details( $input ) {
		$options = next_travel_get_theme_options();

		// Content type.
		$popular_destination_content_type  = $options['popular_destination_content_type'];
		
		$content 		= array();
		$args 			= array();
        $content['cats'] = array();
		switch ( $popular_destination_content_type ) {

			case 'page':
                $page_ids = array();

                for ( $i = 1; $i <= 3; $i++ ) {
                    if ( ! empty( $options['popular_destination_content_page_' . $i] ) )
                        $page_ids[] = $options['popular_destination_content_page_' . $i];
                }
                
                $args = array(
                    'post_type'         => 'page',
                    'post__in'          => ( array ) $page_ids,
                    'posts_per_page'    => absint( 3 ),
                    'orderby'           => 'post__in',
                    );                    
            break;

            case 'post':
                $post_ids = array();

                for ( $i = 1; $i <= 3; $i++ ) {
                    if ( ! empty( $options['popular_destination_content_post_' . $i] ) )
                        $post_ids[] = $options['popular_destination_content_post_' . $i];
                }
                $args = array(
                    'post_type'             => 'post',
                    'post__in'              => ( array ) $post_ids,
                    'posts_per_page'        => absint( 3 ),
                    'orderby'               => 'post__in',
                    'ignore_sticky_posts'   => true,
                    );                    
            break;
		
			case 'category':
				$cat_id = ! empty( $options['popular_destination_content_category'] ) ? $options['popular_destination_content_category'] : '';
				$args = array(
					'post_type'         	=> 'post',
					'posts_per_page'    	=> absint( 3 ),
					'cat'               	=> absint( $cat_id ),
					'ignore_sticky_posts'   => true,
					);                    
			break;

			case 'trip':
                if ( ! class_exists( 'WP_Travel' ) )
                    return;

                $page_ids = array();

                for ( $i = 1; $i <= 3; $i++ ) {
                    if ( ! empty( $options['popular_destination_content_trip_' . $i] ) )
                        $page_ids[] = $options['popular_destination_content_trip_' . $i];
                }
                
                $args = array(
                    'post_type'         => 'itineraries',
                    'post__in'          => ( array ) $page_ids,
                    'posts_per_page'    => absint( 3 ),
                    'orderby'           => 'post__in',
                    );   

                $content['taxonomy'] = 'travel_locations';

            break;

			case 'trip-types': // trip-types
				if ( ! class_exists( 'WP_Travel' ) )
					return;

				$cat_id = ! empty( $options['popular_destination_content_trip_types'] ) ? $options['popular_destination_content_trip_types'] : array();

				$args = array(
					'post_type' => 'itineraries',
					'tax_query' => array(
						array(
							'taxonomy' => 'itinerary_types',
							'field'    => 'id',
							'terms'    => $cat_id,
						),
					),
					'posts_per_page'  => absint( 3 ),
					);

                $content['taxonomy'] = 'itinerary_types';
			break;

			
			case 'destination': //Destination
				if ( ! class_exists( 'WP_Travel' ) )
					return;

				$cat_id = ! empty( $options['popular_destination_content_destination'] ) ? $options['popular_destination_content_destination'] : array();
				
				$args = array(
					'post_type'      => 'itineraries',
					'tax_query' => array(
						array(
							'taxonomy' => 'travel_locations',
							'field'    => 'id',
							'terms'    => $cat_id,
						),
					),
					'posts_per_page'  => absint( 3 ),
					); 

				$content['taxonomy'] = 'travel_locations';
			break;

			case 'activity':
				if ( ! class_exists( 'WP_Travel' ) )
					return;
				
				$cat_id = ! empty( $options['popular_destination_content_activity'] ) ? $options['popular_destination_content_activity'] : array();
				
				if ( empty( $cat_id ) )
                    return;
				$args = array(
					'post_type'      => 'itineraries',
					'tax_query' => array(
						array(
							'taxonomy' => 'activity',
							'field'    => 'id',
							'terms'    => $cat_id,
						),
					),
					'posts_per_page'  => absint( 3 ),
					); 

                $content['taxonomy'] = 'activity';
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
add_filter( 'next_travel_filter_popular_destination_section_details', 'next_travel_get_popular_destination_section_details' );


if ( ! function_exists( 'next_travel_render_popular_destination_section' ) ) :
	/**
	 * Start Destination section
	 *
	 * @return string Destination content
	 * @since Next Travel 1.0.0
	 *
	 */
	 function next_travel_render_popular_destination_section( $content_details = array() ) {
		$options = next_travel_get_theme_options();

		$popular_destination_content_type  	= $options['popular_destination_content_type'];
		$popular_destination_title 		= !empty($options['popular_destination_title']) ? $options['popular_destination_title'] : '';
		$popular_destination_sub_title 		= !empty($options['popular_destination_sub_title']) ? $options['popular_destination_sub_title'] : '';
		$popular_destination_description 	= !empty($options['popular_destination_description']) ? $options['popular_destination_description'] : '';
		$popular_destination_post_btn_label 		= !empty($options['popular_destination_post_btn_label']) ? $options['popular_destination_post_btn_label'] : '';
		$popular_destination_btn_label 		= !empty($options['popular_destination_btn_label']) ? $options['popular_destination_btn_label'] : '';
		$popular_destination_btn_url 		= !empty($options['popular_destination_btn_url']) ? $options['popular_destination_btn_url'] : '';


		if ( empty( $content_details ) ) {
				return;
		} ?>
		<div id="next_travel_popular_destination_section">
			<div id="top-travel-destinations" class="relative page-section same-background">
				<div class="wrapper">
					<div class="top-destination-info">
						<div class="section-header">
							<h3 class="section-subtitle"><?php echo esc_html($popular_destination_sub_title); ?></h3>

							<h2 class="section-title"><?php echo esc_html($popular_destination_title); ?></h2>

							<span class="separator"></span>
						</div><!-- .section-header -->

						<div class="entry-content">
							<p><?php echo next_travel_santize_allow_tag( $popular_destination_description ); ?></p>
						</div><!-- .entry-content -->

						<?php if( !empty( $popular_destination_btn_label ) && !empty( $popular_destination_btn_url ) ): ?>
							<div class="read-more">
								<a href="<?php echo esc_url( $popular_destination_btn_url ); ?>" class="btn"><?php echo esc_html( $popular_destination_btn_label ); ?></a>
							</div><!-- .read-more -->
						<?php endif; ?>
					</div><!-- .top-destination-info -->

					<div class="top-destination-packages col-2 clear">

						<?php foreach ($content_details['details'] as $contents): 
						$taxonomy = isset($content_details['taxonomy']) ? $content_details['taxonomy'] : 'category';
						$terms =  get_the_terms( $contents['id'], $taxonomy);
						?>

						<article>
							<div class="top-destination-item">
								<div class="featured-image" style="background-image: url('<?php echo esc_url( $contents['image'] ); ?>');"></div>

								<div class="entry-container">
									<header class="entry-header">
										<h2 class="entry-title"><a href="<?php echo esc_url($contents['url']); ?>"><?php echo esc_html($contents['title']); ?></a></h2>
										<?php if( $popular_destination_content_type !== 'page' ): ?>
										<span class="destinationtination-location"><?php echo esc_html($terms[0]->name); ?></span>
									<?php endif; ?>
									</header>

									<div class="price-wrapper">
									<?php if( in_array($popular_destination_content_type, array('trip','trip-types','destination','activity')) ): ?>
										<span class="trip-price">                       
											<?php
											$trip_price = WP_Travel_Helpers_Pricings::get_price( array('trip_id'=>$contents['id']) );
											echo wptravel_get_formated_price_currency( $trip_price );
											?>
										</span><!-- .trip-price -->
									<?php endif; ?>

										<?php if( !empty( $popular_destination_post_btn_label ) ): ?>
											<a href="<?php echo esc_url($contents['url']); ?>" class="add-to-list"><?php echo esc_html( $popular_destination_post_btn_label ); ?></a>
										<?php endif; ?>
									</div><!-- .price-wrapper -->
								</div><!-- .entry-container -->
							</div><!-- .top-destination-item -->
						</article>

					<?php endforeach; ?>

				</div><!-- .top-destination-packages -->
			</div><!-- .wrapper -->
		</div><!-- #top-travel-destinations -->
		</div>	
		

<?php }
endif;