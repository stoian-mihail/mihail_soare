<?php
/**
 * The Header for our theme.
 */

// Theme init - don't remove next row! Load custom options
writer_ancora_core_init_theme();

$blog_style = writer_ancora_get_custom_option(is_singular() && !writer_ancora_storage_get('blog_streampage') ? 'single_style' : 'blog_style');
$article_style = writer_ancora_get_custom_option('article_style');
$top_panel_style = writer_ancora_get_custom_option('top_panel_style');
$body_scheme = writer_ancora_get_custom_option('body_scheme');
if (empty($body_scheme)  || writer_ancora_is_inherit_option($body_scheme)) $body_scheme = 'original';

?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="<?php echo esc_attr('scheme_'.$body_scheme); ?>">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1<?php if (writer_ancora_get_theme_option('responsive_layouts')=='yes') echo ', maximum-scale=1'; ?>">
	<meta name="format-detection" content="telephone=no">

	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
    
    <?php	wp_head();	?>
</head>

<body <?php body_class();?>>
	<?php 
	writer_ancora_profiler_add_point(esc_html__('Before Theme HTML output', 'writer-ancora'));
	writer_ancora_profiler_add_point(esc_html__('BODY start', 'writer-ancora'));
	
	echo force_balance_tags(writer_ancora_get_custom_option('gtm_code'));

	do_action( 'before' );
	?>

	<?php
	// Add TOC items 'Home' and "To top"
	writer_ancora_toc_home(); 
	?>

	<?php if ( !writer_ancora_param_is_off(writer_ancora_get_custom_option('show_sidebar_outer')) ) { ?>
	<div class="outer_wrap">
	<?php } ?>

	<?php require_once writer_ancora_get_file_dir('sidebar_outer.php'); ?>
	
	<?php $body_classes = writer_ancora_body_wrap_classes(); ?>

	<div class="body_wrap<?php if (writer_ancora_get_custom_option('one_bg_color')=='no') { echo (' with_two_bg_color'); } ?> <?php echo !empty($body_classes['class']) ? ' '.esc_attr($body_classes['class']) : ''; ?>"<?php echo !empty($body_classes['style']) ? ' style="'.esc_attr($body_classes['style']).'"' : ''; ?>>

		<?php

		$video_bg_show  = writer_ancora_get_custom_option('show_video_bg')=='yes' && (writer_ancora_get_custom_option('video_bg_youtube_code')!='' || writer_ancora_get_custom_option('video_bg_url')!='');
		if ($video_bg_show) {
			$youtube = writer_ancora_get_custom_option('video_bg_youtube_code');
			$video   = writer_ancora_get_custom_option('video_bg_url');
			$overlay = writer_ancora_get_custom_option('video_bg_overlay')=='yes';
			if (!empty($youtube)) {
				?>
				<div class="video_bg<?php echo !empty($overlay) ? ' video_bg_overlay' : ''; ?>" data-youtube-code="<?php echo esc_attr($youtube); ?>"></div>
				<?php
			} else if (!empty($video)) {
				$info = pathinfo($video);
				$ext = !empty($info['extension']) ? $info['extension'] : 'src';
				?>
				<div class="video_bg<?php echo !empty($overlay) ? ' video_bg_overlay' : ''; ?>"><video class="video_bg_tag" width="1280" height="720" data-width="1280" data-height="720" data-ratio="16:9" preload="metadata" autoplay loop src="<?php echo esc_url($video); ?>"><source src="<?php echo esc_url($video); ?>" type="video/<?php echo esc_attr($ext); ?>"></source></video></div>
				<?php
			}
		}
		?>

		<div class="page_wrap">

			<?php
			writer_ancora_profiler_add_point(esc_html__('Before Page Header', 'writer-ancora'));

			$top_panel_scheme = writer_ancora_get_custom_option('top_panel_scheme');
			$top_panel_position = writer_ancora_get_custom_option('top_panel_position');
			// Top panel 'Above' or 'Over'
			if (in_array($top_panel_position, array('above', 'over'))) {
				writer_ancora_show_post_layout(array(
					'layout' => $top_panel_style,
					'position' => $top_panel_position,
					'scheme' => $top_panel_scheme
					), false);
				writer_ancora_profiler_add_point(esc_html__('After show menu', 'writer-ancora'));
			}
			// Slider
			require_once writer_ancora_get_file_dir('templates/headers/_parts/slider.php');
			// Top panel 'Below'
			if ($top_panel_position == 'below') {
				writer_ancora_show_post_layout(array(
					'layout' => $top_panel_style,
					'position' => $top_panel_position,
					'scheme' => $top_panel_scheme
					), false);
				writer_ancora_profiler_add_point(esc_html__('After show menu', 'writer-ancora'));
			}

			// Top of page section: page title and breadcrumbs
			$show_title = writer_ancora_get_custom_option('show_page_title')=='yes';
			$show_breadcrumbs = writer_ancora_get_custom_option('show_breadcrumbs')=='yes';
			if ($show_title || $show_breadcrumbs) {
				?>
				<div class="top_panel_title top_panel_style_<?php echo esc_attr(str_replace('header_', '', $top_panel_style)); ?> <?php echo (!empty($show_title) ? ' title_present' : '') . (!empty($show_breadcrumbs) ? ' breadcrumbs_present' : ''); ?> scheme_<?php echo esc_attr($top_panel_scheme); ?>">
					<div class="top_panel_title_inner top_panel_inner_style_<?php echo esc_attr(str_replace('header_', '', $top_panel_style)); ?> <?php echo (!empty($show_title) ? ' title_present_inner' : '') . (!empty($show_breadcrumbs) ? ' breadcrumbs_present_inner' : ''); ?>">
						<div class="content_wrap">
							<?php if ($show_title) { ?>
								<h1 class="page_title"><?php echo strip_tags(writer_ancora_get_blog_title()); ?></h1>
							<?php } ?>
							<?php if ($show_breadcrumbs) { ?>
								<div class="breadcrumbs">
									<?php if (!is_404()) writer_ancora_show_breadcrumbs(); ?>
								</div>
							<?php } ?>
						</div>
					</div>
				</div>
				<?php
			}
			?>
			
		


			<div class="page_content_wrap page_paddings_<?php echo esc_attr(writer_ancora_get_custom_option('body_paddings')); ?>">
					


				<?php
				writer_ancora_profiler_add_point(esc_html__('Before Page content', 'writer-ancora'));
				$body_style  = writer_ancora_get_custom_option('body_style');
				// Content and sidebar wrapper
				if ($body_style!='fullscreen') writer_ancora_open_wrapper('<div class="content_wrap">');
				
				//*+ Woo content
				if (function_exists('is_shop')) {
                    if (file_exists(writer_ancora_get_file_dir('templates/_parts/woo-content.php')) && is_shop()) {
                       require_once writer_ancora_get_file_dir('templates/_parts/woo-content.php');
                    }
                }

				// Main content wrapper
				writer_ancora_open_wrapper('<div class="content">');



				?>