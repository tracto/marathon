var poststylus = function() {
  return require('poststylus')(['autoprefixer', 'cssnano'])
};

module.exports = function(grunt) {

  grunt.initConfig({

    stylus: {
      compile: {
        options: {
          use: [poststylus],
          'include css': true
        },
        files: {
          // 'web/bundles/tdsmarathon/css/styles.css': 'web/bundles/tdsmarathon/css/styles.styl'
        }
      }
    }

  });

  grunt.loadNpmTasks('grunt-contrib-stylus');

};