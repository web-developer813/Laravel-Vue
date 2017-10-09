<li class="feed-item feed-item--opportunity feed-item--has-profile-photo">

	<div class="item__wrapper">
		
		{{-- nonprofit photo --}}
		<a v-bind:href="item.volunteer.url" class="item__profile-photo">
			<div class="profile-photo">
				<img
					v-bind:src="item.volunteer.profile_photo"
					class="profile-photo__img"
					v-bind:alt="item.volunteer.name"
					v-if="item.volunteer.profile_photo">
				<div class="profile-photo__default" v-if="!item.volunteer.profile_photo">
					<span>@{{ item.volunteer.initials }}</span>
				</div>
			</div>
		</a>

		{{-- content --}}
		<div class="item__content">

			<a v-bind:href="item.admin.edit_url" class="item__name">@{{ item.volunteer.name }}</a>

			<div class="item__meta">
				Applied for <strong class="meta__status status--positive">@{{ item.opportunity.title }}</strong> on @{{ item.application.created_at | datestring }}
			</div>
				
			<div class="item__description" vif="item.application.volunteer_message">
				<p>@{{ item.application.volunteer_message }}</p>
			</div>

			<div class="item__description">
				<a v-bind:href="item.admin.edit_url" class="btn btn--default btn--small">Review Application</a>
			</div>
		</div>

	</div>

</li>