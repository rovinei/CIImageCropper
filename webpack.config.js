var path = require('path');

module.exports = {
  entry: "./src/js/index.js",
  output: {
    path: path.resolve(__dirname, "public/assets/js"),
    filename: "bundle.js"
  }
}

module: {
  loaders: [
    {
      test: /\.jsx$/,
      exclude: /node_modules/,
      loader: 'babel-loader',
      query: {
        presets:[ 'es2015', 'stage-0', 'react' ]
      }
    }
  ]
}