@if (session()->has('flash_notification.message'))
    <div class="js-notification notification notification--{{ session('flash_notification.level') }}">
    	<i class="fa fa-{{ session('flash_notification.level') == 'success' ? 'check' : 'times'}}" aria-hidden="true"></i>
        {!! session('flash_notification.message') !!}
    </div>
@elseif($errors->count())
    <div class="js-notification notification notification--error">
    	<i class="fa fa-times" aria-hidden="true"></i>
        Please fix the errors below and try again.
    </div>
@endif