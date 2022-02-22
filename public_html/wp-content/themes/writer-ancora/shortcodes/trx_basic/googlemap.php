<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('writer_ancora_sc_googlemap_theme_setup')) {
	add_action( 'writer_ancora_action_before_init_theme', 'writer_ancora_sc_googlemap_theme_setup' );
	function writer_ancora_sc_googlemap_theme_setup() {
		add_action('writer_ancora_action_shortcodes_list', 		'writer_ancora_sc_googlemap_reg_shortcodes');
		if (function_exists('writer_ancora_exists_visual_composer') && writer_ancora_exists_visual_composer())
			add_action('writer_ancora_action_shortcodes_list_vc','writer_ancora_sc_googlemap_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

//[trx_googlemap id="unique_id" width="width_in_pixels_or_percent" height="height_in_pixels"]
//	[trx_googlemap_marker address="your_address"]
//[/trx_googlemap]

if (!function_exists('writer_ancora_sc_googlemap')) {	
	function writer_ancora_sc_googlemap($atts, $content = null) {
		if (writer_ancora_in_shortcode_blogger()) return '';
		extract(writer_ancora_html_decode(shortcode_atts(array(
			// Individual params
			"zoom" => 16,
			"style" => 'default',
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"animation" => "",
			"width" => "100%",
			"height" => "400",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$class .= ($class ? ' ' : '') . writer_ancora_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= writer_ancora_get_css_dimensions_from_values($width, $height);
		if (empty($id)) $id = 'sc_googlemap_'.str_replace('.', '', mt_rand());
		if (empty($style)) $style = writer_ancora_get_custom_option('googlemap_style');
        $api_key = writer_ancora_get_theme_option('api_google');
        writer_ancora_enqueue_script( 'googlemap', writer_ancora_get_protocol().'://maps.google.com/maps/api/js'.($api_key ? '?key='.$api_key : ''), array(), null, true );
		writer_ancora_enqueue_script( 'writer_ancora-googlemap-script', writer_ancora_get_file_url('js/core.googlemap.js'), array(), null, true );
		writer_ancora_storage_set('sc_googlemap_markers', array());
		$content = do_shortcode($content);
		$output = '';
		$markers = writer_ancora_storage_get('sc_googlemap_markers');
		if (count($markers) == 0) {
			$markers[] = array(
				'title' => writer_ancora_get_custom_option('googlemap_title'),
				'description' => writer_ancora_strmacros(writer_ancora_get_custom_option('googlemap_description')),
				'latlng' => writer_ancora_get_custom_option('googlemap_latlng'),
				'address' => writer_ancora_get_custom_option('googlemap_address'),
				'point' => writer_ancora_get_custom_option('googlemap_marker')
			);
		}
		$output .= '<div id="'.esc_attr($id).'"'
			. ' class="sc_googlemap'. (!empty($class) ? ' '.esc_attr($class) : '').'"'
			. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
			. (!writer_ancora_param_is_off($animation) ? ' data-animation="'.esc_attr(writer_ancora_get_animation_classes($animation)).'"' : '')
			. ' data-zoom="'.esc_attr($zoom).'"'
			. ' data-style="'.esc_attr($style).'"'
			. '>';
		$cnt = 0;
		foreach ($markers as $marker) {
			$cnt++;
			if (empty($marker['id'])) $marker['id'] = $id.'_'.intval($cnt);
			$output .= '<div id="'.esc_attr($marker['id']).'" class="sc_googlemap_marker"'
				. ' data-title="'.esc_attr($marker['title']).'"'
				. ' data-description="'.esc_attr(writer_ancora_strmacros($marker['description'])).'"'
				. ' data-address="'.esc_attr($marker['address']).'"'
				. ' data-latlng="'.esc_attr($marker['latlng']).'"'
				. ' data-point="'.esc_attr($marker['point']).'"'
				. '></div>';
		}
		$output .= '</div>';
		return apply_filters('writer_ancora_shortcode_output', $output, 'trx_googlemap', $atts, $content);
	}
	writer_ancora_require_shortcode("trx_googlemap", "writer_ancora_sc_googlemap");
}


if (!function_exists('writer_ancora_sc_googlemap_marker')) {	
	function writer_ancora_sc_googlemap_marker($atts, $content = null) {
		if (writer_ancora_in_shortcode_blogger()) return '';
		extract(writer_ancora_html_decode(shortcode_atts(array(
			// Individual params
			"title" => "",
			"address" => "",
			"latlng" => "",
			"point" => "",
			// Common params
			"id" => ""
		), $atts)));
		if (!empty($point)) {
			if ($point > 0) {
				$attach = wp_get_attachment_image_src( $point, 'full' );
				if (isset($attach[0]) && $attach[0]!='')
					$point = $attach[0];
			}
		}
		writer_ancora_storage_set_array('sc_googlemap_markers', '', array(
			'id' => $id,
			'title' => $title,
			'description' => do_shortcode($content),
			'latlng' => $latlng,
			'address' => $address,
			'point' => $point ? $point : writer_ancora_get_custom_option('googlemap_marker')
			)
		);
		return '';
	}
	writer_ancora_require_shortcode("trx_googlemap_marker", "writer_ancora_sc_googlemap_marker");
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'writer_ancora_sc_googlemap_reg_shortcodes' ) ) {
	//add_action('writer_ancora_action_shortcodes_list', 'writer_ancora_sc_googlemap_reg_shortcodes');
	function writer_ancora_sc_googlemap_reg_shortcodes() {
	
		writer_ancora_sc_map("trx_googlemap", array(
			"title" => esc_html__("Google map", 'writer-ancora'),
			"desc" => wp_kses( __("Insert Google map with specified markers", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"zoom" => array(
					"title" => esc_html__("Zoom", 'writer-ancora'),
					"desc" => wp_kses( __("Map zoom factor", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"divider" => true,
					"value" => 16,
					"min" => 1,
					"max" => 20,
					"type" => "spinner"
				),
				"style" => array(
					"title" => esc_html__("Map style", 'writer-ancora'),
					"desc" => wp_kses( __("Select map style", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"value" => "default",
					"type" => "checklist",
					"options" => writer_ancora_get_sc_param('googlemap_styles')
				),
				"width" => writer_ancora_shortcodes_width('100%'),
				"height" => writer_ancora_shortcodes_height(240),
				"top" => writer_ancora_get_sc_param('top'),
				"bottom" => writer_ancora_get_sc_param('bottom'),
				"left" => writer_ancora_get_sc_param('left'),
				"right" => writer_ancora_get_sc_param('right'),
				"id" => writer_ancora_get_sc_param('id'),
				"class" => writer_ancora_get_sc_param('class'),
				"animation" => writer_ancora_get_sc_param('animation'),
				"css" => writer_ancora_get_sc_param('css')
			),
			"children" => array(
				"name" => "trx_googlemap_marker",
				"title" => esc_html__("Google map marker", 'writer-ancora'),
				"desc" => wp_kses( __("Google map marker", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
				"decorate" => false,
				"container" => true,
				"params" => array(
					"address" => array(
						"title" => esc_html__("Address", 'writer-ancora'),
						"desc" => wp_kses( __("Address of this marker", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"value" => "",
						"type" => "text"
					),
					"latlng" => array(
						"title" => esc_html__("Latitude and Longitude", 'writer-ancora'),
						"desc" => wp_kses( __("Comma separated marker's coorditanes (instead Address)", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"value" => "",
						"type" => "text"
					),
					"point" => array(
						"title" => esc_html__("URL for marker image file", 'writer-ancora'),
						"desc" => wp_kses( __("Select or upload image or write URL from other site for this marker. If empty - use default marker", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"readonly" => false,
						"value" => "",
						"type" => "media"
					),
					"title" => array(
						"title" => esc_html__("Title", 'writer-ancora'),
						"desc" => wp_kses( __("Title for this marker", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"value" => "",
						"type" => "text"
					),
					"_content_" => array(
						"title" => esc_html__("Description", 'writer-ancora'),
						"desc" => wp_kses( __("Description for this marker", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"rows" => 4,
						"value" => "",
						"type" => "textarea"
					),
					"id" => writer_ancora_get_sc_param('id')
				)
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'writer_ancora_sc_googlemap_reg_shortcodes_vc' ) ) {
	//add_action('writer_ancora_action_shortcodes_list_vc', 'writer_ancora_sc_googlemap_reg_shortcodes_vc');
	function writer_ancora_sc_googlemap_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_googlemap",
			"name" => esc_html__("Google map", 'writer-ancora'),
			"description" => wp_kses( __("Insert Google map with desired address or coordinates", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
			"category" => esc_html__('Content', 'writer-ancora'),
			'icon' => 'icon_trx_googlemap',
			"class" => "trx_sc_collection trx_sc_googlemap",
			"content_element" => true,
			"is_container" => true,
			"as_parent" => array('only' => 'trx_googlemap_marker'),
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "zoom",
					"heading" => esc_html__("Zoom", 'writer-ancora'),
					"description" => wp_kses( __("Map zoom factor", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"admin_label" => true,
					"class" => "",
					"value" => "16",
					"type" => "textfield"
				),
				array(
					"param_name" => "style",
					"heading" => esc_html__("Style", 'writer-ancora'),
					"description" => wp_kses( __("Map custom style", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(writer_ancora_get_sc_param('googlemap_styles')),
					"type" => "dropdown"
				),
				writer_ancora_get_vc_param('id'),
				writer_ancora_get_vc_param('class'),
				writer_ancora_get_vc_param('animation'),
				writer_ancora_get_vc_param('css'),
				writer_ancora_vc_width('100%'),
				writer_ancora_vc_height(240),
				writer_ancora_get_vc_param('margin_top'),
				writer_ancora_get_vc_param('margin_bottom'),
				writer_ancora_get_vc_param('margin_left'),
				writer_ancora_get_vc_param('margin_right')
			)
		) );
		
		vc_map( array(
			"base" => "trx_googlemap_marker",
			"name" => esc_html__("Googlemap marker", 'writer-ancora'),
			"description" => wp_kses( __("Insert new marker into Google map", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
			"class" => "trx_sc_collection trx_sc_googlemap_marker",
			'icon' => 'icon_trx_googlemap_marker',
			//"allowed_container_element" => 'vc_row',
			"show_settings_on_create" => true,
			"content_element" => true,
			"is_container" => true,
			"as_child" => array('only' => 'trx_googlemap'), // Use only|except attributes to limit parent (separate multiple values with comma)
			"params" => array(
				array(
					"param_name" => "address",
					"heading" => esc_html__("Address", 'writer-ancora'),
					"description" => wp_kses( __("Address of this marker", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "latlng",
					"heading" => esc_html__("Latitude and Longitude", 'writer-ancora'),
					"description" => wp_kses( __("Comma separated marker's coorditanes (instead Address)", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title", 'writer-ancora'),
					"description" => wp_kses( __("Title for this marker", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "point",
					"heading" => esc_html__("URL for marker image file", 'writer-ancora'),
					"description" => wp_kses( __("Select or upload image or write URL from other site for this marker. If empty - use default marker", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				writer_ancora_get_vc_param('id')
			)
		) );
		
		class WPBakeryShortCode_Trx_Googlemap extends WRITER_ANCORA_VC_ShortCodeCollection {}
		class WPBakeryShortCode_Trx_Googlemap_Marker extends WRITER_ANCORA_VC_ShortCodeCollection {}
	}
}
?>