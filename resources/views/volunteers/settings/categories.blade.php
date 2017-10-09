@extends('volunteers.layout')

@section('page_id', 'settings')

@section('scripts')
	@parent
	<script src="https://maps.googleapis.com/maps/api/js?key={{ getenv('GOOGLE_API_KEY') }}&libraries=places&callback=vm.mapsApiLoaded"
    async defer></script>
@stop

@section('page-header')
	<div class="page-header">
		<h2>Interests</h2>
	</div>
@stop

@section('content')
	<div class="two-columns">
		@include('volunteers.settings._sidebar')
		<div class="single-column single-column--box">
			{{ Form::open([
				'route' => 'settings.categories', 'method' => 'put', 'files' => true,
				'autocomplete' => 'off', 'novalidate', 'class' => 'form--list js-form'
			]) }}
				<section>
					@include('app.components.form-field', [
						'field' => 'location', 'label' => 'Location',
						'input' => Form::text('location', $authVolunteer->location ?: old('location'), ['class' => 'form__field js-location-autocomplete', 'placeholder' => 'City, State, Postal Code...'])
					])
				</section>
				<section>
					<h3>Categories</h3>
					<div class="filters__categories">
						@include('app.components.categories-checkboxes', ['entity' => $authVolunteer])
					</div>
				</section>
				<div class="form__footer">
					<button type="submit" class="btn btn--primary btn--large btn--block">
						<i class="fa fa-spinner btn__loader" aria-hidden="true"></i>
						Save Interests
					</button>
				</div>
			{{ Form::close() }}
		</div>
	</div>
@stop