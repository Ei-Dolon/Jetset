<?php
/* 
Plugin Name: Hide Admin Bar
Plugin URI: https://wordpress.org/plugins/hide-admin-bar/
Description: Hides the Admin Bar in WordPress 3.1+, credits to <a href="http://yoast.com/">Yoast</a>, and <a href="https://petemall.com">Pete Mall</a>. If you love this plugin, <a href="https://ko-fi.com/sdenike">buy me a cup of coffee</a>.
Version: 0.4.8
Requires at least: 3.1
Requires PHP: 5.6
Author: Shelby DeNike
Author URI: https://shelbydenike.com
License: GPL v2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: hide-admin-bar
*/ 
add_action('admin_print_scripts-profile.php', 'hide_admin_bar_prefs');
function hide_admin_bar_prefs() { ?>
<style type="text/css">
	.show-admin-bar {display: none;}
</style>
<?php
}
add_filter('show_admin_bar', '__return_false');
?>