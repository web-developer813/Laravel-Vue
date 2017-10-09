@extends('app.emails.master')

@section('title', 'Welcome to Tecdonor!')

@section('content')
	@include('app.components.emails.p', [
		'content' => "Hi {$volunteer->name},"
	])
	@include('app.components.emails.p', [
		'content' => "Thanks for signing up to Tecdonor! As a new volunteer, your efforts are greatly appreciated. You are what drives our mission forward!"
	])
	@include('app.components.emails.p', [
		'content' => "Tecdonor is the first rewards program for volunteers. With every hour you contribute to a nonprofit organization, you earn reward points which can be spent with our corporate partners or donated back to a nonprofit."
	])
	@include('app.components.emails.p', [
		'content' => "To take full advantage of our platform, we encourage you to fill out your profile and look for volunteer opportunities from our rapidly growing community of nonprofit organizations."
	])
	@include('app.components.emails.button', [
		'url' => route('newsfeed'),
		'label' => "Get Started!"
	])
	@include('app.components.emails.p', [
		'content' => "If you have any questions, please reply to this email."
	])
	@include('app.components.emails.p', [
		'content' => "<em>&mdash; The Tecdonor Team</em>"
	])
@stop