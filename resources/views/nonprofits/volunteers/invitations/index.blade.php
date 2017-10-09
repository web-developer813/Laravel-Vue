@extends('nonprofits.layout')

@section('page_id', 'nonprofit-admin-employees-index')

@section('page-header')
	<div class="page-header">
		<h2>Invitations</h2>
	</div>
@stop

@section('content')
	<div class="two-columns">
		@include('nonprofits.volunteers.index._sidebar')

		<div class="single-column">
			<simple-feed
				resource-url="{{ route('api.nonprofits.invitations', [$authNonprofit->id, 'type' => 'volunteer']) }}"
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
					<div class="list">
						<ul>
							<template v-for="item in items">
								@include('nonprofits.volunteers.invitations._list-item')
							</template>
						</ul>
						<div class="list-item no-results" v-show="!items.length && !loading">
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