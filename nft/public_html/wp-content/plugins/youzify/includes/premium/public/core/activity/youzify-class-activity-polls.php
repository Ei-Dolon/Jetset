<?php

/**
 * Poll Activity.
 */
class Youzify_Poll_Activity {

	function __construct() {

		// Filters.
		add_filter( 'youzify_wall_show_everything_filter_actions', array( $this, 'add_show_everything_poll_filter' ) );
		add_filter( 'youzify_wall_post_types_visibility', array( $this, 'enable_poll_activity_posts' ) );
		add_filter( 'youzify_get_activity_content_body', array( $this, 'activity_poll_post_content' ), 20, 2 );

		// Ajax Call
		add_action( 'wp_ajax_youzify_activity_revote_content', array( $this, 'activity_revote_content' ) );
		add_action( 'wp_ajax_nopriv_youzify_activity_revote_content', array( $this, 'activity_revote_content' ) );
		add_action( 'wp_ajax_youzify_activity_poll_new_vote', array( $this, 'activity_poll_new_vote' ) );
		add_action( 'wp_ajax_youzify_activity_all_voters', array( $this, 'activity_all_voters' ) );
		add_action( 'wp_ajax_nopriv_youzify_activity_all_voters', array( $this, 'activity_all_voters' ) );
		add_action( 'wp_ajax_youzify_activity_result_content', array( $this, 'activity_result_content' ) );
		add_action( 'wp_ajax_nopriv_youzify_activity_result_content', array( $this, 'activity_result_content' ) );

	}

	/**
	 * Show All Voters.
	 */
	function activity_all_voters() {

		// Get Modal Args
		$args = array(
			'icon'  => 'fas fa-user-tag',
			'args'  => array( 'activity_id' => $_POST['activity_id'], 'option_id' => $_POST['option_id'] ),
			'function' => array( $this, 'get_voters_popup' ),
			'title'    => __( 'People who voted for this option', 'youzify' )
		);

		ob_start();

		// Get Modal Content
		youzify_wall_modal( $args );

		$result_html = ob_get_contents();

		ob_end_clean();

		wp_send_json_success( $result_html );

	}

	/**
	 * Show Popup of All Voters.
	 */
	function get_voters_popup( $args ) {

		global $wpdb;

		// Create SQL requete;
		$sql = $wpdb->prepare( 'SELECT user_id FROM ' . $wpdb->prefix . 'youzify_activity_polls_votes' .' WHERE activity_id = %d AND option_id = %d', $args['activity_id'], $args['option_id'] );

		// Execute SQL requete.
		$all_voters = $wpdb->get_col( $sql );

		// Get List of All Voters.
		youzify_get_popup_user_list( $all_voters );

	}

	/**
	 * Show Result.
	 */
	function activity_result_content() {

		// Get Activity.
		$youzify_poll_element = bp_activity_get_meta( $_POST['activity_id'], 'youzify_poll_element' );

		// Get Result.
		$results = bp_activity_get_meta( $_POST['activity_id'], 'youzify_polls_result' );

		ob_start();

		$this->poll_results( $results , $youzify_poll_element );

		$result_html = ob_get_contents();

		ob_end_clean();

		wp_send_json_success( $result_html );
	}

	/**
	 * Process Activity Poll Revote.
	 */
	function activity_revote_content() {

		// Get Activity.
		$activity = new BP_Activity_Activity( $_POST['activity_id'] );

		ob_start();

		$this->get_post_content( $activity , true );

		$result_html = ob_get_contents();

		ob_end_clean();

		wp_send_json_success( $result_html );
	}

	/**
	 * Process New Activity Poll Vote.
	 */
	function activity_poll_new_vote() {

		global $wpdb;

		$table = $wpdb->prefix . 'youzify_activity_polls_votes';
		
		if ( ! isset( $_POST['voting_options'] ) ) {
			// Show Error.
			wp_send_json_error( __( 'Sorry, you should at least choose one option.', 'youzify' ) );
		}
		
		// Get Voting Options
		$voting_options = $_POST['voting_options'];

		// Get Poll Post Options.
		$poll_post_options = get_option(
			'youzify_poll_post_options',
			array(
		    	'poll_revote' 		  => 'on',
		    	'options_result' 	  => 'on',
		    	'options_redirection' => 'result',
		    )
		);

		// Get Activity ID.
		$activity_id = $_POST['activity_id'];

		// Get Current User ID.
		$user_id = get_current_user_id();

		// Get if user voted
		$youzify_user_voted = bp_activity_get_meta( $activity_id , 'youzify_user_voted_' . $user_id );

		// Get Voting Results
		$results = bp_activity_get_meta( $activity_id , 'youzify_polls_result' );

		// Check if this user already voted and is not allowed to revote.
		if ( $youzify_user_voted == 1 && $poll_post_options['poll_revote'] != 'on' ) {
			wp_send_json_error( __( 'Sorry, you already voted.', 'youzify' ) );
		}

		// Check if someone voted.
		if ( ! empty( $results ) ) {

			// Check if this user already voted.
			if ( $youzify_user_voted == 1 ) {
				// Delete Old User Choice.
				$wpdb->delete( $table, array( 'user_id' => $user_id , 'activity_id' => $activity_id ), array( '%d', '%d' ) );

			}

			foreach ( $voting_options as $option ) {
				// Insert User Choice of Options.
				$wpdb->insert( $table , array( 'activity_id' => $activity_id , 'option_id' => $option , 'user_id' => $user_id ) , array('%d', '%d', '%d') );
							
			}

		} else {

			// Init Var.
			$results = array();

			// Fill Results.
			foreach ( $voting_options as $option ) {
				// Insert User Choice of Options.
				$wpdb->insert( $table, array( 'activity_id' => $activity_id , 'option_id' => $option , 'user_id' => $user_id ) , array( '%d', '%d','%d' ) );

			}
		}

		// Get Totale votes. 
		$sql = $wpdb->prepare( 'SELECT COUNT( user_id ) FROM ' .$table . ' where activity_id = %d' , $activity_id );

		// Get Total.
		$results['total_count'] = intval( $wpdb->get_var( $sql ) );

		// Get Poll Element.
		$youzify_poll_element = bp_activity_get_meta( $activity_id , 'youzify_poll_element' );

		// Prepare SQL
		foreach ( $youzify_poll_element['poll_options'] as $key => $option ) {

			// code...
			$sql = $wpdb->prepare( 'SELECT COUNT( distinct user_id ) FROM ' .$table . ' WHERE activity_id = %d AND option_id = %d' , $activity_id , $key );

			// Get Count
			$results[ $key ]['count'] = intval( $wpdb->get_var( $sql ) );

			$sql = $wpdb->prepare( 'SELECT user_id FROM ' .$table . ' WHERE activity_id = %d AND option_id = %d LIMIT 5', $activity_id , $key );

			// Get Voters
			$results[ $key ]['voters'] = $wpdb->get_col( $sql );

		}
		

		if ( ! empty( $results ) ) {

			// Save User Voted.
			bp_activity_update_meta( $activity_id , 'youzify_user_voted_' . $user_id, 1 );

			// Save Result.
			bp_activity_update_meta( $activity_id , 'youzify_polls_result', $results ); 

		} else {

			// Delete User Voted.
			bp_activity_delete_meta( $activity_id , 'youzify_user_voted_' . $user_id );

			// Delete Result.
			bp_activity_delete_meta( $activity_id , 'youzify_polls_result' ); 

		}

		ob_start();

		// Show Result If Allowed.
		if ( $poll_post_options['options_redirection'] == 'result' && $poll_post_options['options_result'] == 'on' ) {

			// Get Poll Results
			$this->poll_results( $results , $youzify_poll_element );
			
		} else {

			// Show Message No Redirection.
			wp_send_json_success(
				array(
					'msg' => __( 'Thanks, your vote has been counted.', 'youzify' ),
					'result'=> 1
				)
			);

		}

		$result_html = ob_get_contents();

		ob_end_clean();

		wp_send_json_success( $result_html );

	}

	/**
	 * Poll Post Content
	 */
	function activity_poll_post_content( $content, $activity ) {

		if ( 'activity_poll' == $activity->type && ( ! isset( $_POST['action'] ) || $_POST['action'] != 'new_activity_comment' ) ) {
			$content = $this->get_post_content( $activity );
		}

		return $content;
	}

	/**
	 * Poll Post Content
	 */
	function get_post_content( $activity , $get_form = false )	{

		// Get Element.
		$youzify_poll_element = bp_activity_get_meta( $activity->id , 'youzify_poll_element' );

		if ( empty( $youzify_poll_element  ) ) {
			return;
		}
		
		// Get Result.
		$results = bp_activity_get_meta( $activity->id , 'youzify_polls_result' );

		// Get User If already Voted. 
		$youzify_user_voted = bp_activity_get_meta( $activity->id , 'youzify_user_voted_' . get_current_user_id() );

		// Get Poll Post Option.
		$poll_post_options = get_option(
			'youzify_poll_post_options',
			array(
		    	'poll_revote' 		  => 'on',
		    	'options_result' 	  => 'on',
		    	'options_redirection' => 'result',
		    )
		);

		// If User isnt Allowed To See Result Redirection. 
		$youzify_show_result = $poll_post_options['options_redirection'] == 'result' && $poll_post_options['options_result'] == 'on' && $youzify_user_voted == 1 ? 1 : 0;
		
		ob_start();

		?>

			<div class="activity-inner">
			    <p><?php echo stripslashes_deep( $activity->content ); ?></p>
			</div>

			<div class="youzify-poll-content" data-activity-id="<?php echo $activity->id; ?>">
			    <div class="youzify-poll-inner-content">
			        <div class="youzify-poll-question youzify-current-bg-color"><i class="fas fa-poll-h"></i><?php echo $youzify_poll_element['question']; ?></div>
				        <div class="youzify-poll-holder"><?php

							if ( ! $get_form && $youzify_show_result == 1 ) {
								// Get Poll Result
								$this->poll_results( $results , $youzify_poll_element );

							} else {
								// Get Poll Option
								$this->poll_options( $activity->id, $youzify_poll_element, $results, $youzify_user_voted );

							}
						?>
				    </div>
			    </div>
			</div>

		<?php

		ob_flush();

		$content = ob_get_contents();

		ob_end_clean();

		return $content;
	}

	/**
	 * Get Poll Options.
	 */
	function poll_options( $activity_id, $youzify_poll_element, $results, $youzify_user_voted ) {

		// Get Poll Post Options
		$poll_post_options = get_option(
			'youzify_poll_post_options',
			array(
		    	'poll_revote' 		  => 'on',
		    	'options_result' 	  => 'on',
		    	'options_redirection' => 'result',
		    )
		);


		// Init Var.
		$voted = array();

		// If User Voted
		if ( $youzify_user_voted == 1 ) {

			global $wpdb;

			$sql = $wpdb->prepare( 'SELECT option_id FROM ' . $wpdb->prefix . 'youzify_activity_polls_votes WHERE activity_id = %d AND user_id = %d', $activity_id , get_current_user_id() );

			// Get User Vote.
			$voted = $wpdb->get_col( $sql );

		}


		$image_enable = '';

		$button_disable = $youzify_user_voted != 1 ? 'youzify-submit-vote youzify-disable-vote': 'youzify-submit-vote';

		$button_disable = $poll_post_options['poll_revote'] == 'off' && $youzify_user_voted == 1 ? 'youzify-submit-vote youzify-disable-vote' : $button_disable;

		$checkbox_disable = $poll_post_options['poll_revote'] == 'off' && $youzify_user_voted == 1 && is_user_logged_in() ? 'disabled' : '';

		$is_user_logged_in = is_user_logged_in();
		?>

		<!-- If the user didn't check the multi select options -->
		<div class="youzify-poll-options-holder">
			<div class="youzify-poll-options">

			    <?php
					// Check If Multiple Or Single Choice.
					$check_box = $youzify_poll_element['allow_multiple_options'] == 1 ?  'checkbox' : 'radio';

					foreach ( $youzify_poll_element[ 'poll_options' ] as $key => $option ) {

						if ( isset( $option['attachment'] ) ) {

							// Get Image Attachment URL.
							$img_url = wp_get_attachment_url( $option['attachment'] );

							// Set URL.
							$poll_img = '<a href="' . $img_url . '" rel="nofollow" class="youzify-poll-image-option" data-youzify-lightbox="youzify-post-' . $activity_id . '"><img loading="lazy" ' . youzify_get_image_attributes( $option['attachment'], 'youzify-thumbnail', 'activity-poll' ) . ' alt=""></a>';

						} else {

							// No Image.
							$poll_img = '';

						}

						// Check If User Voted in this Option.
						$checked = ! empty( $voted ) && in_array( $key, $voted ) ? 'checked' : '';

						$input_field = $is_user_logged_in ? '<input type="' . $check_box . '" class="youzify-poll-item-input youzify-current-checked-bg-color ' . $check_box . '" name="youzify_poll_option[]"  value="' . $key . '" ' . $checked . ' ' . $checkbox_disable . '/>' : '';

						// Show Poll Option.
						echo '<label class="youzify-poll-item-label">' . $input_field . $poll_img . '<span class="youzify-poll-item-title"> ' . $option['option'] . '</span></label>';

					}

				?>

			</div>

			<div class="youzify-poll-actions">
				<?php if ( is_user_logged_in() ) : ?>
				<?php if ( ( $poll_post_options['poll_revote'] == 'on' && $youzify_user_voted == 1 ) || $youzify_user_voted != 1 ) : 
					// Check If You User Can Revote.
					$data_revote = $poll_post_options['poll_revote'] == 'on' ? 'on': 'off';

				?>
			    <div class="<?php echo $button_disable; ?> youzify-current-bg-color" data-revote="<?php echo $data_revote; ?>" ><i class="fas fa-vote-yea"></i><?php _e( 'Submit your vote', 'youzify' ); ?></div>
			    <?php endif; ?>
			    <?php endif; ?>
			    <?php if ( $poll_post_options['options_result'] == 'on' ) : ?>
			    <div class="youzify-see-poll-results"><?php _e( 'See Results', 'youzify' ); ?><i class="fas fa-angle-double-right"></i>
			    </div>
			    <?php endif; ?>
			</div>
		</div>

	<?php

	}

	/**
	 * Get Poll Result.
	 */
	function poll_results( $results, $youzify_poll_element ) {


		$result_options = get_option(
			'youzify_poll_result_options',
			array(
				'limit_voters' 	  	   => '3',
				'list_voters' 		   => 'on',
				'options_image_enable' => 'off',
			)
		);

		?>

		<div class="youzify-poll-result-holder">
			<div class="youzify-poll-result ">
			    <?php

					foreach ( $youzify_poll_element[ 'poll_options' ] as $key => $option ) {

						$output = '';

						if ( isset( $option['attachment'] ) ) {
							// Get Image Attachment URL.
							$img = wp_get_attachment_image_src( $option['attachment'] );

							// Set URL.
							$poll_img = '<div class="youzify-result-image" style="background-image: url(' . $img[0] . ')"></div>';

						} else {
							// No Image.
							$poll_img = '';

						}

						if ( isset( $results[ $key ]['count'] ) && isset( $results['total_count'] ) && $results['total_count'] != 0 ) {
							// Get Count
							$count = $results[ $key ]['count'];
							// Calcul %
							$percent = $results[ $key ]['count'] / $results['total_count'] * 100;

							$percent = number_format( $percent , 0 , '.' , ' ' );

						} else {
							// Init Var
							$count = 0;
							$percent = 0;
						}
						// If User is Allowed To See Voters.
						if ( $result_options['list_voters'] == 'on' ) {
							// Check If Anybody Already Voted.
							if ( isset( $results[ $key ]['voters'] ) ) {
								// How Much I Can Show.
								$liked_count = $count - $result_options['limit_voters'];

								foreach ( $results[ $key ]['voters'] as $userIndex => $user_id ) {

									// Get Users Image.
									$img_path = bp_core_fetch_avatar(
										array(
											'item_id' => $user_id,
											'type'	  => 'thumb',
											'html' => true
										)
									);
									// How Much User Visible.
									if ( $userIndex < $result_options['limit_voters'] ) {

									// Get User Image Code.
		        					$output .= '<a data-youzify-tooltip="' . bp_core_get_user_displayname( $user_id ) . '" href="' . bp_core_get_user_domain ( $user_id ) . '">' . bp_core_fetch_avatar( array( 'html' => true, 'type' => 'thumb', 'item_id' => $user_id ) ) .'</a>';
									}

								}

								if ( isset( $output ) ) {

						        	if ( $liked_count > 0 ){
						        		// Display Show More.
										$output .='<a class="youzify-show-voters youzify-view-all" data-option-id="' . $key . '" data-youzify-tooltip="' . __( 'View All', 'youzify' ) . '">+' . $liked_count . '</a>';
						        	}

							    }
							}

						}
						// Display Result.
						echo '<div class="youzify-poll-item">
							<div class="youzify-result-head">
								' . $poll_img . '
								<div class="youzify-result-title">' . $option['option'] . '</div>
							</div>
							<div class="youzify-result-votes"><div class="youzify-post-voted">' . $output . '</div><span class="youzify-voters-nbr">' . sprintf( _n( '%s Vote', '%s Votes', $count, 'youzify' ), $count ) . '</span></div>
							<div class="youzify-result-bar-area">
					            <div class="youzify-result-bar youzify-radius-bar" data-percent="' . $percent . '%"><div class="youzify-result-bar-color youzify-current-bg-color" style="width: ' . $percent . '%;"></div></div>
								<div class="youzify-result-count">' . $percent . '%</div>
							</div>
						</div>';
					}

				?>

		</div>

		<div class="youzify-poll-actions ">
		    <div class="youzify-see-poll-options"><i class="fas fa-angle-double-left"></i><?php _e( 'Back to Poll' , 'youzify' ); ?></div>
		</div>

	</div>

	<?php

	}

	/**
	 * Add "Pool" posts to appear in the activity stream default feed.
	 */
	function add_show_everything_poll_filter( $actions ) {
		$actions[] = 'activity_poll';
		return $actions;
	}

	/**
	 * Enable Activity Poll Posts Visibility.
	 */
	function enable_poll_activity_posts( $post_types ) {
		$post_types['activity_poll'] = youzify_option( 'youzify_enable_wall_polls' , 'on' );
		return $post_types;
	}

}

new Youzify_Poll_Activity;