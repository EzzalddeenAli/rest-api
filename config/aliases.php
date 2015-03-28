<?php

$config['aliases'] = [
        
        /*
        |--------------------------------------------------------------------------
        | Class Aliases
        |--------------------------------------------------------------------------
        |
        | This array of class aliases will be registered when this application
        | is started. However, feel free to register as many as you wish as
        | the aliases are "lazy" loaded so they don't hinder performance.
        |
        */

        'App'        => 'SlimFacades\App',
        'Cache'      => 'App\Facades\CacheFacade',
        'Carbon'     => 'Carbon\Carbon',
        'Config'     => 'SlimFacades\Config',
	'File'       => 'Illuminate\Support\Facades\File',
        'Input'      => 'SlimFacades\Input',
        'Log'        => 'SlimFacades\Log',
        'Middleware' => 'Slim\Middleware',
        'Moloquent'  => 'Jenssegers\Mongodb\Model',
        'Request'    => 'SlimFacades\Request',
        'Response'   => 'App\Facades\ResponseFacade',
        'Route'      => 'App\Facades\RouteFacade',
        'Sentry'     => 'App\Facades\SentryFacade',
        'Slim'       => 'Slim\Slim',
        'Uuid'       => 'Webpatser\Uuid\Uuid',
        'Validator'  => 'App\Facades\ValidatorFacade',
    
    ];
