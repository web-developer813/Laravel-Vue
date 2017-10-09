@extends('volunteers.layout')

@section('page_id', 'newsfeed')

@section('page-aside')
	@include('volunteers.newsfeed._sidebar')
@stop

@section('content')
	<div class="panel">
		<div class="panel-body">
			@include('app.components.feeds.createpost')
		</div>
	</div>
	<div class="row" data-plugin="masonry">
		<simple-feed
			resource-url="{{ route('api.volunteers.newsfeed') }}"
			:filter-categories="{{ $authVolunteer->categories->pluck('id')->toJSON() }}"
			no-results="There are currently no opportunities matching your preferences."
			inline-template>
				<div v-cloak>
					<template v-for="item in items">
						@include('app.components.feeds._feed-item')
					</template>
					<div class="feed__no-results" v-show="!items.length && !loading">
						@{{ noResults }}
					</div>
					@include('app.components.loading')
					@include('app.components.feeds.load-more')
				</div>
		</simple-feed>
	</div>
@stop