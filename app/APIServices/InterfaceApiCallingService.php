<?php


namespace App\APIServices;


interface InterfaceApiCallingService
{
    public function callApi($requestData, $url = '', $header = [], $pattern = 'json');
    function setParams($requestData);
    function callToApi($data, $header = [], $setLocalhost = false);

}