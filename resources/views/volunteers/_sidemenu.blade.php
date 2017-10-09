<div class="site-menubar">
	<div class="site-menubar-body">
		<div>
			<div>
				<ul class="site-menu" data-plugin="menu">
					<li class="site-menu-category">Menu</li>
					<li class="site-menu-item {{ nav_active('dashboard') }}">
						<a class="animsition-link" href="{{ route('dashboard') }}">
							<i class="site-menu-icon md-view-dashboard" aria-hidden="true"></i>
							<span class="site-menu-title">Dashboard</span>
						</a>
					</li>
					<li class="site-menu-item hidden-xs">
						<a class="animsition-link">
							<i class="site-menu-icon md-accounts" aria-hidden="true"></i>
							<span class="site-menu-title">Groups</span>  <div class="site-menu-label"><span class="badge badge-sm badge-primary">Coming Soon</span></div>
						</a>
					</li>
					<li class="site-menu-item">
						<a class="animsition-link" href="javascript:void(0)">
							<i class="site-menu-icon md-star" aria-hidden="true"></i>
							<span class="site-menu-title">Saved</span>  <div class="site-menu-label"><span class="badge badge-sm badge-primary">Coming Soon</span></div>
						</a>
					</li>
					<li class="site-menu-item {{ nav_active('hours.index') }}">
						<a class="animsition-link" href="{{ route('hours.index') }}">
							<i class="site-menu-icon md-time" aria-hidden="true"></i>
							<span class="site-menu-title">History</span>  <div class="site-menu-label"><span class="badge badge-sm badge-primary">Coming Soon</span></div>
						</a>
					</li>
					<li class="site-menu-item {{ nav_active('nonprofits.index') }}">
						<a class="animsition-link" href="{{ route('nonprofits.index') }}">
							<i class="site-menu-icon md-globe" aria-hidden="true"></i>
							<span class="site-menu-title">Nonprofits</span>  <div class="site-menu-label"><span class="badge badge-sm badge-primary">Coming Soon</span></div>
						</a>
					</li>
					<li class="site-menu-item {{ nav_active('forprofits.index', 'resource') }} {{ nav_active('incentives.show') }}">
						<a class="animsition-link" href="{{ route('forprofits.index') }}">
							<i class="site-menu-icon md-shopping-basket" aria-hidden="true"></i>
							<span class="site-menu-title">Marketplace</span>  <div class="site-menu-label"><span class="badge badge-sm badge-primary">Coming Soon</span></div>
						</a>
					</li>
					<li class="site-menu-item {{ nav_active('user.connections') }}">
						<a class="animsition-link" href="{{ route('user.connections') }}">
							<i class="site-menu-icon md-accounts-list-alt" aria-hidden="true"></i>
							<span class="site-menu-title">Connections</span>
						</a>
					</li>
					<li class="site-menu-item {{ nav_active('settings.profile') }}">
						<a class="animsition-link" href="{{ route('settings.profile') }}">
							<i class="site-menu-icon md-settings" aria-hidden="true"></i>
							<span class="site-menu-title">Settings</span>
						</a>
					</li>
					@if(count(auth()->user()->nonprofitsWithAdminAccess) || count(auth()->user()->forprofitsWithAdminAccess))
					<li class="site-menu-category">Organizations</li>
					@endif
					@if(count(auth()->user()->nonprofitsWithAdminAccess))
					<li class="site-menu-item has-sub">
						<a href="javascript:void(0)">
							<i class="site-menu-icon md-money-off" aria-hidden="true"></i>
							<span class="site-menu-title">My Nonprofits</span>
							<span class="site-menu-arrow"></span>
						</a>
						<ul class="site-menu-sub">
							@foreach(auth()->user()->nonprofitsWithAdminAccess as $nonprofit)
							<li class="site-menu-item">
								<a class="animsition-link" href="{{ route('switch-mode', ['mode' => 'nonprofit', 'nonprofit' => $nonprofit->id]) }}">
									<span class="site-menu-title">{{ $nonprofit->name }}</span>
								</a>
							</li>
							@endforeach
						</ul>
					</li>
					@endif
					@if(count(auth()->user()->forprofitsWithAdminAccess))
					<li class="site-menu-item has-sub">
						<a href="javascript:void(0)">
							<i class="site-menu-icon md-city-alt" aria-hidden="true"></i>
							<span class="site-menu-title">My Businesses</span>
							<span class="site-menu-arrow"></span>
						</a>
						<ul class="site-menu-sub">
							@foreach(auth()->user()->forprofitsWithAdminAccess as $forprofit)
							<li class="site-menu-item">
								<a class="animsition-link" href="{{ route('switch-mode', ['mode' => 'forprofit', 'forprofit' => $forprofit->id]) }}">
									<span class="site-menu-title">{{ $forprofit->name }}</span>
								</a>
							</li>
							@endforeach
						</ul>
					</li>
					@endif
					<li class="site-menu-category">Help</li>
					<li class="site-menu-item has-sub">
						<a href="javascript:void(0)">
							<i class="site-menu-icon md-file-text" aria-hidden="true"></i>
							<span class="site-menu-title">Documentation</span>
							<span class="site-menu-arrow"></span>
						</a>
						<ul class="site-menu-sub">
							<li class="site-menu-item">
								<a class="animsition-link" href="#">
									<span class="site-menu-title">FAQ</span>
								</a>
							</li>
							<li class="site-menu-item">
								<a class="animsition-link" href="#">
									<span class="site-menu-title">User Manual</span>
								</a>
							</li>
							<li class="site-menu-item">
								<a class="animsition-link" href="#">
									<span class="site-menu-title">Forums</span>
								</a>
							</li>
						</ul>
					</li>
					<li class="site-menu-item has-sub">
						<a href="javascript:void(0)">
							<i class="site-menu-icon md-help-outline" aria-hidden="true"></i>
							<span class="site-menu-title">Need Help?</span>
							<span class="site-menu-arrow"></span>
						</a>
						<ul class="site-menu-sub">
							<li class="site-menu-item">
								<a class="animsition-link" href="#">
									<span class="site-menu-title">Live Chat</span>
								</a>
							</li>
							<li class="site-menu-item">
								<a class="animsition-link" href="#">
									<span class="site-menu-title">Contact Support</span>
								</a>
							</li>
						</ul>
					</li>
				</ul>
			</div>
		</div>
	</div>
	<div class="site-menubar-footer">
		<a href="{{ route('settings.profile') }}" class="fold-show" data-placement="top" data-toggle="tooltip" data-original-title="Settings">
			<span class="icon md-settings" aria-hidden="true"></span>
		</a>
		<a href="javascript: void(0);" data-placement="top" data-toggle="site-sidebar" data-original-title="Chat" data-url="site-sidebar.tpl">
			<span class="icon md-comment" aria-hidden="true"></span>
		</a>
		<a href="{{ route('logout') }}" data-placement="top" data-toggle="tooltip" data-original-title="Logout">
			<span class="icon md-power" aria-hidden="true"></span>
		</a>
	</div>
</div>