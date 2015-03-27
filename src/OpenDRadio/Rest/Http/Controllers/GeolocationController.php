<?php namespace OpenDRadio\Rest\Http\Controllers;

use Carbon;
use Request;
use Response;
use MaxMind\Db\Reader;
use OpenDRadio\Radio\Models\BroadcastModel;
use OpenDRadio\Radio\Models\StationModel;

class GeolocationController extends BaseController {

	/**
	 * Geo clue the location of the request ip address.
	 *
         * @return void
	 */
	public function geoClue()
	{
		// Get the ip address
		$ip = Request::getIp();

		// Find a geoip record
		$reader = new Reader(ROOT_PATH.'/provider/GeoIP/GeoLite2-City.mmdb');
		$record = $reader->get($ip);
		$reader->close();

		if(empty($record))
		{
			Response::send(400, null, 'Can\'t find a record.');
		}

		$now = new Carbon();

		$response = [
			'timestamp' => $now->timestamp,
			'coords' => $record['location']
		];

		$response['coords']['altitude'] = 0;

		// Remove the timezone
		unset($response['coords']['time_zone']);

		Response::send(200, $response);
	}

}
