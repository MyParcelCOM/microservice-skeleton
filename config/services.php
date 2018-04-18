<?php

return [

    /**
     * This is where you build your array of carrier credentials.
     * The format is always key => env(). You can then store the
     * actual credentials in your .env file.
     */
    'carrier_credentials' => [
        'api_key' => env('CARRIER_API_KEY'),
    ],

];
