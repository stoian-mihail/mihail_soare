<?php
/**
 * Skin file for the theme.
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Theme init
if (!function_exists('writer_ancora_action_skin_theme_setup')) {
	add_action( 'writer_ancora_action_init_theme', 'writer_ancora_action_skin_theme_setup', 1 );
	function writer_ancora_action_skin_theme_setup() {

		// Add skin fonts in the used fonts list
		add_filter('writer_ancora_filter_used_fonts',			'writer_ancora_filter_skin_used_fonts');
		// Add skin fonts (from Google fonts) in the main fonts list (if not present).
		add_filter('writer_ancora_filter_list_fonts',			'writer_ancora_filter_skin_list_fonts');

		// Add skin stylesheets
		add_action('writer_ancora_action_add_styles',			'writer_ancora_action_skin_add_styles');
		// Add skin inline styles
		add_filter('writer_ancora_filter_add_styles_inline',		'writer_ancora_filter_skin_add_styles_inline');
		// Add skin responsive styles
		add_action('writer_ancora_action_add_responsive',		'writer_ancora_action_skin_add_responsive');
		// Add skin responsive inline styles
		add_filter('writer_ancora_filter_add_responsive_inline',	'writer_ancora_filter_skin_add_responsive_inline');

		// Add skin scripts
		add_action('writer_ancora_action_add_scripts',			'writer_ancora_action_skin_add_scripts');
		// Add skin scripts inline
		add_action('writer_ancora_action_add_scripts_inline',	'writer_ancora_action_skin_add_scripts_inline');

		// Add skin less files into list for compilation
		add_filter('writer_ancora_filter_compile_less',			'writer_ancora_filter_skin_compile_less');


		/* Color schemes
		
		// Accenterd colors
		accent1			- theme accented color 1
		accent1_hover	- theme accented color 1 (hover state)
		accent2			- theme accented color 2
		accent2_hover	- theme accented color 2 (hover state)		
		accent3			- theme accented color 3
		accent3_hover	- theme accented color 3 (hover state)		
		
		// Headers, text and links
		text			- main content
		text_light		- post info
		text_dark		- headers
		inverse_text	- text on accented background
		inverse_light	- post info on accented background
		inverse_dark	- headers on accented background
		inverse_link	- links on accented background
		inverse_hover	- hovered links on accented background
		
		// Block's border and background
		bd_color		- border for the entire block
		bg_color		- background color for the entire block
		bg_image, bg_image_position, bg_image_repeat, bg_image_attachment  - first background image for the entire block
		bg_image2,bg_image2_position,bg_image2_repeat,bg_image2_attachment - second background image for the entire block
		
		// Alternative colors - highlight blocks, form fields, etc.
		alter_text		- text on alternative background
		alter_light		- post info on alternative background
		alter_dark		- headers on alternative background
		alter_link		- links on alternative background
		alter_hover		- hovered links on alternative background
		alter_bd_color	- alternative border
		alter_bd_hover	- alternative border for hovered state or active field
		alter_bg_color	- alternative background
		alter_bg_hover	- alternative background for hovered state or active field 
		alter_bg_image, alter_bg_image_position, alter_bg_image_repeat, alter_bg_image_attachment - background image for the alternative block
		
		*/

		// Add color schemes
		writer_ancora_add_color_scheme('original', array(

			'title'					=> esc_html__('Original', 'writer-ancora'),

			// Accent colors
			'accent1'				=> '#e2c75a',
			'accent1_hover'			=> '#d1a805',
			'accent2'				=> '#171717',
			'accent2_hover'			=> '#2c2b2b',
			'accent3'				=> '#ffffff',
			'accent3_hover'			=> '#f5f5f5',
			
			// Headers, text and links colors
			'text'					=> '#545657',
			'text_light'			=> '#727272',
			'text_dark'				=> '#323232',
			'inverse_text'			=> '#ffffff',
			'inverse_light'			=> '#ffffff',
			'inverse_dark'			=> '#ffffff',
			'inverse_link'			=> '#ffffff',
			'inverse_hover'			=> '#ffffff',
			
			// Whole block border and background
			'bd_color'				=> '#e3e3e3',
			'bg_color'				=> '#ffffff',
			'bg_image'				=> '',
			'bg_image_position'		=> 'left top',
			'bg_image_repeat'		=> 'repeat',
			'bg_image_attachment'	=> 'scroll',
			'bg_image2'				=> '',
			'bg_image2_position'	=> 'left top',
			'bg_image2_repeat'		=> 'repeat',
			'bg_image2_attachment'	=> 'scroll',
		
			// Alternative blocks (submenu items, form's fields, etc.)
			'alter_text'			=> '#8a8a8a',
			'alter_light'			=> '#acb4b6',
			'alter_dark'			=> '#232a34',
			'alter_link'			=> '#20c7ca',
			'alter_hover'			=> '#189799',
			'alter_bd_color'		=> '#dddddd',
			'alter_bd_hover'		=> '#bbbbbb',
			'alter_bg_color'		=> '#f7f7f7',
			'alter_bg_hover'		=> '#f0f0f0',
			'alter_bg_image'			=> '',
			'alter_bg_image_position'	=> 'left top',
			'alter_bg_image_repeat'		=> 'repeat',
			'alter_bg_image_attachment'	=> 'scroll',
			)
		);

		// Add color schemes
		writer_ancora_add_color_scheme('light', array(

			'title'					=> esc_html__('Light', 'writer-ancora'),

			// Accent colors
			'accent1'				=> '#20c7ca',
			'accent1_hover'			=> '#189799',
			'accent2'				=> '#ff0000',
			'accent2_hover'			=> '#aa0000',
			'accent3'				=> '',
			'accent3_hover'			=> '',
			
			// Headers, text and links colors
			'text'					=> '#545657',
			'text_light'			=> '#727272',
			'text_dark'				=> '#323232',
			'inverse_text'			=> '#ffffff',
			'inverse_light'			=> '#ffffff',
			'inverse_dark'			=> '#ffffff',
			'inverse_link'			=> '#ffffff',
			'inverse_hover'			=> '#ffffff',
			
			// Whole block border and background
			'bd_color'				=> '#dddddd',
			'bg_color'				=> '#f7f7f7',
			'bg_image'				=> '',
			'bg_image_position'		=> 'left top',
			'bg_image_repeat'		=> 'repeat',
			'bg_image_attachment'	=> 'scroll',
			'bg_image2'				=> '',
			'bg_image2_position'	=> 'left top',
			'bg_image2_repeat'		=> 'repeat',
			'bg_image2_attachment'	=> 'scroll',
		
			// Alternative blocks (submenu items, form's fields, etc.)
			'alter_text'			=> '#8a8a8a',
			'alter_light'			=> '#acb4b6',
			'alter_dark'			=> '#232a34',
			'alter_link'			=> '#20c7ca',
			'alter_hover'			=> '#189799',
			'alter_bd_color'		=> '#e7e7e7',
			'alter_bd_hover'		=> '#dddddd',
			'alter_bg_color'		=> '#ffffff',
			'alter_bg_hover'		=> '#f0f0f0',
			'alter_bg_image'			=> '',
			'alter_bg_image_position'	=> 'left top',
			'alter_bg_image_repeat'		=> 'repeat',
			'alter_bg_image_attachment'	=> 'scroll',
			)
		);

		// Add color schemes
		writer_ancora_add_color_scheme('dark', array(

			'title'					=> esc_html__('Dark', 'writer-ancora'),

			// Accent colors
			'accent1'				=> '#20c7ca',
			'accent1_hover'			=> '#189799',
			'accent2'				=> '#ff0000',
			'accent2_hover'			=> '#aa0000',
			'accent3'				=> '',
			'accent3_hover'			=> '',
			
			// Headers, text and links colors
			'text'					=> '#909090',
			'text_light'			=> '#a0a0a0',
			'text_dark'				=> '#e0e0e0',
			'inverse_text'			=> '#f0f0f0',
			'inverse_light'			=> '#e0e0e0',
			'inverse_dark'			=> '#ffffff',
			'inverse_link'			=> '#ffffff',
			'inverse_hover'			=> '#e5e5e5',
			
			// Whole block border and background
			'bd_color'				=> '#000000',
			'bg_color'				=> '#333333',
			'bg_image'				=> '',
			'bg_image_position'		=> 'left top',
			'bg_image_repeat'		=> 'repeat',
			'bg_image_attachment'	=> 'scroll',
			'bg_image2'				=> '',
			'bg_image2_position'	=> 'left top',
			'bg_image2_repeat'		=> 'repeat',
			'bg_image2_attachment'	=> 'scroll',
		
			// Alternative blocks (submenu items, form's fields, etc.)
			'alter_text'			=> '#999999',
			'alter_light'			=> '#aaaaaa',
			'alter_dark'			=> '#d0d0d0',
			'alter_link'			=> '#20c7ca',
			'alter_hover'			=> '#29fbff',
			'alter_bd_color'		=> '#909090',
			'alter_bd_hover'		=> '#888888',
			'alter_bg_color'		=> '#666666',
			'alter_bg_hover'		=> '#505050',
			'alter_bg_image'			=> '',
			'alter_bg_image_position'	=> 'left top',
			'alter_bg_image_repeat'		=> 'repeat',
			'alter_bg_image_attachment'	=> 'scroll',
			)
		);

		/* Font slugs:
		h1 ... h6	- headers
		p			- plain text
		link		- links
		info		- info blocks (Posted 15 May, 2015 by John Doe)
		menu		- main menu
		submenu		- dropdown menus
		logo		- logo text
		button		- button's caption
		input		- input fields
		*/

		// Add Custom fonts
		writer_ancora_add_custom_font('h1', array(
			'title'			=> esc_html__('Heading 1', 'writer-ancora'),
			'description'	=> '',
			'font-family'	=> 'Domine',
			'font-size' 	=> '4.688em',
			'font-weight'	=> '400',
			'font-style'	=> '',
			'line-height'	=> '1.3em',
			'margin-top'	=> '0.5em',
			'margin-bottom'	=> '0.4em'
			)
		);
		writer_ancora_add_custom_font('h2', array(
			'title'			=> esc_html__('Heading 2', 'writer-ancora'),
			'description'	=> '',
			'font-family'	=> 'Domine',
			'font-size' 	=> '3.125em',
			'font-weight'	=> '400',
			'font-style'	=> '',
			'line-height'	=> '1.3em',
			'margin-top'	=> '0.6667em',
			'margin-bottom'	=> '0.4em'
			)
		);
		writer_ancora_add_custom_font('h3', array(
			'title'			=> esc_html__('Heading 3', 'writer-ancora'),
			'description'	=> '',
			'font-family'	=> 'Domine',
			'font-size' 	=> '2.5em',
			'font-weight'	=> '400',
			'font-style'	=> '',
			'line-height'	=> '1.3em',
			'margin-top'	=> '0.6667em',
			'margin-bottom'	=> '0.4em'
			)
		);
		writer_ancora_add_custom_font('h4', array(
			'title'			=> esc_html__('Heading 4', 'writer-ancora'),
			'description'	=> '',
			'font-family'	=> 'Domine',
			'font-size' 	=> '1.75em',
			'font-weight'	=> '700',
			'font-style'	=> '',
			'line-height'	=> '1.3em',
			'margin-top'	=> '1.2em',
			'margin-bottom'	=> '0.6em'
			)
		);
		writer_ancora_add_custom_font('h5', array(
			'title'			=> esc_html__('Heading 5', 'writer-ancora'),
			'description'	=> '',
			'font-family'	=> 'Domine',
			'font-size' 	=> '1.563em',
			'font-weight'	=> '400',
			'font-style'	=> '',
			'line-height'	=> '1.3em',
			'margin-top'	=> '1.2em',
			'margin-bottom'	=> '0.5em'
			)
		);
		writer_ancora_add_custom_font('h6', array(
			'title'			=> esc_html__('Heading 6', 'writer-ancora'),
			'description'	=> '',
			'font-family'	=> 'Domine',
			'font-size' 	=> '1.25em',
			'font-weight'	=> '700',
			'font-style'	=> '',
			'line-height'	=> '1.3em',
			'margin-top'	=> '1.25em',
			'margin-bottom'	=> '0.65em'
			)
		);
		writer_ancora_add_custom_font('p', array(
			'title'			=> esc_html__('Text', 'writer-ancora'),
			'description'	=> '',
			'font-family'	=> 'Domine',
			'font-size' 	=> '16px',
			'font-weight'	=> '400',
			'font-style'	=> '',
			'line-height'	=> '1.66em',
			'margin-top'	=> '',
			'margin-bottom'	=> '1em'
			)
		);
		writer_ancora_add_custom_font('link', array(
			'title'			=> esc_html__('Links', 'writer-ancora'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '',
			'font-weight'	=> '',
			'font-style'	=> ''
			)
		);
		writer_ancora_add_custom_font('info', array(
			'title'			=> esc_html__('Post info', 'writer-ancora'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '',
			'font-weight'	=> '',
			'font-style'	=> 'i',
			'line-height'	=> '1.2857em',
			'margin-top'	=> '',
			'margin-bottom'	=> '1.5em'
			)
		);
		writer_ancora_add_custom_font('menu', array(
			'title'			=> esc_html__('Main menu items', 'writer-ancora'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '',
			'font-weight'	=> '',
			'font-style'	=> '',
			'line-height'	=> '1.2857em',
			'margin-top'	=> '1.8em',
			'margin-bottom'	=> '1.8em'
			)
		);
		writer_ancora_add_custom_font('submenu', array(
			'title'			=> esc_html__('Dropdown menu items', 'writer-ancora'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '',
			'font-weight'	=> '',
			'font-style'	=> '',
			'line-height'	=> '1.2857em',
			'margin-top'	=> '',
			'margin-bottom'	=> ''
			)
		);
		writer_ancora_add_custom_font('logo', array(
			'title'			=> esc_html__('Logo', 'writer-ancora'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '2.8571em',
			'font-weight'	=> '700',
			'font-style'	=> '',
			'line-height'	=> '0.75em',
			'margin-top'	=> '2.5em',
			'margin-bottom'	=> '2em'
			)
		);
		writer_ancora_add_custom_font('button', array(
			'title'			=> esc_html__('Buttons', 'writer-ancora'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '',
			'font-weight'	=> '',
			'font-style'	=> '',
			'line-height'	=> '1.2857em'
			)
		);
		writer_ancora_add_custom_font('input', array(
			'title'			=> esc_html__('Input fields', 'writer-ancora'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '',
			'font-weight'	=> '',
			'font-style'	=> '',
			'line-height'	=> '1.2857em'
			)
		);

	}
}





//------------------------------------------------------------------------------
// Skin's fonts
//------------------------------------------------------------------------------

// Add skin fonts in the used fonts list
if (!function_exists('writer_ancora_filter_skin_used_fonts')) {
	//add_filter('writer_ancora_filter_used_fonts', 'writer_ancora_filter_skin_used_fonts');
	function writer_ancora_filter_skin_used_fonts($theme_fonts) {
		//$theme_fonts['Roboto'] = 1;
		//$theme_fonts['Love Ya Like A Sister'] = 1;
		return $theme_fonts;
	}
}

// Add skin fonts (from Google fonts) in the main fonts list (if not present).
// To use custom font-face you not need add it into list in this function
// How to install custom @font-face fonts into the theme?
// All @font-face fonts are located in "theme_name/css/font-face/" folder in the separate subfolders for the each font. Subfolder name is a font-family name!
// Place full set of the font files (for each font style and weight) and css-file named stylesheet.css in the each subfolder.
// Create your @font-face kit by using Fontsquirrel @font-face Generator (http://www.fontsquirrel.com/fontface/generator)
// and then extract the font kit (with folder in the kit) into the "theme_name/css/font-face" folder to install
if (!function_exists('writer_ancora_filter_skin_list_fonts')) {
	//add_filter('writer_ancora_filter_list_fonts', 'writer_ancora_filter_skin_list_fonts');
	function writer_ancora_filter_skin_list_fonts($list) {
		// Example:
		if (!isset($list['Domine'])) {
				$list['Domine'] = array(
					'family' => 'serif',																						// (required) font family
					'link'   => 'Domine:400,700',	// (optional) if you use Google font repository
					);
		}
		if (!isset($list['Playfair Display'])) {
				$list['Playfair Display'] = array(
					'family' => 'serif',																						// (required) font family
					'link'   => 'Playfair+Display:400,400italic,700italic,700,900,900italic',	// (optional) if you use Google font repository
					);
		}
		if (!isset($list['Raleway'])) {
				$list['Raleway'] = array(
					'family' => 'sans-serif',																						// (required) font family
					'link'   => 'Raleway:400,400italic,500,500italic,600italic,700,600,700italic',	// (optional) if you use Google font repository
					);
		}
		
		if (!isset($list['Lato']))	$list['Lato'] = array('family'=>'sans-serif');
		if (!isset($list['Montserrat']))	$list['Montserrat'] = array('family'=>'sans-serif');


		return $list;
	}
}



//------------------------------------------------------------------------------
// Skin's stylesheets
//------------------------------------------------------------------------------
// Add skin stylesheets
if (!function_exists('writer_ancora_action_skin_add_styles')) {
	//add_action('writer_ancora_action_add_styles', 'writer_ancora_action_skin_add_styles');
	function writer_ancora_action_skin_add_styles() {
		// Add stylesheet files
		writer_ancora_enqueue_style( 'writer_ancora-skin-style', writer_ancora_get_file_url('skin.css'), array(), null );
		if (file_exists(writer_ancora_get_file_dir('skin.customizer.css')))
			writer_ancora_enqueue_style( 'writer_ancora-skin-customizer-style', writer_ancora_get_file_url('skin.customizer.css'), array(), null );
	}
}

// Add skin inline styles
if (!function_exists('writer_ancora_filter_skin_add_styles_inline')) {
	//add_filter('writer_ancora_filter_add_styles_inline', 'writer_ancora_filter_skin_add_styles_inline');
	function writer_ancora_filter_skin_add_styles_inline($custom_style) {
		// Todo: add skin specific styles in the $custom_style to override
		//       rules from style.css and shortcodes.css
		// Example:
		//		$scheme = writer_ancora_get_custom_option('body_scheme');
		//		if (empty($scheme)) $scheme = 'original';
		//		$clr = writer_ancora_get_scheme_color('accent1');
		//		if (!empty($clr)) {
		// 			$custom_style .= '
		//				a,
		//				.bg_tint_light a,
		//				.top_panel .content .search_wrap.search_style_regular .search_form_wrap .search_submit,
		//				.top_panel .content .search_wrap.search_style_regular .search_icon,
		//				.search_results .post_more,
		//				.search_results .search_results_close {
		//					color:'.esc_attr($clr).';
		//				}
		//			';
		//		}
		return $custom_style;	
	}
}

// Add skin responsive styles
if (!function_exists('writer_ancora_action_skin_add_responsive')) {
	//add_action('writer_ancora_action_add_responsive', 'writer_ancora_action_skin_add_responsive');
	function writer_ancora_action_skin_add_responsive() {
		$suffix = writer_ancora_param_is_off(writer_ancora_get_custom_option('show_sidebar_outer')) ? '' : '-outer';
		if (file_exists(writer_ancora_get_file_dir('skin.responsive'.($suffix).'.css'))) 
			writer_ancora_enqueue_style( 'theme-skin-responsive-style', writer_ancora_get_file_url('skin.responsive'.($suffix).'.css'), array(), null );
	}
}

// Add skin responsive inline styles
if (!function_exists('writer_ancora_filter_skin_add_responsive_inline')) {
	//add_filter('writer_ancora_filter_add_responsive_inline', 'writer_ancora_filter_skin_add_responsive_inline');
	function writer_ancora_filter_skin_add_responsive_inline($custom_style) {
		return $custom_style;	
	}
}

// Add skin.less into list files for compilation
if (!function_exists('writer_ancora_filter_skin_compile_less')) {
	//add_filter('writer_ancora_filter_compile_less', 'writer_ancora_filter_skin_compile_less');
	function writer_ancora_filter_skin_compile_less($files) {
		if (file_exists(writer_ancora_get_file_dir('skin.less'))) {
		 	$files[] = writer_ancora_get_file_dir('skin.less');
		}
		return $files;	
	}
}



//------------------------------------------------------------------------------
// Skin's scripts
//------------------------------------------------------------------------------

// Add skin scripts
if (!function_exists('writer_ancora_action_skin_add_scripts')) {
	//add_action('writer_ancora_action_add_scripts', 'writer_ancora_action_skin_add_scripts');
	function writer_ancora_action_skin_add_scripts() {
		if (file_exists(writer_ancora_get_file_dir('skin.js')))
			writer_ancora_enqueue_script( 'theme-skin-script', writer_ancora_get_file_url('skin.js'), array(), null );
		if (writer_ancora_get_theme_option('show_theme_customizer') == 'yes' && file_exists(writer_ancora_get_file_dir('skin.customizer.js')))
			writer_ancora_enqueue_script( 'theme-skin-customizer-script', writer_ancora_get_file_url('skin.customizer.js'), array(), null );
	}
}

// Add skin scripts inline
if (!function_exists('writer_ancora_action_skin_add_scripts_inline')) {
	//add_action('writer_ancora_action_add_scripts_inline', 'writer_ancora_action_skin_add_scripts_inline');
	function writer_ancora_action_skin_add_scripts_inline() {
		// Todo: add skin specific scripts
		// Example:
		// echo '<script type="text/javascript">'
		//	. 'jQuery(document).ready(function() {'
		//	. "if (WRITER_ANCORA_STORAGE['theme_font']=='') WRITER_ANCORA_STORAGE['theme_font'] = '" . writer_ancora_get_custom_font_settings('p', 'font-family') . "';"
		//	. "WRITER_ANCORA_STORAGE['theme_skin_color'] = '" . writer_ancora_get_scheme_color('accent1') . "';"
		//	. "});"
		//	. "< /script>";
	}
}
?>