<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        '/api/action/new-job',
        '/api/new-job',
        'api/new-job',

        'spg/callback',
        'spg/callbackM',
        'spg/stack-holder/callback',
        'spg/stack-holder/callbackM',

        'api/v1/get-token',
        'api/v1/check-payment-status',
        'api/sp-ipn',
        'wp-api',

        'irms/api/v1/get-token',
        'irms/api/v1/feedback-request-initiate',
        'irms/api/v1/bida-registration-data-provider',
        'irms/api/v1/get-user-info',
        'irms/api/v1/irn',
        'irms/api/v1/callback',
        'irms/api/v1/company-list',
        'irms/api/v1/company-store',
        'irms/api/v1/company-update',

        'mutation/api/v1/get-token',
        // 'external-test/submission',

    ];
}
