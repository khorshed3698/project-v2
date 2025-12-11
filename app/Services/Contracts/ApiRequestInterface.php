<?php

namespace App\Services\Contracts;

interface ApiRequestInterface
{
    public function makeRequest( $baseUrl,  $endpoint,  $method,  $clientId,  $clientSecret,  $data = null);
}
