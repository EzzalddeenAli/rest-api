<?php

$config['database'] = [
    
        /*
        |--------------------------------------------------------------------------
        | Default Database Connection Name
        |--------------------------------------------------------------------------
        |
        | Here you may specify which of the database connections below you wish
        | to use as your default connection for all database work. Of course
        | you may use many connections at once using the Database library.
        |
        */

        'default' => 'mongodb_data',

        /*
        |--------------------------------------------------------------------------
        | Database Connections
        |--------------------------------------------------------------------------
        |
        | Here are each of the database connections setup for your application.
        |
        */

        'connections' => [

                'mongodb_data' => [
                        'driver'   => 'mongodb',
                        'host'     => 'localhost',
                        'port'     => 27017,
                        'username' => '',
                        'password' => '',
                        'database' => 'dradio_data',
                ],

                'mongodb_system' => [
                        'driver'   => 'mongodb',
                        'host'     => 'localhost',
                        'port'     => 27017,
                        'username' => '',
                        'password' => '',
                        'database' => 'dradio_system',
                ],

        ],

        /*
        |--------------------------------------------------------------------------
        | Redis Databases
        |--------------------------------------------------------------------------
        |
        | Redis is an open source, fast, and advanced key-value store that also
        | provides a richer set of commands than a typical key-value systems
        | such as APC or Memcached.
        |
        */

        'redis' => [

                'cluster' => false,

                'default' => [
                        'host'     => '127.0.0.1',
                        'port'     => 6379,
                        'database' => ''
                ],

        ],

];
