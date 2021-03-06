module.exports = function(grunt) {

  // Project configuration.
  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),
    copy: {
      main: {
        nonull: true,
        files: [
          // jquery
          {expand: true, cwd: 'bower_components/jquery/dist/', src: ['*'], dest: 'static/js/vendor/jquery/'},
          // videojs
          {expand: true, cwd: 'bower_components/videojs/dist/video-js/', src: ['*.js'], dest: 'static/js/vendor/videojs/'},
          {expand: true, cwd: 'bower_components/videojs/dist/video-js/lang', src: ['*'], dest: 'static/js/vendor/videojs/lang/'},
          {expand: true, cwd: 'bower_components/videojs/dist/video-js/', src: ['*.css'], dest: 'static/css/vendor/videojs/'},
          {expand: true, cwd: 'bower_components/videojs/dist/video-js/', src: ['font/*'], dest: 'static/css/vendor/videojs/'},
          {expand: true, cwd: 'bower_components/videojs/dist/video-js/', src: ['*.swf'], dest: 'static/swf/vendor/videojs/'},
          // blueimp-file-upload
          {expand: true, cwd: 'bower_components/blueimp-file-upload/js/', src: ['**'], dest: 'static/js/vendor/blueimp-file-upload/'},
          
          // jquery easing
          {expand: true, cwd: 'bower_components/jquery.easing/js/', src: ['*.min.js'], dest: 'static/js/vendor/'},
          // js base64 encoder
          {expand: true, cwd: 'bower_components/js-base64/', src: ['*.min.js'], dest: 'static/js/vendor/'},
          // jquery-toggles
          {expand: true, cwd: 'bower_components/jquery-toggles/', src: ['*.min.js'], dest: 'static/js/vendor/'},
          {expand: true, cwd: 'bower_components/jquery-toggles/css/', src: ['*/*'], dest: 'static/css/vendor/jquery-toggles/'},
          {expand: true, cwd: 'bower_components/jquery-toggles/css/', src: ['*'], dest: 'static/css/vendor/jquery-toggles/'},
        ]
      }          
    }
  });

  grunt.loadNpmTasks('grunt-contrib-copy');
  grunt.loadNpmTasks('grunt-contrib-clean');
  
  // Default task(s).
  grunt.registerTask('default', ['copy']);

};