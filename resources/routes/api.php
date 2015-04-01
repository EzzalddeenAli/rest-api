<?php

/*
 |--------------------------------------------------------------------------
 | Application Route Constraint Patterns
 |--------------------------------------------------------------------------
 |
 | If you would like a route parameter to always be constrained by a
 | given regular expression, here is where you can register all filters
 | for an application.
 |
 */

Route::conditions(array(
        '_id' => '[0-9a-fA-F]{24}',
        'start' => '(19|20)\d\d[\-\/.](0[1-9]|1[012])[\-\/.](0[1-9]|[12][0-9]|3[01])',
        'end' => '(19|20)\d\d[\-\/.](0[1-9]|1[012])[\-\/.](0[1-9]|[12][0-9]|3[01])',
        'token' => '[a-zA-Z0-9_-]{128,248}',
        'version' => '[0-9]{1,6}'
));

/*
 |--------------------------------------------------------------------------
 |  Application Routes
 |--------------------------------------------------------------------------
 |
 | Here is where you can register all of the routes for an application.
 | It's a breeze. Simply tell Slim the URIs it should respond to
 | and give it the Closure to execute when that URI is requested.
 |
 */

Route::group('/v1', function() {

        App::response()->headers->set('X-OpenDRadio-Media-Type', 'opendradio.v1');

        // Playlist
        Route::get('/playlist', 'App\Http\Controllers\PlaylistController:getIndex')->name('playlist_url');

        Route::group('/broadcasts', function () {

            // Get latest resources
            Route::get('/latest', 'App\Http\Controllers\BroadcastController:getLatest')->name('broadcasts_latest_url');

            // Get many resources by date range
            Route::get('/from/:start/to/:end', 'App\Http\Controllers\BroadcastController:getFromTo')->name('broadcasts_from_to_url');

            // Get one resource by id
            Route::get('/id/:_id', 'App\Http\Controllers\BroadcastController:getOne')->name('broadcast_url');
        });

        Route::group('/news', function () {

            // Get latest resources
            Route::get('/latest', 'App\Http\Controllers\NewsController:getLatest')->name('news_latest_url');

            // Get many resources by date range
            Route::get('/from/:start/to/:end', 'App\Http\Controllers\NewsController:getFromTo')->name('news_from_to_url');

            // Get one resource by id
            Route::get('/id/:_id', 'App\Http\Controllers\NewsController:getOne')->name('news_url');
        });

        Route::group('/geo-frequencies', function () {

            // Get all resources
            Route::get('/', 'App\Http\Controllers\GeoFrequencyController:getIndex')->name('geo_frequencies_url');
        });

        // Geolocation service
        Route::get('/geolocation', 'App\Http\Controllers\GeolocationController:getIndex')->name('geo_clue_url');

        Route::group('/device', function () {

            // Post a resource
            Route::post('/register', 'App\Http\Controllers\DeviceController:postDevice');

            // Patch a resource
            Route::patch('/token/:token', 'App\Http\Controllers\DeviceController:patchDevice');

            // Delete a resource
            Route::delete('/token/:token', 'App\Http\Controllers\DeviceController:deleteDevice');
        });

        Route::group('/notification', function () {

            // Get a resource
            Route::get('/message/token/:token/version/:version', 'App\Http\Controllers\NotificationController:getMessage');
        });
});

// Index page (home) route
Route::get('/', 'App\Http\Controllers\HomeController:getIndex');
