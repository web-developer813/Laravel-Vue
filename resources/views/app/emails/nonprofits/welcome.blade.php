@extends('app.emails.master')

@section('title', "Welcome to Tecdonor {$nonprofit->name}!")

@section('content')
	@include('app.components.emails.p', [
		'content' => "Hi,"
	])
	@include('app.components.emails.p', [
		'content' => "Thanks for signing up to Tecdonor! As a valued member of our community, we are committed to helping your mission by connecting your organization with our network volunteers and corporate partners."
	])
	@include('app.components.emails.p', [
		'content' => "To take full advantage of our platform, we encourage you to fill out your profile, invite your employees and volunteers, and create your first volunteer opportunity."
	])
	@include('app.components.emails.button', [
		'url' => route('switch-mode', ['mode' => 'nonprofit', 'nonprofit' => $nonprofit->id]),
		'label' => "Get Started!"
	])
	@include('app.components.emails.p', [
		'content' => "If you have any questions, please reply to this email."
	])
	@include('app.components.emails.p', [
		'content' => "<em>&mdash; The Tecdonor Team</em>"
	])
@stop