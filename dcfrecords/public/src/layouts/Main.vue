<template>
  <div class="container">
    <slot name='header'>
      <topbar v-bind:topbar="topbar"></topbar>
    </slot>
    <div id="content">
      <slot></slot>
    </div>
    <sidebar></sidebar>
    <slot name='footer'></slot>
  </div>
</template>

<script>
  import Topbar from './Topbar.vue';
  import Sidebar from '../layouts/Sidebar.vue';


  export default {
    data(){
      return {
        topbar: {},
      }
    },
    created(){
      var s = this.settings;
      for(var k in s){
        this[k] = s[k];
      }
    },
    props: ['settings'],
    components: {
      Topbar,
      Sidebar
    }
  }
</script>

<style scoped>
 
</style>
