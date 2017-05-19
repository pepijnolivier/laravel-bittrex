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
        'public'  => 'https://bittrex.com/api/' . config('bittrex.api.version') . '/public/',
        'market'  => 'https://bittrex.com/api/' . config('bittrex.api.version') . '/market/',
        'account' => 'https://bittrex.com/api/' . config('bittrex.api.version') . '/account/',
    ],

    'api'  => [
        'version' => '1.1',
    ],

];
