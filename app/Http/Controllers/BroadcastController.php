<?php namespace App\Http\Controllers;

use App;
use Cache;
use Carbon;
use Request;
use Response;
use Illuminate\Database\Eloquent\Collection;
use OpenDRadio\Radio\Models\BroadcastModel;
use OpenDRadio\Radio\Models\StationModel;

class BroadcastController extends BaseController {

        /**
         * The query results limit.
         *
         * @var \Number
         */
        protected $limit = 30;

        /**
         * The query results cache duration.
         *
         * @var \Number
         */
        protected $cacheDuration = 1;

        /**
         * Get the latest broadcasts by date.
         *
         * @return void
         */
        public function getLatest()
        {
                $this->validate(Request::get(), [
                        'sort' => 'string:value|in:asc,desc',
                        'station' => 'string:value|exists:stations,name',
                ]);

                // Get the parameter
                $sort = Request::get('sort') ?: 'asc';

                // Create a simple date range. This might be optimized.
                $fromDate = new Carbon('-1 hour');
                $toDate = new Carbon('+5 hour');

                // Check the station name parameter value
                if (null !== $stationName = Request::get('station'))
                {
                        $station = StationModel::where('name', $stationName)->where('enabled', true)->first();

                        // Create a cache key
                        $cacheKey = sprintf('broadcasts_latest:station_id_%s:sort_%s', $station->getId(), $sort);

                        $collection = Cache::remember($cacheKey, $this->cacheDuration, function() use ($station, $fromDate, $toDate, $sort)
                        {
                                return $station->getBroadcasts()->where('enabled', true)->where('station_id', $station->getId())
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

                        $collection = Cache::remember($cacheKey, $this->cacheDuration, function() use ($fromDate, $toDate, $sort)
                        {
                                $collection = new Collection();

                                StationModel::all()->each(function($station) use(&$collection, $fromDate, $toDate, $sort)
                                {
                                        $collection = $collection->merge($station->getBroadcasts()->where('enabled', true)->where('station_id', $station->getId())
                                                                                ->where('starts_at', '>=', $fromDate)
                                                                                ->where('ends_at', '<=', $toDate)
                                                                                ->orderBy('starts_at', $sort)
                                                                                ->take($this->limit)
                                                                                ->get());
                                });

                                return $collection;
                        });
                }

                if ($collection->isEmpty())
                {
                        Response::send(200, [], '200 - OK, but 0 results found');
                }
                
                Response::send(200, $collection);
        }

        /**
         * Get the broadcasts for a given date range.
         *
         * @param string $start The start day (Y-m-d)
         * @param string $end The end day (Y-m-d)
         * @return void
         */
        public function getFromTo($start, $end)
        {
                $this->validate(array_merge(Request::get(), [
                        'start' => $start,
                        'end' => $end
                ]), [
                        'sort' => 'string:value|in:asc,desc',
                        'page' => 'numeric:value|min:1|max:1000',
                        'per_page' => sprintf('numeric:value|min:1|max:%d', $this->limit),
                        'start' => sprintf('date|date_format:"Y-m-d"|before:%s', $end),
                        'end' => sprintf('date|date_format:"Y-m-d"|after:%s', $start),
                        'station' => 'string:value|exists:stations,name',
                ]);

                // Get the parameters
                $sort = Request::get('sort') ?: 'asc';
                $page = Request::get('page') ?: 1;
                $perPage = Request::get('per_page') ?: $this->limit;

                // Convert the start date format
                $fromDate = Carbon::createFromFormat('Y-m-d', $start);
                $toDate = Carbon::createFromFormat('Y-m-d', $end);

                $skip = (($page > 0) ? $page * $perPage : 0);

                // Check the station name parameter value
                if (null !== $stationName = Request::get('station'))
                {
                        $station = StationModel::where('name', $stationName)->where('enabled', true)->first();

                        // Create a cache key
                        $cacheKey = sprintf('broadcasts_range:station_id_%s:from_%d:to_%d:skip_%d:take_%d:sort_%s', $station->getId(), $fromDate->timestamp, $toDate->timestamp, $skip, $perPage, $sort);

                        $collection = Cache::remember($cacheKey, $this->cacheDuration, function() use ($station, $fromDate, $toDate, $sort, $skip, $perPage)
                        {
                                return $station->getBroadcasts()->where('enabled', true)->where('station_id', $station->getId())
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

                        $collection = Cache::remember($cacheKey, $this->cacheDuration, function() use ($fromDate, $toDate, $sort, $skip, $perPage)
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

                if ($collection->isEmpty())
                {
                        Response::send(200, [], '200 - OK, but 0 results found');
                }

                Response::send(200, $collection);
        }

        /**
         * Get a single broadcast.
         *
         * @param string $id
         * @return void
         */
        public function getOne($id)
        {
                $this->validate(['id' => $id], [
                        'id' => 'mongo_id:value|exists:broadcasts,_id',
                ]);

                // Create a cache key
                $cacheKey = sprintf('broadcast:id_%s', $id);

                $model = Cache::remember($cacheKey, $this->cacheDuration, function() use ($id)
                {
                        return BroadcastModel::where('enabled', true)->where('_id', $id)
                                                ->get()
                                                ->first();
                });

                Response::send(200, $model);
        }

}
