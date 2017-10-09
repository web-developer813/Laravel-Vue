@extends('volunteers.layout')

@section('page_id', 'work-hours-index')

@section('page-header')
	<div class="page-header">
		<h2>Coupons Purchased</h2>
	</div>
@stop

@section('content')
	<div class="two-columns">
		@include('volunteers.applications._sidebar')
		<div class="single-column">
			<simple-feed
				resource-url="{{ route('api.volunteers.incentive-purchases', []) }}"
				no-results="You don't currently have any coupons."
				inline-template>
				<div class="feed feed--two" v-cloak>
				
					{{-- filters --}}
					<div class="feed-filters flex-columns">
						<div class="filters-group column column--grow-1">
							<button class="filter__option" v-bind:class="{'active': filters.status == ''}" @click="filters.status = ''">All</button>
							<button class="filter__option" v-bind:class="{'active': filters.status == 'valid'}" @click="filters.status = 'valid'">Valid</button>
							<button class="filter__option" v-bind:class="{'active': filters.status == 'redeemed'}" @click="filters.status = 'redeemed'">Redeemed</button>
							<button class="filter__option" v-bind:class="{'active': filters.status == 'expired'}" @click="filters.status = 'expired'">Expired</button>
						</div>
						<div class="filters-group column column--35">
							<div class="field--text">
								<input type="text" v-model="filters.search" placeholder="Search...">
							</div>
						</div>
					</div>

					<ul>
						<template v-for="item in items">
							@include('volunteers.incentive-purchases._feed-item')
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