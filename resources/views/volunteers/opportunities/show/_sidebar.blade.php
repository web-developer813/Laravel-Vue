<aside class="sidebar sidebar--right">

	{{-- aleady applied --}}
	@if(current_mode('volunteer'))
		@if($authVolunteer->hasAppliedForOpportunity($opportunity->id))
			<section class="opportunity__application-status status--applied box">
				<i class="fa fa-check" aria-hidden="true"></i>
				You applied for this opportunity
			</section>
		@endif
	@endif

	{{-- details --}}
	<section class="details">
		<h2>Details</h2>
	
		<div class="details__line details__dates {{ $opportunity->expired ? 'dates--expired' : '' }}">
			<i class="fa fa-fw fa-calendar" aria-hidden="true"></i>
			@if($opportunity->has_dates)
				<span>
					{{ $opportunity->start_date->format('M jS, Y') }}
					@if($opportunity->has_multiple_dates)
						<span>&mdash; {{ $opportunity->end_date->format('M jS, Y') }}</span>
					@endif
					@if($opportunity->expired)
						<span>(Expired)</span>
					@endif
				</span>
			@else
				<span>It's flexible! We'll work with your schedule.</span>
			@endif
		</div>

		<div class="details__line details__location">
			<i class="fa fa-fw fa-map-marker" aria-hidden="true"></i>
			@if($opportunity->has_location)
				<span>{{ $opportunity->full_address }}</span>
			@else
				<span>Can be done remotely</span>
			@endif
		</div>
		
		@if($opportunity->nonprofit->website_url)
			<div class="details__line details__website">
				<i class="fa fa-fw fa-globe" aria-hidden="true"></i>
				<a href="{{ $opportunity->nonprofit->website_url }}" target="_blank" rel="nofollow">{{ $opportunity->nonprofit->formatted_website_url }}</a>
			</div>
		@endif
	</section>

	{{-- categories --}}
	@if($opportunity->categories)
		<section class="categories">
			@include('app.components.categories-list', ['categories' => $opportunity->categories])
		</section>
	@endif

	{{-- apply --}}
	@if(current_mode('volunteer'))
		@if(!$authVolunteer->hasAppliedForOpportunity($opportunity->id) && !$opportunity->expired && !$opportunity->closed)
			<section class="opportunity__apply">
				<h2>Why are you interested in this opportunity?</h2>
				{{ Form::open(['route' => ['opportunities.apply', $opportunity->id], 'method' => 'post', 'class' => 'form--simple js-form']) }}
					@include('app.components.form-field', [
						'field' => 'volunteer_message',
						'input' => Form::textarea('volunteer_message', old('volunteer_message'), ['class' => 'form__field', 'rows' => 3])
					])
					<div class="form__footer">
						<button type="submit" class="btn btn--primary btn--block btn--large">
							Apply
						</button>
					</div>
				{{ Form::close() }}
			</section>
		@endif
	@endif
</aside>