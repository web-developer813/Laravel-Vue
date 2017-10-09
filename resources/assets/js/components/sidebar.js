import Vue from 'vue';

Vue.component('sidebar', {
  template: '#sidebar-template',

  props: ['resource-url','show-sidebar'],

  data: function () {
    return {
      items: [],
      meta: {},
      loading: true,
      nextPageUrl: '',
      filters: {
        status: '',
      },
      query: '',
      search: '',
    }
  },
})