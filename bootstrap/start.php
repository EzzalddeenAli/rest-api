<?php

if (!defined('ROOT_PATH'))
{
    define('ROOT_PATH', __DIR__.'/../');
}

if (!defined('APP_PATH'))
{
    define('APP_PATH', ROOT_PATH.'app/');
}

/*
|--------------------------------------------------------------------------
| Initialize The Configuration
|--------------------------------------------------------------------------
|
| Load the app configuration files.
|
*/

$config = array(
    'path.root' => ROOT_PATH,
    'path.app' => APP_PATH
);


foreach (glob(APP_PATH.'config/*.php') as $file) {
    require $file;
}

/*
|--------------------------------------------------------------------------
| Merge The Configuration
|--------------------------------------------------------------------------
|
| Here we are merging the configuration arrays to the base slim
| configuration. You should not be changing these here. 
|
*/

if(isset($config['cache'])) {

    foreach($config['cache'] as $key => $value) {

        $config['slim']['cache.'.$key] = $value;
    }

    unset($config['cache']);
}

if(isset($config['cookies'])) {

    foreach($config['cookies'] as $key => $value) {

        $config['slim']['cookies.'.$key] = $value;
    }

    unset($config['cookies']);
}

if(isset($config['database'])) {

    foreach($config['database'] as $key => $value) {

        $config['slim']['database.'.$key] = $value;
    }

    unset($config['database']);
}

/*
|--------------------------------------------------------------------------
| Set The Default Timezone
|--------------------------------------------------------------------------
|
| Here we will set the default timezone for PHP. PHP is notoriously mean
| if the timezone is not explicitly set. This will be used by each of
| the PHP date and date-time functions throoughout the application.
|
*/

date_default_timezone_set($config['slim']['timezone']);

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| The first thing we will do is create a new Rest application instance
| which serves as the "glue" for all the components of the Application,
| and is the IoC container for the system binding all of the various parts.
|
*/

$app = new OpenDRadio\Rest\Application($config);

$app->boot();

/*
|--------------------------------------------------------------------------
| Load The Application
|--------------------------------------------------------------------------
|
| Here we will load this Slim application. We will keep this in a
| separate location so we can isolate the creation of an application
| from the actual running of the application with a given request.
|
*/

require APP_PATH.'/start/global.php';

/*
|--------------------------------------------------------------------------
| Return The Application
|--------------------------------------------------------------------------
|
| This script returns the application instance. The instance is given to
| the calling script so we can separate the building of the instances
| from the actual running of the application and sending responses.
|
*/

return $app;
