<li class="feed-item feed-item--centered feed-item--donation">
	<a v-bind:href="item.nonprofit.url" class="item__content clearfix">
		<div class="profile-photo">
			<img
				v-bind:src="item.nonprofit.profile_photo"
				class="profile-photo__img"
				v-bind:alt="item.nonprofit.name"
				v-if="item.nonprofit.profile_photo">
			<div class="profile-photo__default" v-if="!item.nonprofit.profile_photo">
				<span>@{{ item.nonprofit.initials }}</span>
			</div>
		</div>
		
		<div>
			<strong>Donated @{{ item.donation.points }} points to @{{ item.nonprofit.name }}</strong>
			<span class="item__meta">@{{ item.donation.created_at | datestring }}</span>
		</div>
	</a>
</li>