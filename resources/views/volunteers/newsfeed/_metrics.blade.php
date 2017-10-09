<div class="dashboard-metrics flex-columns">
	<div class="dashboard-metric column column--25">
		<strong>{{ $authVolunteer->present()->points }}</strong>
		<span>Reward {{ str_plural('Point', $authVolunteer->points) }}</span>
	</div>
	<div class="dashboard-metric column column--25">
		<strong>{{ $authVolunteer->present()->total_hours }}</strong>
		<span>{{ str_plural('Hour', $authVolunteer->total_hours) }} worked</span>
	</div>
	<div class="dashboard-metric column column--25">
		<strong>{{ $authVolunteer->present()->total_points_donations }}</strong>
		<span>{{ str_plural('Point', $authVolunteer->total_points_donations) }} donated</span>
	</div>
	<div class="dashboard-metric column column--25">
		<strong>$0</strong>
		<span>Money donated</span>
	</div>
</div>