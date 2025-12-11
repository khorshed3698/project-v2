<?php


namespace App\Modules\API\Services;


use App\Modules\Settings\Models\Configuration;
use Carbon\Carbon;

class IrmsPortalLogin
{
    private $response_data = '';
    private $client_id;
    private $client_secret_key;
    private $base_url;


    public function __construct()
    {
        $this->client_id = config('app.CLIENT_ID');
        $this->client_secret_key = config('app.CLIENT_SECRET_KEY');
        $this->base_url = config('app.IRMS_BASE_URL');

    }

    /**
     * @return false|string
     */
    public function getToken()
    {

        $api_token = Configuration::firstOrNew(['caption' => 'IRMS_API_TOKEN']);
        if (isset($api_token->value2) && $api_token->value2 > time()) {
            $this->response_token = [
                'responseCode' => 1,
                'data' => $api_token->value
            ];
            return json_encode($this->response_token);
        }

        //base url
        $token_url = $this->base_url . '/api/getToken';

        try {
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($this->getCredentials()));
            curl_setopt($curl, CURLOPT_URL, "$token_url");
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, config('app.curlopt_ssl_verifyhost'));
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, config('app.curlopt_ssl_verifypeer'));
            $result = curl_exec($curl);

            if (curl_errno($curl)) {
                $this->response_data = ['responseCode' => 0, 'msg' => curl_error($curl), 'data' => ''];
                curl_close($curl);
                return json_encode($this->response_data);
            }

            curl_close($curl);

            $decoded_json = json_decode($result, true);

            $this->response_data = [
                'responseCode' => 1,
                'data' => $decoded_json['token'],
            ];

            // updating token
            $api_token->value = $decoded_json['token'];
            $api_token->value2 = strtotime($decoded_json['expire_on']. ' -2 minutes');// deducted for latency
            $api_token->updated_at = Carbon::now();
            $api_token->save();

            return json_encode($this->response_data);
        } catch (\Exception $e) {
            $this->response_data = ['responseCode' => 0, 'msg' => $e->getMessage() . $e->getFile() . $e->getLine(), 'data' => ''];
            return json_encode($this->response_data);
        }
    }

    public function getCredentials()
    {
        return ['client_user_id' => $this->client_id, 'client_secret_key' => $this->client_secret_key];
    }
}
