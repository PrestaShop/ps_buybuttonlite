const path = require('path');

module.exports = {
  chainWebpack: (config) => {
    // Stop generating the HTML page
    config.plugins.delete('html');
    config.plugins.delete('preload');
    config.plugins.delete('prefetch');

    // Allow resolving images in the subfolder src/assets/ 
    config.resolve.alias.set('@', path.resolve(__dirname, 'src'));
  },
  css: {
    extract: true,
  },
  runtimeCompiler: true,
  productionSourceMap: false,
  filenameHashing: false,
  // These rules allow the files to be compiled and stored in the proper folder
  outputDir: '../views/',
  assetsDir: '',  
  publicPath: '../modules/ps_buybuttonlite/views/',
};
