<?php
/**
 * Hashtags Class
 */
class Youzify_Hashtags {

	function __construct() {

		// Shortcodes.
		add_shortcode( 'youzify_hashtags', array( $this, 'hashtags_shortcode' ) );
		add_shortcode( 'youzify_community_hashtags', array( $this, 'community_hashtags_shortcode' ) );

	}

	/**
	 * Hashtags Shortcode.
	 */
	function hashtags_shortcode( $atts ) {

	    $options = shortcode_atts( array(
	        'limit' => 5,
	        'type' => 'popular',
	    ), $atts );

		do_action( 'youzify_before_hashtags_widget' );

		ob_start();
	    $this->widget( $options );
		return ob_get_clean();
	}

	/**
	 * Community Hashtags Shortcode.
	 */
	function community_hashtags_shortcode( $atts ) {

	    $options = shortcode_atts( array(
	        'limit' => 12,
	        'order_by'  => 'popular',
	        'widget' => 'community_hashtags_widget'
	    ), $atts );

		do_action( 'youzify_before_community_hashtags_widget' );

		// Get Community Hashtags
		$hashtags = $this->get_community_hashtags( $options );

		if ( empty( $hashtags ) ) {
			echo '<div class="youzify-no-items-found">' . __( 'No hashtags found!', 'youzify' ) . '</div>';
			return;
		}

		ob_start();

		echo '<div class="youzify-community-hashtags">';

		foreach ( $hashtags as $hashtag ) {
			echo "<a href='" . $this->get_hashtag_link( $hashtag['hashtag'] ) . "' class='youzify-hashtag-item'>#{$hashtag['hashtag']}</a>";
		}

		echo '</div>';

		return ob_get_clean();
	}

	/**
	 * Get Community Hashtags
	 */
	function get_community_hashtags( $args = null ) {

	    $result = get_transient( 'youzify_get_' . $args['widget'] );

	    if ( false === $result ) {

			global $wpdb;

			$request = apply_filters(
				'youzify_get_community_hashtags_sql_request',
				'SELECT * FROM ' . $wpdb->prefix . "youzify_hashtags"
			);

			if ( $args['order_by'] == 'popular' ) {
				$order_by = 'count';
			} elseif ( $args['order_by'] == 'random' ) {
				$order_by = 'RAND()';
			} else {
				$order_by = $args['order_by'];
			}

			if ( isset( $args['order_by'] ) ) {
				$request .= " ORDER BY $order_by DESC";
			}

			if ( isset( $args['limit'] ) ) {
				$request .= " LIMIT {$args['limit']}";
			}

			// Get Result
			$result = $wpdb->get_results( $request , ARRAY_A );

		    set_transient( 'youzify_get_' . $args['widget'], $result, 24 * HOUR_IN_SECONDS );

		}

		return $result;

	}

	/**
	 * Widget Content.
	 */
	function widget( $args ) {

		// Get Hashtags.
		if ( $args['type'] == 'popular' ) {
			$hashtags = $this->get_community_hashtags( array( 'order_by' => 'popular', 'limit' => $args['limit'], 'widget' => 'hashtags_widget' ) );
		} elseif ( $args['type'] == 'trending_today' ) {
			$hashtags = $this->get_trending_hashtags( 1,  $args['limit'] );
		} elseif ( $args['type'] == 'trending_last_week' ) {
			$hashtags = $this->get_trending_hashtags( 7,  $args['limit'] );
		} elseif ( $args['type'] == 'trending_last_month' ) {
			$hashtags = $this->get_trending_hashtags( 30,  $args['limit'] );
		}

		if ( empty( $hashtags ) ) {
			echo '<div class="youzify-no-items-found">' . __( 'No hashtags found!', 'youzify' ) . '</div>';
			return;
		}

		?>

		<div class="youzify-hashtags">

			<?php foreach ( $hashtags as $hashtag ) : ?>
			<div class="youzify-hashtag-item">
				<a href="<?php echo $this->get_hashtag_link( $hashtag['hashtag'] ); ?>" class="youzify-hashtag-title">#<?php echo $hashtag['hashtag']; ?></a>
				<div class="youzify-hashtag-count"><?php echo sprintf( _n( '%s Hashtag', '%s Hashtags', $hashtag['count'], 'youzify' ), $hashtag['count'] ); ?> </div>
			</div>
			<?php endforeach; ?>

		</div>

		<?php

	}

	/**
	 * Get Community Trending Hashtags
	 */
	function get_trending_hashtags( $days, $limit ) {

	    $result = get_transient( 'youzify_get_trending_hashtags' );

	    if ( false === $result ) {

			global $wpdb;

			$request = apply_filters(
				'youzify_get_community_trending_hashtags_sql_request',
				'SELECT hashtag, count( hashtag ) as count FROM ' . $wpdb->prefix . "youzify_hashtags_items WHERE time >= (CURDATE() - INTERVAL $days DAY) GROUP BY hashtag order by count DESC LIMIT $limit"
			);

			// Get Result
			$result = $wpdb->get_results( $request , ARRAY_A );

		    set_transient( 'youzify_get_trending_hashtags', $result, 24 * HOUR_IN_SECONDS );
		}

		return $result;

	}

	/**
	 * Get Hashtag Link
	 */
	function get_hashtag_link( $hashtag = null ) {
		return apply_filters( 'youzify_hashtag_link', bp_get_activity_directory_permalink() . '?s=%23' . $hashtag );
	}

}

new Youzify_Hashtags();