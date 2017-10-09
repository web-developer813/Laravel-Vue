<li class="list-item">
	{{-- name --}}
	<div class="column column--grow-1 column--big">
		<strong>@{{ item.opportunity.title }}</strong>
		<br><small>@{{ item.hours.minutes | min-to-hours }} worked</small>
	</div>
	{{-- points --}}
	<div class="column column--20 u-center">
		@{{ item.hours.points | number }}
		<br><small>points earned</small>
	</div>
	{{-- date --}}
	<div class="column column--25 u-center">
		<span>@{{ item.hours.start_date | datestring}}</span>
		<small v-if="item.hours.has_multiple_dates"><br>to @{{ item.hours.end_date | datestring}}</small>
	</div>
</li>