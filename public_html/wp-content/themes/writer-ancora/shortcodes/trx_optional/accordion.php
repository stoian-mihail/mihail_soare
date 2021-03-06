<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('writer_ancora_sc_accordion_theme_setup')) {
	add_action( 'writer_ancora_action_before_init_theme', 'writer_ancora_sc_accordion_theme_setup' );
	function writer_ancora_sc_accordion_theme_setup() {
		add_action('writer_ancora_action_shortcodes_list', 		'writer_ancora_sc_accordion_reg_shortcodes');
		if (function_exists('writer_ancora_exists_visual_composer') && writer_ancora_exists_visual_composer())
			add_action('writer_ancora_action_shortcodes_list_vc','writer_ancora_sc_accordion_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_accordion counter="off" initial="1"]
	[trx_accordion_item title="Accordion Title 1"]Lorem ipsum dolor sit amet, consectetur adipisicing elit[/trx_accordion_item]
	[trx_accordion_item title="Accordion Title 2"]Proin dignissim commodo magna at luctus. Nam molestie justo augue, nec eleifend urna laoreet non.[/trx_accordion_item]
	[trx_accordion_item title="Accordion Title 3 with custom icons" icon_closed="icon-check" icon_opened="icon-delete"]Curabitur tristique tempus arcu a placerat.[/trx_accordion_item]
[/trx_accordion]
*/
if (!function_exists('writer_ancora_sc_accordion')) {	
	function writer_ancora_sc_accordion($atts, $content=null){	
		if (writer_ancora_in_shortcode_blogger()) return '';
		extract(writer_ancora_html_decode(shortcode_atts(array(
			// Individual params
			"initial" => "1",
			"counter" => "off",
			"icon_closed" => "icon-down-open",
			"icon_opened" => "icon-up-open",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"animation" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$class .= ($class ? ' ' : '') . writer_ancora_get_css_position_as_classes($top, $right, $bottom, $left);
		$initial = max(0, (int) $initial);
		writer_ancora_storage_set('sc_accordion_data', array(
			'counter' => 0,
            'show_counter' => writer_ancora_param_is_on($counter),
            'icon_closed' => empty($icon_closed) || writer_ancora_param_is_inherit($icon_closed) ? "icon-down-open" : $icon_closed,
            'icon_opened' => empty($icon_opened) || writer_ancora_param_is_inherit($icon_opened) ? "icon-up-open" : $icon_opened
            )
        );
		writer_ancora_enqueue_script('jquery-ui-accordion', false, array('jquery','jquery-ui-core'), null, true);
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_accordion'
					. (!empty($class) ? ' '.esc_attr($class) : '')
					. (writer_ancora_param_is_on($counter) ? ' sc_show_counter' : '') 
				. '"'
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
				. ' data-active="' . ($initial-1) . '"'
				. (!writer_ancora_param_is_off($animation) ? ' data-animation="'.esc_attr(writer_ancora_get_animation_classes($animation)).'"' : '')
				. '>'
				. do_shortcode($content)
				. '</div>';
		return apply_filters('writer_ancora_shortcode_output', $output, 'trx_accordion', $atts, $content);
	}
	writer_ancora_require_shortcode('trx_accordion', 'writer_ancora_sc_accordion');
}


if (!function_exists('writer_ancora_sc_accordion_item')) {	
	function writer_ancora_sc_accordion_item($atts, $content=null) {
		if (writer_ancora_in_shortcode_blogger()) return '';
		extract(writer_ancora_html_decode(shortcode_atts( array(
			// Individual params
			"icon_closed" => "icon-down-open",
			"icon_opened" => "icon-up-open",
			"title" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => ""
		), $atts)));
		writer_ancora_storage_inc_array('sc_accordion_data', 'counter');
		if (empty($icon_closed) || writer_ancora_param_is_inherit($icon_closed)) $icon_closed = writer_ancora_storage_get_array('sc_accordion_data', 'icon_closed', '', "icon-down-open");
		if (empty($icon_opened) || writer_ancora_param_is_inherit($icon_opened)) $icon_opened = writer_ancora_storage_get_array('sc_accordion_data', 'icon_opened', '', "icon-up-open");
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_accordion_item' 
				. (!empty($class) ? ' '.esc_attr($class) : '')
				. (writer_ancora_storage_get_array('sc_accordion_data', 'counter') % 2 == 1 ? ' odd' : ' even') 
				. (writer_ancora_storage_get_array('sc_accordion_data', 'counter') == 1 ? ' first' : '') 
				. '">'
				. '<h5 class="sc_accordion_title">'
				. ($title)
				. (!writer_ancora_param_is_off($icon_closed) ? '<span class="sc_accordion_icon sc_accordion_icon_closed '.esc_attr($icon_closed).'"></span>' : '')
				. (!writer_ancora_param_is_off($icon_opened) ? '<span class="sc_accordion_icon sc_accordion_icon_opened '.esc_attr($icon_opened).'"></span>' : '')
				. (writer_ancora_storage_get_array('sc_accordion_data', 'show_counter') ? '<span class="sc_items_counter">'.(writer_ancora_storage_get_array('sc_accordion_data', 'counter')).'</span>' : '')
				. '</h5>'
				. '<div class="sc_accordion_content"'
					. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
					. '>'
					. do_shortcode($content) 
				. '</div>'
				. '</div>';
		return apply_filters('writer_ancora_shortcode_output', $output, 'trx_accordion_item', $atts, $content);
	}
	writer_ancora_require_shortcode('trx_accordion_item', 'writer_ancora_sc_accordion_item');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'writer_ancora_sc_accordion_reg_shortcodes' ) ) {
	//add_action('writer_ancora_action_shortcodes_list', 'writer_ancora_sc_accordion_reg_shortcodes');
	function writer_ancora_sc_accordion_reg_shortcodes() {
	
		writer_ancora_sc_map("trx_accordion", array(
			"title" => esc_html__("Accordion", 'writer-ancora'),
			"desc" => wp_kses( __("Accordion items", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
			"decorate" => true,
			"container" => false,
			"params" => array(
				"counter" => array(
					"title" => esc_html__("Counter", 'writer-ancora'),
					"desc" => wp_kses( __("Display counter before each accordion title", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"value" => "off",
					"type" => "switch",
					"options" => writer_ancora_get_sc_param('on_off')
				),
				"initial" => array(
					"title" => esc_html__("Initially opened item", 'writer-ancora'),
					"desc" => wp_kses( __("Number of initially opened item", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"value" => 1,
					"min" => 0,
					"type" => "spinner"
				),
				"icon_closed" => array(
					"title" => esc_html__("Icon while closed",  'writer-ancora'),
					"desc" => wp_kses( __('Select icon for the closed accordion item from Fontello icons set',  'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"value" => "",
					"type" => "icons",
					"options" => writer_ancora_get_sc_param('icons')
				),
				"icon_opened" => array(
					"title" => esc_html__("Icon while opened",  'writer-ancora'),
					"desc" => wp_kses( __('Select icon for the opened accordion item from Fontello icons set',  'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"value" => "",
					"type" => "icons",
					"options" => writer_ancora_get_sc_param('icons')
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
				"name" => "trx_accordion_item",
				"title" => esc_html__("Item", 'writer-ancora'),
				"desc" => wp_kses( __("Accordion item", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
				"container" => true,
				"params" => array(
					"title" => array(
						"title" => esc_html__("Accordion item title", 'writer-ancora'),
						"desc" => wp_kses( __("Title for current accordion item", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"value" => "",
						"type" => "text"
					),
					"icon_closed" => array(
						"title" => esc_html__("Icon while closed",  'writer-ancora'),
						"desc" => wp_kses( __('Select icon for the closed accordion item from Fontello icons set',  'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"value" => "",
						"type" => "icons",
						"options" => writer_ancora_get_sc_param('icons')
					),
					"icon_opened" => array(
						"title" => esc_html__("Icon while opened",  'writer-ancora'),
						"desc" => wp_kses( __('Select icon for the opened accordion item from Fontello icons set',  'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"value" => "",
						"type" => "icons",
						"options" => writer_ancora_get_sc_param('icons')
					),
					"_content_" => array(
						"title" => esc_html__("Accordion item content", 'writer-ancora'),
						"desc" => wp_kses( __("Current accordion item content", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"rows" => 4,
						"value" => "",
						"type" => "textarea"
					),
					"id" => writer_ancora_get_sc_param('id'),
					"class" => writer_ancora_get_sc_param('class'),
					"css" => writer_ancora_get_sc_param('css')
				)
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'writer_ancora_sc_accordion_reg_shortcodes_vc' ) ) {
	//add_action('writer_ancora_action_shortcodes_list_vc', 'writer_ancora_sc_accordion_reg_shortcodes_vc');
	function writer_ancora_sc_accordion_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_accordion",
			"name" => esc_html__("Accordion", 'writer-ancora'),
			"description" => wp_kses( __("Accordion items", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
			"category" => esc_html__('Content', 'writer-ancora'),
			'icon' => 'icon_trx_accordion',
			"class" => "trx_sc_collection trx_sc_accordion",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => false,
			"as_parent" => array('only' => 'trx_accordion_item'),	// Use only|except attributes to limit child shortcodes (separate multiple values with comma)
			"params" => array(
				array(
					"param_name" => "counter",
					"heading" => esc_html__("Counter", 'writer-ancora'),
					"description" => wp_kses( __("Display counter before each accordion title", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"class" => "",
					"value" => array("Add item numbers before each element" => "on" ),
					"type" => "checkbox"
				),
				array(
					"param_name" => "initial",
					"heading" => esc_html__("Initially opened item", 'writer-ancora'),
					"description" => wp_kses( __("Number of initially opened item", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"class" => "",
					"value" => 1,
					"type" => "textfield"
				),
				array(
					"param_name" => "icon_closed",
					"heading" => esc_html__("Icon while closed", 'writer-ancora'),
					"description" => wp_kses( __("Select icon for the closed accordion item from Fontello icons set", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"class" => "",
					"value" => writer_ancora_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "icon_opened",
					"heading" => esc_html__("Icon while opened", 'writer-ancora'),
					"description" => wp_kses( __("Select icon for the opened accordion item from Fontello icons set", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"class" => "",
					"value" => writer_ancora_get_sc_param('icons'),
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
			'default_content' => '
				[trx_accordion_item title="' . esc_html__( 'Item 1 title', 'writer-ancora' ) . '"][/trx_accordion_item]
				[trx_accordion_item title="' . esc_html__( 'Item 2 title', 'writer-ancora' ) . '"][/trx_accordion_item]
			',
			"custom_markup" => '
				<div class="wpb_accordion_holder wpb_holder clearfix vc_container_for_children">
					%content%
				</div>
				<div class="tab_controls">
					<button class="add_tab" title="'.esc_attr__("Add item", 'writer-ancora').'">'.esc_html__("Add item", 'writer-ancora').'</button>
				</div>
			',
			'js_view' => 'VcTrxAccordionView'
		) );
		
		
		vc_map( array(
			"base" => "trx_accordion_item",
			"name" => esc_html__("Accordion item", 'writer-ancora'),
			"description" => wp_kses( __("Inner accordion item", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
			"show_settings_on_create" => true,
			"content_element" => true,
			"is_container" => true,
			'icon' => 'icon_trx_accordion_item',
			"as_child" => array('only' => 'trx_accordion'), 	// Use only|except attributes to limit parent (separate multiple values with comma)
			"as_parent" => array('except' => 'trx_accordion'),
			"params" => array(
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title", 'writer-ancora'),
					"description" => wp_kses( __("Title for current accordion item", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "icon_closed",
					"heading" => esc_html__("Icon while closed", 'writer-ancora'),
					"description" => wp_kses( __("Select icon for the closed accordion item from Fontello icons set", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"class" => "",
					"value" => writer_ancora_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "icon_opened",
					"heading" => esc_html__("Icon while opened", 'writer-ancora'),
					"description" => wp_kses( __("Select icon for the opened accordion item from Fontello icons set", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"class" => "",
					"value" => writer_ancora_get_sc_param('icons'),
					"type" => "dropdown"
				),
				writer_ancora_get_vc_param('id'),
				writer_ancora_get_vc_param('class'),
				writer_ancora_get_vc_param('css')
			),
		  'js_view' => 'VcTrxAccordionTabView'
		) );

		class WPBakeryShortCode_Trx_Accordion extends WRITER_ANCORA_VC_ShortCodeAccordion {}
		class WPBakeryShortCode_Trx_Accordion_Item extends WRITER_ANCORA_VC_ShortCodeAccordionItem {}
	}
}
?>