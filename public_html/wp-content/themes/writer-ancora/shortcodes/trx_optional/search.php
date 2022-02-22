<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('writer_ancora_sc_search_theme_setup')) {
	add_action( 'writer_ancora_action_before_init_theme', 'writer_ancora_sc_search_theme_setup' );
	function writer_ancora_sc_search_theme_setup() {
		add_action('writer_ancora_action_shortcodes_list', 		'writer_ancora_sc_search_reg_shortcodes');
		if (function_exists('writer_ancora_exists_visual_composer') && writer_ancora_exists_visual_composer())
			add_action('writer_ancora_action_shortcodes_list_vc','writer_ancora_sc_search_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_search id="unique_id" open="yes|no"]
*/

if (!function_exists('writer_ancora_sc_search')) {	
	function writer_ancora_sc_search($atts, $content=null){	
		if (writer_ancora_in_shortcode_blogger()) return '';
		extract(writer_ancora_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "regular",
			"state" => "fixed",
			"scheme" => "original",
			"ajax" => "",
			"title" => esc_html__('Search', 'writer-ancora'),
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$class .= ($class ? ' ' : '') . writer_ancora_get_css_position_as_classes($top, $right, $bottom, $left);
		if (empty($ajax)) $ajax = writer_ancora_get_theme_option('use_ajax_search');
		// Load core messages
		writer_ancora_enqueue_messages();
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') . ' class="search_wrap search_style_'.esc_attr($style).' search_state_'.esc_attr($state)
						. (writer_ancora_param_is_on($ajax) ? ' search_ajax' : '')
						. ($class ? ' '.esc_attr($class) : '')
						. '"'
					. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
					. (!writer_ancora_param_is_off($animation) ? ' data-animation="'.esc_attr(writer_ancora_get_animation_classes($animation)).'"' : '')
					. '>
						<div class="search_form_wrap">
							<form role="search" method="get" class="search_form" action="' . esc_url(home_url('/')) . '">
								<button type="submit" class="search_submit icon-search-light" title="' . ($state=='closed' ? esc_attr__('Open search', 'writer-ancora') : esc_attr__('Start search', 'writer-ancora')) . '"></button>
								<input type="text" class="search_field" placeholder="' . esc_attr($title) . '" value="' . esc_attr(get_search_query()) . '" name="s" />
							</form>
						</div>
						<div class="search_results widget_area' . ($scheme && !writer_ancora_param_is_off($scheme) && !writer_ancora_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '') . '"><a class="search_results_close icon-cancel"></a><div class="search_results_content"></div></div>
				</div>';
		return apply_filters('writer_ancora_shortcode_output', $output, 'trx_search', $atts, $content);
	}
	writer_ancora_require_shortcode('trx_search', 'writer_ancora_sc_search');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'writer_ancora_sc_search_reg_shortcodes' ) ) {
	//add_action('writer_ancora_action_shortcodes_list', 'writer_ancora_sc_search_reg_shortcodes');
	function writer_ancora_sc_search_reg_shortcodes() {
	
		writer_ancora_sc_map("trx_search", array(
			"title" => esc_html__("Search", 'writer-ancora'),
			"desc" => wp_kses( __("Show search form", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"style" => array(
					"title" => esc_html__("Style", 'writer-ancora'),
					"desc" => wp_kses( __("Select style to display search field", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"value" => "regular",
					"options" => array(
						"regular" => esc_html__('Regular', 'writer-ancora'),
						"rounded" => esc_html__('Rounded', 'writer-ancora')
					),
					"type" => "checklist"
				),
				"state" => array(
					"title" => esc_html__("State", 'writer-ancora'),
					"desc" => wp_kses( __("Select search field initial state", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"value" => "fixed",
					"options" => array(
						"fixed"  => esc_html__('Fixed',  'writer-ancora'),
						"opened" => esc_html__('Opened', 'writer-ancora'),
						"closed" => esc_html__('Closed', 'writer-ancora')
					),
					"type" => "checklist"
				),
				"title" => array(
					"title" => esc_html__("Title", 'writer-ancora'),
					"desc" => wp_kses( __("Title (placeholder) for the search field", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"value" => esc_html__("Search &hellip;", 'writer-ancora'),
					"type" => "text"
				),
				"ajax" => array(
					"title" => esc_html__("AJAX", 'writer-ancora'),
					"desc" => wp_kses( __("Search via AJAX or reload page", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"value" => "yes",
					"options" => writer_ancora_get_sc_param('yes_no'),
					"type" => "switch"
				),
				"top" => writer_ancora_get_sc_param('top'),
				"bottom" => writer_ancora_get_sc_param('bottom'),
				"left" => writer_ancora_get_sc_param('left'),
				"right" => writer_ancora_get_sc_param('right'),
				"id" => writer_ancora_get_sc_param('id'),
				"class" => writer_ancora_get_sc_param('class'),
				"animation" => writer_ancora_get_sc_param('animation'),
				"css" => writer_ancora_get_sc_param('css')
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'writer_ancora_sc_search_reg_shortcodes_vc' ) ) {
	//add_action('writer_ancora_action_shortcodes_list_vc', 'writer_ancora_sc_search_reg_shortcodes_vc');
	function writer_ancora_sc_search_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_search",
			"name" => esc_html__("Search form", 'writer-ancora'),
			"description" => wp_kses( __("Insert search form", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
			"category" => esc_html__('Content', 'writer-ancora'),
			'icon' => 'icon_trx_search',
			"class" => "trx_sc_single trx_sc_search",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "style",
					"heading" => esc_html__("Style", 'writer-ancora'),
					"description" => wp_kses( __("Select style to display search field", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"class" => "",
					"value" => array(
						esc_html__('Regular', 'writer-ancora') => "regular",
						esc_html__('Flat', 'writer-ancora') => "flat"
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "state",
					"heading" => esc_html__("State", 'writer-ancora'),
					"description" => wp_kses( __("Select search field initial state", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"class" => "",
					"value" => array(
						esc_html__('Fixed', 'writer-ancora')  => "fixed",
						esc_html__('Opened', 'writer-ancora') => "opened",
						esc_html__('Closed', 'writer-ancora') => "closed"
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title", 'writer-ancora'),
					"description" => wp_kses( __("Title (placeholder) for the search field", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"admin_label" => true,
					"class" => "",
					"value" => esc_html__("Search &hellip;", 'writer-ancora'),
					"type" => "textfield"
				),
				array(
					"param_name" => "ajax",
					"heading" => esc_html__("AJAX", 'writer-ancora'),
					"description" => wp_kses( __("Search via AJAX or reload page", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"class" => "",
					"value" => array(esc_html__('Use AJAX search', 'writer-ancora') => 'yes'),
					"type" => "checkbox"
				),
				writer_ancora_get_vc_param('id'),
				writer_ancora_get_vc_param('class'),
				writer_ancora_get_vc_param('animation'),
				writer_ancora_get_vc_param('css'),
				writer_ancora_get_vc_param('margin_top'),
				writer_ancora_get_vc_param('margin_bottom'),
				writer_ancora_get_vc_param('margin_left'),
				writer_ancora_get_vc_param('margin_right')
			)
		) );
		
		class WPBakeryShortCode_Trx_Search extends WRITER_ANCORA_VC_ShortCodeSingle {}
	}
}
?>