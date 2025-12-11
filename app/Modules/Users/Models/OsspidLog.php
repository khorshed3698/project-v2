<?php


namespace App\Modules\Users\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Mockery\Exception;

class OsspidLog extends Model
{
    const VERSION = '1';

    const STARTING_NUMBER = 0;

    const ACCESS_LOG_REQUEST = 'ACCESS-LOG-HISTORY';

    const FAILED_LOGIN_REQUEST = 'FAILED-LOGIN-HISTORY';

    private $osspid_log_grant_type;

    private $osspid_log_my_client_id;

    private $osspid_log_my_secret_key;

    private $osspid_log_token_url;

    private $osspid_log_data_url;

    private $osspid_log_content_type;

    public function __construct()
    {
        $this->osspid_log_grant_type = config('app.osspid_log_grant_type');
        $this->osspid_log_my_client_id = config('app.osspid_log_my_client_id');
        $this->osspid_log_my_secret_key = config('app.osspid_log_my_secret_key');
        $this->osspid_log_token_url = config('app.osspid_log_token_url');
        $this->osspid_log_data_url = config('app.osspid_log_data_url');
        $this->osspid_log_content_type = config('app.osspid_log_content_type');
    }

    public function getAuthToken() {
        try {
            $postdata = array(
                'grant_type' => $this->osspid_log_grant_type,
                'client_id' => $this->osspid_log_my_client_id,
                'client_secret' => $this->osspid_log_my_secret_key
            );

            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL,  $this->osspid_log_token_url);
            curl_setopt($curl, CURLOPT_TIMEOUT, 100);
            curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 100);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($postdata));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, config('app.curlopt_ssl_verifyhost'));
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, config('app.curlopt_ssl_verifypeer')); # KEEP IT FALSE IF YOU RUN FROM LOCAL PC
            curl_setopt($curl, CURLOPT_HTTPHEADER,
                array("Content-Type: $this->osspid_log_content_type"));
            $response = curl_exec($curl);

            if (curl_error($curl)) {
                $error_msg = curl_error($curl);
                dd($error_msg);
            }
            curl_close($curl);

            $result = json_decode($response);

            if (isset($result)) {
                return $result->access_token;
            }
            return false;
        } catch (Exception $e) {
            return false;
        }
    }

    public function getOsspidAccessLogHistory($access_token) {
       try{
           $data = [
               "osspidServiceRequest" => [
                   "requestType" => self::ACCESS_LOG_REQUEST,
                   "version" => self::VERSION,
                   "requestData" => [
                       "clientId" => config('app.osspid_client_id'),
                       "secretKey" => config('app.osspid_client_secret_key'),
                       "userEmail" => Auth::user()->user_email,
                       "startingNumber" => self::STARTING_NUMBER
                   ]
               ]
           ];
           $postdata = array(
               'param' => json_encode($data)
           );

           $curl = curl_init();
           curl_setopt($curl, CURLOPT_URL, $this->osspid_log_data_url);
           curl_setopt($curl, CURLOPT_POST, 1);
           curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($postdata));
           curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
           curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, config('app.curlopt_ssl_verifyhost'));
           curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, config('app.curlopt_ssl_verifypeer')); # KEEP IT FALSE IF YOU RUN FROM LOCAL PC
           curl_setopt($curl, CURLOPT_HTTPHEADER, array(
               "Authorization: Bearer " . $access_token,
               "Content-Type: $this->osspid_log_content_type"
           ));

           $response = curl_exec($curl);

           if (curl_error($curl)) {
               $error_msg = curl_error($curl);
               dd($error_msg);
           }

           curl_close($curl);
           $user_logs = json_decode($response);

           if (isset($user_logs)) {
               return $user_logs;
           }
           return false;
       } catch (Exception $e) {
           return false;
       }
    }

    public function getOsspidFailedLoginHistory($access_token) {
        try{
            $data = [
                "osspidServiceRequest" => [
                    "requestType" => self::FAILED_LOGIN_REQUEST,
                    "version" => self::VERSION,
                    "requestData" => [
                        "clientId" => config('app.osspid_client_id'),
                        "secretKey" => config('app.osspid_client_secret_key'),
                        "userEmail" => Auth::user()->user_email,
                        "startingNumber" => self::STARTING_NUMBER
                    ]
                ]
            ];
            $postdata = array(
                'param' => json_encode($data)
            );

            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $this->osspid_log_data_url);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($postdata));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, config('app.curlopt_ssl_verifyhost'));
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, config('app.curlopt_ssl_verifypeer')); # KEEP IT FALSE IF YOU RUN FROM LOCAL PC
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                "Authorization: Bearer " . $access_token,
                "Content-Type: $this->osspid_log_content_type"
            ));

            $response = curl_exec($curl);

            if (curl_error($curl)) {
                $error_msg = curl_error($curl);
                dd($error_msg);
            }

            curl_close($curl);
            $user_logs = json_decode($response);

            if (isset($user_logs)) {
                return $user_logs;
            }
            return false;
        } catch (Exception $e) {
            return false;
        }
    }

}