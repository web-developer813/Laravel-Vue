@extends('volunteers.forprofits.profile-layout')

@section('profile-content')
	<simple-feed
		resource-url="{{ route('api.volunteers.donations', ['forprofit' => $forprofit->id]) }}"
		no-results="This business hasn't made any donations yet."
		inline-template>
		
		<div class="feed feed--two" v-cloak>
			<ul>
				<template v-for="item in items">
					@include('volunteers.volunteers.show._feed-item-donation')
				</template>
			</ul>
			<div class="feed__no-results" v-show="!items.length && !loading">
				@{{ noResults }}
			</div>
			@include('app.components.loading')
			@include('app.components.feeds.load-more')
		</div>

	</simple-feed>
@stop