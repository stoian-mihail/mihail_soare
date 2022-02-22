<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('writer_ancora_sc_icon_theme_setup')) {
	add_action( 'writer_ancora_action_before_init_theme', 'writer_ancora_sc_icon_theme_setup' );
	function writer_ancora_sc_icon_theme_setup() {
		add_action('writer_ancora_action_shortcodes_list', 		'writer_ancora_sc_icon_reg_shortcodes');
		if (function_exists('writer_ancora_exists_visual_composer') && writer_ancora_exists_visual_composer())
			add_action('writer_ancora_action_shortcodes_list_vc','writer_ancora_sc_icon_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_icon id="unique_id" style='round|square' icon='' color="" bg_color="" size="" weight=""]
*/

if (!function_exists('writer_ancora_sc_icon')) {	
	function writer_ancora_sc_icon($atts, $content=null){	
		if (writer_ancora_in_shortcode_blogger()) return '';
		extract(writer_ancora_html_decode(shortcode_atts(array(
			// Individual params
			"icon" => "",
			"color" => "",
			"bg_color" => "",
			"bg_shape" => "",
			"font_size" => "",
			"font_weight" => "",
			"align" => "",
			"link" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$class .= ($class ? ' ' : '') . writer_ancora_get_css_position_as_classes($top, $right, $bottom, $left);
		$css2 = ($font_weight != '' && !writer_ancora_is_inherit_option($font_weight) ? 'font-weight:'. esc_attr($font_weight).';' : '')
			. ($font_size != '' ? 'font-size:' . esc_attr(writer_ancora_prepare_css_value($font_size)) . '; line-height: ' . (!$bg_shape || writer_ancora_param_is_inherit($bg_shape) ? '1' : '1.2') . 'em;' : '')
			. ($color != '' ? 'color:'.esc_attr($color).';' : '')
			. ($bg_color != '' ? 'background-color:'.esc_attr($bg_color).';border-color:'.esc_attr($bg_color).';' : '')
		;
		$output = $icon!='' 
			? ($link ? '<a href="'.esc_url($link).'"' : '<span') . ($id ? ' id="'.esc_attr($id).'"' : '')
				. ' class="sc_icon '.esc_attr($icon)
					. ($bg_shape && !writer_ancora_param_is_inherit($bg_shape) ? ' sc_icon_shape_'.esc_attr($bg_shape) : '')
					. ($align && $align!='none' ? ' align'.esc_attr($align) : '') 
					. (!empty($class) ? ' '.esc_attr($class) : '')
				.'"'
				.($css || $css2 ? ' style="'.($class ? 'display:block;' : '') . ($css) . ($css2) . '"' : '')
				.'>'
				.($link ? '</a>' : '</span>')
			: '';
		return apply_filters('writer_ancora_shortcode_output', $output, 'trx_icon', $atts, $content);
	}
	writer_ancora_require_shortcode('trx_icon', 'writer_ancora_sc_icon');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'writer_ancora_sc_icon_reg_shortcodes' ) ) {
	//add_action('writer_ancora_action_shortcodes_list', 'writer_ancora_sc_icon_reg_shortcodes');
	function writer_ancora_sc_icon_reg_shortcodes() {
	
		writer_ancora_sc_map("trx_icon", array(
			"title" => esc_html__("Icon", 'writer-ancora'),
			"desc" => wp_kses( __("Insert icon", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"icon" => array(
					"title" => esc_html__('Icon',  'writer-ancora'),
					"desc" => wp_kses( __('Select font icon from the Fontello icons set',  'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"value" => "",
					"type" => "icons",
					"options" => writer_ancora_get_sc_param('icons')
				),
				"color" => array(
					"title" => esc_html__("Icon's color", 'writer-ancora'),
					"desc" => wp_kses( __("Icon's color", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"dependency" => array(
						'icon' => array('not_empty')
					),
					"value" => "",
					"type" => "color"
				),
				"bg_shape" => array(
					"title" => esc_html__("Background shape", 'writer-ancora'),
					"desc" => wp_kses( __("Shape of the icon background", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"dependency" => array(
						'icon' => array('not_empty')
					),
					"value" => "none",
					"type" => "radio",
					"options" => array(
						'none' => esc_html__('None', 'writer-ancora'),
						'round' => esc_html__('Round', 'writer-ancora'),
						'square' => esc_html__('Square', 'writer-ancora')
					)
				),
				"bg_color" => array(
					"title" => esc_html__("Icon's background color", 'writer-ancora'),
					"desc" => wp_kses( __("Icon's background color", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"dependency" => array(
						'icon' => array('not_empty'),
						'background' => array('round','square')
					),
					"value" => "",
					"type" => "color"
				),
				"font_size" => array(
					"title" => esc_html__("Font size", 'writer-ancora'),
					"desc" => wp_kses( __("Icon's font size", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"dependency" => array(
						'icon' => array('not_empty')
					),
					"value" => "",
					"type" => "spinner",
					"min" => 8,
					"max" => 240
				),
				"font_weight" => array(
					"title" => esc_html__("Font weight", 'writer-ancora'),
					"desc" => wp_kses( __("Icon font weight", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"dependency" => array(
						'icon' => array('not_empty')
					),
					"value" => "",
					"type" => "select",
					"size" => "medium",
					"options" => array(
						'100' => esc_html__('Thin (100)', 'writer-ancora'),
						'300' => esc_html__('Light (300)', 'writer-ancora'),
						'400' => esc_html__('Normal (400)', 'writer-ancora'),
						'700' => esc_html__('Bold (700)', 'writer-ancora')
					)
				),
				"align" => array(
					"title" => esc_html__("Alignment", 'writer-ancora'),
					"desc" => wp_kses( __("Icon text alignment", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"dependency" => array(
						'icon' => array('not_empty')
					),
					"value" => "",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => writer_ancora_get_sc_param('align')
				), 
				"link" => array(
					"title" => esc_html__("Link URL", 'writer-ancora'),
					"desc" => wp_kses( __("Link URL from this icon (if not empty)", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"value" => "",
					"type" => "text"
				),
				"top" => writer_ancora_get_sc_param('top'),
				"bottom" => writer_ancora_get_sc_param('bottom'),
				"left" => writer_ancora_get_sc_param('left'),
				"right" => writer_ancora_get_sc_param('right'),
				"id" => writer_ancora_get_sc_param('id'),
				"class" => writer_ancora_get_sc_param('class'),
				"css" => writer_ancora_get_sc_param('css')
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'writer_ancora_sc_icon_reg_shortcodes_vc' ) ) {
	//add_action('writer_ancora_action_shortcodes_list_vc', 'writer_ancora_sc_icon_reg_shortcodes_vc');
	function writer_ancora_sc_icon_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_icon",
			"name" => esc_html__("Icon", 'writer-ancora'),
			"description" => wp_kses( __("Insert the icon", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
			"category" => esc_html__('Content', 'writer-ancora'),
			'icon' => 'icon_trx_icon',
			"class" => "trx_sc_single trx_sc_icon",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "icon",
					"heading" => esc_html__("Icon", 'writer-ancora'),
					"description" => wp_kses( __("Select icon class from Fontello icons set", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"admin_label" => true,
					"class" => "",
					"value" => writer_ancora_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Text color", 'writer-ancora'),
					"description" => wp_kses( __("Icon's color", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_color",
					"heading" => esc_html__("Background color", 'writer-ancora'),
					"description" => wp_kses( __("Background color for the icon", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_shape",
					"heading" => esc_html__("Background shape", 'writer-ancora'),
					"description" => wp_kses( __("Shape of the icon background", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
						esc_html__('None', 'writer-ancora') => 'none',
						esc_html__('Round', 'writer-ancora') => 'round',
						esc_html__('Square', 'writer-ancora') => 'square'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "font_size",
					"heading" => esc_html__("Font size", 'writer-ancora'),
					"description" => wp_kses( __("Icon's font size", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "font_weight",
					"heading" => esc_html__("Font weight", 'writer-ancora'),
					"description" => wp_kses( __("Icon's font weight", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"class" => "",
					"value" => array(
						esc_html__('Default', 'writer-ancora') => 'inherit',
						esc_html__('Thin (100)', 'writer-ancora') => '100',
						esc_html__('Light (300)', 'writer-ancora') => '300',
						esc_html__('Normal (400)', 'writer-ancora') => '400',
						esc_html__('Bold (700)', 'writer-ancora') => '700'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Icon's alignment", 'writer-ancora'),
					"description" => wp_kses( __("Align icon to left, center or right", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(writer_ancora_get_sc_param('align')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "link",
					"heading" => esc_html__("Link URL", 'writer-ancora'),
					"description" => wp_kses( __("Link URL from this icon (if not empty)", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				writer_ancora_get_vc_param('id'),
				writer_ancora_get_vc_param('class'),
				writer_ancora_get_vc_param('css'),
				writer_ancora_get_vc_param('margin_top'),
				writer_ancora_get_vc_param('margin_bottom'),
				writer_ancora_get_vc_param('margin_left'),
				writer_ancora_get_vc_param('margin_right')
			),
		) );
		
		class WPBakeryShortCode_Trx_Icon extends WRITER_ANCORA_VC_ShortCodeSingle {}
	}
}
?>