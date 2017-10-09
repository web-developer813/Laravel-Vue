<li class="feed-item feed-item--incentive">
	{{-- image --}}
	<div class="item__image"
		v-bind:style="{'background-image': 'url(' + item.purchase.image + ')'}"
		v-if="item.purchase.has_image"></div>

	{{-- content --}}
	<div class="item__content">
		<div class="item__name" target="_blank">
			@{{ item.purchase.title }}
		</div>
		
		<div class="item__meta">
			Purchased on @{{ item.purchase.created_at | datestring }}
		</div>
		
		<div class="item__description">
			<p>@{{ item.purchase.summary }}</p>
		</div>
		
		<div class="item__price">
			<strong>@{{ item.purchase.price | number | pluralize 'point' true }}</strong>
		</div>

		<div class="item__status">
			<strong class="status--neutral" v-if="item.purchase.status == 'redeemed'">You have already redeemed this coupon</strong>
			<strong class="status--negative" v-if="item.purchase.status == 'expired'">This coupon has expired</strong>
		</div>

		<div class="item__buttons" v-if="item.purchase.status == 'valid'">
			<a :href="item.purchase.download_url" class="btn btn--primary btn--small btn--block" target="_blank">Download</a>

			<button
				:data-update-url="item.purchase.send_url"
				class="btn btn--small btn--default btn--block"
				@click.prevent="updateItem">Send by Email</button>
		</div>
	</div>
</li>