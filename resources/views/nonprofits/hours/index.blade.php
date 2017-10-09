@extends('nonprofits.layout')

@section('page_id', 'nonprofit-admin-hours-index')

@section('content')
	<div class="two-columns">
		<aside class="sidebar">
			@include('nonprofits.hours._sidebar')
		</aside>
		
		<div class="single-column">
			<div class="feed-filters">
				@include('app.components.feeds.filter-option', [
					'route' => 'nonprofits.hours.index',
					'params' => [$authNonprofit->id],
					'value' => 'Select All'
				])
				@include('app.components.feeds.filter-option', [
					'route' => 'nonprofits.hours.index',
					'params' => [$authNonprofit->id, 'status' => 'accepted'],
					'value' => 'Select None'
				])
			</div>
			<div class="feed feed--two">
				<ul>
					@foreach($volunteers as $volunteer)
						@include('nonprofits.hours._feed-item')
					@endforeach
				</ul>
				<div class="feed__no-results">
					There are currently no volunteers matching your search criteria.
				</div>
			</div>
		</div>
	</div>
@stop