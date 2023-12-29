( function( $ ) {

	'use strict';

	$( document ).ready( function() {

		/**
		 * Add New tab.
		 */
		$( document ).on( 'click', '#youzify-add-custom-tab' , function( e ) {

			e.preventDefault();

			// Get Data.
			var	name_selector = $( '.youzify-custom-tab-name span' ),
				tabs_form  = $( this ).closest( 'form' ).find( '#youzify-custom-tabs-form' ),
				fieldName	  = 'youzify_custom_tabs[youzify_custom_tab_' + youzify_nextTab + ']',
				tab 	  	  = $.youzify_getAddData( tabs_form, 'youzify_tab' ),
				tab_args   = {
					slug	: tab['slug'],
					value	: tab['title'],
					form 	: tabs_form,
					selector: name_selector,
					type	: 'text',
					tab_link  : tab['link'],
					tab_type  : tab['type'],
					tab_title : tab['title'],
					tab_content : tab['content'],
				};

			// Validate Tab Data
			if ( ! $.validate_tabs_data( tab_args ) ) {
				return false;
			}

			// Add widget item
			$( '#youzify_custom_tabs' ).prepend(
				'<li class="youzify-custom-tab-item" data-tab-name="youzify_custom_tab_'+ youzify_nextTab +'">'+
				'<h2 class="youzify-custom-tab-name">'+
				'<i class="youzify-custom-tab-icon fas fa-angle-right"></i>'+
				'<span>' + tab['title'] + '</span>'+
				'</h2>' +
				'<input type="hidden" name="' + fieldName +'[slug]" value="' + tab['slug'] + '" >'+
				'<input type="hidden" name="' + fieldName +'[link]" value="' + tab['link'] + '" >'+
				'<input type="hidden" name="' + fieldName +'[type]" value="' + tab['type'] + '" >'+
				'<input type="hidden" name="' + fieldName +'[title]" value="' + tab['title'] + '" >'+
				'<input type="hidden" name="' + fieldName +'[content]" value="' + encodeURIComponent( tab['content'] ) + '" >'+
				'<input type="hidden" name="' + fieldName +'[display_sidebar]" value="' + tab['display_sidebar'] + '" >'+
				'<input type="hidden" name="' + fieldName +'[display_nonloggedin]" value="' + tab['display_nonloggedin'] + '" >'+
				'<a class="youzify-edit-item youzify-edit-custom-tab"></a>'+
				'<a class="youzify-delete-item youzify-delete-custom-tab"></a>'+
				'</li>'
			);

			// Hide Modal
			$.youzify_HideModal( tabs_form );

			// Increase ID Number
			youzify_nextTab++;

		});

		/**
		 * Edit Tab.
		 */
		$( document ).on( 'click', '.youzify-edit-custom-tab' , function( e )	{

			// Get Data.
			var tab_item  = $( this ).closest( '.youzify-custom-tab-item' ),
				tabs_form = $( '#youzify-custom-tabs-form' );

			// Get Form Values
			$.youzify_EditForm( {
				button_id	: 'youzify-update-custom-tab',
				form_title	: Youzify_Custom_Tabs.update_tab,
				form 		: tabs_form,
				item 		: tab_item
			});

		});

		/**
		 * Save Tab.
		 */
		$( document ).on( 'click', '#youzify-update-custom-tab' , function( e )	{

			e.preventDefault();

			// Set Up Variables.
			var tab_name = '.youzify-custom-tab-name span',
				tabs_form 	= $( '#youzify-custom-tabs-form' ),
				tab_item 	= $.youzify_getItemObject( tabs_form ),
				tab			= $.youzify_getNewData( tabs_form, 'keyToVal' ),
				tabs_args	= {
					old_title 	: tab_item.find( tab_name ).text(),
					slug		: tab['slug'],
					value		: tab['title'],
					form 		: tabs_form,
					selector 	: $( tab_name ),
					type		: 'text',
					tab_link    : tab['link'],
					tab_type    : tab['type'],
					tab_title   : tab['title'],
					tab_content : tab['content'],
				};

			// Validate Tab Data.
			if ( ! $.validate_tabs_data( tabs_args ) ) {
				return false;
			}

			// Update Data.
			$.youzify_updateFieldsData( tabs_form );

		});

		/**
		 * Validate widget Data.
		 */
		$.validate_tabs_data = function( options ) {

			// O = Options
			var o = $.extend( {}, options );

			if ( o.tab_title == null || $.trim( o.tab_title ) == '') {
				// Show Error Message
                $.ShowPanelMessage( {
                    msg  : Youzify_Custom_Tabs.tab_title_empty,
                    type : 'error'
                } );
                return false;
			}

			// Check if widget Exist or not
			var nameAlreadyeExist = $.youzify_isAlreadyExist( {
				old_title 	: o.old_title,
				selector 	: o.selector,
				value		: o.value,
				type		: 'text'
			} );

			if ( nameAlreadyeExist ) {
				// Show Error Message
                $.ShowPanelMessage( {
                    msg  : Youzify.name_exist,
                    type : 'error'
                });
                return false;
			}

			// Validate Banner Process.
			if ( o.tab_type == 'link' ) {

				if ( o.tab_link == null || $.trim( o.tab_link ) == '' ) {
					// Show Error Message
					$.ShowPanelMessage( {
						msg  : Youzify_Custom_Tabs.tab_url_empty,
						type : 'error'
					} );
					return false;
				}

			} else if ( o.tab_type == 'shortcode' ) {
				if ( o.tab_content == null || $.trim( o.tab_content ) == '' ) {
					// Show Error Message
					$.ShowPanelMessage( {
						msg  : Youzify_Custom_Tabs.tab_code_empty,
						type : 'error'
					} );
					return false;
				}
			}


			return true;
		}

		/**
		 * Remove Item.
		 */
		$( document ).on( 'click', '.youzify-delete-custom-tab', function() {

			// Remove item
			$( this ).closest( 'li' ).remove();

			if ( ! $( '.youzify-custom-tab-item' )[0] ) {
				$( '#youzify_custom_tabs' )
				.append( '<p class="youzify-no-content youzify-no-custom-tabs">' + Youzify_Custom_Tabs.no_custom_tabs + '</p>' );
			}

		});

		/**
		 * Get Fields by Tab type .
		 */
		$( document ).on( 'change', 'input[name=youzify_tab_type]', function() {

			var code = '.youzify-custom-tabs-shortcode-items',
				link = '.youzify-custom-tabs-link-item',
				form = $( this ).closest( '.youzify-custom-tabs-form' );

	        if ( this.value == 'shortcode' ) {
	        	form.find( link ).fadeToggle( 400, function() {
	        		form.find( code ).fadeToggle( 400);
	        	} );
	        } else {
	        	form.find( code ).fadeToggle( 400, function() {
	        		form.find( link ).fadeToggle( 400);
	        	} );
        	}

    	});
	});

})( jQuery );