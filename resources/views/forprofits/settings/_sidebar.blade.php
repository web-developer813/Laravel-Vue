<aside class="sidebar">
	<ul class="sidebar__menu">
		<li class="menu__item">
			<a href="{{ route('forprofits.settings.profile', $authForprofit->id) }}" class="{{ nav_active('forprofits.settings.profile', 'route') }}">Edit Profile</a></li>
		<li class="menu__item">
			<a href="{{ route('forprofits.settings.contact', $authForprofit->id) }}" class="{{ nav_active('forprofits.settings.contact', 'route') }}">Contact Information</a></li>
	</ul>
</aside>