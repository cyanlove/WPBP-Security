new Vue({
	el: document.querySelector('#app'),
	template: `
		<div>
			<div id="container" v-for="item in info">
				<div v-if="item.status == 'fail'" class="accordion fail" @click="togAccordeon">
					{{ item.short_desc }} 
					<button id="fix" value="{{ item.uri }}">
						FIX
					</button>
				</div>
				<div v-else class="accordion passed">
					{{ item.short_desc }}
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
		togAccordeon(event){
			event.target.classList.toggle("active");
		    var panel = event.target.nextElementSibling;
		    if (panel.style.display === "block") {
		      panel.style.display = "none";
		    } else {
		      panel.style.display = "block";
		    }
		}
	}
});
	

