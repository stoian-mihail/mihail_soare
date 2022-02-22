<?php
/*
 * The template for displaying "Page 404"
*/

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'writer_ancora_template_404_theme_setup' ) ) {
	add_action( 'writer_ancora_action_before_init_theme', 'writer_ancora_template_404_theme_setup', 1 );
	function writer_ancora_template_404_theme_setup() {
		writer_ancora_add_template(array(
			'layout' => '404',
			'mode'   => 'internal',
			'title'  => 'Page 404',
			'theme_options' => array(
				'article_style' => 'stretch'
			)
		));
	}
}

// Template output
if ( !function_exists( 'writer_ancora_template_404_output' ) ) {
	function writer_ancora_template_404_output() {
        $img_404  = writer_ancora_get_file_url('images/404-page.png');
        ?>
		<article class="post_item post_item_404">
			<div class="post_content">
				<img class="image-404" src="<?php echo esc_url($img_404) ?> " alt="page 404">
				<h1 class="page_title"><?php esc_html_e( 'Error 404!', 'writer-ancora' ); ?></h1>
				<p class="page_description"><?php echo wp_kses( sprintf( __('Can\'t find what you need? Take a moment and do <br/> a search below or start from <a href="%s">our homepage</a>.', 'writer-ancora'), esc_url(home_url('/')) ), writer_ancora_storage_get('allowed_tags') ); ?></p>
				<div class="page_search"><?php echo trim(writer_ancora_sc_search(array('state'=>'fixed', 'title'=>""))); ?></div>
			</div>
		</article>
		<?php
	}
}
?>