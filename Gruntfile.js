/*
 * Copyright (C) 2015 Welch IT Consulting
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * Filename : Gruntfile
 * Author   : John Welch <jwelch@welchitconsulting.co.uk>
 * Created  : 03 Jan 2015
 */

module.exports = function(grunt) {

    // Load all tasks
    require('load-grunt-tasks')(grunt);

    // Show elapsed time
    require('time-grunt')(grunt);

    // List the Bootstrap JS files in the order they are to be imported
    var bootstrapJsFiles = [
        'assets/vendor/bootstrap/js/transition.js',
        'assets/vendor/bootstrap/js/alert.js',
        'assets/vendor/bootstrap/js/button.js',
        'assets/vendor/bootstrap/js/carousel.js',
        'assets/vendor/bootstrap/js/collapse.js',
        'assets/vendor/bootstrap/js/dropdown.js',
        'assets/vendor/bootstrap/js/modal.js',
        'assets/vendor/bootstrap/js/tooltip.js',
        'assets/vendor/bootstrap/js/popover.js',
        'assets/vendor/bootstrap/js/scrollspy.js',
        'assets/vendor/bootstrap/js/tab.js',
        'assets/vendor/bootstrap/js/affix.js'
    ];

    grunt.initConfig({

        less: {
            moodledev: {
                options: {
                    compress: false,
                    strictMath: true,
                    outputSourceFiles: true,
                    sourceMap: true,
                    sourceMapURL: 'moodle.css.map',
                    sourceMapFilename: 'style/moodle.css.map'
                },
                src: 'assets/less/moodle.less',
                dest: 'style/moodle.css'
            },
            editordev: {
                options: {
                    compress: false,
                    strictMath: true,
                    outputSourceFiles: true,
                    sourceMap: true,
                    sourceMapURL: 'editor.css.map',
                    sourceMapFilename: 'style/editor.css.map'
                },
                src: 'assets/less/editor.less',
                dest: 'style/editor.css'
            },
            moodlebuild: {
                options: {
                    compress: true,
                    strictMath: true,
                    outputSourceFiles: true,
                    sourceMap: true,
                    sourceMapURL: 'moodle.css.map',
                    sourceMapFilename: 'style/moodle.css.map'
                },
                src: 'assets/less/moodle.less',
                dest: 'style/moodle.css'
            },
            editorbuild: {
                options: {
                    compress: true,
                    strictMath: true,
                    outputSourceFiles: true,
                    sourceMap: true,
                    sourceMapURL: 'editor.css.map',
                    sourceMapFilename: 'style/editor.css.map'
                },
                src: 'assets/less/editor.less',
                dest: 'style/editor.css'
            },
        },

        autoprefixer: {
            options: {
                browsers: [ 'last 2 versions', 'ie 8', 'ie 9' ]
            },
            core: {
                options: {
                    map: true
                },
                src: [
                    'style/moodle.css',
                    'style/editor.css'
                ]
            }
        },

        concat: {
            options: {
                separator: ''
            },
            bootstrap: {
                src: [
                    'assets/js/jquery-loaded-test.js',
                    [bootstrapJsFiles]
                ],
                dest: 'jquery/bootstrap.js'
            }
        },

        copy: {
            dev: {
                nonull: true,
                src: 'assets/vendor/html5shiv/dist/html5shiv-printshiv.js',
                dest: 'javascript/html5shiv.js'
            },
            build: {
                nonull: true,
                src: 'assets/vendor/html5shiv/dist/html5shiv-printshiv.min.js',
                dest: 'javascript/html5shiv.js'
            }
        },

        watch: {
            // Watch for any changes to less files and compile.
            files: ["less/**/*.less"],
            tasks: ["less:moodle", "less:editor", "exec:decache"],
            options: {
                spawn: false
            }
        }
    });

    // Register tasks.
    grunt.registerTask("default", ["watch"]);

    grunt.registerTask("dev", [
        'less:moodledev',
        'less:editordev',
        'autoprefixer',
        'copy:dev',
        'concat'
    ]);

    grunt.registerTask("build", [
        'less:moodlebuild',
        'less:editorbuild',
        'autoprefixer',
        'copy:build',
        'concat'
    ]);
};
