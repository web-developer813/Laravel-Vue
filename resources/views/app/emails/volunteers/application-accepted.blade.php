@extends('app.emails.master')

@section('title', "Your application to {$opportunity->title} has been accepted!")

@section('content')
	@include('app.components.emails.p', [
		'content' => "Hi {$volunteer->name},"
	])
	@include('app.components.emails.p', [
		'content' => "Congrats! <em>{$nonprofit->name}</em> has accepted your application to <em>{$opportunity->title}</em>."
	])
	@include('app.components.emails.button', [
		'url' => route('volunteers.applications.show', $application->id),
		'label' => "View Application"
	])
	@include('app.components.emails.p', [
		'content' => "If you have any questions, please reply to this email."
	])
	@include('app.components.emails.p', [
		'content' => "<em>&mdash; The Tecdonor Team</em>"
	])
@stop