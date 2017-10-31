<template>
	<div class="list-item-wrapper">
		<div class="list-item" v-bind:class="status">
			<div class="content-left">
				<div class='icon-wrapper'>
					<i class="icon" v-bind:class="typeIcon(contact.type)"></i>
				</div>
			</div>
			<div class="content-center pointer" v-on:click="open = !open">
				<h4>{{contact.value}}</h4>
				<p class='text-muted'>
					<small>
						{{contact.label}} 
						<time-span 
							v-bind:date="contact.updated_at"
							v-bind:created="contact.created_at"
						></time-span>
					</small>
				</p>
			</div>
			<div class="content-right">
				<div class="btn-group">
				  <button type="button" class="btn btn-default btn-link dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				    <span class="caret"></span>
				  </button>
				  <ul class="dropdown-menu text-right">
				    <li
				    	v-for="status in statuses" 
				    	v-bind:class="{active: status.value == contact.status}">
				    		<a 
				    			v-on:click.prevent="updateStatus(status.value)"
				    			href="javascript:void(0)">
				    			<i class="fa fa-arrow-right"></i> {{status.name}}
				    		</a>
				    </li>
				    <li role="separator" class="divider"></li>
				    <li><v-link v-bind:href="editUrl"><i class='fa fa-pencil'></i> Edit</v-link></li>
				    <li><a v-on:click="deleteContact" href="javascript:void(0)"><i class='fa fa-times'></i> Delete</a></li>
				  </ul>
				</div>
			</div>
		</div>
		
		<slidedown>
			<div class="list-item-addon" v-show="open && contact.events !== undefined && contact.events.length">
				<div class="item-addon-content">
					<ul class="list-unstyled list-contact-events">
						<li 
							v-for="event in contact.events" 
							 dv-bind:class="statusColor(event.value)">
							<p class='text-muted'>
								<small>
									<time-span 
										v-bind:mode="'datetime'"
										v-bind:date="event.updated_at"
										v-bind:heading='false'></time-span>
								</small>
							</p>
							<p class='text'>{{ statusText(event.value) }}</p>
						</li>
					</ul>
				</div>
			</div>
		</slidedown>
		
	</div>
</template>

<script>
import VLink from '../../VLink.vue'
import TimeSpan from '../../TimeSpan.vue'
import Slidedown from "../../transitions/Slidedown.vue";

export default {
	data(){
		return {
			open: false,
		}
	},
	created(){
	},
	methods: {
		statusColor(status){
			return Api.statusColor(status);
		},
		statusText(status){
			return Api.contactStatusText(status);
		},
		typeIcon(type){
			return Api.contactIcon(type);
		},
		deleteContact(){
			Api.deleteContact(this.contact.id_contact, this.contact.id_record).then((data) => {
				if(data.status == 'success'){
					this.$root.$bus.$emit('contact.deleted', this.contact.id_contact);
				}else{
					alert(data.message);
				}
			});
		},
		updateStatus(status){
			Api.updateContactStatus(this.contact.id_contact, this.contact.id_record, status).then((data) => {
				if(data.status == 'success'){
					this.$root.$bus.$emit('contact.status-updated', this.contact.id_contact);
				}else{
					alert(data.message);
				}
			});
		},
	},
	computed: {
		statuses(){
			return Api.contactStatuses();
		},
		status(){
			return Api.statusColor(this.contact.status);
		},
		editUrl(){
			return 'record/' + this.record.id_record + '/contact/' + this.contact.id_contact
		},
	},
	props: ['contact', 'record'],
	components: {
		VLink,
		TimeSpan,
		Slidedown
	},
};
</script>

<style>
	.list-item-addon .item-addon-content {
		padding: 0 50px 5px;
	}
	.list-contact-events li {
		margin-bottom: 10px;
	}
	.list-contact-events li > p:first-child {
		margin-bottom: 0;
	}
</style>