<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'writer_ancora_template_form_2_theme_setup' ) ) {
	add_action( 'writer_ancora_action_before_init_theme', 'writer_ancora_template_form_2_theme_setup', 1 );
	function writer_ancora_template_form_2_theme_setup() {
		writer_ancora_add_template(array(
			'layout' => 'form_2',
			'mode'   => 'forms',
			'title'  => esc_html__('Contact Form 2', 'writer-ancora')
			));
	}
}

// Template output
if ( !function_exists( 'writer_ancora_template_form_2_output' ) ) {
	function writer_ancora_template_form_2_output($post_options, $post_data) {
		$address_1 = writer_ancora_get_theme_option('contact_address_1');
		$address_2 = writer_ancora_get_theme_option('contact_address_2');
		$phone = writer_ancora_get_theme_option('contact_phone');
		$fax = writer_ancora_get_theme_option('contact_fax');
		$email = writer_ancora_get_theme_option('contact_email');
		$open_hours = writer_ancora_get_theme_option('contact_open_hours');
		?>
		<div class="sc_columns columns_wrap">
			<div class="sc_form_fields column-2_3">
				<form <?php echo !empty($post_options['id']) ? ' id="'.esc_attr($post_options['id']).'"' : ''; ?> data-formtype="<?php echo esc_attr($post_options['layout']); ?>" method="post" action="<?php echo esc_url($post_options['action'] ? $post_options['action'] : admin_url('admin-ajax.php')); ?>">
					<?php writer_ancora_sc_form_show_fields($post_options['fields']); ?>
					<div class="sc_form_info">
						<div class="sc_form_item sc_form_field label_over"><label class="required" for="sc_form_username"><?php esc_html_e('Name', 'writer-ancora'); ?></label><input id="sc_form_username" type="text" name="username" placeholder="<?php esc_attr_e('Name *', 'writer-ancora'); ?>"></div>
						<div class="sc_form_item sc_form_field label_over"><label class="required" for="sc_form_email"><?php esc_html_e('E-mail', 'writer-ancora'); ?></label><input id="sc_form_email" type="text" name="email" placeholder="<?php esc_attr_e('E-mail *', 'writer-ancora'); ?>"></div>
						<div class="sc_form_item sc_form_field label_over"><label class="required" for="sc_form_subj"><?php esc_html_e('Subject', 'writer-ancora'); ?></label><input id="sc_form_subj" type="hidden" name="subject" value="subject" placeholder=""></div>
					</div>
					<div class="sc_form_item sc_form_message label_over"><label class="required" for="sc_form_message"><?php esc_html_e('Message', 'writer-ancora'); ?></label><textarea id="sc_form_message" name="message" placeholder="<?php esc_attr_e('Message', 'writer-ancora'); ?>"></textarea></div>
					<div class="sc_form_item sc_form_button"><button class="sc_button sc_button_square sc_button_style_filled sc_button_size_small  sc_button_iconed icon-right"><?php esc_html_e('Send Message', 'writer-ancora'); ?></button></div>
					<div class="result sc_infobox"></div>
				</form>
			</div><div class="sc_form_address column-1_3">
				<div class="sc_form_address_field">
					<span class="sc_form_address_label"><?php esc_html_e('Location', 'writer-ancora'); ?></span>
					<span class="sc_form_address_data"><?php echo trim($address_1) . (!empty($address_1) && !empty($address_2) ? ', ' : '') . $address_2; ?></span>
				</div>
				<div class="sc_form_address_field">
					<span class="sc_form_address_label"><?php esc_html_e('Email', 'writer-ancora'); ?></span>
					<span class="sc_form_address_data"><?php echo trim($email); ?></span>
				</div>
			</div>
		</div>
		<?php
	}
}
?>