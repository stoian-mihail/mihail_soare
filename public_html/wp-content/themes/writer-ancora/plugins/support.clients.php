<?php
/**
 * Writer Ancora Framework: Clients support
 *
 * @package	writer_ancora
 * @since	writer_ancora 1.0
 */

// Theme init
if (!function_exists('writer_ancora_clients_theme_setup')) {
	add_action( 'writer_ancora_action_before_init_theme', 'writer_ancora_clients_theme_setup', 1 );
	function writer_ancora_clients_theme_setup() {

		// Detect current page type, taxonomy and title (for custom post_types use priority < 10 to fire it handles early, than for standard post types)
		add_filter('writer_ancora_filter_get_blog_type',			'writer_ancora_clients_get_blog_type', 9, 2);
		add_filter('writer_ancora_filter_get_blog_title',		'writer_ancora_clients_get_blog_title', 9, 2);
		add_filter('writer_ancora_filter_get_current_taxonomy',	'writer_ancora_clients_get_current_taxonomy', 9, 2);
		add_filter('writer_ancora_filter_is_taxonomy',			'writer_ancora_clients_is_taxonomy', 9, 2);
		add_filter('writer_ancora_filter_get_stream_page_title',	'writer_ancora_clients_get_stream_page_title', 9, 2);
		add_filter('writer_ancora_filter_get_stream_page_link',	'writer_ancora_clients_get_stream_page_link', 9, 2);
		add_filter('writer_ancora_filter_get_stream_page_id',	'writer_ancora_clients_get_stream_page_id', 9, 2);
		add_filter('writer_ancora_filter_query_add_filters',		'writer_ancora_clients_query_add_filters', 9, 2);
		add_filter('writer_ancora_filter_detect_inheritance_key','writer_ancora_clients_detect_inheritance_key', 9, 1);

		// Extra column for clients lists
		if (writer_ancora_get_theme_option('show_overriden_posts')=='yes') {
			add_filter('manage_edit-clients_columns',			'writer_ancora_post_add_options_column', 9);
			add_filter('manage_clients_posts_custom_column',	'writer_ancora_post_fill_options_column', 9, 2);
		}

		// Registar shortcodes [trx_clients] and [trx_clients_item] in the shortcodes list
		add_action('writer_ancora_action_shortcodes_list',		'writer_ancora_clients_reg_shortcodes');
		if (function_exists('writer_ancora_exists_visual_composer') && writer_ancora_exists_visual_composer())
			add_action('writer_ancora_action_shortcodes_list_vc','writer_ancora_clients_reg_shortcodes_vc');
		
		// Add supported data types
		writer_ancora_theme_support_pt('clients');
		writer_ancora_theme_support_tx('clients_group');
	}
}

if ( !function_exists( 'writer_ancora_clients_settings_theme_setup2' ) ) {
	add_action( 'writer_ancora_action_before_init_theme', 'writer_ancora_clients_settings_theme_setup2', 3 );
	function writer_ancora_clients_settings_theme_setup2() {
		// Add post type 'clients' and taxonomy 'clients_group' into theme inheritance list
		writer_ancora_add_theme_inheritance( array('clients' => array(
			'stream_template' => 'blog-clients',
			'single_template' => 'single-client',
			'taxonomy' => array('clients_group'),
			'taxonomy_tags' => array(),
			'post_type' => array('clients'),
			'override' => 'page'
			) )
		);
	}
}


if (!function_exists('writer_ancora_clients_after_theme_setup')) {
	add_action( 'writer_ancora_action_after_init_theme', 'writer_ancora_clients_after_theme_setup' );
	function writer_ancora_clients_after_theme_setup() {
		// Update fields in the meta box
		if (writer_ancora_storage_get_array('post_meta_box', 'page')=='clients') {
			// Meta box fields
			writer_ancora_storage_set_array('post_meta_box', 'title', esc_html__('Client Options', 'writer-ancora'));
			writer_ancora_storage_set_array('post_meta_box', 'fields', array(
				"mb_partition_clients" => array(
					"title" => esc_html__('Clients', 'writer-ancora'),
					"override" => "page,post",
					"divider" => false,
					"icon" => "iconadmin-users",
					"type" => "partition"),
				"mb_info_clients_1" => array(
					"title" => esc_html__('Client details', 'writer-ancora'),
					"override" => "page,post",
					"divider" => false,
					"desc" => wp_kses( __('In this section you can put details for this client', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"class" => "client_meta",
					"type" => "info"),
				"client_name" => array(
					"title" => esc_html__('Contact name',  'writer-ancora'),
					"desc" => wp_kses( __("Name of the contacts manager", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "page,post",
					"class" => "client_name",
					"std" => '',
					"type" => "text"),
				"client_position" => array(
					"title" => esc_html__('Position',  'writer-ancora'),
					"desc" => wp_kses( __("Position of the contacts manager", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "page,post",
					"class" => "client_position",
					"std" => '',
					"type" => "text"),
				"client_show_link" => array(
					"title" => esc_html__('Show link',  'writer-ancora'),
					"desc" => wp_kses( __("Show link to client page", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "page,post",
					"class" => "client_show_link",
					"std" => "no",
					"options" => writer_ancora_get_list_yesno(),
					"type" => "switch"),
				"client_link" => array(
					"title" => esc_html__('Link',  'writer-ancora'),
					"desc" => wp_kses( __("URL of the client's site. If empty - use link to this page", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "page,post",
					"class" => "client_link",
					"std" => '',
					"type" => "text")
				)
			);
		}
	}
}


// Return true, if current page is clients page
if ( !function_exists( 'writer_ancora_is_clients_page' ) ) {
	function writer_ancora_is_clients_page() {
		$is = in_array(writer_ancora_storage_get('page_template'), array('blog-clients', 'single-client'));
		if (!$is) {
			if (!writer_ancora_storage_empty('pre_query'))
				$is = writer_ancora_storage_call_obj_method('pre_query', 'get', 'post_type')=='clients'
						|| writer_ancora_storage_call_obj_method('pre_query', 'is_tax', 'clients_group') 
						|| (writer_ancora_storage_call_obj_method('pre_query', 'is_page') 
							&& ($id=writer_ancora_get_template_page_id('blog-clients')) > 0 
							&& $id==writer_ancora_storage_get_obj_property('pre_query', 'queried_object_id', 0)
							);
			else
				$is = get_query_var('post_type')=='clients' 
						|| is_tax('clients_group') 
						|| (is_page() && ($id=writer_ancora_get_template_page_id('blog-clients')) > 0 && $id==get_the_ID());
		}
		return $is;
	}
}

// Filter to detect current page inheritance key
if ( !function_exists( 'writer_ancora_clients_detect_inheritance_key' ) ) {
	//add_filter('writer_ancora_filter_detect_inheritance_key',	'writer_ancora_clients_detect_inheritance_key', 9, 1);
	function writer_ancora_clients_detect_inheritance_key($key) {
		if (!empty($key)) return $key;
		return writer_ancora_is_clients_page() ? 'clients' : '';
	}
}

// Filter to detect current page slug
if ( !function_exists( 'writer_ancora_clients_get_blog_type' ) ) {
	//add_filter('writer_ancora_filter_get_blog_type',	'writer_ancora_clients_get_blog_type', 9, 2);
	function writer_ancora_clients_get_blog_type($page, $query=null) {
		if (!empty($page)) return $page;
		if ($query && $query->is_tax('clients_group') || is_tax('clients_group'))
			$page = 'clients_category';
		else if ($query && $query->get('post_type')=='clients' || get_query_var('post_type')=='clients')
			$page = $query && $query->is_single() || is_single() ? 'clients_item' : 'clients';
		return $page;
	}
}

// Filter to detect current page title
if ( !function_exists( 'writer_ancora_clients_get_blog_title' ) ) {
	//add_filter('writer_ancora_filter_get_blog_title',	'writer_ancora_clients_get_blog_title', 9, 2);
	function writer_ancora_clients_get_blog_title($title, $page) {
		if (!empty($title)) return $title;
		if ( writer_ancora_strpos($page, 'clients')!==false ) {
			if ( $page == 'clients_category' ) {
				$term = get_term_by( 'slug', get_query_var( 'clients_group' ), 'clients_group', OBJECT);
				$title = $term->name;
			} else if ( $page == 'clients_item' ) {
				$title = writer_ancora_get_post_title();
			} else {
				$title = esc_html__('All clients', 'writer-ancora');
			}
		}
		return $title;
	}
}

// Filter to detect stream page title
if ( !function_exists( 'writer_ancora_clients_get_stream_page_title' ) ) {
	//add_filter('writer_ancora_filter_get_stream_page_title',	'writer_ancora_clients_get_stream_page_title', 9, 2);
	function writer_ancora_clients_get_stream_page_title($title, $page) {
		if (!empty($title)) return $title;
		if (writer_ancora_strpos($page, 'clients')!==false) {
			if (($page_id = writer_ancora_clients_get_stream_page_id(0, $page=='clients' ? 'blog-clients' : $page)) > 0)
				$title = writer_ancora_get_post_title($page_id);
			else
				$title = esc_html__('All clients', 'writer-ancora');				
		}
		return $title;
	}
}

// Filter to detect stream page ID
if ( !function_exists( 'writer_ancora_clients_get_stream_page_id' ) ) {
	//add_filter('writer_ancora_filter_get_stream_page_id',	'writer_ancora_clients_get_stream_page_id', 9, 2);
	function writer_ancora_clients_get_stream_page_id($id, $page) {
		if (!empty($id)) return $id;
		if (writer_ancora_strpos($page, 'clients')!==false) $id = writer_ancora_get_template_page_id('blog-clients');
		return $id;
	}
}

// Filter to detect stream page URL
if ( !function_exists( 'writer_ancora_clients_get_stream_page_link' ) ) {
	//add_filter('writer_ancora_filter_get_stream_page_link',	'writer_ancora_clients_get_stream_page_link', 9, 2);
	function writer_ancora_clients_get_stream_page_link($url, $page) {
		if (!empty($url)) return $url;
		if (writer_ancora_strpos($page, 'clients')!==false) {
			$id = writer_ancora_get_template_page_id('blog-clients');
			if ($id) $url = get_permalink($id);
		}
		return $url;
	}
}

// Filter to detect current taxonomy
if ( !function_exists( 'writer_ancora_clients_get_current_taxonomy' ) ) {
	//add_filter('writer_ancora_filter_get_current_taxonomy',	'writer_ancora_clients_get_current_taxonomy', 9, 2);
	function writer_ancora_clients_get_current_taxonomy($tax, $page) {
		if (!empty($tax)) return $tax;
		if ( writer_ancora_strpos($page, 'clients')!==false ) {
			$tax = 'clients_group';
		}
		return $tax;
	}
}

// Return taxonomy name (slug) if current page is this taxonomy page
if ( !function_exists( 'writer_ancora_clients_is_taxonomy' ) ) {
	//add_filter('writer_ancora_filter_is_taxonomy',	'writer_ancora_clients_is_taxonomy', 9, 2);
	function writer_ancora_clients_is_taxonomy($tax, $query=null) {
		if (!empty($tax))
			return $tax;
		else 
			return $query && $query->get('clients_group')!='' || is_tax('clients_group') ? 'clients_group' : '';
	}
}

// Add custom post type and/or taxonomies arguments to the query
if ( !function_exists( 'writer_ancora_clients_query_add_filters' ) ) {
	//add_filter('writer_ancora_filter_query_add_filters',	'writer_ancora_clients_query_add_filters', 9, 2);
	function writer_ancora_clients_query_add_filters($args, $filter) {
		if ($filter == 'clients') {
			$args['post_type'] = 'clients';
		}
		return $args;
	}
}





// ---------------------------------- [trx_clients] ---------------------------------------

/*
[trx_clients id="unique_id" columns="3" style="clients-1|clients-2|..."]
	[trx_clients_item name="client name" position="director" image="url"]Description text[/trx_clients_item]
	...
[/trx_clients]
*/
if ( !function_exists( 'writer_ancora_sc_clients' ) ) {
	function writer_ancora_sc_clients($atts, $content=null){	
		if (writer_ancora_in_shortcode_blogger()) return '';
		extract(writer_ancora_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "clients-1",
			"columns" => 4,
			"slider" => "no",
			"slides_space" => 0,
			"controls" => "no",
			"interval" => "",
			"autoheight" => "no",
			"custom" => "no",
			"ids" => "",
			"cat" => "",
			"count" => 4,
			"offset" => "",
			"orderby" => "date",
			"order" => "desc",
			"title" => "",
			"subtitle" => "",
			"description" => "",
			"link_caption" => esc_html__('Learn more', 'writer-ancora'),
			"link" => '',
			"scheme" => '',
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

		if (empty($id)) $id = "sc_clients_".str_replace('.', '', mt_rand());
		if (empty($width)) $width = "100%";
		if (!empty($height) && writer_ancora_param_is_on($autoheight)) $autoheight = "no";
		if (empty($interval)) $interval = mt_rand(5000, 10000);

		$class .= ($class ? ' ' : '') . writer_ancora_get_css_position_as_classes($top, $right, $bottom, $left);

		$ws = writer_ancora_get_css_dimensions_from_values($width);
		$hs = writer_ancora_get_css_dimensions_from_values('', $height);
		$css .= ($hs) . ($ws);

		if (writer_ancora_param_is_on($slider)) writer_ancora_enqueue_slider('swiper');
	
		$columns = max(1, min(12, $columns));
		$count = max(1, (int) $count);
		if (writer_ancora_param_is_off($custom) && $count < $columns) $columns = $count;
		writer_ancora_storage_set('sc_clients_data', array(
			'id'=>$id,
            'style'=>$style,
            'counter'=>0,
            'columns'=>$columns,
            'slider'=>$slider,
            'css_wh'=>$ws . $hs
            )
        );

		$output = '<div' . ($id ? ' id="'.esc_attr($id).'_wrap"' : '') 
						. ' class="sc_clients_wrap'
						. ($scheme && !writer_ancora_param_is_off($scheme) && !writer_ancora_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '') 
						.'">'
					. '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
						. ' class="sc_clients sc_clients_style_'.esc_attr($style)
							. ' ' . esc_attr(writer_ancora_get_template_property($style, 'container_classes'))
							. ' ' . esc_attr(writer_ancora_get_slider_controls_classes($controls))
							. (!empty($class) ? ' '.esc_attr($class) : '')
							. (writer_ancora_param_is_on($slider)
								? ' sc_slider_swiper swiper-slider-container'
									. (writer_ancora_param_is_on($autoheight) ? ' sc_slider_height_auto' : '')
									. ($hs ? ' sc_slider_height_fixed' : '')
								: '')
						.'"'
						. (!empty($width) && writer_ancora_strpos($width, '%')===false ? ' data-old-width="' . esc_attr($width) . '"' : '')
						. (!empty($height) && writer_ancora_strpos($height, '%')===false ? ' data-old-height="' . esc_attr($height) . '"' : '')
						. ((int) $interval > 0 ? ' data-interval="'.esc_attr($interval).'"' : '')
						. ($columns > 1 ? ' data-slides-per-view="' . esc_attr($columns) . '"' : '')
						. ($slides_space > 0 ? ' data-slides-space="' . esc_attr($slides_space) . '"' : '')
						. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
						. (!writer_ancora_param_is_off($animation) ? ' data-animation="'.esc_attr(writer_ancora_get_animation_classes($animation)).'"' : '')
					. '>'
					. (!empty($subtitle) ? '<h6 class="sc_clients_subtitle sc_item_subtitle">' . trim(writer_ancora_strmacros($subtitle)) . '</h6>' : '')
					. (!empty($title) ? '<h2 class="sc_clients_title sc_item_title">' . trim(writer_ancora_strmacros($title)) . '</h2>' : '')
					. (!empty($description) ? '<div class="sc_clients_descr sc_item_descr">' . trim(writer_ancora_strmacros($description)) . '</div>' : '')
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
				'post_type' => 'clients',
				'post_status' => 'publish',
				'posts_per_page' => $count,
				'ignore_sticky_posts' => true,
				'order' => $order=='asc' ? 'asc' : 'desc',
			);
		
			if ($offset > 0 && empty($ids)) {
				$args['offset'] = $offset;
			}
		
			$args = writer_ancora_query_add_sort_order($args, $orderby, $order);
			$args = writer_ancora_query_add_posts_and_cats($args, $ids, 'clients', $cat, 'clients_group');

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
				$post_meta = get_post_meta($post_data['post_id'], 'writer_ancora_post_options', true);
				$thumb_sizes = writer_ancora_get_thumb_sizes(array('layout' => $style));
				$args['client_name'] = $post_meta['client_name'];
				$args['client_position'] = $post_meta['client_position'];
				$args['client_image'] = $post_data['post_thumb'];
				$args['client_link'] = writer_ancora_param_is_on('client_show_link')
					? (!empty($post_meta['client_link']) ? $post_meta['client_link'] : $post_data['post_link'])
					: '';
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

		$output .= (!empty($link) ? '<div class="sc_clients_button sc_item_button">'.writer_ancora_do_shortcode('[trx_button link="'.esc_url($link).'" icon="icon-right"]'.esc_html($link_caption).'[/trx_button]').'</div>' : '')
				. '</div><!-- /.sc_clients -->'
			. '</div><!-- /.sc_clients_wrap -->';
	
		// Add template specific scripts and styles
		do_action('writer_ancora_action_blog_scripts', $style);
	
		return apply_filters('writer_ancora_shortcode_output', $output, 'trx_clients', $atts, $content);
	}
	writer_ancora_require_shortcode('trx_clients', 'writer_ancora_sc_clients');
}


if ( !function_exists( 'writer_ancora_sc_clients_item' ) ) {
	function writer_ancora_sc_clients_item($atts, $content=null) {
		if (writer_ancora_in_shortcode_blogger()) return '';
		extract(writer_ancora_html_decode(shortcode_atts( array(
			// Individual params
			"name" => "",
			"position" => "",
			"image" => "",
			"link" => "",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => ""
		), $atts)));
	
		writer_ancora_storage_inc_array('sc_clients_data', 'counter');
	
		$id = $id ? $id : (writer_ancora_storage_get_array('sc_clients_data', 'id') ? writer_ancora_storage_get_array('sc_clients_data', 'id') . '_' . writer_ancora_storage_get_array('sc_clients_data', 'counter') : '');
	
		$descr = trim(chop(do_shortcode($content)));
	
		$thumb_sizes = writer_ancora_get_thumb_sizes(array('layout' => writer_ancora_storage_get_array('sc_clients_data', 'style')));

		if ($image > 0) {
			$attach = wp_get_attachment_image_src( $image, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$image = $attach[0];
		}
		$image = writer_ancora_get_resized_image_tag($image, $thumb_sizes['w'], $thumb_sizes['h']);

		$post_data = array(
			'post_title' => $name,
			'post_excerpt' => $descr
		);
		$args = array(
			'layout' => writer_ancora_storage_get_array('sc_clients_data', 'style'),
			'number' => writer_ancora_storage_get_array('sc_clients_data', 'counter'),
			'columns_count' => writer_ancora_storage_get_array('sc_clients_data', 'columns'),
			'slider' => writer_ancora_storage_get_array('sc_clients_data', 'slider'),
			'show' => false,
			'descr'  => 0,
			'tag_id' => $id,
			'tag_class' => $class,
			'tag_animation' => $animation,
			'tag_css' => $css,
			'tag_css_wh' => writer_ancora_storage_get_array('sc_clients_data', 'css_wh'),
			'client_position' => $position,
			'client_link' => $link,
			'client_image' => $image
		);
		$output = writer_ancora_show_post_layout($args, $post_data);
		return apply_filters('writer_ancora_shortcode_output', $output, 'trx_clients_item', $atts, $content);
	}
	writer_ancora_require_shortcode('trx_clients_item', 'writer_ancora_sc_clients_item');
}
// ---------------------------------- [/trx_clients] ---------------------------------------



// Add [trx_clients] and [trx_clients_item] in the shortcodes list
if (!function_exists('writer_ancora_clients_reg_shortcodes')) {
	//add_filter('writer_ancora_action_shortcodes_list',	'writer_ancora_clients_reg_shortcodes');
	function writer_ancora_clients_reg_shortcodes() {
		if (writer_ancora_storage_isset('shortcodes')) {

			$users = writer_ancora_get_list_users();
			$members = writer_ancora_get_list_posts(false, array(
				'post_type'=>'clients',
				'orderby'=>'title',
				'order'=>'asc',
				'return'=>'title'
				)
			);
			$clients_groups = writer_ancora_get_list_terms(false, 'clients_group');
			$clients_styles = writer_ancora_get_list_templates('clients');
			$controls 		= writer_ancora_get_list_slider_controls();

			writer_ancora_sc_map_after('trx_chat', array(

				// Clients
				"trx_clients" => array(
					"title" => esc_html__("Clients", 'writer-ancora'),
					"desc" => wp_kses( __("Insert clients list in your page (post)", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
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
							"title" => esc_html__("Clients style", 'writer-ancora'),
							"desc" => wp_kses( __("Select style to display clients list", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
							"value" => "clients-1",
							"type" => "select",
							"options" => $clients_styles
						),
						"columns" => array(
							"title" => esc_html__("Columns", 'writer-ancora'),
							"desc" => wp_kses( __("How many columns use to show clients", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
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
							"desc" => wp_kses( __("Use slider to show clients", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
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
							"value" => "no",
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
							"value" => "no",
							"type" => "switch",
							"options" => writer_ancora_get_sc_param('yes_no')
						),
						"custom" => array(
							"title" => esc_html__("Custom", 'writer-ancora'),
							"desc" => wp_kses( __("Allow get team members from inner shortcodes (custom) or get it from specified group (cat)", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
							"divider" => true,
							"value" => "no",
							"type" => "switch",
							"options" => writer_ancora_get_sc_param('yes_no')
						),
						"cat" => array(
							"title" => esc_html__("Categories", 'writer-ancora'),
							"desc" => wp_kses( __("Select categories (groups) to show team members. If empty - select team members from any category (group) or from IDs list", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
							"dependency" => array(
								'custom' => array('no')
							),
							"divider" => true,
							"value" => "",
							"type" => "select",
							"style" => "list",
							"multiple" => true,
							"options" => writer_ancora_array_merge(array(0 => esc_html__('- Select category -', 'writer-ancora')), $clients_groups)
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
						"name" => "trx_clients_item",
						"title" => esc_html__("Client", 'writer-ancora'),
						"desc" => wp_kses( __("Single client (custom parameters)", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"container" => true,
						"params" => array(
							"name" => array(
								"title" => esc_html__("Name", 'writer-ancora'),
								"desc" => wp_kses( __("Client's name", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
								"divider" => true,
								"value" => "",
								"type" => "text"
							),
							"position" => array(
								"title" => esc_html__("Position", 'writer-ancora'),
								"desc" => wp_kses( __("Client's position", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
								"value" => "",
								"type" => "text"
							),
							"link" => array(
								"title" => esc_html__("Link", 'writer-ancora'),
								"desc" => wp_kses( __("Link on client's personal page", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
								"divider" => true,
								"value" => "",
								"type" => "text"
							),
							"image" => array(
								"title" => esc_html__("Image", 'writer-ancora'),
								"desc" => wp_kses( __("Client's image", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
								"value" => "",
								"readonly" => false,
								"type" => "media"
							),
							"_content_" => array(
								"title" => esc_html__("Description", 'writer-ancora'),
								"desc" => wp_kses( __("Client's short description", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
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


// Add [trx_clients] and [trx_clients_item] in the VC shortcodes list
if (!function_exists('writer_ancora_clients_reg_shortcodes_vc')) {
	//add_filter('writer_ancora_action_shortcodes_list_vc',	'writer_ancora_clients_reg_shortcodes_vc');
	function writer_ancora_clients_reg_shortcodes_vc() {

		$clients_groups = writer_ancora_get_list_terms(false, 'clients_group');
		$clients_styles = writer_ancora_get_list_templates('clients');
		$controls		= writer_ancora_get_list_slider_controls();

		// Clients
		vc_map( array(
				"base" => "trx_clients",
				"name" => esc_html__("Clients", 'writer-ancora'),
				"description" => wp_kses( __("Insert clients list", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
				"category" => esc_html__('Content', 'writer-ancora'),
				'icon' => 'icon_trx_clients',
				"class" => "trx_sc_columns trx_sc_clients",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"as_parent" => array('only' => 'trx_clients_item'),
				"params" => array(
					array(
						"param_name" => "style",
						"heading" => esc_html__("Clients style", 'writer-ancora'),
						"description" => wp_kses( __("Select style to display clients list", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"class" => "",
						"admin_label" => true,
						"value" => array_flip($clients_styles),
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
						"param_name" => "slider",
						"heading" => esc_html__("Slider", 'writer-ancora'),
						"description" => wp_kses( __("Use slider to show testimonials", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
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
						"param_name" => "custom",
						"heading" => esc_html__("Custom", 'writer-ancora'),
						"description" => wp_kses( __("Allow get clients from inner shortcodes (custom) or get it from specified group (cat)", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"class" => "",
						"value" => array("Custom clients" => "yes" ),
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
						"description" => wp_kses( __("Select category to show clients. If empty - select clients from any category (group) or from IDs list", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"group" => esc_html__('Query', 'writer-ancora'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => array_flip(writer_ancora_array_merge(array(0 => esc_html__('- Select category -', 'writer-ancora')), $clients_groups)),
						"type" => "dropdown"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", 'writer-ancora'),
						"description" => wp_kses( __("How many columns use to show clients", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
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
						"heading" => esc_html__("client's IDs list", 'writer-ancora'),
						"description" => wp_kses( __("Comma separated list of client's ID. If set - parameters above (category, count, order, etc.)  are ignored!", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
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
				'js_view' => 'VcTrxColumnsView'
			) );
			
			
		vc_map( array(
				"base" => "trx_clients_item",
				"name" => esc_html__("Client", 'writer-ancora'),
				"description" => wp_kses( __("Client - all data pull out from it account on your site", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
				"show_settings_on_create" => true,
				"class" => "trx_sc_collection trx_sc_column_item trx_sc_clients_item",
				"content_element" => true,
				"is_container" => true,
				'icon' => 'icon_trx_clients_item',
				"as_child" => array('only' => 'trx_clients'),
				"as_parent" => array('except' => 'trx_clients'),
				"params" => array(
					array(
						"param_name" => "name",
						"heading" => esc_html__("Name", 'writer-ancora'),
						"description" => wp_kses( __("Client's name", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "position",
						"heading" => esc_html__("Position", 'writer-ancora'),
						"description" => wp_kses( __("Client's position", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "link",
						"heading" => esc_html__("Link", 'writer-ancora'),
						"description" => wp_kses( __("Link on client's personal page", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "image",
						"heading" => esc_html__("Client's image", 'writer-ancora'),
						"description" => wp_kses( __("Clients's image", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					writer_ancora_get_vc_param('id'),
					writer_ancora_get_vc_param('class'),
					writer_ancora_get_vc_param('animation'),
					writer_ancora_get_vc_param('css')
				),
				'js_view' => 'VcTrxColumnItemView'
			) );
			
		class WPBakeryShortCode_Trx_Clients extends WRITER_ANCORA_VC_ShortCodeColumns {}
		class WPBakeryShortCode_Trx_Clients_Item extends WRITER_ANCORA_VC_ShortCodeCollection {}

	}
}
?>