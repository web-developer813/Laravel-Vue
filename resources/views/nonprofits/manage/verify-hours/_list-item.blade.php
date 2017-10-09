<li class="list-item">
	{{-- status --}}
	<div class="column column--status column--15" v-show="isSelected(item.volunteer.id)">
		<span class="status--positive">selected</span>
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
		<br><small>@{{ item.admin.minutes_count | min-to-hours }} verified</small>
	</div>
	{{-- select --}}
	<div class="column column--20">
		<button class="btn btn--default btn--small btn--block"
			@click="toggleVolunteer(item.volunteer.id)">
			<span v-show="!isSelected(item.volunteer.id)">Select</span>	
			<span v-show="isSelected(item.volunteer.id)">Deselect</span>	
		</button>
	</div>
</li>