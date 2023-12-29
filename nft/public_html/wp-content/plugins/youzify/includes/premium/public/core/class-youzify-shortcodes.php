<?php

class Youzify_Shortcodes {

	function __construct() {

		// Activity Directory Shortcode.
		add_shortcode( 'youzify_activity', array( $this, 'activity_shortcode' ) );

		// Members Directory Shortcode.
		add_shortcode( 'youzify_members', array( $this, 'members_directory_shortcode' ) );

		// Groups Directory Shortcode.
		add_shortcode( 'youzify_groups', array( $this, 'groups_directory_shortcode' ) );

	}

	/**
	 * Activity Shortcode.
	 **/
	function activity_shortcode( $atts ) {

		if ( is_admin() || ! bp_is_active( 'activity' ) ) {
			return;
		}

		// Include Wall Files.
	    youzify()->include_activity_files();

	    global $youzify_activity_shortcode_args;

		// Call Mentions Scripts.
	    add_filter( 'bp_activity_maybe_load_mentions_scripts', '__return_true' );

	    bp_activity_mentions_script();

		do_action( 'youzify_before_activity_shortcode' );

		// Get Args.
		$youzify_activity_shortcode_args = wp_parse_args( $atts, array( 'page' => 1, 'show_sidebar' => 'false', 'show_form' => 'true', 'load_more' => 'true', 'show_filter' => 'true' ) );

		if ( $youzify_activity_shortcode_args['show_sidebar'] == 'false' ) {
		    // Remove Sidebar.
		    add_filter( 'youzify_activate_activity_stream_sidebar', '__return_false' );
		}

		$class = $youzify_activity_shortcode_args['show_sidebar'] == 'false' ? 'youzify-no-sidebar' : 'youzify-with-sidebar';

	    // Add Filter.
	    add_filter( 'bp_after_has_activities_parse_args', 'youzify_set_activity_stream_shortcode_atts', 99 );

	    if ( isset( $youzify_activity_shortcode_args['form_roles'] ) ) {
	    	add_filter( 'youzify_is_wall_posting_form_active', 'youzify_set_wall_posting_form_by_role' );
	    }

	    if ( $youzify_activity_shortcode_args['show_form'] == 'false' ) {
	    	add_filter( 'youzify_is_wall_posting_form_active', '__return_false' );
	    }

	    if ( $youzify_activity_shortcode_args['show_filter'] == 'false' ) {
	    	add_filter( 'youzify_enable_activity_directory_filter_bar', '__return_false' );
	    }

	    if ( $youzify_activity_shortcode_args['load_more'] == 'false' ) {
	    	add_filter( 'bp_activity_has_more_items', '__return_false' );
	    }

	    $activity_data = '';

	    if ( ! empty( $youzify_activity_shortcode_args ) ) foreach ( $youzify_activity_shortcode_args as $key => $value) { $activity_data .= "data-$key='$value'"; }

		ob_start();
	    echo "<div class='youzify-activity-shortcode $class' style='display: none;' $activity_data>";
	    include YOUZIFY_TEMPLATE . 'activity/index.php';
	    echo "</div>";

		if ( $youzify_activity_shortcode_args['show_sidebar'] == 'false' ) {
		    // Remove Sidebar.
		    remove_filter( 'youzify_activate_activity_stream_sidebar', '__return_false' );
		}

	    if ( $youzify_activity_shortcode_args['show_filter'] == 'false' ) {
	    	remove_filter( 'youzify_enable_activity_directory_filter_bar', '__return_false' );
	    }

	    if ( isset( $youzify_activity_shortcode_args['form_roles'] ) ) {
	    	remove_filter( 'youzify_is_wall_posting_form_active', 'youzify_set_wall_posting_form_by_role' );
	    }

	    if ( $youzify_activity_shortcode_args['show_form'] == 'false' ) {
	    	remove_filter( 'youzify_is_wall_posting_form_active', '__return_false' );
	    }

	    if ( $youzify_activity_shortcode_args['load_more'] == 'false' ) {
	    	remove_filter( 'bp_activity_has_more_items', '__return_false' );
	    }

	    // Add Filter.
	    remove_filter( 'bp_after_has_activities_parse_args', 'youzify_set_activity_stream_shortcode_atts', 99 );

		return ob_get_clean();
	}

	/**
	 * Members Directory Shortcode
	 */
	function members_directory_shortcode( $atts ) {

		if ( is_admin() ) {
			return;
		}

		// Filters
	    add_filter( 'bp_is_current_component', 'youzify_enable_shortcode_md', 10, 2 );
	    add_filter( 'bp_is_directory', '__return_true' );

	    // Scripts
	    wp_enqueue_script( 'masonry' );
	    wp_enqueue_style( 'youzify-directories', YOUZIFY_ASSETS . 'css/youzify-directories.min.css', array( 'dashicons' ), YOUZIFY_VERSION );
	    wp_enqueue_script( 'youzify-directories', YOUZIFY_ASSETS .'js/youzify-directories.min.js', array( 'jquery' ), YOUZIFY_VERSION, true );

	    global $youzify_md_shortcode_atts;

	    // Get Args.
	    $youzify_md_shortcode_atts = wp_parse_args( $atts, array( 'per_page' => 12, 'member_type' => false, 'show_filter' => 'false', 'exclude' => false ) );

	    // Add Filter.
	    add_filter( 'bp_after_has_members_parse_args', 'youzify_set_members_directory_shortcode_atts', 0 );

	    if ( $youzify_md_shortcode_atts['show_filter'] == false ) {
	        add_filter( 'youzify_display_members_directory_filter', '__return_false' );
	    }

	    $directory_data = '';

	    if ( ! empty( $youzify_md_shortcode_atts ) ) foreach ( $youzify_md_shortcode_atts as $key => $value) { $directory_data .= "data-$key='" . esc_attr( $value ) . "'"; }

	    ob_start();

	    echo "<div class='youzify-members-directory-shortcode youzify-directory-shortcode' {$directory_data}>";
	    include YOUZIFY_TEMPLATE . 'members/index.php';
	    echo "</div>";

	    if ( isset( $youzify_md_shortcode_atts['member_type'] ) ) {

	    	$scope = $youzify_md_shortcode_atts['member_type'];
		    setcookie( 'bp-members-scope', $scope, null, '/' );
		    $_COOKIE['bp-members-scope'] = $scope;

	   	?>


	    <script type="text/javascript">

	    ( function( $ ) {

		    $( document ).ready( function() {
			    	$( '#youzify-members-directory .item-list-tabs li' ).removeClass( 'selected' );
			    	$( '#members-<?php echo $youzify_md_shortcode_atts['member_type']; ?>' ).addClass( 'selected' );
		 	});

	    })( jQuery );

	    </script>

	    <?php

		}

	    // Remove Filter.
	    remove_filter( 'bp_after_has_members_parse_args', 'youzify_set_members_directory_shortcode_atts' );


	    if ( $youzify_md_shortcode_atts['show_filter'] == false ) {
	        remove_filter( 'youzify_display_members_directory_filter', '__return_false' );
	    }

	    // Unset Global Value.
	    unset( $youzify_md_shortcode_atts );

	    remove_filter( 'bp_is_directory', '__return_true' );
	    remove_filter( 'bp_is_current_component', 'youzify_enable_shortcode_md', 10, 2 );

	    return ob_get_clean();
	}

	/**
	 * Groups Directory Shortcode.
	 */
	function groups_directory_shortcode( $atts ) {

		if ( is_admin() ) {
			return;
		}

		// Filter.
	    add_filter( 'youzify_is_groups_directory', '__return_true', 10 );
	    add_filter( 'bp_displayed_user_id', '__return_false', 10 );
	    add_filter( 'bp_is_current_component', 'youzify_enable_groups_directory_shortcode', 10, 2 );

	    // Scripts
	    wp_enqueue_script( 'masonry' );
	    wp_enqueue_style( 'youzify-directories', YOUZIFY_ASSETS . 'css/youzify-directories.min.css', array( 'dashicons' ), YOUZIFY_VERSION );
	    wp_enqueue_script( 'youzify-directories', YOUZIFY_ASSETS .'js/youzify-directories.min.js', array( 'jquery' ), YOUZIFY_VERSION, true );

	    global $youzify_gd_shortcode_atts;

	    // Get Args.
	    $youzify_gd_shortcode_atts = wp_parse_args( $atts, array( 'per_page' => 12, 'show_filter' => false ) );

	    // Add Filter.
	    add_filter( 'bp_after_has_groups_parse_args', 'youzify_set_groups_directory_shortcode_atts' );

	    if ( $youzify_gd_shortcode_atts['show_filter'] == false ) {
	        add_filter( 'youzify_display_groups_directory_filter', '__return_false' );
	    }

	    $directory_data = '';

	    if ( ! empty( $youzify_gd_shortcode_atts ) ) foreach ( $youzify_gd_shortcode_atts as $key => $value) { $directory_data .= "data-$key='". esc_attr( $value ) . "'"; }

	    ob_start();

	    echo "<div class='youzify-groups-directory-shortcode youzify-directory-shortcode' {$directory_data}>";
	    include YOUZIFY_TEMPLATE . 'groups/index.php';
	    echo "</div>";

	    // Remove Filter.
	    remove_filter( 'bp_after_has_groups_parse_args', 'youzify_set_groups_directory_shortcode_atts' );

	    if ( $youzify_gd_shortcode_atts['show_filter'] == false ) {
	        remove_filter( 'youzify_display_groups_directory_filter', '__return_false' );
	    }

	    // Unset Global Value.
	    unset( $youzify_gd_shortcode_atts );

	    remove_filter( 'bp_is_current_component', 'youzify_enable_groups_directory_shortcode', 10, 2 );
	    remove_filter( 'bp_displayed_user_id', '__return_false', 10 );

	    remove_filter( 'youzify_is_groups_directory', '__return_true', 10 );
	    return ob_get_clean();
	}


}

new Youzify_Shortcodes();