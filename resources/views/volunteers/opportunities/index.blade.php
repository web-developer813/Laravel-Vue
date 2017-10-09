@extends('volunteers.layout')

@section('page_id', 'opportunities-index')

@section('page-header')
	<div class="page-header">
		<h2>Volunteer</h2>
	</div>
@stop

@section('content')
	<opportunities-feed
		search="{{ request()->input('search') }}"
		resource-url="{{ route('api.volunteers.opportunities') }}"
		location="{{ $authVolunteer->location }}"
		inline-template>
		
		<div v-cloak>
			{{-- feed --}}
			<div class="single-column single-column--wide">
			
				@include('volunteers.opportunities.index._filters')
				<div class="current-filters">
					Searching for volunteer work near <a href="{{ route('settings.categories') }}"><strong>@{{ location }}</strong></a>
				</div>

				<div class="feed feed--three">
					<ul class="js-masonry">
						<template v-for="item in items">
							@include('volunteers.opportunities._feed-item')
						</template>
					</ul>
					<div class="feed__no-results" v-show="!items.length && !loading">
						There are currently no opportunities matching your search criteria.
					</div>
					@include('app.components.loading')
					@include('app.components.feeds.load-more')
				</div>
			</div>
		</div>
	</opportunities-feed>
@stop