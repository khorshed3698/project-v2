<?php


namespace App\Libraries;


use App\Libraries\OtpServiceJwt as Jwt;
use Illuminate\Support\Facades\Session;

class OtpService
{
    private $baseUrl;
    private $clientId;
    private $clientSecret;
    private $redirectUri;
    private $certsUrl;

    public function __construct($config = null)
    {
//        $config = $config ?? config('otp_service');
//        $this->baseUrl = $config['base_url'] ?? '';
//        $this->clientId = $config['client_id'] ?? '';
//        $this->clientSecret = $config['client_secret'] ?? '';
//        $this->redirectUri = $config['redirect_uri'] ?? '';
//        $this->certsUrl = $config['certs_url'] ?? '';

        $config = isset($config) ? $config : config('otp_service');
        $this->baseUrl = isset($config['base_url']) ? $config['base_url'] : '';
        $this->clientId = isset($config['client_id']) ? $config['client_id'] : '';
        $this->clientSecret = isset($config['client_secret']) ? $config['client_secret'] : '';
        $this->redirectUri = isset($config['redirect_uri']) ? $config['redirect_uri'] : '';
        $this->certsUrl = isset($config['certs_url']) ? $config['certs_url'] : '';
    }

    public function generateOtpVerificationUrl($mobileNo)
    {
        $formattedMobileNo = ltrim($mobileNo, '+');
        //$lastElevenDigit = substr($mobileNo, -11);
        //$formattedMobileNo = $mobileNo;
        $queryParams = http_build_query([
            'client_id' => $this->clientId,
            'mobile_no' => $formattedMobileNo,
            'redirect_uri' => $this->redirectUri
        ]);
        return "{$this->baseUrl}/otp-client/basic?$queryParams";
    }

    // $otpUserData = call back data that will provide from otp service
    // 5 minutes token validate time
    public function verifySecretKey($otpUserData)
    {
        try {

            // $mobileNumber = ossp mobile number.
            //
            $mobileNumber = $cleanedNumber = ltrim(Session::get('oauth_data')->mobile, '+');;

            $postData = [
                "clientKey" => $this->clientId,
                "clientSecret" => $this->clientSecret,
                "mobileNo" => $mobileNumber,
                "optSecretKey" => $otpUserData->secretKey,
            ];


           // dd($otpUserData, $postData, $this->baseUrl);



            $handle = curl_init();
            curl_setopt($handle, CURLOPT_URL, $this->baseUrl . "/otp-client/verify-secret-key");
            curl_setopt($handle, CURLOPT_TIMEOUT, 30);
            curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 30);
            curl_setopt($handle, CURLOPT_POST, 1);
            curl_setopt($handle, CURLOPT_POSTFIELDS, $postData);
            curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, config('app.curlopt_ssl_verifyhost'));
            curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, config('app.curlopt_ssl_verifypeer')); # KEEP IT FALSE IF YOU RUN FROM LOCAL PC
            $response = curl_exec($handle);
            //dd($response);
            if (curl_error($handle)) {
                return (object)['status' => false, 'errorMessage' => 'Failed to verify otp.Please try again'];
            }
            $result = json_decode($response);
            if ($result->responseCode != "OTP200" || !isset($result->data->id_token)) {
                return (object)['status' => false, 'errorMessage' => $this->setErrorMessage($result->responseCode)];
            }

            //dd($result->data->id_token, $this->getPublicKey());
            if ($otpUserData = (new Jwt())->decode($result->data->id_token, $this->getPublicKey())) {
                return (object)['status' => true, 'otpUserData' => $otpUserData];
            }

            return (object)['status' => false, 'errorMessage' => 'Failed to verify OTP.Please try again'];

        } catch (\Exception $e) {
            return (object)['status' => false, 'errorMessage' => 'Failed to verification.Please try again'];
        }
    }

    public function getPublicKey()
    {
        try {
            // Initialize cURL session

            //dd($this->certsUrl);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->certsUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, config('app.curlopt_ssl_verifyhost'));
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, config('app.curlopt_ssl_verifypeer')); # KEEP IT FALSE IF YOU RUN FROM LOCAL PC

            // Execute the cURL request and get the response
            $response = curl_exec($ch);

            if ($response === false) {
                throw new \Exception(curl_error($ch));
            }

            // Close the cURL session
            curl_close($ch);

            $jwks = json_decode($response, true);

            $keys = $jwks['keys'][0];

            if (!$keys) {
                throw new \Exception('Key not found in JWKS');
            }
            return $keys['pem'];

        } catch (\Exception $exception) {
            return '';
        }
    }

    public static function appendCountryCode($mobileNo, $countryCode = '88')
    {
        $lastElevenDigit = substr($mobileNo, -11);
        return $countryCode . $lastElevenDigit;
    }

    public function setErrorMessage($otpResponseCode)
    {
        $responseMessage = [
            "OTP500" => "This Page is Expired. Please request a new OTP and try again.",
            "OTP400" => "Mobile number mismatch between <strong>HAJJ Service</strong> and  <strong>OTP Service</strong>. Please try again after 5 minute",
            "OTP404" => "Unable to connect with OTP service. Please contact with System admin",
        ];
        if (!in_array($otpResponseCode, array_keys($responseMessage))) {
            return 'Failed to verification. Please try again with New OTP';
        }
        return $responseMessage[$otpResponseCode];
    }
}