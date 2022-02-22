<?php
/**
 * Writer Ancora Framework: Services support
 *
 * @package	writer_ancora
 * @since	writer_ancora 1.0
 */

// Theme init
if (!function_exists('writer_ancora_services_theme_setup')) {
	add_action( 'writer_ancora_action_before_init_theme', 'writer_ancora_services_theme_setup',1 );
	function writer_ancora_services_theme_setup() {
		
		// Detect current page type, taxonomy and title (for custom post_types use priority < 10 to fire it handles early, than for standard post types)
		add_filter('writer_ancora_filter_get_blog_type',			'writer_ancora_services_get_blog_type', 9, 2);
		add_filter('writer_ancora_filter_get_blog_title',		'writer_ancora_services_get_blog_title', 9, 2);
		add_filter('writer_ancora_filter_get_current_taxonomy',	'writer_ancora_services_get_current_taxonomy', 9, 2);
		add_filter('writer_ancora_filter_is_taxonomy',			'writer_ancora_services_is_taxonomy', 9, 2);
		add_filter('writer_ancora_filter_get_stream_page_title',	'writer_ancora_services_get_stream_page_title', 9, 2);
		add_filter('writer_ancora_filter_get_stream_page_link',	'writer_ancora_services_get_stream_page_link', 9, 2);
		add_filter('writer_ancora_filter_get_stream_page_id',	'writer_ancora_services_get_stream_page_id', 9, 2);
		add_filter('writer_ancora_filter_query_add_filters',		'writer_ancora_services_query_add_filters', 9, 2);
		add_filter('writer_ancora_filter_detect_inheritance_key','writer_ancora_services_detect_inheritance_key', 9, 1);

		// Extra column for services lists
		if (writer_ancora_get_theme_option('show_overriden_posts')=='yes') {
			add_filter('manage_edit-services_columns',			'writer_ancora_post_add_options_column', 9);
			add_filter('manage_services_posts_custom_column',	'writer_ancora_post_fill_options_column', 9, 2);
		}

		// Register shortcodes [trx_services] and [trx_services_item]
		add_action('writer_ancora_action_shortcodes_list',		'writer_ancora_services_reg_shortcodes');
		if (function_exists('writer_ancora_exists_visual_composer') && writer_ancora_exists_visual_composer())
			add_action('writer_ancora_action_shortcodes_list_vc','writer_ancora_services_reg_shortcodes_vc');
		
		// Add supported data types
		writer_ancora_theme_support_pt('services');
		writer_ancora_theme_support_tx('services_group');
	}
}

if ( !function_exists( 'writer_ancora_services_settings_theme_setup2' ) ) {
	add_action( 'writer_ancora_action_before_init_theme', 'writer_ancora_services_settings_theme_setup2', 3 );
	function writer_ancora_services_settings_theme_setup2() {
		// Add post type 'services' and taxonomy 'services_group' into theme inheritance list
		writer_ancora_add_theme_inheritance( array('services' => array(
			'stream_template' => 'blog-services',
			'single_template' => 'single-service',
			'taxonomy' => array('services_group'),
			'taxonomy_tags' => array(),
			'post_type' => array('services'),
			'override' => 'page'
			) )
		);
	}
}



// Return true, if current page is services page
if ( !function_exists( 'writer_ancora_is_services_page' ) ) {
	function writer_ancora_is_services_page() {
		$is = in_array(writer_ancora_storage_get('page_template'), array('blog-services', 'single-service'));
		if (!$is) {
			if (!writer_ancora_storage_empty('pre_query'))
				$is = writer_ancora_storage_call_obj_method('pre_query', 'get', 'post_type')=='services' 
						|| writer_ancora_storage_call_obj_method('pre_query', 'is_tax', 'services_group') 
						|| (writer_ancora_storage_call_obj_method('pre_query', 'is_page') 
								&& ($id=writer_ancora_get_template_page_id('blog-services')) > 0 
								&& $id==writer_ancora_storage_get_obj_property('pre_query', 'queried_object_id', 0) 
							);
			else
				$is = get_query_var('post_type')=='services' 
						|| is_tax('services_group') 
						|| (is_page() && ($id=writer_ancora_get_template_page_id('blog-services')) > 0 && $id==get_the_ID());
		}
		return $is;
	}
}

// Filter to detect current page inheritance key
if ( !function_exists( 'writer_ancora_services_detect_inheritance_key' ) ) {
	//add_filter('writer_ancora_filter_detect_inheritance_key',	'writer_ancora_services_detect_inheritance_key', 9, 1);
	function writer_ancora_services_detect_inheritance_key($key) {
		if (!empty($key)) return $key;
		return writer_ancora_is_services_page() ? 'services' : '';
	}
}

// Filter to detect current page slug
if ( !function_exists( 'writer_ancora_services_get_blog_type' ) ) {
	//add_filter('writer_ancora_filter_get_blog_type',	'writer_ancora_services_get_blog_type', 9, 2);
	function writer_ancora_services_get_blog_type($page, $query=null) {
		if (!empty($page)) return $page;
		if ($query && $query->is_tax('services_group') || is_tax('services_group'))
			$page = 'services_category';
		else if ($query && $query->get('post_type')=='services' || get_query_var('post_type')=='services')
			$page = $query && $query->is_single() || is_single() ? 'services_item' : 'services';
		return $page;
	}
}

// Filter to detect current page title
if ( !function_exists( 'writer_ancora_services_get_blog_title' ) ) {
	//add_filter('writer_ancora_filter_get_blog_title',	'writer_ancora_services_get_blog_title', 9, 2);
	function writer_ancora_services_get_blog_title($title, $page) {
		if (!empty($title)) return $title;
		if ( writer_ancora_strpos($page, 'services')!==false ) {
			if ( $page == 'services_category' ) {
				$term = get_term_by( 'slug', get_query_var( 'services_group' ), 'services_group', OBJECT);
				$title = $term->name;
			} else if ( $page == 'services_item' ) {
				$title = writer_ancora_get_post_title();
			} else {
				$title = esc_html__('All services', 'writer-ancora');
			}
		}
		return $title;
	}
}

// Filter to detect stream page title
if ( !function_exists( 'writer_ancora_services_get_stream_page_title' ) ) {
	//add_filter('writer_ancora_filter_get_stream_page_title',	'writer_ancora_services_get_stream_page_title', 9, 2);
	function writer_ancora_services_get_stream_page_title($title, $page) {
		if (!empty($title)) return $title;
		if (writer_ancora_strpos($page, 'services')!==false) {
			if (($page_id = writer_ancora_services_get_stream_page_id(0, $page=='services' ? 'blog-services' : $page)) > 0)
				$title = writer_ancora_get_post_title($page_id);
			else
				$title = esc_html__('All services', 'writer-ancora');				
		}
		return $title;
	}
}

// Filter to detect stream page ID
if ( !function_exists( 'writer_ancora_services_get_stream_page_id' ) ) {
	//add_filter('writer_ancora_filter_get_stream_page_id',	'writer_ancora_services_get_stream_page_id', 9, 2);
	function writer_ancora_services_get_stream_page_id($id, $page) {
		if (!empty($id)) return $id;
		if (writer_ancora_strpos($page, 'services')!==false) $id = writer_ancora_get_template_page_id('blog-services');
		return $id;
	}
}

// Filter to detect stream page URL
if ( !function_exists( 'writer_ancora_services_get_stream_page_link' ) ) {
	//add_filter('writer_ancora_filter_get_stream_page_link',	'writer_ancora_services_get_stream_page_link', 9, 2);
	function writer_ancora_services_get_stream_page_link($url, $page) {
		if (!empty($url)) return $url;
		if (writer_ancora_strpos($page, 'services')!==false) {
			$id = writer_ancora_get_template_page_id('blog-services');
			if ($id) $url = get_permalink($id);
		}
		return $url;
	}
}

// Filter to detect current taxonomy
if ( !function_exists( 'writer_ancora_services_get_current_taxonomy' ) ) {
	//add_filter('writer_ancora_filter_get_current_taxonomy',	'writer_ancora_services_get_current_taxonomy', 9, 2);
	function writer_ancora_services_get_current_taxonomy($tax, $page) {
		if (!empty($tax)) return $tax;
		if ( writer_ancora_strpos($page, 'services')!==false ) {
			$tax = 'services_group';
		}
		return $tax;
	}
}

// Return taxonomy name (slug) if current page is this taxonomy page
if ( !function_exists( 'writer_ancora_services_is_taxonomy' ) ) {
	//add_filter('writer_ancora_filter_is_taxonomy',	'writer_ancora_services_is_taxonomy', 9, 2);
	function writer_ancora_services_is_taxonomy($tax, $query=null) {
		if (!empty($tax))
			return $tax;
		else 
			return $query && $query->get('services_group')!='' || is_tax('services_group') ? 'services_group' : '';
	}
}

// Add custom post type and/or taxonomies arguments to the query
if ( !function_exists( 'writer_ancora_services_query_add_filters' ) ) {
	//add_filter('writer_ancora_filter_query_add_filters',	'writer_ancora_services_query_add_filters', 9, 2);
	function writer_ancora_services_query_add_filters($args, $filter) {
		if ($filter == 'services') {
			$args['post_type'] = 'services';
		}
		return $args;
	}
}





// ---------------------------------- [trx_services] ---------------------------------------

/*
[trx_services id="unique_id" columns="4" count="4" style="services-1|services-2|..." title="Block title" subtitle="xxx" description="xxxxxx"]
	[trx_services_item icon="url" title="Item title" description="Item description" link="url" link_caption="Link text"]
	[trx_services_item icon="url" title="Item title" description="Item description" link="url" link_caption="Link text"]
[/trx_services]
*/
if ( !function_exists( 'writer_ancora_sc_services' ) ) {
	function writer_ancora_sc_services($atts, $content=null){	
		if (writer_ancora_in_shortcode_blogger()) return '';
		extract(writer_ancora_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "services-1",
			"columns" => 4,
			"slider" => "no",
			"slides_space" => 0,
			"controls" => "no",
			"interval" => "",
			"autoheight" => "no",
			"align" => "",
			"custom" => "no",
			"type" => "icons",	// icons | images
			"ids" => "",
			"cat" => "",
			"count" => 4,
			"offset" => "",
			"orderby" => "date",
			"order" => "desc",
			"readmore" => esc_html__('Learn more', 'writer-ancora'),
			"title" => "",
			"subtitle" => "",
			"description" => "",
			"link_caption" => esc_html__('Learn more', 'writer-ancora'),
			"link" => '',
			"scheme" => '',
			"image" => '',
			"image_align" => '',
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
	
		if (writer_ancora_param_is_off($slider) && $columns > 1 && $style == 'services-5' && !empty($image)) $columns = 2;
		if (!empty($image)) {
			if ($image > 0) {
				$attach = wp_get_attachment_image_src( $image, 'full' );
				if (isset($attach[0]) && $attach[0]!='')
					$image = $attach[0];
			}
		}

		if (empty($id)) $id = "sc_services_".str_replace('.', '', mt_rand());
		if (empty($width)) $width = "100%";
		if (!empty($height) && writer_ancora_param_is_on($autoheight)) $autoheight = "no";
		if (empty($interval)) $interval = mt_rand(5000, 10000);
		
		$class .= ($class ? ' ' : '') . writer_ancora_get_css_position_as_classes($top, $right, $bottom, $left);

		$ws = writer_ancora_get_css_dimensions_from_values($width);
		$hs = writer_ancora_get_css_dimensions_from_values('', $height);
		$css .= ($hs) . ($ws);

		$count = max(1, (int) $count);
		$columns = max(1, min(12, (int) $columns));
		if (writer_ancora_param_is_off($custom) && $count < $columns) $columns = $count;

		if (writer_ancora_param_is_on($slider)) writer_ancora_enqueue_slider('swiper');

		writer_ancora_storage_set('sc_services_data', array(
			'id' => $id,
            'style' => $style,
            'columns' => $columns,
            'counter' => 0,
            'slider' => $slider,
            'css_wh' => $ws . $hs,
            'readmore' => $readmore
            )
        );
		
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'_wrap"' : '') 
						. ' class="sc_services_wrap'
						. ($scheme && !writer_ancora_param_is_off($scheme) && !writer_ancora_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '') 
						.'">'
					. '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
						. ' class="sc_services'
							. ' sc_services_style_'.esc_attr($style)
							. ' sc_services_type_'.esc_attr($type)
							. ' ' . esc_attr(writer_ancora_get_template_property($style, 'container_classes'))
							. ' ' . esc_attr(writer_ancora_get_slider_controls_classes($controls))
							. (writer_ancora_param_is_on($slider)
								? ' sc_slider_swiper swiper-slider-container'
									. (writer_ancora_param_is_on($autoheight) ? ' sc_slider_height_auto' : '')
									. ($hs ? ' sc_slider_height_fixed' : '')
								: '')
							. (!empty($class) ? ' '.esc_attr($class) : '')
							. ($align!='' && $align!='none' ? ' align'.esc_attr($align) : '')
							. '"'
						. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
						. (!empty($width) && writer_ancora_strpos($width, '%')===false ? ' data-old-width="' . esc_attr($width) . '"' : '')
						. (!empty($height) && writer_ancora_strpos($height, '%')===false ? ' data-old-height="' . esc_attr($height) . '"' : '')
						. ((int) $interval > 0 ? ' data-interval="'.esc_attr($interval).'"' : '')
						. ($columns > 1 ? ' data-slides-per-view="' . esc_attr($columns) . '"' : '')
						. ($slides_space > 0 ? ' data-slides-space="' . esc_attr($slides_space) . '"' : '')
						. (!writer_ancora_param_is_off($animation) ? ' data-animation="'.esc_attr(writer_ancora_get_animation_classes($animation)).'"' : '')
					. '>'
					. (!empty($subtitle) ? '<h6 class="sc_services_subtitle sc_item_subtitle">' . trim(writer_ancora_strmacros($subtitle)) . '</h6>' : '')
					. (!empty($title) ? '<h2 class="sc_services_title sc_item_title">' . trim(writer_ancora_strmacros($title)) . '</h2>' : '')
					. (!empty($description) ? '<div class="sc_services_descr sc_item_descr">' . trim(writer_ancora_strmacros($description)) . '</div>' : '')
					. (writer_ancora_param_is_on($slider) 
						? '<div class="slides swiper-wrapper">' 
						: ($columns > 1 
							? ($style == 'services-5' && !empty($image) 
								? '<div class="sc_service_container sc_align_'.esc_attr($image_align).'">'
									. '<div class="sc_services_image"><img src="'.esc_url($image).'" alt=""></div>' 
								: '')
								. '<div class="sc_columns columns_wrap">' 
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
				'post_type' => 'services',
				'post_status' => 'publish',
				'posts_per_page' => $count,
				'ignore_sticky_posts' => true,
				'order' => $order=='asc' ? 'asc' : 'desc',
				'readmore' => $readmore
			);
		
			if ($offset > 0 && empty($ids)) {
				$args['offset'] = $offset;
			}
		
			$args = writer_ancora_query_add_sort_order($args, $orderby, $order);
			$args = writer_ancora_query_add_posts_and_cats($args, $ids, 'services', $cat, 'services_group');
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
					'readmore' => $readmore,
					'tag_type' => $type,
					'columns_count' => $columns,
					'slider' => $slider,
					'tag_id' => $id ? $id . '_' . $post_number : '',
					'tag_class' => '',
					'tag_animation' => '',
					'tag_css' => '',
					'tag_css_wh' => $ws . $hs
				);
				$output .= writer_ancora_show_post_layout($args);
			}
			wp_reset_postdata();
		}
	
		if (writer_ancora_param_is_on($slider)) {
			$output .= '</div>'
				. '<div class="sc_slider_controls_wrap"><a class="sc_slider_prev" href="#"></a><a class="sc_slider_next" href="#"></a></div>'
				. '<div class="sc_slider_pagination_wrap"></div>';
		} else if ($columns > 1) {
			$output .= '</div>';
			if ($style == 'services-5' && !empty($image))
				$output .= '</div>';
		}

		$output .=  (!empty($link) ? '<div class="sc_services_button sc_item_button">'.writer_ancora_do_shortcode('[trx_button link="'.esc_url($link).'" icon="icon-right"]'.esc_html($link_caption).'[/trx_button]').'</div>' : '')
					. '</div><!-- /.sc_services -->'
				. '</div><!-- /.sc_services_wrap -->';
	
		// Add template specific scripts and styles
		do_action('writer_ancora_action_blog_scripts', $style);
	
		return apply_filters('writer_ancora_shortcode_output', $output, 'trx_services', $atts, $content);
	}
	writer_ancora_require_shortcode('trx_services', 'writer_ancora_sc_services');
}


if ( !function_exists( 'writer_ancora_sc_services_item' ) ) {
	function writer_ancora_sc_services_item($atts, $content=null) {
		if (writer_ancora_in_shortcode_blogger()) return '';
		extract(writer_ancora_html_decode(shortcode_atts( array(
			// Individual params
			"icon" => "",
			"image" => "",
			"title" => "",
			"link" => "",
			"readmore" => "(none)",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => ""
		), $atts)));
	
		writer_ancora_storage_inc_array('sc_services_data', 'counter');

		$id = $id ? $id : (writer_ancora_storage_get_array('sc_services_data', 'id') ? writer_ancora_storage_get_array('sc_services_data', 'id') . '_' . writer_ancora_storage_get_array('sc_services_data', 'counter') : '');

		$descr = trim(chop(do_shortcode($content)));
		$readmore = $readmore=='(none)' ? writer_ancora_storage_get_array('sc_services_data', 'readmore') : $readmore;

		if (!empty($icon)) {
			$type = 'icons';
		} else if (!empty($image)) {
			$type = 'images';
			if ($image > 0) {
				$attach = wp_get_attachment_image_src( $image, 'full' );
				if (isset($attach[0]) && $attach[0]!='')
					$image = $attach[0];
			}
			$thumb_sizes = writer_ancora_get_thumb_sizes(array('layout' => writer_ancora_storage_get_array('sc_services_data', 'style')));
			$image = writer_ancora_get_resized_image_tag($image, $thumb_sizes['w'], $thumb_sizes['h']);
		}
	
		$post_data = array(
			'post_title' => $title,
			'post_excerpt' => $descr,
			'post_thumb' => $image,
			'post_icon' => $icon,
			'post_link' => $link,
			'post_protected' => false,
			'post_format' => 'standard'
		);
		$args = array(
			'layout' => writer_ancora_storage_get_array('sc_services_data', 'style'),
			'number' => writer_ancora_storage_get_array('sc_services_data', 'counter'),
			'columns_count' => writer_ancora_storage_get_array('sc_services_data', 'columns'),
			'slider' => writer_ancora_storage_get_array('sc_services_data', 'slider'),
			'show' => false,
			'descr'  => -1,		// -1 - don't strip tags, 0 - strip_tags, >0 - strip_tags and truncate string
			'readmore' => $readmore,
			'tag_type' => $type,
			'tag_id' => $id,
			'tag_class' => $class,
			'tag_animation' => $animation,
			'tag_css' => $css,
			'tag_css_wh' => writer_ancora_storage_get_array('sc_services_data', 'css_wh')
		);
		$output = writer_ancora_show_post_layout($args, $post_data);
		return apply_filters('writer_ancora_shortcode_output', $output, 'trx_services_item', $atts, $content);
	}
	writer_ancora_require_shortcode('trx_services_item', 'writer_ancora_sc_services_item');
}
// ---------------------------------- [/trx_services] ---------------------------------------



// Add [trx_services] and [trx_services_item] in the shortcodes list
if (!function_exists('writer_ancora_services_reg_shortcodes')) {
	//add_filter('writer_ancora_action_shortcodes_list',	'writer_ancora_services_reg_shortcodes');
	function writer_ancora_services_reg_shortcodes() {
		if (writer_ancora_storage_isset('shortcodes')) {

			$services_groups = writer_ancora_get_list_terms(false, 'services_group');
			$services_styles = writer_ancora_get_list_templates('services');
			$controls 		 = writer_ancora_get_list_slider_controls();

			writer_ancora_sc_map_after('trx_section', array(

				// Services
				"trx_services" => array(
					"title" => esc_html__("Services", 'writer-ancora'),
					"desc" => wp_kses( __("Insert services list in your page (post)", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
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
							"title" => esc_html__("Services style", 'writer-ancora'),
							"desc" => wp_kses( __("Select style to display services list", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
							"value" => "services-1",
							"type" => "select",
							"options" => $services_styles
						),
						"image" => array(
								"title" => esc_html__("Item's image", 'writer-ancora'),
								"desc" => wp_kses( __("Item's image", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
								"dependency" => array(
									'style' => 'services-5'
								),
								"value" => "",
								"readonly" => false,
								"type" => "media"
						),
						"image_align" => array(
							"title" => esc_html__("Image alignment", 'writer-ancora'),
							"desc" => wp_kses( __("Alignment of the image", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
							"divider" => true,
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => writer_ancora_get_sc_param('align')
						),
						"type" => array(
							"title" => esc_html__("Icon's type", 'writer-ancora'),
							"desc" => wp_kses( __("Select type of icons: font icon or image", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
							"value" => "icons",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => array(
								'icons'  => esc_html__('Icons', 'writer-ancora'),
								'images' => esc_html__('Images', 'writer-ancora')
							)
						),
						"columns" => array(
							"title" => esc_html__("Columns", 'writer-ancora'),
							"desc" => wp_kses( __("How many columns use to show services list", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
							"value" => 4,
							"min" => 2,
							"max" => 6,
							"step" => 1,
							"type" => "spinner"
						),
						"scheme" => array(
							"title" => esc_html__("Color scheme", 'writer-ancora'),
							"desc" => wp_kses( __("Select color scheme for this block", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
							"value" => "",
							"type" => "checklist",
							"options" => writer_ancora_get_sc_param('schemes')
						),
						"slider" => array(
							"title" => esc_html__("Slider", 'writer-ancora'),
							"desc" => wp_kses( __("Use slider to show services", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
							"value" => "no",
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
							"desc" => wp_kses( __("Alignment of the services block", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
							"divider" => true,
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => writer_ancora_get_sc_param('align')
						),
						"custom" => array(
							"title" => esc_html__("Custom", 'writer-ancora'),
							"desc" => wp_kses( __("Allow get services items from inner shortcodes (custom) or get it from specified group (cat)", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
							"divider" => true,
							"value" => "no",
							"type" => "switch",
							"options" => writer_ancora_get_sc_param('yes_no')
						),
						"cat" => array(
							"title" => esc_html__("Categories", 'writer-ancora'),
							"desc" => wp_kses( __("Select categories (groups) to show services list. If empty - select services from any category (group) or from IDs list", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
							"dependency" => array(
								'custom' => array('no')
							),
							"divider" => true,
							"value" => "",
							"type" => "select",
							"style" => "list",
							"multiple" => true,
							"options" => writer_ancora_array_merge(array(0 => esc_html__('- Select category -', 'writer-ancora')), $services_groups)
						),
						"count" => array(
							"title" => esc_html__("Number of posts", 'writer-ancora'),
							"desc" => wp_kses( __("How many posts will be displayed? If used IDs - this parameter ignored.", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => 4,
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
							"value" => "title",
							"type" => "select",
							"options" => writer_ancora_get_sc_param('sorting')
						),
						"order" => array(
							"title" => esc_html__("Post order", 'writer-ancora'),
							"desc" => wp_kses( __("Select desired posts order", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => "asc",
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
						"readmore" => array(
							"title" => esc_html__("Read more", 'writer-ancora'),
							"desc" => wp_kses( __("Caption for the Read more link (if empty - link not showed)", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
							"value" => "",
							"type" => "text"
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
					),
					"children" => array(
						"name" => "trx_services_item",
						"title" => esc_html__("Service item", 'writer-ancora'),
						"desc" => wp_kses( __("Service item", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"container" => true,
						"params" => array(
							"title" => array(
								"title" => esc_html__("Title", 'writer-ancora'),
								"desc" => wp_kses( __("Item's title", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
								"divider" => true,
								"value" => "",
								"type" => "text"
							),
							"icon" => array(
								"title" => esc_html__("Item's icon",  'writer-ancora'),
								"desc" => wp_kses( __('Select icon for the item from Fontello icons set',  'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
								"value" => "",
								"type" => "icons",
								"options" => writer_ancora_get_sc_param('icons')
							),
							"image" => array(
								"title" => esc_html__("Item's image", 'writer-ancora'),
								"desc" => wp_kses( __("Item's image (if icon not selected)", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
								"dependency" => array(
									'icon' => array('is_empty', 'none')
								),
								"value" => "",
								"readonly" => false,
								"type" => "media"
							),
							"link" => array(
								"title" => esc_html__("Link", 'writer-ancora'),
								"desc" => wp_kses( __("Link on service's item page", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
								"divider" => true,
								"value" => "",
								"type" => "text"
							),
							"readmore" => array(
								"title" => esc_html__("Read more", 'writer-ancora'),
								"desc" => wp_kses( __("Caption for the Read more link (if empty - link not showed)", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
								"value" => "",
								"type" => "text"
							),
							"_content_" => array(
								"title" => esc_html__("Description", 'writer-ancora'),
								"desc" => wp_kses( __("Item's short description", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
								"divider" => true,
								"rows" => 4,
								"value" => "",
								"type" => "textarea"
							),
							"id" => writer_ancora_get_sc_param('id'),
							"class" => writer_ancora_get_sc_param('class'),
							"animation" => writer_ancora_get_sc_param('animation'),
							"css" => writer_ancora_get_sc_param('css')
						)
					)
				)

			));
		}
	}
}


// Add [trx_services] and [trx_services_item] in the VC shortcodes list
if (!function_exists('writer_ancora_services_reg_shortcodes_vc')) {
	//add_filter('writer_ancora_action_shortcodes_list_vc',	'writer_ancora_services_reg_shortcodes_vc');
	function writer_ancora_services_reg_shortcodes_vc() {

		$services_groups = writer_ancora_get_list_terms(false, 'services_group');
		$services_styles = writer_ancora_get_list_templates('services');
		$controls		 = writer_ancora_get_list_slider_controls();

		// Services
		vc_map( array(
				"base" => "trx_services",
				"name" => esc_html__("Services", 'writer-ancora'),
				"description" => wp_kses( __("Insert services list", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
				"category" => esc_html__('Content', 'writer-ancora'),
				"icon" => 'icon_trx_services',
				"class" => "trx_sc_columns trx_sc_services",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"as_parent" => array('only' => 'trx_services_item'),
				"params" => array(
					array(
						"param_name" => "style",
						"heading" => esc_html__("Services style", 'writer-ancora'),
						"description" => wp_kses( __("Select style to display services list", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"class" => "",
						"admin_label" => true,
						"value" => array_flip($services_styles),
						"type" => "dropdown"
					),
					array(
						"param_name" => "type",
						"heading" => esc_html__("Icon's type", 'writer-ancora'),
						"description" => wp_kses( __("Select type of icons: font icon or image", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"class" => "",
						"admin_label" => true,
						"value" => array(
							esc_html__('Icons', 'writer-ancora') => 'icons',
							esc_html__('Images', 'writer-ancora') => 'images'
						),
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
						"param_name" => "image",
						"heading" => esc_html__("Image", 'writer-ancora'),
						"description" => wp_kses( __("Item's image", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						'dependency' => array(
							'element' => 'style',
							'value' => 'services-5'
						),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "image_align",
						"heading" => esc_html__("Image alignment", 'writer-ancora'),
						"description" => wp_kses( __("Alignment of the image", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"class" => "",
						"value" => array_flip(writer_ancora_get_sc_param('align')),
						"type" => "dropdown"
					),
					array(
						"param_name" => "slider",
						"heading" => esc_html__("Slider", 'writer-ancora'),
						"description" => wp_kses( __("Use slider to show services", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"admin_label" => true,
						"group" => esc_html__('Slider', 'writer-ancora'),
						"class" => "",
						"std" => "no",
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
						"description" => wp_kses( __("Alignment of the services block", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"class" => "",
						"value" => array_flip(writer_ancora_get_sc_param('align')),
						"type" => "dropdown"
					),
					array(
						"param_name" => "custom",
						"heading" => esc_html__("Custom", 'writer-ancora'),
						"description" => wp_kses( __("Allow get services from inner shortcodes (custom) or get it from specified group (cat)", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"class" => "",
						"value" => array("Custom services" => "yes" ),
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
						"description" => wp_kses( __("Select category to show services. If empty - select services from any category (group) or from IDs list", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"group" => esc_html__('Query', 'writer-ancora'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => array_flip(writer_ancora_array_merge(array(0 => esc_html__('- Select category -', 'writer-ancora')), $services_groups)),
						"type" => "dropdown"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", 'writer-ancora'),
						"description" => wp_kses( __("How many columns use to show services list", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"group" => esc_html__('Query', 'writer-ancora'),
						"admin_label" => true,
						"class" => "",
						"value" => "4",
						"type" => "textfield"
					),
					array(
						"param_name" => "count",
						"heading" => esc_html__("Number of posts", 'writer-ancora'),
						"description" => wp_kses( __("How many posts will be displayed? If used IDs - this parameter ignored.", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"admin_label" => true,
						"group" => esc_html__('Query', 'writer-ancora'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => "4",
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
						"heading" => esc_html__("Service's IDs list", 'writer-ancora'),
						"description" => wp_kses( __("Comma separated list of service's ID. If set - parameters above (category, count, order, etc.)  are ignored!", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
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
						"param_name" => "readmore",
						"heading" => esc_html__("Read more", 'writer-ancora'),
						"description" => wp_kses( __("Caption for the Read more link (if empty - link not showed)", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"admin_label" => true,
						"group" => esc_html__('Captions', 'writer-ancora'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
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
				'default_content' => '
					[trx_services_item title="' . esc_html__( 'Service item 1', 'writer-ancora' ) . '"][/trx_services_item]
					[trx_services_item title="' . esc_html__( 'Service item 2', 'writer-ancora' ) . '"][/trx_services_item]
					[trx_services_item title="' . esc_html__( 'Service item 3', 'writer-ancora' ) . '"][/trx_services_item]
					[trx_services_item title="' . esc_html__( 'Service item 4', 'writer-ancora' ) . '"][/trx_services_item]
				',
				'js_view' => 'VcTrxColumnsView'
			) );
			
			
		vc_map( array(
				"base" => "trx_services_item",
				"name" => esc_html__("Services item", 'writer-ancora'),
				"description" => wp_kses( __("Custom services item - all data pull out from shortcode parameters", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
				"show_settings_on_create" => true,
				"class" => "trx_sc_collection trx_sc_column_item trx_sc_services_item",
				"content_element" => true,
				"is_container" => true,
				'icon' => 'icon_trx_services_item',
				"as_child" => array('only' => 'trx_services'),
				"as_parent" => array('except' => 'trx_services'),
				"params" => array(
					array(
						"param_name" => "title",
						"heading" => esc_html__("Title", 'writer-ancora'),
						"description" => wp_kses( __("Item's title", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "icon",
						"heading" => esc_html__("Icon", 'writer-ancora'),
						"description" => wp_kses( __("Select icon for the item from Fontello icons set", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"admin_label" => true,
						"class" => "",
						"value" => writer_ancora_get_sc_param('icons'),
						"type" => "dropdown"
					),
					array(
						"param_name" => "image",
						"heading" => esc_html__("Image", 'writer-ancora'),
						"description" => wp_kses( __("Item's image (if icon is empty)", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "link",
						"heading" => esc_html__("Link", 'writer-ancora'),
						"description" => wp_kses( __("Link on item's page", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "readmore",
						"heading" => esc_html__("Read more", 'writer-ancora'),
						"description" => wp_kses( __("Caption for the Read more link (if empty - link not showed)", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					writer_ancora_get_vc_param('id'),
					writer_ancora_get_vc_param('class'),
					writer_ancora_get_vc_param('animation'),
					writer_ancora_get_vc_param('css')
				),
				'js_view' => 'VcTrxColumnItemView'
			) );
			
		class WPBakeryShortCode_Trx_Services extends WRITER_ANCORA_VC_ShortCodeColumns {}
		class WPBakeryShortCode_Trx_Services_Item extends WRITER_ANCORA_VC_ShortCodeCollection {}

	}
}
?>