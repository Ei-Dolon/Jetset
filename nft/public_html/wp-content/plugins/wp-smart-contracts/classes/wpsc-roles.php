<?php

if( ! defined( 'ABSPATH' ) ) die;

new WPSC_roles();

class WPSC_roles {

	function __construct() {
		// enable uploads to the selected role users
		add_action('init', [$this, "allowUploads"]);
		// Limit media library access for madia owned by users
		add_action('pre_get_posts', [$this, 'usersOwnAttachments']);
	}

	// enable upload feature for frontend users with the setup assigned role
	public function allowUploads() {
		$options = get_option('etherscan_api_key_option');
		$wpsc_role = (WPSC_helpers::valArrElement($options, "wpsc_role") and !empty($options["wpsc_role"]))?$options["wpsc_role"]:false;
		$wpsc_add_upload = (WPSC_helpers::valArrElement($options, "wpsc_add_upload") and !empty($options["wpsc_add_upload"]))?$options["wpsc_add_upload"]:false;

		if ($wpsc_role != "deactivated") {
			$the_role = get_role($wpsc_role);
			switch ($wpsc_add_upload) {
				case "yes":
					$the_role->add_cap('upload_files');
					break;
				case "no":
					$the_role->remove_cap('upload_files');
					break;	
			}
		}
	}

	// filter media to show only user's own media
	public function usersOwnAttachments( $wp_query_obj ) {

		global $current_user, $pagenow;
	
		$is_attachment_request = ($wp_query_obj->get('post_type')=='attachment');
	
		if( !$is_attachment_request ) {
			return;
		}

		if( !is_a( $current_user, 'WP_User') ) {
			return;
		}

		if( !in_array( $pagenow, array( 'upload.php', 'admin-ajax.php' ) ) ) {
			return;
		}
	
		if( !current_user_can('delete_pages') ) {
			$wp_query_obj->set('author', $current_user->ID );
		}
	
		return;
	}

}