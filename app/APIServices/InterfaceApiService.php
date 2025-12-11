<?php


namespace App\APIServices;


interface InterfaceApiService
{
    public function getToken(array $clientData);
    public function generateToken($clientData, int $api_info_id, string $client_encryption_key);
    public function validateToken(array $apache_request_headers, string $encryption_key);

}