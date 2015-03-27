<?php namespace OpenDRadio\Rest\Http\Controllers;

use Cache;
use Carbon;
use Input;
use Response;
use OpenDRadio\Radio\Models\BroadcastModel;
use OpenDRadio\Radio\Models\StationModel;

class BroadcastController extends BaseController {

        /**
         * The query results limit.
         *
         * @var \Number
         * @access protected
         */
        protected $limit = 30;

        /**
         * The query results cache duration.
         *
         * @var \Number
         * @access protected
         */
        protected $cacheDuration = 1;

        /**
         * Get the latest broadcasts by date.
         *
         * @return void
         */
        public function latest()
        {
                // Get the sort parameter value
                if (null === $sort = strtolower(Input::get('sort')))
                {
                        $sort = 'asc';
                }
                else
                {
                        // Validate the value
                        if (!in_array($sort, ['asc', 'desc']))
                        {
                            Response::send(400, null, 'Invalid sort parameter. Possible values: [asc] or [desc]');
                        }
                }

                // Create a simple date range. This might be optimized.
                $fromDate = new Carbon('-1 hour');
                $toDate = new Carbon('+5 hour');

                // Check the station name parameter value
                if (null !== $stationName = Input::get('station'))
                {
                        if (null === $station = StationModel::where('name', $stationName)->where('enabled', true)->first())
                        {
                            Response::send(400, null, 'Invalid station parameter');
                        }

                        // Create a cache key
                        $cacheKey = sprintf('broadcasts_latest:station_id_%s:sort_%s', $station->getId());

                        $models = Cache::remember($cacheKey, $this->cacheDuration, $fromDate, $toDate, $sort, function() use ($station, $fromDate, $toDate, $sort)
                        {
                                return BroadcastModel::where('enabled', true)->where('station_id', $station->getId())
                                                        ->where('starts_at', '>=', $fromDate)
                                                        ->where('ends_at', '<=', $toDate)
                                                        ->orderBy('starts_at', $sort)
                                                        ->take($this->limit)
                                                        ->get();
                        });
                }
                else
                {
                        // Create a cache key
                        $cacheKey = sprintf('broadcasts_latest:sort_%s', $sort);

                        $models = Cache::remember($cacheKey, $this->cacheDuration, function() use ($fromDate, $toDate, $sort)
                        {
                                return BroadcastModel::where('enabled', true)->where('starts_at', '>=', $fromDate)
                                                        ->where('ends_at', '<=', $toDate)
                                                        ->orderBy('starts_at', $sort)
                                                        ->take($this->limit)
                                                        ->get();
                        });
                }

                if ($models->isEmpty())
                {
                        Response::send(200, [], '200 - OK, but 0 results found');
                }
                
                Response::send(200, $models);
        }

        /**
         * Get the broadcasts for a given date range.
         *
         * @param string $start The start day (Y-m-d)
         * @param string $end The end day (Y-m-d)
         * @return void
         */
        public function fromTo($start, $end)
        {
                // Get the sort parameter value
                if (null === $sort = strtolower(Input::get('sort')))
                {
                        $sort = 'asc';
                }
                else
                {
                        // Validate the value
                        if (!in_array($sort, ['asc','desc']))
                        {
                                Response::send(400, null, 'Invalid sort parameter. Possible values: [asc] or [desc]');
                        }
                }

                // Get the page parameter value
                if (null === $page = strtolower(Input::get('page')))
                {
                        $page = 0;
                }
                else
                {
                        // Check if the value must be numeric intval
                        if (!is_numeric($page) && intval($page) != $page)
                        {
                                Response::send(400, null, 'Invalid page parameter. Value must be number');
                        }
                        
                        // Check if the value is larger than zero
                        if ($page <= 0)
                        {
                                Response::send(400, null, 'Invalid page parameter. Value must be larger > [0] (min)');
                        }
                }

                // Get the per page parameter value
                if (null === $perPage = strtolower(Input::get('per_page')))
                {
                        $perPage = $this->limit;
                }
                else
                {
                        // Check if the value is larger than zero and not greater than the limit
                        if ($perPage <= 0 || $perPage > $this->limit)
                        {
                                Response::send(400, null, sprintf('Invalid per_page parameter. Value must be larger > [0] (min) and >= than [%d] (max)', $this->limit));
                        }
                }

                // Convert the start date format
                if (null === $fromDate = Carbon::createFromFormat('Y-m-d', $start))
                {    
                        Response::send(400, null, 'Failed to transform start date');
                }

                // Convert the end date format
                if (null === $toDate = Carbon::createFromFormat('Y-m-d', $end))
                {
                        Response::send(400, null, 'Failed to transform end date');
                }

                // Validate the the date range
                if ($fromDate->timestamp >= $toDate->timestamp || $toDate->timestamp <= $fromDate->timestamp)
                {
                        Response::send(400, null, 'Invalid date range');
                }

                $skip = (($page > 0) ? $page * $perPage : 0);
                
                // Check the station name parameter value
                if (null !== $stationName = Input::get('station'))
                {
                        if (null === $station = StationModel::where('name', $stationName)->where('enabled', true)->first())
                        {
                                Response::send(400, null, 'Invalid station parameter');
                        }

                        // Create a cache key
                        $cacheKey = sprintf('broadcasts_range:station_id_%s:from_%d:to_%d:skip_%d:take_%d:sort_%s', $station->getId(), $fromDate->timestamp, $toDate->timestamp, $skip, $perPage, $sort);

                        $models = Cache::remember($cacheKey, $this->cacheDuration, function() use ($station, $fromDate, $toDate, $sort, $skip, $perPage)
                        {
                                return BroadcastModel::where('enabled', true)->where('station_id', $station->getId())
                                                        ->where('starts_at', '>=', $fromDate)
                                                        ->where('ends_at', '<=', $toDate)
                                                        ->orderBy('starts_at', $sort)
                                                        ->skip($skip)
                                                        ->take($perPage)
                                                        ->get();
                        });
                }
                else
                {
                        // Create a cache key
                        $cacheKey = sprintf('broadcasts_range:from_%d:to_%d:skip_%d:take_%d:sort_%s', $fromDate->timestamp, $toDate->timestamp, $skip, $perPage, $sort);

                        $models = Cache::remember($cacheKey, $this->cacheDuration, function() use ($fromDate, $toDate, $sort, $skip, $perPage)
                        {
                                return BroadcastModel::where('enabled', true)->where('starts_at', '>=', $fromDate)
                                                        ->where('starts_at', '>=', $fromDate)
                                                        ->where('ends_at', '<=', $toDate)
                                                        ->orderBy('starts_at', $sort)
                                                        ->skip($skip)
                                                        ->take($perPage)
                                                        ->get();
                        });
                }

                if ($models->isEmpty())
                {
                        Response::send(200, [], '200 - OK, but 0 results found');
                }

                Response::send(200, $models);
        }

        /**
         * Get a single broadcast.
         *
         * @param string $id            
         * @return void
         */
        public function get($id)
        {
                // Validate the MongoId
                if (false == \MongoId::isValid($id))
                {
                        Response::send(400, null, 'Invalid id');
                }

                // Create a cache key
                $cacheKey = sprintf('broadcast:id_%s', $id);

                $model = Cache::remember($cacheKey, $this->cacheDuration, function() use ($id)
                {
                        return BroadcastModel::where('enabled', true)->where('_id', $id)
                                                ->get()
                                                ->first();
                });

                if (null === $model)
                {
                        Response::send(404);
                }

                Response::send(200, $model);
        }

}