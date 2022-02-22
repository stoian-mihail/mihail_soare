<?php
/**
 * Writer Ancora Framework: Lesson support
 *
 * @package	writer_ancora
 * @since	writer_ancora 1.0
 */

// Theme init
if (!function_exists('writer_ancora_lesson_theme_setup')) {
	add_action( 'writer_ancora_action_before_init_theme', 'writer_ancora_lesson_theme_setup', 1 );
	function writer_ancora_lesson_theme_setup() {

		// Add post specific actions and filters
		if (writer_ancora_storage_get_array('post_meta_box', 'page')=='lesson') {
			add_filter('writer_ancora_filter_post_save_custom_options',		'writer_ancora_lesson_save_custom_options', 10, 3);
		}

		// Add categories (taxonomies) filter for custom posts types
		add_action( 'restrict_manage_posts','writer_ancora_lesson_show_courses_combo' );
		add_filter( 'pre_get_posts', 		'writer_ancora_lesson_add_parent_course_in_query' );

		// Extra column for lessons lists with overriden Theme Options
		if (writer_ancora_get_theme_option('show_overriden_posts')=='yes') {
			add_filter('manage_edit-lesson_columns',		'writer_ancora_post_add_options_column', 9);
			add_filter('manage_lesson_posts_custom_column',	'writer_ancora_post_fill_options_column', 9, 2);
		}
		// Extra column for lessons lists with parent course name
		add_filter('manage_edit-lesson_columns',		'writer_ancora_lesson_add_options_column', 9);
		add_filter('manage_lesson_posts_custom_column',	'writer_ancora_lesson_fill_options_column', 9, 2);

		// Register shortcode [trx_lessons] in the shortcodes list
		add_action('writer_ancora_action_shortcodes_list',		'writer_ancora_lesson_reg_shortcodes');
		if (function_exists('writer_ancora_exists_visual_composer') && writer_ancora_exists_visual_composer())
			add_action('writer_ancora_action_shortcodes_list_vc','writer_ancora_lesson_reg_shortcodes_vc');
		
		// Add supported data types
		writer_ancora_theme_support_pt('lesson');
	}
}


/* Extra column for lessons list
-------------------------------------------------------------------------------------------- */

// Create additional column
if (!function_exists('writer_ancora_lesson_add_options_column')) {
	//add_filter('manage_edit-lesson_columns',	'writer_ancora_lesson_add_options_column', 9);
	function writer_ancora_lesson_add_options_column( $columns ) {
		writer_ancora_array_insert_after( $columns, 'title', array('course_title' => __('Course', 'writer-ancora')) );
		return $columns;
	}
}

// Fill column with data
if (!function_exists('writer_ancora_lesson_fill_options_column')) {
	//add_filter('manage_lesson_custom_column',	'writer_ancora_lesson_fill_options_column', 9, 2);
	function writer_ancora_lesson_fill_options_column($column_name='', $post_id=0) {
		if ($column_name != 'course_title') return;
		if ($parent_id = get_post_meta($post_id, 'parent_course', true)) {
			if ($parent_id > 0) {
				$parent_title = get_the_title($parent_id);
				echo '<a href="#" onclick="jQuery(\'select#parent_course\').val('.intval($parent_id).').siblings(\'input[type=\\\'submit\\\']\').trigger(\'click\'); return false;" title="'.esc_attr(__('Leave only lessons of this course', 'writer-ancora')).'">' . strip_tags($parent_title) . '</a>';
			}
		}
	}
}


/* Display filter for lessons by courses
-------------------------------------------------------------------------------------------- */

// Display filter combobox
if (!function_exists('writer_ancora_lesson_show_courses_combo')) {
	//add_action( 'restrict_manage_posts', 'writer_ancora_lesson_show_courses_combo' );
	function writer_ancora_lesson_show_courses_combo() {
		$page = get_query_var('post_type');
		if ($page != 'lesson') return;
		$courses = writer_ancora_get_list_posts(false, array(
					'post_type' => 'courses',
					'orderby' => 'title',
					'order' => 'asc'
					)
		);
		$list = '';
		if (count($courses) > 0) {
			$slug = 'parent_course';
			$list .= '<label class="screen-reader-text filter_label" for="'.esc_attr($slug).'">' . __('Parent Course:', 'writer-ancora') . "</label> <select name='".esc_attr($slug)."' id='".esc_attr($slug)."' class='postform'>";
			foreach ($courses as $id=>$name) {
				$list .= '<option value='. esc_attr($id) . (isset($_GET[$slug]) && $_GET[$slug] == $id ? ' selected="selected"' : '') . '>' . esc_html($name) . '</option>';
			}
			$list .=  "</select>";
		}
		echo trim($list);
	}
}

// Add filter in main query
if (!function_exists('writer_ancora_lesson_add_parent_course_in_query')) {
	//add_filter( 'pre_get_posts', 'writer_ancora_lesson_add_parent_course_in_query' );
	function writer_ancora_lesson_add_parent_course_in_query($query) {
		if ( is_admin() && writer_ancora_strpos($_SERVER['REQUEST_URI'], 'edit.php')!==false && $query->is_main_query() && $query->get( 'post_type' )=='lesson' ) {
			$parent_course = isset( $_GET['parent_course'] ) ? intval($_GET['parent_course']) : 0;
			if ($parent_course > 0 ) {
				$meta_query = $query->get( 'meta_query' );
				if (!is_array($meta_query)) $meta_query = array();
				$meta_query['relation'] = 'AND';
				$meta_query[] = array(
					'meta_filter' => 'lesson',
					'key' => 'parent_course',
					'value' => $parent_course,
					'compare' => '=',
					'type' => 'NUMERIC'
				);
				$query->set( 'meta_query', $meta_query );
			}
		}
		return $query;
	}
}


/* Display metabox for lessons
-------------------------------------------------------------------------------------------- */

if (!function_exists('writer_ancora_lesson_after_theme_setup')) {
	add_action( 'writer_ancora_action_after_init_theme', 'writer_ancora_lesson_after_theme_setup' );
	function writer_ancora_lesson_after_theme_setup() {
		// Update fields in the meta box
		if (writer_ancora_storage_get_array('post_meta_box', 'page')=='lesson') {
			// Meta box fields
			writer_ancora_storage_set_array('post_meta_box','title', __('Lesson Options', 'writer-ancora'));
			writer_ancora_storage_set_array('post_meta_box', 'fields', array(
				"mb_partition_lessons" => array(
					"title" => __('Lesson', 'writer-ancora'),
					"override" => "page,post",
					"divider" => false,
					"icon" => "iconadmin-users-1",
					"type" => "partition"),
				"mb_info_lessons_1" => array(
					"title" => __('Lesson details', 'writer-ancora'),
					"override" => "page,post",
					"divider" => false,
					"desc" => __('In this section you can put details for this lesson', 'writer-ancora'),
					"class" => "course_meta",
					"type" => "info"),
				"parent_course" => array(
					"title" => __('Parent Course',  'writer-ancora'),
					"desc" => __("Select parent course for this lesson", 'writer-ancora'),
					"override" => "page,post",
					"class" => "lesson_parent_course",
					"std" => '',
					"options" => writer_ancora_get_list_posts(false, array(
						'post_type' => 'courses',
						'orderby' => 'title',
						'order' => 'asc'
						)
					),
					"type" => "select"),
				"teacher" => array(
					"title" => __('Teacher',  'writer-ancora'),
					"desc" => __("Main Teacher for this lesson", 'writer-ancora'),
					"override" => "page,post",
					"class" => "lesson_teacher",
					"std" => '',
					"options" => writer_ancora_get_list_posts(false, array(
						'post_type' => 'team',
						'orderby' => 'title',
						'order' => 'asc')
					),
					"type" => "select"),
				"date_start" => array(
					"title" => __('Start date',  'writer-ancora'),
					"desc" => __("Lesson start date", 'writer-ancora'),
					"override" => "page,post",
					"class" => "lesson_date",
					"std" => date('Y-m-d'),
					"format" => 'yy-mm-dd',
					"type" => "date"),
				"date_end" => array(
					"title" => __('End date',  'writer-ancora'),
					"desc" => __("Lesson finish date", 'writer-ancora'),
					"override" => "page,post",
					"class" => "lesson_date",
					"std" => date('Y-m-d'),
					"format" => 'yy-mm-dd',
					"type" => "date"),
				"shedule" => array(
					"title" => __('Schedule time',  'writer-ancora'),
					"desc" => __("Lesson start days and time. For example: Mon, Wed, Fri 19:00-21:00", 'writer-ancora'),
					"override" => "page,post",
					"class" => "lesson_time",
					"std" => '',
					"divider" => false,
					"type" => "text")
				)
			);
		}
	}
}

// Before save custom options - calc and save average rating
if (!function_exists('writer_ancora_lesson_save_custom_options')) {
	//add_filter('writer_ancora_filter_post_save_custom_options',	'writer_ancora_lesson_save_custom_options', 10, 3);
	function writer_ancora_lesson_save_custom_options($custom_options, $post_type, $post_id) {
		if (isset($custom_options['parent_course'])) {
			update_post_meta($post_id, 'parent_course', $custom_options['parent_course']);
		}
		if (isset($custom_options['date_start'])) {
			update_post_meta($post_id, 'date_start', $custom_options['date_start']);
		}
		return $custom_options;
	}
}


// Return lessons list by parent course post ID
if ( !function_exists( 'writer_ancora_get_lessons_list' ) ) {
	function writer_ancora_get_lessons_list($parent_id, $count=-1) {
		$list = array();
		$args = array(
			'post_type' => 'lesson',
			'post_status' => 'publish',
			'meta_key' => 'date_start',
			'orderby' => 'meta_value',		//'date'
			'order' => 'asc',
			'ignore_sticky_posts' => true,
			'posts_per_page' => $count,
			'meta_query' => array(
				array(
					'key'     => 'parent_course',
					'value'   => $parent_id,
					'compare' => '=',
					'type'    => 'NUMERIC'
				)
			)
		);
		global $post;
		$query = new WP_Query( $args );
		while ( $query->have_posts() ) { $query->the_post();
			$list[] = $post;
		}
		wp_reset_postdata();
		return $list;
	}
}

// Return lessons TOC by parent course post ID
if ( !function_exists( 'writer_ancora_get_lessons_links' ) ) {
	function writer_ancora_get_lessons_links($parent_id, $current_id=0, $opt = array()) {
		$opt = array_merge( array(
			'show_lessons' => true,
			'show_prev_next' => false,
			'header' => '',
			'description' => ''
			), $opt);
		$output = '';
		if ($parent_id > 0) {
			$courses_list = writer_ancora_get_lessons_list($parent_id);
			$courses_toc = '';
			$prev_course = $next_course = null;
			if (count($courses_list) > 1) {
				$step = 0;
				foreach ($courses_list as $course) {
					if ($course->ID == $current_id)
						$step = 1;
					else if ($step==0)
						$prev_course = $course;
					else if ($step==1) {
						$next_course = $course;
						$step = 2;
						if (!$opt['show_lessons']) break;
					}
					if ($opt['show_lessons']) {
						$teacher_id = writer_ancora_get_custom_option('teacher', '', $course->ID, $course->post_type);				//!!!!! Get option from specified post
						$teacher_post = get_post($teacher_id);
						$teacher_link = get_permalink($teacher_id);
						$teacher_position = '';
						// Uncomment next two rows if you want display Teacher's position
						//$teacher_data = get_post_meta($teacher_id, 'team_data', true);
						//$teacher_position = isset($teacher_data['team_member_position']) ? $teacher_data['team_member_position'] : '';
						$course_start = writer_ancora_get_custom_option('date_start', '', $course->ID, $course->post_type);			//!!!!! Get option from specified post
						$courses_toc .= '<li class="sc_list_item course_lesson_item">'
							. '<span class="sc_list_icon icon-dot"></span>'
							. ($course->ID == $current_id ? '<span class="course_lesson_title">' : '<a href="'.esc_url(get_permalink($course->ID)).'" class="course_lesson_title">') 
								. strip_tags($course->post_title) 
							. ($course->ID == $current_id ? '</span>' : '</a>')
							. ' | <span class="course_lesson_date">' . esc_html(writer_ancora_get_date_or_difference(!empty($course_start) ? $course_start : $course->post_date)) . '</span>'
							. ' <span class="course_lesson_by">' . esc_html(__('by', 'writer-ancora')) . '</span>'
							. ' <a href="'.esc_url($teacher_link).'" class="course_lesson_teacher">' . trim($teacher_position) . ' ' . trim($teacher_post->post_title) . '</a>'
							. (!empty($course->post_excerpt) ? '<div class="course_lesson_excerpt">' . strip_tags($course->post_excerpt) . '</div>' : '')
							. '</li>';
					}
				}
				$output .= ($opt['show_lessons'] 
								? ('<div class="course_toc' . ($opt['show_prev_next'] ? ' course_toc_with_pagination' : '') . '">'
									. ($opt['header'] ? '<h2 class="course_toc_title">' . trim($opt['header']) . '</h2>' : '')
									. ($opt['description'] ? '<div class="course_toc_description">' . trim($opt['description']) . '</div>' : '')
									. '<ul class="sc_list sc_list_style_iconed">' . trim($courses_toc) . '</ul>'
									. '</div>')
								: '')
					. ($opt['show_prev_next']
								? ('<nav class="pagination_single pagination_lessons" role="navigation">'
									. ($prev_course != null 
										? '<a href="' . esc_url(get_permalink($prev_course->ID)) . '" class="pager_prev"><span class="pager_numbers">&laquo;&nbsp;' . strip_tags($prev_course->post_title) . '</span></a>'
										: '')
									. ($next_course != null
										? '<a href="' . esc_url(get_permalink($next_course->ID)) . '" class="pager_next"><span class="pager_numbers">' . strip_tags($next_course->post_title) . '&nbsp;&raquo;</span></a>'
										: '')
									. '</nav>')
								: '');
			}
		}
		return $output;
	}
}






// ---------------------------------- [trx_lessons] ---------------------------------------

/*
[trx_lessons course_id="id"]
*/
if ( !function_exists( 'writer_ancora_sc_lessons' ) ) {
	function writer_ancora_sc_lessons($atts, $content=null){	
		if (writer_ancora_in_shortcode_blogger()) return '';
		extract(writer_ancora_html_decode(shortcode_atts(array(
			// Individual params
			"course_id" => "",
			"align" => "",
			"title" => "",
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
	
		$css .= writer_ancora_get_css_position_from_values($top, $right, $bottom, $left, $width, $height);
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
						. ' class="sc_lessons' 
								. (!empty($class) ? ' '.esc_attr($class) : '') 
								. ($align && $align!='none' ? ' align'.esc_attr($align) : '')
								. '"'
							. (!writer_ancora_param_is_off($animation) ? ' data-animation="'.esc_attr(writer_ancora_get_animation_classes($animation)).'"' : '')
							. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
					. '>'
					. writer_ancora_get_lessons_links($course_id, 0, array(
							'header' => $title,
							'description' => $description,
							'show_lessons' => true,
							'show_prev_next' => false
							)
						)
					. '</div>';
		
		return apply_filters('writer_ancora_shortcode_output', $output, 'trx_lessons', $atts, $content);
	}
	writer_ancora_require_shortcode('trx_lessons', 'writer_ancora_sc_lessons');
}
// ---------------------------------- [/trx_lessons] ---------------------------------------


// Add [trx_lessons] in the shortcodes list
if (!function_exists('writer_ancora_lesson_reg_shortcodes')) {
	//add_filter('writer_ancora_action_shortcodes_list',	'writer_ancora_lesson_reg_shortcodes');
	function writer_ancora_lesson_reg_shortcodes() {
		if (writer_ancora_storage_isset('shortcodes')) {

			$courses = writer_ancora_get_list_posts(false, array(
						'post_type' => 'courses',
						'orderby' => 'title',
						'order' => 'asc'
						)
			);

			writer_ancora_sc_map_after('trx_infobox', array(

				// Lessons
				"trx_lessons" => array(
					"title" => __("Lessons", 'writer-ancora'),
					"desc" => __("Insert list of lessons for specified course", 'writer-ancora'),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"course_id" => array(
							"title" => __("Course", 'writer-ancora'),
							"desc" => __("Select the desired course", 'writer-ancora'),
							"value" => "",
							"options" => $courses,
							"type" => "select"
						),
						"title" => array(
							"title" => __("Title", 'writer-ancora'),
							"desc" => __("Title for the section with lessons", 'writer-ancora'),
							"divider" => true,
							"dependency" => array(
								'course_id' => array('not_empty')
							),
							"value" => "",
							"type" => "text"
						),
						"description" => array(
							"title" => __("Description", 'writer-ancora'),
							"desc" => __("Description for the section with lessons", 'writer-ancora'),
							"divider" => true,
							"dependency" => array(
								'course_id' => array('not_empty')
							),
							"value" => "",
							"type" => "text"
						),
						"align" => array(
							"title" => __("Alignment", 'writer-ancora'),
							"desc" => __("Align block to the left or right side", 'writer-ancora'),
							"divider" => true,
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => writer_ancora_get_sc_param('float')
						), 
						"width" => writer_ancora_shortcodes_width(),
						"height" => writer_ancora_shortcodes_height(),
						"top" => writer_ancora_get_sc_param('top'),
						"bottom" => writer_ancora_get_sc_param('bottom'),
						"left" => writer_ancora_get_sc_param('left'),
						"right" => writer_ancora_get_sc_param('right'),
						"id" => writer_ancora_get_sc_param('id'),
						"class" => writer_ancora_get_sc_param('class'),
						"css" => writer_ancora_get_sc_param('css')
					)
				)

			));
		}
	}
}


// Add [trx_lessons] in the VC shortcodes list
if (!function_exists('writer_ancora_lesson_reg_shortcodes_vc')) {
	//add_filter('writer_ancora_action_shortcodes_list_vc',	'writer_ancora_lesson_reg_shortcodes_vc');
	function writer_ancora_lesson_reg_shortcodes_vc() {

		$courses = writer_ancora_get_list_posts(false, array(
					'post_type' => 'courses',
					'orderby' => 'title',
					'order' => 'asc'
					)
		);

		// Lessons
		vc_map( array(
			"base" => "trx_lessons",
			"name" => __("Lessons", 'writer-ancora'),
			"description" => __("Insert list of lessons for specified course", 'writer-ancora'),
			"category" => __('Content', 'writer-ancora'),
			'icon' => 'icon_trx_lessons',
			"class" => "trx_sc_single trx_sc_lessons",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "course_id",
					"heading" => __("Course", 'writer-ancora'),
					"description" => __("Select the desired course", 'writer-ancora'),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip($courses),
					"type" => "dropdown"
				),
				array(
					"param_name" => "title",
					"heading" => __("Title", 'writer-ancora'),
					"description" => __("Title for the section with lessons", 'writer-ancora'),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "description",
					"heading" => __("Description", 'writer-ancora'),
					"description" => __("Description for the section with lessons", 'writer-ancora'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "align",
					"heading" => __("Alignment", 'writer-ancora'),
					"description" => __("Alignment of the lessons block", 'writer-ancora'),
					"class" => "",
					"value" => array_flip(writer_ancora_get_sc_param('align')),
					"type" => "dropdown"
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
			)
		) );
		
		class WPBakeryShortCode_Trx_Lessons extends WRITER_ANCORA_VC_ShortCodeSingle {}
	}
}
?>