<li class="feed-item feed-item--forprofit">
	<a :href="item.forprofit.url" class="item__content">
		<div class="profile-photo">
			<img
				v-bind:src="item.forprofit.profile_photo"
				class="profile-photo__img"
				:alt="item.forprofit.name"
				v-if="item.forprofit.profile_photo">
			<div class="profile-photo__default" v-if="!item.forprofit.profile_photo">
				<span>@{{ item.forprofit.initials }}</span>
			</div>
		</div>
		<div class="item__name">@{{ item.forprofit.name }}</div>
		<div class="item__meta">
			@{{ item.forprofit.monthly_points_remaining }} points to donate this month
		</div>
	</a>
</li>