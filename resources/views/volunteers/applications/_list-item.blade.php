<li class="list-item">
	{{-- status --}}
	<div class="column column--status column--15">
		<span class="status--positive" v-if="item.application.status == 'accepted'">accepted</span>
		<span class="status--negative" v-if="item.application.status == 'rejected'">rejected</span>
		<span class="status--neutral" v-if="item.application.status == 'pending'">pending</span>
	</div>
	{{-- name --}}
	<div class="column column--grow-1 column--big">
		<strong><a :href="item.opportunity.url">@{{ item.opportunity.title }}</a></strong>
		<br><small>By @{{ item.nonprofit.name }} / Applied on @{{ item.application.created_at | datestring }}</small>
	</div>
	{{-- buttons --}}
	<div class="column column--25">
		<a :href="item.application.url" class="btn btn--small btn--default btn--block">View Application</a>
	</div>
</li>