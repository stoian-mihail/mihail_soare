<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'writer_ancora_template_single_portfolio_theme_setup' ) ) {
	add_action( 'writer_ancora_action_before_init_theme', 'writer_ancora_template_single_portfolio_theme_setup', 1 );
	function writer_ancora_template_single_portfolio_theme_setup() {
		writer_ancora_add_template(array(
			'layout' => 'single-portfolio',
			'mode'   => 'single',
			'need_content' => true,
			'need_terms' => true,
			'title'  => esc_html__('Portfolio item', 'writer-ancora'),
			'thumb_title'  => esc_html__('Fullwidth image', 'writer-ancora'),
			'w'		 => 1170,
			'h'		 => null,
			'h_crop' => 659
		));
	}
}

// Template output
if ( !function_exists( 'writer_ancora_template_single_portfolio_output' ) ) {
	function writer_ancora_template_single_portfolio_output($post_options, $post_data) {
		$post_data['post_views']++;
		$avg_author = 0;
		$avg_users  = 0;
		if (!$post_data['post_protected'] && $post_options['reviews'] && writer_ancora_get_custom_option('show_reviews')=='yes') {
			$avg_author = $post_data['post_reviews_author'];
			$avg_users  = $post_data['post_reviews_users'];
		}
		$show_title = writer_ancora_get_custom_option('show_post_title')=='yes' && (writer_ancora_get_custom_option('show_post_title_on_quotes')=='yes' || !in_array($post_data['post_format'], array('aside', 'chat', 'status', 'link', 'quote')));

		writer_ancora_open_wrapper('<article class="' 
				. join(' ', get_post_class('itemscope'
					. ' post_item post_item_single_portfolio'
					. ' post_featured_' . esc_attr($post_options['post_class'])
					. ' post_format_' . esc_attr($post_data['post_format'])))
				. '"'
				. ' itemscope itemtype="http://schema.org/'.($avg_author > 0 || $avg_users > 0 ? 'Review' : 'Article')
				. '">');

		require writer_ancora_get_file_dir('templates/_parts/prev-next-block.php');

		if ($show_title) {
			?>
			<h1 itemprop="<?php echo (float) $avg_author > 0 || (float) $avg_users > 0 ? 'itemReviewed' : 'headline'; ?>" class="post_title entry-title"><?php echo trim($post_data['post_title']); ?></h1>
			<?php
		}

		if (!$post_data['post_protected'] && writer_ancora_get_custom_option('show_post_info')=='yes') {
			require writer_ancora_get_file_dir('templates/_parts/post-info.php');
		}

		require writer_ancora_get_file_dir('templates/_parts/reviews-block.php');

		writer_ancora_open_wrapper('<section class="post_content'.(!$post_data['post_protected'] && $post_data['post_edit_enable'] ? ' '.esc_attr('post_content_editor_present') : '').'" itemprop="'.($avg_author > 0 || $avg_users > 0 ? 'reviewBody' : 'articleBody').'">');
			
		// Post content
		if ($post_data['post_protected']) { 
			echo trim($post_data['post_excerpt']);
			echo get_the_password_form(); 
		} else {
			if (!writer_ancora_storage_empty('reviews_markup') && writer_ancora_strpos($post_data['post_content'], writer_ancora_get_reviews_placeholder())===false) 
				$post_data['post_content'] = writer_ancora_sc_reviews(array()) . ($post_data['post_content']);
			echo trim(writer_ancora_gap_wrapper(writer_ancora_reviews_wrapper($post_data['post_content'])));
			require writer_ancora_get_file_dir('templates/_parts/single-pagination.php');
			if ( writer_ancora_get_custom_option('show_post_tags') == 'yes' && !empty($post_data['post_terms'][$post_data['post_taxonomy_tags']]->terms_links)) {
				?>
				<div class="post_info">
					<span class="post_info_item post_info_tags"><?php esc_html_e('in', 'writer-ancora'); ?> <?php echo join(', ', $post_data['post_terms'][$post_data['post_taxonomy_tags']]->terms_links); ?></span>
				</div>
				<?php
			} 
		}

		if (!$post_data['post_protected'] && $post_data['post_edit_enable']) {
			require writer_ancora_get_file_dir('templates/_parts/editor-area.php');
		}

		writer_ancora_close_wrapper();	// .post_content

		if (!$post_data['post_protected']) {
			require writer_ancora_get_file_dir('templates/_parts/author-info.php');
			require writer_ancora_get_file_dir('templates/_parts/share.php');
			require writer_ancora_get_file_dir('templates/_parts/related-posts.php');
			require writer_ancora_get_file_dir('templates/_parts/comments.php');
		}
	
		writer_ancora_close_wrapper();	// .post_item
	}
}
?>