<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('writer_ancora_sc_chat_theme_setup')) {
	add_action( 'writer_ancora_action_before_init_theme', 'writer_ancora_sc_chat_theme_setup' );
	function writer_ancora_sc_chat_theme_setup() {
		add_action('writer_ancora_action_shortcodes_list', 		'writer_ancora_sc_chat_reg_shortcodes');
		if (function_exists('writer_ancora_exists_visual_composer') && writer_ancora_exists_visual_composer())
			add_action('writer_ancora_action_shortcodes_list_vc','writer_ancora_sc_chat_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_chat id="unique_id" link="url" title=""]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/trx_chat]
[trx_chat id="unique_id" link="url" title=""]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/trx_chat]
...
*/

if (!function_exists('writer_ancora_sc_chat')) {	
	function writer_ancora_sc_chat($atts, $content=null){	
		if (writer_ancora_in_shortcode_blogger()) return '';
		extract(writer_ancora_html_decode(shortcode_atts(array(
			// Individual params
			"photo" => "",
			"title" => "",
			"link" => "",
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
		$css .= writer_ancora_get_css_dimensions_from_values($width, $height);
		$title = $title=='' ? $link : $title;
		if (!empty($photo)) {
			if ($photo > 0) {
				$attach = wp_get_attachment_image_src( $photo, 'full' );
				if (isset($attach[0]) && $attach[0]!='')
					$photo = $attach[0];
			}
			$photo = writer_ancora_get_resized_image_tag($photo, 75, 75);
		}
		$content = do_shortcode($content);
		if (writer_ancora_substr($content, 0, 2)!='<p') $content = '<p>' . ($content) . '</p>';
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_chat' . (!empty($class) ? ' '.esc_attr($class) : '') . '"' 
				. (!writer_ancora_param_is_off($animation) ? ' data-animation="'.esc_attr(writer_ancora_get_animation_classes($animation)).'"' : '')
				. ($css ? ' style="'.esc_attr($css).'"' : '') 
				. '>'
					. '<div class="sc_chat_inner">'
						. ($photo ? '<div class="sc_chat_avatar">'.($photo).'</div>' : '')
						. ($title == '' ? '' : ('<div class="sc_chat_box_content"><div class="sc_chat_title">' . ($link!='' ? '<a href="'.esc_url($link).'">' : '') . ($title) . ($link!='' ? '</a>' : '') . '</div>'))
						. '<div class="sc_chat_content">'.($content).'</div>'
					. '</div></div>'
				. '</div>';
		return apply_filters('writer_ancora_shortcode_output', $output, 'trx_chat', $atts, $content);
	}
	writer_ancora_require_shortcode('trx_chat', 'writer_ancora_sc_chat');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'writer_ancora_sc_chat_reg_shortcodes' ) ) {
	//add_action('writer_ancora_action_shortcodes_list', 'writer_ancora_sc_chat_reg_shortcodes');
	function writer_ancora_sc_chat_reg_shortcodes() {
	
		writer_ancora_sc_map("trx_chat", array(
			"title" => esc_html__("Chat", 'writer-ancora'),
			"desc" => wp_kses( __("Chat message", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
			"decorate" => true,
			"container" => true,
			"params" => array(
				"title" => array(
					"title" => esc_html__("Item title", 'writer-ancora'),
					"desc" => wp_kses( __("Chat item title", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"value" => "",
					"type" => "text"
				),
				"photo" => array(
					"title" => esc_html__("Item photo", 'writer-ancora'),
					"desc" => wp_kses( __("Select or upload image or write URL from other site for the item photo (avatar)", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"readonly" => false,
					"value" => "",
					"type" => "media"
				),
				"link" => array(
					"title" => esc_html__("Item link", 'writer-ancora'),
					"desc" => wp_kses( __("Chat item link", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"value" => "",
					"type" => "text"
				),
				"_content_" => array(
					"title" => esc_html__("Chat item content", 'writer-ancora'),
					"desc" => wp_kses( __("Current chat item content", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"rows" => 4,
					"value" => "",
					"type" => "textarea"
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
if ( !function_exists( 'writer_ancora_sc_chat_reg_shortcodes_vc' ) ) {
	//add_action('writer_ancora_action_shortcodes_list_vc', 'writer_ancora_sc_chat_reg_shortcodes_vc');
	function writer_ancora_sc_chat_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_chat",
			"name" => esc_html__("Chat", 'writer-ancora'),
			"description" => wp_kses( __("Chat message", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
			"category" => esc_html__('Content', 'writer-ancora'),
			'icon' => 'icon_trx_chat',
			"class" => "trx_sc_container trx_sc_chat",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "title",
					"heading" => esc_html__("Item title", 'writer-ancora'),
					"description" => wp_kses( __("Title for current chat item", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "photo",
					"heading" => esc_html__("Item photo", 'writer-ancora'),
					"description" => wp_kses( __("Select or upload image or write URL from other site for the item photo (avatar)", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				array(
					"param_name" => "link",
					"heading" => esc_html__("Link URL", 'writer-ancora'),
					"description" => wp_kses( __("URL for the link on chat title click", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				/*
				array(
					"param_name" => "content",
					"heading" => esc_html__("Chat item content", 'writer-ancora'),
					"description" => wp_kses( __("Current chat item content", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"class" => "",
					"value" => "",
					"type" => "textarea_html"
				),
				*/
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
			'js_view' => 'VcTrxTextContainerView'
		
		) );
		
		class WPBakeryShortCode_Trx_Chat extends WRITER_ANCORA_VC_ShortCodeContainer {}
	}
}
?>