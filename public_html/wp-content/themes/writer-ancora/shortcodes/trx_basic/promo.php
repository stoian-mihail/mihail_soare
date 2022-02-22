<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('writer_ancora_sc_promo_theme_setup')) {
	add_action( 'writer_ancora_action_before_init_theme', 'writer_ancora_sc_promo_theme_setup' );
	function writer_ancora_sc_promo_theme_setup() {
		add_action('writer_ancora_action_shortcodes_list', 		'writer_ancora_sc_promo_reg_shortcodes');
		if (function_exists('writer_ancora_exists_visual_composer') && writer_ancora_exists_visual_composer())
			add_action('writer_ancora_action_shortcodes_list_vc','writer_ancora_sc_promo_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */


if (!function_exists('writer_ancora_sc_promo')) {	
	function writer_ancora_sc_promo($atts, $content=null){	
		if (writer_ancora_in_shortcode_blogger()) return '';
		extract(writer_ancora_html_decode(shortcode_atts(array(
			// Individual params
			"align" => "none",
			"image" => "",
			"image_position" => "left",
			"image_width" => "50%",
			"text_margins" => '',
			"text_align" => "left",
			"scheme" => "",
			"title" => "",
			"subtitle" => "",
			"description" => "",
			"link_caption" => esc_html__('Learn more', 'writer-ancora'),
			"link" => '',
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
	
		if ($image > 0) {
			$attach = wp_get_attachment_image_src($image, 'full');
			if (isset($attach[0]) && $attach[0]!='')
				$image = $attach[0];
		}
		if ($image == '') {
			$image_width = '0%';
			$text_margin = 0;
		}
		
		$width  = writer_ancora_prepare_css_value($width);
		$height = writer_ancora_prepare_css_value($height);
		
		$class .= ($class ? ' ' : '') . writer_ancora_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= writer_ancora_get_css_dimensions_from_values($width, $height);
		
		$css_image = (!empty($image) ? 'background-image:url(' . esc_url($image) . ');' : '')
				     . (!empty($image_width) ? 'width:'.trim($image_width).';' : '')
				     . (!empty($image_position) ? $image_position.': 0;' : '');
	
		$css_text = 'width: calc(100%-'.esc_attr($image_width).'); float: '.($image_position=='left' ? 'right' : 'left').'; margin:'.esc_attr($text_margins).';';
		
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
					. ' class="sc_promo' 
						. ($class ? ' ' . esc_attr($class) : '') 
						. ($scheme && !writer_ancora_param_is_off($scheme) && !writer_ancora_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '') 
						. ($align && $align!='none' ? ' align'.esc_attr($align) : '') 
						. (empty($image) ? ' no_image' : '')
						. '"'
					. (!writer_ancora_param_is_off($animation) ? ' data-animation="'.esc_attr(writer_ancora_get_animation_classes($animation)).'"' : '')
					. ($css ? 'style="'.esc_attr($css).'"' : '')
					.'>' 
					. '<div class="sc_promo_image" style="'.esc_attr($css_image).'"></div>'
					. '<div class="sc_promo_block sc_align_'.esc_attr($text_align).'" style="'.esc_attr($css_text).'">'
						. '<div class="sc_promo_inner">'
								. (!empty($subtitle) ? '<h6 class="sc_promo_subtitle sc_item_subtitle">' . trim(writer_ancora_strmacros($subtitle)) . '</h6>' : '')
								. (!empty($title) ? '<h2 class="sc_promo_title sc_item_title">' . trim(writer_ancora_strmacros($title)) . '</h2>' : '')
								. (!empty($description) ? '<div class="sc_promo_descr sc_item_descr">' . trim(writer_ancora_strmacros($description)) . '</div>' : '')
								. (!empty($content) ? '<div class="sc_promo_content">'.do_shortcode($content).'</div>' : '')
								. (!empty($link) ? '<div class="sc_promo_button sc_item_button">'.writer_ancora_do_shortcode('[trx_button link="'.esc_url($link).'" icon="icon-right"]'.esc_html($link_caption).'[/trx_button]').'</div>' : '')
						. '</div>'
					. '</div>'
				. '</div>';
	
	
	
		return apply_filters('writer_ancora_shortcode_output', $output, 'trx_promo', $atts, $content);
	}
	writer_ancora_require_shortcode('trx_promo', 'writer_ancora_sc_promo');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'writer_ancora_sc_promo_reg_shortcodes' ) ) {
	//add_action('writer_ancora_action_shortcodes_list', 'writer_ancora_sc_promo_reg_shortcodes');
	function writer_ancora_sc_promo_reg_shortcodes() {
	
		writer_ancora_sc_map("trx_promo", array(
			"title" => esc_html__("Promo", 'writer-ancora'),
			"desc" => wp_kses( __("Insert promo diagramm in your page (post)", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
			"decorate" => true,
			"container" => false,
			"params" => array(
				"align" => array(
					"title" => esc_html__("Alignment of the promo block", 'writer-ancora'),
					"desc" => wp_kses( __("Align whole promo block to left or right side of the page or parent container", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"value" => "",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => writer_ancora_get_sc_param('float')
				), 
				"image" => array(
					"title" => esc_html__("Image URL", 'writer-ancora'),
					"desc" => wp_kses( __("Select the promo image from the library for this section", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"readonly" => false,
					"value" => "",
					"type" => "media"
				),
				"image_position" => array(
					"title" => esc_html__("Image position", 'writer-ancora'),
					"desc" => wp_kses( __("Place the image to the left or to the right from the text block", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"value" => "left",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => writer_ancora_get_sc_param('hpos')
				),
				"image_width" => array(
					"title" => esc_html__("Image width", 'writer-ancora'),
					"desc" => wp_kses( __("Width (in pixels or percents) of the block with image", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"value" => "50%",
					"type" => "text"
				),
				"text_margins" => array(
					"title" => esc_html__("Text margins", 'writer-ancora'),
					"desc" => wp_kses( __("Margins for the all sides of the text block (Example: 30px 10px 40px 30px = top right botton left OR 30px = equal for all sides)", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"value" => "",
					"type" => "text"
				),
				"text_align" => array(
					"title" => esc_html__("Text alignment", 'writer-ancora'),
					"desc" => wp_kses( __("Align the text inside the block", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"value" => "left",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => writer_ancora_get_sc_param('align')
				),
				"scheme" => array(
					"title" => esc_html__("Color scheme", 'writer-ancora'),
					"desc" => wp_kses( __("Select color scheme for the section with text", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"value" => "",
					"type" => "checklist",
					"options" => writer_ancora_get_sc_param('schemes')
				),
				"title" => array(
					"title" => esc_html__("Title", 'writer-ancora'),
					"desc" => wp_kses( __("Title for the block", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"value" => "",
					"type" => "text"
				),
				"subtitle" => array(
					"title" => esc_html__("Subtitle", 'writer-ancora'),
					"desc" => wp_kses( __("Subtitle for the block", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"value" => "",
					"type" => "text"
				),
				"description" => array(
					"title" => esc_html__("Description", 'writer-ancora'),
					"desc" => wp_kses( __("Short description for the block", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"value" => "",
					"type" => "textarea"
				),
				"link" => array(
					"title" => esc_html__("Button URL", 'writer-ancora'),
					"desc" => wp_kses( __("Link URL for the button at the bottom of the block", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"value" => "",
					"type" => "text"
				),
				"link_caption" => array(
					"title" => esc_html__("Button caption", 'writer-ancora'),
					"desc" => wp_kses( __("Caption for the button at the bottom of the block", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
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
if ( !function_exists( 'writer_ancora_sc_promo_reg_shortcodes_vc' ) ) {
	//add_action('writer_ancora_action_shortcodes_list_vc', 'writer_ancora_sc_promo_reg_shortcodes_vc');
	function writer_ancora_sc_promo_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_promo",
			"name" => esc_html__("Promo", 'writer-ancora'),
			"description" => wp_kses( __("Insert promo block", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
			"category" => esc_html__('Content', 'writer-ancora'),
			'icon' => 'icon_trx_promo',
			"class" => "trx_sc_collection trx_sc_promo",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "align",
					"heading" => esc_html__("Alignment of the promo block", 'writer-ancora'),
					"description" => wp_kses( __("Align whole promo block to left or right side of the page or parent container", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"class" => "",
					"std" => 'none',
					"value" => array_flip(writer_ancora_get_sc_param('float')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "image",
					"heading" => esc_html__("Image URL", 'writer-ancora'),
					"description" => wp_kses( __("Select the promo image from the library for this section", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				array(
					"param_name" => "image_position",
					"heading" => esc_html__("Image position", 'writer-ancora'),
					"description" => wp_kses( __("Place the image to the left or to the right from the text block", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"class" => "",
					"std" => 'left',
					"value" => array_flip(writer_ancora_get_sc_param('hpos')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "image_width",
					"heading" => esc_html__("Image width", 'writer-ancora'),
					"description" => wp_kses( __("Width (in pixels or percents) of the block with image", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"value" => '',
					"std" => "50%",
					"type" => "textfield"
				),
				array(
					"param_name" => "text_margins",
					"heading" => esc_html__("Text margins", 'writer-ancora'),
					"description" => wp_kses( __("Margins for the all sides of the text block (Example: 30px 10px 40px 30px = top right botton left OR 30px = equal for all sides)", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"value" => '',
					"type" => "textfield"
				),
				array(
					"param_name" => "text_align",
					"heading" => esc_html__("Text alignment", 'writer-ancora'),
					"description" => wp_kses( __("Align text to the left or to the right side inside the block", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"class" => "",
					"std" => 'left',
					"value" => array_flip(writer_ancora_get_sc_param('align')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "scheme",
					"heading" => esc_html__("Color scheme", 'writer-ancora'),
					"description" => wp_kses( __("Select color scheme for the section with text", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"class" => "",
					"value" => array_flip(writer_ancora_get_sc_param('schemes')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title", 'writer-ancora'),
					"description" => wp_kses( __("Title for the block", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"admin_label" => true,
					"group" => esc_html__('Captions', 'writer-ancora'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "subtitle",
					"heading" => esc_html__("Subtitle", 'writer-ancora'),
					"description" => wp_kses( __("Subtitle for the block", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"group" => esc_html__('Captions', 'writer-ancora'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "description",
					"heading" => esc_html__("Description", 'writer-ancora'),
					"description" => wp_kses( __("Description for the block", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"group" => esc_html__('Captions', 'writer-ancora'),
					"class" => "",
					"value" => "",
					"type" => "textarea"
				),
				array(
					"param_name" => "link",
					"heading" => esc_html__("Button URL", 'writer-ancora'),
					"description" => wp_kses( __("Link URL for the button at the bottom of the block", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"group" => esc_html__('Captions', 'writer-ancora'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "link_caption",
					"heading" => esc_html__("Button caption", 'writer-ancora'),
					"description" => wp_kses( __("Caption for the button at the bottom of the block", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"group" => esc_html__('Captions', 'writer-ancora'),
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
		
		class WPBakeryShortCode_Trx_Promo extends WRITER_ANCORA_VC_ShortCodeCollection {}
	}
}
?>