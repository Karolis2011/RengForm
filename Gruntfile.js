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
            'select2': {
                files: [
                    {
                        src: 'node_modules/select2/dist/js/select2.full.min.js',
                        dest: 'public/js/select2/select2.full.min.js'
                    },
                    {
                        src: 'node_modules/select2/dist/css/select2.min.css',
                        dest: 'public/style/css/select2/select2.min.css'
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
                        src: 'node_modules/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js',
                        dest: 'public/js/datetimepicker/bootstrap-datetimepicker.min.js'
                    },
                    {
                        src: 'node_modules/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css',
                        dest: 'public/style/css/datetimepicker/bootstrap-datetimepicker.css'
                    }
                ]
            },
            'datatable': {
                files: [
                    {
                        src: 'node_modules/datatables.net/js/jquery.dataTables.js',
                        dest: 'public/js/datatables/jquery.dataTables.js'
                    },
                    {
                        src: 'node_modules/datatables.net-bs/js/dataTables.bootstrap.js',
                        dest: 'public/js/datatables/dataTables.bootstrap.js'
                    },
                    {
                        src: 'node_modules/datatables.net-bs/css/dataTables.bootstrap.css',
                        dest: 'public/style/css/datatables/dataTables.bootstrap.css'
                    }
                ]
            },
            'admin-lte': {
                files: [
                    {
                        src: 'node_modules/admin-lte/dist/js/adminlte.min.js',
                        dest: 'public/js/adminlte/adminlte.min.js'
                    },
                    {
                        src: 'node_modules/admin-lte/dist/css/AdminLTE.min.css',
                        dest: 'public/style/css/adminlte/AdminLTE.min.css'
                    },
                    {
                        src: 'node_modules/admin-lte/dist/css/skins/skin-blue.min.css',
                        dest: 'public/style/css/adminlte/skin-blue.min.css'
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
                        cwd: 'node_modules/',
                        src: 'bootstrap/fonts/*',
                        flatten: true,
                        expand: true,
                        dest: 'public/style/css/fonts/'
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
        'copy:admin-lte',
        'copy:bootstrap',
        'copy:datatable',
        'copy:datetimepicker',
        'copy:sortable',
        'copy:assets'
    ]);

    //#################### END TASKS REGISTER ####################
};
