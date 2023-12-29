<?php
/**
 * Activity Tag Users
 */
class Youzify_Activity_Tag_Users {

	function __construct( ) {

		// Add Tool
		add_action( 'bp_activity_after_post_form_tools', array( $this, 'tool' ) );
		add_action( 'youzify_after_wall_post_form_textarea', array( $this, 'search_box' ) );

		// Hide Private Users Posts.
		add_filter( 'youzify_activity_post_tagged_users', array( $this, 'action' ), 10, 2 );

		// Handle Save Form Post - Ajax Request.
		add_action( 'wp_ajax_youzify_tag_users_get_user_friends', array( $this, 'get_user_friends' ) );

		// Show Tagged Users Modal
		add_action( 'wp_ajax_youzify_activity_tagged_users_modal', array( $this, 'tagged_users_modal' ) );
		add_action( 'wp_ajax_nopriv_youzify_activity_tagged_users_modal', array( $this, 'tagged_users_modal' ) );

		// Notifications.
		add_action( 'bp_actions', array( $this, 'mark_tag_notifications_as_read' ) );
		add_action( 'youzify_after_activity_tagged_users_save', array( $this, 'add_notification' ), 10, 2 );

	}

	/**
	 * Show Tagged Users.
	 */
	function action( $action, $activity ) {

		// Get Tagged Users.
		$users = bp_activity_get_meta( $activity->id, 'tagged_users' );

		if ( ! empty( $users ) ) {

			$count = count( $users );

			if ( $count == 1 ) {
				$tagged_users = sprintf( __( 'with %s', 'youzify' ), bp_core_get_userlink( $users[0] ) );
			} else if ( $count == 2 ) {
				$tagged_users = sprintf( __( 'with %1s and %2s', 'youzify' ), bp_core_get_userlink( $users[0] ), bp_core_get_userlink( $users[1] ) );
			} else {
				$tagged_users = sprintf( __( 'with %1s and <a href="#" class="youzify-show-tagged-users">%2s others</a>', 'youzify' ), bp_core_get_userlink( $users[0] ), $count - 1 );
			}

			return ' ' . $tagged_users;

		}

		return false;

	}

	/**
	 * Add Tag Users Tool
	 */
	function tool() {

		if ( ! apply_filters( 'youzify_enable_activity_form_tagged_users', true ) ) {
			return;
		}

		?>
		<div class="youzify-tag-users-tool youzify-form-tool" data-youzify-tooltip="<?php _e( 'Tag Friends', 'youzify' ); ?>"><i class="fas fa-user-tag"></i></div>
		<?php
	}


	/**
	 * Tag Users
	 */
	function search_box() {

		?>

		<div class="youzify-wall-list youzify-wall-tagusers">

			<div class="youzify-list-selected-items youzify-tagged-users">
				<div class="youzify-list-items-title youzify-tagusers-with-title"><?php echo apply_filters( 'youzify_wall_form_tag_users_with_title', __( 'with', 'youzify' ) ); ?></div>
			</div>

			<div class="youzify-list-search-form youzify-tagusers-form">
				<div class="youzify-list-search-box youzify-tagusers-search-box">

					<div class="youzify-list-search-container">
						<div class="youzify-list-search-icon youzify-tagusers-search-icon"><i class="fas fa-search"></i></div>
						<input type="text" class="youzify-list-search-input youzify-tagusers-search-input" name="tagusers_search" placeholder="<?php _e( 'Search your friends!', 'youzify' ); ?>">
						<div class="youzify-list-close-icon youzify-tagusers-search-icon youzify-tagusers-close-icon"><i class="fas fa-times"></i></div>
					</div>

				</div>

				<div class="youzify-wall-list-items youzify-wall-tagusers-list"></div>

			</div>

		</div>

		<?php

	}

	/**
	 * Get User Friends.
	 */
	function get_user_friends() {

		// Get User Friends.
		$user_friends = friends_get_friend_user_ids( bp_loggedin_user_id() );

		// Get Current User Friends.
		$friends = apply_filters( 'youzify_tag_users_friends_list', $user_friends );

		ob_start();

		if ( empty( $friends ) ) { ?>
			<div class="youzify-list-notice"><i class="fas fa-user-times"></i><?php _e( 'No friends found !', 'youzify' ); ?></div>
		<?php } else {

			foreach ( $friends as $user_id ) {

			?>

			<div class="youzify-list-item" data-user-id="<?php echo $user_id; ?>">
				<a class="youzify-item-img"  href="<?php echo bp_core_get_user_domain( $user_id ); ?>" style="background-image: url(<?php echo bp_core_fetch_avatar( array( 'html' => false, 'item_id' => $user_id ) ); ?>);"></a>
				<div class="youzify-item-content">
					<div class="youzify-item-left">
						<a href="<?php echo bp_core_get_user_domain( $user_id ); ?>" class="youzify-item-title"><?php echo bp_core_get_user_displayname( $user_id ); ?></a>
						<div class="youzify-item-description"><span>@</span><?php echo bp_core_get_username( $user_id ); ?></div>
					</div>
					<div class="youzify-item-right">
						<div class="youzify-item-button youzify-wall-tag-user"><?php _e( 'Select', 'youzify' ); ?></div>
					</div>
				</div>
			</div>

			<?php

			}
		}

		$content = ob_get_clean();

		wp_send_json_success( $content );

		die();
	}

	/**
	 * Get Post Tagged Users.
	 */
	function tagged_users_modal() {

		// Get Modal Args
		$args = array(
			'icon'  => 'fas fa-user-tag',
			'item_id'  => absint( $_POST['post_id'] ),
			'function' => 'youzify_get_activity_tagged_users',
			'title'    => __( 'People', 'youzify' )
		);

		// Get Modal Content
		youzify_wall_modal( $args );

		die();
	}

	/**
	 * Add User Tag Notification.
	 */
	function add_notification( $activity_id, $users ) {

		// Get Activity.
		$activity = new BP_Activity_Activity( $activity_id );

	    foreach ( $users as $user_id ) {

		    bp_notifications_add_notification(
		    	array(
			        'user_id'           => $user_id,
			        'item_id'           => $activity_id,
			        'secondary_item_id' => $activity->user_id,
			        'component_name'    => 'youzify',
			        'component_action'  => 'youzify_new_tag',
			        'date_notified'     => bp_core_current_time(),
			        'is_new'            => 1
		    	)
		    );

	    }
	}

	/**
	 * Mark Likes notifications as read when reading a topic
	 *
	 */
	function mark_tag_notifications_as_read( $action = '' ) {

		if ( ! bp_is_active( 'activity' ) || ! bp_is_single_activity()  ) {
			return;
		}

		// Bail if no activity ID is passed
		if ( empty( $_GET['activity_id'] ) || ! isset( $_GET['action'] ) || $_GET['action'] != 'youzify_new_tag_mark_read' ) {
			return;
		}

		// Get required data
		$user_id  = bp_loggedin_user_id();
		$activity_id = intval( $_GET['activity_id'] );

		// Check nonce
		if ( ! isset( $_GET['_wpnonce'] ) || ! wp_verify_nonce( $_GET['_wpnonce'], 'youzify_new_tag_mark_read_' . $activity_id ) || ! current_user_can( 'edit_user', $user_id ) ) {
		    bp_core_add_message( __( "Sorry you don't have permission to do that!", 'youzify' ), 'error' );
			return;
		}

		// Attempt to clear notifications for the current user from this topic
		$success = bp_notifications_mark_notifications_by_item_id( $user_id, $activity_id, 'youzify', 'youzify_new_tag' );

		// Do additional subscriptions actions
		do_action( 'youzify_notifications_mark_tag_notifications_as_read', $success, $user_id, $activity_id, $action );

		// Redirect to the topic
		$redirect = bp_activity_get_permalink( $activity_id );

		// Redirect
		wp_safe_redirect( $redirect );

		// For good measure
		exit();
	}

}

new Youzify_Activity_Tag_Users();