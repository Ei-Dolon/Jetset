( function( $ ) {

	'use strict';

	$( document ).ready( function() {

	    // Ajax Login.
	    $( '.youzify-membership-login-form' ).on( 'submit', function( e ) {

	    	// Add Authenticating Class.
	    	$( this ).addClass( 'youzify-authenticating' );

	    	// Init Vars.
	    	var youzify_login_form = $( this ), youzify_btn_txt, youzify_btn_icon, youzify_submit_btn;

	    	// Get Current Button Text & Icon.
	    	youzify_submit_btn = $( this ).find( 'button[type="submit"]' );
	    	youzify_btn_txt  = youzify_submit_btn.find( '.youzify-membership-button-title' ).text();
	    	youzify_btn_icon = youzify_submit_btn.find( '.youzify-membership-button-icon i' ).attr( 'class' );

	    	// Display "Authenticating..." Messages.
	    	youzify_submit_btn.find( '.youzify-membership-button-title' ).text( Youzify.authenticating );
	    	youzify_submit_btn.find( '.youzify-membership-button-icon i' ).attr( 'class', 'fas fa-spinner fa-spin' );

	    	// Get Current Button Icon
	    	var youzify_login_data = {
                'action': 'youzify_ajax_login',
                'username': $( this ).find( 'input[name="log"]' ).val(),
                'password': $( this ).find( 'input[name="pwd"]' ).val(),
                'remember': $( this ).find( 'input[name="rememberme"]' ).val(),
                'redirect_to': $( this ).find( 'input[name="youzify_redirect_to"]' ).val(),
                'security': $( this ).find( 'input[name="youzify_ajax_login_nonce"]' ).val(),
	        };

	        $.ajax({
	            type: 'POST',
	            dataType: 'json',
	            url: ajaxurl,
	            data: youzify_login_data,
	            success: function( response ) {

	                if ( response.loggedin == true ) {
	                	// Change Login Button Title.
	    				youzify_submit_btn.find( '.youzify-membership-button-title' ).text( response.message );
	    				youzify_submit_btn.find( '.youzify-membership-button-icon i' ).attr( 'class', 'fas fa-check' );
		         		// Redirect.
	                    document.location.href = response.redirect_url;
	                } else {

		            	// Add Authenticating Class.
		    			youzify_login_form.removeClass( 'youzify-authenticating' );

	                	// Clear Inputs Depending on the errors ..
	                	if ( response.error_code && 'incorrect_password' == response.error_code ) {
	                		// Clear Password Field.
	                		youzify_login_form.find( 'input[name="pwd"]' ).val( '' );
	                	} else {
	                		// If Username invalid Clear Inputs.
	                		youzify_login_form.find( 'input[name="log"],input[name="pwd"]' ).val( '' );
	                	}
	                	// Change Login Button Title & Icon.
	    				youzify_submit_btn.find( '.youzify-membership-button-title' ).text( youzify_btn_txt );
	    				youzify_submit_btn.find( '.youzify-membership-button-icon i' ).attr( 'class', youzify_btn_icon );
		            	// Show Error Message.
		            	$.youzify_DialogMsg( 'error', response.message );
	                }
	            }
        	});

	        e.preventDefault();

	    });

	});

})( jQuery );