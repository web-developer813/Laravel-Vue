@extends('nonprofits.layout')

@section('page_id', 'nonprofits-manage-volunteers')

@section('page-header')
	<div class="page-header">
		<h2>Verify Hours for <strong>{{ $opportunity->title }}</strong></h2>
	</div>
@stop

@section('scripts')
	@parent
	{!! php_to_js('startDate', $startDate) !!}
	{!! php_to_js('endDate', $endDate) !!}
@stop

@section('content')
<verify-hours
resource-url="{{ route('api.nonprofits.applications', [
	'nonprofit' => $authNonprofit->id,
	'opportunity' => $opportunity->id,
	'status' => 'accepted'
]) }}"
submit-url="{{ route('api.nonprofits.hours.store', $authNonprofit->id) }}"
opportunity="{{ $opportunity->id }}"
no-results="There are currently no volunteers matching your criteria."
inline-template>
	<div class="two-columns">
			@include('nonprofits.manage._sidebar')
			<div class="single-column" v-cloak>
				{{-- form --}}
				<div class="verify-hours-form">
					<form action="" @submit.prevent="submitHours">
					<div class="form__header">
						<div class="selection-label">
							Verifying hours for <strong class="highlight">@{{ selection.length }}</strong> @{{ selection.length }} volunteers
						</div>
						<div class="links">
							<span class="btn btn--default btn--small" @click="selectAll">Select All</span>
							<span class="btn btn--default btn--small" @click="selection = []">Select None</span>
							<span class="btn btn--default btn--small" @click="toggleSearch"><i class="fa fa-search" aria-hidden="true"></i></span>
						</div>
					</div>
					<div class="search" v-show="searchOpened">
						<div class="field--text">
							<input type="text" v-bind:value="search" @input="updateSearchQuery" placeholder="Search volunteers..." class="js-search-input">
						</div>
					</div>
					<div class="flex">
						<div class="datepicker js-datepicker-parent">
							<div type="text" class="datepicker-label js-datepicker">
								<span class="startDateLabel js-startDateLabel">@{{ startDateLabel }}</span><span v-show="multipleDates"> - <span class="endDateLabel js-endDateLabel">@{{ endDateLabel }}</span></span>
								<div class="calendar-icon"><i class="fa fa-calendar" aria-hidden="true"></i></div>
							</div>
							<input type="hidden" class="js-startDate" value="{{ $startDate }}" v-model="startDate">
							<input type="hidden" class="js-endDate" value="{{ $endDate }}" v-model="endDate">
						</div>
						<div class="hourspicker">
							<input type="number" class="field--text" min="0" max="1000" v-model="hours" number>
							<span class="label">@{{ hours }} hours</span>
							<select name="minutes" v-model="minutes" number>
								<option value="0">00</option>
								<option value="15">15</option>
								<option value="30">30</option>
								<option value="45">45</option>
							</select>
							<span class="label label--minutes">min</span>
						</div>
					</div>
					<div class="form__footer">
						<button type="submit" class="btn btn--primary btn--block" v-bind:class="{ 'btn--loading': submitting }" :disabled="submitting || !selection.length">
							<i class="fa fa-spinner btn__loader" aria-hidden="true"></i>
							Verify Hours
						</button>
					</div>
					</form>
				</div>

				{{-- feed --}}
				<div class="list" v-cloak>
					<ul>
						<template v-for="item in items">
							@include('nonprofits.manage.verify-hours._list-item')
						</template>
					</ul>
					<div class="list-item no-results" v-show="!items.length && !loading">
						@{{ noResults }}
					</div>
					@include('app.components.loading')
					@include('app.components.feeds.load-more')
				</div>
			</div>
	</div>
</verify-hours>
@stop