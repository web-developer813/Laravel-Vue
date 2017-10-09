@extends('app.layout')

@section('content')
	<h1>Forgot Your Password?</h1>
	<p>Enter your email below and we'll send you a link to reset your password.
		<br/>Remember your password? <a href="{{ route('login') }}">Login here</a></p>

	{{ Form::open(['route' => 'forgot-password', 'autocomplete' => 'off', 'novalidate', 'class' => 'js-form']) }}
		@include('app.components.form-field', [
			'field' => 'email', 'label' => 'Email',
			'input' => Form::email('email', old('email'))
		])
		<div class="form-field">
			<div class="row">
				<div class="col-md-6">
					<button type="submit" class="btn btn--primary btn--large btn--block">
						<i class="fa fa-spinner btn__loader" aria-hidden="true"></i>
						Reset Password
					</button>
				</div>
			</div>
		</div>
	{{ Form::close() }}
@stop