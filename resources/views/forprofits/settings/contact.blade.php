@extends('forprofits.layout')

@section('page_id', 'settings')

@section('scripts')
	@parent
	<script src="https://maps.googleapis.com/maps/api/js?key={{ getenv('GOOGLE_API_KEY') }}&libraries=places&callback=vm.mapsApiLoaded"
    async defer></script>
@stop

@section('content')
	<div class="two-columns">
		@include('forprofits.settings._sidebar')
		<div class="single-column single-column--box">
			<h2>Contact Information</h2>
			{{ Form::open([
				'route' => ['forprofits.settings.contact', $authForprofit->id], 'method' => 'put', 'files' => true,
				'autocomplete' => 'off', 'novalidate', 'class' => 'form--list js-form'
			]) }}
			@include('app.components.form-field', [
				'field' => 'email', 'label' => 'Email',
				'input' => Form::email('email', old('email') ?: $authForprofit->email, ['class' => 'form__field'])
			])
			@include('app.components.form-field', [
				'field' => 'phone', 'label' => 'Phone Number',
				'input' => Form::tel('phone', old('phone') ?: $authForprofit->phone, ['class' => 'form__field'])
			])
			@include('app.components.form-field', [
				'field' => 'location', 'label' => 'Address',
				'input' => Form::text('location', old('location') ?: $authForprofit->location, ['class' => 'form__field js-location-autocomplete', 'placeholder' => ''])
			])
			@include('app.components.form-field', [
				'field' => 'location_suite', 'label' => 'Unit / Suite',
				'input' => Form::text('location_suite', old('location_suite') ?: $authForprofit->location_suite, ['class' => 'form__field'])
			])
				<div class="form__footer">
					<button type="submit" class="btn btn--primary btn--large btn--block">
						<i class="fa fa-spinner btn__loader" aria-hidden="true"></i>
						Save Settings
					</button>
				</div>
			{{ Form::close() }}
		</div>
	</div>
@stop