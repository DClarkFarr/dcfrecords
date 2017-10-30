import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter);

import routes from './routes'

const router = new VueRouter({
  mode: 'history',
  base: __dirname,
  routes: routes,
})

router.beforeEach((to, from, next) => {
  document.title = to.meta.title
  next()
})



const app = new Vue({
  el: '#app',
  router: router,
  data: {
    currentRoute: window.location.pathname,
  },

  created: function(){
    this.$bus = new Vue();

    if(this.$route.name === null){
      this.$router.push({name: 'Home'});
    }
  },
  template: '<div id="#app"><router-view class="view"></router-view></div>',
  methods: {

  },
  computed: {
    view: function(){
      var matchingView = this.$router.match(this.currentRoute);
      var finalView = matchingView.name !== null ? matchingView : this.$router.match({name: 'not-found'});
      return finalView;
    }
  },
  watch: {
    view: function(val){
      this.$router.push( $.extend({}, val) );
    }
  }

});

window.app = app;


