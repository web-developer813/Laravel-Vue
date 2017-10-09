import Vue from 'vue';
import VueResource from 'vue-resource';
import Ladda from 'ladda';
import helpers from '../mixins/common-helpers.js';

Vue.use(VueResource);

Vue.component('create-post', {
	template: '#create-post-template',

	props: ['action','method'],

	mixins: [helpers],

	data: function () {
		return {
			content: '',
			image: '',
			loading: false,
			hasDanger: false,
			hasMessage: false,
			message: '',
		}
	},

	created: function() {
		Vue.http.headers.common['X-CSRF-TOKEN'] = this.csrfToken;
	},

	watch: {
		content: function(val, oldVal) {
			if (this.content.length > 2) {
				this.hasDanger = false;
			}
		}
	},

	methods: {
		onFileChange(e) {

		    let files = e.target.files || e.dataTransfer.files;

		    if (!files.length)

		        return;

		    this.createImage(files[0]);

		},

		createImage(file) {

		    let reader = new FileReader();

		    let vm = this;

		    reader.onload = (e) => {

		        vm.image = e.target.result;

		    };

		    reader.readAsDataURL(file);

		},
		formData: function () {
			console.log($('form'));
			var form = $('form')[1];
			var formData = new FormData(form);
			if ($('.js-file-input').length) {
				var files = $('.js-file-input').prop('files');
				if(files.length)
				{
					formData.append('image', files[0]);
				}
			}
			return formData;
		},
		createItem: function(event) {
			this.loading = true;
			var $el = $(event.target);
			var $btn = $el.closest('button');
			if (this.content.length < 2) {
				this.hasDanger = true;
				this.loading = false;
				this.notify('Please share a little more before posting...','Keep Going','warning');
			}

			var l = Ladda.create($btn[0]);
			l.start();

			var data = {content: this.content, media: this.image};

			this.$http.post(this.action,data).then(response => {
				if (response.data.success) {
					this.notify(response.data.message,'Successfully Posted','success')
					this.content = '';
					this.loading = false;
					l.stop();
				} else {
					this.notify('There was an error creating your post. Please try again','Uh Oh!','error');
					l.stop();
				}

			}, response => {
				console.log(response);
				l.stop();
			});

		},
		updateItem: function (event) {
			var btn = event.target
			var url = btn.getAttribute('data-update-url')

			// disable button
			btn.setAttribute('disabled', 'true');

			// get data
			var data = (btn.getAttribute('data-update-data'))
				? JSON.parse(btn.getAttribute('data-update-data').replace(/'/g, '"').replace(/([a-z][^:]*)(?=\s*:)/gi, '"$1"'))
				: {};

			this.$http.put(url, data).then(function (response) {
				// success message
        		$('#body').append('<global-notification type="success">' + response.data.message + '</global-notification>')
        		vm.$compile(vm.$el)

        		// update item
        		if (_.has(response.data, 'item'))
        		{
					var item_type = btn.getAttribute('data-item-type')
					var item_id = btn.getAttribute('data-item-id')

	        		var elementPos = this.items.map(function(x) {
	        			return x[item_type].id;
	        		}).indexOf(parseInt(item_id));

	        		Vue.set(this.items, elementPos, response.data.item)
        		}

        		// enable button
        		btn.removeAttribute('disabled')
			}, function (response) {
				// error message
        		console.log(response.data.message)
        		// enable button
        		btn.removeAttribute('disabled')
			}.bind(this));
		},
		deleteItem: function (id, event) {
			var btn = event.target
			var url = btn.getAttribute('data-delete-url')

			// disable button
			btn.setAttribute('disabled', 'true');

			this.$http.delete(url).then(function (response) {
				// success message
        		$('#body').append('<global-notification type="success">' + response.data.message + '</global-notification>')
        		vm.$compile(vm.$el)

        		var item_type = btn.getAttribute('data-item-type')

				this.items = this.items.filter(function( entry ) {
					return entry[item_type].id !== id;
				});
			}, function (response) {
				// error message
				console.log(response);
        		// enable button
        		btn.removeAttribute('disabled')
			}.bind(this));
		},
	}
})
