@if (session()->has('flash_notification.message'))
	<script>
		toastr.{{ session('flash_notification.level') }}('{!! session('flash_notification.message') !!}','',{
			"positionClass": "toast-top-full-width",
			"closeButton": true,
			"timeOut": "0",
		});
	</script>
@elseif($errors->count())
	<script>
		toastr.error('Please fix the errors and try again.','',{
			"positionClass": "toast-top-full-width",
			"closeButton": true,
			"timeOut": "0",
		});
	</script>
@endif