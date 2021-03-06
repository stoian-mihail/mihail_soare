<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'writer_ancora_template_no_search_theme_setup' ) ) {
	add_action( 'writer_ancora_action_before_init_theme', 'writer_ancora_template_no_search_theme_setup', 1 );
	function writer_ancora_template_no_search_theme_setup() {
		writer_ancora_add_template(array(
			'layout' => 'no-search',
			'mode'   => 'internal',
			'title'  => esc_html__('No search results found', 'writer-ancora')
		));
	}
}

// Template output
if ( !function_exists( 'writer_ancora_template_no_search_output' ) ) {
	function writer_ancora_template_no_search_output($post_options, $post_data) {
		?>
		<article class="post_item">
			<div class="post_content">
				<h2 class="post_title"><?php echo sprintf(esc_html__('Search: %s', 'writer-ancora'), get_search_query()); ?></h2>
				<p><?php esc_html_e( 'Sorry, but nothing matched your search criteria. Please try again with some different keywords.', 'writer-ancora' ); ?></p>
				<p><?php echo wp_kses( sprintf(__('Go back, or return to <a href="%s">%s</a> home page to choose a new page.', 'writer-ancora'), esc_url(home_url('/')), get_bloginfo()), writer_ancora_storage_get('allowed_tags') ); ?>
				<br><?php esc_html_e('Please report any broken links to our team.', 'writer-ancora'); ?></p>
				<?php echo trim(writer_ancora_sc_search(array('state'=>"fixed"))); ?>
			</div>	<!-- /.post_content -->
		</article>	<!-- /.post_item -->
		<?php
	}
}
?>