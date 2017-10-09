@extends('app.emails.master')

@section('title', 'We received a request to reset the password for your account.')

@section('content')
	@include('app.components.emails.p', [
		'content' => "Hi {$name},"
	])
	@include('app.components.emails.p', [
		'content' => "If you requested a reset for your Tecdonor password, click the button below. If you didn’t make this request, please ignore this email."
	])
	@include('app.components.emails.p', [
		'content' => "This link will expire in one hour."
	])
	@include('app.components.emails.button', [
		'url' => route('reset-password', ['resetToken' => $token, 'email' => urlencode($email)]),
		'label' => "Reset Password"
	])
@stop