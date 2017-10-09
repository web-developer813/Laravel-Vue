@extends('app.layout')

@section('body_class','animsition page-login-v3 layout-full')

@section('scripts')
	@parent
	<script src="https://maps.googleapis.com/maps/api/js?key={{ getenv('GOOGLE_API_KEY') }}&libraries=places&callback=vm.mapsApiLoaded"
    async defer></script>
@stop

@section('content')
<div class="page vertical-align text-center" data-animsition-in="fade-in" data-animsition-out="fade-out">
	<div class="page-content vertical-align-middle">
		<div class="panel panel-transparent">
			<div class="brand">
				<img class="brand-img" src="/img/tecdonor-logo-white.png" alt="Tecdonor" height="25">
				<h2 class="font-size-40" style="color:#fff;">Welcome Aboard!</h2>
			</div>
			<p class="font-size-20" style="color:#fff;">Just a couple quick questions before we take you to the platform</p>
		</div>
		<div class="panel">
			<div class="panel-body">
				{{ Form::open([
					'route' => 'welcome', 'method' => 'put',
					'autocomplete' => 'off', 'novalidate', 'class' => 'mt-0 js-form'
				])}}
				<h3 class="mt-0 mb-20">Where would you like to Volunteer?</h3>
				<div class="form-group">
					<input type="text" name="location" class="form-control form-control-lg js-location-autocomplete" placeholder="City, State, Postal Code...">
				</div>
				<h3 class="mt-40 mb-20">What causes are you most interested in?</h3>
				<div class="form-group mb-30">
					<ul class="list-unstyled list-inline">
						@include('app.components.categories-checkboxes')
					</ul>
				</div>
				<div class="form-group">
					<button class="btn btn-primary btn-lg btn-block" type="submit">Continue</button>
				</div>
				{{ Form::close() }}
			</div>
		</div>
		<footer class="page-copyright page-copyright-inverse">
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
</div>
@stop

@section('plugin_vendor')
{!! script_cdn('/vendor/jquery-placeholder/jquery.placeholder.min.js') !!}
{!! script_cdn('/vendor/formvalidation/formValidation.min.js') !!}
{!! script_cdn('/vendor/formvalidation/framework/bootstrap4.min.js') !!}
@stop

@section('plugin_scripts')
{!! script_cdn('/js/Plugin/jquery-placeholder.min.js') !!}
{!! script_cdn('/js/Plugin/material.min.js') !!}
@stop