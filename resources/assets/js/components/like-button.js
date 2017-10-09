import Vue from 'vue';
import VueResource from 'vue-resource';
import Ladda from 'ladda';
import helpers from '../mixins/common-helpers.js';

Vue.use(VueResource);

// Vue.component('global-notification', {
Vue.component('like-button', {
	template: '<button :class="btnClass" @click="handleLike" data-style="zoom-in"><span class="ladda-label"><i :class="iconClass" aria-hidden="true"></i> {{ likedText }}</span></button>',

	props: {
		likeUrl: {
			type: String,
			default: 'api/likes'
		},
		unlikeUrl: {
			type: String,
			default: 'api/likes'
		},
		likedByMe: null,
		likeId: {
			type: Number,
		},
		likableId: {
			type: Number,
		},
		object: {
			type: Number,
		},
		model: {
			type: String,
			default: 'App\\Post'
		},
		item: null,
		likeText: {
			type: String,
			default: this.likedText
		},
		unlikeText: {
			type: String,
			default: 'Unlike'
		},
		iconLikeClass: {
			type: String,
			default: 'icon md-thumb-up'
		},
		iconUnlikeClass: {
			type: String,
			default: 'icon md-thumb-up'
		},
		btnLikeClass: {
			type: String,
			default: 'btn btn-success btn-sm ladda-button'
		},
		btnUnlikeClass: {
			type: String,
			default: 'btn btn-success btn-sm ladda-button'
		}
	},

	mixins: [helpers],

	data: function() {
		return {
			isLikedByMe: this.likedByMe,
			target: this.likedByMe ? this.likeId : this.object,
		}
	},

	computed: {
		iconClass: function() {
			return this.isLikedByMe ? this.iconUnlikeClass : this.iconLikeClass;
		},
		btnClass: function() {
			return this.isLikedByMe ? this.btnLikeClass : this.btnUnlikeClass;
		},
		btnText: function() {
			return this.isLikedByMe ? this.unlikeText : this.likeText;
		},
		likedText: function() {
			var text = this.item.total_likes;
			if (this.isLikedByMe) {
				// I like this
				if (this.item.total_likes > 1) {
					// Me and others like this
					var text = 'Liked by You and ' + (this.item.total_likes - 1) + ' others';
				} else {
					// Only I like this
					var text = 'You Like This';
				}
			}
			return text;
		},
	},

	methods: {
		handleLike: function(event) {
			var btn = this.getButton(event.target);
			if (this.isLikedByMe) {
				var l = Ladda.create(btn);
				l.start();
				this.item.total_likes--;
				this.isLikedByMe = false;
				this.$http.delete(this.unlikeUrl+'/'+this.target).then(response => {
					if (response.data.success) {
						l.stop();
						this.target = this.object;
						this.notify('Item successfully unliked.','Success','success');
					} else {
						l.stop();
						this.item.total_likes++;
						this.isLikedByMe = true;
						this.handleError(response);
					}

				}, response => {
					l.stop();
					this.item.total_likes++;
					this.isLikedByMe = true;
					this.handleError(response);
				});

			} else if (!this.isLikedByMe) {
				var l = Ladda.create(btn);
				l.start();
				this.item.total_likes++;
				var data = {likable_id: parseInt(this.target), likable_type: this.model};
				this.isLikedByMe = true;
				this.$http.post(this.likeUrl,data).then(response => {
					if (response.data.success) {
						if(this.item != null) {
							this.item.liked = response.data.object;
						}
						l.stop();
						this.target = response.data.object.id;
						//this.notify('Successfully like this item','Success','success');
					} else {
						l.stop();
						this.item.total_likes--;
						this.isLikedByMe = false;
						this.handleError(response);
					}
				}, response => {
					l.stop();
					this.item.total_likes--;
					this.isLikedByMe = false;
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