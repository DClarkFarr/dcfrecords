<template>
	<div class='record-list-item' v-bind:class="status">
		
		<div class='icon'>
			<span v-html="statusIcon"></span>
		</div>
		<div class='content' v-on:click="go">
			<h4>{{record.name}}</h4>
			<p class='text-muted'>
				<span v-html="statusText"></span> 
				<span class='' v-if="lastUpdated">
					<small>
						<user-span v-bind:user="lastEvent.user"></user-span>
						<time-span 
							v-bind:date="lastUpdated"
							v-bind:created="lastCreated"></time-span>
					</small>
				</span>			
			</p>
			<p class='text-muted'>	
				<span class='' v-if="createdAt">
					<small><span style="display: inline-block; padding-left: 18px;">Created</span>
						<user-span v-bind:user="record.user"></user-span>
						<time-span 
							v-bind:date="record.updated_at"
							v-bind:created="record.created_at"></time-span>
					</small>
				</span>
			</p>
		</div>
		<div class='buttons'>
			<v-link v-if="global().canEdit('admin', record.user.id_user)" v-bind:href="editLink" class="btn btn-link btn-default">
				<i class='fa fa-pencil'></i>
			</v-link>
		</div>
		
	</div>
</template>

<script>
import VLink from '../../VLink.vue'
import TimeSpan from '../../TimeSpan.vue'
import UserSpan from '../../UserSpan.vue'

export default {
	props: ['record'],
	computed: {
		statusIcon(){
			var event = this.record.events[0];
			if(event !== undefined){
				return "<span class='"+ Api.statusColor(event.value) +"'><i class='"+ Api.contactIcon(event.contact.type) +"'></i></span>";
			}
		},
		statusText(){
			var event = this.record.events[0];
			if(event !== undefined){
				return "<span class='text-wrap "+ Api.statusColor(event.value) +"'> " + Api.contactStatusText(event.value) + "</span>";
			}
		},
		status() {
			return Api.statusColor(this.record.status);
		},
		editLink(){
			return '/record/edit/' + this.record.id_record;
		},
		profileLink(){
			return '/record/' + this.record.id_record;
		},
		lastEvent(){
			var obj = this.record.events[0];
			obj.user = $.extend({}, obj.user);
			return obj;
		},
		lastUpdated(){
			var event = this.record.events[0];
			if(event !== undefined){
				return event.updated_at;
			}
		},
		lastCreated(){
			var event = this.record.events[0];
			if(event !== undefined){
				return event.created_at;
			}
		},
		createdAt(){
			return Api.timeElapsed(this.record.created_at);
		}
	},
	methods: {	
		go(){
			this.$root.redirect(this.profileLink);
		},
	},
	components: {
		VLink,
		TimeSpan,
		UserSpan,
	}
}
</script>

<style>
	

</style>