<div class="sidebar">
	<ul class="sidebar__menu">
		<li class="menu__item">
			<a href="{{ route('nonprofits.invitations.create', $authNonprofit->id) }}" class="{{ nav_active('nonprofits.invitations.create', 'route') }}">Invite Volunteers</a></li>
		<li class="menu__item">
			<a href="{{ route('nonprofits.invitations.index', $authNonprofit->id) }}" class="{{ nav_active('nonprofits.invitations.index', 'route') }}">Invitations</a></li>
	</ul>
</div>