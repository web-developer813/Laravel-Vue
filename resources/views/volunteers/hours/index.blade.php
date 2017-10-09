@extends('volunteers.layout')

@section('page_id', 'work-hours-index')

@section('page-header')
	<div class="page-header">
		<h2>Hours Worked</h2>
	</div>
@stop

@section('content')
	<div class="two-columns">
		@include('volunteers.applications._sidebar')
		<div class="single-column">
			<simple-feed
				resource-url="{{ route('api.volunteers.hours', []) }}"
				no-results="You don't currently have any history."
				inline-template>
				<div class="list" v-cloak>
					<ul>
						<template v-for="item in items">
							@include('volunteers.hours._list-item')
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