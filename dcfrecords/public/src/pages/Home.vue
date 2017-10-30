<template>
  <main-layout>
  	<div class="list-records">
  		<div class="btn-line v-center">
        <v-link href="/record/edit" class="btn btn-round btn-red">
          <i class='fa fa-plus'></i>
        </v-link>
        <span class="pull-right">
          <button v-show="show == 'completed'" v-on:click="toggleShow('active')" class="btn btn-link btn-success">Show: Competed</button>
          <button v-show="show == 'active'" v-on:click="toggleShow('completed')" class="btn btn-link btn-primary">Show: Active</button>
        </span>
        
      </div>
      <br>
      
      <record-list v-bind:show="show"></record-list>

  	</div>
  	
  </main-layout>
</template>

<script>
  import MainLayout from '../layouts/Main.vue'
  import RecordList from '../components/records/List.vue'
  import VLink from '../components/VLink.vue'

  export default {
    data: function(){
      return {
        show: 'active',
      }
    },
    methods: {
      toggleShow(status){
        this.show = status;
      }
    },
    components: {
      MainLayout,
      RecordList,
      VLink,
    },
  }
</script>
