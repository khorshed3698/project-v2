<?php

return [

    /*
    |--------------------------------------------------------------------------
    | This file is for storing the credentials for stakeholder services.
    |--------------------------------------------------------------------------
    */


    /*
    |--------------------------------------------------------------------------
    | Bangladesh Investment Development Authority Agent ID
    |--------------------------------------------------------------------------
    */
    'agent_id' => 3,

    /*
    |--------------------------------------------------------------------------
    | Chittagong Development Authority (CDA)
    |--------------------------------------------------------------------------
    |
    | 1. Occupancy Certificate
    |
    */

    'cda' => [
        'oc' => [
            'client'        => env('CDA_OC_CLIENT', 'bida-client'),
            'secret'        => env('CDA_OC_CLIENT_SECRET', '9dde1f49-f424-4c89-ba5b-113c865f1f9f'),
            'token_url'     => env('CDA_OC_TOKEN_API_URL','https://idp.oss.net.bd/auth/realms/test/protocol/openid-connect/token'),
            'service_url'   => env('CDA_OC_SERVICE_API_URL','https://testapi-k8s.oss.net.bd/api/cda-oca')
        ],
    ],

    'bfcdc' => [
        'exiting' => [
            'client'        => env('BFCDC_EXISTING_CLIENT', 'bida-client'),
            'secret'        => env('BFCDC_EXISTING_CLIENT_SECRET', '9dde1f49-f424-4c89-ba5b-113c865f1f9f'),
            'token_url'     => env('BFCDC_EXISTING_TOKEN_API_URL','https://idp.oss.net.bd/auth/realms/test/protocol/openid-connect/token'),
            'service_url'   => env('BFCDC_EXISTING_SERVICE_API_URL','https://testapi-k8s.oss.net.bd/api/fire-enoc')
        ],
        'proposed' => [
            'client'        => env('BFCDC_PROPOSED_CLIENT', 'bida-client'),
            'secret'        => env('BFCDC_PROPOSED_CLIENT_SECRET', '9dde1f49-f424-4c89-ba5b-113c865f1f9f'),
            'token_url'     => env('BFCDC_PROPOSED_TOKEN_API_URL','https://idp.oss.net.bd/auth/realms/test/protocol/openid-connect/token'),
            'service_url'   => env('BFCDC_PROPOSED_SERVICE_API_URL','https://testapi-k8s.oss.net.bd/api/fire-enoc')
        ],
    ],

    'ml' => [
        'client'        => env('ML_CLIENT', 'bida-client'),
        'secret'        => env('ML_CLIENT_SECRET', '9dde1f49-f424-4c89-ba5b-113c865f1f9f'),
        'token_url'     => env('ML_TOKEN_API_URL','https://idp.oss.net.bd/auth/realms/test/protocol/openid-connect/token'),
        'service_url'   => env('ML_SERVICE_API_URL','https://testapi-k8s.oss.net.bd/api/emutation')
    ],

    'constant' => [
        'bida_token_url'  => env('bida_idp_url', 'https://idp.oss.net.bd/auth/realms/test/protocol/openid-connect/token'),
        'bida_client_id' => 'bida-client',
        'bida_client_secret' => '9dde1f49-f424-4c89-ba5b-113c865f1f9f',
    ],

];