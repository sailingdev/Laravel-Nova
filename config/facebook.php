<?php

return [
    'marketing' => [ 
        'app_id' => env('FB_MARKETING_APP_ID', null),
        'client_token' => env('FB_MARKETING_CLIENT_TOKEN', null),
        'app_secret' => env('FB_MARKETING_APP_SECRET', null),
        'rd_app_access_token' => env('RD_FB_MARKETING_APP_ACCESS_TOKEN', null),
        'tt_app_access_token' => env('TT_FB_MARKETING_APP_ACCESS_TOKEN', null),
        'account_id' => env('FB_MARKETING_ACCOUNT_ID', null)
    ],
];
