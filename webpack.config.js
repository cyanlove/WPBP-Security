const { VueLoaderPlugin } = require('vue-loader');
const path = require('path');

module.exports = {
  mode: 'development',
  entry: './admin/src/js/wp-security-bp-admin.js',
  output: {
    path: path.resolve(__dirname, 'admin/js'),
    filename: 'wp-security-bp-admin.min.js'
  },
  module:{
  		rules:[
  			{
  				test:/\.js$/,
  				exclude: /node_modules/,
    				use: {
    					loader: 'babel-loader',
              options: {
                presets: ['@babel/preset-env']
              }
    				}
  			},
  			{
  				test:/\.vue$/,
          exclude: /node_modules/,
    				use: {
    					loader: 'vue-loader',
    				}
  			},
        {
          test: /\.css$/,
          use: [
            'vue-style-loader',
            'css-loader'
          ]
        },
  		]
  },
  plugins: [
  	new VueLoaderPlugin()
  ]
};