<?php
if ( ! defined( 'ABSPATH' ) )
{
	exit;   
} // Exit if accessed directly

if ( ! empty( $_POST ) && check_admin_referer( 'phoen_wgl_nonce_action', 'phoen_wgl_nonce_input_field' ) )
{

	$check_gl = sanitize_text_field($_POST['check_gl']);
	
	$choose_grid_list = sanitize_text_field($_POST['choose_grid_list']);
	
	$grid_list_setting_data = array('check_gl' => $check_gl,'choose_grid_list' => $choose_grid_list);
	
	$query_check = update_option('grid_list_view_data', $grid_list_setting_data );

	
	if($query_check == 1)
	{
	?>
		<div class="updated" id="message">

			<p><strong>Setting updated.</strong></p>

		</div>

	<?php
	}
	else
	{
		?>
			<div class="error below-h2" id="message"><p> Something Went Wrong Please Try Again With Valid Data.</p></div>
		<?php
	}
}

$data = get_option('grid_list_view_data');

if(!empty($data))
{
	extract($data);
}
 
 
?>

<div id="profile-page" class="wrap">
<?php

if( isset( $_GET['tab'] ) ) {
	
	$tab = sanitize_text_field( $_GET['tab'] );
	
}
else
{
	$tab = '';
}

?>
<h2>
	Grid/List plugin  Options 
</h2>
	
	<h2 class="nav-tab-wrapper woo-nav-tab-wrapper">
			
		<a class="nav-tab <?php if($tab == 'general' || $tab == ''){ echo esc_html( "nav-tab-active" ); } ?>" href="?page=gridlist_toggle_setting&amp;tab=general">Setting</a>
		<a class="nav-tab <?php if($tab == 'premium'){ echo esc_html( "nav-tab-active" ); } ?>" href="?page=gridlist_toggle_setting&amp;tab=premium">Premium</a>
		
	</h2>

<form novalidate="novalidate" method="post" action="" >
<?php 
if($tab == 'general' || $tab == '')
{
	
?>
<table class="form-table">

	<tbody>

		<h3 class="setting-title">General Options</h3>
		
		<tr class="user-nickname-wrap">

			<th><label for="check_gl">Enable Grid/List</label></th>

			<td><input type="checkbox" value="enable" <?php if($check_gl == 'enable'){ echo "checked"; } ?> id="check_gl" name="check_gl" ></td>

		</tr>
		
		<tr class="user-user-login-wrap">

			<th><label for="choose_grid_list">Choose option</label></th>

			<td>
			
				<select name="choose_grid_list" >
					
					<option <?php if($choose_grid_list == 'phoen_grid'){ echo "selected"; } ?> value="phoen_grid">Grid</option>
					
					<option <?php if($choose_grid_list == 'phoen_list'){ echo "selected"; } ?> value="phoen_list">List</option>
				
				</select>
				
			</td>

		</tr>
		
		<tr class="user-user-login-wrap">
			
			<td>
			
				<?php echo wp_nonce_field( 'phoen_wgl_nonce_action', 'phoen_wgl_nonce_input_field' );?>
			
			</td>

		</tr>
		
	</tbody>	

</table>

<p class="submit"><input type="submit" value="Save changes" class="button button-primary" id="submit" name="submit"></p>
	
<?php
} 

?>

</form>
<?php 

if($tab == 'premium')
{ 
	
	require_once(dirname(__FILE__).'/phoen_premium.php');
}
	?>
</div>

<style>
.form-table th {
    width: 270px;
	padding: 25px;
}
.form-table td {
	
    padding: 20px 10px;
}
.form-table {
	background-color: #fff;
}
h3 {
    padding: 10px;
}
.px-multiply{ color:#ccc; vertical-align:bottom;}

.long{ display:inline-block; vertical-align:middle; }

.wid{ display:inline-block; vertical-align:middle; }

.up{ display:block;}

.grey{ color:#b0adad;}
</style>