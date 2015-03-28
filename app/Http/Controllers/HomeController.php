<?php namespace App\Http\Controllers;

use App;
use Response;

class HomeController extends BaseController {

        /**
         * Get all resources.
         *
         * @return void
         */
        public function getIndex()
        {
                $routes = App::router()->getNamedRoutes();

                if (!$routes->valid())
                {
                        Response::send(200, [], '200 - OK, but no endpoints found');
                }

                $response = [];

                foreach ($routes as $route)
                {
                        $response[$route->getName()] = $route->getPattern();
                }

                Response::send(200, $response);
        }

}
