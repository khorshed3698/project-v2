<?php

return [

    /*
    |--------------------------------------------------------------------------
    | API Keys
    |--------------------------------------------------------------------------
    |
    | Set the public and private API keys as provided by reCAPTCHA.
    |
    | In version 2 of reCAPTCHA, public_key is the Site key,
    | and private_key is the Secret key.
    |
    , '6Lcf0hETAAAAAMhWAS3BsL5CHLJDeL_b865xTBc7'
    , '6Lcf0hETAAAAAFSCj09GSRc5tKj0jA3sQ1Su8f20'
    */
    'public_key'     => env('RECAPTCHA_PUBLIC_KEY', '6LeZySUTAAAAAJPrGc0dRe2_pTnqt2ukM90OVBXA'),
    'private_key'    => env('RECAPTCHA_PRIVATE_KEY', '6LeZySUTAAAAAE36UOR9xTH0ukOw9infVFON9t5V'),
    'site_url'    => env('RECAPTCHA_SITE_URL', 'https://www.google.com/recaptcha/api/siteverify'),

    /*
    |--------------------------------------------------------------------------
    | Template
    |--------------------------------------------------------------------------
    |
    | Set a template to use if you don't want to use the standard one.
    |
    */
    'template'    => '',

    /*
    |--------------------------------------------------------------------------
    | Driver
    |--------------------------------------------------------------------------
    |
    | Determine how to call out to get response; values are 'curl' or 'native'.
    | Only applies to v2.
    |
    */
    'driver'      => 'curl',

    /*
    |--------------------------------------------------------------------------
    | Options
    |--------------------------------------------------------------------------
    |
    | Various options for the driver
    |
    */
    'options'     => [

        'curl_timeout' => 1,

    ],

    /*
    |--------------------------------------------------------------------------
    | Version
    |--------------------------------------------------------------------------
    |
    | Set which version of ReCaptcha to use.
    |
    */

    'version'     => 2,

];
