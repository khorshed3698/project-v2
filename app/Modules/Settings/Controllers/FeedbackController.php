<?php

namespace App\Modules\Settings\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Modules\Settings\Models\Features;
use App\Modules\Settings\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FeedbackController extends Controller
{
    // public function feedback(Request $request){
    // 	$userId = Auth::User()->id;
    // 	$sdata = new Feedback();

    // 	$sdata->user_id    = $userId;
    // 	$sdata->feature_id = $request->featurId;
    //   $sdata->feedback = $request->value;
    // 	$sdata->type = 1;

    // 	$sdata->save();

    // }

    // public function fMsgShow(Request $request){

    //   $user_id = Auth::user()->id;
    //   $userfeedback   = Feedback::where('user_id',$user_id)
    //                 ->where('type',1)
    //                 ->orderby('created_at','desc')
    //                 ->first();

    //   if($userfeedback){
    //       $end = Carbon::parse($userfeedback->created_at);
    //       $now = Carbon::now();

    //       $lengthDays = Features::where('feature_id',$userfeedback->feature_id)->first();
    //       if($lengthDays->showing_length <= $end->diffInDays($now)){            
    //           $featureId =  $userfeedback->feature_id;
    //           $nextFeatureInfo = Features::where('status',1)->where('feature_id', '>', $featureId)->first();

    //           if($nextFeatureInfo){
    //             return response()->json($nextFeatureInfo);
    //           }else{
    //             $featureinfo = Features::where('status',1)->orderby('id','asc')->first();
    //             return response()->json($featureinfo);  
    //            }
    //         }else{
    //           return 0;
    //         }
    //   }else{
    //     $featureinfo = Features::where('status',1)->orderby('id','asc')->first();
    //     return response()->json($featureinfo);
    //   }
    // }


}
 