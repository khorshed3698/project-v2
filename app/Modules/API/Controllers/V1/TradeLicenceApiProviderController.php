<?php

namespace App\Modules\API\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Modules\API\Models\ApiTokenList;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TradeLicenceApiProviderController extends Controller
{

    public function checkPaymentStatus(Request $request){

        $token = $request->token;
        $ossRef = $request->ossref;

        $responseCode = null;
        $status = 'error';
        $amount = null;
        $message = null;

        if($token){

            if( $this->validateToken($token)){

                //todo:: rest function will implement here

                $responseCode = 0;
                $status = 'successs';
                $message = "rest function will implement here";

            }else{
                $responseCode = 5;
                $status = 'error';
                $message = "Invalide token for authorization";
            }

        }else{
            $responseCode = 5;
            $status = 'error';
            $message = "Authorization Token Required";
        }

        return response()->json([
            'response_code' => $responseCode,
            'status' => $status,
            'message' => $message,
            'amount' => $amount,
            'data' => [],
        ]);

    }


    private function validateToken($token){


        $presentTime = Carbon::now()->toDateTimeString();

        $tokenList = ApiTokenList::where('token', $token)
            ->whereRaw("valid_till > '".$presentTime."'")
            ->first();

        if($tokenList){

            return true;
        }

        return false;
    }


}