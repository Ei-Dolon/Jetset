import $ from 'jquery'

if ($) {
	// https://woocommerce.com/document/composite-products/composite-products-js-api-reference/#using-the-api
	$('.composite_data').on('wc-composite-initializing', (event, composite) => {
		composite.actions.add_action('component_selection_changed', () => {
			setTimeout(() => {
				ctEvents.trigger('blocksy:frontend:init')
			}, 1000)
		})
	})
	;[
		'updated_checkout',
		'wc_fragments_refreshed',
		'found_variation',
		'reset_data',
		'wc_fragments_loaded',
	].map((event) => {
		$(document.body).on(event, () =>
			ctEvents.trigger('blocksy:frontend:init')
		)
	})

	$('.wc-product-table').on('draw.wcpt', () => {
		ctEvents.trigger('blocksy:frontend:init')
	})

	document.addEventListener('wpfAjaxSuccess', (e) => {
		ctEvents.trigger('blocksy:frontend:init')
	})

	document.addEventListener('facetwp-loaded', () => {
		ctEvents.trigger('blocksy:frontend:init')
	})

	$(() => {
		setTimeout(() => {
			ctEvents.trigger('blocksy:frontend:init')
		}, 100)
	})
	;[
		'berocket_ajax_filtering_end',
		'preload',
		'jet-filter-content-rendered',
		'yith_infs_added_elem',
		'yith-wcan-ajax-filtered',
		'sf:ajaxfinish',
		'ddwcpoRenderVariation',
	].map((event) => {
		$(document).on(event, () => {
			setTimeout(() => {
				ctEvents.trigger('blocksy:frontend:init')
			}, 100)
		})
	})
}
