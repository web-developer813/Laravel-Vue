<li class="list-item">
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
		<br><small>@{{ item.hours.minutes | min-to-hours }} / @{{{ item.hours.formatted_dates }}}</small>
	</div>
	{{-- button dropdown --}}
	<div class="column column--20">
		<div class="btn-group">
			<a :href="item.volunteer.url" class="btn btn--default btn--small">View Profile</a>
			<button type="button" class="btn btn--default btn--small dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			<span class="caret"></span>
			</button>
			<ul class="dropdown-menu pull-right">
				<li>
					<button
						:data-delete-url="item.hours.delete_url"
						data-item-type="hours"
						class="btn btn--danger btn--small btn--block"
						@click.prevent="deleteItem(item.hours.id, $event)">Delete Hours</button>
				</li>
			</ul>
		</div>
	</div>
</li>