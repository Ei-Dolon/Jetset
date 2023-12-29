( function ( $ ) {
	$( document ).on( 'ready', function () {
		const nonce = document.getElementById( 'menu_actions_nonce' ).value;
			$('#adminmenu #toplevel_page_hostinger li a').click(function () {
				let action = $(this).attr("href").split('#').pop();

				$.ajax( {
					url: ajaxurl,
					method: 'POST',
					data: {
						action: 'hostinger_menu_action',
						nonce: nonce,
						location: 'side_bar',
						event_action: action
					},
					success: function ( data ) {
					},
					error: function ( xhr, status, error ) {
						console.log( 'AJAX request failed: ' + error );
					}
				} );
			});

		$('.hsr-wrapper__list .hsr-list__item').click(function () {
			let action = $(this).data('name');

			$.ajax( {
				url: ajaxurl,
				method: 'POST',
				data: {
					action: 'hostinger_menu_action',
					nonce: nonce,
					location: 'home_page',
					event_action: action
				},
				success: function ( data ) {
				},
				error: function ( xhr, status, error ) {
					console.log( 'AJAX request failed: ' + error );
				}
			} );
		});

	} );

} )( jQuery );
