<template>
	<button v-bind:class="cls" v-bind:disabled="status">
		<slot v-if="!status">
			Save
		</slot>
		<slot v-if="status" name='loading'>
			<i class='fa fa-refresh fa-spin'></i> Loading...
		</slot>
	</button>
</template>

<script>
export default {
	created(){
		if(this.loading){
			this.status = true;
		}
	},
	watch: {
		loading(val){
			this.status = val;
		},
	},
	props: ['loading'],
	data(){
		return {
			status: false,
			cls: {'btn': 1, 'btn-lg': 1, 'btn-block': 1, 'btn-primary': 1},
		}
	},
}

</script>