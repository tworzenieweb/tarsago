'use strict';
module.exports = function(grunt) {

  grunt.initConfig({
    jshint: {
      all: [
        'Gruntfile.js',
        'web/assets/js/app.js'
      ]
    },
    less: {
      dist: {
        files: {
          'web/assets/css/all.min.css': [
            'web/assets/less/app.less',
            'web/assets/vendor/bootstrap-datepicker/build/build3.less'
          ]
        },
        options: {
          compress: true,
          // LESS source map
          // To enable, set sourceMap to true and update sourceMapRootpath based on your install
          sourceMap: false,
          sourceMapFilename: 'web/assets/css/all.min.css.map',
          sourceMapRootpath: 'web/assets/css/'
        }
      }
    },
    uglify: {
      dist: {
        files: {
          'web/assets/js/scripts.min.js': [
            'web/assets/vendor/bootstrap/js/transition.js',
            'web/assets/vendor/bootstrap/js/alert.js',
            'web/assets/vendor/bootstrap/js/button.js',
            'web/assets/vendor/bootstrap/js/carousel.js',
            'web/assets/vendor/bootstrap/js/collapse.js',
            'web/assets/vendor/bootstrap/js/dropdown.js',
            'web/assets/vendor/bootstrap/js/modal.js',
            'web/assets/vendor/bootstrap/js/tooltip.js',
            'web/assets/vendor/bootstrap/js/popover.js',
            'web/assets/vendor/bootstrap/js/scrollspy.js',
            'web/assets/vendor/bootstrap/js/tab.js',
            'web/assets/vendor/bootstrap/js/affix.js',
            'web/assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.js',
            'web/assets/vendor/bootstrap-datepicker/js/locales/bootstrap-datepicker.pl.js',
            'web/assets/js/app.js'
          ]
        },
        options: {
          // JS source map: to enable, uncomment the lines below and update sourceMappingURL based on your install
          // sourceMap: 'assets/js/scripts.min.js.map',
          // sourceMappingURL: '/app/themes/roots/assets/js/scripts.min.js.map'
        }
      }
    },
    
    watch: {
      less: {
        files: [
          'web/assets/less/*.less',
        ],
        tasks: ['less']
      },
      js: {
        files: [
          '<%= jshint.all %>'
        ],
        tasks: ['uglify']
      },
      livereload: {
        // Browser live reloading
        // https://github.com/gruntjs/grunt-contrib-watch#live-reloading
        options: {
          livereload: false
        },
        files: [
          
        ]
      }
    },
    clean: {
      dist: [
        'web/assets/css/all.min.css',
        'web/assets/js/scripts.min.js'
      ]
    }
  });

  // Load tasks
  grunt.loadNpmTasks('grunt-contrib-clean');
  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-contrib-watch');
  grunt.loadNpmTasks('grunt-contrib-less');

  // Register tasks
  grunt.registerTask('default', [
    'clean',
    'less',
    'uglify',
  ]);
  grunt.registerTask('dev', [
    'watch'
  ]);

};
