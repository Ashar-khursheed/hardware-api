<?php

return [
    'api_key' => env('SHIPSTATION_API_KEY', ''),
    'api_secret' => env('SHIPSTATION_API_SECRET', ''),
    'account_email' => env('SHIPSTATION_ACCOUNT_EMAIL', ''),
    'from_postal_code' => env('SHIPSTATION_FROM_POSTAL_CODE', '90210'),
    'from_state' => env('SHIPSTATION_FROM_STATE', 'CA'),
    'from_country' => env('SHIPSTATION_FROM_COUNTRY', 'US'),
    'api_url' => env('SHIPSTATION_API_URL', 'https://ssapi.shipstation.com'),
];
