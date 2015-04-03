<?php

$config['messenger'] = [

        /*
        |--------------------------------------------------------------------------
        | Services
        |--------------------------------------------------------------------------
        |
        | The secret key used for cookie encryption. You should change this
        | setting if you use encrypted HTTP cookies in your Slim application.
        |
        */

        'services' => [
                
                'mozilla' => [
                        'api' => 'https://updates.push.services.mozilla.com'
                ]

        ],

];
