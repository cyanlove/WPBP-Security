const { VueLoaderPlugin } = require('vue-loader');
const path = require('path');

module.exports = {
  mode: 'development',
  entry: './admin/js/wp-security-bp-view.js',
  output: {
    path: path.resolve(__dirname, 'dist'),
    filename: 'bundle.js'
  },
  module:{
  		rules:[
  			{
  				test:/\.js$/,
  				exclude: /node_modules/,
  				use: {
  					loader: 'babel-loader'
  				}
  			},
  			{
  				test:/\.vue$/,
          exclude: /node_modules/,
  				use: {
  					loader: 'vue-loader',
  				}
  			}
  		]
  },
  plugins: [
  	new VueLoaderPlugin()
  ]
};