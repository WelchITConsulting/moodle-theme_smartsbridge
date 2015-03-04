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
            dev: {
                // Compile moodle styles.
                moodle: {
                    options: {
                        compress: false,
                        strictMath: true,
                        outputSourceFiles: true,
                        sourceMap: false
                    },
                    files: {
                        'style/moodle.css': 'assets/less/moodle.less'
                    }
                },
                // Compile editor styles.
                editor: {
                    options: {
                        compress: false,
                        sourceMap: false
                    },
                    files: {
                        "style/editor.css": "assets/less/editor.less"
                    }
                }
//            },
//            build: {
//                moodle: {
//                    options: {
//                        compress: true,
//                        sourceMap: false,
//                        outputSourceFiles: true
//                    },
//                    files: {
//                        "style/moodle.min.css": "assets/less/moodle.less",
//                    }
//                },
//                // Compile editor styles.
//                editor: {
//                    options: {
//                        compress: true,
//                        sourceMap: false,
//                        outputSourceFiles: true
//                    },
//                    files: {
//                        "style/editor.min.css": "assets/less/editor.less"
//                    }
//                }
            }
        },
        autoprefixer: {
            options: {
                browsers: [
                    'Android 2.3',
                    'Android >= 4',
                    'Chrome >= 20',
                    'Explorer >= 8',
                    'Firefox >= 24',
                    'iOS >= 6',
                    'Opera >= 12',
                    'Safari >= 6'
                ]
            },
            core: {
                options: {
                    map: true
                },
                src: [
                    'style/moodle.css',
                    'style/moodle-rtl.css',
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
        'less:dev',
//        'autoprefixer',
        'concat'
    ]);

    grunt.registerTask("build", [
        'less:build',
        'autoprefixer',
        'concat'
    ]);
};
