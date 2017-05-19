<?php
return [
    /*
    |--------------------------------------------------------------------------
    | Bittrex authentication
    |--------------------------------------------------------------------------
    |
    | Authentication key and secret for bittrex API.
    |
     */

    'auth' => [
        'key'    => env('BITTREX_KEY', ''),
        'secret' => env('BITTREX_SECRET', ''),
    ],

    /*
    |--------------------------------------------------------------------------
    | Api URLS
    |--------------------------------------------------------------------------
    |
    | Urls for Bittrex public, market and account API
    |
     */

    'urls' => [
        'public'  => 'https://bittrex.com/api/v1.1/public/',
        'market'  => 'https://bittrex.com/api/v1.1/market/',
        'account' => 'https://bittrex.com/api/v1.1account/',
    ],

];
