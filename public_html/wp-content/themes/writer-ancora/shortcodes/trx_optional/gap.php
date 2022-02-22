<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('writer_ancora_sc_gap_theme_setup')) {
	add_action( 'writer_ancora_action_before_init_theme', 'writer_ancora_sc_gap_theme_setup' );
	function writer_ancora_sc_gap_theme_setup() {
		add_action('writer_ancora_action_shortcodes_list', 		'writer_ancora_sc_gap_reg_shortcodes');
		if (function_exists('writer_ancora_exists_visual_composer') && writer_ancora_exists_visual_composer())
			add_action('writer_ancora_action_shortcodes_list_vc','writer_ancora_sc_gap_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

//[trx_gap]Fullwidth content[/trx_gap]

if (!function_exists('writer_ancora_sc_gap')) {	
	function writer_ancora_sc_gap($atts, $content = null) {
		if (writer_ancora_in_shortcode_blogger()) return '';
		$output = writer_ancora_gap_start() . do_shortcode($content) . writer_ancora_gap_end();
		return apply_filters('writer_ancora_shortcode_output', $output, 'trx_gap', $atts, $content);
	}
	writer_ancora_require_shortcode("trx_gap", "writer_ancora_sc_gap");
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'writer_ancora_sc_gap_reg_shortcodes' ) ) {
	//add_action('writer_ancora_action_shortcodes_list', 'writer_ancora_sc_gap_reg_shortcodes');
	function writer_ancora_sc_gap_reg_shortcodes() {
	
		writer_ancora_sc_map("trx_gap", array(
			"title" => esc_html__("Gap", 'writer-ancora'),
			"desc" => wp_kses( __("Insert gap (fullwidth area) in the post content. Attention! Use the gap only in the posts (pages) without left or right sidebar", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
			"decorate" => true,
			"container" => true,
			"params" => array(
				"_content_" => array(
					"title" => esc_html__("Gap content", 'writer-ancora'),
					"desc" => wp_kses( __("Gap inner content", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"rows" => 4,
					"value" => "",
					"type" => "textarea"
				)
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'writer_ancora_sc_gap_reg_shortcodes_vc' ) ) {
	//add_action('writer_ancora_action_shortcodes_list_vc', 'writer_ancora_sc_gap_reg_shortcodes_vc');
	function writer_ancora_sc_gap_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_gap",
			"name" => esc_html__("Gap", 'writer-ancora'),
			"description" => wp_kses( __("Insert gap (fullwidth area) in the post content", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
			"category" => esc_html__('Structure', 'writer-ancora'),
			'icon' => 'icon_trx_gap',
			"class" => "trx_sc_collection trx_sc_gap",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => false,
			"params" => array(
				/*
				array(
					"param_name" => "content",
					"heading" => esc_html__("Gap content", 'writer-ancora'),
					"description" => wp_kses( __("Gap inner content", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"class" => "",
					"value" => "",
					"type" => "textarea_html"
				)
				*/
			)
		) );
		
		class WPBakeryShortCode_Trx_Gap extends WRITER_ANCORA_VC_ShortCodeCollection {}
	}
}
?>