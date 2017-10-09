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
		<br><small>@{{ item.role.label }}</small>
	</div>
	{{-- button dropdown --}}
	<div class="column column--20">
		<a :href="item.volunteer.url" class="btn btn--default btn--small btn--block" v-if="item.role.name == 'forprofit_owner'">View Profile</a>
		<div class="btn-group" v-if="item.role.name != 'forprofit_owner'">
			<a :href="item.volunteer.url" class="btn btn--default btn--small">View Profile</a>
			<button type="button" class="btn btn--default btn--small dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			<span class="caret"></span>
			</button>
			<ul class="dropdown-menu pull-right">
				<li v-if="item.role.name == 'forprofit_employee'">
					<button
						:data-update-url="item.admin.update_url"
						data-item-type="volunteer"
						:data-item-id="item.volunteer.id"
						data-update-data="{ role: 'forprofit_manager' }"
						class="btn btn--primary btn--block btn--small"
						@click.prevent="updateItem">Make Manager</button>
				</li>
				<li v-if="item.role.name == 'forprofit_manager'">
					<button
						:data-update-url="item.admin.update_url"
						data-item-type="volunteer"
						:data-item-id="item.volunteer.id"
						data-update-data="{ role: 'forprofit_employee' }"
						class="btn btn--default btn--block btn--small"
						@click.prevent="updateItem">Remove Manager</button>
				</li>
				<li v-if="item.role.name != 'forprofit_owner'">
					<button
						:data-delete-url="item.admin.delete_url"
						data-item-type="volunteer"
						class="btn btn--danger btn--block btn--small"
						@click.prevent="deleteItem(item.volunteer.id, $event)">Remove Employee</button>
				</li>
			</ul>
		</div>
	</div>
</li>