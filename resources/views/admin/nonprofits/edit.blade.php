@extends('admin.layout')

@section('content')
	<div class="single-column single-column--box">
		<h1 class="page-title">Edit Nonprofit</h1>

		{{ Form::model($nonprofit, ['route' => ['admin.nonprofits.update', $nonprofit->id], 'method' => 'put', 'class' => 'form--list js-form']) }}
			<section>
				<h3>About the organization</h3>
				@include('app.components.form-field', [
					'field' => 'name', 'label' => 'Name',
					'input' => Form::text('name', old('name'), ['class' => 'form__field'])
				])
				@include('app.components.form-field', [
					'field' => 'description', 'label' => 'Description',
					'input' => Form::textarea('description', old('description'), ['class' => 'form__field', 'rows' => 3])
				])
				@include('app.components.form-field', [
					'field' => 'mission', 'label' => 'Mission',
					'input' => Form::textarea('mission', old('mission'), ['class' => 'form__field', 'rows' => 3])
				])
				@include('app.components.form-field', [
					'field' => 'website_url', 'label' => 'Website',
					'input' => Form::text('website_url', old('website_url'), ['class' => 'form__field'])
				])
				@include('app.components.form-field', [
					'field' => 'photo', 'label' => 'Logo',
					'input' => Form::file('photo', ['class' => 'form__field'])
				])
			</section>
			<section>
				<h3>Legal Information</h3>
				<div class="form-field">
					<label class="form__label">501(c)(3)</label>
					<div class="field-wrapper">
						@if($nonprofit->file_501c3)
							<a href="{{ $nonprofit->file_501c3_url }}" target="_blank">View document</a>
						@else
							No 501(c)(3) provided
						@endif
					</div>
				</div>
				@include('app.components.form-field', [
					'field' => 'tax_id', 'label' => 'EIN / Tax ID',
					'input' => Form::text('tax_id', old('tax_id'), ['class' => 'form__field'])
				])
			</section>
			<section>
				<h3>Contact Information</h3>
				@include('app.components.form-field', [
					'field' => 'email', 'label' => 'Email',
					'input' => Form::email('email', old('email'), ['class' => 'form__field'])
				])
				@include('app.components.form-field', [
					'field' => 'phone', 'label' => 'Phone Number',
					'input' => Form::tel('phone', old('phone'), ['class' => 'form__field'])
				])
				@include('app.components.form-field', [
					'field' => 'location', 'label' => 'Location',
					'input' => Form::text('location', old('location'), ['class' => 'form__field'])
				])
				@include('app.components.form-field', [
					'field' => 'location_suite', 'label' => 'Unit / Suite',
					'input' => Form::text('location_suite', old('location_suite'), ['class' => 'form__field'])
				])
			</section>
			<section>
				<h3>Categories</h3>
				<div class="filters__categories">
					@include('app.components.categories-checkboxes')
				</div>
			</section>
			<section>
				<h3>Verified</h3>
				@include('app.components.checkbox-toggle', [
					'field' => 'verified', 'label' => 'Verified', 'value' => true,
				])
			</section>

			<div class="form__footer">
				<button type="submit" class="btn btn--primary btn--large btn--block">
					<i class="fa fa-spinner btn__loader" aria-hidden="true"></i>
					Save Nonprofit
				</button>
			</div>
		{{ Form::close() }}
	</div>
@stop