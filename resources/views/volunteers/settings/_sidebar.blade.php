<div class="sidebar">
	<ul class="sidebar__menu">
		<li class="menu__item">
			<a href="{{ route('settings.profile') }}" class="{{ nav_active('settings.profile') }}">Edit Profile</a></li>
		<li class="menu__item">
			<a href="{{ route('settings.categories') }}" class="{{ nav_active('settings.categories') }}">Interests</a></li>
		<li class="menu__item">
			<a href="{{ route('settings.account') }}" class="{{ nav_active('settings.account') }}">Account</a></li>
		<li class="menu__item">
			<a href="{{ route('settings.billing') }}" class="{{ nav_active('settings.billing', 'resource') }}">Billing</a></li>
	</ul>
</div>