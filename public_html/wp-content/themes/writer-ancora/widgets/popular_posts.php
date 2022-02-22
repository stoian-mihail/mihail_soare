<?php
/**
 * Theme Widget: Most popular posts
 */

// Theme init
if (!function_exists('writer_ancora_widget_popular_posts_theme_setup')) {
	add_action( 'writer_ancora_action_before_init_theme', 'writer_ancora_widget_popular_posts_theme_setup', 1 );
	function writer_ancora_widget_popular_posts_theme_setup() {

		// Register shortcodes in the shortcodes list
		//add_action('writer_ancora_action_shortcodes_list',	'writer_ancora_widget_popular_posts_reg_shortcodes');
		if (function_exists('writer_ancora_exists_visual_composer') && writer_ancora_exists_visual_composer())
			add_action('writer_ancora_action_shortcodes_list_vc','writer_ancora_widget_popular_posts_reg_shortcodes_vc');
	}
}

// Load widget
if (!function_exists('writer_ancora_widget_popular_posts_load')) {
	add_action( 'widgets_init', 'writer_ancora_widget_popular_posts_load' );
	function writer_ancora_widget_popular_posts_load() {
		register_widget('writer_ancora_widget_popular_posts');
	}
}

// Widget Class
class writer_ancora_widget_popular_posts extends WP_Widget {

	function __construct() {
		$widget_ops = array('classname' => 'widget_popular_posts', 'description' => esc_html__('The most popular and most commented blog posts (extended)', 'writer-ancora'));
		parent::__construct( 'writer_ancora_widget_popular_posts', esc_html__('Writer - Most Popular & Commented Posts', 'writer-ancora'), $widget_ops );

		// Add thumb sizes into list
		writer_ancora_add_thumb_sizes( array( 'layout' => 'widgets', 'w' => 75, 'h' => 75, 'title'=>esc_html__('Widgets', 'writer-ancora') ) );
	}

	// Show widget
	function widget($args, $instance) {
		extract($args);

		global $post;

		$title = apply_filters('widget_title', isset($instance['title']) ? $instance['title'] : '');
		$title_tabs = array(
			isset($instance['title_popular']) 	? $instance['title_popular']	: '',
			isset($instance['title_commented'])	? $instance['title_commented']	: '',
			isset($instance['title_liked'])		? $instance['title_liked']		: ''
		);

		$post_type = isset($instance['post_type']) ? $instance['post_type'] : 'post';
		$category = isset($instance['category']) ? (int) $instance['category'] : 0;
		$taxonomy = writer_ancora_get_taxonomy_categories_by_post_type($post_type);

		$number = isset($instance['number']) ? (int) $instance['number'] : '';

		$show_date = isset($instance['show_date']) ? (int) $instance['show_date'] : 0;
		$show_image = isset($instance['show_image']) ? (int) $instance['show_image'] : 0;
		$show_author = isset($instance['show_author']) ? (int) $instance['show_author'] : 0;
		$show_counters = isset($instance['show_counters']) && $instance['show_counters'] > 0 ? writer_ancora_get_theme_option('blog_counters') : '';

		$titles = '';
		$content = '';
		$id = 'widget_popular_posts_'.str_replace('.', '', mt_rand());

		for ($i=0; $i<3; $i++) {
			
			if ( empty($title_tabs[$i]) ) continue;

			$args = array(
				'post_type' => $post_type,
				'post_status' => current_user_can('read_private_pages') && current_user_can('read_private_posts') ? array('publish', 'private') : 'publish',
				'post_password' => '',
				'posts_per_page' => $number,
				'ignore_sticky_posts' => true,
				'order' => 'DESC',
			);
			if ($i==0) {			// Most popular
				$args['meta_key'] = 'writer_ancora_post_views_count';
				$args['orderby'] = 'meta_value_num';
				$show_counters = $show_counters ? 'views' : '';
			} else if ($i==2) {		// Most liked
				$args['meta_key'] = 'writer_ancora_post_likes_count';
				$args['orderby'] = 'meta_value_num';
				$show_counters = $show_counters ? 'likes' : '';
			} else {				// Most commented
				$args['orderby'] = 'comment_count';
				$show_counters = $show_counters ? 'comments' : '';
			}
			if ($category > 0) {
				if ($taxonomy=='category')
					$args['cat'] = $category;
				else {
					$args['tax_query'] = array(
						array(
							'taxonomy' => $taxonomy,
							'field' => 'id',
							'terms' => $category
						)
					);
				}
			}
			$ex = writer_ancora_get_theme_option('exclude_cats');
			if (!empty($ex)) {
				$args['category__not_in'] = explode(',', $ex);
			}
			
			$q = new WP_Query($args); 
			
			if ($q->have_posts()) {
				$post_number = 0;
				$output = '';
				while ($q->have_posts()) { $q->the_post();
					$post_number++;
					require writer_ancora_get_file_dir('templates/_parts/widgets-posts.php');
					if ($post_number >= $number) break;
				}
				if ( !empty($output) ) {
					$titles .= '<li class="sc_tabs_title"><a href="#'.$id.'_'.esc_attr($i).'">'.esc_html($title_tabs[$i]).'</a></li>';
					$content .= '<div id="'.$id.'_'.esc_attr($i).'" class="widget_popular_posts_tab_content sc_tabs_content">' . $output . '</div>';
				}
			}
		}


		wp_reset_postdata();

		
		if ( !empty($titles) ) {
	
			// Before widget (defined by themes)
			echo trim($before_widget);
			
			// Display the widget title if one was input (before and after defined by themes)
			if ($title) echo trim($before_title . $title . $after_title);

			echo '<div id="'.$id.'" class="widget_popular_posts_content sc_tabs sc_tabs_style_2 no_jquery_ui">'
					. '<ul class="widget_popular_posts_tab_titles sc_tabs_titles">' . trim($titles) . '</ul>'
					. $content
				. '</div>';
			
			// After widget (defined by themes)
			echo trim($after_widget);
		}
	}

	// Update the widget settings.
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['title_popular'] = strip_tags($new_instance['title_popular']);
		$instance['title_commented'] = strip_tags($new_instance['title_commented']);
		$instance['title_liked'] = strip_tags($new_instance['title_liked']);
		$instance['number'] = (int) $new_instance['number'];
		$instance['show_date'] = (int) $new_instance['show_date'];
		$instance['show_image'] = (int) $new_instance['show_image'];
		$instance['show_author'] = (int) $new_instance['show_author'];
		$instance['show_counters'] = (int) $new_instance['show_counters'];
		$instance['category'] = (int) $new_instance['category'];
		$instance['post_type'] = strip_tags( $new_instance['post_type'] );
		return $instance;
	}

	// Displays the widget settings controls on the widget panel.
	function form($instance) {

		// Set up some default widget settings
		$instance = wp_parse_args( (array) $instance, array(
			'title' => '', 
			'title_popular' => '', 
			'title_commented' => '', 
			'title_liked' => '', 
			'number' => '4', 
			'show_date' => '1', 
			'show_image' => '1', 
			'show_author' => '1', 
			'show_counters' => '1', 
			'category'=>'0',
			'post_type' => 'post'
			)
		);
		$title = $instance['title'];
		$title_popular = $instance['title_popular'];
		$title_commented = $instance['title_commented'];
		$title_liked = $instance['title_liked'];
		$number = (int) $instance['number'];
		$show_date = (int) $instance['show_date'];
		$show_image = (int) $instance['show_image'];
		$show_author = (int) $instance['show_author'];
		$show_counters = (int) $instance['show_counters'];
		$post_type = $instance['post_type'];
		$category = (int) $instance['category'];

		$posts_types = writer_ancora_get_list_posts_types(false);
		$categories = writer_ancora_get_list_terms(false, writer_ancora_get_taxonomy_categories_by_post_type($post_type));
		?>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Widget title:', 'writer-ancora'); ?></label>
			<input id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" value="<?php echo esc_attr($title); ?>" style="width:100%;" />
		</p>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id('title_popular')); ?>"><?php esc_html_e('Most popular tab title:', 'writer-ancora'); ?></label>
			<input id="<?php echo esc_attr($this->get_field_id('title_popular')); ?>" name="<?php echo esc_attr($this->get_field_name('title_popular')); ?>" value="<?php echo esc_attr($title_popular); ?>" style="width:100%;" />
		</p>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id('title_commented')); ?>"><?php esc_html_e('Most commented tab title:', 'writer-ancora'); ?></label>
			<input id="<?php echo esc_attr($this->get_field_id('title_commented')); ?>" name="<?php echo esc_attr($this->get_field_name('title_commented')); ?>" value="<?php echo esc_attr($title_commented); ?>" style="width:100%;" />
		</p>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id('title_liked')); ?>"><?php esc_html_e('Most liked tab title:', 'writer-ancora'); ?></label>
			<input id="<?php echo esc_attr($this->get_field_id('title_liked')); ?>" name="<?php echo esc_attr($this->get_field_name('title_liked')); ?>" value="<?php echo esc_attr($title_liked); ?>" style="width:100%;" />
		</p>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id('post_type')); ?>"><?php esc_html_e('Post type:', 'writer-ancora'); ?></label>
			<select id="<?php echo esc_attr($this->get_field_id('post_type')); ?>" name="<?php echo esc_attr($this->get_field_name('post_type')); ?>" style="width:100%;" onchange="writer_ancora_admin_change_post_type(this);">
			<?php
				if (is_array($posts_types) && count($posts_types) > 0) {
					foreach ($posts_types as $type => $type_name) {
						echo '<option value="'.esc_attr($type).'"'.($post_type==$type ? ' selected="selected"' : '').'>'.esc_html($type_name).'</option>';
					}
				}
			?>
			</select>
		</p>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id('category')); ?>"><?php esc_html_e('Category:', 'writer-ancora'); ?></label>
			<select id="<?php echo esc_attr($this->get_field_id('category')); ?>" name="<?php echo esc_attr($this->get_field_name('category')); ?>" style="width:100%;">
				<option value="0"><?php esc_html_e('-- Any category --', 'writer-ancora'); ?></option> 
				<?php
				if (is_array($categories) && count($categories) > 0) {
					foreach ($categories as $cat_id => $cat_name) {
						echo '<option value="'.esc_attr($cat_id).'"'.($category==$cat_id ? ' selected="selected"' : '').'>'.esc_html($cat_name).'</option>';
					}
				}
				?>
			</select>
		</p>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id('number')); ?>"><?php esc_html_e('Number posts to show:', 'writer-ancora'); ?></label>
			<input type="text" id="<?php echo esc_attr($this->get_field_id('number')); ?>" name="<?php echo esc_attr($this->get_field_name('number')); ?>" value="<?php echo esc_attr($number); ?>" style="width:100%;" />
		</p>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id('show_image')); ?>_1"><?php esc_html_e('Show post image:', 'writer-ancora'); ?></label><br />
			<input type="radio" id="<?php echo esc_attr($this->get_field_id('show_image')); ?>_1" name="<?php echo esc_attr($this->get_field_name('show_image')); ?>" value="1" <?php echo (1==$show_image ? ' checked="checked"' : ''); ?> />
			<label for="<?php echo esc_attr($this->get_field_id('show_image')); ?>_1"><?php esc_html_e('Show', 'writer-ancora'); ?></label>
			<input type="radio" id="<?php echo esc_attr($this->get_field_id('show_image')); ?>_0" name="<?php echo esc_attr($this->get_field_name('show_image')); ?>" value="0" <?php echo (0==$show_image ? ' checked="checked"' : ''); ?> />
			<label for="<?php echo esc_attr($this->get_field_id('show_image')); ?>_0"><?php esc_html_e('Hide', 'writer-ancora'); ?></label>
		</p>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id('show_author')); ?>_1"><?php esc_html_e('Show post author:', 'writer-ancora'); ?></label><br />
			<input type="radio" id="<?php echo esc_attr($this->get_field_id('show_author')); ?>_1" name="<?php echo esc_attr($this->get_field_name('show_author')); ?>" value="1" <?php echo (1==$show_author ? ' checked="checked"' : ''); ?> />
			<label for="<?php echo esc_attr($this->get_field_id('show_author')); ?>_1"><?php esc_html_e('Show', 'writer-ancora'); ?></label>
			<input type="radio" id="<?php echo esc_attr($this->get_field_id('show_author')); ?>_0" name="<?php echo esc_attr($this->get_field_name('show_author')); ?>" value="0" <?php echo (0==$show_author ? ' checked="checked"' : ''); ?> />
			<label for="<?php echo esc_attr($this->get_field_id('show_author')); ?>_0"><?php esc_html_e('Hide', 'writer-ancora'); ?></label>
		</p>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id('show_date')); ?>_1"><?php esc_html_e('Show post date:', 'writer-ancora'); ?></label><br />
			<input type="radio" id="<?php echo esc_attr($this->get_field_id('show_date')); ?>_1" name="<?php echo esc_attr($this->get_field_name('show_date')); ?>" value="1" <?php echo (1==$show_date ? ' checked="checked"' : ''); ?> />
			<label for="<?php echo esc_attr($this->get_field_id('show_date')); ?>_1"><?php esc_html_e('Show', 'writer-ancora'); ?></label>
			<input type="radio" id="<?php echo esc_attr($this->get_field_id('show_date')); ?>_0" name="<?php echo esc_attr($this->get_field_name('show_date')); ?>" value="0" <?php echo (0==$show_date ? ' checked="checked"' : ''); ?> />
			<label for="<?php echo esc_attr($this->get_field_id('show_date')); ?>_0"><?php esc_html_e('Hide', 'writer-ancora'); ?></label>
		</p>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id('show_counters')); ?>_1"><?php esc_html_e('Show post counters:', 'writer-ancora'); ?></label><br />
			<input type="radio" id="<?php echo esc_attr($this->get_field_id('show_counters')); ?>_2" name="<?php echo esc_attr($this->get_field_name('show_counters')); ?>" value="1" <?php echo (1==$show_counters ? ' checked="checked"' : ''); ?> />
			<label for="<?php echo esc_attr($this->get_field_id('show_counters')); ?>_1"><?php esc_html_e('Show', 'writer-ancora'); ?></label>
			<input type="radio" id="<?php echo esc_attr($this->get_field_id('show_counters')); ?>_0" name="<?php echo esc_attr($this->get_field_name('show_counters')); ?>" value="0" <?php echo (0==$show_counters ? ' checked="checked"' : ''); ?> />
			<label for="<?php echo esc_attr($this->get_field_id('show_counters')); ?>_0"><?php esc_html_e('Hide', 'writer-ancora'); ?></label>
		</p>

	<?php
	}
}



// trx_widget_popular_posts
//-------------------------------------------------------------
/*
[trx_widget_popular_posts id="unique_id" title="Widget title" title_popular="title for the tab 'most popular'" title_commented="title for the tab 'most commented'" title_liked="title for the tab 'most liked'" number="4"]
*/
if ( !function_exists( 'writer_ancora_sc_widget_popular_posts' ) ) {
	function writer_ancora_sc_widget_popular_posts($atts, $content=null){	
		$atts = writer_ancora_html_decode(shortcode_atts(array(
			// Individual params
			"title" => "",
			"title_popular" => "",
			"title_commented" => "",
			"title_liked" => "",
			"number" => 4,
			"show_date" => 1,
			"show_image" => 1,
			"show_author" => 1,
			"show_counters" => 1,
			'category' 		=> '',
			'cat' 			=> 0,
			'post_type'		=> 'post',
			// Common params
			"id" => "",
			"class" => "",
			"css" => ""
		), $atts));
		if ($atts['post_type']=='') $atts['post_type'] = 'post';
		if ($atts['cat']!='' && $atts['category']=='') $atts['category'] = $atts['cat'];
		if ($atts['show_date']=='') $atts['show_date'] = 0;
		if ($atts['show_image']=='') $atts['show_image'] = 0;
		if ($atts['show_author']=='') $atts['show_author'] = 0;
		if ($atts['show_counters']=='') $atts['show_counters'] = 0;
		extract($atts);
		$type = 'writer_ancora_widget_popular_posts';
		$output = '';
		global $wp_widget_factory;
		if ( is_object( $wp_widget_factory ) && isset( $wp_widget_factory->widgets, $wp_widget_factory->widgets[ $type ] ) ) {
			$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '')
							. ' class="widget_area sc_widget_popular_posts' 
								. (writer_ancora_exists_visual_composer() ? ' vc_widget_popular_posts wpb_content_element' : '') 
								. (!empty($class) ? ' ' . esc_attr($class) : '') 
						. '">';
			ob_start();
			the_widget( $type, $atts, writer_ancora_prepare_widgets_args(writer_ancora_storage_get('widgets_args'), $id ? $id.'_widget' : 'widget_popular_posts', 'widget_popular_posts') );
			$output .= ob_get_contents();
			ob_end_clean();
			$output .= '</div>';
		}
		return apply_filters('writer_ancora_shortcode_output', $output, 'trx_widget_popular_posts', $atts, $content);
	}
	writer_ancora_require_shortcode("trx_widget_popular_posts", "writer_ancora_sc_widget_popular_posts");
}


// Add [trx_widget_popular_posts] in the VC shortcodes list
if (!function_exists('writer_ancora_widget_popular_posts_reg_shortcodes_vc')) {
	//add_action('writer_ancora_action_shortcodes_list_vc','writer_ancora_widget_popular_posts_reg_shortcodes_vc');
	function writer_ancora_widget_popular_posts_reg_shortcodes_vc() {
		
		$posts_types = writer_ancora_get_list_posts_types(false);
		$categories = writer_ancora_get_list_terms(false, writer_ancora_get_taxonomy_categories_by_post_type('post'));

		vc_map( array(
				"base" => "trx_widget_popular_posts",
				"name" => esc_html__("Widget Popular Posts", 'writer-ancora'),
				"description" => wp_kses( __("Insert popular posts list with thumbs and post's meta", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
				"category" => esc_html__('Content', 'writer-ancora'),
				"icon" => 'icon_trx_widget_popular_posts',
				"class" => "trx_widget_popular_posts",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "title",
						"heading" => esc_html__("Widget title", 'writer-ancora'),
						"description" => wp_kses( __("Title of the widget", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "title_popular",
						"heading" => esc_html__("Most popular tab title", 'writer-ancora'),
						"description" => wp_kses( __("Most popular tab title", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "title_commented",
						"heading" => esc_html__("Most commented tab title", 'writer-ancora'),
						"description" => wp_kses( __("Most commented tab title", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "title_liked",
						"heading" => esc_html__("Most liked tab title", 'writer-ancora'),
						"description" => wp_kses( __("Most liked tab title", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "number",
						"heading" => esc_html__("Number posts to show", 'writer-ancora'),
						"description" => wp_kses( __("How many posts display in widget?", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"admin_label" => true,
						"class" => "",
						"value" => "4",
						"type" => "textfield"
					),
					array(
						"param_name" => "post_type",
						"heading" => esc_html__("Post type", 'writer-ancora'),
						"description" => wp_kses( __("Select post type to show", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"class" => "",
						"std" => "post",
						"value" => array_flip($posts_types),
						"type" => "dropdown"
					),
					array(
						"param_name" => "cat",
						"heading" => esc_html__("Parent category", 'writer-ancora'),
						"description" => wp_kses( __("Select parent category. If empty - show posts from any category", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"class" => "",
						"value" => array_flip(writer_ancora_array_merge(array(0 => esc_html__('- Select category -', 'writer-ancora')), $categories)),
						"type" => "dropdown"
					),
					array(
						"param_name" => "show_image",
						"heading" => esc_html__("Show post's image", 'writer-ancora'),
						"description" => wp_kses( __("Do you want display post's featured image?", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"group" => esc_html__('Details', 'writer-ancora'),
						"class" => "",
						"std" => 1,
						"value" => array("Show image" => "1" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "show_author",
						"heading" => esc_html__("Show post's author", 'writer-ancora'),
						"description" => wp_kses( __("Do you want display post's author?", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"group" => esc_html__('Details', 'writer-ancora'),
						"class" => "",
						"std" => 1,
						"value" => array("Show author" => "1" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "show_date",
						"heading" => esc_html__("Show post's date", 'writer-ancora'),
						"description" => wp_kses( __("Do you want display post's publish date?", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"group" => esc_html__('Details', 'writer-ancora'),
						"class" => "",
						"std" => 1,
						"value" => array("Show date" => "1" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "show_counters",
						"heading" => esc_html__("Show post's counters", 'writer-ancora'),
						"description" => wp_kses( __("Do you want display post's counters?", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
						"group" => esc_html__('Details', 'writer-ancora'),
						"class" => "",
						"std" => 1,
						"value" => array("Show counters" => "1" ),
						"type" => "checkbox"
					),
					writer_ancora_get_vc_param('id'),
					writer_ancora_get_vc_param('class'),
					writer_ancora_get_vc_param('css')
				)
			) );
			
		class WPBakeryShortCode_Trx_Widget_Popular_Posts extends WPBakeryShortCode {}

	}
}
?>