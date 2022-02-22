<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('writer_ancora_sc_br_theme_setup')) {
	add_action( 'writer_ancora_action_before_init_theme', 'writer_ancora_sc_br_theme_setup' );
	function writer_ancora_sc_br_theme_setup() {
		add_action('writer_ancora_action_shortcodes_list', 		'writer_ancora_sc_br_reg_shortcodes');
		if (function_exists('writer_ancora_exists_visual_composer') && writer_ancora_exists_visual_composer())
			add_action('writer_ancora_action_shortcodes_list_vc','writer_ancora_sc_br_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_br clear="left|right|both"]
*/

if (!function_exists('writer_ancora_sc_br')) {	
	function writer_ancora_sc_br($atts, $content = null) {
		if (writer_ancora_in_shortcode_blogger()) return '';
		extract(writer_ancora_html_decode(shortcode_atts(array(
			"clear" => ""
		), $atts)));
		$output = in_array($clear, array('left', 'right', 'both', 'all')) 
			? '<div class="clearfix" style="clear:' . str_replace('all', 'both', $clear) . '"></div>'
			: '<br />';
		return apply_filters('writer_ancora_shortcode_output', $output, 'trx_br', $atts, $content);
	}
	writer_ancora_require_shortcode("trx_br", "writer_ancora_sc_br");
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'writer_ancora_sc_br_reg_shortcodes' ) ) {
	//add_action('writer_ancora_action_shortcodes_list', 'writer_ancora_sc_br_reg_shortcodes');
	function writer_ancora_sc_br_reg_shortcodes() {
	
		writer_ancora_sc_map("trx_br", array(
			"title" => esc_html__("Break", 'writer-ancora'),
			"desc" => wp_kses( __("Line break with clear floating (if need)", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"clear" => 	array(
					"title" => esc_html__("Clear floating", 'writer-ancora'),
					"desc" => wp_kses( __("Clear floating (if need)", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"value" => "",
					"type" => "checklist",
					"options" => array(
						'none' => esc_html__('None', 'writer-ancora'),
						'left' => esc_html__('Left', 'writer-ancora'),
						'right' => esc_html__('Right', 'writer-ancora'),
						'both' => esc_html__('Both', 'writer-ancora')
					)
				)
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'writer_ancora_sc_br_reg_shortcodes_vc' ) ) {
	//add_action('writer_ancora_action_shortcodes_list_vc', 'writer_ancora_sc_br_reg_shortcodes_vc');
	function writer_ancora_sc_br_reg_shortcodes_vc() {
/*
		vc_map( array(
			"base" => "trx_br",
			"name" => esc_html__("Line break", 'writer-ancora'),
			"description" => wp_kses( __("Line break or Clear Floating", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
			"category" => esc_html__('Content', 'writer-ancora'),
			'icon' => 'icon_trx_br',
			"class" => "trx_sc_single trx_sc_br",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "clear",
					"heading" => esc_html__("Clear floating", 'writer-ancora'),
					"description" => wp_kses( __("Select clear side (if need)", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"class" => "",
					"value" => "",
					"value" => array(
						esc_html__('None', 'writer-ancora') => 'none',
						esc_html__('Left', 'writer-ancora') => 'left',
						esc_html__('Right', 'writer-ancora') => 'right',
						esc_html__('Both', 'writer-ancora') => 'both'
					),
					"type" => "dropdown"
				)
			)
		) );
		
		class WPBakeryShortCode_Trx_Br extends WRITER_ANCORA_VC_ShortCodeSingle {}
*/
	}
}
?>