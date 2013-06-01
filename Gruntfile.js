'use strict';

/**
 * Think about:
 *      - livereload,
 *      - compass / sass and
 *      - rsync for deploy tasks
 **/

// basic Grunt.js setup

module.exports = function( grunt ) {
    var JS_PATH  = 'assets/js/',
        JS_SOURCE_PATH = JS_PATH + 'source/',
        JS_VENDOR_PATH = JS_PATH + 'vendor/',
        IMG_PATH = 'assets/img/',
        CSS_PATH = 'assets/css/';

    // load all grunt tasks
    require( 'matchdep' ).filterDev( 'grunt-*' ).forEach( grunt.loadNpmTasks );

    grunt.initConfig( {
        // watch for changes and trigger, jshint, uglify
        watch: {
            js: {
                files: '<%= jshint.all %>',
                tasks: ['jshint', 'uglify']
            },

            css: {
                files: [
                    CSS_PATH + 'style.dev.css'
                ],
                tasks: ['cssmin:dist']
            }
        },

        // javascript linting with jshint
        jshint: {
            options: {
                jshintrc: '.jshintrc',
                "force": true
            },

            all: [
                'Gruntfile.js',
                JS_SOURCE_PATH + '**/*.js'
            ]
        },

        // use cssmin for minifying CSS file(s)
        cssmin: {
            dist: {
                options: {
                    banner: '@charset \'UTF-8\';\n\n' +
                            '/**\n' +
                            ' * @package     WordPress\n' +
                            ' * @subpackage  WP-Basis Theme\n' +
                            ' * @template    stylesheet\n' +
                            ' * @since       0.0.1\n' +
                            ' * \n' +
                            ' * Theme Name:  WP-Basis Codename: namespace\n' +
                            ' * Theme URI:   http://wpbasis.de/\n' +
                            ' * Description: An powerful theme framework for WordPress.\n' +
                            ' * Author:      Frank Bültge\n' +
                            ' * Author URI:  http://bueltge.de/\n' +
                            ' * Version:     0.0.1 Beta\n' +
                            ' * Template:    \n' +
                            ' * Status:      Development\n' +
                            ' * Tags:        translation-ready, theme-options\n' +
                            ' * License:     GNU General Public License (GPL) version 3\n' +
                            ' * License URI: license.txt\n' +
                            ' */\n'
                },

                files: {
                    'style.css': [
                        'assets/css/style.dev.css'
                    ]
                }
            }
        },

        // uglify to concat, minify, and make source maps
        uglify: {
            dist: {
                options: {
                    sourceMap: JS_PATH + 'map/source-map.js'
                },

                files: {
                    'assets/js/libs.min.js': [
                        JS_VENDOR_PATH + 'modernizr.js'
                    ],

                    'assets/js/main.min.js': [
                        JS_SOURCE_PATH + 'main.js'
                    ]
                }
            }
        },

        // image optimization
        imagemin: {
            dist: {
                options: {
                    optimizationLevel: 7,
                    progressive: true
                },

                files: [{
                    expand: true,
                    cwd: IMG_PATH,
                    src: '**/*',
                    dest: IMG_PATH
                }]
            }
        }
    } );

    // register task
    grunt.registerTask( 'default', ['watch'] );
};