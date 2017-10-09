@extends('nonprofits.layout')

@section('page_id', 'opportunity-editor')

@section('scripts')
	@parent
	<script src="https://maps.googleapis.com/maps/api/js?key={{ getenv('GOOGLE_API_KEY') }}&libraries=places&callback=vm.mapsApiLoaded"
    async defer></script>
	{!! php_to_js('startDate', $start_date) !!}
	{!! php_to_js('endDate', $end_date) !!}
@stop

@section('page-content')
<opportunity-editor
	submit-url="{{ secure_url(URL::route('api.nonprofits.opportunities.update', [$authNonprofit->id, $opportunity->id], false)) }}"
	title="{{ $opportunity->title }}"
	description="{{ $opportunity->description }}"
	:maximum_accepted_applicant="{{$opportunity->max_accepted_applicant}}"
	:ispublished="{{ $opportunity->published ? "true" : "false" }}"
	image="{{ $opportunity->image }}"
	selcategories="{{ $opportunity->categories->pluck('id')->toJSON() }}"
	:isvirtual="{{ $opportunity->virtual ? "true" : "false" }}"
	location="{{ $opportunity->location }}"
	location_suite="{{ $opportunity->location_suite }}"
	:isflexible="{{ $opportunity->flexible ? "true" : "false" }}"
	has-start-date="{{ $start_date }}"
	has-end-date="{{ $end_date }}"
	hours_estimate="{{ $opportunity->hours_estimate }}"
	contact_name="{{ $opportunity->contact_name }}"
	contact_email="{{ $opportunity->contact_email }}"
	contact_phone="{{ $opportunity->contact_phone }}"
	authNonprofit="{{ $authNonprofit->id }}"
	opportunity="{{ $opportunity->id }}"
	cloudinary-name="{{ env('CLOUDINARY_PRESET_NAME') }}"
	cloudinary-key="{{ env('CLOUDINARY_API_KEY') }}"
	inline-template>
<div class="page">
	<div class="page-main">
		{{ Form::model($opportunity, [
				'url' => secure_url(URL::route('api.nonprofits.opportunities.update',[$authNonprofit->id, $opportunity->id], false)), 
				'method' => 'put', 
				'files' => true,
				'autocomplete' => 'off', 
				'novalidate', 
				'class' => 'form--list', 
				'@submit.prevent' => 'submitEditForm', 
				'v-cloak'
		]) }}
		<div class="page-header">
			<h1 class="page-title mb-10"><span>Edit: </span> <span class="grey-600 font-weight-100">@{{ title }}</span></h1>
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="{{ route('nonprofits.dashboard', $authNonprofit->id) }}">Dashboard</a></li>
				<li class="breadcrumb-item"><a href="{{ route('nonprofits.opportunities.index', $authNonprofit->id) }}">Opportunities</a></li>
				<li class="breadcrumb-item active">@{{ title }}</li>
			</ol>
			<div class="page-header-actions">
		        <button type="submit" class="btn btn-lg btn-success ml-30 ladda-button" data-style="zoom-in">
		        	<span class="ladda-label">Save <i class="icon md-edit" aria-hidden="true"></i></span>
		        </button>
		    </div>
		</div>
		<div class="page-content container-fluid">
			<div class="row">
				<div class="col-lg-7">
					<div class="panel">
						<div class="panel-body container-fluid">
							<div class="row">
								<div class="col-sm-9">
									<h3 class="mb-30 green-500">Opportunity Info</h3>
									<input type="hidden" name="published" v-model="published">
								</div>
								<div class="col-sm-3 text-right">
									{{--<label class="form-control-label font-size-18" for="oppublished">Published</label>--}}
									<toggle-button :value="published" @change="published = !published" color="#4caf50" :sync="true" :labels="{checked: 'Published', unchecked: 'Hidden'}" :width="90" :height="30" />
								</div>
							</div>
							<div class="row">
								<div class="col-sm-12">
									<div class="form-group mb-20">
										<label class="form-control-label" for="optitle">Title</label>
										<input type="text" class="form-control input-lg" id="optitle" name="title" v-model="title" placeholder="Enter a title for your opportunity" />
									</div>
									<div class="form-group">
										<label class="form-control-label" for="opdescription">Description</label>
										<textarea v-model="description" class="form-control" id="opdescription" name="description" rows="3" placeholder="Enter a description of your opportunity">@{{ description }}</textarea>
									</div>
									<div class="form-group">
										<label class="form-control-label" for="max_accepted_applicant">Maximum Accepted Applicants</label>
										<input type="number" class="form-control input-lg" min="1" id="max_accepted_applicant" name="max_accepted_applicant" v-model="max_accepted_applicant" required/>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-12">
									<h3 class="mt-30 mb-30 green-500">Categories</h3>
									<ul class="list-unstyled list-inline">
										@foreach($categories as $category)
										<li class="list-inline-item">
											<div class="checkbox-custom checkbox-success">
												<input v-model="categories" id="id-checkbox-{{ $category->id }}" type="checkbox" v-bind:value="{{ $category->id }}" name="categories[]" :disabled="catCount > 2 && categories.indexOf({{ $category->id }}) === -1"
												{{ in_array($category->id, $opportunity->categories->pluck('id')->all()) ? 'checked' : '' }}
												>
												<label for="id-checkbox-{{ $category->id }}">{{ $category->name }}</label>
											</div>
										</li>
										@endforeach
									</ul>						
								</div>
							</div>
						</div>
					</div>

					<div class="panel">
						<div class="panel-body container-fluid">
							<skill-opportunity
								logged-account="{{$authNonprofit}}"
								mode="nonprofit"
								resource-url="{{ secure_url(URL::route('api.nonprofits.opportunities.skills', ['nonprofit' => $authNonprofit->id, 'opportunity_id' => $opportunity->id], false)) }}"
								opportunity-id="{{ $opportunity->id }}"
								inline-template>
								@include('app.templates.skill-opportunity')
							</skill-opportunity>
						</div>
					</div>
				</div>
				<div class="col-lg-5">
					<div class="row">
						<div class="col-sm-12">
							<div class="panel">
								<div class="panel-body container-fluid">
									<h3 class="mb-30 green-500">Image</h3>
									<input type="file" class="js-file-input dropify" id="input-file-now" data-plugin="dropify" data-max-file-size-preview="3M" data-allowed-file-extensions="jpg png jpeg" v-bind:data-default-file="image" :disabled="isSaving" @change="imageUpload($event.target.name, $event.target.files);" />
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12">
							<div class="panel">
								<div class="panel-body container-fluid">
									<h3 class="green-500">Location</h3>
									<input type="hidden" name="virtual" v-model="virtual">
									<div class="form-group">
										<label class="form-control-label font-size-18" for="opvirtual">Remote Work</label>
										<toggle-button :value="virtual" @change="virtual = !virtual" color="#4caf50" :sync="true" :labels="false"/>
									</div>
									<div class="form-group-wrapper" v-show="!virtual">
										<div class="form-group">
											<label class="form-control-label" for="oplocation">Location</label>
											<input type="text" class="form-control input-lg js-location-autocomplete" id="oplocation" name="location" v-model="location" placeholder="Address, City, State, Zip..." />
										</div>
										<div class="form-group">
											<label class="form-control-label" for="oplocationsuite">Unit / Suite</label>
											<input type="text" class="form-control input-lg" id="oplocationsuite" name="location_suite" v-model="location_suite" placeholder="Suite 1030C" />
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12">
							<div class="panel">
								<div class="panel-body container-fluid">
									<h3 class="green-500">Date &amp; Time</h3>
									<input type="hidden" name="flexible" v-model="flexible">
									<div class="form-group">
										<label class="form-control-label font-size-18" for="opflexible">Flexible Dates</label>
										<toggle-button :value="flexible" @change="flexible = !flexible" color="#4caf50" :sync="true" :labels="false"/>
									</div>
									<div class="form-group" v-show="!flexible">
										<date-range-picker id="opdates" v-on:dateRangeChanged="onDateRangeChanged" :start-date-label="startDateLabel" :end-date-label="endDateLabel" :multiple-dates="multipleDates" inline-template>
											<div class="datepicker js-datepicker-parent" :id="id">
												<div type="text" class="datepicker-label js-datepicker">
													<span class="startDateLabel js-startDateLabel">@{{ startDateLabel }}</span><span v-show="multipleDates"> - <span class="endDateLabel js-endDateLabel">@{{ endDateLabel }}</span></span>
													<div class="calendar-icon"><i class="icon md-calendar" aria-hidden="true"></i></div>
												</div>
											</div>
										</date-range-picker>
										<input type="hidden" name="start_date" value="{{ $start_date }}" v-model="startDate" v-if="!flexible">
										<input type="hidden" name="end_date" value="{{ $end_date }}" v-model="endDate" v-if="!flexible">
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			
		</div>
		{{ Form::close() }}
	</div>
</div>
</opportunity-editor>
@stop

@section('jquery-docready')
$(function() {
  if($.fn.cloudinary_fileupload !== undefined) {
    $("input.cloudinary-fileupload[type=file]").cloudinary_fileupload();
  }
});
@stop