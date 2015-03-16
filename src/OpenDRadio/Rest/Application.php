<?php namespace OpenDRadio\Rest;

use Slim\Slim;
use SlimFacades\Facade;
use SlimServices\ServiceManager;
use Cartalyst\Sentry\Sentry;
use Cartalyst\Sentry\Cookies\NativeCookie;
use Cartalyst\Sentry\Sessions\NativeSession;
use Cartalyst\Sentry\Users\Eloquent\Provider as UserProvider;
use Cartalyst\Sentry\Groups\Eloquent\Provider as GroupProvider;
use Cartalyst\Sentry\Hashing\BcryptHasher;
use Cartalyst\Sentry\Throttling\Eloquent\Provider as ThrottleProvider;

class Application {

        /**
         * Slim instance.
         *
         * @var \Slim\Slim
         * @access protected
         */
        protected $slim;

        /**
         * The application configuration.
         *
         * @var array
         * @access protected
         */
        protected $config;

        /**
         * Constructor.
         *
         * @param array $config            
         */
        public function __construct(array $config = array())
        {
                $this->slim = new Slim($config['slim']);

                // Set the default response headers
                $this->slim->response()->header('Content-Type', 'application/json; charset=utf-8');

                $this->config = $config;
        }

        /**
         * Boot up the slim facade accessor.
         *
         * @param array $config            
         * @return void
         */
        public function bootFacade($config)
        {
                Facade::setFacadeApplication($this->slim);
                Facade::registerAliases($config);
        }

        /**
         * Boot up the slim services.
         *
         * @param array $config            
         * @return void
         */
        public function bootProviders($config)
        {
                $services = new ServiceManager($this->slim);
                $services->registerServices($config);
        }

        /**
         * Boot up Sentry authorization provider.
         *
         * @param array $config            
         * @return void
         */
        public function bootSentry($config)
        {
                $slim = $this->slim;

                $this->slim->container->singleton('sentry', function () use($slim, $config)
                {
                        $hasherProvider = $this->hasherProviderFactory();
                        $userProvider = $this->userProviderFactory($hasherProvider, $config);
                        $groupProvider = $this->groupProviderFactory($config);
                        $throttleProvider = $this->throttleProviderFactory($userProvider, $config);

                        return new Sentry($userProvider, $groupProvider, $throttleProvider, new NativeSession(), new NativeCookie(), $slim->request->getIp());
                });
        }

        /**
         * Boot up the user provider factory.
         *
         * @param array $config            
         * @return \Cartalyst\Sentry\Users\Eloquent\Provider
         */
        protected function userProviderFactory($hasher, $config)
        {
                $model = $config['users']['model'];

                if (method_exists($model, 'setLoginAttributeName'))
                {
                        $loginAttribute = $config['users']['login_attribute'];

                        forward_static_call_array([
                                $model,
                                'setLoginAttributeName'
                        ], [
                                $loginAttribute
                        ]);
                }

                // Define the Group model to use for relationships.
                if (method_exists($model, 'setGroupModel'))
                {
                        $groupModel = $config['groups']['model'];

                        forward_static_call_array([
                                $model,
                                'setGroupModel'
                        ], [
                                $groupModel
                        ]);
                }

                // Define the user group pivot table name to use for relationships.
                if (method_exists($model, 'setUserGroupsPivot'))
                {
                        $pivotTable = $config['user_groups_pivot_table'];

                        forward_static_call_array([
                                $model,
                                'setUserGroupsPivot'
                        ], [
                                $pivotTable
                        ]);
                }

                return new UserProvider($hasher, $model);
        }

        /**
         * Boot up the group provider factory.
         *
         * @param array $config            
         * @return \Cartalyst\Sentry\Groups\Eloquent\Provider
         */
        protected function groupProviderFactory($config)
        {
                $model = $config['groups']['model'];

                // Define the User model to use for relationships.
                if (method_exists($model, 'setUserModel'))
                {
                        $userModel = $config['users']['model'];

                        forward_static_call_array([
                                $model,
                                'setUserModel'
                        ], [
                                $userModel
                        ]);
                }

                // Define the user group pivot table name to use for relationships.
                if (method_exists($model, 'setUserGroupsPivot'))
                {
                        $pivotTable = $config['user_groups_pivot_table'];

                        forward_static_call_array([
                                $model,
                                'setUserGroupsPivot'
                        ], [
                                $pivotTable
                        ]);
                }

                return new GroupProvider($model);
        }

        /**
         * Sentry specific factory, adopted from SentryServiceProvider.
         *
         * @return \Cartalyst\Sentry\Hashing\BcryptHasher
         */
        protected function hasherProviderFactory()
        {
                return new BcryptHasher();
        }

        /**
         * Boot up the throttle provider factory.
         *
         * @param \Cartalyst\Sentry\Users\Eloquent\Provider $userProvider            
         * @param array $config            
         * @return \Cartalyst\Sentry\Throttling\Eloquent\Provider
         */
        protected function throttleProviderFactory($userProvider, $config)
        {
                $model = $config['throttling']['model'];

                $throttleProvider = new ThrottleProvider($userProvider, $model);

                if ($config['throttling']['enabled'] === false)
                {
                        $throttleProvider->disable();
                }

                if (method_exists($model, 'setAttemptLimit'))
                {
                        $attemptLimit = $config['throttling']['attempt_limit'];

                        forward_static_call_array([
                                $model,
                                'setAttemptLimit'
                        ], [
                                $attemptLimit
                        ]);
                }

                if (method_exists($model, 'setSuspensionTime'))
                {
                        $suspensionTime = $config['throttling']['suspension_time'];

                        forward_static_call_array([
                                $model,
                                'setSuspensionTime'
                        ], [
                                $suspensionTime
                        ]);
                }

                // Define the User model to use for relationships.
                if (method_exists($model, 'setUserModel')) {
                        $userModel = $config['users']['model'];

                        forward_static_call_array([
                                $model,
                                'setUserModel'
                        ], [
                                $userModel
                        ]);
                }

                return $throttleProvider;
        }

        /**
         * Run the boot sequence.
         *
         * @return void
         */
        public function boot()
        {
                $this->bootFacade($this->config['aliases']);
                $this->bootProviders($this->config['providers']);
                $this->bootSentry($this->config['sentry']);
        }

        /**
         * Run the Slim application.
         *
         * @return void
         */
        public function run()
        {
                $this->slim->run();
        }

}