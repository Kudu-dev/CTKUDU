<?php

return [

    /*
    |--------------------------------------------------------------------------
    | CrunchTime API Connection
    |--------------------------------------------------------------------------
    */

    'base_url' => env('CTKUDU_BASE_URL', 'https://webservices.net-chef.com/'),
    'tokens' => [
        'location' => env('CTKUDU_TOKEN_LOCATION'),
        'uom' => env('CTKUDU_TOKEN_UOM'),
        'product' => env('CTKUDU_TOKEN_PRODUCT'),
        'company_product' => env('CTKUDU_TOKEN_COMPANY_PRODUCT'),
        'inventory' => env('CTKUDU_TOKEN_INVENTORY'),
        'recipe' => env('CTKUDU_TOKEN_RECIPE'),
        'purchaseorder' => env('CTKUDU_TOKEN_PURCHASEORDER'),
    ],
    'sitename' => env('CTKUDU_SITENAME'),
    'userid' => env('CTKUDU_USERID'),
    'password' => env('CTKUDU_PASSWORD'),
    'traceId' => env('CTKUDU_TRACEID'),

    /*
    |--------------------------------------------------------------------------
    | HTTP Settings
    |--------------------------------------------------------------------------
    */

    'timeout' => (int)env('CTKUDU_TIMEOUT', 60),
    'connect_timeout' => (int)env('CTKUDU_CONNECT_TIMEOUT', 10),

    /*
    |--------------------------------------------------------------------------
    | Retry Settings
    |--------------------------------------------------------------------------
    */

    'retry' => [
        'times' => (int)env('CTKUDU_RETRY_TIMES', 3),
        'sleep' => (int)env('CTKUDU_RETRY_SLEEP', 500),
    ],

    /*
    |--------------------------------------------------------------------------
    | Logging
    |--------------------------------------------------------------------------
    */

    'logging' => [
        'enabled' => (bool)env('CTKUDU_LOGGING', false),
        'channel' => env('CTKUDU_LOG_CHANNEL'),
    ],

];
