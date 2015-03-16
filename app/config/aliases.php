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

    'App' => 'SlimFacades\App',
    'Cache' => 'OpenDRadio\Rest\Facades\CacheFacade',
    'Carbon' => 'Carbon\Carbon',
    'Config' => 'SlimFacades\Config',
    'Input' => 'SlimFacades\Input',
    'Log' => 'SlimFacades\Log',
    'Middleware' => 'Slim\Middleware',
    'Moloquent' => 'Jenssegers\Mongodb\Model',
    'Request' => 'SlimFacades\Request',
    'Response' => 'OpenDRadio\Rest\Facades\ResponseFacade',
    'Route' => 'OpenDRadio\Rest\Facades\RouteFacade',
    'Sentry' => 'OpenDRadio\Rest\Facades\SentryFacade',
    'Slim' => 'Slim\Slim',
    'Uuid' => 'Webpatser\Uuid\Uuid',

];