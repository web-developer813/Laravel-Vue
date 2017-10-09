<li class="feed-item feed-item--incentive">
	{{-- image --}}
	<a
		:href="item.incentive.url"
		class="item__image"
		v-bind:style="{'background-image': 'url(' + item.incentive.image + ')'}"
		v-if="item.incentive.has_image"></a>

	{{-- content --}}
	<div class="item__content">
		<a :href="item.incentive.url" class="item__name">
				@{{ item.incentive.title }}
			</a>
			
			<div class="item__meta">
				Added on @{{ item.incentive.created_at | datestring }}
			</div>
			
			<div class="item__description">
				<p>@{{ item.incentive.excerpt }}</p>
			</div>
			
			<div class="item__price">
				<a :href="item.incentive.url" class="btn btn--primary btn--small">
					Buy for <strong>@{{ item.incentive.price }} points</strong>
				</a>
			</div>
	</div>
</li>