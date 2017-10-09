<li class="feed-item feed-item--centered feed-item--donation">
	<a :href="item.donation.edit_url" class="item__content clearfix">
		<div class="profile-photo">
			<img
				v-bind:src="item.nonprofit.profile_photo"
				class="profile-photo__img"
				:alt="item.nonprofit.name"
				v-if="item.nonprofit.profile_photo">
			<div class="profile-photo__default" v-if="!item.nonprofit.profile_photo">
				<span>@{{ item.nonprofit.initials }}</span>
			</div>
		</div>
		
		<div>
			<strong>Received donation request from @{{ item.nonprofit.name }} for @{{ item.donation.points }} points</strong>
			<span class="item__meta">
				<strong class="meta__status status--positive" v-if="item.donation.fulfilled">@{{ item.donation.status }}</strong>
				<strong class="meta__status status--neutral" v-if="!item.donation.fulfilled">@{{ item.donation.status }}</strong>
				&middot; @{{ item.donation.created_at | datestring }}
			</span>
		</div>
	</a>
</li>