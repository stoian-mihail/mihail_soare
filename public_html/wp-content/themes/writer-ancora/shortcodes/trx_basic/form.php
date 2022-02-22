<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('writer_ancora_sc_form_theme_setup')) {
	add_action( 'writer_ancora_action_before_init_theme', 'writer_ancora_sc_form_theme_setup' );
	function writer_ancora_sc_form_theme_setup() {
		add_action('writer_ancora_action_shortcodes_list', 		'writer_ancora_sc_form_reg_shortcodes');
		if (function_exists('writer_ancora_exists_visual_composer') && writer_ancora_exists_visual_composer())
			add_action('writer_ancora_action_shortcodes_list_vc','writer_ancora_sc_form_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_form id="unique_id" title="Contact Form" description="Mauris aliquam habitasse magna."]
*/

if (!function_exists('writer_ancora_sc_form')) {	
	function writer_ancora_sc_form($atts, $content = null) {
		if (writer_ancora_in_shortcode_blogger()) return '';
		extract(writer_ancora_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "form_custom",
			"action" => "",
			"return_url" => "",
			"return_page" => "",
			"align" => "",
			"title" => "",
			"subtitle" => "",
			"description" => "",
			"scheme" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"animation" => "",
			"width" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
	
		if (empty($id)) $id = "sc_form_".str_replace('.', '', mt_rand());
		$class .= ($class ? ' ' : '') . writer_ancora_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= writer_ancora_get_css_dimensions_from_values($width);
	
		writer_ancora_enqueue_messages();	// Load core messages
	
		writer_ancora_storage_set('sc_form_data', array(
			'id' => $id,
            'counter' => 0
            )
        );
	
		if ($style == 'form_custom')
			$content = do_shortcode($content);
		
		$fields = array();
		if (!empty($return_page)) 
			$return_url = get_permalink($return_page);
		if (!empty($return_url))
			$fields[] = array(
				'name' => 'return_url',
				'type' => 'hidden',
				'value' => $return_url
			);

		$output = '<div ' . ($id ? ' id="'.esc_attr($id).'_wrap"' : '')
					. ' class="sc_form_wrap'
					. ($scheme && !writer_ancora_param_is_off($scheme) && !writer_ancora_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '') 
					. '">'
			.'<div ' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_form'
					. ' sc_form_style_'.($style) 
					. (!empty($align) && !writer_ancora_param_is_off($align) ? ' align'.esc_attr($align) : '') 
					. (!empty($class) ? ' '.esc_attr($class) : '') 
					. '"'
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
				. (!writer_ancora_param_is_off($animation) ? ' data-animation="'.esc_attr(writer_ancora_get_animation_classes($animation)).'"' : '')
				. '>'
					. (!empty($subtitle) 
						? '<h6 class="sc_form_subtitle sc_item_subtitle">' . trim(writer_ancora_strmacros($subtitle)) . '</h6>' 
						: '')
					. (!empty($title) 
						? '<h2 class="sc_form_title sc_item_title">' . trim(writer_ancora_strmacros($title)) . '</h2>' 
						: '')
					. (!empty($description) 
						? '<div class="sc_form_descr sc_item_descr">' . trim(writer_ancora_strmacros($description)) . ($style == 1 ? do_shortcode('[trx_socials size="tiny" shape="round"][/trx_socials]') : '') . '</div>' 
						: '');
		
		$output .= writer_ancora_show_post_layout(array(
												'layout' => $style,
												'id' => $id,
												'action' => $action,
												'content' => $content,
												'fields' => $fields,
												'show' => false
												), false);

		$output .= '</div>'
				. '</div>';
	
		return apply_filters('writer_ancora_shortcode_output', $output, 'trx_form', $atts, $content);
	}
	writer_ancora_require_shortcode("trx_form", "writer_ancora_sc_form");
}

if (!function_exists('writer_ancora_sc_form_item')) {	
	function writer_ancora_sc_form_item($atts, $content=null) {
		if (writer_ancora_in_shortcode_blogger()) return '';
		extract(writer_ancora_html_decode(shortcode_atts( array(
			// Individual params
			"type" => "text",
			"name" => "",
			"value" => "",
			"options" => "",
			"align" => "",
			"label" => "",
			"label_position" => "top",
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
	
		writer_ancora_storage_inc_array('sc_form_data', 'counter');
	
		$class .= ($class ? ' ' : '') . writer_ancora_get_css_position_as_classes($top, $right, $bottom, $left);
		if (empty($id)) $id = writer_ancora_storage_get_array('sc_form_data', 'id').'_'.writer_ancora_storage_get_array('sc_form_data', 'counter');
	
		$label = $type!='button' && $type!='submit' && $label ? '<label for="' . esc_attr($id) . '">' . esc_attr($label) . '</label>' : $label;
	
		// Open field container
		$output = '<div class="sc_form_item sc_form_item_'.esc_attr($type)
						.' sc_form_'.($type == 'textarea' ? 'message' : ($type == 'button' || $type == 'submit' ? 'button' : 'field'))
						.' label_'.esc_attr($label_position)
						.($class ? ' '.esc_attr($class) : '')
						.($align && $align!='none' ? ' align'.esc_attr($align) : '')
					.'"'
					. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
					. (!writer_ancora_param_is_off($animation) ? ' data-animation="'.esc_attr(writer_ancora_get_animation_classes($animation)).'"' : '')
					. '>';
		
		// Label top or left
		if ($type!='button' && $type!='submit' && ($label_position=='top' || $label_position=='left'))
			$output .= $label;

		// Field output
		if ($type == 'textarea')

			$output .= '<textarea id="' . esc_attr($id) . '" name="' . esc_attr($name ? $name : $id) . '">' . esc_attr($value) . '</textarea>';

		else if ($type=='button' || $type=='submit')

			$output .= '<button id="' . esc_attr($id) . '">'.($label ? $label : $value).'</button>';

		else if ($type=='radio' || $type=='checkbox') {

			if (!empty($options)) {
				$options = explode('|', $options);
				if (!empty($options)) {
					$i = 0;
					foreach ($options as $v) {
						$i++;
						$parts = explode('=', $v);
						if (count($parts)==1) $parts[1] = $parts[0];
						$output .= '<div class="sc_form_element">'
										. '<input type="'.esc_attr($type) . '"'
											. ' id="' . esc_attr($id.($i>1 ? '_'.intval($i) : '')) . '"'
											. ' name="' . esc_attr($name ? $name : $id) . (count($options) > 1 && $type=='checkbox' ? '[]' : '') . '"'
											. ' value="' . esc_attr(trim(chop($parts[0]))) . '"' 
											. (in_array($parts[0], explode(',', $value)) ? ' checked="checked"' : '') 
										. '>'
										. '<label for="' . esc_attr($id.($i>1 ? '_'.intval($i) : '')) . '">' . trim(chop($parts[1])) . '</label>'
									. '</div>';
					}
				}
			}

		} else if ($type=='select') {

			if (!empty($options)) {
				$options = explode('|', $options);
				if (!empty($options)) {
					$output .= '<div class="sc_form_select_container">'
						. '<select id="' . esc_attr($id) . '" name="' . esc_attr($name ? $name : $id) . '">';
					foreach ($options as $v) {
						$parts = explode('=', $v);
						if (count($parts)==1) $parts[1] = $parts[0];
						$output .= '<option'
										. ' value="' . esc_attr(trim(chop($parts[0]))) . '"' 
										. (in_array($parts[0], explode(',', $value)) ? ' selected="selected"' : '') 
									. '>'
									. trim(chop($parts[1]))
									. '</option>';
					}
					$output .= '</select>'
							. '</div>';
				}
			}

		} else if ($type=='date') {
			writer_ancora_enqueue_script( 'jquery-picker', writer_ancora_get_file_url('/js/picker/picker.js'), array('jquery'), null, true );
			writer_ancora_enqueue_script( 'jquery-picker-date', writer_ancora_get_file_url('/js/picker/picker.date.js'), array('jquery'), null, true );
			$output .= '<div class="sc_form_date_wrap icon-calendar-light">'
						. '<input placeholder="' . esc_attr__('Date', 'writer-ancora') . '" id="' . esc_attr($id) . '" class="js__datepicker" type="text" name="' . esc_attr($name ? $name : $id) . '">'
					. '</div>';

		} else if ($type=='time') {
			writer_ancora_enqueue_script( 'jquery-picker', writer_ancora_get_file_url('/js/picker/picker.js'), array('jquery'), null, true );
			writer_ancora_enqueue_script( 'jquery-picker-time', writer_ancora_get_file_url('/js/picker/picker.time.js'), array('jquery'), null, true );
			$output .= '<div class="sc_form_time_wrap icon-clock-empty">'
						. '<input placeholder="' . esc_attr__('Time', 'writer-ancora') . '" id="' . esc_attr($id) . '" class="js__timepicker" type="text" name="' . esc_attr($name ? $name : $id) . '">'
					. '</div>';
	
		} else

			$output .= '<input type="'.esc_attr($type ? $type : 'text').'" id="' . esc_attr($id) . '" name="' . esc_attr($name ? $name : $id) . '" value="' . esc_attr($value) . '">';

		// Label bottom
		if ($type!='button' && $type!='submit' && $label_position=='bottom')
			$output .= $label;
		
		// Close field container
		$output .= '</div>';
	
		return apply_filters('writer_ancora_shortcode_output', $output, 'trx_form_item', $atts, $content);
	}
	writer_ancora_require_shortcode('trx_form_item', 'writer_ancora_sc_form_item');
}

// AJAX Callback: Send contact form data
if ( !function_exists( 'writer_ancora_sc_form_send' ) ) {
	function writer_ancora_sc_form_send() {
	
		if ( !wp_verify_nonce( writer_ancora_get_value_gp('nonce'), admin_url('admin-ajax.php') ) )
			die();
	
		$response = array('error'=>'');
		if (!($contact_email = writer_ancora_get_theme_option('contact_email')) && !($contact_email = writer_ancora_get_theme_option('admin_email'))) 
			$response['error'] = esc_html__('Unknown admin email!', 'writer-ancora');
		else {
			$type = writer_ancora_substr($_REQUEST['type'], 0, 7);
			parse_str($_POST['data'], $post_data);

			if (in_array($type, array('form_1', 'form_2'))) {
				$user_name	= writer_ancora_strshort($post_data['username'],	100);
				$user_email	= writer_ancora_strshort($post_data['email'],	100);
				$user_subj	= writer_ancora_strshort($post_data['subject'],	100);
				$user_msg	= writer_ancora_strshort($post_data['message'],	writer_ancora_get_theme_option('message_maxlength_contacts'));
		
				$subj = sprintf(esc_html__('Site %s - Contact form message from %s', 'writer-ancora'), get_bloginfo('site_name'), $user_name);
				$msg = "\n".esc_html__('Name:', 'writer-ancora')   .' '.esc_html($user_name)
					.  "\n".esc_html__('E-mail:', 'writer-ancora') .' '.esc_html($user_email)
					.  "\n".esc_html__('Subject:', 'writer-ancora').' '.esc_html($user_subj)
					.  "\n".esc_html__('Message:', 'writer-ancora').' '.esc_html($user_msg);

			} else {

				$subj = sprintf(esc_html__('Site %s - Custom form data', 'writer-ancora'), get_bloginfo('site_name'));
				$msg = '';
				if (is_array($post_data) && count($post_data) > 0) {
					foreach ($post_data as $k=>$v)
						$msg .= "\n{$k}: $v";
				}
			}

			$msg .= "\n\n............. " . get_bloginfo('site_name') . " (" . esc_url(home_url('/')) . ") ............";

			$mail = writer_ancora_get_theme_option('mail_function');
			if (!@$mail($contact_email, $subj, apply_filters('writer_ancora_filter_form_send_message', $msg))) {
				$response['error'] = esc_html__('Error send message!', 'writer-ancora');
			}
		
			echo json_encode($response);
			die();
		}
	}
}

// Show additional fields in the form
if ( !function_exists( 'writer_ancora_sc_form_show_fields' ) ) {
	function writer_ancora_sc_form_show_fields($fields) {
		if (is_array($fields) && count($fields)>0) {
			foreach ($fields as $f) {
				if (in_array($f['type'], array('hidden', 'text'))) {
					echo '<input type="'.esc_attr($f['type']).'" name="'.esc_attr($f['name']).'" value="'.esc_attr($f['value']).'">';
				}
			}
		}
	}
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'writer_ancora_sc_form_reg_shortcodes' ) ) {
	//add_action('writer_ancora_action_shortcodes_list', 'writer_ancora_sc_form_reg_shortcodes');
	function writer_ancora_sc_form_reg_shortcodes() {
	
		$pages = writer_ancora_get_list_pages(false);

		writer_ancora_sc_map("trx_form", array(
			"title" => esc_html__("Form", 'writer-ancora'),
			"desc" => wp_kses( __("Insert form with specified style or with set of custom fields", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
			"decorate" => true,
			"container" => false,
			"params" => array(
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
					"type" => "text"
				),
				"style" => array(
					"title" => esc_html__("Style", 'writer-ancora'),
					"desc" => wp_kses( __("Select style of the form (if 'style' is not equal 'Custom Form' - all tabs 'Field #' are ignored!)", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"divider" => true,
					"value" => 'form_custom',
					"options" => writer_ancora_get_sc_param('forms'),
					"type" => "checklist"
				), 
				"scheme" => array(
					"title" => esc_html__("Color scheme", 'writer-ancora'),
					"desc" => wp_kses( __("Select color scheme for this block", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"divider" => true,
					"value" => "",
					"type" => "checklist",
					"options" => writer_ancora_get_sc_param('schemes')
				),
				"action" => array(
					"title" => esc_html__("Action", 'writer-ancora'),
					"desc" => wp_kses( __("Contact form action (URL to handle form data). If empty - use internal action", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"divider" => true,
					"value" => "",
					"type" => "text"
				),
				"return_page" => array(
					"title" => esc_html__("Page after submit", 'writer-ancora'),
					"desc" => wp_kses( __("Select page to redirect after form submit", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"value" => "0",
					"type" => "select",
					"options" => $pages
				),
				"return_url" => array(
					"title" => esc_html__("URL to redirect", 'writer-ancora'),
					"desc" => wp_kses( __("or specify any URL to redirect after form submit. If both fields are empty - no navigate from current page after submission", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"value" => "",
					"type" => "text"
				),
				"align" => array(
					"title" => esc_html__("Align", 'writer-ancora'),
					"desc" => wp_kses( __("Select form alignment", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"divider" => true,
					"value" => "none",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => writer_ancora_get_sc_param('align')
				),
				"width" => writer_ancora_shortcodes_width(),
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
				"name" => "trx_form_item",
				"title" => esc_html__("Field", 'writer-ancora'),
				"desc" => wp_kses( __("Custom field", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
				"container" => false,
				"params" => array(
					"type" => array(
						"title" => esc_html__("Type", 'writer-ancora'),
						"desc" => wp_kses( __("Type of the custom field", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"value" => "text",
						"type" => "checklist",
						"dir" => "horizontal",
						"options" => writer_ancora_get_sc_param('field_types')
					), 
					"name" => array(
						"title" => esc_html__("Name", 'writer-ancora'),
						"desc" => wp_kses( __("Name of the custom field", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"value" => "",
						"type" => "text"
					),
					"value" => array(
						"title" => esc_html__("Default value", 'writer-ancora'),
						"desc" => wp_kses( __("Default value of the custom field", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"value" => "",
						"type" => "text"
					),
					"options" => array(
						"title" => esc_html__("Options", 'writer-ancora'),
						"desc" => wp_kses( __("Field options. For example: big=My daddy|middle=My brother|small=My little sister", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"dependency" => array(
							'type' => array('radio', 'checkbox', 'select')
						),
						"value" => "",
						"type" => "text"
					),
					"label" => array(
						"title" => esc_html__("Label", 'writer-ancora'),
						"desc" => wp_kses( __("Label for the custom field", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"value" => "",
						"type" => "text"
					),
					"label_position" => array(
						"title" => esc_html__("Label position", 'writer-ancora'),
						"desc" => wp_kses( __("Label position relative to the field", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"value" => "top",
						"type" => "checklist",
						"dir" => "horizontal",
						"options" => writer_ancora_get_sc_param('label_positions')
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
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'writer_ancora_sc_form_reg_shortcodes_vc' ) ) {
	//add_action('writer_ancora_action_shortcodes_list_vc', 'writer_ancora_sc_form_reg_shortcodes_vc');
	function writer_ancora_sc_form_reg_shortcodes_vc() {

		$pages = writer_ancora_get_list_pages(false);
	
		vc_map( array(
			"base" => "trx_form",
			"name" => esc_html__("Form", 'writer-ancora'),
			"description" => wp_kses( __("Insert form with specefied style of with set of custom fields", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
			"category" => esc_html__('Content', 'writer-ancora'),
			'icon' => 'icon_trx_form',
			"class" => "trx_sc_collection trx_sc_form",
			"content_element" => true,
			"is_container" => true,
			"as_parent" => array('except' => 'trx_form'),
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "style",
					"heading" => esc_html__("Style", 'writer-ancora'),
					"description" => wp_kses( __("Select style of the form (if 'style' is not equal 'custom' - all tabs 'Field NN' are ignored!", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"admin_label" => true,
					"class" => "",
					"std" => "form_custom",
					"value" => array_flip(writer_ancora_get_sc_param('forms')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "scheme",
					"heading" => esc_html__("Color scheme", 'writer-ancora'),
					"description" => wp_kses( __("Select color scheme for this block", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"class" => "",
					"value" => array_flip(writer_ancora_get_sc_param('schemes')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "action",
					"heading" => esc_html__("Action", 'writer-ancora'),
					"description" => wp_kses( __("Contact form action (URL to handle form data). If empty - use internal action", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "return_page",
					"heading" => esc_html__("Page after submit", 'writer-ancora'),
					"description" => wp_kses( __("Select page to redirect after form submit", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"class" => "",
					"std" => 0,
					"value" => array_flip($pages),
					"type" => "dropdown"
				),
				array(
					"param_name" => "return_url",
					"heading" => esc_html__("URL to redirect", 'writer-ancora'),
					"description" => wp_kses( __("or specify any URL to redirect after form submit. If both fields are empty - no navigate from current page after submission", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Alignment", 'writer-ancora'),
					"description" => wp_kses( __("Select form alignment", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"class" => "",
					"value" => array_flip(writer_ancora_get_sc_param('align')),
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
				writer_ancora_get_vc_param('id'),
				writer_ancora_get_vc_param('class'),
				writer_ancora_get_vc_param('animation'),
				writer_ancora_get_vc_param('css'),
				writer_ancora_vc_width(),
				writer_ancora_get_vc_param('margin_top'),
				writer_ancora_get_vc_param('margin_bottom'),
				writer_ancora_get_vc_param('margin_left'),
				writer_ancora_get_vc_param('margin_right')
			)
		) );
		
		
		vc_map( array(
			"base" => "trx_form_item",
			"name" => esc_html__("Form item (custom field)", 'writer-ancora'),
			"description" => wp_kses( __("Custom field for the contact form", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
			"class" => "trx_sc_item trx_sc_form_item",
			'icon' => 'icon_trx_form_item',
			//"allowed_container_element" => 'vc_row',
			"show_settings_on_create" => true,
			"content_element" => true,
			"is_container" => false,
			"as_child" => array('only' => 'trx_form,trx_column_item'), // Use only|except attributes to limit parent (separate multiple values with comma)
			"params" => array(
				array(
					"param_name" => "type",
					"heading" => esc_html__("Type", 'writer-ancora'),
					"description" => wp_kses( __("Select type of the custom field", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(writer_ancora_get_sc_param('field_types')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "name",
					"heading" => esc_html__("Name", 'writer-ancora'),
					"description" => wp_kses( __("Name of the custom field", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "value",
					"heading" => esc_html__("Default value", 'writer-ancora'),
					"description" => wp_kses( __("Default value of the custom field", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "options",
					"heading" => esc_html__("Options", 'writer-ancora'),
					"description" => wp_kses( __("Field options. For example: big=My daddy|middle=My brother|small=My little sister", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					'dependency' => array(
						'element' => 'type',
						'value' => array('radio','checkbox','select')
					),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "label",
					"heading" => esc_html__("Label", 'writer-ancora'),
					"description" => wp_kses( __("Label for the custom field", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "label_position",
					"heading" => esc_html__("Label position", 'writer-ancora'),
					"description" => wp_kses( __("Label position relative to the field", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"class" => "",
					"value" => array_flip(writer_ancora_get_sc_param('label_positions')),
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
			)
		) );
		
		class WPBakeryShortCode_Trx_Form extends WRITER_ANCORA_VC_ShortCodeCollection {}
		class WPBakeryShortCode_Trx_Form_Item extends WRITER_ANCORA_VC_ShortCodeItem {}
	}
}
?>