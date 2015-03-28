<?php namespace App\Facades;

use SlimFacades\Route;

class RouteFacade extends Route {

        /**
         * Add application-wide route conditions.
         *
         * @see \Slim\Route::setDefaultConditions
         * @return void
         */
        public static function conditions($args)
        {
                return \Slim\Route::setDefaultConditions($args);
        }

}
