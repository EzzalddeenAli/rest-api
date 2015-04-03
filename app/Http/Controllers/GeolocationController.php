<?php namespace App\Http\Controllers;

use Cache;
use Carbon;
use Request;
use Response;
use MaxMind\Db\Reader;

class GeolocationController extends BaseController {

        /**
         * The query results cache duration.
         *
         * @var \Number
         */
        protected $cacheDuration = 30;

		/**
		 * Geo clue the location of the request ip address.
		 *
		 * @return void
		 */
		public function getIndex()
		{
				// Get the ip address
				$ip = Request::getIp();

				// Create a cache key based on the IP type
				if(filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4))
				{
						$cacheKey = sprintf('geolocation:ipv4_%s', str_replace('.', '_', $ip));
				}
				else
				{
						$cacheKey = sprintf('geolocation:ipv6_%s', str_replace(':', '_', strtolower($ip)));
				}

				$record = Cache::remember($cacheKey, $this->cacheDuration, function() use ($ip)
				{
						// Find a geoip record
						$reader = new Reader(storage_path('geoip/GeoLite2-City.mmdb'));
						$record = $reader->get($ip);
						$reader->close();

						return $record;
				});

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
