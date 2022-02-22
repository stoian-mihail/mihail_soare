<?php
/**
Plugin Name: Google Analytics Master
Plugin URI: http://wordpress.techgasp.com/google-analytics-master/
Version: 5.0.2
Author: TechGasp
Author URI: http://wordpress.techgasp.com
Text Domain: google-analytics-master
Description: Google Analytics Master is the professional plugin to add Google Analytics tracking to your wordpress.
License: GPL2 or later
*/
/*
Copyright 2013 TechGasp  (email : info@techgasp.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as 
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
if(!class_exists('google_analytics_master')) :
///////DEFINE VERSION///////
define( 'GOOGLE_ANALYTICS_MASTER_VERSION', '5.0.2' );

global $google_analytics_master_name;
$google_analytics_master_name = "Google Analytics Master"; //pretty name
if( is_multisite() ) {
update_site_option( 'google_analytics_master_name', $google_analytics_master_name );
}
else{
update_option( 'google_analytics_master_name', $google_analytics_master_name );
}

class google_analytics_master{
public static function content_with_quote($content){
$quote = '<p>' . get_option('tsm_quote') . '</p>';
	return $content . $quote;
}
//SETTINGS LINK IN PLUGIN MANAGER
public static function google_analytics_master_links( $links, $file ) {
if ( $file == plugin_basename( dirname(__FILE__).'/google-analytics-master.php' ) ) {
		if( is_network_admin() ){
		$techgasp_plugin_url = network_admin_url( 'admin.php?page=google-analytics-master' );
		}
		else {
		$techgasp_plugin_url = admin_url( 'admin.php?page=google-analytics-master' );
		}
	$links[] = '<a href="' . $techgasp_plugin_url . '">'.__( 'Settings' ).'</a>';
	}
	return $links;
}
//END CLASS
}
add_filter( 'the_content', array('google_analytics_master', 'content_with_quote'));
add_filter( 'plugin_action_links', array('google_analytics_master', 'google_analytics_master_links'), 10, 2 );
endif;

// HOOK ADMIN
require_once( dirname( __FILE__ ) . '/includes/google-analytics-master-admin.php');
// HOOK ADMIN SETTINGS
require_once( dirname( __FILE__ ) . '/includes/google-analytics-master-admin-settings.php');
// HOOK ADMIN STATISTICS QUICK
require_once( dirname( __FILE__ ) . '/includes/google-analytics-master-admin-statistics-quick.php');
// HOOK ADMIN STATISTICS 7 DAYS
require_once( dirname( __FILE__ ) . '/includes/google-analytics-master-admin-statistics-7-days.php');
// HOOK ADMIN STATISTICS 30 Days
require_once( dirname( __FILE__ ) . '/includes/google-analytics-master-admin-statistics-30-days.php');

// HOOK ANALYTICS ACTIVE
require_once( dirname( __FILE__ ) . '/includes/google-analytics-master-active.php');
// HOOK DASHBOARD WIDGET SMALL
require_once( dirname( __FILE__ ) . '/includes/google-analytics-master-widget-dashboard-small.php');
