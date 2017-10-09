<aside class="sidebar">

	{{-- menu --}}
	<ul class="sidebar__menu">
		<li class="menu__item">
			<a href="{{ route('nonprofits.volunteers.index', $authNonprofit->id) }}" class="{{ nav_active('nonprofits.volunteers.index', 'route') }}">Browse Volunteers</a></li>
		<li class="menu__item">
			<a href="{{ route('nonprofits.volunteers.invitations.create', $authNonprofit->id) }}" class="{{ nav_active('nonprofits.volunteers.invitations.create', 'route') }}">Invite Volunteers</a></li>
		<li class="menu__item">
			<a href="{{ route('nonprofits.volunteers.invitations.index', $authNonprofit->id) }}" class="{{ nav_active('nonprofits.volunteers.invitations.index', 'route') }}">Invitations</a></li>
	</ul>
	
	{{-- volunteers --}}
	{{-- <section>
		<h3 class="sidebar__title sidebar__title--first">Total Volunteers</h3>
		<div class="sidebar__points">{{ number_format($authNonprofit->volunteers->count()) }}</div>
	</section> --}}
</aside>