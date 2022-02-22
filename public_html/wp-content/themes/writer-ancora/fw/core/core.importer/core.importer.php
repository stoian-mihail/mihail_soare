<?php
// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


// Theme init
if (!function_exists('writer_ancora_importer_theme_setup')) {
	add_action( 'writer_ancora_action_after_init_theme', 'writer_ancora_importer_theme_setup' );		// Fire this action after load theme options
	function writer_ancora_importer_theme_setup() {
		if (is_admin() && current_user_can('import') && writer_ancora_get_theme_option('admin_dummy_data')=='yes') {
			new writer_ancora_dummy_data_importer();
		}
	}
}

class writer_ancora_dummy_data_importer {

	// Theme specific settings
	var $options = array(
		'debug'					=> false,						// Enable debug output
		'posts_at_once'			=> 5,							// How many posts imported at one AJAX call
		'data_type'				=> 'vc',						// Default dummy data type
		'file_with_content'		=> array(
			'no_vc'				=> 'demo/dummy_data.xml',		// Name of the file with demo content without VC wrappers
			'vc'				=> 'demo/dummy_data_vc.xml'		// Name of the file with demo content for Visual Composer
			),
		'file_with_mods'		=> 'demo/theme_mods.txt',		// Name of the file with theme mods
		'file_with_options'		=> 'demo/theme_options.txt',	// Name of the file with theme options
		'file_with_templates'	=> 'demo/templates_options.txt',// Name of the file with templates options
		'file_with_widgets'		=> 'demo/widgets.txt',			// Name of the file with widgets data
		'uploads_folder'		=> 'imports',					// Folder with images on demo server
		'domain_dev'			=> '',							// Domain on developer's server. 											MUST BE SET IN THEME!
		'domain_demo'			=> '',							// Domain on demo-server.													MUST BE SET IN THEME!
		'taxonomies'			=> array(),						// List of required taxonomies: 'post_type' => 'taxonomy', ...				MUST BE SET OR CHANGED IN THEME!
		'plugins_initial_state'	=> 0,							// Initial state of the plugin's checkboxes: 1 - checked, 0 - unchecked		MUST BE SET OR CHANGED IN THEME!
		'additional_options'	=> array(						// Additional options slugs (for export plugins settings).					MUST BE SET OR CHANGED IN THEME!
			// WP
			'blogname',
			'blogdescription',
			'posts_per_page',
			'show_on_front',
			'page_on_front',
			'page_for_posts'
		)
	);

	var $error    = '';				// Error message
	var $success  = '';				// Success message
	var $result   = 0;				// Import posts percent (if break inside)
	
	var $last_slider = 0;			// Last imported slider number. 															MUST BE SET OR CHANGED IN THEME!

	var $export_mods = '';
	var $export_options = '';
	var $export_templates = '';
	var $export_widgets = '';
	var $uploads_url = '';
	var $uploads_dir = '';
	var $import_log = '';
	var $import_last_id = 0;
		
	var	$response = array(
			'action' => '',
			'error' => '',
			'result' => '100'
		);

	//-----------------------------------------------------------------------------------
	// Constuctor
	//-----------------------------------------------------------------------------------
	function __construct() {
	    $this->options = apply_filters('writer_ancora_filter_importer_options', $this->options);
		$uploads_info = wp_upload_dir();
		$this->uploads_dir = $uploads_info['basedir'];
		$this->uploads_url = $uploads_info['baseurl'];
		if ($this->options['debug']) define('IMPORT_DEBUG', true);
		$this->import_log = writer_ancora_get_file_dir('core/core.importer/importer.log');
		if (empty($this->import_log)) {
			$this->import_log = get_template_directory().'/fw/core/core.importer/importer.log';
			if (!file_exists($this->import_log)) writer_ancora_fpc($this->import_log, '');
		}
		$log = explode('|', writer_ancora_fgc($this->import_log));
		$this->import_last_id = (int) $log[0];
		$this->result = empty($log[1]) ? 0 : (int) $log[1];
		$this->last_slider = empty($log[2]) ? '' : $log[2];
		// Add menu item
		add_action('admin_menu', 					array($this, 'admin_menu_item'));
		// Add menu item
		add_action('admin_enqueue_scripts', 		array($this, 'admin_scripts'));
		// AJAX handler
		add_action('wp_ajax_writer_ancora_importer_start_import',		array($this, 'importer'));
		add_action('wp_ajax_nopriv_writer_ancora_importer_start_import',	array($this, 'importer'));
	}

	//-----------------------------------------------------------------------------------
	// Admin Interface
	//-----------------------------------------------------------------------------------
	
	// Add menu item
	function admin_menu_item() {
		if ( current_user_can( 'manage_options' ) ) {
			// Add in admin menu 'Theme Options'
			writer_ancora_admin_add_menu_item('theme', array(
				'page_title' => esc_html__('Install Dummy Data', 'writer-ancora'),
				'menu_title' => esc_html__('Install Dummy Data', 'writer-ancora'),
				'capability' => 'manage_options',
				'menu_slug'  => 'trx_importer',
				'callback'   => array($this, 'build_page'),
				'icon'		 => ''
				)
			);
		}
	}
	
	// Add script
	function admin_scripts() {
		writer_ancora_enqueue_style(  'writer_ancora-importer-style',  writer_ancora_get_file_url('core/core.importer/core.importer.css'), array(), null );
		writer_ancora_enqueue_script( 'writer_ancora-importer-script', writer_ancora_get_file_url('core/core.importer/core.importer.js'), array('jquery'), null, true );	
	}
	
	
	//-----------------------------------------------------------------------------------
	// Build the Main Page
	//-----------------------------------------------------------------------------------
	function build_page() {

		// Export data
		if ( isset($_POST['exporter_action']) ) {
			if ( !wp_verify_nonce( writer_ancora_get_value_gp('nonce'), admin_url() ) )
				$this->error = esc_html__('Incorrect WP-nonce data! Operation canceled!', 'writer-ancora');
			else
				$this->exporter();
		}
		?>

		<div class="trx_importer">
			<div class="trx_importer_section">
				<h2 class="trx_title"><?php esc_html_e('Writer Importer', 'writer-ancora'); ?></h2>
				<p><b><?php esc_html_e('Attention! Important info:', 'writer-ancora'); ?></b></p>
				<ol>
					<li><?php esc_html_e('Data import will replace all existing content - so you get a complete copy of our demo site', 'writer-ancora'); ?></li>
					<li><?php esc_html_e('Data import can take a long time (sometimes more than 10 minutes) - please wait until the end of the procedure, do not navigate away from the page.', 'writer-ancora'); ?></li>
					<li><?php esc_html_e('Web-servers set the time limit for the execution of php-scripts. Therefore, the import process will be split into parts. Upon completion of each part - the import will resume automatically!', 'writer-ancora'); ?></li>
				</ol>

				<form id="trx_importer_form">

					<p><b><?php esc_html_e('Select the data to import:', 'writer-ancora'); ?></b></p>

					<p>
					<?php
					$checked = 'checked="checked"';
					if (!empty($this->options['file_with_content']['vc']) && file_exists(writer_ancora_get_file_dir($this->options['file_with_content']['vc']))) {
						?>
						<input type="radio" <?php echo ('vc' == $this->options['data_type'] ? trim($checked) : ''); ?> value="vc" name="data_type" id="data_type_vc" /><label for="data_type_vc"><?php esc_html_e('Import data for edit in the Visual Composer', 'writer-ancora'); ?></label><br>
						<?php
						if ($this->options['data_type']=='vc') $checked = '';
					}
					if (!empty($this->options['file_with_content']['no_vc']) && file_exists(writer_ancora_get_file_dir($this->options['file_with_content']['no_vc']))) {
						?>
						<input type="radio" <?php echo ('no_vc'==$this->options['data_type'] || $checked ? trim($checked) : ''); ?> value="no_vc" name="data_type" id="data_type_no_vc" /><label for="data_type_no_vc"><?php esc_html_e('Import data without Visual Composer wrappers', 'writer-ancora'); ?></label>
						<?php
					}
					?>
					</p>

					<p>
					<input type="checkbox" checked="checked" value="1" name="import_posts" id="import_posts" /> <label for="import_posts"><?php esc_html_e('Import posts', 'writer-ancora'); ?></label><br>
					<span class="import_posts_params">
						<input type="radio" checked="checked" value="1" name="fetch_attachments" id="fetch_attachments_1" /> <label for="fetch_attachments_1"><?php esc_html_e('Upload attachments from demo-server', 'writer-ancora'); ?></label><br>
						<input type="radio" value="0" name="fetch_attachments" id="fetch_attachments_0" /> <label for="fetch_attachments_0"><?php esc_html_e('Leave existing attachments', 'writer-ancora'); ?></label>
					</span>
					</p>

					<p>
					<input type="checkbox" checked="checked" value="1" name="import_tm" id="import_tm" /> <label for="import_tm"><?php esc_html_e('Import Theme Mods', 'writer-ancora'); ?></label><br>
					<input type="checkbox" checked="checked" value="1" name="import_to" id="import_to" /> <label for="import_to"><?php esc_html_e('Import Theme Options', 'writer-ancora'); ?></label><br>
					<input type="checkbox" checked="checked" value="1" name="import_tpl" id="import_tpl" /> <label for="import_tpl"><?php esc_html_e('Import Templates Options', 'writer-ancora'); ?></label><br>
					<input type="checkbox" checked="checked" value="1" name="import_widgets" id="import_widgets" /> <label for="import_widgets"><?php esc_html_e('Import Widgets', 'writer-ancora'); ?></label><br><br>

					<?php do_action('writer_ancora_action_importer_params', $this); ?>
					</p>

					<div class="trx_buttons">
						<?php if ($this->import_last_id > 0 || !empty($this->last_slider)) { ?>
							<h4 class="trx_importer_complete"><?php sprintf(esc_html__('Import posts completed by %s', 'writer-ancora'), $this->result.'%'); ?></h4>
							<input type="button" value="<?php
								if ($this->import_last_id > 0)
									printf(esc_html__('Continue import (from ID=%s)', 'writer-ancora'), $this->import_last_id);
								else
									esc_html_e('Continue import sliders', 'writer-ancora');
								?>" data-last_id="<?php echo esc_attr($this->import_last_id); ?>" data-last_slider="<?php echo esc_attr($this->last_slider); ?>">
							<input type="button" value="<?php esc_attr_e('Start import again', 'writer-ancora'); ?>">
						<?php } else { ?>
							<input type="button" value="<?php esc_attr_e('Start import', 'writer-ancora'); ?>">
						<?php } ?>
					</div>

				</form>
				
				<div id="trx_importer_progress" class="notice notice-info style_<?php echo esc_attr(writer_ancora_get_theme_setting('admin_dummy_style')); ?>">
					<h4 class="trx_importer_progress_title"><?php esc_html_e('Import demo data', 'writer-ancora'); ?></h4>
					<table border="0" cellpadding="4" style="margin-bottom:2em;">
					<tr class="import_posts">
						<td class="import_progress_item"><?php esc_html_e('Posts', 'writer-ancora'); ?></td>
						<td class="import_progress_status"></td>
					</tr>
					<tr class="import_tm">
						<td class="import_progress_item"><?php esc_html_e('Theme Mods', 'writer-ancora'); ?></td>
						<td class="import_progress_status"></td>
					</tr>
					<tr class="import_to">
						<td class="import_progress_item"><?php esc_html_e('Theme Options', 'writer-ancora'); ?></td>
						<td class="import_progress_status"></td>
					</tr>
					<tr class="import_tpl">
						<td class="import_progress_item"><?php esc_html_e('Templates Options', 'writer-ancora'); ?></td>
						<td class="import_progress_status"></td>
					</tr>
					<tr class="import_widgets">
						<td class="import_progress_item"><?php esc_html_e('Widgets', 'writer-ancora'); ?></td>
						<td class="import_progress_status"></td>
					</tr>
					<?php do_action('writer_ancora_action_importer_import_fields', $this); ?>
					</table>
					<h4 class="trx_importer_progress_complete"><?php esc_html_e('Congratulations! Data import complete!', 'writer-ancora'); ?> <a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('View site', 'writer-ancora'); ?></a></h4>
				</div>
				
			</div>

			<div class="trx_exporter_section">
				<h2 class="trx_title"><?php esc_html_e('Writer Exporter', 'writer-ancora'); ?></h2>
				<form id="trx_exporter_form" action="#" method="post">

					<input type="hidden" value="<?php echo esc_attr(wp_create_nonce(admin_url())); ?>" name="nonce" />
					<input type="hidden" value="all" name="exporter_action" />

					<div class="trx_buttons">
						<?php if ($this->export_options!='') { ?>

							<table border="0" cellpadding="6">
							<tr>
								<th align="left"><?php esc_html_e('Theme Mods', 'writer-ancora'); ?></th>
								<td><?php writer_ancora_fpc(writer_ancora_get_file_dir('core/core.importer/export/theme_mods.txt'), $this->export_mods); ?>
									<a download="theme_mods.txt" href="<?php echo esc_url(writer_ancora_get_file_url('core/core.importer/export/theme_mods.txt')); ?>"><?php esc_html_e('Download', 'writer-ancora'); ?></a>
								</td>
							</tr>
							<tr>
								<th align="left"><?php esc_html_e('Theme Options', 'writer-ancora'); ?></th>
								<td><?php writer_ancora_fpc(writer_ancora_get_file_dir('core/core.importer/export/theme_options.txt'), $this->export_options); ?>
									<a download="theme_options.txt" href="<?php echo esc_url(writer_ancora_get_file_url('core/core.importer/export/theme_options.txt')); ?>"><?php esc_html_e('Download', 'writer-ancora'); ?></a>
								</td>
							</tr>
							<tr>
								<th align="left"><?php esc_html_e('Templates Options', 'writer-ancora'); ?></th>
								<td><?php writer_ancora_fpc(writer_ancora_get_file_dir('core/core.importer/export/templates_options.txt'), $this->export_templates); ?>
									<a download="templates_options.txt" href="<?php echo esc_url(writer_ancora_get_file_url('core/core.importer/export/templates_options.txt')); ?>"><?php esc_html_e('Download', 'writer-ancora'); ?></a>
								</td>
							</tr>
							<tr>
								<th align="left"><?php esc_html_e('Widgets', 'writer-ancora'); ?></th>
								<td><?php writer_ancora_fpc(writer_ancora_get_file_dir('core/core.importer/export/widgets.txt'), $this->export_widgets); ?>
									<a download="widgets.txt" href="<?php echo esc_url(writer_ancora_get_file_url('core/core.importer/export/widgets.txt')); ?>"><?php esc_html_e('Download', 'writer-ancora'); ?></a>
								</td>
							</tr>
							
							<?php do_action('writer_ancora_action_importer_export_fields', $this); ?>

							</table>

						<?php } else { ?>

							<input type="submit" value="<?php esc_attr_e('Export Theme Options', 'writer-ancora'); ?>">

						<?php } ?>
					</div>

				</form>
			</div>
		</div>
		<?php
	}

	// Check for required plugings
	function check_required_plugins($list='') {
		$not_installed = '';
		if (in_array('trx_utils', writer_ancora_storage_get('required_plugins')) && !defined('TRX_UTILS_VERSION') )
			$not_installed .= 'Writer Ancora Utilities';
		$not_installed = apply_filters('writer_ancora_filter_importer_required_plugins', $not_installed, $list);
		if ($not_installed) {
			$this->error = '<b>'.esc_html__('Attention! For correct installation of the selected demo data, you must install and activate the following plugins: ', 'writer-ancora').'</b><br>'.($not_installed);
			return false;
		}
		return true;
	}
	
	
	//-----------------------------------------------------------------------------------
	// Export dummy data
	//-----------------------------------------------------------------------------------
	function exporter() {
		global $wpdb;
		$suppress = $wpdb->suppress_errors();

		// Export theme mods
		$this->export_mods = serialize($this->prepare_data(get_theme_mods()));

		// Export theme, templates and categories options and VC templates
		$rows = $wpdb->get_results( "SELECT option_name, option_value FROM " . esc_sql($wpdb->options) . " WHERE option_name LIKE '" . esc_sql(writer_ancora_storage_get('options_prefix')) . "_options%'" );
		$options = array();
		if (is_array($rows) && count($rows) > 0) {
			foreach ($rows as $row) {
				$options[$row->option_name] = writer_ancora_unserialize($row->option_value);
			}
		}
		// Export additional options
		if (is_array($this->options['additional_options']) && count($this->options['additional_options']) > 0) {
			foreach ($this->options['additional_options'] as $opt) {
				$rows = $wpdb->get_results( "SELECT option_name, option_value FROM " . esc_sql($wpdb->options) . " WHERE option_name LIKE '" . esc_sql($opt) . "'" );
				if (is_array($rows) && count($rows) > 0) {
					foreach ($rows as $row) {
						$options[$row->option_name] = writer_ancora_unserialize($row->option_value);
					}
				}
			}
		}
		$this->export_options = serialize($this->prepare_data($options));

		// Export templates options
		$rows = $wpdb->get_results( "SELECT option_name, option_value FROM " . esc_sql($wpdb->options) . " WHERE option_name LIKE '".esc_sql(writer_ancora_storage_get('options_prefix'))."_options_template_%'" );
		$options = array();
		if (is_array($rows) && count($rows) > 0) {
			foreach ($rows as $row) {
				$options[$row->option_name] = writer_ancora_unserialize($row->option_value);
			}
		}
		$this->export_templates = serialize($this->prepare_data($options));

		// Export widgets
		$rows = $wpdb->get_results( "SELECT option_name, option_value FROM " . esc_sql($wpdb->options) . " WHERE option_name = 'sidebars_widgets' OR option_name LIKE 'widget_%'" );
		$options = array();
		if (is_array($rows) && count($rows) > 0) {
			foreach ($rows as $row) {
				$options[$row->option_name] = writer_ancora_unserialize($row->option_value);
			}
		}
		$this->export_widgets = serialize($this->prepare_data($options));

		// Export Theme specific post types
		do_action('writer_ancora_action_importer_export', $this);

		$wpdb->suppress_errors( $suppress );
	}
	
	
	//-----------------------------------------------------------------------------------
	// Export specified table
	//-----------------------------------------------------------------------------------
	function export_dump($table) {
		global $wpdb;
		$rows = array();
		if ( count($wpdb->get_results( "SHOW TABLES LIKE '".esc_sql($wpdb->prefix . $table)."'", ARRAY_A )) == 1 ) {
			$rows = $this->prepare_data( $wpdb->get_results( "SELECT * FROM ".esc_sql($wpdb->prefix . $table), ARRAY_A ) );
		}
		return $rows;
	}
	
	
	//-----------------------------------------------------------------------------------
	// Import dummy data
	//-----------------------------------------------------------------------------------
	//add_action('wp_ajax_writer_ancora_importer_start_import',			array($this, 'importer'));
	//add_action('wp_ajax_nopriv_writer_ancora_importer_start_import',	array($this, 'importer'));
	function importer() {

		if ($this->options['debug']) dfl(esc_html__('AJAX handler for importer', 'writer-ancora'));

		if ( !isset($_POST['importer_action']) || !wp_verify_nonce( writer_ancora_get_value_gp('ajax_nonce'), admin_url('admin-ajax.php') ) )
			die();

		$action = $this->response['action'] = $_POST['importer_action'];

		if ($this->options['debug']) dfl( sprintf(esc_html__('Dispatch action: %s', 'writer-ancora'), $action) );
		
		global $wpdb;
		$suppress = $wpdb->suppress_errors();

		ob_start();

		// Start import - clear tables, etc.
		if ($action == 'import_start') {
			if (!$this->check_required_plugins($_POST['clear_tables']))
				$this->response['error'] = $this->error;
			else
				if (!empty($_POST['clear_tables'])) $this->clear_tables();

		// Import posts
		} else if ($action == 'import_posts') {
			$result = $this->import_posts();
			if ($result >= 100) do_action('writer_ancora_action_importer_after_import_posts', $this);
			$this->response['result'] = $result;

		// Import Theme Mods
		} else if ($action == 'import_tm') {
			$this->import_theme_mods();

		// Import Theme Options
		} else if ($action == 'import_to') {
			$this->import_theme_options();

		// Import Templates Options
		} else if ($action == 'import_tpl') {
			$this->import_templates_options();

		// Import Widgets
		} else if ($action == 'import_widgets') {
			$this->import_widgets();

		// End import - clear cache, flush rules, etc.
		} else if ($action == 'import_end') {
			writer_ancora_clear_cache('all');
			flush_rewrite_rules();

		// Import Theme specific posts
		} else {
			do_action('writer_ancora_action_importer_import', $this, $action);
		}

		ob_end_clean();

		$wpdb->suppress_errors($suppress);

		if ($this->options['debug']) dfl( sprintf(esc_html__('AJAX handler finished - send results to client', 'writer-ancora'), $action) );

		echo json_encode($this->response);
		die();
	}


	// Import XML file with posts data
	function import_posts() {
		// Load WP Importer class
		if ($this->options['debug']) dfl(esc_html__('Start import posts', 'writer-ancora'));
		if ( !defined('WP_LOAD_IMPORTERS') ) define('WP_LOAD_IMPORTERS', true); // we are loading importers
		if ( !class_exists('WP_Import') ) {
			require writer_ancora_get_file_dir('core/core.importer/wordpress-importer.php');
		}
		if ( class_exists( 'WP_Import' ) ) {
			$theme_xml = writer_ancora_get_file_dir($this->options['file_with_content'][$_POST['data_type']=='vc' ? 'vc' : 'no_vc']);
			$importer = new WP_Import();
			$importer->debug = $this->options['debug'];
			$importer->posts_at_once = $this->options['posts_at_once'];
			$importer->fetch_attachments = isset($_POST['fetch_attachments']) && $_POST['fetch_attachments']==1;
			$importer->uploads_folder = $this->options['uploads_folder'];
			$importer->demo_url = 'http://' . $this->options['domain_demo'] . '/';
			$importer->start_from_id = (int) $_POST['last_id'] > 0 ? $this->import_last_id : 0;
			$importer->import_log = $this->import_log;
			$this->prepare_taxonomies();
			$result = $importer->import($theme_xml);
			if ($result>=100) writer_ancora_fpc($this->import_log, '');
		}
		return $result;
	}
	
	
	// Delete all data from tables
	function clear_tables() {
		global $wpdb;
		if (writer_ancora_strpos($_POST['clear_tables'], 'posts')!==false && $this->import_last_id==0) {
			if ($this->options['debug']) dfl( esc_html__('Clear posts tables', 'writer-ancora') );
			$res = $wpdb->query("TRUNCATE TABLE " . esc_sql($wpdb->comments));
			if ( is_wp_error( $res ) ) dfl( esc_html__( 'Failed truncate table "comments".', 'writer-ancora' ) . ' ' . ($res->get_error_message()) );
			$res = $wpdb->query("TRUNCATE TABLE " . esc_sql($wpdb->commentmeta));
			if ( is_wp_error( $res ) ) dfl( esc_html__( 'Failed truncate table "commentmeta".', 'writer-ancora' ) . ' ' . ($res->get_error_message()) );
			$res = $wpdb->query("TRUNCATE TABLE " . esc_sql($wpdb->postmeta));
			if ( is_wp_error( $res ) ) dfl( esc_html__( 'Failed truncate table "postmeta".', 'writer-ancora' ) . ' ' . ($res->get_error_message()) );
			$res = $wpdb->query("TRUNCATE TABLE " . esc_sql($wpdb->posts));
			if ( is_wp_error( $res ) ) dfl( esc_html__( 'Failed truncate table "posts".', 'writer-ancora' ) . ' ' . ($res->get_error_message()) );
			$res = $wpdb->query("TRUNCATE TABLE " . esc_sql($wpdb->terms));
			if ( is_wp_error( $res ) ) dfl( esc_html__( 'Failed truncate table "terms".', 'writer-ancora' ) . ' ' . ($res->get_error_message()) );
			$res = $wpdb->query("TRUNCATE TABLE " . esc_sql($wpdb->term_relationships));
			if ( is_wp_error( $res ) ) dfl( esc_html__( 'Failed truncate table "term_relationships".', 'writer-ancora' ) . ' ' . ($res->get_error_message()) );
			$res = $wpdb->query("TRUNCATE TABLE " . esc_sql($wpdb->term_taxonomy));
			if ( is_wp_error( $res ) ) dfl( esc_html__( 'Failed truncate table "term_taxonomy".', 'writer-ancora' ) . ' ' . ($res->get_error_message()) );
		}
		do_action('writer_ancora_action_importer_clear_tables', $this, $_POST['clear_tables']);
	}

	
	// Prepare additional taxes
	function prepare_taxonomies() {
		if ($this->options['debug']) dfl(esc_html__('Create custom taxonomies', 'writer-ancora'));
		if (isset($this->options['taxonomies']) && is_array($this->options['taxonomies']) && count($this->options['taxonomies']) > 0) {
			foreach ($this->options['taxonomies'] as $type=>$tax) {
				writer_ancora_theme_support( 'taxonomy', $tax, array(
					'post_type'			=> array( $type ),
					'hierarchical'		=> false,
					'query_var'			=> $tax,
					'rewrite'			=> true,
					'public'			=> false,
					'show_ui'			=> false,
					'show_admin_column'	=> false,
					'_builtin'			=> false
					)
				);
			}
		}
	}


	// Import theme mods
	function import_theme_mods() {
		if (empty($this->options['file_with_mods'])) return;
		if ($this->options['debug']) dfl(esc_html__('Import Theme Mods', 'writer-ancora'));
		$txt = writer_ancora_fgc(writer_ancora_get_file_dir($this->options['file_with_mods']));
		$data = writer_ancora_unserialize($txt);
		// Replace upload url in options
		if (is_array($data) && count($data) > 0) {
			foreach ($data as $k=>$v) {
				if (is_array($v) && count($v) > 0) {
					foreach ($v as $k1=>$v1) {
						$v[$k1] = $this->replace_uploads($v1);
					}
				} else
					$v = $this->replace_uploads($v);
			}
			$theme = get_option( 'stylesheet' );
			update_option( "theme_mods_$theme", $data );
		}
	}


	// Import theme options
	function import_theme_options() {
		if (empty($this->options['file_with_options'])) return;
		if ($this->options['debug']) dfl(esc_html__('Reset Theme Options', 'writer-ancora'));
		writer_ancora_options_reset();
		if ($this->options['debug']) dfl(esc_html__('Import Theme Options', 'writer-ancora'));
		$txt = writer_ancora_fgc(writer_ancora_get_file_dir($this->options['file_with_options']));
		$data = writer_ancora_unserialize($txt);
		// Replace upload url in options
		if (is_array($data) && count($data) > 0) {
			foreach ($data as $k=>$v) {
				if (is_array($v) && count($v) > 0) {
					foreach ($v as $k1=>$v1) {
						$v[$k1] = $this->replace_uploads($v1);
					}
				} else
					$v = $this->replace_uploads($v);
				if ($k == 'mega_main_menu_options' && isset($v['last_modified']))
					$v['last_modified'] = time()+30;
				update_option( $k, $v );
			}
		}
		writer_ancora_load_main_options();
	}


	// Import templates options
	function import_templates_options() {
		if (empty($this->options['file_with_templates'])) return;
		if ($this->options['debug']) dfl(esc_html__('Import Templates Options', 'writer-ancora'));
		$txt = writer_ancora_fgc(writer_ancora_get_file_dir($this->options['file_with_templates']));
		$data = writer_ancora_unserialize($txt);
		// Replace upload url in options
		if (is_array($data) && count($data) > 0) {
			foreach ($data as $k=>$v) {
				if (is_array($v) && count($v) > 0) {
					foreach ($v as $k1=>$v1) {
						$v[$k1] = $this->replace_uploads($v1);
					}
				} else
					$v = $this->replace_uploads($v);
				update_option( $k, $v );
			}
		}
	}


	// Import widgets
	function import_widgets() {
		if (empty($this->options['file_with_widgets'])) return;
		if ($this->options['debug']) dfl(esc_html__('Import Widgets', 'writer-ancora'));
		$txt = writer_ancora_fgc(writer_ancora_get_file_dir($this->options['file_with_widgets']));
		$data = writer_ancora_unserialize($txt);
		if (is_array($data) && count($data) > 0) {
			foreach ($data as $k=>$v) {
				update_option( $k, $this->replace_uploads($v) );
			}
		}
	}


	// Import any SQL dump
	function import_dump($slug, $title) {
		if (empty($this->options['file_with_'.$slug])) return;
		if ($this->options['debug']) dfl(sprintf(esc_html__('Import dump of "%s"', 'writer-ancora'), $title));
		$txt = writer_ancora_fgc(writer_ancora_get_file_dir($this->options['file_with_'.$slug]));
		$data = writer_ancora_unserialize($txt);
		if (is_array($data) && count($data) > 0) {
			global $wpdb;
			foreach ($data as $table=>$rows) {
				// Clear table, if it is not 'users' or 'usermeta'
				if (!in_array($table, array('users', 'usermeta')))
					$res = $wpdb->query("TRUNCATE TABLE " . esc_sql($wpdb->prefix . $table));
				$values = $fields = '';
				if (is_array($rows) && count($rows) > 0) {
					foreach ($rows as $row) {
						$f = '';
						$v = '';
						if (is_array($row) && count($row) > 0) {
							foreach ($row as $field => $value) {
								$f .= ($f ? ',' : '') . "'" . esc_sql($field) . "'";
								$v .= ($v ? ',' : '') . "'" . esc_sql($value) . "'";
							}
						}
						if ($fields == '') $fields = '(' . $f . ')';
						$values .= ($values ? ',' : '') . '(' . $v . ')';
						// If query length exceed 64K - run query, because MySQL not accept long query string
						// If current table 'users' or 'usermeta' - run queries row by row, because we append data
						if (writer_ancora_strlen($values) > 64000 || in_array($table, array('users', 'usermeta'))) {
							// Attention! All items in the variable $values escaped on the loop above - esc_sql($value)
							$q = "INSERT INTO ".esc_sql($wpdb->prefix . $table)." VALUES {$values}";
							$wpdb->query($q);
							$values = $fields = '';
						}
					}
				}
				if (!empty($values)) {
					// Attention! All items in the variable $values escaped on the loop above - esc_sql($value)
					$q = "INSERT INTO ".esc_sql($wpdb->prefix . $table)." VALUES {$values}";
					$wpdb->query($q);
				}
			}
		}
	}

	
	// Replace uploads dir to new url
	function replace_uploads($str) {
		return writer_ancora_replace_uploads_url($str, $this->options['uploads_folder']);
	}

	
	// Replace strings then export data
	function prepare_data($str) {
		if (is_array($str) && count($str) > 0) {
			foreach ($str as $k=>$v) {
				$str[$k] = $this->prepare_data($v);
			}
		} else if (is_string($str)) {
			// Replace '/uploads/' to '/imports/'
			if ($this->options['uploads_folder']!='uploads') 					$str = str_replace('/uploads/', "/{$this->options['uploads_folder']}/", $str);
			// Replace developers domain to demo domain
			if ($this->options['domain_dev']!=$this->options['domain_demo'])	$str = str_replace($this->options['domain_dev'], $this->options['domain_demo'], $str);
			// Replace DOS-style line endings to UNIX-style
			$str = str_replace("\r\n", "\n", $str);
		}
		return $str;
	}
}
?>