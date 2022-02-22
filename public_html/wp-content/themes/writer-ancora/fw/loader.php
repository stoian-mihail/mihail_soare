<?php
/**
 * Writer Ancora Framework
 *
 * @package writer_ancora
 * @since writer_ancora 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Framework directory path from theme root
if ( ! defined( 'WRITER_ANCORA_FW_DIR' ) )			define( 'WRITER_ANCORA_FW_DIR', '/fw/' );

// Theme timing
if ( ! defined( 'WRITER_ANCORA_START_TIME' ) )		define( 'WRITER_ANCORA_START_TIME', microtime(true));		// Framework start time
if ( ! defined( 'WRITER_ANCORA_START_MEMORY' ) )		define( 'WRITER_ANCORA_START_MEMORY', memory_get_usage());	// Memory usage before core loading
if ( ! defined( 'WRITER_ANCORA_START_QUERIES' ) )	define( 'WRITER_ANCORA_START_QUERIES', get_num_queries());	// DB queries used

// Global variables storage
$WRITER_ANCORA_STORAGE = array(
	'options_prefix' => 'writer_ancora',	// Used as prefix for store theme's options in the post meta and wp options
	'page_template'	=> '',			// Storage for current page template name (used in the inheritance system)
	'widgets_args' => array(		// Arguments to register widgets
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h5 class="widget_title">',
		'after_title'   => '</h5>',
	),
    'allowed_tags'	=> array(		// Allowed tags list (with attributes) in translations
    	'p' => array(),
    	'br' => array(),
    	'b' => array(),
    	'strong' => array(),
    	'i' => array(),
    	'em' => array(),
    	'u' => array(),
    	'a' => array(
			'href' => array(),
			'title' => array(),
			'target' => array(),
			'id' => array(),
			'class' => array()
		),
    	'span' => array(
			'id' => array(),
			'class' => array()
		)
    )	
);

/* Theme setup section
-------------------------------------------------------------------- */
if ( !function_exists( 'writer_ancora_loader_theme_setup' ) ) {
	add_action( 'after_setup_theme', 'writer_ancora_loader_theme_setup', 20 );
	function writer_ancora_loader_theme_setup() {

		writer_ancora_profiler_add_point(esc_html__('After load theme required files', 'writer-ancora'));

		// Before init theme
		do_action('writer_ancora_action_before_init_theme');

		// Load current values for main theme options
		writer_ancora_load_main_options();

		// Theme core init - only for admin side. In frontend it called from header.php
		if ( is_admin() ) {
			writer_ancora_core_init_theme();
		}
	}
}


/* Include core parts
------------------------------------------------------------------------ */

// Manual load important libraries before load all rest files
// core.strings must be first - we use writer_ancora_str...() in the writer_ancora_get_file_dir()
require_once (file_exists(get_stylesheet_directory().(WRITER_ANCORA_FW_DIR).'core/core.strings.php') ? get_stylesheet_directory() : get_template_directory()).(WRITER_ANCORA_FW_DIR).'core/core.strings.php';
// core.files must be first - we use writer_ancora_get_file_dir() to include all rest parts
require_once (file_exists(get_stylesheet_directory().(WRITER_ANCORA_FW_DIR).'core/core.files.php') ? get_stylesheet_directory() : get_template_directory()).(WRITER_ANCORA_FW_DIR).'core/core.files.php';

// Include theme variables storage
require_once writer_ancora_get_file_dir('core/core.storage.php');

// Include debug and profiler
require_once writer_ancora_get_file_dir('core/core.debug.php');

// Include custom theme files
writer_ancora_autoload_folder( 'includes' );

// Include core files
writer_ancora_autoload_folder( 'core' );

// Include theme-specific plugins and post types
writer_ancora_autoload_folder( 'plugins' );

// Include theme templates
writer_ancora_autoload_folder( 'templates' );

// Include theme widgets
writer_ancora_autoload_folder( 'widgets' );
?>