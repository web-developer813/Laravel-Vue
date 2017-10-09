<li class="feed-item feed-item--verify-hours">
	@include('app.components.profile-photo', [
		'source' => $application->volunteer->profilePhoto, 'name' => $application->volunteer->name, 'class' => 'profilePhoto--profile'
	])
	<div class="item__name">{{ $application->volunteer->name }}</div>
	<div class="item__meta">
		{{-- status --}}
		<span class="meta__status {{ $application->present()->css_status }}">
			{{ $application->present()->status }}
		</span>

		{{-- time --}}
		&middot; {{ $application->present()->date }}
	</div>
	<div class="item__buttons">
		<a href="{{ route('nonprofits.applications.edit', [$authNonprofit->id, $application->id]) }}" class="btn btn--small btn--block">View Application</a>
	</div>
</li>