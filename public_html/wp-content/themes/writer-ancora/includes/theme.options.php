<?php

/* Theme setup section
-------------------------------------------------------------------- */

// ONLY FOR PROGRAMMERS, NOT FOR CUSTOMER
// Framework settings

writer_ancora_storage_set('settings', array(
	
	'less_compiler'		=> 'lessc',								// no|lessc|less - Compiler for the .less
																// lessc - fast & low memory required, but .less-map, shadows & gradients not supprted
																// less  - slow, but support all features
	'less_nested'		=> false,								// Use nested selectors when compiling less - increase .css size, but allow using nested color schemes
	'less_prefix'		=> '',									// any string - Use prefix before each selector when compile less. For example: 'html '
	'less_separator'	=> '/*---LESS_SEPARATOR---*/',			// string - separator inside .less file to split it when compiling to reduce memory usage
																// (compilation speed gets a bit slow)
	'less_map'			=> 'internal',							// no|internal|external - Generate map for .less files. 
																// Warning! You need more then 128Mb for PHP scripts on your server! Supported only if less_compiler=less (see above)
	
	'customizer_demo'	=> true,								// Show color customizer demo (if many color settings) or not (if only accent colors used)

	'allow_fullscreen'	=> false,								// Allow fullscreen and fullwide body styles

	'socials_type'		=> 'icons',								// images|icons - Use this kind of pictograms for all socials: share, social profiles, team members socials, etc.
	'slides_type'		=> 'bg',								// images|bg - Use image as slide's content or as slide's background

	'add_image_size'	=> false,								// Add theme's thumb sizes into WP list sizes. 
																// If false - new image thumb will be generated on demand,
																// otherwise - all thumb sizes will be generated when image is loaded

	'use_list_cache'	=> true,								// Use cache for any lists (increase theme speed, but get 15-20K memory)
	'use_post_cache'	=> true,								// Use cache for post_data (increase theme speed, decrease queries number, but get more memory - up to 300K)
	'use_menu_cache'	=> true,								// Use cache for menu (increase theme speed, decrease queries number, but not display current menu item and ancestor!)

	'allow_profiler'	=> true,								// Allow to show theme profiler when 'debug mode' is on

	'admin_dummy_style' => 2									// 1 | 2 - Progress bar style when import dummy data
	)
);



// Default Theme Options
if ( !function_exists( 'writer_ancora_options_settings_theme_setup' ) ) {
	add_action( 'writer_ancora_action_before_init_theme', 'writer_ancora_options_settings_theme_setup', 2 );	// Priority 1 for add writer_ancora_filter handlers
	function writer_ancora_options_settings_theme_setup() {
		
		// Clear all saved Theme Options on first theme run
		add_action('after_switch_theme', 'writer_ancora_options_reset');

		// Settings 
		$socials_type = writer_ancora_get_theme_setting('socials_type');
				
		// Prepare arrays 
		writer_ancora_storage_set('options_params', apply_filters('writer_ancora_filter_theme_options_params', array(
			'list_fonts'				=> array('$writer_ancora_get_list_fonts' => ''),
			'list_fonts_styles'			=> array('$writer_ancora_get_list_fonts_styles' => ''),
			'list_socials' 				=> array('$writer_ancora_get_list_socials' => ''),
			'list_icons' 				=> array('$writer_ancora_get_list_icons' => ''),
			'list_posts_types' 			=> array('$writer_ancora_get_list_posts_types' => ''),
			'list_categories' 			=> array('$writer_ancora_get_list_categories' => ''),
			'list_menus'				=> array('$writer_ancora_get_list_menus' => ''),
			'list_sidebars'				=> array('$writer_ancora_get_list_sidebars' => ''),
			'list_positions' 			=> array('$writer_ancora_get_list_sidebars_positions' => ''),
			'list_skins'				=> array('$writer_ancora_get_list_skins' => ''),
			'list_color_schemes'		=> array('$writer_ancora_get_list_color_schemes' => ''),
			'list_bg_tints'				=> array('$writer_ancora_get_list_bg_tints' => ''),
			'list_body_styles'			=> array('$writer_ancora_get_list_body_styles' => ''),
			'list_header_styles'		=> array('$writer_ancora_get_list_templates_header' => ''),
			'list_blog_styles'			=> array('$writer_ancora_get_list_templates_blog' => ''),
			'list_single_styles'		=> array('$writer_ancora_get_list_templates_single' => ''),
			'list_article_styles'		=> array('$writer_ancora_get_list_article_styles' => ''),
			'list_blog_counters' 		=> array('$writer_ancora_get_list_blog_counters' => ''),
			'list_animations_in' 		=> array('$writer_ancora_get_list_animations_in' => ''),
			'list_animations_out'		=> array('$writer_ancora_get_list_animations_out' => ''),
			'list_filters'				=> array('$writer_ancora_get_list_portfolio_filters' => ''),
			'list_hovers'				=> array('$writer_ancora_get_list_hovers' => ''),
			'list_hovers_dir'			=> array('$writer_ancora_get_list_hovers_directions' => ''),
			'list_alter_sizes'			=> array('$writer_ancora_get_list_alter_sizes' => ''),
			'list_sliders' 				=> array('$writer_ancora_get_list_sliders' => ''),
			'list_bg_image_positions'	=> array('$writer_ancora_get_list_bg_image_positions' => ''),
			'list_popups' 				=> array('$writer_ancora_get_list_popup_engines' => ''),
			'list_gmap_styles'		 	=> array('$writer_ancora_get_list_googlemap_styles' => ''),
			'list_yes_no' 				=> array('$writer_ancora_get_list_yesno' => ''),
			'list_on_off' 				=> array('$writer_ancora_get_list_onoff' => ''),
			'list_show_hide' 			=> array('$writer_ancora_get_list_showhide' => ''),
			'list_sorting' 				=> array('$writer_ancora_get_list_sortings' => ''),
			'list_ordering' 			=> array('$writer_ancora_get_list_orderings' => ''),
			'list_locations' 			=> array('$writer_ancora_get_list_dedicated_locations' => '')
			)
		));


		// Theme options array
		writer_ancora_storage_set('options', array(

		
		//###############################
		//#### Customization         #### 
		//###############################
		'partition_customization' => array(
					"title" => esc_html__('Customization', 'writer-ancora'),
					"start" => "partitions",
					"override" => "category,services_group,page,post",
					"icon" => "iconadmin-cog-alt",
					"type" => "partition"
					),
		
		
		// Customization -> Body Style
		//-------------------------------------------------
		
		'customization_body' => array(
					"title" => esc_html__('Body style', 'writer-ancora'),
					"override" => "category,services_group,post,page",
					"icon" => 'iconadmin-picture',
					"start" => "customization_tabs",
					"type" => "tab"
					),
		
		'info_body_1' => array(
					"title" => esc_html__('Body parameters', 'writer-ancora'),
					"desc" => wp_kses( __('Select body style, skin and color scheme for entire site. You can override this parameters on any page, post or category', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,post,page",
					"type" => "info"
					),

		'body_style' => array(
					"title" => esc_html__('Body style', 'writer-ancora'),
					"desc" => wp_kses( __('Select body style:', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') )
								. ' <br>' 
								. wp_kses( __('<b>boxed</b> - if you want use background color and/or image', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') )
								. ',<br>'
								. wp_kses( __('<b>wide</b> - page fill whole window with centered content', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') )
								. (writer_ancora_get_theme_setting('allow_fullscreen') 
									? ',<br>' . wp_kses( __('<b>fullwide</b> - page content stretched on the full width of the window (with few left and right paddings)', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') )
									: '')
								. (writer_ancora_get_theme_setting('allow_fullscreen') 
									? ',<br>' . wp_kses( __('<b>fullscreen</b> - page content fill whole window without any paddings', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') )
									: ''),
					"override" => "category,services_group,post,page",
					"std" => "wide",
					"options" => writer_ancora_get_options_param('list_body_styles'),
					"dir" => "horizontal",
					"type" => "radio"
					),
		
		'body_paddings' => array(
					"title" => esc_html__('Page paddings', 'writer-ancora'),
					"desc" => wp_kses( __('Add paddings above and below the page content', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "post,page",
					"std" => "yes",
					"options" => writer_ancora_get_options_param('list_yes_no'),
					"type" => "switch"
					),

		'theme_skin' => array(
					"title" => esc_html__('Select theme skin', 'writer-ancora'),
					"desc" => wp_kses( __('Select skin for the theme decoration', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,post,page",
					"std" => "less",
					"options" => writer_ancora_get_options_param('list_skins'),
					"type" => "select"
					),

		"body_scheme" => array(
					"title" => esc_html__('Color scheme', 'writer-ancora'),
					"desc" => wp_kses( __('Select predefined color scheme for the entire page', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,post,page",
					"std" => "original",
					"dir" => "horizontal",
					"options" => writer_ancora_get_options_param('list_color_schemes'),
					"type" => "checklist"),
		
		'body_filled' => array(
					"title" => esc_html__('Fill body', 'writer-ancora'),
					"desc" => wp_kses( __('Fill the page background with the solid color or leave it transparend to show background image (or video background)', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,post,page",
					"std" => "yes",
					"options" => writer_ancora_get_options_param('list_yes_no'),
					"type" => "switch"
					),

		'info_body_2' => array(
					"title" => esc_html__('Background color and image', 'writer-ancora'),
					"desc" => wp_kses( __('Color and image for the site background', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,post,page",
					"type" => "info"
					),


		'one_bg_color' => array(
					"title" => esc_html__('Fill background one default color',  'writer-ancora'),
					"desc" => wp_kses( __("Fill background one default color - 'Background color'", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,post,page",
					"std" => "yes",
					"options" => writer_ancora_get_options_param('list_yes_no'),
					"type" => "switch"
					),


		'bg_custom' => array(
					"title" => esc_html__('Use custom background',  'writer-ancora'),
					"desc" => wp_kses( __("Use custom color and/or image as the site background", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,post,page",
					"std" => "no",
					"options" => writer_ancora_get_options_param('list_yes_no'),
					"type" => "switch"
					),
		
		'bg_color' => array(
					"title" => esc_html__('Background color',  'writer-ancora'),
					"desc" => wp_kses( __('Body background color',  'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'bg_custom' => array('yes')
					),
					"std" => "#ffffff",
					"type" => "color"
					),

		'bg_pattern' => array(
					"title" => esc_html__('Background predefined pattern',  'writer-ancora'),
					"desc" => wp_kses( __('Select theme background pattern (first case - without pattern)',  'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'bg_custom' => array('yes')
					),
					"std" => "",
					"options" => array(
						0 => writer_ancora_get_file_url('images/spacer.png'),
						1 => writer_ancora_get_file_url('images/bg/pattern_1.jpg'),
						2 => writer_ancora_get_file_url('images/bg/pattern_2.jpg'),
						3 => writer_ancora_get_file_url('images/bg/pattern_3.jpg'),
						4 => writer_ancora_get_file_url('images/bg/pattern_4.jpg'),
						5 => writer_ancora_get_file_url('images/bg/pattern_5.jpg')
					),
					"style" => "list",
					"type" => "images"
					),
		
		'bg_pattern_custom' => array(
					"title" => esc_html__('Background custom pattern',  'writer-ancora'),
					"desc" => wp_kses( __('Select or upload background custom pattern. If selected - use it instead the theme predefined pattern (selected in the field above)',  'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'bg_custom' => array('yes')
					),
					"std" => "",
					"type" => "media"
					),
		
		'bg_image' => array(
					"title" => esc_html__('Background predefined image',  'writer-ancora'),
					"desc" => wp_kses( __('Select theme background image (first case - without image)',  'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,post,page",
					"std" => "",
					"dependency" => array(
						'bg_custom' => array('yes')
					),
					"options" => array(
						0 => writer_ancora_get_file_url('images/spacer.png'),
						1 => writer_ancora_get_file_url('images/bg/image_1_thumb.jpg'),
						2 => writer_ancora_get_file_url('images/bg/image_2_thumb.jpg'),
						3 => writer_ancora_get_file_url('images/bg/image_3_thumb.jpg')
					),
					"style" => "list",
					"type" => "images"
					),
		
		'bg_image_custom' => array(
					"title" => esc_html__('Background custom image',  'writer-ancora'),
					"desc" => wp_kses( __('Select or upload background custom image. If selected - use it instead the theme predefined image (selected in the field above)',  'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'bg_custom' => array('yes')
					),
					"std" => "",
					"type" => "media"
					),
		
		'bg_image_custom_position' => array( 
					"title" => esc_html__('Background custom image position',  'writer-ancora'),
					"desc" => wp_kses( __('Select custom image position',  'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,post,page",
					"std" => "left_top",
					"dependency" => array(
						'bg_custom' => array('yes')
					),
					"options" => array(
						'left_top' => "Left Top",
						'center_top' => "Center Top",
						'right_top' => "Right Top",
						'left_center' => "Left Center",
						'center_center' => "Center Center",
						'right_center' => "Right Center",
						'left_bottom' => "Left Bottom",
						'center_bottom' => "Center Bottom",
						'right_bottom' => "Right Bottom",
					),
					"type" => "select"
					),
		
		'bg_image_load' => array(
					"title" => esc_html__('Load background image', 'writer-ancora'),
					"desc" => wp_kses( __('Always load background images or only for boxed body style', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,post,page",
					"std" => "boxed",
					"size" => "medium",
					"dependency" => array(
						'bg_custom' => array('yes')
					),
					"options" => array(
						'boxed' => esc_html__('Boxed', 'writer-ancora'),
						'always' => esc_html__('Always', 'writer-ancora')
					),
					"type" => "switch"
					),

		
		'info_body_3' => array(
					"title" => esc_html__('Video background', 'writer-ancora'),
					"desc" => wp_kses( __('Parameters of the video, used as site background', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,post,page",
					"type" => "info"
					),

		'show_video_bg' => array(
					"title" => esc_html__('Show video background',  'writer-ancora'),
					"desc" => wp_kses( __("Show video as the site background", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,post,page",
					"std" => "no",
					"options" => writer_ancora_get_options_param('list_yes_no'),
					"type" => "switch"
					),

		'video_bg_youtube_code' => array(
					"title" => esc_html__('Youtube code for video bg',  'writer-ancora'),
					"desc" => wp_kses( __("Youtube code of video", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_video_bg' => array('yes')
					),
					"std" => "",
					"type" => "text"
					),

		'video_bg_url' => array(
					"title" => esc_html__('Local video for video bg',  'writer-ancora'),
					"desc" => wp_kses( __("URL to video-file (uploaded on your site)", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"readonly" =>false,
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_video_bg' => array('yes')
					),
					"before" => array(	'title' => esc_html__('Choose video', 'writer-ancora'),
										'action' => 'media_upload',
										'multiple' => false,
										'linked_field' => '',
										'type' => 'video',
										'captions' => array('choose' => esc_html__( 'Choose Video', 'writer-ancora'),
															'update' => esc_html__( 'Select Video', 'writer-ancora')
														)
								),
					"std" => "",
					"type" => "media"
					),

		'video_bg_overlay' => array(
					"title" => esc_html__('Use overlay for video bg', 'writer-ancora'),
					"desc" => wp_kses( __('Use overlay texture for the video background', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_video_bg' => array('yes')
					),
					"std" => "no",
					"options" => writer_ancora_get_options_param('list_yes_no'),
					"type" => "switch"
					),
		
		
		
		
		
		// Customization -> Header
		//-------------------------------------------------
		
		'customization_header' => array(
					"title" => esc_html__("Header", 'writer-ancora'),
					"override" => "category,services_group,post,page",
					"icon" => 'iconadmin-window',
					"type" => "tab"),
		
		"info_header_1" => array(
					"title" => esc_html__('Top panel', 'writer-ancora'),
					"desc" => wp_kses( __('Top panel settings. It include user menu area (with contact info, cart button, language selector, login/logout menu and user menu) and main menu area (with logo and main menu).', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,post,page",
					"type" => "info"),
		
		"top_panel_style" => array(
					"title" => esc_html__('Top panel style', 'writer-ancora'),
					"desc" => wp_kses( __('Select desired style of the page header', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,post,page",
					"std" => "header_2",
					"options" => writer_ancora_get_options_param('list_header_styles'),
					"style" => "list",
					"type" => "images"),

		"top_panel_image" => array(
					"title" => esc_html__('Top panel image', 'writer-ancora'),
					"desc" => wp_kses( __('Select default background image of the page header (if not single post or featured image for current post is not specified)', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'top_panel_style' => array('header_7')
					),
					"std" => "",
					"type" => "media"),
		
		"top_panel_position" => array( 
					"title" => esc_html__('Top panel position', 'writer-ancora'),
					"desc" => wp_kses( __('Select position for the top panel with logo and main menu', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,post,page",
					"std" => "above",
					"options" => array(
						'hide'  => esc_html__('Hide', 'writer-ancora'),
						'above' => esc_html__('Above slider', 'writer-ancora'),
						'below' => esc_html__('Below slider', 'writer-ancora'),
						'over'  => esc_html__('Over slider', 'writer-ancora')
					),
					"type" => "checklist"),

		"top_panel_scheme" => array(
					"title" => esc_html__('Top panel color scheme', 'writer-ancora'),
					"desc" => wp_kses( __('Select predefined color scheme for the top panel', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,post,page",
					"std" => "original",
					"dir" => "horizontal",
					"options" => writer_ancora_get_options_param('list_color_schemes'),
					"type" => "checklist"),

		"pushy_panel_scheme" => array(
					"title" => esc_html__('Push panel color scheme', 'writer-ancora'),
					"desc" => wp_kses( __('Select predefined color scheme for the push panel (with logo, menu and socials)', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'top_panel_style' => array('header_8')
					),
					"std" => "dark",
					"dir" => "horizontal",
					"options" => writer_ancora_get_options_param('list_color_schemes'),
					"type" => "checklist"),
		
		"show_page_title" => array(
					"title" => esc_html__('Show Page title', 'writer-ancora'),
					"desc" => wp_kses( __('Show post/page/category title', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,post,page",
					"std" => "yes",
					"options" => writer_ancora_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"show_breadcrumbs" => array(
					"title" => esc_html__('Show Breadcrumbs', 'writer-ancora'),
					"desc" => wp_kses( __('Show path to current category (post, page)', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,post,page",
					"std" => "yes",
					"options" => writer_ancora_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"breadcrumbs_max_level" => array(
					"title" => esc_html__('Breadcrumbs max nesting', 'writer-ancora'),
					"desc" => wp_kses( __("Max number of the nested categories in the breadcrumbs (0 - unlimited)", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"dependency" => array(
						'show_breadcrumbs' => array('yes')
					),
					"std" => "0",
					"min" => 0,
					"max" => 100,
					"step" => 1,
					"type" => "spinner"),

		"show_cart_button" => array(
					"title" => esc_html__('Show Cart button', 'writer-ancora'),
					"desc" => wp_kses( __('Show Cart button on header', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,post,page",
					"std" => "no",
					"options" => writer_ancora_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		
		"info_header_2" => array( 
					"title" => esc_html__('Main menu style and position', 'writer-ancora'),
					"desc" => wp_kses( __('Select the Main menu style and position', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,post,page",
					"type" => "info"),
		
		"menu_main" => array( 
					"title" => esc_html__('Select main menu',  'writer-ancora'),
					"desc" => wp_kses( __('Select main menu for the current page',  'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,post,page",
					"std" => "default",
					"options" => writer_ancora_get_options_param('list_menus'),
					"type" => "select"),
		
		"menu_attachment" => array( 
					"title" => esc_html__('Main menu attachment', 'writer-ancora'),
					"desc" => wp_kses( __('Attach main menu to top of window then page scroll down', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"std" => "fixed",
					"options" => array(
						"fixed"=>esc_html__("Fix menu position", 'writer-ancora'), 
						"none"=>esc_html__("Don't fix menu position", 'writer-ancora')
					),
					"dir" => "vertical",
					"type" => "radio"),

		"menu_slider" => array( 
					"title" => esc_html__('Main menu slider', 'writer-ancora'),
					"desc" => wp_kses( __('Use slider background for main menu items', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"std" => "yes",
					"type" => "switch",
					"options" => writer_ancora_get_options_param('list_yes_no')),

		"menu_animation_in" => array( 
					"title" => esc_html__('Submenu show animation', 'writer-ancora'),
					"desc" => wp_kses( __('Select animation to show submenu ', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"std" => "fadeIn",
					"type" => "select",
					"options" => writer_ancora_get_options_param('list_animations_in')),

		"menu_animation_out" => array( 
					"title" => esc_html__('Submenu hide animation', 'writer-ancora'),
					"desc" => wp_kses( __('Select animation to hide submenu ', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"std" => "fadeOutDown",
					"type" => "select",
					"options" => writer_ancora_get_options_param('list_animations_out')),
		
		"menu_relayout" => array( 
					"title" => esc_html__('Main menu relayout', 'writer-ancora'),
					"desc" => wp_kses( __('Allow relayout main menu if window width less then this value', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"std" => 960,
					"min" => 320,
					"max" => 1024,
					"type" => "spinner"),
		
		"menu_responsive" => array( 
					"title" => esc_html__('Main menu responsive', 'writer-ancora'),
					"desc" => wp_kses( __('Allow responsive version for the main menu if window width less then this value', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"std" => 640,
					"min" => 320,
					"max" => 1024,
					"type" => "spinner"),
		
		"menu_width" => array( 
					"title" => esc_html__('Submenu width', 'writer-ancora'),
					"desc" => wp_kses( __('Width for dropdown menus in main menu', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"step" => 5,
					"std" => "",
					"min" => 180,
					"max" => 300,
					"mask" => "?999",
					"type" => "spinner"),
		
		
		
		"info_header_3" => array(
					"title" => esc_html__("User's menu area components", 'writer-ancora'),
					"desc" => wp_kses( __("Select parts for the user's menu area", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,page,post",
					"type" => "info"),
		
		"show_top_panel_top" => array(
					"title" => esc_html__('Show user menu area', 'writer-ancora'),
					"desc" => wp_kses( __('Show user menu area on top of page', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,post,page",
					"std" => "no",
					"options" => writer_ancora_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"menu_user" => array(
					"title" => esc_html__('Select user menu',  'writer-ancora'),
					"desc" => wp_kses( __('Select user menu for the current page',  'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_top_panel_top' => array('yes')
					),
					"std" => "default",
					"options" => writer_ancora_get_options_param('list_menus'),
					"type" => "select"),
		
		"show_languages" => array(
					"title" => esc_html__('Show language selector', 'writer-ancora'),
					"desc" => wp_kses( __('Show language selector in the user menu (if WPML plugin installed and current page/post has multilanguage version)', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"dependency" => array(
						'show_top_panel_top' => array('yes')
					),
					"std" => "yes",
					"options" => writer_ancora_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"show_login" => array( 
					"title" => esc_html__('Show Login/Logout buttons', 'writer-ancora'),
					"desc" => wp_kses( __('Show Login and Logout buttons in the user menu area', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"dependency" => array(
						'show_top_panel_top' => array('yes')
					),
					"std" => "yes",
					"options" => writer_ancora_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"show_bookmarks" => array(
					"title" => esc_html__('Show bookmarks', 'writer-ancora'),
					"desc" => wp_kses( __('Show bookmarks selector in the user menu', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"dependency" => array(
						'show_top_panel_top' => array('yes')
					),
					"std" => "yes",
					"options" => writer_ancora_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"show_socials" => array( 
					"title" => esc_html__('Show Social icons', 'writer-ancora'),
					"desc" => wp_kses( __('Show Social icons in the user menu area', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"dependency" => array(
						'show_top_panel_top' => array('yes')
					),
					"std" => "yes",
					"options" => writer_ancora_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		

		
		"info_header_4" => array( 
					"title" => esc_html__("Table of Contents (TOC)", 'writer-ancora'),
					"desc" => wp_kses( __("Table of Contents for the current page. Automatically created if the page contains objects with id starting with 'toc_'", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,page,post",
					"type" => "info"),
		
		"menu_toc" => array( 
					"title" => esc_html__('TOC position', 'writer-ancora'),
					"desc" => wp_kses( __('Show TOC for the current page', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,post,page",
					"std" => "float",
					"options" => array(
						'hide'  => esc_html__('Hide', 'writer-ancora'),
						'fixed' => esc_html__('Fixed', 'writer-ancora'),
						'float' => esc_html__('Float', 'writer-ancora')
					),
					"type" => "checklist"),
		
		"menu_toc_home" => array(
					"title" => esc_html__('Add "Home" into TOC', 'writer-ancora'),
					"desc" => wp_kses( __('Automatically add "Home" item into table of contents - return to home page of the site', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'menu_toc' => array('fixed','float')
					),
					"std" => "yes",
					"options" => writer_ancora_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"menu_toc_top" => array( 
					"title" => esc_html__('Add "To Top" into TOC', 'writer-ancora'),
					"desc" => wp_kses( __('Automatically add "To Top" item into table of contents - scroll to top of the page', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'menu_toc' => array('fixed','float')
					),
					"std" => "yes",
					"options" => writer_ancora_get_options_param('list_yes_no'),
					"type" => "switch"),

		
		
		
		'info_header_5' => array(
					"title" => esc_html__('Main logo', 'writer-ancora'),
					"desc" => wp_kses( __("Select or upload logos for the site's header and select it position", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,post,page",
					"type" => "info"
					),

		'logo' => array(
					"title" => esc_html__('Logo image', 'writer-ancora'),
					"desc" => wp_kses( __('Main logo image', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,post,page",
					"std" => writer_ancora_get_file_url('images/logo1.png'),
					"type" => "media"
					),

		'logo_retina' => array(
					"title" => esc_html__('Logo image for Retina', 'writer-ancora'),
					"desc" => wp_kses( __('Main logo image used on Retina display', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,post,page",
					"std" => "",
					"type" => "media"
					),

		'logo_fixed' => array(
					"title" => esc_html__('Logo image (fixed header)', 'writer-ancora'),
					"desc" => wp_kses( __('Logo image for the header (if menu is fixed after the page is scrolled)', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,post,page",
					"divider" => false,
					"std" => "",
					"type" => "media"
					),

		'logo_text' => array(
					"title" => esc_html__('Logo text', 'writer-ancora'),
					"desc" => wp_kses( __('Logo text - display it after logo image', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,post,page",
					"std" => '',
					"type" => "text"
					),

		'logo_height' => array(
					"title" => esc_html__('Logo height', 'writer-ancora'),
					"desc" => wp_kses( __('Height for the logo in the header area', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,post,page",
					"step" => 1,
					"std" => '',
					"min" => 10,
					"max" => 300,
					"mask" => "?999",
					"type" => "spinner"
					),

		'logo_offset' => array(
					"title" => esc_html__('Logo top offset', 'writer-ancora'),
					"desc" => wp_kses( __('Top offset for the logo in the header area', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,post,page",
					"step" => 1,
					"std" => '',
					"min" => 0,
					"max" => 99,
					"mask" => "?99",
					"type" => "spinner"
					),
		
		
		
		
		
		
		
		// Customization -> Slider
		//-------------------------------------------------
		
		"customization_slider" => array( 
					"title" => esc_html__('Slider', 'writer-ancora'),
					"icon" => "iconadmin-picture",
					"override" => "category,services_group,page",
					"type" => "tab"),
		
		"info_slider_1" => array(
					"title" => esc_html__('Main slider parameters', 'writer-ancora'),
					"desc" => wp_kses( __('Select parameters for main slider (you can override it in each category and page)', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,page",
					"type" => "info"),
					
		"show_slider" => array(
					"title" => esc_html__('Show Slider', 'writer-ancora'),
					"desc" => wp_kses( __('Do you want to show slider on each page (post)', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,page",
					"std" => "no",
					"options" => writer_ancora_get_options_param('list_yes_no'),
					"type" => "switch"),
					
		"slider_display" => array(
					"title" => esc_html__('Slider display', 'writer-ancora'),
					"desc" => wp_kses( __('How display slider: boxed (fixed width and height), fullwide (fixed height) or fullscreen', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_slider' => array('yes')
					),
					"std" => "fullwide",
					"options" => array(
						"boxed"=>esc_html__("Boxed", 'writer-ancora'),
						"fullwide"=>esc_html__("Fullwide", 'writer-ancora'),
						"fullscreen"=>esc_html__("Fullscreen", 'writer-ancora')
					),
					"type" => "checklist"),
		
		"slider_height" => array(
					"title" => esc_html__("Height (in pixels)", 'writer-ancora'),
					"desc" => wp_kses( __("Slider height (in pixels) - only if slider display with fixed height.", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_slider' => array('yes')
					),
					"std" => '',
					"min" => 100,
					"step" => 10,
					"type" => "spinner"),
		
		"slider_engine" => array(
					"title" => esc_html__('Slider engine', 'writer-ancora'),
					"desc" => wp_kses( __('What engine use to show slider?', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_slider' => array('yes')
					),
					"std" => "swiper",
					"options" => writer_ancora_get_options_param('list_sliders'),
					"type" => "radio"),
		
		"slider_category" => array(
					"title" => esc_html__('Posts Slider: Category to show', 'writer-ancora'),
					"desc" => wp_kses( __('Select category to show in Flexslider (ignored for Revolution and Royal sliders)', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => "",
					"options" => writer_ancora_array_merge(array(0 => esc_html__('- Select category -', 'writer-ancora')), writer_ancora_get_options_param('list_categories')),
					"type" => "select",
					"multiple" => true,
					"style" => "list"),
		
		"slider_posts" => array(
					"title" => esc_html__('Posts Slider: Number posts or comma separated posts list',  'writer-ancora'),
					"desc" => wp_kses( __("How many recent posts display in slider or comma separated list of posts ID (in this case selected category ignored)", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => "5",
					"type" => "text"),
		
		"slider_orderby" => array(
					"title" => esc_html__("Posts Slider: Posts order by",  'writer-ancora'),
					"desc" => wp_kses( __("Posts in slider ordered by date (default), comments, views, author rating, users rating, random or alphabetically", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => "date",
					"options" => writer_ancora_get_options_param('list_sorting'),
					"type" => "select"),
		
		"slider_order" => array(
					"title" => esc_html__("Posts Slider: Posts order", 'writer-ancora'),
					"desc" => wp_kses( __('Select the desired ordering method for posts', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => "desc",
					"options" => writer_ancora_get_options_param('list_ordering'),
					"size" => "big",
					"type" => "switch"),
					
		"slider_interval" => array(
					"title" => esc_html__("Posts Slider: Slide change interval", 'writer-ancora'),
					"desc" => wp_kses( __("Interval (in ms) for slides change in slider", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => 7000,
					"min" => 100,
					"step" => 100,
					"type" => "spinner"),
		
		"slider_pagination" => array(
					"title" => esc_html__("Posts Slider: Pagination", 'writer-ancora'),
					"desc" => wp_kses( __("Choose pagination style for the slider", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => "no",
					"options" => array(
						'no'   => esc_html__('None', 'writer-ancora'),
						'yes'  => esc_html__('Dots', 'writer-ancora'), 
						'over' => esc_html__('Titles', 'writer-ancora')
					),
					"type" => "checklist"),
		
		"slider_infobox" => array(
					"title" => esc_html__("Posts Slider: Show infobox", 'writer-ancora'),
					"desc" => wp_kses( __("Do you want to show post's title, reviews rating and description on slides in slider", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => "slide",
					"options" => array(
						'no'    => esc_html__('None',  'writer-ancora'),
						'slide' => esc_html__('Slide', 'writer-ancora'), 
						'fixed' => esc_html__('Fixed', 'writer-ancora')
					),
					"type" => "checklist"),
					
		"slider_info_category" => array(
					"title" => esc_html__("Posts Slider: Show post's category", 'writer-ancora'),
					"desc" => wp_kses( __("Do you want to show post's category on slides in slider", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => "yes",
					"options" => writer_ancora_get_options_param('list_yes_no'),
					"type" => "switch"),
					
		"slider_info_reviews" => array(
					"title" => esc_html__("Posts Slider: Show post's reviews rating", 'writer-ancora'),
					"desc" => wp_kses( __("Do you want to show post's reviews rating on slides in slider", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => "yes",
					"options" => writer_ancora_get_options_param('list_yes_no'),
					"type" => "switch"),
					
		"slider_info_descriptions" => array(
					"title" => esc_html__("Posts Slider: Show post's descriptions", 'writer-ancora'),
					"desc" => wp_kses( __("How many characters show in the post's description in slider. 0 - no descriptions", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => 0,
					"min" => 0,
					"step" => 10,
					"type" => "spinner"),
		
		
		
		
		
		// Customization -> Sidebars
		//-------------------------------------------------
		
		"customization_sidebars" => array( 
					"title" => esc_html__('Sidebars', 'writer-ancora'),
					"icon" => "iconadmin-indent-right",
					"override" => "category,services_group,post,page",
					"type" => "tab"),
		
		"info_sidebars_1" => array( 
					"title" => esc_html__('Custom sidebars', 'writer-ancora'),
					"desc" => wp_kses( __('In this section you can create unlimited sidebars. You can fill them with widgets in the menu Appearance - Widgets', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"type" => "info"),
		
		"custom_sidebars" => array(
					"title" => esc_html__('Custom sidebars',  'writer-ancora'),
					"desc" => wp_kses( __('Manage custom sidebars. You can use it with each category (page, post) independently',  'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"std" => "",
					"cloneable" => true,
					"type" => "text"),
		
		"info_sidebars_2" => array(
					"title" => esc_html__('Main sidebar', 'writer-ancora'),
					"desc" => wp_kses( __('Show / Hide and select main sidebar', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,post,page",
					"type" => "info"),
		
		'show_sidebar_main' => array( 
					"title" => esc_html__('Show main sidebar',  'writer-ancora'),
					"desc" => wp_kses( __('Select position for the main sidebar or hide it',  'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,post,page",
					"std" => "right",
					"options" => writer_ancora_get_options_param('list_positions'),
					"dir" => "horizontal",
					"type" => "checklist"),

		"sidebar_main_scheme" => array(
					"title" => esc_html__("Color scheme", 'writer-ancora'),
					"desc" => wp_kses( __('Select predefined color scheme for the main sidebar', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_sidebar_main' => array('left', 'right')
					),
					"std" => "original",
					"dir" => "horizontal",
					"options" => writer_ancora_get_options_param('list_color_schemes'),
					"type" => "checklist"),
		
		"sidebar_main" => array( 
					"title" => esc_html__('Select main sidebar',  'writer-ancora'),
					"desc" => wp_kses( __('Select main sidebar content',  'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_sidebar_main' => array('left', 'right')
					),
					"std" => "sidebar_main",
					"options" => writer_ancora_get_options_param('list_sidebars'),
					"type" => "select"),
		
		"info_sidebars_3" => array(
					"title" => esc_html__('Outer sidebar', 'writer-ancora'),
					"desc" => wp_kses( __('Show / Hide and select outer sidebar (sidemenu, logo, etc.', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,post,page",
					"type" => "hidden"), //info
		
		'show_sidebar_outer' => array( 
					"title" => esc_html__('Show outer sidebar',  'writer-ancora'),
					"desc" => wp_kses( __('Select position for the outer sidebar or hide it',  'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,post,page",
					"std" => "hide",
					"options" => writer_ancora_get_options_param('list_positions'),
					"dir" => "horizontal",
					"type" => "hidden"), //checklist

		"sidebar_outer_scheme" => array(
					"title" => esc_html__("Color scheme", 'writer-ancora'),
					"desc" => wp_kses( __('Select predefined color scheme for the outer sidebar', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_sidebar_outer' => array('left', 'right')
					),
					"std" => "original",
					"dir" => "horizontal",
					"options" => writer_ancora_get_options_param('list_color_schemes'),
					"type" => "hidden"),  //checklist
		
		"sidebar_outer_show_logo" => array( 
					"title" => esc_html__('Show Logo', 'writer-ancora'),
					"desc" => wp_kses( __('Show Logo in the outer sidebar', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_sidebar_outer' => array('left', 'right')
					),
					"std" => "yes",
					"options" => writer_ancora_get_options_param('list_yes_no'),
					"type" => "hidden"),  //switch
		
		"sidebar_outer_show_socials" => array( 
					"title" => esc_html__('Show Social icons', 'writer-ancora'),
					"desc" => wp_kses( __('Show Social icons in the outer sidebar', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_sidebar_outer' => array('left', 'right')
					),
					"std" => "yes",
					"options" => writer_ancora_get_options_param('list_yes_no'),
					"type" => "hidden"),  //switch
		
		"sidebar_outer_show_menu" => array( 
					"title" => esc_html__('Show Menu', 'writer-ancora'),
					"desc" => wp_kses( __('Show Menu in the outer sidebar', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_sidebar_outer' => array('left', 'right')
					),
					"std" => "yes",
					"options" => writer_ancora_get_options_param('list_yes_no'),
					"type" => "hidden"), //switch
		
		"menu_side" => array(
					"title" => esc_html__('Select menu',  'writer-ancora'),
					"desc" => wp_kses( __('Select menu for the outer sidebar',  'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_sidebar_outer' => array('left', 'right'),
						'sidebar_outer_show_menu' => array('yes')
					),
					"std" => "default",
					"options" => writer_ancora_get_options_param('list_menus'),
					"type" => "select"),
		
		"sidebar_outer_show_widgets" => array( 
					"title" => esc_html__('Show Widgets', 'writer-ancora'),
					"desc" => wp_kses( __('Show Widgets in the outer sidebar', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_sidebar_outer' => array('left', 'right')
					),
					"std" => "yes",
					"options" => writer_ancora_get_options_param('list_yes_no'),
					"type" => "hidden"), //switch

		"sidebar_outer" => array( 
					"title" => esc_html__('Select outer sidebar',  'writer-ancora'),
					"desc" => wp_kses( __('Select outer sidebar content',  'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'sidebar_outer_show_widgets' => array('yes'),
						'show_sidebar_outer' => array('left', 'right')
					),
					"std" => "sidebar_outer",
					"options" => writer_ancora_get_options_param('list_sidebars'),
					"type" => "hidden"),  //select
		
		
		
		
		// Customization -> Footer
		//-------------------------------------------------
		
		'customization_footer' => array(
					"title" => esc_html__("Footer", 'writer-ancora'),
					"override" => "category,services_group,post,page",
					"icon" => 'iconadmin-window',
					"type" => "tab"),
		
		
		"info_footer_1" => array(
					"title" => esc_html__("Footer components", 'writer-ancora'),
					"desc" => wp_kses( __("Select components of the footer, set style and put the content for the user's footer area", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,page,post",
					"type" => "info"),
		
		"show_sidebar_footer" => array(
					"title" => esc_html__('Show footer sidebar', 'writer-ancora'),
					"desc" => wp_kses( __('Select style for the footer sidebar or hide it', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,post,page",
					"std" => "yes",
					"options" => writer_ancora_get_options_param('list_yes_no'),
					"type" => "switch"),

		"sidebar_footer_scheme" => array(
					"title" => esc_html__("Color scheme", 'writer-ancora'),
					"desc" => wp_kses( __('Select predefined color scheme for the footer', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_sidebar_footer' => array('yes')
					),
					"std" => "original",
					"dir" => "horizontal",
					"options" => writer_ancora_get_options_param('list_color_schemes'),
					"type" => "checklist"),
		
		"sidebar_footer" => array( 
					"title" => esc_html__('Select footer sidebar',  'writer-ancora'),
					"desc" => wp_kses( __('Select footer sidebar for the blog page',  'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_sidebar_footer' => array('yes')
					),
					"std" => "sidebar_footer",
					"options" => writer_ancora_get_options_param('list_sidebars'),
					"type" => "select"),
		
		"sidebar_footer_columns" => array( 
					"title" => esc_html__('Footer sidebar columns',  'writer-ancora'),
					"desc" => wp_kses( __('Select columns number for the footer sidebar',  'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_sidebar_footer' => array('yes')
					),
					"std" => 3,
					"min" => 1,
					"max" => 6,
					"type" => "spinner"),
		
		
		"info_footer_2" => array(
					"title" => esc_html__('Testimonials in Footer', 'writer-ancora'),
					"desc" => wp_kses( __('Select parameters for Testimonials in the Footer', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,page,post",
					"type" => "info"),

		"show_testimonials_in_footer" => array(
					"title" => esc_html__('Show Testimonials in footer', 'writer-ancora'),
					"desc" => wp_kses( __('Show Testimonials slider in footer. For correct operation of the slider (and shortcode testimonials) you must fill out Testimonials posts on the menu "Testimonials"', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,post,page",
					"std" => "no",
					"options" => writer_ancora_get_options_param('list_yes_no'),
					"type" => "switch"),

		"testimonials_scheme" => array(
					"title" => esc_html__("Color scheme", 'writer-ancora'),
					"desc" => wp_kses( __('Select predefined color scheme for the testimonials area', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_testimonials_in_footer' => array('yes')
					),
					"std" => "original",
					"dir" => "horizontal",
					"options" => writer_ancora_get_options_param('list_color_schemes'),
					"type" => "checklist"),

		"testimonials_count" => array( 
					"title" => esc_html__('Testimonials count', 'writer-ancora'),
					"desc" => wp_kses( __('Number testimonials to show', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_testimonials_in_footer' => array('yes')
					),
					"std" => 3,
					"step" => 1,
					"min" => 1,
					"max" => 10,
					"type" => "spinner"),
		
		
		"info_footer_3" => array(
					"title" => esc_html__('Twitter in Footer', 'writer-ancora'),
					"desc" => wp_kses( __('Select parameters for Twitter stream in the Footer (you can override it in each category and page)', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,page,post",
					"type" => "info"),

		"show_twitter_in_footer" => array(
					"title" => esc_html__('Show Twitter in footer', 'writer-ancora'),
					"desc" => wp_kses( __('Show Twitter slider in footer. For correct operation of the slider (and shortcode twitter) you must fill out the Twitter API keys on the menu "Appearance - Theme Options - Socials"', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,post,page",
					"std" => "no",
					"options" => writer_ancora_get_options_param('list_yes_no'),
					"type" => "switch"),

		"twitter_scheme" => array(
					"title" => esc_html__("Color scheme", 'writer-ancora'),
					"desc" => wp_kses( __('Select predefined color scheme for the twitter area', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_twitter_in_footer' => array('yes')
					),
					"std" => "original",
					"dir" => "horizontal",
					"options" => writer_ancora_get_options_param('list_color_schemes'),
					"type" => "checklist"),

		"twitter_count" => array( 
					"title" => esc_html__('Twitter count', 'writer-ancora'),
					"desc" => wp_kses( __('Number twitter to show', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_twitter_in_footer' => array('yes')
					),
					"std" => 3,
					"step" => 1,
					"min" => 1,
					"max" => 10,
					"type" => "spinner"),


		"info_footer_4" => array(
					"title" => esc_html__('Google map parameters', 'writer-ancora'),
					"desc" => wp_kses( __('Select parameters for Google map (you can override it in each category and page)', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,page,post",
					"type" => "info"),
					
		"show_googlemap" => array(
					"title" => esc_html__('Show Google Map', 'writer-ancora'),
					"desc" => wp_kses( __('Do you want to show Google map on each page (post)', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,page,post",
					"std" => "no",
					"options" => writer_ancora_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"googlemap_height" => array(
					"title" => esc_html__("Map height", 'writer-ancora'),
					"desc" => wp_kses( __("Map height (default - in pixels, allows any CSS units of measure)", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_googlemap' => array('yes')
					),
					"std" => 400,
					"min" => 100,
					"step" => 10,
					"type" => "spinner"),
		
		"googlemap_address" => array(
					"title" => esc_html__('Address to show on map',  'writer-ancora'),
					"desc" => wp_kses( __("Enter address to show on map center", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,page,post",
					"dependency" => array(
						'show_googlemap' => array('yes')
					),
					"std" => "",
					"type" => "text"),
		
		"googlemap_latlng" => array(
					"title" => esc_html__('Latitude and Longitude to show on map',  'writer-ancora'),
					"desc" => wp_kses( __("Enter coordinates (separated by comma) to show on map center (instead of address)", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,page,post",
					"dependency" => array(
						'show_googlemap' => array('yes')
					),
					"std" => "",
					"type" => "text"),
		
		"googlemap_title" => array(
					"title" => esc_html__('Title to show on map',  'writer-ancora'),
					"desc" => wp_kses( __("Enter title to show on map center", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,page,post",
					"dependency" => array(
						'show_googlemap' => array('yes')
					),
					"std" => "",
					"type" => "text"),
		
		"googlemap_description" => array(
					"title" => esc_html__('Description to show on map',  'writer-ancora'),
					"desc" => wp_kses( __("Enter description to show on map center", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,page,post",
					"dependency" => array(
						'show_googlemap' => array('yes')
					),
					"std" => "",
					"type" => "text"),
		
		"googlemap_zoom" => array(
					"title" => esc_html__('Google map initial zoom',  'writer-ancora'),
					"desc" => wp_kses( __("Enter desired initial zoom for Google map", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,page,post",
					"dependency" => array(
						'show_googlemap' => array('yes')
					),
					"std" => 16,
					"min" => 1,
					"max" => 20,
					"step" => 1,
					"type" => "spinner"),
		
		"googlemap_style" => array(
					"title" => esc_html__('Google map style',  'writer-ancora'),
					"desc" => wp_kses( __("Select style to show Google map", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,page,post",
					"dependency" => array(
						'show_googlemap' => array('yes')
					),
					"std" => 'style1',
					"options" => writer_ancora_get_options_param('list_gmap_styles'),
					"type" => "select"),
		
		"googlemap_marker" => array(
					"title" => esc_html__('Google map marker',  'writer-ancora'),
					"desc" => wp_kses( __("Select or upload png-image with Google map marker", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"dependency" => array(
						'show_googlemap' => array('yes')
					),
					"std" => '',
					"type" => "media"),
		
		
		
		"info_footer_5" => array(
					"title" => esc_html__("Contacts area", 'writer-ancora'),
					"desc" => wp_kses( __("Show/Hide contacts area in the footer", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,page,post",
					"type" => "info"),
		
		"show_contacts_in_footer" => array(
					"title" => esc_html__('Show Contacts in footer', 'writer-ancora'),
					"desc" => wp_kses( __('Show contact information area in footer: site logo, contact info and large social icons', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,post,page",
					"std" => "yes",
					"options" => writer_ancora_get_options_param('list_yes_no'),
					"type" => "switch"),

		"contacts_scheme" => array(
					"title" => esc_html__("Color scheme", 'writer-ancora'),
					"desc" => wp_kses( __('Select predefined color scheme for the contacts area', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_contacts_in_footer' => array('yes')
					),
					"std" => "original",
					"dir" => "horizontal",
					"options" => writer_ancora_get_options_param('list_color_schemes'),
					"type" => "checklist"),

		'logo_footer' => array(
					"title" => esc_html__('Logo image for footer', 'writer-ancora'),
					"desc" => wp_kses( __('Logo image in the footer (in the contacts area)', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_contacts_in_footer' => array('yes')
					),
					"std" => "",
					"type" => "media"
					),

		'logo_footer_retina' => array(
					"title" => esc_html__('Logo image for footer for Retina', 'writer-ancora'),
					"desc" => wp_kses( __('Logo image in the footer (in the contacts area) used on Retina display', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_contacts_in_footer' => array('yes')
					),
					"std" => "",
					"type" => "media"
					),
		
		'logo_footer_height' => array(
					"title" => esc_html__('Logo height', 'writer-ancora'),
					"desc" => wp_kses( __('Height for the logo in the footer area (in the contacts area)', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_contacts_in_footer' => array('yes')
					),
					"step" => 1,
					"std" => 30,
					"min" => 10,
					"max" => 300,
					"mask" => "?999",
					"type" => "spinner"
					),
		
		
		
		"info_footer_6" => array(
					"title" => esc_html__("Copyright and footer menu", 'writer-ancora'),
					"desc" => wp_kses( __("Show/Hide copyright area in the footer", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,page,post",
					"type" => "info"),

		"show_copyright_in_footer" => array(
					"title" => esc_html__('Show Copyright area in footer', 'writer-ancora'),
					"desc" => wp_kses( __('Show area with copyright information, footer menu and small social icons in footer', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,post,page",
					"std" => "plain",
					"options" => array(
						'none' => esc_html__('Hide', 'writer-ancora'),
						'text' => esc_html__('Text', 'writer-ancora'),
						'menu' => esc_html__('Text and menu', 'writer-ancora'),
						'socials' => esc_html__('Text and Social icons', 'writer-ancora')
					),
					"type" => "checklist"),

		"copyright_scheme" => array(
					"title" => esc_html__("Color scheme", 'writer-ancora'),
					"desc" => wp_kses( __('Select predefined color scheme for the copyright area', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_copyright_in_footer' => array('text', 'menu', 'socials')
					),
					"std" => "original",
					"dir" => "horizontal",
					"options" => writer_ancora_get_options_param('list_color_schemes'),
					"type" => "checklist"),
		
		"menu_footer" => array( 
					"title" => esc_html__('Select footer menu',  'writer-ancora'),
					"desc" => wp_kses( __('Select footer menu for the current page',  'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,post,page",
					"std" => "default",
					"dependency" => array(
						'show_copyright_in_footer' => array('menu')
					),
					"options" => writer_ancora_get_options_param('list_menus'),
					"type" => "select"),

		"footer_copyright" => array(
					"title" => esc_html__('Footer copyright text',  'writer-ancora'),
					"desc" => wp_kses( __("Copyright text to show in footer area (bottom of site)", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,page,post",
					"dependency" => array(
						'show_copyright_in_footer' => array('text', 'menu', 'socials')
					),
					"allow_html" => true,
					"std" => "Writer Ancora &copy; 2014 All Rights Reserved ",
					"rows" => "10",
					"type" => "editor"),




		// Customization -> Other
		//-------------------------------------------------
		
		'customization_other' => array(
					"title" => esc_html__('Other', 'writer-ancora'),
					"override" => "category,services_group,page,post",
					"icon" => 'iconadmin-cog',
					"type" => "tab"
					),

		'info_other_1' => array(
					"title" => esc_html__('Theme customization other parameters', 'writer-ancora'),
					"desc" => wp_kses( __('Animation parameters and responsive layouts for the small screens', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"type" => "info"
					),

		'show_theme_customizer' => array(
					"title" => esc_html__('Show Theme customizer', 'writer-ancora'),
					"desc" => wp_kses( __('Do you want to show theme customizer in the right panel? Your website visitors will be able to customise it yourself.', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"std" => "no",
					"options" => writer_ancora_get_options_param('list_yes_no'),
					"type" => "switch"
					),

		"customizer_demo" => array(
					"title" => esc_html__('Theme customizer panel demo time', 'writer-ancora'),
					"desc" => wp_kses( __('Timer for demo mode for the customizer panel (in milliseconds: 1000ms = 1s). If 0 - no demo.', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"dependency" => array(
						'show_theme_customizer' => array('yes')
					),
					"std" => "0",
					"min" => 0,
					"max" => 10000,
					"step" => 500,
					"type" => "spinner"),
		
		'css_animation' => array(
					"title" => esc_html__('Extended CSS animations', 'writer-ancora'),
					"desc" => wp_kses( __('Do you want use extended animations effects on your site?', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"std" => "yes",
					"options" => writer_ancora_get_options_param('list_yes_no'),
					"type" => "switch"
					),

		'remember_visitors_settings' => array(
					"title" => esc_html__("Remember visitor's settings", 'writer-ancora'),
					"desc" => wp_kses( __('To remember the settings that were made by the visitor, when navigating to other pages or to limit their effect only within the current page', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"std" => "no",
					"options" => writer_ancora_get_options_param('list_yes_no'),
					"type" => "switch"
					),
					
		'responsive_layouts' => array(
					"title" => esc_html__('Responsive Layouts', 'writer-ancora'),
					"desc" => wp_kses( __('Do you want use responsive layouts on small screen or still use main layout?', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"std" => "yes",
					"options" => writer_ancora_get_options_param('list_yes_no'),
					"type" => "switch"
					),
		
		'page_preloader' => array(
					"title" => esc_html__('Show page preloader',  'writer-ancora'),
					"desc" => wp_kses( __('Do you want show animated page preloader?',  'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"std" => "",
					"type" => "media"
					),


		'info_other_2' => array(
					"title" => esc_html__('Google fonts parameters', 'writer-ancora'),
					"desc" => wp_kses( __('Specify additional parameters, used to load Google fonts', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"type" => "info"
					),
		
		"fonts_subset" => array(
					"title" => esc_html__('Characters subset', 'writer-ancora'),
					"desc" => wp_kses( __('Select subset, included into used Google fonts', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"std" => "latin,latin-ext",
					"options" => array(
						'latin' => esc_html__('Latin', 'writer-ancora'),
						'latin-ext' => esc_html__('Latin Extended', 'writer-ancora'),
						'greek' => esc_html__('Greek', 'writer-ancora'),
						'greek-ext' => esc_html__('Greek Extended', 'writer-ancora'),
						'cyrillic' => esc_html__('Cyrillic', 'writer-ancora'),
						'cyrillic-ext' => esc_html__('Cyrillic Extended', 'writer-ancora'),
						'vietnamese' => esc_html__('Vietnamese', 'writer-ancora')
					),
					"size" => "medium",
					"dir" => "vertical",
					"multiple" => true,
					"type" => "checklist"),


		'info_other_3' => array(
					"title" => esc_html__('Additional CSS and HTML/JS code', 'writer-ancora'),
					"desc" => wp_kses( __('Put here your custom CSS and JS code', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,page,post",
					"type" => "info"
					),
					
		'custom_css_html' => array(
					"title" => esc_html__('Use custom CSS/HTML/JS', 'writer-ancora'),
					"desc" => wp_kses( __('Do you want use custom HTML/CSS/JS code in your site? For example: custom styles, Google Analitics code, etc.', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"std" => "no",
					"options" => writer_ancora_get_options_param('list_yes_no'),
					"type" => "switch"
					),
		
		"gtm_code" => array(
					"title" => esc_html__('Google tags manager or Google analitics code',  'writer-ancora'),
					"desc" => wp_kses( __('Put here Google Tags Manager (GTM) code from your account: Google analitics, remarketing, etc. This code will be placed after open body tag.',  'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"dependency" => array(
						'custom_css_html' => array('yes')
					),
					"cols" => 80,
					"rows" => 20,
					"std" => "",
					"allow_html" => true,
					"allow_js" => true,
					"type" => "textarea"),
		
		"gtm_code2" => array(
					"title" => esc_html__('Google remarketing code',  'writer-ancora'),
					"desc" => wp_kses( __('Put here Google Remarketing code from your account. This code will be placed before close body tag.',  'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"dependency" => array(
						'custom_css_html' => array('yes')
					),
					"divider" => false,
					"cols" => 80,
					"rows" => 20,
					"std" => "",
					"allow_html" => true,
					"allow_js" => true,
					"type" => "textarea"),
		
		'custom_code' => array(
					"title" => esc_html__('Your custom HTML/JS code',  'writer-ancora'),
					"desc" => wp_kses( __('Put here your invisible html/js code: Google analitics, counters, etc',  'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'custom_css_html' => array('yes')
					),
					"cols" => 80,
					"rows" => 20,
					"std" => "",
					"allow_html" => true,
					"allow_js" => true,
					"type" => "textarea"
					),
		
		'custom_css' => array(
					"title" => esc_html__('Your custom CSS code',  'writer-ancora'),
					"desc" => wp_kses( __('Put here your css code to correct main theme styles',  'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'custom_css_html' => array('yes')
					),
					"divider" => false,
					"cols" => 80,
					"rows" => 20,
					"std" => "",
					"type" => "textarea"
					),
		
		
		
		
		
		
		
		
		
		//###############################
		//#### Blog and Single pages #### 
		//###############################
		"partition_blog" => array(
					"title" => esc_html__('Blog &amp; Single', 'writer-ancora'),
					"icon" => "iconadmin-docs",
					"override" => "category,services_group,post,page",
					"type" => "partition"),
		
		
		
		// Blog -> Stream page
		//-------------------------------------------------
		
		'blog_tab_stream' => array(
					"title" => esc_html__('Stream page', 'writer-ancora'),
					"start" => 'blog_tabs',
					"icon" => "iconadmin-docs",
					"override" => "category,services_group,post,page",
					"type" => "tab"),
		
		"info_blog_1" => array(
					"title" => esc_html__('Blog streampage parameters', 'writer-ancora'),
					"desc" => wp_kses( __('Select desired blog streampage parameters (you can override it in each category)', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,post,page",
					"type" => "info"),
		
		"blog_style" => array(
					"title" => esc_html__('Blog style', 'writer-ancora'),
					"desc" => wp_kses( __('Select desired blog style', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,page",
					"std" => "excerpt",
					"options" => writer_ancora_get_options_param('list_blog_styles'),
					"type" => "select"),
		
		"hover_style" => array(
					"title" => esc_html__('Hover style', 'writer-ancora'),
					"desc" => wp_kses( __('Select desired hover style (only for Blog style = Portfolio)', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,page",
					"dependency" => array(
						'blog_style' => array('portfolio','grid','square','colored')
					),
					"std" => "square effect_shift",
					"options" => writer_ancora_get_options_param('list_hovers'),
					"type" => "select"),
		
		"hover_dir" => array(
					"title" => esc_html__('Hover dir', 'writer-ancora'),
					"desc" => wp_kses( __('Select hover direction (only for Blog style = Portfolio and Hover style = Circle or Square)', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,page",
					"dependency" => array(
						'blog_style' => array('portfolio','grid','square','colored'),
						'hover_style' => array('square','circle')
					),
					"std" => "left_to_right",
					"options" => writer_ancora_get_options_param('list_hovers_dir'),
					"type" => "select"),
		
		"article_style" => array(
					"title" => esc_html__('Article style', 'writer-ancora'),
					"desc" => wp_kses( __('Select article display method: boxed or stretch', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,page",
					"std" => "stretch",
					"options" => writer_ancora_get_options_param('list_article_styles'),
					"size" => "medium",
					"type" => "switch"),
		
		"dedicated_location" => array(
					"title" => esc_html__('Dedicated location', 'writer-ancora'),
					"desc" => wp_kses( __('Select location for the dedicated content or featured image in the "excerpt" blog style', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,page,post",
					"dependency" => array(
						'blog_style' => array('excerpt')
					),
					"std" => "default",
					"options" => writer_ancora_get_options_param('list_locations'),
					"type" => "select"),
		
		"show_filters" => array(
					"title" => esc_html__('Show filters', 'writer-ancora'),
					"desc" => wp_kses( __('What taxonomy use for filter buttons', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,page",
					"dependency" => array(
						'blog_style' => array('portfolio','grid','square','colored')
					),
					"std" => "hide",
					"options" => writer_ancora_get_options_param('list_filters'),
					"type" => "checklist"),
		
		"blog_sort" => array(
					"title" => esc_html__('Blog posts sorted by', 'writer-ancora'),
					"desc" => wp_kses( __('Select the desired sorting method for posts', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,page",
					"std" => "date",
					"options" => writer_ancora_get_options_param('list_sorting'),
					"dir" => "vertical",
					"type" => "radio"),
		
		"blog_order" => array(
					"title" => esc_html__('Blog posts order', 'writer-ancora'),
					"desc" => wp_kses( __('Select the desired ordering method for posts', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,page",
					"std" => "desc",
					"options" => writer_ancora_get_options_param('list_ordering'),
					"size" => "big",
					"type" => "switch"),
		
		"posts_per_page" => array(
					"title" => esc_html__('Blog posts per page',  'writer-ancora'),
					"desc" => wp_kses( __('How many posts display on blog pages for selected style. If empty or 0 - inherit system WordPress settings',  'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,page",
					"std" => "12",
					"mask" => "?99",
					"type" => "text"),
		
		"post_excerpt_maxlength" => array(
					"title" => esc_html__('Excerpt maxlength for streampage',  'writer-ancora'),
					"desc" => wp_kses( __('How many characters from post excerpt are display in blog streampage (only for Blog style = Excerpt). 0 - do not trim excerpt.',  'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,page",
					"dependency" => array(
						'blog_style' => array('excerpt', 'portfolio', 'grid', 'square', 'related')
					),
					"std" => "250",
					"mask" => "?9999",
					"type" => "text"),
		
		"post_excerpt_maxlength_masonry" => array(
					"title" => esc_html__('Excerpt maxlength for classic and masonry',  'writer-ancora'),
					"desc" => wp_kses( __('How many characters from post excerpt are display in blog streampage (only for Blog style = Classic or Masonry). 0 - do not trim excerpt.',  'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,page",
					"dependency" => array(
						'blog_style' => array('masonry', 'classic')
					),
					"std" => "150",
					"mask" => "?9999",
					"type" => "text"),
		
		
		
		
		// Blog -> Single page
		//-------------------------------------------------
		
		'blog_tab_single' => array(
					"title" => esc_html__('Single page', 'writer-ancora'),
					"icon" => "iconadmin-doc",
					"override" => "category,services_group,post,page",
					"type" => "tab"),
		
		
		"info_single_1" => array(
					"title" => esc_html__('Single (detail) pages parameters', 'writer-ancora'),
					"desc" => wp_kses( __('Select desired parameters for single (detail) pages (you can override it in each category and single post (page))', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,page,post",
					"type" => "info"),
		
		"single_style" => array(
					"title" => esc_html__('Single page style', 'writer-ancora'),
					"desc" => wp_kses( __('Select desired style for single page', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,page,post",
					"std" => "single-standard",
					"options" => writer_ancora_get_options_param('list_single_styles'),
					"dir" => "horizontal",
					"type" => "radio"),

		"icon" => array(
					"title" => esc_html__('Select post icon', 'writer-ancora'),
					"desc" => wp_kses( __('Select icon for output before post/category name in some layouts', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "services_group,page,post",
					"std" => "",
					"options" => writer_ancora_get_options_param('list_icons'),
					"style" => "select",
					"type" => "icons"
					),

		"alter_thumb_size" => array(
					"title" => esc_html__('Alter thumb size (WxH)',  'writer-ancora'),
					"override" => "page,post",
					"desc" => wp_kses( __("Select thumb size for the alternative portfolio layout (number items horizontally x number items vertically)", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"class" => "",
					"std" => "1_1",
					"type" => "radio",
					"options" => writer_ancora_get_options_param('list_alter_sizes')
					),
		
		"show_featured_image" => array(
					"title" => esc_html__('Show featured image before post',  'writer-ancora'),
					"desc" => wp_kses( __("Show featured image (if selected) before post content on single pages", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,page,post",
					"std" => "yes",
					"options" => writer_ancora_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"show_post_title" => array(
					"title" => esc_html__('Show post title', 'writer-ancora'),
					"desc" => wp_kses( __('Show area with post title on single pages', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,post,page",
					"std" => "yes",
					"options" => writer_ancora_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"show_post_title_on_quotes" => array(
					"title" => esc_html__('Show post title on links, chat, quote, status', 'writer-ancora'),
					"desc" => wp_kses( __('Show area with post title on single and blog pages in specific post formats: links, chat, quote, status', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,page",
					"std" => "no",
					"options" => writer_ancora_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"show_post_info" => array(
					"title" => esc_html__('Show post info', 'writer-ancora'),
					"desc" => wp_kses( __('Show area with post info on single pages', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,post,page",
					"std" => "yes",
					"options" => writer_ancora_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"show_text_before_readmore" => array(
					"title" => esc_html__('Show text before "Read more" tag', 'writer-ancora'),
					"desc" => wp_kses( __('Show text before "Read more" tag on single pages', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"std" => "yes",
					"options" => writer_ancora_get_options_param('list_yes_no'),
					"type" => "switch"),
					
		"show_post_author" => array(
					"title" => esc_html__('Show post author details',  'writer-ancora'),
					"desc" => wp_kses( __("Show post author information block on single post page", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,post,page",
					"std" => "yes",
					"options" => writer_ancora_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"show_post_tags" => array(
					"title" => esc_html__('Show post tags',  'writer-ancora'),
					"desc" => wp_kses( __("Show tags block on single post page", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,post,page",
					"std" => "yes",
					"options" => writer_ancora_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"show_post_related" => array(
					"title" => esc_html__('Show related posts',  'writer-ancora'),
					"desc" => wp_kses( __("Show related posts block on single post page", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,post,page",
					"std" => "no",
					"options" => writer_ancora_get_options_param('list_yes_no'),
					"type" => "switch"),

		"post_related_count" => array(
					"title" => esc_html__('Related posts number',  'writer-ancora'),
					"desc" => wp_kses( __("How many related posts showed on single post page", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"dependency" => array(
						'show_post_related' => array('yes')
					),
					"override" => "category,services_group,post,page",
					"std" => "2",
					"step" => 1,
					"min" => 2,
					"max" => 8,
					"type" => "spinner"),

		"post_related_columns" => array(
					"title" => esc_html__('Related posts columns',  'writer-ancora'),
					"desc" => wp_kses( __("How many columns used to show related posts on single post page. 1 - use scrolling to show all related posts", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_post_related' => array('yes')
					),
					"std" => "2",
					"step" => 1,
					"min" => 1,
					"max" => 4,
					"type" => "spinner"),
		
		"post_related_sort" => array(
					"title" => esc_html__('Related posts sorted by', 'writer-ancora'),
					"desc" => wp_kses( __('Select the desired sorting method for related posts', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
		//			"override" => "category,services_group,page",
					"dependency" => array(
						'show_post_related' => array('yes')
					),
					"std" => "date",
					"options" => writer_ancora_get_options_param('list_sorting'),
					"type" => "select"),
		
		"post_related_order" => array(
					"title" => esc_html__('Related posts order', 'writer-ancora'),
					"desc" => wp_kses( __('Select the desired ordering method for related posts', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
		//			"override" => "category,services_group,page",
					"dependency" => array(
						'show_post_related' => array('yes')
					),
					"std" => "desc",
					"options" => writer_ancora_get_options_param('list_ordering'),
					"size" => "big",
					"type" => "switch"),
		
		"show_post_comments" => array(
					"title" => esc_html__('Show comments',  'writer-ancora'),
					"desc" => wp_kses( __("Show comments block on single post page", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,post,page",
					"std" => "yes",
					"options" => writer_ancora_get_options_param('list_yes_no'),
					"type" => "hidden"), // switch
		
		
		
		// Blog -> Other parameters
		//-------------------------------------------------
		
		'blog_tab_other' => array(
					"title" => esc_html__('Other parameters', 'writer-ancora'),
					"icon" => "iconadmin-newspaper",
					"override" => "category,services_group,page",
					"type" => "tab"),
		
		"info_blog_other_1" => array(
					"title" => esc_html__('Other Blog parameters', 'writer-ancora'),
					"desc" => wp_kses( __('Select excluded categories, substitute parameters, etc.', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"type" => "info"),
		
		"exclude_cats" => array(
					"title" => esc_html__('Exclude categories', 'writer-ancora'),
					"desc" => wp_kses( __('Select categories, which posts are exclude from blog page', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"std" => "",
					"options" => writer_ancora_get_options_param('list_categories'),
					"multiple" => true,
					"style" => "list",
					"type" => "select"),
		
		"blog_pagination" => array(
					"title" => esc_html__('Blog pagination', 'writer-ancora'),
					"desc" => wp_kses( __('Select type of the pagination on blog streampages', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"std" => "pages",
					"override" => "category,services_group,page",
					"options" => array(
						'pages'    => esc_html__('Standard page numbers', 'writer-ancora'),
						'slider'   => esc_html__('Slider with page numbers', 'writer-ancora'),
						'viewmore' => esc_html__('"View more" button', 'writer-ancora'),
						'infinite' => esc_html__('Infinite scroll', 'writer-ancora')
					),
					"dir" => "vertical",
					"type" => "radio"),
		
		"blog_counters" => array(
					"title" => esc_html__('Blog counters', 'writer-ancora'),
					"desc" => wp_kses( __('Select counters, displayed near the post title', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,page",
					"std" => "views",
					"options" => writer_ancora_get_options_param('list_blog_counters'),
					"dir" => "vertical",
					"multiple" => true,
					"type" => "checklist"),
		
		"close_category" => array(
					"title" => esc_html__("Post's category announce", 'writer-ancora'),
					"desc" => wp_kses( __('What category display in announce block (over posts thumb) - original or nearest parental', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,page",
					"std" => "parental",
					"options" => array(
						'parental' => esc_html__('Nearest parental category', 'writer-ancora'),
						'original' => esc_html__("Original post's category", 'writer-ancora')
					),
					"dir" => "vertical",
					"type" => "radio"),
		
		"show_date_after" => array(
					"title" => esc_html__('Show post date after', 'writer-ancora'),
					"desc" => wp_kses( __('Show post date after N days (before - show post age)', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,page",
					"std" => "30",
					"mask" => "?99",
					"type" => "text"),
		
		
		
		
		
		//###############################
		//#### Reviews               #### 
		//###############################
		"partition_reviews" => array(
					"title" => esc_html__('Reviews', 'writer-ancora'),
					"icon" => "iconadmin-newspaper",
					"override" => "category,services_group,services_group",
					"type" => "partition"),
		
		"info_reviews_1" => array(
					"title" => esc_html__('Reviews criterias', 'writer-ancora'),
					"desc" => wp_kses( __('Set up list of reviews criterias. You can override it in any category.', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,services_group",
					"type" => "info"),
		
		"show_reviews" => array(
					"title" => esc_html__('Show reviews block',  'writer-ancora'),
					"desc" => wp_kses( __("Show reviews block on single post page and average reviews rating after post's title in stream pages", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,services_group",
					"std" => "yes",
					"options" => writer_ancora_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"reviews_max_level" => array(
					"title" => esc_html__('Max reviews level',  'writer-ancora'),
					"desc" => wp_kses( __("Maximum level for reviews marks", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"std" => "5",
					"options" => array(
						'5'=>esc_html__('5 stars', 'writer-ancora'), 
						'10'=>esc_html__('10 stars', 'writer-ancora'), 
						'100'=>esc_html__('100%', 'writer-ancora')
					),
					"type" => "radio",
					),
		
		"reviews_style" => array(
					"title" => esc_html__('Show rating as',  'writer-ancora'),
					"desc" => wp_kses( __("Show rating marks as text or as stars/progress bars.", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"std" => "stars",
					"options" => array(
						'text' => esc_html__('As text (for example: 7.5 / 10)', 'writer-ancora'), 
						'stars' => esc_html__('As stars or bars', 'writer-ancora')
					),
					"dir" => "vertical",
					"type" => "radio"),
		
		"reviews_criterias_levels" => array(
					"title" => esc_html__('Reviews Criterias Levels', 'writer-ancora'),
					"desc" => wp_kses( __('Words to mark criterials levels. Just write the word and press "Enter". Also you can arrange words.', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"std" => esc_html__("bad,poor,normal,good,great", 'writer-ancora'),
					"type" => "tags"),
		
		"reviews_first" => array(
					"title" => esc_html__('Show first reviews',  'writer-ancora'),
					"desc" => wp_kses( __("What reviews will be displayed first: by author or by visitors. Also this type of reviews will display under post's title.", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"std" => "author",
					"options" => array(
						'author' => esc_html__('By author', 'writer-ancora'),
						'users' => esc_html__('By visitors', 'writer-ancora')
						),
					"dir" => "horizontal",
					"type" => "radio"),
		
		"reviews_second" => array(
					"title" => esc_html__('Hide second reviews',  'writer-ancora'),
					"desc" => wp_kses( __("Do you want hide second reviews tab in widgets and single posts?", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"std" => "show",
					"options" => writer_ancora_get_options_param('list_show_hide'),
					"size" => "medium",
					"type" => "switch"),
		
		"reviews_can_vote" => array(
					"title" => esc_html__('What visitors can vote',  'writer-ancora'),
					"desc" => wp_kses( __("What visitors can vote: all or only registered", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"std" => "all",
					"options" => array(
						'all'=>esc_html__('All visitors', 'writer-ancora'), 
						'registered'=>esc_html__('Only registered', 'writer-ancora')
					),
					"dir" => "horizontal",
					"type" => "radio"),
		
		"reviews_criterias" => array(
					"title" => esc_html__('Reviews criterias',  'writer-ancora'),
					"desc" => wp_kses( __('Add default reviews criterias.',  'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,services_group",
					"std" => "",
					"cloneable" => true,
					"type" => "text"),

		// Don't remove this parameter - it used in admin for store marks
		"reviews_marks" => array(
					"std" => "",
					"type" => "hidden"),
		





		//###############################
		//#### Media                #### 
		//###############################
		"partition_media" => array(
					"title" => esc_html__('Media', 'writer-ancora'),
					"icon" => "iconadmin-picture",
					"override" => "category,services_group,post,page",
					"type" => "partition"),
		
		"info_media_1" => array(
					"title" => esc_html__('Media settings', 'writer-ancora'),
					"desc" => wp_kses( __('Set up parameters to show images, galleries, audio and video posts', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,services_group",
					"type" => "info"),
					
		"retina_ready" => array(
					"title" => esc_html__('Image dimensions', 'writer-ancora'),
					"desc" => wp_kses( __('What dimensions use for uploaded image: Original or "Retina ready" (twice enlarged)', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"std" => "1",
					"size" => "medium",
					"options" => array(
						"1" => esc_html__("Original", 'writer-ancora'), 
						"2" => esc_html__("Retina", 'writer-ancora')
					),
					"type" => "switch"),
		
		"images_quality" => array(
					"title" => esc_html__('Quality for cropped images', 'writer-ancora'),
					"desc" => wp_kses( __('Quality (1-100) to save cropped images', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"std" => "70",
					"min" => 1,
					"max" => 100,
					"type" => "spinner"),
		
		"substitute_gallery" => array(
					"title" => esc_html__('Substitute standard WordPress gallery', 'writer-ancora'),
					"desc" => wp_kses( __('Substitute standard WordPress gallery with our slider on the single pages', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,post,page",
					"std" => "no",
					"options" => writer_ancora_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"gallery_instead_image" => array(
					"title" => esc_html__('Show gallery instead featured image', 'writer-ancora'),
					"desc" => wp_kses( __('Show slider with gallery instead featured image on blog streampage and in the related posts section for the gallery posts', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,post,page",
					"std" => "yes",
					"options" => writer_ancora_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"gallery_max_slides" => array(
					"title" => esc_html__('Max images number in the slider', 'writer-ancora'),
					"desc" => wp_kses( __('Maximum images number from gallery into slider', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'gallery_instead_image' => array('yes')
					),
					"std" => "5",
					"min" => 2,
					"max" => 10,
					"type" => "spinner"),
		
		"popup_engine" => array(
					"title" => esc_html__('Popup engine to zoom images', 'writer-ancora'),
					"desc" => wp_kses( __('Select engine to show popup windows with images and galleries', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"std" => "magnific",
					"options" => writer_ancora_get_options_param('list_popups'),
					"type" => "select"),
		
		"substitute_audio" => array(
					"title" => esc_html__('Substitute audio tags', 'writer-ancora'),
					"desc" => wp_kses( __('Substitute audio tag with source from soundcloud to embed player', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,post,page",
					"std" => "yes",
					"options" => writer_ancora_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"substitute_video" => array(
					"title" => esc_html__('Substitute video tags', 'writer-ancora'),
					"desc" => wp_kses( __('Substitute video tags with embed players or leave video tags unchanged (if you use third party plugins for the video tags)', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,post,page",
					"std" => "yes",
					"options" => writer_ancora_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"use_mediaelement" => array(
					"title" => esc_html__('Use Media Element script for audio and video tags', 'writer-ancora'),
					"desc" => wp_kses( __('Do you want use the Media Element script for all audio and video tags on your site or leave standard HTML5 behaviour?', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"std" => "yes",
					"options" => writer_ancora_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		
		
		
		//###############################
		//#### Socials               #### 
		//###############################
		"partition_socials" => array(
					"title" => esc_html__('Socials', 'writer-ancora'),
					"icon" => "iconadmin-users",
					"override" => "category,services_group,page",
					"type" => "partition"),
		
		"info_socials_1" => array(
					"title" => esc_html__('Social networks', 'writer-ancora'),
					"desc" => wp_kses( __("Social networks list for site footer and Social widget", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"type" => "info"),
		
		"social_icons" => array(
					"title" => esc_html__('Social networks',  'writer-ancora'),
					"desc" => wp_kses( __('Select icon and write URL to your profile in desired social networks.',  'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"std" => array(array('url'=>'', 'icon'=>'')),
					"cloneable" => true,
					"size" => "small",
					"style" => $socials_type,
					"options" => $socials_type=='images' ? writer_ancora_get_options_param('list_socials') : writer_ancora_get_options_param('list_icons'),
					"type" => "socials"),
		
		"info_socials_2" => array(
					"title" => esc_html__('Share buttons', 'writer-ancora'),
					"desc" => wp_kses( __("Add button's code for each social share network.<br>
					In share url you can use next macro:<br>
					<b>{url}</b> - share post (page) URL,<br>
					<b>{title}</b> - post title,<br>
					<b>{image}</b> - post image,<br>
					<b>{descr}</b> - post description (if supported)<br>
					For example:<br>
					<b>Facebook</b> share string: <em>http://www.facebook.com/sharer.php?u={link}&amp;t={title}</em><br>
					<b>Delicious</b> share string: <em>http://delicious.com/save?url={link}&amp;title={title}&amp;note={descr}</em>", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,page",
					"type" => "info"),
		
		"show_share" => array(
					"title" => esc_html__('Show social share buttons',  'writer-ancora'),
					"desc" => wp_kses( __("Show social share buttons block", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,page",
					"std" => "horizontal",
					"options" => array(
						'hide'		=> esc_html__('Hide', 'writer-ancora'),
						'vertical'	=> esc_html__('Vertical', 'writer-ancora'),
						'horizontal'=> esc_html__('Horizontal', 'writer-ancora')
					),
					"type" => "checklist"),

		"show_share_counters" => array(
					"title" => esc_html__('Show share counters',  'writer-ancora'),
					"desc" => wp_kses( __("Show share counters after social buttons", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_share' => array('vertical', 'horizontal')
					),
					"std" => "yes",
					"options" => writer_ancora_get_options_param('list_yes_no'),
					"type" => "switch"),

		"share_caption" => array(
					"title" => esc_html__('Share block caption',  'writer-ancora'),
					"desc" => wp_kses( __('Caption for the block with social share buttons',  'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_share' => array('vertical', 'horizontal')
					),
					"std" => esc_html__('Share:', 'writer-ancora'),
					"type" => "text"),
		
		"share_buttons" => array(
					"title" => esc_html__('Share buttons',  'writer-ancora'),
					"desc" => wp_kses( __('Select icon and write share URL for desired social networks.<br><b>Important!</b> If you leave text field empty - internal theme link will be used (if present).',  'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"dependency" => array(
						'show_share' => array('vertical', 'horizontal')
					),
					"std" => array(array('url'=>'', 'icon'=>'')),
					"cloneable" => true,
					"size" => "small",
					"style" => $socials_type,
					"options" => $socials_type=='images' ? writer_ancora_get_options_param('list_socials') : writer_ancora_get_options_param('list_icons'),
					"type" => "socials"),
		
		
		"info_socials_3" => array(
					"title" => esc_html__('Twitter API keys', 'writer-ancora'),
					"desc" => wp_kses( __("Put to this section Twitter API 1.1 keys.<br>You can take them after registration your application in <strong>https://apps.twitter.com/</strong>", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"type" => "info"),
		
		"twitter_username" => array(
					"title" => esc_html__('Twitter username',  'writer-ancora'),
					"desc" => wp_kses( __('Your login (username) in Twitter',  'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"divider" => false,
					"std" => "",
					"type" => "text"),
		
		"twitter_consumer_key" => array(
					"title" => esc_html__('Consumer Key',  'writer-ancora'),
					"desc" => wp_kses( __('Twitter API Consumer key',  'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"divider" => false,
					"std" => "",
					"type" => "text"),
		
		"twitter_consumer_secret" => array(
					"title" => esc_html__('Consumer Secret',  'writer-ancora'),
					"desc" => wp_kses( __('Twitter API Consumer secret',  'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"divider" => false,
					"std" => "",
					"type" => "text"),
		
		"twitter_token_key" => array(
					"title" => esc_html__('Token Key',  'writer-ancora'),
					"desc" => wp_kses( __('Twitter API Token key',  'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"divider" => false,
					"std" => "",
					"type" => "text"),
		
		"twitter_token_secret" => array(
					"title" => esc_html__('Token Secret',  'writer-ancora'),
					"desc" => wp_kses( __('Twitter API Token secret',  'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"divider" => false,
					"std" => "",
					"type" => "text"),
		
		
		
		
		
		//###############################
		//#### Contact info          #### 
		//###############################
		"partition_contacts" => array(
					"title" => esc_html__('Contact info', 'writer-ancora'),
					"icon" => "iconadmin-mail",
					"type" => "partition"),
		
		"info_contact_1" => array(
					"title" => esc_html__('Contact information', 'writer-ancora'),
					"desc" => wp_kses( __('Company address, phones and e-mail', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"type" => "info"),
		
		"contact_info" => array(
					"title" => esc_html__('Contacts in the header', 'writer-ancora'),
					"desc" => wp_kses( __('String with contact info in the left side of the site header', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"std" => "",
					"before" => array('icon'=>'iconadmin-home'),
					"allow_html" => true,
					"type" => "text"),
		
		"contact_open_hours" => array(
					"title" => esc_html__('Open hours in the header', 'writer-ancora'),
					"desc" => wp_kses( __('String with open hours in the site header', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"std" => "",
					"before" => array('icon'=>'iconadmin-clock'),
					"allow_html" => true,
					"type" => "text"),
		
		"contact_email" => array(
					"title" => esc_html__('Contact form email', 'writer-ancora'),
					"desc" => wp_kses( __('E-mail for send contact form and user registration data', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"std" => "",
					"before" => array('icon'=>'iconadmin-mail'),
					"type" => "text"),
		
		"contact_address_1" => array(
					"title" => esc_html__('Company address (part 1)', 'writer-ancora'),
					"desc" => wp_kses( __('Company country, post code and city', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"std" => "",
					"before" => array('icon'=>'iconadmin-home'),
					"type" => "text"),
		
		"contact_address_2" => array(
					"title" => esc_html__('Company address (part 2)', 'writer-ancora'),
					"desc" => wp_kses( __('Street and house number', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"std" => "",
					"before" => array('icon'=>'iconadmin-home'),
					"type" => "text"),
		
		"contact_phone" => array(
					"title" => esc_html__('Phone', 'writer-ancora'),
					"desc" => wp_kses( __('Phone number', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"std" => "",
					"before" => array('icon'=>'iconadmin-phone'),
					"allow_html" => true,
					"type" => "text"),
		
		"contact_fax" => array(
					"title" => esc_html__('Fax', 'writer-ancora'),
					"desc" => wp_kses( __('Fax number', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"std" => "",
					"before" => array('icon'=>'iconadmin-phone'),
					"allow_html" => true,
					"type" => "text"),
		
		"info_contact_2" => array(
					"title" => esc_html__('Contact and Comments form', 'writer-ancora'),
					"desc" => wp_kses( __('Maximum length of the messages in the contact form shortcode and in the comments form', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"type" => "info"),
		
		"message_maxlength_contacts" => array(
					"title" => esc_html__('Contact form message', 'writer-ancora'),
					"desc" => wp_kses( __("Message's maxlength in the contact form shortcode", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"std" => "1000",
					"min" => 0,
					"max" => 10000,
					"step" => 100,
					"type" => "spinner"),
		
		"message_maxlength_comments" => array(
					"title" => esc_html__('Comments form message', 'writer-ancora'),
					"desc" => wp_kses( __("Message's maxlength in the comments form", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"std" => "1000",
					"min" => 0,
					"max" => 10000,
					"step" => 100,
					"type" => "spinner"),
		
		"info_contact_3" => array(
					"title" => esc_html__('Default mail function', 'writer-ancora'),
					"desc" => wp_kses( __('What function you want to use for sending mail: the built-in WordPress wp_mail() or standard PHP mail() function? Attention! Some plugins may not work with one of them and you always have the ability to switch to alternative.', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"type" => "info"),
		
		"mail_function" => array(
					"title" => esc_html__("Mail function", 'writer-ancora'),
					"desc" => wp_kses( __("What function you want to use for sending mail?", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"std" => "wp_mail",
					"size" => "medium",
					"options" => array(
						'wp_mail' => esc_html__('WP mail', 'writer-ancora'),
						'mail' => esc_html__('PHP mail', 'writer-ancora')
					),
					"type" => "switch"),
		
		
		
		
		
		
		
		//###############################
		//#### Search parameters     #### 
		//###############################
		"partition_search" => array(
					"title" => esc_html__('Search', 'writer-ancora'),
					"icon" => "iconadmin-search",
					"type" => "partition"),
		
		"info_search_1" => array(
					"title" => esc_html__('Search parameters', 'writer-ancora'),
					"desc" => wp_kses( __('Enable/disable AJAX search and output settings for it', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"type" => "info"),
		
		"show_search" => array(
					"title" => esc_html__('Show search field', 'writer-ancora'),
					"desc" => wp_kses( __('Show search field in the top area and side menus', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"std" => "yes",
					"options" => writer_ancora_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"use_ajax_search" => array(
					"title" => esc_html__('Enable AJAX search', 'writer-ancora'),
					"desc" => wp_kses( __('Use incremental AJAX search for the search field in top of page', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"dependency" => array(
						'show_search' => array('yes')
					),
					"std" => "yes",
					"options" => writer_ancora_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"ajax_search_min_length" => array(
					"title" => esc_html__('Min search string length',  'writer-ancora'),
					"desc" => wp_kses( __('The minimum length of the search string',  'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"dependency" => array(
						'show_search' => array('yes'),
						'use_ajax_search' => array('yes')
					),
					"std" => 4,
					"min" => 3,
					"type" => "spinner"),
		
		"ajax_search_delay" => array(
					"title" => esc_html__('Delay before search (in ms)',  'writer-ancora'),
					"desc" => wp_kses( __('How much time (in milliseconds, 1000 ms = 1 second) must pass after the last character before the start search',  'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"dependency" => array(
						'show_search' => array('yes'),
						'use_ajax_search' => array('yes')
					),
					"std" => 500,
					"min" => 300,
					"max" => 1000,
					"step" => 100,
					"type" => "spinner"),
		
		"ajax_search_types" => array(
					"title" => esc_html__('Search area', 'writer-ancora'),
					"desc" => wp_kses( __('Select post types, what will be include in search results. If not selected - use all types.', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"dependency" => array(
						'show_search' => array('yes'),
						'use_ajax_search' => array('yes')
					),
					"std" => "",
					"options" => writer_ancora_get_options_param('list_posts_types'),
					"multiple" => true,
					"style" => "list",
					"type" => "select"),
		
		"ajax_search_posts_count" => array(
					"title" => esc_html__('Posts number in output',  'writer-ancora'),
					"dependency" => array(
						'show_search' => array('yes'),
						'use_ajax_search' => array('yes')
					),
					"desc" => wp_kses( __('Number of the posts to show in search results',  'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"std" => 5,
					"min" => 1,
					"max" => 10,
					"type" => "spinner"),
		
		"ajax_search_posts_image" => array(
					"title" => esc_html__("Show post's image", 'writer-ancora'),
					"dependency" => array(
						'show_search' => array('yes'),
						'use_ajax_search' => array('yes')
					),
					"desc" => wp_kses( __("Show post's thumbnail in the search results", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"std" => "yes",
					"options" => writer_ancora_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"ajax_search_posts_date" => array(
					"title" => esc_html__("Show post's date", 'writer-ancora'),
					"dependency" => array(
						'show_search' => array('yes'),
						'use_ajax_search' => array('yes')
					),
					"desc" => wp_kses( __("Show post's publish date in the search results", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"std" => "yes",
					"options" => writer_ancora_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"ajax_search_posts_author" => array(
					"title" => esc_html__("Show post's author", 'writer-ancora'),
					"dependency" => array(
						'show_search' => array('yes'),
						'use_ajax_search' => array('yes')
					),
					"desc" => wp_kses( __("Show post's author in the search results", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"std" => "yes",
					"options" => writer_ancora_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"ajax_search_posts_counters" => array(
					"title" => esc_html__("Show post's counters", 'writer-ancora'),
					"dependency" => array(
						'show_search' => array('yes'),
						'use_ajax_search' => array('yes')
					),
					"desc" => wp_kses( __("Show post's counters (views, comments, likes) in the search results", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"std" => "yes",
					"options" => writer_ancora_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		
		
		
		
		//###############################
		//#### Service               #### 
		//###############################
		
		"partition_service" => array(
					"title" => esc_html__('Service', 'writer-ancora'),
					"icon" => "iconadmin-wrench",
					"type" => "partition"),
		
		"info_service_1" => array(
					"title" => esc_html__('Theme functionality', 'writer-ancora'),
					"desc" => wp_kses( __('Basic theme functionality settings', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"type" => "info"),
		
		"notify_about_new_registration" => array(
					"title" => esc_html__('Notify about new registration', 'writer-ancora'),
					"desc" => wp_kses( __('Send E-mail with new registration data to the contact email or to site admin e-mail (if contact email is empty)', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"divider" => false,
					"std" => "no",
					"options" => array(
						'no'    => esc_html__('No', 'writer-ancora'),
						'both'  => esc_html__('Both', 'writer-ancora'),
						'admin' => esc_html__('Admin', 'writer-ancora'),
						'user'  => esc_html__('User', 'writer-ancora')
					),
					"dir" => "horizontal",
					"type" => "checklist"),
		
		"use_ajax_views_counter" => array(
					"title" => esc_html__('Use AJAX post views counter', 'writer-ancora'),
					"desc" => wp_kses( __('Use javascript for post views count (if site work under the caching plugin) or increment views count in single page template', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"std" => "no",
					"options" => writer_ancora_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"allow_editor" => array(
					"title" => esc_html__('Frontend editor',  'writer-ancora'),
					"desc" => wp_kses( __("Allow authors to edit their posts in frontend area)", 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"std" => "no",
					"options" => writer_ancora_get_options_param('list_yes_no'),
					"type" => "switch"),

		"admin_add_filters" => array(
					"title" => esc_html__('Additional filters in the admin panel', 'writer-ancora'),
					"desc" => wp_kses( __('Show additional filters (on post formats, tags and categories) in admin panel page "Posts". <br>Attention! If you have more than 2.000-3.000 posts, enabling this option may cause slow load of the "Posts" page! If you encounter such slow down, simply open Appearance - Theme Options - Service and set "No" for this option.', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"std" => "yes",
					"options" => writer_ancora_get_options_param('list_yes_no'),
					"type" => "switch"),

		"show_overriden_taxonomies" => array(
					"title" => esc_html__('Show overriden options for taxonomies', 'writer-ancora'),
					"desc" => wp_kses( __('Show extra column in categories list, where changed (overriden) theme options are displayed.', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"std" => "yes",
					"options" => writer_ancora_get_options_param('list_yes_no'),
					"type" => "switch"),

		"show_overriden_posts" => array(
					"title" => esc_html__('Show overriden options for posts and pages', 'writer-ancora'),
					"desc" => wp_kses( __('Show extra column in posts and pages list, where changed (overriden) theme options are displayed.', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"std" => "yes",
					"options" => writer_ancora_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"admin_dummy_data" => array(
					"title" => esc_html__('Enable Dummy Data Installer', 'writer-ancora'),
					"desc" => wp_kses( __('Show "Install Dummy Data" in the menu "Appearance". <b>Attention!</b> When you install dummy data all content of your site will be replaced!', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"std" => "yes",
					"options" => writer_ancora_get_options_param('list_yes_no'),
					"type" => "switch"),

		"admin_dummy_timeout" => array(
					"title" => esc_html__('Dummy Data Installer Timeout',  'writer-ancora'),
					"desc" => wp_kses( __('Web-servers set the time limit for the execution of php-scripts. By default, this is 30 sec. Therefore, the import process will be split into parts. Upon completion of each part - the import will resume automatically! The import process will try to increase this limit to the time, specified in this field.',  'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"std" => 120,
					"min" => 30,
					"max" => 1800,
					"type" => "spinner"),
		
		"admin_emailer" => array(
					"title" => esc_html__('Enable Emailer in the admin panel', 'writer-ancora'),
					"desc" => wp_kses( __('Allow to use Writer Ancora Emailer for mass-volume e-mail distribution and management of mailing lists in "Appearance - Emailer"', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"std" => "no",
					"options" => writer_ancora_get_options_param('list_yes_no'),
					"type" => "switch"),

		"admin_po_composer" => array(
					"title" => esc_html__('Enable PO Composer in the admin panel', 'writer-ancora'),
					"desc" => wp_kses( __('Allow to use "PO Composer" for edit language files in this theme (in the "Appearance - PO Composer")', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"std" => "no",
					"options" => writer_ancora_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"debug_mode" => array(
					"title" => esc_html__('Debug mode', 'writer-ancora'),
					"desc" => wp_kses( __('In debug mode we are using unpacked scripts and styles, else - using minified scripts and styles (if present). <b>Attention!</b> If you have modified the source code in the js or css files, regardless of this option will be used latest (modified) version stylesheets and scripts. You can re-create minified versions of files using on-line services or utilities', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"std" => "no",
					"options" => writer_ancora_get_options_param('list_yes_no'),
					"type" => "switch"),

        "info_service_3" => array(
                "title" => esc_html__('API Keys', 'writer-ancora'),
                "desc" => wp_kses_data( __('API Keys for some Web services', 'writer-ancora') ),
                "type" => "info"),
        'api_google' => array(
                "title" => esc_html__('Google API Key', 'writer-ancora'),
                "desc" => wp_kses_data( __("Insert Google API Key for browsers into the field above to generate Google Maps", 'writer-ancora') ),
                "std" => "",
                "type" => "text"),
		
		"info_service_2" => array(
					"title" => esc_html__('Clear WordPress cache', 'writer-ancora'),
					"desc" => wp_kses( __('For example, it recommended after activating the WPML plugin - in the cache are incorrect data about the structure of categories and your site may display "white screen". After clearing the cache usually the performance of the site is restored.', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"type" => "info"),
		
		"clear_cache" => array(
					"title" => esc_html__('Clear cache', 'writer-ancora'),
					"desc" => wp_kses( __('Clear WordPress cache data', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
					"divider" => false,
					"icon" => "iconadmin-trash",
					"action" => "clear_cache",
					"type" => "button")
		));



		
		
		
		//###############################################
		//#### Hidden fields (for internal use only) #### 
		//###############################################
		/*
		writer_ancora_storage_set_array('options', "custom_stylesheet_file", array(
			"title" => esc_html__('Custom stylesheet file', 'writer-ancora'),
			"desc" => wp_kses( __('Path to the custom stylesheet (stored in the uploads folder)', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
			"std" => "",
			"type" => "hidden"
			)
		);
		
		writer_ancora_storage_set_array('options', "custom_stylesheet_url", array(
			"title" => esc_html__('Custom stylesheet url', 'writer-ancora'),
			"desc" => wp_kses( __('URL to the custom stylesheet (stored in the uploads folder)', 'writer-ancora'), writer_ancora_storage_get('allowed_tags') ),
			"std" => "",
			"type" => "hidden"
			)
		);
		*/

	}
}


// Update all temporary vars (start with $writer_ancora_) in the Theme Options with actual lists
if ( !function_exists( 'writer_ancora_options_settings_theme_setup2' ) ) {
	add_action( 'writer_ancora_action_after_init_theme', 'writer_ancora_options_settings_theme_setup2', 1 );
	function writer_ancora_options_settings_theme_setup2() {
		if (writer_ancora_options_is_used()) {
			// Replace arrays with actual parameters
			$lists = array();
			$tmp = writer_ancora_storage_get('options');
			if (is_array($tmp) && count($tmp) > 0) {
				$prefix = '$writer_ancora_';
				$prefix_len = writer_ancora_strlen($prefix);
				foreach ($tmp as $k=>$v) {
					if (isset($v['options']) && is_array($v['options']) && count($v['options']) > 0) {
						foreach ($v['options'] as $k1=>$v1) {
							if (writer_ancora_substr($k1, 0, $prefix_len) == $prefix || writer_ancora_substr($v1, 0, $prefix_len) == $prefix) {
								$list_func = writer_ancora_substr(writer_ancora_substr($k1, 0, $prefix_len) == $prefix ? $k1 : $v1, 1);
								unset($tmp[$k]['options'][$k1]);
								if (isset($lists[$list_func]))
									$tmp[$k]['options'] = writer_ancora_array_merge($tmp[$k]['options'], $lists[$list_func]);
								else {
									if (function_exists($list_func)) {
										$tmp[$k]['options'] = $lists[$list_func] = writer_ancora_array_merge($tmp[$k]['options'], $list_func == 'writer_ancora_get_list_menus' ? $list_func(true) : $list_func());
								   	} else
								   		dfl(sprintf(esc_html__('Wrong function name %s in the theme options array', 'writer-ancora'), $list_func));
								}
							}
						}
					}
				}
				writer_ancora_storage_set('options', $tmp);
			}
		}
	}
}

// Reset old Theme Options while theme first run
if ( !function_exists( 'writer_ancora_options_reset' ) ) {
	function writer_ancora_options_reset($clear=true) {
		$theme_data = wp_get_theme();
		$slug = str_replace(' ', '_', trim(writer_ancora_strtolower((string) $theme_data->get('Name'))));
		$option_name = 'writer_ancora_'.strip_tags($slug).'_options_reset';
		if ( get_option($option_name, false) === false ) {	// && (string) $theme_data->get('Version') == '1.0'
			if ($clear) {
				// Remove Theme Options from WP Options
				global $wpdb;
				$wpdb->query('delete from '.esc_sql($wpdb->options).' where option_name like "writer_ancora_%"');
				// Add Templates Options
				if (file_exists(writer_ancora_get_file_dir('demo/templates_options.txt'))) {
					$txt = writer_ancora_fgc(writer_ancora_get_file_dir('demo/templates_options.txt'));
					$data = writer_ancora_unserialize($txt);
					// Replace upload url in options
					if (is_array($data) && count($data) > 0) {
						foreach ($data as $k=>$v) {
							if (is_array($v) && count($v) > 0) {
								foreach ($v as $k1=>$v1) {
									$v[$k1] = writer_ancora_replace_uploads_url(writer_ancora_replace_uploads_url($v1, 'uploads'), 'imports');
								}
							}
							add_option( $k, $v, '', 'yes' );
						}
					}
				}
			}
			add_option($option_name, 1, '', 'yes');
		}
	}
}

?>