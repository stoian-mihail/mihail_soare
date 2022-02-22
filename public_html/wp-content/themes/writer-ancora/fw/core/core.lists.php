<?php
/**
 * Writer Ancora Framework: return lists
 *
 * @package writer_ancora
 * @since writer_ancora 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }



// Return styles list
if ( !function_exists( 'writer_ancora_get_list_styles' ) ) {
	function writer_ancora_get_list_styles($from=1, $to=2, $prepend_inherit=false) {
		$list = array();
		for ($i=$from; $i<=$to; $i++)
			$list[$i] = sprintf(esc_html__('Style %d', 'writer-ancora'), $i);
		return $prepend_inherit ? writer_ancora_array_merge(array('inherit' => esc_html__("Inherit", 'writer-ancora')), $list) : $list;
	}
}


// Return list of the shortcodes margins
if ( !function_exists( 'writer_ancora_get_list_margins' ) ) {
	function writer_ancora_get_list_margins($prepend_inherit=false) {
		if (($list = writer_ancora_storage_get('list_margins'))=='') {
			$list = array(
				'null'		=> esc_html__('0 (No margin)',	'writer-ancora'),
				'tiny'		=> esc_html__('Tiny',		'writer-ancora'),
				'small'		=> esc_html__('Small',		'writer-ancora'),
				'medium'	=> esc_html__('Medium',		'writer-ancora'),
				'large'		=> esc_html__('Large',		'writer-ancora'),
				'huge'		=> esc_html__('Huge',		'writer-ancora'),
				'tiny-'		=> esc_html__('Tiny (negative)',	'writer-ancora'),
				'small-'	=> esc_html__('Small (negative)',	'writer-ancora'),
				'medium-'	=> esc_html__('Medium (negative)',	'writer-ancora'),
				'large-'	=> esc_html__('Large (negative)',	'writer-ancora'),
				'huge-'		=> esc_html__('Huge (negative)',	'writer-ancora')
				);
			$list = apply_filters('writer_ancora_filter_list_margins', $list);
			if (writer_ancora_get_theme_setting('use_list_cache')) writer_ancora_storage_set('list_margins', $list);
		}
		return $prepend_inherit ? writer_ancora_array_merge(array('inherit' => esc_html__("Inherit", 'writer-ancora')), $list) : $list;
	}
}


// Return list of the animations
if ( !function_exists( 'writer_ancora_get_list_animations' ) ) {
	function writer_ancora_get_list_animations($prepend_inherit=false) {
		if (($list = writer_ancora_storage_get('list_animations'))=='') {
			$list = array(
				'none'			=> esc_html__('- None -',	'writer-ancora'),
				'bounced'		=> esc_html__('Bounced',		'writer-ancora'),
				'flash'			=> esc_html__('Flash',		'writer-ancora'),
				'flip'			=> esc_html__('Flip',		'writer-ancora'),
				'pulse'			=> esc_html__('Pulse',		'writer-ancora'),
				'rubberBand'	=> esc_html__('Rubber Band',	'writer-ancora'),
				'shake'			=> esc_html__('Shake',		'writer-ancora'),
				'swing'			=> esc_html__('Swing',		'writer-ancora'),
				'tada'			=> esc_html__('Tada',		'writer-ancora'),
				'wobble'		=> esc_html__('Wobble',		'writer-ancora')
				);
			$list = apply_filters('writer_ancora_filter_list_animations', $list);
			if (writer_ancora_get_theme_setting('use_list_cache')) writer_ancora_storage_set('list_animations', $list);
		}
		return $prepend_inherit ? writer_ancora_array_merge(array('inherit' => esc_html__("Inherit", 'writer-ancora')), $list) : $list;
	}
}


// Return list of the line styles
if ( !function_exists( 'writer_ancora_get_list_line_styles' ) ) {
	function writer_ancora_get_list_line_styles($prepend_inherit=false) {
		if (($list = writer_ancora_storage_get('list_line_styles'))=='') {
			$list = array(
				'solid'	=> esc_html__('Solid', 'writer-ancora'),
				'dashed'=> esc_html__('Dashed', 'writer-ancora'),
				'dotted'=> esc_html__('Dotted', 'writer-ancora'),
				'double'=> esc_html__('Double', 'writer-ancora'),
				'image'	=> esc_html__('Image', 'writer-ancora')
				);
			$list = apply_filters('writer_ancora_filter_list_line_styles', $list);
			if (writer_ancora_get_theme_setting('use_list_cache')) writer_ancora_storage_set('list_line_styles', $list);
		}
		return $prepend_inherit ? writer_ancora_array_merge(array('inherit' => esc_html__("Inherit", 'writer-ancora')), $list) : $list;
	}
}


// Return list of the enter animations
if ( !function_exists( 'writer_ancora_get_list_animations_in' ) ) {
	function writer_ancora_get_list_animations_in($prepend_inherit=false) {
		if (($list = writer_ancora_storage_get('list_animations_in'))=='') {
			$list = array(
				'none'				=> esc_html__('- None -',			'writer-ancora'),
				'bounceIn'			=> esc_html__('Bounce In',			'writer-ancora'),
				'bounceInUp'		=> esc_html__('Bounce In Up',		'writer-ancora'),
				'bounceInDown'		=> esc_html__('Bounce In Down',		'writer-ancora'),
				'bounceInLeft'		=> esc_html__('Bounce In Left',		'writer-ancora'),
				'bounceInRight'		=> esc_html__('Bounce In Right',	'writer-ancora'),
				'fadeIn'			=> esc_html__('Fade In',			'writer-ancora'),
				'fadeInUp'			=> esc_html__('Fade In Up',			'writer-ancora'),
				'fadeInDown'		=> esc_html__('Fade In Down',		'writer-ancora'),
				'fadeInLeft'		=> esc_html__('Fade In Left',		'writer-ancora'),
				'fadeInRight'		=> esc_html__('Fade In Right',		'writer-ancora'),
				'fadeInUpBig'		=> esc_html__('Fade In Up Big',		'writer-ancora'),
				'fadeInDownBig'		=> esc_html__('Fade In Down Big',	'writer-ancora'),
				'fadeInLeftBig'		=> esc_html__('Fade In Left Big',	'writer-ancora'),
				'fadeInRightBig'	=> esc_html__('Fade In Right Big',	'writer-ancora'),
				'flipInX'			=> esc_html__('Flip In X',			'writer-ancora'),
				'flipInY'			=> esc_html__('Flip In Y',			'writer-ancora'),
				'lightSpeedIn'		=> esc_html__('Light Speed In',		'writer-ancora'),
				'rotateIn'			=> esc_html__('Rotate In',			'writer-ancora'),
				'rotateInUpLeft'	=> esc_html__('Rotate In Down Left','writer-ancora'),
				'rotateInUpRight'	=> esc_html__('Rotate In Up Right',	'writer-ancora'),
				'rotateInDownLeft'	=> esc_html__('Rotate In Up Left',	'writer-ancora'),
				'rotateInDownRight'	=> esc_html__('Rotate In Down Right','writer-ancora'),
				'rollIn'			=> esc_html__('Roll In',			'writer-ancora'),
				'slideInUp'			=> esc_html__('Slide In Up',		'writer-ancora'),
				'slideInDown'		=> esc_html__('Slide In Down',		'writer-ancora'),
				'slideInLeft'		=> esc_html__('Slide In Left',		'writer-ancora'),
				'slideInRight'		=> esc_html__('Slide In Right',		'writer-ancora'),
				'zoomIn'			=> esc_html__('Zoom In',			'writer-ancora'),
				'zoomInUp'			=> esc_html__('Zoom In Up',			'writer-ancora'),
				'zoomInDown'		=> esc_html__('Zoom In Down',		'writer-ancora'),
				'zoomInLeft'		=> esc_html__('Zoom In Left',		'writer-ancora'),
				'zoomInRight'		=> esc_html__('Zoom In Right',		'writer-ancora')
				);
			$list = apply_filters('writer_ancora_filter_list_animations_in', $list);
			if (writer_ancora_get_theme_setting('use_list_cache')) writer_ancora_storage_set('list_animations_in', $list);
		}
		return $prepend_inherit ? writer_ancora_array_merge(array('inherit' => esc_html__("Inherit", 'writer-ancora')), $list) : $list;
	}
}


// Return list of the out animations
if ( !function_exists( 'writer_ancora_get_list_animations_out' ) ) {
	function writer_ancora_get_list_animations_out($prepend_inherit=false) {
		if (($list = writer_ancora_storage_get('list_animations_out'))=='') {
			$list = array(
				'none'				=> esc_html__('- None -',	'writer-ancora'),
				'bounceOut'			=> esc_html__('Bounce Out',			'writer-ancora'),
				'bounceOutUp'		=> esc_html__('Bounce Out Up',		'writer-ancora'),
				'bounceOutDown'		=> esc_html__('Bounce Out Down',		'writer-ancora'),
				'bounceOutLeft'		=> esc_html__('Bounce Out Left',		'writer-ancora'),
				'bounceOutRight'	=> esc_html__('Bounce Out Right',	'writer-ancora'),
				'fadeOut'			=> esc_html__('Fade Out',			'writer-ancora'),
				'fadeOutUp'			=> esc_html__('Fade Out Up',			'writer-ancora'),
				'fadeOutDown'		=> esc_html__('Fade Out Down',		'writer-ancora'),
				'fadeOutLeft'		=> esc_html__('Fade Out Left',		'writer-ancora'),
				'fadeOutRight'		=> esc_html__('Fade Out Right',		'writer-ancora'),
				'fadeOutUpBig'		=> esc_html__('Fade Out Up Big',		'writer-ancora'),
				'fadeOutDownBig'	=> esc_html__('Fade Out Down Big',	'writer-ancora'),
				'fadeOutLeftBig'	=> esc_html__('Fade Out Left Big',	'writer-ancora'),
				'fadeOutRightBig'	=> esc_html__('Fade Out Right Big',	'writer-ancora'),
				'flipOutX'			=> esc_html__('Flip Out X',			'writer-ancora'),
				'flipOutY'			=> esc_html__('Flip Out Y',			'writer-ancora'),
				'hinge'				=> esc_html__('Hinge Out',			'writer-ancora'),
				'lightSpeedOut'		=> esc_html__('Light Speed Out',		'writer-ancora'),
				'rotateOut'			=> esc_html__('Rotate Out',			'writer-ancora'),
				'rotateOutUpLeft'	=> esc_html__('Rotate Out Down Left',	'writer-ancora'),
				'rotateOutUpRight'	=> esc_html__('Rotate Out Up Right',		'writer-ancora'),
				'rotateOutDownLeft'	=> esc_html__('Rotate Out Up Left',		'writer-ancora'),
				'rotateOutDownRight'=> esc_html__('Rotate Out Down Right',	'writer-ancora'),
				'rollOut'			=> esc_html__('Roll Out',		'writer-ancora'),
				'slideOutUp'		=> esc_html__('Slide Out Up',		'writer-ancora'),
				'slideOutDown'		=> esc_html__('Slide Out Down',	'writer-ancora'),
				'slideOutLeft'		=> esc_html__('Slide Out Left',	'writer-ancora'),
				'slideOutRight'		=> esc_html__('Slide Out Right',	'writer-ancora'),
				'zoomOut'			=> esc_html__('Zoom Out',			'writer-ancora'),
				'zoomOutUp'			=> esc_html__('Zoom Out Up',		'writer-ancora'),
				'zoomOutDown'		=> esc_html__('Zoom Out Down',	'writer-ancora'),
				'zoomOutLeft'		=> esc_html__('Zoom Out Left',	'writer-ancora'),
				'zoomOutRight'		=> esc_html__('Zoom Out Right',	'writer-ancora')
				);
			$list = apply_filters('writer_ancora_filter_list_animations_out', $list);
			if (writer_ancora_get_theme_setting('use_list_cache')) writer_ancora_storage_set('list_animations_out', $list);
		}
		return $prepend_inherit ? writer_ancora_array_merge(array('inherit' => esc_html__("Inherit", 'writer-ancora')), $list) : $list;
	}
}

// Return classes list for the specified animation
if (!function_exists('writer_ancora_get_animation_classes')) {
	function writer_ancora_get_animation_classes($animation, $speed='normal', $loop='none') {
		// speed:	fast=0.5s | normal=1s | slow=2s
		// loop:	none | infinite
		return writer_ancora_param_is_off($animation) ? '' : 'animated '.esc_attr($animation).' '.esc_attr($speed).(!writer_ancora_param_is_off($loop) ? ' '.esc_attr($loop) : '');
	}
}


// Return list of categories
if ( !function_exists( 'writer_ancora_get_list_categories' ) ) {
	function writer_ancora_get_list_categories($prepend_inherit=false) {
		if (($list = writer_ancora_storage_get('list_categories'))=='') {
			$list = array();
			$args = array(
				'type'                     => 'post',
				'child_of'                 => 0,
				'parent'                   => '',
				'orderby'                  => 'name',
				'order'                    => 'ASC',
				'hide_empty'               => 0,
				'hierarchical'             => 1,
				'exclude'                  => '',
				'include'                  => '',
				'number'                   => '',
				'taxonomy'                 => 'category',
				'pad_counts'               => false );
			$taxonomies = get_categories( $args );
			if (is_array($taxonomies) && count($taxonomies) > 0) {
				foreach ($taxonomies as $cat) {
					$list[$cat->term_id] = $cat->name;
				}
			}
			if (writer_ancora_get_theme_setting('use_list_cache')) writer_ancora_storage_set('list_categories', $list);
		}
		return $prepend_inherit ? writer_ancora_array_merge(array('inherit' => esc_html__("Inherit", 'writer-ancora')), $list) : $list;
	}
}


// Return list of taxonomies
if ( !function_exists( 'writer_ancora_get_list_terms' ) ) {
	function writer_ancora_get_list_terms($prepend_inherit=false, $taxonomy='category') {
		if (($list = writer_ancora_storage_get('list_taxonomies_'.($taxonomy)))=='') {
			$list = array();
			if ( is_array($taxonomy) || taxonomy_exists($taxonomy) ) {
				$terms = get_terms( $taxonomy, array(
					'child_of'                 => 0,
					'parent'                   => '',
					'orderby'                  => 'name',
					'order'                    => 'ASC',
					'hide_empty'               => 0,
					'hierarchical'             => 1,
					'exclude'                  => '',
					'include'                  => '',
					'number'                   => '',
					'taxonomy'                 => $taxonomy,
					'pad_counts'               => false
					)
				);
			} else {
				$terms = writer_ancora_get_terms_by_taxonomy_from_db($taxonomy);
			}
			if (!is_wp_error( $terms ) && is_array($terms) && count($terms) > 0) {
				foreach ($terms as $cat) {
					$list[$cat->term_id] = $cat->name;	// . ($taxonomy!='category' ? ' /'.($cat->taxonomy).'/' : '');
				}
			}
			if (writer_ancora_get_theme_setting('use_list_cache')) writer_ancora_storage_set('list_taxonomies_'.($taxonomy), $list);
		}
		return $prepend_inherit ? writer_ancora_array_merge(array('inherit' => esc_html__("Inherit", 'writer-ancora')), $list) : $list;
	}
}

// Return list of post's types
if ( !function_exists( 'writer_ancora_get_list_posts_types' ) ) {
	function writer_ancora_get_list_posts_types($prepend_inherit=false) {
		if (($list = writer_ancora_storage_get('list_posts_types'))=='') {
			/* 
			// This way to return all registered post types
			$types = get_post_types();
			if (in_array('post', $types)) $list['post'] = esc_html__('Post', 'writer-ancora');
			if (is_array($types) && count($types) > 0) {
				foreach ($types as $t) {
					if ($t == 'post') continue;
					$list[$t] = writer_ancora_strtoproper($t);
				}
			}
			*/
			// Return only theme inheritance supported post types
			$list = apply_filters('writer_ancora_filter_list_post_types', $list);
			if (writer_ancora_get_theme_setting('use_list_cache')) writer_ancora_storage_set('list_posts_types', $list);
		}
		return $prepend_inherit ? writer_ancora_array_merge(array('inherit' => esc_html__("Inherit", 'writer-ancora')), $list) : $list;
	}
}


// Return list post items from any post type and taxonomy
if ( !function_exists( 'writer_ancora_get_list_posts' ) ) {
	function writer_ancora_get_list_posts($prepend_inherit=false, $opt=array()) {
		$opt = array_merge(array(
			'post_type'			=> 'post',
			'post_status'		=> 'publish',
			'taxonomy'			=> 'category',
			'taxonomy_value'	=> '',
			'posts_per_page'	=> -1,
			'orderby'			=> 'post_date',
			'order'				=> 'desc',
			'return'			=> 'id'
			), is_array($opt) ? $opt : array('post_type'=>$opt));

		$hash = 'list_posts_'.($opt['post_type']).'_'.($opt['taxonomy']).'_'.($opt['taxonomy_value']).'_'.($opt['orderby']).'_'.($opt['order']).'_'.($opt['return']).'_'.($opt['posts_per_page']);
		if (($list = writer_ancora_storage_get($hash))=='') {
			$list = array();
			$list['none'] = esc_html__("- Not selected -", 'writer-ancora');
			$args = array(
				'post_type' => $opt['post_type'],
				'post_status' => $opt['post_status'],
				'posts_per_page' => $opt['posts_per_page'],
				'ignore_sticky_posts' => true,
				'orderby'	=> $opt['orderby'],
				'order'		=> $opt['order']
			);
			if (!empty($opt['taxonomy_value'])) {
				$args['tax_query'] = array(
					array(
						'taxonomy' => $opt['taxonomy'],
						'field' => (int) $opt['taxonomy_value'] > 0 ? 'id' : 'slug',
						'terms' => $opt['taxonomy_value']
					)
				);
			}
			$posts = get_posts( $args );
			if (is_array($posts) && count($posts) > 0) {
				foreach ($posts as $post) {
					$list[$opt['return']=='id' ? $post->ID : $post->post_title] = $post->post_title;
				}
			}
			if (writer_ancora_get_theme_setting('use_list_cache')) writer_ancora_storage_set($hash, $list);
		}
		return $prepend_inherit ? writer_ancora_array_merge(array('inherit' => esc_html__("Inherit", 'writer-ancora')), $list) : $list;
	}
}


// Return list pages
if ( !function_exists( 'writer_ancora_get_list_pages' ) ) {
	function writer_ancora_get_list_pages($prepend_inherit=false, $opt=array()) {
		$opt = array_merge(array(
			'post_type'			=> 'page',
			'post_status'		=> 'publish',
			'posts_per_page'	=> -1,
			'orderby'			=> 'title',
			'order'				=> 'asc',
			'return'			=> 'id'
			), is_array($opt) ? $opt : array('post_type'=>$opt));
		return writer_ancora_get_list_posts($prepend_inherit, $opt);
	}
}


// Return list of registered users
if ( !function_exists( 'writer_ancora_get_list_users' ) ) {
	function writer_ancora_get_list_users($prepend_inherit=false, $roles=array('administrator', 'editor', 'author', 'contributor', 'shop_manager')) {
		if (($list = writer_ancora_storage_get('list_users'))=='') {
			$list = array();
			$list['none'] = esc_html__("- Not selected -", 'writer-ancora');
			$args = array(
				'orderby'	=> 'display_name',
				'order'		=> 'ASC' );
			$users = get_users( $args );
			if (is_array($users) && count($users) > 0) {
				foreach ($users as $user) {
					$accept = true;
					if (is_array($user->roles)) {
						if (is_array($user->roles) && count($user->roles) > 0) {
							$accept = false;
							foreach ($user->roles as $role) {
								if (in_array($role, $roles)) {
									$accept = true;
									break;
								}
							}
						}
					}
					if ($accept) $list[$user->user_login] = $user->display_name;
				}
			}
			if (writer_ancora_get_theme_setting('use_list_cache')) writer_ancora_storage_set('list_users', $list);
		}
		return $prepend_inherit ? writer_ancora_array_merge(array('inherit' => esc_html__("Inherit", 'writer-ancora')), $list) : $list;
	}
}


// Return slider engines list, prepended inherit (if need)
if ( !function_exists( 'writer_ancora_get_list_sliders' ) ) {
	function writer_ancora_get_list_sliders($prepend_inherit=false) {
		if (($list = writer_ancora_storage_get('list_sliders'))=='') {
			$list = array(
				'swiper' => esc_html__("Posts slider (Swiper)", 'writer-ancora')
			);
			$list = apply_filters('writer_ancora_filter_list_sliders', $list);
			if (writer_ancora_get_theme_setting('use_list_cache')) writer_ancora_storage_set('list_sliders', $list);
		}
		return $prepend_inherit ? writer_ancora_array_merge(array('inherit' => esc_html__("Inherit", 'writer-ancora')), $list) : $list;
	}
}


// Return slider controls list, prepended inherit (if need)
if ( !function_exists( 'writer_ancora_get_list_slider_controls' ) ) {
	function writer_ancora_get_list_slider_controls($prepend_inherit=false) {
		if (($list = writer_ancora_storage_get('list_slider_controls'))=='') {
			$list = array(
				'no'		=> esc_html__('None', 'writer-ancora'),
				'side'		=> esc_html__('Side', 'writer-ancora'),
				'bottom'	=> esc_html__('Bottom', 'writer-ancora'),
				'pagination'=> esc_html__('Pagination', 'writer-ancora')
				);
			$list = apply_filters('writer_ancora_filter_list_slider_controls', $list);
			if (writer_ancora_get_theme_setting('use_list_cache')) writer_ancora_storage_set('list_slider_controls', $list);
		}
		return $prepend_inherit ? writer_ancora_array_merge(array('inherit' => esc_html__("Inherit", 'writer-ancora')), $list) : $list;
	}
}


// Return slider controls classes
if ( !function_exists( 'writer_ancora_get_slider_controls_classes' ) ) {
	function writer_ancora_get_slider_controls_classes($controls) {
		if (writer_ancora_param_is_off($controls))	$classes = 'sc_slider_nopagination sc_slider_nocontrols';
		else if ($controls=='bottom')			$classes = 'sc_slider_nopagination sc_slider_controls sc_slider_controls_bottom';
		else if ($controls=='pagination')		$classes = 'sc_slider_pagination sc_slider_pagination_bottom sc_slider_nocontrols';
		else									$classes = 'sc_slider_nopagination sc_slider_controls sc_slider_controls_side';
		return $classes;
	}
}

// Return list with popup engines
if ( !function_exists( 'writer_ancora_get_list_popup_engines' ) ) {
	function writer_ancora_get_list_popup_engines($prepend_inherit=false) {
		if (($list = writer_ancora_storage_get('list_popup_engines'))=='') {
			$list = array(
				"pretty"	=> esc_html__("Pretty photo", 'writer-ancora'),
				"magnific"	=> esc_html__("Magnific popup", 'writer-ancora')
				);
			$list = apply_filters('writer_ancora_filter_list_popup_engines', $list);
			if (writer_ancora_get_theme_setting('use_list_cache')) writer_ancora_storage_set('list_popup_engines', $list);
		}
		return $prepend_inherit ? writer_ancora_array_merge(array('inherit' => esc_html__("Inherit", 'writer-ancora')), $list) : $list;
	}
}

// Return menus list, prepended inherit
if ( !function_exists( 'writer_ancora_get_list_menus' ) ) {
	function writer_ancora_get_list_menus($prepend_inherit=false) {
		if (($list = writer_ancora_storage_get('list_menus'))=='') {
			$list = array();
			$list['default'] = esc_html__("Default", 'writer-ancora');
			$menus = wp_get_nav_menus();
			if (is_array($menus) && count($menus) > 0) {
				foreach ($menus as $menu) {
					$list[$menu->slug] = $menu->name;
				}
			}
			if (writer_ancora_get_theme_setting('use_list_cache')) writer_ancora_storage_set('list_menus', $list);
		}
		return $prepend_inherit ? writer_ancora_array_merge(array('inherit' => esc_html__("Inherit", 'writer-ancora')), $list) : $list;
	}
}

// Return custom sidebars list, prepended inherit and main sidebars item (if need)
if ( !function_exists( 'writer_ancora_get_list_sidebars' ) ) {
	function writer_ancora_get_list_sidebars($prepend_inherit=false) {
		if (($list = writer_ancora_storage_get('list_sidebars'))=='') {
			if (($list = writer_ancora_storage_get('registered_sidebars'))=='') $list = array();
			if (writer_ancora_get_theme_setting('use_list_cache')) writer_ancora_storage_set('list_sidebars', $list);
		}
		return $prepend_inherit ? writer_ancora_array_merge(array('inherit' => esc_html__("Inherit", 'writer-ancora')), $list) : $list;
	}
}

// Return sidebars positions
if ( !function_exists( 'writer_ancora_get_list_sidebars_positions' ) ) {
	function writer_ancora_get_list_sidebars_positions($prepend_inherit=false) {
		if (($list = writer_ancora_storage_get('list_sidebars_positions'))=='') {
			$list = array(
				'none'  => esc_html__('Hide',  'writer-ancora'),
				'left'  => esc_html__('Left',  'writer-ancora'),
				'right' => esc_html__('Right', 'writer-ancora')
				);
			if (writer_ancora_get_theme_setting('use_list_cache')) writer_ancora_storage_set('list_sidebars_positions', $list);
		}
		return $prepend_inherit ? writer_ancora_array_merge(array('inherit' => esc_html__("Inherit", 'writer-ancora')), $list) : $list;
	}
}

// Return sidebars class
if ( !function_exists( 'writer_ancora_get_sidebar_class' ) ) {
	function writer_ancora_get_sidebar_class() {
		$sb_main = writer_ancora_get_custom_option('show_sidebar_main');
		$sb_outer = writer_ancora_get_custom_option('show_sidebar_outer');
		return (writer_ancora_param_is_off($sb_main) ? 'sidebar_hide' : 'sidebar_show sidebar_'.($sb_main))
				. ' ' . (writer_ancora_param_is_off($sb_outer) ? 'sidebar_outer_hide' : 'sidebar_outer_show sidebar_outer_'.($sb_outer));
	}
}

// Return body styles list, prepended inherit
if ( !function_exists( 'writer_ancora_get_list_body_styles' ) ) {
	function writer_ancora_get_list_body_styles($prepend_inherit=false) {
		if (($list = writer_ancora_storage_get('list_body_styles'))=='') {
			$list = array(
				'boxed'	=> esc_html__('Boxed',		'writer-ancora'),
				'wide'	=> esc_html__('Wide',		'writer-ancora')
				);
			if (writer_ancora_get_theme_setting('allow_fullscreen')) {
				$list['fullwide']	= esc_html__('Fullwide',	'writer-ancora');
				$list['fullscreen']	= esc_html__('Fullscreen',	'writer-ancora');
			}
			$list = apply_filters('writer_ancora_filter_list_body_styles', $list);
			if (writer_ancora_get_theme_setting('use_list_cache')) writer_ancora_storage_set('list_body_styles', $list);
		}
		return $prepend_inherit ? writer_ancora_array_merge(array('inherit' => esc_html__("Inherit", 'writer-ancora')), $list) : $list;
	}
}

// Return skins list, prepended inherit
if ( !function_exists( 'writer_ancora_get_list_skins' ) ) {
	function writer_ancora_get_list_skins($prepend_inherit=false) {
		if (($list = writer_ancora_storage_get('list_skins'))=='') {
			$list = writer_ancora_get_list_folders("skins");
			if (writer_ancora_get_theme_setting('use_list_cache')) writer_ancora_storage_set('list_skins', $list);
		}
		return $prepend_inherit ? writer_ancora_array_merge(array('inherit' => esc_html__("Inherit", 'writer-ancora')), $list) : $list;
	}
}

// Return css-themes list
if ( !function_exists( 'writer_ancora_get_list_themes' ) ) {
	function writer_ancora_get_list_themes($prepend_inherit=false) {
		if (($list = writer_ancora_storage_get('list_themes'))=='') {
			$list = writer_ancora_get_list_files("css/themes");
			if (writer_ancora_get_theme_setting('use_list_cache')) writer_ancora_storage_set('list_themes', $list);
		}
		return $prepend_inherit ? writer_ancora_array_merge(array('inherit' => esc_html__("Inherit", 'writer-ancora')), $list) : $list;
	}
}

// Return templates list, prepended inherit
if ( !function_exists( 'writer_ancora_get_list_templates' ) ) {
	function writer_ancora_get_list_templates($mode='') {
		if (($list = writer_ancora_storage_get('list_templates_'.($mode)))=='') {
			$list = array();
			$tpl = writer_ancora_storage_get('registered_templates');
			if (is_array($tpl) && count($tpl) > 0) {
				foreach ($tpl as $k=>$v) {
					if ($mode=='' || in_array($mode, explode(',', $v['mode'])))
						$list[$k] = !empty($v['icon']) 
									? $v['icon'] 
									: (!empty($v['title']) 
										? $v['title'] 
										: writer_ancora_strtoproper($v['layout'])
										);
				}
			}
			if (writer_ancora_get_theme_setting('use_list_cache')) writer_ancora_storage_set('list_templates_'.($mode), $list);
		}
		return $list;
	}
}

// Return blog styles list, prepended inherit
if ( !function_exists( 'writer_ancora_get_list_templates_blog' ) ) {
	function writer_ancora_get_list_templates_blog($prepend_inherit=false) {
		if (($list = writer_ancora_storage_get('list_templates_blog'))=='') {
			$list = writer_ancora_get_list_templates('blog');
			if (writer_ancora_get_theme_setting('use_list_cache')) writer_ancora_storage_set('list_templates_blog', $list);
		}
		return $prepend_inherit ? writer_ancora_array_merge(array('inherit' => esc_html__("Inherit", 'writer-ancora')), $list) : $list;
	}
}

// Return blogger styles list, prepended inherit
if ( !function_exists( 'writer_ancora_get_list_templates_blogger' ) ) {
	function writer_ancora_get_list_templates_blogger($prepend_inherit=false) {
		if (($list = writer_ancora_storage_get('list_templates_blogger'))=='') {
			$list = writer_ancora_array_merge(writer_ancora_get_list_templates('blogger'), writer_ancora_get_list_templates('blog'));
			if (writer_ancora_get_theme_setting('use_list_cache')) writer_ancora_storage_set('list_templates_blogger', $list);
		}
		return $prepend_inherit ? writer_ancora_array_merge(array('inherit' => esc_html__("Inherit", 'writer-ancora')), $list) : $list;
	}
}

// Return single page styles list, prepended inherit
if ( !function_exists( 'writer_ancora_get_list_templates_single' ) ) {
	function writer_ancora_get_list_templates_single($prepend_inherit=false) {
		if (($list = writer_ancora_storage_get('list_templates_single'))=='') {
			$list = writer_ancora_get_list_templates('single');
			if (writer_ancora_get_theme_setting('use_list_cache')) writer_ancora_storage_set('list_templates_single', $list);
		}
		return $prepend_inherit ? writer_ancora_array_merge(array('inherit' => esc_html__("Inherit", 'writer-ancora')), $list) : $list;
	}
}

// Return header styles list, prepended inherit
if ( !function_exists( 'writer_ancora_get_list_templates_header' ) ) {
	function writer_ancora_get_list_templates_header($prepend_inherit=false) {
		if (($list = writer_ancora_storage_get('list_templates_header'))=='') {
			$list = writer_ancora_get_list_templates('header');
			if (writer_ancora_get_theme_setting('use_list_cache')) writer_ancora_storage_set('list_templates_header', $list);
		}
		return $prepend_inherit ? writer_ancora_array_merge(array('inherit' => esc_html__("Inherit", 'writer-ancora')), $list) : $list;
	}
}

// Return form styles list, prepended inherit
if ( !function_exists( 'writer_ancora_get_list_templates_forms' ) ) {
	function writer_ancora_get_list_templates_forms($prepend_inherit=false) {
		if (($list = writer_ancora_storage_get('list_templates_forms'))=='') {
			$list = writer_ancora_get_list_templates('forms');
			if (writer_ancora_get_theme_setting('use_list_cache')) writer_ancora_storage_set('list_templates_forms', $list);
		}
		return $prepend_inherit ? writer_ancora_array_merge(array('inherit' => esc_html__("Inherit", 'writer-ancora')), $list) : $list;
	}
}

// Return article styles list, prepended inherit
if ( !function_exists( 'writer_ancora_get_list_article_styles' ) ) {
	function writer_ancora_get_list_article_styles($prepend_inherit=false) {
		if (($list = writer_ancora_storage_get('list_article_styles'))=='') {
			$list = array(
				"boxed"   => esc_html__('Boxed', 'writer-ancora'),
				"stretch" => esc_html__('Stretch', 'writer-ancora')
				);
			if (writer_ancora_get_theme_setting('use_list_cache')) writer_ancora_storage_set('list_article_styles', $list);
		}
		return $prepend_inherit ? writer_ancora_array_merge(array('inherit' => esc_html__("Inherit", 'writer-ancora')), $list) : $list;
	}
}

// Return post-formats filters list, prepended inherit
if ( !function_exists( 'writer_ancora_get_list_post_formats_filters' ) ) {
	function writer_ancora_get_list_post_formats_filters($prepend_inherit=false) {
		if (($list = writer_ancora_storage_get('list_post_formats_filters'))=='') {
			$list = array(
				"no"      => esc_html__('All posts', 'writer-ancora'),
				"thumbs"  => esc_html__('With thumbs', 'writer-ancora'),
				"reviews" => esc_html__('With reviews', 'writer-ancora'),
				"video"   => esc_html__('With videos', 'writer-ancora'),
				"audio"   => esc_html__('With audios', 'writer-ancora'),
				"gallery" => esc_html__('With galleries', 'writer-ancora')
				);
			if (writer_ancora_get_theme_setting('use_list_cache')) writer_ancora_storage_set('list_post_formats_filters', $list);
		}
		return $prepend_inherit ? writer_ancora_array_merge(array('inherit' => esc_html__("Inherit", 'writer-ancora')), $list) : $list;
	}
}

// Return portfolio filters list, prepended inherit
if ( !function_exists( 'writer_ancora_get_list_portfolio_filters' ) ) {
	function writer_ancora_get_list_portfolio_filters($prepend_inherit=false) {
		if (($list = writer_ancora_storage_get('list_portfolio_filters'))=='') {
			$list = array(
				"hide"		=> esc_html__('Hide', 'writer-ancora'),
				"tags"		=> esc_html__('Tags', 'writer-ancora'),
				"categories"=> esc_html__('Categories', 'writer-ancora')
				);
			if (writer_ancora_get_theme_setting('use_list_cache')) writer_ancora_storage_set('list_portfolio_filters', $list);
		}
		return $prepend_inherit ? writer_ancora_array_merge(array('inherit' => esc_html__("Inherit", 'writer-ancora')), $list) : $list;
	}
}

// Return hover styles list, prepended inherit
if ( !function_exists( 'writer_ancora_get_list_hovers' ) ) {
	function writer_ancora_get_list_hovers($prepend_inherit=false) {
		if (($list = writer_ancora_storage_get('list_hovers'))=='') {
			$list = array();
			$list['circle effect1']  = esc_html__('Circle Effect 1',  'writer-ancora');
			$list['circle effect2']  = esc_html__('Circle Effect 2',  'writer-ancora');
			$list['circle effect3']  = esc_html__('Circle Effect 3',  'writer-ancora');
			$list['circle effect4']  = esc_html__('Circle Effect 4',  'writer-ancora');
			$list['circle effect5']  = esc_html__('Circle Effect 5',  'writer-ancora');
			$list['circle effect6']  = esc_html__('Circle Effect 6',  'writer-ancora');
			$list['circle effect7']  = esc_html__('Circle Effect 7',  'writer-ancora');
			$list['circle effect8']  = esc_html__('Circle Effect 8',  'writer-ancora');
			$list['circle effect9']  = esc_html__('Circle Effect 9',  'writer-ancora');
			$list['circle effect10'] = esc_html__('Circle Effect 10',  'writer-ancora');
			$list['circle effect11'] = esc_html__('Circle Effect 11',  'writer-ancora');
			$list['circle effect12'] = esc_html__('Circle Effect 12',  'writer-ancora');
			$list['circle effect13'] = esc_html__('Circle Effect 13',  'writer-ancora');
			$list['circle effect14'] = esc_html__('Circle Effect 14',  'writer-ancora');
			$list['circle effect15'] = esc_html__('Circle Effect 15',  'writer-ancora');
			$list['circle effect16'] = esc_html__('Circle Effect 16',  'writer-ancora');
			$list['circle effect17'] = esc_html__('Circle Effect 17',  'writer-ancora');
			$list['circle effect18'] = esc_html__('Circle Effect 18',  'writer-ancora');
			$list['circle effect19'] = esc_html__('Circle Effect 19',  'writer-ancora');
			$list['circle effect20'] = esc_html__('Circle Effect 20',  'writer-ancora');
			$list['square effect1']  = esc_html__('Square Effect 1',  'writer-ancora');
			$list['square effect2']  = esc_html__('Square Effect 2',  'writer-ancora');
			$list['square effect3']  = esc_html__('Square Effect 3',  'writer-ancora');
	//		$list['square effect4']  = esc_html__('Square Effect 4',  'writer-ancora');
			$list['square effect5']  = esc_html__('Square Effect 5',  'writer-ancora');
			$list['square effect6']  = esc_html__('Square Effect 6',  'writer-ancora');
			$list['square effect7']  = esc_html__('Square Effect 7',  'writer-ancora');
			$list['square effect8']  = esc_html__('Square Effect 8',  'writer-ancora');
			$list['square effect9']  = esc_html__('Square Effect 9',  'writer-ancora');
			$list['square effect10'] = esc_html__('Square Effect 10',  'writer-ancora');
			$list['square effect11'] = esc_html__('Square Effect 11',  'writer-ancora');
			$list['square effect12'] = esc_html__('Square Effect 12',  'writer-ancora');
			$list['square effect13'] = esc_html__('Square Effect 13',  'writer-ancora');
			$list['square effect14'] = esc_html__('Square Effect 14',  'writer-ancora');
			$list['square effect15'] = esc_html__('Square Effect 15',  'writer-ancora');
			$list['square effect_dir']   = esc_html__('Square Effect Dir',   'writer-ancora');
			$list['square effect_shift'] = esc_html__('Square Effect Shift', 'writer-ancora');
			$list['square effect_book']  = esc_html__('Square Effect Book',  'writer-ancora');
			$list['square effect_more']  = esc_html__('Square Effect More',  'writer-ancora');
			$list['square effect_fade']  = esc_html__('Square Effect Fade',  'writer-ancora');
			$list = apply_filters('writer_ancora_filter_portfolio_hovers', $list);
			if (writer_ancora_get_theme_setting('use_list_cache')) writer_ancora_storage_set('list_hovers', $list);
		}
		return $prepend_inherit ? writer_ancora_array_merge(array('inherit' => esc_html__("Inherit", 'writer-ancora')), $list) : $list;
	}
}


// Return list of the blog counters
if ( !function_exists( 'writer_ancora_get_list_blog_counters' ) ) {
	function writer_ancora_get_list_blog_counters($prepend_inherit=false) {
		if (($list = writer_ancora_storage_get('list_blog_counters'))=='') {
			$list = array(
				'views'		=> esc_html__('Views', 'writer-ancora'),
				'likes'		=> esc_html__('Likes', 'writer-ancora'),
				'rating'	=> esc_html__('Rating', 'writer-ancora'),
				'comments'	=> esc_html__('Comments', 'writer-ancora')
				);
			$list = apply_filters('writer_ancora_filter_list_blog_counters', $list);
			if (writer_ancora_get_theme_setting('use_list_cache')) writer_ancora_storage_set('list_blog_counters', $list);
		}
		return $prepend_inherit ? writer_ancora_array_merge(array('inherit' => esc_html__("Inherit", 'writer-ancora')), $list) : $list;
	}
}

// Return list of the item sizes for the portfolio alter style, prepended inherit
if ( !function_exists( 'writer_ancora_get_list_alter_sizes' ) ) {
	function writer_ancora_get_list_alter_sizes($prepend_inherit=false) {
		if (($list = writer_ancora_storage_get('list_alter_sizes'))=='') {
			$list = array(
					'1_1' => esc_html__('1x1', 'writer-ancora'),
					'1_2' => esc_html__('1x2', 'writer-ancora'),
					'2_1' => esc_html__('2x1', 'writer-ancora'),
					'2_2' => esc_html__('2x2', 'writer-ancora'),
					'1_3' => esc_html__('1x3', 'writer-ancora'),
					'2_3' => esc_html__('2x3', 'writer-ancora'),
					'3_1' => esc_html__('3x1', 'writer-ancora'),
					'3_2' => esc_html__('3x2', 'writer-ancora'),
					'3_3' => esc_html__('3x3', 'writer-ancora')
					);
			$list = apply_filters('writer_ancora_filter_portfolio_alter_sizes', $list);
			if (writer_ancora_get_theme_setting('use_list_cache')) writer_ancora_storage_set('list_alter_sizes', $list);
		}
		return $prepend_inherit ? writer_ancora_array_merge(array('inherit' => esc_html__("Inherit", 'writer-ancora')), $list) : $list;
	}
}

// Return extended hover directions list, prepended inherit
if ( !function_exists( 'writer_ancora_get_list_hovers_directions' ) ) {
	function writer_ancora_get_list_hovers_directions($prepend_inherit=false) {
		if (($list = writer_ancora_storage_get('list_hovers_directions'))=='') {
			$list = array(
				'left_to_right' => esc_html__('Left to Right',  'writer-ancora'),
				'right_to_left' => esc_html__('Right to Left',  'writer-ancora'),
				'top_to_bottom' => esc_html__('Top to Bottom',  'writer-ancora'),
				'bottom_to_top' => esc_html__('Bottom to Top',  'writer-ancora'),
				'scale_up'      => esc_html__('Scale Up',  'writer-ancora'),
				'scale_down'    => esc_html__('Scale Down',  'writer-ancora'),
				'scale_down_up' => esc_html__('Scale Down-Up',  'writer-ancora'),
				'from_left_and_right' => esc_html__('From Left and Right',  'writer-ancora'),
				'from_top_and_bottom' => esc_html__('From Top and Bottom',  'writer-ancora')
			);
			$list = apply_filters('writer_ancora_filter_portfolio_hovers_directions', $list);
			if (writer_ancora_get_theme_setting('use_list_cache')) writer_ancora_storage_set('list_hovers_directions', $list);
		}
		return $prepend_inherit ? writer_ancora_array_merge(array('inherit' => esc_html__("Inherit", 'writer-ancora')), $list) : $list;
	}
}


// Return list of the label positions in the custom forms
if ( !function_exists( 'writer_ancora_get_list_label_positions' ) ) {
	function writer_ancora_get_list_label_positions($prepend_inherit=false) {
		if (($list = writer_ancora_storage_get('list_label_positions'))=='') {
			$list = array(
				'top'		=> esc_html__('Top',		'writer-ancora'),
				'bottom'	=> esc_html__('Bottom',		'writer-ancora'),
				'left'		=> esc_html__('Left',		'writer-ancora'),
				'over'		=> esc_html__('Over',		'writer-ancora')
			);
			$list = apply_filters('writer_ancora_filter_label_positions', $list);
			if (writer_ancora_get_theme_setting('use_list_cache')) writer_ancora_storage_set('list_label_positions', $list);
		}
		return $prepend_inherit ? writer_ancora_array_merge(array('inherit' => esc_html__("Inherit", 'writer-ancora')), $list) : $list;
	}
}


// Return list of the bg image positions
if ( !function_exists( 'writer_ancora_get_list_bg_image_positions' ) ) {
	function writer_ancora_get_list_bg_image_positions($prepend_inherit=false) {
		if (($list = writer_ancora_storage_get('list_bg_image_positions'))=='') {
			$list = array(
				'left top'	   => esc_html__('Left Top', 'writer-ancora'),
				'center top'   => esc_html__("Center Top", 'writer-ancora'),
				'right top'    => esc_html__("Right Top", 'writer-ancora'),
				'left center'  => esc_html__("Left Center", 'writer-ancora'),
				'center center'=> esc_html__("Center Center", 'writer-ancora'),
				'right center' => esc_html__("Right Center", 'writer-ancora'),
				'left bottom'  => esc_html__("Left Bottom", 'writer-ancora'),
				'center bottom'=> esc_html__("Center Bottom", 'writer-ancora'),
				'right bottom' => esc_html__("Right Bottom", 'writer-ancora')
			);
			if (writer_ancora_get_theme_setting('use_list_cache')) writer_ancora_storage_set('list_bg_image_positions', $list);
		}
		return $prepend_inherit ? writer_ancora_array_merge(array('inherit' => esc_html__("Inherit", 'writer-ancora')), $list) : $list;
	}
}


// Return list of the bg image repeat
if ( !function_exists( 'writer_ancora_get_list_bg_image_repeats' ) ) {
	function writer_ancora_get_list_bg_image_repeats($prepend_inherit=false) {
		if (($list = writer_ancora_storage_get('list_bg_image_repeats'))=='') {
			$list = array(
				'repeat'	=> esc_html__('Repeat', 'writer-ancora'),
				'repeat-x'	=> esc_html__('Repeat X', 'writer-ancora'),
				'repeat-y'	=> esc_html__('Repeat Y', 'writer-ancora'),
				'no-repeat'	=> esc_html__('No Repeat', 'writer-ancora')
			);
			if (writer_ancora_get_theme_setting('use_list_cache')) writer_ancora_storage_set('list_bg_image_repeats', $list);
		}
		return $prepend_inherit ? writer_ancora_array_merge(array('inherit' => esc_html__("Inherit", 'writer-ancora')), $list) : $list;
	}
}


// Return list of the bg image attachment
if ( !function_exists( 'writer_ancora_get_list_bg_image_attachments' ) ) {
	function writer_ancora_get_list_bg_image_attachments($prepend_inherit=false) {
		if (($list = writer_ancora_storage_get('list_bg_image_attachments'))=='') {
			$list = array(
				'scroll'	=> esc_html__('Scroll', 'writer-ancora'),
				'fixed'		=> esc_html__('Fixed', 'writer-ancora'),
				'local'		=> esc_html__('Local', 'writer-ancora')
			);
			if (writer_ancora_get_theme_setting('use_list_cache')) writer_ancora_storage_set('list_bg_image_attachments', $list);
		}
		return $prepend_inherit ? writer_ancora_array_merge(array('inherit' => esc_html__("Inherit", 'writer-ancora')), $list) : $list;
	}
}


// Return list of the bg tints
if ( !function_exists( 'writer_ancora_get_list_bg_tints' ) ) {
	function writer_ancora_get_list_bg_tints($prepend_inherit=false) {
		if (($list = writer_ancora_storage_get('list_bg_tints'))=='') {
			$list = array(
				'white'	=> esc_html__('White', 'writer-ancora'),
				'light'	=> esc_html__('Light', 'writer-ancora'),
				'dark'	=> esc_html__('Dark', 'writer-ancora')
			);
			$list = apply_filters('writer_ancora_filter_bg_tints', $list);
			if (writer_ancora_get_theme_setting('use_list_cache')) writer_ancora_storage_set('list_bg_tints', $list);
		}
		return $prepend_inherit ? writer_ancora_array_merge(array('inherit' => esc_html__("Inherit", 'writer-ancora')), $list) : $list;
	}
}

// Return custom fields types list, prepended inherit
if ( !function_exists( 'writer_ancora_get_list_field_types' ) ) {
	function writer_ancora_get_list_field_types($prepend_inherit=false) {
		if (($list = writer_ancora_storage_get('list_field_types'))=='') {
			$list = array(
				'text'     => esc_html__('Text',  'writer-ancora'),
				'textarea' => esc_html__('Text Area','writer-ancora'),
				'password' => esc_html__('Password',  'writer-ancora'),
				'radio'    => esc_html__('Radio',  'writer-ancora'),
				'checkbox' => esc_html__('Checkbox',  'writer-ancora'),
				'select'   => esc_html__('Select',  'writer-ancora'),
				'date'     => esc_html__('Date','writer-ancora'),
				'time'     => esc_html__('Time','writer-ancora'),
				'button'   => esc_html__('Button','writer-ancora')
			);
			$list = apply_filters('writer_ancora_filter_field_types', $list);
			if (writer_ancora_get_theme_setting('use_list_cache')) writer_ancora_storage_set('list_field_types', $list);
		}
		return $prepend_inherit ? writer_ancora_array_merge(array('inherit' => esc_html__("Inherit", 'writer-ancora')), $list) : $list;
	}
}

// Return Google map styles
if ( !function_exists( 'writer_ancora_get_list_googlemap_styles' ) ) {
	function writer_ancora_get_list_googlemap_styles($prepend_inherit=false) {
		if (($list = writer_ancora_storage_get('list_googlemap_styles'))=='') {
			$list = array(
				'default' => esc_html__('Default', 'writer-ancora'),
				'simple' => esc_html__('Simple', 'writer-ancora'),
				'greyscale' => esc_html__('Greyscale', 'writer-ancora'),
				'greyscale2' => esc_html__('Greyscale 2', 'writer-ancora'),
				'invert' => esc_html__('Invert', 'writer-ancora'),
				'dark' => esc_html__('Dark', 'writer-ancora'),
				'style1' => esc_html__('Custom style 1', 'writer-ancora'),
				'style2' => esc_html__('Custom style 2', 'writer-ancora'),
				'style3' => esc_html__('Custom style 3', 'writer-ancora')
			);
			$list = apply_filters('writer_ancora_filter_googlemap_styles', $list);
			if (writer_ancora_get_theme_setting('use_list_cache')) writer_ancora_storage_set('list_googlemap_styles', $list);
		}
		return $prepend_inherit ? writer_ancora_array_merge(array('inherit' => esc_html__("Inherit", 'writer-ancora')), $list) : $list;
	}
}

// Return iconed classes list
if ( !function_exists( 'writer_ancora_get_list_icons' ) ) {
	function writer_ancora_get_list_icons($prepend_inherit=false) {
		if (($list = writer_ancora_storage_get('list_icons'))=='') {
			$list = writer_ancora_parse_icons_classes(writer_ancora_get_file_dir("css/fontello/css/fontello-codes.css"));
			if (writer_ancora_get_theme_setting('use_list_cache')) writer_ancora_storage_set('list_icons', $list);
		}
		return $prepend_inherit ? writer_ancora_array_merge(array('inherit' => esc_html__("Inherit", 'writer-ancora')), $list) : $list;
	}
}

// Return socials list
if ( !function_exists( 'writer_ancora_get_list_socials' ) ) {
	function writer_ancora_get_list_socials($prepend_inherit=false) {
		if (($list = writer_ancora_storage_get('list_socials'))=='') {
			$list = writer_ancora_get_list_files("images/socials", "png");
			if (writer_ancora_get_theme_setting('use_list_cache')) writer_ancora_storage_set('list_socials', $list);
		}
		return $prepend_inherit ? writer_ancora_array_merge(array('inherit' => esc_html__("Inherit", 'writer-ancora')), $list) : $list;
	}
}

// Return flags list
if ( !function_exists( 'writer_ancora_get_list_flags' ) ) {
	function writer_ancora_get_list_flags($prepend_inherit=false) {
		if (($list = writer_ancora_storage_get('list_flags'))=='') {
			$list = writer_ancora_get_list_files("images/flags", "png");
			if (writer_ancora_get_theme_setting('use_list_cache')) writer_ancora_storage_set('list_flags', $list);
		}
		return $prepend_inherit ? writer_ancora_array_merge(array('inherit' => esc_html__("Inherit", 'writer-ancora')), $list) : $list;
	}
}

// Return list with 'Yes' and 'No' items
if ( !function_exists( 'writer_ancora_get_list_yesno' ) ) {
	function writer_ancora_get_list_yesno($prepend_inherit=false) {
		$list = array(
			'yes' => esc_html__("Yes", 'writer-ancora'),
			'no'  => esc_html__("No", 'writer-ancora')
		);
		return $prepend_inherit ? writer_ancora_array_merge(array('inherit' => esc_html__("Inherit", 'writer-ancora')), $list) : $list;
	}
}

// Return list with 'On' and 'Of' items
if ( !function_exists( 'writer_ancora_get_list_onoff' ) ) {
	function writer_ancora_get_list_onoff($prepend_inherit=false) {
		$list = array(
			"on" => esc_html__("On", 'writer-ancora'),
			"off" => esc_html__("Off", 'writer-ancora')
		);
		return $prepend_inherit ? writer_ancora_array_merge(array('inherit' => esc_html__("Inherit", 'writer-ancora')), $list) : $list;
	}
}

// Return list with 'Show' and 'Hide' items
if ( !function_exists( 'writer_ancora_get_list_showhide' ) ) {
	function writer_ancora_get_list_showhide($prepend_inherit=false) {
		$list = array(
			"show" => esc_html__("Show", 'writer-ancora'),
			"hide" => esc_html__("Hide", 'writer-ancora')
		);
		return $prepend_inherit ? writer_ancora_array_merge(array('inherit' => esc_html__("Inherit", 'writer-ancora')), $list) : $list;
	}
}

// Return list with 'Ascending' and 'Descending' items
if ( !function_exists( 'writer_ancora_get_list_orderings' ) ) {
	function writer_ancora_get_list_orderings($prepend_inherit=false) {
		$list = array(
			"asc" => esc_html__("Ascending", 'writer-ancora'),
			"desc" => esc_html__("Descending", 'writer-ancora')
		);
		return $prepend_inherit ? writer_ancora_array_merge(array('inherit' => esc_html__("Inherit", 'writer-ancora')), $list) : $list;
	}
}

// Return list with 'Horizontal' and 'Vertical' items
if ( !function_exists( 'writer_ancora_get_list_directions' ) ) {
	function writer_ancora_get_list_directions($prepend_inherit=false) {
		$list = array(
			"horizontal" => esc_html__("Horizontal", 'writer-ancora'),
			"vertical" => esc_html__("Vertical", 'writer-ancora')
		);
		return $prepend_inherit ? writer_ancora_array_merge(array('inherit' => esc_html__("Inherit", 'writer-ancora')), $list) : $list;
	}
}

// Return list with item's shapes
if ( !function_exists( 'writer_ancora_get_list_shapes' ) ) {
	function writer_ancora_get_list_shapes($prepend_inherit=false) {
		$list = array(
			"round"  => esc_html__("Round", 'writer-ancora'),
			"square" => esc_html__("Square", 'writer-ancora')
		);
		return $prepend_inherit ? writer_ancora_array_merge(array('inherit' => esc_html__("Inherit", 'writer-ancora')), $list) : $list;
	}
}

// Return list with item's sizes
if ( !function_exists( 'writer_ancora_get_list_sizes' ) ) {
	function writer_ancora_get_list_sizes($prepend_inherit=false) {
		$list = array(
			"tiny"   => esc_html__("Tiny", 'writer-ancora'),
			"small"  => esc_html__("Small", 'writer-ancora'),
			"medium" => esc_html__("Medium", 'writer-ancora'),
			"large"  => esc_html__("Large", 'writer-ancora')
		);
		return $prepend_inherit ? writer_ancora_array_merge(array('inherit' => esc_html__("Inherit", 'writer-ancora')), $list) : $list;
	}
}

// Return list with float items
if ( !function_exists( 'writer_ancora_get_list_floats' ) ) {
	function writer_ancora_get_list_floats($prepend_inherit=false) {
		$list = array(
			"none" => esc_html__("None", 'writer-ancora'),
			"left" => esc_html__("Float Left", 'writer-ancora'),
			"right" => esc_html__("Float Right", 'writer-ancora')
		);
		return $prepend_inherit ? writer_ancora_array_merge(array('inherit' => esc_html__("Inherit", 'writer-ancora')), $list) : $list;
	}
}

// Return list with alignment items
if ( !function_exists( 'writer_ancora_get_list_alignments' ) ) {
	function writer_ancora_get_list_alignments($justify=false, $prepend_inherit=false) {
		$list = array(
			"none" => esc_html__("None", 'writer-ancora'),
			"left" => esc_html__("Left", 'writer-ancora'),
			"center" => esc_html__("Center", 'writer-ancora'),
			"right" => esc_html__("Right", 'writer-ancora')
		);
		if ($justify) $list["justify"] = esc_html__("Justify", 'writer-ancora');
		return $prepend_inherit ? writer_ancora_array_merge(array('inherit' => esc_html__("Inherit", 'writer-ancora')), $list) : $list;
	}
}

// Return list with horizontal positions
if ( !function_exists( 'writer_ancora_get_list_hpos' ) ) {
	function writer_ancora_get_list_hpos($prepend_inherit=false, $center=false) {
		$list = array();
		$list['left'] = esc_html__("Left", 'writer-ancora');
		if ($center) $list['center'] = esc_html__("Center", 'writer-ancora');
		$list['right'] = esc_html__("Right", 'writer-ancora');
		return $prepend_inherit ? writer_ancora_array_merge(array('inherit' => esc_html__("Inherit", 'writer-ancora')), $list) : $list;
	}
}

// Return list with vertical positions
if ( !function_exists( 'writer_ancora_get_list_vpos' ) ) {
	function writer_ancora_get_list_vpos($prepend_inherit=false, $center=false) {
		$list = array();
		$list['top'] = esc_html__("Top", 'writer-ancora');
		if ($center) $list['center'] = esc_html__("Center", 'writer-ancora');
		$list['bottom'] = esc_html__("Bottom", 'writer-ancora');
		return $prepend_inherit ? writer_ancora_array_merge(array('inherit' => esc_html__("Inherit", 'writer-ancora')), $list) : $list;
	}
}

// Return sorting list items
if ( !function_exists( 'writer_ancora_get_list_sortings' ) ) {
	function writer_ancora_get_list_sortings($prepend_inherit=false) {
		if (($list = writer_ancora_storage_get('list_sortings'))=='') {
			$list = array(
				"date" => esc_html__("Date", 'writer-ancora'),
				"title" => esc_html__("Alphabetically", 'writer-ancora'),
				"views" => esc_html__("Popular (views count)", 'writer-ancora'),
				"comments" => esc_html__("Most commented (comments count)", 'writer-ancora'),
				"author_rating" => esc_html__("Author rating", 'writer-ancora'),
				"users_rating" => esc_html__("Visitors (users) rating", 'writer-ancora'),
				"random" => esc_html__("Random", 'writer-ancora')
			);
			$list = apply_filters('writer_ancora_filter_list_sortings', $list);
			if (writer_ancora_get_theme_setting('use_list_cache')) writer_ancora_storage_set('list_sortings', $list);
		}
		return $prepend_inherit ? writer_ancora_array_merge(array('inherit' => esc_html__("Inherit", 'writer-ancora')), $list) : $list;
	}
}

// Return list with columns widths
if ( !function_exists( 'writer_ancora_get_list_columns' ) ) {
	function writer_ancora_get_list_columns($prepend_inherit=false) {
		if (($list = writer_ancora_storage_get('list_columns'))=='') {
			$list = array(
				"none" => esc_html__("None", 'writer-ancora'),
				"1_1" => esc_html__("100%", 'writer-ancora'),
				"1_2" => esc_html__("1/2", 'writer-ancora'),
				"1_3" => esc_html__("1/3", 'writer-ancora'),
				"2_3" => esc_html__("2/3", 'writer-ancora'),
				"1_4" => esc_html__("1/4", 'writer-ancora'),
				"3_4" => esc_html__("3/4", 'writer-ancora'),
				"1_5" => esc_html__("1/5", 'writer-ancora'),
				"2_5" => esc_html__("2/5", 'writer-ancora'),
				"3_5" => esc_html__("3/5", 'writer-ancora'),
				"4_5" => esc_html__("4/5", 'writer-ancora'),
				"1_6" => esc_html__("1/6", 'writer-ancora'),
				"5_6" => esc_html__("5/6", 'writer-ancora'),
				"1_7" => esc_html__("1/7", 'writer-ancora'),
				"2_7" => esc_html__("2/7", 'writer-ancora'),
				"3_7" => esc_html__("3/7", 'writer-ancora'),
				"4_7" => esc_html__("4/7", 'writer-ancora'),
				"5_7" => esc_html__("5/7", 'writer-ancora'),
				"6_7" => esc_html__("6/7", 'writer-ancora'),
				"1_8" => esc_html__("1/8", 'writer-ancora'),
				"3_8" => esc_html__("3/8", 'writer-ancora'),
				"5_8" => esc_html__("5/8", 'writer-ancora'),
				"7_8" => esc_html__("7/8", 'writer-ancora'),
				"1_9" => esc_html__("1/9", 'writer-ancora'),
				"2_9" => esc_html__("2/9", 'writer-ancora'),
				"4_9" => esc_html__("4/9", 'writer-ancora'),
				"5_9" => esc_html__("5/9", 'writer-ancora'),
				"7_9" => esc_html__("7/9", 'writer-ancora'),
				"8_9" => esc_html__("8/9", 'writer-ancora'),
				"1_10"=> esc_html__("1/10", 'writer-ancora'),
				"3_10"=> esc_html__("3/10", 'writer-ancora'),
				"7_10"=> esc_html__("7/10", 'writer-ancora'),
				"9_10"=> esc_html__("9/10", 'writer-ancora'),
				"1_11"=> esc_html__("1/11", 'writer-ancora'),
				"2_11"=> esc_html__("2/11", 'writer-ancora'),
				"3_11"=> esc_html__("3/11", 'writer-ancora'),
				"4_11"=> esc_html__("4/11", 'writer-ancora'),
				"5_11"=> esc_html__("5/11", 'writer-ancora'),
				"6_11"=> esc_html__("6/11", 'writer-ancora'),
				"7_11"=> esc_html__("7/11", 'writer-ancora'),
				"8_11"=> esc_html__("8/11", 'writer-ancora'),
				"9_11"=> esc_html__("9/11", 'writer-ancora'),
				"10_11"=> esc_html__("10/11", 'writer-ancora'),
				"1_12"=> esc_html__("1/12", 'writer-ancora'),
				"5_12"=> esc_html__("5/12", 'writer-ancora'),
				"7_12"=> esc_html__("7/12", 'writer-ancora'),
				"10_12"=> esc_html__("10/12", 'writer-ancora'),
				"11_12"=> esc_html__("11/12", 'writer-ancora')
			);
			$list = apply_filters('writer_ancora_filter_list_columns', $list);
			if (writer_ancora_get_theme_setting('use_list_cache')) writer_ancora_storage_set('list_columns', $list);
		}
		return $prepend_inherit ? writer_ancora_array_merge(array('inherit' => esc_html__("Inherit", 'writer-ancora')), $list) : $list;
	}
}

// Return list of locations for the dedicated content
if ( !function_exists( 'writer_ancora_get_list_dedicated_locations' ) ) {
	function writer_ancora_get_list_dedicated_locations($prepend_inherit=false) {
		if (($list = writer_ancora_storage_get('list_dedicated_locations'))=='') {
			$list = array(
				"default" => esc_html__('As in the post defined', 'writer-ancora'),
				"center"  => esc_html__('Above the text of the post', 'writer-ancora'),
				"left"    => esc_html__('To the left the text of the post', 'writer-ancora'),
				"right"   => esc_html__('To the right the text of the post', 'writer-ancora'),
				"alter"   => esc_html__('Alternates for each post', 'writer-ancora')
			);
			$list = apply_filters('writer_ancora_filter_list_dedicated_locations', $list);
			if (writer_ancora_get_theme_setting('use_list_cache')) writer_ancora_storage_set('list_dedicated_locations', $list);
		}
		return $prepend_inherit ? writer_ancora_array_merge(array('inherit' => esc_html__("Inherit", 'writer-ancora')), $list) : $list;
	}
}

// Return post-format name
if ( !function_exists( 'writer_ancora_get_post_format_name' ) ) {
	function writer_ancora_get_post_format_name($format, $single=true) {
		$name = '';
		if ($format=='gallery')		$name = $single ? esc_html__('gallery', 'writer-ancora') : esc_html__('galleries', 'writer-ancora');
		else if ($format=='video')	$name = $single ? esc_html__('video', 'writer-ancora') : esc_html__('videos', 'writer-ancora');
		else if ($format=='audio')	$name = $single ? esc_html__('audio', 'writer-ancora') : esc_html__('audios', 'writer-ancora');
		else if ($format=='image')	$name = $single ? esc_html__('image', 'writer-ancora') : esc_html__('images', 'writer-ancora');
		else if ($format=='quote')	$name = $single ? esc_html__('quote', 'writer-ancora') : esc_html__('quotes', 'writer-ancora');
		else if ($format=='link')	$name = $single ? esc_html__('link', 'writer-ancora') : esc_html__('links', 'writer-ancora');
		else if ($format=='status')	$name = $single ? esc_html__('status', 'writer-ancora') : esc_html__('statuses', 'writer-ancora');
		else if ($format=='aside')	$name = $single ? esc_html__('aside', 'writer-ancora') : esc_html__('asides', 'writer-ancora');
		else if ($format=='chat')	$name = $single ? esc_html__('chat', 'writer-ancora') : esc_html__('chats', 'writer-ancora');
		else						$name = $single ? esc_html__('standard', 'writer-ancora') : esc_html__('standards', 'writer-ancora');
		return apply_filters('writer_ancora_filter_list_post_format_name', $name, $format);
	}
}

// Return post-format icon name (from Fontello library)
if ( !function_exists( 'writer_ancora_get_post_format_icon' ) ) {
	function writer_ancora_get_post_format_icon($format) {
		$icon = 'icon-';
		if ($format=='gallery')		$icon .= 'pictures';
		else if ($format=='video')	$icon .= 'video';
		else if ($format=='audio')	$icon .= 'note';
		else if ($format=='image')	$icon .= 'picture';
		else if ($format=='quote')	$icon .= 'quote';
		else if ($format=='link')	$icon .= 'link';
		else if ($format=='status')	$icon .= 'comment';
		else if ($format=='aside')	$icon .= 'doc-text';
		else if ($format=='chat')	$icon .= 'chat';
		else						$icon .= 'book-open';
		return apply_filters('writer_ancora_filter_list_post_format_icon', $icon, $format);
	}
}

// Return fonts styles list, prepended inherit
if ( !function_exists( 'writer_ancora_get_list_fonts_styles' ) ) {
	function writer_ancora_get_list_fonts_styles($prepend_inherit=false) {
		if (($list = writer_ancora_storage_get('list_fonts_styles'))=='') {
			$list = array(
				'i' => esc_html__('I','writer-ancora'),
				'u' => esc_html__('U', 'writer-ancora')
			);
			if (writer_ancora_get_theme_setting('use_list_cache')) writer_ancora_storage_set('list_fonts_styles', $list);
		}
		return $prepend_inherit ? writer_ancora_array_merge(array('inherit' => esc_html__("Inherit", 'writer-ancora')), $list) : $list;
	}
}

// Return Google fonts list
if ( !function_exists( 'writer_ancora_get_list_fonts' ) ) {
	function writer_ancora_get_list_fonts($prepend_inherit=false) {
		if (($list = writer_ancora_storage_get('list_fonts'))=='') {
			$list = array();
			$list = writer_ancora_array_merge($list, writer_ancora_get_list_font_faces());
			// Google and custom fonts list:
			//$list['Advent Pro'] = array(
			//		'family'=>'sans-serif',																						// (required) font family
			//		'link'=>'Advent+Pro:100,100italic,300,300italic,400,400italic,500,500italic,700,700italic,900,900italic',	// (optional) if you use Google font repository
			//		'css'=>writer_ancora_get_file_url('/css/font-face/Advent-Pro/stylesheet.css')									// (optional) if you use custom font-face
			//		);
			$list = writer_ancora_array_merge($list, array(
				'Advent Pro' => array('family'=>'sans-serif'),
				'Alegreya Sans' => array('family'=>'sans-serif'),
				'Arimo' => array('family'=>'sans-serif'),
				'Asap' => array('family'=>'sans-serif'),
				'Averia Sans Libre' => array('family'=>'cursive'),
				'Averia Serif Libre' => array('family'=>'cursive'),
				'Bree Serif' => array('family'=>'serif',),
				'Cabin' => array('family'=>'sans-serif'),
				'Cabin Condensed' => array('family'=>'sans-serif'),
				'Caudex' => array('family'=>'serif'),
				'Comfortaa' => array('family'=>'cursive'),
				'Cousine' => array('family'=>'sans-serif'),
				'Crimson Text' => array('family'=>'serif'),
				'Cuprum' => array('family'=>'sans-serif'),
				'Dosis' => array('family'=>'sans-serif'),
				'Economica' => array('family'=>'sans-serif'),
				'Exo' => array('family'=>'sans-serif'),
				'Expletus Sans' => array('family'=>'cursive'),
				'Karla' => array('family'=>'sans-serif'),
				'Lato' => array('family'=>'sans-serif'),
				'Lekton' => array('family'=>'sans-serif'),
				'Lobster Two' => array('family'=>'cursive'),
				'Maven Pro' => array('family'=>'sans-serif'),
				'Merriweather' => array('family'=>'serif'),
				'Montserrat' => array('family'=>'sans-serif'),
				'Neuton' => array('family'=>'serif'),
				'Noticia Text' => array('family'=>'serif'),
				'Old Standard TT' => array('family'=>'serif'),
				'Open Sans' => array('family'=>'sans-serif'),
				'Orbitron' => array('family'=>'sans-serif'),
				'Oswald' => array('family'=>'sans-serif'),
				'Overlock' => array('family'=>'cursive'),
				'Oxygen' => array('family'=>'sans-serif'),
				'Philosopher' => array('family'=>'serif'),
				'PT Serif' => array('family'=>'serif'),
				'Puritan' => array('family'=>'sans-serif'),
				'Raleway' => array('family'=>'sans-serif'),
				'Roboto' => array('family'=>'sans-serif'),
				'Roboto Slab' => array('family'=>'sans-serif'),
				'Roboto Condensed' => array('family'=>'sans-serif'),
				'Rosario' => array('family'=>'sans-serif'),
				'Share' => array('family'=>'cursive'),
				'Signika' => array('family'=>'sans-serif'),
				'Signika Negative' => array('family'=>'sans-serif'),
				'Source Sans Pro' => array('family'=>'sans-serif'),
				'Tinos' => array('family'=>'serif'),
				'Ubuntu' => array('family'=>'sans-serif'),
				'Vollkorn' => array('family'=>'serif')
				)
			);
			$list = apply_filters('writer_ancora_filter_list_fonts', $list);
			if (writer_ancora_get_theme_setting('use_list_cache')) writer_ancora_storage_set('list_fonts', $list);
		}
		return $prepend_inherit ? writer_ancora_array_merge(array('inherit' => esc_html__("Inherit", 'writer-ancora')), $list) : $list;
	}
}

// Return Custom font-face list
if ( !function_exists( 'writer_ancora_get_list_font_faces' ) ) {
	function writer_ancora_get_list_font_faces($prepend_inherit=false) {
		static $list = false;
		if (is_array($list)) return $list;
		$list = array();
		$dir = writer_ancora_get_folder_dir("css/font-face");
		if ( is_dir($dir) ) {
			$hdir = @ opendir( $dir );
			if ( $hdir ) {
				while (($file = readdir( $hdir ) ) !== false ) {
					$pi = pathinfo( ($dir) . '/' . ($file) );
					if ( substr($file, 0, 1) == '.' || ! is_dir( ($dir) . '/' . ($file) ) )
						continue;
					$css = file_exists( ($dir) . '/' . ($file) . '/' . ($file) . '.css' ) 
						? writer_ancora_get_folder_url("css/font-face/".($file).'/'.($file).'.css')
						: (file_exists( ($dir) . '/' . ($file) . '/stylesheet.css' ) 
							? writer_ancora_get_folder_url("css/font-face/".($file).'/stylesheet.css')
							: '');
					if ($css != '')
						$list[$file.' ('.esc_html__('uploaded font', 'writer-ancora').')'] = array('css' => $css);
				}
				@closedir( $hdir );
			}
		}
		return $list;
	}
}
?>