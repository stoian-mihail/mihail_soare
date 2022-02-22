<?php
/* WPML support functions
------------------------------------------------------------------------------- */

// Check if WPML installed and activated
if ( !function_exists( 'writer_ancora_exists_wpml' ) ) {
	function writer_ancora_exists_wpml() {
		return defined('ICL_SITEPRESS_VERSION') && class_exists('sitepress');
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'writer_ancora_wpml_required_plugins' ) ) {
	//add_filter('writer_ancora_filter_required_plugins',	'writer_ancora_wpml_required_plugins');
	function writer_ancora_wpml_required_plugins($list=array()) {
		if (in_array('wpml', writer_ancora_storage_get('required_plugins')))
			$list[] = array(
					'name' 		=> 'WPML',
					'slug' 		=> 'wpml',
					'source'	=> writer_ancora_get_file_dir('plugins/install/wpml.zip'),
					'required' 	=> false
				);

		return $list;
	}
}
?>