<template>
	<main-view>
		<h3>My Account</h3>
		<form class="my-account-form" v-on:submit.prevent="saveUser">
			<div class="form-group">
				<label>Username</label>
				<input class="form-control input-lg" placeholder="Username" v-model="user.username" disabled>
			</div>
			<div class="form-group">
				<label>First Name</label>
				<input class="form-control input-lg" placeholder="First Name" v-model="form.first_name">
			</div>
			<div class="form-group">
				<label>Last Name</label>
				<input class="form-control input-lg" placeholder="Last Name" v-model="form.last_name">
			</div>
			<div class="form-group">
				<label>Email</label>
				<input class="form-control input-lg" placeholder="Email Address" v-model="form.email">
			</div>
			<div class="form-group">
				<form-button v-bind:loading="loading">Save Account Info</form-button>
			</div>
		</form>
	</main-view>
</template>

<script>
import MainView from "../layouts/Main.vue"

import UserMixin from '../mixins/User.vue'

import FormButton from '../components/FormButton.vue'

export default {
	data(){
		return {
			loading: false,
			form: {
				first_name: '',
				last_name: '',
				email: '',
			},
		}
	},
	created(){
		this.getUser( (data) => {
			this.user = data.user;
			this.setForm();
		});
	},
	methods: {
		setForm(){
			for(var key in this.form){
				this.form[key] = this.user[key];
			}
		},
		saveUser(){
			this.loading = true;
			Api.userSave(this.form).then((data) => {
				this.loading = false;
				this.setUser(data.user);
				this.setForm();
			}).fail(() => {
				this.loading = false;
			});
		},
	},
	mixins: [UserMixin],
	components: {
		MainView,
		FormButton,
	},
}
</script>