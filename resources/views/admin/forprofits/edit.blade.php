@extends('admin.layout')

@section('content')
	<div class="single-column single-column--box">
		<h1 class="page-title">Edit Forprofit</h1>

		{{ Form::model($forprofit, ['route' => ['admin.forprofits.update', $forprofit->id], 'method' => 'put', 'class' => 'form--list js-form']) }}
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

			<h3>Verified</h3>
			@include('app.components.checkbox-toggle', [
				'field' => 'verified', 'label' => 'Verified', 'value' => true,
			])

			<div class="form__footer">
				<button type="submit" class="btn btn--primary btn--large btn--block">
					<i class="fa fa-spinner btn__loader" aria-hidden="true"></i>
					Save Forprofit
				</button>
			</div>
		{{ Form::close() }}
	</div>
@stop