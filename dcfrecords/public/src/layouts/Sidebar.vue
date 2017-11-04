<template>
	<sidebar-slide>
		<div v-show="sidebarOpen" class='sidebar'>
			<div class="sidebar-slide">
				<div v-on:click="hideSidebar" class="sidebar-slide-bg"></div>
				<div class='sidebar-slide-content'>
					<div class="sidebar-header">
						<div class='username'>{{user.username}} ({{user.first_name}} {{user.last_name}})</div>
						<div class='email'>{{user.email}}</div>
					</div>
					<div class="sidebar-content">
						<ul class="list-unstyled">
							<li><v-link href='/'><i class='icon fa fa-fw fa-home'></i> Home</v-link></li>
							<li><v-link href='/my-account'><i class='icon fa fa-fw fa-user'></i> My Account</v-link></li>
						</ul>
					</div>
					<div class="sidebar-footer text-center">
						<a v-on:click="logout" class='btn btn-link btn-primary' href="javascript:void(0)"><i class="icon fa fa-fw fa-sign-out"></i> Sign Out</a>
					</div>
				</div>
			</div>
		</div>
	</sidebar-slide>
</template>

<script>
import SidebarSlide from '../components/transitions/SidebarSlide.vue'
import VLink from '../components/VLink.vue'
import UserMixin from '../mixins/User.vue'

export default {
	data(){
		return {
			sidebarOpen: false,
		}
	},
	created(){
		this.$root.$bus.$on('sidebar.show', () => {
			this.sidebarOpen = true;
		});
		this.$root.$bus.$on('sidebar.hide', () => {
			this.sidebarOpen = false;
		});

		this.onUserUpdate();
	},
	mixins: [UserMixin],
	methods: {
		logout(){
			Api.deleteCookie('user_guid');
			this.$root.redirect('/login');
		},
		hideSidebar(){
			this.$root.$bus.$emit('sidebar.hide');
		}
	},
	components: {
		SidebarSlide,
		VLink,
	},
}
</script>

<style>
.sidebar-header {
	background: #3B5998;
	color: white;
	padding: 15px;
	padding-top: 35px;
}
.sidebar-header .username {
	font-weight: bold;
}
.sidebar-content {
	height: 60%;
	height: calc(100% - 145px);
	overflow-x: auto;
}
.sidebar-footer {
	border-top: solid 3px #EDEEEF;
	padding-top: 15px;
}
.sidebar-footer .btn-primary i.fa {
	color: #387EF5;
}
.sidebar a {
	color: #898A8B;
	display: inline-block;
	padding: 15px;
	font-weight: bold;
	font-size: 16px;
	vertical-align: middle;
	line-height: 16px;
}
.sidebar i.icon {
	display: inline-block;
	font-size: 25px;
	vertical-align: middle;
}
.sidebar li i.icon {
	margin-right: 25px;
	line-height: 16px;
}
.sidebar li a {
	width: 100%;
}
.sidebar li a.active {
	color: #333;
	background: #EDEEEF;
}
.sidebar a:hover {
	text-decoration: none;
}
</style>