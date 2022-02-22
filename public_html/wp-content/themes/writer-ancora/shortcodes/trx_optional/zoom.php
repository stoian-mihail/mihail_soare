<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('writer_ancora_sc_zoom_theme_setup')) {
	add_action( 'writer_ancora_action_before_init_theme', 'writer_ancora_sc_zoom_theme_setup' );
	function writer_ancora_sc_zoom_theme_setup() {
		add_action('writer_ancora_action_shortcodes_list', 		'writer_ancora_sc_zoom_reg_shortcodes');
		if (function_exists('writer_ancora_exists_visual_composer') && writer_ancora_exists_visual_composer())
			add_action('writer_ancora_action_shortcodes_list_vc','writer_ancora_sc_zoom_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_zoom id="unique_id" border="none|light|dark"]
*/

if (!function_exists('writer_ancora_sc_zoom')) {	
	function writer_ancora_sc_zoom($atts, $content=null){	
		if (writer_ancora_in_shortcode_blogger()) return '';
		extract(writer_ancora_html_decode(shortcode_atts(array(
			// Individual params
			"effect" => "zoom",
			"src" => "",
			"url" => "",
			"over" => "",
			"align" => "",
			"bg_image" => "",
			"bg_top" => '',
			"bg_bottom" => '',
			"bg_left" => '',
			"bg_right" => '',
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"width" => "",
			"height" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
	
		writer_ancora_enqueue_script( 'writer_ancora-elevate-zoom-script', writer_ancora_get_file_url('js/jquery.elevateZoom-3.0.4.js'), array(), null, true );
	
		$class .= ($class ? ' ' : '') . writer_ancora_get_css_position_as_classes($top, $right, $bottom, $left);
		$css_dim = writer_ancora_get_css_dimensions_from_values($width, $height);
		$css_bg = writer_ancora_get_css_paddings_from_values($bg_top, $bg_right, $bg_bottom, $bg_left);
		$width  = writer_ancora_prepare_css_value($width);
		$height = writer_ancora_prepare_css_value($height);
		if (empty($id)) $id = 'sc_zoom_'.str_replace('.', '', mt_rand());
		$src = $src!='' ? $src : $url;
		if ($src > 0) {
			$attach = wp_get_attachment_image_src( $src, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$src = $attach[0];
		}
		if ($over > 0) {
			$attach = wp_get_attachment_image_src( $over, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$over = $attach[0];
		}
		if ($effect=='lens' && ((int) $width > 0 && writer_ancora_substr($width, -2, 2)=='px') || ((int) $height > 0 && writer_ancora_substr($height, -2, 2)=='px')) {
			if ($src)
				$src = writer_ancora_get_resized_image_url($src, (int) $width > 0 && writer_ancora_substr($width, -2, 2)=='px' ? (int) $width : null, (int) $height > 0 && writer_ancora_substr($height, -2, 2)=='px' ? (int) $height : null);
			if ($over)
				$over = writer_ancora_get_resized_image_url($over, (int) $width > 0 && writer_ancora_substr($width, -2, 2)=='px' ? (int) $width : null, (int) $height > 0 && writer_ancora_substr($height, -2, 2)=='px' ? (int) $height : null);
		}
		if ($bg_image > 0) {
			$attach = wp_get_attachment_image_src( $bg_image, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$bg_image = $attach[0];
		}
		if ($bg_image) {
			$css_bg .= $css . 'background-image: url('.esc_url($bg_image).');';
			$css = $css_dim;
		} else {
			$css .= $css_dim;
		}
		$output = empty($src) 
				? '' 
				: (
					(!empty($bg_image) 
						? '<div class="sc_zoom_wrap'
								. (!empty($class) ? ' '.esc_attr($class) : '')
								. ($align && $align!='none' ? ' align'.esc_attr($align) : '') 
								. '"'
							. (!writer_ancora_param_is_off($animation) ? ' data-animation="'.esc_attr(writer_ancora_get_animation_classes($animation)).'"' : '')
							. ($css_bg!='' ? ' style="'.esc_attr($css_bg).'"' : '') 
							. '>' 
						: '')
					.'<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
						. ' class="sc_zoom' 
								. (empty($bg_image) && !empty($class) ? ' '.esc_attr($class) : '') 
								. (empty($bg_image) && $align && $align!='none' ? ' align'.esc_attr($align) : '')
								. '"'
							. (empty($bg_image) && !writer_ancora_param_is_off($animation) ? ' data-animation="'.esc_attr(writer_ancora_get_animation_classes($animation)).'"' : '')
							. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
							. '>'
							. '<img src="'.esc_url($src).'"' . ($css_dim!='' ? ' style="'.esc_attr($css_dim).'"' : '') . ' data-zoom-image="'.esc_url($over).'" alt="" />'
					. '</div>'
					. (!empty($bg_image) 
						? '</div>' 
						: '')
				);
		return apply_filters('writer_ancora_shortcode_output', $output, 'trx_zoom', $atts, $content);
	}
	writer_ancora_require_shortcode('trx_zoom', 'writer_ancora_sc_zoom');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'writer_ancora_sc_zoom_reg_shortcodes' ) ) {
	//add_action('writer_ancora_action_shortcodes_list', 'writer_ancora_sc_zoom_reg_shortcodes');
	function writer_ancora_sc_zoom_reg_shortcodes() {
	
		writer_ancora_sc_map("trx_zoom", array(
			"title" => esc_html__("Zoom", 'writer-ancora'),
			"desc" => wp_kses( __("Insert the image with zoom/lens effect", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"effect" => array(
					"title" => esc_html__("Effect", 'writer-ancora'),
					"desc" => wp_kses( __("Select effect to display overlapping image", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"value" => "lens",
					"size" => "medium",
					"type" => "switch",
					"options" => array(
						"lens" => esc_html__('Lens', 'writer-ancora'),
						"zoom" => esc_html__('Zoom', 'writer-ancora')
					)
				),
				"url" => array(
					"title" => esc_html__("Main image", 'writer-ancora'),
					"desc" => wp_kses( __("Select or upload main image", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"readonly" => false,
					"value" => "",
					"type" => "media"
				),
				"over" => array(
					"title" => esc_html__("Overlaping image", 'writer-ancora'),
					"desc" => wp_kses( __("Select or upload overlaping image", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"readonly" => false,
					"value" => "",
					"type" => "media"
				),
				"align" => array(
					"title" => esc_html__("Float zoom", 'writer-ancora'),
					"desc" => wp_kses( __("Float zoom to left or right side", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"value" => "",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => writer_ancora_get_sc_param('float')
				), 
				"bg_image" => array(
					"title" => esc_html__("Background image", 'writer-ancora'),
					"desc" => wp_kses( __("Select or upload image or write URL from other site for zoom block background. Attention! If you use background image - specify paddings below from background margins to zoom block in percents!", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"divider" => true,
					"readonly" => false,
					"value" => "",
					"type" => "media"
				),
				"bg_top" => array(
					"title" => esc_html__("Top offset", 'writer-ancora'),
					"desc" => wp_kses( __("Top offset (padding) inside background image to zoom block (in percent). For example: 3%", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"dependency" => array(
						'bg_image' => array('not_empty')
					),
					"value" => "",
					"type" => "text"
				),
				"bg_bottom" => array(
					"title" => esc_html__("Bottom offset", 'writer-ancora'),
					"desc" => wp_kses( __("Bottom offset (padding) inside background image to zoom block (in percent). For example: 3%", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"dependency" => array(
						'bg_image' => array('not_empty')
					),
					"value" => "",
					"type" => "text"
				),
				"bg_left" => array(
					"title" => esc_html__("Left offset", 'writer-ancora'),
					"desc" => wp_kses( __("Left offset (padding) inside background image to zoom block (in percent). For example: 20%", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"dependency" => array(
						'bg_image' => array('not_empty')
					),
					"value" => "",
					"type" => "text"
				),
				"bg_right" => array(
					"title" => esc_html__("Right offset", 'writer-ancora'),
					"desc" => wp_kses( __("Right offset (padding) inside background image to zoom block (in percent). For example: 12%", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"dependency" => array(
						'bg_image' => array('not_empty')
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
if ( !function_exists( 'writer_ancora_sc_zoom_reg_shortcodes_vc' ) ) {
	//add_action('writer_ancora_action_shortcodes_list_vc', 'writer_ancora_sc_zoom_reg_shortcodes_vc');
	function writer_ancora_sc_zoom_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_zoom",
			"name" => esc_html__("Zoom", 'writer-ancora'),
			"description" => wp_kses( __("Insert the image with zoom/lens effect", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
			"category" => esc_html__('Content', 'writer-ancora'),
			'icon' => 'icon_trx_zoom',
			"class" => "trx_sc_single trx_sc_zoom",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "effect",
					"heading" => esc_html__("Effect", 'writer-ancora'),
					"description" => wp_kses( __("Select effect to display overlapping image", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"admin_label" => true,
					"class" => "",
					"std" => "zoom",
					"value" => array(
						esc_html__('Lens', 'writer-ancora') => 'lens',
						esc_html__('Zoom', 'writer-ancora') => 'zoom'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "url",
					"heading" => esc_html__("Main image", 'writer-ancora'),
					"description" => wp_kses( __("Select or upload main image", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				array(
					"param_name" => "over",
					"heading" => esc_html__("Overlaping image", 'writer-ancora'),
					"description" => wp_kses( __("Select or upload overlaping image", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Alignment", 'writer-ancora'),
					"description" => wp_kses( __("Float zoom to left or right side", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(writer_ancora_get_sc_param('float')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "bg_image",
					"heading" => esc_html__("Background image", 'writer-ancora'),
					"description" => wp_kses( __("Select or upload image or write URL from other site for zoom background. Attention! If you use background image - specify paddings below from background margins to video block in percents!", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"group" => esc_html__('Background', 'writer-ancora'),
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				array(
					"param_name" => "bg_top",
					"heading" => esc_html__("Top offset", 'writer-ancora'),
					"description" => wp_kses( __("Top offset (padding) from background image to zoom block (in percent). For example: 3%", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"group" => esc_html__('Background', 'writer-ancora'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "bg_bottom",
					"heading" => esc_html__("Bottom offset", 'writer-ancora'),
					"description" => wp_kses( __("Bottom offset (padding) from background image to zoom block (in percent). For example: 3%", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"group" => esc_html__('Background', 'writer-ancora'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "bg_left",
					"heading" => esc_html__("Left offset", 'writer-ancora'),
					"description" => wp_kses( __("Left offset (padding) from background image to zoom block (in percent). For example: 20%", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"group" => esc_html__('Background', 'writer-ancora'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "bg_right",
					"heading" => esc_html__("Right offset", 'writer-ancora'),
					"description" => wp_kses( __("Right offset (padding) from background image to zoom block (in percent). For example: 12%", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"group" => esc_html__('Background', 'writer-ancora'),
					"class" => "",
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
			)
		) );
		
		class WPBakeryShortCode_Trx_Zoom extends WRITER_ANCORA_VC_ShortCodeSingle {}
	}
}
?>