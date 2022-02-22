<?php
if(!class_exists('WP_List_Table')){
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}
class google_analytics_master_admin_settings_table extends WP_List_Table {
	/**
	 * Display the rows of records in the table
	 * @return string, echo the markup of the rows
	 */
	function display() {
if ( $_POST) {
	if(is_multisite()){
		if ( isset($_POST['google_analytics_master_code_id']) ){
				update_site_option('google_analytics_master_code_id', $_POST['google_analytics_master_code_id'] );
			}
			else{
				update_site_option('google_analytics_master_code_id', '' );
			}
			if ( isset($_POST['google_analytics_master_activate_script']) ){
				update_site_option('google_analytics_master_activate_script', $_POST['google_analytics_master_activate_script'] );
			}
			else{
				update_site_option('google_analytics_master_activate_script', 'false' );
			}
			if ( isset($_POST['google_analytics_master_script_id']) ){
				update_site_option('google_analytics_master_script_id', $_POST['google_analytics_master_script_id'] );
			}
			else{
				update_site_option('google_analytics_master_script_id', '' );
			}
			if ( isset($_POST['google_analytics_master_activate_footer']) ){
				update_site_option('google_analytics_master_activate_footer', $_POST['google_analytics_master_activate_footer'] );
			}
			else{
				update_site_option('google_analytics_master_activate_footer', 'false' );
			}
			if ( isset($_POST['google_analytics_master_client_id']) ){
				update_site_option('google_analytics_master_client_id', $_POST['google_analytics_master_client_id'] );
			}
			else{
				update_site_option('google_analytics_master_client_id', 'false' );
			}
			if ( isset($_POST['google_analytics_master_admin_bar']) ){
				update_site_option('google_analytics_master_admin_bar', $_POST['google_analytics_master_admin_bar'] );
			}
			else{
				update_site_option('google_analytics_master_admin_bar', '' );
			}
	}
	else{
		if ( isset($_POST['google_analytics_master_code_id']) ){
				update_option('google_analytics_master_code_id', $_POST['google_analytics_master_code_id'] );
			}
			else{
				update_option('google_analytics_master_code_id', '' );
			}
			if ( isset($_POST['google_analytics_master_activate_script']) ){
				update_option('google_analytics_master_activate_script', $_POST['google_analytics_master_activate_script'] );
			}
			else{
				update_option('google_analytics_master_activate_script', 'false' );
			}
			if ( isset($_POST['google_analytics_master_script_id']) ){
				update_option('google_analytics_master_script_id', $_POST['google_analytics_master_script_id'] );
			}
			else{
				update_option('google_analytics_master_script_id', '' );
			}
			if ( isset($_POST['google_analytics_master_activate_footer']) ){
				update_option('google_analytics_master_activate_footer', $_POST['google_analytics_master_activate_footer'] );
			}
			else{
				update_option('google_analytics_master_activate_footer', 'false' );
			}
			if ( isset($_POST['google_analytics_master_client_id']) ){
				update_option('google_analytics_master_client_id', $_POST['google_analytics_master_client_id'] );
			}
			else{
				update_option('google_analytics_master_client_id', 'false' );
			}
			if ( isset($_POST['google_analytics_master_admin_bar']) ){
				update_option('google_analytics_master_admin_bar', $_POST['google_analytics_master_admin_bar'] );
			}
			else{
				update_option('google_analytics_master_admin_bar', '' );
			}
	}
?>
<div id="message" class="updated fade">
<p><strong><?php _e('Settings Saved!', 'google_analytics_master'); ?></strong></p>
</div>
<?php
}
?>
<form method="post" width='1'>
<fieldset class="options">

<table class="widefat" cellspacing="0">
	<thead>
		<tr>
			<th colspan="3"><h2><img src="<?php echo plugins_url('../images/techgasp-minilogo-16.png', __FILE__); ?>" style="float:left; height:16px; vertical-align:middle;" /><?php _e('&nbsp;Tracking Code', 'google_analytics_master'); ?></h2><small><?php _e('&nbsp;(used to send analytics data to google)', 'google_analytics_master'); ?></small></th>
		</tr>
	</thead>

	<tfoot>
		<tr>
			<th colspan="3"></th>
		</tr>
	</tfoot>

	<tbody>
		<tr class="alternate">
			<th class="check-column" scope="row"></th>
			<td><h3>Code or Script:</h3></td>
			<td></td>
		</tr>
		<tr class="alternate">
			<th class="check-column" scope="row"></th>
			<td style="vertical-align:middle"><label for="google_analytics_master_code_id"><?php _e('Tracking Code ID:', 'google_analytics_master'); ?></label></td>
			<td><input id="google_analytics_master_code_id" name="google_analytics_master_code_id" type="text" size="22" value="<?php
if (is_multisite()){
	echo get_site_option('google_analytics_master_code_id');
}
else{
	echo get_option('google_analytics_master_code_id');
} ?>">
				<p>Insert your tracking ID, example <b>UA-15519963-99</b>. Leave blank if you plan to use the Script Code ID.</p>
			</td>
		</tr>
		<tr class="alternate">
			<th class="check-column" scope="row"><input name="google_analytics_master_activate_script" id="google_analytics_master_activate_script" value="true" type="checkbox" <?php
if (is_multisite()){	
	echo get_site_option('google_analytics_master_activate_script') == 'true' ? 'checked="checked"':'';
}
else{
	echo get_option('google_analytics_master_activate_script') == 'true' ? 'checked="checked"':'';
}
?> /></th>
			<td><label for="google_analytics_master_activate_script"><b><?php _e('Activate Script ID', 'google_analytics_master'); ?></b></label></td>
			<td></td>
		</tr>
		<tr class="alternate">
			<th class="check-column" scope="row"></th>
			<td><label for="google_analytics_master_script_id"><?php _e('insert script:', 'google_analytics_master'); ?></label></td>
			<td><textarea cols="22" rows="5" id="google_analytics_master_script_id" name="google_analytics_master_script_id" ><?php
if (is_multisite()){	
	echo stripslashes(get_site_option('google_analytics_master_script_id'));
}
else{
	echo stripslashes(get_option('google_analytics_master_script_id'));
}
?></textarea>
				<p>Activating the Script ID will force this plugin to use the script instead of the above Tracking Code ID. Copy and paste your Traditional, the new Universal Google Analytics Script. <a href="https://www.google.com/analytics" target="_blank">Jump to Analytics Website</a>.</p>
			</td>
		</tr>
		<tr>
			<th class="check-column" scope="row"></th>
			<td><h3>Theme Placement:</h3></td>
			<td></td>
		</tr>
		<tr>
			<th class="check-column" scope="row"><input name="google_analytics_master_activate_footer" id="google_analytics_master_activate_footer" value="true" type="checkbox" <?php
if (is_multisite()){	
	echo get_site_option('google_analytics_master_activate_footer') == 'true' ? 'checked="checked"':'';
}
else{
	echo get_option('google_analytics_master_activate_footer') == 'true' ? 'checked="checked"':'';
}	
?> /></th>
			<td><label for="google_analytics_master_activate_footer"><b><?php _e('Activate in Theme Footer', 'google_analytics_master'); ?></b></label>
				<p>Default is <b>off</b>, Test Performance.</p>
			</td>
			<td></td>
		</tr>
	</tbody>
</table>
<p class="submit" style="margin:0px; padding-top:5px; height:30px;"><input class='button-primary' type='submit' name='update' value='<?php _e("Save Settings", 'google_analytics_master'); ?>' id='submitbutton' /></p>

<table class="widefat" cellspacing="0">
	<thead>
		<tr>
			<th colspan="3"><h2><img src="<?php echo plugins_url('../images/techgasp-minilogo-16.png', __FILE__); ?>" style="float:left; height:16px; vertical-align:middle;" /><?php _e('&nbsp;Statistics', 'google_analytics_master'); ?></h2><small><?php _e('&nbsp;(used to retreive statistics and display them inside your wordpress)', 'google_analytics_master'); ?></small></th>
		</tr>
	</thead>

	<tfoot>
		<tr>
			<th colspan="3"></th>
		</tr>
	</tfoot>
		<tbody>
		<tr class="alternate">
			<th class="check-column" scope="row"></th>
			<td><h3>Google API:</h3></td>
			<td></td>
		</tr>
		<!--
		<tr class="alternate">
			<th class="check-column" scope="row">
<input name="google_analytics_master_admin_bar" id="google_analytics_master_admin_bar" value="true" type="checkbox" <?php echo get_option('google_analytics_master_admin_bar') == 'true' ? 'checked="checked"':''; ?> />
			</th>
			<td>
<label for="google_analytics_master_admin_bar"><b><?php _e('Wordpress Admin Bar', 'google_analytics_master'); ?></b></label>
			</td>
			<td></td>
			<td>Real-time Visitors display on Wordpress Administrator Bar</td>
		</tr>
		-->
		<tr class="alternate">
			<th class="check-column" scope="row"></th>
			<td><label for="google_analytics_master_code_id"><?php _e('Client ID:', 'google_analytics_master'); ?></label></td>
			<td><input id="google_analytics_master_code_id" name="google_analytics_master_client_id" type="text" size="22" value="<?php
if (is_multisite()){	
	echo get_site_option('google_analytics_master_client_id');
}
else{
	echo get_option('google_analytics_master_client_id');
}
?>">
				<p>Insert your google api client id, example <b>623325626209-j1jm9d78ge0v4uf8b9cor31qsirungrq.apps.googleusercontent.com</b></p>
				<h3>How do I get my Google Client ID?</h3>
				<p>Visit the <a href="https://console.developers.google.com" target="_blank">Get Google Analytics OAuth 2.0 Credentials -> Client ID</a>. Log in if prompted to do so.</p>
			</td>
		</tr>
</table>

<p class="submit" style="margin:0px; padding-top:5px; height:30px;"><input class='button-primary' type='submit' name='update' value='<?php _e("Save Settings", 'google_analytics_master'); ?>' id='submitbutton' /></p>
</fieldset>
</form>
<?php
	}
//CLASS ENDS
}
