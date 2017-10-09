<header class="app-header app-header--admin">
	<div class="container">
		<a href="{{ route('admin.dashboard') }}" class="header__logo desktop-only">
			<img src='/img/tecdonor-logo.png' srcSet="/img/tecdonor-logo.png 1x, /img/tecdonor-logo@2x.png 2x" alt="Tecdonor" height="18" />
		</a>
		<ul class="header__nav">
			@include('app.components.nav-link', [
				'route' => 'admin.nonprofits.index', 'icon' => 'fa-th-list', 'name' => 'Nonprofits'
			])
			@include('app.components.nav-link', [
				'route' => 'admin.forprofits.index', 'icon' => 'fa-th-list', 'name' => 'Forprofits'
			])
			@include('app.components.nav-link', [
				'route' => 'admin.volunteers.index', 'icon' => 'fa-user', 'name' => 'Volunteers'
			])
			<li class="desktop-only">
				<div class="dropdown header__dropdown">
					<a href="#" class="dropdown-trigger" data-toggle="dropdown">
						@include('app.components.avatar', [
							'source' => $authVolunteer->profilePhoto, 'name' => $authVolunteer->name
						])
					</a>
					<ul class="dropdown-menu dropdown-menu-right">
						<li><a href="{{ route('newsfeed') }}">
							Leave Admin Mode</a></li>
					</ul>
				</div>
			</li>
		</ul>
	</div>
</header>