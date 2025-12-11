<?php

namespace App\Modules\API\Services;

use Symfony\Component\HttpFoundation\Response as HttpResponse;

trait ApiResponse
{
    /**
     * @param string $message
     * @param string $response_type
     * @param int $status_code
     * @param $data
     * @return array
     */
    public function responseJson($message = '', $response_type = 'BIDA-API', $status_code = HttpResponse::HTTP_BAD_REQUEST, $data = '')
    {
        $response['response'] = [
            'responseTime' => time(),
            'responseType' => $response_type,
            'responseCode' => $status_code,
            'responseData' => $data,
            'message' => $message
        ];

        return response()->json($response);
    }
}
