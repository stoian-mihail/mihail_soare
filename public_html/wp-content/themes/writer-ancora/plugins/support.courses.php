<?php
/**
 * Writer Ancora Framework: Courses support
 *
 * @package	writer_ancora
 * @since	writer_ancora 1.0
 */

// Theme init
if (!function_exists('writer_ancora_courses_theme_setup')) {
	add_action( 'writer_ancora_action_before_init_theme', 'writer_ancora_courses_theme_setup', 1 );

	function writer_ancora_courses_theme_setup() {

		// Add post specific actions and filters
		if (writer_ancora_storage_get_array('post_meta_box', 'page')=='courses') {
			add_action('admin_enqueue_scripts',							'writer_ancora_courses_admin_scripts');
			add_action('writer_ancora_action_post_before_show_meta_box',		'writer_ancora_courses_before_show_meta_box', 10, 2);
			add_action('writer_ancora_action_post_after_show_meta_box',		'writer_ancora_courses_after_show_meta_box', 10, 2);
			add_filter('writer_ancora_filter_post_load_custom_options',		'writer_ancora_courses_load_custom_options', 10, 3);
			add_filter('writer_ancora_filter_post_save_custom_options',		'writer_ancora_courses_save_custom_options', 10, 3);
			add_filter('writer_ancora_filter_post_show_custom_field_option',	'writer_ancora_courses_show_custom_field_option', 10, 4);
		}
		
		// Detect current page type, taxonomy and title (for custom post_types use priority < 10 to fire it handles early, than for standard post types)
		add_filter('writer_ancora_filter_get_blog_type',						'writer_ancora_courses_get_blog_type', 9, 2);
		add_filter('writer_ancora_filter_get_blog_title',					'writer_ancora_courses_get_blog_title', 9, 2);
		add_filter('writer_ancora_filter_get_current_taxonomy',				'writer_ancora_courses_get_current_taxonomy', 9, 2);
		add_filter('writer_ancora_filter_is_taxonomy',						'writer_ancora_courses_is_taxonomy', 9, 2);
		add_filter('writer_ancora_filter_get_period_links',					'writer_ancora_courses_get_period_links', 9, 3);
		add_filter('writer_ancora_filter_get_stream_page_title',				'writer_ancora_courses_get_stream_page_title', 9, 2);
		add_filter('writer_ancora_filter_get_stream_page_link',				'writer_ancora_courses_get_stream_page_link', 9, 2);
		add_filter('writer_ancora_filter_get_stream_page_id',				'writer_ancora_courses_get_stream_page_id', 9, 2);
		add_filter('writer_ancora_filter_query_add_filters',					'writer_ancora_courses_query_add_filters', 9, 2);
		add_filter('writer_ancora_filter_detect_inheritance_key',			'writer_ancora_courses_detect_inheritance_key', 9, 1);
		add_filter('writer_ancora_filter_related_posts_args',				'writer_ancora_courses_related_posts_args', 9, 2);
		add_filter('writer_ancora_filter_related_posts_title',				'writer_ancora_courses_related_posts_title', 9, 2);
		add_filter('writer_ancora_filter_list_post_types', 					'writer_ancora_courses_list_post_types', 10, 1);
		add_filter('writer_ancora_filter_post_date',		 					'writer_ancora_courses_post_date', 9, 3);

		// Advanced Calendar filters
		add_filter('writer_ancora_filter_calendar_get_prev_month',			'writer_ancora_courses_calendar_get_prev_month', 9, 2);
		add_filter('writer_ancora_filter_calendar_get_next_month',			'writer_ancora_courses_calendar_get_next_month', 9, 2);
		add_filter('writer_ancora_filter_calendar_get_curr_month_posts',		'writer_ancora_courses_calendar_get_curr_month_posts', 9, 2);

		// Add Main Query parameters
		add_filter( 'posts_join',										'writer_ancora_courses_posts_join', 10, 2 );
		add_filter( 'getarchives_join',									'writer_ancora_courses_getarchives_join', 10, 2 );
		add_filter( 'posts_where',										'writer_ancora_courses_posts_where', 10, 2 );
		add_filter( 'getarchives_where',								'writer_ancora_courses_getarchives_where', 10, 2 );

		// Extra column for courses lists
		if (writer_ancora_get_theme_option('show_overriden_posts')=='yes') {
			add_filter('manage_edit-courses_columns',			'writer_ancora_post_add_options_column', 9);
			add_filter('manage_courses_posts_custom_column',	'writer_ancora_post_fill_options_column', 9, 2);
		}
		
		// Add supported data types
		writer_ancora_theme_support_pt('courses');
		writer_ancora_theme_support_tx('courses_group');
		writer_ancora_theme_support_tx('courses_tag');
	}
}

if ( !function_exists( 'writer_ancora_courses_settings_theme_setup2' ) ) {
	add_action( 'writer_ancora_action_before_init_theme', 'writer_ancora_courses_settings_theme_setup2', 3 );
	function writer_ancora_courses_settings_theme_setup2() {

		// Add post type 'courses' and taxonomies 'courses_group' and 'courses_tag' into theme inheritance list
		writer_ancora_add_theme_inheritance( array('courses' => array(
			'stream_template' => 'blog-courses',
			'single_template' => 'single-course',
			'taxonomy' => array('courses_group'),
			'taxonomy_tags' => array('courses_tag'),
			'post_type' => array('courses', 'lesson'),
			'override' => 'page'
			) )
		);

		// Add Courses specific options in the Theme Options
		writer_ancora_storage_set_array_before('options', 'partition_reviews', array(
		
			"partition_courses" => array(
					"title" => __('Courses', 'writer-ancora'),
					"icon" => "iconadmin-users",
					"override" => "courses_group",
					"type" => "partition"),
		
			"info_courses_1" => array(
					"title" => __('Courses settings', 'writer-ancora'),
					"desc" => __('Set up courses posts behaviour in the blog.', 'writer-ancora'),
					"override" => "courses_group",
					"type" => "info"),
		
			"show_courses_in_blog" => array(
					"title" => __('Show courses in the blog',  'writer-ancora'),
					"desc" => __("Show courses in stream pages (blog, archives) or only in special pages", 'writer-ancora'),
					"divider" => false,
					"std" => "yes",
					"options" => writer_ancora_get_options_param('list_yes_no'),
					"type" => "switch"),

			"show_countdown" => array(
					"title" => __('Show countdown',  'writer-ancora'),
					"desc" => __("Show countdown section with time to class start", 'writer-ancora'),
					"std" => "1",
					"override" => "courses_group",
					"style" => "horizontal",
					"options" => array(
						0 => __('Hide', 'writer-ancora'),
						1 => __('Type 1', 'writer-ancora'),
						2 => __('Type 2', 'writer-ancora')
					),
					"dir" => "horizontal",
					"type" => "checklist")
			)
		);	

	}
}


if (!function_exists('writer_ancora_courses_after_theme_setup')) {
	add_action( 'writer_ancora_action_after_init_theme', 'writer_ancora_courses_after_theme_setup' );
	function writer_ancora_courses_after_theme_setup() {
		// Update fields in the meta box
		if (writer_ancora_storage_get_array('post_meta_box', 'page')=='courses') {
			// Meta box fields
			writer_ancora_storage_set_array('post_meta_box', 'title', __('Course Options', 'writer-ancora'));
			writer_ancora_storage_set_array('post_meta_box', 'fields', array(
				"mb_partition_courses" => array(
					"title" => __('Courses', 'writer-ancora'),
					"override" => "page,post",
					"divider" => false,
					"icon" => "iconadmin-users-1",
					"type" => "partition"),
				"mb_info_courses_1" => array(
					"title" => __('Course details', 'writer-ancora'),
					"override" => "page,post",
					"divider" => false,
					"desc" => __('In this section you can put details for this course', 'writer-ancora'),
					"class" => "course_meta",
					"type" => "info"),
				"mark_as_new" => array(
					"title" => __('Mark "New"',  'writer-ancora'),
					"desc" => __('Mark this course item as "New" until date', 'writer-ancora'),
					"override" => "page,post",
					"class" => "course_mark_new",
					"std" => date('Y-m-d', strtotime('+1 month')),
					"format" => 'yy-mm-dd',
					"divider" => false,
					"type" => "date"),
				"date_start" => array(
					"title" => __('Start date',  'writer-ancora'),
					"desc" => __("Class start date", 'writer-ancora'),
					"override" => "page,post",
					"class" => "course_date",
					"std" => date('Y-m-d'),
					"format" => 'yy-mm-dd',
					"type" => "date"),
				"date_end" => array(
					"title" => __('End date',  'writer-ancora'),
					"desc" => __("Class end date", 'writer-ancora'),
					"override" => "page,post",
					"class" => "course_date",
					"std" => date('Y-m-d', strtotime('+1 month')),
					"format" => 'yy-mm-dd',
					"divider" => false,
					"type" => "date"),
				"shedule" => array(
					"title" => __('Schedule time',  'writer-ancora'),
					"desc" => __("Class start days and time. For example: Mon, Wed, Fri 19:00-21:00", 'writer-ancora'),
					"override" => "page,post",
					"class" => "course_time",
					"std" => '',
					"divider" => false,
					"type" => "text"),
				"price" => array(
					"title" => __('Price',  'writer-ancora'),
					"desc" => __("Course item price", 'writer-ancora'),
					"override" => "page,post",
					"class" => "course_price",
					"std" => '',
					"type" => "text"),
				"price_period" => array(
					"title" => __('Price period',  'writer-ancora'),
					"desc" => __("Course item price period (monthly, quarterly, yearly). If empty - price for whole course.", 'writer-ancora'),
					"override" => "page,post",
					"class" => "course_price_period",
					"std" => '',
					"divider" => false,
					"type" => "text"),
				"teacher" => array(
					"title" => __('Teacher',  'writer-ancora'),
					"desc" => __("Main Teacher for this course", 'writer-ancora'),
					"override" => "page,post",
					"class" => "course_teacher",
					"std" => '',
					"options" => writer_ancora_get_list_posts(false, array(
						'post_type' => 'team',
						'orderby' => 'title',
						'order' => 'asc')
					),
					"type" => "select"),
				"product" => array(
					"title" => __('Link to course product',  'writer-ancora'),
					"desc" => __("Link to product page for this course", 'writer-ancora'),
					"override" => "page,post",
					"class" => "course_product",
					"std" => '',
					"options" => writer_ancora_get_list_posts(false, 'product'),
					"type" => "select"),
				"partition_reviews" => array(
					"title" => __('Reviews', 'writer-ancora'),
					"override" => "page,post",
					"divider" => false,
					"icon" => "iconadmin-newspaper",
					"type" => "partition"),
				"info_reviews_1" => array(
					"title" => __('Reviews criterias for this course', 'writer-ancora'),
					"override" => "page,post",
					"divider" => false,
					"desc" => __('In this section you can put your reviews marks for this course', 'writer-ancora'),
					"class" => "reviews_meta",
					"type" => "info"),
				"show_reviews" => array(
					"title" => __('Show reviews block',  'writer-ancora'),
					"desc" => __("Show reviews block on single post page and average reviews rating after post's title in stream pages", 'writer-ancora'),
					"override" => "page,post",
					"class" => "reviews_meta",
					"std" => "inherit",
					"divider" => false,
					"style" => "horizontal",
					"options" => writer_ancora_get_list_yesno(),
					"type" => "radio"),
				"reviews_marks" => array(
					"title" => __('Reviews marks',  'writer-ancora'),
					"override" => "page,post",
					"desc" => __("Marks for this review", 'writer-ancora'),
					"class" => "reviews_meta reviews_tab reviews_users",
					"std" => "",
					"options" => writer_ancora_get_custom_option('reviews_criterias'),
					"type" => "reviews")
				)
			);
		}
	}
}


// Admin scripts
if (!function_exists('writer_ancora_courses_admin_scripts')) {
	//add_action('admin_enqueue_scripts', 'writer_ancora_courses_admin_scripts');
	function writer_ancora_courses_admin_scripts() {
		if (writer_ancora_storage_get_array('post_meta_box', 'page')=='courses')
			writer_ancora_enqueue_script( 'writer_ancora-core-reviews-script', writer_ancora_get_file_url('js/core.reviews.js'), array('jquery'), null, true );
	}
}


// Open reviews container before Theme options block
if (!function_exists('writer_ancora_courses_before_show_meta_box')) {
	//add_action('writer_ancora_action_post_before_show_meta_box', 'writer_ancora_courses_before_show_meta_box', 10, 2);
	function writer_ancora_courses_before_show_meta_box($post_type, $post_id) {
		$max_level = max(5, (int) writer_ancora_get_theme_option('reviews_max_level'));
		?>
		<script type="text/javascript">
			jQuery(document).ready(function() {
				// Prepare global values for the review procedure
				WRITER_ANCORA_STORAGE['reviews_levels']			= "<?php echo trim(writer_ancora_get_theme_option('reviews_criterias_levels')); ?>";
				WRITER_ANCORA_STORAGE['reviews_max_level'] 		= <?php echo (int) $max_level; ?>;
				WRITER_ANCORA_STORAGE['reviews_allow_user_marks']= true;
			});
		</script>
		<div class="reviews_area reviews_<?php echo esc_attr($max_level); ?>">
		<?php
	}
}


// Close reviews container after Theme options block
if (!function_exists('writer_ancora_courses_after_show_meta_box')) {
	//add_action('writer_ancora_action_courses_after_show_meta_box', 'writer_ancora_courses_after_show_meta_box', 10, 2);
	function writer_ancora_courses_after_show_meta_box($post_type, $post_id) {
		?>
		</div>
		<?php
	}
}


// Load custom options filter - prepare reviews marks
if (!function_exists('writer_ancora_courses_load_custom_options')) {
	//add_filter('writer_ancora_filter_post_load_custom_options', 'writer_ancora_courses_load_custom_options', 10, 3);
	function writer_ancora_courses_load_custom_options($custom_options, $post_type, $post_id) {
		if (isset($custom_options['reviews_marks'])) 
			$custom_options['reviews_marks'] = writer_ancora_reviews_marks_to_display($custom_options['reviews_marks']);
		return $custom_options;
	}
}

// Before show reviews field - add taxonomy specific criterias
if (!function_exists('writer_ancora_courses_show_custom_field_option')) {
	//add_filter('writer_ancora_filter_post_show_custom_field_option',	'writer_ancora_courses_show_custom_field_option', 10, 4);
	function writer_ancora_courses_show_custom_field_option($option, $id, $post_type, $post_id) {
		if ($id == 'reviews_marks') {
			$cat_list = writer_ancora_get_terms_by_post_id(array(
				'taxonomy' => 'courses_group',
				'post_id' => $post_id
				)
			);
			if (!empty($cat_list['courses_group']->terms)) {
				foreach ($cat_list['courses_group']->terms as $cat) {
					$term_id = (int) $cat->term_id;
					$prop = writer_ancora_taxonomy_get_inherited_property('courses_group', $term_id, 'reviews_criterias');
					if (!empty($prop) && !writer_ancora_is_inherit_option($prop)) {
						$option['options'] = $prop;
						break;
					}
				}
			}
		}
		return $option;
	}
}

// Before save custom options - calc and save average rating
if (!function_exists('writer_ancora_courses_save_custom_options')) {
	//add_filter('writer_ancora_filter_post_save_custom_options',	'writer_ancora_courses_save_custom_options', 10, 3);
	function writer_ancora_courses_save_custom_options($custom_options, $post_type, $post_id) {
		if (isset($custom_options['reviews_marks'])) {
			if (($avg = writer_ancora_reviews_get_average_rating($custom_options['reviews_marks'])) > 0)
				update_post_meta($post_id, 'reviews_avg', $avg);
		}
		if (isset($custom_options['teacher'])) {
			update_post_meta($post_id, 'teacher', $custom_options['teacher']);
		}
		if (isset($custom_options['date_start'])) {
			update_post_meta($post_id, 'date_start', $custom_options['date_start']);
		}
		if (isset($custom_options['date_end'])) {
			update_post_meta($post_id, 'date_end', $custom_options['date_end']);
		}
		return $custom_options;
	}
}




// Return true, if current page is single post page or category archive or blog stream page
if ( !function_exists( 'writer_ancora_is_courses_page' ) ) {
	function writer_ancora_is_courses_page() {
		$is = in_array(writer_ancora_storage_get('page_template'), array('blog-courses', 'single-course'));
		if (!$is) {
			if (!writer_ancora_storage_empty('pre_query'))
				$is = writer_ancora_storage_call_obj_method('pre_query', 'get', 'post_type')=='courses'
						|| writer_ancora_storage_call_obj_method('pre_query', 'is_tax', 'courses_group') 
						|| writer_ancora_storage_call_obj_method('pre_query', 'is_tax', 'courses_tag') 
						|| (writer_ancora_storage_call_obj_method('pre_query', 'is_page') 
							&& ($id=writer_ancora_get_template_page_id('blog-courses')) > 0 
							&& $id==writer_ancora_storage_get_obj_property('pre_query', 'queried_object_id', 0)
							);
			else
				$is = get_query_var('post_type')=='courses' 
						|| is_tax('courses_group') 
						|| is_tax('courses_tag') 
						|| (is_page() && ($id=writer_ancora_get_template_page_id('blog-courses')) > 0 && $id==get_the_ID());
		}
		return $is;
	}
}

// Filter to detect current page inheritance key
if ( !function_exists( 'writer_ancora_courses_detect_inheritance_key' ) ) {
	//add_filter('writer_ancora_filter_detect_inheritance_key',	'writer_ancora_courses_detect_inheritance_key', 9, 1);
	function writer_ancora_courses_detect_inheritance_key($key) {
		if (!empty($key)) return $key;
		return writer_ancora_is_courses_page() ? 'courses' : '';
	}
}

// Filter to detect current page slug
if ( !function_exists( 'writer_ancora_courses_get_blog_type' ) ) {
	//add_filter('writer_ancora_filter_get_blog_type',	'writer_ancora_courses_get_blog_type', 9, 2);
	function writer_ancora_courses_get_blog_type($page, $query=null) {
		if (!empty($page)) return $page;
		if ($query && $query->is_tax('courses_group') || is_tax('courses_group'))
			$page = 'courses_category';
		else if ($query && $query->is_tax('courses_tag') || is_tax('courses_tag'))
			$page = 'courses_tag';
		else if ($query && $query->get('post_type')=='courses' || get_query_var('post_type')=='courses')
			$page = $query && $query->is_single() || is_single() ? 'courses_item' : 'courses';
		else if ($query && $query->get('post_type')=='lesson' || get_query_var('post_type')=='lesson')
			$page = $query && $query->is_single() || is_single() ? 'courses_lesson' : 'courses';
		return $page;
	}
}

// Filter to detect current page title
if ( !function_exists( 'writer_ancora_courses_get_blog_title' ) ) {
	//add_filter('writer_ancora_filter_get_blog_title',	'writer_ancora_courses_get_blog_title', 9, 2);
	function writer_ancora_courses_get_blog_title($title, $page) {
		if (!empty($title)) return $title;
		if ( $page == 'archives_day' && get_post_type()=='courses' ) {
			$dt = strtotime(get_post_meta(get_the_ID(), 'date_start', true));
			$title = sprintf( __( 'Daily Archives: %s', 'writer-ancora' ), writer_ancora_get_date_translations(date( get_option('date_format'), $dt )) );
		} else if ( $page == 'archives_month' && get_post_type()=='courses' ) {
			$dt = strtotime(get_post_meta(get_the_ID(), 'date_start', true));
			$title = sprintf( __( 'Monthly Archives: %s', 'writer-ancora' ), writer_ancora_get_date_translations(date( 'F Y', $dt )) );
		} else if ( $page == 'archives_year' && get_post_type()=='courses' ) {
			$dt = strtotime(get_post_meta(get_the_ID(), 'date_start', true));
			$title = sprintf( __( 'Yearly Archives: %s', 'writer-ancora' ), date( 'Y', $dt ) );
		} else if ( writer_ancora_strpos($page, 'courses')!==false ) {
			if ( $page == 'courses_category' ) {
				$term = get_term_by( 'slug', get_query_var( 'courses_group' ), 'courses_group', OBJECT);
				$title = $term->name;
			} else if ( $page == 'courses_tag' ) {
				$term = get_term_by( 'slug', get_query_var( 'courses_tag' ), 'courses_tag', OBJECT);
				$title = __('Tag:', 'writer-ancora') . ' ' . ($term->name);
			} else if ( $page == 'courses_item' || $page == 'courses_lesson' ) {
				$title = writer_ancora_get_post_title();
			} else if (($page_id = writer_ancora_get_template_page_id($page)) > 0) {
				$title = writer_ancora_get_post_title($page_id);
			} else {
				$title = __('All courses', 'writer-ancora');
			}
		}
		return $title;
	}
}

// Filter to detect stream page title
if ( !function_exists( 'writer_ancora_courses_get_stream_page_title' ) ) {
	//add_filter('writer_ancora_filter_get_stream_page_title',	'writer_ancora_courses_get_stream_page_title', 9, 2);
	function writer_ancora_courses_get_stream_page_title($title, $page) {
		if (!empty($title)) return $title;
		if (writer_ancora_strpos($page, 'courses')!==false) {
			if (($page_id = writer_ancora_courses_get_stream_page_id(0, $page=='courses' ? 'blog-courses' : $page)) > 0)
				$title = writer_ancora_get_post_title($page_id);
			else
				$title = __('All courses', 'writer-ancora');				
		}
		return $title;
	}
}

// Filter to detect stream page ID
if ( !function_exists( 'writer_ancora_courses_get_stream_page_id' ) ) {
	//add_filter('writer_ancora_filter_get_stream_page_id',	'writer_ancora_courses_get_stream_page_id', 9, 2);
	function writer_ancora_courses_get_stream_page_id($id, $page) {
		if (!empty($id)) return $id;
		if (writer_ancora_strpos($page, 'courses')!==false) $id = writer_ancora_get_template_page_id('blog-courses');
		return $id;
	}
}

// Filter to detect stream page URL
if ( !function_exists( 'writer_ancora_courses_get_stream_page_link' ) ) {
	//add_filter('writer_ancora_filter_get_stream_page_link', 'writer_ancora_courses_get_stream_page_link', 9, 2);
	function writer_ancora_courses_get_stream_page_link($url, $page) {
		if (!empty($url)) return $url;
		if (writer_ancora_strpos($page, 'courses')!==false) {
			$id = writer_ancora_get_template_page_id('blog-courses');
			if ($id) $url = get_permalink($id);
		}
		return $url;
	}
}

// Filter to detect taxonomy name (slug) for the current post, category, blog
if ( !function_exists( 'writer_ancora_courses_get_current_taxonomy' ) ) {
	//add_filter('writer_ancora_filter_get_current_taxonomy',	'writer_ancora_courses_get_current_taxonomy', 9, 2);
	function writer_ancora_courses_get_current_taxonomy($tax, $page) {
		if (!empty($tax)) return $tax;
		if ( writer_ancora_strpos($page, 'courses')!==false ) {
			$tax = 'courses_group';
		}
		return $tax;
	}
}

// Return taxonomy name (slug) if current page is this taxonomy page
if ( !function_exists( 'writer_ancora_courses_is_taxonomy' ) ) {
	//add_filter('writer_ancora_filter_is_taxonomy',	'writer_ancora_courses_is_taxonomy', 10, 2);
	function writer_ancora_courses_is_taxonomy($tax, $query=null) {
		if (!empty($tax))
			return $tax;
		else 
			return $query && $query->get('courses_group')!='' || is_tax('courses_group') ? 'courses_group' : '';
	}
}

// Filter to return breadcrumbs links to the parent period
if ( !function_exists( 'writer_ancora_courses_get_period_links' ) ) {
	//add_filter('writer_ancora_filter_get_period_links',	'writer_ancora_courses_get_period_links', 9, 3);
	function writer_ancora_courses_get_period_links($links, $page, $delimiter='') {
		if (!empty($links)) return $links;
		global $post;
		if (in_array($page, array('archives_day', 'archives_month')) && is_object($post) && get_post_type()=='courses') {
			$dt = strtotime(get_post_meta(get_the_ID(), 'date_start', true));
			$year  = date('Y', $dt); 
			$month = date('m', $dt); 
			$links = '<a class="breadcrumbs_item cat_parent" href="' . get_year_link( $year ) . '">' . ($year) . '</a>';
			if ($page == 'archives_day')
				$links .= (!empty($links) ? $delimiter : '') . '<a class="breadcrumbs_item cat_parent" href="' . get_month_link( $year, $month ) . '">' . trim(writer_ancora_get_date_translations(date('F', $dt))) . '</a>';
		}
		return $links;
	}
}

// Add custom post type and/or taxonomies arguments to the query
if ( !function_exists( 'writer_ancora_courses_query_add_filters' ) ) {
	//add_filter('writer_ancora_filter_query_add_filters',	'writer_ancora_courses_query_add_filters', 9, 2);
	function writer_ancora_courses_query_add_filters($args, $filter) {
		if ($filter == 'courses') {
			$args['post_type'] = 'courses';
		}
		return $args;
	}
}

// Change query args to show related courses for teacher
if ( !function_exists( 'writer_ancora_courses_related_posts_args' ) ) {
	//add_filter('writer_ancora_filter_related_posts_args',	'writer_ancora_courses_related_posts_args', 9, 2);
	function writer_ancora_courses_related_posts_args($args, $post_data) {
		if ($post_data['post_type'] == 'team') {
			$args['post_type'] = 'courses';
			if (empty($args['meta_query'])) $args['meta_query'] = array();
			$args['meta_query']['relation'] = 'AND';
			$args['meta_query'][] = array(
				'meta_filter' => 'teacher',
				'key' => 'teacher',
				'value' => $post_data['post_id'],
				'compare' => '=',
				'type' => 'NUMERIC'
			);
			$args['meta_query'][] = array(
				'meta_filter' => 'date_start',
				'key' => 'date_start',
				'value' => date('Y-m-d'),
				'compare' => '<=',
				'type' => 'DATE'
			);
			$args['meta_query'][] = array(
				'meta_filter' => 'date_end',
				'key' => 'date_end',
				'value' => date('Y-m-d'),
				'compare' => '>=',
				'type' => 'DATE'
			);
			unset($args['post__not_in']);
			if (!empty($args['tax_query'])) {
				foreach ($args['tax_query'] as $k=>$v) {
					if (!empty($v['taxonomy']) && writer_ancora_strpos($v['taxonomy'], 'team')!==false) {
						unset($args['tax_query'][$k]);
					}
				}
			}
		} else if ($post_data['post_type'] == 'lesson') {
			$args['post_type'] = 'lesson';
			$parent_course = get_post_meta($post_data['post_id'], 'parent_course', true);
			if (empty($args['meta_query'])) $args['meta_query'] = array();
			$args['meta_query']['relation'] = 'AND';
			$args['meta_query'][] = array(
				'meta_filter' => 'lesson',
				'key' => 'parent_course',
				'value' => $parent_course,
				'compare' => '=',
				'type' => 'NUMERIC'
			);
			if (!empty($args['tax_query'])) {
				foreach ($args['tax_query'] as $k=>$v) {
					if (!empty($v['taxonomy']) && writer_ancora_strpos($v['taxonomy'], 'team')!==false) {
						unset($args['tax_query'][$k]);
					}
				}
			}
		}
		return $args;
	}
}

// Return related posts title
if ( !function_exists( 'writer_ancora_courses_related_posts_title' ) ) {
	//add_filter('writer_ancora_filter_related_posts_title',	'writer_ancora_courses_related_posts_title', 9, 2);
	function writer_ancora_courses_related_posts_title($title, $post_type) {
		if ($post_type == 'team')
			$title = __('Currently Teaching', 'writer-ancora');
		else if ($post_type == 'courses')
			$title = __('Related Courses', 'writer-ancora');
		else if ($post_type == 'lesson')
			$title = __('Related Lessons', 'writer-ancora');
		return $title;
	}
}

// Add custom post type into list
if ( !function_exists( 'writer_ancora_courses_list_post_types' ) ) {
	//add_filter('writer_ancora_filter_list_post_types', 	'writer_ancora_courses_list_post_types', 10, 1);
	function writer_ancora_courses_list_post_types($list) {
		if (writer_ancora_get_theme_option('show_courses_in_blog')=='yes') {
			$list['courses'] = __('Courses', 'writer-ancora');
		}
		return $list;
	}
}


// Return previous month and year with published posts
if ( !function_exists( 'writer_ancora_courses_calendar_get_prev_month' ) ) {
	//add_filter('writer_ancora_filter_calendar_get_prev_month',	'writer_ancora_courses_calendar_get_prev_month', 9, 2);
	function writer_ancora_courses_calendar_get_prev_month($prev, $opt) {
		if (!empty($opt['posts_types']) && !in_array('courses', $opt['posts_types'])) return $prev;
		if (!empty($prev['done']) && in_array('courses', $prev['done'])) return $prev;
		$args = array(
			'post_type' => 'courses',
			'post_status' => current_user_can('read_private_pages') && current_user_can('read_private_posts') ? array('publish', 'private') : 'publish',
			'posts_per_page' => 1,
			'ignore_sticky_posts' => true,
			'orderby' => 'meta_value',
			'meta_key' => 'date_start',
			'order' => 'desc',
			'meta_query' => array(
				array(
					'key' => 'date_start',
					'value' => ($opt['year']).'-'.($opt['month']).'-01',
					'compare' => '<',
					'type' => 'DATE'
				)
			)
		);
		$q = new WP_Query($args);
		$month = $year = 0;
		if ($q->have_posts()) {
			while ($q->have_posts()) { $q->the_post();
				$dt = strtotime(get_post_meta(get_the_ID(), 'date_start', true));
				$year  = date('Y', $dt);
				$month = date('m', $dt);
			}
			wp_reset_postdata();
		}
		if (empty($prev) || ($year+$month>0 && ($prev['year']+$prev['month']==0 || ($prev['year']).($prev['month']) < ($year).($month)))) {
			$prev['year'] = $year;
			$prev['month'] = $month;
		}
		if (empty($prev['done'])) $prev['done'] = array();
		$prev['done'][] = 'courses';
		return $prev;
	}
}

// Return next month and year with published posts
if ( !function_exists( 'writer_ancora_courses_calendar_get_next_month' ) ) {
	//add_filter('writer_ancora_filter_calendar_get_next_month',	'writer_ancora_courses_calendar_get_next_month', 9, 2);
	function writer_ancora_courses_calendar_get_next_month($next, $opt) {
		if (!empty($opt['posts_types']) && !in_array('courses', $opt['posts_types'])) return $next;
		if (!empty($next['done']) && in_array('courses', $next['done'])) return $next;
		$args = array(
			'post_type' => 'courses',
			'post_status' => current_user_can('read_private_pages') && current_user_can('read_private_posts') ? array('publish', 'private') : 'publish',
			'posts_per_page' => 1,
			'ignore_sticky_posts' => true,
			'orderby' => 'meta_value',
			'meta_key' => 'date_start',
			'order' => 'asc',
			'meta_query' => array(
				array(
					'key' => 'date_start',
					'value' => ($opt['year']).'-'.($opt['month']).'-'.($opt['last_day']).' 23:59:59',
					'compare' => '>',
					'type' => 'DATE'
				)
			)
		);
		$q = new WP_Query($args);
		$month = $year = 0;
		if ($q->have_posts()) {
			while ($q->have_posts()) { $q->the_post();
				$dt = strtotime(get_post_meta(get_the_ID(), 'date_start', true));
				$year  = date('Y', $dt);
				$month = date('m', $dt);
			}
			wp_reset_postdata();
		}
		if (empty($next) || ($year+$month>0 && ($next['year']+$next['month']==0 || ($next['year']).($next['month']) > ($year).($month)))) {
			$next['year'] = $year;
			$next['month'] = $month;
		}
		if (empty($next['done'])) $next['done'] = array();
		$next['done'][] = 'courses';
		return $next;
	}
}

// Return current month published posts
if ( !function_exists( 'writer_ancora_courses_calendar_get_curr_month_posts' ) ) {
	//add_filter('writer_ancora_filter_calendar_get_curr_month_posts',	'writer_ancora_courses_calendar_get_curr_month_posts', 9, 2);
	function writer_ancora_courses_calendar_get_curr_month_posts($posts, $opt) {
		if (!empty($opt['posts_types']) && !in_array('courses', $opt['posts_types'])) return $posts;
		if (!empty($posts['done']) && in_array('courses', $posts['done'])) return $posts;
		$args = array(
			'post_type' => 'courses',
			'post_status' => current_user_can('read_private_pages') && current_user_can('read_private_posts') ? array('publish', 'private') : 'publish',
			'posts_per_page' => -1,
			'ignore_sticky_posts' => true,
			'orderby' => 'meta_value',
			'order' => 'asc',
			'meta_query' => array(
				array(
					'key' => 'date_start',
					'value' => array(($opt['year']).'-'.($opt['month']).'-01', ($opt['year']).'-'.($opt['month']).'-'.($opt['last_day']).' 23:59:59'),
					'compare' => 'BETWEEN',
					'type' => 'DATE'
				)
			)
		);
		$q = new WP_Query($args);
		if ($q->have_posts()) {
			if (empty($posts)) $posts = array();
			while ($q->have_posts()) { $q->the_post();
				$dt = strtotime(get_post_meta(get_the_ID(), 'date_start', true));
				$day = (int) date('d', $dt);
				$title = apply_filters('the_title', get_the_title());
				if (empty($posts[$day])) 
					$posts[$day] = array();
				if (empty($posts[$day]['link']) && count($opt['posts_types'])==1)
					$posts[$day]['link'] = get_day_link($opt['year'], $opt['month'], $day);
				if (empty($posts[$day]['titles']))
					$posts[$day]['titles'] = $title;
				else
					$posts[$day]['titles'] = is_int($posts[$day]['titles']) ? $posts[$day]['titles']+1 : 2;
				if (empty($posts[$day]['posts'])) $posts[$day]['posts'] = array();
				$posts[$day]['posts'][] = array(
					'post_id' => get_the_ID(),
					'post_type' => get_post_type(),
					'post_date' => date(get_option('date_format'), $dt),
					'post_title' => $title,
					'post_link' => get_permalink()
				);
			}
			wp_reset_postdata();
		}
		if (empty($posts['done'])) $posts['done'] = array();
		$posts['done'][] = 'courses';
		return $posts;
	}
}

// Pre query: Join tables into main query
if ( !function_exists( 'writer_ancora_courses_posts_join' ) ) {
	// add_action( 'posts_join', 'writer_ancora_courses_posts_join', 10, 2 );
	function writer_ancora_courses_posts_join($join_sql, $query) {
		if (writer_ancora_get_theme_option('show_courses_in_blog')=='yes' && !is_admin() && $query->is_main_query()) {
			if ($query->is_day || $query->is_month || $query->is_year) {
				global $wpdb;
				$join_sql .= " LEFT JOIN " . esc_sql($wpdb->postmeta) . " AS _courses_meta ON " . esc_sql($wpdb->posts) . ".ID = _courses_meta.post_id AND  _courses_meta.meta_key = 'date_start'";
			}
		}
		return $join_sql;
	}
}

// Pre query: Join tables into archives widget query
if ( !function_exists( 'writer_ancora_courses_getarchives_join' ) ) {
	// add_action( 'getarchives_join', 'writer_ancora_courses_getarchives_join', 10, 2 );
	function writer_ancora_courses_getarchives_join($join_sql, $r) {
		if (writer_ancora_get_theme_option('show_courses_in_blog')=='yes') {
			global $wpdb;
			$join_sql .= " LEFT JOIN " . esc_sql($wpdb->postmeta) . " AS _courses_meta ON " . esc_sql($wpdb->posts) . ".ID = _courses_meta.post_id AND  _courses_meta.meta_key = 'date_start'";
		}
		return $join_sql;
	}
}

// Pre query: Where section into main query
if ( !function_exists( 'writer_ancora_courses_posts_where' ) ) {
	// add_action( 'posts_where', 'writer_ancora_courses_posts_where', 10, 2 );
	function writer_ancora_courses_posts_where($where_sql, $query) {
		if (writer_ancora_get_theme_option('show_courses_in_blog')=='yes' && !is_admin() && $query->is_main_query()) {
			if ($query->is_day || $query->is_month || $query->is_year) {
				global $wpdb;
				$where_sql .= " OR (1=1";
				// Posts status
				if ((!isset($_REQUEST['preview']) || $_REQUEST['preview']!='true') && (!isset($_REQUEST['vc_editable']) || $_REQUEST['vc_editable']!='true')) {
					if (current_user_can('read_private_pages') && current_user_can('read_private_posts'))
						$where_sql .= " AND (" . esc_sql($wpdb->posts) . ".post_status='publish' OR " . esc_sql($wpdb->posts) . ".post_status='private')";
					else
						$where_sql .= " AND " . esc_sql($wpdb->posts) . ".post_status='publish'";
				}
				// Posts type and date
				$dt = $query->get('m');
				$y = $query->get('year');
				if (empty($y)) $y = (int) writer_ancora_substr($dt, 0, 4);
				$where_sql .= " AND " . esc_sql($wpdb->posts) . ".post_type='courses' AND YEAR(_courses_meta.meta_value)=".esc_sql($y);
				if ($query->is_month || $query->is_day) {
					$m = $query->get('monthnum');
					if (empty($m)) $m = (int) writer_ancora_substr($dt, 4, 2);
					$where_sql .= " AND MONTH(_courses_meta.meta_value)=".esc_sql($m);
				}
				if ($query->is_day) {
					$d = $query->get('day');
					if (empty($d)) $d = (int) writer_ancora_substr($dt, 6, 2);
					$where_sql .= " AND DAYOFMONTH(_courses_meta.meta_value)=".esc_sql($d);
				}
				$where_sql .= ')';
			}
		}
		return $where_sql;
	}
}

// Pre query: Where section into archives widget query
if ( !function_exists( 'writer_ancora_courses_getarchives_where' ) ) {
	// add_action( 'getarchives_where', 'writer_ancora_courses_getarchives_where', 10, 2 );
	function writer_ancora_courses_getarchives_where($where_sql, $r) {
		if (writer_ancora_get_theme_option('show_courses_in_blog')=='yes') {
			global $wpdb;
			// Posts type and date
			$where_sql .= " OR " . esc_sql($wpdb->posts) . ".post_type='courses'";
		}
		return $where_sql;
	}
}

// Return courses start date instead post publish date
if ( !function_exists( 'writer_ancora_courses_post_date' ) ) {
	//add_filter('writer_ancora_filter_post_date', 'writer_ancora_courses_post_date', 9, 3);
	function writer_ancora_courses_post_date($post_date, $post_id, $post_type) {
		if ($post_type == 'courses') {
			$post_date = get_post_meta($post_id, 'date_start', true);
		}
		return $post_date;
	}
}
?>