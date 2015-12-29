var Vue = require('vue');
var VueRouter = require('vue-router');

Vue.use(require('vue-resource'));
Vue.use(VueRouter);

Vue.config.debug = true;

var router = new VueRouter({
  hashbang: false,
  history: true
});

router.map({
  '/:year': {
    name: 'year',
    component: require('./views/breakdown.vue')
  }
});

var App = Vue.extend({

  components: {
    'continent-table': require('./Components/ContinentTable.vue'),
  },

  route: {
    activate: function (transition) {
      console.log(transition)
      this.year = transition.to.year;
    }
  },

  watch: {
    countries: function (countries) {
      window.makeChart(this.countries);
    },

    year: function (val) {
      this.$http.get('/api/continents/' + val)
        .then(function (response) {
          this.countries = response.data;
        });
      this.$route.router.go({ name: 'year', params: { year: val } });
    }
  },

  data: function () {
    return {
      year: 2015,
      countries: {}
    }
  },

  ready: function () {
    this.year = this.$route.params.year;
  }

});

router.start(App, '#app');
