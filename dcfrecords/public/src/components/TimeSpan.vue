<template>
	<span class="timespan" 
		v-bind:class="mode ? 'has-mode mode-' + mode : 'no-mode'"
		v-on:click.prevent.stop="nextState">
		<span v-if="showHeading" class='timespan-heading'><i class="fa fa-calendar"></i></span>
		<span class='timespan-date timeago' v-if="state == 0">{{timeago}}</span>
		<span class='timespan-date datetime' v-else-if="state == 1">{{datetime}}</span>
		<span class='timespan-date timesince' v-else-if="state == 2">{{timesince}}</span>
	</span>
</template>

<script>
export default {
	data(){
		return {
			showHeading: true,
			jsDate: "",
			local_state: '',
			states: ['timeago', 'datetime', 'timesince'],
		};
	},
	created(){
		this.jsDate = Api.dateFromUTC(this.date);

		if(this.$root.$bus.timespan_state === undefined){
			this.$root.$bus.timespan_state = 0;
		}

		if(this.heading !== undefined){
			this.showHeading = this.heading;
		}

		this.local_state = this.$root.$bus.timespan_state;

		if(this.mode !== undefined){
			this.local_state = this.states.indexOf(this.mode);
		}else{

		}
		this.$root.$bus.$on('timespan.state', (state) => {
			if(this.mode === undefined){
				if(state == 2 && !this.created){
					state = 0;
				}
				this.$root.$bus.timespan_state = state;
				this.local_state = state;
			}
		});
	},
	props: ['date', 'created', 'heading', 'mode'],
	computed: {
		state: function(){
			return this.local_state;
		},
		timeago(){
			return Api.timeElapsed(this.date) + " Ago";
		},
		datetime(){
			return Api.datetime(this.date);
		},
		timesince(){
			if(!this.created){
				return "";
			}
			return Api.timeElapsed(this.date) + " Since " + Api.time(this.created) + " " + Api.date(this.created);
		},
	},
	methods: {
		nextState: function(){
			if(this.mode !== undefined){
				return;
			}
			var max = this.states.length - 1;
			var state = this.$root.$bus.timespan_state + 1;
			if(state > max){
				state = 0;
			}
			this.$root.$bus.$emit('timespan.state', state);
		}
	},
}
</script>

<style>
	.timespan:not(.has-mode) {
		display: inline-block;
		margin: -3px 5px;
		padding: 3px 5px;
		cursor: pointer;
		transition: all ease-in-out 0.3s;
		font-weight: normal;
		background: #f6f6f6;
		border-radius: 3px;	
	}
	.timespan:not(.has-mode):hover {
		background: #efefef;
		color: #333;
	}
</style>