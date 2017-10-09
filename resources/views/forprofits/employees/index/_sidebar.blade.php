<aside class="sidebar">
	{{-- employees --}}
	{{-- <section>
		<h3 class="sidebar__title sidebar__title--first">Total Employees</h3>
		<div class="sidebar__points">{{ number_format($authForprofit->employees()->count()) }}</div>
	</section> --}}

	{{-- menu --}}
	<ul class="sidebar__menu">
		<li class="menu__item">
			<a href="{{ route('forprofits.employees.index', $authForprofit->id) }}" class="{{ nav_active('forprofits.employees.index', 'route') }}">Manage Employees</a></li>
		<li class="menu__item">
			<a href="{{ route('forprofits.invitations.create', $authForprofit->id) }}" class="{{ nav_active('forprofits.invitations.create', 'route') }}">Invite Employees</a></li>
		<li class="menu__item">
			<a href="{{ route('forprofits.invitations.index', $authForprofit->id) }}" class="{{ nav_active('forprofits.invitations.index', 'route') }}">Invitations</a></li>
	</ul>
</aside>