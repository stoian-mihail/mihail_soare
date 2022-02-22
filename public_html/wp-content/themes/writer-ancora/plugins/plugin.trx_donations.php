<?php
/* Writer Ancora Donations support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('writer_ancora_trx_donations_theme_setup')) {
	add_action( 'writer_ancora_action_before_init_theme', 'writer_ancora_trx_donations_theme_setup', 1 );
	function writer_ancora_trx_donations_theme_setup() {

		// Register shortcode in the shortcodes list
		if (writer_ancora_exists_trx_donations()) {
			// Detect current page type, taxonomy and title (for custom post_types use priority < 10 to fire it handles early, than for standard post types)
			add_filter('writer_ancora_filter_get_blog_type',			'writer_ancora_trx_donations_get_blog_type', 9, 2);
			add_filter('writer_ancora_filter_get_blog_title',		'writer_ancora_trx_donations_get_blog_title', 9, 2);
			add_filter('writer_ancora_filter_get_current_taxonomy',	'writer_ancora_trx_donations_get_current_taxonomy', 9, 2);
			add_filter('writer_ancora_filter_is_taxonomy',			'writer_ancora_trx_donations_is_taxonomy', 9, 2);
			add_filter('writer_ancora_filter_get_stream_page_title',	'writer_ancora_trx_donations_get_stream_page_title', 9, 2);
			add_filter('writer_ancora_filter_get_stream_page_link',	'writer_ancora_trx_donations_get_stream_page_link', 9, 2);
			add_filter('writer_ancora_filter_get_stream_page_id',	'writer_ancora_trx_donations_get_stream_page_id', 9, 2);
			add_filter('writer_ancora_filter_query_add_filters',		'writer_ancora_trx_donations_query_add_filters', 9, 2);
			add_filter('writer_ancora_filter_detect_inheritance_key','writer_ancora_trx_donations_detect_inheritance_key', 9, 1);
			add_filter('writer_ancora_filter_list_post_types',		'writer_ancora_trx_donations_list_post_types');
			// Register shortcodes in the list
			add_action('writer_ancora_action_shortcodes_list',		'writer_ancora_trx_donations_reg_shortcodes');
			if (function_exists('writer_ancora_exists_visual_composer') && writer_ancora_exists_visual_composer())
				add_action('writer_ancora_action_shortcodes_list_vc','writer_ancora_trx_donations_reg_shortcodes_vc');
			if (is_admin()) {
				add_filter( 'writer_ancora_filter_importer_options',				'writer_ancora_trx_donations_importer_set_options' );
			}
		}
		if (is_admin()) {
			add_filter( 'writer_ancora_filter_importer_required_plugins',	'writer_ancora_trx_donations_importer_required_plugins', 10, 2 );
			add_filter( 'writer_ancora_filter_required_plugins',				'writer_ancora_trx_donations_required_plugins' );
		}
	}
}

if ( !function_exists( 'writer_ancora_trx_donations_settings_theme_setup2' ) ) {
	add_action( 'writer_ancora_action_before_init_theme', 'writer_ancora_trx_donations_settings_theme_setup2', 3 );
	function writer_ancora_trx_donations_settings_theme_setup2() {
		// Add Donations post type and taxonomy into theme inheritance list
		if (writer_ancora_exists_trx_donations()) {
			writer_ancora_add_theme_inheritance( array('donations' => array(
				'stream_template' => 'blog-donations',
				'single_template' => 'single-donation',
				'taxonomy' => array(WRITER_ANCORA_Donations::TAXONOMY),
				'taxonomy_tags' => array(),
				'post_type' => array(WRITER_ANCORA_Donations::POST_TYPE),
				'override' => 'page'
				) )
			);
		}
	}
}

// Check if Writer Ancora Donations installed and activated
if ( !function_exists( 'writer_ancora_exists_trx_donations' ) ) {
	function writer_ancora_exists_trx_donations() {
		return class_exists('WRITER_ANCORA_Donations');
	}
}


// Return true, if current page is donations page
if ( !function_exists( 'writer_ancora_is_trx_donations_page' ) ) {
	function writer_ancora_is_trx_donations_page() {
		$is = false;
		if (writer_ancora_exists_trx_donations()) {
			$is = in_array(writer_ancora_storage_get('page_template'), array('blog-donations', 'single-donation'));
			if (!$is) {
				if (!writer_ancora_storage_empty('pre_query'))
					$is = (writer_ancora_storage_call_obj_method('pre_query', 'is_single') && writer_ancora_storage_call_obj_method('pre_query', 'get', 'post_type') == WRITER_ANCORA_Donations::POST_TYPE) 
							|| writer_ancora_storage_call_obj_method('pre_query', 'is_post_type_archive', WRITER_ANCORA_Donations::POST_TYPE) 
							|| writer_ancora_storage_call_obj_method('pre_query', 'is_tax', WRITER_ANCORA_Donations::TAXONOMY);
				else
					$is = (is_single() && get_query_var('post_type') == WRITER_ANCORA_Donations::POST_TYPE) 
							|| is_post_type_archive(WRITER_ANCORA_Donations::POST_TYPE) 
							|| is_tax(WRITER_ANCORA_Donations::TAXONOMY);
			}
		}
		return $is;
	}
}

// Filter to detect current page inheritance key
if ( !function_exists( 'writer_ancora_trx_donations_detect_inheritance_key' ) ) {
	//add_filter('writer_ancora_filter_detect_inheritance_key',	'writer_ancora_trx_donations_detect_inheritance_key', 9, 1);
	function writer_ancora_trx_donations_detect_inheritance_key($key) {
		if (!empty($key)) return $key;
		return writer_ancora_is_trx_donations_page() ? 'donations' : '';
	}
}

// Filter to detect current page slug
if ( !function_exists( 'writer_ancora_trx_donations_get_blog_type' ) ) {
	//add_filter('writer_ancora_filter_get_blog_type',	'writer_ancora_trx_donations_get_blog_type', 9, 2);
	function writer_ancora_trx_donations_get_blog_type($page, $query=null) {
		if (!empty($page)) return $page;
		if ($query && $query->is_tax(WRITER_ANCORA_Donations::TAXONOMY) || is_tax(WRITER_ANCORA_Donations::TAXONOMY))
			$page = 'donations_category';
		else if ($query && $query->get('post_type')==WRITER_ANCORA_Donations::POST_TYPE || get_query_var('post_type')==WRITER_ANCORA_Donations::POST_TYPE)
			$page = $query && $query->is_single() || is_single() ? 'donations_item' : 'donations';
		return $page;
	}
}

// Filter to detect current page title
if ( !function_exists( 'writer_ancora_trx_donations_get_blog_title' ) ) {
	//add_filter('writer_ancora_filter_get_blog_title',	'writer_ancora_trx_donations_get_blog_title', 9, 2);
	function writer_ancora_trx_donations_get_blog_title($title, $page) {
		if (!empty($title)) return $title;
		if ( writer_ancora_strpos($page, 'donations')!==false ) {
			if ( $page == 'donations_category' ) {
				$term = get_term_by( 'slug', get_query_var( WRITER_ANCORA_Donations::TAXONOMY ), WRITER_ANCORA_Donations::TAXONOMY, OBJECT);
				$title = $term->name;
			} else if ( $page == 'donations_item' ) {
				$title = writer_ancora_get_post_title();
			} else {
				$title = esc_html__('All donations', 'writer-ancora');
			}
		}

		return $title;
	}
}

// Filter to detect stream page title
if ( !function_exists( 'writer_ancora_trx_donations_get_stream_page_title' ) ) {
	//add_filter('writer_ancora_filter_get_stream_page_title',	'writer_ancora_trx_donations_get_stream_page_title', 9, 2);
	function writer_ancora_trx_donations_get_stream_page_title($title, $page) {
		if (!empty($title)) return $title;
		if (writer_ancora_strpos($page, 'donations')!==false) {
			if (($page_id = writer_ancora_trx_donations_get_stream_page_id(0, $page=='donations' ? 'blog-donations' : $page)) > 0)
				$title = writer_ancora_get_post_title($page_id);
			else
				$title = esc_html__('All donations', 'writer-ancora');				
		}
		return $title;
	}
}

// Filter to detect stream page ID
if ( !function_exists( 'writer_ancora_trx_donations_get_stream_page_id' ) ) {
	//add_filter('writer_ancora_filter_get_stream_page_id',	'writer_ancora_trx_donations_get_stream_page_id', 9, 2);
	function writer_ancora_trx_donations_get_stream_page_id($id, $page) {
		if (!empty($id)) return $id;
		if (writer_ancora_strpos($page, 'donations')!==false) $id = writer_ancora_get_template_page_id('blog-donations');
		return $id;
	}
}

// Filter to detect stream page URL
if ( !function_exists( 'writer_ancora_trx_donations_get_stream_page_link' ) ) {
	//add_filter('writer_ancora_filter_get_stream_page_link',	'writer_ancora_trx_donations_get_stream_page_link', 9, 2);
	function writer_ancora_trx_donations_get_stream_page_link($url, $page) {
		if (!empty($url)) return $url;
		if (writer_ancora_strpos($page, 'donations')!==false) {
			$id = writer_ancora_get_template_page_id('blog-donations');
			if ($id) $url = get_permalink($id);
		}
		return $url;
	}
}

// Filter to detect current taxonomy
if ( !function_exists( 'writer_ancora_trx_donations_get_current_taxonomy' ) ) {
	//add_filter('writer_ancora_filter_get_current_taxonomy',	'writer_ancora_trx_donations_get_current_taxonomy', 9, 2);
	function writer_ancora_trx_donations_get_current_taxonomy($tax, $page) {
		if (!empty($tax)) return $tax;
		if ( writer_ancora_strpos($page, 'donations')!==false ) {
			$tax = WRITER_ANCORA_Donations::TAXONOMY;
		}
		return $tax;
	}
}

// Return taxonomy name (slug) if current page is this taxonomy page
if ( !function_exists( 'writer_ancora_trx_donations_is_taxonomy' ) ) {
	//add_filter('writer_ancora_filter_is_taxonomy',	'writer_ancora_trx_donations_is_taxonomy', 9, 2);
	function writer_ancora_trx_donations_is_taxonomy($tax, $query=null) {
		if (!empty($tax))
			return $tax;
		else 
			return $query && $query->get(WRITER_ANCORA_Donations::TAXONOMY)!='' || is_tax(WRITER_ANCORA_Donations::TAXONOMY) ? WRITER_ANCORA_Donations::TAXONOMY : '';
	}
}

// Add custom post type and/or taxonomies arguments to the query
if ( !function_exists( 'writer_ancora_trx_donations_query_add_filters' ) ) {
	//add_filter('writer_ancora_filter_query_add_filters',	'writer_ancora_trx_donations_query_add_filters', 9, 2);
	function writer_ancora_trx_donations_query_add_filters($args, $filter) {
		if ($filter == 'donations') {
			$args['post_type'] = WRITER_ANCORA_Donations::POST_TYPE;
		}
		return $args;
	}
}

// Add custom post type to the list
if ( !function_exists( 'writer_ancora_trx_donations_list_post_types' ) ) {
	//add_filter('writer_ancora_filter_list_post_types',		'writer_ancora_trx_donations_list_post_types');
	function writer_ancora_trx_donations_list_post_types($list) {
		$list[WRITER_ANCORA_Donations::POST_TYPE] = esc_html__('Donations', 'writer-ancora');
		return $list;
	}
}


// Register shortcode in the shortcodes list
if (!function_exists('writer_ancora_trx_donations_reg_shortcodes')) {
	//add_filter('writer_ancora_action_shortcodes_list',	'writer_ancora_trx_donations_reg_shortcodes');
	function writer_ancora_trx_donations_reg_shortcodes() {
		if (writer_ancora_storage_isset('shortcodes')) {

			$plugin = WRITER_ANCORA_Donations::get_instance();
			$donations_groups = writer_ancora_get_list_terms(false, WRITER_ANCORA_Donations::TAXONOMY);

			writer_ancora_sc_map_before('trx_dropcaps', array(

				// Writer Ancora Donations form
				"trx_donations_form" => array(
					"title" => esc_html__("Donations form", 'writer-ancora'),
					"desc" => esc_html__("Insert Writer Ancora Donations form", 'writer-ancora'),
					"decorate" => true,
					"container" => false,
					"params" => array(
						"title" => array(
							"title" => esc_html__("Title", 'writer-ancora'),
							"desc" => esc_html__("Title for the donations form", 'writer-ancora'),
							"value" => "",
							"type" => "text"
						),
						"subtitle" => array(
							"title" => esc_html__("Subtitle", 'writer-ancora'),
							"desc" => esc_html__("Subtitle for the donations form", 'writer-ancora'),
							"value" => "",
							"type" => "text"
						),
						"description" => array(
							"title" => esc_html__("Description", 'writer-ancora'),
							"desc" => esc_html__("Short description for the donations form", 'writer-ancora'),
							"value" => "",
							"type" => "textarea"
						),
						"align" => array(
							"title" => esc_html__("Alignment", 'writer-ancora'),
							"desc" => esc_html__("Alignment of the donations form", 'writer-ancora'),
							"divider" => true,
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => writer_ancora_get_sc_param('align')
						),
						"account" => array(
							"title" => esc_html__("PayPal account", 'writer-ancora'),
							"desc" => esc_html__("PayPal account's e-mail. If empty - used from Writer Ancora Donations settings", 'writer-ancora'),
							"divider" => true,
							"value" => "",
							"type" => "text"
						),
						"sandbox" => array(
							"title" => esc_html__("Sandbox mode", 'writer-ancora'),
							"desc" => esc_html__("Use PayPal sandbox to test payments", 'writer-ancora'),
							"dependency" => array(
								'account' => array('not_empty')
							),
							"value" => "yes",
							"type" => "switch",
							"options" => writer_ancora_get_sc_param('yes_no')
						),
						"amount" => array(
							"title" => esc_html__("Default amount", 'writer-ancora'),
							"desc" => esc_html__("Specify amount, initially selected in the form", 'writer-ancora'),
							"dependency" => array(
								'account' => array('not_empty')
							),
							"value" => 5,
							"min" => 1,
							"step" => 5,
							"type" => "spinner"
						),
						"currency" => array(
							"title" => esc_html__("Currency", 'writer-ancora'),
							"desc" => esc_html__("Select payment's currency", 'writer-ancora'),
							"dependency" => array(
								'account' => array('not_empty')
							),
							"divider" => true,
							"value" => "",
							"type" => "select",
							"style" => "list",
							"multiple" => true,
							"options" => writer_ancora_array_merge(array(0 => esc_html__('- Select currency -', 'writer-ancora')), $plugin->currency_codes)
						),
						"width" => writer_ancora_shortcodes_width(),
						"top" => writer_ancora_get_sc_param('top'),
						"bottom" => writer_ancora_get_sc_param('bottom'),
						"left" => writer_ancora_get_sc_param('left'),
						"right" => writer_ancora_get_sc_param('right'),
						"id" => writer_ancora_get_sc_param('id'),
						"class" => writer_ancora_get_sc_param('class'),
						"css" => writer_ancora_get_sc_param('css')
					)
				),
				
				
				// Writer Ancora Donations form
				"trx_donations_list" => array(
					"title" => esc_html__("Donations list", 'writer-ancora'),
					"desc" => esc_html__("Insert Writer Ancora Doantions list", 'writer-ancora'),
					"decorate" => true,
					"container" => false,
					"params" => array(
						"title" => array(
							"title" => esc_html__("Title", 'writer-ancora'),
							"desc" => esc_html__("Title for the donations list", 'writer-ancora'),
							"value" => "",
							"type" => "text"
						),
						"subtitle" => array(
							"title" => esc_html__("Subtitle", 'writer-ancora'),
							"desc" => esc_html__("Subtitle for the donations list", 'writer-ancora'),
							"value" => "",
							"type" => "text"
						),
						"description" => array(
							"title" => esc_html__("Description", 'writer-ancora'),
							"desc" => esc_html__("Short description for the donations list", 'writer-ancora'),
							"value" => "",
							"type" => "textarea"
						),
						"link" => array(
							"title" => esc_html__("Button URL", 'writer-ancora'),
							"desc" => esc_html__("Link URL for the button at the bottom of the block", 'writer-ancora'),
							"divider" => true,
							"value" => "",
							"type" => "text"
						),
						"link_caption" => array(
							"title" => esc_html__("Button caption", 'writer-ancora'),
							"desc" => esc_html__("Caption for the button at the bottom of the block", 'writer-ancora'),
							"value" => "",
							"type" => "text"
						),
						"style" => array(
							"title" => esc_html__("List style", 'writer-ancora'),
							"desc" => esc_html__("Select style to display donations", 'writer-ancora'),
							"value" => "excerpt",
							"type" => "select",
							"options" => array(
								'excerpt' => esc_html__('Excerpt', 'writer-ancora')
							)
						),
						"readmore" => array(
							"title" => esc_html__("Read more text", 'writer-ancora'),
							"desc" => esc_html__("Text of the 'Read more' link", 'writer-ancora'),
							"value" => esc_html__('Read more', 'writer-ancora'),
							"type" => "text"
						),
						"cat" => array(
							"title" => esc_html__("Categories", 'writer-ancora'),
							"desc" => esc_html__("Select categories (groups) to show donations. If empty - select donations from any category (group) or from IDs list", 'writer-ancora'),
							"divider" => true,
							"value" => "",
							"type" => "select",
							"style" => "list",
							"multiple" => true,
							"options" => writer_ancora_array_merge(array(0 => esc_html__('- Select category -', 'writer-ancora')), $donations_groups)
						),
						"count" => array(
							"title" => esc_html__("Number of donations", 'writer-ancora'),
							"desc" => esc_html__("How many donations will be displayed? If used IDs - this parameter ignored.", 'writer-ancora'),
							"value" => 3,
							"min" => 1,
							"max" => 100,
							"type" => "spinner"
						),
						"columns" => array(
							"title" => esc_html__("Columns", 'writer-ancora'),
							"desc" => esc_html__("How many columns use to show donations list", 'writer-ancora'),
							"value" => 3,
							"min" => 2,
							"max" => 6,
							"step" => 1,
							"type" => "spinner"
						),
						"offset" => array(
							"title" => esc_html__("Offset before select posts", 'writer-ancora'),
							"desc" => esc_html__("Skip posts before select next part.", 'writer-ancora'),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => 0,
							"min" => 0,
							"type" => "spinner"
						),
						"orderby" => array(
							"title" => esc_html__("Donadions order by", 'writer-ancora'),
							"desc" => esc_html__("Select desired sorting method", 'writer-ancora'),
							"value" => "date",
							"type" => "select",
							"options" => writer_ancora_get_sc_param('sorting')
						),
						"order" => array(
							"title" => esc_html__("Donations order", 'writer-ancora'),
							"desc" => esc_html__("Select donations order", 'writer-ancora'),
							"value" => "desc",
							"type" => "switch",
							"size" => "big",
							"options" => writer_ancora_get_sc_param('ordering')
						),
						"ids" => array(
							"title" => esc_html__("Donations IDs list", 'writer-ancora'),
							"desc" => esc_html__("Comma separated list of donations ID. If set - parameters above are ignored!", 'writer-ancora'),
							"value" => "",
							"type" => "text"
						),
						"top" => writer_ancora_get_sc_param('top'),
						"bottom" => writer_ancora_get_sc_param('bottom'),
						"id" => writer_ancora_get_sc_param('id'),
						"class" => writer_ancora_get_sc_param('class'),
						"css" => writer_ancora_get_sc_param('css')
					)
				)

			));
		}
	}
}


// Register shortcode in the VC shortcodes list
if (!function_exists('writer_ancora_trx_donations_reg_shortcodes_vc')) {
	//add_filter('writer_ancora_action_shortcodes_list_vc',	'writer_ancora_trx_donations_reg_shortcodes_vc');
	function writer_ancora_trx_donations_reg_shortcodes_vc() {

		$plugin = WRITER_ANCORA_Donations::get_instance();
		$donations_groups = writer_ancora_get_list_terms(false, WRITER_ANCORA_Donations::TAXONOMY);

		// Writer Ancora Donations form
		vc_map( array(
				"base" => "trx_donations_form",
				"name" => esc_html__("Donations form", 'writer-ancora'),
				"description" => esc_html__("Insert Writer Ancora Donations form", 'writer-ancora'),
				"category" => esc_html__('Content', 'writer-ancora'),
				'icon' => 'icon_trx_donations_form',
				"class" => "trx_sc_single trx_sc_donations_form",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "title",
						"heading" => esc_html__("Title", 'writer-ancora'),
						"description" => esc_html__("Title for the donations form", 'writer-ancora'),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "subtitle",
						"heading" => esc_html__("Subtitle", 'writer-ancora'),
						"description" => esc_html__("Subtitle for the donations form", 'writer-ancora'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "description",
						"heading" => esc_html__("Description", 'writer-ancora'),
						"description" => esc_html__("Description for the donations form", 'writer-ancora'),
						"class" => "",
						"value" => "",
						"type" => "textarea"
					),
					array(
						"param_name" => "align",
						"heading" => esc_html__("Alignment", 'writer-ancora'),
						"description" => esc_html__("Alignment of the donations form", 'writer-ancora'),
						"class" => "",
						"value" => array_flip(writer_ancora_get_sc_param('align')),
						"type" => "dropdown"
					),
					array(
						"param_name" => "account",
						"heading" => esc_html__("PayPal account", 'writer-ancora'),
						"description" => esc_html__("PayPal account's e-mail. If empty - used from Writer Ancora Donations settings", 'writer-ancora'),
						"admin_label" => true,
						"group" => esc_html__('PayPal', 'writer-ancora'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "sandbox",
						"heading" => esc_html__("Sandbox mode", 'writer-ancora'),
						"description" => esc_html__("Use PayPal sandbox to test payments", 'writer-ancora'),
						"admin_label" => true,
						"group" => esc_html__('PayPal', 'writer-ancora'),
						'dependency' => array(
							'element' => 'account',
							'not_empty' => true
						),
						"class" => "",
						"value" => array("Sandbox mode" => "yes" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "amount",
						"heading" => esc_html__("Default amount", 'writer-ancora'),
						"description" => esc_html__("Specify amount, initially selected in the form", 'writer-ancora'),
						"admin_label" => true,
						"group" => esc_html__('PayPal', 'writer-ancora'),
						"class" => "",
						"value" => "5",
						"type" => "textfield"
					),
					array(
						"param_name" => "currency",
						"heading" => esc_html__("Currency", 'writer-ancora'),
						"description" => esc_html__("Select payment's currency", 'writer-ancora'),
						"class" => "",
						"value" => array_flip(writer_ancora_array_merge(array(0 => esc_html__('- Select currency -', 'writer-ancora')), $plugin->currency_codes)),
						"type" => "dropdown"
					),
					writer_ancora_get_vc_param('id'),
					writer_ancora_get_vc_param('class'),
					writer_ancora_get_vc_param('css'),
					writer_ancora_vc_width(),
					writer_ancora_get_vc_param('margin_top'),
					writer_ancora_get_vc_param('margin_bottom'),
					writer_ancora_get_vc_param('margin_left'),
					writer_ancora_get_vc_param('margin_right')
				)
			) );
			
		class WPBakeryShortCode_Trx_Donations_Form extends WRITER_ANCORA_VC_ShortCodeSingle {}



		// Writer Ancora Donations list
		vc_map( array(
				"base" => "trx_donations_list",
				"name" => esc_html__("Donations list", 'writer-ancora'),
				"description" => esc_html__("Insert Writer Ancora Donations list", 'writer-ancora'),
				"category" => esc_html__('Content', 'writer-ancora'),
				'icon' => 'icon_trx_donations_list',
				"class" => "trx_sc_single trx_sc_donations_list",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "style",
						"heading" => esc_html__("List style", 'writer-ancora'),
						"description" => esc_html__("Select style to display donations", 'writer-ancora'),
						"class" => "",
						"value" => array(
							esc_html__('Excerpt', 'writer-ancora') => 'excerpt'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "title",
						"heading" => esc_html__("Title", 'writer-ancora'),
						"description" => esc_html__("Title for the donations form", 'writer-ancora'),
						"group" => esc_html__('Captions', 'writer-ancora'),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "subtitle",
						"heading" => esc_html__("Subtitle", 'writer-ancora'),
						"description" => esc_html__("Subtitle for the donations form", 'writer-ancora'),
						"group" => esc_html__('Captions', 'writer-ancora'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "description",
						"heading" => esc_html__("Description", 'writer-ancora'),
						"description" => esc_html__("Description for the donations form", 'writer-ancora'),
						"group" => esc_html__('Captions', 'writer-ancora'),
						"class" => "",
						"value" => "",
						"type" => "textarea"
					),
					array(
						"param_name" => "link",
						"heading" => esc_html__("Button URL", 'writer-ancora'),
						"description" => esc_html__("Link URL for the button at the bottom of the block", 'writer-ancora'),
						"group" => esc_html__('Captions', 'writer-ancora'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "link_caption",
						"heading" => esc_html__("Button caption", 'writer-ancora'),
						"description" => esc_html__("Caption for the button at the bottom of the block", 'writer-ancora'),
						"group" => esc_html__('Captions', 'writer-ancora'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "readmore",
						"heading" => esc_html__("Read more text", 'writer-ancora'),
						"description" => esc_html__("Text of the 'Read more' link", 'writer-ancora'),
						"group" => esc_html__('Captions', 'writer-ancora'),
						"class" => "",
						"value" => esc_html__('Read more', 'writer-ancora'),
						"type" => "textfield"
					),
					array(
						"param_name" => "cat",
						"heading" => esc_html__("Categories", 'writer-ancora'),
						"description" => esc_html__("Select category to show donations. If empty - select donations from any category (group) or from IDs list", 'writer-ancora'),
						"group" => esc_html__('Query', 'writer-ancora'),
						"class" => "",
						"value" => array_flip(writer_ancora_array_merge(array(0 => esc_html__('- Select category -', 'writer-ancora')), $donations_groups)),
						"type" => "dropdown"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", 'writer-ancora'),
						"description" => esc_html__("How many columns use to show donations", 'writer-ancora'),
						"group" => esc_html__('Query', 'writer-ancora'),
						"admin_label" => true,
						"class" => "",
						"value" => "3",
						"type" => "textfield"
					),
					array(
						"param_name" => "count",
						"heading" => esc_html__("Number of posts", 'writer-ancora'),
						"description" => esc_html__("How many posts will be displayed? If used IDs - this parameter ignored.", 'writer-ancora'),
						"group" => esc_html__('Query', 'writer-ancora'),
						"class" => "",
						"value" => "3",
						"type" => "textfield"
					),
					array(
						"param_name" => "offset",
						"heading" => esc_html__("Offset before select posts", 'writer-ancora'),
						"description" => esc_html__("Skip posts before select next part.", 'writer-ancora'),
						"group" => esc_html__('Query', 'writer-ancora'),
						"class" => "",
						"value" => "0",
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => esc_html__("Post sorting", 'writer-ancora'),
						"description" => esc_html__("Select desired posts sorting method", 'writer-ancora'),
						"group" => esc_html__('Query', 'writer-ancora'),
						"class" => "",
						"value" => array_flip(writer_ancora_get_sc_param('sorting')),
						"type" => "dropdown"
					),
					array(
						"param_name" => "order",
						"heading" => esc_html__("Post order", 'writer-ancora'),
						"description" => esc_html__("Select desired posts order", 'writer-ancora'),
						"group" => esc_html__('Query', 'writer-ancora'),
						"class" => "",
						"value" => array_flip(writer_ancora_get_sc_param('ordering')),
						"type" => "dropdown"
					),
					array(
						"param_name" => "ids",
						"heading" => esc_html__("client's IDs list", 'writer-ancora'),
						"description" => esc_html__("Comma separated list of donation's ID. If set - parameters above (category, count, order, etc.)  are ignored!", 'writer-ancora'),
						"group" => esc_html__('Query', 'writer-ancora'),
						'dependency' => array(
							'element' => 'cats',
							'is_empty' => true
						),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),

					writer_ancora_get_vc_param('id'),
					writer_ancora_get_vc_param('class'),
					writer_ancora_get_vc_param('css'),
					writer_ancora_get_vc_param('margin_top'),
					writer_ancora_get_vc_param('margin_bottom')
				)
			) );
			
		class WPBakeryShortCode_Trx_Donations_List extends WRITER_ANCORA_VC_ShortCodeSingle {}

	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'writer_ancora_trx_donations_required_plugins' ) ) {
	//add_filter('writer_ancora_filter_required_plugins',	'writer_ancora_trx_donations_required_plugins');
	function writer_ancora_trx_donations_required_plugins($list=array()) {
		if (in_array('trx_donations', writer_ancora_storage_get('required_plugins')))
			$list[] = array(
					'name' 		=> 'Writer Ancora Donations',
					'slug' 		=> 'trx_donations',
					'source'	=> writer_ancora_get_file_dir('plugins/install/trx_donations.zip'),
					'required' 	=> false
					);
		return $list;
	}
}



// One-click import support
//------------------------------------------------------------------------

// Check in the required plugins
if ( !function_exists( 'writer_ancora_trx_donations_importer_required_plugins' ) ) {
	//add_filter( 'writer_ancora_filter_importer_required_plugins',	'writer_ancora_trx_donations_importer_required_plugins', 10, 2 );
	function writer_ancora_trx_donations_importer_required_plugins($not_installed='', $list='') {
		//if (in_array('trx_donations', writer_ancora_storage_get('required_plugins')) && !writer_ancora_exists_trx_donations() )
		if (writer_ancora_strpos($list, 'trx_donations')!==false && !writer_ancora_exists_trx_donations() )
			$not_installed .= '<br>Writer Ancora Donations';
		return $not_installed;
	}
}

// Set options for one-click importer
if ( !function_exists( 'writer_ancora_trx_donations_importer_set_options' ) ) {
	//add_filter( 'writer_ancora_filter_importer_options',	'writer_ancora_trx_donations_importer_set_options' );
	function writer_ancora_trx_donations_importer_set_options($options=array()) {
		if ( in_array('trx_donations', writer_ancora_storage_get('required_plugins')) && writer_ancora_exists_trx_donations() ) {
			$options['additional_options'][] = 'trx_donations_options';		// Add slugs to export options for this plugin

		}
		return $options;
	}
}
?>