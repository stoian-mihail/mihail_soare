<?php
/**
 * Writer Ancora Framework: Theme options custom fields
 *
 * @package	writer_ancora
 * @since	writer_ancora 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'writer_ancora_options_custom_theme_setup' ) ) {
	add_action( 'writer_ancora_action_before_init_theme', 'writer_ancora_options_custom_theme_setup' );
	function writer_ancora_options_custom_theme_setup() {

		if ( is_admin() ) {
			add_action("admin_enqueue_scripts",	'writer_ancora_options_custom_load_scripts');
		}
		
	}
}

// Load required styles and scripts for custom options fields
if ( !function_exists( 'writer_ancora_options_custom_load_scripts' ) ) {
	//add_action("admin_enqueue_scripts", 'writer_ancora_options_custom_load_scripts');
	function writer_ancora_options_custom_load_scripts() {
		writer_ancora_enqueue_script( 'writer_ancora-options-custom-script',	writer_ancora_get_file_url('core/core.options/js/core.options-custom.js'), array(), null, true );	
	}
}


// Show theme specific fields in Post (and Page) options
function writer_ancora_show_custom_field($id, $field, $value) {
	$output = '';
	switch ($field['type']) {
		case 'reviews':
			$output .= '<div class="reviews_block">' . trim(writer_ancora_reviews_get_markup($field, $value, true)) . '</div>';
			break;

		case 'mediamanager':
			wp_enqueue_media( );
			$output .= '<a id="'.esc_attr($id).'" class="button mediamanager"
				data-param="' . esc_attr($id) . '"
				data-choose="'.esc_attr(isset($field['multiple']) && $field['multiple'] ? esc_html__( 'Choose Images', 'writer-ancora') : esc_html__( 'Choose Image', 'writer-ancora')).'"
				data-update="'.esc_attr(isset($field['multiple']) && $field['multiple'] ? esc_html__( 'Add to Gallery', 'writer-ancora') : esc_html__( 'Choose Image', 'writer-ancora')).'"
				data-multiple="'.esc_attr(isset($field['multiple']) && $field['multiple'] ? 'true' : 'false').'"
				data-linked-field="'.esc_attr($field['media_field_id']).'"
				onclick="writer_ancora_show_media_manager(this); return false;"
				>' . (isset($field['multiple']) && $field['multiple'] ? esc_html__( 'Choose Images', 'writer-ancora') : esc_html__( 'Choose Image', 'writer-ancora')) . '</a>';
			break;
	}
	return apply_filters('writer_ancora_filter_show_custom_field', $output, $id, $field, $value);
}
?>