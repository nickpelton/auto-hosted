/**
 * Metabox file Uploader
 */
jQuery(document).ready(function ($) {
	'use strict';

	var formfield;

	/**
	 * File and image upload handling
	 */
	$('.ahs_upload_file').change(function () {
		formfield = $(this).attr('name');
		$('#' + formfield + '_id').val("");
	});

	$('.ahs_upload_button').live('click', function () {
		var buttonLabel;
		formfield = $(this).prev('input').attr('name');
		buttonLabel = 'Use as ' + $('label[for=' + formfield + ']').text();
		tb_show('', 'media-upload.php?post_id=' + $('#post_ID').val() + '&type=file&ahs_force_send=true&ahs_send_label=' + buttonLabel + '&TB_iframe=true');
		return false;
	});

	$('.ahs_remove_file_button').live('click', function () {
		formfield = $(this).attr('rel');
		$('input#' + formfield).val('');
		$('input#' + formfield + '_id').val('');
		$(this).parent().remove();
		return false;
	});

	window.ahsrestore_send_to_editor = window.send_to_editor;
    window.send_to_editor = function (html) {
		var itemurl, itemclass, itemClassBits, itemid, htmlBits, itemtitle,
			image, uploadStatus = true;

		if (formfield) {

	        if ($(html).html(html).find('img').length > 0) {
				itemurl = $(html).html(html).find('img').attr('src'); // Use the URL to the size selected.
				itemclass = $(html).html(html).find('img').attr('class'); // Extract the ID from the returned class name.
				itemClassBits = itemclass.split(" ");
				itemid = itemClassBits[itemClassBits.length - 1];
				itemid = itemid.replace('wp-image-', '');
	        } else {
				// It's not an image. Get the URL to the file instead.
				htmlBits = html.split("'"); // jQuery seems to strip out XHTML when assigning the string to an object. Use alternate method.
				itemurl = htmlBits[1]; // Use the URL to the file.
				itemtitle = htmlBits[2];
				itemtitle = itemtitle.replace('>', '');
				itemtitle = itemtitle.replace('</a>', '');
				itemid = ""; // TO DO: Get ID for non-image attachments.
			}

			image = /(zip)$/gi;

			if (itemurl.match(image)) {
				html = '<a href="' + itemurl + '" target="_blank" rel="external">View File</a>';
				uploadStatus = '<div class="no_image"><span class="file_link">' + html + '</span>&nbsp;&nbsp;&nbsp;<a href="#" class="ahs_remove_file_button" rel="' + formfield + '">Remove</a></div>';
			} else {
				// If it's not zip file, add notice.
				html = '<span style="color:red">Only zip file is supported</span> ';
				uploadStatus = '<div class="no_image"><span class="file_link">' + html + '</span>&nbsp;&nbsp;&nbsp;<a href="#" class="ahs_remove_file_button" rel="' + formfield + '">Remove</a></div>';
			}

			$('#' + formfield).val(itemurl);
			$('#' + formfield + '_id').val(itemid);
			$('#' + formfield).siblings('.ahs_upload_status').slideDown().html(uploadStatus);
			tb_remove();

		} else {
			window.ahsrestore_send_to_editor(html);
		}

		formfield = '';
	};
});