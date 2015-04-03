<?php namespace App\Http\Controllers;

use Cache;
use Response;
use OpenDRadio\Radio\Models\GeoFrequencyModel;

class GeoFrequencyController extends BaseController {

        /**
         * The query results cache duration.
         *
         * @var \Number
         */
        protected $cacheDuration = 5;

        /**
         * Get all geo frequencies as an aggregated result.
         *
         * @return void
         */
        public function getIndex()
        {
                $ops = [[
                        '$group' => [
                                '_id' => [
                                        'country' => '$country',
                                        'state' => '$state'
                                ],
                                'cities' => [
                                        '$addToSet' => [
                                                '_id' => '$_id',
                                                'city' => '$city',
                                                'coords' => '$coords',
                                                'frequencies' => '$frequencies',
                                                'updated_at' => '$updated_at'
                                        ]
                                ]
                        ]
                ], [
                        '$group' => [
                            '_id' => '$_id.country',
                                'states' => [
                                        '$addToSet' => [
                                                'state' => '$_id.state',
                                                'cities' => '$cities'
                                        ]
                                ]
                        ]
                ]];

                $geoFrequencies = [];

                $aggregation = Cache::remember('geo_frequencies_all', $this->cacheDuration, function() use ($ops)
                {
                        return GeoFrequencyModel::raw()->aggregate($ops);
                });

                if (!empty($aggregation['result']))
                {
                        $results = $aggregation['result'];

                        foreach ($results as $resultKey => $result)
                        {
                                $result['country'] = $result['_id'];
                                unset($result['_id']);

                                ksort($result);

                                foreach ($result['states'] as $stateKey => $state)
                                {
                                        foreach ($state['cities'] as $cityKey => $city)
                                        {
                                                // Convert the MongoId
                                                $city['_id'] = $city['_id']->{'$id'};

                                                // Convert date (W3C)
                                                $updated_at = new \DateTime();
                                                $updated_at->setTimestamp($city['updated_at']->sec);

                                                foreach ($city['frequencies'] as $frequencyKey => $frequency)
                                                {
                                                        // Convert the MongoId
                                                        $frequency['_id'] = $frequency['_id']->{'$id'};

                                                        ksort($frequency);

                                                        $city['frequencies'][$frequencyKey] = $frequency;
                                                }

                                                $city['updated_at'] = $updated_at->format(\DateTime::W3C);

                                                ksort($city);

                                                $result['states'][$stateKey]['cities'][$cityKey] = $city;
                                        }
                                }

                                $geoFrequencies[] = $result;
                        }
                }
                else
                {
                        Response::send(200, [], '200 - OK, but 0 results found');
                }

                Response::send(200, $geoFrequencies);
        }

}
