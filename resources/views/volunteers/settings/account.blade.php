@extends('volunteers.layout')

@section('page_id', 'settings')

@section('page-header')
	<div class="page-header">
		<h2>Account Settings</h2>
	</div>
@stop

@section('content')
	<div class="two-columns">
		@include('volunteers.settings._sidebar')
		<div class="single-column single-column--box">
			{{ Form::open([
				'route' => 'settings.account', 'method' => 'put', 'files' => true,
				'autocomplete' => 'off', 'novalidate', 'class' => 'form--list js-form'
			]) }}
				@include('app.components.form-field', [
					'field' => 'email', 'label' => 'Email',
					'input' => Form::text('email', old('email') ?: $user->email, ['class' => 'form__field', 'placeholder' => 'Email'])
				])
				@include('app.components.form-field', [
					'field' => 'password', 'label' => 'New Password',
					'input' => Form::password('password', ['class' => 'form__field', 'autocomplete' => 'new-password', 'placeholder' => 'New password'])
				])
				<div class="form__footer">
					<button type="submit" class="btn btn--primary btn--large btn--block">
						<i class="fa fa-spinner btn__loader" aria-hidden="true"></i>
						Save Settings
					</button>
				</div>
			{{ Form::close() }}
		</div>
	</div>
@stop