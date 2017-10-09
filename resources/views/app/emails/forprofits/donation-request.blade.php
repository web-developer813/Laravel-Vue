@extends('app.emails.master')

@section('title', "You received a donation request from {$nonprofit->name}")

@section('content')
	@include('app.components.emails.p', [
		'content' => "{$forprofit->name},"
	])
	@include('app.components.emails.p', [
		'content' => "Your company received a donation request from <em>{$nonprofit->name}</em> in products and services to the amount of " . pluralize('point', $donation->points) . "."
	])
	@include('app.components.emails.p', [
		'content' => "Please contact <em>{$nonprofit->name}</em> and make arrangements to fulfill this request."
	])
	@include('app.components.emails.button', [
		'url' => route('switch-mode', ['mode' => 'forprofit', 'forprofit' => $forprofit->id, 'next' => route('forprofits.donations.edit', [$forprofit->id, $donation->id], false)]),
		'label' => "Help {$nonprofit->name}"
	])
	@include('app.components.emails.p', [
		'content' => "Thank you for your involvement with Tecdonor."
	])
	@include('app.components.emails.p', [
		'content' => "If you have any questions, please reply to this email."
	])
	@include('app.components.emails.p', [
		'content' => "<em>&mdash; The Tecdonor Team</em>"
	])
@stop