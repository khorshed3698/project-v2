<?php

namespace App\Services;

use App\Services\Contracts\ApiRequestInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

abstract class ApiRequestHandler implements ApiRequestInterface
{
    protected function handleRequest($method, $url, $data = [], $headers='')
    {
        if (!in_array(strtoupper($method), ['GET', 'POST', 'PUT', 'DELETE'])) {
            throw new \Exception("Unsupported HTTP method: $method");
        }
        $token = $headers;
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);

        $headers = [];
        if (!empty($token)) {
            $headers[] = "Authorization: Bearer $token";
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        switch (strtoupper($method)) {
            case 'GET':
                if (!empty($data)) {
                    $queryString = http_build_query($data);
                    curl_setopt($ch, CURLOPT_URL, "$url?$queryString");
                }
                break;

            case 'POST':
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                $headers[] = 'Content-Type: application/json';
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                break;

            case 'PUT':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                $headers[] = 'Content-Type: application/json';
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                break;

            case 'DELETE':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
                if (!empty($data)) {
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                    $headers[] = 'Content-Type: application/json';
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                }
                break;

            default:
                throw new \Exception("Unsupported HTTP method: $method");
        }

        $response = curl_exec($ch);
        $error = curl_error($ch);
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        if ($error) {
            throw new \Exception("cURL error: $error");
        }

        if ($statusCode < 200 || $statusCode >= 300) {
            throw new \Exception("Request failed with status code: $statusCode\nResponse: $response");
        }
        return json_decode($response, true);
    }
}