@extends('volunteers.layout')

@section('page_id', 'applications-index')

@section('page-header')
	<div class="page-header">
		<h2>Applications</h2>
	</div>
@stop

@section('content')
	<div class="two-columns">
		@include('volunteers.applications._sidebar')
		<div class="single-column">
			<simple-feed
				resource-url="{{ route('api.volunteers.applications', ['status' => request()->status]) }}"
				inline-template>
				<div v-cloak>

					{{-- filters --}}
					<div class="feed-filters flex-columns">
						<div class="filters-group column column--grow-1">
							<button class="filter__option" v-bind:class="{'active': filters.status == ''}" @click="filters.status = ''">All</button>
							<button class="filter__option" v-bind:class="{'active': filters.status == 'accepted'}" @click="filters.status = 'accepted'">Accepted</button>
							<button class="filter__option" v-bind:class="{'active': filters.status == 'rejected'}" @click="filters.status = 'rejected'">Rejected</button>
							<button class="filter__option" v-bind:class="{'active': filters.status == 'pending'}" @click="filters.status = 'pending'">Pending</button>
						</div>
						<div class="filters-group column column--35">
							<div class="field--text">
								<input type="text" v-bind:value="filters.search" @input="updateSearchQuery" placeholder="Search...">
							</div>
						</div>
					</div>

					<div class="list">
						<ul>
							<template v-for="item in items">
								@include('volunteers.applications._list-item')
							</template>
						</ul>
						<div class="list-item no-results" v-show="!items.length && !loading">
							You don't have any @{{ filters.status }} applications.
						</div>
						@include('app.components.loading')
						@include('app.components.feeds.load-more')
					</div>

				</div>
			</simple-feed>
		</div>
	</div>
@stop