<?php
/**
 * Writer Ancora Framework: messages subsystem
 *
 * @package	writer_ancora
 * @since	writer_ancora 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Theme init
if (!function_exists('writer_ancora_messages_theme_setup')) {
	add_action( 'writer_ancora_action_before_init_theme', 'writer_ancora_messages_theme_setup' );
	function writer_ancora_messages_theme_setup() {
		// Core messages strings
		add_action('writer_ancora_action_add_scripts_inline', 'writer_ancora_messages_add_scripts_inline');
	}
}


/* Session messages
------------------------------------------------------------------------------------- */

if (!function_exists('writer_ancora_get_error_msg')) {
	function writer_ancora_get_error_msg() {
		return writer_ancora_storage_get('error_msg');
	}
}

if (!function_exists('writer_ancora_set_error_msg')) {
	function writer_ancora_set_error_msg($msg) {
		$msg2 = writer_ancora_get_error_msg();
		writer_ancora_storage_set('error_msg', trim($msg2) . ($msg2=='' ? '' : '<br />') . trim($msg));
	}
}

if (!function_exists('writer_ancora_get_success_msg')) {
	function writer_ancora_get_success_msg() {
		return writer_ancora_storage_get('success_msg');
	}
}

if (!function_exists('writer_ancora_set_success_msg')) {
	function writer_ancora_set_success_msg($msg) {
		$msg2 = writer_ancora_get_success_msg();
		writer_ancora_storage_set('success_msg', trim($msg2) . ($msg2=='' ? '' : '<br />') . trim($msg));
	}
}

if (!function_exists('writer_ancora_get_notice_msg')) {
	function writer_ancora_get_notice_msg() {
		return writer_ancora_storage_get('notice_msg');
	}
}

if (!function_exists('writer_ancora_set_notice_msg')) {
	function writer_ancora_set_notice_msg($msg) {
		$msg2 = writer_ancora_get_notice_msg();
		writer_ancora_storage_set('notice_msg', trim($msg2) . ($msg2=='' ? '' : '<br />') . trim($msg));
	}
}


/* System messages (save when page reload)
------------------------------------------------------------------------------------- */
if (!function_exists('writer_ancora_set_system_message')) {
	function writer_ancora_set_system_message($msg, $status='info', $hdr='') {
		update_option('writer_ancora_message', array('message' => $msg, 'status' => $status, 'header' => $hdr));
	}
}

if (!function_exists('writer_ancora_get_system_message')) {
	function writer_ancora_get_system_message($del=false) {
		$msg = get_option('writer_ancora_message', false);
		if (!$msg)
			$msg = array('message' => '', 'status' => '', 'header' => '');
		else if ($del)
			writer_ancora_del_system_message();
		return $msg;
	}
}

if (!function_exists('writer_ancora_del_system_message')) {
	function writer_ancora_del_system_message() {
		delete_option('writer_ancora_message');
	}
}


/* Messages strings
------------------------------------------------------------------------------------- */

if (!function_exists('writer_ancora_messages_add_scripts_inline')) {
	function writer_ancora_messages_add_scripts_inline() {
		echo '<script type="text/javascript">'
			
			. "if (typeof WRITER_ANCORA_STORAGE == 'undefined') var WRITER_ANCORA_STORAGE = {};"
			
			// Strings for translation
			. 'WRITER_ANCORA_STORAGE["strings"] = {'
				. 'ajax_error: 			"' . addslashes(esc_html__('Invalid server answer', 'writer-ancora')) . '",'
				. 'bookmark_add: 		"' . addslashes(esc_html__('Add the bookmark', 'writer-ancora')) . '",'
				. 'bookmark_added:		"' . addslashes(esc_html__('Current page has been successfully added to the bookmarks. You can see it in the right panel on the tab \'Bookmarks\'', 'writer-ancora')) . '",'
				. 'bookmark_del: 		"' . addslashes(esc_html__('Delete this bookmark', 'writer-ancora')) . '",'
				. 'bookmark_title:		"' . addslashes(esc_html__('Enter bookmark title', 'writer-ancora')) . '",'
				. 'bookmark_exists:		"' . addslashes(esc_html__('Current page already exists in the bookmarks list', 'writer-ancora')) . '",'
				. 'search_error:		"' . addslashes(esc_html__('Error occurs in AJAX search! Please, type your query and press search icon for the traditional search way.', 'writer-ancora')) . '",'
				. 'email_confirm:		"' . addslashes(esc_html__('On the e-mail address "%s" we sent a confirmation email. Please, open it and click on the link.', 'writer-ancora')) . '",'
				. 'reviews_vote:		"' . addslashes(esc_html__('Thanks for your vote! New average rating is:', 'writer-ancora')) . '",'
				. 'reviews_error:		"' . addslashes(esc_html__('Error saving your vote! Please, try again later.', 'writer-ancora')) . '",'
				. 'error_like:			"' . addslashes(esc_html__('Error saving your like! Please, try again later.', 'writer-ancora')) . '",'
				. 'error_global:		"' . addslashes(esc_html__('Global error text', 'writer-ancora')) . '",'
				. 'name_empty:			"' . addslashes(esc_html__('The name can\'t be empty', 'writer-ancora')) . '",'
				. 'name_long:			"' . addslashes(esc_html__('Too long name', 'writer-ancora')) . '",'
				. 'email_empty:			"' . addslashes(esc_html__('Too short (or empty) email address', 'writer-ancora')) . '",'
				. 'email_long:			"' . addslashes(esc_html__('Too long email address', 'writer-ancora')) . '",'
				. 'email_not_valid:		"' . addslashes(esc_html__('Invalid email address', 'writer-ancora')) . '",'
				. 'subject_empty:		"' . addslashes(esc_html__('The subject can\'t be empty', 'writer-ancora')) . '",'
				. 'subject_long:		"' . addslashes(esc_html__('Too long subject', 'writer-ancora')) . '",'
				. 'text_empty:			"' . addslashes(esc_html__('The message text can\'t be empty', 'writer-ancora')) . '",'
				. 'text_long:			"' . addslashes(esc_html__('Too long message text', 'writer-ancora')) . '",'
				. 'send_complete:		"' . addslashes(esc_html__("Send message complete!", 'writer-ancora')) . '",'
				. 'send_error:			"' . addslashes(esc_html__('Transmit failed!', 'writer-ancora')) . '",'
				. 'login_empty:			"' . addslashes(esc_html__('The Login field can\'t be empty', 'writer-ancora')) . '",'
				. 'login_long:			"' . addslashes(esc_html__('Too long login field', 'writer-ancora')) . '",'
				. 'login_success:		"' . addslashes(esc_html__('Login success! The page will be reloaded in 3 sec.', 'writer-ancora')) . '",'
				. 'login_failed:		"' . addslashes(esc_html__('Login failed!', 'writer-ancora')) . '",'
				. 'password_empty:		"' . addslashes(esc_html__('The password can\'t be empty and shorter then 4 characters', 'writer-ancora')) . '",'
				. 'password_long:		"' . addslashes(esc_html__('Too long password', 'writer-ancora')) . '",'
				. 'password_not_equal:	"' . addslashes(esc_html__('The passwords in both fields are not equal', 'writer-ancora')) . '",'
				. 'registration_success:"' . addslashes(esc_html__('Registration success! Please log in!', 'writer-ancora')) . '",'
				. 'registration_failed:	"' . addslashes(esc_html__('Registration failed!', 'writer-ancora')) . '",'
				. 'geocode_error:		"' . addslashes(esc_html__('Geocode was not successful for the following reason:', 'writer-ancora')) . '",'
				. 'googlemap_not_avail:	"' . addslashes(esc_html__('Google map API not available!', 'writer-ancora')) . '",'
				. 'editor_save_success:	"' . addslashes(esc_html__("Post content saved!", 'writer-ancora')) . '",'
				. 'editor_save_error:	"' . addslashes(esc_html__("Error saving post data!", 'writer-ancora')) . '",'
				. 'editor_delete_post:	"' . addslashes(esc_html__("You really want to delete the current post?", 'writer-ancora')) . '",'
				. 'editor_delete_post_header:"' . addslashes(esc_html__("Delete post", 'writer-ancora')) . '",'
				. 'editor_delete_success:	"' . addslashes(esc_html__("Post deleted!", 'writer-ancora')) . '",'
				. 'editor_delete_error:		"' . addslashes(esc_html__("Error deleting post!", 'writer-ancora')) . '",'
				. 'editor_caption_cancel:	"' . addslashes(esc_html__('Cancel', 'writer-ancora')) . '",'
				. 'editor_caption_close:	"' . addslashes(esc_html__('Close', 'writer-ancora')) . '"'
				. '};'
			
			. '</script>';
	}
}
?>