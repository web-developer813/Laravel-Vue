<div class="col-lg-6 masonry-item feed-item">
	<div class="card">
		<div class="card-header white bg-cyan-600 p-30 clearfix">
			<a class="float-left thumbnail mr-20" v-bind:href="item.forprofit.url">
				<img class="img-rounded img-bordered img-bordered-primary" v-bind:src="item.forprofit.profile_photo" v-bind:alt="item.forprofit.name">
			</a>
			<div class="float-left">
				<div class="font-size-20 mb-15"><a v-bind:href="item.forprofit.url" class="grey-50">@{{ item.forprofit.name }}</a></div>
				<p class="mb-5 text-nowrap"><i class="icon md-pin mr-10" aria-hidden="true"></i>
					<span class="text-break">@{{ item.forprofit.full_address }}</span>
				</p>
				<p class="mb-5 text-nowrap"><i class="icon md-ticket-star mr-10" aria-hidden="true"></i>
					<span class="text-break">@{{ item.forprofit.incentives_count + ' ' + pluralize('incentive',parseInt(item.forprofit.incentives_count)) }}</span>
				</p>
			</div>
		</div>
		<div class="card-footer bg-white">
			<div class="row no-space py-20 px-30 text-center">
				<div class="col-6">
					<div class="counter">
						<span class="counter-number cyan-600">102</span>
						<div class="counter-label">Followers</div>
					</div>
				</div>
				<div class="col-6">
					<div class="counter">
						<span class="counter-number cyan-600">125</span>
						<div class="counter-label">Following</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>