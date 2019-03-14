<template>
	<div>
		<checkblock 
			v-for="checkinfo in info" 
			:checkinfo="checkinfo" 
			:key="checkinfo.id"
			@fix="fix"
		>
		</checkblock>
	</div>
</template>
<script>
import checkblock from './checkblock.vue';
import axios from 'axios';
export default{
	data() {
		return {
			info: []
		}
	},
	components:{
		checkblock
	},
	mounted() {
		this.getInfo()
	},
	methods:{
		getInfo(){
			//Get all checks responses in json
			var params = new URLSearchParams();
			params.append('action', 'check-all');

			axios.post(ajaxurl, params)
			.then( response => {
				this.info = response.data;
				console.log(this.info)
			})
			.catch( error => {
				console.log(error);
			});
		},
		fix(e){
			//Fix action
			var params = new URLSearchParams();
			params.append('action', e);

			axios.post(ajaxurl, params)
			.then( response => {
				this.info = response.data;
				console.log(this.info)
			})
			.catch( error => {
				console.log(error);
			});
		},
	}
}
</script> 