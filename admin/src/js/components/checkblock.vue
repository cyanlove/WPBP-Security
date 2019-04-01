<template>
	<nav class="notice" 
		:class="[checkinfo.status === 'fail' ? 'notice-error'   : '', 
				 checkinfo.status === 'pass' ? 'notice-success' : '',
				 checkinfo.status !== 'pass' && 'fail' ? 'notice-warning' : '']" 
		>
	    <input 
		  	class ="activate" 
		  	type  ="checkbox" 
		  	:id   ="'accordion-' + key" 
		  	:name ="'accordion-' + key"
		  	@click="arrowRotate"
	  	>
	    <label 
			:for  ="'accordion-' + key" 
			class ="menu-title"
	    >
		  	<div class="nav-left">
		  		<div id="short-desc">
		  			<span>{{ checkinfo.short_desc }}</span>
		  		</div>
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
		arrowRotate(event){
			this.currdeg = this.currdeg + 180
			const arrow = event.target.nextElementSibling.childNodes[2].childNodes[2]
			arrow.style.transform = `rotateX(${this.currdeg}deg)`			
		}
	}
}
</script>
