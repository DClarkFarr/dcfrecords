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

Vue.mixin({
  methods: {
    global(){
      return {
        scope: this,
        getUser: function(){
          return this.scope.$root.user || {};
        },
        getPermissions: function(){
          return ['pending', 'user', 'admin'];  //0, 1, 2
        },
        canEdit(levels, id_user){
          return this.hasPermission(levels) || this.isOwner( id_user );
        },
        hasPermission: function(levels){
          var user = this.getUser(), permissions = this.getPermissions();
          if(typeof levels == 'string'){
            levels = [levels];
          }
          if(user !== undefined && user.permission !== undefined){
            var approved = false, userIndex = permissions.indexOf(user.permission), adminIndex = permissions.indexOf('admin');

            for(var l = 0; l < levels.length; l++){
              var levelIndex = permissions.indexOf(levels[l]);
              if( userIndex === levelIndex || userIndex === adminIndex){
                approved = true;
              }
            }

            return approved;
          }

          return false;
        },
        isOwner: function(id_user){
          var user = this.getUser();
          if(user && user.id_user !== undefined){
            return parseInt(user.id_user) === parseInt(id_user);
          }
          return false;
        },
      };
    },
  },
});

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


