<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Session;

Route::group(['module' => 'LoginAndOthers', 'middleware' => ['XssProtection']], function() {



    // old routes comments start
    // new landing page routes app/Modules/Web/routes.php

    // Route::get('/login', 'LoginController@index');
    // Route::get('/login/{lang}', 'LoginController@index');
    // old routes comments end

    /*
     * Check login process
     */
//        Route::post('/login/check', 'LoginController@check');
    Route::post('/login/load-login-form', 'LoginController@loadLoginForm');
    Route::post('/login/load-login-otp-form', 'LoginController@loadLoginOtpForm');
    Route::post('/login/otp-login-email-validation-with-token-provide', 'LoginController@otpLoginEmailValidationWithTokenProvide');
    Route::post('/login/otp-login-check', 'LoginController@checkOtpLogin');
    Route::get('get-routes', 'LoginController@allClassRoute');
    Route::post('/login/type_wise_details', 'LoginController@type_wise_details');



    //OSSPID LOGIN and signup
//    Route::get('osspid-callback', 'OSSPIDLoginController@osspidCallback');
//    Route::get('osspid_signUp', 'OSSPIDLoginController@osspid_signUp');
//    Route::get('osspid_signUpOne', 'OSSPIDLoginController@osspidSignUpOne');
//    Route::patch('osspid/store', 'OSSPIDLoginController@OsspidStore');
//    Route::get('osspid/logout', 'OSSPIDLoginController@osspidLogout');


    Route::get('re-captcha', 'LoginController@reCaptcha');

    // Route::get('/logout', 'OSSPIDLoginController@osspidLogout');

    // KeyCloak
    Route::get('keycloak/callback', 'KeycloakController@callback');
    Route::get('/logout', 'KeycloakController@logout');

    Route::post('/api/new-job', 'ApiController@newJob');
    Route::post('/api/action/new-job', 'ApiController@actionNewJob');

    Route::get('/single-notice/{id}','LoginController@singleNotice');


    // Articles
//    Route::get('articles/about-bida-quick-service-portal', 'ArticlesController@aboutBidaQuickServicePortal');
//    Route::get('articles/one-stop-service', 'ArticlesController@aboutOneStopService');
//    Route::get('articles/about-osspid', 'ArticlesController@aboutOsspid');
//    Route::get('articles/privacy-statement', 'ArticlesController@privacyStatement');
//    Route::get('articles/available-services', 'ArticlesController@availableOnlineServices');
//    Route::get('articles/document-and-downloads', 'ArticlesController@documentAndDownloads');
//    Route::get('articles/investment-promotion-agency-bd', 'ArticlesController@investmentPromotionAgencyBd');
//    Route::get('articles/certificate-issuing-agency-bd', 'ArticlesController@certificateIssuingAgencyBbd');
//    Route::get('articles/utility-service-provider', 'ArticlesController@utilityServiceProvider');
//    Route::get('articles/support', 'ArticlesController@support');
//    Route::get('articles/business-sector', 'ArticlesController@businessSector');
//    Route::get('articles/bida', 'ArticlesController@aboutBida');
//    Route::get('articles/terms-of-services', 'ArticlesController@termsOfServices');

    Route::get('/log', '\Srmilon\LogViewer\LogViewerController@index');

    /*
     * API FOR BIDA NEW LANDING PAGE
     */
    // Route::post('/bida-oss-landing/dataSet', 'LoginController@dynamicDataSets');
});


//Route::get('/dateCalculate', 'GoogleLoginController@dateCalculate');
//Route::get('/testDate', 'GoogleLoginController@testDate');
//Route::get('/dateCalculateImran', 'GoogleLoginController@dateCalculateImran');


/*
 * Google Login routes
 */
//Route::get('auth/google', 'GoogleLoginController@redirectToProvider');
//Route::get('auth/google/callback', 'GoogleLoginController@handleProviderCallback');
//
//Route::get('oauth/google/callback', 'GoogleLoginController@handleProviderCallback');





/*
 /*
 * Google Login routes
 */
//Route::get('auth/facebook', 'FacebookLoginController@redirectToProvider');
//Route::get('auth/facebook/callback', 'FacebookLoginController@handleProviderCallback');


Route::get('/run-artisan-command-clear', function(){
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    Artisan::call('route:clear');
    Artisan::call('config:cache');
    return redirect()->back();
});

Route::get('/run-artisan-command-up', function(){
    Artisan::call('up');
    return redirect()->back();
});


/*
 *
 * For language changes
 */
Route::get('language/{lan}', function ($lang) {
    App::setLocale($lang);
    Session::put('lang', $lang);
    \App\Modules\Users\Models\UsersModel::setLanguage($lang);
    return redirect()->back();
});




Route::get('language/outside/{lan}', function ($lang) {

    App::setLocale($lang);
    Session::set('lang', $lang);
    return redirect('login/'.$lang);
});


Route::get('/usermanual-getdata','LoginController@getUserManual');
Route::get('cron/signature/script/{id}', 'SignEncodeController@compressSignGeneration');
Route::get('cron/signature/script', 'SignEncodeController@compressSignGenerationAll');

foreach (glob(__DIR__ . '/routes/*.php') as $route_file) {
    require_once $route_file;
}
