@extends('volunteers.layout')

@section('page_id', 'applications-show')

@section('content')
	<div class="single-column">

		{{-- response --}}
		<div class="response feed-item feed-item--has-status">
			<div class="item__status {{ $application->present()->css_status }}"></div>
			
			{{-- pending --}}
			@if($application->isPending())
				<div class="response--pending">
					<strong>{{ $nonprofit->present()->name }} has not responded yet.</strong>
				</div>
			@else
				@if($application->isAccepted())
					<strong>Your application has been accepted!</strong>
				@elseif($application->isRejected())
					<strong>Your application has been declined.</strong>
				@endif

				@if($application->nonprofit_message)
					<div class="nonprofit-message">
						{!! $application->present()->nonprofit_message !!}
					</div>
				@endif
			@endif
		</div>
		
		{{-- opportunity --}}
		<div class="opportunity feed-item">
			<div class="item__content">
				<div class="item__name">
				{{ $opportunity->title }}
				</div>

				<div class="item__meta">
				Posted by <a href="{{ route('nonprofits.show', $opportunity->nonprofit_id) }}">{{ $opportunity->nonprofit->name}}</a> &middot; {{ $opportunity->present()->date }}
				</div>

				<div class="item__description">
				<p>{{ $opportunity->present()->excerpt }}</p>
				</div>

				<a href="{{ route('opportunities.show', $opportunity->id) }}" class="opportunity__link">
					View opportunity details
				</a>
			</div>
		</div>

		{{-- your application --}}
		<div class="application">
			<strong>You applied for this opportunity {{ $application->present()->date }}.</strong>

			@if($application->volunteer_message)
				<div class="volunteer-message">
					{!! $application->present()->volunteer_message !!}
				</div>
			@endif
		</div>
	</div>
@stop