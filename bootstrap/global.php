<?php

/*
|--------------------------------------------------------------------------
| Application Not Found Handler
|--------------------------------------------------------------------------
|
| The Not Found handler will be invoked when a matching route is
| not found for the current HTTP request. This method acts as both
| a getter and a setter.
|
*/

App::notFound(function() {

    Response::send(404);
});

/*
|--------------------------------------------------------------------------
| Application Error Handler
|--------------------------------------------------------------------------
|
| The Error handler will be invoked when an exception or error occurs.
| This method acts as both a getter and a setter.
|
*/

App::error(function (\Exception $e) {

    Response::send($e->getCode(), null, $e->getMessage());
});
