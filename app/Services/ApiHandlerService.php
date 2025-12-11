<?php

namespace App\Services;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use Exception;


class ApiHandlerService
{
    public function makeRequest(
        $baseUrl,
        $endpoint,
        $method,
        $clientId,
        $clientSecret,
        $data = null
    )
    {
        if (empty($baseUrl) || empty($endpoint) || empty($method) || empty($clientId) || empty($clientSecret)) {
            Log::error('API Request Failed: Missing required parameters.');
            return ['error' => 'Missing required parameters.'];
        }
        $validMethods = ['GET', 'POST'];
        if (!in_array(strtoupper($method), $validMethods)) {
            Log::error('API Request Failed: Invalid HTTP method ' . $method);
            return ['error' => 'Invalid HTTP method.'];
        }
        try {
            $url = $baseUrl . $endpoint;

            $tokenCacheKey =  'api_token';
            if (Cache::has($tokenCacheKey)) {
                $token = Cache::get('api_token');
            }else {
                $tokenService = new TokenService();
                $token = $tokenService->getToken($clientId, $clientSecret);
                Cache::put($tokenCacheKey, $token, Carbon::now()->addMinutes(25));
            }

            $response = $this->handleRequest($method, $url, $data, $token);
            if ($response) {
                $statusCode = $response['responseCode'];
                if ($statusCode >= 200 && $statusCode < 300) {
                    return $response;
                } else {
                    Log::error('API Request Failed: ' . $response->statusCode . ' - ' . $response->getBody());
                    return ['error' => 'Request failed with status code ' . $statusCode];
                }
            } else {
                Log::error('API Request Failed: No response returned.');
                return ['error' => 'No response returned from API.'];
            }
        } catch (\Exception $e) {
            Log::error('API Request Failed: ' . $e->getMessage());
            return ['error' => 'API request failed: ' . $e->getMessage()];
        }
    }

    private function handleRequest($method, $url, $data = [], $headers='')
    {
        if (!in_array(strtoupper($method), ['GET', 'POST'])) {
            throw new Exception("Unsupported HTTP method: $method");
        }
        $token = $headers;
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, config('app.curlopt_ssl_verifyhost'));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, config('app.curlopt_ssl_verifypeer'));

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

            default:
                throw new Exception("Unsupported HTTP method: $method");
        }

        $response = curl_exec($ch);
        $error = curl_error($ch);
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        if ($error) {
            throw new Exception("cURL error: $error");
        }

        if ($statusCode < 200 || $statusCode >= 300) {
            throw new Exception("Request failed with status code: $statusCode\n Response: $response");
        }
        return json_decode($response, true);
    }
}
