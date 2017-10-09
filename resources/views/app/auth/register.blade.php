@extends('app.layout')

@section('content')
	<h1>Get involved for free today</h1>
	<p>Create a free tecdonor account to browse volunteer opportunities and earn rewards for your time and effort. Already have a tecdonor account? <a href="{{ route('login') }}">Log in here</a></p>
	
	{{ Form::open([
		'route' => 'register', 'autocomplete' => 'off', 'novalidate', 'class' => 'js-form'
	]) }}
		@include('app.components.form-field', [
			'field' => 'name', 'label' => 'Your Full Name',
			'input' => Form::text('name', old('name'))
		])
		@include('app.components.form-field', [
			'field' => 'email', 'label' => 'Email',
			'input' => Form::email('email')
		])
		@include('app.components.form-field', [
			'field' => 'password', 'label' => 'Password',
			'input' => Form::password('password', ['autocomplete' => 'new-password'])
		])
		<div class="form-field">
			<div class="row">
				<div class="col-md-6">
					<button type="submit" class="btn btn--primary btn--large btn--block">
						<i class="fa fa-spinner btn__loader" aria-hidden="true"></i>
						Get Started!
					</button>
				</div>
				<div class="col-md-6">
					<p class="small-meta">By clicking this button, you agree to Tecdonors's Privacy Policy and Terms of Use.</p>
				</div>
			</div>
		</div>
	{{ Form::close() }}
@stop