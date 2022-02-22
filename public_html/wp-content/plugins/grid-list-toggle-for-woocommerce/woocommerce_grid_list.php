<?php
/**
Plugin Name: Grid & List toggle for Woocommerce
Plugin URI: http://www.phoeniixx.com
Description:Adds a grid & list view toggle to product archives.
Author: phoeniixx
Text Domain: grid-list-toggle-for-Woocommerce
Version: 1.2.3
Author URI: http://www.phoeniixx.com
**/
if ( ! defined( 'ABSPATH' ) )
{
	exit;   
}
	// Exit if accessed directly
/**
 * Check if WooCommerce is active
 **/
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) 
{
	
	register_activation_hook(__FILE__, 'phoen_gridlist_registration');
	
	function phoen_gridlist_registration()
	{
		$data = array('check_gl'=>'enable', 'choose_grid_list'=>'phoen_list');
		
		if(!get_option('grid_list_view_data'))
		{
			update_option('grid_list_view_data', $data);
		}

	}
	function phoen_gridlist_plugin_path() {
	 
	  // gets the absolute path to this plugin directory
	 
	  return untrailingslashit( plugin_dir_path( __FILE__ ) );
	 
	}
	
	add_action( 'wp', 'phoen_state_of_grid_lists' );

	function phoen_state_of_grid_lists() {

		global $post;
		
		$terms = get_the_terms( $post->ID, 'product_cat' );
		
		if (is_shop() || !empty($terms)){
			
			add_filter( 'woocommerce_locate_template', 'phoen_gridlist_woocommerce_locate_template', 10, 3 );
	
			add_action( 'woocommerce_after_shop_loop_item', 'phoe_show_short_desc' );
	
			add_action( 'woocommerce_after_shop_loop_item', 'phoe_show_short_btn' );
		}
		
	}	
 
	function phoen_gridlist_woocommerce_locate_template( $template, $template_name, $template_path ) {
	 
	  global $woocommerce;
	 
	  $_template = $template;
	 
	  if ( ! $template_path ) $template_path = $woocommerce->template_url;
	 
	  $plugin_path  = phoen_gridlist_plugin_path() . '/woocommerce/';
	 
	 //echo $plugin_path;
	 
	  // Look within passed path within the theme - this is priority
	 
	  $template = locate_template(
	 
		array(
	 
		  $template_path . $template_name,
	 
		  $template_name
	 
		)
	 
	  );
	
	  // Modification: Get the template from this plugin, if it exists
	 
	  if ( ! $template && file_exists( $plugin_path . $template_name ) )
	 
		$template = $plugin_path . $template_name;
	 
	  // Use default template
	 
	  if ( ! $template )
	 
		$template = $_template;
	 
	  return $template;
	 
	}
	
	$data = get_option('grid_list_view_data');
		if(!empty($data)){
			extract($data);
		}
	if($check_gl=='enable')
	{
		
		add_action( 'wp_head', 'phoen_wgl_style');
	}
	
	function phoen_wgl_style()
	{
		
		global $post;
		
		$terms = get_the_terms( $post->ID, 'product_cat' );
		
		if(is_shop() || !empty($terms))  {
			
			phoen_gridlist_set_default_view();
			wp_enqueue_script( 'phoen_gridlist_cookie', plugin_dir_url( __FILE__ ) . '/js/jquery.cookie.js', array( 'jquery' ) );
			wp_enqueue_script( 'phoen_gridlist_script', plugin_dir_url( __FILE__ ) . '/js/phoen_gridlist.js', array( 'jquery' ) );
			
		}
		
		wp_enqueue_style( 'phoen_gridlist_style', plugin_dir_url( __FILE__ ) . '/css/wc_grid_list.css');
		wp_enqueue_style('phoen_style_dashicons',site_url().'/wp-includes/css/dashicons.min.css');
	
	}
	
	function phoen_gridlist_set_default_view() 
	{
		
		$data = get_option('grid_list_view_data');
		if(!empty($data)){
			extract($data);
			ob_start();
		}  	?> 			<script>
					
						jQuery(document).ready(function(){
							//alert(jQuery.cookie('gridcookie'));
							if(jQuery.cookie('phoen_gridcookie') == null)
							{	
								jQuery( 'ul.products' ).addClass( '<?php echo $choose_grid_list; ?>' );
								jQuery( '.phoen_gridlist_toggle #<?php echo $choose_grid_list; ?>' ).addClass( 'active' );
							}else{
								jQuery( 'ul.products' ).addClass(jQuery.cookie('phoen_gridcookie'));
								jQuery( '.phoen_gridlist_toggle #'+jQuery.cookie('phoen_gridcookie') ).addClass( 'active' );
							}
							
						});						
									    	
					</script>
			<?php  	}
			
	$data = get_option('grid_list_view_data');
	if(!empty($data)){
		extract($data);
	}
	if($check_gl=='enable')
	{
		add_action( 'woocommerce_before_shop_loop', 'phoen_grid_list_toggle_button',35);
	}
	
	function phoen_grid_list_toggle_button() 
	{
		?>
			<nav class="phoen_gridlist_toggle">
				<a href="javascript:void(0);" id="phoen_grid" title="<?php _e('Grid view', 'woocommerce-grid-list-toggle'); ?>"><span class="dashicons dashicons-grid-view"></span> </a>
				<a href="javascript:void(0);" id="phoen_list" title="<?php _e('List view', 'woocommerce-grid-list-toggle'); ?>"><span class="dashicons dashicons-exerpt-view"></span></a>
			</nav>
		<?php
	}
	
	add_action('admin_menu', 'phoen_gridlist_toggle_custom_menu');
	
	function phoen_gridlist_toggle_custom_menu() 
	{

		$plugin_dir_url =  plugin_dir_url( __FILE__ );
		
		add_menu_page( 'phoeniixx', __( 'Phoeniixx', 'phe' ), 'nosuchcapability', 'phoeniixx', NULL, $plugin_dir_url.'/images/logo-wp.png', 57 );
        
		add_submenu_page( 'phoeniixx', 'Grid/List', 'Grid/List', 'manage_options', 'gridlist_toggle_setting', 'gridlist_toggle_setting' );	
	
	}
	
	function gridlist_toggle_setting()
	{
			
		require_once(dirname(__FILE__).'/admin_setting.php');
			
	}
	
	function phoe_show_short_btn() {
     
		?>
		<div class="phoeniixx_short_btn"></div>
		<?php 
	} 
	
	function phoe_show_short_desc() {
		?>

		<div class="phoeniixx_short_desc" style="display:<?php echo($_COOKIE['phoen_gridcookie']=='phoen_grid')?'none':'block';?>"  ><?php the_excerpt(); ?> </div>
		<?php 
    
	} 
	
}  ?>