<?php

/**
 * Add Patches Settings Tab
 */
function youzify_patches_settings_menu( $tabs ) {

	$tabs['patches'] = array(
   	    'id'    => 'patches',
   	    'icon'  => 'fas fa-magic',
   	    'function' => 'youzify_patches_settings',
   	    'title' => __( 'Patches Settings', 'youzify' ),
    );

    return $tabs;

}

add_filter( 'youzify_panel_general_settings_menus', 'youzify_patches_settings_menu', 9999 );

/**
 * Move WP Fields To Buddypress Xprofile Fields
 */
function youzify_patche_move_wp_fields_to_bp_settings() {

    if ( ! youzify_option( 'install_youzer_2.1.5_options' ) ) {
        return false;
    }

    if ( youzify_option( 'yz_patch_move_wptobp' ) ) {
    	return;
    }

    global $Youzify_Settings;

    $Youzify_Settings->get_field(
        array(
            'title' => sprintf( __( 'Move Wordpress fields to Buddypress xprofile fields. %s', 'youzify' ), $patche_status ),
            'type'  => 'openBox'
        )
    );

    // Get User Total Count.
	$user_count_query = count_users();
	$button_args = array(
    	'class' => 'youzify-wild-field',
        'desc'  => __( 'This is a required step to move all the previous profile & contact fields values to the new generated xprofile fields. please note that this operation might take a long time to finish because it will go through all the users in database one by one and update their fields.', 'youzify' ),
        'button_title' => __( 'Update Fields', 'youzify' ),
        'button_data' => array(
        	'step' => 1,
        	'total' => $user_count_query['total_users'],
        	'perstep' => apply_filters( 'youzify_patch_move_wptobp_per_step', 5 ),
        ),
        'id'    => 'youzify-run-wptobp-patch',
        'type'  => 'button'
    );

    $Youzify_Settings->get_field( $button_args );

	// Check is Profile Fields Are Installed.
    $xprofile_fields_installed = youzify_option( 'yz_install_xprofile_groups' );

    if ( ! $xprofile_fields_installed ) {

	    // Include Setup File.
	    require_once YOUZIFY_PATH . '/includes/public/core/class-youzify-setup.php';

	    // Init Setup Class.
	    $Setup = new Youzify_Setup();

	    // Install Xprofile Fields.
	    $Setup->create_xprofile_groups();

    }

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

	?>

	<script type="text/javascript">
	( function( $ ) {

        jQuery( document ).ready( function(){

		/**
		 * Process Updating Fields.
		 */
		$.process_step  = function( step, perstep, total, self ) {

			$.ajax({
				type: 'POST',
				url: ajaxurl,
				data: {
					action: 'youzify_patche_move_wp_fields_to_bp',
					step: step,
					total: total,
					perstep: perstep,
				},
				dataType: "json",
				success: function( response ) {

					var export_form = $( '#youzify-run-wptobp-patch' );

					if ( 'done' == response.step ) {

						export_form.addClass( 'youzify-is-updated' );

						// window.location = response.url;
						export_form.html( '<i class="fas fa-check"></i>Done !' );

					} else {

						$('.youzify-button-progress').animate({
							width: response.percentage + '%',
						}, 50, function() {
							// Animation complete.
						});

						var total_users = ( response.step * response.perstep ) - response.perstep,
							users = total_users < response.total ? total_users : response.total;

						export_form.find( '.youzify-items-count' ).html(users);

						$.process_step( parseInt( response.step ), parseInt( response.perstep ), parseInt( response.total ), self );

					}

				}
			}).fail( function ( response ) {
				if ( window.console && window.console.log ) {
					console.log( response );
				}
			});

		}

		$( 'body' ).on( 'click', '#youzify-run-wptobp-patch', function(e) {

			if ( $( this ).hasClass( 'youzify-is-updated' ) ) {
				return;
			}

			e.preventDefault();

			var per_step = $( this ).data( 'perstep' );
			var total = $( this ).data( 'total' );

			$( this ).html( '<i class="fas fa-spinner fa-spin"></i>Updating <div class="youzify-button-progress"></div><span class="youzify-items-count">' + 1 + '</span>' + ' / ' + total + ' Users' );

			// Start The process.
			$.process_step( 1, per_step, total, self );

		});

	});

})( jQuery );
	</script>

	<?php
}

add_action( 'youzify_patches_settings', 'youzify_patche_move_wp_fields_to_bp_settings', 99 );


/**
 * Process batch exports via ajax
 */
function youzify_patche_move_wp_fields_to_bp() {

	// Init Vars.
	$total = isset( $_POST['total'] ) ? absint( $_POST['total'] ): null;
	$step = isset( $_POST['step'] ) ? absint( $_POST['step'] ) : null;
	$perstep = isset( $_POST['perstep'] ) ? absint( $_POST['perstep'] ) : null;

	$ret = youzify_patch_move_wptobp_process_step( $step, $perstep, $total );

	// Get Percentage.
	$percentage = ( $step * $perstep / $total ) * 100;

	if ( $ret ) {
		$step += 1;
		echo json_encode( array( 'users' => $ret, 'step' => $step, 'total'=> $total, 'perstep' => $perstep, 'percentage' => $percentage ) ); exit;
	} else {
		echo json_encode( array( 'step' => 'done' ) ); exit;
	}

}

add_action( 'wp_ajax_youzify_patche_move_wp_fields_to_bp', 'youzify_patche_move_wp_fields_to_bp' );


function youzify_patch_move_wptobp_process_step( $step, $per_step, $total  ) {

	// Init Vars
	$more = false;
	// $done = false;
	$i      = 1;
	$offset = $step > 1 ? ( $per_step * ( $step - 1 ) ) : 0;

	$done = $offset > $total ? true :  false;

	if ( ! $done ) {

		$more = true;

		// main user query
		$args = array(
		    'fields'    => 'id',
		    'number'    => $per_step,
		    'offset'    => $offset
		);

		// Get the results
		$authors = get_users( $args );

	    // Get Profile Fields.
		$profile_fields = youzify_option( 'yz_xprofile_contact_info_group_ids' );
		$contact_fields = youzify_option( 'yz_xprofile_profile_info_group_ids' );

		$all_fields = (array) $contact_fields + (array) $profile_fields;

		// Remove Group ID Field.
		unset( $all_fields['group_id'] );

		// Update Fields.
		foreach ( $authors as $user_id ) {

			foreach ( $all_fields as $user_meta => $field_id ) {

				// Get Old Value.
				$old_value = get_the_author_meta( $user_meta, $user_id );

				if ( empty( $old_value ) ) {
					continue;
				}

				// Set New Value.
		        xprofile_set_field_data( $field_id, $user_id, $old_value );

		        // Delete Old Value.
				// delete_user_meta( $user_id, $user_meta );

			}

		}

	} else {
	    youzify_update_option( 'yz_patch_move_wptobp', true );
	}

	return $more;
}

/**
 * Move to the new media system
 **/

/**
 * Check for Youzify then check if the user media tables are installed.
 */
add_action( 'youzify_patches_settings', 'youzify_patch_move_to_new_media_system' );

function youzify_patch_move_to_new_media_system() {

    if ( youzify_option( 'yz_patch_new_media_system' ) ) {
        return false;
    }

    global $Youzify_Settings, $wpdb, $bp;

	$already_installed = youzify_option( 'yz_patch_new_media_system' );

	$total = $wpdb->get_var( "SELECT count(*) FROM {$bp->activity->table_name} WHERE type IN ( 'activity_status', 'activity_photo', 'activity_link', 'activity_slideshow', 'activity_quote', 'activity_video', 'activity_audio', 'activity_file', 'new_cover', 'new_avatar')" );


	if ( ! $already_installed && $total == 0 ) {

	    youzify_update_option( 'yz_patch_new_media_system', true );

		// Install New Widget.
		$overview_widgets = youzify_options( 'yz_profile_main_widgets' );
		$sidebar_widgets  = youzify_options( 'yz_profile_sidebar_widgets' );
		$all_widgets      = array_merge( $overview_widgets, $sidebar_widgets );

		$install_widget = true;

		foreach ( $all_widgets as $widget ) {
			if ( key( $widget ) == 'wall_media' )  {
				$install_widget = false;
			}
		}

		if ( $install_widget == true ) {
			$sidebar_widgets[] = array( 'wall_media' => 'visible' );
			update_option( 'yz_profile_sidebar_widgets', $sidebar_widgets );
		}

	}

	$already_installed = youzify_option( 'yz_patch_new_media_system' );


	$patche_status = $already_installed ? '<span class="youzify-title-status">' . __( 'Installed', 'youzify' ) . '</span>' : '';

    $Youzify_Settings->get_field(
        array(
            'title' => sprintf( __( 'Migrate to the new media system. %s', 'youzify' ), $patche_status ),
            'type'  => 'openBox'
        )
    );

	$button_args = array(
    	'class' => 'youzify-wild-field',
        'desc'  => __( 'Please note that this operation might take a long time to finish because it will move all the old activity posts media ( images, videos, audios, files ) to a new database more organized and structured.<br><span style="color: red;text-transform: initial;">Make sure to enable the following functions on your server before running this patch : <b>CURL</b> and <b>getimagesize</b></span>', 'youzify' ),
        'button_title' => __( 'Migrate Now', 'youzify' ),
        'button_data' => array(
        	'run-patch' => 'true',
        	'step' => 1,
        	'items' => 'Posts',
        	'action' => 'youzify_patche_move_to_new_media',
        	'total' => $total,
        	'perstep' => apply_filters( 'youzify_patch_move_wptobp_per_step', 10 ),
        ),
        'id'    => 'youzify-run-media-patch',
        'type'  => 'button'
    );

	if ( $already_installed ) {
		unset( $button_args['button_title'] );
	}

    $Youzify_Settings->get_field( $button_args );

	// Check is Profile Fields Are Installed.
    $xprofile_fields_installed = youzify_option( 'yz_is_media_table_installed' );

    if ( ! $xprofile_fields_installed ) {

	    // Include Setup File.
	    require_once YOUZIFY_PATH . '/includes/public/core/class-youzify-setup.php';

	    // Init Setup Class.
	    $Youzify_Setup = new Youzify_Setup();

	    // Build Database.
	    $Youzify_Setup->build_database_tables();

    }

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

}

/***
 * check for Youzify then check if the user media tables are installed.
 */
add_action( 'youzify_patches_settings', 'youzify_patch_move_to_new_media_system2' );

function youzify_patch_move_to_new_media_system2() {

    if ( youzify_option( 'yz_patch_new_media_system2' ) ) {
        return false;
    }

    global $Youzify_Settings, $wpdb, $bp;

	$already_installed = youzify_option( 'yz_patch_new_media_system2' );

	$patche_status = $already_installed ? '<span class="youzify-title-status">' . __( 'Installed', 'youzify' ) . '</span>' : '';

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Upgrade media system database.', 'youzify' ),
            'type'  => 'openBox'
        )
    );

	$total = $wpdb->get_var( "SELECT count(*) FROM " . $wpdb->prefix . "youzify_media" );

	if ( ! $already_installed && $total == 0 ) {
	    youzify_update_option( 'yz_patch_new_media_system2', true );
	}

	$button_args = array(
    	'class' => 'youzify-wild-field',
        'desc'  => __( 'This operation will add new media database table columns to optimize our media system and make it faster.', 'youzify' ),
        'button_title' => __( 'Upgrade Now', 'youzify' ),
        'button_data' => array(
        	'run-patch' => 'true',
        	'step' => 1,
        	'items' => 'Posts',
        	'action' => 'youzify_patche_move_to_new_media2',
        	'total' => $total,
        	'perstep' => apply_filters( 'youzify_patch_move_wptobp_per_step', 10 ),
        ),
        'id'    => 'youzify-run-media-patch2',
        'type'  => 'button'
    );

    $Youzify_Settings->get_field( $button_args );

	// Check is Profile Fields Are Installed.
    $installed = youzify_option( 'yz_install_youzify_media_new_tables' );

    if ( ! $installed ) {

	    // Include Setup File.
	    require_once YOUZIFY_PATH . '/includes/public/core/class-youzify-setup.php';

	    // Init Setup Class.
	    $Youzify_Setup = new Youzify_Setup();

	    // Build Database.
	    $Youzify_Setup->build_database_tables();

    }

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

}

/**
 * Migrating Ajax Call.
 */
function youzify_patche_move_to_new_media_ajax() {

	// Init Vars.
	$total = isset( $_POST['total'] ) ? absint( $_POST['total'] ): null;
	$step = isset( $_POST['step'] ) ? absint( $_POST['step'] ) : null;
	$perstep = isset( $_POST['perstep'] ) ? absint( $_POST['perstep'] ) : null;

	$ret = youzify_patche_move_to_new_media_process_step( $step, $perstep, $total );

	// Get Percentage.
	$percentage = ( $step * $perstep / $total ) * 100;

	if ( $ret ) {
		$step += 1;
		echo json_encode( array( 'users' => $ret, 'step' => $step, 'total'=> $total, 'perstep' => $perstep, 'percentage' => $percentage ) ); exit;
	} else {
		echo json_encode( array( 'step' => 'done' ) ); exit;
	}

}

add_action( 'wp_ajax_youzify_patche_move_to_new_media', 'youzify_patche_move_to_new_media_ajax' );

/**
 * Migrating Ajax Call.
 */
function youzify_patche_move_to_new_media_ajax2() {

	// Init Vars.
	$total = isset( $_POST['total'] ) ? absint( $_POST['total'] ): null;
	$step = isset( $_POST['step'] ) ? absint( $_POST['step'] ) : null;
	$perstep = isset( $_POST['perstep'] ) ? absint( $_POST['perstep'] ) : null;

	$ret = youzify_patche_move_to_new_media_process_step2( $step, $perstep, $total );

	// Get Percentage.
	$percentage = ( $step * $perstep / $total ) * 100;

	if ( $ret ) {
		$step += 1;
		echo json_encode( array( 'users' => $ret, 'step' => $step, 'total'=> $total, 'perstep' => $perstep, 'percentage' => $percentage ) ); exit;
	} else {
		echo json_encode( array( 'step' => 'done' ) ); exit;
	}

}

add_action( 'wp_ajax_youzify_patche_move_to_new_media2', 'youzify_patche_move_to_new_media_ajax2' );

/**
 * Migration Process.
 */
function youzify_patche_move_to_new_media_process_step2( $step, $per_step, $total  ) {
	// Init Vars
	$more = false;
	$i      = 1;
	$offset = $step > 1 ? ( $per_step * ( $step - 1 ) ) : 0;

	$done = $offset > $total ? true :  false;

	if ( ! $done ) {

		$more = true;

		global $bp, $wpdb;

	    // Get Global Request
		$files = $wpdb->get_results( "SELECT * FROM " . $wpdb->prefix . "youzify_media LIMIT $per_step OFFSET $offset", ARRAY_A );

		if ( empty( $files ) ) {
			return false;
		}

		foreach ( $files as $file ) {

			$update = array();

			if ( $file['type'] == 'none' ) {
				// Get File Source.
				$src = maybe_unserialize( $file['src'] );
				$update['type'] = youzify_get_file_type( $src['original'] );
			}

			if ( $file['component'] == 'activity' ) {
				$update['privacy'] = $wpdb->get_var(  "SELECT privacy from {$bp->activity->table_name} WHERE id = {$file['item_id']}" );
			} elseif ( $file['component'] == 'message' ) {
				$update['privacy'] = 'onlyme';
			} elseif( $file['component'] == 'comment' ) {
				$activity_id = $wpdb->get_var( "SELECT item_id from {$bp->activity->table_name} WHERE id = {$file['item_id']}" );
				$update['privacy'] = $wpdb->get_var(  "SELECT privacy from {$bp->activity->table_name} WHERE id = $activity_id" );
			}

			if ( empty( $update['privacy'] ) ) {
				$update['privacy'] = 'public';
			}

			if ( ! empty( $update ) ) {
				$wpdb->update( $wpdb->prefix . 'youzify_media', $update, array( 'id' => $file['id'] ) );
			}

		}

	} else {
        youzify_update_option( 'yz_patch_new_media_system2', true );
	}

	return $more;
}


/**
 * Migration Process.
 */
function youzify_patche_move_to_new_media_process_step( $step, $per_step, $total  ) {
	// Init Vars
	$more = false;
	$i      = 1;
	$offset = $step > 1 ? ( $per_step * ( $step - 1 ) ) : 0;

	$done = $offset > $total ? true :  false;

	if ( ! $done ) {

		$more = true;

		global $bp, $wpdb;

	    // Get Global Request
		$posts = $wpdb->get_results( "SELECT id, content, type, date_recorded FROM {$bp->activity->table_name} WHERE type IN ( 'activity_status', 'activity_photo', 'activity_link', 'activity_slideshow', 'activity_quote', 'activity_video', 'activity_audio', 'new_avatar', 'new_cover', 'activity_file' ) LIMIT $per_step OFFSET $offset", ARRAY_A );

		if ( empty( $posts ) ) {
			return false;
		}


		global $Youzify_upload_dir;

		// Image Extensions
		$images_ext = array( 'jpeg', 'jpg', 'png', 'gif' );

		foreach ( $posts as $post ) {

			$atts = array();

			// Get Attachments
			if ( $post['type'] == 'new_avatar' ) {

				$avatar = bp_activity_get_meta( $post['id'], 'youzify-avatar' );

				if ( empty( $avatar ) ) {
					continue;
				}

				$atts[0] = youzify_patch_move_media_get_image_args( $avatar );


			} elseif( $post['type'] == 'new_cover' ) {

				$cover = bp_activity_get_meta( $post['id'], 'youzify-cover-image' );

				if ( empty( $cover ) ) {
					continue;
				}

				$atts[0] = youzify_patch_move_media_get_image_args( $cover );

			} elseif( $post['type'] == 'activity_status' ) {

				if ( empty( $post['content'] ) ) {
					continue;
				}

				$embed_exists = false;

				$supported_videos = youzify_attachments_embeds_videos();

				// Get Post Urls.
				if ( preg_match_all( '#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#', $post['content'], $match ) ) {

					foreach ( array_unique( $match[0] ) as $link ) {

						foreach ( $supported_videos as $provider => $domain ) {

							$video_id = youzify_get_embed_video_id( $provider, $link );

							if ( ! empty( $video_id ) ) {

								$embed_exists = true;

								$video_data = array( 'provider' => $provider, 'original' => $video_id );

								$thumbnails = youzify_get_embed_video_thumbnails( $provider, $video_id );

								if ( ! empty( $thumbnails ) ) {
									$video_data['thumbnail'] = $thumbnails;
								}

								$atts[] = $video_data;
							}

						}

					}

				}

				// Change Activity Type from status to video.
				if ( $embed_exists ) {
					$activity = new BP_Activity_Activity( $post['id'] );
					$activity->type = 'activity_video';
					$activity->save();
				}

			} else {
				$atts = bp_activity_get_meta( $post['id'], 'attachments' );
			}

			if ( empty( $atts ) ) {
				continue;
			}

			$atts = maybe_unserialize( $atts );

			foreach ( $atts as $attachment ) {

				// Get Data.
				// $data = array( 'file_size' => $attachment['file_size'] );

				$original_image = isset( $attachment['original'] ) ? $attachment['original'] : ( isset( $attachment['file_name'] ) ? $attachment['file_name'] :  '' );

				if ( empty( $original_image ) ) {
					continue;
				}

				$src = array( 'original' => $original_image );

				$thumbnail_image = isset( $attachment['thumbnail'] ) && file_exists( $Youzify_upload_dir . $attachment['thumbnail'] ) ? $attachment['thumbnail'] : '';

				if ( ! empty( $thumbnail_image ) ) {
					$src['thumbnail'] = $thumbnail_image;
				}

				// Add Video Provider if Found.
				if ( isset( $attachment['provider'] ) ) {
					$src['provider'] = $attachment['provider'];
				}

				if ( $post['type'] == 'activity_video' && ! isset( $src['provider'] ) ) {
					$src['provider'] = 'local';
				}

				$ext = strtolower( pathinfo( $original_image, PATHINFO_EXTENSION ) );

				// Add Image Resolutions.
				if ( ! in_array( $post['type'], array( 'activity_audio', 'activity_video', 'activity_file', 'activity_status' ) ) && in_array( $ext, $images_ext ) ) {
					$attachment['size'] = youzify_get_image_size( $original_image );
				}

				if ( isset( $attachment['original'] ) ) {
					unset( $attachment['original'] );
				}

				if ( isset( $attachment['thumbnail'] ) ) {
					unset( $attachment['thumbnail'] );
				}

				// Unset Thumbnail Data.
				if ( isset( $attachment['provider'] ) ) {
					unset( $attachment['provider'] );
				}

				if ( $post['type'] != 'activity_comment' ) {
					$privacy = $wpdb->get_var(  "SELECT privacy from {$bp->activity->table_name} WHERE id = {$post['id']}" );
				} else {
					$ac_id = $wpdb->get_var( "SELECT item_id from {$bp->activity->table_name} WHERE id = {$post['id']}" );
					$privacy = $wpdb->get_var( "SELECT privacy from {$bp->activity->table_name} WHERE id = $ac_id" );
				}

				if ( empty( $privacy ) ) {
					$privacy = 'public';
				}

				$args = array(
					'src' => serialize( $src ),
					'data' => ! empty( $attachment ) ? serialize( $attachment ) : '',
					'item_id' => $post['id'],
					'privacy' => $privacy,
					'type' => youzify_get_file_type( $attachment['original'] ),
					'time' => $post['date_recorded'],
				);

				// Set New Hashtag Count.
				$result = $wpdb->insert( $wpdb->prefix . 'youzify_media', $args );

			}

		}

		// Delete Old Value Here.

	} else {

        youzify_update_option( 'yz_patch_new_media_system', true, 'no' );
        youzify_update_option( 'yz_patch_new_media_system2', true, 'no' );

		// Install New Widget.
		$overview_widgets = youzify_options( 'yz_profile_main_widgets' );
		$sidebar_widgets  = youzify_options( 'yz_profile_sidebar_widgets' );
		$all_widgets      = array_merge( $overview_widgets, $sidebar_widgets );

		$install_widget = true;

		if ( isset( $all_widgets['wall_media'] ) ) {
			$install_widget = false;
		}

		if ( $install_widget == true ) {
			$sidebar_widgets['wall_media'] = 'visible';
			update_option( 'yz_profile_sidebar_widgets', $sidebar_widgets );
		}

	}

	return $more;
}


function youzify_patch_move_media_get_image_args( $image_url ) {

	global $Youzify_upload_dir;

	$image_name = basename( $image_url );

	$image_path = $Youzify_upload_dir . $image_name;

	// Get Avatar Args.
	$args = array( 'original' => $image_name, 'file_size' => filesize( $image_path ), 'real_name' => $image_name );

	// Get File Size
	$file_size = youzify_get_image_size( $image_url );

	if ( ! empty( $file_size ) ) {
		$args['size'] = array( 'width' => $file_size[0], 'height' => $file_size[1] );
	}

	return $args;

}



/***
 * Optimize Data Baze.
 */
add_action( 'youzify_patches_settings', 'youzify_patch_optimize_database' );

function youzify_patch_optimize_database() {

	// delete_option( 'youzify_patch_optimize_database' );
    if ( youzify_option( 'yz_patch_optimize_database' ) ) {
        return false;
    }

    global $Youzify_Settings, $wpdb;

	$already_installed = youzify_option( 'yz_patch_optimize_database' );

	$patche_status = $already_installed ? '<span class="youzify-title-status">' . __( 'Installed', 'youzify' ) . '</span>' : '';

    $Youzify_Settings->get_field(
        array(
            'title' => sprintf( __( 'Optimize Youzify Database. %s', 'youzify' ), $patche_status ),
            'type'  => 'openBox'
        )
    );

	// $total = $wpdb->get_var( "SELECT COUNT(*) FROM " . $wpdb->prefix . "options WHERE autoload = 'yes' AND option_name Like 'youzify_%'" );

	$button_args = array(
    	'class' => 'youzify-wild-field',
        'desc'  => __( 'Before many Youzify options were called on all the pages by running this patch they will be called only when needed. This operation will increase your website pages speed.', 'youzify' ),
        'button_title' => __( 'Optimize Now', 'youzify' ),
        'button_data' => array(
        	'run-single-patch' => 'true',
        	'action' => 'youzify_patch_optimize_database',
        ),
        'id'    => 'youzify-run-optimize-database-patch',
        'type'  => 'button'
    );

	if ( $already_installed ) {
		unset( $button_args['button_title'] );
	}

    $Youzify_Settings->get_field( $button_args );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

}

/**
 * Migrating Ajax Call.
 */
function youzify_patche_optimize_database_ajax() {

	global $wpdb;

	// Remove Autoload Functions.
	$wpdb->query( "UPDATE " . $wpdb->prefix . "options SET autoload = 'no' WHERE option_name LIKE 'youzify_%' " );

    /**
     * Save Unallowed Post Types.
     */
	$post_types = array(
        'activity_link'         => youzify_option( 'yz_enable_wall_link', 'on' ),
        'activity_file'         => youzify_option( 'yz_enable_wall_file', 'on' ),
        'activity_audio'        => youzify_option( 'yz_enable_wall_audio', 'on' ),
        'activity_photo'        => youzify_option( 'yz_enable_wall_photo', 'on' ),
        'activity_video'        => youzify_option( 'yz_enable_wall_video', 'on' ),
        'activity_quote'        => youzify_option( 'yz_enable_wall_quote', 'on' ),
        'activity_giphy'        => youzify_option( 'yz_enable_wall_giphy', 'on' ),
        'activity_status'       => youzify_option( 'yz_enable_wall_status', 'on' ),
        'activity_update'       => youzify_option( 'yz_enable_wall_status', 'on' ),
        'new_cover'             => youzify_option( 'yz_enable_wall_new_cover', 'on' ),
        'activity_slideshow'    => youzify_option( 'yz_enable_wall_slideshow', 'on' ),
        'new_avatar'            => youzify_option( 'yz_enable_wall_new_avatar', 'on' ),
        'new_member'            => youzify_option( 'yz_enable_wall_new_member', 'on' ),
        'joined_group'          => youzify_option( 'yz_enable_wall_joined_group', 'on' ),
        'new_blog_post'         => youzify_option( 'yz_enable_wall_new_blog_post', 'on' ),
        'created_group'         => youzify_option( 'yz_enable_wall_created_group', 'on' ),
        'updated_profile'       => youzify_option( 'yz_enable_wall_updated_profile', 'off' ),
        'new_blog_comment'      => youzify_option( 'yz_enable_wall_new_blog_comment', 'off' ),
        'friendship_created'    => youzify_option( 'yz_enable_wall_friendship_created', 'on' ),
        'friendship_accepted'   => youzify_option( 'yz_enable_wall_friendship_accepted', 'on' ),
    );

    if ( class_exists( 'WooCommerce' ) ) {
        $post_types['new_wc_product'] = __( 'New Product', 'youzify' );
        $post_types['new_wc_purchase'] = __( 'New Purchase', 'youzify' );
    }

    if ( class_exists( 'bbPress' ) ) {
        $post_types['bbp_topic_create'] = __( 'Forum Topic', 'youzify' );
        $post_types['bbp_reply_create'] = __( 'Forum Reply', 'youzify' );
    }

	$unallowed_activities = array();

	foreach ( $post_types as $activity_type => $activity_visibilty ) {

		if ( $activity_visibilty != 'on' ) {

			$unallowed_activities[] = $activity_type;

			if ( $activity_type == 'activity_status' ) {
				$unallowed_activities[] = 'activity_update';
			}

		}

	}

	if ( in_array( 'friendship_accepted', $unallowed_activities ) && in_array( 'friendship_created', $unallowed_activities ) ) {
		$unallowed_activities[] = 'friendship_accepted,friendship_created';
		foreach ( array( 'friendship_accepted', 'friendship_created' ) as $type ) {
			if ( ( $key = array_search( $type, $unallowed_activities ) ) !== false) {
				unset( $unallowed_activities[ $key ] );
			}
		}
	}

	if ( empty( $unallowed_activities ) ) {
		delete_option( 'yz_unallowed_activities' );
	} else {
		update_option( 'yz_unallowed_activities', $unallowed_activities, 'no' );
	}

	// Save Tabs.
	$tabs = array();
	$old_tabs = youzify_get_profile_primary_nav();
	$default_tabs = youzify_profile_tabs_default_value();

	foreach ( $old_tabs as $old_tab ) {

		if ( $slug == 'activity' ) {
			continue;
		}

		$slug = $old_tab['slug'];


		$position = youzify_option( 'yz_' . $slug . '_tab_order' );
		if ( ! empty( $position ) && $position != $old_tab['position'] ) {
			$tabs[ $slug ]['position'] = $position;
		}

		$title = youzify_option( 'yz_' . $slug  . '_tab_title' );
		$old_title = _bp_strip_spans_from_title( $old_tab['name'] );
		if ( ! empty( $title ) && $title != $old_title ) {
			$count = strstr( $old_title, '<span' );
			$tabs[ $slug ]['name'] = ! empty( $count ) ? $title . $count : $title;
		}

		$visibility = youzify_options( 'yz_display_' . $slug . '_tab', 'on' );

		if ( $visibility == 'off' ) {
			$tabs[ $slug ]['visibility'] = 'off';
		}

		$icon = youzify_options( 'yz_' . $slug . '_tab_icon' );
		if ( ! empty( $icon ) && $icon != 'fas fa-globe-asia' && $icon != 'globe' && $icon != 'fas fa-globe' ) {
			if ( isset( $default_tabs[ $slug ]['icon'] ) ) {
				if ( $icon != $default_tabs[ $slug ]['icon'] ) {
					$tabs[ $slug ]['icon'] = $icon;
				}
			} else {
				$tabs[ $slug ]['icon'] = $icon;
			}
		}

		$deleted = youzify_option( 'yz_delete_' . $slug . '_tab', 'off' );
		if ( ! empty( $deleted ) && $deleted == 'on' ) {
			$tabs[ $slug ]['deleted'] = 'on';
		}

	}

	if ( empty( $tabs ) ) {
		delete_option( 'yz_profile_tabs' );
	} else {
		update_option( 'yz_profile_tabs', $tabs, 'no' );
	}

    $hidden = array();
    $o_widgets = array();
    $s_widgets = array();

    $overview_widgets = youzify_options( 'yz_profile_main_widgets' );
    $sidebar_widgets = youzify_options( 'yz_profile_sidebar_widgets' );

    if ( ! empty( $overview_widgets ) ) {
		foreach ( $overview_widgets as $o_widget ) {
			if ( ! empty( $o_widget ) ) {
			    foreach ( $o_widget as $o_widget_name => $o_visibility ) {
			        $o_widgets[ $o_widget_name] = $o_visibility;
			    }
			}
		}
    }

    if ( ! empty( $sidebar_widgets ) ) {
	    foreach ( $sidebar_widgets as $s_widget ) {
	    	if ( ! empty( $s_widget ) ) {
		    	foreach ( $s_widget as $s_widget_name => $s_visibility ) {
		            $s_widgets[ $s_widget_name ] = $s_visibility;
		        }
	    	}
	    }
    }

    update_option( 'yz_profile_main_widgets', $o_widgets, 'no' );
    update_option( 'yz_profile_sidebar_widgets', $s_widgets, 'no' );

    $all_widgets = array_merge( $overview_widgets, $sidebar_widgets );

    if ( ! empty( $all_widgets ) ) {
	    foreach ( $all_widgets as $widget ) {
	    	if ( ! empty( $widget ) ) {
		        foreach ( $widget as $widget_name => $visibility ) {
		            if ( $visibility == 'invisible' ) {
		                $hidden[] = $widget_name;
		            }
		        }
		    }

	    }
    }

    if ( ! empty( $hidden ) ) {
        youzify_update_option( 'yz_profile_hidden_widgets' , $hidden, 'no' );
    } else {
        youzify_delete_option( 'yz_profile_hidden_widgets' );
    }

    youzify_update_option( 'yz_patch_optimize_database', true );

	if ( ! youzify_option( 'yz_install_youzify_media_new_tables' ) ) {

		global $wpdb;

		$row = $wpdb->get_results(  "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = '" . $wpdb->prefix . "youzify_media' AND column_name = 'component'"  );

		$row2 = $wpdb->get_results(  "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = '" . $wpdb->prefix . "youzify_media' AND column_name = 'type'"  );

		if ( empty( $row ) ) {
		   $wpdb->query("ALTER TABLE " . $wpdb->prefix . "youzify_media ADD component varchar(10) NULL DEFAULT 'activity'");
		}

		if ( empty( $row2 ) ) {
		   $wpdb->query("ALTER TABLE " . $wpdb->prefix . "youzify_media ADD type varchar(10) NULL DEFAULT 'none'");
		}

		update_option( 'yz_install_youzify_media_new_tables', 1, 'no' );

	}

	echo json_encode( array( 'step' => 'done' ) ); exit;

}

add_action( 'wp_ajax_youzify_patch_optimize_database', 'youzify_patche_optimize_database_ajax' );


/**
 * Migration Process.
 */
function youzify_patche_optimize_database_process_step( $step, $per_step, $total  ) {
	// Init Vars
	$more = false;
	$i      = 1;
	$offset = $step > 1 ? ( $per_step * ( $step - 1 ) ) : 0;

	$done = $offset > $total ? true :  false;

	if ( ! $done ) {

		$more = true;

		global $bp, $wpdb;

	    // Get Global Request
		$options = $wpdb->get_results( "SELECT option_id FROM " . $wpdb->prefix . "options WHERE autoload = 'yes' AND option_name LIKE 'youzify_%' LIMIT $per_step OFFSET $offset", ARRAY_A );

		$options = wp_list_pluck( $options, 'option_id' );

		if ( empty( $options ) ) {
			return false;
		}

		$wpdb->query( "UPDATE " . $wpdb->prefix . "options SET autoload = 'no' WHERE option_name LIKE 'youzify_%' " );

	} else {
        youzify_update_option( 'yz_patch_optimize_database', true );
	}

	return $more;
}

/**
 * Run WP TO BP Patch Notice.
 */
function youzify_move_wp_fields_to_bp_notice() {

    $patch_url = add_query_arg( array( 'page' => 'youzify-panel&tab=patches' ), admin_url( 'admin.php' ) );

    if ( ! youzify_option( 'yz_patch_new_media_system' ) ) { ?>

        <div class="notice notice-warning">
            <p><?php echo sprintf( __( "<strong>Youzify - New Media System Important Patch:<br> </strong>Please run the following patch <strong><a href='%1s'>Migrate to The New Youzify Media System.</a></strong> This operation will move all the old activity posts media ( images, videos, audios, files ) to a new database more organized and structured.", 'youzify' ), $patch_url ); ?></p>
        </div>

        <?php

    }

    if ( ! youzify_option( 'yz_patch_new_media_system2' ) ) { ?>

        <div class="notice notice-warning">
            <p><?php echo sprintf( __( "<strong>Youzify - Media Optimization Patch :<br> </strong>Please run the following patch <strong><a href='%1s'> Upgrade Media System Database.</a></strong> This operation will improve the media system structure.", 'youzify' ), $patch_url ); ?></p>
        </div>

        <?php

    }

    if ( ! youzify_option( 'yz_patch_new_wp_media_library' ) ) { ?>

        <div class="notice notice-warning">
            <p><?php echo sprintf( "<strong>Youzify - Upgrade to Wordpress Media Library Patch :</strong><br><br>Please run the following patch <strong><a href='%1s'> Upgrade to Wordpress Media Library.</a></strong> This operation will improve the media system and make it more optimized and very fast.", $patch_url ); ?></p>
        </div>

        <?php

    }

    if ( ! youzify_option( 'yz_patch_optimize_database' ) ) { ?>

        <div class="notice notice-warning">
            <p><?php echo sprintf( __( "<strong>Youzify - Database Optimization Patch :<br> </strong>Please run the following patch <strong><a href='%1s'>Optimize Youzify Database</a></strong> This will increase your website speed.", 'youzify' ), $patch_url ); ?></p>
        </div>

        <?php

    }

    if ( youzify_option( 'install_youzer_2.1.5_options' ) ) {

        if ( ! youzify_option( 'yz_patch_move_wptobp' ) ) { ?>

        <div class="notice notice-warning">
            <p><?php echo sprintf( __( "<strong>Youzify - Important Patch :<br> </strong>Please run the following patch <strong><a href='%1s'>Move Wordpress Fields To The Buddypress Xprofile Fields.</a></strong> This patch will move all the previews users fields values to the new created Buddypress fields so now you can have the full control over profile info tab and contact info tab fields also : Re-order them, Control their visibility or even remove them if you want.</strong>", 'youzify' ), $patch_url ); ?></p>
        </div>

        <?php

        }
    }

    if ( ! youzify_option( 'youzify_social_login_update' ) ) { ?>

        <div class="notice notice-warning">

            <a href="<?php echo add_query_arg( 'youzify-dismiss-extension-notice', 'youzify_social_login_update', youzify_get_current_page_url() ); ?>" type="button" class="yz-delete-notice"></a>

            <p><strong style="color: red;">Youzify - Important Notices:<br></strong></p>
            <p><strong>01. Youzify - Shortcodes Notice:<br></strong></p>
            <p>If you are using any Youzer Shortcodes, consider changing the word "youzer" to "youzify". For more details check our shortcodes page: <a href="https://kainelabs.ticksy.com/article/13189/">Youzify Shortcodes</a></p>
            <p><strong>Example:</strong> The old activity shortcode <strong>[youzer_activity]</strong> is now <strong>[youzify_activity]</strong>.</p>
            <p><strong>02. Youzify - Social Login Notice:<br></strong></p>
            <p>If you are using social login buttons or instagram widget consider renewing to this new callback URL's:</p>
            <ul>
                <li><strong>Facebook</strong>: <?php echo home_url( '/youzify-auth/social-login/Facebook' ); ?></li>
                <li><strong>Google</strong>: <?php echo home_url( '/youzify-auth/social-login/Google' ); ?></li>
                <li><strong>Twitter</strong>: <?php echo home_url( '/youzify-auth/social-login/Twitter' ); ?></li>
                <li><strong>Instagram</strong>: <?php echo home_url( '/youzify-auth/social-login/Instagram' ); ?></li>
                <li><strong>Linked In</strong>: <?php echo home_url( '/youzify-auth/social-login/LinkedIn' ); ?></li>
                <li><strong>Twitch</strong>: <?php echo home_url( '/youzify-auth/social-login/TwitchTV' ); ?></li>
                <li><strong>Instagram Widget</strong>: <?php echo home_url( '/youzify-auth/feed/Instagram' ); ?></li>
            </ul>
            <p><strong style="color:red;">Important</strong> : to make the social login links works fine please go to WordPress Dashboard > Settings > Permalinks and scroll to the bottom and click "Save Changes".</p>
            <p><strong>Ps:</strong> All these URL's are case sensitive so please copy them as they are written !</p>
            <p>if you found any issues regarding this please open a new ticket on <a href="https://kainelabs.ticksy.com">kainelabs.ticksy.com</a> and our support team will help you.</p>
            <p><strong>Ps:</strong> We still support the old callback URL's format for few more months, but we recommend changing them as soon as possible to make sure the social login feature is up to date.</p>
        </div>
        <style type="text/css">
            .yz-delete-notice {
                float: right;
                margin-top: 5px;
                text-decoration: none;
            }
            .yz-delete-notice:before {
                background: 0 0;
                color: #72777c;
                content: "\f153";
                display: block;
                font: normal 16px/20px dashicons;
                speak: none;
                height: 20px;
                text-align: center;
                width: 20px;
                -webkit-font-smoothing: antialiased;
            }

        </style>
        <?php
    }


}

/**
 * New Extension Notice
 **/
function youzify_display_new_extension_notice() {

    $youzify_ea_notice = 'yz_hide_yzea_notice';
    $youzify_pc_notice = 'yz_hide_yzpc_notice';
    $youzify_bm_notice = 'yz_hide_yzbm_notice';
    $youzify_bmr_presale_notice = 'yz_hide_yzbmr_presale_notice';
    $youzify_hide_yasale3_notice = 'yz_hide_yasale3_notice';
    $load_lightbox = false;

    if ( isset( $_GET['youzify-dismiss-extension-notice'] ) ) {
        youzify_update_option( $_GET['youzify-dismiss-extension-notice'], 1 );
    }

    // if ( ! youzify_option( $youzify_hide_yasale3_notice ) ) {

    //     $start_date = new DateTime( '2020/04/16' );
    //     $end_date = new DateTime( '2020/05/01' );
    //     $now = new DateTime();

    //     if ( $now >= $start_date && $now < $end_date ) {
    //         // Get Extension.
    //         youzify_get_notice_addon( array(
    //             'class' => 'youzify-sale-notice',
    //             'notice_id' => $youzify_hide_yasale3_notice,
    //             'utm_campaign' => 'youzify-3rd-anniversary-2020',
    //             'utm_medium' => 'admin-banner',
    //             'utm_source' => 'clients-site',
    //             'title' => 'Youzify 3rd Anniversary Sale - 40% OFF Discount ! ',
    //             'link' => 'https://www.youzify.com/',
    //             'buy_now' => 'https://www.youzify.com/?',
    //             'image' => 'https://kainelabs.com/tmp/youzify-anniversary-3.png',
    //             'description' => "Use code <strong>YOUZER3RD</strong> & Save <strong>40%</strong> on all Youzify Extensions.<br>Limited Time Offer.",
    //             'buy_now_title' => "Let's Shop"
    //         ) );
    //     }
    // }

    if ( ! youzify_option( $youzify_bmr_presale_notice ) ) {
        $load_lightbox = true;
        $data = array(
            'notice_id' => $youzify_bmr_presale_notice,
            'utm_campaign' => 'youzify-membership-restrictions',
            'utm_medium' => 'admin-banner',
            'utm_source' => 'clients-site',
            'title' => 'BuddyPress Membership Restrictions',
            'link' => 'https://www.youzify.com/downloads/buddypress-membership-restrictions/',
            'buy_now' => 'https://www.youzify.com/checkout/?edd_action=add_to_cart&download_id=46428&edd_options%5Bprice_id%5D=1',
            'image' => 'https://www.youzify.com/wp-content/uploads/edd/2020/03/thumbnail.png',
            'description' => 'Instead of spending thousands of dollars on customizations Meet the most complete BuddyPress membership restrictions plugin to restrict BuddyPress community features and content for visitors, members or by user role to take the full control over what your website users get exclusive access to.',
            'features' => array(
                'Set Members, Visitors ( Non Logged In Users ) Or By User Role Restrictions.',
                'Set Custom Redirect Page for Visitors, Members or By User Role.',
                'Set BuddyPress Components Restrictions.',
                'Set Wordpress Pages & BuddyPress Pages Restrictions.',
                'Set The Maximum Restrictions & Minimum Requirements For Activity Posts & Comments.',
                'Set Public, Private, Hidden Groups Restrictions.',
                'Set Friendship, Messages, Follows, Reviews Restrictions.',
                'Set Profile Tabs & Profile Widgets Restrictions.',
                'And much much more ... check all the detailed features on the add-on description page !'
            ),
            'images' => array(
                array( 'title' => 'Components Restrictions', 'link' => 'https://www.youzify.com/wp-content/uploads/edd/2020/04/components-restrictions.png' ),
                array( 'title' => 'Activity Form Restrictions', 'link' => 'https://www.youzify.com/wp-content/uploads/edd/2020/04/activity-form-restrictions.png' ),
                array( 'title' => 'Activity Posting Restrictions', 'link' => 'https://www.youzify.com/wp-content/uploads/edd/2020/04/activity-posting-restrictions.png' ),
                array( 'title' => 'Activity Posting Requirements', 'link' => 'https://www.youzify.com/wp-content/uploads/edd/2020/04/activity-posting-requirements.png' ),
                array( 'title' => 'Activity Feed Post Types Restrictions', 'link' => 'https://www.youzify.com/wp-content/uploads/edd/2020/04/activity-feed-post-types-restrictions.png' ),
                array( 'title' => 'Activity Post Buttons Restrictions', 'link' => 'https://www.youzify.com/wp-content/uploads/edd/2020/04/activity-post-buttons-restrictions.png' ),
                array( 'title' => 'Activity Comments Requirements', 'link' => 'https://www.youzify.com/wp-content/uploads/edd/2020/04/activity-comments-requirements.png' ),
                array( 'title' => 'Activity Comments Restrictions', 'link' => 'https://www.youzify.com/wp-content/uploads/edd/2020/04/activity-comments-restrictions.png' ),
                array( 'title' => 'Groups Creation Restrictions', 'link' => 'https://www.youzify.com/wp-content/uploads/edd/2020/04/groups-creation-restrictions.png' ),
                array( 'title' => 'Public Groups Restrictions', 'link' => 'https://www.youzify.com/wp-content/uploads/edd/2020/04/public-groups-restrictions.png' ),
                array( 'title' => 'Hidden Groups Restrictions', 'link' => 'https://www.youzify.com/wp-content/uploads/edd/2020/04/hidden-groups-restrictions.png' ),
                array( 'title' => 'Private Group Restrictions', 'link' => 'https://www.youzify.com/wp-content/uploads/edd/2020/04/private-groups-restrictions.png' ),
                array( 'title' => 'BuddyPress Pages Restrictions', 'link' => 'https://www.youzify.com/wp-content/uploads/edd/2020/04/buddypress-pages-restrictions.png' ),
                array( 'title' => 'Friendship Restrictions', 'link' => 'https://www.youzify.com/wp-content/uploads/edd/2020/04/friendship-restrictions.png' ),
                array( 'title' => 'Messages Restrictions', 'link' => 'https://www.youzify.com/wp-content/uploads/edd/2020/04/messages-restrictions.png' ),
                array( 'title' => 'Follows Restrictions', 'link' => 'https://www.youzify.com/wp-content/uploads/edd/2020/04/follows-restrictions.png' ),
                array( 'title' => 'Profile Tab Restrictions', 'link' => 'https://www.youzify.com/wp-content/uploads/edd/2020/04/profile-tabs-restrictions.png' ),
                array( 'title' => 'Reviews Restrictions', 'link' => 'https://www.youzify.com/wp-content/uploads/edd/2020/04/reviews-restrictions.png' ),
                array( 'title' => 'Wordpress Pages Restrictions', 'link' => 'https://www.youzify.com/wp-content/uploads/edd/2020/04/wordpress-pages-restrictions.png' ),
                array( 'title' => 'Profile Widgets Creation Restrictions', 'link' => 'https://www.youzify.com/wp-content/uploads/edd/2020/04/profile-widgets-creation-restrictions.png' ),
                array( 'title' => 'Profile Widgets Visibility Restrictions', 'link' => 'https://www.youzify.com/wp-content/uploads/edd/2020/04/profile-widgets-visibility-restrictions.png' ),
            )
         );

        // Get Extension.
        youzify_get_notice_addon( $data );
    }

    if ( ! youzify_option( $youzify_ea_notice ) ) {
        $load_lightbox = true;
        $data = array(
            'notice_id' => $youzify_ea_notice,
            'utm_campaign' => 'youzify-edit-activity',
            'utm_medium' => 'admin-banner',
            'utm_source' => 'clients-site',
            'title' => 'Youzify - Buddypress Edit Activity',
            'link' => 'https://www.youzify.com/downloads/buddypress-edit-activity/',
            'buy_now' => 'https://www.youzify.com/checkout/?edd_action=add_to_cart&download_id=22081&edd_options%5Bprice_id%5D=1',
            'image' => 'https://www.youzify.com/wp-content/uploads/edd/2019/05/Untitled-1.png',
            'description' => 'Allow members to edit their activity posts, comment and replies from the front-end with real time modifications. Set users that can edit their own activities and moderators by role and control editable activities by post type and set a timeout for how long they should remain editable and much more ...',
            'features' => array(
                'Set Members That Can Edit Their Own Activities and Comments by Role.',
                'Set Editable Activities By Post Type.',
                'Set Moderators That Can Edit All The Site Activities by Role.',
                'Set Edit Button Timeout ( How long activities should remain editable ).',
                'Enable / Disable Attachments Edition.',
                'Enable / Disable Comments & Replies Edition.',
                'Real Time Modifications. No Refresh Page Required !'
            ),
            'images' => array(
                array( 'title' => 'Post & Comments Edit Buttons', 'link' => 'https://www.youzify.com/wp-content/uploads/edd/2019/05/normal-post.png' ),
                array( 'title' => 'Photos Post Edit Form', 'link' => 'https://www.youzify.com/wp-content/uploads/edd/2019/05/photospost.png' ),
                array( 'title' => 'Live URL Preview Post Edit Form', 'link' => 'https://www.youzify.com/wp-content/uploads/edd/2019/05/link.png' ),
                array( 'title' => 'Quote Post Edit Form', 'link' => 'https://www.youzify.com/wp-content/uploads/edd/2019/05/edit-quote-form.png' ),
                array( 'title' => 'Quote Post Edit Button', 'link' => 'https://www.youzify.com/wp-content/uploads/edd/2019/05/quote-post-edit-buttton.png' ),
                array( 'title' => 'Link Post Edit Form', 'link' => 'https://www.youzify.com/wp-content/uploads/edd/2019/05/link-post-edit.png' )
            )
         );

        // Get Extension.
        youzify_get_notice_addon( $data );
    }

    if ( ! youzify_option( $youzify_pc_notice ) ) {
        $load_lightbox = true;
        $data2 = array(
            'notice_id' => $youzify_pc_notice,
            'utm_campaign' => 'youzify-profile-completeness',
            'utm_medium' => 'admin-banner',
            'utm_source' => 'clients-site',
            'title' => 'Youzify - Buddypress Profile Completeness',
            'link' => 'https://www.youzify.com/downloads/buddypress-profile-completeness/',
            'buy_now' => 'https://www.youzify.com/?edd_action=add_to_cart&download_id=21146&edd_options%5Bprice_id%5D=1',
            'image' => 'https://www.youzify.com/wp-content/uploads/edd/2019/05/youzer-profile-completeness.png',
            'description' => 'Say good bye to the blank profiles, buddypress profile completeness is the best way to force or encourage users to complete their profile fields, profile widgets and more. also gives you the ability to apply restrictions on incomplete profiles.',
            'features' => array(
                '3 Fields Status ( Forced, Required, Optional ).',
                'Apply Profile Completeness System For Specific Roles.',
                'Enable / Disable Hiding Incomplete Profiles from Members Directory.',
                'Enable / Disable Marking Complete Profiles as Verified.',
                'Enable / Disable The following Actions For Incomplete Profiles : Posts, Comments, Replies, Follows, Groups, Messages ...',
                'Supported Fields : All Buddypress Fields, Youzify Widgets, Buddypress Avatar & Cover Images.',
                'Profile Completeness Shortcode : [youzify_profile_completeness].',
                'Ajaxed Profile Completeness Widget.'
            ),
            'images' => array(
                array( 'title' => 'Profile Completeness Widget', 'link' => 'https://www.youzify.com/wp-content/uploads/edd/2019/06/complete-profile.png' ),
                array( 'title' => 'Profile Completeness – Profile Info', 'link' => 'https://www.youzify.com/wp-content/uploads/edd/2019/06/profile-info.png' ),
                array( 'title' => 'Profile Completeness – Profile Images', 'link' => 'https://www.youzify.com/wp-content/uploads/edd/2019/06/upload-images.png' ),
                array( 'title' => 'Profile Completeness – Widgets Settings', 'link' => 'https://www.youzify.com/wp-content/uploads/edd/2019/06/widgets-settings.png' ),
                array( 'title' => 'Profile Completeness – Account Restrictions', 'link' => 'https://www.youzify.com/wp-content/uploads/edd/2019/06/account-restrictions.png' )
            )
         );

        // Get Extension.
        youzify_get_notice_addon( $data2 );
    }

    if (  ! youzify_option( $youzify_bm_notice ) ) {
        $load_lightbox = true;
        $data3 = array(
            'notice_id' => $youzify_bm_notice,
            'utm_campaign' => 'youzify-buddypress-moderation',
            'utm_medium' => 'admin-banner',
            'utm_source' => 'clients-site',
            'title' => 'Youzify - Buddypress Moderation',
            'link' => 'https://www.youzify.com/downloads/buddypress-moderation-plugin/',
            'buy_now' => 'https://www.youzify.com/?edd_action=add_to_cart&download_id=27779&edd_options%5Bprice_id%5D=1',
            'image' => 'https://cldup.com/m9j-9YtX-C.png',
            'description' => "Moderating your online community is not an option — it’s a must. Meet the most complete buddyPress moderation solution with an advanced features to take the full control over your community and keep it safe with automatic moderation features and automatic restrictions.",
            'features' => array(
                'Moderation Components : Members, Activities, Comments, Messages, Groups.',
                'Set What Roles Can Reports Items & Moderator Roles.',
                'Automatic Moderation After an item reach a certain numner of reports.',
                'Apply Temporary or Official Restrictions for Specific Periods. ( Disable posts, comments, messages, friends, follows ... )',
                'Allow Visitors to Report Items & Add Unlimited Reports Subjects.',
                'Customizable Notification Emails when a New Reports is Added, Restored, Deleted, Hidden & More ...',
                'Advanced Moderation Table With Bulk and Single Actions : View, Close, Restore, Delete, Delete & Punish, Mark as Spammer & More ...',
                'And Many Many Other Features You Can Check Them On The Extension Page.'
            ),
            'images' => array(
                array( 'title' => 'Reports Table ( Moderation Queue )', 'link' => 'https://www.youzify.com/wp-content/uploads/edd/2019/11/reports2.png' ),
                array( 'title' => 'Restrictions Table', 'link' => 'https://www.youzify.com/wp-content/uploads/edd/2019/11/restrictions.png' ),
                array( 'title' => 'Activity Posts & Comments Report Button', 'link' => 'https://www.youzify.com/wp-content/uploads/edd/2019/11/posts-comments.png' ),
                array( 'title' => 'Members Directory – User Report Button', 'link' => 'https://www.youzify.com/wp-content/uploads/edd/2019/11/members.png' ),
                array( 'title' => 'User Profile Report Button', 'link' => 'https://www.youzify.com/wp-content/uploads/edd/2019/11/user-profile.png' ),
                array( 'title' => 'Groups Directory – Group Report Button', 'link' => 'https://www.youzify.com/wp-content/uploads/edd/2019/11/groups.png' ),
                array( 'title' => 'Single Group Page Report Button', 'link' => 'https://www.youzify.com/wp-content/uploads/edd/2019/11/groups-single-page.png' ),
                array( 'title' => 'Messages Report Button', 'link' => 'https://www.youzify.com/wp-content/uploads/edd/2019/11/message.png' ),
                array( 'title' => 'Logged-In Users Report Form', 'link' => 'https://www.youzify.com/wp-content/uploads/edd/2019/11/members-report-form.png' ),
                array( 'title' => 'Visitors Report Form', 'link' => 'https://www.youzify.com/wp-content/uploads/edd/2019/11/visitors-report.png' )
            )
         );

        // Get Extension.
        youzify_get_notice_addon( $data3 );
    }

    if ( $load_lightbox ) {
        // Load Light Box CSS and JS.
        wp_enqueue_style( 'youzify-lightbox', YOUZIFY_ASSETS . 'css/youzify-lightbox.min.css', array(), YOUZIFY_VERSION );
        wp_enqueue_script( 'youzify-lightbox', YOUZIFY_ASSETS . 'js/youzify-lightbox.min.js', array( 'jquery' ), YOUZIFY_VERSION, true );
    }

}

add_action( 'admin_notices', 'youzify_display_new_extension_notice' );
add_action( 'admin_notices', 'youzify_move_wp_fields_to_bp_notice' );

/**
 * Get Notice Add-on
 */
function youzify_get_notice_addon( $data ) { ?>

    <style type="text/css">

        body .youzify-addon-notice {
            padding: 0;
            border: none;
            overflow: hidden;
            box-shadow: none;
            margin-top: 15px;
            max-width: 870px;
            position: relative;
            margin-bottom: 15px;
            margin: 0 auto 15px !important;
        }

        .youzify-addon-notice .youzify-addon-notice-content {
            /*float: left;*/
            /*width: 80%;*/
            /*margin-left: 20%;*/
            padding: 25px 35px;
        }
/*
        .youzify-addon-notice.youzify-horizontal-layout .youzify-addon-notice-img {
            display: block;
            background-size: cover;
            background-position: center;
            float: left;
            width: 20%;
            height: 100%;
            position: absolute;
        }
*/
        .youzify-addon-notice .youzify-addon-notice-img {
            display: block;
            background-size: cover;
            background-position: center;
            width: 100%;
        }

        .youzify-addon-notice .youzify-addon-notice-title {
            font-size: 17px;
            font-weight: 600;
            color: #646464;
            margin-bottom: 10px;
        }

        .youzify-addon-notice .youzify-addon-notice-title .youzify-addon-notice-tag {
            color: #fff;
            display: inline-block;
            text-transform: uppercase;
            font-weight: 600;
            margin-left: 8px;
            font-size: 10px;
            padding: 0px 8px;
            border-radius: 2px;
            background-color: #FFC107;
        }

        .youzify-addon-notice .youzify-addon-notice-description {
            font-size: 13px;
            color: #646464;
            line-height: 24px;
            margin-bottom: 15px;
        }

        .youzify-addon-notice .youzify-addon-notice-buttons a {
            color: #fff;
            font-size: 14px;
            font-weight: 600;
            min-width: 110px;
            text-align: center;
            margin-right: 12px;
            padding: 15px 25px;
            border-radius: 3px;
            display: inline-block;
            vertical-align: middle;
            text-decoration: none;
        }

        .youzify-addon-notice .youzify-addon-notice-buttons a:focus {
            box-shadow: none !important;
        }

        .youzify-addon-notice .notice-dismiss {
            text-decoration: none;
        }

        .youzify-addon-notice .youzify-addon-notice-buttons a.youzify-addon-view-features {
            background-color: #03A9F4;
        }

        .youzify-addon-notice .youzify-addon-notice-buttons a.youzify-addon-buy-now {
            background-color: #8bc34a;
        }


        .youzify-addon-notice .youzify-addon-notice-buttons a.youzify-addon-delete-notice {
            background: #F44336;
        }

        .youzify-addon-features {
            margin-bottom: 25px;
        }

        .youzify-addon-notice .youzify-addon-features p {
            margin: 0 0 12px;
        }

        .youzify-addon-notice .youzify-addon-features p:last-of-type {
            margin-bottom: 0;
        }

        .youzify-addon-screenshots {
            margin-bottom: 20px;
        }

        .youzify-addon-screenshots .youzify-screenshot-item {
            width: 60px;
            height: 60px;
            border-radius: 3px;
            /*margin-right: 10px;*/
            /*margin-bottom: 5px;*/
            margin: 5px 10px 5px 0;
            display: inline-block;
            background-size: cover;
        }

        .youzify-addon-section-title {
            color: #646464;
            font-size: 13px;
            font-weight: 600;
            background: #eee;
            border-radius: 3px;
            margin-bottom: 15px;
            display: inline-block;
            padding: 4px 12px 5px;
        }

        .youzify-sale-notice .youzify-addon-view-features {
            display: none !important;
        }

    </style>

    <?php

        // <a href="<?php echo add_query_arg( 'youzify-dismiss-extension-notice', $data['notice_id'], youzify_get_current_page_url() ); ? >" type="button" class="notice-dismiss">Dismiss.</a>
        $link = $data['link'] .'?utm_campaign=' . $data['utm_campaign'] . '&utm_medium=' . $data['utm_medium'] . '&utm_source=' . $data['utm_source'] . '&utm_content=view-all-features';

       $img_link = $data['link'] .'?utm_campaign=' . $data['utm_campaign'] . '&utm_medium=' . $data['utm_medium'] . '&utm_source=' . $data['utm_source'] . '&utm_content=notice-cover';

        $buy = $data['buy_now'] .'&utm_campaign=' . $data['utm_campaign'] . '&utm_medium=' . $data['utm_medium'] . '&utm_source=' . $data['utm_source'] . '&utm_content=buy-now';

        ?>

    <div id="<?php echo $data['notice_id']; ?>" class="youzify-addon-notice updated notice notice-success <?php echo isset( $data['class'] ) ? $data['class'] : ''; ?>">
        <!-- <div class="youzify-addon-notice-img" style="background-image:url(<?php echo $data['image']; ?>);"></div> -->
        <a href="<?php echo $img_link; ?>"><img class="youzify-addon-notice-img" src="<?php echo $data['image']; ?>" alt=""></a>
        <div class="youzify-addon-notice-content">
            <div class="youzify-addon-notice-title"><?php echo $data['title']; ?><span class="youzify-addon-notice-tag">New</span></div>
            <div class="youzify-addon-notice-description"><?php echo $data['description']; ?></div>
            <?php if ( isset( $data['features'] ) ) : ?>
            <div class="youzify-addon-features">
                <div class="youzify-addon-section-title">Features</div><br>
                <?php foreach ( $data['features'] as $feature ) : ?>
                <p>- <?php echo $feature; ?></p>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
            <?php if ( isset( $data['images'] ) ) : ?>
            <div class="youzify-addon-screenshots" data-youzify-lightbox="<?php echo $data['notice_id']; ?>">
                <div class="youzify-addon-section-title">Screenshots</div><br>
                <?php foreach ( $data['images'] as $image ) : ?>
                <a href="<?php echo $image['link']; ?>" data-youzify-lightbox="<?php echo $data['notice_id']; ?>" data-title="<?php echo $image['title']; ?>"><div class="youzify-screenshot-item" style="background-image: url(<?php echo $image['link']; ?>)"></div></a>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
            <div class="youzify-addon-notice-buttons">
                <a href="<?php echo $link; ?>" class="youzify-addon-view-features">View All Features</a>
                <a href="<?php echo $buy; ?>" class="youzify-addon-buy-now"><?php echo isset( $data['buy_now_title'] ) ? $data['buy_now_title'] : __( 'Buy Now', 'youzify' ); ?></a>
                <a href="<?php echo add_query_arg( 'youzify-dismiss-extension-notice', $data['notice_id'], youzify_get_current_page_url() ); ?>" type="button" class="youzify-addon-delete-notice">Delete Notice</a>
            </div>
        </div>
    </div>

    <?php
}
