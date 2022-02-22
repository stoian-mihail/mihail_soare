<?php
/**
 * Theme sprecific functions and definitions
 */


/* Theme setup section
------------------------------------------------------------------- */

// Set the content width based on the theme's design and stylesheet.
if ( ! isset( $content_width ) ) $content_width = 1170; /* pixels */

// Add theme specific actions and filters
// Attention! Function were add theme specific actions and filters handlers must have priority 1
if ( !function_exists( 'writer_ancora_theme_setup' ) ) {
	add_action( 'writer_ancora_action_before_init_theme', 'writer_ancora_theme_setup', 1 );
	function writer_ancora_theme_setup() {

		// Register theme menus
		add_filter( 'writer_ancora_filter_add_theme_menus',		'writer_ancora_add_theme_menus' );

		// Register theme sidebars
		add_filter( 'writer_ancora_filter_add_theme_sidebars',	'writer_ancora_add_theme_sidebars' );

		// Set options for importer
		add_filter( 'writer_ancora_filter_importer_options',		'writer_ancora_set_importer_options' );

		// Add theme specified classes into the body
		add_filter( 'body_class', 'writer_ancora_body_classes' );

		// Set list of the theme required plugins
		writer_ancora_storage_set('required_plugins', array(
			'essgrids',
			'instagram_feed',
			'revslider',
			'trx_utils',
			'visual_composer',
			'woocommerce'
			)
		);
		
	}
}


// Add/Remove theme nav menus
if ( !function_exists( 'writer_ancora_add_theme_menus' ) ) {
	function writer_ancora_add_theme_menus($menus) {
		//For example:
		//$menus['menu_footer'] = esc_html__('Footer Menu', 'writer-ancora');
		//if (isset($menus['menu_panel'])) unset($menus['menu_panel']);
		return $menus;
	}
}


// Add theme specific widgetized areas
if ( !function_exists( 'writer_ancora_add_theme_sidebars' ) ) {
	function writer_ancora_add_theme_sidebars($sidebars=array()) {
		if (is_array($sidebars)) {
			$theme_sidebars = array(
				'sidebar_main'		=> esc_html__( 'Main Sidebar', 'writer-ancora' ),
				'sidebar_footer'	=> esc_html__( 'Footer Sidebar', 'writer-ancora' )
			);
			if (function_exists('writer_ancora_exists_woocommerce') && writer_ancora_exists_woocommerce()) {
				$theme_sidebars['sidebar_cart']  = esc_html__( 'WooCommerce Cart Sidebar', 'writer-ancora' );
			}
			$sidebars = array_merge($theme_sidebars, $sidebars);
		}
		return $sidebars;
	}
}


// Add theme specified classes into the body
if ( !function_exists('writer_ancora_body_classes') ) {
	//add_filter( 'body_class', 'writer_ancora_body_classes' );
	function writer_ancora_body_classes( $classes ) {

		$classes[] = 'writer_ancora_body';
		$classes[] = 'body_style_' . trim(writer_ancora_get_custom_option('body_style'));
		$classes[] = 'body_' . (writer_ancora_get_custom_option('body_filled')=='yes' ? 'filled' : 'transparent');
		$classes[] = 'theme_skin_' . trim(writer_ancora_get_custom_option('theme_skin'));
		$classes[] = 'article_style_' . trim(writer_ancora_get_custom_option('article_style'));
		
		$blog_style = writer_ancora_get_custom_option(is_singular() && !writer_ancora_storage_get('blog_streampage') ? 'single_style' : 'blog_style');
		$classes[] = 'layout_' . trim($blog_style);
		$classes[] = 'template_' . trim(writer_ancora_get_template_name($blog_style));
		
		$body_scheme = writer_ancora_get_custom_option('body_scheme');
		if (empty($body_scheme)  || writer_ancora_is_inherit_option($body_scheme)) $body_scheme = 'original';
		$classes[] = 'scheme_' . $body_scheme;

		$top_panel_position = writer_ancora_get_custom_option('top_panel_position');
		if (!writer_ancora_param_is_off($top_panel_position)) {
			$classes[] = 'top_panel_show';
			$classes[] = 'top_panel_' . trim($top_panel_position);
		} else 
			$classes[] = 'top_panel_hide';
		$classes[] = writer_ancora_get_sidebar_class();

		if (writer_ancora_get_custom_option('show_video_bg')=='yes' && (writer_ancora_get_custom_option('video_bg_youtube_code')!='' || writer_ancora_get_custom_option('video_bg_url')!=''))
			$classes[] = 'video_bg_show';

		if (writer_ancora_get_theme_option('page_preloader')!='')
			$classes[] = 'preloader';

		return $classes;

	}
}


// Add theme specified classes into the body
if ( !function_exists('writer_ancora_body_wrap_classes') ) {
	function writer_ancora_body_wrap_classes() {
		$body_style  = writer_ancora_get_custom_option('body_style');
		$class = $style = '';
		if ($body_style=='boxed' || writer_ancora_get_custom_option('bg_image_load')=='always') {
			if (($img = (int) writer_ancora_get_custom_option('bg_image', 0)) > 0)
				$class = 'bg_image_'.($img);
			else if (($img = (int) writer_ancora_get_custom_option('bg_pattern', 0)) > 0)
				$class = 'bg_pattern_'.($img);
			else if (($img = writer_ancora_get_custom_option('bg_color', '')) != '')
				$style = 'background-color: '.($img).';';
			else if (writer_ancora_get_custom_option('bg_custom')=='yes') {
				if (($img = writer_ancora_get_custom_option('bg_image_custom')) != '')
					$style = 'background: url('.esc_url($img).') ' . str_replace('_', ' ', writer_ancora_get_custom_option('bg_image_custom_position')) . ' no-repeat fixed;';
				else if (($img = writer_ancora_get_custom_option('bg_pattern_custom')) != '')
					$style = 'background: url('.esc_url($img).') 0 0 repeat fixed;';
				else if (($img = writer_ancora_get_custom_option('bg_image')) > 0)
					$class = 'bg_image_'.($img);
				else if (($img = writer_ancora_get_custom_option('bg_pattern')) > 0)
					$class = 'bg_pattern_'.($img);
				if (($img = writer_ancora_get_custom_option('bg_color')) != '')
					$style .= 'background-color: '.($img).';';
			}
		}

		$body_classes = array('class' => $class, 'style' => $style );

		return $body_classes;

	}
}


// Add TOC items 'Home' and "To top"
if ( !function_exists('writer_ancora_toc_home') ) {
	function writer_ancora_toc_home( ) {
		if (writer_ancora_get_custom_option('menu_toc_home')=='yes')
				echo trim(writer_ancora_sc_anchor(array(
					'id' => "toc_home",
					'title' => esc_html__('Home', 'writer-ancora'),
					'description' => esc_html__('{{Return to Home}} - ||navigate to home page of the site', 'writer-ancora'),
					'icon' => "icon-home",
					'separator' => "yes",
					'url' => esc_url(home_url('/'))
					)
				)); 
		if (writer_ancora_get_custom_option('menu_toc_top')=='yes')
			echo trim(writer_ancora_sc_anchor(array(
				'id' => "toc_top",
				'title' => esc_html__('To Top', 'writer-ancora'),
				'description' => esc_html__('{{Back to top}} - ||scroll to top of the page', 'writer-ancora'),
				'icon' => "icon-double-up",
				'separator' => "yes")
				)); 
	}
}

// Set theme specific importer options
if ( !function_exists( 'writer_ancora_set_importer_options' ) ) {
	function writer_ancora_set_importer_options($options=array()) {
		if (is_array($options)) {
			$options['debug'] = writer_ancora_get_theme_option('debug_mode')=='yes';
			$options['domain_dev'] = esc_url('writer.dv.ancorathemes.com');
			$options['domain_demo'] = esc_url('writer.ancorathemes.com');
			$options['menus'] = array(
				'menu-main'	  => esc_html__('Main menu', 'writer-ancora'),
				'menu-user'	  => esc_html__('User menu', 'writer-ancora'),
				'menu-footer' => esc_html__('Footer menu', 'writer-ancora'),
				'menu-outer'  => esc_html__('Main menu', 'writer-ancora')
			);

		}
		return $options;
	}
}


/* Include framework core files
------------------------------------------------------------------- */
// If now is WP Heartbeat call - skip loading theme core files
if (!isset($_POST['action']) || $_POST['action']!="heartbeat") {
	require_once get_template_directory().'/fw/loader.php';
}
?>