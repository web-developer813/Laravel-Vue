var Vue = require('vue')

Vue.component('google-map', {
	template: '<div class="map"></div>',

	props: ['address', 'lat', 'lng', 'controls'],

	watch: {
		address: function () {
			this.locateAddress()
		}
	},

	events: {
		MapsApiLoaded: function () {
			this.createMap()
		},
		ResizeMaps: function () {
			google.maps.event.trigger(this.map, 'resize')
			this.locateAddress()
		}
	},

	methods: {
		createMap: function () {
			var controls = this.controls ? !this.controls : true
			this.map = new google.maps.Map(this.$el, {
				zoom: 15,
				disableDefaultUI: controls,
				draggable: false,
			    scrollwheel: false,
			    panControl: false,
			    maxZoom: 15,
			    minZoom: 15,
			})

			if (this.address) this.locateAddress()
			else if (this.lat && this.lng) this.locateCoords()
		},
		locateAddress: function () {
			var geocoder = new google.maps.Geocoder();
			var _this = this

			geocoder.geocode({ address: this.address }, function (results, status) {
				if (status === google.maps.GeocoderStatus.OK) {
					_this.setMapCenter(results[0].geometry.location)
				}

				// error
				console.log('could not locate address')
			})
		},
		locateCoords: function () {
			this.setMapCenter({lat: parseFloat(this.lat), lng: parseFloat(this.lng)})
		},
		setMapCenter: function (coords) {
			this.map.setCenter(coords)
			return new google.maps.Marker({
				map: this.map,
				position: coords
			})
		}
	}
})