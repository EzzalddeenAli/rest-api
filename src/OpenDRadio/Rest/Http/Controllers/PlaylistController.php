<?php namespace OpenDRadio\Rest\Http\Controllers;

use Cache;
use Input;
use Response;
use OpenDRadio\Radio\Models\StationModel;

class PlaylistController extends BaseController {
    
        /**
         * The query results cache duration.
         *
         * @var \Number
         * @access protected
         */
        protected $cacheDuration = 5;

        /**
         * Get all stations and audio streams.
         *
         * @return void
         */
        public function all()
        {
                // Get the sort parameter value
                if (null === $sort = strtolower(Input::get('sort')))
                {
                        $sort = 'asc';
                }
                else
                {
                        // Validate the value
                        if (! in_array($sort, array('asc', 'desc')))
                        {
                                Response::send(400, null, 'Invalid sort parameter. Possible values: [asc] or [desc]');
                        }
                }

                // Check the station name parameter value
                if (null !== $permanent = Input::get('permanent'))
                {
                        if ($permanent !== 'true' && $permanent !== '✓' && $permanent !== 'false')
                        {
                                Response::send(400, null, 'Invalid permanent parameter. Value must be boolean');
                        }

                        $usePermanent = ($permanent === 'true' || $permanent === '✓' ? true : false);

                        // Create a cache key
                        $cacheKey = sprintf('playlist:permanent_%s:sort_%s', $boolean, $sort);

                        $models = Cache::remember($cacheKey, $this->cacheDuration, function() use ($usePermanent, $sort)
                        {
                                return StationModel::where('enabled', true)->where('permanent', $usePermanent)
                                                            ->orderBy('updated_at', $sort)
                                                            ->get();
                        });
                }
                else
                {
                        // Create a cache key
                        $cacheKey = sprintf('playlist:sort_%s', $sort);

                        $models = Cache::remember($cacheKey, $this->cacheDuration, function() use ($sort)
                        {
                                return StationModel::where('enabled', true)->orderBy('updated_at', $sort)
                                                    ->get();
                        });
                }

                if ($models->isEmpty())
                {
                        Response::send(200, array(), '200 - OK, but 0 results found');
                }

                Response::send(200, $models);
        }

}