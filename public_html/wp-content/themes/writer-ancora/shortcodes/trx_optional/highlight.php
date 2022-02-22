<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('writer_ancora_sc_highlight_theme_setup')) {
	add_action( 'writer_ancora_action_before_init_theme', 'writer_ancora_sc_highlight_theme_setup' );
	function writer_ancora_sc_highlight_theme_setup() {
		add_action('writer_ancora_action_shortcodes_list', 		'writer_ancora_sc_highlight_reg_shortcodes');
		if (function_exists('writer_ancora_exists_visual_composer') && writer_ancora_exists_visual_composer())
			add_action('writer_ancora_action_shortcodes_list_vc','writer_ancora_sc_highlight_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_highlight id="unique_id" color="fore_color's_name_or_#rrggbb" backcolor="back_color's_name_or_#rrggbb" style="custom_style"]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/trx_highlight]
*/

if (!function_exists('writer_ancora_sc_highlight')) {	
	function writer_ancora_sc_highlight($atts, $content=null){	
		if (writer_ancora_in_shortcode_blogger()) return '';
		extract(writer_ancora_html_decode(shortcode_atts(array(
			// Individual params
			"color" => "",
			"bg_color" => "",
			"font_size" => "",
			"type" => "1",
			// Common params
			"id" => "",
			"class" => "",
			"css" => ""
		), $atts)));
		$css .= ($color != '' ? 'color:' . esc_attr($color) . ';' : '')
			.($bg_color != '' ? 'background-color:' . esc_attr($bg_color) . ';' : '')
			.($font_size != '' ? 'font-size:' . esc_attr(writer_ancora_prepare_css_value($font_size)) . '; line-height: 1em;' : '');
		$output = '<span' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_highlight'.($type>0 ? ' sc_highlight_style_'.esc_attr($type) : ''). (!empty($class) ? ' '.esc_attr($class) : '').'"'
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
				. '>' 
				. do_shortcode($content) 
				. '</span>';
		return apply_filters('writer_ancora_shortcode_output', $output, 'trx_highlight', $atts, $content);
	}
	writer_ancora_require_shortcode('trx_highlight', 'writer_ancora_sc_highlight');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'writer_ancora_sc_highlight_reg_shortcodes' ) ) {
	//add_action('writer_ancora_action_shortcodes_list', 'writer_ancora_sc_highlight_reg_shortcodes');
	function writer_ancora_sc_highlight_reg_shortcodes() {
	
		writer_ancora_sc_map("trx_highlight", array(
			"title" => esc_html__("Highlight text", 'writer-ancora'),
			"desc" => wp_kses( __("Highlight text with selected color, background color and other styles", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"type" => array(
					"title" => esc_html__("Type", 'writer-ancora'),
					"desc" => wp_kses( __("Highlight type", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"value" => "1",
					"type" => "checklist",
					"options" => array(
						0 => esc_html__('Custom', 'writer-ancora'),
						1 => esc_html__('Type 1', 'writer-ancora'),
						2 => esc_html__('Type 2', 'writer-ancora'),
						3 => esc_html__('Type 3', 'writer-ancora')
					)
				),
				"color" => array(
					"title" => esc_html__("Color", 'writer-ancora'),
					"desc" => wp_kses( __("Color for the highlighted text", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"divider" => true,
					"value" => "",
					"type" => "color"
				),
				"bg_color" => array(
					"title" => esc_html__("Background color", 'writer-ancora'),
					"desc" => wp_kses( __("Background color for the highlighted text", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"value" => "",
					"type" => "color"
				),
				"font_size" => array(
					"title" => esc_html__("Font size", 'writer-ancora'),
					"desc" => wp_kses( __("Font size of the highlighted text (default - in pixels, allows any CSS units of measure)", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"value" => "",
					"type" => "text"
				),
				"_content_" => array(
					"title" => esc_html__("Highlighting content", 'writer-ancora'),
					"desc" => wp_kses( __("Content for highlight", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"divider" => true,
					"rows" => 4,
					"value" => "",
					"type" => "textarea"
				),
				"id" => writer_ancora_get_sc_param('id'),
				"class" => writer_ancora_get_sc_param('class'),
				"css" => writer_ancora_get_sc_param('css')
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'writer_ancora_sc_highlight_reg_shortcodes_vc' ) ) {
	//add_action('writer_ancora_action_shortcodes_list_vc', 'writer_ancora_sc_highlight_reg_shortcodes_vc');
	function writer_ancora_sc_highlight_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_highlight",
			"name" => esc_html__("Highlight text", 'writer-ancora'),
			"description" => wp_kses( __("Highlight text with selected color, background color and other styles", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
			"category" => esc_html__('Content', 'writer-ancora'),
			'icon' => 'icon_trx_highlight',
			"class" => "trx_sc_single trx_sc_highlight",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "type",
					"heading" => esc_html__("Type", 'writer-ancora'),
					"description" => wp_kses( __("Highlight type", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
							esc_html__('Custom', 'writer-ancora') => 0,
							esc_html__('Type 1', 'writer-ancora') => 1,
							esc_html__('Type 2', 'writer-ancora') => 2,
							esc_html__('Type 3', 'writer-ancora') => 3
						),
					"type" => "dropdown"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Text color", 'writer-ancora'),
					"description" => wp_kses( __("Color for the highlighted text", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_color",
					"heading" => esc_html__("Background color", 'writer-ancora'),
					"description" => wp_kses( __("Background color for the highlighted text", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "font_size",
					"heading" => esc_html__("Font size", 'writer-ancora'),
					"description" => wp_kses( __("Font size for the highlighted text (default - in pixels, allows any CSS units of measure)", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "content",
					"heading" => esc_html__("Highlight text", 'writer-ancora'),
					"description" => wp_kses( __("Content for highlight", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"class" => "",
					"value" => "",
					"type" => "textarea_html"
				),
				writer_ancora_get_vc_param('id'),
				writer_ancora_get_vc_param('class'),
				writer_ancora_get_vc_param('css')
			),
			'js_view' => 'VcTrxTextView'
		) );
		
		class WPBakeryShortCode_Trx_Highlight extends WRITER_ANCORA_VC_ShortCodeSingle {}
	}
}
?>