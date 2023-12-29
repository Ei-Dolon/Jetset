( function( $ ) {

	'use strict';

	$( document ).ready( function() {

		/**
		 * Add New widget.
		 */
		$( document ).on( 'click', '#youzify-add-custom-widget' , function( e ) {

			e.preventDefault();

			// Get Data.
			var	name_selector = $( '.youzify-custom-widget-name span' ),
				widgets_form  = $( '#youzify-custom-widgets-form' ),
				fieldName	  = 'youzify_custom_widgets[youzify_custom_widget_' + youzify_nextCustomWidget + ']',
				widget 	  	  = $.youzify_getAddData( widgets_form, 'youzify_widget' ),
				widget_args   = {
					value	: widget['name'],
					form 	: widgets_form,
					selector: name_selector,
					type	: 'text'
				};

			// Validate widget Data
			if ( ! $.validate_widgets_data( widget_args ) ) {
				return false;
			}

			// Add widget item
			$( '#youzify_custom_widgets' ).prepend(
				'<li class="youzify-custom-widget-item" data-widget-name="youzify_custom_widget_'+ youzify_nextCustomWidget +'">'+
				'<h2 class="youzify-custom-widget-name">'+
				'<i class="youzify-custom-widget-icon '+ widget['icon'] +'"></i>'+
				'<span>' + widget['name'] + '</span>'+
				'</h2>' +
				'<input type="hidden" name="' + fieldName +'[icon]" value="' + widget['icon'] + '" >'+
				'<input type="hidden" name="' + fieldName +'[name]" value="' + widget['name'] + '" >'+
				'<input type="hidden" name="' + fieldName +'[content]" value="' + encodeURIComponent( widget['content'] ) + '" >'+
				'<input type="hidden" name="' + fieldName +'[display_title]" value="' + widget['display_title'] + '" >'+
				'<input type="hidden" name="' + fieldName +'[display_padding]" value="' + widget['display_padding'] + '" >'+
				'<a class="youzify-edit-item youzify-edit-custom-widget"></a>'+
				'<a class="youzify-delete-item youzify-delete-custom-widget"></a>'+
				'</li>'
			);

			// Hide Modal
			$.youzify_HideModal( widgets_form );

			// Increase Social widget Number
			youzify_nextCustomWidget++;

		});

		/**
		 * Edit widget.
		 */
		$( document ).on( 'click', '.youzify-edit-custom-widget' , function( e )	{

			// Get Data.
			var widget_item  = $( this ).closest( '.youzify-custom-widget-item' ),
				widgets_form = $( '#youzify-custom-widgets-form' );

			// Get Form Values
			$.youzify_EditForm( {
				button_id	: 'youzify-update-custom-widget',
				form_title	: Youzify_Custom_Widgets.update_widget,
				form 		: widgets_form,
				item 		: widget_item
			});

		});

		/**
		 * Save widget.
		 */
		$( document ).on( 'click', '#youzify-update-custom-widget' , function( e )	{

			e.preventDefault();

			// Set Up Variables.
			var widget_name 	= '.youzify-custom-widget-name span',
				widgets_form 	= $( '#youzify-custom-widgets-form' ),
				widget_item 	= $.youzify_getItemObject( widgets_form ),
				widget			= $.youzify_getNewData( widgets_form, 'keyToVal' ),
				widgets_args	= {
					old_title 	: widget_item.find( widget_name ).text(),
					value		: widget['name'],
					form 		: widgets_form,
					selector 	: $( widget_name ),
					type		: 'text'
				};

			// Validate widget Data
			if ( ! $.validate_widgets_data( widgets_args ) ) {
				return false;
			}

			// Update Data
			$.youzify_updateFieldsData( widgets_form );

		});

		/**
		 * Validate widget Data.
		 */
		$.validate_widgets_data = function( options ) {

			// O = Options
			var o = $.extend( {}, options );

			// Check if Data is Empty.
			if ( $.isDataEmpty( o.form ) ) {
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

			return true;
		}

		/**
		 * Remove Item.
		 */
		$( document ).on( 'click', '.youzify-delete-custom-widget', function() {

			// Remove item
			$( this ).closest( 'li' ).remove();

			if ( ! $( '.youzify-custom-widget-item' )[0] ) {
				$( '#youzify_custom_widgets' ) .append( '<p class="youzify-no-content youzify-no-custom-widgets">' + Youzify_Custom_Widgets.no_custom_widgets + '</p>' );
			}

		});

	});

})( jQuery );