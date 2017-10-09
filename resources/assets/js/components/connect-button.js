import Vue from 'vue';
import VueResource from 'vue-resource';
import Ladda from 'ladda';
import helpers from '../mixins/common-helpers.js';
//import modal from 'bootstrap';

Vue.use(VueResource);

// Vue.component('global-notification', {
Vue.component('connect-button', {
	template: '<button :class="btnClass" @click="handleConnect" data-style="zoom-in"><span class="ladda-label"> {{ btnText }}  <i :class="iconClass" aria-hidden="true"></i> </span></button>',

	props: {
		connectUrl: {
			type: String,
			default: 'api/connections'
		},
		connected: null,
		connectedStatus: {
			type: Number,
			default: -1
		},
		targetId: {
			type: Number,
		},
		model: {
			type: String,
			default: 'App\\Volunteer'
		},
		item: null,
		connectText: {
			type: String,
			default: 'Connect'
		},
		pendingText: {
			type: String,
			default: 'Pending'
		},
		disconnectText: {
			type: String,
			default: 'Connected'
		},
		iconConnectClass: {
			type: String,
			default: 'icon md-leak'
		},
		iconPendingClass: {
			type: String,
			default: 'icon md-leak-off'
		},
		iconDisconnectClass: {
			type: String,
			default: 'icon md-leak-off'
		},
		btnConnectClass: {
			type: String,
			default: 'btn btn-warning ladda-button'
		},
		btnPendingClass: {
			type: String,
			default: 'btn btn-info ladda-button'
		},
		btnDisconnectClass: {
			type: String,
			default: 'btn btn-default ladda-button'
		},
		connectConfirmText: {
			type: String,
			default: 'Once connected this user will be able to collaborate with you and reach you over live chat'
		},
		disconnectConfirmText: {
			type: String,
			default: 'You will no longer be able to collaborate or chat with this user.'
		},
		pendingConfirmText: {
			type: String,
			default: 'Are you sure you want to cancel this request to connect?'
		}
	},

	mixins: [helpers],

	data: function() {
		return {
			isConnected: this.connected,
			status: this.connectedStatus,
			target: this.isConnected ? this.connectId : this.object,
			showModal: false,
			messages: [
				{ id: 'knowMsg', title: 'We know each other', message: 'Hey, I thought it would be great to connect with you on Tecdonor since we already know each other. What do you say?', selected: false },
				{ id: 'interestMsg', title: 'We share the same interests', message: 'Hey, I thought it would be great to connect with you on Tecdonor since share the same interests. What do you say?', selected: false },
				{ id: 'volunteerMsg', title: 'Volunteer Together', message: 'Hey, I thought it would be great to connect with you on Tecdonor so we can find opportunities to volunteer together. What do you say?', selected: false },
				{ id: 'locationMsg', title: 'We are in the same neighborhood', message: 'Hey, I thought it would be great to connect with you on Tecdonor since we in the same neighborhood. What do you say?', selected: false },
				{ id: 'otherMsg', title: 'Other', message: 'Add Your Custom Message', selected: false },
			],
			message: '',
		}
	},

	computed: {
		iconClass: function() {
			switch (this.status) {
				case 0:
					return this.iconPendingClass;
					break;
				case 1:
					return this.iconDisconnectClass;
					break;
				default:
					return this.iconConnectClass;
					break;
			}
		},
		btnClass: function() {
			switch (this.status) {
				case 0:
					return this.btnPendingClass;
					break;
				case 1:
					return this.btnDisconnectClass;
					break;
				default:
					return this.btnConnectClass;
					break;
			}
		},
		btnText: function() {
			switch (this.status) {
				case 0:
					return this.pendingText;
					break;
				case 1:
					return this.disconnectText;
					break;
				default:
					return this.connectText;
					break;
			}
		},
	},

	methods: {
		handleConnect: function(event) {
			var btn = this.getButton(event.target);
			switch (this.status) {
				case 1:
					swal({
						title: "Are you sure?",
						text: this.disconnectConfirmText,
						type: "warning",
						showCancelButton: true,
						confirmButtonClass: "btn-warning",
						confirmButtonText: 'Yes, Disconnect',
						closeOnConfirm: true,
						customClass: 'sweet-alert'
					},
					function(isConfirm) {
						if (isConfirm) {
							this.unfriend(btn);
						} else {
							console.log('This was cancelled');
						}
					});
					break;
				case 0:
					swal({
						title: "Are you sure?",
						text: this.pendingConfirmText,
						type: "warning",
						showCancelButton: true,
						confirmButtonClass: "btn-warning",
						confirmButtonText: 'Yes, Disconnect',
						closeOnConfirm: true,
						customClass: 'sweet-alert'
					},
					function(isConfirm) {
						if (isConfirm) {
							this.unfriend(btn);
						} else {
							console.log('This was cancelled');
						}
					});
					break;
				default:
					
					break;
			}
		},
		sendRequest: function(event) {
			$('#connectConfirm').hide();
			$('.modal-backdrop').remove();
			var btn = this.getButton(event.target);
			this.befriend(btn);
		},
		befriend: function(btn) {
			var l = Ladda.create(btn);
			l.start();
			this.$http.post(this.connectUrl,{method: 'befriend', target_id: this.targetId, message: this.message}).then(response => {
				if (response.data.success) {
					if(this.item != null) {
						this.item.connected = response.data.object;
					}
					this.isConnected = response.data.object;
					this.status = response.data.object.status;
					l.stop();
					this.notify('Successfully Sent Connection Request','Success','success');
				} else {
					l.stop();
					this.handleError(response);
				}
			}, response => {
				l.stop();
				this.handleError(response);
			});
		},
		unfriend: function(btn) {
			var l = Ladda.create(btn);
			l.start();
			this.$http.post(this.connectUrl,{method: 'unfriend', target_id: this.targetId}).then(response => {
				if (response.data.success) {
					l.stop();
					this.status = this.object.status;
					this.notify('Successfully disconnected from this user.','Success','success');
				} else {
					l.stop();
					this.handleError(response);
				}

			}, response => {
				l.stop();
				this.handleError(response);
			});
		},
		handleError: function(response,message) {
			console.log(response);
			if (message != null) {
				this.notify(message,'Error','error')
			}
		},
		connectTpl: function() {
			var tpl = '';
			tpl = tpl + '<div class="sa-html">';
			tpl = tpl + '<p>Share why you\'d like to connect with this user.</p>';
			tpl = tpl + '<div class="reasons">';
			for (var i = 0, len = this.messages.length; i < len; i++) {
				tpl = tpl + '<div class="radio-custom radio-primary">';
				tpl = tpl + '<input type="radio" name="messages[]" value="'+ this.messages[i].message +'" v-model="message">';
				tpl = tpl + '<label for="'+ this.messages[i].id +'">'+ this.messages[i].title +'</label>';
				tpl = tpl + '</div>';
			}
			tpl = tpl + '</div>';
			tpl = tpl + '<div class="reasonmessage">';
			tpl = tpl + '<textarea name="message">' + this.message + '</textarea>';
			tpl = tpl + '</div>';
			tpl = tpl + '</div>';
			return tpl;
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