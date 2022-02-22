<?php
/**
 * Writer Ancora Framework: Testimonial support
 *
 * @package	writer_ancora
 * @since	writer_ancora 1.0
 */

// Theme init
if (!function_exists('writer_ancora_testimonial_theme_setup')) {
	add_action( 'writer_ancora_action_before_init_theme', 'writer_ancora_testimonial_theme_setup', 1 );
	function writer_ancora_testimonial_theme_setup() {
	
		// Add item in the admin menu
		add_action('add_meta_boxes',		'writer_ancora_testimonial_add_meta_box');

		// Save data from meta box
		add_action('save_post',				'writer_ancora_testimonial_save_data');

		// Register shortcodes [trx_testimonials] and [trx_testimonials_item]
		add_action('writer_ancora_action_shortcodes_list',		'writer_ancora_testimonials_reg_shortcodes');
		if (function_exists('writer_ancora_exists_visual_composer') && writer_ancora_exists_visual_composer())
			add_action('writer_ancora_action_shortcodes_list_vc','writer_ancora_testimonials_reg_shortcodes_vc');

		// Meta box fields
		writer_ancora_storage_set('testimonial_meta_box', array(
			'id' => 'testimonial-meta-box',
			'title' => esc_html__('Testimonial Details', 'writer-ancora'),
			'page' => 'testimonial',
			'context' => 'normal',
			'priority' => 'high',
			'fields' => array(
				"testimonial_author" => array(
					"title" => esc_html__('Testimonial author',  'writer-ancora'),
					"desc" => wp_kses( __("Name of the testimonial's author", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"class" => "testimonial_author",
					"std" => "",
					"type" => "text"),
				"testimonial_position" => array(
					"title" => esc_html__("Author's position",  'writer-ancora'),
					"desc" => wp_kses( __("Position of the testimonial's author", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"class" => "testimonial_author",
					"std" => "",
					"type" => "text"),
				"testimonial_email" => array(
					"title" => esc_html__("Author's e-mail",  'writer-ancora'),
					"desc" => wp_kses( __("E-mail of the testimonial's author - need to take Gravatar (if registered)", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"class" => "testimonial_email",
					"std" => "",
					"type" => "text"),
				"testimonial_link" => array(
					"title" => esc_html__('Testimonial link',  'writer-ancora'),
					"desc" => wp_kses( __("URL of the testimonial source or author profile page", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"class" => "testimonial_link",
					"std" => "",
					"type" => "text")
				)
			)
		);
		
		// Add supported data types
		writer_ancora_theme_support_pt('testimonial');
		writer_ancora_theme_support_tx('testimonial_group');
		
	}
}


// Add meta box
if (!function_exists('writer_ancora_testimonial_add_meta_box')) {
	//add_action('add_meta_boxes', 'writer_ancora_testimonial_add_meta_box');
	function writer_ancora_testimonial_add_meta_box() {
		$mb = writer_ancora_storage_get('testimonial_meta_box');
		add_meta_box($mb['id'], $mb['title'], 'writer_ancora_testimonial_show_meta_box', $mb['page'], $mb['context'], $mb['priority']);
	}
}

// Callback function to show fields in meta box
if (!function_exists('writer_ancora_testimonial_show_meta_box')) {
	function writer_ancora_testimonial_show_meta_box() {
		global $post;

		// Use nonce for verification
		echo '<input type="hidden" name="meta_box_testimonial_nonce" value="'.esc_attr(wp_create_nonce(admin_url())).'" />';
		
		$data = get_post_meta($post->ID, 'writer_ancora_testimonial_data', true);
	
		$fields = writer_ancora_storage_get_array('testimonial_meta_box', 'fields');
		?>
		<table class="testimonial_area">
		<?php
		if (is_array($fields) && count($fields) > 0) {
			foreach ($fields as $id=>$field) { 
				$meta = isset($data[$id]) ? $data[$id] : '';
				?>
				<tr class="testimonial_field <?php echo esc_attr($field['class']); ?>" valign="top">
					<td><label for="<?php echo esc_attr($id); ?>"><?php echo esc_attr($field['title']); ?></label></td>
					<td><input type="text" name="<?php echo esc_attr($id); ?>" id="<?php echo esc_attr($id); ?>" value="<?php echo esc_attr($meta); ?>" size="30" />
						<br><small><?php echo esc_attr($field['desc']); ?></small></td>
				</tr>
				<?php
			}
		}
		?>
		</table>
		<?php
	}
}


// Save data from meta box
if (!function_exists('writer_ancora_testimonial_save_data')) {
	//add_action('save_post', 'writer_ancora_testimonial_save_data');
	function writer_ancora_testimonial_save_data($post_id) {
		// verify nonce
		if ( !wp_verify_nonce( writer_ancora_get_value_gp('meta_box_testimonial_nonce'), admin_url() ) )
			return $post_id;

		// check autosave
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return $post_id;
		}

		// check permissions
		if ($_POST['post_type']!='testimonial' || !current_user_can('edit_post', $post_id)) {
			return $post_id;
		}

		$data = array();

		$fields = writer_ancora_storage_get_array('testimonial_meta_box', 'fields');

		// Post type specific data handling
		if (is_array($fields) && count($fields) > 0) {
			foreach ($fields as $id=>$field) { 
				if (isset($_POST[$id])) 
					$data[$id] = stripslashes($_POST[$id]);
			}
		}

		update_post_meta($post_id, 'writer_ancora_testimonial_data', $data);
	}
}






// ---------------------------------- [trx_testimonials] ---------------------------------------

/*
[trx_testimonials id="unique_id" style="1|2|3"]
	[trx_testimonials_item user="user_login"]Testimonials text[/trx_testimonials_item]
	[trx_testimonials_item email="" name="" position="" photo="photo_url"]Testimonials text[/trx_testimonials]
[/trx_testimonials]
*/

if (!function_exists('writer_ancora_sc_testimonials')) {
	function writer_ancora_sc_testimonials($atts, $content=null){	
		if (writer_ancora_in_shortcode_blogger()) return '';
		extract(writer_ancora_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "testimonials-1",
			"columns" => 1,
			"slider" => "yes",
			"slides_space" => 0,
			"controls" => "no",
			"interval" => "",
			"autoheight" => "no",
			"align" => "",
			"custom" => "no",
			"ids" => "",
			"cat" => "",
			"count" => "3",
			"offset" => "",
			"orderby" => "date",
			"order" => "desc",
			"scheme" => "",
			"bg_color" => "",
			"bg_image" => "",
			"bg_overlay" => "",
			"bg_texture" => "",
			"title" => "",
			"subtitle" => "",
			"description" => "",
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
	
		if (empty($id)) $id = "sc_testimonials_".str_replace('.', '', mt_rand());
		if (empty($width)) $width = "100%";
		if (!empty($height) && writer_ancora_param_is_on($autoheight)) $autoheight = "no";
		if (empty($interval)) $interval = mt_rand(5000, 10000);
	
		if ($bg_image > 0) {
			$attach = wp_get_attachment_image_src( $bg_image, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$bg_image = $attach[0];
		}
	
		if ($bg_overlay > 0) {
			if ($bg_color=='') $bg_color = writer_ancora_get_scheme_color('bg');
			$rgb = writer_ancora_hex2rgb($bg_color);
		}
		
		$class .= ($class ? ' ' : '') . writer_ancora_get_css_position_as_classes($top, $right, $bottom, $left);

		$ws = writer_ancora_get_css_dimensions_from_values($width);
		$hs = writer_ancora_get_css_dimensions_from_values('', $height);
		$css .= ($hs) . ($ws);

		$count = max(1, (int) $count);
		$columns = max(1, min(12, (int) $columns));
		if (writer_ancora_param_is_off($custom) && $count < $columns) $columns = $count;
		
		writer_ancora_storage_set('sc_testimonials_data', array(
			'id' => $id,
            'style' => $style,
            'columns' => $columns,
            'counter' => 0,
            'slider' => $slider,
            'css_wh' => $ws . $hs
            )
        );

		if (writer_ancora_param_is_on($slider)) writer_ancora_enqueue_slider('swiper');
	
		$output = ($bg_color!='' || $bg_image!='' || $bg_overlay>0 || $bg_texture>0 || writer_ancora_strlen($bg_texture)>2 || ($scheme && !writer_ancora_param_is_off($scheme) && !writer_ancora_param_is_inherit($scheme))
					? '<div class="sc_testimonials_wrap sc_section'
							. ($scheme && !writer_ancora_param_is_off($scheme) && !writer_ancora_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '') 
							. '"'
						.' style="'
							. ($bg_color !== '' && $bg_overlay==0 ? 'background-color:' . esc_attr($bg_color) . ';' : '')
							. ($bg_image !== '' ? 'background-image:url(' . esc_url($bg_image) . ');' : '')
							. '"'
						. (!writer_ancora_param_is_off($animation) ? ' data-animation="'.esc_attr(writer_ancora_get_animation_classes($animation)).'"' : '')
						. '>'
						. '<div class="sc_section_overlay'.($bg_texture>0 ? ' texture_bg_'.esc_attr($bg_texture) : '') . '"'
								. ' style="' . ($bg_overlay>0 ? 'background-color:rgba('.(int)$rgb['r'].','.(int)$rgb['g'].','.(int)$rgb['b'].','.min(1, max(0, $bg_overlay)).');' : '')
									. (writer_ancora_strlen($bg_texture)>2 ? 'background-image:url('.esc_url($bg_texture).');' : '')
									. '"'
									. ($bg_overlay > 0 ? ' data-overlay="'.esc_attr($bg_overlay).'" data-bg_color="'.esc_attr($bg_color).'"' : '')
									. '>' 
					: '')
				. '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_testimonials sc_testimonials_style_'.esc_attr($style)
 					. ' ' . esc_attr(writer_ancora_get_template_property($style, 'container_classes'))
 					. (writer_ancora_param_is_on($slider)
						? ' sc_slider_swiper swiper-slider-container'
							. ' ' . esc_attr(writer_ancora_get_slider_controls_classes($controls))
							. (writer_ancora_param_is_on($autoheight) ? ' sc_slider_height_auto' : '')
							. ($hs ? ' sc_slider_height_fixed' : '')
						: '')
					. (!empty($class) ? ' '.esc_attr($class) : '')
					. ($align!='' && $align!='none' ? ' align'.esc_attr($align) : '')
					. '"'
				. ($bg_color=='' && $bg_image=='' && $bg_overlay==0 && ($bg_texture=='' || $bg_texture=='0') && !writer_ancora_param_is_off($animation) ? ' data-animation="'.esc_attr(writer_ancora_get_animation_classes($animation)).'"' : '')
				. (!empty($width) && writer_ancora_strpos($width, '%')===false ? ' data-old-width="' . esc_attr($width) . '"' : '')
				. (!empty($height) && writer_ancora_strpos($height, '%')===false ? ' data-old-height="' . esc_attr($height) . '"' : '')
				. ((int) $interval > 0 ? ' data-interval="'.esc_attr($interval).'"' : '')
				. ($columns > 1 ? ' data-slides-per-view="' . esc_attr($columns) . '"' : '')
				. ($slides_space > 0 ? ' data-slides-space="' . esc_attr($slides_space) . '"' : '')
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
			. '>'
			. (!empty($subtitle) ? '<h6 class="sc_testimonials_subtitle sc_item_subtitle">' . trim(writer_ancora_strmacros($subtitle)) . '</h6>' : '')
			. (!empty($title) ? '<h2 class="sc_testimonials_title sc_item_title">' . trim(writer_ancora_strmacros($title)) . '</h2>' : '')
			. (!empty($description) ? '<div class="sc_testimonials_descr sc_item_descr">' . trim(writer_ancora_strmacros($description)) . '</div>' : '')
			. (writer_ancora_param_is_on($slider) 
				? '<div class="slides swiper-wrapper">' 
				: ($columns > 1 
					? '<div class="sc_columns columns_wrap">' 
					: '')
				);
	
		$content = do_shortcode($content);
			
		if (writer_ancora_param_is_on($custom) && $content) {
			$output .= $content;
		} else {
			global $post;
		
			if (!empty($ids)) {
				$posts = explode(',', $ids);
				$count = count($posts);
			}
			
			$args = array(
				'post_type' => 'testimonial',
				'post_status' => 'publish',
				'posts_per_page' => $count,
				'ignore_sticky_posts' => true,
				'order' => $order=='asc' ? 'asc' : 'desc',
			);
		
			if ($offset > 0 && empty($ids)) {
				$args['offset'] = $offset;
			}
		
			$args = writer_ancora_query_add_sort_order($args, $orderby, $order);
			$args = writer_ancora_query_add_posts_and_cats($args, $ids, 'testimonial', $cat, 'testimonial_group');
	
			$query = new WP_Query( $args );
	
			$post_number = 0;
				
			while ( $query->have_posts() ) { 
				$query->the_post();
				$post_number++;
				$args = array(
					'layout' => $style,
					'show' => false,
					'number' => $post_number,
					'posts_on_page' => ($count > 0 ? $count : $query->found_posts),
					"descr" => writer_ancora_get_custom_option('post_excerpt_maxlength'.($columns > 1 ? '_masonry' : '')),
					"orderby" => $orderby,
					'content' => false,
					'terms_list' => false,
					'columns_count' => $columns,
					'slider' => $slider,
					'tag_id' => $id ? $id . '_' . $post_number : '',
					'tag_class' => '',
					'tag_animation' => '',
					'tag_css' => '',
					'tag_css_wh' => $ws . $hs
				);
				$post_data = writer_ancora_get_post_data($args);
				$post_data['post_content'] = wpautop($post_data['post_content']);	// Add <p> around text and paragraphs. Need separate call because 'content'=>false (see above)
				$post_meta = get_post_meta($post_data['post_id'], 'writer_ancora_testimonial_data', true);
				$thumb_sizes = writer_ancora_get_thumb_sizes(array('layout' => $style));
				$args['author'] = $post_meta['testimonial_author'];
				$args['position'] = $post_meta['testimonial_position'];
				$args['link'] = !empty($post_meta['testimonial_link']) ? $post_meta['testimonial_link'] : '';	//$post_data['post_link'];
				$args['email'] = $post_meta['testimonial_email'];
				$args['photo'] = $post_data['post_thumb'];
				$mult = writer_ancora_get_retina_multiplier();
				if (empty($args['photo']) && !empty($args['email'])) $args['photo'] = get_avatar($args['email'], $thumb_sizes['w']*$mult);
				$output .= writer_ancora_show_post_layout($args, $post_data);
			}
			wp_reset_postdata();
		}
	
		if (writer_ancora_param_is_on($slider)) {
			$output .= '</div>'
				. '<div class="sc_slider_controls_wrap"><a class="sc_slider_prev" href="#"></a><a class="sc_slider_next" href="#"></a></div>'
				. '<div class="sc_slider_pagination_wrap"></div>';
		} else if ($columns > 1) {
			$output .= '</div>';
		}

		$output .= '</div>'
					. ($bg_color!='' || $bg_image!='' || $bg_overlay>0 || $bg_texture>0 || writer_ancora_strlen($bg_texture)>2
						?  '</div></div>'
						: '');
	
		// Add template specific scripts and styles
		do_action('writer_ancora_action_blog_scripts', $style);

		return apply_filters('writer_ancora_shortcode_output', $output, 'trx_testimonials', $atts, $content);
	}
	writer_ancora_require_shortcode('trx_testimonials', 'writer_ancora_sc_testimonials');
}
	
	
if (!function_exists('writer_ancora_sc_testimonials_item')) {
	function writer_ancora_sc_testimonials_item($atts, $content=null){	
		if (writer_ancora_in_shortcode_blogger()) return '';
		extract(writer_ancora_html_decode(shortcode_atts(array(
			// Individual params
			"author" => "",
			"position" => "",
			"link" => "",
			"photo" => "",
			"email" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
		), $atts)));

		writer_ancora_storage_inc_array('sc_testimonials_data', 'counter');
	
		$id = $id ? $id : (writer_ancora_storage_get_array('sc_testimonials_data', 'id') ? writer_ancora_storage_get_array('sc_testimonials_data', 'id') . '_' . writer_ancora_storage_get_array('sc_testimonials_data', 'counter') : '');
	
		$thumb_sizes = writer_ancora_get_thumb_sizes(array('layout' => writer_ancora_storage_get_array('sc_testimonials_data', 'style')));

		if (empty($photo)) {
			if (!empty($email))
				$mult = writer_ancora_get_retina_multiplier();
				$photo = get_avatar($email, $thumb_sizes['w']*$mult);
		} else {
			if ($photo > 0) {
				$attach = wp_get_attachment_image_src( $photo, 'full' );
				if (isset($attach[0]) && $attach[0]!='')
					$photo = $attach[0];
			}
			$photo = writer_ancora_get_resized_image_tag($photo, $thumb_sizes['w'], $thumb_sizes['h']);
		}

		$post_data = array(
			'post_content' => do_shortcode($content)
		);
		$args = array(
			'layout' => writer_ancora_storage_get_array('sc_testimonials_data', 'style'),
			'number' => writer_ancora_storage_get_array('sc_testimonials_data', 'counter'),
			'columns_count' => writer_ancora_storage_get_array('sc_testimonials_data', 'columns'),
			'slider' => writer_ancora_storage_get_array('sc_testimonials_data', 'slider'),
			'show' => false,
			'descr'  => 0,
			'tag_id' => $id,
			'tag_class' => $class,
			'tag_animation' => '',
			'tag_css' => $css,
			'tag_css_wh' => writer_ancora_storage_get_array('sc_testimonials_data', 'css_wh'),
			'author' => $author,
			'position' => $position,
			'link' => $link,
			'email' => $email,
			'photo' => $photo
		);
		$output = writer_ancora_show_post_layout($args, $post_data);

		return apply_filters('writer_ancora_shortcode_output', $output, 'trx_testimonials_item', $atts, $content);
	}
	writer_ancora_require_shortcode('trx_testimonials_item', 'writer_ancora_sc_testimonials_item');
}
// ---------------------------------- [/trx_testimonials] ---------------------------------------



// Add [trx_testimonials] and [trx_testimonials_item] in the shortcodes list
if (!function_exists('writer_ancora_testimonials_reg_shortcodes')) {
	//add_filter('writer_ancora_action_shortcodes_list',	'writer_ancora_testimonials_reg_shortcodes');
	function writer_ancora_testimonials_reg_shortcodes() {
		if (writer_ancora_storage_isset('shortcodes')) {

			$testimonials_groups = writer_ancora_get_list_terms(false, 'testimonial_group');
			$testimonials_styles = writer_ancora_get_list_templates('testimonials');
			$controls = writer_ancora_get_list_slider_controls();

			writer_ancora_sc_map_before('trx_title', array(
			
				// Testimonials
				"trx_testimonials" => array(
					"title" => esc_html__("Testimonials", 'writer-ancora'),
					"desc" => wp_kses( __("Insert testimonials into post (page)", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
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
							"type" => "textarea"
						),
						"style" => array(
							"title" => esc_html__("Testimonials style", 'writer-ancora'),
							"desc" => wp_kses( __("Select style to display testimonials", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
							"value" => "testimonials-1",
							"type" => "select",
							"options" => $testimonials_styles
						),
						"columns" => array(
							"title" => esc_html__("Columns", 'writer-ancora'),
							"desc" => wp_kses( __("How many columns use to show testimonials", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
							"value" => 1,
							"min" => 1,
							"max" => 6,
							"step" => 1,
							"type" => "spinner"
						),
						"slider" => array(
							"title" => esc_html__("Slider", 'writer-ancora'),
							"desc" => wp_kses( __("Use slider to show testimonials", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
							"value" => "yes",
							"type" => "switch",
							"options" => writer_ancora_get_sc_param('yes_no')
						),
						"controls" => array(
							"title" => esc_html__("Controls", 'writer-ancora'),
							"desc" => wp_kses( __("Slider controls style and position", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
							"dependency" => array(
								'slider' => array('yes')
							),
							"divider" => true,
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $controls
						),
						"slides_space" => array(
							"title" => esc_html__("Space between slides", 'writer-ancora'),
							"desc" => wp_kses( __("Size of space (in px) between slides", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
							"dependency" => array(
								'slider' => array('yes')
							),
							"value" => 0,
							"min" => 0,
							"max" => 100,
							"step" => 10,
							"type" => "spinner"
						),
						"interval" => array(
							"title" => esc_html__("Slides change interval", 'writer-ancora'),
							"desc" => wp_kses( __("Slides change interval (in milliseconds: 1000ms = 1s)", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
							"dependency" => array(
								'slider' => array('yes')
							),
							"value" => 7000,
							"step" => 500,
							"min" => 0,
							"type" => "spinner"
						),
						"autoheight" => array(
							"title" => esc_html__("Autoheight", 'writer-ancora'),
							"desc" => wp_kses( __("Change whole slider's height (make it equal current slide's height)", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
							"dependency" => array(
								'slider' => array('yes')
							),
							"value" => "yes",
							"type" => "switch",
							"options" => writer_ancora_get_sc_param('yes_no')
						),
						"align" => array(
							"title" => esc_html__("Alignment", 'writer-ancora'),
							"desc" => wp_kses( __("Alignment of the testimonials block", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
							"divider" => true,
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => writer_ancora_get_sc_param('align')
						),
						"custom" => array(
							"title" => esc_html__("Custom", 'writer-ancora'),
							"desc" => wp_kses( __("Allow get testimonials from inner shortcodes (custom) or get it from specified group (cat)", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
							"divider" => true,
							"value" => "no",
							"type" => "switch",
							"options" => writer_ancora_get_sc_param('yes_no')
						),
						"cat" => array(
							"title" => esc_html__("Categories", 'writer-ancora'),
							"desc" => wp_kses( __("Select categories (groups) to show testimonials. If empty - select testimonials from any category (group) or from IDs list", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
							"dependency" => array(
								'custom' => array('no')
							),
							"divider" => true,
							"value" => "",
							"type" => "select",
							"style" => "list",
							"multiple" => true,
							"options" => writer_ancora_array_merge(array(0 => esc_html__('- Select category -', 'writer-ancora')), $testimonials_groups)
						),
						"count" => array(
							"title" => esc_html__("Number of posts", 'writer-ancora'),
							"desc" => wp_kses( __("How many posts will be displayed? If used IDs - this parameter ignored.", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => 3,
							"min" => 1,
							"max" => 100,
							"type" => "spinner"
						),
						"offset" => array(
							"title" => esc_html__("Offset before select posts", 'writer-ancora'),
							"desc" => wp_kses( __("Skip posts before select next part.", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => 0,
							"min" => 0,
							"type" => "spinner"
						),
						"orderby" => array(
							"title" => esc_html__("Post order by", 'writer-ancora'),
							"desc" => wp_kses( __("Select desired posts sorting method", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => "date",
							"type" => "select",
							"options" => writer_ancora_get_sc_param('sorting')
						),
						"order" => array(
							"title" => esc_html__("Post order", 'writer-ancora'),
							"desc" => wp_kses( __("Select desired posts order", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => "desc",
							"type" => "switch",
							"size" => "big",
							"options" => writer_ancora_get_sc_param('ordering')
						),
						"ids" => array(
							"title" => esc_html__("Post IDs list", 'writer-ancora'),
							"desc" => wp_kses( __("Comma separated list of posts ID. If set - parameters above are ignored!", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => "",
							"type" => "text"
						),
						"scheme" => array(
							"title" => esc_html__("Color scheme", 'writer-ancora'),
							"desc" => wp_kses( __("Select color scheme for this block", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
							"value" => "",
							"type" => "checklist",
							"options" => writer_ancora_get_sc_param('schemes')
						),
						"bg_color" => array(
							"title" => esc_html__("Background color", 'writer-ancora'),
							"desc" => wp_kses( __("Any background color for this section", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
							"value" => "",
							"type" => "color"
						),
						"bg_image" => array(
							"title" => esc_html__("Background image URL", 'writer-ancora'),
							"desc" => wp_kses( __("Select or upload image or write URL from other site for the background", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
							"readonly" => false,
							"value" => "",
							"type" => "media"
						),
						"bg_overlay" => array(
							"title" => esc_html__("Overlay", 'writer-ancora'),
							"desc" => wp_kses( __("Overlay color opacity (from 0.0 to 1.0)", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
							"min" => "0",
							"max" => "1",
							"step" => "0.1",
							"value" => "0",
							"type" => "spinner"
						),
						"bg_texture" => array(
							"title" => esc_html__("Texture", 'writer-ancora'),
							"desc" => wp_kses( __("Predefined texture style from 1 to 11. 0 - without texture.", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
							"min" => "0",
							"max" => "11",
							"step" => "1",
							"value" => "0",
							"type" => "spinner"
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
					),
					"children" => array(
						"name" => "trx_testimonials_item",
						"title" => esc_html__("Item", 'writer-ancora'),
						"desc" => wp_kses( __("Testimonials item (custom parameters)", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"container" => true,
						"params" => array(
							"author" => array(
								"title" => esc_html__("Author", 'writer-ancora'),
								"desc" => wp_kses( __("Name of the testimonmials author", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
								"value" => "",
								"type" => "text"
							),
							"link" => array(
								"title" => esc_html__("Link", 'writer-ancora'),
								"desc" => wp_kses( __("Link URL to the testimonmials author page", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
								"value" => "",
								"type" => "text"
							),
							"email" => array(
								"title" => esc_html__("E-mail", 'writer-ancora'),
								"desc" => wp_kses( __("E-mail of the testimonmials author (to get gravatar)", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
								"value" => "",
								"type" => "text"
							),
							"photo" => array(
								"title" => esc_html__("Photo", 'writer-ancora'),
								"desc" => wp_kses( __("Select or upload photo of testimonmials author or write URL of photo from other site", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
								"value" => "",
								"type" => "media"
							),
							"_content_" => array(
								"title" => esc_html__("Testimonials text", 'writer-ancora'),
								"desc" => wp_kses( __("Current testimonials text", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
								"divider" => true,
								"rows" => 4,
								"value" => "",
								"type" => "textarea"
							),
							"id" => writer_ancora_get_sc_param('id'),
							"class" => writer_ancora_get_sc_param('class'),
							"css" => writer_ancora_get_sc_param('css')
						)
					)
				)

			));
		}
	}
}


// Add [trx_testimonials] and [trx_testimonials_item] in the VC shortcodes list
if (!function_exists('writer_ancora_testimonials_reg_shortcodes_vc')) {
	//add_filter('writer_ancora_action_shortcodes_list_vc',	'writer_ancora_testimonials_reg_shortcodes_vc');
	function writer_ancora_testimonials_reg_shortcodes_vc() {

		$testimonials_groups = writer_ancora_get_list_terms(false, 'testimonial_group');
		$testimonials_styles = writer_ancora_get_list_templates('testimonials');
		$controls			 = writer_ancora_get_list_slider_controls();
			
		// Testimonials			
		vc_map( array(
				"base" => "trx_testimonials",
				"name" => esc_html__("Testimonials", 'writer-ancora'),
				"description" => wp_kses( __("Insert testimonials slider", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
				"category" => esc_html__('Content', 'writer-ancora'),
				'icon' => 'icon_trx_testimonials',
				"class" => "trx_sc_columns trx_sc_testimonials",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"as_parent" => array('only' => 'trx_testimonials_item'),
				"params" => array(
					array(
						"param_name" => "style",
						"heading" => esc_html__("Testimonials style", 'writer-ancora'),
						"description" => wp_kses( __("Select style to display testimonials", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"class" => "",
						"admin_label" => true,
						"value" => array_flip($testimonials_styles),
						"type" => "dropdown"
					),
					array(
						"param_name" => "slider",
						"heading" => esc_html__("Slider", 'writer-ancora'),
						"description" => wp_kses( __("Use slider to show testimonials", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"admin_label" => true,
						"group" => esc_html__('Slider', 'writer-ancora'),
						"class" => "",
						"std" => "yes",
						"value" => array_flip(writer_ancora_get_sc_param('yes_no')),
						"type" => "dropdown"
					),
					array(
						"param_name" => "controls",
						"heading" => esc_html__("Controls", 'writer-ancora'),
						"description" => wp_kses( __("Slider controls style and position", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"admin_label" => true,
						"group" => esc_html__('Slider', 'writer-ancora'),
						'dependency' => array(
							'element' => 'slider',
							'value' => 'yes'
						),
						"class" => "",
						"std" => "no",
						"value" => array_flip($controls),
						"type" => "dropdown"
					),
					array(
						"param_name" => "slides_space",
						"heading" => esc_html__("Space between slides", 'writer-ancora'),
						"description" => wp_kses( __("Size of space (in px) between slides", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"admin_label" => true,
						"group" => esc_html__('Slider', 'writer-ancora'),
						'dependency' => array(
							'element' => 'slider',
							'value' => 'yes'
						),
						"class" => "",
						"value" => "0",
						"type" => "textfield"
					),
					array(
						"param_name" => "interval",
						"heading" => esc_html__("Slides change interval", 'writer-ancora'),
						"description" => wp_kses( __("Slides change interval (in milliseconds: 1000ms = 1s)", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"group" => esc_html__('Slider', 'writer-ancora'),
						'dependency' => array(
							'element' => 'slider',
							'value' => 'yes'
						),
						"class" => "",
						"value" => "7000",
						"type" => "textfield"
					),
					array(
						"param_name" => "autoheight",
						"heading" => esc_html__("Autoheight", 'writer-ancora'),
						"description" => wp_kses( __("Change whole slider's height (make it equal current slide's height)", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"group" => esc_html__('Slider', 'writer-ancora'),
						'dependency' => array(
							'element' => 'slider',
							'value' => 'yes'
						),
						"class" => "",
						"value" => array("Autoheight" => "yes" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "align",
						"heading" => esc_html__("Alignment", 'writer-ancora'),
						"description" => wp_kses( __("Alignment of the testimonials block", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"class" => "",
						"value" => array_flip(writer_ancora_get_sc_param('align')),
						"type" => "dropdown"
					),
					array(
						"param_name" => "custom",
						"heading" => esc_html__("Custom", 'writer-ancora'),
						"description" => wp_kses( __("Allow get testimonials from inner shortcodes (custom) or get it from specified group (cat)", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"class" => "",
						"value" => array("Custom slides" => "yes" ),
						"type" => "checkbox"
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
						"param_name" => "cat",
						"heading" => esc_html__("Categories", 'writer-ancora'),
						"description" => wp_kses( __("Select categories (groups) to show testimonials. If empty - select testimonials from any category (group) or from IDs list", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"group" => esc_html__('Query', 'writer-ancora'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => array_flip(writer_ancora_array_merge(array(0 => esc_html__('- Select category -', 'writer-ancora')), $testimonials_groups)),
						"type" => "dropdown"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", 'writer-ancora'),
						"description" => wp_kses( __("How many columns use to show testimonials", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"group" => esc_html__('Query', 'writer-ancora'),
						"admin_label" => true,
						"class" => "",
						"value" => "1",
						"type" => "textfield"
					),
					array(
						"param_name" => "count",
						"heading" => esc_html__("Number of posts", 'writer-ancora'),
						"description" => wp_kses( __("How many posts will be displayed? If used IDs - this parameter ignored.", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"group" => esc_html__('Query', 'writer-ancora'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => "3",
						"type" => "textfield"
					),
					array(
						"param_name" => "offset",
						"heading" => esc_html__("Offset before select posts", 'writer-ancora'),
						"description" => wp_kses( __("Skip posts before select next part.", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"group" => esc_html__('Query', 'writer-ancora'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => "0",
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => esc_html__("Post sorting", 'writer-ancora'),
						"description" => wp_kses( __("Select desired posts sorting method", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"group" => esc_html__('Query', 'writer-ancora'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => array_flip(writer_ancora_get_sc_param('sorting')),
						"type" => "dropdown"
					),
					array(
						"param_name" => "order",
						"heading" => esc_html__("Post order", 'writer-ancora'),
						"description" => wp_kses( __("Select desired posts order", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"group" => esc_html__('Query', 'writer-ancora'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => array_flip(writer_ancora_get_sc_param('ordering')),
						"type" => "dropdown"
					),
					array(
						"param_name" => "ids",
						"heading" => esc_html__("Post IDs list", 'writer-ancora'),
						"description" => wp_kses( __("Comma separated list of posts ID. If set - parameters above are ignored!", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"group" => esc_html__('Query', 'writer-ancora'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "scheme",
						"heading" => esc_html__("Color scheme", 'writer-ancora'),
						"description" => wp_kses( __("Select color scheme for this block", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"group" => esc_html__('Colors and Images', 'writer-ancora'),
						"class" => "",
						"value" => array_flip(writer_ancora_get_sc_param('schemes')),
						"type" => "dropdown"
					),
					array(
						"param_name" => "bg_color",
						"heading" => esc_html__("Background color", 'writer-ancora'),
						"description" => wp_kses( __("Any background color for this section", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"group" => esc_html__('Colors and Images', 'writer-ancora'),
						"class" => "",
						"value" => "",
						"type" => "colorpicker"
					),
					array(
						"param_name" => "bg_image",
						"heading" => esc_html__("Background image URL", 'writer-ancora'),
						"description" => wp_kses( __("Select background image from library for this section", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"group" => esc_html__('Colors and Images', 'writer-ancora'),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "bg_overlay",
						"heading" => esc_html__("Overlay", 'writer-ancora'),
						"description" => wp_kses( __("Overlay color opacity (from 0.0 to 1.0)", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"group" => esc_html__('Colors and Images', 'writer-ancora'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "bg_texture",
						"heading" => esc_html__("Texture", 'writer-ancora'),
						"description" => wp_kses( __("Texture style from 1 to 11. Empty or 0 - without texture.", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"group" => esc_html__('Colors and Images', 'writer-ancora'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					writer_ancora_vc_width(),
					writer_ancora_vc_height(),
					writer_ancora_get_vc_param('margin_top'),
					writer_ancora_get_vc_param('margin_bottom'),
					writer_ancora_get_vc_param('margin_left'),
					writer_ancora_get_vc_param('margin_right'),
					writer_ancora_get_vc_param('id'),
					writer_ancora_get_vc_param('class'),
					writer_ancora_get_vc_param('animation'),
					writer_ancora_get_vc_param('css')
				),
				'js_view' => 'VcTrxColumnsView'
		) );
			
			
		vc_map( array(
				"base" => "trx_testimonials_item",
				"name" => esc_html__("Testimonial", 'writer-ancora'),
				"description" => wp_kses( __("Single testimonials item", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
				"show_settings_on_create" => true,
				"class" => "trx_sc_collection trx_sc_column_item trx_sc_testimonials_item",
				"content_element" => true,
				"is_container" => true,
				'icon' => 'icon_trx_testimonials_item',
				"as_child" => array('only' => 'trx_testimonials'),
				"as_parent" => array('except' => 'trx_testimonials'),
				"params" => array(
					array(
						"param_name" => "author",
						"heading" => esc_html__("Author", 'writer-ancora'),
						"description" => wp_kses( __("Name of the testimonmials author", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "link",
						"heading" => esc_html__("Link", 'writer-ancora'),
						"description" => wp_kses( __("Link URL to the testimonmials author page", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "email",
						"heading" => esc_html__("E-mail", 'writer-ancora'),
						"description" => wp_kses( __("E-mail of the testimonmials author", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "photo",
						"heading" => esc_html__("Photo", 'writer-ancora'),
						"description" => wp_kses( __("Select or upload photo of testimonmials author or write URL of photo from other site", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					/*
					array(
						"param_name" => "content",
						"heading" => esc_html__("Testimonials text", 'writer-ancora'),
						"description" => wp_kses( __("Current testimonials text", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
					*/
					writer_ancora_get_vc_param('id'),
					writer_ancora_get_vc_param('class'),
					writer_ancora_get_vc_param('css')
				),
				'js_view' => 'VcTrxColumnItemView'
		) );
			
		class WPBakeryShortCode_Trx_Testimonials extends WRITER_ANCORA_VC_ShortCodeColumns {}
		class WPBakeryShortCode_Trx_Testimonials_Item extends WRITER_ANCORA_VC_ShortCodeCollection {}
		
	}
}
?>