<?php

namespace App\Http\Controllers;
use App\Libraries\ImageProcessing;
use App\Modules\Users\Models\Users;
use Illuminate\Support\Facades\URL;

class SignEncodeController extends Controller
{


    public function compressSignGeneration($id){
        $user_data = Users::where('id', '=', $id)->first();
//dd($user_data);

        if (empty($user_data)) {
            echo "user data not found!";
            exit();
        }

        $signature = '';

        $signature_url = URL::to('/')."/users/signature/" . $user_data->signature;
        echo $user_data->signature;
        if ($user_data->signature != '' & file_exists($_SERVER['DOCUMENT_ROOT']."/users/signature/" . $user_data->signature)) {



            if (!empty($signature_url) && (env('server_type') != 'local')) {
                $arrContextOptions=array(
                    "ssl"=>array(
                        "verify_peer"=>false,
                        "verify_peer_name"=>false,
                    ),
                );
                $signature = file_get_contents($signature_url, false, stream_context_create($arrContextOptions));
            } else {
                $signature = 'No signature found';
            }


            $base64ResizeImage = base64_encode(ImageProcessing::resizeBase64Image(base64_encode($signature),150,40));
            $user_data->signature_encode = $base64ResizeImage;
            $user_data->save();
            echo 11;
        }else{
            echo "Sign not found for : $user_data->user_email";
        }
    }



    public function compressSignGenerationAll(){

        $user_data = Users::get();

        echo "Signature encoded for:<br>";
        foreach ($user_data as $user){
            $signature = '';
            if ($user->signature != '' & file_exists($_SERVER['DOCUMENT_ROOT']."/users/signature/" . $user->signature)) {
                $signature_url = URL::to('/')."/users/signature/" . $user->signature;
                if (!empty($signature_url) && (env('server_type') != 'local')) {
                    $arrContextOptions=array(
                        "ssl"=>array(
                            "verify_peer"=>false,
                            "verify_peer_name"=>false,
                        ),
                    );
                    $signature = file_get_contents($signature_url, false, stream_context_create($arrContextOptions));
                } else {
                    $signature = 'No signature found';
                }
                $base64ResizeImage = base64_encode(ImageProcessing::resizeBase64Image(base64_encode($signature),150,40));
                $user_data = Users::find($user->id);
                $user_data->signature_encode = $base64ResizeImage;
                $user_data->save();
                echo $user->user_email."<br>";
            }
            else{
                echo "Sign not found for : $user->user_email<br>";
            }
        }
    }
}


