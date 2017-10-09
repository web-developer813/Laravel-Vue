@extends('app.layout')

@section('body_class','animsition page-login-v2 layout-full page-dark')

@section('content')
<div class="page-brand-info">
	<div class="brand">
		<img class="brand-img" src="/img/tecdonor-logo@2x.png" alt="Tecdonor">
	</div>
	<p class="font-size-20">Your are just moments away from entering a world where volunteers frolic through fields of green grass and opportunities abound... well, maybe not that but it is a really cool tool!</p>
</div>
<div class="page-login-main">
	<div class="brand hidden-md-up">
		<img class="brand-img" src="/img/tecdonor-logo@2x.png" alt="Tecdonor">
		<h3 class="brand-text font-size-40">Tecdonor</h3>
	</div>
	<h3 class="font-size-24">Sign In</h3>
	<p>Log in to your tecdonor account with your email and password.</p>
	{{ Form::open(array('url' => secure_url(URL::route('login','',false)), 'novalidate', 'class' => 'login-form')) }}
		<div class="form-group form-material floating" data-plugin="formMaterial">
			<input type="email" class="form-control empty" id="inputEmail" name="email">
			<label class="floating-label" for="inputEmail">Email</label>
		</div>
		<div class="form-group form-material floating" data-plugin="formMaterial">
			<input type="password" class="form-control empty" id="inputPassword" name="password">
			<label class="floating-label" for="inputPassword">Password</label>
		</div>
		<div class="form-group clearfix">
			<div class="checkbox-custom checkbox-inline checkbox-primary float-left">
				<input type="checkbox" id="remember" name="checkbox">
				<label for="inputCheckbox">Remember me</label>
			</div>
			<a class="float-right" href="{{ route('forgot-password') }}">Forgot password?</a>
		</div>
		<button type="submit" class="btn btn-primary btn-block">Sign in</button>
	{{ Form::close() }}
	<p>No account? <a href="{{ route('register') }}">Sign Up</a></p>
	<footer class="page-copyright">
		<p>&copy; 2017. Tecdonor</p>
		<div class="social">
			<a class="btn btn-icon btn-round social-twitter mx-5" href="javascript:void(0)">
				<i class="icon bd-twitter" aria-hidden="true"></i>
			</a>
			<a class="btn btn-icon btn-round social-facebook mx-5" href="javascript:void(0)">
				<i class="icon bd-facebook" aria-hidden="true"></i>
			</a>
			<a class="btn btn-icon btn-round social-linkedin mx-5" href="javascript:void(0)">
				<i class="icon bd-linkedin" aria-hidden="true"></i>
			</a>
		</div>
	</footer>
</div>
@stop