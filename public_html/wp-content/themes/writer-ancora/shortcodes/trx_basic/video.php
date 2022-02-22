<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('writer_ancora_sc_video_theme_setup')) {
	add_action( 'writer_ancora_action_before_init_theme', 'writer_ancora_sc_video_theme_setup' );
	function writer_ancora_sc_video_theme_setup() {
		add_action('writer_ancora_action_shortcodes_list', 		'writer_ancora_sc_video_reg_shortcodes');
		if (function_exists('writer_ancora_exists_visual_composer') && writer_ancora_exists_visual_composer())
			add_action('writer_ancora_action_shortcodes_list_vc','writer_ancora_sc_video_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

//[trx_video id="unique_id" url="http://player.vimeo.com/video/20245032?title=0&amp;byline=0&amp;portrait=0" width="" height=""]

if (!function_exists('writer_ancora_sc_video')) {	
	function writer_ancora_sc_video($atts, $content = null) {
		if (writer_ancora_in_shortcode_blogger()) return '';
		extract(writer_ancora_html_decode(shortcode_atts(array(
			// Individual params
			"url" => '',
			"src" => '',
			"image" => '',
			"ratio" => '16:9',
			"autoplay" => 'off',
			"align" => '',
			"bg_image" => '',
			"bg_top" => '',
			"bg_bottom" => '',
			"bg_left" => '',
			"bg_right" => '',
			"frame" => "on",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"width" => '',
			"height" => '',
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
	
		if (empty($autoplay)) $autoplay = 'off';
		
		$ratio = empty($ratio) ? "16:9" : str_replace(array('/','\\','-'), ':', $ratio);
		$ratio_parts = explode(':', $ratio);
		if (empty($height) && empty($width)) {
			$width='100%';
			if (writer_ancora_param_is_off(writer_ancora_get_custom_option('substitute_video'))) $height="400";
		}
		$ed = writer_ancora_substr($width, -1);
		if (empty($height) && !empty($width) && $ed!='%') {
			$height = round($width / $ratio_parts[0] * $ratio_parts[1]);
		}
		if (!empty($height) && empty($width)) {
			$width = round($height * $ratio_parts[0] / $ratio_parts[1]);
		}
		$class .= ($class ? ' ' : '') . writer_ancora_get_css_position_as_classes($top, $right, $bottom, $left);
		$css_dim = writer_ancora_get_css_dimensions_from_values($width, $height);
		$css_bg = writer_ancora_get_css_paddings_from_values($bg_top, $bg_right, $bg_bottom, $bg_left);
	
		if ($src=='' && $url=='' && isset($atts[0])) {
			$src = $atts[0];
		}
		$url = $src!='' ? $src : $url;
		if ($image!='' && writer_ancora_param_is_off($image))
			$image = '';
		else {
			if (writer_ancora_param_is_on($autoplay) && is_singular() && !writer_ancora_storage_get('blog_streampage'))
				$image = '';
			else {
				if ($image > 0) {
					$attach = wp_get_attachment_image_src( $image, 'full' );
					if (isset($attach[0]) && $attach[0]!='')
						$image = $attach[0];
				}
				if ($bg_image) {
					$thumb_sizes = writer_ancora_get_thumb_sizes(array(
						'layout' => 'grid_3'
					));
					if (!is_single() || !empty($image)) $image = writer_ancora_get_resized_image_url(empty($image) ? get_the_ID() : $image, $thumb_sizes['w'], $thumb_sizes['h'], null, false, false, false);
				} else
					if (!is_single() || !empty($image)) $image = writer_ancora_get_resized_image_url(empty($image) ? get_the_ID() : $image, $ed!='%' ? $width : null, $height);
				if (empty($image) && (!is_singular() || writer_ancora_storage_get('blog_streampage')))	// || writer_ancora_param_is_off($autoplay)))
					$image = writer_ancora_get_video_cover_image($url);
			}
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
	
		$url = writer_ancora_get_video_player_url($src!='' ? $src : $url);
		
		$video = '<video' . ($id ? ' id="' . esc_attr($id) . '"' : '') 
			. ' class="sc_video'. (!empty($class) ? ' '.esc_attr($class) : '').'"'
			. ' src="' . esc_url($url) . '"'
			. ' width="' . esc_attr($width) . '" height="' . esc_attr($height) . '"' 
			. ' data-width="' . esc_attr($width) . '" data-height="' . esc_attr($height) . '"' 
			. ' data-ratio="'.esc_attr($ratio).'"'
			. ($image ? ' poster="'.esc_attr($image).'" data-image="'.esc_attr($image).'"' : '') 
			. (!writer_ancora_param_is_off($animation) ? ' data-animation="'.esc_attr(writer_ancora_get_animation_classes($animation)).'"' : '')
			. ($align && $align!='none' ? ' data-align="'.esc_attr($align).'"' : '')
			. ($bg_image ? ' data-bg-image="'.esc_attr($bg_image).'"' : '') 
			. ($css_bg!='' ? ' data-style="'.esc_attr($css_bg).'"' : '') 
			. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
			. (($image && writer_ancora_param_is_on(writer_ancora_get_custom_option('substitute_video'))) || (writer_ancora_param_is_on($autoplay) && is_singular() && !writer_ancora_storage_get('blog_streampage')) ? ' autoplay="autoplay"' : '') 
			. ' controls="controls" loop="loop"'
			. '>'
			. '</video>';
		if (writer_ancora_param_is_off(writer_ancora_get_custom_option('substitute_video'))) {
			if (writer_ancora_param_is_on($frame)) $video = writer_ancora_get_video_frame($video, $image, $css, $css_bg);
		} else {
			if ((isset($_GET['vc_editable']) && $_GET['vc_editable']=='true') && (isset($_POST['action']) && $_POST['action']=='vc_load_shortcode')) {
				$video = writer_ancora_substitute_video($video, $width, $height, false);
			}
		}
		if (writer_ancora_get_theme_option('use_mediaelement')=='yes')
			writer_ancora_enqueue_script('wp-mediaelement');
		return apply_filters('writer_ancora_shortcode_output', $video, 'trx_video', $atts, $content);
	}
	writer_ancora_require_shortcode("trx_video", "writer_ancora_sc_video");
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'writer_ancora_sc_video_reg_shortcodes' ) ) {
	//add_action('writer_ancora_action_shortcodes_list', 'writer_ancora_sc_video_reg_shortcodes');
	function writer_ancora_sc_video_reg_shortcodes() {
	
		writer_ancora_sc_map("trx_video", array(
			"title" => esc_html__("Video", 'writer-ancora'),
			"desc" => wp_kses( __("Insert video player", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"url" => array(
					"title" => esc_html__("URL for video file", 'writer-ancora'),
					"desc" => wp_kses( __("Select video from media library or paste URL for video file from other site", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"readonly" => false,
					"value" => "",
					"type" => "media",
					"before" => array(
						'title' => esc_html__('Choose video', 'writer-ancora'),
						'action' => 'media_upload',
						'type' => 'video',
						'multiple' => false,
						'linked_field' => '',
						'captions' => array( 	
							'choose' => esc_html__('Choose video file', 'writer-ancora'),
							'update' => esc_html__('Select video file', 'writer-ancora')
						)
					),
					"after" => array(
						'icon' => 'icon-cancel',
						'action' => 'media_reset'
					)
				),
				"ratio" => array(
					"title" => esc_html__("Ratio", 'writer-ancora'),
					"desc" => wp_kses( __("Ratio of the video", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"value" => "16:9",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => array(
						"16:9" => esc_html__("16:9", 'writer-ancora'),
						"4:3" => esc_html__("4:3", 'writer-ancora')
					)
				),
				"autoplay" => array(
					"title" => esc_html__("Autoplay video", 'writer-ancora'),
					"desc" => wp_kses( __("Autoplay video on page load", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"value" => "off",
					"type" => "switch",
					"options" => writer_ancora_get_sc_param('on_off')
				),
				"align" => array(
					"title" => esc_html__("Align", 'writer-ancora'),
					"desc" => wp_kses( __("Select block alignment", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"value" => "none",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => writer_ancora_get_sc_param('align')
				),
				"image" => array(
					"title" => esc_html__("Cover image", 'writer-ancora'),
					"desc" => wp_kses( __("Select or upload image or write URL from other site for video preview", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"readonly" => false,
					"value" => "",
					"type" => "media"
				),
				"bg_image" => array(
					"title" => esc_html__("Background image", 'writer-ancora'),
					"desc" => wp_kses( __("Select or upload image or write URL from other site for video background. Attention! If you use background image - specify paddings below from background margins to video block in percents!", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"divider" => true,
					"readonly" => false,
					"value" => "",
					"type" => "media"
				),
				"bg_top" => array(
					"title" => esc_html__("Top offset", 'writer-ancora'),
					"desc" => wp_kses( __("Top offset (padding) inside background image to video block (in percent). For example: 3%", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"dependency" => array(
						'bg_image' => array('not_empty')
					),
					"value" => "",
					"type" => "text"
				),
				"bg_bottom" => array(
					"title" => esc_html__("Bottom offset", 'writer-ancora'),
					"desc" => wp_kses( __("Bottom offset (padding) inside background image to video block (in percent). For example: 3%", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"dependency" => array(
						'bg_image' => array('not_empty')
					),
					"value" => "",
					"type" => "text"
				),
				"bg_left" => array(
					"title" => esc_html__("Left offset", 'writer-ancora'),
					"desc" => wp_kses( __("Left offset (padding) inside background image to video block (in percent). For example: 20%", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"dependency" => array(
						'bg_image' => array('not_empty')
					),
					"value" => "",
					"type" => "text"
				),
				"bg_right" => array(
					"title" => esc_html__("Right offset", 'writer-ancora'),
					"desc" => wp_kses( __("Right offset (padding) inside background image to video block (in percent). For example: 12%", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
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
if ( !function_exists( 'writer_ancora_sc_video_reg_shortcodes_vc' ) ) {
	//add_action('writer_ancora_action_shortcodes_list_vc', 'writer_ancora_sc_video_reg_shortcodes_vc');
	function writer_ancora_sc_video_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_video",
			"name" => esc_html__("Video", 'writer-ancora'),
			"description" => wp_kses( __("Insert video player", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
			"category" => esc_html__('Content', 'writer-ancora'),
			'icon' => 'icon_trx_video',
			"class" => "trx_sc_single trx_sc_video",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "url",
					"heading" => esc_html__("URL for video file", 'writer-ancora'),
					"description" => wp_kses( __("Paste URL for video file", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "ratio",
					"heading" => esc_html__("Ratio", 'writer-ancora'),
					"description" => wp_kses( __("Select ratio for display video", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"class" => "",
					"value" => array(
						esc_html__('16:9', 'writer-ancora') => "16:9",
						esc_html__('4:3', 'writer-ancora') => "4:3"
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "autoplay",
					"heading" => esc_html__("Autoplay video", 'writer-ancora'),
					"description" => wp_kses( __("Autoplay video on page load", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"admin_label" => true,
					"class" => "",
					"value" => array("Autoplay" => "on" ),
					"type" => "checkbox"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Alignment", 'writer-ancora'),
					"description" => wp_kses( __("Select block alignment", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"class" => "",
					"value" => array_flip(writer_ancora_get_sc_param('align')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "image",
					"heading" => esc_html__("Cover image", 'writer-ancora'),
					"description" => wp_kses( __("Select or upload image or write URL from other site for video preview", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				array(
					"param_name" => "bg_image",
					"heading" => esc_html__("Background image", 'writer-ancora'),
					"description" => wp_kses( __("Select or upload image or write URL from other site for video background. Attention! If you use background image - specify paddings below from background margins to video block in percents!", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"group" => esc_html__('Background', 'writer-ancora'),
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				array(
					"param_name" => "bg_top",
					"heading" => esc_html__("Top offset", 'writer-ancora'),
					"description" => wp_kses( __("Top offset (padding) from background image to video block (in percent). For example: 3%", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"group" => esc_html__('Background', 'writer-ancora'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "bg_bottom",
					"heading" => esc_html__("Bottom offset", 'writer-ancora'),
					"description" => wp_kses( __("Bottom offset (padding) from background image to video block (in percent). For example: 3%", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"group" => esc_html__('Background', 'writer-ancora'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "bg_left",
					"heading" => esc_html__("Left offset", 'writer-ancora'),
					"description" => wp_kses( __("Left offset (padding) from background image to video block (in percent). For example: 20%", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"group" => esc_html__('Background', 'writer-ancora'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "bg_right",
					"heading" => esc_html__("Right offset", 'writer-ancora'),
					"description" => wp_kses( __("Right offset (padding) from background image to video block (in percent). For example: 12%", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
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
		
		class WPBakeryShortCode_Trx_Video extends WRITER_ANCORA_VC_ShortCodeSingle {}
	}
}
?>