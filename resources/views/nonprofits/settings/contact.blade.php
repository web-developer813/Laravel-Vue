@extends('nonprofits.layout')

@section('page_id', 'settings')

@section('scripts')
	@parent
	<script src="https://maps.googleapis.com/maps/api/js?key={{ getenv('GOOGLE_API_KEY') }}&libraries=places&callback=vm.mapsApiLoaded"
    async defer></script>
@stop

@section('content')
	<div class="two-columns">
		@include('nonprofits.settings._sidebar')
		<div class="single-column single-column--box">
			{{ Form::open([
				'route' => ['nonprofits.settings.contact', $authNonprofit->id], 'method' => 'put', 'files' => true,
				'autocomplete' => 'off', 'novalidate', 'class' => 'form--list js-form'
			]) }}
				<section>
					@include('app.components.form-field', [
						'field' => 'email', 'label' => 'Email',
						'input' => Form::email('email', old('email') ?: $authNonprofit->email, ['class' => 'form__field'])
					])
					@include('app.components.form-field', [
						'field' => 'phone', 'label' => 'Phone Number',
						'input' => Form::tel('phone', old('phone') ?: $authNonprofit->phone, ['class' => 'form__field'])
					])
					@include('app.components.form-field', [
						'field' => 'location', 'label' => 'Address',
						'input' => Form::text('location', old('location') ?: $authNonprofit->location, ['class' => 'form__field js-location-autocomplete'])
					])
					@include('app.components.form-field', [
						'field' => 'location_suite', 'label' => 'Unit / Suite',
						'input' => Form::text('location_suite', old('location_suite') ?: $authNonprofit->location_suite, ['class' => 'form__field'])
					])
				</section>
				<section>
					<h3>Legal Information</h3>
					@if($authNonprofit->file_501c3)
						<div class="form-field">
							<label class="form__label">Current 501(c)(3)</label>
							<div class="field-wrapper">
								<a href="{{ $authNonprofit->file_501c3_url }}" target="_blank">View document</a>
							</div>
						</div>
					@endif
					<div class="form-field {{ form_error('file') }}">
						<label class="form__label">New 501(c)(3)</label>
						<div class="field-wrapper">
							<span class="btn btn--default btn--small file-upload js-file-uploader" data-name="file_501c3">Upload new 501(c)(3)</span>
							<span class="file-selected">No file selected</span>
							<span class="error-block">
								{{ $errors->first('file') ?: '' }}
							</span>
						</div>
					</div>
					@include('app.components.form-field', [
						'field' => 'tax_id', 'label' => 'EIN / Tax ID',
						'input' => Form::text('tax_id', old('tax_id') ?: $authNonprofit->tax_id, ['class' => 'form__field'])
					])
				</section>
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