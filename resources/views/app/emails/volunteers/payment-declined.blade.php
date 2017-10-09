@extends('app.emails.master')

@section('title', "Your payment to Tecdonor has been declined")

@section('content')
	@include('app.components.emails.p', [
		'content' => "Hi {$billable->name},"
	])
	@include('app.components.emails.p', [
		'content' => "We've been unable to process your payment for Tecdonor using your credit card on file. For your account to stay active, can you please take a moment to update your payment information on our site?."
	])
	@include('app.components.emails.button', [
		'url' => route('settings.billing'),
		'label' => "Update Payment Information"
	])
	@include('app.components.emails.p', [
		'content' => "If you have any questions, please reply to this email."
	])
	@include('app.components.emails.p', [
		'content' => "<em>&mdash; The Tecdonor Team</em>"
	])
@stop