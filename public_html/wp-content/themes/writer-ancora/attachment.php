<?php
/**
Template Name: Attachment page
 */
get_header(); 

while ( have_posts() ) { the_post();

	// Move writer_ancora_set_post_views to the javascript - counter will work under cache system
	if (writer_ancora_get_custom_option('use_ajax_views_counter')=='no') {
		writer_ancora_set_post_views(get_the_ID());
	}

	writer_ancora_show_post_layout(
		array(
			'layout' => 'attachment',
			'sidebar' => !writer_ancora_param_is_off(writer_ancora_get_custom_option('show_sidebar_main'))
		)
	);

}

get_footer();
?>