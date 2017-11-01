<template>
	<main-view v-bind:settings="{topbar: {menu: false}}">
		<div class="text-center">
			<h3>Forgot Password</h3>
			<h1>Request Temporary Password</h1>
		</div>
		<slidedown>
			<div v-show="message.text.length" class='alert' v-bind:class="'alert-' + message.type ">{{message.text}}</div>
		</slidedown>
		<form v-on:submit.prevent="resetPassword" class="resset-password-form">
			<div class="form-group">
				<input class="form-control input-lg" placeholder="Username" v-model="username">
			</div>
			<div class="form-group">
				<input type="email" class="form-control input-lg" placeholder="Account Email Address" v-model="email">
			</div>
			<div class="form-group">
				<button class="btn btn-lg btn-block btn-primary">Request Password Reset</button>
			</div>
		</form>	
		<div class="text-center">
			<ul class="list-unstyled list-inline">
				<li>
					<v-link href='/login'><i class='fa fa-arrow-left'></i> Back to Login</v-link>
				</li>
			</ul>
		</div>
	</main-view>
</template>

<script>
import MainView from '../layouts/Main.vue'
import VLink from '../components/VLink.vue'
import Slidedown from '../components/transitions/Slidedown.vue'

export default {
	data(){
		return {
			username: '',
			email: '',
			message: {text: "", type: ''},
		}
	},
	methods: {
		clearMessage(){
			this.message.text = "";
			this.message.type = '';
		},
		resetPassword(){
			Api.resetPassword(this.username, this.email).then((data) => {
				if(data.status == 'success'){
					this.message = {type: 'success', text: 'Password Email Sent'};
				}else{
					this.message = {type: 'danger', text: data.message};
				}
			});
		},
	},
	components: {
		MainView,
		VLink,
		Slidedown,
	},
	
}
</script>