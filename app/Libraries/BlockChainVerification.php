<?php


namespace App\Libraries;

use Illuminate\Support\Facades\Log;

class BlockChainVerification
{
    const VERIFY_ML = 'VERIFY_BLOCKCHAIN';

    const VERSION = '1.0';

    private $blockchain_base_url;
    private $blockchain_details_url;

    public function __construct()
    {
        $this->blockchain_base_url = config('app.BLOCKCHAIN_BASE_URL');
        $this->blockchain_details_url = config('app.BLOCKCHAIN_DETAILS_URL');
    }

    public function verifyData($tracking_no)
    {
        if(empty($tracking_no)){
            return false;
        }
        $queryData = ['tracking_no' => $tracking_no];
        $urlWithQuery = $this->blockchain_base_url . '?' . http_build_query($queryData);
        $response = $this->curlResponse($urlWithQuery);

        return $response;
    }

    public function getDetails($block_no, $tracking_no)
    {
        if(!isset($block_no) || empty($tracking_no)){
            return false;
        }
        $queryData = ['block_no' => $block_no, 'tracking_no' => $tracking_no];
        $urlWithQuery = $this->blockchain_details_url . '?' . http_build_query($queryData);
        $response = $this->curlResponse($urlWithQuery);

        return $response;
    }

    public function curlResponse($url)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            
        ));

        $response = curl_exec($curl);

        if ($response === false) {
            Log::error("CURL Error in BlockChainVerification : " . curl_error($curl));
            return false;
        }

        curl_close($curl);

        $decoded_output = json_decode($response, true);


        return $decoded_output;
    }

}
