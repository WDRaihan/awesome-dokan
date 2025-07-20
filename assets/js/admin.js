jQuery(document).ready(function ($) {

	$('#upload_custom_logo').on('click', function (e) {
		e.preventDefault();
		var custom_uploader = wp.media({
				multiple: false
			})
			.on('select', function () {
				var attachment = custom_uploader.state().get('selection').first().toJSON();
				$('#custom_logo').val(attachment.url);
				$('#custom_logo_preview').html('<img src="'+attachment.url+'" style="max-height: 50px;" />');
			})
			.open();
	});
});