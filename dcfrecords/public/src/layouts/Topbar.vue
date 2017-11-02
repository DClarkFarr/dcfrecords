<template>
	<div class="topbar">
    <div v-show="settings.home" class='topbar-link link-left'>
      <v-link href="/"><i class='fa fa-home'></i></v-link>
    </div>

    <div class='topbar-title text-center' v-if="title">{{title}}</div>

    <div v-show="settings.menu" class="topbar-link link-right">
      <a href="javascript:void(0)" v-on:click="showSidebar"><i class='fa fa-bars'></i></a>
    </div>
  </div>
</template>

<script>
  import VLink from '../components/VLink.vue';

  export default {
    data: function(){
      return {
        settings: {
          menu: true,
          home: true,
        },
      };
    },
    created(){
      this.settings = $.extend(this.settings, this.topbar);
    },
    methods: {
      showSidebar(){
        this.$root.$bus.$emit('sidebar.show');
      },
    },
    props: {
      title: {default: 'DCF Records'},
      topbar: {},
    },
    components: {
      VLink,
    }
  }

</script>


<style scoped>
  .topbar {
    background: #3B5998;
    margin-left: -15px;
    margin-right: -15px;
    padding: 5px 15px;
    color: white;
    min-height: 30px;
    position: relative;
  }
  .topbar a {
    color: white;
  }

  .topbar-title {
    font-size: 18px;
    margin: 0 50px;
  }

  .topbar-link {
    position: absolute;
    top: 0;
  }
  .topbar-link.link-right {
    right: 15px;
  }
  .topbar-link.link-left {
    left: 15px;
  }
  .topbar-link a {
    font-size: 20px;
      line-height: 1.7;
  }

  .topbar-page {
    display: inline-block;
  }

  .topbar-title + .topbar-page {
    margin-left: 15px;
  }

  .topbar-title + .topbar-page:before {
    content: "/";
    margin-left: -10px;
    margin-right: 10px;
  }
</style>