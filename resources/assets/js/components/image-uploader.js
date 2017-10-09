$(document).ready(function() {
	$('.js-image-uploader').on('click', function(e) {
		var name = $(this).data('name')
		var newFileInput = $('<input name="' + name + '" type="file" class="js-file-input">')
		$(this).next('.js-file-input').remove()
		$(this).after(newFileInput)
		newFileInput.click()
	});
	$(document).on('change', '.js-file-input', function(e) {
		var uploader = $(this);
		var files = uploader.prop('files')
		var reader = new FileReader();

		if (!files.length) return

		reader.onload = function (e) {
			var base64 = e.target.result
			uploader.prev('.js-image-uploader').css('background-image', 'url("' + base64 + '")');
		}
		reader.readAsDataURL(files[0]);
	});
});