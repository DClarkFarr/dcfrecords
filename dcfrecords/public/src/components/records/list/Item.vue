<template>
	<div class='record-list-item' v-bind:class="status">
		
		<div class='icon'>
			<div class='circle'></div>
		</div>
		<div class='content' v-on:click="go">
			<h4>{{record.name}}</h4>
			<p class='text-muted'>
				<span v-html="statusText"></span> 
				<span class='' v-if="lastUpdated"><small> - Updated: {{lastUpdated}}</small></span>			
			</p>
			<p class='text-muted'>	
				<span class='' v-if="createdAt"><small>Created: {{createdAt}}</small></span>
			</p>
		</div>
		<div class='buttons'>
			<v-link v-bind:href="editLink" class="btn btn-link btn-default">
				<i class='fa fa-pencil'></i>
			</v-link>
		</div>
		
	</div>
</template>

<script>
import VLink from '../../VLink.vue'
export default {
	props: ['record'],
	computed: {
		statusText(){
			var event = this.record.events[0];
			if(event !== undefined){
				return "<span class='text-wrap "+ Api.statusColor(event.value) +"'><i class='"+ Api.contactIcon(event.contact.type) +"'></i> " + Api.contactStatusText(event.value) + "</span>";
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
		lastUpdated(){
			var event = this.record.events[0];
			if(event !== undefined){
				return Api.timeElapsed(event.updated_at);
			}
		},
		createdAt(){
			return Api.timeElapsed(this.record.created_at);
		}
	},
	methods: {
		
		go(){
			this.$root.currentRoute = this.profileLink;
			this.$router.push({name: 'RecordProfile', params: {id: this.record.id_record}});
		},
	},
	components: {
		VLink,
	}
}
</script>

<style scoped>
	
</style>