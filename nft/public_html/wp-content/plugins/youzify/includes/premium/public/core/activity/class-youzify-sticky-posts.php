<?php
/**
 * Activity Sticky Posts Class
 */
class Youzify_Activity_Sticky_Posts {

	function __construct( ) {

		// Actions.
		add_action( 'youzify_before_activity_loop_posts', array( $this, 'add_activity_sticksy_posts' ) );

		// Filters.
		add_filter( 'youzify_activity_tools', array( $this, 'add_pin_posts_tool' ), 10, 2 );
		add_filter( 'bp_ajax_querystring', array( $this, 'exclude_sticky_posts' ), 999, 2 );

		// Delete Sticky Posts On Delete Activity.
		add_action( 'bp_activity_after_delete', array( $this, 'delete_sticky_posts' ) );

	}

	/**
	 * Add New Activity Tool.
	 */
	function add_pin_posts_tool( $tools, $post_id ) {

		if ( ! $this->is_user_can_pin_posts() ) {
			return $tools;
		}

		if ( $this->is_post_pinned( $post_id ) ) {
			// Get Unpin Button Data.
			$action = 'unpin';
			$class  = 'youzify-unpin-post';
			$title  = __( 'Unpin', 'youzify' );
			$icon   = 'fas fa-thumbtack fa-flip-vertical';
		} else {
			// Get Pin Button Data.
			$action = 'pin';
			$icon   = 'fas fa-thumbtack';
			$class  = 'youzify-pin-post';
			$title  = __( 'Pin', 'youzify' );
		}

		// Get Tool Data.
		$tools[] = array(
			'icon' => $icon,
			'title' => $title,
			'action' => $action,
			'class' => array( 'youzify-pin-tool', $class )
		);

		return $tools;
	}

	/**
	 * Check if Activity is Pinned
	 */
	function is_post_pinned( $activity_id = null ) {


		// Get Sticky Activities.
		$sticky_activities = $this->get_sticky_posts();

		if ( empty( $activity_id ) || empty( $sticky_activities ) ) {
			return false;
		}

		if ( ! in_array( $activity_id, $sticky_activities ) ) {
			return false;
		}

		return true;

	}

	/**
	 * Exclude Sticky Activities
	 */
	function exclude_sticky_posts( $query, $object = null ) {

		if ( $object != 'activity' || isset( $_GET['s'] ) ) {
			return $query;
		}

		// Get Posts Per Page Number.
		$sticky_posts = $this->get_sticky_posts_ids();

		if ( empty( $sticky_posts ) ) {
			return $query;
		}

		if ( ! empty( $query ) ) {
	        $query .= '&';
	    }

	    // Convert Query into Args.
	    $args = wp_parse_args( $query );

	    // Exclude Activities.
	    if ( ! empty( $args['exclude'] ) ) {
			$query .= 'exclude=' . $args['exclude'] . ',' . $sticky_posts;
	    } else {
			$query .= 'exclude=' . $sticky_posts;
	    }

		return $query;
	}

	/**
	 * Add Sticky Posts.
	 */
	function add_activity_sticksy_posts() {

		if ( isset( $_POST['page'] ) && $_POST['page'] > 1 ) {
			return;
		}

		// Get Sticky Posts ID's.
		$posts_ids = $this->get_sticky_posts_ids();

		if ( empty( $posts_ids ) ) {
			return;
		}

		global $activities_template;

		$old_activities_template = $activities_template;

		if ( bp_has_activities( array( 'in' => $posts_ids, 'per_page' => count( explode( ',', $posts_ids ) ) , 'show_hidden' => 1, 'display_comments' => 'threaded' ) ) ) {

			add_filter( 'bp_get_activity_css_class', array( $this, 'add_pinned_post_class' ) );
			add_filter( 'youzify_activity_new_post_action', array( $this, 'activity_pinned_tag' ), 10, 2 );

			while ( bp_activities() ) : bp_the_activity();
				bp_get_template_part( 'activity/entry' );
			endwhile;

			remove_filter( 'bp_get_activity_css_class', array( $this, 'add_pinned_post_class' ) );
			remove_filter( 'youzify_activity_new_post_action', array( $this, 'activity_pinned_tag' ), 10, 2 );

		}

		$activities_template = $old_activities_template;

	}

	/**
	 * Add "Pinned Tag" to activity.
	 */
	function activity_pinned_tag( $action, $activity ) {
		return $action . apply_filters( 'youzify_activity_pinned_tag', '<span class="youzify-pinned-post-tag"><i class="fas fa-thumbtack"></i><span>' . __( 'Pinned Post', 'youzify' ) . '</span></span>' );
	}

	/**
	 * Add Pinned Activity Class
	 */
	function add_pinned_post_class( $class ) {

		// Remove Data Class.
		return str_replace( 'date-recorded-', 'date-', $class . ' youzify-pinned-post' );

	}

	/**
	 * Get Sticky Posts ID's ( String )
	 */
	function get_sticky_posts_ids( $component = null, $group_id = null ) {

		// Get Stikcy Posts Array
		$sticky_posts = $this->get_sticky_posts( $component, $group_id );

		// Convert Ids into a list seprarated with comas
		return implode( ',', (array) $sticky_posts );

	}

	/**
	 * Get Sticky Posts.
	 */
	function get_sticky_posts( $component = null, $group_id = null ) {

		// Get Component.
		$component = bp_is_groups_component() ? 'groups' : 'activity';

		// Get Group ID.
		if ( bp_is_active( 'groups' ) ) {
			$group_id = ! empty( $group_id ) ? $group_id : bp_get_current_group_id();
		}

		// Get Sticky Posts ID's
		$posts_ids = apply_filters( 'youzify_get_sticky_posts', youzify_option( 'youzify_' . $component . '_sticky_posts' ), $component, $group_id );

		// Get Group Sticky Posts ID's.
		if ( 'groups' == $component ) {
			$posts_ids = isset( $posts_ids[ $group_id ] ) ? $posts_ids[ $group_id ] : array();
		}

		// Remove Duplicated Values.
		$posts_ids = is_array( $posts_ids ) ? array_unique( $posts_ids ) : $posts_ids;

		return $posts_ids;

	}

	/**
	 * Check is User Can Pin Activities.
	 */
	function is_user_can_pin_posts() {

		if ( ! is_user_logged_in() ) {
			return false;
		}

		// Get Components
		$component = bp_current_component() == 'groups' ? 'groups' : 'activity';

		if ( 'off' == youzify_option( 'youzify_enable_' . $component . '_sticky_posts', 'on' ) ) {
			return false;
		}

		if ( bp_is_active( 'groups' ) && bp_is_group() && bp_group_is_admin() ) {
			return true;
		}

		// Get Current User Data.
		$user = wp_get_current_user();

		// Filter Roles.
		$allowed_roles = apply_filters( 'youzify_allowed_roles_to_pin_posts', array( 'administrator' ) );

		foreach ( $allowed_roles as $role ) {
			if ( in_array( $role, (array) $user->roles ) ) {
				return true;
			}
		}

		return false;

	}

	/**
	 * Delete Sticky
	 */
	function delete_sticky_posts( $activities ) {

    	foreach ( $activities as $activity ) {

			// Get All Sticky Posts.
			$sticky_posts = youzify_option( 'youzify_' . $activity->component . '_sticky_posts' );

			if ( empty( $sticky_posts ) ) {
				continue;
			}

			switch ( $activity->component ) {

				case 'groups':

					if ( is_array( $sticky_posts[ $activity->item_id ] ) ) {

						// Get Removed Post Key.
						$post_key = array_search( $activity->id, $sticky_posts[ $activity->item_id ] );

						// Remove Post.
						if ( $post_key !== false && isset( $sticky_posts[ $activity->item_id ][ $post_key ] ) ) {
							unset( $sticky_posts[ $activity->item_id ][ $post_key ] );
						}

					}

					break;

				case 'activity':

					// Get Removed Post Key.
					$post_key = array_search( $activity->id, $sticky_posts );

					// Remove Post.
					if ( $post_key !== false && isset( $sticky_posts[ $post_key ] ) ) {
						unset( $sticky_posts[ $post_key ] );
					}

					break;

			}

			// Update Sticky Posts.
			update_option( 'youzify_' . $activity->component . '_sticky_posts', $sticky_posts, 'no' );

		}
	}

}

new Youzify_Activity_Sticky_Posts();