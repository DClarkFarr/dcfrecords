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
    user: false,
    view: false,
  },

  created: function(){
    this.$bus = new Vue({});

    Api.init(); 
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
        Api.getUser().then((data) => {
          if(data.status == 'success'){
            this.user = data.user;
            if($.isFunction(callback)){
              callback(data);
            }
          }else{
            this.redirect('/login');
          }
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


