jQuery(document).ready(function($){

	var optionsmenu_upload;
	var optionsmenu_selector;

	function optionsmenu_add_file(event, selector) {

		var upload = $(".uploaded-file"), frame;
		var $el = $(this);
		optionsmenu_selector = selector;

		event.preventDefault();

		// If the media frame already exists, reopen it.
		if ( optionsmenu_upload ) {
			optionsmenu_upload.open();
		} else {
			// Create the media frame.
			optionsmenu_upload = wp.media.frames.optionsmenu_upload =  wp.media({
				// Set the title mtm the modal.
				title: $el.data('choose'),

				// Customize the submit button.
				button: {
					// Set the text mtm the button.
					text: $el.data('update'),
					// Tell the button not to close the modal, since we're
					// going to refresh the page when the image is selected.
					close: false
				}
			});

			// When an image is selected, run a callback.
			optionsmenu_upload.on( 'select', function() {
				// Grab the selected attachment.
				var attachment = optionsmenu_upload.state().get('selection').first();
				optionsmenu_upload.close();
				optionsmenu_selector.find('.upload').val(attachment.attributes.url);
				if ( attachment.attributes.type == 'image' ) {
					optionsmenu_selector.find('.screenshot').empty().hide().append('<img src="' + attachment.attributes.url + '"><div><a class="remove-image">Remove</a></div>').slideDown('fast');
				}
				optionsmenu_selector.find('.upload-button').unbind().addClass('remove-file').removeClass('upload-button').val(mtm_optionsmenu_l10n.remove);
				optionsmenu_selector.find('.mtm-background-properties').slideDown();
				optionsmenu_selector.find('.remove-image, .remove-file').on('click', function() {
					optionsmenu_remove_file( $(this).parents('.section') );
				});
			});

		}

		// Finally, open the modal.
		optionsmenu_upload.open();
	}

	function optionsmenu_remove_file(selector) {
		selector.find('.remove-image').hide();
		selector.find('.upload').val('');
		selector.find('.mtm-background-properties').hide();
		selector.find('.screenshot').slideUp();
		selector.find('.remove-file').unbind().addClass('upload-button').removeClass('remove-file').val(mtm_optionsmenu_l10n.upload);
		// We don't display the upload button if .upload-notice is present
		// This means the user doesn't have the WordPress 3.5 Media Library Support
		if ( $('.section-upload .upload-notice').length > 0 ) {
			$('.upload-button').remove();
		}
		selector.find('.upload-button').on('click', function(event) {
			optionsmenu_add_file(event, $(this).parents('.section'));
		});
	}

	$('.remove-image, .remove-file').on('click', function() {
		optionsmenu_remove_file( $(this).parents('.section') );
    });

    $('.upload-button').click( function( event ) {
    	optionsmenu_add_file(event, $(this).parents('.section'));
    });

});