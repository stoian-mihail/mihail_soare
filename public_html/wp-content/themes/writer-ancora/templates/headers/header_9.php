<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'writer_ancora_template_header_9_theme_setup' ) ) {
	add_action( 'writer_ancora_action_before_init_theme', 'writer_ancora_template_header_9_theme_setup', 1 );
	function writer_ancora_template_header_9_theme_setup() {
		writer_ancora_add_template(array(
			'layout' => 'header_9',
			'mode'   => 'header',
			'title'  => esc_html__('Header 9', 'writer-ancora'),
			'icon'   => writer_ancora_get_file_url('templates/headers/images/9.jpg')
			));
	}
}

// Template output
if ( !function_exists( 'writer_ancora_template_header_9_output' ) ) {
	function writer_ancora_template_header_9_output($post_options, $post_data) {

		// WP custom header
		$header_css = '';
		if ($post_options['position'] != 'over') {
			$header_image = get_header_image();
			$header_css = $header_image!='' 
				? ' style="background: url('.esc_url($header_image).') repeat center top"' 
				: '';
		}
		?>

		<div class="top_panel_fixed_wrap"></div>

		<header class="top_panel_wrap  top_panel_style_9 scheme_<?php echo esc_attr($post_options['scheme']); ?>">
			
			<div class="top_panel_wrap_inner  top_panel_inner_style_9 top_panel_position_<?php echo esc_attr(writer_ancora_get_custom_option('top_panel_position')); ?>">
			
				<?php if (writer_ancora_get_custom_option('show_top_panel_top')=='yes') { ?>
				<div class="top_panel_top">
					<div class="content_wrap clearfix">
						<?php
						$top_panel_top_components = array('contact_info', 'login', 'currency', 'bookmarks', 'socials');
						require_once writer_ancora_get_file_dir('templates/headers/_parts/top-panel-top.php');
						?>
					</div>
				</div>
				<?php } ?>

				<div class="top_panel_middle" <?php echo trim($header_css); ?>>
					<div class="content_wrap">
						<div class="contact_logo">
							<?php writer_ancora_show_logo(true, true); ?>
						</div>
						
						<div class="contact_info">
							<?php
							$contact_phone=trim(writer_ancora_get_custom_option('contact_phone'));
							$contact_email=trim(writer_ancora_get_custom_option('contact_email'));
							if (!empty($contact_phone) || !empty($contact_email)) {
								?><div class="contact_field contact_phone">
									<span class="contact_email"><?php esc_html_e('Write me:', 'writer-ancora'); ?> <span><?php echo force_balance_tags($contact_email); ?></span></span>
								</div>
							<?php } ?>
							<div class="menu_pushy_wrap clearfix">
								<a href="#" class="menu_pushy_button icon-menu"></a>
							</div>
							<div class="top_panel_buttons">
								<?php
								
								if (function_exists('writer_ancora_exists_woocommerce') && writer_ancora_exists_woocommerce() && (writer_ancora_is_woocommerce_page() && writer_ancora_get_custom_option('show_cart')=='shop' || writer_ancora_get_custom_option('show_cart')=='always' || writer_ancora_get_custom_option('show_cart_button')=='yes' )  && !(is_checkout() || is_cart() || defined('WOOCOMMERCE_CHECKOUT') || defined('WOOCOMMERCE_CART'))) { 
									?>
									<div class="menu_main_cart top_panel_icon">
										<?php require_once writer_ancora_get_file_dir('templates/headers/_parts/contact-info-cart.php'); ?>
									</div>
									<?php
								}
								?>
							</div>
							
						</div>
					</div>
				</div>

			</div>

		</header>

		<nav class="menu_pushy_nav_area pushy pushy-left scheme_<?php echo esc_attr(writer_ancora_get_custom_option('pushy_panel_scheme')); ?>">
			<div class="pushy_inner">
	
				<a href="#" class="close-pushy"></a>
	
				<?php 
				writer_ancora_show_logo(false, false, false, false);
	
				$menu_main = writer_ancora_get_nav_menu('menu_main');
				if (empty($menu_main)) $menu_main = writer_ancora_get_nav_menu();
				echo str_replace('menu_main', 'menu_pushy', $menu_main);
	
				
	
				
				?>

			</div>
        </nav>

        <!-- Site Overlay -->
        <div class="site-overlay"></div>
		<?php
	}
}
?>