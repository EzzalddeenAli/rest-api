<?php namespace OpenDRadio\Rest\Http\Controllers;

use App;
use Response;

class HomeController extends BaseController {

        /**
         * Get all endpoints.
         *
         * @return void
         */
        public function welcome()
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
