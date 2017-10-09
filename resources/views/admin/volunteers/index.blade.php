@extends('admin.layout')

@section('page-header')
	<div class="page-header">
		<h2>Volunteers</h2>
	</div>
@stop

@section('content')
	<div class="container">
		<div class="single-column single-column--wide">
		<simple-feed
			resource-url="{{ route('api.admin.volunteers') }}"
			no-results="There are no volunteers matching your criteria."
			inline-template>
			<div v-cloak>

				{{-- filters --}}
				<div class="feed-filters flex-columns">
					<div class="filters-group column column--80 column--grow-1">
						<div class="field--text">
							<input type="text" v-bind:value="filters.search" @input="updateSearchQuery" placeholder="Search volunteers...">
						</div>
					</div>
					<div class="filters-group column--20 column--grow-1">
						@{{ meta.count | number | pluralize 'volunteer' true }}
					</div>
				</div>
				
				{{-- feed --}}
				<div class="list">
					<ul>
						<template v-for="item in items">
							@include('admin.volunteers.index._list-item')
						</template>
					</ul>
					<div class="list-item no-results" v-show="!items.length && !loading">
						@{{{ noResults }}}
					</div>
					@include('app.components.loading')
					@include('app.components.feeds.load-more')
				</div>

			</div>
		</simple-feed>
		</div>
	</div>
@stop