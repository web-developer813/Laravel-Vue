jQuery = require('../../vendor/jquery/jquery.js');
require('bootstrap');
import Switchery from '../../vendor/switchery/switchery.js';
import toastr from '../../vendor/toastr/toastr.js';
import Masonry from 'masonry-layout';

export default {
    methods: {
        initTooltip: function() {
            $('[data-toggle="tooltip"]').tooltip();
        },
        initSwitchery: function() {
            $('[data-plugin="switchery"]').each(function(index){
                var switchery = new Switchery(this);
            });
        },
        initMasonry: function (options) {
            var opts = options ? options : { itemSelector: '.feed-item' };
            if ($('.js-masonry').length) {
                this.$nextTick(function () {
                    this.msnry = new Masonry('.js-masonry', opts);
                });
            }
        },
        notify: function (message,title,type,options) {
            var title = title ? title : '';
            var type = type ? type : 'info';
            var options = options ? options : {
                "positionClass": "toast-top-right",
                "closeButton": true,
                "showDuration": "800",
            };
            toastr[type](message,title,options);
        }
    }
}