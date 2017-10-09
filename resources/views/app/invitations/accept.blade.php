@extends('app.layout')

@section('content')
	<h1>You were invited to Tecdonor</h1>
	<p><strong>{{ $inviter->name }}</strong> would like to invite you to join them on Tecdonor.</p>
	<p>To accept their invitation, please login to your Tecdonor account. If you don't have an account, you can sign up for for free.</p>

	<a href="{{ route('register') }}" class="btn btn--primary btn--large">Sign Up</a>
	<a href="{{ route('login') }}" class="btn btn--default btn--large">Login</a>
@stop