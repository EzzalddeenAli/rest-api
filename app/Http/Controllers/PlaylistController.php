<?php namespace OpenDRadio\Rest\Http\Controllers;

use Cache;
use Request;
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
        public function getIndex()
        {
                $this->validate(Request::get(), [
                    'sort' => 'string:value|in:asc,desc',
                    'permanent' => 'in:true,false,0,1,✓'
                ]);

                // Get the parameter
                $sort = Request::get('sort') ?: 'asc';

                // Check the station name parameter value
                if (null !== $permanent = Request::get('permanent'))
                {
                        $usePermanent = ($permanent === 'true' || $permanent === 1 || $permanent === '✓' ? true : false);

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
