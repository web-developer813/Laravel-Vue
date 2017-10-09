import Vue from 'vue';
import VueResource from 'vue-resource'

import babelHelpers from '../vendor/babel-external-helpers/babel-external-helpers.js';
global.jQuery = require('../vendor/jquery/jquery.js');
const $ = jQuery;
global.Tether = require('tether');
require('bootstrap');
import Echo from "laravel-echo";
import Pusher from 'pusher-js';
window.Pusher = Pusher;

import animsition from '../vendor/animsition/animsition2.js';
require('../vendor/mousewheel/jquery.mousewheel.js');
require('../vendor/asscrollbar/jquery-asScrollbar.js');
require('../vendor/asscrollable/jquery-asScrollable.js');
require('../vendor/ashoverscroll/jquery-asHoverScroll.js');
require('../vendor/waves/waves.js');
require('../vendor/screenfull/screenfull.js');
require('../vendor/slidepanel/jquery-slidePanel.js');
global.Chartist = require('chartist');
require('../vendor/chartist-plugin-tooltip/chartist-plugin-tooltip.js');
require('../vendor/peity/jquery.peity.js');
require('../vendor/bootstrap-sweetalert/sweetalert.js');
require('../vendor/jquery-placeholder/jquery.placeholder.js');
require('../vendor/formvalidation/formValidation.js');
require('../vendor/formvalidation/framework/bootstrap4.js');
global.toastr = require('../vendor/toastr/toastr.js');
import autosize from 'autosize';

require('../es/State.js');
require('../es/Component.js');
require('../es/Plugin.js');
require('../es/Base.js');
global.Config = require('../es/Config.js');
require('../es/Section/Menubar.js');
require('../es/Section/GridMenu.js');
//require('../es/Section/Sidebar.js');
require('../es/Section/PageAside.js');
require('../es/Plugin/menu.js');

let Site = require('../es/Site.js');

require('../es/Plugin/animsition.js');
require('../es/Plugin/asscrollable.js');
require('../es/Plugin/slidepanel.js');
require('../es/Plugin/switchery.js');
require('../es/Plugin/toastr.js');
require('../es/Plugin/jquery-placeholder.js');
require('../es/Plugin/material.js');

// big components
require('./components/newsfeed.js');
require('./components/opportunities-feed.js');
require('./components/nonprofits-feed.js');
require('./components/forprofits-feed.js');
require('./components/simple-feed.js');
require('./components/hours-feed.js');
require('./components/donations-feed.js');
require('./components/friend-requests.js');
require('./components/create-post.js');
require('./components/connections.js');
require('./components/verify-hours.js');
require('./components/opportunity-editor.js');
require('./components/incentive-editor.js');
require('./components/nonprofit-settings.js');
require('./components/nonprofit-create.js');
require('./components/skill-endorsement.js');
require('./components/skill-opportunity.js');


// small components
require('./components/threads.js');
require('./components/messages.js');
require('./components/message-count.js');
require('./components/connections-online.js');
require('./components/connections-selector.js');
require('./components/map.js');
require('./components/modal.js');
require('./components/follow-button.js');
require('./components/like-button.js');
require('./components/connect-button.js');
require('./components/daterange-picker.js');
require('./components/image-uploader.js');
require('./components/file-uploader.js');
require('./components/stripe-form.js');

// filters
require('./helpers/filters.js');

// mixins
var submitLink = require('./mixins/submit-link.js');
var ajaxLink = require('./mixins/ajax-link.js');
var submitAjaxForm = require('./mixins/ajax-form.js');
var addPostImage = require('./mixins/add-post-image.js');
var ajaxUpload = require('./mixins/ajax-upload.js');

//require('./components/twiliochat.js');

//load Vue-Components
Vue.component('create-post', require('./components/vue/Dropzone.vue'));


Config.set('assets', '../');

// autosize text areas
$(document).ready(function() {
	Site.run();
	autosize($('textarea'));
});

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: env.PUSHER_KEY,
    cluster: env.PUSHER_CLUSTER,
    encrypted: true
});

// vuejs
global.eventHub = new Vue();

global.vm = new Vue({
	el:'#body',
	mixins: [submitLink, submitAjaxForm, ajaxLink],
	data: function(){
		return {
			volunteer: volunteer,
            showSidebar: false,
		}
	},
	filters: {
		timeago: function (date) {
			return moment(date, 'YYYY-MM-DD hh:mm:ss').fromNow(2);
		}
	},
	mounted() {
		this.listen();
		eventHub.$on('ReadMessages', data => {
			console.log('Read Messages emitted...');
			this.total = this.total - 1;
		});
		eventHub.$on('toggleSidebar', data => {
			console.log('toggleSidebar emitted');
			this.showSidebar = !this.showSidebar;
		});
        //this.listenForWhisper();
    },
	methods: {
		mapsApiLoaded: function () {
			eventHub.$emit('MapsApiLoaded')

			// location autocomplete
			if ($('.js-location-autocomplete').length)
			{
				var input = $('.js-location-autocomplete')[0];
				var autocomplete = new google.maps.places.Autocomplete(input);
				google.maps.event.addListener(autocomplete, 'place_changed', function() {
					$(input).trigger('change')
				})
				$(input).keypress(function(e){
					if ( e.which == 13 ) e.preventDefault();
				});
			}
		},
		listen: function() {
            window.Echo.private('user-'+volunteer.id)
            .listen('NewThread', (e) => {
            	//console.log(e);
            });
            window.Echo.channel('status')
                .listen('UserOnline', (e) => {
                    $( '.avatar-'+e.volunteer.id ).each(function() {
                        $(this).removeClass('avatar-off');
                        $(this).addClass('avatar-online');
                    });
                })
                .listen('UserOffline', (e) => {
                    $( '.avatar-'+e.volunteer.id ).each(function() {
                        $(this).removeClass('avatar-online');
                        $(this).addClass('avatar-off');
                    });
                });
        }
	}
});
