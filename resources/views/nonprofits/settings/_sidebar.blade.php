<div class="sidebar">
	<ul class="sidebar__menu">
		<li class="menu__item">
			<a href="{{ route('nonprofits.settings.profile', $authNonprofit->id) }}" class="{{ nav_active('nonprofits.settings.profile', 'route') }}">Edit Profile</a></li>
		<li class="menu__item">
			<a href="{{ route('nonprofits.settings.contact', $authNonprofit->id) }}" class="{{ nav_active('nonprofits.settings.contact', 'route') }}">Contact Information</a></li>
		<li class="menu__item">
			<a href="{{ route('nonprofits.settings.categories', $authNonprofit->id) }}" class="{{ nav_active('nonprofits.settings.categories', 'route') }}">Interests</a></li>
		{{-- <li class="menu__item">
			<a href="{{ route('nonprofits.settings.billing', $authNonprofit->id) }}" class="{{ nav_active('nonprofits.settings.billing', 'resource', [$authNonprofit->id]) }}">Billing</a></li> --}}
	</ul>
</div>