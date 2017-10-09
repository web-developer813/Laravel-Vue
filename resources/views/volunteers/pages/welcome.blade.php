@extends('volunteers.layout')

@section('page_id', 'welcome')

@section('scripts')
	@parent
	<script src="https://maps.googleapis.com/maps/api/js?key={{ getenv('GOOGLE_API_KEY') }}&libraries=places&callback=vm.mapsApiLoaded"
    async defer></script>
@stop

@section('content')
	<div class="panel panel-transparent">
		<h1 class="page-title">Welcome to Tecdonor</h1>
		<p>Tell us a bit more about what you care about for a more personalized experienced. </p>
	</div>
	{{ Form::open([
		'route' => 'welcome', 'method' => 'put',
		'autocomplete' => 'off', 'novalidate', 'class' => 'form--list js-form'
	])}}
	<div class="panel">
		<div class="panel-heading">
			<h3 class="panel-title">Where would you like to volunteer?</h3>
		</div>
		<div class="panel-body">
			<div class="form-group">
				<input type="text" name="location" class="form-control form-control-lg js-location-autocomplete" placeholder="City, State, Postal Code...">
			</div>
		</div>
	</div>
	<div class="panel">
		<div class="panel-heading">
			<h3 class="panel-title">Which causes are you most interested in?</h3>
		</div>
		<div class="panel-body">
			<div class="form-group">
				@include('app.components.categories-checkboxes')
			</div>
		</div>
	</div>
	<div class="panel panel-transparent">
		<div class="form-group">
			<button type="submit" class="btn btn-primary btn-large btn-block">
				Save Preferences
			</button>
		</div>
	</div>
	{{ Form::close() }}
@stop