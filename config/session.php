<?php

$config['cookies'] = [
        
        /*
        |--------------------------------------------------------------------------
        | Session Lifetime
        |--------------------------------------------------------------------------
        |
        | Determines the lifetime of HTTP cookies created by the Slim application.
        | If this is an integer, it must be a valid UNIX timestamp at which
        | the cookie expires. If this is a string, it is parsed by the
        | strtotime() function to extrapolate a valid UNIX timestamp at which
        | the cookie expires.
        |
        */

        'expires'       => '10 minutes',

        /*
        |--------------------------------------------------------------------------
        | Session Cookie Path
        |--------------------------------------------------------------------------
        |
        | The session cookie path determines the path for which the cookie will
        | be regarded as available. Typically, this will be the root path of
        | your application but you are free to change this when necessary.
        |
        */

        'path'          => '/',

        /*
        |--------------------------------------------------------------------------
        | Session Cookie Name
        |--------------------------------------------------------------------------
        |
        | Here you may change the name of the cookie used to identify a session
        | instance by ID. The name specified here will get used every time a
        | new session cookie is created by the framework for every driver.
        |
        */

        'name'          => '',

        /*
        |--------------------------------------------------------------------------
        | Session Cookie Domain
        |--------------------------------------------------------------------------
        |
        | Here you may change the domain of the cookie used to identify a session
        | in your application. This will determine which domains the cookie is
        | available to in your application. A sensible default has been set.
        |
        */

        'domain'        => '',

        /*
        |--------------------------------------------------------------------------
        | HTTPS Only Cookies
        |--------------------------------------------------------------------------
        |
        | By setting this option to true, session cookies will only be sent back
        | to the server if the browser has a HTTPS connection. This will keep
        | the cookie from being sent to you if it can not be done securely.
        |
        */

        'secure'        => true,

        /*
        |--------------------------------------------------------------------------
        | HTTP Only Cookies
        |--------------------------------------------------------------------------
        |
        | By setting this option to true, session cookies will only be sent back
        | to the server if the browser has a HTTP connection. This will keep
        | the cookie from being sent to you if it can not be done securely.
        |
        */

        'httponly'      => false,

        /*
        |--------------------------------------------------------------------------
        | Session Cookie Secret Key
        |--------------------------------------------------------------------------
        |
        | The secret key used for cookie encryption. You should change this
        | setting if you use encrypted HTTP cookies in your Slim application.
        |
        */

        'secret_key'    => '',

        /*
        |--------------------------------------------------------------------------
        | Session Cookie Cipher
        |--------------------------------------------------------------------------
        |
        | The mcrypt cipher used for HTTP cookie encryption.
        | @see http://php.net/manual/en/mcrypt.ciphers.php
        |
        */

        'cipher'        => MCRYPT_RIJNDAEL_256,

        /*
        |--------------------------------------------------------------------------
        | Session Cookie Cipher Mode
        |--------------------------------------------------------------------------
        |
        | The mcrypt cipher mode used for HTTP cookie encryption.
        | @see http://php.net/manual/en/mcrypt.constants.php
        |
        */

        'cipher_mode'   => MCRYPT_MODE_CBC,

];
