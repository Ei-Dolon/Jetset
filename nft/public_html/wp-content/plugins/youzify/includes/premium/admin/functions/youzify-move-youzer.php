<?php
/**
 * Move Youzer Data to Youzify
 */
class Youzify_Move_Youzer {

	function __construct( ) {

		// Add New
		add_filter( 'youzify_panel_general_settings_menus', array( $this, 'settings_menu' ), 9999 );

		// Process Data.
		add_action( 'wp_ajax_youzify_process_patch_data', array( $this, 'process_data' ) );
		add_action( 'youzer_upgrade_patches', array( $this, 'youzer_upgrade_patches_content' ) );

	}

	/**
	 * Add Patches Settings Tab
	 */
	function settings_menu( $tabs ) {

		if ( youzify_option( 'youzify_hide_upgrade_youzer' ) ) {
			return $tabs;
		}

		ob_start();
		do_action( 'youzify_patches_settings' );
		$content = ob_get_contents();
		ob_end_clean();

		if ( empty( $content ) ) {

			$tabs['youzer_upgrade'] = array(
		   	    'id'    => 'youzer_upgrade',
		   	    'icon'  => 'fas fa-people-carry',
		   	    'function' => array( $this, 'youzer_upgrade_patches' ),
		   	    'title' => __( 'Upgrade Youzer to Youzify', 'youzify' ),
		    );

		}

	    return $tabs;

	}

	/**
	 * Settings
	 */
	function youzer_upgrade_patches() {

		ob_start();
		do_action( 'youzer_upgrade_patches' );
		$content = ob_get_contents();
		ob_end_clean();

		if ( ! empty( $content ) ) {
			// Content.
			echo $content;
		    // Scripts
		    $this->scripts();
		} else {
			update_option( 'youzify_hide_upgrade_youzer', true );
		}
	}

	function youzer_upgrade_patches_content() {

	    global $Youzify_Settings, $wpdb, $bp;

	    if ( ! youzify_option( 'youzify_upgrade_move_youzer_panel' ) ) {

		    $Youzify_Settings->get_field(
		        array(
		            'title' => __( 'Move Youzer Panel Options', 'youzify' ),
		            'type'  => 'openBox'
		        )
		    );

		    // Delete Old Fields
		    // $wpdb->get_results( "DELETE FROM " . $wpdb->prefix . "options WHERE option_name Like 'yz_compress_%'" );

		    $Youzify_Settings->get_field(
		    	array(
			    	'class' => 'youzify-wild-field',
			        'desc'  => __( 'This operation will move all Youzer panel options data.', 'youzify' ),
			        'button_title' => __( 'Upgrade Now', 'youzify' ),
			        'button_data' => array(
			        	'run-single-patch' => 'true',
			        	'function' => 'move_youzer_panel',
			        ),
			        'id' => 'move_youzer_panel',
			        'type'  => 'button'
		    	)
		    );

		    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );
	    }

	    if ( ! youzify_option( 'youzify_upgrade_youzer_database_tables' ) ) {

		    $Youzify_Settings->get_field(
		        array(
		            'title' => __( 'Upgrade Youzer Database Tables', 'youzify' ),
		            'type'  => 'openBox'
		        )
		    );

		    $Youzify_Settings->get_field(
		    	array(
			    	'class' => 'youzify-wild-field',
			        'desc'  => __( 'This operation will move all Youzer database tables to Youzify.', 'youzify' ),
			        'button_title' => __( 'Upgrade Now', 'youzify' ),
			        'button_data' => array(
			        	'run-single-patch' => 'true',
			        	'function' => 'rename_youzer_database_tables',
			        ),
			        'id' => 'rename_youzer_database_tables',
			        'type'  => 'button'
		    	)
		    );

		    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );
	    }

	    if ( ! youzify_option( 'youzify_upgrade_move_users_meta' ) ) {

		    $Youzify_Settings->get_field(
		        array(
		            'title' => __( 'Move Youzer Users Meta', 'youzify' ),
		            'type'  => 'openBox'
		        )
		    );

		    // Get User Total Count.
			$user_count_query = count_users();

		    $Youzify_Settings->get_field(
		    	array(
			    	'class' => 'youzify-wild-field',
			        'desc'  => __( 'This operation will move all Youzer users meta like widgets data.', 'youzify' ),
			        'button_title' => __( 'Upgrade Now', 'youzify' ),
			        'button_data' => array(
			        	'run-patch' => 'true',
			        	'step' => 1,
			        	'items' => 'Users',
			        	'function' => 'move_users_meta',
			        	'total' => $user_count_query['total_users'],
			        	'perstep' => 10,
			        ),
			        'id' => 'move_users_meta',
			        'type'  => 'button'
		    	)
		    );

		    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );
	    }

	    if ( ! youzify_option( 'youzify_upgrade_move_activity_meta' ) ) {


		    // Get Total Count
			$total = $wpdb->get_var( "SELECT count(*) FROM " . $wpdb->prefix . "youzify_media" );

			if ( $total == 0 ) {
			    youzify_update_option( 'youzify_upgrade_move_activity_meta', true );
			} else {

			    $Youzify_Settings->get_field(
			        array(
			            'title' => __( 'Move Youzer Media Meta', 'youzify' ),
			            'type'  => 'openBox'
			        )
			    );

			    $Youzify_Settings->get_field(
			    	array(
				    	'class' => 'youzify-wild-field',
				        'desc'  => __( 'This operation will move all Youzer media meta.', 'youzify' ),
				        'button_title' => __( 'Upgrade Now', 'youzify' ),
				        'button_data' => array(
				        	'run-patch' => 'true',
				        	'step' => 1,
				        	'items' => 'Activities',
				        	'function' => 'move_activity_meta',
				        	'total' => $total,
				        	'perstep' => 10,
				        ),
				        'id' => 'move_activity_meta',
				        'type'  => 'button'
			    	)
			    );

			    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

			}
	    }

	    if ( ! youzify_option( 'youzify_upgrade_move_activity_meta2' ) ) {

		    // Get Total Count
			$total = $wpdb->get_var( "SELECT count(*) FROM {$bp->activity->table_name}" );

			if ( $total == 0 ) {
			    youzify_update_option( 'youzify_upgrade_move_activity_meta2', true );
			} else {

			    $Youzify_Settings->get_field(
			        array(
			            'title' => __( 'Move Youzer Activities Meta', 'youzify' ),
			            'type'  => 'openBox'
			        )
			    );

			    $Youzify_Settings->get_field(
			    	array(
				    	'class' => 'youzify-wild-field',
				        'desc'  => __( 'This operation will move all Youzer activities meta.', 'youzify' ),
				        'button_title' => __( 'Upgrade Now', 'youzify' ),
				        'button_data' => array(
				        	'run-patch' => 'true',
				        	'step' => 1,
				        	'items' => 'Activities',
				        	'function' => 'move_activity_meta2',
				        	'total' => $total,
				        	'perstep' => 1000,
				        ),
				        'id' => 'move_activity_meta2',
				        'type'  => 'button'
			    	)
			    );

		    	$Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );
			}

	    }

	    if ( ! youzify_option( 'youzify_upgrade_activity_reactions' ) ) {

			global $bp;

			$reactions_table = $wpdb->prefix . 'yz_reactions';

			if ( $wpdb->get_var( "SHOW TABLES LIKE '$reactions_table'" ) == $reactions_table ) {

			    // Get Total Count
				$total = $wpdb->get_var( "SELECT count(*) FROM {$bp->activity->table_name}" );

				if ( $total != 0 ) {

				    $Youzify_Settings->get_field(
				        array(
				            'title' => __( 'Move Optimize Activity Reactions Add-on', 'youzify' ),
				            'type'  => 'openBox'
				        )
				    );

				    $Youzify_Settings->get_field(
				    	array(
					    	'class' => 'youzify-wild-field',
					        'desc'  => __( 'This operation will optimize Activity Reactions Add-on to make it much more faster.', 'youzify' ),
					        'button_title' => __( 'Upgrade Now', 'youzify' ),
					        'button_data' => array(
					        	'run-patch' => 'true',
					        	'step' => 1,
					        	'items' => 'Activities',
					        	'function' => 'upgrade_activity_reactions',
					        	'total' => $total,
					        	'perstep' => 50,
					        ),
					        'id' => 'upgrade_activity_reactions',
					        'type'  => 'button'
				    	)
				    );

				    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

				} else {
					youzify_update_option( 'youzify_upgrade_activity_reactions', true );
				}
			} else {
				youzify_update_option( 'youzify_upgrade_activity_reactions', true );
	    	}
	    }

	}

	/**
	 * Move Youzer Panel Options.
	 */
	function move_youzer_panel( $step, $per_step, $total ) {

    	global $wpdb;

	    $total = $wpdb->get_results( "SELECT * FROM " . $wpdb->prefix . "options WHERE option_name Like 'yz\_%' OR option_name Like 'logy\_%' ", ARRAY_A );

	    foreach ( $total as $key => $option ) {

		    // Get New Key.
		    $new_key = str_replace( array( 'yz_', 'logy_' ), 'youzify_',  $option['option_name'] );

	        if ( empty( get_option( $new_key ) ) ) {

	        	// Get New Value.
	            $new_value = str_replace( array( 'logy-', 'klabs-', 'yz-' ), 'youzify-',  $option['option_value'] );

	            // Get New Value
	            $new_value = str_replace( 'yz_', 'youzify_', $new_value );

	            if ( is_serialized( $option['option_value'] ) ) {
	                $new_value = $this->fix_serialized( $new_value );
	            }

	            // Update Option.
            	youzify_update_option( $new_key, maybe_unserialize( $new_value ) );

	        }

	    }

    $options = array(
    	'logy_pages' => 'youzify_membership_pages',
    	'logy_login_form_title' => 'youzify_login_form_title',
    	'logy_login_signin_btn_title' => 'youzify_login_signin_btn_title',
    	'logy_login_register_btn_title' => 'youzify_login_register_btn_title',
    	'logy_login_lostpswd_title' => 'youzify_login_lostpswd_title',
    	'logy_login_form_subtitle' => 'youzify_login_form_subtitle',
    	'logy_lostpswd_form_title' => 'youzify_lostpswd_form_title',
    	'logy_lostpswd_submit_btn_title' => 'youzify_lostpswd_submit_btn_title',
    	'logy_lostpswd_form_subtitle' => 'youzify_lostpswd_form_subtitle',
    	'logy_signup_signin_btn_title' => 'youzify_signup_signin_btn_title',
    	'logy_signup_form_title' => 'youzify_signup_form_title',
    	'logy_signup_register_btn_title' => 'youzify_signup_register_btn_title',
    	'logy_signup_form_subtitle' => 'youzify_signup_form_subtitle',
    	'logy_login_form_enable_header'     => 'youzify_login_form_enable_header',
        'logy_login_form_layout'            => 'youzify_login_form_layout',
        'logy_login_icons_position'         => 'youzify_login_icons_position',
        'logy_login_actions_layout'         => 'youzify_login_actions_layout',
        'logy_login_btn_icons_position'     => 'youzify_login_btn_icons_position',
        'logy_login_btn_format'             => 'youzify_login_btn_format',
        'logy_login_fields_format'          => 'youzify_login_fields_format',
        'logy_social_btns_icons_position'   => 'youzify_social_btns_icons_position',
        'logy_social_btns_format'           => 'youzify_social_btns_format',
        'logy_social_btns_type'             => 'youzify_social_btns_type',
        'logy_enable_social_login'          => 'youzify_enable_social_login',
        'logy_enable_social_login_email_confirmation' => 'youzify_enable_social_login_email_confirmation',
        'logy_lostpswd_form_enable_header'  => 'youzify_lostpswd_form_enable_header',
        'logy_show_terms_privacy_note'      => 'youzify_show_terms_privacy_note',
        'logy_signup_form_enable_header'    => 'youzify_signup_form_enable_header',
        'logy_signup_actions_layout'        => 'youzify_signup_actions_layout',
        'logy_signup_btn_icons_position'    => 'youzify_signup_btn_icons_position',
        'logy_signup_btn_format'            => 'youzify_signup_btn_format',
        'logy_long_lockout_duration'    => 'youzify_membership_long_lockout_duration',
        'logy_short_lockout_duration'   => 'youzify_membership_short_lockout_duration',
        'logy_retries_duration'         => 'youzify_membership_retries_duration',
        'logy_enable_limit_login'       => 'youzify_enable_limit_login',
        'logy_allowed_retries'          => 'youzify_membership_allowed_retries',
        'logy_allowed_lockouts'         => 'youzify_membership_allowed_lockouts',
        'logy_login_retries'         => 'youzify_membership_login_retries',
        'logy_login_lockouts'         => 'youzify_membership_login_lockouts',
        'logy_is_installed'         => 'youzify_membership_is_installed',
        'logy_hide_subscribers_dash'         => 'youzify_hide_subscribers_dash',
        'logy_enable_recaptcha'         => 'youzify_enable_signup_recaptcha',
        'logy_recaptcha_site_key'         => 'youzify_signup_recaptcha_site_key',
        'logy_recaptcha_secret_key'         => 'youzify_signup_recaptcha_secret_key',
        'logy_plugin_margin_top' =>'youzify_membership_forms_margin_top',
        'logy_plugin_margin_bottom' =>'youzify_membership_forms_margin_bottom',
		'logy_login_remember_color' =>'youzify_login_remember_color',
		'logy_login_remember_color' =>'youzify_login_remember_color',
		'logy_login_checkbox_border_color' =>'youzify_login_checkbox_border_color',
		'logy_login_checkbox_icon_color' =>'youzify_login_checkbox_icon_color',
		'logy_login_submit_bg_color' =>'youzify_login_submit_bg_color',
		'logy_login_submit_txt_color' =>'youzify_login_submit_txt_color',
		'logy_login_regbutton_bg_color' =>'youzify_login_regbutton_bg_color',
		'logy_login_regbutton_txt_color' =>'youzify_login_regbutton_txt_color',
		'logy_login_wg_margin_top' =>'youzify_login_wg_margin_top',
		'logy_login_wg_margin_bottom' =>'youzify_login_wg_margin_bottom',
		'logy_register_wg_margin_top' =>'youzify_register_wg_margin_top',
		'logy_register_wg_margin_bottom' => 'youzify_register_wg_margin_bottom',

		'logy_signup_inputs_txt_color' => 'youzify_signup_inputs_txt_color',
		'logy_signup_inputs_bg_color' => 'youzify_signup_inputs_bg_color',
		'logy_signup_inputs_border_color' => 'youzify_signup_inputs_border_color',
		'logy_signup_fields_icons_color' => 'youzify_signup_fields_icons_color',
		'logy_signup_fields_icons_bg_color' => 'youzify_signup_fields_icons_bg_color',
		'logy_signup_title_color' => 'youzify_signup_title_color',
		'logy_signup_subtitle_color' => 'youzify_signup_subtitle_color',
		'logy_signup_cover_title_bg_color' => 'youzify_signup_cover_title_bg_color',
		'logy_signup_label_color' => 'youzify_signup_label_color',

		'logy_login_subtitle_color' => 'youzify_login_subtitle_color',
		'logy_login_cover_title_bg_color' => 'youzify_login_cover_title_bg_color',
		'logy_login_label_color' => 'youzify_login_label_color',
		'logy_login_inputs_txt_color' => 'youzify_login_inputs_txt_color',
		'logy_login_inputs_bg_color' => 'youzify_login_inputs_bg_color',
		'logy_login_inputs_border_color' => 'youzify_login_inputs_border_color',
		'logy_login_fields_icons_color' => 'youzify_login_fields_icons_color',
		'logy_login_fields_icons_bg_color' => 'youzify_login_fields_icons_bg_color',

		'logy_login_placeholder_color' => 'youzify_login_placeholder_color',
		'logy_login_lostpswd_color' => 'youzify_login_lostpswd_color',
		'logy_signup_submit_txt_color' => 'youzify_signup_submit_txt_color',
		'logy_signup_placeholder_color' => 'youzify_signup_placeholder_color',
		'logy_signup_placeholder_color' => 'youzify_signup_placeholder_color',
		'logy_signup_submit_bg_color' => 'youzify_signup_submit_bg_color',
		'logy_signup_loginbutton_bg_color' => 'youzify_signup_loginbutton_bg_color',
		'logy_signup_loginbutton_txt_color' => 'youzify_signup_loginbutton_txt_color',
		'logy_login_title_color' => 'youzify_login_title_color',
        'logy_terms_url' =>'youzify_membership_terms_url',
        'logy_show_terms_privacy_note' =>'youzify_membership_show_terms_privacy_note',
        'logy_privacy_url' =>'youzify_membership_terms_url',
        'logy_user_after_login_redirect' =>'youzify_user_after_login_redirect',
        'logy_admin_after_login_redirect' =>'youzify_admin_after_login_redirect',
        'logy_after_logout_redirect' =>'youzify_after_logout_redirect',
        'logy_login_custom_register_link' =>'youzify_login_custom_register_link',
		'logy_login_cover' => 'youzify_login_cover',
		'logy_signup_cover' => 'youzify_signup_cover',
		'logy_lostpswd_cover' => 'youzify_lostpswd_cover'
    );

		foreach ( $options as $old_option => $new_option ) {

			if ( ! empty( youzify_option( $new_option ) ) ) {

				$old_option_value = youzify_option( $old_option );

				if ( ! empty( $old_option_value ) ) {
					$old_option_value = str_replace( 'logy-', 'form-', $old_option_value );
					youzify_update_option( $new_option, $old_option_value );
				}

			}

		}

		/**
		 * Install Social Networks.
		 */

		// Get Providers.
		$providers = apply_filters( 'youzify_social_login_providers_list', array( 'Facebook', 'Twitter', 'Google', 'LinkedIn', 'Instagram', 'TwitchTV' ) );

		// Reset Social Provider Input's.
        foreach ( $providers as $provider ) {

        	// Transform Provider Name to lower case.
        	$provider = strtolower( $provider );

        	// Reset Provider Status's
			if ( youzify_option( 'logy_' . $provider . '_app_status' ) ) {
				youzify_update_option( 'youzify_' . $provider . '_app_status' );
			}

        	// Reset Provider Keys.
			if ( youzify_option( 'logy_' . $provider . '_app_key' ) ) {
				youzify_update_option( 'youzify_' . $provider . '_app_key' );
			}

        	// Reset Provider Secret Keys.
			if ( youzify_option( 'logy_' . $provider . '_app_secret' ) ) {
				youzify_update_option( 'youzify_' . $provider . '_app_secret' );
			}

        	// Reset Provider Notes.
			if ( youzify_option( 'logy_' . $provider .'_setup_steps' ) ) {
				youzify_update_option( 'youzify_' . $provider .'_setup_steps' );
			}

        }

	    $member_types = youzify_option( 'youzify_member_types' );

	    if ( ! empty( $member_types ) ) {

		    foreach ( $member_types as $type ) {

		        // Add Member Type.
		        $term = $this->bp_core_admin_insert_type(
		            array(
		                'taxonomy' => 'bp_member_type',
		                'bp_type_id' => $type['id'],
		                'bp_type_singular_name' => $type['singular'],
		                'bp_type_name' => $type['name'],
		                'bp_type_has_directory' => $type['show_in_md'] == 'true' ? true : false,
		                'bp_type_directory_slug' => $type['slug'],
		                'bp_type_show_in_list' => true
		            )
		        );

		        // Save Meta
		        if ( ! empty( $term ) && is_array( $term) ) {
		        	$term_id = $term['term_id'];
		        	$register = $type['register'] == 'true' ? true : false;
		            update_term_meta( $term_id, 'youzify_type_icon', $type['icon'] );
		            update_term_meta( $term_id, 'youzify_type_show_in_profile_settings',1 );
		            update_term_meta( $term_id, 'youzify_type_bg_left_color', $type['left_color'] );
		            update_term_meta( $term_id, 'youzify_type_bg_right_color', $type['right_color'] );
		     		update_term_meta( $term_id, 'youzify_type_show_in_registration', $register );
		        }
		    }
		}

	    youzify_update_option( 'youzify_upgrade_move_youzer_panel', true );

		echo json_encode( array( 'step' => 'done' ) ); exit;

	}

	function bp_core_admin_insert_type( $args = array() ) {

	$default_args = array(
		'taxonomy'   => '',
		'bp_type_id' => '',
	);

	$args = array_map( 'wp_unslash', $args );
	$args = bp_parse_args(
		$args,
		$default_args,
		'admin_insert_type'
	);

	if ( ! $args['bp_type_id'] || ! $args['taxonomy'] ) {
		//  return new WP_Error(
		// 	 'invalid_type_taxonomy',
		// 	 __( 'The Type ID value is missing', 'buddypress' ),
		// 	 array(
		// 		'message' => 1,
		// 	 )
		// );
		return;
	}

	$type_id       = sanitize_title( $args['bp_type_id'] );
	$type_taxonomy = sanitize_key( $args['taxonomy'] );

	/**
	 * Filter here to check for an already existing type.
	 *
	 * @since 7.0.0
	 *
	 * @param boolean $value   True if the type exists. False otherwise.
	 * @param string  $type_id The Type's ID.
	 */
	$type_exists = apply_filters( "{$type_taxonomy}_check_existing_type", false, $type_id );

	if ( false !== $type_exists ) {
		return new WP_Error(
			'type_already_exists',
			__( 'The Type already exists', 'buddypress' ),
			array(
			   'message' => 5,
			)
	   );
		return;
	}

	// Get defaulte values for metadata.
	// $metadata = bp_core_admin_get_type_default_meta_values( $type_taxonomy );

	// Validate metadata
	// $metas = array_filter( array_intersect_key( $args, $metadata ) );

	// Insert the Type into the database.
	$type_term_id = bp_insert_term(
		$type_id,
		$type_taxonomy,
		array(
			'slug'  => $type_id,
			'metas' => $args,
		)
	);

	if ( is_wp_error( $type_term_id ) ) {
		$type_term_id->add_data(
			array(
				'message' => 3,
			)
		);

		return $type_term_id;
	}

	/**
	 * Hook here to add code once the type has been inserted.
	 *
	 * @since 7.0.0
	 *
	 * @param integer $type_term_id  The Type's term_ID.
	 * @param string  $type_taxonomy The Type's taxonomy name.
	 * @param string  $type_id       The Type's ID.
	 */
	do_action( 'bp_type_inserted', $type_term_id, $type_taxonomy, $type_id );

	// Finally return the inserted Type's term ID.
	return $type_term_id;
}

	/**
	 * Move Database Tables.
	 */
	function rename_youzer_database_tables( $step, $per_step, $total ) {

    	global $wpdb;

	    $tables= array(
	        'yz_media' => 'youzify_media',
	        'yz_bookmark' => 'youzify_bookmarks',
	        'logy_users' => 'youzify_social_login_users',
	        'yz_reviews' => 'youzify_reviews',
	        'yz_hashtags' => 'youzify_hashtags',
	        'yz_hashtags_items' => 'youzify_hashtags_items',
	    );

	    foreach ( $tables as $old_table => $new_table ) {
	        $wpdb->query( "DROP TABLE IF EXISTS " . $wpdb->prefix . $new_table );
	        $wpdb->query( 'ALTER TABLE ' . $wpdb->prefix . $old_table . ' RENAME TO ' . $wpdb->prefix . $new_table . ';' );
	    }

	    youzify_update_option( 'youzify_upgrade_youzer_database_tables', true );

		echo json_encode( array( 'step' => 'done' ) ); exit;

	}

	/**
	 * Move User Meta.
	 */
	function move_users_meta( $step, $per_step, $total ) {

		// Init Vars
		$more = false;
		$i      = 1;
		$offset = $step > 1 ? ( $per_step * ( $step - 1 ) ) : 0;

		$done = $offset > $total ? true :  false;

		if ( ! $done ) {

	    	global $wpdb;

			$more = true;

			// main user query
			$args = array(
			    'fields'    => 'id',
			    'number'    => $per_step,
			    'offset'    => $offset
			);

			// Get the results
			$authors = get_users( $args );

			// Update Fields.
			foreach ( $authors as $user_id ) {

			    $options = $wpdb->get_results( "SELECT * FROM " . $wpdb->prefix . "usermeta WHERE  meta_key Like 'wg_%' AND user_id = $user_id OR meta_key Like 'youzer_%' and user_id = $user_id  OR meta_key Like 'yz\_%' and user_id = $user_id ", ARRAY_A );

			    if ( ! empty( $options ) ) {

				    foreach ( $options as $key => $option ) {

				        // Get New Key.
				        $new_key = str_replace( 'wg_' , 'youzify_wg_', $option['meta_key'] );
				        $new_key = str_replace( array( 'yz_', 'youzer_' ), 'youzify_', $new_key );

				        if ( $new_key == 'youzify_wg_instagram_account_user_data' && ! empty( $option['meta_value'] ) ) {
			                  $option['meta_value'] = youzify_convert_incomplete_class_to_object( $option['meta_value'] );
			            }

				        if ( empty( get_user_meta( $user_id, $new_key, true ) ) ) {
				            update_user_meta( $user_id, $new_key, maybe_unserialize( $option['meta_value'] ) );
				        }

				    }
			    }

			    // Get Views Number.
			    $views_nbr = (int) get_post_meta( $user_id, 'profile_views_count', true );

			    if ( ! empty( $views_nbr ) ) {

			    	// Update Views
			        $new_views = (int) get_user_meta( $user_id, 'youzify_profile_views_count', true );
			        $total = $new_views + $views_nbr;
			        update_user_meta( $user_id, 'youzify_profile_views_count', $total );

			    	// Update IPs
			        $new_ips = get_user_meta( $user_id, 'profile_views_ip', true );
			        update_user_meta( $user_id, 'youzify_profile_views_ip', $new_ips );

			    }

			    // Get Post ID
			    $post_widget_id = get_user_meta( $user_id, 'yz_profile_wg_post_id', true );

			    if ( ! empty( $post_widget_id ) && empty( get_user_meta( $user_id, 'youzify_wg_post_id', true ) ) ) {
			        update_user_meta( $user_id, 'youzify_wg_post_id', $post_widget_id );
			    }

			    // Get Social Avatar
			    $social_avatar = get_user_meta( $user_id, 'logy_avatar', true );

			    if ( ! empty( $social_avatar ) && empty( get_user_meta( $user_id, 'youzify_social_avatar', true ) ) ) {
			        update_user_meta( $user_id, 'youzify_social_avatar', $social_avatar );
			    }

			}

		} else {
		    youzify_update_option( 'youzify_upgrade_move_users_meta', true );
		}

		return $more;
	}

	/**
	 * Move Activity Meta.
	 */
	function move_activity_meta( $step, $per_step, $total ) {

		// Init Vars
		$more = false;
		$i      = 1;
		$offset = $step > 1 ? ( $per_step * ( $step - 1 ) ) : 0;

		$done = $offset > $total ? true :  false;

		if ( ! $done ) {

	    	global $wpdb;

			$more = true;

		    // Get Global Request
			$files = $wpdb->get_results( "SELECT id, item_id, component FROM " . $wpdb->prefix . "youzify_media LIMIT $per_step OFFSET $offset", ARRAY_A );

			if ( ! empty( $files ) ) {

			    foreach ( $files as $file ) {

			    	switch ( $file['component'] ) {
			    		case 'activity':
			    		case 'comment':
			    		case 'groups':

					        $old_activity = bp_activity_get_meta( $file['item_id'], 'yz_attachments' );

					        if ( ! empty( $old_activity ) && empty( bp_activity_get_meta( $file['item_id'], 'youzify_attachments' )  ) ) {
					            bp_activity_update_meta( $file['item_id'], 'youzify_attachments', $old_activity );
					        }

			    			break;

			    		case 'message':

					        $old_media = bp_messages_get_meta( $file['item_id'], 'yz_attachments' );

					        if ( ! empty( $old_media ) && empty( bp_messages_get_meta( $file['item_id'], 'youzify_attachments' )  ) ) {
					            bp_messages_update_meta( $file['item_id'], 'youzify_attachments', $old_media );
					        }
			    			break;
			    	}


			    }

			}

		} else {
		    youzify_update_option( 'youzify_upgrade_move_activity_meta', true );
		}

		return $more;
	}

	/**
	 * Move Activity Meta.
	 */
	function move_activity_meta2( $step, $per_step, $total ) {

		// Init Vars
		$more = false;
		$i      = 1;
		$offset = $step > 1 ? ( $per_step * ( $step - 1 ) ) : 0;

		$done = $offset > $total ? true :  false;

		if ( ! $done ) {

	        global $wpdb, $bp;

			$more = true;

	        // Get Global Request
	         $files = $wpdb->get_results( "SELECT id, type FROM {$bp->activity->table_name} LIMIT $per_step OFFSET $offset", ARRAY_A );

	        if ( ! empty( $files ) ) {

	            foreach ( $files as $file ) {

	                $share_count = bp_activity_get_meta( $file['id'], 'yz_activity_share_count' );

	                if ( ! empty( $share_count ) ) {
	                    bp_activity_update_meta( $file['id'], 'youzify_activity_share_count', $share_count );
	                }

	                if ( $file['type'] == 'activity_quote' ) {

	                    $old_quote  = bp_activity_get_meta( $file['id'], 'yz-quote-text' );
	                    if ( ! empty( $old_quote ) ) {
	                        bp_activity_update_meta( $file['id'], 'youzify-quote-text', $old_quote );
	                    }

	                    $old_quote_owner  = bp_activity_get_meta( $file['id'], 'yz-quote-owner' );

	                    if ( ! empty( $old_quote_owner ) && empty( bp_activity_get_meta( $file['id'], 'youzify-quote-owner' )  ) ) {
	                        bp_activity_update_meta( $file['id'], 'youzify-quote-owner', $old_quote_owner );
	                    }

	                }

	                if ( $file['type'] == 'activity_link' ) {
	                    $old_link  = bp_activity_get_meta( $file['id'], 'yz-link-url' );

	                    if ( ! empty( $old_link ) && empty( bp_activity_get_meta( $file['id'], 'youzify-link-url' )  ) ) {
	                        bp_activity_update_meta( $file['id'], 'youzify-link-url', $old_link );
	                        bp_activity_update_meta( $file['id'], 'youzify-link-desc', bp_activity_get_meta( $file['id'], 'yz-link-desc' ) );
	                        bp_activity_update_meta( $file['id'], 'youzify-link-title', bp_activity_get_meta( $file['id'], 'yz-link-title' ) );
	                    }
	                }

	            }
	        }

		} else {
		    youzify_update_option( 'youzify_upgrade_move_activity_meta2', true );
		}

		return $more;
	}

	/**
	 * Move Activity Meta.
	 */
	function upgrade_activity_reactions( $step, $per_step, $total ) {

		// Init Vars
		$more = false;
		$i      = 1;
		$offset = $step > 1 ? ( $per_step * ( $step - 1 ) ) : 0;

		$done = $offset > $total ? true :  false;

		if ( ! $done ) {

			$more = true;

	    	global $wpdb, $bp;


		    // Get Global Request
			$activities = $wpdb->get_results( "SELECT * FROM {$bp->activity->table_name} LIMIT $per_step OFFSET $offset", ARRAY_A );

			if ( empty( $activities ) ) {
				return false;
			}

			$reactions_table = $wpdb->prefix . 'yz_reactions';

			foreach ( $activities as $activity ) {

				// Get Result
				$reactions = $wpdb->get_results( $wpdb->prepare( "SELECT emoji_id FROM $reactions_table WHERE activity_id = %d", $activity['id'] ), ARRAY_A );

				if ( ! empty( $reactions ) ) {

					// Get Result
					$reactions = wp_list_pluck( $reactions, 'emoji_id' );

					// Get Result with Count.
					$reactions = array( 'emojis' => array_count_values( $reactions ), 'total' => count( $reactions ) );

					// Update Meta
					bp_activity_update_meta( $activity['id'], 'youzify_activity_reactions', $reactions );

				}

			}

		} else {
		    youzify_update_option( 'youzify_upgrade_activity_reactions', true );
		}

		return $more;
	}

	/**
	 * Process batch exports via ajax
	 */
	function process_data() {

		// Init Vars.
		$total = isset( $_POST['total'] ) ? absint( $_POST['total'] ): 1;
		$step = isset( $_POST['step'] ) ? absint( $_POST['step'] ) : 1;
		$perstep = isset( $_POST['perstep'] ) ? absint( $_POST['perstep'] ) : 1;
		$function = isset( $_POST['function'] ) ? $_POST['function']: null;

		$ret = $this->$function( $step, $perstep, $total );

		// Get Percentage.
		$percentage = ( $step * $perstep / $total ) * 100;

		if ( $ret ) {
			$step += 1;
			echo json_encode( array( 'users' => $ret, 'step' => $step, 'total'=> $total, 'perstep' => $perstep, 'percentage' => $percentage ) ); exit;
		} else {
			echo json_encode( array( 'step' => 'done' ) ); exit;
		}

	}

	/**
	 * Scripts
	 */
	function scripts() { ?>

	<script type="text/javascript">

	( function( $ ) {

		/**
		 * Process Updating Fields.
		 */
		$.youzify_patch_process_step = function( current_button, callback, step, perstep, total, self ) {
			$.ajax({
				type: 'POST',
				url: ajaxurl,
				data: {
					action: 'youzify_process_patch_data',
					step: step,
					total: total,
					perstep: perstep,
					function: callback
				},
				dataType: 'json',
				success: function( response ) {
					if ( 'done' == response.step ) {

						current_button.addClass( 'youzify-is-updated' );

						// window.location = response.url;
						current_button.html( '<i class="fas fa-check"></i>Done !' );

					} else {

						current_button.find( '.youzify-button-progress' ).animate({
							width: response.percentage + '%',
						}, 50, function() {
							// Animation complete.
						});

						var total_items = ( response.step * response.perstep ) - response.perstep,
							items = total_items < response.total ? total_items : response.total;

						current_button.find( '.youzify-items-count' ).html( items );

						$.youzify_patch_process_step( current_button, callback, parseInt( response.step ), parseInt( response.perstep ), parseInt( response.total ), self );

					}

				}
			}).fail( function ( response ) {
				if ( window.console && window.console.log ) {
					console.log( response );
				}
			});

		}


		/**
		 * Process Updating Fields.
		 */
		$.youzify_run_patch = function( current_button, callback, self ) {

			$.ajax({
				type: 'POST',
				url: ajaxurl,
				data: {
					action: 'youzify_process_patch_data',
					function: callback,
				},
				dataType: 'json',
				success: function( response ) {

					if ( 'done' == response.step ) {

						current_button.addClass( 'youzify-is-updated' );

						// window.location = response.url;
						current_button.html( '<i class="fas fa-check"></i>Done!' );

					}

				}
			}).fail( function ( response ) {
				if ( window.console && window.console.log ) {
					console.log( response );
				}
			});

		}

		$( 'body' ).on( 'click', 'a[data-run-single-patch="true"]', function(e) {

			if ( $( this ).hasClass( 'youzify-is-updated' ) ) {
				return;
			}

			e.preventDefault();

			$( this ).html( '<i class="fas fa-spinner fa-spin"></i>Updating...' );

			// Start The process.
			$.youzify_run_patch( $( this ), $( this ).data( 'function' ), self );


		});

		$( 'body' ).on( 'click', 'a[data-run-patch="true"]', function(e) {

			if ( $( this ).hasClass( 'youzify-is-updated' ) ) {
				return;
			}

			e.preventDefault();

			var per_step = $( this ).data( 'perstep' );
			var total = $( this ).data( 'total' );
			var callback = $( this ).data( 'function' );

			$( this ).html( '<i class="fas fa-spinner fa-spin"></i>Updating <div class="youzify-button-progress"></div><span class="youzify-items-count">' + 1 + '</span>' + ' / ' + total + ' ' + $( this ).data( 'items' ) );

			// Start The process.
			$.youzify_patch_process_step( $( this ), callback, 1, per_step, total, self );

		});

	})( jQuery );

	</script>

		<?php

	}

	// Fix Serialized.
	function fix_serialized($string) {
	    // securities
	    if ( !preg_match( '/^[aOs]:/', $string ) ) return $string;
	    if ( @unserialize( $string ) !== false ) return $string;
	    $string = preg_replace( "%\n%", "", $string );
	    // doublequote exploding
	    $data = preg_replace( '%";%', "µµµ", $string );
	    $tab = explode( "µµµ", $data );
	    $new_data = '';
	    foreach ( $tab as $line ) {
	        $new_data .= preg_replace_callback( '%\bs:(\d+):"(.*)%', 'youzify_fix_str_length', $line );
	    }

	    return $new_data;
	}
}

new Youzify_Move_Youzer();