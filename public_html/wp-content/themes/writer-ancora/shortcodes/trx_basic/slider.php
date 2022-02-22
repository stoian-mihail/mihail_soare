<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('writer_ancora_sc_slider_theme_setup')) {
	add_action( 'writer_ancora_action_before_init_theme', 'writer_ancora_sc_slider_theme_setup' );
	function writer_ancora_sc_slider_theme_setup() {
		add_action('writer_ancora_action_shortcodes_list', 		'writer_ancora_sc_slider_reg_shortcodes');
		if (function_exists('writer_ancora_exists_visual_composer') && writer_ancora_exists_visual_composer())
			add_action('writer_ancora_action_shortcodes_list_vc','writer_ancora_sc_slider_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_slider id="unique_id" engine="revo|royal|flex|swiper|chop" alias="revolution_slider_alias|royal_slider_id" titles="no|slide|fixed" cat="id|slug" count="posts_number" ids="comma_separated_id_list" offset="" width="" height="" align="" top="" bottom=""]
[trx_slider_item src="image_url"]
[/trx_slider]
*/

if (!function_exists('writer_ancora_sc_slider')) {	
	function writer_ancora_sc_slider($atts, $content=null){	
		if (writer_ancora_in_shortcode_blogger()) return '';
		extract(writer_ancora_html_decode(shortcode_atts(array(
			// Individual params
			"engine" => 'swiper',
			"custom" => "no",
			"alias" => "",
			"post_type" => "post",
			"ids" => "",
			"cat" => "",
			"count" => "0",
			"offset" => "",
			"orderby" => "date",
			"order" => "desc",
			"controls" => "no",
			"pagination" => "no",
			"slides_space" => 0,
			"slides_per_view" => 1,
			"titles" => "no",
			"descriptions" => writer_ancora_get_custom_option('slider_info_descriptions'),
			"links" => "no",
			"align" => "",
			"interval" => "",
			"date_format" => "",
			"crop" => "yes",
			"autoheight" => "no",
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

		if (empty($width) && $pagination!='full') $width = "100%";
		if (empty($height) && ($pagination=='full' || $pagination=='over')) $height = 250;
		if (!empty($height) && writer_ancora_param_is_on($autoheight)) $autoheight = "off";
		if (empty($interval)) $interval = mt_rand(5000, 10000);
		if (empty($custom)) $custom = 'no';
		if (empty($controls)) $controls = 'no';
		if (empty($pagination)) $pagination = 'no';
		if (empty($titles)) $titles = 'no';
		if (empty($links)) $links = 'no';
		if (empty($autoheight)) $autoheight = 'no';
		if (empty($crop)) $crop = 'no';

		writer_ancora_storage_set('sc_slider_data', array(
			'engine' => $engine,
            'width'  => writer_ancora_prepare_css_value($width),
            'height' => writer_ancora_prepare_css_value($height),
            'links'  => writer_ancora_param_is_on($links),
            'bg_image' => writer_ancora_get_theme_setting('slides_type')=='bg',
            'crop_image' => $crop
            )
        );
	
		if (empty($id)) $id = "sc_slider_".str_replace('.', '', mt_rand());
		
		$class2 = writer_ancora_get_css_position_as_classes($top, $right, $bottom, $left);
		$ws = writer_ancora_get_css_dimensions_from_values($width);
		$hs = writer_ancora_get_css_dimensions_from_values('', $height);
	
		$css .= ($hs) . ($ws);
		
		if ($engine!='swiper' && in_array($pagination, array('full', 'over'))) $pagination = 'yes';
		
		$output = (in_array($pagination, array('full', 'over')) 
					? '<div class="sc_slider_pagination_area sc_slider_pagination_'.esc_attr($pagination)
							. ($align!='' && $align!='none' ? ' align'.esc_attr($align) : '')
							. ($class2 ? ' '.esc_attr($class2) : '')
							. '"'
						. (!writer_ancora_param_is_off($animation) ? ' data-animation="'.esc_attr(writer_ancora_get_animation_classes($animation)).'"' : '')
						. ($hs ? ' style="'.esc_attr($hs).'"' : '') 
						.'>' 
					: '')
				. '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_slider sc_slider_' . esc_attr($engine)
					. ($engine=='swiper' ? ' swiper-slider-container' : '')
					. (!empty($class) ? ' '.esc_attr($class) : '')
					. (writer_ancora_param_is_on($autoheight) ? ' sc_slider_height_auto' : '')
					. ($hs ? ' sc_slider_height_fixed' : '')
					. (writer_ancora_param_is_on($controls) ? ' sc_slider_controls' : ' sc_slider_nocontrols')
					. (writer_ancora_param_is_on($pagination) ? ' sc_slider_pagination' : ' sc_slider_nopagination')
					. (writer_ancora_storage_get_array('sc_slider_data', 'bg_image') ? ' sc_slider_bg' : ' sc_slider_images')
					. (!in_array($pagination, array('full', 'over')) 
							? ($class2 ? ' '.esc_attr($class2) : '') . ($align!='' && $align!='none' ? ' align'.esc_attr($align) : '')
							: '')
					. '"'
				. (!in_array($pagination, array('full', 'over')) && !writer_ancora_param_is_off($animation) ? ' data-animation="'.esc_attr(writer_ancora_get_animation_classes($animation)).'"' : '')
				. ($slides_space > 0 ? ' data-slides-space="' . esc_attr($slides_space) . '"' : '')
				. ($slides_per_view > 1 ? ' data-slides-per_view="' . esc_attr($slides_per_view) . '"' : '')
				. (!empty($width) && writer_ancora_strpos($width, '%')===false ? ' data-old-width="' . esc_attr($width) . '"' : '')
				. (!empty($height) && writer_ancora_strpos($height, '%')===false ? ' data-old-height="' . esc_attr($height) . '"' : '')
				. ((int) $interval > 0 ? ' data-interval="'.esc_attr($interval).'"' : '')
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
			. '>';
	
		writer_ancora_enqueue_slider($engine);
	
		if ($engine=='revo') {
			if (!empty($alias))
				$output .= do_shortcode('[rev_slider '.esc_attr($alias).']');
			else
				$output = '';
		} else if ($engine=='swiper') {
			
			$caption = '';
	
			$output .= '<div class="slides'
				.($engine=='swiper' ? ' swiper-wrapper' : '').'"'
				.($engine=='swiper' && writer_ancora_storage_get_array('sc_slider_data', 'bg_image') ? ' style="'.esc_attr($hs).'"' : '')
				.'>';
	
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
					'post_type' => 'post',
					'post_status' => 'publish',
					'posts_per_page' => $count,
					'ignore_sticky_posts' => true,
					'order' => $order=='asc' ? 'asc' : 'desc',
				);
		
				if ($offset > 0 && empty($ids)) {
					$args['offset'] = $offset;
				}
		
				$args = writer_ancora_query_add_sort_order($args, $orderby, $order);
				$args = writer_ancora_query_add_filters($args, 'thumbs');
				$args = writer_ancora_query_add_posts_and_cats($args, $ids, $post_type, $cat);
	
				$query = new WP_Query( $args );
	
				$post_number = 0;
				$pagination_items = '';
				$show_image 	= 1;
				$show_types 	= 0;
				$show_date 		= 1;
				$show_author 	= 0;
				$show_links 	= 0;
				$show_counters	= 'views';	//comments | rating
				
				while ( $query->have_posts() ) { 
					$query->the_post();
					$post_number++;
					$post_id = get_the_ID();
					$post_type = get_post_type();
					$post_title = get_the_title();
					$post_link = get_permalink();
					$post_date = get_the_date(!empty($date_format) ? $date_format : 'd.m.y');
					$post_attachment = wp_get_attachment_url(get_post_thumbnail_id($post_id));
					if (writer_ancora_param_is_on($crop)) {
						$post_attachment = writer_ancora_storage_get_array('sc_slider_data', 'bg_image')
							? writer_ancora_get_resized_image_url($post_attachment, !empty($width) && (float) $width.' ' == $width.' ' ? $width : null, !empty($height) && (float) $height.' ' == $height.' ' ? $height : null)
							: writer_ancora_get_resized_image_tag($post_attachment, !empty($width) && (float) $width.' ' == $width.' ' ? $width : null, !empty($height) && (float) $height.' ' == $height.' ' ? $height : null);
					} else if (!writer_ancora_storage_get_array('sc_slider_data', 'bg_image')) {
						$post_attachment = '<img src="'.esc_url($post_attachment).'" alt="">';
					}
					$post_accent_color = '';
					$post_category = '';
					$post_category_link = '';
	
					if (in_array($pagination, array('full', 'over'))) {
						$old_output = $output;
						$output = '';
						if (file_exists(writer_ancora_get_file_dir('templates/_parts/widgets-posts.php'))) {
							require writer_ancora_get_file_dir('templates/_parts/widgets-posts.php');
						}
						$pagination_items .= $output;
						$output = $old_output;
					}
					$output .= '<div' 
						. ' class="'.esc_attr($engine).'-slide"'
						. ' data-style="'.esc_attr(($ws).($hs)).'"'
						. ' style="'
							. (writer_ancora_storage_get_array('sc_slider_data', 'bg_image') ? 'background-image:url(' . esc_url($post_attachment) . ');' : '') . ($ws) . ($hs)
							. '"'
						. '>' 
						. (writer_ancora_param_is_on($links) ? '<a href="'.esc_url($post_link).'" title="'.esc_attr($post_title).'">' : '')
						. (!writer_ancora_storage_get_array('sc_slider_data', 'bg_image') ? $post_attachment : '')
						;
					$caption = $engine=='swiper' ? '' : $caption;
					if (!writer_ancora_param_is_off($titles)) {
						$post_hover_bg  = writer_ancora_get_scheme_color('accent1');
						$post_bg = '';
						if ($post_hover_bg!='' && !writer_ancora_is_inherit_option($post_hover_bg)) {
							$rgb = writer_ancora_hex2rgb($post_hover_bg);
							$post_hover_ie = str_replace('#', '', $post_hover_bg);
							$post_bg = "background-color: rgba({$rgb['r']},{$rgb['g']},{$rgb['b']},0.8);";
						}
						$caption .= '<div class="sc_slider_info' . ($titles=='fixed' ? ' sc_slider_info_fixed' : '') . ($engine=='swiper' ? ' content-slide' : '') . '"'.($post_bg!='' ? ' style="'.esc_attr($post_bg).'"' : '').'>';
						$post_descr = writer_ancora_get_post_excerpt();
						if (writer_ancora_get_custom_option("slider_info_category")=='yes') { // || empty($cat)) {
							// Get all post's categories
							$post_tax = writer_ancora_get_taxonomy_categories_by_post_type($post_type);
							if (!empty($post_tax)) {
								$post_terms = writer_ancora_get_terms_by_post_id(array('post_id'=>$post_id, 'taxonomy'=>$post_tax));
								if (!empty($post_terms[$post_tax])) {
									if (!empty($post_terms[$post_tax]->closest_parent)) {
										$post_category = $post_terms[$post_tax]->closest_parent->name;
										$post_category_link = $post_terms[$post_tax]->closest_parent->link;
									}
									if ($post_category!='') {
										$caption .= '<div class="sc_slider_category"'.(writer_ancora_substr($post_accent_color, 0, 1)=='#' ? ' style="background-color: '.esc_attr($post_accent_color).'"' : '').'><a href="'.esc_url($post_category_link).'">'.($post_category).'</a></div>';
									}
								}
							}
						}
						$output_reviews = '';
						if (writer_ancora_get_custom_option('show_reviews')=='yes' && writer_ancora_get_custom_option('slider_info_reviews')=='yes') {
							$avg_author = writer_ancora_reviews_marks_to_display(get_post_meta($post_id, 'writer_ancora_reviews_avg'.((writer_ancora_get_theme_option('reviews_first')=='author' && $orderby != 'users_rating') || $orderby == 'author_rating' ? '' : '2'), true));
							if ($avg_author > 0) {
								$output_reviews .= '<div class="sc_slider_reviews post_rating reviews_summary blog_reviews' . (writer_ancora_get_custom_option("slider_info_category")=='yes' ? ' after_category' : '') . '">'
									. '<div class="criteria_summary criteria_row">' . trim(writer_ancora_reviews_get_summary_stars($avg_author, false, false, 5)) . '</div>'
									. '</div>';
							}
						}
						if (writer_ancora_get_custom_option("slider_info_category")=='yes') $caption .= $output_reviews;
						$caption .= '<h3 class="sc_slider_subtitle"><a href="'.esc_url($post_link).'">'.($post_title).'</a></h3>';
						if (writer_ancora_get_custom_option("slider_info_category")!='yes') $caption .= $output_reviews;
						if ($descriptions > 0) {
							$caption .= '<div class="sc_slider_descr">'.trim(writer_ancora_strshort($post_descr, $descriptions)).'</div>';
						}
						$caption .= '</div>';
					}
					$output .= ($engine=='swiper' ? $caption : '') . (writer_ancora_param_is_on($links) ? '</a>' : '' ) . '</div>';
				}
				wp_reset_postdata();
			}
	
			$output .= '</div>';
			if ($engine=='swiper') {
				if (writer_ancora_param_is_on($controls))
					$output .= '<div class="sc_slider_controls_wrap"><a class="sc_slider_prev" href="#"></a><a class="sc_slider_next" href="#"></a></div>';
				if (writer_ancora_param_is_on($pagination))
					$output .= '<div class="sc_slider_pagination_wrap"></div>';
			}
		
		} else
			$output = '';
		
		if (!empty($output)) {
			$output .= '</div>';
			if (!empty($pagination_items)) {
				$output .= '
					<div class="sc_slider_pagination widget_area"'.($hs ? ' style="'.esc_attr($hs).'"' : '').'>
						<div id="'.esc_attr($id).'_scroll" class="sc_scroll sc_scroll_vertical swiper-slider-container scroll-container"'.($hs ? ' style="'.esc_attr($hs).'"' : '').'>
							<div class="sc_scroll_wrapper swiper-wrapper">
								<div class="sc_scroll_slide swiper-slide">
									'.($pagination_items).'
								</div>
							</div>
							<div id="'.esc_attr($id).'_scroll_bar" class="sc_scroll_bar sc_scroll_bar_vertical"></div>
						</div>
					</div>';
				$output .= '</div>';
			}
		}
	
		return apply_filters('writer_ancora_shortcode_output', $output, 'trx_slider', $atts, $content);
	}
	writer_ancora_require_shortcode('trx_slider', 'writer_ancora_sc_slider');
}


if (!function_exists('writer_ancora_sc_slider_item')) {	
	function writer_ancora_sc_slider_item($atts, $content=null) {
		if (writer_ancora_in_shortcode_blogger()) return '';
		extract(writer_ancora_html_decode(shortcode_atts( array(
			// Individual params
			"src" => "",
			"url" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => ""
		), $atts)));
		$src = $src!='' ? $src : $url;
		if ($src > 0) {
			$attach = wp_get_attachment_image_src( $src, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$src = $attach[0];
		}

		$engine = writer_ancora_storage_get_array('sc_slider_data', 'engine');
		$crop_image = writer_ancora_storage_get_array('sc_slider_data', 'crop_image');
		$bg_image = writer_ancora_storage_get_array('sc_slider_data', 'bg_image');
		$links = writer_ancora_storage_get_array('sc_slider_data', 'links');
		$width = writer_ancora_storage_get_array('sc_slider_data', 'width');
		$height = writer_ancora_storage_get_array('sc_slider_data', 'height');
	
		if ($src && writer_ancora_param_is_on($crop_image)) {
			$src = writer_ancora_storage_get_array('sc_slider_data', 'bg_image')
				? writer_ancora_get_resized_image_url($src, !empty($width) && writer_ancora_strpos($width, '%')===false ? $width : null, !empty($height) && writer_ancora_strpos($height, '%')===false ? $height : null)
				: writer_ancora_get_resized_image_tag($src, !empty($width) && writer_ancora_strpos($width, '%')===false ? $width : null, !empty($height) && writer_ancora_strpos($height, '%')===false ? $height : null);
		} else if ($src && !$bg_image) {
			$src = '<img src="'.esc_url($src).'" alt="">';
		}
	
		$css .= ($bg_image ? 'background-image:url(' . esc_url($src) . ');' : '')
				. (!empty($width)  ? 'width:'  . esc_attr($width)  . ';' : '')
				. (!empty($height) ? 'height:' . esc_attr($height) . ';' : '');
	
		$content = do_shortcode($content);
	
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '').' class="'.esc_attr($engine).'-slide' . (!empty($class) ? ' '.esc_attr($class) : '') . '"'
				. ($css ? ' style="'.esc_attr($css).'"' : '')
				.'>' 
				. ($src && writer_ancora_param_is_on($links) ? '<a href="'.esc_url($src).'">' : '')
				. ($src && !$bg_image ? $src : $content)
				. ($src && writer_ancora_param_is_on($links) ? '</a>' : '')
			. '</div>';
		return apply_filters('writer_ancora_shortcode_output', $output, 'trx_slider_item', $atts, $content);
	}
	writer_ancora_require_shortcode('trx_slider_item', 'writer_ancora_sc_slider_item');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'writer_ancora_sc_slider_reg_shortcodes' ) ) {
	//add_action('writer_ancora_action_shortcodes_list', 'writer_ancora_sc_slider_reg_shortcodes');
	function writer_ancora_sc_slider_reg_shortcodes() {
	
		writer_ancora_sc_map("trx_slider", array(
			"title" => esc_html__("Slider", 'writer-ancora'),
			"desc" => wp_kses( __("Insert slider into your post (page)", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
			"decorate" => true,
			"container" => false,
			"params" => array_merge(array(
				"engine" => array(
					"title" => esc_html__("Slider engine", 'writer-ancora'),
					"desc" => wp_kses( __("Select engine for slider. Attention! Swiper is built-in engine, all other engines appears only if corresponding plugings are installed", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"value" => "swiper",
					"type" => "checklist",
					"options" => writer_ancora_get_sc_param('sliders')
				),
				"align" => array(
					"title" => esc_html__("Float slider", 'writer-ancora'),
					"desc" => wp_kses( __("Float slider to left or right side", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"divider" => true,
					"value" => "",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => writer_ancora_get_sc_param('float')
				),
				"custom" => array(
					"title" => esc_html__("Custom slides", 'writer-ancora'),
					"desc" => wp_kses( __("Make custom slides from inner shortcodes (prepare it on tabs) or prepare slides from posts thumbnails", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"divider" => true,
					"value" => "no",
					"type" => "switch",
					"options" => writer_ancora_get_sc_param('yes_no')
				)
				),
				function_exists('writer_ancora_exists_revslider') && writer_ancora_exists_revslider() ? array(
				"alias" => array(
					"title" => esc_html__("Revolution slider alias", 'writer-ancora'),
					"desc" => wp_kses( __("Select Revolution slider to display", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"dependency" => array(
						'engine' => array('revo')
					),
					"divider" => true,
					"value" => "",
					"type" => "select",
					"options" => writer_ancora_get_sc_param('revo_sliders')
				)) : array(), array(
				"cat" => array(
					"title" => esc_html__("Swiper: Category list", 'writer-ancora'),
					"desc" => wp_kses( __("Select category to show post's images. If empty - select posts from any category or from IDs list", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"dependency" => array(
						'engine' => array('swiper')
					),
					"divider" => true,
					"value" => "",
					"type" => "select",
					"style" => "list",
					"multiple" => true,
					"options" => writer_ancora_array_merge(array(0 => esc_html__('- Select category -', 'writer-ancora')), writer_ancora_get_sc_param('categories'))
				),
				"count" => array(
					"title" => esc_html__("Swiper: Number of posts", 'writer-ancora'),
					"desc" => wp_kses( __("How many posts will be displayed? If used IDs - this parameter ignored.", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"dependency" => array(
						'engine' => array('swiper')
					),
					"value" => 3,
					"min" => 1,
					"max" => 100,
					"type" => "spinner"
				),
				"offset" => array(
					"title" => esc_html__("Swiper: Offset before select posts", 'writer-ancora'),
					"desc" => wp_kses( __("Skip posts before select next part.", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"dependency" => array(
						'engine' => array('swiper')
					),
					"value" => 0,
					"min" => 0,
					"type" => "spinner"
				),
				"orderby" => array(
					"title" => esc_html__("Swiper: Post order by", 'writer-ancora'),
					"desc" => wp_kses( __("Select desired posts sorting method", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"dependency" => array(
						'engine' => array('swiper')
					),
					"value" => "date",
					"type" => "select",
					"options" => writer_ancora_get_sc_param('sorting')
				),
				"order" => array(
					"title" => esc_html__("Swiper: Post order", 'writer-ancora'),
					"desc" => wp_kses( __("Select desired posts order", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"dependency" => array(
						'engine' => array('swiper')
					),
					"value" => "desc",
					"type" => "switch",
					"size" => "big",
					"options" => writer_ancora_get_sc_param('ordering')
				),
				"ids" => array(
					"title" => esc_html__("Swiper: Post IDs list", 'writer-ancora'),
					"desc" => wp_kses( __("Comma separated list of posts ID. If set - parameters above are ignored!", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"dependency" => array(
						'engine' => array('swiper')
					),
					"value" => "",
					"type" => "text"
				),
				"controls" => array(
					"title" => esc_html__("Swiper: Show slider controls", 'writer-ancora'),
					"desc" => wp_kses( __("Show arrows inside slider", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"dependency" => array(
						'engine' => array('swiper')
					),
					"divider" => true,
					"value" => "no",
					"type" => "switch",
					"options" => writer_ancora_get_sc_param('yes_no')
				),
				"pagination" => array(
					"title" => esc_html__("Swiper: Show slider pagination", 'writer-ancora'),
					"desc" => wp_kses( __("Show bullets for switch slides", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"dependency" => array(
						'engine' => array('swiper')
					),
					"value" => "no",
					"type" => "checklist",
					"options" => array(
						'no'   => esc_html__('None', 'writer-ancora'),
						'yes'  => esc_html__('Dots', 'writer-ancora'), 
						'full' => esc_html__('Side Titles', 'writer-ancora'),
						'over' => esc_html__('Over Titles', 'writer-ancora')
					)
				),
				"titles" => array(
					"title" => esc_html__("Swiper: Show titles section", 'writer-ancora'),
					"desc" => wp_kses( __("Show section with post's title and short post's description", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"dependency" => array(
						'engine' => array('swiper')
					),
					"divider" => true,
					"value" => "no",
					"type" => "checklist",
					"options" => array(
						"no"    => esc_html__('Not show', 'writer-ancora'),
						"slide" => esc_html__('Show/Hide info', 'writer-ancora'),
						"fixed" => esc_html__('Fixed info', 'writer-ancora')
					)
				),
				"descriptions" => array(
					"title" => esc_html__("Swiper: Post descriptions", 'writer-ancora'),
					"dependency" => array(
						'engine' => array('swiper')
					),
					"desc" => wp_kses( __("Show post's excerpt max length (characters)", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"value" => 0,
					"min" => 0,
					"max" => 1000,
					"step" => 10,
					"type" => "spinner"
				),
				"links" => array(
					"title" => esc_html__("Swiper: Post's title as link", 'writer-ancora'),
					"desc" => wp_kses( __("Make links from post's titles", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"dependency" => array(
						'engine' => array('swiper')
					),
					"value" => "yes",
					"type" => "switch",
					"options" => writer_ancora_get_sc_param('yes_no')
				),
				"crop" => array(
					"title" => esc_html__("Swiper: Crop images", 'writer-ancora'),
					"desc" => wp_kses( __("Crop images in each slide or live it unchanged", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"dependency" => array(
						'engine' => array('swiper')
					),
					"value" => "yes",
					"type" => "switch",
					"options" => writer_ancora_get_sc_param('yes_no')
				),
				"autoheight" => array(
					"title" => esc_html__("Swiper: Autoheight", 'writer-ancora'),
					"desc" => wp_kses( __("Change whole slider's height (make it equal current slide's height)", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"dependency" => array(
						'engine' => array('swiper')
					),
					"value" => "yes",
					"type" => "switch",
					"options" => writer_ancora_get_sc_param('yes_no')
				),
				"slides_per_view" => array(
					"title" => esc_html__("Swiper: Slides per view", 'writer-ancora'),
					"desc" => wp_kses( __("Slides per view showed in this slider", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"dependency" => array(
						'engine' => array('swiper')
					),
					"value" => 1,
					"min" => 1,
					"max" => 6,
					"step" => 1,
					"type" => "spinner"
				),
				"slides_space" => array(
					"title" => esc_html__("Swiper: Space between slides", 'writer-ancora'),
					"desc" => wp_kses( __("Size of space (in px) between slides", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"dependency" => array(
						'engine' => array('swiper')
					),
					"value" => 0,
					"min" => 0,
					"max" => 100,
					"step" => 10,
					"type" => "spinner"
				),
				"interval" => array(
					"title" => esc_html__("Swiper: Slides change interval", 'writer-ancora'),
					"desc" => wp_kses( __("Slides change interval (in milliseconds: 1000ms = 1s)", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"dependency" => array(
						'engine' => array('swiper')
					),
					"value" => 5000,
					"step" => 500,
					"min" => 0,
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
			)),
			"children" => array(
				"name" => "trx_slider_item",
				"title" => esc_html__("Slide", 'writer-ancora'),
				"desc" => wp_kses( __("Slider item", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
				"container" => false,
				"params" => array(
					"src" => array(
						"title" => esc_html__("URL (source) for image file", 'writer-ancora'),
						"desc" => wp_kses( __("Select or upload image or write URL from other site for the current slide", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"readonly" => false,
						"value" => "",
						"type" => "media"
					),
					"id" => writer_ancora_get_sc_param('id'),
					"class" => writer_ancora_get_sc_param('class'),
					"css" => writer_ancora_get_sc_param('css')
				)
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'writer_ancora_sc_slider_reg_shortcodes_vc' ) ) {
	//add_action('writer_ancora_action_shortcodes_list_vc', 'writer_ancora_sc_slider_reg_shortcodes_vc');
	function writer_ancora_sc_slider_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_slider",
			"name" => esc_html__("Slider", 'writer-ancora'),
			"description" => wp_kses( __("Insert slider", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
			"category" => esc_html__('Content', 'writer-ancora'),
			'icon' => 'icon_trx_slider',
			"class" => "trx_sc_collection trx_sc_slider",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => true,
			"as_parent" => array('only' => 'trx_slider_item'),
			"params" => array_merge(array(
				array(
					"param_name" => "engine",
					"heading" => esc_html__("Engine", 'writer-ancora'),
					"description" => wp_kses( __("Select engine for slider. Attention! Swiper is built-in engine, all other engines appears only if corresponding plugings are installed", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(writer_ancora_get_sc_param('sliders')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Float slider", 'writer-ancora'),
					"description" => wp_kses( __("Float slider to left or right side", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"class" => "",
					"value" => array_flip(writer_ancora_get_sc_param('float')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "custom",
					"heading" => esc_html__("Custom slides", 'writer-ancora'),
					"description" => wp_kses( __("Make custom slides from inner shortcodes (prepare it on tabs) or prepare slides from posts thumbnails", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"class" => "",
					"value" => array(esc_html__('Custom slides', 'writer-ancora') => 'yes'),
					"type" => "checkbox"
				)
				),
				function_exists('writer_ancora_exists_revslider') && writer_ancora_exists_revslider() ? array(
				array(
					"param_name" => "alias",
					"heading" => esc_html__("Revolution slider alias", 'writer-ancora'),
					"description" => wp_kses( __("Select Revolution slider to display", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"admin_label" => true,
					"class" => "",
					'dependency' => array(
						'element' => 'engine',
						'value' => array('revo')
					),
					"value" => array_flip(writer_ancora_array_merge(array('none' => esc_html__('- Select slider -', 'writer-ancora')), writer_ancora_get_sc_param('revo_sliders'))),
					"type" => "dropdown"
				)) : array(), array(
				array(
					"param_name" => "cat",
					"heading" => esc_html__("Categories list", 'writer-ancora'),
					"description" => wp_kses( __("Select category. If empty - show posts from any category or from IDs list", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					'dependency' => array(
						'element' => 'engine',
						'value' => array('swiper')
					),
					"class" => "",
					"value" => array_flip(writer_ancora_array_merge(array(0 => esc_html__('- Select category -', 'writer-ancora')), writer_ancora_get_sc_param('categories'))),
					"type" => "dropdown"
				),
				array(
					"param_name" => "count",
					"heading" => esc_html__("Swiper: Number of posts", 'writer-ancora'),
					"description" => wp_kses( __("How many posts will be displayed? If used IDs - this parameter ignored.", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					'dependency' => array(
						'element' => 'engine',
						'value' => array('swiper')
					),
					"class" => "",
					"value" => "3",
					"type" => "textfield"
				),
				array(
					"param_name" => "offset",
					"heading" => esc_html__("Swiper: Offset before select posts", 'writer-ancora'),
					"description" => wp_kses( __("Skip posts before select next part.", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					'dependency' => array(
						'element' => 'engine',
						'value' => array('swiper')
					),
					"class" => "",
					"value" => "0",
					"type" => "textfield"
				),
				array(
					"param_name" => "orderby",
					"heading" => esc_html__("Swiper: Post sorting", 'writer-ancora'),
					"description" => wp_kses( __("Select desired posts sorting method", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					'dependency' => array(
						'element' => 'engine',
						'value' => array('swiper')
					),
					"class" => "",
					"value" => array_flip(writer_ancora_get_sc_param('sorting')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "order",
					"heading" => esc_html__("Swiper: Post order", 'writer-ancora'),
					"description" => wp_kses( __("Select desired posts order", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					'dependency' => array(
						'element' => 'engine',
						'value' => array('swiper')
					),
					"class" => "",
					"value" => array_flip(writer_ancora_get_sc_param('ordering')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "ids",
					"heading" => esc_html__("Swiper: Post IDs list", 'writer-ancora'),
					"description" => wp_kses( __("Comma separated list of posts ID. If set - parameters above are ignored!", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					'dependency' => array(
						'element' => 'engine',
						'value' => array('swiper')
					),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "controls",
					"heading" => esc_html__("Swiper: Show slider controls", 'writer-ancora'),
					"description" => wp_kses( __("Show arrows inside slider", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"group" => esc_html__('Details', 'writer-ancora'),
					'dependency' => array(
						'element' => 'engine',
						'value' => array('swiper')
					),
					"class" => "",
					"value" => array(esc_html__('Show controls', 'writer-ancora') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "pagination",
					"heading" => esc_html__("Swiper: Show slider pagination", 'writer-ancora'),
					"description" => wp_kses( __("Show bullets or titles to switch slides", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"group" => esc_html__('Details', 'writer-ancora'),
					'dependency' => array(
						'element' => 'engine',
						'value' => array('swiper')
					),
					"class" => "",
					"std" => "no",
					"value" => array(
							esc_html__('None', 'writer-ancora') => 'no',
							esc_html__('Dots', 'writer-ancora') => 'yes', 
							esc_html__('Side Titles', 'writer-ancora') => 'full',
							esc_html__('Over Titles', 'writer-ancora') => 'over'
						),
					"type" => "dropdown"
				),
				array(
					"param_name" => "titles",
					"heading" => esc_html__("Swiper: Show titles section", 'writer-ancora'),
					"description" => wp_kses( __("Show section with post's title and short post's description", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"group" => esc_html__('Details', 'writer-ancora'),
					'dependency' => array(
						'element' => 'engine',
						'value' => array('swiper')
					),
					"class" => "",
					"value" => array(
							esc_html__('Not show', 'writer-ancora') => "no",
							esc_html__('Show/Hide info', 'writer-ancora') => "slide",
							esc_html__('Fixed info', 'writer-ancora') => "fixed"
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "descriptions",
					"heading" => esc_html__("Swiper: Post descriptions", 'writer-ancora'),
					"description" => wp_kses( __("Show post's excerpt max length (characters)", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"group" => esc_html__('Details', 'writer-ancora'),
					'dependency' => array(
						'element' => 'engine',
						'value' => array('swiper')
					),
					"class" => "",
					"value" => "0",
					"type" => "textfield"
				),
				array(
					"param_name" => "links",
					"heading" => esc_html__("Swiper: Post's title as link", 'writer-ancora'),
					"description" => wp_kses( __("Make links from post's titles", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"group" => esc_html__('Details', 'writer-ancora'),
					'dependency' => array(
						'element' => 'engine',
						'value' => array('swiper')
					),
					"class" => "",
					"value" => array(esc_html__('Titles as a links', 'writer-ancora') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "crop",
					"heading" => esc_html__("Swiper: Crop images", 'writer-ancora'),
					"description" => wp_kses( __("Crop images in each slide or live it unchanged", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"group" => esc_html__('Details', 'writer-ancora'),
					'dependency' => array(
						'element' => 'engine',
						'value' => array('swiper')
					),
					"class" => "",
					"value" => array(esc_html__('Crop images', 'writer-ancora') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "autoheight",
					"heading" => esc_html__("Swiper: Autoheight", 'writer-ancora'),
					"description" => wp_kses( __("Change whole slider's height (make it equal current slide's height)", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"group" => esc_html__('Details', 'writer-ancora'),
					'dependency' => array(
						'element' => 'engine',
						'value' => array('swiper')
					),
					"class" => "",
					"value" => array(esc_html__('Autoheight', 'writer-ancora') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "slides_per_view",
					"heading" => esc_html__("Swiper: Slides per view", 'writer-ancora'),
					"description" => wp_kses( __("Slides per view showed in this slider", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"admin_label" => true,
					"group" => esc_html__('Details', 'writer-ancora'),
					'dependency' => array(
						'element' => 'engine',
						'value' => array('swiper')
					),
					"class" => "",
					"value" => "1",
					"type" => "textfield"
				),
				array(
					"param_name" => "slides_space",
					"heading" => esc_html__("Swiper: Space between slides", 'writer-ancora'),
					"description" => wp_kses( __("Size of space (in px) between slides", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"admin_label" => true,
					"group" => esc_html__('Details', 'writer-ancora'),
					'dependency' => array(
						'element' => 'engine',
						'value' => array('swiper')
					),
					"class" => "",
					"value" => "0",
					"type" => "textfield"
				),
				array(
					"param_name" => "interval",
					"heading" => esc_html__("Swiper: Slides change interval", 'writer-ancora'),
					"description" => wp_kses( __("Slides change interval (in milliseconds: 1000ms = 1s)", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"group" => esc_html__('Details', 'writer-ancora'),
					'dependency' => array(
						'element' => 'engine',
						'value' => array('swiper')
					),
					"class" => "",
					"value" => "5000",
					"type" => "textfield"
				),
				writer_ancora_get_vc_param('id'),
				writer_ancora_get_vc_param('class'),
				writer_ancora_get_vc_param('animation'),
				writer_ancora_get_vc_param('css'),
				writer_ancora_vc_width(),
				writer_ancora_vc_height(),
				writer_ancora_get_vc_param('margin_top'),
				writer_ancora_get_vc_param('margin_bottom'),
				writer_ancora_get_vc_param('margin_left'),
				writer_ancora_get_vc_param('margin_right')
			))
		) );
		
		
		vc_map( array(
			"base" => "trx_slider_item",
			"name" => esc_html__("Slide", 'writer-ancora'),
			"description" => wp_kses( __("Slider item - single slide", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
			"show_settings_on_create" => true,
			"content_element" => true,
			"is_container" => false,
			'icon' => 'icon_trx_slider_item',
			"class" => "trx_sc_single trx_sc_slider_item",
			"as_child" => array('only' => 'trx_slider'),
			"as_parent" => array('except' => 'trx_slider'),
			"params" => array(
				array(
					"param_name" => "src",
					"heading" => esc_html__("URL (source) for image file", 'writer-ancora'),
					"description" => wp_kses( __("Select or upload image or write URL from other site for the current slide", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				writer_ancora_get_vc_param('id'),
				writer_ancora_get_vc_param('class'),
				writer_ancora_get_vc_param('css')
			)
		) );
		
		class WPBakeryShortCode_Trx_Slider extends WRITER_ANCORA_VC_ShortCodeCollection {}
		class WPBakeryShortCode_Trx_Slider_Item extends WRITER_ANCORA_VC_ShortCodeSingle {}
	}
}
?>