<?php


namespace App\APIServices;


use App\ApiClientMaster;
use App\Libraries\Encryption;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

abstract class AbstractApiCallingService implements InterfaceApiCallingService
{

    protected $apiUrl;
    protected $data;

    protected function setApiUrl($url)
    {
        $this->apiUrl = $url;
    }

    public function setParams($requestData)
    {
        $this->data = $requestData;
    }

    private function getApiUrl()
    {
        return $this->apiUrl;
    }

    /**
     * @param $data
     * @param array $header
     * @param bool $setLocalhost
     * @return bool|string
     */
    public function callToApi($data, $header = [], $setLocalhost = false)
    {
        $curl = curl_init();

        if (!$setLocalhost) {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2); // The default value for this option is 2. It means, it has to have the same name in the certificate as is in the URL you operate against.
        } else {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, config('app.curlopt_ssl_verifypeer'));
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, config('app.curlopt_ssl_verifyhost')); // When the verify value is 0, the connection succeeds regardless of the names in the certificate.
        }

        curl_setopt($curl, CURLOPT_URL, $this->getApiUrl());
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_TIMEOUT, 60);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);

        $response = curl_exec($curl);
        $err = curl_error($curl);
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $curlErrorNo = curl_errno($curl);
        curl_close($curl);

        if ($code == 200 & !$curlErrorNo) {
            return $response;
        } else {
            return $err;
        }
    }

    /**
     * @param $response
     * @param string $type
     * @param string $pattern
     * @return false|mixed|string
     */
    protected function formatResponse($response)
    {
        $response_data = json_decode($response, true);

        return $response_data;
    }

}