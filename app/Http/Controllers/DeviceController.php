<?php namespace App\Http\Controllers;

use Config;
use Request;
use Response;
use Uuid;
use App\Utils\KeyGenerator;
use OpenDRadio\Messenger\Models\DeviceModel;
use OpenDRadio\Messenger\Models\EndpointModel;
use OpenDRadio\Messenger\Models\UuidModel;

class DeviceController extends BaseController {

	/**
	 * The key generator implementation.
	 *
	 * @var \App\Utils\KeyGeneratorInterface
	 * @access protected
	 */
	protected $keyGenerator;

	/**
	 * Create a new token repository instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
                Request::headers()->set('content-type','application/json');
		$this->keyGenerator = new KeyGenerator();
	}

        /**
         * The app platforms.
         *
         * @var \Array
         */
        protected $appPlatforms = [
            'firefox',
            'firefox-os',
            'chrome',
            'chrome-os'
        ];

        /**
         * The app versions.
         *
         * @var \Array
         */
        protected $appVersions = [
            '0.1.1.100',
            '0.1.1.200',
        ];

        /**
         * Determine wether to use strong cryptography.
         *
         * @var boolean
	 * @access protected
         */
        protected $useStrongCrypto = true;

        /**
         * Register a new device.
         *
         * @return void
         */
        public function postDevice()
        {
                if(Request::headers()->get('Content-Type') !== 'application/json')
                {
                        Response::send(405, null, 'Invalid content-type header');
                }

                if(null === ($data = json_decode(Request::getBody(), true)))
                {
                        Response::send(405, null, 'Invalid JSON body');
                }

                $this->validate(array_merge($data, [
                        'sharing' => (bool) $data['sharing']
                ]), [
                        'platform' => sprintf('required|string:value|in:%s', implode(',', $this->appPlatforms)),
                        'language' => 'required|string:value',
                        'url' => 'string:value',
                        'registration_id' => 'string:value',
                        'sharing' => 'required|boolean:value',
                        'app_version' => sprintf('required|regex:/^(([0-9]+\.[1-9]+\.[0-9])(\.[1-9][0-9][0-9])?)$/|in:%s', implode(',', $this->appVersions)),
                ]);

                $platform = $data['platform'];
                $language = $data['language'];
                $sharing = $data['sharing'];
                $appVersion = $data['app_version'];

                switch($platform)
                {
                        case 'firefox':
                        case 'firefox-os':
                                $url = urldecode($data['url']);

                                if(!filter_var($url, FILTER_VALIDATE_URL))
                                {
                                        Response::send(405, null, 'Invalid url');
                                }

                                $url = parse_url($url);

                                if(sprintf('%s://%s', $url['scheme'], $url['host']) !== Config::get('messenger.services')['mozilla']['api'])
                                {
                                        Response::send(405, null, 'Push url endpoint not allowed');
                                }

                                if(null !== DeviceModel::where('endpoint.url', $url['path'])->get()->first())
                                {
                                        Response::send(405, null, 'Endpoint has already been registered');
                                }

                                if($sharing)
                                {
                                        //
                                }

                                $endpoint = new EndpointModel([
                                        'type' => EndpointModel::TYPE_MOZILLA,
                                        'url' => $url['path'],
                                        'version' => 1
                                ]);
                        break;
                    
                        case 'chrome':
                        case 'chrome-os':
                                $registrationId = $data['registration_id'];

                                if(null !== DeviceModel::where('endpoint.registration_id', $registrationId))
                                {
                                        Response::send(405, null, 'Endpoint has already been registered');
                                }

                                $endpoint = new EndpointModel([
                                        'type' => Endpoint::TYPE_GOOGLE_CLOUD_MESSAGING,
                                        'registration_id' => $registrationId,
                                        'version' => 0
                                ]);
                        break;
                }

                $key = $this->keyGenerator->create(128);

                $device = new DeviceModel([
                        'platform' => $platform,
                        'token' => $key,
                        'language' => $language,
                        'app_version' => $appVersion
                ]);

                $device->endpoint()->associate($endpoint);

                $device->uuid()->associate(new UuidModel([
                        'value' => Uuid::generate(UuidModel::VERSION_4)->string,
                        'version' => (int) UuidModel::VERSION_4
                ]));

                $device->save();

                Response::send(200, $device);
        }

        /**
         * Update a device.
         *
         * @param string $token
         * @return void
         */
        public function patchDevice($token)
        {
                print_r(123); die;

                Response::send(200, $device);
        }

        /**
         * Delete a device.
         *
         * @param string $token
         * @return void
         */
        public function deleteDevice($token)
        {
                print_r(123); die;

                Response::send(200, $device);
        }

}
