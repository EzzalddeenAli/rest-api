<?php

$config['cache'] = [

        /*
        |--------------------------------------------------------------------------
        | Default Cache Driver
        |--------------------------------------------------------------------------
        |
        | This option controls the default cache "driver" that will be used when
        | using the Caching library. Of course, you may use other drivers any
        | time you wish. This is the default when another is not specified.
        |
        | Supported: "apc", "memached" or "redis"
        |
        */

        'default' => 'redis',

        /*
        |--------------------------------------------------------------------------
        | Cache Stores
        |--------------------------------------------------------------------------
        |
        | Here you may define all of the cache "stores" for your application as
        | well as their drivers. You may even define multiple stores for the
        | same cache driver to group types of items stored in your caches.
        |
        */
    
        'stores.redis' => [
            
                'driver' => 'redis',

                'connection' => 'default',
    
        ],

        /*
        |--------------------------------------------------------------------------
        | Cache Key Prefix
        |--------------------------------------------------------------------------
        |
        | When utilizing a RAM based store such as APC or Memcached, there might
        | be other applications utilizing the same cache. So, we'll specify a
        | value to get prefixed to all our keys so we can avoid collisions.
        |
        */

        'prefix' => 'api_dradio',

];
