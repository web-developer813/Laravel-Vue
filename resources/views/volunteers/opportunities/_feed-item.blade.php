<li class="feed-item feed-item--opportunity">
	{{-- image --}}
	<a :href="item.opportunity.url" class="item__image"
		v-bind:style="{'background-image': 'url(' + item.opportunity.image_url + ')'}"
		v-if="item.opportunity.has_image"></a>

	<div class="item__wrapper clearfix">
		{{-- content --}}
			<a :href="item.opportunity.url" class="item__name">
				@{{ item.opportunity.title }}
			</a>

			<div class="item__meta">
				Posted by <a :href="item.nonprofit.url">@{{ item.nonprofit.name }}</a>
				<div v-if="item.authVolunteer.has_applied">
					<strong class="meta__status status--positive">You have applied</strong>
				</div>
			</div>

			<div class="item__dates" v-bind:class="{ 'dates--expired': item.opportunity.expired}">
				<i class="fa fa-fw fa-calendar" aria-hidden="true"></i>
				<span v-if="item.opportunity.has_dates">
					@{{ item.opportunity.start_date | moment 'ddd MMM Do, YYYY' }}
					<span v-if="item.opportunity.has_multiple_dates">&mdash; @{{ item.opportunity.end_date | moment 'ddd MMM Do, YYYY' }}</span>
					<span v-if="item.opportunity.expired">(Expired)</span>
				</span>
				<span v-if="!item.opportunity.has_dates">It's flexible! We'll work with your schedule.</span>
			</div>

			<div class="item__location">
				<i class="fa fa-fw fa-map-marker" aria-hidden="true"></i>
				<span v-if="item.opportunity.has_location">@{{ item.opportunity.full_address }}</span>
				<span v-if="!item.opportunity.has_location">Can be done remotely</span>
			</div>

			<div class="item__categories" v-if="item.opportunity.categories.length">
				<ul class="categories-list clearfix">
				<li v-for="category in item.opportunity.categories">
					<i class="fa fa-tag" aria-hidden="true"></i>
					@{{ category.name }}
				</li>
			</div>
	</div>
</li>