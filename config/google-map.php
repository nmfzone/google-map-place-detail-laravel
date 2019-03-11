<?php

use Carbon\Carbon;

return [

    'api_key' => env('GOOGLE_MAPS_API_KEY', 'key'),

    'place_details' => [

        'url' => 'https://maps.googleapis.com/maps/api/place/details',

        'lang' => null,

        'max_retry' => 3,

        'cache_duration' => Carbon::now()->addYears(10),

    ],

];
