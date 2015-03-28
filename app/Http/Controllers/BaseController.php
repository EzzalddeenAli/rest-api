<?php namespace App\Http\Controllers;

use App\Contracts\ValidatesRequests;

class BaseController {
        
        use ValidatesRequests;

        /**
         * Constructor.
         */
        public function __construct()
        {
                //
        }

}
