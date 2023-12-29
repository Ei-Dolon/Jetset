<?php
/**
* Loads all the components related to customizer styles
*
* @since Kortez Travel 1.0.0
*/

function kortez_travel_default_styles(){

	// Begin Style
	$css = '<style>';

	# Site Title
	#Site Title Height
	$logo_width = get_theme_mod( 'logo_width', 270 );
	$css .= '
		.site-header .site-branding > a {
			max-width: '. esc_attr( $logo_width ) .'px;
			overflow: hidden;
			display: inline-block;
		}
	';
	
	# Colors
	$site_body_text_color = get_theme_mod( 'site_body_text_color', '#333333' );
	$site_heading_text_color = get_theme_mod( 'site_heading_text_color', '#030303' );
	$css .= '
		/* Site body Text */
		body, html {
			color: '. esc_attr( $site_body_text_color ) .';
		}
		/* Heading Text */
		h1, h2, h3, h4, h5, h6, .product-title {
			color: '. esc_attr( $site_heading_text_color ) .';
		}
	';

	# Header Image / Slider
	#Header Image Height
	$header_image_height = get_theme_mod( 'header_image_height', 80 );
	$css .= '
		@media only screen and (min-width: 992px) {
			.site-header:not(.sticky-header) .header-image-wrap {
				height: '. esc_attr( $header_image_height ) .'px;
				width: 100%;
				position: relative;
			}
		}
	';

	# Preloader logo width
	$preloader_custom_image_width = get_theme_mod( 'preloader_custom_image_width', 40 );
	$css .= '
		.preloader-content {
			max-width: '. esc_attr( $preloader_custom_image_width ) .'px;
			overflow: hidden;
			display: inline-block;
		}
	';

	// woocommerce option styles

	// woocommerce product card styles
	$product_card_style 		= get_theme_mod( 'woocommerce_product_card_style', 'card_style_one' );
	// Product image and card radius
	$shop_product_image_radius 	= get_theme_mod( 'shop_product_image_radius', 0 );
	$shop_product_card_radius 	= get_theme_mod( 'shop_product_card_radius', 0 );
	if( $product_card_style == 'card_style_one' ){
		$css .= '
			.woocommerce .products li.product .woo-product-image img {
				border-radius: '. esc_attr( $shop_product_image_radius ) .'px;
			}
		';
	}elseif( $product_card_style == 'card_style_two' ){
		$css .= '
			.woocommerce .product .product-inner {
				border: 1px solid #e6e6e6;
				padding: 15px;
			}
			.woocommerce .product .product-inner {
				border-radius: '. esc_attr( $shop_product_card_radius ) .'px;
				overflow: hidden
			}
			.woocommerce .products li.product .woo-product-image img {
				border-radius: '. esc_attr( $shop_product_image_radius ) .'px;
			}
		';
	}elseif( $product_card_style == 'card_style_three' ){
		$css .= '
			.woocommerce .product .product-inner {
				border: 1px solid #e6e6e6;
			}
			.woocommerce .product .product-inner .product-inner-contents {
				padding: 0 20px 20px;
			}
			.woocommerce .product .product-inner {
				border-radius: '. esc_attr( $shop_product_card_radius ) .'px;
				overflow: hidden;
			}
		';
	}

	// Add to cart Colors
	$add_to_cart_button 	= get_theme_mod( 'woocommerce_add_to_cart_button', 'cart_button_two' );
	$add_to_cart_bg_color 	= get_theme_mod( 'add_to_cart_bg_color', '#333333' );
	$add_to_cart_text_color = get_theme_mod( 'add_to_cart_text_color', '#ffffff' );
	
	if( $add_to_cart_button == 'cart_button_three' ){
		$add_to_cart_text_color = get_theme_mod( 'add_to_cart_black_text_color', '#333333' );
	}elseif( $add_to_cart_button == 'cart_button_four' ){
		$add_to_cart_bg_color 	= get_theme_mod( 'add_to_cart_white_bg_color', '#ffffff' );
		$add_to_cart_text_color = get_theme_mod( 'cart_four_black_text_color', '#333333' );
	}
	$css .= '
		.woocommerce .button-cart_button_two a.button {
			background-color: '. esc_attr( $add_to_cart_bg_color ) .';
			color: '. esc_attr( $add_to_cart_text_color ) .';
		}
		.woocommerce .button-cart_button_three > a.button {
			border-bottom-color: '. esc_attr( $add_to_cart_text_color ) .';
			color: '. esc_attr( $add_to_cart_text_color ) .';
		}
		.woocommerce .button-cart_button_four > a.button {
			background-color: '. esc_attr( $add_to_cart_bg_color ) .';
			color: '. esc_attr( $add_to_cart_text_color ) .';
		}
	';

	// Add to cart button radius
	$add_cart_button_radius = get_theme_mod( 'add_cart_button_radius', 0 );
	$css .= '
		.woocommerce .button-cart_button_four > a.button {
			border-radius: '. esc_attr( $add_cart_button_radius ) .'px;
		}
		.woocommerce .button-cart_button_two a.button {
			border-radius: '. esc_attr( $add_cart_button_radius ) .'px;
		}
	';
	// Add to cart layout four diagonal spacing
	$cart_four_diagonal_spacing = get_theme_mod( 'cart_four_diagonal_spacing', 10 );
	$css .= '
		.woocommerce ul.products li.product .button-cart_button_four {
		    left: '. esc_attr( $cart_four_diagonal_spacing ) .'px;
		    bottom: '. esc_attr( $cart_four_diagonal_spacing ) .'px;
		}
	';

	// Sale Tag Layout
	$sale_tag_layout = get_theme_mod( 'woocommerce_sale_tag_layout', 'sale_tag_layout_one' );
	// Sale Button diagonal spacing
	$sale_button_diagonal_spacing = get_theme_mod( 'sale_button_diagonal_spacing', 8 );
	
	$icon_group_layout 				= get_theme_mod( 'icon_group_layout', 'group_layout_one' );

	if( $icon_group_layout != 'group_layout_four' ){
		if( $sale_tag_layout == 'sale_tag_layout_one' ){
			$css .= '
				.woocommerce ul.products li.product .onsale {
					top: '. esc_attr( $sale_button_diagonal_spacing ) .'px;
					right: '. esc_attr( $sale_button_diagonal_spacing ) .'px;
				}
			';
		}elseif( $sale_tag_layout == 'sale_tag_layout_two' ){
			$css .= '
				.woocommerce ul.products li.product .onsale {
					top: '. esc_attr( $sale_button_diagonal_spacing ) .'px;
					left: '. esc_attr( $sale_button_diagonal_spacing ) .'px;
					right: auto;
				}
			';
		}
	}else{
		$css .= '
			.woocommerce ul.products li.product .onsale {
				top: '. esc_attr( $sale_button_diagonal_spacing ) .'px;
				left: '. esc_attr( $sale_button_diagonal_spacing ) .'px;
				right: auto;
			}
		';
	}

	// Sale Tag Colors
	$sale_tag_bg_color = get_theme_mod( 'sale_tag_bg_color', '#DB5362' );
	$sale_tag_text_color = get_theme_mod( 'sale_tag_text_color', '#ffffff' );
	$css .= '
		.woocommerce ul.products li.product span.onsale {
			background-color: '. esc_attr( $sale_tag_bg_color ) .';
			color: '. esc_attr( $sale_tag_text_color ) .';
		}
	';

	$sale_button_border_radius = get_theme_mod( 'sale_button_border_radius', 0 );
	$css .= '
		.woocommerce ul.products li.product span.onsale {
			border-radius: '. esc_attr( $sale_button_border_radius ) .'px;
		}
	';

	// Single Products
	$disable_single_product_sku = get_theme_mod( 'disable_single_product_sku', false );
	$disable_single_product_category = get_theme_mod( 'disable_single_product_category', false );
	$disable_single_product_tags = get_theme_mod( 'disable_single_product_tags', false );

	if( $disable_single_product_sku ){
        $css .= '
			.single-product .product_meta .sku_wrapper {
				display: none !important;
			}
		';
    }
    if( $disable_single_product_category ){
        $css .= '
			.single-product .product_meta .posted_in {
				display: none !important;
			}
		';
    }

    if( $disable_single_product_tags ){
        $css .= '
			.single-product .product_meta .tagged_as {
				display: none !important;
			}
		';
    }

    // disable single product border
    if( $disable_single_product_sku && $disable_single_product_category && $disable_single_product_tags ){
    	$css .= '
			body[class*=woocommerce] .product_meta {
				border-top: none;
				padding-top: 0;
			}
		';
    }

    // Icon Group layout
    $icon_group_layout 				= get_theme_mod( 'icon_group_layout', 'group_layout_one' );
    $icon_group_one_border_radius 	= get_theme_mod( 'icon_group_one_border_radius', 100 );
    $icon_group_two_border_radius 	= get_theme_mod( 'icon_group_two_border_radius', 0 );
    $icon_group_three_border_radius = get_theme_mod( 'icon_group_three_border_radius', 0 );
    $icon_group_four_border_radius 	= get_theme_mod( 'icon_group_four_border_radius', 100 );

    // Icon group layout  diagonal spacing
	$icon_group_diagonal_spacing = get_theme_mod( 'icon_group_diagonal_spacing', 10 );

	if( $icon_group_layout == 'group_layout_one' ){
		$css .= '
			body[class*=woocommerce] ul.products li .product-compare-wishlist a {
				opacity: 0;
				z-index: 99;
			}
			body[class*=woocommerce] ul.products li .product-wishlist a {
				top: 50%;
				left: 50%;
				-webkit-transform: translate(-50%, -50%);
				-moz-transform: translate(-50%, -50%);
				-ms-transform: translate(-50%, -50%);
				-o-transform: translate(-50%, -50%);
				transform: translate(-50%, -50%);
			}
			body[class*=woocommerce] ul.products li .product-compare a {
				top: 50%;
				left: 50%;
				-webkit-transform: translate(-60px, -50%);
				-moz-transform: translate(-60px, -50%);
				-ms-transform: translate(-60px, -50%);
				-o-transform: translate(-60px, -50%);
				transform: translate(-60px, -50%);
			}
			body[class*=woocommerce] ul.products li .product-view a {
				top: 50%;
				left: 50%;
				-webkit-transform: translate(25px, -50%);
				-moz-transform: translate(25px, -50%);
				-ms-transform: translate(25px, -50%);
				-o-transform: translate(25px, -50%);
				transform: translate(25px, -50%);
			}
			body[class*=woocommerce] ul.products li .product-compare-wishlist a i {
				background-color: #ffffff;
				border-radius: '. esc_attr( $icon_group_one_border_radius ) .'px;
				line-height: 35px;
				height: 35px;
				text-align: center;
				width: 35px;
			}
			body[class*=woocommerce] ul.products li:hover .product-compare-wishlist a, 
			body[class*=woocommerce] ul.products li:focus .product-compare-wishlist a {
				opacity: 1;
			}
		';
	}elseif( $icon_group_layout == 'group_layout_two' ){
		$css .= '
			body[class*=woocommerce] ul.products li .product-compare-wishlist a {
				opacity: 0;
				z-index: 99;
			}
			body[class*=woocommerce] ul.products li .product-wishlist a {
				top: 50%;
				left: 50%;
				-webkit-transform: translate(-50%, -50%);
				-moz-transform: translate(-50%, -50%);
				-ms-transform: translate(-50%, -50%);
				-o-transform: translate(-50%, -50%);
				transform: translate(-50%, -50%);
			}
			body[class*=woocommerce] ul.products li .product-compare a {
				top: 50%;
				left: 50%;
				-webkit-transform: translate(-45px, -50%);
				-moz-transform: translate(-45px, -50%);
				-ms-transform: translate(-45px, -50%);
				-o-transform: translate(-45px, -50%);
				transform: translate(-45px, -50%);
			}
			body[class*=woocommerce] ul.products li .product-view a {
				top: 50%;
				left: 50%;
				-webkit-transform: translate(11px, -50%);
				-moz-transform: translate(11px, -50%);
				-ms-transform: translate(11px, -50%);
				-o-transform: translate(11px, -50%);
				transform: translate(11px, -50%);
			}
			body[class*=woocommerce] ul.products li .product-compare-wishlist a i {
				background-color: #ffffff;
				line-height: 35px;
				height: 35px;
				text-align: center;
				padding: 0 5px;
			}
			body[class*=woocommerce] ul.products li .product-compare-wishlist > div:first-child a i {
				padding-left: 16px;
				border-top-left-radius: '. esc_attr( $icon_group_two_border_radius ) .'px;
				border-bottom-left-radius: '. esc_attr( $icon_group_two_border_radius ) .'px;
			}
			body[class*=woocommerce] ul.products li .product-compare-wishlist > div:last-child a i {
				padding-right: 16px;
				border-top-right-radius: '. esc_attr( $icon_group_two_border_radius ) .'px;
				border-bottom-right-radius: '. esc_attr( $icon_group_two_border_radius ) .'px;
			}
			body[class*=woocommerce] ul.products li:hover .product-compare-wishlist a, 
			body[class*=woocommerce] ul.products li:focus .product-compare-wishlist a {
				opacity: 1;
			}
			body[class*=woocommerce] ul.products li .product-compare-wishlist a i {
				font-size: 13px;
			}
		';
	}elseif( $icon_group_layout == 'group_layout_three' ){
		$css .= '
			body[class*=woocommerce] ul.products li .product-compare-wishlist a {
				opacity: 0;
				z-index: 99;
			}
			body[class*=woocommerce] ul.products li .group_layout_three .product-view a {
				bottom: '. esc_attr( $icon_group_diagonal_spacing ) .'px;
				right: '. esc_attr( $icon_group_diagonal_spacing ) .'px;
			}
			body[class*=woocommerce] ul.products li .group_layout_three .product-wishlist a {
				bottom: '. esc_attr( $icon_group_diagonal_spacing ) .'px;
				right: '. esc_attr( $icon_group_diagonal_spacing + 34 ) .'px;
			}
			body[class*=woocommerce] ul.products li .group_layout_three .product-compare a {
				bottom: '. esc_attr( $icon_group_diagonal_spacing ) .'px;
				right: '. esc_attr( $icon_group_diagonal_spacing + 57 ) .'px;
			}
			body[class*=woocommerce] ul.products li .product-compare-wishlist a i {
				background-color: #ffffff;
				line-height: 35px;
				height: 35px;
				text-align: center;
				padding: 0 5px;
			}
			body[class*=woocommerce] ul.products li .product-compare-wishlist > div:first-child a i {
				padding-left: 16px;
				border-top-left-radius: '. esc_attr( $icon_group_three_border_radius ) .'px;
				border-bottom-left-radius: '. esc_attr( $icon_group_three_border_radius ) .'px;
			}
			body[class*=woocommerce] ul.products li .product-compare-wishlist > div:last-child a i {
				padding-right: 16px;
				border-top-right-radius: '. esc_attr( $icon_group_three_border_radius ) .'px;
				border-bottom-right-radius: '. esc_attr( $icon_group_three_border_radius ) .'px;
			}
			body[class*=woocommerce] ul.products li:hover .product-compare-wishlist a, 
			body[class*=woocommerce] ul.products li:focus .product-compare-wishlist a {
				opacity: 1;
			}
			body[class*=woocommerce] ul.products li .product-compare-wishlist a i {
				font-size: 13px;
			}
		';
	}elseif( $icon_group_layout == 'group_layout_four' ){
		$css .= '
			body[class*=woocommerce] ul.products li .product-compare-wishlist a {
				opacity: 1;
				z-index: 99;
			}
			body[class*=woocommerce] ul.products li .group_layout_four .product-wishlist a {
				top: '. esc_attr( $icon_group_diagonal_spacing ) .'px;
				right: '. esc_attr( $icon_group_diagonal_spacing ) .'px;
			}
			body[class*=woocommerce] ul.products li .group_layout_four .product-compare a {
				top: '. esc_attr( $icon_group_diagonal_spacing + 45 ) .'px;
				right: '. esc_attr( $icon_group_diagonal_spacing ) .'px;
			}
			body[class*=woocommerce] ul.products li .group_layout_four .product-view a {
				top: '. esc_attr( $icon_group_diagonal_spacing + 90 ) .'px;
				right: '. esc_attr( $icon_group_diagonal_spacing ) .'px;
			}
			body[class*=woocommerce] ul.products li .product-compare-wishlist a i {
				background-color: #ffffff;
				border-radius: '. esc_attr( $icon_group_four_border_radius ) .'px;
				line-height: 35px;
				height: 35px;
				text-align: center;
				width: 35px;
			}
			body[class*=woocommerce] ul.products li .product-compare a,
			body[class*=woocommerce] ul.products li .product-view a {
				opacity: 0;
			}
			body[class*=woocommerce] ul.products li .product-compare a {
				-webkit-transition: all 0.4s ease-out 0s;
				-moz-transition: all 0.4s ease-out 0s;
				-ms-transition: all 0.4s ease-out 0s;
				-o-transition: all 0.4s ease-out 0s;
				transition: all 0.4s ease-out 0s;
			}
			body[class*=woocommerce] ul.products li .product-view a {
				-webkit-transition: all 0.4s ease-out 0.2s;
				-moz-transition: all 0.4s ease-out 0.2s;
				-ms-transition: all 0.4s ease-out 0.2s;
				-o-transition: all 0.4s ease-out 0.2s;
				transition: all 0.4s ease-out 0.2s;
			}
			body[class*=woocommerce] ul.products li:hover .product-compare a, 
			body[class*=woocommerce] ul.products li:focus .product-compare a, 
			body[class*=woocommerce] ul.products li:active .product-compare a,
			body[class*=woocommerce] ul.products li:hover .product-view a, 
			body[class*=woocommerce] ul.products li:focus .product-view a, 
			body[class*=woocommerce] ul.products li:active .product-view a {
				opacity: 1;
			}
			body[class*=woocommerce] ul.products li:hover .product-compare-wishlist a, 
			body[class*=woocommerce] ul.products li:focus .product-compare-wishlist a {
				opacity: 1;
			}
			.woocommerce .product-wishlist .feedback, .woocommerce .yith-wcwl-add-to-wishlist .feedback {
				margin-left: 3.5%;
    			margin-right: 25%;
			}
			.woocommerce .woocommerce ul.products li.product .onsale {
				right: auto;
				left: 8px;
			}
			.woocommerce .info-tooltip {
				top: 50%;
				left: 50%;
				-webkit-transform: translate(-50%, -50%);
				-moz-transform: translate(-50%, -50%);
				-ms-transform: translate(-50%, -50%);
				-o-transform: translate(-50%, -50%);
				transform: translate(-50%, -50%);
				-webkit-transition: right 0.4s;
				-moz-transition: right 0.4s;
				-ms-transition: right 0.4s;
				-o-transition: right 0.4s;
				transition: right 0.4s;
			}
			.woocommerce .product-compare-wishlist a:hover .info-tooltip {
				top: 50%;
				left: auto;
				-webkit-transform: translate(-10px, -50%);
				-moz-transform: translate(-10px, -50%);
				-ms-transform: translate(-10px, -50%);
				-o-transform: translate(-10px, -50%);
				transform: translate(-10px, -50%);
				right: 100%;
			}
		';
	}

	/* Icon group colors */
	$icon_group_bg_color 	= get_theme_mod( 'icon_group_bg_color', '#ffffff' );
	$icon_group_text_color  = get_theme_mod( 'icon_group_text_color', '#383838' );
	$css .= '
		body[class*=woocommerce] ul.products li .product-compare-wishlist a i {
			background-color: '. esc_attr( $icon_group_bg_color ) .';
		}
		body[class*=woocommerce] ul.products li .product-compare-wishlist a i {
			color: '. esc_attr( $icon_group_text_color ) .';
		}
	';

	// End Style
	$css .= '</style>';
	?>
	<?php

	// return generated & compressed CSS
	echo str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $css); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}
add_action( 'wp_head', 'kortez_travel_default_styles' );