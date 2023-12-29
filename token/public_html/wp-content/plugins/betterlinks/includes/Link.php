<?php
namespace BetterLinks;

use BetterLinks\Link\Utils;

class Link extends Utils {

	public function __construct() {
		if ( ! is_admin() && isset( $_SERVER['REQUEST_METHOD'] ) && 'GET' === $_SERVER['REQUEST_METHOD'] ) {
			add_action( 'init', array( $this, 'run_redirect' ), 0 );
		}
	}

	/**
	 * Redirects short links to the destination url
	 */
	public function run_redirect() {
		$request_uri = isset( $_SERVER['REQUEST_URI'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
		$request_uri = stripslashes( rawurldecode( $request_uri ) );
		$request_uri = substr( $request_uri, strlen( wp_parse_url( site_url( '/' ), PHP_URL_PATH ) ) );
		$param       = explode( '?', $request_uri, 2 );
		$data        = $this->get_slug_raw( rtrim( current( $param ), '/' ) );

		if ( empty( $data['target_url'] ) || ! apply_filters( 'betterlinks/pre_before_redirect', $data ) ) { //phpcs:ignore. 
			if ( apply_filters( 'betterlinks/is_password_protected_redirect_compatible', false ) ) { // phpcs:ignore.
				$referer           = isset( $_SERVER['HTTP_REFERER'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_REFERER'] ) ) : null;
				$request_uri       = site_url( '/' ) . $request_uri;
				$short_url         = $this->get_protected_self_url_short_link( $request_uri );
				$referer_short_url = $this->referer_short_url( $referer );

				// if request are coming from password protected form and password is okk.
				if ( $referer_short_url === $short_url ) {
					return false;
				}
				$data = $this->get_slug_raw( $short_url );

				$password_protection_status = \BetterLinksPro\Link::get_password_protection_status();
				$id                         = isset( $data['ID'] ) ? $data['ID'] : null;
				if ( empty( $id ) ) {
					return false;
				}
				$is_active_cookie = $this->passsword_cookie_enabled( $password_protection_status['remember_password_cookies'], $id );
				if ( $is_active_cookie ) {
					return false;
				}
			}

			if ( empty( $data['target_url'] ) || ! apply_filters( 'betterlinks/pre_before_redirect', $data ) ) { // phpcs:ignore
				return false;
			}
		}
		$data = apply_filters( 'betterlinks/link/before_dispatch_redirect', $data ); // phpcs:ignore.
		if ( empty( $data ) ) {
			return false;
		}

		do_action( 'betterlinks/before_redirect', $data ); // phpcs:ignore.
		$this->dispatch_redirect( $data, next( $param ) );
	}
}
