<template>
	<div id="checkblock">
		<div 
			class="accordion"
			v-bind:class="checkinfo.status"  	
			@click="togAccordeon"
		>	
			{{ checkinfo.short_desc }} 	

			<div id="inputs_desc">
				<button 
					id="fix" 
					v-show="checkinfo.action" 
					@click="$emit('fix',checkinfo.action)"
				>
				 Fix
				</button>
				<div id="show-icon">
					{{ !isOpen ? open : close }}
				</div>
			</div>
		</div>
		<div class="panel">
			<p><strong>Message:</strong> {{ checkinfo.message }}</p>
		</div>
	</div>
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
		return{
			isOpen: false,
			open: '+',
			close: '-'
		}
	},
	methods:{
		togAccordeon(event){	
			var panel = event.target.nextElementSibling;
			if ( event.target.id == 'fix' ){
				return
			}
			if ( event.target.id == 'show-icon' ){
				return
			}
		    if ( panel.style.display === "block" ) {
		      panel.style.display = "none";
		      this.isOpen = !this.isOpen;
		    } else {
		      panel.style.display = "block";
		      this.isOpen = !this.isOpen;
		    }
		}
	}
}
</script> 
