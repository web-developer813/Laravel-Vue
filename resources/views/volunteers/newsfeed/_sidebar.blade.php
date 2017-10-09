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
						<div class="card-block p-0 p-30">
							<div class="counter text-left">
								<span class="counter-number">{{ $authVolunteer->present()->total_hours }}</span>
								<div class="counter-label text-uppercase mb-20">{{ str_plural('Hour', $authVolunteer->total_hours) }} worked</div>
							</div>
							<div class="ct-chart h-100"></div>
						</div>
					</div><!-- End Panel Linearea Simple -->
				</section>
				<section class="page-aside-section">
					<h5 class="page-aside-title">More Stuff</h5>
				</section>
			</div>
		</div>
	</div><!---page-aside-inner-->
</div>
@section('jquery')
	@include('volunteers.components.charts')
@stop