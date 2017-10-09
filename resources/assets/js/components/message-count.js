import Vue from 'vue';

Vue.component('message-count', {
	template: '{{ count }}',

	props: ['count'],

	data: function () {
		return {
			total: this.count,
		}
	},

	mounted() {
		eventHub.$on('ReadMessages', data => {
			console.log('Read Messages emitted...');
			this.total = this.total - 1;
		});
		eventHub.$on('NewThreadCreated', data => {
			this.total = this.total + 1;
		});
		eventHub.$on('NewMessageCreated', data => {
			this.total = this.total + 1;
		})
	},

	methods: {
		toggleSidebar: function() {
			eventHub.$emit('toggleSidebar');
		}
	}
})
