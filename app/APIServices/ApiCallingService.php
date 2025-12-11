<?php


namespace App\APIServices;


use App\Libraries\CommonFunction;
use Illuminate\Support\Facades\Session;

class ApiCallingService extends AbstractApiCallingService
{
    protected $config = [];

    public function __construct()
    {
        $this->config = config('services');
    }

    public function callApi($requestData, $url = '', $header = [], $pattern = 'json')
    {
        if (empty($requestData)) {
            return "Please provide a valid information";
        }

        try {
            $this->setApiUrl($url);

            // Set the required/additional params
            $this->setParams($requestData);


            // Now, call the API
            $response = $this->callToApi($this->data, $header, $this->config['api']['connect_from_localhost']);

            $formattedResponse = $this->formatResponse($response, $pattern); // Here we will define the response pattern

            return $formattedResponse;

        } catch (\Exception $e) {
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . "[API-100]");
            return redirect()->back();
        }

    }
}