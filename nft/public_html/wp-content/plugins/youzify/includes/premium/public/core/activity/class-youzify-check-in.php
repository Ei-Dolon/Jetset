<?php
/**
 * Activity Tag Users
 */
class Youzify_Activity_Check_In {

	function __construct( ) {

		// Add Tool
		add_action( 'bp_activity_after_post_form_tools', array( $this, 'tool' ) );

		add_action( 'youzify_after_wall_post_form_textarea', array( $this, 'search_box' ) );

		// Hide Private Users Posts.
		add_filter( 'youzify_activity_new_post_action', array( $this, 'action' ), 10, 2 );

		// Filter by Place ID.
		add_action( 'bp_after_has_activities_parse_args', array( $this, 'filter_activities_by_place' ), 10, 2 );

	}

	function filter_activities_by_place( $r ) {

		if (  ! bp_is_activity_component() || ! isset( $_REQUEST['place_id'] ) || empty( $_REQUEST['place_id'] ) ) {
			return $r;
		}

		if ( empty( $r['meta_query'] ) ) {
			$r['meta_query'] = array(
				array(
					'key'     => 'youzify_checkin_place_id',
					'value'   => $_REQUEST['place_id'],
				)
			);
		} else {
			array_push( $r['meta_query'], array(
				'key'     => 'youzify_checkin_place_id',
				'value'   => $_REQUEST['place_id'],
			) );
		}

		return $r;
	}

	/**
	 * Show Tagged Users.
	 */
	function action( $action, $activity ) {

		// Get Check Label.
		$label = bp_activity_get_meta( $activity->id, 'youzify_checkin_label' );

		if ( ! empty( $label ) ) {

			$place_id = bp_activity_get_meta( $activity->id, 'youzify_checkin_place_id' );

			$permalink = '';

			if ( ! empty( $place_id ) ) {
				$permalink = add_query_arg( 'place_id', $place_id, bp_get_activity_directory_permalink() );
			}

			$tagged_users = bp_activity_get_meta( $activity->id, 'tagged_users' );

			$mood = bp_activity_get_meta( $activity->id, 'mood' );

			if ( empty( $mood ) && empty( $tagged_users ) ) {

				$string = sprintf( __( 'is in <a href="%1s" class="youzify-map-label">%2s</a>', 'youzify' ), $permalink, $label );

			} elseif ( ! empty( $mood ) ) {

				$string = sprintf( __( 'at <a href="%1s" class="youzify-map-label">%2s</a>', 'youzify' ), $permalink, $label );
			} else {

				$string = sprintf( __( 'in <a href="%1s" class="youzify-map-label">%2s</a>', 'youzify' ), $permalink, $label );
			}

			return $action . ' ' . $string;

		}

		return $action;

	}

	/**
	 * Add Chyeck In Tool
	 */
	function tool() {

		if ( ! apply_filters( 'youzify_enable_activity_form_check_in', true ) ) {
			return;
		}

		$api_key = youzify_option( 'youzify_google_map_api_key' );

		if ( empty( $api_key ) ) {
			return;
		}

		?>
		<div class="youzify-checkin-tool youzify-form-tool" data-key="<?php echo $api_key; ?>"data-youzify-tooltip="<?php _e( 'Check in', 'youzify' ); ?>"><i class="fas fa-map-marker-alt"></i></div>
		<?php
	}


	/**
	 * Tag Users
	 */
	function search_box() {

		$countries = youzify_option( 'youzify_google_map_allowed_countries' );

		?>
		<script type="text/javascript">
			var youzify_map_autocomplete_options = {
                types: [ 'establishment', 'geocode' ],
			   <?php echo ! empty( $countries ) ? 'componentRestrictions: { country: [' . "'" . implode("','", $countries) . "'" . '] }' : ''; ?>
			 };
		</script>
		<div class="youzify-wall-list youzify-wall-checkin">

			<div class="youzify-list-search-form youzify-checkin-form">
				<div class="youzify-list-search-box youzify-checkin-search-box">

					<div class="youzify-list-search-container">
						<div class="youzify-list-search-icon youzify-checkin-search-icon"><i class="fas fa-search"></i></div>
						<input type="text" class="youzify-list-search-input youzify-checkin-search-input" name="checkin_search" placeholder="<?php _e( 'Where are you?', 'youzify' ); ?>">
						<div class="youzify-list-close-icon youzify-checkin-search-icon youzify-checkin-close-icon"><i class="fas fa-times"></i></div>
					</div>

				</div>

				<div class="youzify-wall-list-items youzify-wall-checkin-list"></div>

				<input type="hidden" name="checkin_place_id">
				<input type="hidden" name="checkin_label">

				<div class="youzify-geomap-content">
					<div class="youzify-geomap"></div>
					<div class="youzify-remove-map"><i class="fas fa-times"></i></div>
				</div>
			</div>
		</div>

		<?php

	}

}

new Youzify_Activity_Check_In();