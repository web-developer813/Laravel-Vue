import Vue from 'vue';
import VueResource from 'vue-resource';
import _ from 'lodash';
import moment from 'moment';
import infiniteScroll from 'vue-infinite-scroll';
import helpers from '../mixins/common-helpers.js';
import select2 from 'select2';
require('../../es/Plugin/select2.js');

Vue.use(infiniteScroll);
Vue.use(VueResource);

Vue.component('connections-selector', {
	template: '<select class="form-control select2-primary" multiple="multiple" style="width:95%"></select>',

	props: ['resource-url'],

 	mixins: [helpers],

	data: function () {
		return {
			items: [],
			members: [],
			meta: {},
			loading: false,
			nextPageUrl: '',
			selected: '',
			value: '',
		}
	},

	created: function () {
		//this.loadItems(true);
	},

	mounted() {
		this.handleSelect(this.$el);
	},

	watch: {
		value: function (value) {
			$(this.$el).val(value).trigger('change');
		},
		items: function (items) {
			$(this.$el).select2({ data: items })
		}
	},

	destroyed: function () {
		$(this.$el).off().select2('destroy');
	},

	methods: {
		loadItems: function (reload) {
			this.loading = true;
			var url = (reload) ? this.resourceUrl : this.nextPageUrl;

			if (reload) this.items = []

			this.$http.post(url,{method: 'getFriends'}).then(function (response) {
				var data = response.data;
				this.items = (reload) ? data.object.data : this.items.concat(data.object.data);
				if (data.meta) { this.meta = data.meta };
				this.nextPageUrl = data.nextPageUrl;
				this.loading = false;
				this.handleSelect();
			}.bind(this));
		},
		loadMore: function () {
			if (this.nextPageUrl) {
				this.loadItems()
			}
		},
		formatUser: function (user) {
			if (user.loading) return user.name;

			var markup = "<div class='media'>" +
			"<div class='pr-20'><a class='avatar' href='javascript:void(0)'><img class='img-fluid' src='" + user.profile_photo + "' alt='" + user.name + "' /></a></div>" +
			"<div class='media-body'>"+
			"<h5 class='mt-0 mb-5'>"+ user.name +"</h5>"+
			"<span class='font-size-10'>"+ user.location +"</span>"+
			"</div>";
			return markup;
		},
		formatUserSelection: function (user) {
			return user.name;
		},
		handleSelect: function (el) {
			this.loading = true;

			let self = this;

			let s2 = $(el).select2({
				ajax: {
					url: this.resourceUrl,
					dataType: 'json',
					method: 'POST',
					delay: 250,
					data: function (params) {
						return {
							method: 'getFriendsWhere',
							q: params.term,
							page: params.page
						};
					},
					processResults: function (data, params) {
						self.loading = false;
						params.page = params.page || 1;
						return {
							results: data.object.data,
							pagination: {
								more: (params.page * 20) < data.meta.total
							}
						};
					},
					cache: true
				},
				placeholder: 'Start typing a users name...',
				escapeMarkup: function (markup) { return markup; },
				minimumInputLength: 1,
				templateResult: this.formatUser,
				templateSelection: this.formatUserSelection
			}).on('select2:select', function (evt) {
				self.adduser(evt.params.data);
			}).on('select2:unselect', function (evt) {
				self.removeuser(evt.params.data);
			});
		},
		adduser: function(user) {
			this.$emit('adduser',user);
		},
		removeuser: function(user) {
			this.$emit('removeuser',user);
		},
		handleError: function (data) {
			console.log(data);
		},
	},
})