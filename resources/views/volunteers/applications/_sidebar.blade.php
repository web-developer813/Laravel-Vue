<div class="sidebar">
	<ul class="sidebar__menu">
		<li class="menu__item">
			<a href="{{ route('applications.index') }}" class="{{ nav_active('applications.index', 'route') }}"> Applications</a></li>
		<li class="menu__item">
			<a href="{{ route('hours.index') }}" class="{{ nav_active('hours.index', 'route') }}">Hours Worked</a></li>
		<li class="menu__item">
			<a href="{{ route('incentive-purchases.index') }}" class="{{ nav_active('incentive-purchases.index', 'route') }}">Coupons Purchased</a></li>
	</ul>
</div>