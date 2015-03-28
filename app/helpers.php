<?php

if (!function_exists('slim'))
{
	/**
	 * Get the available container instance.
	 *
	 * @param  string  $key
	 * @return mixed|\Slim\Slim
	 */
	function slim($key = '')
	{
		if (is_null($key)) return App::container;
		return App::make($key);
	}
}

if (!function_exists('base_path'))
{
	/**
	 * Get the path to the base of the install.
	 *
	 * @param  string  $path
	 * @return string
	 */
	function base_path($path = '')
	{
		return slim('path.base').($path ? '/'.$path : $path);
	}
}

if (!function_exists('storage_path'))
{
	/**
	 * Get the path to the storage folder.
	 *
	 * @param  string  $path
	 * @return string
	 */
	function storage_path($path = '')
	{
		return slim('path.storage').($path ? '/'.$path : $path);
	}
}
