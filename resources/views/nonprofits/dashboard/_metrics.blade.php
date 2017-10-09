<div class="dashboard-metrics flex-columns">
	<div class="dashboard-metric column column--33">
		<strong>{{ $authNonprofit->present()->total_volunteers }}</strong>
		<span>{{ str_plural('Volunteer', $authNonprofit->total_volunteers) }}</span>
	</div>
	<div class="dashboard-metric column column--33">
		<strong>{{ $authNonprofit->present()->total_hours }}</strong>
		<span>{{ str_plural('Hour', $authNonprofit->total_hours) }} verified</span>
	</div>
	<div class="dashboard-metric column column--33">
		<strong>{{ $authNonprofit->present()->points }}</strong>
		<span>{{ str_plural('Point', $authNonprofit->points) }}</span>
	</div>
	{{-- <div class="dashboard-metric column column--25">
		<strong>$0</strong>
		<span>Money received</span>
	</div> --}}
</div>