@extends('app.layout')

@section('content')
	<h1>Check Your Email</h1>
	<p>We've sent an email to <strong>{{ request()->input('email', 'your email address') }}</strong>. Click the link in the email to reset your password.</p>
	<p>If you don't see the email, check other places it might be, like your junk, spam, social, or other folders.</p>
	<p><a href="{{ route('forgot-password') }}">I didn't receive the email</a></p>
@stop