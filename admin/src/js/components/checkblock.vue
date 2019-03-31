<template>
	<nav class="drop-down-menu">
	    <input 
		  	class ="activate" 
		  	type  ="checkbox" 
		  	:id   ="'accordion-' + key" 
		  	:name ="'accordion-' + key"
		  	@click="arrowSwap"
	  	>
	    <label 
			:for  ="'accordion-' + key" 
			class ="menu-title"
	    >
		  	<div class="nav-left">
		  		<div v-bind:class="checkinfo.status"></div>
		  		<strong>{{ checkinfo.short_desc }}</strong>
		  	</div>
		  	<div class="nav-right">
		  		<button 
						id="fix" 
						class="button" 
						v-show="checkinfo.action" 
						@click="$emit('fix',checkinfo.action)"
				>Fix
				</button>
				<span class="dashicons dashicons-arrow-down" id="arrow"></span>
		  	</div>	
	    </label>
	    <div class="drop-down">
	  		<div class="drop-down-content">
	  			<span><strong>Message: </strong>{{ checkinfo.message }}</span>
	  		</div>
	    </div>
    </nav>
</template>
<script>
export default{
	props:{
		checkinfo:{
			type: Object,
			required: true,
		}
	},
	data(){
		return {
			key: this.$vnode.key,
			currdeg: 0,
		}
	},
	methods: {
		arrowSwap(event){
			this.currdeg = this.currdeg + 180
			const arrow = event.target.nextElementSibling.childNodes[2].childNodes[2]

			arrow.style.transform = `rotateX(${this.currdeg}deg)`			
		}
	}
}
</script>
<style scoped>
/*dropdown container*/
.drop-down-menu {
	display: block;	
	box-shadow: 0 1px 1px rgba(0,0,0,.04);
	margin-bottom: 10px;
	box-sizing: border-box;
}
/*input*/
.activate {
	display: none;
	position: absolute;
	cursor: pointer;
	width: 100%;
	height: 40px;
	margin: 0 0 0 -15px;
	opacity: 0;
}
/*label*/
.menu-title {
	cursor: pointer;	
	display:block;
	padding: 10px;
	height: 50px;
	background: #FAFAFA;
	border: 1px solid #e5e5e5;
	transition: all 0.2s;
	display: flex;
	justify-content: space-between;
	align-items: center;
	box-sizing: border-box;
}
.menu-title:hover {
	border: 1px solid rgb(68, 68, 68);
}
.nav-right, .nav-left{
	display: flex;
	justify-content: center;
	align-items: center;
}
.fail {
	width: 12px;
	height: 12px;
	margin-right: 10px;
	border-radius: 50%;
    background-color: #FF4545;
    box-shadow: #EBE9E9 0 -1px 1px 1px, inset #A62C2C 0 -1px 4px, #898989 0 3px 12px;
}
.pass{
	width: 12px;
	height: 12px;
	margin-right: 10px;
	border-radius: 50%;
    background-color: #33E248;
    box-shadow: #EBE9E9 0 -1px 5px 1px, inset #56A058 0 -1px 4px, #898989 0 3px 12px;
}
#fix{
	margin-right: 10px;
	width: 80px;
	color: #72777c;
}
/*dropdown content*/
.drop-down {
	max-height: 0;
	overflow: hidden;
}
.drop-down-content{
	min-height: 70px;
	padding: 10px 20px 10px 20px;
	display: flex;
	justify-content: left;
	align-items: center;
	background: #fff;
	border: 1px solid #e5e5e5;
	border-top: 0px;
}
/*checked:*/
.activate:checked ~ .drop-down {
	max-height: 200px;
}
/*transitions*/
.drop-down, .menu span, .drop-down-menu, #arrow {
	-webkit-transition: all 0.4s ease;
	-moz-transition: all 0.4s ease;
	-o-transition: all 0.4s ease;
	transition: all 0.4s ease;
}
</style>
