<?php

namespace App\Modules\CompanyRegSingleForm\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\CompanyRegSingleForm\Models\RjscSubmissionVerify;


class GetCompanyController extends Controller
{
    public  static function getCompanyNameBysubimmsionNo($submission_no)
    {
        $rjscVerifyData = RjscSubmissionVerify::orderBy('id', 'desc')->where('submission_no', $submission_no)->first();
        return '32';
    }

}