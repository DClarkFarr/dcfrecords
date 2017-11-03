<template>
	<div v-if="!loading" class="record-list">
		
		<record-list-item 
			v-bind:record='record' 
			v-for="record in records" 
			v-bind:key="record.id_record"></record-list-item>	
		
		<div v-if="!records.length">
			<div class="jumbotron text-center">
				<h3>No Records Yet</h3>
				<p>Create one to get started</p>
			</div>
		</div>
	</div>
	<div v-else-if="loading">
		<div class="text-center text-muted">
			<i class="fa fa-refresh fa-spin fa-3x"></i>
			<br>Loading...
		</div>
	</div>
</template>

<script>
	import RecordListItem from './list/Item.vue';

	export default {
		data (){
			return {
				loading: true,
				total: 0,
				offset: 0,
				records: [],
			}
		},
		created (){
			this.getRecords();
		},
		methods: {
			getRecords(){
				Api.getRecords({show: this.show, owned: this.owned ? 1 : 0}).then((data) => {	
					this.total = data.total;
					this.offest = data.offset;
					this.records = data.records;
					this.loading = false;
				});
			}
		},
		watch: {
			show(val){
				this.getRecords();
			},
			owned(){
				this.getRecords();
			},
		},
		props: ['show', 'owned'],
		components: {
			RecordListItem,
		},
	}
</script>

<style>
	.record-list-item {
		display: flex;
		width: 100%;
		padding-top: 15px;
		padding-bottom: 15px;
	}
	.record-list-item {
		border-bottom: solid 1px #BEC7CC;
	}
	.record-list-item:last-child {
		border-bottom: none;
	}
	.record-list-item .icon {
		margin-right: 15px;
		width: 50px;
		display: inline-block;
		align-self: center;
	}
	.record-list-item .buttons {
		margin-left: 15px;
		width: 50px;
		display: inline-block;
		align-self: center;
	}
	.record-list-item .content {
		flex-grow: 1;
		cursor: pointer;
	}
	

</style>