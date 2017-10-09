@extends('volunteers.layout')

@section('page_id', 'nonprofit-admin-forprofits-index')

@section('page-content')	
<div class="page">	
	<simple-feed
		resource-url="{{ route('api.volunteers.forprofits') }}"
		no-results="There are currently no businesses matching your criteria."
		inline-template>
	<div class="page-main" v-cloak>
		<div class="page-header">
			<h1 class="page-title">Marketplace</h1>
			<div class="page-header-actions">
				<form>
					<div class="input-search input-search-dark">
						<i class="input-search-icon md-search" aria-hidden="true"></i>
						<input type="text" class="form-control" v-bind:value="filters.search" @input="updateSearchQuery" placeholder="Search Businesses...">
					</div>
				</form>
			</div>
		</div>
		<div class="page-content container-fluid">
			<div class="row js-masonry">
				<template v-for="item in items">
					@include('volunteers.forprofits._feed-item')
				</template>
			</div>
			<div class="row justify-content-center" v-show="!items.length && !loading">
				<div class="col-sm-10 text-center">
					@{{ noResults }}
				</div>
			</div>
			<div class="row justify-content-center" v-show="loading">
				<div class="col-md-10 text-center">
					@include('app.components.loading')
				</div>
			</div>
			@include('app.components.feeds.load-more')
		</div>
	</div>
	</simple-feed>
</div>
@stop