<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('writer_ancora_sc_button_theme_setup')) {
	add_action( 'writer_ancora_action_before_init_theme', 'writer_ancora_sc_button_theme_setup' );
	function writer_ancora_sc_button_theme_setup() {
		add_action('writer_ancora_action_shortcodes_list', 		'writer_ancora_sc_button_reg_shortcodes');
		if (function_exists('writer_ancora_exists_visual_composer') && writer_ancora_exists_visual_composer())
			add_action('writer_ancora_action_shortcodes_list_vc','writer_ancora_sc_button_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_button id="unique_id" type="square|round" fullsize="0|1" style="global|light|dark" size="mini|medium|big|huge|banner" icon="icon-name" link='#' target='']Button caption[/trx_button]
*/

if (!function_exists('writer_ancora_sc_button')) {	
	function writer_ancora_sc_button($atts, $content=null){	
		if (writer_ancora_in_shortcode_blogger()) return '';
		extract(writer_ancora_html_decode(shortcode_atts(array(
			// Individual params
			"type" => "square",
			"style" => "filled",
			"size" => "small",
			"icon" => "",
			"color" => "",
			"bg_color" => "",
			"link" => "",
			"target" => "",
			"align" => "",
			"rel" => "",
			"popup" => "no",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"animation" => "",
			"width" => "",
			"height" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$class .= ($class ? ' ' : '') . writer_ancora_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= writer_ancora_get_css_dimensions_from_values($width, $height)
			. ($color !== '' ? 'color:' . esc_attr($color) .';' : '')
			. ($bg_color !== '' ? 'background-color:' . esc_attr($bg_color) . '; border-color:'. esc_attr($bg_color) .';' : '');
		if (writer_ancora_param_is_on($popup)) writer_ancora_enqueue_popup('magnific');
		$output = '<a href="' . (empty($link) ? '#' : $link) . '"'
			. (!empty($target) ? ' target="'.esc_attr($target).'"' : '')
			. (!empty($rel) ? ' rel="'.esc_attr($rel).'"' : '')
			. (!writer_ancora_param_is_off($animation) ? ' data-animation="'.esc_attr(writer_ancora_get_animation_classes($animation)).'"' : '')
			. ' class="sc_button sc_button_' . esc_attr($type) 
					. ' sc_button_style_' . esc_attr($style) 
					. ' sc_button_size_' . esc_attr($size)
					. ($align && $align!='none' ? ' align'.esc_attr($align) : '') 
					. (!empty($class) ? ' '.esc_attr($class) : '')
					. ($icon!='' ? '  sc_button_iconed '. esc_attr($icon) : '') 
					. (writer_ancora_param_is_on($popup) ? ' sc_popup_link' : '') 
					. '"'
			. ($id ? ' id="'.esc_attr($id).'"' : '') 
			. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
			. '>'
			. do_shortcode($content)
			. '</a>';
		return apply_filters('writer_ancora_shortcode_output', $output, 'trx_button', $atts, $content);
	}
	writer_ancora_require_shortcode('trx_button', 'writer_ancora_sc_button');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'writer_ancora_sc_button_reg_shortcodes' ) ) {
	//add_action('writer_ancora_action_shortcodes_list', 'writer_ancora_sc_button_reg_shortcodes');
	function writer_ancora_sc_button_reg_shortcodes() {
	
		writer_ancora_sc_map("trx_button", array(
			"title" => esc_html__("Button", 'writer-ancora'),
			"desc" => wp_kses( __("Button with link", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"_content_" => array(
					"title" => esc_html__("Caption", 'writer-ancora'),
					"desc" => wp_kses( __("Button caption", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"value" => "",
					"type" => "text"
				),
				"type" => array(
					"title" => esc_html__("Button's shape", 'writer-ancora'),
					"desc" => wp_kses( __("Select button's shape", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"value" => "square",
					"size" => "medium",
					"options" => array(
						'square' => esc_html__('Square', 'writer-ancora'),
						'round' => esc_html__('Round', 'writer-ancora')
					),
					"type" => "switch"
				), 
				"style" => array(
					"title" => esc_html__("Button's style", 'writer-ancora'),
					"desc" => wp_kses( __("Select button's style", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"value" => "default",
					"dir" => "horizontal",
					"options" => array(
						'filled' => esc_html__('Filled', 'writer-ancora'),
						'border' => esc_html__('Border', 'writer-ancora'),
						'style_2' => esc_html__('Style 2', 'writer-ancora')
					),
					"type" => "checklist"
				), 
				"size" => array(
					"title" => esc_html__("Button's size", 'writer-ancora'),
					"desc" => wp_kses( __("Select button's size", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"value" => "small",
					"dir" => "horizontal",
					"options" => array(
						'small' => esc_html__('Small', 'writer-ancora'),
						'medium' => esc_html__('Medium', 'writer-ancora'),
						'large' => esc_html__('Large', 'writer-ancora')
					),
					"type" => "checklist"
				), 
				"icon" => array(
					"title" => esc_html__("Button's icon",  'writer-ancora'),
					"desc" => wp_kses( __('Select icon for the title from Fontello icons set',  'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"value" => "",
					"type" => "icons",
					"options" => writer_ancora_get_sc_param('icons')
				),
				"color" => array(
					"title" => esc_html__("Button's text color", 'writer-ancora'),
					"desc" => wp_kses( __("Any color for button's caption", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"std" => "",
					"value" => "",
					"type" => "color"
				),
				"bg_color" => array(
					"title" => esc_html__("Button's backcolor", 'writer-ancora'),
					"desc" => wp_kses( __("Any color for button's background", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"value" => "",
					"type" => "color"
				),
				"align" => array(
					"title" => esc_html__("Button's alignment", 'writer-ancora'),
					"desc" => wp_kses( __("Align button to left, center or right", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"value" => "none",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => writer_ancora_get_sc_param('align')
				), 
				"link" => array(
					"title" => esc_html__("Link URL", 'writer-ancora'),
					"desc" => wp_kses( __("URL for link on button click", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"divider" => true,
					"value" => "",
					"type" => "text"
				),
				"target" => array(
					"title" => esc_html__("Link target", 'writer-ancora'),
					"desc" => wp_kses( __("Target for link on button click", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"dependency" => array(
						'link' => array('not_empty')
					),
					"value" => "",
					"type" => "text"
				),
				"popup" => array(
					"title" => esc_html__("Open link in popup", 'writer-ancora'),
					"desc" => wp_kses( __("Open link target in popup window", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"dependency" => array(
						'link' => array('not_empty')
					),
					"value" => "no",
					"type" => "switch",
					"options" => writer_ancora_get_sc_param('yes_no')
				), 
				"rel" => array(
					"title" => esc_html__("Rel attribute", 'writer-ancora'),
					"desc" => wp_kses( __("Rel attribute for button's link (if need)", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"dependency" => array(
						'link' => array('not_empty')
					),
					"value" => "",
					"type" => "text"
				),
				"width" => writer_ancora_shortcodes_width(),
				"height" => writer_ancora_shortcodes_height(),
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
if ( !function_exists( 'writer_ancora_sc_button_reg_shortcodes_vc' ) ) {
	//add_action('writer_ancora_action_shortcodes_list_vc', 'writer_ancora_sc_button_reg_shortcodes_vc');
	function writer_ancora_sc_button_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_button",
			"name" => esc_html__("Button", 'writer-ancora'),
			"description" => wp_kses( __("Button with link", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
			"category" => esc_html__('Content', 'writer-ancora'),
			'icon' => 'icon_trx_button',
			"class" => "trx_sc_single trx_sc_button",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "content",
					"heading" => esc_html__("Caption", 'writer-ancora'),
					"description" => wp_kses( __("Button caption", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "type",
					"heading" => esc_html__("Button's shape", 'writer-ancora'),
					"description" => wp_kses( __("Select button's shape", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"class" => "",
					"value" => array(
						esc_html__('Square', 'writer-ancora') => 'square',
						esc_html__('Round', 'writer-ancora') => 'round'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "style",
					"heading" => esc_html__("Button's style", 'writer-ancora'),
					"description" => wp_kses( __("Select button's style", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"class" => "",
					"value" => array(
						esc_html__('Filled', 'writer-ancora') => 'filled',
						esc_html__('Border', 'writer-ancora') => 'border',
						esc_html__('Style 2', 'writer-ancora') => 'style_2'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "size",
					"heading" => esc_html__("Button's size", 'writer-ancora'),
					"description" => wp_kses( __("Select button's size", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
						esc_html__('Small', 'writer-ancora') => 'small',
						esc_html__('Medium', 'writer-ancora') => 'medium',
						esc_html__('Large', 'writer-ancora') => 'large'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("Button's icon", 'writer-ancora'),
					"description" => wp_kses( __("Select icon for the title from Fontello icons set", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"class" => "",
					"value" => writer_ancora_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Button's text color", 'writer-ancora'),
					"description" => wp_kses( __("Any color for button's caption", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_color",
					"heading" => esc_html__("Button's backcolor", 'writer-ancora'),
					"description" => wp_kses( __("Any color for button's background", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Button's alignment", 'writer-ancora'),
					"description" => wp_kses( __("Align button to left, center or right", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"class" => "",
					"value" => array_flip(writer_ancora_get_sc_param('align')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "link",
					"heading" => esc_html__("Link URL", 'writer-ancora'),
					"description" => wp_kses( __("URL for the link on button click", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"class" => "",
					"group" => esc_html__('Link', 'writer-ancora'),
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "target",
					"heading" => esc_html__("Link target", 'writer-ancora'),
					"description" => wp_kses( __("Target for the link on button click", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"class" => "",
					"group" => esc_html__('Link', 'writer-ancora'),
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "popup",
					"heading" => esc_html__("Open link in popup", 'writer-ancora'),
					"description" => wp_kses( __("Open link target in popup window", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"class" => "",
					"group" => esc_html__('Link', 'writer-ancora'),
					"value" => array(esc_html__('Open in popup', 'writer-ancora') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "rel",
					"heading" => esc_html__("Rel attribute", 'writer-ancora'),
					"description" => wp_kses( __("Rel attribute for the button's link (if need", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"class" => "",
					"group" => esc_html__('Link', 'writer-ancora'),
					"value" => "",
					"type" => "textfield"
				),
				writer_ancora_get_vc_param('id'),
				writer_ancora_get_vc_param('class'),
				writer_ancora_get_vc_param('animation'),
				writer_ancora_get_vc_param('css'),
				writer_ancora_vc_width(),
				writer_ancora_vc_height(),
				writer_ancora_get_vc_param('margin_top'),
				writer_ancora_get_vc_param('margin_bottom'),
				writer_ancora_get_vc_param('margin_left'),
				writer_ancora_get_vc_param('margin_right')
			),
			'js_view' => 'VcTrxTextView'
		) );
		
		class WPBakeryShortCode_Trx_Button extends WRITER_ANCORA_VC_ShortCodeSingle {}
	}
}
?>