<template>
	<div class="topbar">
    <div class='topbar-link'>
      <v-link href="/"><i class='fa fa-home'></i></v-link>
    </div>

    <div class='topbar-title' v-if="title">{{title}}</div>
    <div class='topbar-page' v-if="page">{{page}}</div>

    
  </div>
</template>

<script>
  import VLink from '../components/VLink.vue';

  export default {
    data: function(){
      return {

      };
    },
    props: {
      title: {default: 'DCF Records'},
      page: {default: ''}
    },
    components: {
      VLink
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
  }
  .topbar a {
    color: white;
  }

  .topbar-title {
    font-size: 18px;
  }

  .topbar-link {
    float: right;
  }
  .topbar-link a {
    font-size: 20px;
  }
  .topbar-title {
  display: inline-block;
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