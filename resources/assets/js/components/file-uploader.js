$(document).ready(function() {
	$('.js-file-uploader').on('click', function(e) {
		var name = $(this).data('name')
		var newFileInput = $('<input name="' + name + '" type="file" class="js-file-input">')
		$(this).next('.js-file-input').remove()
		$(this).after(newFileInput)
		newFileInput.click()
	});
	$(document).on('change', '.js-file-input', function(e) {
		var uploader = $(this);
		var filename = uploader.val() ? uploader.val().split('\\').pop() : 'No file selected'
		uploader.nextAll('.file-selected:first').html(filename)
	});
});