<?php

// Check if shortcodes settings are now used
if ( !function_exists( 'writer_ancora_shortcodes_is_used' ) ) {
	function writer_ancora_shortcodes_is_used() {
		return writer_ancora_options_is_used() 															// All modes when Theme Options are used
			|| (is_admin() && isset($_POST['action']) 
					&& in_array($_POST['action'], array('vc_edit_form', 'wpb_show_edit_form')))		// AJAX query when save post/page
			|| (is_admin() && writer_ancora_strpos($_SERVER['REQUEST_URI'], 'vc-roles')!==false)			// VC Role Manager
			|| (function_exists('writer_ancora_vc_is_frontend') && writer_ancora_vc_is_frontend());			// VC Frontend editor mode
	}
}

// Width and height params
if ( !function_exists( 'writer_ancora_shortcodes_width' ) ) {
	function writer_ancora_shortcodes_width($w="") {
		return array(
			"title" => esc_html__("Width", 'writer-ancora'),
			"divider" => true,
			"value" => $w,
			"type" => "text"
		);
	}
}
if ( !function_exists( 'writer_ancora_shortcodes_height' ) ) {
	function writer_ancora_shortcodes_height($h='') {
		return array(
			"title" => esc_html__("Height", 'writer-ancora'),
			"desc" => wp_kses( __("Width (in pixels or percent) and height (only in pixels) of element", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
			"value" => $h,
			"type" => "text"
		);
	}
}

// Return sc_param value
if ( !function_exists( 'writer_ancora_get_sc_param' ) ) {
	function writer_ancora_get_sc_param($prm) {
		return writer_ancora_storage_get_array('sc_params', $prm);
	}
}

// Set sc_param value
if ( !function_exists( 'writer_ancora_set_sc_param' ) ) {
	function writer_ancora_set_sc_param($prm, $val) {
		writer_ancora_storage_set_array('sc_params', $prm, $val);
	}
}

// Add sc settings in the sc list
if ( !function_exists( 'writer_ancora_sc_map' ) ) {
	function writer_ancora_sc_map($sc_name, $sc_settings) {
		writer_ancora_storage_set_array('shortcodes', $sc_name, $sc_settings);
	}
}

// Add sc settings in the sc list after the key
if ( !function_exists( 'writer_ancora_sc_map_after' ) ) {
	function writer_ancora_sc_map_after($after, $sc_name, $sc_settings='') {
		writer_ancora_storage_set_array_after('shortcodes', $after, $sc_name, $sc_settings);
	}
}

// Add sc settings in the sc list before the key
if ( !function_exists( 'writer_ancora_sc_map_before' ) ) {
	function writer_ancora_sc_map_before($before, $sc_name, $sc_settings='') {
		writer_ancora_storage_set_array_before('shortcodes', $before, $sc_name, $sc_settings);
	}
}



/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'writer_ancora_shortcodes_settings_theme_setup' ) ) {
//	if ( writer_ancora_vc_is_frontend() )
	if ( (isset($_GET['vc_editable']) && $_GET['vc_editable']=='true') || (isset($_GET['vc_action']) && $_GET['vc_action']=='vc_inline') )
		add_action( 'writer_ancora_action_before_init_theme', 'writer_ancora_shortcodes_settings_theme_setup', 20 );
	else
		add_action( 'writer_ancora_action_after_init_theme', 'writer_ancora_shortcodes_settings_theme_setup' );
	function writer_ancora_shortcodes_settings_theme_setup() {
		if (writer_ancora_shortcodes_is_used()) {

			// Sort templates alphabetically
			$tmp = writer_ancora_storage_get('registered_templates');
			ksort($tmp);
			writer_ancora_storage_set('registered_templates', $tmp);

			// Prepare arrays 
			writer_ancora_storage_set('sc_params', array(
			
				// Current element id
				'id' => array(
					"title" => esc_html__("Element ID", 'writer-ancora'),
					"desc" => wp_kses( __("ID for current element", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"divider" => true,
					"value" => "",
					"type" => "text"
				),
			
				// Current element class
				'class' => array(
					"title" => esc_html__("Element CSS class", 'writer-ancora'),
					"desc" => wp_kses( __("CSS class for current element (optional)", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"value" => "",
					"type" => "text"
				),
			
				// Current element style
				'css' => array(
					"title" => esc_html__("CSS styles", 'writer-ancora'),
					"desc" => wp_kses( __("Any additional CSS rules (if need)", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"value" => "",
					"type" => "text"
				),
			
			
				// Switcher choises
				'list_styles' => array(
					'ul'	=> esc_html__('Unordered', 'writer-ancora'),
					'ol'	=> esc_html__('Ordered', 'writer-ancora'),
					'iconed'=> esc_html__('Iconed', 'writer-ancora')
				),

				'yes_no'	=> writer_ancora_get_list_yesno(),
				'on_off'	=> writer_ancora_get_list_onoff(),
				'dir' 		=> writer_ancora_get_list_directions(),
				'align'		=> writer_ancora_get_list_alignments(),
				'float'		=> writer_ancora_get_list_floats(),
				'hpos'		=> writer_ancora_get_list_hpos(),
				'show_hide'	=> writer_ancora_get_list_showhide(),
				'sorting' 	=> writer_ancora_get_list_sortings(),
				'ordering' 	=> writer_ancora_get_list_orderings(),
				'shapes'	=> writer_ancora_get_list_shapes(),
				'sizes'		=> writer_ancora_get_list_sizes(),
				'sliders'	=> writer_ancora_get_list_sliders(),
				'categories'=> writer_ancora_get_list_categories(),
				'columns'	=> writer_ancora_get_list_columns(),
				'images'	=> array_merge(array('none'=>"none"), writer_ancora_get_list_files("images/icons", "png")),
				'icons'		=> array_merge(array("inherit", "none"), writer_ancora_get_list_icons()),
				'locations'	=> writer_ancora_get_list_dedicated_locations(),
				'filters'	=> writer_ancora_get_list_portfolio_filters(),
				'formats'	=> writer_ancora_get_list_post_formats_filters(),
				'hovers'	=> writer_ancora_get_list_hovers(true),
				'hovers_dir'=> writer_ancora_get_list_hovers_directions(true),
				'schemes'	=> writer_ancora_get_list_color_schemes(true),
				'animations'		=> writer_ancora_get_list_animations_in(),
				'margins' 			=> writer_ancora_get_list_margins(true),
				'blogger_styles'	=> writer_ancora_get_list_templates_blogger(),
				'forms'				=> writer_ancora_get_list_templates_forms(),
				'posts_types'		=> writer_ancora_get_list_posts_types(),
				'googlemap_styles'	=> writer_ancora_get_list_googlemap_styles(),
				'field_types'		=> writer_ancora_get_list_field_types(),
				'label_positions'	=> writer_ancora_get_list_label_positions()
				)
			);

			// Common params
			writer_ancora_set_sc_param('animation', array(
				"title" => esc_html__("Animation",  'writer-ancora'),
				"desc" => wp_kses( __('Select animation while object enter in the visible area of page',  'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
				"value" => "none",
				"type" => "select",
				"options" => writer_ancora_get_sc_param('animations')
				)
			);
			writer_ancora_set_sc_param('top', array(
				"title" => esc_html__("Top margin",  'writer-ancora'),
				"divider" => true,
				"value" => "inherit",
				"type" => "select",
				"options" => writer_ancora_get_sc_param('margins')
				)
			);
			writer_ancora_set_sc_param('bottom', array(
				"title" => esc_html__("Bottom margin",  'writer-ancora'),
				"value" => "inherit",
				"type" => "select",
				"options" => writer_ancora_get_sc_param('margins')
				)
			);
			writer_ancora_set_sc_param('left', array(
				"title" => esc_html__("Left margin",  'writer-ancora'),
				"value" => "inherit",
				"type" => "select",
				"options" => writer_ancora_get_sc_param('margins')
				)
			);
			writer_ancora_set_sc_param('right', array(
				"title" => esc_html__("Right margin",  'writer-ancora'),
				"desc" => wp_kses( __("Margins around this shortcode", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
				"value" => "inherit",
				"type" => "select",
				"options" => writer_ancora_get_sc_param('margins')
				)
			);

			writer_ancora_storage_set('sc_params', apply_filters('writer_ancora_filter_shortcodes_params', writer_ancora_storage_get('sc_params')));

			// Shortcodes list
			//------------------------------------------------------------------
			writer_ancora_storage_set('shortcodes', array());
			
			// Register shortcodes
			do_action('writer_ancora_action_shortcodes_list');

			// Sort shortcodes list
			$tmp = writer_ancora_storage_get('shortcodes');
			uasort($tmp, function($a, $b) {
				return strcmp($a['title'], $b['title']);
			});
			writer_ancora_storage_set('shortcodes', $tmp);
		}
	}
}
?>