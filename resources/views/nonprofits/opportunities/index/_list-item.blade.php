<li class="list-item">
	{{-- status --}}
	<div class="column column--status column--10">
		<span v-if="item.opportunity.expired" class="status--negative">expired</span>
		<span v-if="!item.opportunity.expired && item.opportunity.published" class="status--positive">published</span>
		<span v-if="!item.opportunity.published" class="status--neutral">draft</span>
	</div>
	{{-- title --}}
	<div class="column column--grow-1 column--big">
		<strong><a v-bind:href="item.admin.edit_url">@{{ item.opportunity.title }}</a></strong>
		<br>
		<small v-if="item.opportunity.has_dates">@{{ item.opportunity.formatted_dates }}</small>
		<small v-if="!item.opportunity.has_dates">Flexible dates</small>
		<small>/</small>
		<small v-if="item.opportunity.has_location">@{{ item.opportunity.short_location }}</small>
		<small v-if="!item.opportunity.has_location">Remote work</small>
	</div>
	{{-- edit --}}
	<div class="column column--10">
		<a v-bind:href="item.admin.edit_url" class="btn btn--default btn--xs btn--block">Edit</a>
	</div>
	{{-- applications --}}
	<div class="column column--15">
		<a v-bind:href="item.admin.applications_url" class="btn btn--default btn--xs btn--block">
			Applications
			<span v-if="item.admin.applications_count">(@{{ item.admin.applications_count }})</span>
		</a>
	</div>
	{{-- verify hours --}}
	<div class="column column--15">
		<a v-bind:href="item.admin.verify_hours_url" class="btn btn--default btn--xs btn--block">Verify Hours</a>
	</div>
</li>