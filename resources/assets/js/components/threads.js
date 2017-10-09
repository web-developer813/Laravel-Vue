import Vue from 'vue'
import VueResource from 'vue-resource'
import _ from 'lodash';
import moment from 'moment';
import infiniteScroll from 'vue-infinite-scroll';
import helpers from '../mixins/common-helpers.js';

Vue.use(infiniteScroll);
Vue.use(VueResource);

Vue.component('threads', {
	template: '#threads-template',

	props: {
		resourceUrl: {
			type: String,
			required: true
		},
 	},

 	mixins: [helpers],

	data: function () {
		return {
			items: [],
			meta: {},
			loading: true,
			nextPageUrl: '',
			active: 0,
			activeIndex: 0,
			newThread: false,
			newMessage: {
				id: 0,
				new: true,
				name: 'New',
				recipients: [],
				unread_messages: 0,
				updated_at: new Date().toISOString(),
			},
			hasNew: false,
		}
	},

	computed: {
		activeThreadRecipients: function() {
			let recipients = [];
			if (this.active > 0) {
				recipients = this.items[this.activeIndex].recipients;
			}
			return recipients;
		},
		activeThreadTitle: function() {
			let title = 'New';
			if (this.active > 0) {
				title = this.items[this.activeIndex].title;
			}
			return title;
		},
		newMessageTitle: function() {
			let recipients = 'New Message';
			if (this.newMessage.recipients.length) {
				let names = [];
				for (var i = this.newMessage.recipients.length - 1; i >= 0; i--) {
					names.push(this.newMessage.recipients[i].name);
				}
				if (names.length > 1) {
					names.reverse();
				}
				recipients = _.truncate(names.join(', '),{length: 25});
			}
			return recipients;
		},
		newMessageExcerpt: function() {
			let excerpt = 'Add some recipients...';
			if (this.newMessageTitle.length && this.newMessageTitle !== 'New Message') {
				excerpt = _.truncate('New Conversation: ' + this.newMessageTitle);
			}
			return excerpt;
		},
		newMessageAvatar: function() {
			let avatars = [{profile_photo: 'img/td-light.png', name: 'new'}];
			if (this.newMessage.recipients.length) {
				avatars = [];
				let length = this.newMessage.recipients.length < 3 ? this.newMessage.recipients.length : 3;
				for (var i = length - 1; i >= 0; i--) {
					avatars.push({profile_photo: this.newMessage.recipients[i].profile_photo, name: this.newMessage.recipients[i].name});
				}
				if (avatars.length > 1) {
					avatars.reverse();
				}
			}
			return avatars;
		}
	},

	created: function () {
		this.loadItems(true);
	},

	mounted() {
		this.listen();
	},

	methods: {
		loadItems: function (reload) {
			this.loading = true;
			this.newThread = false;
			var url = (reload) ? this.resourceUrl : this.nextPageUrl;

			if (reload) this.items = []

			this.$http.get(url).then(function (response) {
				var data = response.data;
				this.items = (reload) ? data.items : this.items.concat(data.items);
				if (data.meta) { this.meta = data.meta };
				this.nextPageUrl = data.nextPageUrl;
				this.loading = false;
			}.bind(this));
		},
		loadMore: function () {
			if (this.nextPageUrl) {
				this.loadItems()
			}
		},
		activate: function(id,index) {
			this.newThread = false;
			if (id === 0) {
				this.newThread = true;
			}
			this.active = id;
			this.activeIndex = index;
			console.log(this.items[index].unread_messages);
			if(this.items[index].unread_messages) {
				eventHub.$emit('ReadMessages',{total: 1});
			}
			this.items[index].unread_messages = 0;
		},
		startThread: function(event) {
			this.newThread = true;
			this.active = 0;
			if (!this.hasNew) {
				this.items.unshift(this.newMessage);
			}
			this.hasNew = true;
		},
		addtothread: function (user) {
			this.newMessage.recipients.push(user);
		},
		removefromthread: function(user) {
			let idx = _.findIndex(this.newMessage.recipients, function(o) { return o.id == user.id; });
			this.newMessage.recipients.splice(idx,1);
		},
		addNewMessage: function(message) {
			let url = 'api/threads';
			//let threadId = this.items[this.active].id;
			let threadId = this.active;
			let ids = [];
			for (let i = message.recipients.length - 1; i >= 0; i--) {
				ids.push(message.recipients[i].id);
			}

			let thread = {id: threadId, recipients: message.recipients, recipientIds: ids, body: message.body, title: this.newMessageTitle, excerpt: this.newMessageExcerpt};

			if (this.newThread) {
				this.$http.post(url,thread).then(function (response) {
					var data = response.data;
					if (data.success) {
						this.items.splice(0,1);
						this.items.unshift(data.object);
						this.hasNew = false;
					} else {
						this.notify('There was an error creating this thread. Please try again.','Error','error');
					}

				}.bind(this));
			} else {
				this.$http.put(url,thread).then(function (response) {
					var data = response.data;
					if (data.success) {
						this.items[this.activeIndex].excerpt = _.truncate(message.body,{length: 25});
					} else {
						this.notify('There was an error updating this thread. Please try again.','Error','error');
					}

				}.bind(this));
			}
		},
		listClasses: function(item) {
			let classes = ['list-group-item'];
			if (this.active === item.id) {
				classes.push('active');
			}
			if (item.id === 0) {
				classes.push('isNew');
			}
			return classes.join(' ');
		},
		avatarClasses: function(item,idx) {
			let classes = ['avatar'];
			if (idx > 0) {
				classes.push('avatar-behind');
				classes.push('avatar-behind-'+idx);
			} else {
				classes.push('avatar-front');
				classes.push('avatar-'+item.id);
			}
			if (idx == 0 && item.status == 1) {
				classes.push('avatar-online');
			} else if (idx == 0 && item.status ==0) {
				classes.push('avatar-off');
			}

			return classes.join(' ');
		},
		listen: function() {
			window.Echo.private('user-'+volunteer.id)
                .listen('NewThread', (e) => {
                    this.items.unshift(e);
                    eventHub.$emit('NewThreadCreated',{total: 1});
                })
                .listen('ThreadUpdate', (e) => {
                    if(e.id !== this.active) {
                    	let idx = _.findIndex(this.items, function(o) { return o.id == e.id; });
                    	if (idx >= 0) {
                    		this.items[idx].unread_messages = 1;
	                    	let object = this.items[idx];
	                    	object.updated_at = e.updated_at;
	                    	this.items.splice(idx,1);
	                    	this.items.unshift(object);
	                    	eventHub.$emit('NewMessageCreated',{total: 1});
                    	}
                    }
                });
		},
		handleError: function (data) {
			console.log(data);
		},
	},
	filters: {
		timeago: function (date) {
			return moment(date).fromNow(true) + ' ago';
		},
		datetomonth: function (date) {
			return moment(date, 'YYYY-MM-DD').format('MMM')
		},
		datetoday: function (date) {
			return moment(date, 'YYYY-MM-DD').format('D')
		},
		datetoyear: function (date) {
			return moment(date, 'YYYY-MM-DD').format('YYYY')
		},
	},
})