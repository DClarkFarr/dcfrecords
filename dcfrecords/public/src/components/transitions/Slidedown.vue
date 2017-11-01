<template>
	<transition
		v-bind:css="false"
		v-on:before-enter="beforeEnter"
		v-on:enter="enter"
		v-on:after-enter="afterEnter"
		v-on:enter-cancelled="enterCancelled"
		v-on:before-leave="beforeLeave"
		v-on:leave="leave"
		v-on:after-leave="afterLeave"
		v-on:leave-cancelled="leaveCancelled">
		<slot></slot>
	</transition>
</template>

<script>
export default {
	methods: {

		beforeEnter: function (el) {

		},
		// the done callback is optional when
		// used in combination with CSS
		enter: function (el, done) {
			var $el = $(el);
			if( ! ( $el.hasClass('enter') || $el.hasClass('leave')) ){
				$el.css('display', 'none');
			}
			if( $el.hasClass('leave')){
				$el.stop().css('height', 'auto');
			}
			$el.addClass('enter').removeClass('leave').slideDown(250, function(){
				$el.removeClass('enter');
				done();
			});
		},
		afterEnter: function (el) {

		},
		enterCancelled: function (el) {

		},

		// --------
		// LEAVING
		// --------
		beforeLeave: function (el) {
		// ...
		},
		// the done callback is optional when
		// used in combination with CSS
		leave: function (el, done) {
			var $el = $(el);
			if($el.hasClass('enter')){
				$el.stop().css('height', 'auto');
			}
			$el.addClass('leave').removeClass('enter').slideUp(250, function(){
				$el.removeClass('leave');
				done();
			});	
		},
		afterLeave: function (el) {
		// ...
		},
		// leaveCancelled only available with v-show
		leaveCancelled: function (el) {

		},
	}//endmethods
}

</script>