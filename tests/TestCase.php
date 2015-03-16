<?php

use \Slim\Environment;

class TestCase extends PHPUnit_Framework_TestCase {

	/**
	 * The Illuminate application instance.
	 *
	 * @var Illuminate\Foundation\Application
	 */
	protected $app;

	/**
	 * Refresh the application instance.
	 *
	 * @return void
	 */
	protected function refreshApplication()
	{
		$this->app = $this->createApplication();
	}

	public function request($method, $path, $options = array())
	{
		// Capture STDOUT
		ob_start();
	
		// Prepare a mock environment
		Environment::mock(array_merge(array(
		    'REQUEST_METHOD' => $method,
		    'PATH_INFO' => $path,
		    'SERVER_NAME' => 'slim-test.dev',
		), $options));
	
		$app = new \Slim\Slim();
		$this->app = $app;
		$this->request = $app->request();
		$this->response = $app->response();
	
		// Return STDOUT
		return ob_get_clean();
	}
     
	public function get($path, $options = array())
	{
		$this->request('GET', $path, $options);
	}
}