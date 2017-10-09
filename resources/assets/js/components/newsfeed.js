var Vue = require('vue');
var VueResource = require('vue-resource');
var _ = require('lodash');

Vue.use(VueResource);

Vue.component('newsfeed', {
	template: '#newsfeed-template',

	props: ['resourceUrl', 'categories'],

	data: function () {
		return {
			items: [],
			loading: true,
			nextPageUrl: ''
		}
	},

	created: function () {
		this.loadItems()
	},

	methods: {
		getFilterParams: function () {
			return _.pickBy({
				categories: this.categories
			})
		},
		loadItems: function (submit) {
			this.loading = true

			var reload = (!this.nextPageUrl || submit == true)
			var url = (reload) ? this.resourceUrl : this.nextPageUrl;
			var params = (reload) ? this.getFilterParams() : {};

			this.$http.get(url, {params: params}).then(function (response) {
				var data = response.data
				this.items = (submit) ? data.items : this.items.concat(data.items)
				this.nextPageUrl = data.nextPageUrl
				this.loading = false
			}.bind(this));
		},
		loadMore: function () {
			this.loadItems()
		},
	}
})