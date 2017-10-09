<div class="page-aside">
	<div class="page-aside-switch">
		<i class="icon md-chevron-left" aria-hidden="true"></i>
		<i class="icon md-chevron-right" aria-hidden="true"></i>
	</div>
	<div class="page-aside-inner page-aside-scroll">
		<div data-role="container">
			<div data-role="content">
				<section class="page-aside-section">
					<h5 class="page-aside-title">Total Volunteer Hours</h5>
					<!-- Panel Linearea Simple -->
					<div class="card card-shadow" id="chartVolunteerHours">
						<div class="card-block p-0 pl-30 pr-30">
							<div class="counter text-left">
								<span class="counter-number font-size-30">{{ $authVolunteer->present()->total_hours }}</span>
								<div class="counter-label font-size-12">{{ str_plural('Hour', $authVolunteer->total_hours) }} worked</div>
							</div>
							<div class="ct-chart h-100 ct-chart-orange"></div>
						</div>
					</div><!-- End Panel Linearea Simple -->
					<h5 class="page-aside-title">Tax Exempt Coins</h5>
					<!-- Panel Linearea Simple -->
					<div class="card card-shadow" id="chartVolunteerTec">
						<div class="card-block p-0 pl-30 pr-30">
							<div class="counter text-left">
								<span class="counter-number font-size-30">{{ $authVolunteer->present()->points }}</span>
								<div class="counter-label font-size-12">
									Reward {{ str_plural('Point', $authVolunteer->points) }}
								</div>
							</div>
							<div class="ct-chart h-100 ct-chart-indigo"></div>
						</div>
					</div><!-- End Panel Linearea Simple -->
					<h5 class="page-aside-title">Total Donations</h5>
					<!-- Panel Linearea Simple -->
					<div class="card card-shadow" id="chartVolunteerDonations">
						<div class="card-block p-0 pl-30 pr-30">
							<div class="counter text-left">
								<span class="counter-number font-size-30">{{ $authVolunteer->present()->total_points_donations }}</span>
								<div class="counter-label font-size-12">{{ str_plural('Point', $authVolunteer->total_points_donations) }} donated</div>
							</div>
							<div class="ct-chart h-100 ct-chart-green"></div>
						</div>
					</div><!-- End Panel Linearea Simple -->
					<h5 class="page-aside-title">Connections</h5>
					<!-- Panel Linearea Simple -->
					<div class="card card-shadow" id="chartVolunteerConnections">
						<div class="card-block p-0 pl-30 pr-30">
							<div class="counter text-left">
								<span class="counter-number font-size-30">{{ $authVolunteer->totalfollows }}</span>
								<div class="counter-label font-size-12">Connections</div>
							</div>
							<div class="ct-chart h-100"></div>
						</div>
					</div><!-- End Panel Linearea Simple -->
				</section>
			</div>
		</div>
	</div><!---page-aside-inner-->
</div>
@section('jquery')
	@include('volunteers.components.charts')
@stop