<?php

namespace App\Modules\NewReg\Controllers;


use App\Modules\NewReg\Models\RjscNrSubmitForms;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;


class IndividualFileController extends Controller
{
   public function getIndividualFile(){
       return view('NewReg::individual');
   }
   public function storeIndividualFile(Request $request){
       $count=count($request->form_name);
       for ($i=0; $i < $count; $i++) {
           $individual= new RjscNrSubmitForms;
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
           $individual->save();
           Session::flash('success', "Individual File Upload successfully");
           return redirect()->back();
       }
   }
}
