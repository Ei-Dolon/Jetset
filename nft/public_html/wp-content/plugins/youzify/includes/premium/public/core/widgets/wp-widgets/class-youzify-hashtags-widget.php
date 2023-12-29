<?php

/**
 * Smart Author Box Widget
 */

class Youzify_Hashtags_Widget extends WP_Widget {

	function __construct() {
		parent::__construct(
			'youzify_hashtags',
			__( 'Youzify - Hashtags', 'youzify' ),
			array( 'description' => __( 'Hashtags Widget', 'youzify' ) )
		);
	}

	/**
	 * Back-end widget form.
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {

		// Default Widget Settings
	    $defaults = array(
	        'title' => __( 'Hashtags', 'youzify' ),
	        'type' => 'popular',
	        'limit' => 5,
	    );

	    // Get Widget Data.
	    $instance = wp_parse_args( (array) $instance, $defaults );

	    // Get Input's Data.
		$options = array(
			'popular' => __( 'Popular', 'youzify' ),
			'trending_today' => __( 'Trending Today', 'youzify' ),
			'trending_last_week' => __( 'Trending Last Week', 'youzify' ),
			'trending_last_month' => __( 'Trending Last Month', 'youzify' )
		);

		?>

		<!-- Title. -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', 'youzify' ); ?></label>
			<input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" class="widefat" value="<?php echo $instance['title']; ?>">
		</p>

	    <p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'type' ) ); ?>"><?php esc_attr_e( 'Type', 'youzify' ); ?></label>
	        <select id="<?php echo $this->get_field_id( 'type' ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'type' ) ); ?>" class="widefat" style="width:100%;">
	            <?php foreach( $options as $options_id => $option_name ) { ?>
	            	<option <?php selected( $instance['type'], $options_id ); ?> value="<?php echo $options_id; ?>"><?php echo $option_name; ?></option>
	            <?php } ?>
	        </select>
	    </p>

		<!-- Limit Hashtags Number. -->
		<p>
			<label for="<?php echo $this->get_field_id( 'limit' ); ?>"><?php _e( 'Hashtags Number:', 'youzify' ); ?>
				<input class="widefat" id="<?php echo $this->get_field_id( 'limit' ); ?>" name="<?php echo $this->get_field_name( 'limit' ); ?>" type="text" value="<?php echo esc_attr( $instance['limit'] ); ?>" style="width: 30%">
			</label>
		</p>

		<?php
	}

	/**
	 * Sanitize widget form values as they are saved.
	 */
	public function update( $new_instance, $old_instance ) {

		$instance = array();

		// Update Values.
		$instance['limit'] = absint( $new_instance['limit'] );
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['type'] = ! empty( $new_instance['type'] ) ? $new_instance['type'] : 'popular';

		return $instance;
	}

	/**
	 * Login Widget Content
	 */
	public function widget( $args, $instance ) {

		echo $args['before_widget'];

		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'];
			echo apply_filters( 'widget_title', $instance['title'] );
			echo $args['after_title'];
		}

		// Display Widgets.
		echo '<div class="youzify-wp-widget youzify-hashtags-widget">';
		echo do_shortcode( "[youzify_hashtags type='{$instance['type']}' limit='{$instance['limit']}']" );
		echo '</div>';

		echo $args['after_widget'];

	}

}