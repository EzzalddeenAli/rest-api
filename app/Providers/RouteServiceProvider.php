<?php namespace App\Providers;

use File;
use Illuminate\Support\ServiceProvider;

class RouteServiceProvider extends ServiceProvider{

	/**
	 * Define your route model bindings, pattern filters, etc.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->registerRoutes();
	}

	/**
	 * Register the application routes.
	 *
	 * @return void
	 */
	public function registerRoutes()
	{
                $files = File::glob(realpath(base_path('resources/routes')) . '/*.php');
    
                foreach ($files as $file)
                {
                        require $file;
                }
	}

}
