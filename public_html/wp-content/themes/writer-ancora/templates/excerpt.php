<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'writer_ancora_template_excerpt_theme_setup' ) ) {
	add_action( 'writer_ancora_action_before_init_theme', 'writer_ancora_template_excerpt_theme_setup', 1 );
	function writer_ancora_template_excerpt_theme_setup() {
		writer_ancora_add_template(array(
			'layout' => 'excerpt',
			'mode'   => 'blog',
			'need_terms' => true,
			'title'  => esc_html__('Excerpt', 'writer-ancora'),
			'thumb_title'  => esc_html__('Large  image (crop)', 'writer-ancora'),
			'w'		 => 900,
			'h'		 => 350
		));
	}
}

// Template output
if ( !function_exists( 'writer_ancora_template_excerpt_output' ) ) {
	function writer_ancora_template_excerpt_output($post_options, $post_data) {
		$show_title = true;	//!in_array($post_data['post_format'], array('aside', 'chat', 'status', 'link', 'quote'));
		$tag = writer_ancora_in_shortcode_blogger(true) ? 'div' : 'article';
		?>
		<<?php echo trim($tag); ?> <?php post_class('post_item post_item_excerpt post_featured_' . esc_attr($post_options['post_class']) . ' post_format_'.esc_attr($post_data['post_format']) . ($post_options['number']%2==0 ? ' even' : ' odd') . ($post_options['number']==0 ? ' first' : '') . ($post_options['number']==$post_options['posts_on_page']? ' last' : '') . ($post_options['add_view_more'] ? ' viewmore' : '')); ?>>
			<?php
			if ($post_data['post_flags']['sticky']) {
				?><span class="sticky_label"></span><?php
			}

			if ($show_title && $post_options['location'] == 'center' && !empty($post_data['post_title'])) {
				?><h3 class="post_title"><a href="<?php echo esc_url($post_data['post_link']); ?>"><?php echo trim($post_data['post_title']); ?></a></h3><?php
			}
			
			if (!$post_data['post_protected'] && (!empty($post_options['dedicated']) || $post_data['post_thumb'] || $post_data['post_gallery'] || $post_data['post_video'] || $post_data['post_audio'])) {
				?>
				<div class="post_featured">
				<?php
				if (!empty($post_options['dedicated'])) {
					echo trim($post_options['dedicated']);
				} else if ($post_data['post_thumb'] || $post_data['post_gallery'] || $post_data['post_video'] || $post_data['post_audio']) {
					require writer_ancora_get_file_dir('templates/_parts/post-featured.php');
				}
				?>
				</div>
			<?php
			}
			?>
			

			<?php if(!in_array($post_data['post_format'], array('quote', 'link', 'chat', 'aside', 'status'))) {  ?>

			<div class="post_content">
                <div class="post_content_left">
                    <?php
                    if ($show_title && $post_options['location'] != 'center' && !empty($post_data['post_title'])) {
                        ?><h3 class="post_title"><a href="<?php echo esc_url($post_data['post_link']); ?>"><?php echo trim($post_data['post_title']); ?></a></h3><?php
                    }
                    ?>
                    <div class="post_descr">
                    <?php
                        if ($post_data['post_protected']) {
                            echo trim($post_data['post_excerpt']);
                        } else {
                            if ($post_data['post_excerpt']) {
                                echo in_array($post_data['post_format'], array('quote', 'link', 'chat', 'aside', 'status')) ? $post_data['post_excerpt'] : '<p>'.trim(writer_ancora_strshort($post_data['post_excerpt'], isset($post_options['descr']) ? $post_options['descr'] : writer_ancora_get_custom_option('post_excerpt_maxlength'))).'</p>';
                            }
                        }

                    ?>
                    </div>
                </div><!-- /.post_content_left -->
                <div class="post_content_right">
                    <?php
                    if (!$post_data['post_protected'] && $post_options['info']) {
                        require writer_ancora_get_file_dir('templates/_parts/post-info.php');
                    }
                    ?>
                </div><!-- /.post_content_right -->
            </div>	<!-- /.post_content -->


        <?php } else { ?>

				<div class="post_content clearfix">
					<?php
					if ($show_title && $post_options['location'] != 'center' && !empty($post_data['post_title'])) {
						?><h3 class="post_title"><a href="<?php echo esc_url($post_data['post_link']); ?>"><?php echo trim($post_data['post_title']); ?></a></h3><?php 
					}
					?>
				
					<div class="post_descr">
					<?php
						if ($post_data['post_protected']) {
							echo trim($post_data['post_excerpt']); 
						} else {
							if ($post_data['post_excerpt']) {
								echo in_array($post_data['post_format'], array('quote', 'link', 'chat', 'aside', 'status')) ? $post_data['post_excerpt'] : '<p>'.trim(writer_ancora_strshort($post_data['post_excerpt'], isset($post_options['descr']) ? $post_options['descr'] : writer_ancora_get_custom_option('post_excerpt_maxlength'))).'</p>';
							}
						}
						
					?>
					</div>

				</div>
	
			<?php } ?>

		</<?php echo trim($tag); ?>>	<!-- /.post_item -->

	<?php
	}
}
?>