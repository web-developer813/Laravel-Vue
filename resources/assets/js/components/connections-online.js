import Vue from 'vue'
import VueResource from 'vue-resource'
import _ from 'lodash';
import moment from 'moment';
import infiniteScroll from 'vue-infinite-scroll';
import helpers from '../mixins/common-helpers.js';

Vue.use(infiniteScroll);
Vue.use(VueResource);

Vue.component('connections-online', {
  template: '#connections-online-template',

  props: ['resource-url'],

  mixins: [helpers],

  data: function () {
    return {
      items: [],
      meta: {},
      loading: true,
      conversationLoading: true,
      showConversation: false,
      nextPageUrl: '',
      filters: {
        status: '',
      },
      query: '',
      search: '',
    }
  },
  watch: {
    filters: {
      handler: function (val, oldVal) {
        this.loadItems(true);
      },
      deep: true
    },
    search: _.debounce(function(val) {
        this.query = val;
      }, 250, { 'maxWait': 1000 }
    ),
  },

  computed: {
    filteredItems: function () {
      return this.findBy(this.items, this.query, 'name');
    }
  },

  created: function () {
    // set categories
    if (this.filterCategories) {
      this.filters.categories = this.filterCategories;
    }
    this.loadItems(true);
  },

  methods: {
    loadItems: function (reload) {
      this.loading = true;
      var url = (reload) ? this.resourceUrl : this.nextPageUrl;
      var params = this.filterParams(reload);

      if (reload) this.items = []

      this.$http.post(url, {method: 'getOnlineFriends',params: params}).then(function (response) {
        var data = response.data;
        this.items = (reload) ? data.object.data : this.items.concat(data.object.data);
        if (data.meta) { this.meta = data.meta };
        this.nextPageUrl = data.nextPageUrl;
        this.loading = false;
        this.listen();
      }.bind(this));
    },
    loadMore: function () {
      if (this.nextPageUrl) {
        this.loadItems()
      }
    },
    getConversation: function(id) {
      this.conversationLoading = true;
      this.showConversation = true;

      let participants = [];
      participants.push(id);
      participants.push(volunteer.id);
      participants.sort();
      let subject = participants.join('::');
      console.log(subject);
      // this.$http.post().then(function (response) {
      //   this.conversationLoading = false;
      // });
    },
    handleError: function (data) {
      console.log(data);
    },
    findBy: function (list, value, column) {
      return list.filter(function (item) {
        return item[column].toLowerCase().includes(value.toLowerCase());
      })
    },
    filterParams: function (reload) {
      if (reload)
      {
        var filters = _.extend({ 'feed-item': this.feedItem }, this.filters);
        // var filters = {
        //  'feed-item': this.feedItem,
        //  search: this.filters.search,
        //  status: this.filters.status,
        //  categories: this.filters.categories
        // }
        return _.omitBy(filters, function(value) {
          return _.isUndefined(value) || _.isNull(value) || value === '';
        });
      }

      return _.omitBy({
        'feed-item': this.feedItem
      }, _.isNil)
    },
    toggleValue: function (option) {
      this.optionToggles[option] = !this.optionToggles[option]
    },
    listen: function() {
      window.Echo.channel('status')
        .listen('UserOnline', (e) => {
          if (e.volunteer.id !== volunteer.id) {
            this.items.push(e.volunteer);
          }
        })
        .listen('UserOffline', (e) => {
            let idx = _.findIndex(this.items, function(o) { return o.id == e.volunteer.id; });
            if (idx >= 0) {
              this.items.splice(idx,1);
            }
        });
    },
    delayedToggle: function() {

    },
  },
})