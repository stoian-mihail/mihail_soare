<?php
/* Visual Composer support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('writer_ancora_vc_theme_setup')) {
	add_action( 'writer_ancora_action_before_init_theme', 'writer_ancora_vc_theme_setup', 1 );
	function writer_ancora_vc_theme_setup() {
		if (writer_ancora_exists_visual_composer()) {
			if (is_admin()) {
				add_filter( 'writer_ancora_filter_importer_options',				'writer_ancora_vc_importer_set_options' );
			}
		}
		if (is_admin()) {
			add_filter( 'writer_ancora_filter_importer_required_plugins',		'writer_ancora_vc_importer_required_plugins', 10, 2 );
			add_filter( 'writer_ancora_filter_required_plugins',					'writer_ancora_vc_required_plugins' );
		}
	}
}

// Check if Visual Composer installed and activated
if ( !function_exists( 'writer_ancora_exists_visual_composer' ) ) {
	function writer_ancora_exists_visual_composer() {
		return class_exists('Vc_Manager');
	}
}

// Check if Visual Composer in frontend editor mode
if ( !function_exists( 'writer_ancora_vc_is_frontend' ) ) {
	function writer_ancora_vc_is_frontend() {
		return (isset($_GET['vc_editable']) && $_GET['vc_editable']=='true')
			|| (isset($_GET['vc_action']) && $_GET['vc_action']=='vc_inline');
		//return function_exists('vc_is_frontend_editor') && vc_is_frontend_editor();
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'writer_ancora_vc_required_plugins' ) ) {
	//add_filter('writer_ancora_filter_required_plugins',	'writer_ancora_vc_required_plugins');
	function writer_ancora_vc_required_plugins($list=array()) {
		if (in_array('visual_composer', writer_ancora_storage_get('required_plugins')))
			$list[] = array(
					'name' 		=> 'Visual Composer',
					'slug' 		=> 'js_composer',
					'source'	=> writer_ancora_get_file_dir('plugins/install/js_composer.zip'),
					'required' 	=> false
				);
		return $list;
	}
}



// One-click import support
//------------------------------------------------------------------------

// Check VC in the required plugins
if ( !function_exists( 'writer_ancora_vc_importer_required_plugins' ) ) {
	//add_filter( 'writer_ancora_filter_importer_required_plugins',	'writer_ancora_vc_importer_required_plugins', 10, 2 );
	function writer_ancora_vc_importer_required_plugins($not_installed='', $list='') {
		//if (in_array('visual_composer', writer_ancora_storage_get('required_plugins')) && !writer_ancora_exists_visual_composer() && writer_ancora_get_value_gp('data_type')=='vc' )
		if (!writer_ancora_exists_visual_composer() )		// && writer_ancora_strpos($list, 'visual_composer')!==false
			$not_installed .= '<br>Visual Composer';
		return $not_installed;
	}
}

// Set options for one-click importer
if ( !function_exists( 'writer_ancora_vc_importer_set_options' ) ) {
	//add_filter( 'writer_ancora_filter_importer_options',	'writer_ancora_vc_importer_set_options' );
	function writer_ancora_vc_importer_set_options($options=array()) {
		if ( in_array('visual_composer', writer_ancora_storage_get('required_plugins')) && writer_ancora_exists_visual_composer() ) {
			$options['additional_options'][] = 'wpb_js_templates';		// Add slugs to export options for this plugin

		}
		return $options;
	}
}
?>