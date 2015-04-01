<?php namespace App\Http\Controllers;

use Config;
use Response;
use OpenDRadio\Messenger\Models\DeviceModel;

class NotificationController extends BaseController {

        /**
         * Get a message.
         *
         * @param string $token
         * @param integer $version
         * @return void
         */
        public function getMessage($token, $version)
        {
                $this->validate(['token' => $token], [
                        'token' => 'exists:devices,token',
                ], [], 'mongodb_system');

                $device = DeviceModel::where('token', $token)->get()->first();

                if(null === $versionMessage = $device->messages()->where('version', $version)->get()->first())
                {
                        Response::send(404, null, 'Message with version given id not found');
                }

                if(null === $message = $versionMessage->message()->get())
                {
                        Response::send(405, null, 'Message not found');
                }

                Response::send(200, $message);
        }

}
