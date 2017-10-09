@extends('nonprofits.layout')

@section('page_id', 'nonprofit-admin-forprofits-index')

@section('page-header')
	<div class="page-header">
		<h2>Request New Donations</h2>
	</div>
@stop

@section('content')		
	<div class="two-columns">
		@include('nonprofits.donations.index._sidebar')

		<div class="single-column">
			<simple-feed
				resource-url="{{ route('api.nonprofits.forprofits', $authNonprofit->id) }}"
				no-results="There are currently no businesses matching your criteria."
				inline-template>
				<div v-cloak>
					<div class="feed feed--two">
				
						{{-- filters --}}
						<div class="feed-filters flex-columns">
							<div class="filters-group column column--grow-1">
								<div class="field--text">
									<input type="text" v-bind:value="filters.search" @input="updateSearchQuery" placeholder="Search businesses...">
								</div>
							</div>
						</div>

						<ul>
							<template v-for="item in items">
								@include('nonprofits.forprofits._feed-item')
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
		</div>
	</div>
@stop