<li class="feed-item feed-item--opportunity feed-item--has-profile-photo">

	<div class="item__wrapper">
		
		{{-- nonprofit photo --}}
		<a :href="item.volunteer.url" class="item__profile-photo">
			<div class="profile-photo">
				<img
					v-bind:src="item.volunteer.profile_photo"
					class="profile-photo__img"
					:alt="item.volunteer.name"
					v-if="item.volunteer.profile_photo">
				<div class="profile-photo__default" v-if="!item.volunteer.profile_photo">
					<span>@{{ item.volunteer.initials }}</span>
				</div>
			</div>
		</a>

		{{-- content --}}
		<div class="item__content">

			<a :href="item.volunteer.url" class="item__name">@{{ item.volunteer.name }}</a>

			<div class="item__meta">
				<strong class="meta__status status--positive">Volunteered @{{ item.hours.minutes | min-to-hours }}</strong>
				&middot; <a :href="item.nonprofit.url">@{{ item.nonprofit.name }}</a>
			</div>

			<div class="item__meta">
				@{{ item.hours.start_date | datestring }}
				<span v-if="item.hours.multiple_dates"> to @{{ item.hours.end_date | datestring }}</span>
			</div>
				
			<div class="item__description">
				<strong>@{{ item.opportunity.title }}</strong>
				<p v-if="item.opportunity.excerpt">@{{ item.opportunity.excerpt }}</p>
			</div>

			<div class="item__categories" v-if="item.opportunity.categories.length">
				<ul class="categories-list clearfix">
				<li v-for="category in item.opportunity.categories">
					<i class="fa fa-tag" aria-hidden="true"></i>
					@{{ category.name }}
				</li>
			</div>
		</div>

	</div>

</li>