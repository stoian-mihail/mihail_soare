<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('writer_ancora_sc_title_theme_setup')) {
	add_action( 'writer_ancora_action_before_init_theme', 'writer_ancora_sc_title_theme_setup' );
	function writer_ancora_sc_title_theme_setup() {
		add_action('writer_ancora_action_shortcodes_list', 		'writer_ancora_sc_title_reg_shortcodes');
		if (function_exists('writer_ancora_exists_visual_composer') && writer_ancora_exists_visual_composer())
			add_action('writer_ancora_action_shortcodes_list_vc','writer_ancora_sc_title_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_title id="unique_id" style='regular|iconed' icon='' image='' background="on|off" type="1-6"]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/trx_title]
*/

if (!function_exists('writer_ancora_sc_title')) {	
	function writer_ancora_sc_title($atts, $content=null){	
		if (writer_ancora_in_shortcode_blogger()) return '';
		extract(writer_ancora_html_decode(shortcode_atts(array(
			// Individual params
			"type" => "1",
			"style" => "regular",
			"align" => "",
			"font_weight" => "",
			"font_size" => "",
			"color" => "",
			"icon" => "",
			"image" => "",
			"picture" => "",
			"image_size" => "small",
			"position" => "left",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"width" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$class .= ($class ? ' ' : '') . writer_ancora_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= writer_ancora_get_css_dimensions_from_values($width)
			.($align && $align!='none' && !writer_ancora_param_is_inherit($align) ? 'text-align:' . esc_attr($align) .';' : '')
			.($color ? 'color:' . esc_attr($color) .';' : '')
			.($font_weight && !writer_ancora_param_is_inherit($font_weight) ? 'font-weight:' . esc_attr($font_weight) .';' : '')
			.($font_size   ? 'font-size:' . esc_attr($font_size) .';' : '')
			;
		$type = min(6, max(1, $type));
		if ($picture > 0) {
			$attach = wp_get_attachment_image_src( $picture, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$picture = $attach[0];
		}
		$pic = $style!='iconed' 
			? '' 
			: '<span class="sc_title_icon sc_title_icon_'.esc_attr($position).'  sc_title_icon_'.esc_attr($image_size).($icon!='' && $icon!='none' ? ' '.esc_attr($icon) : '').'"'.'>'
				.($picture ? '<img src="'.esc_url($picture).'" alt="" />' : '')
				.(empty($picture) && $image && $image!='none' ? '<img src="'.esc_url(writer_ancora_strpos($image, 'http:')!==false ? $image : writer_ancora_get_file_url('images/icons/'.($image).'.png')).'" alt="" />' : '')
				.'</span>';
		$output = '<h' . esc_attr($type) . ($id ? ' id="'.esc_attr($id).'"' : '')
				. ' class="sc_title sc_title_'.esc_attr($style)
					.($align && $align!='none' && !writer_ancora_param_is_inherit($align) ? ' sc_align_' . esc_attr($align) : '')
					.(!empty($class) ? ' '.esc_attr($class) : '')
					.'"'
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
				. (!writer_ancora_param_is_off($animation) ? ' data-animation="'.esc_attr(writer_ancora_get_animation_classes($animation)).'"' : '')
				. '>'
					. ($pic)
					. ($style=='divider' ? '<span class="sc_title_divider_before"'.($color ? ' style="background-color: '.esc_attr($color).'"' : '').'></span>' : '')
					. '<span class="content-title">' .do_shortcode($content) . '</span>'
					. ($style=='divider' ? '<div class="sc_title_divider_after"'.($color ? ' style="background-color: '.esc_attr($color).'"' : '').'></div>' : '')
				. '</h' . esc_attr($type) . '>';
		return apply_filters('writer_ancora_shortcode_output', $output, 'trx_title', $atts, $content);
	}
	writer_ancora_require_shortcode('trx_title', 'writer_ancora_sc_title');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'writer_ancora_sc_title_reg_shortcodes' ) ) {
	//add_action('writer_ancora_action_shortcodes_list', 'writer_ancora_sc_title_reg_shortcodes');
	function writer_ancora_sc_title_reg_shortcodes() {
	
		writer_ancora_sc_map("trx_title", array(
			"title" => esc_html__("Title", 'writer-ancora'),
			"desc" => wp_kses( __("Create header tag (1-6 level) with many styles", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"_content_" => array(
					"title" => esc_html__("Title content", 'writer-ancora'),
					"desc" => wp_kses( __("Title content", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"rows" => 4,
					"value" => "",
					"type" => "textarea"
				),
				"type" => array(
					"title" => esc_html__("Title type", 'writer-ancora'),
					"desc" => wp_kses( __("Title type (header level)", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"divider" => true,
					"value" => "1",
					"type" => "select",
					"options" => array(
						'1' => esc_html__('Header 1', 'writer-ancora'),
						'2' => esc_html__('Header 2', 'writer-ancora'),
						'3' => esc_html__('Header 3', 'writer-ancora'),
						'4' => esc_html__('Header 4', 'writer-ancora'),
						'5' => esc_html__('Header 5', 'writer-ancora'),
						'6' => esc_html__('Header 6', 'writer-ancora'),
					)
				),
				"style" => array(
					"title" => esc_html__("Title style", 'writer-ancora'),
					"desc" => wp_kses( __("Title style", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"value" => "regular",
					"type" => "select",
					"options" => array(
						'regular' => esc_html__('Regular', 'writer-ancora'),
						'underline' => esc_html__('Underline', 'writer-ancora'),
						'divider' => esc_html__('Divider', 'writer-ancora'),
						'iconed' => esc_html__('With icon (image)', 'writer-ancora')
					)
				),
				"align" => array(
					"title" => esc_html__("Alignment", 'writer-ancora'),
					"desc" => wp_kses( __("Title text alignment", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"value" => "",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => writer_ancora_get_sc_param('align')
				), 
				"font_size" => array(
					"title" => esc_html__("Font_size", 'writer-ancora'),
					"desc" => wp_kses( __("Custom font size. If empty - use theme default", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"value" => "",
					"type" => "text"
				),
				"font_weight" => array(
					"title" => esc_html__("Font weight", 'writer-ancora'),
					"desc" => wp_kses( __("Custom font weight. If empty or inherit - use theme default", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"value" => "",
					"type" => "select",
					"size" => "medium",
					"options" => array(
						'inherit' => esc_html__('Default', 'writer-ancora'),
						'100' => esc_html__('Thin (100)', 'writer-ancora'),
						'300' => esc_html__('Light (300)', 'writer-ancora'),
						'400' => esc_html__('Normal (400)', 'writer-ancora'),
						'600' => esc_html__('Semibold (600)', 'writer-ancora'),
						'700' => esc_html__('Bold (700)', 'writer-ancora'),
						'900' => esc_html__('Black (900)', 'writer-ancora')
					)
				),
				"color" => array(
					"title" => esc_html__("Title color", 'writer-ancora'),
					"desc" => wp_kses( __("Select color for the title", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"value" => "",
					"type" => "color"
				),
				"icon" => array(
					"title" => esc_html__('Title font icon',  'writer-ancora'),
					"desc" => wp_kses( __("Select font icon for the title from Fontello icons set (if style=iconed)",  'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"dependency" => array(
						'style' => array('iconed')
					),
					"value" => "",
					"type" => "icons",
					"options" => writer_ancora_get_sc_param('icons')
				),
				"image" => array(
					"title" => esc_html__('or image icon',  'writer-ancora'),
					"desc" => wp_kses( __("Select image icon for the title instead icon above (if style=iconed)",  'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"dependency" => array(
						'style' => array('iconed')
					),
					"value" => "",
					"type" => "images",
					"size" => "small",
					"options" => writer_ancora_get_sc_param('images')
				),
				"picture" => array(
					"title" => esc_html__('or URL for image file', 'writer-ancora'),
					"desc" => wp_kses( __("Select or upload image or write URL from other site (if style=iconed)", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"dependency" => array(
						'style' => array('iconed')
					),
					"readonly" => false,
					"value" => "",
					"type" => "media"
				),
				"image_size" => array(
					"title" => esc_html__('Image (picture) size', 'writer-ancora'),
					"desc" => wp_kses( __("Select image (picture) size (if style='iconed')", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"dependency" => array(
						'style' => array('iconed')
					),
					"value" => "small",
					"type" => "checklist",
					"options" => array(
						'small' => esc_html__('Small', 'writer-ancora'),
						'medium' => esc_html__('Medium', 'writer-ancora'),
						'large' => esc_html__('Large', 'writer-ancora')
					)
				),
				"position" => array(
					"title" => esc_html__('Icon (image) position', 'writer-ancora'),
					"desc" => wp_kses( __("Select icon (image) position (if style=iconed)", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"dependency" => array(
						'style' => array('iconed')
					),
					"value" => "left",
					"type" => "checklist",
					"options" => array(
						'top' => esc_html__('Top', 'writer-ancora'),
						'left' => esc_html__('Left', 'writer-ancora')
					)
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
if ( !function_exists( 'writer_ancora_sc_title_reg_shortcodes_vc' ) ) {
	//add_action('writer_ancora_action_shortcodes_list_vc', 'writer_ancora_sc_title_reg_shortcodes_vc');
	function writer_ancora_sc_title_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_title",
			"name" => esc_html__("Title", 'writer-ancora'),
			"description" => wp_kses( __("Create header tag (1-6 level) with many styles", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
			"category" => esc_html__('Content', 'writer-ancora'),
			'icon' => 'icon_trx_title',
			"class" => "trx_sc_single trx_sc_title",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "content",
					"heading" => esc_html__("Title content", 'writer-ancora'),
					"description" => wp_kses( __("Title content", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"class" => "",
					"value" => "",
					"type" => "textarea_html"
				),
				array(
					"param_name" => "type",
					"heading" => esc_html__("Title type", 'writer-ancora'),
					"description" => wp_kses( __("Title type (header level)", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
						esc_html__('Header 1', 'writer-ancora') => '1',
						esc_html__('Header 2', 'writer-ancora') => '2',
						esc_html__('Header 3', 'writer-ancora') => '3',
						esc_html__('Header 4', 'writer-ancora') => '4',
						esc_html__('Header 5', 'writer-ancora') => '5',
						esc_html__('Header 6', 'writer-ancora') => '6'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "style",
					"heading" => esc_html__("Title style", 'writer-ancora'),
					"description" => wp_kses( __("Title style: only text (regular) or with icon/image (iconed)", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
						esc_html__('Regular', 'writer-ancora') => 'regular',
						esc_html__('Underline', 'writer-ancora') => 'underline',
						esc_html__('Divider', 'writer-ancora') => 'divider',
						esc_html__('With icon (image)', 'writer-ancora') => 'iconed'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Alignment", 'writer-ancora'),
					"description" => wp_kses( __("Title text alignment", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(writer_ancora_get_sc_param('align')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "font_size",
					"heading" => esc_html__("Font size", 'writer-ancora'),
					"description" => wp_kses( __("Custom font size. If empty - use theme default", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "font_weight",
					"heading" => esc_html__("Font weight", 'writer-ancora'),
					"description" => wp_kses( __("Custom font weight. If empty or inherit - use theme default", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"class" => "",
					"value" => array(
						esc_html__('Default', 'writer-ancora') => 'inherit',
						esc_html__('Thin (100)', 'writer-ancora') => '100',
						esc_html__('Light (300)', 'writer-ancora') => '300',
						esc_html__('Normal (400)', 'writer-ancora') => '400',
						esc_html__('Semibold (600)', 'writer-ancora') => '600',
						esc_html__('Bold (700)', 'writer-ancora') => '700',
						esc_html__('Black (900)', 'writer-ancora') => '900'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Title color", 'writer-ancora'),
					"description" => wp_kses( __("Select color for the title", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("Title font icon", 'writer-ancora'),
					"description" => wp_kses( __("Select font icon for the title from Fontello icons set (if style=iconed)", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"class" => "",
					"group" => esc_html__('Icon &amp; Image', 'writer-ancora'),
					'dependency' => array(
						'element' => 'style',
						'value' => array('iconed')
					),
					"value" => writer_ancora_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "image",
					"heading" => esc_html__("or image icon", 'writer-ancora'),
					"description" => wp_kses( __("Select image icon for the title instead icon above (if style=iconed)", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"class" => "",
					"group" => esc_html__('Icon &amp; Image', 'writer-ancora'),
					'dependency' => array(
						'element' => 'style',
						'value' => array('iconed')
					),
					"value" => writer_ancora_get_sc_param('images'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "picture",
					"heading" => esc_html__("or select uploaded image", 'writer-ancora'),
					"description" => wp_kses( __("Select or upload image or write URL from other site (if style=iconed)", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"group" => esc_html__('Icon &amp; Image', 'writer-ancora'),
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				array(
					"param_name" => "image_size",
					"heading" => esc_html__("Image (picture) size", 'writer-ancora'),
					"description" => wp_kses( __("Select image (picture) size (if style=iconed)", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"group" => esc_html__('Icon &amp; Image', 'writer-ancora'),
					"class" => "",
					"value" => array(
						esc_html__('Small', 'writer-ancora') => 'small',
						esc_html__('Medium', 'writer-ancora') => 'medium',
						esc_html__('Large', 'writer-ancora') => 'large'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "position",
					"heading" => esc_html__("Icon (image) position", 'writer-ancora'),
					"description" => wp_kses( __("Select icon (image) position (if style=iconed)", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"group" => esc_html__('Icon &amp; Image', 'writer-ancora'),
					"class" => "",
					"std" => "left",
					"value" => array(
						esc_html__('Top', 'writer-ancora') => 'top',
						esc_html__('Left', 'writer-ancora') => 'left'
					),
					"type" => "dropdown"
				),
				writer_ancora_get_vc_param('id'),
				writer_ancora_get_vc_param('class'),
				writer_ancora_get_vc_param('animation'),
				writer_ancora_get_vc_param('css'),
				writer_ancora_get_vc_param('margin_top'),
				writer_ancora_get_vc_param('margin_bottom'),
				writer_ancora_get_vc_param('margin_left'),
				writer_ancora_get_vc_param('margin_right')
			),
			'js_view' => 'VcTrxTextView'
		) );
		
		class WPBakeryShortCode_Trx_Title extends WRITER_ANCORA_VC_ShortCodeSingle {}
	}
}
?>