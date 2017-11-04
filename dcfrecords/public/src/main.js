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

import UserMixin from './mixins/User.vue'

const app = new Vue({
  el: '#app',
  router: router,
  data: {
    user: false,
    view: false,
  },
  mixins: [UserMixin],
  created: function(){
    this.$bus = new Vue({});

    Api.init(); 

    this.onUserUpdate();

    this.authenticateUser((data) => {
      if(this.$route.name === null || (this.$route.path !== undefined && this.$route.path.indexOf('/login') > -1) ){
        this.redirect('/');
      }
    });

  },
  template: '<div id="#app"><router-view class="view"></router-view></div>',
  methods: {
    authenticateUser(callback){
      if(Api.user_guid){
        this.getUser( (data) => {
          this.setUser(data.user);
          if($.isFunction(callback)){
            callback(data);
          }
        }, (data) => {
          this.redirect('/login');
        });
      }else{
        this.redirect('/login');
      }
    },
    redirect(href){
      var view = this.getView(href);
      this.$router.push( view );
    },
    getView(href){
      var matchingView = this.$router.match(href);
      if(!this.user){
        var loginView = this.$router.match({name: 'Login'});

        if(loginView != matchingView && matchingView.path.indexOf('/login') < 0){
          matchingView = loginView;
        }
      }

      var finalView = matchingView.name !== null ? matchingView : this.$router.match({name: 'not-found'});
      return $.extend({}, finalView);
    },
  },

});

window.app = app;


