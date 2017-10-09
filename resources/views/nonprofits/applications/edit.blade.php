@extends('nonprofits.layout')

@section('page_id', 'nonprofit-admin-applications-edit')

@section('content')
	<div class="two-columns">
		{{-- main content --}}
		<div class="single-column">
			{{-- status --}}
			<div class="response feed-item feed-item--has-status">
				<div class="item__status {{ $application->present()->css_status }}"></div>
				<strong class="response__status">This application is currently {{ strtolower($application->present()->status) }}.</strong>
			</div>

			{{-- application --}}
			<div class="application">
				<strong>{{ $volunteer->present()->name }} applied for this opportunity {{ $application->present()->date }}.</strong>

				@if($application->volunteer_message)
					<div class="volunteer-message">
						{!! $application->present()->volunteer_message !!}
					</div>
				@endif

				<a href="{{ route('volunteers.show', $volunteer->id) }}" class="btn btn--small volunteer-link" target="_blank">
					View volunteer details
				</a>

				@if($volunteer->resume)
					<a href="{{ $volunteer->resume_url }}" class="btn btn--small" target="_blank">View volunteer's resume</a>
				@endif
			</div>

			{{-- respond --}}
			@if(!$opportunity->closed || strtolower($application->present()->status) == 'accepted')
				{{ Form::open([
					'route' => ['nonprofits.applications.update', $authNonprofit->id, $application->id],
					'method' => 'put', 'class' => 'response__form form--simple js-form'
				]) }}
					@include('app.components.form-field', [
						'field' => 'nonprofit_message', 'label' => 'Enter a message for the volunteer',
						'input' => Form::textarea('nonprofit_message', $application->nonprofit_message, ['class' => 'form__field', 'rows' => 3])
					])
					<div class="form__footer">
						<button type="submit" name="accepted" value="1" class="btn btn--primary btn--large btn--accept js-button-value">
							Accept
						</button>
						<button type="submit" name="accepted" value="0" class="btn btn--danger btn--large btn--reject js-button-value">
							Reject
						</button>
					</div>
				{{ Form::close() }}
			@else
				<h4>The quantity of accepted applicants is reach for this opportunity!</h4>
			@endif
		</div>
		
		{{-- opportunity --}}
		<aside class="sidebar sidebar--right">
			<div class="box">
				<div class="item__name">
				{{ $opportunity->title }}
				@if($opportunity->closed)
				<small>(closed)</small>
				@endif
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
		</aside>

	</div>
@stop