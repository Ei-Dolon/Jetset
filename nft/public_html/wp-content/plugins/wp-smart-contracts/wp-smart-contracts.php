<?php
/*
Plugin Name: WP Smart Contracts
Plugin URI: https://www.wpsmartcontracts.com/
Description: Easily create powerful Smart Contracts in multiple blockchain networks
Version: 2.0.9
Author: WPSmartContracts.com
Text Domain: wp-smart-contracts
Domain Path: /languages
License: GPLv2 or later
*/

if( ! defined( 'ABSPATH' ) ) die;

/**
 * Load generic helpers
 */
require_once("classes/wpsc-helpers.php");

/**
 * Load role manager
 */
require_once("classes/wpsc-roles.php");

/**
 * Template system, in JS and PHP
 */
require_once("classes/wpsc-mustache.php");

/**
 * Dashboard
 */
require_once("classes/wpsc-dashboard.php");

/**
 * Create Custom Post Types
 */
require_once("classes/wpsc-cpt.php");

/**
 * Load css and js
 */
require_once("classes/wpsc-assets.php");

/**
 * Load shortcodes
 */
require_once("classes/wpsc-shortcodes.php");

/**
 * Load Queries
 */
require_once("classes/wpsc-queries.php");

/**
 * Load wp-admin settings
 */
require_once("classes/wpsc-settings.php");

/**
 * Load Block Explorer Options
 */
require_once("classes/wpsc-endpoints.php");

/**
 * Load Clean Template
 */
require_once("classes/wpsc-page-templater.php");

/**
 * Create plugin pages on plugin activate
 */
add_action('init', function() {
    WPSC_helpers::createPluginPages();
});

/**	
 * Process cookies for gallery filtering	
 */	
add_action('template_redirect', function() {	
    WPSC_helpers::processGalleryCookies();	
});	

/**
 * IPFS
 */
require_once(__DIR__ . "/classes/wpsc-rest-client.php");
require_once(__DIR__ . "/classes/wpsc-media-ipfs.php");

/**
 * Internal use only, load deployer
 */
if (file_exists(__DIR__ . "/classes/wpsc-deployer.php")) {
    require_once(__DIR__ . "/classes/wpsc-deployer.php");
}

/**
 * Bulk Mint
 */
require_once("classes/wpsc-bulk-mint.php");

/**
 * Admin setup wizard
 */
require_once("classes/wpsc-admin-setup.php");

/**
 * GLB Animation Files support
 */
require_once("classes/wpsc-glb.php");

/**
 * Signature suppoprt
 */
require_once("classes/web3-login/web3-login.php");
