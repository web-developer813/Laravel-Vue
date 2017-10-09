@extends('volunteers.layout')

@section('page_id', 'nonprofits-create')

@section('scripts')
	@parent
	<script src="https://maps.googleapis.com/maps/api/js?key={{ getenv('GOOGLE_API_KEY') }}&libraries=places&callback=vm.mapsApiLoaded"
    async defer></script>
@stop

@section('content')
	<div class="single-column single-column--box">
		<h1 class="page-title">New Nonprofit</h1>
		<nonprofit-create inline-template>
			{{ Form::open([
				'route' => 'nonprofits.store', 'files' => true,
				'autocomplete' => 'off', 'novalidate', 'class' => 'form--list js-form'
			]) }}
				<section>
					<h3>Tell us about your nonprofit organization</h3>
					@include('app.components.form-field', [
						'field' => 'name', 'label' => 'Name *',
						'input' => Form::text('name', old('name'), ['class' => 'form__field'])
					])
					@include('app.components.form-field', [
						'field' => 'mission', 'label' => 'Mission',
						'input' => Form::text('mission', old('mission'), ['class' => 'form__field'])
					])
					@include('app.components.form-field', [
						'field' => 'description', 'label' => 'Description',
						'input' => Form::textarea('description', old('description'), ['class' => 'form__field', 'rows' => 3])
					])
					@include('app.components.form-field', [
						'field' => 'website_url', 'label' => 'Website',
						'input' => Form::text('website_url', old('website_url'), ['class' => 'form__field'])
					])
					<div class="form-field {{ form_error('photo') }}">
						<label class="form__label">Logo</label>
						<div class="field-wrapper">
							<span class="btn btn--default btn--small file-upload js-file-uploader" data-name="photo">Upload Logo</span>
							<span class="file-selected">No file selected</span>
							<span class="error-block">
								{{ $errors->first('photo') ?: '' }}
							</span>
						</div>
					</div>
				</section>
				<section>
					<h3>Legal Information</h3>
					<div class="form-field {{ form_error('file_501c3') }}">
						<label class="form__label">501(c)(3)</label>
						<div class="field-wrapper">
							<span class="btn btn--default btn--small file-upload js-file-uploader" data-name="file_501c3">Upload 501(c)(3)</span>
							<span class="file-selected">No file selected</span>
							<span class="error-block">
								{{ $errors->first('file_501c3') ?: '' }}
							</span>
							<span class="help-block">You will have to provide your 501(c)(3) before your organization can be approved.</span>
						</div>
					</div>
					@include('app.components.form-field', [
						'field' => 'tax_id', 'label' => 'EIN / Tax ID',
						'input' => Form::text('tax_id', old('tax_id'), ['class' => 'form__field']),
						'help_block' => 'You will have to provide your EIN / Tax ID before your organization can be approved.'
					])
				</section>
				<section>
					<h3>Contact Information</h3>
					@include('app.components.form-field', [
						'field' => 'email', 'label' => 'Email *',
						'input' => Form::email('email', old('email'), ['class' => 'form__field'])
					])
					@include('app.components.form-field', [
						'field' => 'phone', 'label' => 'Phone Number *',
						'input' => Form::tel('phone', old('phone'), ['class' => 'form__field'])
					])
					@include('app.components.form-field', [
						'field' => 'location', 'label' => 'Address *',
						'input' => Form::text('location', old('location'), ['class' => 'form__field js-location-autocomplete', 'placeholder' => ''])
					])
					@include('app.components.form-field', [
						'field' => 'location_suite', 'label' => 'Unit / Suite',
						'input' => Form::text('location_suite', old('location_suite'), ['class' => 'form__field'])
					])
				</section>
				<section>
					<h3>Which causes are you interested in?</h3>
					<div class="categories">
						@foreach($categories as $category)
							<div class="category-checkbox">
								<input
									type="checkbox"
									name="categories[]"
									value="{{ $category->id }}"
									@if(old('categories') && in_array($category->id, old('categories')))
										checked="checked"
									@endif
									id="id-checkbox-{{ $category->id }}"
									v-model="formData.categories">
								<label for="id-checkbox-{{ $category->id }}" @click.prevent="toggleCategory('{{ $category->id }}')">
									{{ $category->name }}
								</label>
							</div>
						@endforeach
					</div>
				</section>
				<div class="form__footer">
					<button type="submit" class="btn btn--primary btn--large btn--block">
						<i class="fa fa-spinner btn__loader" aria-hidden="true"></i>
						Create New Nonprofit
					</button>
				</div>
			{{ Form::close() }}
		</nonprofit-create>
	</div>
@stop

{{-- @section('scripts')
	<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBBmdN6dGgaYy2VIthEj-lAm3njLygTQF8&libraries=places"></script>
	@parent
@stop --}}