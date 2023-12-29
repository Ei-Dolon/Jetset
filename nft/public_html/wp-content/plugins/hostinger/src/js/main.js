import './autocomplete_steps';
import './videos'

( function ( $ ) {
	$( document ).on( 'ready', function () {
		const openClass = 'open';
		const completedClass = 'completed';
		const homeTab = $( '.hostinger.hsr-onboarding' );
		const learnTab = $( '.hsr-learn-more' );
		const aiAssistantTab = $( '.hsr-ai-assistant-tab' );
		aiAssistantTab.hide();
		learnTab.hide();
		let selectedTab = 'Home';
		const stepsTitle = $( '.hsr-onboarding-step--title' )
		const gotItBtn = $( '.hsr-got-it-btn' );
		const publishBtn = $( '.hsr-publish-btn' );
		const closeBtn = $( '.hsr-close-btn' );
		const navigationItem = $( '.hsr-list__item' );
		const knowledgeCard = $( '#card-knowledge' );
		const helpCard = $( '#card-help' );

		gotItBtn.on( 'click', function ( e ) {
			e.preventDefault();
			const element = $( this );
			const step = $( this ).data( 'step' );
			let remaining_tasks = $( '.hsr-onboarding-steps' ).data( 'remaining-tasks' );

			$.ajax( {
				type: 'post',
				dataType: 'json',
				url: ajaxurl,
				data: {
					action: 'hostinger_complete_onboarding_step',
					step: step,
				},
				success: function () {
					element.closest( '.hsr-onboarding-step--content' ).slideUp()
					element.parents( '.hsr-onboarding-step' )
						.find( '.hsr-onboarding-step--status' )
						.addClass( completedClass )

					if ( remaining_tasks > 0 ) {
						remaining_tasks = remaining_tasks - 1;
						$( '.hsr-onboarding-steps' ).data( 'remaining-tasks', remaining_tasks )

						if ( remaining_tasks === 0 ) {
							$( '.hsr-publish-btn' ).addClass( completedClass );
						}

					}
				},
			} )
		} )

		stepsTitle.on( 'click', function () {
			$( this ).find( '.hsr-onboarding-step--expand' ).toggleClass( openClass );
			$( this ).parent().find( '.hsr-onboarding-step--content' ).slideToggle( 200 );
		} )

		publishBtn.on( 'click', function ( e ) {
			e.preventDefault();
			$( '.hsr-modal' ).addClass( 'open' );
			$( 'body' ).addClass( 'modal-open' );
			$.ajax( {
				type: 'post',
				dataType: 'json',
				url: ajaxurl,
				data: {
					action: 'hostinger_publish_website',
					maintenance: 0,
				},
				success: function ( result ) {
					const previewBtn = $( '.hsr-preview-btn' );
					$( '.hsr-circular' ).addClass( 'hsr-hide' )
					$( '.hsr-success-circular' ).addClass( 'hsr-show' )
					$( '.hsr-publish-modal--footer' ).addClass( 'show' )
					$( '.hsr-publish-modal--body h3' ).text( result.data.title );
					$( '.hsr-publish-modal--body__description' ).text( result.data.description );
					$( '.hsr-publish-btn' ).addClass( 'hsr-preview' )
					previewBtn.addClass( 'hsr-preview' )
					previewBtn.text( result.data.content.btn.text )
					$( '.hsr-onboarding__title' ).text( result.data.content.title );
					$( '.hsr-onboarding__description' ).text( result.data.content.description );
				},
			} )
		} )

		closeBtn.on( 'click', function () {
			$( '.hsr-modal' ).removeClass( 'open' );
			$( 'body' ).removeClass( 'modal-open' );
		} )
		navigationItem.click( function () {
			var clickedItem = $( this );
			$( '.hsr-list__item' ).removeClass( 'hsr-active' );
			clickedItem.addClass( 'hsr-active' );
			selectedTab = clickedItem.data( 'name' );
			if ( selectedTab === 'home' ) {
				homeTab.show();
				aiAssistantTab.hide();
				learnTab.hide();
			} else if ( selectedTab === 'learn' ) {
				homeTab.hide();
				aiAssistantTab.hide();
				learnTab.show();
			} else if ( selectedTab === 'ai-assistant' ) {
				homeTab.hide();
				learnTab.hide();
				aiAssistantTab.show();
			}

			add_admin_menu_class();
		} );

		if ( window.location.hash === "#ai-assistant" ) {
			$( '.hsr-list__item' ).removeClass( 'hsr-active' );
			$( '.hsr-list__item.hts-ai-assistant-tab' ).addClass( 'hsr-active' );
			homeTab.hide();
			learnTab.hide();
			aiAssistantTab.show();
		}

		if ( window.location.hash === "#home" ) {
			$( '.hsr-list__item' ).removeClass( 'hsr-active' );
			$( '.hsr-list__item.hts-home-tab' ).addClass( 'hsr-active' );
			homeTab.show();
			aiAssistantTab.hide();
			learnTab.hide();
		}

		if ( window.location.hash === "#learn" ) {
			$( '.hsr-list__item' ).removeClass( 'hsr-active' );
			$( '.hsr-list__item.hts-learn-tab' ).addClass( 'hsr-active' );
			homeTab.hide();
			aiAssistantTab.hide();
			learnTab.show();
		}

		helpCard.click( function () {
			window.open( 'https://hostinger.com/cpanel-login?r=jump-to/new-panel/section/help', '_blank' );
		} );
		knowledgeCard.click( function () {
			window.open( 'https://support.hostinger.com/en/?q=WordPress', '_blank' );
		} );

		document.querySelectorAll( '.hsr-playlist-item' ).forEach( function ( item ) {
			const firstItem = document.querySelector( '.hsr-playlist-item:first-child' );
			firstItem.classList.add( 'hsr-active-video' );
			firstItem.querySelector( '.hsr-playlist-item-arrow' ).style.visibility = 'visible';
			item.addEventListener( 'click', function () {
				document.querySelectorAll( '.hsr-playlist-item.hsr-active-video' ).forEach( function ( selectedItem ) {
					selectedItem.classList.remove( 'hsr-active-video' );
					selectedItem.querySelector( '.hsr-playlist-item-arrow' ).style.visibility = 'hidden';
				} );
				this.classList.add( 'hsr-active-video' );
				this.querySelector( '.hsr-playlist-item-arrow' ).style.visibility = 'visible';
			} );
		} );

		function add_admin_menu_class () {
			const tabSelectors = [
				'.hsr-list__item.hts-home-tab',
				'.hsr-list__item.hts-learn-tab',
				'.hsr-list__item.hts-ai-assistant-tab',
				'.hsr-list__item.hts-ai-website-tab'
			];

			const hostingerSubMenu = document.querySelectorAll( '#toplevel_page_hostinger .wp-submenu li' );
			if ( hostingerSubMenu ) {
				hostingerSubMenu.forEach( item => {
					item.classList.remove( 'current' );
				} );

				tabSelectors.forEach( ( selector, index ) => {
					const tab = document.querySelector( selector );
					if ( tab && tab.classList.contains( 'hsr-active' ) ) {
						if ( typeof hostingerSubMenu[ index + 1 ] !== "undefined" ) {
							hostingerSubMenu[ index + 1 ].classList.add( 'current' );
						}
					}
				} );
			}

		}

		add_admin_menu_class();

		// Copy nameservers to clipboard
		$(document).ready(function() {
			$('.hts-nameservers svg').click(function() {
				let textToCopy = $(this).closest('div').find('b').text();
				copyTextToClipboard(textToCopy);
			});
		});

		function copyTextToClipboard(text) {
			let textArea = document.createElement('textarea');
			textArea.value = text;
			document.body.appendChild(textArea);
			textArea.select();
			document.execCommand('copy');
			document.body.removeChild(textArea);
		}

	} );

} )( jQuery );
