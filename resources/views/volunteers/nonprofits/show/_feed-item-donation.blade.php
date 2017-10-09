<li class="feed-item feed-item--centered feed-item--donation">
	<a :href="item.donater.url" class="item__content clearfix">
		<div class="profile-photo">
			<img
				v-bind:src="item.donater.profile_photo"
				class="profile-photo__img"
				:alt="item.donater.name"
				v-if="item.donater.profile_photo">
			<div class="profile-photo__default" v-if="!item.donater.profile_photo">
				<span>@{{ item.donater.initials }}</span>
			</div>
		</div>
		<div>
			<strong>Received @{{ item.donation.points }} points from @{{ item.donater.name }}</strong>
			<span class="item__meta">@{{ item.donation.created_at | datestring }}</span>
		</div>
	</a>
</li>