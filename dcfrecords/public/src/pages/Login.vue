<template>
	<main-view v-bind:settings="{topbar: {menu: false}}">
		<div class="text-center">
			<h3>DCF Records</h3>
			<h1>Welcome</h1>
			<slidedown>
				<div v-show="message.errors.length" class='alert alert-danger'>
					<ul class="list-unstyled">
						<li v-for="error in message.errors">- {{error}}</li>
					</ul>
				</div>
				}
			</slidedown>
			<form v-on:submit.prevent="login">
				<div class="form-group">
					<input type="text" class="form-control input-lg" v-model="username" placeholder="Username">
				</div>
				<div class="form-group">
					<input type="password" class="form-control input-lg" v-model="password" placeholder="Password">
				</div>
				<div class="form-group">
					<button class="btn btn-primary btn-lg btn-block">Login</button>
				</div>
			</form>
			<div class="text-center">
				<ul class="list-inline list-unstyled">
					<li><v-link href="/login/forgot" class="btn btn-link"><i class='fa fa-question'></i> Forgot Password</v-link></li>
					<li><v-link href="/login/create" class="btn btn-link"><i class='fa fa-user'></i> Create Account</v-link></li>
				</ul>
			</div>
		</div>
	</main-view>
</template>

<script type="text/javascript">
import MainView from '../layouts/Main.vue'
import Slidedown from '../components/transitions/Slidedown.vue'
import VLink from '../components/VLink.vue'

export default {
	data(){
		return {
			username: '',
			password: '',
			message: {type: '', errors: []},
		};
	},
	created(){
		this.clearMessage();
	},
	methods: {
		clearMessage(){
			this.message = {type: '', errors: []};
		},
		login(){
			this.clearMessage();
			Api.userLogin(this.username, this.password).then((data) => {
				if(data.status == 'success'){
					this.$root.user = data.user;
					Api.user_guid = data.user.user_guid;
					Api.setCookie('user_guid', data.user.user_guid);
					this.$root.redirect('/');
				}else{
					this.message = Object.assign({type: 'danger', errors: data.errors});
				}
			});
		},
	},
	components: {
		MainView,
		Slidedown,
		VLink,
	},
}
</script>