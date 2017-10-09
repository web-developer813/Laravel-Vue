import Vue from 'vue'
import VueResource from 'vue-resource'
import _ from 'lodash';
import moment from 'moment';
import infiniteScroll from 'vue-infinite-scroll';
import VueChatScroll from 'vue-chat-scroll';
import helpers from '../mixins/common-helpers.js';

const scrollToBottom = el => {
    el.scrollTop = el.scrollHeight
}

const vChatScroll = {
    bind: (el, binding) => {
        let timeout
        let scrolled = false

        el.addEventListener('scroll', e => {
            if (timeout) window.clearTimeout(timeout)
            timeout = window.setTimeout(function() {
                scrolled = el.scrollTop + el.clientHeight + 1 < el.scrollHeight
            }, 200)
        });

        (new MutationObserver(e => {
            let config = binding.value || {}
            let pause = config.always === false && scrolled
            if (pause || e[e.length - 1].addedNodes.length !== 1) return
            scrollToBottom(el)
        })).observe(el, {childList: true})
    },
    inserted: scrollToBottom
}

Vue.directive('chat-scroll', vChatScroll)

Vue.use(infiniteScroll);
Vue.use(VueResource);

Vue.component('messages', {
	template: '#messages-template',

	props: {
		resourceUrl: {
			type: String,
			required: true
		},
		active: {
			type: Number,
			default: 0
		},
		newThread: {
			type: Boolean,
			default: false
		},
		to: {
			type: Array,
			default: []
		},
		title: {
			type: String,
			default: 'New'
		}
 	},

 	mixins: [helpers],

	data: function () {
		return {
			items: [],
			meta: {},
			loading: true,
			nextPageUrl: '',
			messageText: '',
			isTyping: false,
			typer: '',
			initialTotal: 0,
			clientConnected: false,
		}
	},

	computed: {
		messageHeader: function() {
			let names = [];
			for (var i = this.to.length - 1; i >= 0; i--) {
				names.push(this.to[i].name);
			}
			return 'Conversation: ' + names.join(', ');
		}
	},

	watch: {
		active: function(val, oldVal) {
			if (val) {
				this.newThread = false;
				this.loadItems(true);
				this.listen(val);
				this.stopListen(oldVal);
			}
		},
		newThread: function(val, oldVal) {
			if(val) {
				this.loading = false;
				this.items = [];
				this.active = 0;
				this.new = true;
			}
		},
		messageText: function(val,oldVal){
			if (this.active > 0) {
				let channel = window.Echo.private('thread-' + this.active);
				setTimeout(function() {
					channel.whisper('typing', {
			        	name: volunteer.name
			    	});
				}, 300);
			}
		},
		clientConnected: function(val,oldVal) {
			if (!val) {
				this.loading = true;
			} else if (val) {
				this.loading = false;
			}
		}
	},
	created: function () {
		if (this.active) {
			this.loadItems(true);
		} else {
			this.loading = false;
		}
	},

	methods: {
		loadItems: function (reload) {
			this.loading = true;
			let url = (reload) ? this.resourceUrl + '/' + this.active : this.nextPageUrl;

			if (reload) this.items = []

			this.$http.get(url).then(function (response) {
				let data = response.data;
				this.items = (reload) ? data.items : this.items.concat(data.items);
				if (data.meta) { 
					this.meta = data.meta 
					this.initialTotal = data.meta.total;
				};
				if (data.thread.recipients) {this.to = data.thread.recipients;}
				this.nextPageUrl = data.nextPageUrl;
				this.loading = false;
			}.bind(this));
		},
		addmessage: function() {
		    let error = false;
			if(!this.messageText.length) {
				this.notify('Please add some content first...','Alert','warning');
				error = true;
			}
			if(!this.to.length) {
				this.notify('Please add at least one recipient first...','Alert','warning');
				error = true;
			}
			if (!error) {
			    this.items.push({id: 0, actor: volunteer, body: this.messageText, mine: true, user_id: volunteer.id, created_at: moment().format() });
                this.$emit('addmessage',{body: this.messageText, recipients: this.to});
                this.messageText = '';
            }
		},
		createthread: function() {
			this.$emit('createthread');
		},
		loadMore: function () {
			if (this.nextPageUrl) {
				this.loadItems()
			}
		},
		useradded: function(user) {
			this.to.push(user);
			this.$emit('useradded',user);
		},
		userremoved: function(user) {
			let idx = _.findIndex(this.to, function(o) { return o.id == user.id; });
			this.to.splice(idx,1);
			this.$emit('userremoved',user);
		},
        listen: function(threadId) {
            window.Echo.private('thread-' + threadId)
            .listen('pusher:subscription_error', (e) => {
            	this.clientConnected = false;
            })
            .listen('pusher:subscription_succeeded', () => {
            	this.clientConnected = true;
            })
            .listen('NewMessage', (e) => {
            	if (e.user_id !== volunteer.id && e.thread_id === this.active) {
            		this.items.push(e);
            		let url = 'api/threads/read';
            		this.$http.post(url,{thread_id: e.thread_id, user_id: volunteer.id}).then(function(response){
            			console.log(response);
            		});
            	}
            }).listenForWhisper('typing', (e) => {
        		if (volunteer.name != e.name) {
        			let self = this;
        			this.isTyping = true;
        			this.typer = e.name;
        			setTimeout(function() {
        				self.isTyping = false;
        				self.typer = '';
        			}, 5000);
        		}
    		});
        },
        stopListen: function(threadId) {
        	window.Echo.leave('private-thead-'+threadId);
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