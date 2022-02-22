			<?php
			$info_parts = array_merge(array(
				'snippets' => false,	// For singular post/page/team/client/service etc.
				'date' => true,
				'author' => true,
				'terms' => true,
				'counters' => true,
				'tag' => 'div'			// 'p' for portfolio hovers 
				), isset($info_parts) && is_array($info_parts) ? $info_parts : array());
			?>
			<<?php echo esc_attr($info_parts['tag']); ?> class="post_info">
				<?php
				if ($info_parts['date']) {
					$post_date = apply_filters('writer_ancora_filter_post_date', $post_data['post_date_sql'], $post_data['post_id'], $post_data['post_type']);
					$post_date_diff = writer_ancora_get_date_or_difference($post_date);
					?>
					<span class="post_info_item post_info_posted"><?php echo (in_array($post_data['post_type'], array('post', 'page', 'product')) ? '' : ($post_date[0] <= date('Y-m-d') ? esc_html__('Started', 'writer-ancora') : esc_html__('Will start', 'writer-ancora'))); ?> <a href="<?php echo esc_url($post_data['post_link']); ?>" class="post_info_date<?php echo esc_attr($info_parts['snippets'] ? ' date updated' : ''); ?>"<?php echo !empty($info_parts['snippets']) ? ' itemprop="datePublished" content="'.esc_attr($post_date).'"' : ''; ?>><?php echo esc_html($post_date_diff); ?></a></span>
					<?php
				}
				if ($info_parts['author'] && $post_data['post_type']=='post') {
					?>
					<span class="post_info_item post_info_posted_by<?php echo !empty($info_parts['snippets']) ? ' vcard' : ''; ?>"<?php echo !empty($info_parts['snippets']) ? ' itemprop="author"' : ''; ?>><a href="<?php echo esc_url($post_data['post_author_url']); ?>" class="post_info_author"><?php echo trim($post_data['post_author']); ?></a></span>
				<?php 
				}
			

				if (!empty($post_data['post_terms'][$post_data['post_taxonomy_tags']]->terms)) { ?>
                    <span class="post_info_item post_info_tags"><span class="icons_post_info icon-tag-1"></span><?php echo join(', ', $post_data['post_terms'][$post_data['post_taxonomy_tags']]->terms_links); ?></span>
                    <?php 
                }


				if ($info_parts['counters']) {
					?>
					<span class="post_info_item post_info_counters"><?php require writer_ancora_get_file_dir('templates/_parts/counters.php'); ?></span>
					<?php
				}
				if (is_single() && !writer_ancora_storage_get('blog_streampage') && ($post_data['post_edit_enable'] || $post_data['post_delete_enable'])) {
					?>
					<span class="frontend_editor_buttons">
						<?php if ($post_data['post_edit_enable']) { ?>
						<span class="post_info_item post_info_button post_info_button_edit"><a id="frontend_editor_icon_edit" class="icon-pencil" title="<?php esc_attr_e('Edit post', 'writer-ancora'); ?>" href="#"><?php esc_html_e('Edit', 'writer-ancora'); ?></a></span>
						<?php } ?>
						<?php if ($post_data['post_delete_enable']) { ?>
						<span class="post_info_item post_info_button post_info_button_delete"><a id="frontend_editor_icon_delete" class="icon-trash" title="<?php esc_attr_e('Delete post', 'writer-ancora'); ?>" href="#"><?php esc_html_e('Delete', 'writer-ancora'); ?></a></span>
						<?php } ?>
					</span>
					<?php
				}
				?>
			</<?php echo esc_attr($info_parts['tag']); ?>>
