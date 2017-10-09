<li class="list-item">
	{{-- image --}}
	<div class="column column--5 column--image column--square">
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
		<br><small>@{{ item.admin.email }}</small>
	</div>
	{{-- plan --}}
	<div class="column column--20">
		@{{ item.admin.plan_id }}
	</div>
	{{-- view profile --}}
	<div class="column column--15">
		<div class="btn-group">
			<a :href="item.volunteer.url" class="btn btn--default btn--small">View Profile</a>
			<button type="button" class="btn btn--default btn--small dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			<span class="caret"></span>
			</button>
			<ul class="dropdown-menu pull-right">
				<li>
					<button
						:data-update-url="item.admin.upgrade_to_free_url"
						data-item-type="volunteer"
						:data-item-id="item.volunteer.id"
						data-update-data="{ plan: 'free' }"
						class="btn btn--default btn--block btn--small"
						@click.prevent="updateItem">Upgrade to Free Plan</button>
				</li>
			</ul>
		</div>
	</div>
</li>