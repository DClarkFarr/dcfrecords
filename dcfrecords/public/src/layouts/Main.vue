<template>
  <div class="container">
    <slot name='header'>
      <topbar></topbar>
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
    components: {
      Topbar
    }
  }
</script>

<style scoped>
 
</style>
