<?php
/* Instagram Widget support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('writer_ancora_instagram_widget_theme_setup')) {
	add_action( 'writer_ancora_action_before_init_theme', 'writer_ancora_instagram_widget_theme_setup', 1 );
	function writer_ancora_instagram_widget_theme_setup() {
		if (is_admin()) {
			add_filter( 'writer_ancora_filter_importer_required_plugins',		'writer_ancora_instagram_widget_importer_required_plugins', 10, 2 );
			add_filter( 'writer_ancora_filter_required_plugins',					'writer_ancora_instagram_widget_required_plugins' );
		}
	}
}

// Check if Instagram Widget installed and activated
if ( !function_exists( 'writer_ancora_exists_instagram_widget' ) ) {
	function writer_ancora_exists_instagram_widget() {
		return writer_ancora_widget_is_active('wp-instagram-widget/wp-instagram-widget');
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'writer_ancora_instagram_widget_required_plugins' ) ) {
	//add_filter('writer_ancora_filter_required_plugins',	'writer_ancora_instagram_widget_required_plugins');
	function writer_ancora_instagram_widget_required_plugins($list=array()) {
		if (in_array('instagram_widget', writer_ancora_storage_get('required_plugins')))
			$list[] = array(
					'name' 		=> 'Instagram Widget',
					'slug' 		=> 'wp-instagram-widget',
					'required' 	=> false
				);
		return $list;
	}
}



// One-click import support
//------------------------------------------------------------------------

// Check Instagram Widget in the required plugins
if ( !function_exists( 'writer_ancora_instagram_widget_importer_required_plugins' ) ) {
	//add_filter( 'writer_ancora_filter_importer_required_plugins',	'writer_ancora_instagram_widget_importer_required_plugins', 10, 2 );
	function writer_ancora_instagram_widget_importer_required_plugins($not_installed='', $list='') {
		//if (in_array('instagram_widget', writer_ancora_storage_get('required_plugins')) && !writer_ancora_exists_instagram_widget() )
		if (writer_ancora_strpos($list, 'instagram_widget')!==false && !writer_ancora_exists_instagram_widget() )
			$not_installed .= '<br>WP Instagram Widget';
		return $not_installed;
	}
}
?>