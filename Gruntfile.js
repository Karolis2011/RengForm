module.exports = function (grunt) {
    'use strict';

    require('jit-grunt')(grunt);

    // Arrange configs alphabetically
    grunt.initConfig({
        clean: {
            css: {
                src: ['public/style/css']
            },
            js: {
                src: ['public/js']
            }
        },
        copy: {
            'assets': {
                files: [
                    {
                        cwd: 'assets/css',
                        src: '**/*',
                        dest: 'public/style/css/custom',
                        expand: true
                    },
                    {
                        cwd: 'assets/js',
                        src: '**/*',
                        dest: 'public/js/custom',
                        expand: true
                    }
                ]
            },
            'formBuilder': {
                files: [
                    {
                        src: 'node_modules/formBuilder/dist/form-builder.min.js',
                        dest: 'public/js/formBuilder/form-builder.min.js'
                    },
                    {
                        src: 'node_modules/formBuilder/dist/form-render.min.js',
                        dest: 'public/js/formBuilder/form-render.min.js'
                    },
                    {
                        src: 'node_modules/jquery-ui-sortable/jquery-ui.min.js',
                        dest: 'public/js/jquery-ui-sortable/jquery-ui.min.js'
                    }
                ]
            },
            'select2': {
                files: [
                    {
                        src: 'node_modules/select2/dist/js/select2.full.min.js',
                        dest: 'public/js/select2/select2.full.min.js'
                    },
                    {
                        src: 'node_modules/select2/dist/css/select2.min.css',
                        dest: 'public/style/css/select2/select2.min.css'
                    },
                    {
                        src: 'node_modules/select2-bootstrap4-theme/dist/select2-bootstrap4.min.css',
                        dest: 'public/style/css/select2/select2-bootstrap4.min.css'
                    }
                ]
            },
            'sortable': {
                files: [
                    {
                        src: 'node_modules/sortablejs/Sortable.min.js',
                        dest: 'public/js/sortablejs/Sortable.min.js'
                    }
                ]
            },
            'datetimepicker': {
                files: [
                    {
                        src: 'node_modules/moment/min/moment.min.js',
                        dest: 'public/js/moment/moment.min.js'
                    },
                    {
                        src: 'node_modules/pc-bootstrap4-datetimepicker/build/js/bootstrap-datetimepicker.min.js',
                        dest: 'public/js/datetimepicker/bootstrap-datetimepicker.min.js'
                    },
                    {
                        src: 'node_modules/pc-bootstrap4-datetimepicker/build/css/bootstrap-datetimepicker.css',
                        dest: 'public/style/css/datetimepicker/bootstrap-datetimepicker.css'
                    }
                ]
            },
            'bootstrap': {
                files: [
                    {
                        src: 'node_modules/bootstrap/dist/js/bootstrap.min.js',
                        dest: 'public/js/bootstrap/bootstrap.min.js'
                    },
                    {
                        src: 'node_modules/bootstrap/dist/css/bootstrap.min.css',
                        dest: 'public/style/css/bootstrap/bootstrap.min.css'
                    },
                    {
                        src: 'node_modules/popper.js/dist/umd/popper.min.js',
                        dest: 'public/js/popper/popper.min.js'
                    }
                ]
            },
            'flag-icon-css': {
                files: [
                    {
                        src: 'node_modules/flag-icon-css/css/flag-icon.min.css',
                        dest: 'public/style/css/flag-icon/flag-icon.min.css'
                    },
                    {
                        cwd: 'node_modules/',
                        src: 'flag-icon-css/flags/1x1/*',
                        flatten: true,
                        expand: true,
                        dest: 'public/style/css/flags/1x1'
                    },
                    {
                        cwd: 'node_modules/',
                        src: 'flag-icon-css/flags/4x3/*',
                        flatten: true,
                        expand: true,
                        dest: 'public/style/css/flags/4x3'
                    }
                ]
            },
            'rengform': {
                files: [
                    {
                        src: 'node_modules/jquery/dist/jquery.min.js',
                        dest: 'public/js/jquery/jquery.min.js'
                    },
                    {
                        src: 'node_modules/admin-lte/plugins/jQueryUI/jquery-ui.min.js',
                        dest: 'public/js/jquery-ui/jquery-ui.min.js'
                    },
                    {
                        src: 'node_modules/font-awesome/css/font-awesome.min.css',
                        dest: 'public/style/css/font-awesome/font-awesome.min.css'
                    },
                    {
                        cwd: 'node_modules/',
                        src: 'font-awesome/fonts/*',
                        flatten: true,
                        expand: true,
                        dest: 'public/style/css/fonts/'
                    }
                ]
            }
        }
    });

    //#################### BEGIN TASKS REGISTER ####################

    // Default task
    grunt.registerTask('default', ['init']);

    // Initial task
    grunt.registerTask('init', [
        'clean:css',
        'clean:js',
        'rengform'
    ]);

    // Production task
    grunt.registerTask('prod', [
        'clean:css',
        'clean:js',
        'rengform'
    ]);

    grunt.registerTask('rengform', [
        'copy:rengform',
        'copy:bootstrap',
        'copy:datetimepicker',
        'copy:sortable',
        'copy:select2',
        'copy:formBuilder',
        'copy:flag-icon-css',
        'copy:assets'
    ]);

    //#################### END TASKS REGISTER ####################
};
