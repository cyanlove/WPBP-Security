new Vue({
	el: document.querySelector('#app'),
	template: `
		<div>
			<div id="container" v-for="item in info">
				<div v-bind:class="[ item.status === 'fail' ? 'failed' : 'passed' ]"  class="accordion" @click="togAccordeon">
					{{ item.short_desc }} 
					<button id="fix" v-show="item.status === 'fail'" @click="fix(item.uri)">
						FIX
					</button>
				</div>
				<div class="panel">
					<div class="panel-content">
						<p><strong>Message:</strong> {{ item.message }}</p>
					</div>
				</div>
			</div>
		</div>
	`,
	data(){
		return{
			info: []
		}
	},
	mounted(){
		this.getInfo();
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
		fix(e){
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
});
	

