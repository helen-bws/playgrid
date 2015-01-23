jQuery(document).ready(function ($){
	// Add Color Picker to all inputs that have 'color-field' class
	//Template Todo: Convert code ro pure JS
	$('.al_colorpicker > input[type="text"]').wpColorPicker();
	$('.meta_color_picker').wpColorPicker();

	$('.al_admin_area .row-with-input').each(function(){
		var dismissButton = $('.fa-remove', this);
		var hiddenInput = $('input:hidden', this);
		var fileInput = $('input:file', this);
		var image = $('img', this);
		var labelThis = $('label', this);


		dismissButton.on('click', function(){
			hiddenInput.val("");
			image.removeAttr('src');
			image.removeAttr('alt');
			fileInput.val("");
			$(this).parents('.image-wrap').fadeOut(1000);
		});

		fileInput.on('change', function(e){
			var file = this.files;
			labelThis.after('<div class="uploaded-file-information">New Image: <b>'+file[0]['name']+'</b></div>')
		});
	});

	$('.al_checkbox').each(function(){
		$(this).find('input[type="checkbox"]').each(function(){
			$(this).on('change', function(){
				if($(this).prop('checked')){
					$(this).prev().find('.fa').removeClass('fa-toggle-off').addClass('fa-toggle-on');
				} else {
					$(this).prev().find('.fa').removeClass('fa-toggle-on').addClass('fa-toggle-off');
				}
			});

			if($(this).prop('checked')){
				$(this).prev().prepend('<i class="fa fa-toggle-on"></i>');
			} else {
				$(this).prev().prepend('<i class="fa fa-toggle-off"></i>');
			}
		});
	});
	$('label[for*="contact_map"]').each(function(){
		$(this).append(' <span class="fa fa-info-circle map-info-trigger"></span>');
	});

	$('.map-info-trigger').tooltip({
		html: true,
		title: 'Latitude Longitude coords. Comma separated',
		placement: 'bottom',
		template: '<div class="tooltip infiltrate-tooltip" role="tooltip">' +
		'<div class="tooltip-arrow"></div>' +
		'<div class="tooltip-inner"></div>' +
		'<div class="explain-map-content"></div>' +
		'</div>'
	});
});