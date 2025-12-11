<?php

namespace App\Modules\NewRegForeign\Controllers;


use App\Modules\NewReg\Models\RjscNrFDoc;
use App\Modules\NewReg\Models\RjscNrFPayment;
use App\Modules\NewReg\Models\RjscNrPFaymentInfo;
use App\Modules\NewReg\Models\RjscNrfRequest;
use App\Modules\NewReg\Models\RjscNrSubmitForms;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;


class IndividualFileController extends Controller
{
   public function getIndividualFile(){
       /*$app_id = Session::get('current_app_id');
       $app_id = Encryption::decodeId($app_id);*/
       $app_id = 788;
       $individualFile = RjscNrSubmitForms::where('app_id', $app_id)->where('is_extra',1)->get(['form_name','file']);
       return view('NewReg::individual',compact('individualFile'));
   }

   public function storeIndividualFile(Request $request){
       /*$app_id = Session::get('current_app_id');
       $app_id = Encryption::decodeId($app_id);*/
       $app_id = 788;
       RjscNrSubmitForms::where('app_id',$app_id)->delete();

       $count=count($request->form_name);
       for ($i=0; $i < $count; $i++) {
           $individual= new RjscNrSubmitForms;
           $individual->ref_id = 0;
           $individual->app_id = $app_id;
           $individual->form_name=$request->form_name[$i];
           $image = $request->file('file');
           if (isset($image)) {
               $currentDate = Carbon::now()->toDateString();
               $imagename =$currentDate.'-'.uniqid().'.'. $image[$i]->getClientOriginalExtension();
               if (!file_exists('ind_uploads')) {
                   mkdir('ind_uploads',0777,true);
               }
               $image[$i]->move('ind_uploads',$imagename);
           }else{
               $imagename = 'default.png';
           }
           $individual->file = $imagename;
           $individual->status = 1;
           $individual->save();
       }
       Session::flash('success', "Individual File Upload successfully");
       return redirect()->back();
   }
}
