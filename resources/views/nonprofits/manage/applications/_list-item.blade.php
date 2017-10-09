<li class="list-item">
	{{-- status --}}
	<div class="column column--status column--15">
		<span class="status--positive" v-if="item.application.status == 'accepted'">accepted</span>
		<span class="status--negative" v-if="item.application.status == 'rejected'">rejected</span>
		<span class="status--neutral" v-if="item.application.status == 'pending'">pending</span>
	</div>
	{{-- image --}}
	<div class="column column--10 column--image column--square">
		{{-- profile photo --}}
		<a :href="item.volunteer.url" class="profile-photo">
			<img
				v-bind:src="item.volunteer.profile_photo"
				class="profile-photo__img"
				:alt="item.volunteer.name"
				v-if="item.volunteer.profile_photo">
			{{-- default image --}}
			<div class="profile-photo__default" v-if="!item.volunteer.profile_photo">
				<span>@{{ item.volunteer.initials }}</span>
			</div>
		</a>
	</div>
	{{-- name --}}
	<div class="column column--grow-1 column--big">
		<strong><a :href="item.volunteer.url">@{{ item.volunteer.name }}</a></strong>
	</div>
	{{-- select --}}
	<div class="column column--25">
		<a :href="item.admin.edit_url" class="btn btn--default btn--small btn--block">View Application</a>
	</div>
</li>