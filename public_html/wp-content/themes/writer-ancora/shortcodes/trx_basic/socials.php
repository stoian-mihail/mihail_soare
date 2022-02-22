<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('writer_ancora_sc_socials_theme_setup')) {
	add_action( 'writer_ancora_action_before_init_theme', 'writer_ancora_sc_socials_theme_setup' );
	function writer_ancora_sc_socials_theme_setup() {
		add_action('writer_ancora_action_shortcodes_list', 		'writer_ancora_sc_socials_reg_shortcodes');
		if (function_exists('writer_ancora_exists_visual_composer') && writer_ancora_exists_visual_composer())
			add_action('writer_ancora_action_shortcodes_list_vc','writer_ancora_sc_socials_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_socials id="unique_id" size="small"]
	[trx_social_item name="facebook" url="profile url" icon="path for the icon"]
	[trx_social_item name="twitter" url="profile url"]
[/trx_socials]
*/

if (!function_exists('writer_ancora_sc_socials')) {	
	function writer_ancora_sc_socials($atts, $content=null){	
		if (writer_ancora_in_shortcode_blogger()) return '';
		extract(writer_ancora_html_decode(shortcode_atts(array(
			// Individual params
			"size" => "small",		// tiny | small | medium | large
			"shape" => "square",	// round | square
			"type" => writer_ancora_get_theme_setting('socials_type'),	// icons | images
			"socials" => "",
			"custom" => "no",
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
		writer_ancora_storage_set('sc_social_data', array(
			'icons' => false,
            'type' => $type
            )
        );
		if (!empty($socials)) {
			$allowed = explode('|', $socials);
			$list = array();
			for ($i=0; $i<count($allowed); $i++) {
				$s = explode('=', $allowed[$i]);
				if (!empty($s[1])) {
					$list[] = array(
						'icon'	=> $type=='images' ? writer_ancora_get_socials_url($s[0]) : 'icon-'.trim($s[0]),
						'url'	=> $s[1]
						);
				}
			}
			if (count($list) > 0) writer_ancora_storage_set_array('sc_social_data', 'icons', $list);
		} else if (writer_ancora_param_is_off($custom))
			$content = do_shortcode($content);
		if (writer_ancora_storage_get_array('sc_social_data', 'icons')===false) writer_ancora_storage_set_array('sc_social_data', 'icons', writer_ancora_get_custom_option('social_icons'));
		$output = writer_ancora_prepare_socials(writer_ancora_storage_get_array('sc_social_data', 'icons'));
		$output = $output
			? '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_socials sc_socials_type_' . esc_attr($type) . ' sc_socials_shape_' . esc_attr($shape) . ' sc_socials_size_' . esc_attr($size) . (!empty($class) ? ' '.esc_attr($class) : '') . '"' 
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
				. (!writer_ancora_param_is_off($animation) ? ' data-animation="'.esc_attr(writer_ancora_get_animation_classes($animation)).'"' : '')
				. '>' 
				. ($output)
				. '</div>'
			: '';
		return apply_filters('writer_ancora_shortcode_output', $output, 'trx_socials', $atts, $content);
	}
	writer_ancora_require_shortcode('trx_socials', 'writer_ancora_sc_socials');
}


if (!function_exists('writer_ancora_sc_social_item')) {	
	function writer_ancora_sc_social_item($atts, $content=null){	
		if (writer_ancora_in_shortcode_blogger()) return '';
		extract(writer_ancora_html_decode(shortcode_atts(array(
			// Individual params
			"name" => "",
			"url" => "",
			"icon" => ""
		), $atts)));
		if (!empty($name) && empty($icon)) {
			$type = writer_ancora_storage_get_array('sc_social_data', 'type');
			if ($type=='images') {
				if (file_exists(writer_ancora_get_socials_dir($name.'.png')))
					$icon = writer_ancora_get_socials_url($name.'.png');
			} else
				$icon = 'icon-'.esc_attr($name);
		}
		if (!empty($icon) && !empty($url)) {
			if (writer_ancora_storage_get_array('sc_social_data', 'icons')===false) writer_ancora_storage_set_array('sc_social_data', 'icons', array());
			writer_ancora_storage_set_array2('sc_social_data', 'icons', '', array(
				'icon' => $icon,
				'url' => $url
				)
			);
		}
		return '';
	}
	writer_ancora_require_shortcode('trx_social_item', 'writer_ancora_sc_social_item');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'writer_ancora_sc_socials_reg_shortcodes' ) ) {
	//add_action('writer_ancora_action_shortcodes_list', 'writer_ancora_sc_socials_reg_shortcodes');
	function writer_ancora_sc_socials_reg_shortcodes() {
	
		writer_ancora_sc_map("trx_socials", array(
			"title" => esc_html__("Social icons", 'writer-ancora'),
			"desc" => wp_kses( __("List of social icons (with hovers)", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
			"decorate" => true,
			"container" => false,
			"params" => array(
				"type" => array(
					"title" => esc_html__("Icon's type", 'writer-ancora'),
					"desc" => wp_kses( __("Type of the icons - images or font icons", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"value" => writer_ancora_get_theme_setting('socials_type'),
					"options" => array(
						'icons' => esc_html__('Icons', 'writer-ancora'),
						'images' => esc_html__('Images', 'writer-ancora')
					),
					"type" => "checklist"
				), 
				"size" => array(
					"title" => esc_html__("Icon's size", 'writer-ancora'),
					"desc" => wp_kses( __("Size of the icons", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"value" => "small",
					"options" => writer_ancora_get_sc_param('sizes'),
					"type" => "checklist"
				), 
				"shape" => array(
					"title" => esc_html__("Icon's shape", 'writer-ancora'),
					"desc" => wp_kses( __("Shape of the icons", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"value" => "square",
					"options" => writer_ancora_get_sc_param('shapes'),
					"type" => "checklist"
				), 
				"socials" => array(
					"title" => esc_html__("Manual socials list", 'writer-ancora'),
					"desc" => wp_kses( __("Custom list of social networks. For example: twitter=http://twitter.com/my_profile|facebook=http://facebook.com/my_profile. If empty - use socials from Theme options.", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"divider" => true,
					"value" => "",
					"type" => "text"
				),
				"custom" => array(
					"title" => esc_html__("Custom socials", 'writer-ancora'),
					"desc" => wp_kses( __("Make custom icons from inner shortcodes (prepare it on tabs)", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"divider" => true,
					"value" => "no",
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
			),
			"children" => array(
				"name" => "trx_social_item",
				"title" => esc_html__("Custom social item", 'writer-ancora'),
				"desc" => wp_kses( __("Custom social item: name, profile url and icon url", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
				"decorate" => false,
				"container" => false,
				"params" => array(
					"name" => array(
						"title" => esc_html__("Social name", 'writer-ancora'),
						"desc" => wp_kses( __("Name (slug) of the social network (twitter, facebook, linkedin, etc.)", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"value" => "",
						"type" => "text"
					),
					"url" => array(
						"title" => esc_html__("Your profile URL", 'writer-ancora'),
						"desc" => wp_kses( __("URL of your profile in specified social network", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"value" => "",
						"type" => "text"
					),
					"icon" => array(
						"title" => esc_html__("URL (source) for icon file", 'writer-ancora'),
						"desc" => wp_kses( __("Select or upload image or write URL from other site for the current social icon", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"readonly" => false,
						"value" => "",
						"type" => "media"
					)
				)
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'writer_ancora_sc_socials_reg_shortcodes_vc' ) ) {
	//add_action('writer_ancora_action_shortcodes_list_vc', 'writer_ancora_sc_socials_reg_shortcodes_vc');
	function writer_ancora_sc_socials_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_socials",
			"name" => esc_html__("Social icons", 'writer-ancora'),
			"description" => wp_kses( __("Custom social icons", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
			"category" => esc_html__('Content', 'writer-ancora'),
			'icon' => 'icon_trx_socials',
			"class" => "trx_sc_collection trx_sc_socials",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => true,
			"as_parent" => array('only' => 'trx_social_item'),
			"params" => array_merge(array(
				array(
					"param_name" => "type",
					"heading" => esc_html__("Icon's type", 'writer-ancora'),
					"description" => wp_kses( __("Type of the icons - images or font icons", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"class" => "",
					"std" => writer_ancora_get_theme_setting('socials_type'),
					"value" => array(
						esc_html__('Icons', 'writer-ancora') => 'icons',
						esc_html__('Images', 'writer-ancora') => 'images'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "size",
					"heading" => esc_html__("Icon's size", 'writer-ancora'),
					"description" => wp_kses( __("Size of the icons", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"class" => "",
					"std" => "small",
					"value" => array_flip(writer_ancora_get_sc_param('sizes')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "shape",
					"heading" => esc_html__("Icon's shape", 'writer-ancora'),
					"description" => wp_kses( __("Shape of the icons", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"class" => "",
					"std" => "square",
					"value" => array_flip(writer_ancora_get_sc_param('shapes')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "socials",
					"heading" => esc_html__("Manual socials list", 'writer-ancora'),
					"description" => wp_kses( __("Custom list of social networks. For example: twitter=http://twitter.com/my_profile|facebook=http://facebook.com/my_profile. If empty - use socials from Theme options.", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "custom",
					"heading" => esc_html__("Custom socials", 'writer-ancora'),
					"description" => wp_kses( __("Make custom icons from inner shortcodes (prepare it on tabs)", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"class" => "",
					"value" => array(esc_html__('Custom socials', 'writer-ancora') => 'yes'),
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
			))
		) );
		
		
		vc_map( array(
			"base" => "trx_social_item",
			"name" => esc_html__("Custom social item", 'writer-ancora'),
			"description" => wp_kses( __("Custom social item: name, profile url and icon url", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
			"show_settings_on_create" => true,
			"content_element" => true,
			"is_container" => false,
			'icon' => 'icon_trx_social_item',
			"class" => "trx_sc_single trx_sc_social_item",
			"as_child" => array('only' => 'trx_socials'),
			"as_parent" => array('except' => 'trx_socials'),
			"params" => array(
				array(
					"param_name" => "name",
					"heading" => esc_html__("Social name", 'writer-ancora'),
					"description" => wp_kses( __("Name (slug) of the social network (twitter, facebook, linkedin, etc.)", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "url",
					"heading" => esc_html__("Your profile URL", 'writer-ancora'),
					"description" => wp_kses( __("URL of your profile in specified social network", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("URL (source) for icon file", 'writer-ancora'),
					"description" => wp_kses( __("Select or upload image or write URL from other site for the current social icon", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				)
			)
		) );
		
		class WPBakeryShortCode_Trx_Socials extends WRITER_ANCORA_VC_ShortCodeCollection {}
		class WPBakeryShortCode_Trx_Social_Item extends WRITER_ANCORA_VC_ShortCodeSingle {}
	}
}
?>