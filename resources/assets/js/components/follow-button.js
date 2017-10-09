import Vue from 'vue';
import VueResource from 'vue-resource';
import Ladda from 'ladda';
import helpers from '../mixins/common-helpers.js';

Vue.use(VueResource);

// Vue.component('global-notification', {
Vue.component('follow-button', {
	template: '<button :class="btnClass" @click="handleFollow" data-style="zoom-in"><span class="ladda-label"> {{ btnText }}  <i :class="iconClass" aria-hidden="true"></i> </span></button>',

	props: {
		followUrl: {
			type: String,
			default: 'api/follows'
		},
		following: null,
		followId: {
			type: Number,
		},
		followableId: {
			type: Number,
		},
		object: {
			type: Number,
		},
		model: {
			type: String,
			default: 'App\\Volunteer'
		},
		item: null,
		followText: {
			type: String,
			default: 'Follow'
		},
		unfollowText: {
			type: String,
			default: 'Following'
		},
		iconFollowClass: {
			type: String,
			default: 'icon md-account-add'
		},
		iconUnfollowClass: {
			type: String,
			default: 'icon md-check'
		},
		btnFollowClass: {
			type: String,
			default: 'btn btn-success ladda-button'
		},
		btnUnfollowClass: {
			type: String,
			default: 'btn btn-primary ladda-button'
		}
	},

	mixins: [helpers],

	data: function() {
		return {
			isFollowing: this.following,
			target: this.following ? this.followId : this.object,
		}
	},

	computed: {
		iconClass: function() {
			return this.isFollowing ? this.iconUnfollowClass : this.iconFollowClass;
		},
		btnClass: function() {
			return this.isFollowing ? this.btnUnfollowClass : this.btnFollowClass;
		},
		btnText: function() {
			return this.isFollowing ? this.unfollowText : this.followText;
		},
	},

	methods: {
		handleFollow: function(event) {
			var btn = this.getButton(event.target);
			if (this.isFollowing) {
				var l = Ladda.create(btn);
				l.start();
				this.isFollowing = false;
				this.$http.delete(this.followUrl+'/'+this.target).then(response => {
					if (response.data.success) {
						l.stop();
						this.target = this.object;
						this.notify('User successfully unfollowed.','Success','success');
					} else {
						l.stop();
						this.isFollowing = true;
						this.handleError(response);
					}

				}, response => {
					l.stop();
					this.isFollowing = true;
					this.handleError(response);
				});

			} else if (!this.isFollowing) {
				var l = Ladda.create(btn);
				l.start();
				var data = {entity_to_follow: parseInt(this.target), entity_type: this.model};
				this.isFollowing = true;
				this.$http.post(this.followUrl,data).then(response => {
					if (response.data.success) {
						if(this.item != null) {
							this.item.following = response.data.object;
						}
						l.stop();
						this.target = response.data.object.id;
						this.notify('Successfully Followed User','Success','success');
					} else {
						l.stop();
						this.isFollowing = false;
						this.handleError(response);
					}
				}, response => {
					l.stop();
					this.isFollowing = false;
					this.handleError(response);
				});
			}
		},
		handleError: function(response,message) {
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