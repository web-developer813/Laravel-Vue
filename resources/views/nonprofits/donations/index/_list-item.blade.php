<li class="list-item">
	{{-- status --}}
	<div class="column column--status column--10">
		<span v-if="item.donation.fulfilled" class="status--positive">fulfilled</span>
		<span v-if="!item.donation.fulfilled" class="status--neutral">pending</span>
	</div>
	{{-- image --}}
	<div class="column column--10 column--image column--square">
		{{-- profile photo --}}
		<a :href="item.donater.url" class="profile-photo">
			<img
				v-bind:src="item.donater.profile_photo"
				class="profile-photo__img"
				:alt="item.donater.name"
				v-if="item.donater.profile_photo">
			{{-- default image --}}
			<div class="profile-photo__default" v-if="!item.donater.profile_photo">
				<span>@{{ item.donater.initials }}</span>
			</div>
		</a>
	</div>
	{{-- name --}}
	<div class="column column--grow-1 column--big">
		<strong><a :href="item.donater.url">@{{ item.donater.name }}</a></strong>
	</div>
	{{-- status --}}
	<div class="column column--status column--20">
		@{{ item.donation.points }} points
	</div>
</li>