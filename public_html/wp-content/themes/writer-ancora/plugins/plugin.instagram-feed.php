<?php
/* Instagram Feed support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('writer_ancora_instagram_feed_theme_setup')) {
	add_action( 'writer_ancora_action_before_init_theme', 'writer_ancora_instagram_feed_theme_setup', 1 );
	function writer_ancora_instagram_feed_theme_setup() {
		if (writer_ancora_exists_instagram_feed()) {
			if (is_admin()) {
				add_filter( 'writer_ancora_filter_importer_options',				'writer_ancora_instagram_feed_importer_set_options' );
			}
		}
		if (is_admin()) {
			add_filter( 'writer_ancora_filter_importer_required_plugins',		'writer_ancora_instagram_feed_importer_required_plugins', 10, 2 );
			add_filter( 'writer_ancora_filter_required_plugins',					'writer_ancora_instagram_feed_required_plugins' );
		}
	}
}

// Check if Instagram Feed installed and activated
if ( !function_exists( 'writer_ancora_exists_instagram_feed' ) ) {
	function writer_ancora_exists_instagram_feed() {
		return defined('SBIVER');
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'writer_ancora_instagram_feed_required_plugins' ) ) {
	//add_filter('writer_ancora_filter_required_plugins',	'writer_ancora_instagram_feed_required_plugins');
	function writer_ancora_instagram_feed_required_plugins($list=array()) {
		if (in_array('instagram_feed', writer_ancora_storage_get('required_plugins')))
			$list[] = array(
					'name' 		=> 'Instagram Feed',
					'slug' 		=> 'instagram-feed',
					'required' 	=> false
				);
		return $list;
	}
}



// One-click import support
//------------------------------------------------------------------------

// Check Instagram Feed in the required plugins
if ( !function_exists( 'writer_ancora_instagram_feed_importer_required_plugins' ) ) {
	//add_filter( 'writer_ancora_filter_importer_required_plugins',	'writer_ancora_instagram_feed_importer_required_plugins', 10, 2 );
	function writer_ancora_instagram_feed_importer_required_plugins($not_installed='', $list='') {
		//if (in_array('instagram_feed', writer_ancora_storage_get('required_plugins')) && !writer_ancora_exists_instagram_feed() )
		if (writer_ancora_strpos($list, 'instagram_feed')!==false && !writer_ancora_exists_instagram_feed() )
			$not_installed .= '<br>Instagram Feed';
		return $not_installed;
	}
}

// Set options for one-click importer
if ( !function_exists( 'writer_ancora_instagram_feed_importer_set_options' ) ) {
	//add_filter( 'writer_ancora_filter_importer_options',	'writer_ancora_instagram_feed_importer_set_options' );
	function writer_ancora_instagram_feed_importer_set_options($options=array()) {
		if ( in_array('instagram_feed', writer_ancora_storage_get('required_plugins')) && writer_ancora_exists_instagram_feed() ) {
			$options['additional_options'][] = 'sb_instagram_settings';		// Add slugs to export options for this plugin
		}
		return $options;
	}
}
?>