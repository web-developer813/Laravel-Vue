@extends('forprofits.layout')

@section('page_id', 'forprofits-dashboard')

@section('content')
	<div class="single-column">
		@include('forprofits.dashboard._metrics')
	</div>
	{{-- <div class="two-columns"> --}}
		{{-- @include('forprofits.dashboard._sidebar') --}}

		<div class="single-column">
			<simple-feed
				resource-url="{{ route('api.forprofits.hours', $authForprofit->id) }}"
				no-results="Your employees have not completed any work yet."
				inline-template>
				
				<div class="feed" v-cloak>
					<ul>
						<template v-for="item in items">
							@include('forprofits.dashboard._feed-item--hours')
						</template>
					</ul>
					<div class="feed__no-results" v-show="!items.length && !loading">
						@{{ noResults }}
					</div>
					@include('app.components.loading')
					@include('app.components.feeds.load-more')
				</div>

			</simple-feed>
		</div>
	{{-- </div> --}}
@stop