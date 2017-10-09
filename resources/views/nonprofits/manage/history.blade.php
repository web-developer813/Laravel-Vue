@extends('nonprofits.layout')

@section('page_id', 'nonprofits-dashboard')

@section('page-header')
	<div class="page-header">
		<h2>History for <strong>{{ $opportunity->title }}</strong></h2>
	</div>
@stop

@section('content')
	<div class="two-columns">
		@include('nonprofits.manage._sidebar')
		<div class="single-column">
			<simple-feed
				resource-url="{{ route('api.nonprofits.hours', [
					'nonprofit' => $authNonprofit->id,
					'opportunity' => $opportunity->id,
				]) }}"
				no-results="There is currently no history for this opportunity."
				inline-template>
				<div class="list" v-cloak>
					<ul>
						<template v-for="item in items">
							@include('nonprofits.manage.history._list-item')
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