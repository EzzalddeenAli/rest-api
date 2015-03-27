<?php

$config['slim'] = [

    /*
    |--------------------------------------------------------------------------
    | Application Mode
    |--------------------------------------------------------------------------
    |
    | Slim supports the concept of modes in that you may define your own modes
    | and prompt Slim to prepare itself appropriately for the current mode.
    | For example, you may want to enable debugging in “development” mode but
    | not in “production” mode. The examples below demonstrate how to configure
    | Slim differently for a given mode.
    |
    */

    'mode' => 'development',

    /*
    |--------------------------------------------------------------------------
    | Application Debug Mode
    |--------------------------------------------------------------------------
    |
    | When debugging is enabled and an exception or error occurs, a diagnostic
    | screen will appear with the error description, the affected file,
    | the file line number, and a stack trace. If debugging is disabled,
    | the custom Error handler will be invoked instead.
    |
    */

    'debug' => true,

    /*
    |--------------------------------------------------------------------------
    | Application HTTP Version
    |--------------------------------------------------------------------------
    |
    | By default, Slim returns an HTTP/1.1 response to the client. Use this
    | setting if you need to return an HTTP/1.0 response. This is useful if
    | you use PHPFog or an nginx server configuration where you communicate
    | with backend proxies rather than directly with the HTTP client.
    |
    */

    'http.version' => '1.1',

    /*
    |--------------------------------------------------------------------------
    | Application Timezone
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default timezone for your application, which
    | will be used by the PHP date and date-time functions. We have gone
    | ahead and set this to a sensible default for you out of the box.
    |
    */

    'timezone' => 'Europe/Berlin',

    /*
    |--------------------------------------------------------------------------
    | Application Logging
    |--------------------------------------------------------------------------
    |
    | This enables or disables Slim’s logger.
    |
    */

    'log.enabled' => true,

    /*
    |--------------------------------------------------------------------------
    | Application Log Level
    |--------------------------------------------------------------------------
    |
    | The Slim application’s log object will respect or ignore logged messages
    | based on its log level setting. When you invoke the log objects’s
    | methods, you are inherently assigning a level to the logged message.
    |
    */

    'log.level' => Slim\Log::DEBUG,

    /*
    |--------------------------------------------------------------------------
    | Application Log Path
    |--------------------------------------------------------------------------
    |
    | The Slim application’s log path.
    |
    */

    'log.path' => APP_PATH.'storage/logs',

];
