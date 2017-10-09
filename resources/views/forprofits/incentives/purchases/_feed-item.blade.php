<li class="feed-item feed-item--has-buttons feed-item--incentive-purchase">
	<div class="item__content">

		<div class="item__wrapper">
			<div class="item__name">@{{ item.volunteer.name }}</div>
			<div class="item__meta">
				<strong class="meta__status status--positive" v-show="item.purchase.status == 'valid'">Coupon Valid</strong>
				<strong class="meta__status status--neutral" v-show="item.purchase.status == 'redeemed'">Coupon redeemed</strong>
				<strong class="meta__status status--negative" v-show="item.purchase.status == 'expired'">Coupon Expired</strong>
				&middot; Purchased on @{{ item.purchase.created_at | datestring }}
			</div>

			<div class="item__description" v-if="item.purchase.coupon_code || item.purchase.expires_at">
				<p class="item__coupon-code" v-if="item.purchase.coupon_code">
					Coupon Code: <strong>@{{ item.purchase.coupon_code }}</strong>
				</p>

				<p class="item__expiration" v-if="item.purchase.expires_at">
					Expires on: <strong>@{{ item.purchase.expires_at | datestring }}</strong>
				</p>
			</div>
		</div>

		<div class="item__buttons">
			<button
				:data-update-url="item.purchase.update_url"
				data-item-type="purchase"
				:data-item-id="item.purchase.id"
				:data-update-data="{ redeemed: true }"
				class="btn btn--small btn--primary"
				@click.prevent="updateItem"
				v-if="item.purchase.status == 'valid'">Mark as Redeemed</button>
			<button
				:data-update-url="item.purchase.update_url"
				data-item-type="purchase"
				:data-item-id="item.purchase.id"
				:data-update-data="{ redeemed: false }"
				class="btn btn--small btn--warning"
				@click.prevent="updateItem"
				v-if="item.purchase.status == 'redeemed'">Undo Redeem</button>
			<a :href="item.purchase.download_url" class="btn btn--small btn--default" target="_blank">Download</a>
		</div>
		
	</div>
</li>