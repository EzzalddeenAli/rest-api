<?php namespace OpenDRadio\Rest\Http\Controllers;

use Input;
use Response;
use OpenDRadio\Messenger\Models\DeviceModel;

class DeviceController extends BaseController {

	/**
	 * Create a new device.
	 *
	 * @return void
	 */
	public function create()
	{
		if (Input::isFormData()) {
		    $formData = json_decode(Input::getBody());
	
				if(!in_array('token'), $formData) {
			Response::send(400, null, 'Invalid token');
		    }
		} else {
		    $token = Input::put('token');
		}
		
		if (null === $token) {
		    Response::send(400, null, 'Invalid token');
		}
		
		print_r(Input::getContentType());
		die();
		print_r(Input::getBody());
		print_r('???');
		die();
		
		// token: endpoint,
		// browser: mediator.execute("browser:info").name,
		// user_agent: (mediator.getUser().allowDataSharing() ? navigator.userAgent : false),
		// language: navigator.language
	}
    
	/**
	 * Update a device.
	 *
	 * @param string $token
	 *            The device token
	 * @return void
	 */
	public function update($token)
	{
		$device = DeviceModel::where('token', $token)->get()->first();

		if ($device->trashed())
		{
			Response::send(400, null, 'Device has been soft deleted');
		}

		$device->save();

		Response::send(200, null, '200 - OK, updated');
	}
    
	/**
	 * Delete a device.
	 *
	 * @param string $token
	 *            The device token
	 * @return void
	 */
	public function delete($token)
	{
	    $device = DeviceModel::where('token', $token)->get()->first();

	    if ($device->trashed())
	    {
		    Response::send(400, null, 'Device has been soft deleted');
	    }

	    $device->delete();

	    Response::send(200, null, '200 - OK, deleted');
	}

}