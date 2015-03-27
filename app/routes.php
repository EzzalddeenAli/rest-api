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
    'token' => '[a-zA-Z0-9_-]{64,128}',
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

Route::group('/v1', function () {

    // Playlist
    Route::get('/playlist', 'OpenDRadio\Rest\Http\Controllers\PlaylistController:all')->name('playlist_url');

    Route::group('/broadcasts', function () {

        // Get latest resources
        Route::get('/latest', 'OpenDRadio\Rest\Http\Controllers\BroadcastController:latest')->name('broadcasts_latest_url');

        // Get many resources by date range
        Route::get('/from/:start/to/:end', 'OpenDRadio\Rest\Http\Controllers\BroadcastController:fromTo')->name('broadcasts_from_to_url');
        
        // Get one resource by id
        Route::get('/id/:_id', 'OpenDRadio\Rest\Http\Controllers\BroadcastController:get')->name('broadcast_url');
    });

    Route::group('/news', function () {

        // Get latest resources
        Route::get('/latest', 'OpenDRadio\Rest\Http\Controllers\NewsController:latest')->name('news_latest_url');

        // Get many resources by date range
        Route::get('/from/:start/to/:end', 'OpenDRadio\Rest\Http\Controllers\NewsController:fromTo')->name('news_from_to_url');

        // Get one resource by id
        Route::get('/id/:_id', 'OpenDRadio\Rest\Http\Controllers\NewsController:get')->name('news_url');
    });

    Route::group('/geo-frequencies', function () {
        
        // Get all resources
        Route::get('/', 'OpenDRadio\Rest\Http\Controllers\GeoFrequencyController:all')->name('geo_frequencies_url');
    });

    // Geolocation service
    Route::get('/geolocation', 'OpenDRadio\Rest\Http\Controllers\GeolocationController:geoClue')->name('geo_clue_url');
});

// Index page (home) route
Route::get('/', 'OpenDRadio\Rest\Http\Controllers\HomeController:welcome');
