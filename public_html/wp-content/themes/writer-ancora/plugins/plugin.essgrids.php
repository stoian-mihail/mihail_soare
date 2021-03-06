<?php
/* Essential Grid support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('writer_ancora_essgrids_theme_setup')) {
	add_action( 'writer_ancora_action_before_init_theme', 'writer_ancora_essgrids_theme_setup', 1 );
	function writer_ancora_essgrids_theme_setup() {
		// Register shortcode in the shortcodes list
		if (writer_ancora_exists_essgrids()) {
			if (is_admin()) {
				add_filter( 'writer_ancora_filter_importer_options',			'writer_ancora_essgrids_importer_set_options', 10, 1 );
				add_action( 'writer_ancora_action_importer_params',			'writer_ancora_essgrids_importer_show_params', 10, 1 );
				add_action( 'writer_ancora_action_importer_import',			'writer_ancora_essgrids_importer_import', 10, 2 );
				add_action( 'writer_ancora_action_importer_import_fields',	'writer_ancora_essgrids_importer_import_fields', 10, 1 );
			}
		}
		if (is_admin()) {
			add_filter( 'writer_ancora_filter_importer_required_plugins',	'writer_ancora_essgrids_importer_required_plugins', 10, 2 );
			add_filter( 'writer_ancora_filter_required_plugins',				'writer_ancora_essgrids_required_plugins' );
		}
	}
}


// Check if Ess. Grid installed and activated
if ( !function_exists( 'writer_ancora_exists_essgrids' ) ) {
	function writer_ancora_exists_essgrids() {
		return defined('EG_PLUGIN_PATH');
		//return class_exists('Essential_Grid');
		//return is_plugin_active('essential-grid/essential-grid.php');
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'writer_ancora_essgrids_required_plugins' ) ) {
	//add_filter('writer_ancora_filter_required_plugins',	'writer_ancora_essgrids_required_plugins');
	function writer_ancora_essgrids_required_plugins($list=array()) {
		if (in_array('essgrids', writer_ancora_storage_get('required_plugins')))
			$list[] = array(
					'name' 		=> 'Essential Grid',
					'slug' 		=> 'essential-grid',
					'source'	=> writer_ancora_get_file_dir('plugins/install/essential-grid.zip'),
					'required' 	=> false
					);
		return $list;
	}
}



// One-click import support
//------------------------------------------------------------------------

// Check in the required plugins
if ( !function_exists( 'writer_ancora_essgrids_importer_required_plugins' ) ) {
	//add_filter( 'writer_ancora_filter_importer_required_plugins',	'writer_ancora_essgrids_importer_required_plugins', 10, 2 );
	function writer_ancora_essgrids_importer_required_plugins($not_installed='', $list='') {
		//if (in_array('essgrids', writer_ancora_storage_get('required_plugins')) && !writer_ancora_exists_essgrids() )
		if (writer_ancora_strpos($list, 'essgrids')!==false && !writer_ancora_exists_essgrids() )
			$not_installed .= '<br>Essential Grids';
		return $not_installed;
	}
}

// Set options for one-click importer
if ( !function_exists( 'writer_ancora_essgrids_importer_set_options' ) ) {
	//add_filter( 'writer_ancora_filter_importer_options',	'writer_ancora_essgrids_importer_set_options', 10, 1 );
	function writer_ancora_essgrids_importer_set_options($options=array()) {
		if ( in_array('essgrids', writer_ancora_storage_get('required_plugins')) && writer_ancora_exists_essgrids() ) {
			$options['folder_with_essgrids'] = 'demo/essgrids';			// Name of the folder with Essential Grids
		}
		return $options;
	}
}

// Add checkbox to the one-click importer
if ( !function_exists( 'writer_ancora_essgrids_importer_show_params' ) ) {
	//add_action( 'writer_ancora_action_importer_params',	'writer_ancora_essgrids_importer_show_params', 10, 1 );
	function writer_ancora_essgrids_importer_show_params($importer) {
		?>
		<input type="checkbox" <?php echo in_array('essgrids', writer_ancora_storage_get('required_plugins')) && $importer->options['plugins_initial_state'] 
											? 'checked="checked"' 
											: ''; ?> value="1" name="import_essgrids" id="import_essgrids" /> <label for="import_essgrids"><?php esc_html_e('Import Ess.Grids', 'writer-ancora'); ?></label><br>
		<?php
	}
}

// Import posts
if ( !function_exists( 'writer_ancora_essgrids_importer_import' ) ) {
	//add_action( 'writer_ancora_action_importer_import',	'writer_ancora_essgrids_importer_import', 10, 2 );
	function writer_ancora_essgrids_importer_import($importer, $action) {
		if ( $action == 'import_essgrids' ) {
			$dir = writer_ancora_get_folder_dir($importer->options['folder_with_essgrids']);
			if ( is_dir($dir) ) {
				$hdir = @opendir( $dir );
				if ( $hdir ) {
					if ($importer->options['debug']) dfl( esc_html__('Import Essential Grid', 'writer-ancora') );
					while (($file = readdir($hdir)) !== false ) {
						$pi = pathinfo( ($dir) . '/' . ($file) );
						if ( substr($file, 0, 1) == '.' || is_dir( ($dir) . '/' . ($file) ) || $pi['extension']!='json' )
							continue;
						if ($importer->options['debug']) dfl( sprintf(esc_html__('Process file "%s":', 'writer-ancora'), $file) );
						try{
							$im = new Essential_Grid_Import();
							$data = json_decode(writer_ancora_fgc(($dir) . '/' . ($file)), true);
							// Prepare arrays with overwrite flags
							$tmp = array();
							if (is_array($data) && count($data) > 0) {
								foreach ($data as $k=>$v) {
									if ($k=='grids') {			$name = 'grids'; $name_1= 'grid'; $name_id='id'; }
									else if ($k=='skins') {		$name = 'skins'; $name_1= 'skin'; $name_id='id'; }
									else if ($k=='elements') {	$name = 'elements'; $name_1= 'element'; $name_id='id'; }
									else if ($k=='navigation-skins') {	$name = 'navigation-skins'; $name1= 'nav-skin'; $name_id='id'; }
									else if ($k=='punch-fonts') {	$name = 'punch-fonts'; $name1= 'punch-fonts'; $name_id='handle'; }
									else if ($k=='custom-meta') {	$name = 'custom-meta'; $name1= 'custom-meta'; $name_id='handle'; }
									if ($k=='global-css') {
										$tmp['import-global-styles'] = "on";
										$tmp['global-styles-overwrite'] = "append";
									} else {
										$tmp['import-'.$name] = "true";
										$tmp['import-'.$name.'-'.$name_id] = array();
										if (is_array($v) && count($v) > 0) {
											foreach ($v as $v1) {
												$tmp['import-'.$name.'-'.$name_id][] = $v1[$name_id];
												$tmp[$name_1.'-overwrite-'.$name_id] = 'append';
											}
										}
									}
								}
							}
							$im->set_overwrite_data($tmp); //set overwrite data global to class
							
							$skins = @$data['skins'];
							if (!empty($skins) && is_array($skins)){
								$skins_ids = @$tmp['import-skins-id'];
								$skins_imported = $im->import_skins($skins, $skins_ids);
							}
							
							$navigation_skins = @$data['navigation-skins'];
							if (!empty($navigation_skins) && is_array($navigation_skins)){
								$navigation_skins_ids = @$tmp['import-navigation-skins-id'];
								$navigation_skins_imported = $im->import_navigation_skins(@$navigation_skins, $navigation_skins_ids);
							}
							
							$grids = @$data['grids'];
							if (!empty($grids) && is_array($grids)){
								$grids_ids = @$tmp['import-grids-id'];
								$grids_imported = $im->import_grids($grids, $grids_ids);
							}
							
							$elements = @$data['elements'];
							if (!empty($elements) && is_array($elements)){
								$elements_ids = @$tmp['import-elements-id'];
								$elements_imported = $im->import_elements(@$elements, $elements_ids);
							}
							
							$custom_metas = @$data['custom-meta'];
							if (!empty($custom_metas) && is_array($custom_metas)){
								$custom_metas_handle = @$tmp['import-custom-meta-handle'];
								$custom_metas_imported = $im->import_custom_meta($custom_metas, $custom_metas_handle);
							}
							
							$custom_fonts = @$data['punch-fonts'];
							if (!empty($custom_fonts) && is_array($custom_fonts)){
								$custom_fonts_handle = @$tmp['import-punch-fonts-handle'];
								$custom_fonts_imported = $im->import_punch_fonts($custom_fonts, $custom_fonts_handle);
							}
							
							if (@$tmp['import-global-styles'] == 'on'){
								$global_css = @$data['global-css'];
								$global_styles_imported = $im->import_global_styles($tglobal_css);
							}

							if ($importer->options['debug']) dfl( esc_html__('Essential Grid import complete', 'writer-ancora') );
							
						} catch (Exception $d) {
							
							$msg = sprintf(esc_html__('Essential Grid import error: %s', 'writer-ancora'), $d->getMessage());
							$importer->response['error'] = $msg;
							dfl( $msg );

						}
						
						break;
					}
					@closedir( $hdir );
				}
			}
		}
	}
}

// Display import progress
if ( !function_exists( 'writer_ancora_essgrids_importer_import_fields' ) ) {
	//add_action( 'writer_ancora_action_importer_import_fields',	'writer_ancora_essgrids_importer_import_fields', 10, 1 );
	function writer_ancora_essgrids_importer_import_fields($importer) {
		?>
		<tr class="import_essgrids">
			<td class="import_progress_item"><?php esc_html_e('Essential Grid', 'writer-ancora'); ?></td>
			<td class="import_progress_status"></td>
		</tr>
		<?php
	}
}
?>