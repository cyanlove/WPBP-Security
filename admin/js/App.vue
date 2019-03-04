<template>
	<div>
		<checkblock 
			v-for="checkinfo in info" 
			:checkinfo="checkinfo" 
			:key="checkinfo.id"
		>
		</checkblock>
	</div>
</template>
<script>
import checkblock from './components/checkblock.vue';
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
			var params = new URLSearchParams();
			params.append('action', 'wp-security-bp');

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