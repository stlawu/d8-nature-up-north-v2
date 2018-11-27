var pkg = require('./package.json');

module.exports = function(grunt){
    require('matchdep').filterDev('grunt-*').forEach(grunt.loadNpmTasks);
    grunt.util.linefeed = '\n';
    grunt.initConfig({
        pkg: pkg,
        dist: '..',
        filename: pkg.name,
        meta: {
            banner: ['/*',
                     ' * <%= pkg.name %>',
                     ' * Version: <%= pkg.version %> - <%= grunt.template.today("yyyy-mm-dd") %>',
                     ' */\n'].join('\n')
        },
        ngdocs: {
            options: {
                dest: 'docs'
            },
            all: ['src/js/**/*.js','src/js/index.ngdoc']
        },
        htmlhint: {
            build: {
                options: {
                    'tag-pair': true,
                    'tagname-lowercase': true,
                    'attr-lowercase': true,
                    'attr-value-double-quotes': true,
                    'doctype-first': false,
                    'spec-char-escape': true,
                    'id-unique': true,
                    'head-script-disabled': true,
                    'style-disabled': true
                },
                src: ['src/js/**/*.html']
            }
        },
        jshint: {
            files: ['Gruntfile.js','src/js/**/*.js','!src/js/partials/*.js'],
            options: {
                jshintrc: '.jshintrc'
            }
        },
        html2js: {
            'map-component': {
                src: ['src/js/**/*.html'],
                dest: 'src/js/partials/templates.html.js'
            }
        },
        concat: {
            dist: {
                options: {
                    banner: '<%= meta.banner %>\n',
                    srcMap: true
                },
                src: [], // list generated in build.
                dest: '<%= dist %>/js/<%= filename %>.js'
            },
            thirdParty: {
                src: [
                    'node_modules/angular/angular.js',
                    'node_modules/lodash/lodash.js',
                    'node_modules/angular-simple-logger/dist/angular-simple-logger.js',
                    'node_modules/angular-google-maps/dist/angular-google-maps.js'
                ],
                dest: '<%= dist %>/js/<%= filename %>-requirements.js'
            }
        },
        uglify: {
            dist:{
                options: {
                    banner: '<%= meta.banner %>'
                },
                src:['<%= concat.dist.dest %>'],
                dest:'<%= dist %>/js/<%= filename %>.min.js'
            },
            thirdParty: {
                src:['<%= dist %>/js/<%= filename %>-requirements.js'],
                dest:'<%= dist %>/js/<%= filename %>-requirements.min.js'
            }
        },
        copy: {

        },
        sass: {
            dist: {
                options: {
                    style: 'expanded'
                },
                files: {
                    '<%= dist %>/css/component.css': 'src/css/component.scss'
                }
            }
        },
        delta: {
            html: {
                files: ['src/js/**/*.html'],
                tasks: ['htmlhint', 'html2js', 'build']
            },
            js: {
                files: ['src/js/**/*.js'],
                tasks: ['jshint','build']
            },
            css: {
                files: [
                    'src/css/*.scss'
                ],
                tasks: ['sass']
            }
        }
    });

    grunt.registerTask('before-test',['htmlhint','jshint','html2js']);
    grunt.registerTask('after-test',['sass','build']);
    grunt.renameTask('watch','delta');
    grunt.registerTask('watch',['before-test', 'after-test', 'delta']);
    grunt.registerTask('default', ['before-test', /*'test',*/ 'after-test']);
    grunt.registerTask('thirdParty',['concat:thirdParty','uglify:thirdParty']);

    grunt.registerTask('build',function() {
        var jsSrc = [];
        // there is no semblance of order here so need to be careful about
        // dependencies between .js files
        grunt.file.expand({filter: 'isFile', cwd: '.'}, 'src/js/**')
             .forEach(function(f){
                if(f.search(/\.js$/) > 0 && f.search(/\.spec\.js$/) === -1) {
                    jsSrc.push(f);
                }
             });
        grunt.config('concat.dist.src', grunt.config('concat.dist.src').concat(jsSrc));
        grunt.task.run(['concat:dist','uglify:dist']);
    });
};
