<aside class="sidebar">
	{{-- menu --}}
	<ul class="sidebar__menu">
		<li class="menu__item">
			<a href="{{ route('nonprofits.employees.index', $authNonprofit->id) }}" class="{{ nav_active('nonprofits.employees.index', 'route') }}">Manage Employees</a></li>
		<li class="menu__item">
			<a href="{{ route('nonprofits.employees.invitations.create', $authNonprofit->id) }}" class="{{ nav_active('nonprofits.employees.invitations.create', 'route') }}">Invite Employees</a></li>
		<li class="menu__item">
			<a href="{{ route('nonprofits.employees.invitations.index', $authNonprofit->id) }}" class="{{ nav_active('nonprofits.employees.invitations.index', 'route') }}">Invitations</a></li>
	</ul>
</aside>