<nav class="site-navbar navbar navbar-default navbar-fixed-top navbar-mega" role="navigation">
	<div class="navbar-header">
		<button type="button" class="navbar-toggler hamburger hamburger-close navbar-toggler-left hided" data-toggle="menubar">
			<span class="sr-only">Toggle navigation</span>
			<span class="hamburger-bar"></span>
		</button>
		<button type="button" class="navbar-toggler collapsed" data-target="#site-navbar-collapse" data-toggle="collapse">
			<i class="icon md-more" aria-hidden="true"></i>
		</button>
		<div class="navbar-brand navbar-brand-center site-gridmenu-toggle" data-toggle="gridmenu">
			<img class="navbar-brand-logo" src="/img/launcher/launcher-icon.png" title="Tecdonor">
			<span class="navbar-brand-text hidden-xs-down font-weight-400"> Business Platform</span>
		</div>
		<button type="button" class="navbar-toggler collapsed" data-target="#site-navbar-search" data-toggle="collapse">
			<span class="sr-only">Toggle Search</span>
			<i class="icon md-search" aria-hidden="true"></i>
		</button>
	</div>
	<div class="navbar-container container-fluid">
		<!-- Navbar Collapse -->
		<div class="collapse navbar-collapse navbar-collapse-toolbar" id="site-navbar-collapse">
			<!-- Navbar Toolbar -->
			<ul class="nav navbar-toolbar">
				<li class="nav-item">
					<a class="nav-link" href="javascript:void(0)">Engage</a>
				</li>
				<li class="nav-item">
					<a class="nav-link {{ nav_active('forprofits.donations.index', 'resource', $authForprofit->id) }}" href="{{ route('forprofits.donations.index', $authForprofit->id) }}">Donations</a>
				</li>
				<li class="nav-item {{ nav_active('forprofits.employees.index', 'resource', $authForprofit->id) }}">
					<a class="nav-link" href="{{ route('forprofits.employees.index', $authForprofit->id) }}">Employees</a>
				</li>
			</ul><!-- End Navbar Toolbar -->
			<!-- Navbar Toolbar Right -->
			<ul class="nav navbar-toolbar navbar-right navbar-toolbar-right">
				<li class="nav-item hidden-float">
					<a class="nav-link icon md-search" data-toggle="collapse" href="#" data-target="#site-navbar-search" role="button">
						<span class="sr-only">Toggle Search</span>
					</a>
				</li>
				<li class="nav-item dropdown">
					<a class="nav-link" data-toggle="dropdown" href="javascript:void(0)" title="Notifications" aria-expanded="false" data-animation="scale-up" role="button">
						<i class="icon md-notifications" aria-hidden="true"></i>
						<span class="badge badge-pill badge-danger up">5</span>
					</a>
					<div class="dropdown-menu dropdown-menu-right dropdown-menu-media" role="menu">
						<div class="dropdown-menu-header">
							<h5>NOTIFICATIONS</h5>
							<span class="badge badge-round badge-danger">New 5</span>
						</div>
						<div class="list-group">
							<div data-role="container">
								<div data-role="content">
									<a class="list-group-item dropdown-item" href="javascript:void(0)" role="menuitem">
										<div class="media">
											<div class="pr-10">
												<i class="icon md-receipt bg-red-600 white icon-circle" aria-hidden="true"></i>
											</div>
											<div class="media-body">
												<h6 class="media-heading">A new order has been placed</h6>
												<time class="media-meta" datetime="2017-06-12T20:50:48+08:00">5 hours ago</time>
											</div>
										</div>
									</a>
									<a class="list-group-item dropdown-item" href="javascript:void(0)" role="menuitem">
										<div class="media">
											<div class="pr-10">
												<i class="icon md-account bg-green-600 white icon-circle" aria-hidden="true"></i>
											</div>
											<div class="media-body">
												<h6 class="media-heading">Completed the task</h6>
												<time class="media-meta" datetime="2017-06-11T18:29:20+08:00">2 days ago</time>
											</div>
										</div>
									</a>
									<a class="list-group-item dropdown-item" href="javascript:void(0)" role="menuitem">
										<div class="media">
											<div class="pr-10">
												<i class="icon md-settings bg-red-600 white icon-circle" aria-hidden="true"></i>
											</div>
											<div class="media-body">
												<h6 class="media-heading">Settings updated</h6>
												<time class="media-meta" datetime="2017-06-11T14:05:00+08:00">2 days ago</time>
											</div>
										</div>
									</a>
									<a class="list-group-item dropdown-item" href="javascript:void(0)" role="menuitem">
										<div class="media">
											<div class="pr-10">
												<i class="icon md-calendar bg-blue-600 white icon-circle" aria-hidden="true"></i>
											</div>
											<div class="media-body">
												<h6 class="media-heading">Event started</h6>
												<time class="media-meta" datetime="2017-06-10T13:50:18+08:00">3 days ago</time>
											</div>
										</div>
									</a>
									<a class="list-group-item dropdown-item" href="javascript:void(0)" role="menuitem">
										<div class="media">
											<div class="pr-10">
												<i class="icon md-comment bg-orange-600 white icon-circle" aria-hidden="true"></i>
											</div>
											<div class="media-body">
												<h6 class="media-heading">Message received</h6>
												<time class="media-meta" datetime="2017-06-10T12:34:48+08:00">3 days ago</time>
											</div>
										</div>
									</a>
								</div>
							</div>
						</div>
						<div class="dropdown-menu-footer">
							<a class="dropdown-menu-footer-btn" href="javascript:void(0)" role="button">
								<i class="icon md-settings" aria-hidden="true"></i>
							</a>
							<a class="dropdown-item" href="javascript:void(0)" role="menuitem">All notifications</a>
						</div>
					</div>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="#" title="Chat" @click="showSidebar = !showSidebar">
						<i class="icon md-comment" aria-hidden="true"></i>
					</a>
				</li>
				<li class="nav-item dropdown">
					<a class="nav-link navbar-avatar" data-toggle="dropdown" href="#" aria-expanded="false" data-animation="scale-up" role="button">
						@include('app.components.avatar', ['source' => $authForprofit->profilePhoto, 'name' => $authForprofit->name])
					</a>
					<div class="dropdown-menu" role="menu">
						<a class="dropdown-item" href="{{ route('forprofits.show', $authForprofit->id) }}" role="menuitem"><i class="icon md-account" aria-hidden="true"></i>Our Profile</a>
						<a class="dropdown-item" href="{{ route('forprofits.settings.profile', $authForprofit->id) }}" role="menuitem"><i class="icon md-settings" aria-hidden="true"></i> Settings</a>
						<div class="dropdown-divider"></div>
						<a class="dropdown-item" href="{{ route('switch-mode', 'volunteer') }}" role="menuitem"><i class="icon md-money-off" aria-hidden="true"></i> Leave Business Mode</a>
						<a class="dropdown-item" href="{{ route('logout') }}" role="menuitem"><i class="icon md-power" aria-hidden="true"></i> Logout</a>
					</div>
				</li>
			</ul><!-- End Navbar Toolbar Right -->
		</div><!-- End Navbar Collapse -->
		<!-- Site Navbar Seach -->
		<div class="collapse navbar-search-overlap" id="site-navbar-search">
			<form role="search">
				<div class="form-group">
					<div class="input-search">
						<i class="input-search-icon md-search" aria-hidden="true"></i>
						<input type="text" class="form-control" name="site-search" placeholder="Search...">
						<button type="button" class="input-search-close icon md-close" data-target="#site-navbar-search" data-toggle="collapse" aria-label="Close"></button>
					</div>
				</div>
			</form>
		</div><!-- End Site Navbar Seach -->
	</div>
</nav>