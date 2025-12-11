<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Application Debug Mode
    |--------------------------------------------------------------------------
    |
    | When your application is in debug mode, detailed error messages with
    | stack traces will be shown on every error that occurs within your
    | application. If disabled, a simple generic error page is shown.
    |
    */

    'debug' => env('APP_DEBUG', false),

    /*
    |--------------------------------------------------------------------------
    | Application URL
    |-------------------------------------------------------------------------
    |
    | This URL is used by the console to properly generate URLs when using
    | the Artisan command line tool. You should set this to the root of
    | your application so that it is used when running Artisan tasks.
    |
    */

    'url' => 'http://localhost',

    /*
    |--------------------------------------------------------------------------
    | Application Timezone
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default timezone for your application, which
    | will be used by the PHP date and date-time functions. We have gone
    | ahead and set this to a sensible default for you out of the box.
    |
    */

    'timezone' => 'Asia/Dhaka',

    /*
    |--------------------------------------------------------------------------
    | Application Locale Configuration
    |--------------------------------------------------------------------------
    |
    | The application locale determines the default locale that will be used
    | by the translation service provider. You are free to set this value
    | to any of the locales which will be supported by the application.
    |
    */
    'locale' => 'en',
    // 'locale' => Session::get('lang'),
    'locales' => ['en' => 'English', 'bn' => 'Bangla'],

    /*
    |--------------------------------------------------------------------------
    | Application Fallback Locale
    |--------------------------------------------------------------------------
    |
    | The fallback locale determines the locale to use when the current one
    | is not available. You may change the value to correspond to any of
    | the language folders that are provided through your application.
    |
    */

    'fallback_locale' => 'en',

    /*
    |--------------------------------------------------------------------------
    | Encryption Key
    |--------------------------------------------------------------------------
    |
    | This key is used by the Illuminate encrypter service and should be set
    | to a random, 32 character string, otherwise these encrypted strings
    | will not be safe. Please do this before deploying an application!
    |
    */

    'key' => env('APP_KEY', 'SomeRandomString'),

    'cipher' => 'AES-256-CBC',

    /*
    |--------------------------------------------------------------------------
    | Logging Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure the log settings for your application. Out of
    | the box, Laravel uses the Monolog PHP logging library. This gives
    | you a variety of powerful log handlers / formatters to utilize.
    |
    | Available Settings: "single", "daily", "syslog", "errorlog"
    |
    */

    'log' => env('APP_LOG', 'daily'),
    'log_max_files' => 30,

    /*
    |--------------------------------------------------------------------------
    | OSS-PID Configuration
    |--------------------------------------------------------------------------
    */

    'osspid_client_id' => env('osspid_client_id', ''),
    'osspid_client_secret_key' => env('osspid_client_secret_key', ''),
    'osspid_base_url' => env('osspid_base_url', ''),
    'osspid_base_url_ip' => env('osspid_base_url_ip', ''),
    'osspid_auth_url' => env('osspid_base_url', '').'/osspid-client/auth',


    /*

   |--------------------------------------------------------------------------
   | OSS-PID LOG Configuration
   |--------------------------------------------------------------------------
   */

    'osspid_log_grant_type' => env('osspid_log_grant_type', ''),
    'osspid_log_my_client_id' => env('osspid_log_client_id', ''),
    'osspid_log_my_secret_key' => env('osspid_log_my_secret_key', ''),
    'osspid_log_content_type' => env('osspid_log_content_type', ''),
    'osspid_log_token_url' => env('osspid_log_token_url', ''),
    'osspid_log_data_url' => env('osspid_log_data_url', ''),

    /*
    |--------------------------------------------------------------------------
    | NID API Configuration
    |
    | All of the default values are taken from LIVE .env
    |--------------------------------------------------------------------------
    */

    /* old zaman vai
    'NID_SERVER' => env('NID_SERVER', 'http://192.168.151.115:8091/bidanid'),
    'NID_SERVER_CLIENT_ID' => env('NID_SERVER_CLIENT_ID', 'BIDA'),
    'NID_SERVER_REG_KEY' => env('NID_SERVER_REG_KEY', 'A86471D7-941A-4350-A0C2-CC30F5205E92'),
    */

    'NID_TOKEN_SERVER' => env('NID_TOKEN_SERVER', ''),
    'NID_SERVER' => env('NID_SERVER', ''),
    'NID_SERVER_CLIENT_ID' => env('NID_SERVER_CLIENT_ID', ''),
    'NID_SERVER_REG_KEY' => env('NID_SERVER_REG_KEY', ''),
    'NID_GRANT_TYPE' => env('NID_GRANT_TYPE', ''),


    /*
    |--------------------------------------------------------------------------
    | e-TIN API Configuration
    |
    | All of the default values are taken from LIVE .env
    |--------------------------------------------------------------------------
    */
    'ETIN_SERVER' => env('ETIN_SERVER', ''),
    'ETIN_USERNAME' => env('ETIN_USERNAME', ''),
    'ETIN_PASSWORD' => env('ETIN_PASSWORD', ''),

    /*
    |--------------------------------------------------------------------------
    | DB Configuration
    |--------------------------------------------------------------------------
    */
    'db_host' => env('DB_HOST'),
    'db_database' => env('DB_DATABASE'),
    'db_username' => env('DB_USERNAME'),
    'db_password' => env('DB_PASSWORD'),

    /*
    |--------------------------------------------------------------------------
    | Robi API for sending SMS Configuration
    |--------------------------------------------------------------------------
    */
    'robi_sms_username' => env('ROBI_SMS_USERNAME', ''),
    'robi_sms_password' => env('ROBI_SMS_PASSWORD', ''),
    'robi_sms_from' => env('ROBI_SMS_FROM', ''),

    /*
    |--------------------------------------------------------------------------
    | PDF MODIFIER Configuration
    |--------------------------------------------------------------------------
    */
    'pdf_modifier_username' => env('PDF_MODIFIER_USERNAME', ''),
    'pdf_modifier_password' => env('PDF_MODIFIER_PASSWORD', ''),
    'pdf_modifier_client_id' => env('PDF_MODIFIER_CLIENT_ID', ''),

    /*
    |--------------------------------------------------------------------------
    | Other Configuration
    |--------------------------------------------------------------------------
    */

    'env' => env('APP_ENV', ''),
    'domain' => env('DOMAIN_NAME', ''),
    'managed_by' => 'Business Automation Ltd.',
    'managed_by_url' => 'https://www.ba-systems.com',

    'project_name' => env('PROJECT_NAME', ''),
    'project_code' => env('project_code', ''),
    'project_root' => env('PROJECT_ROOT', ''),
    'project_root_ip' => env('PROJECT_ROOT_IP', ''),

    'oss_code' => env('OSS_CODE', ''),

    'server_type' => env('server_type', ''),

    'SOCIAL_WIDGET_ID' => env('SOCIAL_WIDGET_ID', ''),
    'SOCIAL_WIDGET_SITE_URL' => env('SOCIAL_WIDGET_SITE_URL', ''),

    'payment_api_url' => env('PAYMENT_API_URL', ''),
    'rjsc_base_url' => env('RJSC_BASE_URL', ''),
    'http_proxy' => env('HTTP_PROXY', ''),
    'old_training_domain' => env('OLD_TRAINING_DOMAIN', ''),
    'board_meeting_img' => env('BOARD_MEETING_IMG', ''),

    /*
    |--------------------------------------------------------------------------
    | Email & SMS Webservice Configuration
    |--------------------------------------------------------------------------
    */

    'SMS_API_URL_FOR_TOKEN' => env('SMS_API_URL_FOR_TOKEN', ''),
    'SMS_CLIENT_ID' => env('SMS_CLIENT_ID', ''),
    'SMS_CLIENT_SECRET' => env('SMS_CLIENT_SECRET', ''),
    'SMS_GRANT_TYPE' => env('SMS_GRANT_TYPE', ''),
    'EMAIL_API_URL_FOR_SEND' => env('EMAIL_API_URL_FOR_SEND', ''),
    'EMAIL_FROM_FOR_EMAIL_API' => env('EMAIL_FROM_FOR_EMAIL_API', ''),
    'SMS_API_URL_FOR_SEND' => env('SMS_API_URL_FOR_SEND', ''),


    /*
    |--------------------------------------------------------------------------
    | IRMS API Configuration
    |--------------------------------------------------------------------------
    */

    'CLIENT_ID' => env('CLIENT_ID', ''),
    'CLIENT_SECRET_KEY' => env('CLIENT_SECRET_KEY', ''),
    'IRMS_BASE_URL' => env('IRMS_BASE_URL', ''),


    /*
    |--------------------------------------------------------------------------
    | NID JWT Configuration
    |--------------------------------------------------------------------------
    */

    'NID_JWT_ID' => env('NID_JWT_ID', ''),
    'NID_JWT_SECRET_KEY' => env('NID_JWT_SECRET_KEY', ''),
    'NID_JWT_ENCRYPTION_KEY' => env('NID_JWT_ENCRYPTION_KEY', ''),

    /*
    |--------------------------------------------------------------------------
    | InsightDB API Configuration
    |--------------------------------------------------------------------------
    */

    'insightdb_oauth_token_url' => env('INSIGHTDB_OAUTH_TOKEN_URL', ''),
    'insightdb_oauth_client_id' => env('INSIGHTDB_OAUTH_CLIENT_ID', ''),
    'insightdb_oauth_client_secret' => env('INSIGHTDB_OAUTH_CLIENT_SECRET', ''),
    'insightdb_api_base_url' => env('INSIGHTDB_API_BASE_URL', ''),
    'insightdb_api_timeout' => env('INSIGHTDB_API_TIMEOUT', ''),

    /*
    |--------------------------------------------------------------------------
    | Landing Page API Endpoint
    |--------------------------------------------------------------------------
    */

    'landing_page_public_data' => env('LANDING_PAGE_PUBLIC_DATA', ''),
    'landing_page_notice' => env('LANDING_PAGE_NOTICE', ''),

    /*
    |--------------------------------------------------------------------------
    | ML API Endpoint
    |--------------------------------------------------------------------------
    */

    'ml_grant_type' => env('ML_GRANT_TYPE', ''),
    'ml_suggest_remarks' => env('ML_SUGGEST_REMARKS', ''),
    'ml_text_auto_complete' => env('ML_TEXT_AUTO_COMPLETE', ''),
    'ml_suggest_staus' => env('ML_SUGGEST_STAUS', ''),

    'speech_endpoint' =>  env('SPEECH_ENDPOINT', ''),
    'speech_client_id' =>  env('SPEECH_CLIENT_ID', ''),
    'speech_client_secret' =>  env('SPEECH_CLIENT_SECRET', ''),
    'speech_server_secret' =>  env('SPEECH_SERVER_SECRET', ''),

    /*
    |--------------------------------------------------------------------------
    | BLOCKCHAIN Route
    |--------------------------------------------------------------------------
    */

    'BLOCKCHAIN_BASE_URL' => env('BLOCKCHAIN_BASE_URL', ''),
    'BLOCKCHAIN_DETAILS_URL' => env('BLOCKCHAIN_DETAILS_URL', ''),

    'curlopt_ssl_verifypeer' => env('CURLOPT_SSL_VERIFYPEER', ''),
    'curlopt_ssl_verifyhost' => env('CURLOPT_SSL_VERIFYHOST', ''),
    'support_contact_mobile' => env('SUPPORT_CONTACT_MOBILE', '+8809678771353'),
    'support_contact_email' => env('SUPPORT_CONTACT_EMAIL', 'ossbida@ba-systems.com'),

    /*
    |--------------------------------------------------------------------------
    | Autoload Service Providers
    |--------------------------------------------------------------------------
    |
    | The service providers listed here will be automatically loaded on the
    | request to your application. Feel free to add your own services to
    | this array to grant expanded functionality to your applications.
    |
    */

    'providers' => [

        /*
         * Laravel Framework Service Providers...
         */
        Illuminate\Foundation\Providers\ArtisanServiceProvider::class,
        Illuminate\Auth\AuthServiceProvider::class,
        Illuminate\Broadcasting\BroadcastServiceProvider::class,
        Illuminate\Bus\BusServiceProvider::class,
        Illuminate\Cache\CacheServiceProvider::class,
        Illuminate\Foundation\Providers\ConsoleSupportServiceProvider::class,
        Illuminate\Routing\ControllerServiceProvider::class,
        Illuminate\Cookie\CookieServiceProvider::class,
        Illuminate\Database\DatabaseServiceProvider::class,
        Illuminate\Encryption\EncryptionServiceProvider::class,
        Illuminate\Filesystem\FilesystemServiceProvider::class,
        Illuminate\Foundation\Providers\FoundationServiceProvider::class,
        Illuminate\Hashing\HashServiceProvider::class,
        Illuminate\Mail\MailServiceProvider::class,
        Illuminate\Pagination\PaginationServiceProvider::class,
        Illuminate\Pipeline\PipelineServiceProvider::class,
        Illuminate\Queue\QueueServiceProvider::class,
        Illuminate\Redis\RedisServiceProvider::class,
        Illuminate\Auth\Passwords\PasswordResetServiceProvider::class,
        Illuminate\Session\SessionServiceProvider::class,
        Illuminate\Translation\TranslationServiceProvider::class,
        Illuminate\Validation\ValidationServiceProvider::class,
        Illuminate\View\ViewServiceProvider::class,
        Collective\Html\HtmlServiceProvider::class,
        ArtemSchander\L5Modular\ModuleServiceProvider::class,
        yajra\Datatables\DatatablesServiceProvider::class,
        Jenssegers\Agent\AgentServiceProvider::class,
//        Milon\Barcode\BarcodeServiceProvider::class,
        Maatwebsite\Excel\ExcelServiceProvider::class,
        Arcanedev\LogViewer\LogViewerServiceProvider::class,

        /*
         * Google ReCaptcha
         */
        Greggilbert\Recaptcha\RecaptchaServiceProvider::class,

        /*
         * captcha for login
         */
        Mews\Captcha\CaptchaServiceProvider::class,

        /*
         *  Debugbar
         */
        //Barryvdh\Debugbar\ServiceProvider::class,

        /*
         * Application Service Providers...
         */
        App\Providers\AppServiceProvider::class,
        App\Providers\AuthServiceProvider::class,
        App\Providers\EventServiceProvider::class,
        App\Providers\RouteServiceProvider::class,

//        Jenssegers\Agent\AgentServiceProvider::class,
//        SimpleSoftwareIO\QrCode\QrCodeServiceProvider::class,
        /*
         * Google login service providers
         */
        //Laravel\Socialite\SocialiteServiceProvider::class,
//        TinyAda\RSA\RSAServiceProvider::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Class Aliases
    |--------------------------------------------------------------------------
    |
    | This array of class aliases will be registered when this application
    | is started. However, feel free to register as many as you wish as
    | the aliases are "lazy" loaded so they don't hinder performance.
    |
    */

    'aliases' => [

        'App' => Illuminate\Support\Facades\App::class,
        'Artisan' => Illuminate\Support\Facades\Artisan::class,
        'Auth' => Illuminate\Support\Facades\Auth::class,
        'Blade' => Illuminate\Support\Facades\Blade::class,
        'Bus' => Illuminate\Support\Facades\Bus::class,
        'Cache' => Illuminate\Support\Facades\Cache::class,
        'Config' => Illuminate\Support\Facades\Config::class,
        'Cookie' => Illuminate\Support\Facades\Cookie::class,
        'Crypt' => Illuminate\Support\Facades\Crypt::class,
        'DB' => Illuminate\Support\Facades\DB::class,
        'Eloquent' => Illuminate\Database\Eloquent\Model::class,
        'Event' => Illuminate\Support\Facades\Event::class,
        'File' => Illuminate\Support\Facades\File::class,
        'Gate' => Illuminate\Support\Facades\Gate::class,
        'Hash' => Illuminate\Support\Facades\Hash::class,
        'Input' => Illuminate\Support\Facades\Input::class,
        'Lang' => Illuminate\Support\Facades\Lang::class,
        'Log' => Illuminate\Support\Facades\Log::class,
        'Mail' => Illuminate\Support\Facades\Mail::class,
        'Password' => Illuminate\Support\Facades\Password::class,
        'Queue' => Illuminate\Support\Facades\Queue::class,
        'Redirect' => Illuminate\Support\Facades\Redirect::class,
        'Redis' => Illuminate\Support\Facades\Redis::class,
        'Request' => Illuminate\Support\Facades\Request::class,
        'Response' => Illuminate\Support\Facades\Response::class,
        'Route' => Illuminate\Support\Facades\Route::class,
        'Schema' => Illuminate\Support\Facades\Schema::class,
        'Session' => Illuminate\Support\Facades\Session::class,
        'Storage' => Illuminate\Support\Facades\Storage::class,
        'URL' => Illuminate\Support\Facades\URL::class,
        'Validator' => Illuminate\Support\Facades\Validator::class,
        'View' => Illuminate\Support\Facades\View::class,
        'Form' => Collective\Html\FormFacade::class,
        'Html' => Collective\Html\HtmlFacade::class,
        'Datatables' => yajra\Datatables\Datatables::class,
        'Recaptcha' => Greggilbert\Recaptcha\Facades\Recaptcha::class,
        'Captcha' => Mews\Captcha\Facades\Captcha::class,
        'CommonFunction' => 'App\Libraries\CommonFunction',
        'Encryption' => 'App\Libraries\Encryption',
        'ACL' => 'App\Libraries\ACL',
        'Socialite' => Laravel\Socialite\Facades\Socialite::class,
        //'Debugbar' => Barryvdh\Debugbar\Facade::class,
//        'RSA' =>  TinyAda\RSA\RSA::class,
        'Agent' => Jenssegers\Agent\Facades\Agent::class,
        'QrCode' => SimpleSoftwareIO\QrCode\Facades\QrCode::class,
//        'DNS1D' => Milon\Barcode\Facades\DNS1DFacade::class,
//        'DNS2D' => Milon\Barcode\Facades\DNS2DFacade::class,
        'Excel' => Maatwebsite\Excel\Facades\Excel::class,

    ],

];