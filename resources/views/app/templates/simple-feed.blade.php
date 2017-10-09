<template id="simple-feed-template">
	<div class="feed">
		<ul>
			<template v-for="item in items">
				@{{ item }}
			</template>
		</ul>
		<div class="feed__no-results" v-show="!items.length && !loading">
			@{{ noResults }}
		</div>
		@include('app.components.loading')
		@include('app.components.feeds.load-more')
	</div>
</template>