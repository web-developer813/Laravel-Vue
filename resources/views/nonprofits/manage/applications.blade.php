@extends('nonprofits.layout')

@section('page_id', 'nonprofits-dashboard')

@section('page-header')
	<div class="page-header">
		<h2>Applications for <strong>{{ $opportunity->title }}</strong></h2>
	</div>
@stop

@section('content')
	<div class="two-columns">
		@include('nonprofits.manage._sidebar')
		<div class="single-column">
			<simple-feed
				resource-url="{{ route('api.nonprofits.applications', ['nonprofit' => $authNonprofit->id, 'opportunity' => $opportunity->id]) }}"
				no-results="There are currently no {{ request()->status }} applications for this opportunity."
				inline-template>
				<div class="list" v-cloak>
				
					{{-- filters --}}
					<div class="feed-filters">
						<div class="filters-group">
							<button class="filter__option" v-bind:class="{'active': filters.status == ''}" @click="filters.status = ''">All</button>
							<button class="filter__option" v-bind:class="{'active': filters.status == 'accepted'}" @click="filters.status = 'accepted'">Accepted</button>
							<button class="filter__option" v-bind:class="{'active': filters.status == 'rejected'}" @click="filters.status = 'rejected'">Rejected</button>
							<button class="filter__option" v-bind:class="{'active': filters.status == 'pending'}" @click="filters.status = 'pending'">Pending</button>
						</div>
						<div class="filters-group">
							<div class="field--text">
								<input type="text" v-model="filters.search" placeholder="Search...">
							</div>
						</div>
					</div>

					<ul>
						<template v-for="item in items">
							@include('nonprofits.manage.applications._list-item')
						</template>
					</ul>
					<div class="list-item no-results" v-show="!items.length && !loading">
						@{{ noResults }}
					</div>
					@include('app.components.loading')
					@include('app.components.feeds.load-more')
				</div>
			</simple-feed>
		</div>
	</div>
@stop