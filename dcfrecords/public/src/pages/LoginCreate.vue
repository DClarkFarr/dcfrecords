<template>
	<main-view v-bind:settings="{topbar: {menu: false}}">
		<div class="create-account">
			<slidedown>
				<div v-show="message.errors.length" class='alert alert-danger'>
					<ul class="list-unstyled">
						<li v-for="error in message.errors">- {{error}}</li>
					</ul>
				</div>
				}
			</slidedown>
			<h3>Create Account</h3>
			<div v-show="step == 'tab1'" class="tab1 text-center">
				<h1>Step 1/2</h1>
				<form v-on:submit.prevent="tab1" class="tab1-form">
					<div class="form-group">
						<input type="text" class="form-control input-lg" placeholder="First Name" v-model="fields.first_name">
					</div>
					<div class="form-group">
						<input type="text" class="form-control input-lg" placeholder="Last Name" v-model="fields.last_name">
					</div>
					<div class="form-group">
						<input type="email" class="form-control input-lg" placeholder="Email Address" v-model="fields.email">
					</div>
					<div class="form-group">
						<button class="btn btn-lg btn-block btn-primary">Next</button>
					</div>
				</form>	
				<div class="text-center">
					<ul class="list-unstyled list-inline">
						<li>
							<v-link href='/login'><i class='fa fa-arrow-left'></i> Back to Login</v-link>
						</li>
					</ul>
				</div>
			</div>
			<div v-show="step == 'tab2'" class="tab2">
				<h1>Step 2/2</h1>
				<form v-on:submit.prevent="tab2" class="tab1-form">
					<div class="form-group">
						<div class="form-group has-feedback" v-bind:class="{'has-success': usernameIsValid, 'has-error' : !usernameIsValid}">
						    <input type="text" class="form-control input-lg" placeholder="Username" v-model="fields.username">
						    <span class="glyphicon form-control-feedback" v-bind:class="{'glyphicon-ok' : usernameIsValid, 'glyphicon-remove': !usernameIsValid}"></span>
						</div>
					</div>
					<div class="form-group">
						<input type="password" class="form-control input-lg" placeholder="Password" v-model="fields.password">
					</div>
					<div class="form-group">
						<input type="password" class="form-control input-lg" placeholder="Confirm Password" v-model="fields.confirm_password">
					</div>
					<div class="form-group">
						<button class="btn btn-lg btn-block btn-primary">Create Account</button>
					</div>
				</form>	
				<div class="text-center">
					<ul class="list-unstyled list-inline">
						<li>
							<v-link href='/login'><i class='fa fa-arrow-left'></i> Back to Login</v-link>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</main-view>
</template>

<script>
import MainView from '../layouts/Main.vue'
import Slidedown from '../components/transitions/Slidedown.vue'
import VLink from '../components/VLink.vue'

export default {
	data(){
		return {
			step: 'tab1',
			fields: {
				first_name: '',
				last_name: '',
				email: '',
				username: '',
				password: '',
				confirm_password: '',
			},
			message: {type: 'danger', errors: {}},
			usernameIsValid: false,
		}
	},
	create(){
	},
	watch: {
		'fields.username': function(username){
			this.clearMessage();
			Api.checkUsername(username).then((data) => {
				if(data.status == 'success'){
					this.usernameIsValid = true;
				}else{
					this.usernameIsValid = false;
					this.message.errors.push(data.message);
				}
			});
		},
	},
	methods: {
		clearMessage(){
			this.message = {type: 'danger', errors: []};
		},
		tab1(){
			this.clearMessage();
			if( !(this.fields.first_name && this.fields.last_name) ){
				this.message.errors.push('Please include first and last name');
			}
			if(!this.fields.email){
				this.message.errors.push('Please include an email');
			}

			if(!this.message.errors.length){
				Api.checkUsernameAndEmail(this.fields.first_name, this.fields.last_name, this.fields.email).then( (data) => {
					if(data.status == 'success'){
						this.step = 'tab2';
						this.fields.username = data.username;
					}else{
						this.message.errors = data.errors;
					}
				});
			}
		},
		tab2(){
			this.clearMessage();
			Api.createUser(this.fields).then( (data) => {
				if(data.status == 'success'){
					this.$root.user = data.user;
					Api.user_guid = data.user.user_guid;
					Api.setCookie('user_guid', data.user.user_guid);
					this.$root.redirect('/');
				}else{
					this.message.errors = data.errors;
				}
			});
		},
	},
	components: {
		MainView,
		Slidedown,
		VLink,
	}
}
</script>