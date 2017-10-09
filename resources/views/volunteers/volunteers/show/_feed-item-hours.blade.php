<li class="feed-item feed-item--opportunity">
	{{-- image --}}
	<div class="item__image"
		v-bind:style="{'background-image': 'url(' + item.opportunity.image_url + ')'}"
		v-if="item.opportunity.has_image"></div>

	{{-- content --}}
	<div class="item__content">
		<div class="item__name">@{{ item.opportunity.title }}</div>

		<div class="item__meta">
			<strong class="meta__status status--positive">Volunteered @{{ item.hours.minutes | min-to-hours }}</strong>
			&middot; <a v-bind:href="item.nonprofit.url">@{{ item.nonprofit.name }}</a>
		</div>

		<div class="item__meta">
			@{{ item.hours.start_date | datestring }}
			<span v-if="item.hours.multiple_dates"> to @{{ item.hours.end_date | datestring }}</span>
		</div>
			
		<div class="item__description" v-if="item.opportunity.excerpt">
			<p v-html="item.opportunity.excerpt"></p>
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