@extends('app.emails.master')

@section('title', 'The coupon your purchased is ready for download')

@section('content')
	@include('app.components.emails.p', [
		'content' => "Hi {$volunteer->name},"
	])
	@include('app.components.emails.p', [
		'content' => "The coupon you purchased from <em>{$forprofit->name}</em> is ready for download."
	])
	@include('app.components.emails.button', [
		'url' => route('incentive-purchases.show', $purchase->id),
		'label' => "Download"
	])
	@include('app.components.emails.p', [
		'content' => "If you have any questions, please reply to this email."
	])
	@include('app.components.emails.p', [
		'content' => "<em>&mdash; The Tecdonor Team</em>"
	])
@stop