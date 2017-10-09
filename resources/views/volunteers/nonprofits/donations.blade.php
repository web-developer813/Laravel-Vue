@extends('volunteers.nonprofits.profile-layout')

@section('profile-content')
	<simple-feed
		resource-url="{{ route('api.volunteers.donations', ['nonprofit' => $nonprofit->id]) }}"
		no-results="This nonprofit hasn't received any donations yet."
		inline-template>
		
		<div class="feed feed--two" v-cloak>
			<ul>
				<template v-for="item in items">
					@include('volunteers.nonprofits.show._feed-item-donation')
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