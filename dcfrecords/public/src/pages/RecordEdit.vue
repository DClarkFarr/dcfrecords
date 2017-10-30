<template>
  <main-layout>
    <form class='record-form' v-on:submit.prevent="saveRecord()">
		<h3>{{title}} <v-link v-if="record.id_record" v-bind:href="backUrl" class="btn btn-link"><i class='fa fa-arrow-left'></i> Back</v-link></h3>
		<div class="alert" 
			v-show="message.text"
			v-bind:class="{'alert-success': message.type == 'success', 'alert-danger': message.type != 'success'}"
			>{{message.text}}</div>
		<div class="form-group">
			<input class='form-control input-lg' placeholder='Name' v-model="record.name">
		</div>
		<div class="form-group">
			<textarea class='form-control input-lg' placeholder='Lead/Info' v-model="record.lead"></textarea>
		</div>
		<div class="form-group">
			<select v-model="record.status" class="form-control input-lg">
				<option value='created'>Created</option>
				<option value='pending'>Pending</option>
				<option value='contacted'>Contacted</option>
				<option value='complete'>Complete</option>
			</select>
		</div>
		<div class="form-group">
			<button class="btn btn-primary btn-lg btn-block">Save Record</button>
		</div>
    </form>
  </main-layout>
</template>

<script>
  import MainLayout from '../layouts/Main.vue'
  import VLink from '../components/VLink.vue'

  export default {
    components: {
      MainLayout,
      VLink,
    },
    data(){
    	return {
    		record: {
    			status: 'created',
    		},
    		message: {},
    	};
    },
    created(a){
    	this.clearMessage();
    	this.getRecord();
    },
    methods: {
    	clearMessage(){
    		this.message = Object.assign(this.defaultMessage());
    	},
    	defaultMessage(){
    		return {
    			type: '',
    			text: '',
    		};
    	},
    	getRecord(){
    		if(this.$route.params.id === undefined){
    			return;
    		}
    		Api.getRecord(this.$route.params.id).then((data) => {
    			if(data.status == 'success'){
    				this.record = data.record;
    			}else{
    				this.message = {type: 'failed', text: data.message};
    			}
    		});
    	},
    	saveRecord(){
    		this.clearMessage();

    		Api.saveRecord(this.$route.params.id, this.record).then((data) => {
    			if(data.status == 'success'){
                    this.$root.currentRoute = '/record/' + data.record.id_record;
    				this.$router.push({name: 'RecordProfile', params: {id: data.record.id_record}});
    			}else{
    				this.message = {type: 'failed', text: data.message};
    			}
    		});
    	}
    },
    computed: {
        backUrl(){
            return '/record/' + this.record.id_record; 
        },
    	title(){
    		return this.$route.params.id === undefined ? 'Create Record' : 'Edit Record';
    	}
    },
    
  }
</script>