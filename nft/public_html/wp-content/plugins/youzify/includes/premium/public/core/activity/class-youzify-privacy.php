<?php
/**
 * Activity Privacy
 */
class Youzify_Activity_Privacy {

	function __construct( ) {

		// Add Tool
		add_action( 'bp_activity_before_post_form_tools', array( $this, 'tool' ) );

		// Hide Private Users Posts.
		add_filter( 'bp_activity_get_where_conditions', array( $this, 'where' ), 2, 2 );

		// Add Post Privacy.
		add_filter( 'youzify_insert_activity_meta', array( $this, 'add_posts_privacy' ), 10, 3 );

		// Check Sticy Post Visibility.
		add_filter( 'youzify_get_sticky_posts', array( $this, 'hide_private_sticky_posts' ), 10, 2 );

	}

	/**
	 * Check Sticky Post Visibilyty.
	 */
	function hide_private_sticky_posts( $posts_ids, $component ) {

		if ( $component == 'groups' || empty( $posts_ids ) ) {
			return $posts_ids;
		}

		foreach ( $posts_ids as $key => $post_id ) {

			$show_post = $this->sticky_post_visibility( $post_id );

			if ( ! $show_post ) {
				unset( $posts_ids[ $key ] );
			}

		}

		return $posts_ids;

	}

	/**
	 * Check Sticky Post Visibilyty.
	 */
	function sticky_post_visibility( $activity_id ) {

		// Set Default Visibility.
		$visibility = true;

		// Get Post Visibility.
		$privacy = $this->get_privacy( $activity_id );

		if ( $privacy == 'public'  ) {
			return true;
		}

		if ( ! is_user_logged_in() ) {
			return false;
		}

		switch ( $privacy ) {

			case 'onlyme':

				// Get Activity.
				$activity = new BP_Activity_Activity( $activity_id );

				if ( bp_loggedin_user_id() != $activity->user_id ) {
					$visibility = false;
				}

				break;

			case 'friends':

				if ( bp_is_active( 'friends' ) ) {

					// Get Activity.
					$activity = new BP_Activity_Activity( $activity_id );

					if ( bp_loggedin_user_id() != $activity->user_id && ! friends_check_friendship( bp_loggedin_user_id(), $activity->user_id ) ) {
						$visibility = false;
					}

				} else {
					$visibility = false;
				}

				break;

			case 'members':

				if ( ! is_user_logged_in() ) {
					$visibility = false;
				}

				break;

		}

		return $visibility;

	}

	/**
	 * Get Activity Privacy.
	 */
	function get_privacy( $activity_id ) {

		global $wpdb, $bp;

		// Prepare SQL
		$sql = $wpdb->prepare( "SELECT privacy from {$bp->activity->table_name} WHERE id = %d", $activity_id );

		// Update Privacy
		return $wpdb->get_var( $sql );

	}

	/**
	 * Set Post Privacy.
	 **/
	function where( $where, $args ) {

		if ( is_super_admin() || bp_is_my_profile() || ( bp_is_active( 'groups' ) && bp_is_group() ) || ( isset( $args['in'] ) && ! empty( $args['in'] ) ) ) {
			return $where;
		}

		if ( ! is_user_logged_in() ) {
			$where['nonloggedin'] = 'a.privacy = "public"';
			return $where;
		}

		$current_user = bp_loggedin_user_id();

		$privacy = array();

		if ( ! is_user_logged_in() ) {
			$privacy[] = "'members'";
		}

		if ( bp_displayed_user_id() != $current_user ) {
			$privacy[] = "'onlyme'";
		}

		if ( bp_is_active( 'friends' ) && ! friends_check_friendship( $current_user, bp_displayed_user_id() ) ) {
			$privacy[] = "'friends'";
		}

		if ( ! empty( $privacy ) ) {

			$req = 'a.privacy NOT IN (' . implode( ',' , $privacy ) .  ')';

			if ( ! bp_is_user() ) {

				if ( bp_is_active( 'friends' ) ) {

					// Get Current User Friends.
					$friends = friends_get_friend_user_ids( bp_loggedin_user_id() );

					$friends[] = $current_user;

					if ( ! empty( $friends ) ) {

						$req .= ' OR a.user_id IN ( ' . implode( ',', $friends ). ') AND a.privacy = "friends"';

						if ( isset( $args['filter']['since'] ) && ! empty( $args['filter']['since'] ) ) {
							$req .= " AND a.date_recorded > '" . $args['filter']['since'] . "'";
						}

					}
				}

				if ( is_user_logged_in() ) {

					$req .= ' OR a.user_id = ' . $current_user . ' AND a.privacy = "onlyme"';

					if ( isset( $args['filter']['since'] ) && ! empty( $args['filter']['since'] ) ) {
						$req .= " AND a.date_recorded > '" . $args['filter']['since'] . "'";
					}

				}

			}

			$where['privacy'] = $req;

		}

		return $where;

	}

	/*
	 * Add Privacy Button.
	 */
	function tool() {

		if ( ! apply_filters( 'youzify_enable_activity_form_privacy', true ) ) {
			return;
		}

		if ( bp_is_group() ) {
			return;
		}

		$options = $this->privacy_options();

		?>

		<div class="youzify-privacy-tool" data-youzify-tooltip="<?php _e( 'Who should see this?', 'youzify' ); ?>">
			<span class="youzify-privacy-title"><?php _e( 'Privacy:', 'youzify' ); ?></span>
			<select name="youzify-activity-privacy" class="youzify-activity-privacy youzify-btn">
				<?php foreach ( $options as $key => $option) : ?>
				<option value="<?php echo $key; ?>"><i class="<?php echo $option['icon']; ?>"></i><?php echo $option['title']; ?></option>
				<?php endforeach; ?>
			</select>
		</div>

		<?php
	}

	/**
	 * Activity Privacy Options.
	 */
	function privacy_options() {

		$options = array(
			'public' => array( 'title' => __( 'Public', 'youzify' ), 'icon' => 'fas fa-globe-asia' ),
			'onlyme' => array( 'title' => __( 'Only Me', 'youzify' ), 'icon' => 'fas fa-lock' ),
			'friends' => array( 'title' => __( 'My Friends', 'youzify' ), 'icon' => 'fas fa-user-friends' ),
			'members' => array( 'title' => __( 'Members', 'youzify' ), 'icon' => 'fas fa-users' ),
		);

		return apply_filters( 'youzify_wall_activity_privacy_options', $options );

	}

	/**
	 * Add Post Privacy.
	 */
	function add_posts_privacy( $time, $default_content, $activity_id ) {

		global $activities_template;

		$options = $this->privacy_options();

		if ( isset( $_POST['youzify-activity-privacy'] ) ) {
			$privacy = sanitize_text_field( $_POST['youzify-activity-privacy'] );
			$activities_template->activity->privacy = $privacy;
		} elseif ( ! empty( $activities_template->activity->privacy ) ) {
			$privacy = $activities_template->activity->privacy;
		} else {
			$privacy = $this->get_privacy( $activity_id );
		}

		$content = '<i class="' . $options[ $privacy ]['icon'] . '"></i>';

		return $content . '<span class="youzify-separator-point">•</span>' . $time;
	}

}

new Youzify_Activity_Privacy();