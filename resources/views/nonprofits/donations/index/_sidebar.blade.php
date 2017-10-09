<aside class="sidebar">
	<ul class="sidebar__menu">
		<li class="menu__item">
			<a href="{{ route('nonprofits.forprofits.index', $authNonprofit->id) }}" class="{{ nav_active('nonprofits.forprofits.index', 'route') }}">Request New Donations</a></li>
		<li class="menu__item">
			<a href="{{ route('nonprofits.donations.index', $authNonprofit->id) }}" class="{{ nav_active('nonprofits.donations.index', 'route') }}">Donations History</a></li>
	</ul>
</aside>