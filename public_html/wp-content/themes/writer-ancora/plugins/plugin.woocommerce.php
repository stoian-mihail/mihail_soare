<?php
/* Woocommerce support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('writer_ancora_woocommerce_theme_setup')) {
	add_action( 'writer_ancora_action_before_init_theme', 'writer_ancora_woocommerce_theme_setup', 1 );
	function writer_ancora_woocommerce_theme_setup() {

		if (writer_ancora_exists_woocommerce()) {
			add_action('writer_ancora_action_add_styles', 				'writer_ancora_woocommerce_frontend_scripts' );

			// Detect current page type, taxonomy and title (for custom post_types use priority < 10 to fire it handles early, than for standard post types)
			add_filter('writer_ancora_filter_get_blog_type',				'writer_ancora_woocommerce_get_blog_type', 9, 2);
			add_filter('writer_ancora_filter_get_blog_title',			'writer_ancora_woocommerce_get_blog_title', 9, 2);
			add_filter('writer_ancora_filter_get_current_taxonomy',		'writer_ancora_woocommerce_get_current_taxonomy', 9, 2);
			add_filter('writer_ancora_filter_is_taxonomy',				'writer_ancora_woocommerce_is_taxonomy', 9, 2);
			add_filter('writer_ancora_filter_get_stream_page_title',		'writer_ancora_woocommerce_get_stream_page_title', 9, 2);
			add_filter('writer_ancora_filter_get_stream_page_link',		'writer_ancora_woocommerce_get_stream_page_link', 9, 2);
			add_filter('writer_ancora_filter_get_stream_page_id',		'writer_ancora_woocommerce_get_stream_page_id', 9, 2);
			add_filter('writer_ancora_filter_detect_inheritance_key',	'writer_ancora_woocommerce_detect_inheritance_key', 9, 1);
			add_filter('writer_ancora_filter_detect_template_page_id',	'writer_ancora_woocommerce_detect_template_page_id', 9, 2);
			add_filter('writer_ancora_filter_orderby_need',				'writer_ancora_woocommerce_orderby_need', 9, 2);

			add_filter('writer_ancora_filter_list_post_types', 			'writer_ancora_woocommerce_list_post_types', 10, 1);

			add_action('writer_ancora_action_shortcodes_list', 			'writer_ancora_woocommerce_reg_shortcodes', 20);
			if (function_exists('writer_ancora_exists_visual_composer') && writer_ancora_exists_visual_composer())
				add_action('writer_ancora_action_shortcodes_list_vc',	'writer_ancora_woocommerce_reg_shortcodes_vc', 20);

			if (is_admin()) {
				add_filter( 'writer_ancora_filter_importer_options',				'writer_ancora_woocommerce_importer_set_options' );
				add_action( 'writer_ancora_action_importer_after_import_posts',	'writer_ancora_woocommerce_importer_after_import_posts', 10, 1 );
			}
		}

		if (is_admin()) {
			add_filter( 'writer_ancora_filter_importer_required_plugins',		'writer_ancora_woocommerce_importer_required_plugins', 10, 2 );
			add_filter( 'writer_ancora_filter_required_plugins',					'writer_ancora_woocommerce_required_plugins' );
		}
	}
}

if ( !function_exists( 'writer_ancora_woocommerce_settings_theme_setup2' ) ) {
	add_action( 'writer_ancora_action_before_init_theme', 'writer_ancora_woocommerce_settings_theme_setup2', 3 );
	function writer_ancora_woocommerce_settings_theme_setup2() {
		if (writer_ancora_exists_woocommerce()) {
			// Add WooCommerce pages in the Theme inheritance system
			writer_ancora_add_theme_inheritance( array( 'woocommerce' => array(
				'stream_template' => '',
				'single_template' => '',
				'taxonomy' => array('product_cat'),
				'taxonomy_tags' => array('product_tag'),
				'post_type' => array('product'),
				'override' => 'page'
				) )
			);

			// Add WooCommerce specific options in the Theme Options

			writer_ancora_storage_set_array_before('options', 'partition_service', array(
				
				"partition_woocommerce" => array(
					"title" => esc_html__('WooCommerce', 'writer-ancora'),
					"icon" => "iconadmin-basket",
					"type" => "partition"),

				"info_wooc_1" => array(
					"title" => esc_html__('WooCommerce products list parameters', 'writer-ancora'),
					"desc" => esc_html__("Select WooCommerce products list's style and crop parameters", 'writer-ancora'),
					"type" => "info"),
		
				"shop_mode" => array(
					"title" => esc_html__('Shop list style',  'writer-ancora'),
					"desc" => esc_html__("WooCommerce products list's style: thumbs or list with description", 'writer-ancora'),
					"std" => "thumbs",
					"divider" => false,
					"options" => array(
						'thumbs' => esc_html__('Thumbs', 'writer-ancora'),
						'list' => esc_html__('List', 'writer-ancora')
					),
					"type" => "checklist"),
		
				"show_mode_buttons" => array(
					"title" => esc_html__('Show style buttons',  'writer-ancora'),
					"desc" => esc_html__("Show buttons to allow visitors change list style", 'writer-ancora'),
					"std" => "yes",
					"options" => writer_ancora_get_options_param('list_yes_no'),
					"type" => "switch"),

				"shop_loop_columns" => array(
					"title" => esc_html__('Shop columns',  'writer-ancora'),
					"desc" => esc_html__("How many columns used to show products on shop page", 'writer-ancora'),
					"override" => "category,post,page",
					"std" => "3",
					"step" => 1,
					"min" => 1,
					"max" => 6,
					"type" => "spinner"),

				"show_currency" => array(
					"title" => esc_html__('Show currency selector', 'writer-ancora'),
					"desc" => esc_html__('Show currency selector in the user menu', 'writer-ancora'),
					"std" => "yes",
					"options" => writer_ancora_get_options_param('list_yes_no'),
					"type" => "switch"),
		
				"show_cart" => array(
					"title" => esc_html__('Show cart button', 'writer-ancora'),
					"desc" => esc_html__('Show cart button in the user menu', 'writer-ancora'),
					"std" => "shop",
					"options" => array(
						'hide'   => esc_html__('Hide', 'writer-ancora'),
						'always' => esc_html__('Always', 'writer-ancora'),
						'shop'   => esc_html__('Only on shop pages', 'writer-ancora')
					),
					"type" => "checklist"),

				"crop_product_thumb" => array(
					"title" => esc_html__("Crop product's thumbnail",  'writer-ancora'),
					"desc" => esc_html__("Crop product's thumbnails on search results page or scale it", 'writer-ancora'),
					"std" => "no",
					"options" => writer_ancora_get_options_param('list_yes_no'),
					"type" => "switch")
				
				)
			);

		}
	}
}

// WooCommerce hooks
if (!function_exists('writer_ancora_woocommerce_theme_setup3')) {
	add_action( 'writer_ancora_action_after_init_theme', 'writer_ancora_woocommerce_theme_setup3' );
	function writer_ancora_woocommerce_theme_setup3() {

		if (writer_ancora_exists_woocommerce()) {

			add_action(    'woocommerce_before_subcategory_title',		'writer_ancora_woocommerce_open_thumb_wrapper', 9 );
			add_action(    'woocommerce_before_shop_loop_item_title',	'writer_ancora_woocommerce_open_thumb_wrapper', 9 );

			add_action(    'woocommerce_before_subcategory_title',		'writer_ancora_woocommerce_open_item_wrapper', 20 );
			add_action(    'woocommerce_before_shop_loop_item_title',	'writer_ancora_woocommerce_open_item_wrapper', 20 );

			add_action(    'woocommerce_after_subcategory',				'writer_ancora_woocommerce_close_item_wrapper', 20 );
			add_action(    'woocommerce_after_shop_loop_item',			'writer_ancora_woocommerce_close_item_wrapper', 20 );

			add_action(    'woocommerce_after_shop_loop_item_title',	'writer_ancora_woocommerce_after_shop_loop_item_title', 7);

			add_action(    'woocommerce_after_subcategory_title',		'writer_ancora_woocommerce_after_subcategory_title', 10 );
		}

		if (writer_ancora_is_woocommerce_page()) {
			
			remove_action( 'woocommerce_sidebar', 						'woocommerce_get_sidebar', 10 );					// Remove WOOC sidebar
			
			remove_action( 'woocommerce_before_main_content',			'woocommerce_output_content_wrapper', 10);
			add_action(    'woocommerce_before_main_content',			'writer_ancora_woocommerce_wrapper_start', 10);
			
			remove_action( 'woocommerce_after_main_content',			'woocommerce_output_content_wrapper_end', 10);		
			add_action(    'woocommerce_after_main_content',			'writer_ancora_woocommerce_wrapper_end', 10);

			add_action(    'woocommerce_show_page_title',				'writer_ancora_woocommerce_show_page_title', 10);

			remove_action( 'woocommerce_single_product_summary',		'woocommerce_template_single_title', 5);		
			add_action(    'woocommerce_single_product_summary',		'writer_ancora_woocommerce_show_product_title', 5 );

			add_action(    'woocommerce_before_shop_loop', 				'writer_ancora_woocommerce_before_shop_loop', 10 );

			remove_action( 'woocommerce_after_shop_loop',				'woocommerce_pagination', 10 );
			add_action(    'woocommerce_after_shop_loop',				'writer_ancora_woocommerce_pagination', 10 );

			add_action(    'woocommerce_product_meta_end',				'writer_ancora_woocommerce_show_product_id', 10);

			add_filter(    'woocommerce_output_related_products_args',	'writer_ancora_woocommerce_output_related_products_args' );
			
			add_filter(    'woocommerce_product_thumbnails_columns',	'writer_ancora_woocommerce_product_thumbnails_columns' );

			add_filter(    'loop_shop_columns',							'writer_ancora_woocommerce_loop_shop_columns' );

			add_filter(    'get_product_search_form',					'writer_ancora_woocommerce_get_product_search_form' );

			add_filter(    'post_class',								'writer_ancora_woocommerce_loop_shop_columns_class' );
			add_action(    'the_title',									'writer_ancora_woocommerce_the_title');
			
			writer_ancora_enqueue_popup();
		}
	}
}



// Check if WooCommerce installed and activated
if ( !function_exists( 'writer_ancora_exists_woocommerce' ) ) {
	function writer_ancora_exists_woocommerce() {
		return class_exists('Woocommerce');
		//return function_exists('is_woocommerce');
	}
}

// Return true, if current page is any woocommerce page
if ( !function_exists( 'writer_ancora_is_woocommerce_page' ) ) {
	function writer_ancora_is_woocommerce_page() {
		$rez = false;
		if (writer_ancora_exists_woocommerce()) {
			if (!writer_ancora_storage_empty('pre_query')) {
				$id = writer_ancora_storage_get_obj_property('pre_query', 'queried_object_id', 0);
				$rez = writer_ancora_storage_call_obj_method('pre_query', 'get', 'post_type')=='product' 
						|| $id==wc_get_page_id('shop')
						|| $id==wc_get_page_id('cart')
						|| $id==wc_get_page_id('checkout')
						|| $id==wc_get_page_id('myaccount')
						|| writer_ancora_storage_call_obj_method('pre_query', 'is_tax', 'product_cat')
						|| writer_ancora_storage_call_obj_method('pre_query', 'is_tax', 'product_tag')
						|| writer_ancora_storage_call_obj_method('pre_query', 'is_tax', get_object_taxonomies('product'));
						
			} else
				$rez = is_shop() || is_product() || is_product_category() || is_product_tag() || is_product_taxonomy() || is_cart() || is_checkout() || is_account_page();
		}
		return $rez;
	}
}

// Filter to detect current page inheritance key
if ( !function_exists( 'writer_ancora_woocommerce_detect_inheritance_key' ) ) {
	//add_filter('writer_ancora_filter_detect_inheritance_key',	'writer_ancora_woocommerce_detect_inheritance_key', 9, 1);
	function writer_ancora_woocommerce_detect_inheritance_key($key) {
		if (!empty($key)) return $key;
		return writer_ancora_is_woocommerce_page() ? 'woocommerce' : '';
	}
}

// Filter to detect current template page id
if ( !function_exists( 'writer_ancora_woocommerce_detect_template_page_id' ) ) {
	//add_filter('writer_ancora_filter_detect_template_page_id',	'writer_ancora_woocommerce_detect_template_page_id', 9, 2);
	function writer_ancora_woocommerce_detect_template_page_id($id, $key) {
		if (!empty($id)) return $id;
		if ($key == 'woocommerce_cart')				$id = get_option('woocommerce_cart_page_id');
		else if ($key == 'woocommerce_checkout')	$id = get_option('woocommerce_checkout_page_id');
		else if ($key == 'woocommerce_account')		$id = get_option('woocommerce_account_page_id');
		else if ($key == 'woocommerce')				$id = get_option('woocommerce_shop_page_id');
		return $id;
	}
}

// Filter to detect current page type (slug)
if ( !function_exists( 'writer_ancora_woocommerce_get_blog_type' ) ) {
	//add_filter('writer_ancora_filter_get_blog_type',	'writer_ancora_woocommerce_get_blog_type', 9, 2);
	function writer_ancora_woocommerce_get_blog_type($page, $query=null) {
		if (!empty($page)) return $page;
		
		if (is_shop()) 					$page = 'woocommerce_shop';
		else if ($query && $query->get('post_type')=='product' || is_product())		$page = 'woocommerce_product';
		else if ($query && $query->get('product_tag')!='' || is_product_tag())		$page = 'woocommerce_tag';
		else if ($query && $query->get('product_cat')!='' || is_product_category())	$page = 'woocommerce_category';
		else if (is_cart())				$page = 'woocommerce_cart';
		else if (is_checkout())			$page = 'woocommerce_checkout';
		else if (is_account_page())		$page = 'woocommerce_account';
		else if (is_woocommerce())		$page = 'woocommerce';
		return $page;
	}
}

// Filter to detect current page title
if ( !function_exists( 'writer_ancora_woocommerce_get_blog_title' ) ) {
	//add_filter('writer_ancora_filter_get_blog_title',	'writer_ancora_woocommerce_get_blog_title', 9, 2);
	function writer_ancora_woocommerce_get_blog_title($title, $page) {
		if (!empty($title)) return $title;
		
		if ( writer_ancora_strpos($page, 'woocommerce')!==false ) {
			if ( $page == 'woocommerce_category' ) {
				$term = get_term_by( 'slug', get_query_var( 'product_cat' ), 'product_cat', OBJECT);
				$title = $term->name;
			} else if ( $page == 'woocommerce_tag' ) {
				$term = get_term_by( 'slug', get_query_var( 'product_tag' ), 'product_tag', OBJECT);
				$title = esc_html__('Tag:', 'writer-ancora') . ' ' . esc_html($term->name);
			} else if ( $page == 'woocommerce_cart' ) {
				$title = esc_html__( 'Your cart', 'writer-ancora' );
			} else if ( $page == 'woocommerce_checkout' ) {
				$title = esc_html__( 'Checkout', 'writer-ancora' );
			} else if ( $page == 'woocommerce_account' ) {
				$title = esc_html__( 'Account', 'writer-ancora' );
			} else if ( $page == 'woocommerce_product' ) {
				$title = writer_ancora_get_post_title();
			} else if (($page_id=get_option('woocommerce_shop_page_id')) > 0) {
				$title = writer_ancora_get_post_title($page_id);
			} else {
				$title = esc_html__( 'Shop', 'writer-ancora' );
			}
		}
		
		return $title;
	}
}

// Filter to detect stream page title
if ( !function_exists( 'writer_ancora_woocommerce_get_stream_page_title' ) ) {
	//add_filter('writer_ancora_filter_get_stream_page_title',	'writer_ancora_woocommerce_get_stream_page_title', 9, 2);
	function writer_ancora_woocommerce_get_stream_page_title($title, $page) {
		if (!empty($title)) return $title;
		if (writer_ancora_strpos($page, 'woocommerce')!==false) {
			if (($page_id = writer_ancora_woocommerce_get_stream_page_id(0, $page)) > 0)
				$title = writer_ancora_get_post_title($page_id);
			else
				$title = esc_html__('Shop', 'writer-ancora');				
		}
		return $title;
	}
}

// Filter to detect stream page ID
if ( !function_exists( 'writer_ancora_woocommerce_get_stream_page_id' ) ) {
	//add_filter('writer_ancora_filter_get_stream_page_id',	'writer_ancora_woocommerce_get_stream_page_id', 9, 2);
	function writer_ancora_woocommerce_get_stream_page_id($id, $page) {
		if (!empty($id)) return $id;
		if (writer_ancora_strpos($page, 'woocommerce')!==false) {
			$id = get_option('woocommerce_shop_page_id');
		}
		return $id;
	}
}

// Filter to detect stream page link
if ( !function_exists( 'writer_ancora_woocommerce_get_stream_page_link' ) ) {
	//add_filter('writer_ancora_filter_get_stream_page_link',	'writer_ancora_woocommerce_get_stream_page_link', 9, 2);
	function writer_ancora_woocommerce_get_stream_page_link($url, $page) {
		if (!empty($url)) return $url;
		if (writer_ancora_strpos($page, 'woocommerce')!==false) {
			$id = writer_ancora_woocommerce_get_stream_page_id(0, $page);
			if ($id) $url = get_permalink($id);
		}
		return $url;
	}
}

// Filter to detect current taxonomy
if ( !function_exists( 'writer_ancora_woocommerce_get_current_taxonomy' ) ) {
	//add_filter('writer_ancora_filter_get_current_taxonomy',	'writer_ancora_woocommerce_get_current_taxonomy', 9, 2);
	function writer_ancora_woocommerce_get_current_taxonomy($tax, $page) {
		if (!empty($tax)) return $tax;
		if ( writer_ancora_strpos($page, 'woocommerce')!==false ) {
			$tax = 'product_cat';
		}
		return $tax;
	}
}

// Return taxonomy name (slug) if current page is this taxonomy page
if ( !function_exists( 'writer_ancora_woocommerce_is_taxonomy' ) ) {
	//add_filter('writer_ancora_filter_is_taxonomy',	'writer_ancora_woocommerce_is_taxonomy', 9, 2);
	function writer_ancora_woocommerce_is_taxonomy($tax, $query=null) {
		if (!empty($tax))
			return $tax;
		else 
			return $query && $query->get('product_cat')!='' || is_product_category() ? 'product_cat' : '';
	}
}

// Return false if current plugin not need theme orderby setting
if ( !function_exists( 'writer_ancora_woocommerce_orderby_need' ) ) {
	//add_filter('writer_ancora_filter_orderby_need',	'writer_ancora_woocommerce_orderby_need', 9, 2);
	function writer_ancora_woocommerce_orderby_need($need, $query=null) {
		if ($need == false)
			return $need;
		else
			return $query && !($query->get('post_type')=='product' || $query->get('product_cat')!='' || $query->get('product_tag')!='');
	}
}

// Add custom post type into list
if ( !function_exists( 'writer_ancora_woocommerce_list_post_types' ) ) {
	//add_filter('writer_ancora_filter_list_post_types', 	'writer_ancora_woocommerce_list_post_types', 10, 1);
	function writer_ancora_woocommerce_list_post_types($list) {
		$list['product'] = esc_html__('Products', 'writer-ancora');
		return $list;
	}
}


	
// Enqueue WooCommerce custom styles
if ( !function_exists( 'writer_ancora_woocommerce_frontend_scripts' ) ) {
	//add_action( 'writer_ancora_action_add_styles', 'writer_ancora_woocommerce_frontend_scripts' );
	function writer_ancora_woocommerce_frontend_scripts() {
		if (writer_ancora_is_woocommerce_page() || writer_ancora_get_custom_option('show_cart')=='always')
			writer_ancora_enqueue_style( 'writer_ancora-woo-style',  writer_ancora_get_file_url('css/woo-style.css'), array(), null );
	}
}

// Replace standard WooCommerce function
/*
if ( ! function_exists( 'woocommerce_get_product_thumbnail' ) ) {
	function woocommerce_get_product_thumbnail( $size = 'shop_catalog', $placeholder_width = 0, $placeholder_height = 0  ) {
		global $post;
		if ( has_post_thumbnail() ) {
			$s = wc_get_image_size( $size );
			return writer_ancora_get_resized_image_tag($post->ID, $s['width'], writer_ancora_get_theme_option('crop_product_thumb')=='no' ? null :  $s['height']);
			//return get_the_post_thumbnail( $post->ID, array($s['width'], $s['height']) );
		} else if ( wc_placeholder_img_src() )
			return wc_placeholder_img( $size );
	}
}
*/

// Before main content
if ( !function_exists( 'writer_ancora_woocommerce_wrapper_start' ) ) {
	//remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
	//add_action('woocommerce_before_main_content', 'writer_ancora_woocommerce_wrapper_start', 10);
	function writer_ancora_woocommerce_wrapper_start() {
		if (is_product() || is_cart() || is_checkout() || is_account_page()) {
			?>
			<article class="post_item post_item_single post_item_product">
			<?php
		} else {
			?>
			<div class="list_products shop_mode_<?php echo !writer_ancora_storage_empty('shop_mode') ? writer_ancora_storage_get('shop_mode') : 'thumbs'; ?>">
			<?php
		}
	}
}

// After main content
if ( !function_exists( 'writer_ancora_woocommerce_wrapper_end' ) ) {
	//remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);		
	//add_action('woocommerce_after_main_content', 'writer_ancora_woocommerce_wrapper_end', 10);
	function writer_ancora_woocommerce_wrapper_end() {
		if (is_product() || is_cart() || is_checkout() || is_account_page()) {
			?>
			</article>	<!-- .post_item -->
			<?php
		} else {
			?>
			</div>	<!-- .list_products -->
			<?php
		}
	}
}

// Check to show page title
if ( !function_exists( 'writer_ancora_woocommerce_show_page_title' ) ) {
	//add_action('woocommerce_show_page_title', 'writer_ancora_woocommerce_show_page_title', 10);
	function writer_ancora_woocommerce_show_page_title($defa=true) {
		return writer_ancora_get_custom_option('show_page_title')=='no';
	}
}

// Check to show product title
if ( !function_exists( 'writer_ancora_woocommerce_show_product_title' ) ) {
	//remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5);		
	//add_action( 'woocommerce_single_product_summary', 'writer_ancora_woocommerce_show_product_title', 5 );
	function writer_ancora_woocommerce_show_product_title() {
		if (writer_ancora_get_custom_option('show_post_title')=='yes' || writer_ancora_get_custom_option('show_page_title')=='no') {
			wc_get_template( 'single-product/title.php' );
		}
	}
}

// Add list mode buttons
if ( !function_exists( 'writer_ancora_woocommerce_before_shop_loop' ) ) {
	//add_action( 'woocommerce_before_shop_loop', 'writer_ancora_woocommerce_before_shop_loop', 10 );
	function writer_ancora_woocommerce_before_shop_loop() {
		if (writer_ancora_get_custom_option('show_mode_buttons')=='yes') {
			echo '<div class="mode_buttons"><form action="' . esc_url(writer_ancora_get_protocol().'://' . ($_SERVER["HTTP_HOST"]) . ($_SERVER["REQUEST_URI"])).'" method="post">'
				. '<input type="hidden" name="writer_ancora_shop_mode" value="'.esc_attr(writer_ancora_storage_get('shop_mode')).'" />'
				. '<a href="#" class="woocommerce_thumbs icon-th" title="'.esc_attr__('Show products as thumbs', 'writer-ancora').'"></a>'
				. '<a href="#" class="woocommerce_list icon-th-list" title="'.esc_attr__('Show products as list', 'writer-ancora').'"></a>'
				. '</form></div>';
		}
	}
}


// Open thumbs wrapper for categories and products
if ( !function_exists( 'writer_ancora_woocommerce_open_thumb_wrapper' ) ) {
	//add_action( 'woocommerce_before_subcategory_title', 'writer_ancora_woocommerce_open_thumb_wrapper', 9 );
	//add_action( 'woocommerce_before_shop_loop_item_title', 'writer_ancora_woocommerce_open_thumb_wrapper', 9 );
	function writer_ancora_woocommerce_open_thumb_wrapper($cat='') {
		writer_ancora_storage_set('in_product_item', true);
		?>
		<div class="post_item_wrap">
			<div class="post_featured">
				<div class="post_thumb">
					<a class="hover_icon hover_icon_link" href="<?php echo get_permalink(); ?>">
		<?php
	}
}

// Open item wrapper for categories and products
if ( !function_exists( 'writer_ancora_woocommerce_open_item_wrapper' ) ) {
	//add_action( 'woocommerce_before_subcategory_title', 'writer_ancora_woocommerce_open_item_wrapper', 20 );
	//add_action( 'woocommerce_before_shop_loop_item_title', 'writer_ancora_woocommerce_open_item_wrapper', 20 );
	function writer_ancora_woocommerce_open_item_wrapper($cat='') {
		?>
				</a>
			</div>
		</div>
		<div class="post_content">
		<?php
	}
}

// Close item wrapper for categories and products
if ( !function_exists( 'writer_ancora_woocommerce_close_item_wrapper' ) ) {
	//add_action( 'woocommerce_after_subcategory', 'writer_ancora_woocommerce_close_item_wrapper', 20 );
	//add_action( 'woocommerce_after_shop_loop_item', 'writer_ancora_woocommerce_close_item_wrapper', 20 );
	function writer_ancora_woocommerce_close_item_wrapper($cat='') {
		?>
			</div>
		</div>
		<?php
		writer_ancora_storage_set('in_product_item', false);
	}
}

// Add excerpt in output for the product in the list mode
if ( !function_exists( 'writer_ancora_woocommerce_after_shop_loop_item_title' ) ) {
	//add_action( 'woocommerce_after_shop_loop_item_title', 'writer_ancora_woocommerce_after_shop_loop_item_title', 7);
	function writer_ancora_woocommerce_after_shop_loop_item_title() {
		if (writer_ancora_storage_get('shop_mode') == 'list') {
		    $excerpt = apply_filters('the_excerpt', get_the_excerpt());
			echo '<div class="description">'.trim($excerpt).'</div>';
		}
	}
}

// Add excerpt in output for the product in the list mode
if ( !function_exists( 'writer_ancora_woocommerce_after_subcategory_title' ) ) {
	//add_action( 'woocommerce_after_subcategory_title', 'writer_ancora_woocommerce_after_subcategory_title', 10 );
	function writer_ancora_woocommerce_after_subcategory_title($category) {
		if (writer_ancora_storage_get('shop_mode') == 'list')
			echo '<div class="description">' . trim($category->description) . '</div>';
	}
}

// Add Product ID for single product
if ( !function_exists( 'writer_ancora_woocommerce_show_product_id' ) ) {
	//add_action( 'woocommerce_product_meta_end', 'writer_ancora_woocommerce_show_product_id', 10);
	function writer_ancora_woocommerce_show_product_id() {
		global $post, $product;
		echo '<span class="product_id">'.esc_html__('Product ID: ', 'writer-ancora') . '<span>' . ($post->ID) . '</span></span>';
	}
}

// Redefine number of related products
if ( !function_exists( 'writer_ancora_woocommerce_output_related_products_args' ) ) {
	//add_filter( 'woocommerce_output_related_products_args', 'writer_ancora_woocommerce_output_related_products_args' );
	function writer_ancora_woocommerce_output_related_products_args($args) {
		$ppp = $ccc = 0;
		if (writer_ancora_param_is_on(writer_ancora_get_custom_option('show_post_related'))) {
			$ccc_add = in_array(writer_ancora_get_custom_option('body_style'), array('fullwide', 'fullscreen')) ? 1 : 0;
			$ccc =  writer_ancora_get_custom_option('post_related_columns');
			$ccc = $ccc > 0 ? $ccc : (writer_ancora_param_is_off(writer_ancora_get_custom_option('show_sidebar_main')) ? 3+$ccc_add : 2+$ccc_add);
			$ppp = writer_ancora_get_custom_option('post_related_count');
			$ppp = $ppp > 0 ? $ppp : $ccc;
		}
		$args['posts_per_page'] = $ppp;
		$args['columns'] = $ccc;
		return $args;
	}
}

// Number columns for product thumbnails
if ( !function_exists( 'writer_ancora_woocommerce_product_thumbnails_columns' ) ) {
	//add_filter( 'woocommerce_product_thumbnails_columns', 'writer_ancora_woocommerce_product_thumbnails_columns' );
	function writer_ancora_woocommerce_product_thumbnails_columns($cols) {
		return 5;
	}
}

// Add column class into product item in shop streampage
if ( !function_exists( 'writer_ancora_woocommerce_loop_shop_columns_class' ) ) {
	//add_filter( 'post_class', 'writer_ancora_woocommerce_loop_shop_columns_class' );
	function writer_ancora_woocommerce_loop_shop_columns_class($class) {
		global $woocommerce_loop;
		if (is_product()) {
			if (!empty($woocommerce_loop['columns']))
			$class[] = ' column-1_'.esc_attr($woocommerce_loop['columns']);
		} else if (!is_product() && !is_cart() && !is_checkout() && !is_account_page()) {
			$ccc_add = in_array(writer_ancora_get_custom_option('body_style'), array('fullwide', 'fullscreen')) ? 1 : 0;
			$ccc =  writer_ancora_get_custom_option('shop_loop_columns');
			$ccc = $ccc > 0 ? $ccc : (writer_ancora_param_is_off(writer_ancora_get_custom_option('show_sidebar_main')) ? 3+$ccc_add : 2+$ccc_add);
			$class[] = ' column-1_'.esc_attr($ccc);
		}
		return $class;
	}
}

// Number columns for shop streampage
if ( !function_exists( 'writer_ancora_woocommerce_loop_shop_columns' ) ) {
	//add_filter( 'loop_shop_columns', 'writer_ancora_woocommerce_loop_shop_columns' );
	function writer_ancora_woocommerce_loop_shop_columns($cols) {
		$ccc_add = in_array(writer_ancora_get_custom_option('body_style'), array('fullwide', 'fullscreen')) ? 1 : 0;
		$ccc =  writer_ancora_get_custom_option('shop_loop_columns');
		$ccc = $ccc > 0 ? $ccc : (writer_ancora_param_is_off(writer_ancora_get_custom_option('show_sidebar_main')) ? 3+$ccc_add : 2+$ccc_add);
		return $ccc;
	}
}

// Search form
if ( !function_exists( 'writer_ancora_woocommerce_get_product_search_form' ) ) {
	//add_filter( 'get_product_search_form', 'writer_ancora_woocommerce_get_product_search_form' );
	function writer_ancora_woocommerce_get_product_search_form($form) {
		return '
		<form role="search" method="get" class="search_form" action="' . esc_url(home_url('/')) . '">
			<input type="text" class="search_field" placeholder="' . esc_attr__('Search for products &hellip;', 'writer-ancora') . '" value="' . get_search_query() . '" name="s" title="' . esc_attr__('Search for products:', 'writer-ancora') . '" /><button class="search_button icon-search" type="submit"></button>
			<input type="hidden" name="post_type" value="product" />
		</form>
		';
	}
}

// Wrap product title into link
if ( !function_exists( 'writer_ancora_woocommerce_the_title' ) ) {
	//add_filter( 'the_title', 'writer_ancora_woocommerce_the_title' );
	function writer_ancora_woocommerce_the_title($title) {
		if (writer_ancora_storage_get('in_product_item') && get_post_type()=='product') {
			$title = '<a href="'.get_permalink().'">'.($title).'</a>';
		}
		return $title;
	}
}

// Show pagination links
if ( !function_exists( 'writer_ancora_woocommerce_pagination' ) ) {
	//add_filter( 'woocommerce_after_shop_loop', 'writer_ancora_woocommerce_pagination', 10 );
	function writer_ancora_woocommerce_pagination() {
		$style = writer_ancora_get_custom_option('blog_pagination');
		writer_ancora_show_pagination(array(
			'class' => 'pagination_wrap pagination_' . esc_attr($style),
			'style' => $style,
			'button_class' => '',
			'first_text'=> '',
			'last_text' => '',
			'prev_text' => '',
			'next_text' => '',
			'pages_in_group' => $style=='pages' ? 10 : 20
			)
		);
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'writer_ancora_woocommerce_required_plugins' ) ) {
	//add_filter('writer_ancora_filter_required_plugins',	'writer_ancora_woocommerce_required_plugins');
	function writer_ancora_woocommerce_required_plugins($list=array()) {
		if (in_array('woocommerce', writer_ancora_storage_get('required_plugins')))
			$list[] = array(
					'name' 		=> 'WooCommerce',
					'slug' 		=> 'woocommerce',
					'required' 	=> false
				);

		return $list;
	}
}



// One-click import support
//------------------------------------------------------------------------

// Check WooC in the required plugins
if ( !function_exists( 'writer_ancora_woocommerce_importer_required_plugins' ) ) {
	//add_filter( 'writer_ancora_filter_importer_required_plugins',	'writer_ancora_woocommerce_importer_required_plugins', 10, 2 );
	function writer_ancora_woocommerce_importer_required_plugins($not_installed='', $list='') {
		//if (in_array('woocommerce', writer_ancora_storage_get('required_plugins')) && !writer_ancora_exists_woocommerce() )
		if (writer_ancora_strpos($list, 'woocommerce')!==false && !writer_ancora_exists_woocommerce() )
			$not_installed .= '<br>WooCommerce';
		return $not_installed;
	}
}

// Set options for one-click importer
if ( !function_exists( 'writer_ancora_woocommerce_importer_set_options' ) ) {
	//add_filter( 'writer_ancora_filter_importer_options',	'writer_ancora_woocommerce_importer_set_options' );
	function writer_ancora_woocommerce_importer_set_options($options=array()) {
		if ( in_array('woocommerce', writer_ancora_storage_get('required_plugins')) && writer_ancora_exists_woocommerce() ) {
			$options['additional_options'][] = 'shop_%';		// Add slugs to export options for this plugin
			$options['additional_options'][] = 'woocommerce_%';
		}
		return $options;
	}
}

// Setup WooC pages after import posts complete
if ( !function_exists( 'writer_ancora_woocommerce_importer_after_import_posts' ) ) {
	//add_action( 'writer_ancora_action_importer_after_import_posts',	'writer_ancora_woocommerce_importer_after_import_posts', 10, 1 );
	function writer_ancora_woocommerce_importer_after_import_posts($importer) {
		$wooc_pages = array(						// Options slugs and pages titles for WooCommerce pages
			'woocommerce_shop_page_id' 				=> 'Shop',
			'woocommerce_cart_page_id' 				=> 'Cart',
			'woocommerce_checkout_page_id' 			=> 'Checkout',
			'woocommerce_pay_page_id' 				=> 'Checkout &#8594; Pay',
			'woocommerce_thanks_page_id' 			=> 'Order Received',
			'woocommerce_myaccount_page_id' 		=> 'My Account',
			'woocommerce_edit_address_page_id'		=> 'Edit My Address',
			'woocommerce_view_order_page_id'		=> 'View Order',
			'woocommerce_change_password_page_id'	=> 'Change Password',
			'woocommerce_logout_page_id'			=> 'Logout',
			'woocommerce_lost_password_page_id'		=> 'Lost Password'
		);
		foreach ($wooc_pages as $woo_page_name => $woo_page_title) {
			$woopage = get_page_by_title( $woo_page_title );
			if ($woopage->ID) {
				update_option($woo_page_name, $woopage->ID);
			}
		}
		// We no longer need to install pages
		delete_option( '_wc_needs_pages' );
		delete_transient( '_wc_activation_redirect' );
	}
}



// Register shortcodes to the internal builder
//------------------------------------------------------------------------
if ( !function_exists( 'writer_ancora_woocommerce_reg_shortcodes' ) ) {
	//add_action('writer_ancora_action_shortcodes_list', 'writer_ancora_woocommerce_reg_shortcodes', 20);
	function writer_ancora_woocommerce_reg_shortcodes() {

		// WooCommerce - Cart
		writer_ancora_sc_map("woocommerce_cart", array(
			"title" => esc_html__("Woocommerce: Cart", 'writer-ancora'),
			"desc" => wp_kses( __("WooCommerce shortcode: show Cart page", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
			"decorate" => false,
			"container" => false,
			"params" => array()
			)
		);
		
		// WooCommerce - Checkout
		writer_ancora_sc_map("woocommerce_checkout", array(
			"title" => esc_html__("Woocommerce: Checkout", 'writer-ancora'),
			"desc" => wp_kses( __("WooCommerce shortcode: show Checkout page", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
			"decorate" => false,
			"container" => false,
			"params" => array()
			)
		);
		
		// WooCommerce - My Account
		writer_ancora_sc_map("woocommerce_my_account", array(
			"title" => esc_html__("Woocommerce: My Account", 'writer-ancora'),
			"desc" => wp_kses( __("WooCommerce shortcode: show My Account page", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
			"decorate" => false,
			"container" => false,
			"params" => array()
			)
		);
		
		// WooCommerce - Order Tracking
		writer_ancora_sc_map("woocommerce_order_tracking", array(
			"title" => esc_html__("Woocommerce: Order Tracking", 'writer-ancora'),
			"desc" => wp_kses( __("WooCommerce shortcode: show Order Tracking page", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
			"decorate" => false,
			"container" => false,
			"params" => array()
			)
		);
		
		// WooCommerce - Shop Messages
		writer_ancora_sc_map("shop_messages", array(
			"title" => esc_html__("Woocommerce: Shop Messages", 'writer-ancora'),
			"desc" => wp_kses( __("WooCommerce shortcode: show shop messages", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
			"decorate" => false,
			"container" => false,
			"params" => array()
			)
		);
		
		// WooCommerce - Product Page
		writer_ancora_sc_map("product_page", array(
			"title" => esc_html__("Woocommerce: Product Page", 'writer-ancora'),
			"desc" => wp_kses( __("WooCommerce shortcode: display single product page", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"sku" => array(
					"title" => esc_html__("SKU", 'writer-ancora'),
					"desc" => wp_kses( __("SKU code of displayed product", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"value" => "",
					"type" => "text"
				),
				"id" => array(
					"title" => esc_html__("ID", 'writer-ancora'),
					"desc" => wp_kses( __("ID of displayed product", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"value" => "",
					"type" => "text"
				),
				"posts_per_page" => array(
					"title" => esc_html__("Number", 'writer-ancora'),
					"desc" => wp_kses( __("How many products showed", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"value" => "1",
					"min" => 1,
					"type" => "spinner"
				),
				"post_type" => array(
					"title" => esc_html__("Post type", 'writer-ancora'),
					"desc" => wp_kses( __("Post type for the WP query (leave 'product')", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"value" => "product",
					"type" => "text"
				),
				"post_status" => array(
					"title" => esc_html__("Post status", 'writer-ancora'),
					"desc" => wp_kses( __("Display posts only with this status", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"value" => "publish",
					"type" => "select",
					"options" => array(
						"publish" => esc_html__('Publish', 'writer-ancora'),
						"protected" => esc_html__('Protected', 'writer-ancora'),
						"private" => esc_html__('Private', 'writer-ancora'),
						"pending" => esc_html__('Pending', 'writer-ancora'),
						"draft" => esc_html__('Draft', 'writer-ancora')
						)
					)
				)
			)
		);
		
		// WooCommerce - Product
		writer_ancora_sc_map("product", array(
			"title" => esc_html__("Woocommerce: Product", 'writer-ancora'),
			"desc" => wp_kses( __("WooCommerce shortcode: display one product", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"sku" => array(
					"title" => esc_html__("SKU", 'writer-ancora'),
					"desc" => wp_kses( __("SKU code of displayed product", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"value" => "",
					"type" => "text"
				),
				"id" => array(
					"title" => esc_html__("ID", 'writer-ancora'),
					"desc" => wp_kses( __("ID of displayed product", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"value" => "",
					"type" => "text"
					)
				)
			)
		);
		
		// WooCommerce - Best Selling Products
		writer_ancora_sc_map("best_selling_products", array(
			"title" => esc_html__("Woocommerce: Best Selling Products", 'writer-ancora'),
			"desc" => wp_kses( __("WooCommerce shortcode: show best selling products", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"per_page" => array(
					"title" => esc_html__("Number", 'writer-ancora'),
					"desc" => wp_kses( __("How many products showed", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"value" => 4,
					"min" => 1,
					"type" => "spinner"
				),
				"columns" => array(
					"title" => esc_html__("Columns", 'writer-ancora'),
					"desc" => wp_kses( __("How many columns per row use for products output", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"value" => 4,
					"min" => 2,
					"max" => 4,
					"type" => "spinner"
					)
				)
			)
		);
		
		// WooCommerce - Recent Products
		writer_ancora_sc_map("recent_products", array(
			"title" => esc_html__("Woocommerce: Recent Products", 'writer-ancora'),
			"desc" => wp_kses( __("WooCommerce shortcode: show recent products", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"per_page" => array(
					"title" => esc_html__("Number", 'writer-ancora'),
					"desc" => wp_kses( __("How many products showed", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"value" => 4,
					"min" => 1,
					"type" => "spinner"
				),
				"columns" => array(
					"title" => esc_html__("Columns", 'writer-ancora'),
					"desc" => wp_kses( __("How many columns per row use for products output", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"value" => 4,
					"min" => 2,
					"max" => 4,
					"type" => "spinner"
				),
				"orderby" => array(
					"title" => esc_html__("Order by", 'writer-ancora'),
					"desc" => wp_kses( __("Sorting order for products output", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"value" => "date",
					"type" => "select",
					"options" => array(
						"date" => esc_html__('Date', 'writer-ancora'),
						"title" => esc_html__('Title', 'writer-ancora')
					)
				),
				"order" => array(
					"title" => esc_html__("Order", 'writer-ancora'),
					"desc" => wp_kses( __("Sorting order for products output", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"value" => "desc",
					"type" => "switch",
					"size" => "big",
					"options" => writer_ancora_get_sc_param('ordering')
					)
				)
			)
		);
		
		// WooCommerce - Related Products
		writer_ancora_sc_map("related_products", array(
			"title" => esc_html__("Woocommerce: Related Products", 'writer-ancora'),
			"desc" => wp_kses( __("WooCommerce shortcode: show related products", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"posts_per_page" => array(
					"title" => esc_html__("Number", 'writer-ancora'),
					"desc" => wp_kses( __("How many products showed", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"value" => 4,
					"min" => 1,
					"type" => "spinner"
				),
				"columns" => array(
					"title" => esc_html__("Columns", 'writer-ancora'),
					"desc" => wp_kses( __("How many columns per row use for products output", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"value" => 4,
					"min" => 2,
					"max" => 4,
					"type" => "spinner"
				),
				"orderby" => array(
					"title" => esc_html__("Order by", 'writer-ancora'),
					"desc" => wp_kses( __("Sorting order for products output", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"value" => "date",
					"type" => "select",
					"options" => array(
						"date" => esc_html__('Date', 'writer-ancora'),
						"title" => esc_html__('Title', 'writer-ancora')
						)
					)
				)
			)
		);
		
		// WooCommerce - Featured Products
		writer_ancora_sc_map("featured_products", array(
			"title" => esc_html__("Woocommerce: Featured Products", 'writer-ancora'),
			"desc" => wp_kses( __("WooCommerce shortcode: show featured products", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"per_page" => array(
					"title" => esc_html__("Number", 'writer-ancora'),
					"desc" => wp_kses( __("How many products showed", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"value" => 4,
					"min" => 1,
					"type" => "spinner"
				),
				"columns" => array(
					"title" => esc_html__("Columns", 'writer-ancora'),
					"desc" => wp_kses( __("How many columns per row use for products output", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"value" => 4,
					"min" => 2,
					"max" => 4,
					"type" => "spinner"
				),
				"orderby" => array(
					"title" => esc_html__("Order by", 'writer-ancora'),
					"desc" => wp_kses( __("Sorting order for products output", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"value" => "date",
					"type" => "select",
					"options" => array(
						"date" => esc_html__('Date', 'writer-ancora'),
						"title" => esc_html__('Title', 'writer-ancora')
					)
				),
				"order" => array(
					"title" => esc_html__("Order", 'writer-ancora'),
					"desc" => wp_kses( __("Sorting order for products output", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"value" => "desc",
					"type" => "switch",
					"size" => "big",
					"options" => writer_ancora_get_sc_param('ordering')
					)
				)
			)
		);
		
		// WooCommerce - Top Rated Products
		writer_ancora_sc_map("featured_products", array(
			"title" => esc_html__("Woocommerce: Top Rated Products", 'writer-ancora'),
			"desc" => wp_kses( __("WooCommerce shortcode: show top rated products", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"per_page" => array(
					"title" => esc_html__("Number", 'writer-ancora'),
					"desc" => wp_kses( __("How many products showed", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"value" => 4,
					"min" => 1,
					"type" => "spinner"
				),
				"columns" => array(
					"title" => esc_html__("Columns", 'writer-ancora'),
					"desc" => wp_kses( __("How many columns per row use for products output", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"value" => 4,
					"min" => 2,
					"max" => 4,
					"type" => "spinner"
				),
				"orderby" => array(
					"title" => esc_html__("Order by", 'writer-ancora'),
					"desc" => wp_kses( __("Sorting order for products output", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"value" => "date",
					"type" => "select",
					"options" => array(
						"date" => esc_html__('Date', 'writer-ancora'),
						"title" => esc_html__('Title', 'writer-ancora')
					)
				),
				"order" => array(
					"title" => esc_html__("Order", 'writer-ancora'),
					"desc" => wp_kses( __("Sorting order for products output", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"value" => "desc",
					"type" => "switch",
					"size" => "big",
					"options" => writer_ancora_get_sc_param('ordering')
					)
				)
			)
		);
		
		// WooCommerce - Sale Products
		writer_ancora_sc_map("featured_products", array(
			"title" => esc_html__("Woocommerce: Sale Products", 'writer-ancora'),
			"desc" => wp_kses( __("WooCommerce shortcode: list products on sale", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"per_page" => array(
					"title" => esc_html__("Number", 'writer-ancora'),
					"desc" => wp_kses( __("How many products showed", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"value" => 4,
					"min" => 1,
					"type" => "spinner"
				),
				"columns" => array(
					"title" => esc_html__("Columns", 'writer-ancora'),
					"desc" => wp_kses( __("How many columns per row use for products output", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"value" => 4,
					"min" => 2,
					"max" => 4,
					"type" => "spinner"
				),
				"orderby" => array(
					"title" => esc_html__("Order by", 'writer-ancora'),
					"desc" => wp_kses( __("Sorting order for products output", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"value" => "date",
					"type" => "select",
					"options" => array(
						"date" => esc_html__('Date', 'writer-ancora'),
						"title" => esc_html__('Title', 'writer-ancora')
					)
				),
				"order" => array(
					"title" => esc_html__("Order", 'writer-ancora'),
					"desc" => wp_kses( __("Sorting order for products output", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"value" => "desc",
					"type" => "switch",
					"size" => "big",
					"options" => writer_ancora_get_sc_param('ordering')
					)
				)
			)
		);
		
		// WooCommerce - Product Category
		writer_ancora_sc_map("product_category", array(
			"title" => esc_html__("Woocommerce: Products from category", 'writer-ancora'),
			"desc" => wp_kses( __("WooCommerce shortcode: list products in specified category(-ies)", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"per_page" => array(
					"title" => esc_html__("Number", 'writer-ancora'),
					"desc" => wp_kses( __("How many products showed", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"value" => 4,
					"min" => 1,
					"type" => "spinner"
				),
				"columns" => array(
					"title" => esc_html__("Columns", 'writer-ancora'),
					"desc" => wp_kses( __("How many columns per row use for products output", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"value" => 4,
					"min" => 2,
					"max" => 4,
					"type" => "spinner"
				),
				"orderby" => array(
					"title" => esc_html__("Order by", 'writer-ancora'),
					"desc" => wp_kses( __("Sorting order for products output", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"value" => "date",
					"type" => "select",
					"options" => array(
						"date" => esc_html__('Date', 'writer-ancora'),
						"title" => esc_html__('Title', 'writer-ancora')
					)
				),
				"order" => array(
					"title" => esc_html__("Order", 'writer-ancora'),
					"desc" => wp_kses( __("Sorting order for products output", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"value" => "desc",
					"type" => "switch",
					"size" => "big",
					"options" => writer_ancora_get_sc_param('ordering')
				),
				"category" => array(
					"title" => esc_html__("Categories", 'writer-ancora'),
					"desc" => wp_kses( __("Comma separated category slugs", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"value" => '',
					"type" => "text"
				),
				"operator" => array(
					"title" => esc_html__("Operator", 'writer-ancora'),
					"desc" => wp_kses( __("Categories operator", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"value" => "IN",
					"type" => "checklist",
					"size" => "medium",
					"options" => array(
						"IN" => esc_html__('IN', 'writer-ancora'),
						"NOT IN" => esc_html__('NOT IN', 'writer-ancora'),
						"AND" => esc_html__('AND', 'writer-ancora')
						)
					)
				)
			)
		);
		
		// WooCommerce - Products
		writer_ancora_sc_map("products", array(
			"title" => esc_html__("Woocommerce: Products", 'writer-ancora'),
			"desc" => wp_kses( __("WooCommerce shortcode: list all products", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"skus" => array(
					"title" => esc_html__("SKUs", 'writer-ancora'),
					"desc" => wp_kses( __("Comma separated SKU codes of products", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"value" => "",
					"type" => "text"
				),
				"ids" => array(
					"title" => esc_html__("IDs", 'writer-ancora'),
					"desc" => wp_kses( __("Comma separated ID of products", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"value" => "",
					"type" => "text"
				),
				"columns" => array(
					"title" => esc_html__("Columns", 'writer-ancora'),
					"desc" => wp_kses( __("How many columns per row use for products output", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"value" => 4,
					"min" => 2,
					"max" => 4,
					"type" => "spinner"
				),
				"orderby" => array(
					"title" => esc_html__("Order by", 'writer-ancora'),
					"desc" => wp_kses( __("Sorting order for products output", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"value" => "date",
					"type" => "select",
					"options" => array(
						"date" => esc_html__('Date', 'writer-ancora'),
						"title" => esc_html__('Title', 'writer-ancora')
					)
				),
				"order" => array(
					"title" => esc_html__("Order", 'writer-ancora'),
					"desc" => wp_kses( __("Sorting order for products output", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"value" => "desc",
					"type" => "switch",
					"size" => "big",
					"options" => writer_ancora_get_sc_param('ordering')
					)
				)
			)
		);
		
		// WooCommerce - Product attribute
		writer_ancora_sc_map("product_attribute", array(
			"title" => esc_html__("Woocommerce: Products by Attribute", 'writer-ancora'),
			"desc" => wp_kses( __("WooCommerce shortcode: show products with specified attribute", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"per_page" => array(
					"title" => esc_html__("Number", 'writer-ancora'),
					"desc" => wp_kses( __("How many products showed", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"value" => 4,
					"min" => 1,
					"type" => "spinner"
				),
				"columns" => array(
					"title" => esc_html__("Columns", 'writer-ancora'),
					"desc" => wp_kses( __("How many columns per row use for products output", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"value" => 4,
					"min" => 2,
					"max" => 4,
					"type" => "spinner"
				),
				"orderby" => array(
					"title" => esc_html__("Order by", 'writer-ancora'),
					"desc" => wp_kses( __("Sorting order for products output", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"value" => "date",
					"type" => "select",
					"options" => array(
						"date" => esc_html__('Date', 'writer-ancora'),
						"title" => esc_html__('Title', 'writer-ancora')
					)
				),
				"order" => array(
					"title" => esc_html__("Order", 'writer-ancora'),
					"desc" => wp_kses( __("Sorting order for products output", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"value" => "desc",
					"type" => "switch",
					"size" => "big",
					"options" => writer_ancora_get_sc_param('ordering')
				),
				"attribute" => array(
					"title" => esc_html__("Attribute", 'writer-ancora'),
					"desc" => wp_kses( __("Attribute name", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"value" => "",
					"type" => "text"
				),
				"filter" => array(
					"title" => esc_html__("Filter", 'writer-ancora'),
					"desc" => wp_kses( __("Attribute value", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"value" => "",
					"type" => "text"
					)
				)
			)
		);
		
		// WooCommerce - Products Categories
		writer_ancora_sc_map("product_categories", array(
			"title" => esc_html__("Woocommerce: Product Categories", 'writer-ancora'),
			"desc" => wp_kses( __("WooCommerce shortcode: show categories with products", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"number" => array(
					"title" => esc_html__("Number", 'writer-ancora'),
					"desc" => wp_kses( __("How many categories showed", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"value" => 4,
					"min" => 1,
					"type" => "spinner"
				),
				"columns" => array(
					"title" => esc_html__("Columns", 'writer-ancora'),
					"desc" => wp_kses( __("How many columns per row use for categories output", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"value" => 4,
					"min" => 2,
					"max" => 4,
					"type" => "spinner"
				),
				"orderby" => array(
					"title" => esc_html__("Order by", 'writer-ancora'),
					"desc" => wp_kses( __("Sorting order for products output", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"value" => "date",
					"type" => "select",
					"options" => array(
						"date" => esc_html__('Date', 'writer-ancora'),
						"title" => esc_html__('Title', 'writer-ancora')
					)
				),
				"order" => array(
					"title" => esc_html__("Order", 'writer-ancora'),
					"desc" => wp_kses( __("Sorting order for products output", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"value" => "desc",
					"type" => "switch",
					"size" => "big",
					"options" => writer_ancora_get_sc_param('ordering')
				),
				"parent" => array(
					"title" => esc_html__("Parent", 'writer-ancora'),
					"desc" => wp_kses( __("Parent category slug", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"value" => "",
					"type" => "text"
				),
				"ids" => array(
					"title" => esc_html__("IDs", 'writer-ancora'),
					"desc" => wp_kses( __("Comma separated ID of products", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"value" => "",
					"type" => "text"
				),
				"hide_empty" => array(
					"title" => esc_html__("Hide empty", 'writer-ancora'),
					"desc" => wp_kses( __("Hide empty categories", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"value" => "yes",
					"type" => "switch",
					"options" => writer_ancora_get_sc_param('yes_no')
					)
				)
			)
		);
	}
}



// Register shortcodes to the VC builder
//------------------------------------------------------------------------
if ( !function_exists( 'writer_ancora_woocommerce_reg_shortcodes_vc' ) ) {
	//add_action('writer_ancora_action_shortcodes_list_vc', 'writer_ancora_woocommerce_reg_shortcodes_vc');
	function writer_ancora_woocommerce_reg_shortcodes_vc() {
	
		if (false && function_exists('writer_ancora_exists_woocommerce') && writer_ancora_exists_woocommerce()) {
		
			// WooCommerce - Cart
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "woocommerce_cart",
				"name" => esc_html__("Cart", 'writer-ancora'),
				"description" => wp_kses( __("WooCommerce shortcode: show cart page", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
				"category" => esc_html__('WooCommerce', 'writer-ancora'),
				'icon' => 'icon_trx_wooc_cart',
				"class" => "trx_sc_alone trx_sc_woocommerce_cart",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => false,
				"params" => array(
					array(
						"param_name" => "dummy",
						"heading" => esc_html__("Dummy data", 'writer-ancora'),
						"description" => wp_kses( __("Dummy data - not used in shortcodes", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					)
				)
			) );
			
			class WPBakeryShortCode_Woocommerce_Cart extends WRITER_ANCORA_VC_ShortCodeAlone {}
		
		
			// WooCommerce - Checkout
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "woocommerce_checkout",
				"name" => esc_html__("Checkout", 'writer-ancora'),
				"description" => wp_kses( __("WooCommerce shortcode: show checkout page", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
				"category" => esc_html__('WooCommerce', 'writer-ancora'),
				'icon' => 'icon_trx_wooc_checkout',
				"class" => "trx_sc_alone trx_sc_woocommerce_checkout",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => false,
				"params" => array(
					array(
						"param_name" => "dummy",
						"heading" => esc_html__("Dummy data", 'writer-ancora'),
						"description" => wp_kses( __("Dummy data - not used in shortcodes", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					)
				)
			) );
			
			class WPBakeryShortCode_Woocommerce_Checkout extends WRITER_ANCORA_VC_ShortCodeAlone {}
		
		
			// WooCommerce - My Account
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "woocommerce_my_account",
				"name" => esc_html__("My Account", 'writer-ancora'),
				"description" => wp_kses( __("WooCommerce shortcode: show my account page", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
				"category" => esc_html__('WooCommerce', 'writer-ancora'),
				'icon' => 'icon_trx_wooc_my_account',
				"class" => "trx_sc_alone trx_sc_woocommerce_my_account",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => false,
				"params" => array(
					array(
						"param_name" => "dummy",
						"heading" => esc_html__("Dummy data", 'writer-ancora'),
						"description" => wp_kses( __("Dummy data - not used in shortcodes", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					)
				)
			) );
			
			class WPBakeryShortCode_Woocommerce_My_Account extends WRITER_ANCORA_VC_ShortCodeAlone {}
		
		
			// WooCommerce - Order Tracking
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "woocommerce_order_tracking",
				"name" => esc_html__("Order Tracking", 'writer-ancora'),
				"description" => wp_kses( __("WooCommerce shortcode: show order tracking page", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
				"category" => esc_html__('WooCommerce', 'writer-ancora'),
				'icon' => 'icon_trx_wooc_order_tracking',
				"class" => "trx_sc_alone trx_sc_woocommerce_order_tracking",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => false,
				"params" => array(
					array(
						"param_name" => "dummy",
						"heading" => esc_html__("Dummy data", 'writer-ancora'),
						"description" => wp_kses( __("Dummy data - not used in shortcodes", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					)
				)
			) );
			
			class WPBakeryShortCode_Woocommerce_Order_Tracking extends WRITER_ANCORA_VC_ShortCodeAlone {}
		
		
			// WooCommerce - Shop Messages
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "shop_messages",
				"name" => esc_html__("Shop Messages", 'writer-ancora'),
				"description" => wp_kses( __("WooCommerce shortcode: show shop messages", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
				"category" => esc_html__('WooCommerce', 'writer-ancora'),
				'icon' => 'icon_trx_wooc_shop_messages',
				"class" => "trx_sc_alone trx_sc_shop_messages",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => false,
				"params" => array(
					array(
						"param_name" => "dummy",
						"heading" => esc_html__("Dummy data", 'writer-ancora'),
						"description" => wp_kses( __("Dummy data - not used in shortcodes", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					)
				)
			) );
			
			class WPBakeryShortCode_Shop_Messages extends WRITER_ANCORA_VC_ShortCodeAlone {}
		
		
			// WooCommerce - Product Page
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "product_page",
				"name" => esc_html__("Product Page", 'writer-ancora'),
				"description" => wp_kses( __("WooCommerce shortcode: display single product page", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
				"category" => esc_html__('WooCommerce', 'writer-ancora'),
				'icon' => 'icon_trx_product_page',
				"class" => "trx_sc_single trx_sc_product_page",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "sku",
						"heading" => esc_html__("SKU", 'writer-ancora'),
						"description" => wp_kses( __("SKU code of displayed product", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "id",
						"heading" => esc_html__("ID", 'writer-ancora'),
						"description" => wp_kses( __("ID of displayed product", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "posts_per_page",
						"heading" => esc_html__("Number", 'writer-ancora'),
						"description" => wp_kses( __("How many products showed", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"admin_label" => true,
						"class" => "",
						"value" => "1",
						"type" => "textfield"
					),
					array(
						"param_name" => "post_type",
						"heading" => esc_html__("Post type", 'writer-ancora'),
						"description" => wp_kses( __("Post type for the WP query (leave 'product')", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"class" => "",
						"value" => "product",
						"type" => "textfield"
					),
					array(
						"param_name" => "post_status",
						"heading" => esc_html__("Post status", 'writer-ancora'),
						"description" => wp_kses( __("Display posts only with this status", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"class" => "",
						"value" => array(
							esc_html__('Publish', 'writer-ancora') => 'publish',
							esc_html__('Protected', 'writer-ancora') => 'protected',
							esc_html__('Private', 'writer-ancora') => 'private',
							esc_html__('Pending', 'writer-ancora') => 'pending',
							esc_html__('Draft', 'writer-ancora') => 'draft'
						),
						"type" => "dropdown"
					)
				)
			) );
			
			class WPBakeryShortCode_Product_Page extends WRITER_ANCORA_VC_ShortCodeSingle {}
		
		
		
			// WooCommerce - Product
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "product",
				"name" => esc_html__("Product", 'writer-ancora'),
				"description" => wp_kses( __("WooCommerce shortcode: display one product", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
				"category" => esc_html__('WooCommerce', 'writer-ancora'),
				'icon' => 'icon_trx_product',
				"class" => "trx_sc_single trx_sc_product",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "sku",
						"heading" => esc_html__("SKU", 'writer-ancora'),
						"description" => wp_kses( __("Product's SKU code", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "id",
						"heading" => esc_html__("ID", 'writer-ancora'),
						"description" => wp_kses( __("Product's ID", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					)
				)
			) );
			
			class WPBakeryShortCode_Product extends WRITER_ANCORA_VC_ShortCodeSingle {}
		
		
			// WooCommerce - Best Selling Products
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "best_selling_products",
				"name" => esc_html__("Best Selling Products", 'writer-ancora'),
				"description" => wp_kses( __("WooCommerce shortcode: show best selling products", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
				"category" => esc_html__('WooCommerce', 'writer-ancora'),
				'icon' => 'icon_trx_best_selling_products',
				"class" => "trx_sc_single trx_sc_best_selling_products",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "per_page",
						"heading" => esc_html__("Number", 'writer-ancora'),
						"description" => wp_kses( __("How many products showed", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"admin_label" => true,
						"class" => "",
						"value" => "4",
						"type" => "textfield"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", 'writer-ancora'),
						"description" => wp_kses( __("How many columns per row use for products output", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"admin_label" => true,
						"class" => "",
						"value" => "1",
						"type" => "textfield"
					)
				)
			) );
			
			class WPBakeryShortCode_Best_Selling_Products extends WRITER_ANCORA_VC_ShortCodeSingle {}
		
		
		
			// WooCommerce - Recent Products
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "recent_products",
				"name" => esc_html__("Recent Products", 'writer-ancora'),
				"description" => wp_kses( __("WooCommerce shortcode: show recent products", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
				"category" => esc_html__('WooCommerce', 'writer-ancora'),
				'icon' => 'icon_trx_recent_products',
				"class" => "trx_sc_single trx_sc_recent_products",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "per_page",
						"heading" => esc_html__("Number", 'writer-ancora'),
						"description" => wp_kses( __("How many products showed", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"admin_label" => true,
						"class" => "",
						"value" => "4",
						"type" => "textfield"

					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", 'writer-ancora'),
						"description" => wp_kses( __("How many columns per row use for products output", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"admin_label" => true,
						"class" => "",
						"value" => "1",
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => esc_html__("Order by", 'writer-ancora'),
						"description" => wp_kses( __("Sorting order for products output", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							esc_html__('Date', 'writer-ancora') => 'date',
							esc_html__('Title', 'writer-ancora') => 'title'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "order",
						"heading" => esc_html__("Order", 'writer-ancora'),
						"description" => wp_kses( __("Sorting order for products output", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip(writer_ancora_get_sc_param('ordering')),
						"type" => "dropdown"
					)
				)
			) );
			
			class WPBakeryShortCode_Recent_Products extends WRITER_ANCORA_VC_ShortCodeSingle {}
		
		
		
			// WooCommerce - Related Products
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "related_products",
				"name" => esc_html__("Related Products", 'writer-ancora'),
				"description" => wp_kses( __("WooCommerce shortcode: show related products", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
				"category" => esc_html__('WooCommerce', 'writer-ancora'),
				'icon' => 'icon_trx_related_products',
				"class" => "trx_sc_single trx_sc_related_products",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "posts_per_page",
						"heading" => esc_html__("Number", 'writer-ancora'),
						"description" => wp_kses( __("How many products showed", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"admin_label" => true,
						"class" => "",
						"value" => "4",
						"type" => "textfield"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", 'writer-ancora'),
						"description" => wp_kses( __("How many columns per row use for products output", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"admin_label" => true,
						"class" => "",
						"value" => "1",
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => esc_html__("Order by", 'writer-ancora'),
						"description" => wp_kses( __("Sorting order for products output", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							esc_html__('Date', 'writer-ancora') => 'date',
							esc_html__('Title', 'writer-ancora') => 'title'
						),
						"type" => "dropdown"
					)
				)
			) );
			
			class WPBakeryShortCode_Related_Products extends WRITER_ANCORA_VC_ShortCodeSingle {}
		
		
		
			// WooCommerce - Featured Products
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "featured_products",
				"name" => esc_html__("Featured Products", 'writer-ancora'),
				"description" => wp_kses( __("WooCommerce shortcode: show featured products", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
				"category" => esc_html__('WooCommerce', 'writer-ancora'),
				'icon' => 'icon_trx_featured_products',
				"class" => "trx_sc_single trx_sc_featured_products",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "per_page",
						"heading" => esc_html__("Number", 'writer-ancora'),
						"description" => wp_kses( __("How many products showed", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"admin_label" => true,
						"class" => "",
						"value" => "4",
						"type" => "textfield"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", 'writer-ancora'),
						"description" => wp_kses( __("How many columns per row use for products output", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"admin_label" => true,
						"class" => "",
						"value" => "1",
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => esc_html__("Order by", 'writer-ancora'),
						"description" => wp_kses( __("Sorting order for products output", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							esc_html__('Date', 'writer-ancora') => 'date',
							esc_html__('Title', 'writer-ancora') => 'title'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "order",
						"heading" => esc_html__("Order", 'writer-ancora'),
						"description" => wp_kses( __("Sorting order for products output", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip(writer_ancora_get_sc_param('ordering')),
						"type" => "dropdown"
					)
				)
			) );
			
			class WPBakeryShortCode_Featured_Products extends WRITER_ANCORA_VC_ShortCodeSingle {}
		
		
		
			// WooCommerce - Top Rated Products
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "top_rated_products",
				"name" => esc_html__("Top Rated Products", 'writer-ancora'),
				"description" => wp_kses( __("WooCommerce shortcode: show top rated products", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
				"category" => esc_html__('WooCommerce', 'writer-ancora'),
				'icon' => 'icon_trx_top_rated_products',
				"class" => "trx_sc_single trx_sc_top_rated_products",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "per_page",
						"heading" => esc_html__("Number", 'writer-ancora'),
						"description" => wp_kses( __("How many products showed", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"admin_label" => true,
						"class" => "",
						"value" => "4",
						"type" => "textfield"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", 'writer-ancora'),
						"description" => wp_kses( __("How many columns per row use for products output", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"admin_label" => true,
						"class" => "",
						"value" => "1",
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => esc_html__("Order by", 'writer-ancora'),
						"description" => wp_kses( __("Sorting order for products output", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							esc_html__('Date', 'writer-ancora') => 'date',
							esc_html__('Title', 'writer-ancora') => 'title'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "order",
						"heading" => esc_html__("Order", 'writer-ancora'),
						"description" => wp_kses( __("Sorting order for products output", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip(writer_ancora_get_sc_param('ordering')),
						"type" => "dropdown"
					)
				)
			) );
			
			class WPBakeryShortCode_Top_Rated_Products extends WRITER_ANCORA_VC_ShortCodeSingle {}
		
		
		
			// WooCommerce - Sale Products
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "sale_products",
				"name" => esc_html__("Sale Products", 'writer-ancora'),
				"description" => wp_kses( __("WooCommerce shortcode: list products on sale", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
				"category" => esc_html__('WooCommerce', 'writer-ancora'),
				'icon' => 'icon_trx_sale_products',
				"class" => "trx_sc_single trx_sc_sale_products",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "per_page",
						"heading" => esc_html__("Number", 'writer-ancora'),
						"description" => wp_kses( __("How many products showed", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"admin_label" => true,
						"class" => "",
						"value" => "4",
						"type" => "textfield"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", 'writer-ancora'),
						"description" => wp_kses( __("How many columns per row use for products output", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"admin_label" => true,
						"class" => "",
						"value" => "1",
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => esc_html__("Order by", 'writer-ancora'),
						"description" => wp_kses( __("Sorting order for products output", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							esc_html__('Date', 'writer-ancora') => 'date',
							esc_html__('Title', 'writer-ancora') => 'title'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "order",
						"heading" => esc_html__("Order", 'writer-ancora'),
						"description" => wp_kses( __("Sorting order for products output", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip(writer_ancora_get_sc_param('ordering')),
						"type" => "dropdown"
					)
				)
			) );
			
			class WPBakeryShortCode_Sale_Products extends WRITER_ANCORA_VC_ShortCodeSingle {}
		
		
		
			// WooCommerce - Product Category
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "product_category",
				"name" => esc_html__("Products from category", 'writer-ancora'),
				"description" => wp_kses( __("WooCommerce shortcode: list products in specified category(-ies)", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
				"category" => esc_html__('WooCommerce', 'writer-ancora'),
				'icon' => 'icon_trx_product_category',
				"class" => "trx_sc_single trx_sc_product_category",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "per_page",
						"heading" => esc_html__("Number", 'writer-ancora'),
						"description" => wp_kses( __("How many products showed", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"admin_label" => true,
						"class" => "",
						"value" => "4",
						"type" => "textfield"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", 'writer-ancora'),
						"description" => wp_kses( __("How many columns per row use for products output", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"admin_label" => true,
						"class" => "",
						"value" => "1",
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => esc_html__("Order by", 'writer-ancora'),
						"description" => wp_kses( __("Sorting order for products output", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							esc_html__('Date', 'writer-ancora') => 'date',
							esc_html__('Title', 'writer-ancora') => 'title'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "order",
						"heading" => esc_html__("Order", 'writer-ancora'),
						"description" => wp_kses( __("Sorting order for products output", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip(writer_ancora_get_sc_param('ordering')),
						"type" => "dropdown"
					),
					array(
						"param_name" => "category",
						"heading" => esc_html__("Categories", 'writer-ancora'),
						"description" => wp_kses( __("Comma separated category slugs", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "operator",
						"heading" => esc_html__("Operator", 'writer-ancora'),
						"description" => wp_kses( __("Categories operator", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							esc_html__('IN', 'writer-ancora') => 'IN',
							esc_html__('NOT IN', 'writer-ancora') => 'NOT IN',
							esc_html__('AND', 'writer-ancora') => 'AND'
						),
						"type" => "dropdown"
					)
				)
			) );
			
			class WPBakeryShortCode_Product_Category extends WRITER_ANCORA_VC_ShortCodeSingle {}
		
		
		
			// WooCommerce - Products
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "products",
				"name" => esc_html__("Products", 'writer-ancora'),
				"description" => wp_kses( __("WooCommerce shortcode: list all products", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
				"category" => esc_html__('WooCommerce', 'writer-ancora'),
				'icon' => 'icon_trx_products',
				"class" => "trx_sc_single trx_sc_products",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "skus",
						"heading" => esc_html__("SKUs", 'writer-ancora'),
						"description" => wp_kses( __("Comma separated SKU codes of products", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "ids",
						"heading" => esc_html__("IDs", 'writer-ancora'),
						"description" => wp_kses( __("Comma separated ID of products", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", 'writer-ancora'),
						"description" => wp_kses( __("How many columns per row use for products output", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"admin_label" => true,
						"class" => "",
						"value" => "1",
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => esc_html__("Order by", 'writer-ancora'),
						"description" => wp_kses( __("Sorting order for products output", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							esc_html__('Date', 'writer-ancora') => 'date',
							esc_html__('Title', 'writer-ancora') => 'title'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "order",
						"heading" => esc_html__("Order", 'writer-ancora'),
						"description" => wp_kses( __("Sorting order for products output", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip(writer_ancora_get_sc_param('ordering')),
						"type" => "dropdown"
					)
				)
			) );
			
			class WPBakeryShortCode_Products extends WRITER_ANCORA_VC_ShortCodeSingle {}
		
		
		
		
			// WooCommerce - Product Attribute
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "product_attribute",
				"name" => esc_html__("Products by Attribute", 'writer-ancora'),
				"description" => wp_kses( __("WooCommerce shortcode: show products with specified attribute", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
				"category" => esc_html__('WooCommerce', 'writer-ancora'),
				'icon' => 'icon_trx_product_attribute',
				"class" => "trx_sc_single trx_sc_product_attribute",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "per_page",
						"heading" => esc_html__("Number", 'writer-ancora'),
						"description" => wp_kses( __("How many products showed", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"admin_label" => true,
						"class" => "",
						"value" => "4",
						"type" => "textfield"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", 'writer-ancora'),
						"description" => wp_kses( __("How many columns per row use for products output", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"admin_label" => true,
						"class" => "",
						"value" => "1",
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => esc_html__("Order by", 'writer-ancora'),
						"description" => wp_kses( __("Sorting order for products output", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							esc_html__('Date', 'writer-ancora') => 'date',
							esc_html__('Title', 'writer-ancora') => 'title'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "order",
						"heading" => esc_html__("Order", 'writer-ancora'),
						"description" => wp_kses( __("Sorting order for products output", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip(writer_ancora_get_sc_param('ordering')),
						"type" => "dropdown"
					),
					array(
						"param_name" => "attribute",
						"heading" => esc_html__("Attribute", 'writer-ancora'),
						"description" => wp_kses( __("Attribute name", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "filter",
						"heading" => esc_html__("Filter", 'writer-ancora'),
						"description" => wp_kses( __("Attribute value", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					)
				)
			) );
			
			class WPBakeryShortCode_Product_Attribute extends WRITER_ANCORA_VC_ShortCodeSingle {}
		
		
		
			// WooCommerce - Products Categories
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "product_categories",
				"name" => esc_html__("Product Categories", 'writer-ancora'),
				"description" => wp_kses( __("WooCommerce shortcode: show categories with products", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
				"category" => esc_html__('WooCommerce', 'writer-ancora'),
				'icon' => 'icon_trx_product_categories',
				"class" => "trx_sc_single trx_sc_product_categories",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "number",
						"heading" => esc_html__("Number", 'writer-ancora'),
						"description" => wp_kses( __("How many categories showed", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"admin_label" => true,
						"class" => "",
						"value" => "4",
						"type" => "textfield"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", 'writer-ancora'),
						"description" => wp_kses( __("How many columns per row use for categories output", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"admin_label" => true,
						"class" => "",
						"value" => "1",
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => esc_html__("Order by", 'writer-ancora'),
						"description" => wp_kses( __("Sorting order for products output", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							esc_html__('Date', 'writer-ancora') => 'date',
							esc_html__('Title', 'writer-ancora') => 'title'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "order",
						"heading" => esc_html__("Order", 'writer-ancora'),
						"description" => wp_kses( __("Sorting order for products output", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip(writer_ancora_get_sc_param('ordering')),
						"type" => "dropdown"
					),
					array(
						"param_name" => "parent",
						"heading" => esc_html__("Parent", 'writer-ancora'),
						"description" => wp_kses( __("Parent category slug", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"admin_label" => true,
						"class" => "",
						"value" => "date",
						"type" => "textfield"
					),
					array(
						"param_name" => "ids",
						"heading" => esc_html__("IDs", 'writer-ancora'),
						"description" => wp_kses( __("Comma separated ID of products", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "hide_empty",
						"heading" => esc_html__("Hide empty", 'writer-ancora'),
						"description" => wp_kses( __("Hide empty categories", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"class" => "",
						"value" => array("Hide empty" => "1" ),
						"type" => "checkbox"
					)
				)
			) );
			
			class WPBakeryShortCode_Products_Categories extends WRITER_ANCORA_VC_ShortCodeSingle {}
		
			/*
		
			// WooCommerce - Add to cart
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "add_to_cart",
				"name" => esc_html__("Add to cart", 'writer-ancora'),
				"description" => wp_kses( __("WooCommerce shortcode: Display a single product price + cart button", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
				"category" => esc_html__('WooCommerce', 'writer-ancora'),
				'icon' => 'icon_trx_add_to_cart',
				"class" => "trx_sc_single trx_sc_add_to_cart",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "id",
						"heading" => esc_html__("ID", 'writer-ancora'),
						"description" => wp_kses( __("Product's ID", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "sku",
						"heading" => esc_html__("SKU", 'writer-ancora'),
						"description" => wp_kses( __("Product's SKU code", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "quantity",
						"heading" => esc_html__("Quantity", 'writer-ancora'),
						"description" => wp_kses( __("How many item add", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"admin_label" => true,
						"class" => "",
						"value" => "1",
						"type" => "textfield"
					),
					array(
						"param_name" => "show_price",
						"heading" => esc_html__("Show price", 'writer-ancora'),
						"description" => wp_kses( __("Show price near button", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"class" => "",
						"value" => array("Show price" => "true" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "class",
						"heading" => esc_html__("Class", 'writer-ancora'),
						"description" => wp_kses( __("CSS class", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "style",
						"heading" => esc_html__("CSS style", 'writer-ancora'),
						"description" => wp_kses( __("CSS style for additional decoration", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					)
				)
			) );
			
			class WPBakeryShortCode_Add_To_Cart extends WRITER_ANCORA_VC_ShortCodeSingle {}
			*/
		}
	}
}

?>