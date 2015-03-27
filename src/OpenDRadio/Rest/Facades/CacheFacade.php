<?php namespace OpenDRadio\Rest\Facades;

use SlimFacades\Facade;

class CacheFacade extends Facade {

	/**
	 * Get the registered name of the component.
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor() { return 'cache'; }

}
