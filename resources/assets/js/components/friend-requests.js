import Vue from 'vue';
import VueResource from 'vue-resource';
import Ladda from 'ladda';
import helpers from '../mixins/common-helpers.js';

Vue.use(VueResource);

// Vue.component('global-notification', {
Vue.component('friend-requests', {
	template: '<span>Sender ID: {{ sender_id }} || Sender Type: {{ sender_type }} || Recipient ID: {{ recipient_id }} || Recipient Type: {{ recipient_type }}</span><button @click="handleRequest(true)">Accept</button><button @click="handleRequest(false)">Deny</button><button @click="handleBlock">Block</button>',

	props: {
		resourceUrl: {
			type: String,
			default: 'api/connections/list'
		},
		actionUrl: {
			type: String,
			default: 'api/connections'
		},
	},

	mixins: [helpers],

	data: function() {
		return {
			items: [],
			meta: {},
			loading: true,
			nextPageUrl: '',
		}
	},

	created: function () {
		this.loadItems(true);
	},

	computed: {
		iconClass: function() {
			return 'test';
		},
	},

	methods: {
		loadItems: function(reload) {
			this.loading = true;
			var url = (reload) ? this.resourceUrl : this.nextPageUrl;

			if (reload) this.items = []

			this.$http.post(url, {method: 'getFriendRequests'}).then(function (response) {
				var data = response.data;
				this.items = (reload) ? data.object : this.items.concat(data.object);
				if (data.meta) { this.meta = data.meta };
				this.nextPageUrl = data.nextPageUrl;
				this.loading = false;
			}.bind(this));
		},
		showMessage: function(index) {
			swal({
				title: this.items[index].firstname,
				text: this.items[index].location,
				showCancelButton: true,
				confirmButtonClass: "btn-info",
				confirmButtonText: 'Accept',
				closeOnConfirm: true,
				customClass: 'sweet-alert'
			},
			function(isConfirm) {
				if (isConfirm) {
					console.log('this was confirmed');
				} else {
					console.log('This was cancelled');
				}
			});
		},
		handleRequest: function(accept,item) {
			if (accept) {
				item.accepted = true;
				this.meta.total--;
				this.$http.post(this.actionUrl,{method: 'acceptFriendRequest', target_id: item.id}).then(response => {
					if (response.data.success) {
						this.notify('Successfully Connected','Info','info');
					} else {
						item.accepted = false;
						this.meta.total++;
						this.handleError(response);
					}
				}, response => {
					item.accepted = false;
					this.meta.total++;
					this.handleError(response);
				});
			} else {
				item.denied = true;
				this.meta.total--;
				this.$http.post(this.actionUrl,{method: 'denyFriendRequest', target_id: item.id}).then(response => {
					if (response.data.success) {
						this.notify('Connection Denied','Info','info');
					} else {
						item.denied = false;
						this.meta.total++;
						this.handleError(response);
					}
				}, response => {
					item.denied = false;
					this.meta.total++;
					this.handleError(response);
				});
			}
		},
		handleBlock: function() {
			console.log('Friend Blocked');
		},
		handleError: function(response) {
			console.log(response);
			if (message != null) {
				this.notify(message,'Error','error')
			}
		},
		getButton: function(target) {
			if (target.nodeName == 'I') {
				var btn = target.parentNode.parentNode;
			} else if (target.nodeName == 'SPAN') {
				var btn = target.parentNode;
			} else if (target.nodeName == 'BUTTON') {
				var btn = target;
			}
			return btn;
		},
	}
})