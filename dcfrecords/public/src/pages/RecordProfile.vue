<template>
	<main-layout>
		<div class="alert" 
			v-show="message.text"
			v-bind:class="{'alert-success': message.type == 'success', 'alert-danger': message.type != 'success'}"
			>{{message.text}}</div>
	 	<div class="profile-header" v-bind:class="status">
	 		<div class="text-center">
	 			<div class="circle">
	 				<div class="icon"></div>
	 			</div>
	 			<div>
	 				<h4 class='inline relative'><span>{{record.name}}</span> <v-link class='btn btn-link edit-link' v-bind:href="'/record/edit/' + record.id_record"><i class='fa fa-pencil'></i></v-link></h4>
	 			</div>
	 			<p class='text-muted'><small>Lead: {{record.lead}}</small></p>
	 			<p><user-span v-bind:user='record.user'></user-span></p>
	 		</div>
	 	</div>
	 	<div class='profile-tabs' style="margin-top: 25px;">
		  <!-- Nav tabs -->
		  <ul class="nav nav-tabs nav-justified" role="tablist">
		    <li role="presentation" class="active"><a href="#info" aria-controls="info" role="tab" data-toggle="tab">Info</a></li>
		    <li role="presentation"><a href="#history" aria-controls="history" role="tab" data-toggle="tab">History</a></li>
		  </ul>

		  <!-- Tab panes -->
		  <div class="tab-content">
		    <div role="tabpanel" class="tab-pane active" id="info">
		    	
		    	<transition-group class='list-items' name="flip-list" tag="div">
		    		<record-contact-item 
						v-for="contact in record.contacts"					
						v-bind:contact="contact"
						v-bind:record="record"
						v-bind:key="contact.id_contact"
						></record-contact-item>
		    	</transition-group>

				<div class="jumbotron text-center" v-if="!record.contacts.length">
					<h3>No Contact Methods</h3>
					<p>Click below to enter one or more</p>
				</div>
				<br>
				<div class="text-center">
					<v-link v-bind:href="contactUrl()" class="btn btn-round btn-red">
			          <i class='fa fa-plus'></i>
			        </v-link>
				</div>
		    </div>
		    <div role="tabpanel" class="tab-pane" id="history">
		    	<div v-if="record.events && record.events.length">
		    		<div class="card"
		    			v-for="event in record.events"
		    			v-bind:class="statusColor(event.value)">
		    			<div class="card-border-right"></div>
		    			<div class="card-content">
		    				<button
		    					v-on:click="deleteEvent(event)" 
		    					class='pull-right btn btn-link btn-danger'>
		    					<i class="fa fa-times"></i>
		    				</button>
		    				<p class="text-muted">
		    					<small>
		    						<i v-bind:class="typeIcon(event.contact.type)"></i>
		    					</small>
		    					<small>
		    						<time-span
		    							v-bind:date="event.created_at"
		    							v-bind:heading="false"
		    							v-bind:mode="'datetime'"
		    							></time-span>
		    					</small>
		    				</p>
		    				<h4>
		    					<span>{{statusText(event.value)}}</span>
		    				</h4>
		    				<p class="text-muted">{{event.contact.value}}</p></p>
		    			</div>
		    		</div>
		    	</div>
		    	<div v-else>
		    		<div class="jumbotron text-center">
		    			<h3>No Events For Record</h3>
		    			<p>More will appear as actions are taken</p>
		    		</div>
		    	</div>
		    </div>
		  </div>

		</div>
	</main-layout>
</template>

<script>
import MainLayout from '../layouts/Main.vue'
import VLink from '../components/VLink.vue'
import TimeSpan from '../components/TimeSpan.vue'
import UserSpan from '../components/UserSpan.vue'
import RecordContactItem from '../components/records/contact/Item.vue'

export default {
	data(){
    	return {
    		record: {
    			status: 'created',
    			contacts: [],
    			user: {},
    		},
    		message: {},
    	};
    },
    created(){
    	this.getRecord();

    	this.$root.$bus.$on('contact.deleted', (id) => {
    		this.getRecord();
    	});
    	this.$root.$bus.$on('contact.status-updated', (id_contact) => {
    		this.getRecord();
    	});

    	this.$root.$bus.$on('contact.event.deleted', (id_contact) => {
    		this.getRecord();
    	});
    },
	computed: {
		status() {
			return Api.statusColor(this.record.status);
		},
	},
	methods: {
		deleteEvent(event){
			Api.deleteEvent(event.id_record, event.id_event).then((data) => {
				if(data.status == 'success'){
					this.$root.$bus.$emit('contact.event.deleted');
				}else{
					alert(data.message);
				}
			});
		},
		typeIcon(type){
			return Api.contactIcon(type);
		},
		statusColor(status){
			return Api.statusColor(status);
		},
		statusText(status){
			return Api.contactStatusText(status);
		},
		timeAgo(date){
			return Api.timeElapsed(date);
		},
		contactUrl: function(id_contact){
			return 'record/' + this.record.id_record + '/contact' + (id_contact ? '/' + id_contact : '');
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
	},
	components: {
		MainLayout,
		VLink,
		RecordContactItem,
		TimeSpan,
		UserSpan
	},
}
</script>

<style>
	.profile-header h4 {
		max-width: 80%;
	}
	.profile-header .edit-link {
	    position: absolute;
	    right: -40px;
	    top: 3px;
	}
	.tab-content {
		padding: 15px 0;
	}
	.nav-tabs > li > a {
		padding-top: 3px;
		padding-bottom: 3px;
		color: #555;
	}
	.nav-tabs>li.active>a, 
	.nav-tabs>li.active>a:focus, 
	.nav-tabs>li.active>a:hover {
		background: #95989A;
		color: #fff;
	}
	.icon {
		font-size: 24px;
		color: #95989A;
	}
	.icon-wrapper {
		margin-top: -8px;
		margin-bottom: -8px;
		padding-top: 8px;
		padding-bottom: 8px;
	}
	.content-left .icon-wrapper {
		padding-right: 15px;
		border-right: solid 1px;
	}

</style>