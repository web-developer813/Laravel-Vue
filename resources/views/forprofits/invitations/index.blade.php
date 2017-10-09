@extends('forprofits.layout')

@section('page_id', 'nonprofit-admin-employees-index')

@section('page-header')
	<div class="page-header">
		<h2>Invitations</h2>
	</div>
@stop

@section('content')
	<div class="two-columns">
		{{-- @include('forprofits.invitations._sidebar') --}}
		@include('forprofits.employees.index._sidebar')

		<div class="single-column">
			<simple-feed
				resource-url="{{ route('api.forprofits.invitations', [$authForprofit->id, 'type' => 'employee']) }}"
				no-results="There are no invitations matching your criteria."
				inline-template>
				<div v-cloak>

					{{-- filters --}}
					<div class="feed-filters flex-columns">
						<div class="filters-group column column--grow-1">
							<button class="filter__option" v-bind:class="{'active': filters.status == ''}" @click="filters.status = ''">All</button>
							<button class="filter__option" v-bind:class="{'active': filters.status == 'accepted'}" @click="filters.status = 'accepted'">Accepted</button>
							<button class="filter__option" v-bind:class="{'active': filters.status == 'rejected'}" @click="filters.status = 'pending'">Pending</button>
						</div>
						<div class="filters-group column column--35">
							<div class="field--text">
								<input type="text" v-bind:value="filters.search" @input="updateSearchQuery" placeholder="Search invitations...">
							</div>
						</div>
					</div>
					
					{{-- feed --}}
					<div class="feed">
						<ul>
							<template v-for="item in items">
								@include('forprofits.invitations._list-item')
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