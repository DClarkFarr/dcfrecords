<template>
  <a
    v-bind:href="href"
    v-bind:class="{ active: isActive }"
    v-on:click="go"
  >
    <slot></slot>
  </a>
</template>

<script>
  import routes from '../routes'

  export default {
    props: {
      href: {
        type:String,
        required: true 
      }
    },
    computed: {
      isActive () {
        return this.href === this.$route.path
      }
    },
    methods: {
      go (event) {
        event.preventDefault();
        this.$root.redirect(this.href);
      }
    }
  }
</script>

<style scoped>
  .active {
    /* color: cornflowerblue; */
  }
</style>
