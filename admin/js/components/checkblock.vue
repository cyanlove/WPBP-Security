<template>
	<div id="checkblock">
		<div class="accordion"
		v-bind:class="[ checkinfo.status === 'fail' ? 'failed' : 'passed' ]"  	
		@click="togAccordeon">
		{{ checkinfo.short_desc }} 
			<button id="fix" 
			v-show="checkinfo.status === 'fail'" 
			@click="fix(checkinfo.uri)">
			 
			 FIX
			</button>
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
	methods:{
		fix(e){
			alert(e)
		},
		togAccordeon(event){
			var panel = event.target.nextElementSibling;
			if(event.target.id == 'fix'){
				return
			}
		    if (panel.style.display === "block") {
		      panel.style.display = "none";
		    } else {
		      panel.style.display = "block";
		    }
		}
	}
}
</script>