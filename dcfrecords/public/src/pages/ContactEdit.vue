<template>
  <main-layout>
    <div>
    	<v-link v-bind:href="'/record/' + (record.id_record ? record.id_record : $route.params.record)"><i class='fa fa-arrow-left'></i> Back to {{record.name ? record.name : 'Profile'}}</v-link>
    	<form v-on:submit.prevent="saveContact()" action="post" class="contact-form">
    		<h2>{{title}}</h2>
    		<div class="alert" 
			v-show="message.text"
			v-bind:class="{'alert-success': message.type == 'success', 'alert-danger': message.type != 'success'}"
			>{{message.text}}</div>
			<div class="form-group">
				<select v-model="contact.type" class="form-control">
					<option v-for="type in types" v-bind:value="type.value">{{type.name}}</option>
				</select>
			</div>
    		<div class="form-group">
    			<input type="text" class="form-control" v-model="contact.label" placeholder="Label">
    		</div>
    		<div class="form-group">
    			<input type="text" class="form-control" v-model="contact.value" placeholder="Phone #, FB Page, or Address" required>
    		</div>
    		<div class="form-group">
				<select v-model="contact.status" class="form-control">
					<option v-for="status in statuses" v-bind:value="status.value">{{status.name}}</option>
				</select>
			</div>
			<div class="form-group">
				<button class="btn btn-primary btn-lg btn-block">Save Contact</button>
			</div>
    	</form>
    </div>
  </main-layout>
</template>

<script>
  import MainLayout from '../layouts/Main.vue'
  import VLink from '../components/VLink.vue'

  export default {
  	data(){
  		return {
  			record: {
  				contacts: {},
  			},
  			contact: {

  			},
  			types: Api.contactTypes(),
  			statuses: Api.contactStatuses(),
  			message: {},
  		}
  	},
  	computed: {
  		title(){
  			return (this.contact.id_contact !== undefined ? 'Update' : 'Create') + ' Contact';
  		}
  	},
  	
  	created(){
  		if(this.contact.id_contact === undefined){
  			this.contact.type = Api.contactTypes()[0].value;
  			this.contact.status = Api.contactStatuses()[0].value;
  		}
  		this.getRecord();

  		if(this.$route.params.id !== undefined){
  			this.getContact();
  		}
  	},
  	methods: {
  		clearMessage(){
	  		this.message = {};
	  	},
  		saveContact(){
  			this.clearMessage();
  			Api.saveContact(this.$route.params.id, this.$route.params.record, this.contact)
  			.then((data) => {
  				if(data.status == 'success'){
            this.$root.redirect('/record/' + this.record.id_record);
  				}else{
  					this.message = {type: 'danger', text: data.message};
  				}
  			});
  		},
  		getRecord(){
    		if(this.$route.params.record === undefined){
    			return;
    		}
    		Api.getRecord(this.$route.params.record).then((data) => {
    			if(data.status == 'success'){
    				this.record = data.record;
    			}else{
    				this.message = {type: 'failed', text: data.message};
    			}
    		});
    	},
    	getContact(){
    		Api.getContact(this.$route.params.id, this.$route.params.record).then((data) => {
    			if(data.status == 'success'){
    				this.contact = data.contact;
    			}else{
    				this.message = {type: 'failed', text: data.message};
    			}
    		});
    	},
  	},
    components: {
      MainLayout,
      VLink,
    }
  }
</script>
