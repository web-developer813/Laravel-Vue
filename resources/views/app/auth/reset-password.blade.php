@extends('app.layout')

@section('content')
	<h1>Choose a New Password</h1>
	<p>Please choose a new password.
		<br/>Remember your password? <a href={{ route('login') }}>Login here</a></p>
	{{ Form::open(['route' => 'reset-password', 'autocomplete' => 'off', 'novalidate', 'class' => 'js-form']) }}
		{{ Form::hidden('email', urldecode(request()->input('email'))) }}
		{{ Form::hidden('resetToken', request()->input('resetToken')) }}
		@include('app.components.form-field', [
			'field' => 'password', 'label' => 'Password',
			'input' => Form::password('password', ['autocomplete' => 'new-password'])
		])
		<div class="form-field">
			<div class="row">
				<div class="col-md-6">
					<button type="submit" class="btn btn--primary btn--large btn--block">
						<i class="fa fa-spinner btn__loader" aria-hidden="true"></i>
						Update Password
					</button>
				</div>
	            <div class="col-md-6">
	            	<a href={{ route('forgot-password') }} class="btn btn--link btn--large btn--block">Try Again</a>
	            </div>
			</div>
		</div>
	{{ Form::close() }}
@stop