<?php

return [
    'base_url' => env('OTP_SERVICE_BASE_URL', ''),
    'client_id' => env('OTP_SERVICE_CLIENT_ID', ''),
    'client_secret' => env('OTP_SERVICE_CLIENT_SECRET', ''),
    'redirect_uri' => env('OTP_SERVICE_REDIRECT_URL', ''),
    'certs_url' => env('OTP_SERVICE_CERTS_URL', ''),
];