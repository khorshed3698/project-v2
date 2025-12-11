<?php

/**
 * @return mixed
 */
function getVisitorRealIP()
{
    // Get real visitor IP behind CloudFlare network
    if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
        $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
        $_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
    }
    $client = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote = $_SERVER['REMOTE_ADDR'];

    if (filter_var($client, FILTER_VALIDATE_IP)) {
        $ip = $client;
    } elseif (filter_var($forward, FILTER_VALIDATE_IP)) {
        $ip = $forward;
    } else {
        $ip = $remote;
    }
    // if above code is not working properly then
    /*
    if(!empty($_SERVER['HTTP_X_REAL_IP'])){
        $ip=$_SERVER['HTTP_X_REAL_IP'];
    }
    */

    //dd($request->ip(),$request->getClientIp(), $request->REMOTE_ADDR, $ip,$_SERVER['HTTP_X_REAL_IP']);

    return $ip;
}

/**
 * @return mixed|string
 */
function getVisitorUserAGent()
{
    return isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'N/A';
}

/**
 * @param $clientData
 * @return bool
 */
function isAuthorizedClientUrl($clientData)
{
    //$request_ip = getVisitorRealIP();
    $request_ip = $_SERVER['REMOTE_ADDR'];
    $return_data = [
        'return_type' => '',
        'return_data' => ''
    ];
    $allowed_ip_list = explode(",", $clientData->allowed_ips);
    if (!in_array("$request_ip", $allowed_ip_list)) {
        $return_data['return_type'] = false;
        $return_data['return_data'] = $request_ip;
//        return false;
    } else {
        $return_data['return_type'] = true;
    }
    return $return_data;
}

function generateUniqueId()
{
    $last6DigitUnique = substr(uniqid(), -5);
    $bytes = random_bytes(2);
    return bin2hex($bytes) . $last6DigitUnique;
}

if (!function_exists('apache_request_headers')) {
    function apache_request_headers()
    {
        static $arrHttpHeaders;
        if (!$arrHttpHeaders) {
            // Your custom function code here
            $arrCasedHeaders = array(
                'Dasl' => 'DASL',
                'Dav' => 'DAV',
                'Etag' => 'ETag',
                'Mime-Version' => 'MIME-Version',
                'Slug' => 'SLUG',
                'Te' => 'TE',
                'Www-Authenticate' => 'WWW-Authenticate',
                'Content-Md5' => 'Content-MD5',
                'Content-Id' => 'Content-ID',
                'Content-Features' => 'Content-features',
            );
            $arrHttpHeaders = array();

            foreach ($_SERVER as $strKey => $mixValue) {
                if ('HTTP_' !== substr($strKey, 0, 5)) {
                    continue;
                }

                $strHeaderKey = strtolower(substr($strKey, 5));

                if (0 < substr_count($strHeaderKey, '_')) {
                    $arrHeaderKey = explode('_', $strHeaderKey);
                    $arrHeaderKey = array_map('ucfirst', $arrHeaderKey);
                    $strHeaderKey = implode('-', $arrHeaderKey);
                } else {
                    $strHeaderKey = ucfirst($strHeaderKey);
                }

                if (array_key_exists($strHeaderKey, $arrCasedHeaders)) {
                    $strHeaderKey = $arrCasedHeaders[$strHeaderKey];
                }

                $arrHttpHeaders[$strHeaderKey] = $mixValue;
            }

            // Handle Authorization header if not set in $_SERVER
            if (!empty($arrHttpHeaders['Authorization'])) {
                if (!isset($_SERVER['PHP_AUTH_USER'])) {
                    list($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']) = explode(':', base64_decode(substr($arrHttpHeaders['Authorization'], 6)));
                }
            }
        }
        return $arrHttpHeaders;
    }
}
