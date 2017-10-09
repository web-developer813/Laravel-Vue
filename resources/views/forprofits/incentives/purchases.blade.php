@extends('forprofits.layout')

@section('page_id', 'forprofits-admin-incentives-index')

@section('page-header')
	<div class="page-header">
		<h2>Purchases for <strong>{{ $incentive->title }}</strong></h2>
	</div>
@stop

@section('content')
	<div class="single-column">
		
		<simple-feed
			search="{{ request()->input('search') }}"
			resource-url="{{ route('api.forprofits.incentive-purchases', [$authForprofit->id, 'incentive' => $incentive->id]) }}"
			no-results="There are no purchases matching your criteria."
			inline-template>

			<div v-cloak>
				{{-- filters --}}
				<div class="feed-filters flex-columns">
					<div class="filters-group column column--grow-1">
						<button class="filter__option" v-bind:class="{'active': filters.status == ''}" @click="filters.status = ''">All</button>
						<button class="filter__option" v-bind:class="{'active': filters.status == 'valid'}" @click="filters.status = 'valid'">Valid</button>
						<button class="filter__option" v-bind:class="{'active': filters.status == 'redeemed'}" @click="filters.status = 'redeemed'">Redeemed</button>
						<button class="filter__option" v-bind:class="{'active': filters.status == 'expired'}" @click="filters.status = 'expired'">Expired</button>
					</div>
					<div class="filters-group column column--30">
						<div class="field--text">
							<input type="text" v-bind:value="filters.search" @input="updateSearchQuery" placeholder="Search...">
						</div>
					</div>
				</div>

				<div class="feed">
					<ul>
						<template v-for="item in items">
							@include('forprofits.incentives.purchases._feed-item')
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
@stop