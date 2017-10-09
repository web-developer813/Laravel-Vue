<li class="list-item">
	{{-- status --}}
	<div class="column column--status column--10">
		<span v-if="item.incentive.active" class="status--positive">active</span>
		<span v-if="!item.incentive.active" class="status--neutral">inactive</span>
	</div>
	{{-- title --}}
	<div class="column column--grow-1 column--big">
		<strong><a :href="item.admin.edit_url">@{{ item.incentive.title }}</a></strong>
		<br>
		<small>@{{ item.incentive.price }} points</small>
	</div>
	{{-- edit --}}
	<div class="column column--10">
		<a :href="item.admin.edit_url" class="btn btn--default btn--xs btn--block">Edit</a>
	</div>
	{{-- purchases --}}
	<div class="column column--15">
		<a :href="item.admin.purchases_url" class="btn btn--default btn--xs btn--block">
			Purchases (@{{ item.admin.purchases_count }})
		</a>
	</div>
</li>