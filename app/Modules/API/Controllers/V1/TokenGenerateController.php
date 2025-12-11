<?php
/**
 * Created by PhpStorm.
 * User: mehedi
 * Date: 1/8/19
 * Time: 1:03 PM
 */

namespace App\Modules\API\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Modules\API\Models\ApiTokenList;
use App\Modules\API\Models\ApiTokenUser;
use Carbon\Carbon;
use Illuminate\Http\Request;


class TokenGenerateController extends Controller
{
    public function getToken(Request $request)
    {

        try {

            $user = null;
            $password = null;

            if (!empty($request->user)) {
                $user = $request->user;
            } else {
                return $this->errorReturn('400', 'user');
            }

            if (!empty($request->password)) {
                $password = $request->password;
            } else {
                return $this->errorReturn('400', 'password', $user);
            }

            $tokenUser = ApiTokenUser::where('user', $user)->where('password', md5($password))->first();

            if (count($tokenUser) > 0) {

                $tokenList = ApiTokenList::create([
                    'token_user_id' => $tokenUser->id,
                    'token' => $this->generateToken(),
                    'valid_till' => Carbon::now()->addDay(1)->toDateTimeString(),
                    'ref_data' => '',
                ]);

                if(!empty($tokenList->token)){

                    return response()->json([
                        'status' => 'success',
                        'status_code' => 200,
                        'message' => "Token Generated",
                        'token' => $tokenList->token,
                        'validity_till' => $tokenList->valid_till,
                        'data' => [],
                    ]);

                }else{

                    return $this->errorReturn('500');
                }


            } else {

                return $this->errorReturn('401', '',$user);
            }

        } catch (\Exception $e) {

            return $this->errorReturn(CommonFunction::showErrorPublic($e->getMessage()));
        }
    }


    private function errorReturn($statusCode, $fieldName = '', $ref_data = '', $message = 'Sorry, There is a error')
    {

        switch ($statusCode) {
            case '400' :
                $message = 'The request is invalid as without authentication info.';
                if ($fieldName == 'user') {
                    $message = 'The request is invalid for empty user field';
                }
                if ($fieldName == 'password') {
                    $message = 'The request is invalid for empty password for user : ' . $ref_data ;
                }
                break;

            case '401' :
                $message = 'Authorization Required, user and password not matched for this user :' . $ref_data;
                break;

            case '404' :
                $message = 'The URI requested is invalid ';
                break;

            case '500' :
                $message = 'There is a internal failure . Please try again';
                break;

            default :
                break;
        }

        return response()->json([
            'status' => 'error',
            'status_code' => $statusCode,
            'message' => $message,
        ]);

    }


    private function generateToken()
    {

        return hash('sha256', sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x', mt_rand(10, 0xffff), mt_rand(0, 0xffff), mt_rand(11, 0xffff), mt_rand(0, 0x0fff) | 0x4000, mt_rand(0, 0x3fff) | 0x8000, mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)));
    }

}