<?php

// Settings to make all errors more obvious during testing
error_reporting(-1);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
date_default_timezone_set('UTC');

use There4\Slim\Test\WebTestCase;

define('ROOT_PATH', realpath(__DIR__.'/..'));

require_once ROOT_PATH.'/vendor/autoload.php';

// Initialize our own copy of the slim application
class LocalWebTestCase extends WebTestCase {

        public function getSlimInstance()
        {
                $app = new \Slim\Slim(array(
                    'version'        => '0.0.0',
                    'debug'          => false,
                    'mode'           => 'testing',
                    'templates.path' => __DIR__ . '/../app/templates'
                ));

                $app = require_once ROOT_PATH.'/bootstrap/start.php';
                $app->run();

                return $app;
        }
}
