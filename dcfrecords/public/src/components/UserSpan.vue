<template>
	<span class='userspan' v-bind:class="{'has-mode' :  mode }" v-on:click.prevent.stop="nextMode">
		<small>
			<span v-if='heading'><i class='fa fa-user'></i></span>
			<span v-if="display == 'username'"> {{user.username}}</span>
			<span v-if="display == 'fullname'"> {{user.first_name}} {{user.last_name}}</span>
		</small>
	</span>
</template>

<script>
export default {
	data(){
		return {
			display: '',
			modes: ['username', 'fullname'],
			heading: true
		}
	},
	created(){
		var mode = this.mode;
		if(!mode){
			mode = this.modes[0];
		}
		this.display = mode;
		
		this.$root.$bus.$on('userspan.toggle', (display) => {
			this.display = display;
		});
	},
	methods: {
		nextMode(){
			var index = this.modes.indexOf(this.display), max = this.modes.length - 1;
			if( index >= max){
				index = 0;
			}else{
				index += 1;
			}

			this.display = this.modes[index];

			this.$root.$bus.$emit('userspan.toggle', this.display);
		},
	},
	props: ['user', 'mode'],
}
</script>

<style>

	.userspan:not(.has-mode) {
		display: inline-block;
		margin: -3px 5px;
		padding: 3px 5px;
		cursor: pointer;
		transition: all ease-in-out 0.3s;
		font-weight: normal;
		background: #f6f6f6;
		border-radius: 3px;	
	}
	.userspan:not(.has-mode):hover {
		background: #efefef;
		color: #333;
	}

	@media(max-width: 767px){
		.userspan {
			font-size: 13px;
		}
		.userspan:not(.has-mode){
			margin: 0;
		}

		.userspan:not(.has-mode) + .userspan:not(.has-mode){
			margin-left: 5px;
		}
	}

</style>