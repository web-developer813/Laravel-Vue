<div class="dashboard-metrics flex-columns">
	<div class="dashboard-metric column column--33">
		<strong>{{ $authForprofit->present()->total_hours }}</strong>
		<span>{{ str_plural('Hour', $authForprofit->total_hours) }} worked</span>
	</div>
	<div class="dashboard-metric column column--33">
		<strong>{{ $authForprofit->present()->total_points }}</strong>
		<span>{{ str_plural('Point', $authForprofit->total_points) }} donated</span>
	</div>
	<div class="dashboard-metric column column--33">
		<strong>{{ $authForprofit->present()->total_coupons_sold }}</strong>
		<span>{{ str_plural('Coupon', $authForprofit->total_coupons_sold) }} sold</span>
	</div>
	{{-- <div class="dashboard-metric column column--25">
		<strong>$0</strong>
		<span>Money received</span>
	</div> --}}
</div>