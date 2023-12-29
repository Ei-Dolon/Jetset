<?php
/**
 * Hashtags Class
 */
class Youzify_Account_Settings_Pro {

	function __construct() {
		add_action( 'youzify_after_account_settings_head', array( $this, 'get_switch_lighting_mode_button'  ) );
	}

	/**
	 * Get Lighting Switch Mode
	 */
	function get_switch_lighting_mode_button() {

		// Check if lighting is editable
	    if ( youzify_option( 'youzify_allow_lighting_edition', 'on' ) == 'off' || ! bp_is_my_profile() ) {
	    	return;
	    }

        // Get User ID.
        $user_id = bp_displayed_user_id();

        $mode = get_user_meta( $user_id, 'youzify_lighting_mode', true );

        if ( empty( $mode ) ) {
            $mode = youzify_option( 'youzify_default_lighting_mode', 'light' );
        }

		?>

		<div class="youzify-lighting-mode">
			<span class="youzify-switch-mode" data-dark-label="<?php _e( 'Dark Mode', 'youzify' ); ?>" data-light-label="<?php _e( 'Light Mode', 'youzify' ); ?>" data-youzify-tooltip="<?php echo $mode == 'light' ? __( 'Dark Mode', 'youzify' ) : __( 'Light Mode', 'youzify' ); ?>" data-mode="<?php echo $mode == 'light' ? 'dark' : 'light'; ?>"><i class="<?php echo $mode == 'light' ? 'far fa-moon' : 'fas fa-sun'; ?>"></i></span>
		</div>

		<?php
	}
}

new Youzify_Account_Settings_Pro();