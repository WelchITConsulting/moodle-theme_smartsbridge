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

    // PHP strings for exec task.
    var moodleroot = 'dirname(dirname(__DIR__))',
        configfile = moodleroot + ' . "/config.php"',
        decachephp = '';

    decachephp += "define(\"CLI_SCRIPT\", true);";
    decachephp += "require(" + configfile  + ");";
    decachephp += "theme_reset_all_caches();";

    grunt.initConfig({
        less: {
            // Compile moodle styles.
            moodle: {
                options: {
                    compress: true,
                    sourceMap: false,
                    outputSourceFiles: true
                },
                files: {
                    "style/moodle.css": "less/moodle.less",
                }
            },
            // Compile editor styles.
            editor: {
                options: {
                    compress: true,
                    sourceMap: false,
                    outputSourceFiles: true
                },
                files: {
                    "style/editor.css": "less/editor.less"
                }
            }
        },
        exec: {
            decache: {
                cmd: "php -r '" + decachephp + "'",
                callback: function(error, stdout, stderror) {
                    // exec will output error messages
                    // just add one to confirm success.
                    if (!error) {
                        grunt.log.writeln("Moodle theme cache reset.");
                    }
                }
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
        'less'
    ]);

    grunt.registerTask("build", [
        'less'
    ]);
};
