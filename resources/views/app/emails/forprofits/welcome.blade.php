@extends('app.emails.master')

@section('title', "Welcome to Tecdonor {$forprofit->name}!")

@section('content')
	@include('app.components.emails.p', [
		'content' => "Hi,"
	])
	@include('app.components.emails.p', [
		'content' => "Thanks for signing up to Tecdonor! As our corporate partner, you are a valued contributor and a pioneer in setting a new industry standard for corporate social responsibility."
	])
	@include('app.components.emails.p', [
		'content' => "Your efforts are greatly appreciated and won’t go unnoticed by our rapidly growing community of volunteers and nonprofit organizations."
	])
	@include('app.components.emails.p', [
		'content' => "To take full advantage of our platform, we encourage you to fill out your profile, invite your employees, set your monthly donation treshhold and create incentives that volunteers can purchase with their points."
	])
	@include('app.components.emails.button', [
		'url' => route('switch-mode', ['mode' => 'forprofit', 'forprofit' => $forprofit->id]),
		'label' => "Get Started!"
	])
	@include('app.components.emails.p', [
		'content' => "If you have any questions, please reply to this email."
	])
	@include('app.components.emails.p', [
		'content' => "<em>&mdash; The Tecdonor Team</em>"
	])
@stop