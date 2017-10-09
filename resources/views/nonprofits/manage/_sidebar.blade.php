<div class="sidebar">
	<ul class="sidebar__menu">
		<li class="menu__item">
			<a href="{{ route('nonprofits.manage.applications', [$authNonprofit->id, $opportunity->id]) }}" class="{{ nav_active('nonprofits.manage.applications', 'route') }}">Applications</a></li>
		<li class="menu__item">
			<a href="{{ route('nonprofits.manage.verify-hours', [$authNonprofit->id, $opportunity->id]) }}" class="{{ nav_active('nonprofits.manage.verify-hours', 'route') }}">Verify Hours</a></li>
		<li class="menu__item">
			<a href="{{ route('nonprofits.manage.history', [$authNonprofit->id, $opportunity->id]) }}" class="{{ nav_active('nonprofits.manage.history', 'route') }}">History</a></li>
	</ul>
</div>