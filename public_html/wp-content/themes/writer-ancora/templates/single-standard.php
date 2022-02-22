<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'writer_ancora_template_single_standard_theme_setup' ) ) {
	add_action( 'writer_ancora_action_before_init_theme', 'writer_ancora_template_single_standard_theme_setup', 1 );
	function writer_ancora_template_single_standard_theme_setup() {
		writer_ancora_add_template(array(
			'layout' => 'single-standard',
			'mode'   => 'single',
			'need_content' => true,
			'need_terms' => true,
			'title'  => esc_html__('Single standard', 'writer-ancora'),
			'thumb_title'  => esc_html__('Fullwidth image (crop)', 'writer-ancora'),
			'w'		 => 1170,
			'h'		 => 659
		));
	}
}

// Template output
if ( !function_exists( 'writer_ancora_template_single_standard_output' ) ) {
	function writer_ancora_template_single_standard_output($post_options, $post_data) {
		$post_data['post_views']++;
		$avg_author = 0;
		$avg_users  = 0;
		if (!$post_data['post_protected'] && $post_options['reviews'] && writer_ancora_get_custom_option('show_reviews')=='yes') {
			$avg_author = $post_data['post_reviews_author'];
			$avg_users  = $post_data['post_reviews_users'];
		}
		$show_title = writer_ancora_get_custom_option('show_post_title')=='yes' && (writer_ancora_get_custom_option('show_post_title_on_quotes')=='yes' || !in_array($post_data['post_format'], array('aside', 'chat', 'status', 'link', 'quote')));
		$title_tag = writer_ancora_get_custom_option('show_page_title')=='yes' ? 'h3' : 'h1';

		writer_ancora_open_wrapper('<article class="' 
				. join(' ', get_post_class('itemscope'
					. ' post_item post_item_single'
					. ' post_featured_' . esc_attr($post_options['post_class'])
					. ' post_format_' . esc_attr($post_data['post_format'])))
				. '"'
				. ' itemscope itemtype="http://schema.org/'.($avg_author > 0 || $avg_users > 0 ? 'Review' : 'Article')
				. '">');

		if ($show_title && $post_options['location'] == 'center' && writer_ancora_get_custom_option('show_page_title')=='no') {
			?>
			<<?php echo esc_html($title_tag); ?> itemprop="<?php echo (float) $avg_author > 0 || (float) $avg_users > 0 ? 'itemReviewed' : 'headline'; ?>" class="post_title entry-title"><span class="post_icon <?php echo esc_attr($post_data['post_icon']); ?>"></span><?php echo trim($post_data['post_title']); ?></<?php echo esc_html($title_tag); ?>>
		<?php 
		}

		if (!$post_data['post_protected'] && (
			!empty($post_options['dedicated']) ||
			(writer_ancora_get_custom_option('show_featured_image')=='yes' && $post_data['post_thumb'])	// && $post_data['post_format']!='gallery' && $post_data['post_format']!='image')
		)) {
			?>
			<section class="post_featured">
			<?php
			if (!empty($post_options['dedicated'])) {
				echo trim($post_options['dedicated']);
			} else {
				writer_ancora_enqueue_popup();
				?>
				<div class="post_thumb" data-image="<?php echo esc_url($post_data['post_attachment']); ?>" data-title="<?php echo esc_attr($post_data['post_title']); ?>">
					<a class="hover_icon hover_icon_view" href="<?php echo esc_url($post_data['post_attachment']); ?>" title="<?php echo esc_attr($post_data['post_title']); ?>"><?php echo trim($post_data['post_thumb']); ?></a>
				</div>
				<?php 
			}
			?>
			</section>
			<?php
		}
			
		
		if ($show_title && $post_options['location'] != 'center' && writer_ancora_get_custom_option('show_page_title')=='no') {
			?>
			<<?php echo esc_html($title_tag); ?> itemprop="<?php echo (float) $avg_author > 0 || (float) $avg_users > 0 ? 'itemReviewed' : 'headline'; ?>" class="post_title entry-title"><span class="post_icon <?php echo esc_attr($post_data['post_icon']); ?>"></span><?php echo trim($post_data['post_title']); ?></<?php echo esc_html($title_tag); ?>>
			<?php 
		}

		if (!$post_data['post_protected'] && writer_ancora_get_custom_option('show_post_info')=='yes') {
			$info_parts = array('snippets'=>true);
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
				<div class="post_info post_info_bottom">
					<span class="post_info_item post_info_tags"><?php esc_html_e('Tags:', 'writer-ancora'); ?> <?php echo join(', ', $post_data['post_terms'][$post_data['post_taxonomy_tags']]->terms_links); ?></span>
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
		}

		$sidebar_present = !writer_ancora_param_is_off(writer_ancora_get_custom_option('show_sidebar_main'));
		if (!$sidebar_present) writer_ancora_close_wrapper();	// .post_item
		require writer_ancora_get_file_dir('templates/_parts/related-posts.php');
		if ($sidebar_present) writer_ancora_close_wrapper();		// .post_item

		if (!$post_data['post_protected']) {
			require writer_ancora_get_file_dir('templates/_parts/comments.php');
		}
	}
}
?>