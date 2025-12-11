<?php

namespace App\Modules\ExternalTest\Controllers;

use App\Libraries\Encryption;
use App\Modules\ExternalTest\Models\ExternalTest;
use http\Env\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use phpDocumentor\Reflection\DocBlock\Tags\Reference\Url;

class ExternalTestController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $datas = ExternalTest::all();
        return view("ExternalTest::index",compact('datas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $app =  ExternalTest::firstOrNew([
            'uid' => $request->bida_oss_id
        ]);
        $app->appdata = json_encode($request->all());
        $app->status = 1;
        $app->save();
        return response()->json(['status'=>200,'message'=>'Application Submitted successfully','redirect_url'=>url().'/external-test/show/'.Encryption::encodeId($app->id)]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $decodedId = Encryption::decodeId($id);
        $appData = ExternalTest::find($decodedId);

        return view("ExternalTest::view",compact('appData'));
    }

    public function status(Request $request)
    {
//        dd($request->all());
        $appData = ExternalTest::where('uid',$request->bida_oss_id)->first();
        if ($appData){
            $response =
                [
                    'status'=>200,
                    'message'=>'',
                    'applicationStatusText'=>'Submitted',
                    'applicationStatusId'=>1,
                    'applicationViewUrl'=>url().'/external-test/application/'.Encryption::encodeId($appData->id)
                ];
        }else{
            $response =
                [
                    'status'=>201,
                    'message'=>'Application not found'
                ];
        }

        return response()->json($response);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
