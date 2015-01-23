jQuery(document).ready(function($) {
	$('.al-meta-img-container-wrap').each(function() {
		var thisCheckbox = $('input[type="checkbox"]', this);
		var thisLabel = $('label', this);
		thisCheckbox.on('change', function() {
			if ($(this).is(':checked')) {
				$(this).parent().append('<div class="image-curtain"><p>Image will be removed after saving post</p></div>');
			} else {
				$(this).parent().find('.image-curtain').remove();
			}
		});
	});

	$('.al-filemeta-upload-wrap').each(function() {
		$('input:file', this).on('change', function(e) {
			var file = this.files;
			$(this).parent().find('.uploaded-file-information').remove();
			$('.label-meta-upload-cp').after('<div class="uploaded-file-information">New Image: <b>'+file[0]['name']+'</b></div>')
		});
	});
});