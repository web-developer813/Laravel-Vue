@extends('forprofits.layout')

@section('page_id', 'forprofits-dashboard')

@section('content')
	<div class="two-columns">
		@include('forprofits.donations.index._sidebar')

		<div class="single-column">
			<simple-feed
				resource-url="{{ route('api.forprofits.donations', ['forprofit' => $authForprofit->id]) }}"
				no-results="There are no donations matching your criteria."
				inline-template>
				
				<div class="feed feed--two" v-cloak>
				
					{{-- filters --}}
					<div class="feed-filters flex-columns">
						<div class="filters-group column column--grow-1">
							<button class="filter__option" v-bind:class="{'active': filters.status == ''}" @click="filters.status = ''">All</button>
							<button class="filter__option" v-bind:class="{'active': filters.status == 'fulfilled'}" @click="filters.status = 'fulfilled'">Fulfilled</button>
							<button class="filter__option" v-bind:class="{'active': filters.status == 'pending'}" @click="filters.status = 'pending'">Pending</button>
						</div>
						<div class="filters-group column column--35">
							<div class="field--text">
								<input type="text" v-bind:value="filters.search" @input="updateSearchQuery" placeholder="Search donations...">
							</div>
						</div>
					</div>

					<ul>
						<template v-for="item in items">
							@include('forprofits.donations.index._feed-item')
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
	</div>
@stop