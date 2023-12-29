<?php

class Youzify_Premium_Ajax {

	function __construct() {

		// Call share Form Modal.
		add_action( 'wp_ajax_youzify_get_share_activity_form', array( $this, 'get_share_activity_form' ) );

    	// Handle Sticky Posts.
		add_action( 'wp_ajax_youzify_handle_sticky_posts',  array( $this, 'handle_sticky_posts' ) );

    	// Handle Bookmarks Posts.
		add_action( 'wp_ajax_youzify_handle_posts_bookmark',  array( $this, 'handle_posts_bookmark' ) );

		// Switch Lighting Mode.
		add_action( 'wp_ajax_youzify_switch_lighting_mode',  array( $this, 'switch_lighting_mode' ) );
	}

	/**
	 * Switch Lighting Mode
	 */
	function switch_lighting_mode() {

		// Check if lighting is editable
		if ( youzify_option( 'youzify_allow_lighting_edition', 'on' ) == 'off' ) {
			wp_send_json_error();
		}

		// Get User ID.
		$user_id = bp_displayed_user_id() ? bp_displayed_user_id() : bp_loggedin_user_id();

		// Get Mode.
		$mode = isset( $_POST['mode'] ) ? $_POST['mode'] : 'light';

		// Update Mode
		update_user_meta( $user_id, 'youzify_lighting_mode', $mode );

		wp_send_json_success();
	
	}

	/**
	 * Get Share Activity Form.
	 */
	function get_share_activity_form() {

 		// Check Nonce Security
 		check_ajax_referer( 'youzify-nonce', 'youzify_share_activity_nonce' );

		// Get Activity ID.
		$activity_id = isset( $_POST['activity_id'] ) ? absint( $_POST['activity_id'] ) : false;

		if ( ! $activity_id ) {
			die( json_encode( array( 'remove_button' => true, 'error' => __( 'Nothing found!', 'youzify' ) ) ) );
		}

		// Get activity ID.
		$activity = new BP_Activity_Activity( $activity_id );

		// Get Activity ID for a shared post.
		if ( apply_filters( 'youzify_disable_sharing_shared_posts', true ) ) {
			if ( $activity->type == 'activity_share' ) {
				$activity_id = $activity->secondary_item_id;
			}
		}

	    // Args
	    $modal_args = array(
	    	'title' => __( 'Share Activity', 'youzify' ),
	    	'title_icon' => 'far fa-share-square',
	        'modal_type' => 'div',
	        'hide-action' => true,
	        'show_close' => false,
	        'id'        => 'youzify-share-activity-form',
	        'button_id' => 'youzify-share-activity',
	    );

	    // Get User Share Form.
		ob_start();
	    youzify_modal( $modal_args, array( $this, 'form_content' ) );
	    $form = ob_get_contents();
	    ob_end_clean();

	    // Get User Post Preview.
		ob_start();

		global $wp_embed;

		if ( bp_use_embed_in_activity() ) {
			add_filter( 'bp_get_activity_content_body', array( $wp_embed, 'autoembed' ), 8 );
		}

	    youzify_activity()->get_wall_shared_post( $activity_id );

	    $preview = ob_get_contents();

	    ob_end_clean();

	    // Send Result.
		wp_send_json_success(
			array(
				'activity_id' => $activity_id,
				'form' => $form,
				'posts_emojis' => youzify_option( 'youzify_enable_posts_emoji', 'on' ),
				'preview' => $preview,
				'show_all' => '<div class="youzify-show-all-less"><div class="youzify-show-all"><i class="fas fa-arrow-down"></i>' . __( 'Show All', 'youzify' ) . '</div><div class="youzify-collapse"><i class="fas fa-arrow-up"></i>' . __( 'Collapse', 'youzify' ) . '</div></div>'
			)
		);

	}

	/**
	 * Form Content
	 */
	function form_content() {

		// Hide Groups Post In Hidden Fields.
		add_filter( 'youzify_show_group_postin_hidden_fields', '__return_false' );

		// Add Custom Fields
		add_action( 'youzify_wall_before_submit_form_action', array( $this, 'add_share_form_custom_fields' )  );

		?>

		<div id="youzify-share-activity-wrapper">
			<?php bp_get_template_part( 'activity/post-form' ); ?>
		</div>

		<?php

	}

	/**
	 * Add Post in Button
	 */
	function add_share_form_custom_fields() {

		// Add Post In Button.
		$this->post_in_button();

		?>
		<input type="hidden" name="youzify_share_form" value="true">
		<?php if ( bp_is_active( 'groups' ) && bp_is_group() ) : ?>
		<input type="hidden" name="youzify_share_form_current_group" value="<?php echo bp_get_current_group_id(); ?>">
		<?php
		endif;
	}

	/**
	 * Add Post in Button
	 */
	function post_in_button() {

		if ( ! bp_is_active( 'groups' ) || ( ! bp_is_my_profile() && ! bp_is_group() ) ) {
			return;
		}

		$show_all_options = true;

		if ( bp_is_group() ) {
			$group = groups_get_group( array( 'group_id' => bp_get_current_group_id() ) );
			if ( $group->status != 'public' ) {
				$show_all_options = false;
			}
		}

		if ( ! $show_all_options ) {
			echo '<style type="text/css">#whats-new-post-in-box select, #whats-new-post-in-box .nice-select {pointer-events: none; } #whats-new-post-in-box .nice-select:after {display:none;}</style>';
		}

		?>

		<div id="whats-new-post-in-box">

			<label for="whats-new-post-in" ><?php _e( 'Post in:', 'youzify' ); ?></label>
			<select id="whats-new-post-in" name="whats-new-post-in">
				<?php if ( $show_all_options ) : ?>
				<option selected="selected" value="0"><?php _e( 'My Profile', 'youzify' ); ?></option>

				<?php if ( bp_has_groups( 'user_id=' . bp_loggedin_user_id() . '&type=alphabetical&max=100&per_page=100&populate_extras=0&update_meta_cache=0' ) ) :
					while ( bp_groups() ) : bp_the_group(); ?>

						<option value="<?php bp_group_id(); ?>"><?php bp_group_name(); ?></option>

					<?php endwhile;
				endif; ?>
				<?php else: ?>
					<option value="<?php bp_current_group_id(); ?>"><?php bp_current_group_name(); ?></option>
				<?php endif; ?>
			</select>
		</div>
		<input type="hidden" id="whats-new-post-object" name="whats-new-post-object" value="groups">

		<?php
	}

	/**
	 * Handle Sticky Posts
	 */
	function handle_sticky_posts() {

		// Hook.
		do_action( 'youzify_before_handle_sticky_posts' );

		// Check Ajax Referer.
		check_ajax_referer( 'youzify-nonce', 'security' );

		if ( ! is_user_logged_in() ) {
			$data['error'] = __( 'The action you have requested is not allowed.', 'youzify' );
			die( json_encode( $data ) );
		}

		// Get Data.
		$data = array();

		// Allowed Actions
		$allowed_actions = array( 'pin', 'unpin' );

		// Get Data.
		$post_id = isset( $_POST['post_id'] ) ? absint( $_POST['post_id'] ) : null;
		$action = isset( $_POST['operation'] ) ? sanitize_text_field( $_POST['operation'] ) : null;
		$component = isset( $_POST['component'] ) ? sanitize_text_field( $_POST['component'] ) : null;

		// Check if The Post ID & The Component are Exist.
		if ( empty( $post_id ) || empty( $component ) ) {
			$data['error'] = __( "Sorry we didn't receive enough data to process this action.", 'youzify' );
			die( json_encode( $data ) );
		}

		// Check Requested Action.
		if ( empty( $action ) || ! in_array( $action, $allowed_actions ) ) {
			$data['error'] = __( 'The action you have requested does not exist.', 'youzify' );
			die( json_encode( $data ) );
		}

		// Get All Sticky Posts.
		$sticky_posts = youzify_option( 'youzify_' . $component . '_sticky_posts', array() );

		// Add the new pinned post.
		if ( 'pin' == $action ) {

			if ( 'groups' == $component ) {

				// Get Activity.
				$activity = new BP_Activity_Activity( $post_id );

				$sticky_posts[ $activity->item_id ][] = $post_id;

			} elseif ( 'activity' == $component ) {
				$sticky_posts[] = $post_id;
			}

			$data['action'] = 'unpin';
			$data['msg'] = __( 'The activity was pinned successfully', 'youzify' );

		} elseif ( 'unpin' == $action ) {

			if ( 'groups' == $component ) {

				// Get Activity.
				$activity = new BP_Activity_Activity( $post_id );

				if ( isset( $sticky_posts[ $activity->item_id ] ) ) {

					// Get Removed Post Key.
					$post_key = array_search( $post_id, $sticky_posts[ $activity->item_id ] );

					// Remove Post.
					if ( isset( $sticky_posts[ $activity->item_id ][ $post_key ] ) ) {
						unset( $sticky_posts[ $activity->item_id ][ $post_key ] );
					}

				}

			} elseif ( 'activity' == $component ) {

				// Get Removed Post Key.
				$post_key = array_search( $post_id, $sticky_posts );

				// Remove Post.
				if ( isset( $sticky_posts[ $post_key ] ) ) {
					unset( $sticky_posts[ $post_key ] );
				}

			}

			$data['action'] = 'pin';
			$data['msg'] = __( 'The activity is unpinned successfully', 'youzify' );
		}

		// Update Sticky Posts.
		if ( ! empty( $sticky_posts ) ) {
			update_option( 'youzify_' . $component . '_sticky_posts', $sticky_posts, 'no' );
		} else {
			delete_option( 'youzify_' . $component . '_sticky_posts' );
		}

		// Add Pin/Unpin Strings.
		$data['pin'] = __( 'Pin', 'youzify' );
		$data['unpin'] = __( 'Unpin', 'youzify' );

		die( json_encode( $data ) );

	}

	/**
	 * Handle Posts Bookmark
	 */
	function handle_posts_bookmark() {

		// Hook.
		do_action( 'youzify_before_handle_bookmark_posts' );

		// Check Ajax Referer.
		check_ajax_referer( 'youzify-nonce', 'security' );

		if ( ! is_user_logged_in() ) {
			$response['error'] = __( 'The action you have requested is not allowed.', 'youzify' );
			die( json_encode( $response ) );
		}

		// Allowed Actions
		$allowed_actions = array( 'save', 'unsave' );

		// Get Table Data.
		$data = array(
			'user_id' => bp_loggedin_user_id(),
			'item_id' => isset( $_POST['item_id'] ) ? absint( $_POST['item_id'] ) : null,
			'item_type' => isset( $_POST['item_type'] ) ? sanitize_text_field( $_POST['item_type'] ) : null,
			'collection_id' => isset( $_POST['collection_id'] ) ? absint( $_POST['collection_id'] ) : '0'
		);

		// Get Data.
		$action = isset( $_POST['operation'] ) ? sanitize_text_field( $_POST['operation'] ) : null;

		// Check if The Post ID & The Component are Exist.
		if ( empty( $data['item_id'] ) || empty( $data['item_type'] ) ) {
			$response['error'] = __( "Sorry we didn't receive enough data to process this action.", 'youzify' );
			die( json_encode( $response ) );
		}

		// Check Requested Action.
		if ( empty( $action ) || ! in_array( $action, $allowed_actions ) ) {
			$response['error'] = __( 'The action you have requested does not exist.', 'youzify' );
			die( json_encode( $response ) );
		}

		// Check if user Already Bookmarked Post ( Returns ID ).
		$bookmark_id = youzify_get_bookmark_id( $data['user_id'], $data['item_id'], $data['item_type'] );

		global $wpdb, $Youzify_bookmark_table;

		if ( 'save' == $action ) {

			// Check is post already bookmarked !
			if ( $bookmark_id ) {
				$response['error'] = __( 'This item is already bookmarked.', 'youzify' );
				die( json_encode( $response ) );
			}

			// Get Current Time.
			$data['time'] = bp_core_current_time();

			// Insert Post.
			$result = $wpdb->insert( $Youzify_bookmark_table, $data );

			if ( $result ) {
				do_action( 'youzify_after_bookmark_save', $wpdb->insert_id, $data['user_id'] );
			}

			$response['action'] = 'unsave';
			$response['msg'] = __( 'The item was bookmarked successfully', 'youzify' );

		} elseif ( 'unsave' == $action ) {

			// Hook.
			do_action( 'youzify_before_bookmark_delete', $bookmark_id, $data['user_id'] );

			$delete_bookmark = $wpdb->delete( $Youzify_bookmark_table, array( 'id' => $bookmark_id ), array( '%d' ) );

			$response['action'] = 'save';
			$response['msg'] = __( 'The bookmark was removed successfully', 'youzify' );
		}

		// Delete Transient.
	    delete_transient( 'youzify_user_bookmarks_' . $data['user_id'] );

		$response['unsave_post'] = __( 'Remove Bookmark', 'youzify' );
		$response['save_post'] = __( 'Bookmark', 'youzify' );

		die( json_encode( $response ) );

	}

}

new Youzify_Premium_Ajax();