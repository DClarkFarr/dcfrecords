<template>
	<transition name='sidebar-slide'>
		<slot></slot>
	</transition>
</template>

<style>
.sidebar-slide-bg {
	background: rgba(0, 0, 0, 0.5);
	position: absolute;
	top: 0;
	left: 0;
	height: 100%;
	width: 100%;
	opacity: 1;
}
.sidebar-slide-content {
	background: white;
	position: absolute;
	right: 0;
	width: 60%;
	max-width: 450px;
	min-width: 300px;
	height: 100%;
	top: 0;
}

.sidebar-slide-enter-active,
.sidebar-slide-leave-active {
	transition: all linear .3s;
}

.sidebar-slide-enter-active .sidebar-slide-bg, 
.sidebar-slide-leave-active .sidebar-slide-bg {
	transition: all linear .25s;
}
.sidebar-slide-enter-active .sidebar-slide-content, 
.sidebar-slide-leave-active .sidebar-slide-content {
	transition: all ease-in-out .25s;
}

.sidebar-slide-enter .sidebar-slide-bg, 
.sidebar-slide-leave-to .sidebar-slide-bg {
  opacity: 0;
}
.sidebar-slide-enter .sidebar-slide-content, 
.sidebar-slide-leave-to .sidebar-slide-content {
  right: -450px;
}
</style>