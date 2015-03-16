<?php namespace OpenDRadio\Rest\Facades;

use SlimFacades\Facade;

class SentryFacade extends Facade {

	/**
	 * Get the registered name of the component.
	 *
	 * @return string
	 */
        protected static function getFacadeAccessor()
        {
                return 'sentry';
        }

}