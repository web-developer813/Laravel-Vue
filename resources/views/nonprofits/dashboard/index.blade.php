@extends('nonprofits.layout')

@section('page_id', 'nonprofits-dashboard')

@section('content')
	<div class="single-column">
		@include('nonprofits.dashboard._metrics')
	</div>
	{{-- <div class="two-columns"> --}}
		{{-- @include('nonprofits.dashboard._sidebar') --}}

		<div class="single-column">
			<simple-feed
				resource-url="{{ route('api.nonprofits.applications', $authNonprofit->id) }}"
				no-results="Your organization has not received any applications yet."
				inline-template>
				<div class="feed" v-cloak>
					<ul>
						<template v-for="item in items">
							@include('nonprofits.dashboard._feed-item--applications')
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