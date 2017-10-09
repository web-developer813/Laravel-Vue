@extends('volunteers.forprofits.profile-layout')

@section('profile-content')
	<simple-feed
		resource-url="{{ route('api.volunteers.incentives', ['forprofit' => $forprofit->id, 'order' => 'date']) }}"
		no-results="This business hasn't posted any incentives."
		inline-template>

		<div v-cloak>

			<div class="feed feed--two">
				<ul class="js-masonry">
					<template v-for="item in items">
						@include('volunteers.incentives._feed-item')
					</template>
				</ul>
				<div class="feed__no-results" v-show="!items.length && !loading">
					@{{ noResults }}
				</div>
				@include('app.components.loading')
				@include('app.components.feeds.load-more')
			</div>
			
		</div>

	</simple-feed>
@stop