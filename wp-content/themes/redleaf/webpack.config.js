const BrowserSyncPlugin = require('browser-sync-webpack-plugin');
const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const CssMinimizerPlugin = require('css-minimizer-webpack-plugin');
const AssetsPlugin = require('assets-webpack-plugin');
const TerserPlugin = require("terser-webpack-plugin");
const DEV = process.env.NODE_ENV === 'development';
const { CleanWebpackPlugin } = require('clean-webpack-plugin')
const pathsToClean = ['dist'];
const cleanOptions = { root: __dirname, verbose: true, dry: false, exclude: [] };

// Remove old hashed files
// Source Maps
module.exports = {
  entry: {
    main: "./src/index.js",
    virtualtour: "./js/virtual-tour.js",
  },
  output: {
    path: path.resolve(__dirname, 'dist'),
    filename: DEV ? '[name].js' : '[name]-[hash:6].js',
    publicPath: '/dist/'
  },
  module: {
    rules: [
      {
        test: /\.js$/,
        exclude: /node_modules/,
        use: {
          loader: "babel-loader",
          options: { sourceMap: DEV ? true : false }
        }
      },
      {
        test: /\.(png|jpg|eot|woff|ttf|svg|woff2|otf)$/,
        loader: 'url-loader'
      },
      {
        test: /\.css$/,
        use: [
          MiniCssExtractPlugin.loader,
				  { loader: 'css-loader', options: { url: false, sourceMap: DEV ? true : false } }
        ]
      },
      {
        test: /\.scss$/,
        use: [
          // only use the mode of development
          MiniCssExtractPlugin.loader,
          { loader: 'css-loader', options: { url: false, sourceMap: DEV ? true : false } },
          { loader: 'postcss-loader' },
  				{ loader: 'sass-loader', options: { sourceMap: DEV ? true : false } }
        ]
      }
    ]
  },
  optimization: {
    minimize: DEV ? false : true,
    minimizer: [
      // For webpack@5 you can use the `...` syntax to extend existing minimizers (i.e. `terser-webpack-plugin`), uncomment the next line
      // `...`
      new CssMinimizerPlugin(),
      new TerserPlugin(),
    ],
  },
  devtool: DEV ? "source-map" : false,
  plugins: [
    new CleanWebpackPlugin(cleanOptions),
    new AssetsPlugin({
      path: path.resolve(__dirname, 'dist'),
      filename: 'assets.json',
    }),
    new MiniCssExtractPlugin({ filename: DEV ? 'style.css' : 'style-[hash:6].css' }),
    new BrowserSyncPlugin(
      // BrowserSync options
      {
        // browse to http://localhost:3000/ during development
        host: 'localhost',
        port: 3000,
        // proxy the Webpack Dev Server endpoint
        // (which should be serving on http://localhost:3100/)
        // through BrowserSync
        proxy: 'http://localhost:8888/clearmarble/redleaf',
        files: [
          '**/*.php',
          '*.php',
          '**/*.css',
          '**/*.js',
          './dist/main.js'
        ]
      },
      // plugin options
      {
        // prevent BrowserSync from reloading the page
        // and let Webpack Dev Server take care of this
        reload: false
      }
    )
  ]
}
