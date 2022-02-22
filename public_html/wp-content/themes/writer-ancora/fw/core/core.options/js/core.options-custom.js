/* global jQuery:false */

jQuery(document).ready(function() {
	WRITER_ANCORA_STORAGE['media_frame'] = null;
	WRITER_ANCORA_STORAGE['media_link'] = '';
});

function writer_ancora_show_media_manager(el) {
	"use strict";

	WRITER_ANCORA_STORAGE['media_link'] = jQuery(el);
	// If the media frame already exists, reopen it.
	if ( WRITER_ANCORA_STORAGE['media_frame'] ) {
		WRITER_ANCORA_STORAGE['media_frame'].open();
		return false;
	}

	// Create the media frame.
	WRITER_ANCORA_STORAGE['media_frame'] = wp.media({
		// Set the title of the modal.
		title: WRITER_ANCORA_STORAGE['media_link'].data('choose'),
		// Tell the modal to show only images.
		library: {
			type: 'image'
		},
		// Multiple choise
		multiple: WRITER_ANCORA_STORAGE['media_link'].data('multiple')===true ? 'add' : false,
		// Customize the submit button.
		button: {
			// Set the text of the button.
			text: WRITER_ANCORA_STORAGE['media_link'].data('update'),
			// Tell the button not to close the modal, since we're
			// going to refresh the page when the image is selected.
			close: true
		}
	});

	// When an image is selected, run a callback.
	WRITER_ANCORA_STORAGE['media_frame'].on( 'select', function(selection) {
		"use strict";
		// Grab the selected attachment.
		var field = jQuery("#"+WRITER_ANCORA_STORAGE['media_link'].data('linked-field')).eq(0);
		var attachment = '';
		if (WRITER_ANCORA_STORAGE['media_link'].data('multiple')===true) {
			WRITER_ANCORA_STORAGE['media_frame'].state().get('selection').map( function( att ) {
				attachment += (attachment ? "\n" : "") + att.toJSON().url;
			});
			var val = field.val();
			attachment = val + (val ? "\n" : '') + attachment;
		} else {
			attachment = WRITER_ANCORA_STORAGE['media_frame'].state().get('selection').first().toJSON().url;
		}
		field.val(attachment);
		field.trigger('change');
	});

	// Finally, open the modal.
	WRITER_ANCORA_STORAGE['media_frame'].open();
	return false;
}
