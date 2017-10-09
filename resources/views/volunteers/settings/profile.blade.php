@extends('volunteers.layout')

@section('page_id', 'settings')

@section('body_class','animsition page-profile-v2')

@section('content')
<div class="row">
	<div class="col-lg-8">
		<div class="card card-shadow">
			{{ Form::open([
				'route' => 'settings.profile', 'method' => 'put', 'files' => true,
				'autocomplete' => 'off', 'novalidate', 'class' => 'form--list js-form'
			]) }}
				
				@include('app.components.form-field', [
					'field' => 'name', 'label' => 'Name',
					'input' => Form::text('name', old('name') ?: $volunteer->name, ['class' => 'form__field', 'placeholder' => 'Full name'])
				])
				@include('app.components.form-field', [
					'field' => 'description', 'label' => 'Short Bio',
					'input' => Form::textarea('description', old('description') ?: $volunteer->description, ['class' => 'form__field', 'rows' => 3, 'placeholder' => 'Short bio'])
				])
				<div class="form-field {{ form_error('photo') }}">
					<label class="form__label">Photo</label>
					<div class="field-wrapper">
						<span class="btn btn--default btn--small file-upload js-file-uploader" data-name="photo">Upload Photo</span>
						<span class="file-selected">No file selected</span>
						<span class="error-block">
							{{ $errors->first('photo') ?: '' }}
						</span>
					</div>
				</div>
				@if($volunteer->resume)
					<div class="form-field">
						<label class="form__label">Current Resume</label>
						<div class="field-wrapper">
							<a href="{{ $volunteer->resume_url }}" target="_blank">View Resume</a>
						</div>
					</div>
				@endif
				<div class="form-field {{ form_error('resume') }}">
					<label class="form__label">Resume</label>
					<div class="field-wrapper">
						<span class="btn btn--default btn--small file-upload js-file-uploader" data-name="resume">Upload Resume</span>
						<span class="file-selected">No file selected</span>
						<span class="error-block">
							{{ $errors->first('resume') ?: '' }}
						</span>
					</div>
				</div>
				<div class="form__footer">
					<button type="submit" class="btn btn--primary btn--large btn--block">
						<i class="fa fa-spinner btn__loader" aria-hidden="true"></i>
						Save Profile
					</button>
				</div>
			{{ Form::close() }}
			<br />
		</div>
	</div>
</div>
@stop
