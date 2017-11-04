<script>
export default {
	data(){
		return {
			user: {},
			handle: false,
		}
	},
	methods: {
		onUserUpdate(){
			this.$root.$bus.$on('user.update', (user) => {
				this.user = user;
			});

			if(this.$root.user !== undefined){
				this.user = this.$root.user;
			}
		},
		setUser(user){
			this.user = user;
			
			this.$root.$bus.$emit('user.update', user);
		},
		getUser(success, fail){
			Api.getUser().then((data) => {
	          if(data.status == 'success'){
	            if($.isFunction(success)){
	              success(data);
	            }
	          }else{
	           	if($.isFunction()){
	           		fail(data);
	           	}
	          }
	        });
		},
	},
}
</script>