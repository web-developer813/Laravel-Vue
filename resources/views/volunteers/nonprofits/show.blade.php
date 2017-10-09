@extends('volunteers.nonprofits.profile-layout')

@section('profile-content')
	<simple-feed
		resource-url="{{ route('api.volunteers.opportunities', ['nonprofit' => $nonprofit->id, 'order' => 'date']) }}"
		no-results="This organization has no open opportunities."
		inline-template>
			<div v-cloak>
				<div class="feed feed--two">
					<ul class="js-masonry">
						<template v-for="item in items">
							@include('volunteers.opportunities._feed-item')
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