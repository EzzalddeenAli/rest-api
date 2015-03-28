<?php

$config['providers'] = [
    
        /*
        |--------------------------------------------------------------------------
        | Autoloaded Service Providers
        |--------------------------------------------------------------------------
        |
        | The service providers listed here will be automatically loaded on the
        | request to your application. Feel free to add your own services to
        | this array to grant expanded functionality to your applications.
        |
        */

        'Illuminate\Events\EventServiceProvider',
        'Illuminate\Database\DatabaseServiceProvider',
        'Illuminate\Cache\CacheServiceProvider',
        'Illuminate\Filesystem\FilesystemServiceProvider',
        'Illuminate\Redis\RedisServiceProvider',
        'Illuminate\Validation\ValidationServiceProvider',
        'Illuminate\Translation\TranslationServiceProvider',
        'App\Providers\RouteServiceProvider',
        'Jenssegers\Mongodb\MongodbServiceProvider',

];
