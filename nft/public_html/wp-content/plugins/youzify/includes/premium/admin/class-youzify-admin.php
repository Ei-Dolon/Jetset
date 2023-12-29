<?php

class Youzify_Admin_Pro {

	function __construct() {

		// Include file.
		$this->includes();

		// Mark All Features as available.
		add_action( 'youzify_is_feature_available', '__return_true' );

		// Add Profile Custom Widgets Settings.
		add_action( 'youzify_profile_custom_widgets_settings', array( $this, 'profile_custom_widgets_settings' ) );

		// Add Profile Custom Tab Settings.
		add_action( 'youzify_profile_custom_tabs_settings', array( $this, 'profile_custom_tabs_settings' ) );

		// Change Review Link.
		add_filter( 'youzify_plugin_post_new_review_link', array( $this, 'set_review_link' ) );

	}

	/**
	 * Custom Tab Settings.
	 */
	function includes() {

		if ( ! get_option( 'youzify_hide_old_patches' ) ) {

			// Include Patches.
			include YOUZIFY_PATH . 'includes/premium/admin/youzify-patches.php';
			include YOUZIFY_PATH . 'includes/premium/admin/functions/youzify-upgrade-wordpress-library.php';
			include YOUZIFY_PATH . 'includes/premium/admin/functions/youzify-move-youzer.php';

		}

	}

	/**
	 * Custom Tab Settings.
	 */
	function profile_custom_tabs_settings() {

    	wp_enqueue_script( 'youzify-custom-tabs', YOUZIFY_URL . 'includes/premium/admin/assets/js/youzify-custom-tabs.min.js', array( 'jquery' ), YOUZIFY_VERSION, true );

	    wp_localize_script( 'youzify-custom-tabs', 'Youzify_Custom_Tabs', array(
	        'tab_url_empty'   => __( 'Tab link URL is empty!', 'youzify' ),
	        'no_custom_tabs'  => __( 'No custom tabs found!', 'youzify' ),
	        'tab_code_empty'  => __( 'Tab content is empty!', 'youzify' ),
	        'tab_title_empty' => __( 'Tab title is empty!', 'youzify' ),
	        'update_tab'      => __( 'Update Tab', 'youzify' )
	    ) );

	    // Get New Custom Tabs Form.
	    youzify_panel_modal_form( array(
	        'id'        => 'youzify-custom-tabs-form',
	        'title'     => __( 'Create New Tab', 'youzify' ),
	        'button_id' => 'youzify-add-custom-tab'
	    ), 'youzify_profile_custom_tabs_form' );

	}

	/**
	 * Custom Tab Settings.
	 */
	function profile_custom_widgets_settings() {

	    wp_enqueue_script( 'youzify-profile-widgets', YOUZIFY_URL . 'includes/premium/admin/assets/js/youzify-profile-widgets.min.js', array( 'jquery' ), YOUZIFY_VERSION, true );
	    wp_localize_script( 'youzify-profile-widgets', 'Youzify_Custom_Widgets', array(
	        'update_widget' => __( 'Update Widget', 'youzify' ),
	        'no_custom_widgets' => __( 'No custom widgets found!', 'youzify' )
	    ) );

	    // Get New Custom Widgets Form.
	    youzify_panel_modal_form( array(
	        'id'        => 'youzify-custom-widgets-form',
	        'title'     => __( 'Create New Widget', 'youzify' ),
	        'button_id' => 'youzify-add-custom-widget'
	    ), 'youzify_get_custom_widgetsform' );


	}

	/**
	 * Set Post Review Link
	 */
	function set_review_link() {
		return 'https://codecanyon.net/item/youzer-new-wordpress-user-profiles-era/19716647';
	}

}

new Youzify_Admin_Pro();