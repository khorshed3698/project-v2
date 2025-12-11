<?php

namespace App\Modules\NewRegForeign\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\NewReg\Models\RjscSubmissionVerify;
use Illuminate\Http\Request;


class GetCompanyForeignController extends Controller
{
    public  static function getCompanyNameBysubimmsionNo($submission_no)
    {
        // $rjscVerifyData = RjscSubmissionVerify::orderBy('id', 'desc')->where('submission_no', $submission_no)->first();
        return '';
    }

}