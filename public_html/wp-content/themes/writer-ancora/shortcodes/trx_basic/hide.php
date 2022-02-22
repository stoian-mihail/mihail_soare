<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('writer_ancora_sc_hide_theme_setup')) {
	add_action( 'writer_ancora_action_before_init_theme', 'writer_ancora_sc_hide_theme_setup' );
	function writer_ancora_sc_hide_theme_setup() {
		add_action('writer_ancora_action_shortcodes_list', 		'writer_ancora_sc_hide_reg_shortcodes');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_hide selector="unique_id"]
*/

if (!function_exists('writer_ancora_sc_hide')) {	
	function writer_ancora_sc_hide($atts, $content=null){	
		if (writer_ancora_in_shortcode_blogger()) return '';
		extract(writer_ancora_html_decode(shortcode_atts(array(
			// Individual params
			"selector" => "",
			"hide" => "on",
			"delay" => 0
		), $atts)));
		$selector = trim(chop($selector));
		$output = $selector == '' ? '' : 
			'<script type="text/javascript">
				jQuery(document).ready(function() {
					'.($delay>0 ? 'setTimeout(function() {' : '').'
					jQuery("'.esc_attr($selector).'").' . ($hide=='on' ? 'hide' : 'show') . '();
					'.($delay>0 ? '},'.($delay).');' : '').'
				});
			</script>';
		return apply_filters('writer_ancora_shortcode_output', $output, 'trx_hide', $atts, $content);
	}
	writer_ancora_require_shortcode('trx_hide', 'writer_ancora_sc_hide');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'writer_ancora_sc_hide_reg_shortcodes' ) ) {
	//add_action('writer_ancora_action_shortcodes_list', 'writer_ancora_sc_hide_reg_shortcodes');
	function writer_ancora_sc_hide_reg_shortcodes() {
	
		writer_ancora_sc_map("trx_hide", array(
			"title" => esc_html__("Hide/Show any block", 'writer-ancora'),
			"desc" => wp_kses( __("Hide or Show any block with desired CSS-selector", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"selector" => array(
					"title" => esc_html__("Selector", 'writer-ancora'),
					"desc" => wp_kses( __("Any block's CSS-selector", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"value" => "",
					"type" => "text"
				),
				"hide" => array(
					"title" => esc_html__("Hide or Show", 'writer-ancora'),
					"desc" => wp_kses( __("New state for the block: hide or show", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"value" => "yes",
					"size" => "small",
					"options" => writer_ancora_get_sc_param('yes_no'),
					"type" => "switch"
				)
			)
		));
	}
}
?>