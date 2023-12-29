<?php

class Youzify_Pro_Login {

	function __construct() {

		// Pop-Up Login
        if ( youzify_option( 'youzify_enable_login_popup', 'off' ) == 'on' ) {
			add_action( 'wp_footer', array( $this, 'get_popup_login_form' ) );
			add_filter( 'nav_menu_link_attributes', array( $this, 'add_login_page_attribute' ), 10, 3 );
		}

		// Ajax Login.
	    if ( youzify_option( 'youzify_enable_ajax_login', 'off' ) == 'on' ) {
			add_action( 'wp_enqueue_scripts', array( $this, 'scripts' ) );
			add_action( 'wp_ajax_nopriv_youzify_ajax_login', array( $this, 'ajax_login' ) );
			add_action( 'youzify_after_login_buttons', array( $this, 'ajax_login_nonce' ) );
    	}

	}

	/**
	 * is Ajax Login Enabled
	 */
	function get_popup_login_form() { ?>

	    <div class="youzify-popup-login" style="opacity: 0; visibility: hidden;">
	        <?php echo do_shortcode( '[youzify_login]' ); ?>
	    </div>

	    <script type="text/javascript">
	    ( function( $ ) {

	        $( document ).ready( function() {

	            // Add Close Button to Login Popup.
	            $( '.youzify-popup-login .youzify-membership-form-header' )
	            .append( '<i class="fas fa-times youzify-close-login"></i>' );

	            // Display Login Popup.
	            $( '[data-show-youzify-login="true"],.youzify-show-youzify-login-popup a' ).on( 'click', function( e ) {

	                e.preventDefault();

	                $( '.youzify-popup-login:first' ).addClass( 'youzify-is-visible' );

	            });

	            // Close Login Popup.
	            $( '.youzify-popup-login' ).on( 'click', function( e ) {
	                if ( $( e.target ).is( '.youzify-close-login' ) || $( e.target ).is( '.youzify-popup-login' ) ) {
	                    e.preventDefault();
	                    $( this ).removeClass( 'youzify-is-visible' );
	                }
	            });

	            // Close Dialog if you user Clicked Cancel
	            $( '.youzify-close-login' ).on( 'click', function( e ) {
	                e.preventDefault();
	                $( '.youzify-popup-login' ).removeClass( 'youzify-is-visible' );
	            });

	        });

	    })( jQuery );

	    </script>

	    <?php
	}

	/**
	 * is Ajax Login Enabled
	 */
	function add_login_page_attribute( $atts, $item, $args ) {

	    // Get Login Page ID.
	    $login_page_id = youzify_membership_page_id( 'login' );

		// Add Attribute.
	    if ( ! empty( $login_page_id ) ) {
		    if ( $item->object_id == $login_page_id ) {
		        $atts['data-show-youzify-login'] = 'true';
		    }
	    }

	    return $atts;
	}

	/**
	 * Add Ajax Login Nonce
	 */
	function ajax_login_nonce() {

		// Get Ajax Nonce.
		$ajax_nonce = wp_create_nonce( 'youzify-ajax-login-nonce' );

		?>

		<input type="hidden" name="youzify_ajax_login_nonce" value="<?php echo $ajax_nonce; ?>">

		<?php
	}

	/**
	 * Ajaxed Login
	 */
	function ajax_login() {

	    // First check the nonce, if it fails the function will break.
	    check_ajax_referer( 'youzify-ajax-login-nonce', 'security' );

	    // Init Credentials.
	    $creds = array();

	    // Get Credentials.
	    $creds['remember'] = sanitize_text_field( $_POST['remember'] );
	    $creds['user_login'] = sanitize_text_field( $_POST['username'] );
	    $creds['user_password'] = sanitize_text_field( $_POST['password'] );

	    // Nonce is checked, get the POST data and sign user on
	    $user = wp_signon( $creds );

	    // Get Response.
	    if ( is_wp_error( $user ) ) {
	        echo json_encode(
	            array(
	                'loggedin' => false,
	                'error_code' => $user->get_error_code(),
	                'message'  => $user->get_error_message()
	            )
	        );
	    } else {

	    	global $Youzify_Membership;

	    	// Get Redirect URL.
	    	$redirect_url = $Youzify_Membership->login->redirect_after_login( null, null, $user );

	    	// Response.
	        echo json_encode(
	            array(
	                'loggedin' => true,
	                'redirect_url' => apply_filters( 'youzify_ajax_login_redirect_url', $redirect_url, $user ),
	                'message'  =>__( 'Logged in successfully, redirecting...', 'youzify' )
	            )
	        );

	    }

	    die();

	}

	/**
	 * Call Membership Scripts .
	 */
	function scripts() {

        // Profile Ajax Pagination Script
        wp_enqueue_script( 'youzify-membership', YOUZIFY_PREMIUM_ASSETS . 'js/youzify-membership.min.js', array( 'jquery') , YOUZIFY_VERSION, true );

	}

}

new Youzify_Pro_Login();