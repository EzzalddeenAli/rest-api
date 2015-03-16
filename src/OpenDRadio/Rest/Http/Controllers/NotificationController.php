<?php namespace OpenDRadio\Rest\Http\Controllers;

use Input;
use Response;
use OpenDRadio\Messenger\Models\DeviceModel;

class NotificationController extends BaseController {

        /**
         * The query results cache duration.
         *
         * @var \Number
         * @access protected
         */
        protected $cacheDuration = 5;

        /**
         * Get one notification by its token and the version of the message
         *
         * @return void
         */
        public function get($token, $version)
        {
                //
        }

}