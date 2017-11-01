<template>
  <div class="container">
    <slot name='header'>
      <topbar v-bind:topbar="topbar"></topbar>
    </slot>
    <div id="content">
      <slot></slot>
    </div>
    <slot name='footer'></slot>
  </div>
</template>

<script>
  import Topbar from './Topbar.vue';

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
      Topbar
    }
  }
</script>

<style scoped>
 
</style>
