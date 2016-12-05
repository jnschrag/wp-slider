<?php
/**
 * Fired when the plugin is uninstalled.
 * Package: Hero Menu
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}
 
$option_name = 'js_hm_settings';
 
delete_option($option_name);
 
// for site options in Multisite
delete_site_option($option_name);

?>