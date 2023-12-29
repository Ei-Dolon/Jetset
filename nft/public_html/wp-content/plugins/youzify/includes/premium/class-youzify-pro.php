<?php

/**
 * Youzify Pro Class.
 */
class Youzify_Pro {

	function __construct() {

		// Setup Constants.
		$this->setup_contants();

		if ( is_admin() ) {
			include YOUZIFY_PATH . 'includes/premium/admin/class-youzify-admin.php';

			// if ( ! class_exists( 'EDD_SL_Plugin_Updater' ) ) {
			// 	require_once YOUZIFY_PATH . 'includes/premium/admin/functions/kainelabs-plugins-updater.php';
			// }

		}

		// Includes
		add_action( 'init', array( $this, 'includes' ), 10 );
		add_action( 'plugins_loaded', array( $this, 'include_gamipress' ), 10 );

		// Load Widgets.
		add_action( 'widgets_init', array( $this, 'widgets' ) );

		// Include Wall Functions
		// add_action( 'youzify_activity_files', array( $this, 'include_activity_files' ) );
		// add_action( 'youzify_activity_files', array( $this, 'include_activity_files' ) );

		// Activity Comment Buttons
		add_action( 'youzify_activity_comment_buttons', array( $this, 'add_activity_comment_buttons' ) );

	}

	/**
	 * Include Gamipress Filess
	 */
	function include_gamipress() {

        if ( youzify_is_gamipress_active() ) {
            require YOUZIFY_CORE . 'gamipress/youzify-gamipress-functions.php';
        }
	}

	/**
	 * Constants.
	 */
	function setup_contants() {

		// Premium Core Path
		define( 'YOUZIFY_PREMIUM_CORE', YOUZIFY_PATH . 'includes/premium/public/core/' );

		// Premium Assets Path
		define( 'YOUZIFY_PREMIUM_ASSETS', YOUZIFY_URL . 'includes/premium/public/assets/' );

	}

	/**
	 * Widgets
	 */
	function widgets() {

		// Include Widgets Files.
	    require YOUZIFY_PREMIUM_CORE . 'widgets/wp-widgets/class-youzify-hashtags-widget.php';
	    require YOUZIFY_PREMIUM_CORE . 'widgets/wp-widgets/class-youzify-group-suggestions-widget.php';
	    require YOUZIFY_PREMIUM_CORE . 'widgets/wp-widgets/class-youzify-friend-suggestions-widget.php';
	    require YOUZIFY_PREMIUM_CORE . 'widgets/wp-widgets/class-youzify-community-hashtags-widget.php';

	    // Init Widgets
	    register_widget( 'Youzify_Hashtags_Widget' );
	    register_widget( 'Youzify_Community_Hashtags_Widget' );
	    register_widget( 'Youzify_Group_Suggestions_Widget' );
	    register_widget( 'Youzify_Friend_Suggestions_Widget' );

	}

	/**
	 * Include Files.
	 */
	function includes() {

    	if ( wp_doing_ajax() ) {
        	include YOUZIFY_PREMIUM_CORE . 'class-youzify-ajax.php';
    	}

    	if ( ! is_user_logged_in() && youzify_is_membership_system_active() ) {
        	include YOUZIFY_PREMIUM_CORE . 'membership/class-youzify-login.php';
    	}

    	// Include Hashtags.
        include YOUZIFY_PREMIUM_CORE . 'class-youzify-account.php';
        include YOUZIFY_PREMIUM_CORE . 'class-youzify-hashtags.php';
        include YOUZIFY_PREMIUM_CORE . 'class-youzify-messages.php';
        include YOUZIFY_PREMIUM_CORE . 'class-youzify-shortcodes.php';

        // Include Activity Files.
        $this->include_activity_files();

        // Include Functions
        require YOUZIFY_PREMIUM_CORE . 'functions/youzify-bp-uploader-functions.php';
        
        if ( bp_is_active( 'notifications' ) ) {

            // Include Live Notification.
            if ( 'on' == youzify_option( 'youzify_enable_live_notifications', 'on' ) ) {
                require YOUZIFY_PREMIUM_CORE . 'functions/youzify-live-notifications-functions.php';
            }

        }

	}

	/**
	 * Add Activity Comment Buttons.
	 */
	function add_activity_comment_buttons() {

		if ( youzify_option( 'youzify_wall_comments_attachments', 'on' ) == 'on' ) :

			add_action( 'bp_activity_entry_comments', array( $this, 'add_comment_attachments_container' ) );

		?>

		<div class="youzify-wall-upload-btn" data-youzify-tooltip="<?php _e( 'Upload Attachments', 'youzify' ); ?>"><i class="fas fa-paperclip"></i></div>
		<?php endif; ?>

		<?php if ( youzify_option( 'youzify_wall_comments_gif', 'on' ) == 'on' ) : ?>
		<div class="youzify-wall-add-gif" data-youzify-tooltip="<?php _e( 'Post a GIF', 'youzify' ); ?>">
			<i class="fas fa-photo-video"></i>
	    	<div class="youzify-comment-giphy-form youzify-wall-giphy-form">
	            <div class="youzify-giphy-search-form">
	                <input type="text" class="youzify-giphy-search-input" name="giphy_search" placeholder="<?php _e( 'Search for GIFs...', 'youzify' ); ?>">
	            </div>
	            <div class="youzify-giphy-items-content">
	                <div class="youzify-load-more-giphys" data-page="2"><i class="fas fa-ellipsis-h"></i></div>
					<div class="youzify-no-gifs-found"><i class="far fa-frown"></i><?php _e( 'No GIFs found', 'youzify' ); ?></div>
	            </div>
	        </div>
	    </div>
		<?php endif;

	}

	/**
	 * Add Attachments Container HTML.
	 */
	function add_comment_attachments_container() { ?>
		<div class="youzify-attachments youzify-wall-attachments">
			<input hidden="true" class="youzify-upload-attachments" type="file" name="attachments[]">
			<div class="youzify-form-attachments"></div>
		</div>
		<?php
	}

	/**
	 * Include Wall Files.
	 */
	function include_activity_files() {

		// Include Hashtags Functions.
    	require YOUZIFY_PREMIUM_CORE . 'activity/class-youzify-hashtags.php';

		// Include Activity Privacy Functions.
    	if ( youzify_enable_activity_privacy() ) {
	    	require YOUZIFY_PREMIUM_CORE . 'activity/class-youzify-privacy.php';
    	}

		// Include Activity Share Posts Functions.
    	if ( youzify_is_share_posts_active() ) {
			require YOUZIFY_PREMIUM_CORE . 'activity/class-youzify-share.php';
    	}

		// Include Activity Mood Posts Functions.
    	if ( youzify_enable_activity_mood() ) {
	    	require YOUZIFY_PREMIUM_CORE . 'activity/class-youzify-mood.php';
    	}

		// Include Activity Check in Functions.
    	if ( youzify_enable_activity_checkin() ) {
	    	require YOUZIFY_PREMIUM_CORE . 'activity/class-youzify-check-in.php';
    	}

		// Include Activity Tag Friends Functions.
    	if ( youzify_enable_activity_tag_friends() ) {
			require YOUZIFY_PREMIUM_CORE . 'activity/class-youzify-tag-users.php';
    	}

		// Include Activity Sticky Posts Functions.
    	if ( youzify_is_sticky_posts_active() ) {
			require YOUZIFY_PREMIUM_CORE . 'activity/class-youzify-sticky-posts.php';
    	}

		// Include Activity Bookmarks Posts Functions.
	    if ( $this->is_bookmark_active() ) {
			require YOUZIFY_PREMIUM_CORE . 'activity/class-youzify-bookmarks.php';
    	}

		// Include Activity Polls Functions.
	    if ( $this->is_polls_active() ) {
			require YOUZIFY_PREMIUM_CORE . 'activity/youzify-class-activity-polls.php';
			require YOUZIFY_PREMIUM_CORE . 'activity/youzify-class-activity-polls-form.php';
    	}

	}

    /**
	 * Check if "bookmarking posts" option is enabled.
	 */
	function is_bookmark_active() {
	    $activate = 'on' == youzify_option( 'youzify_enable_bookmarks', 'on' ) ? true : false;
	    return apply_filters( 'youzify_is_bookmarks_active', $activate );
	}

    /**
	 * Check if activity polls is enabled.
	 */
	function is_polls_active() {
	    $activate = 'on' == youzify_option( 'youzify_enable_activity_polls', 'on' ) ? true : false;
	    return apply_filters( 'youzify_is_activity_polls_active', $activate );
	}

}

new Youzify_Pro();