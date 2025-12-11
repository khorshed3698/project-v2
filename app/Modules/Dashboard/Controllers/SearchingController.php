<?php

namespace App\Modules\Dashboard\Controllers;

use Illuminate\Http\Request;
use App\Libraries\Encryption;
use App\Libraries\CommonFunction;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Modules\Users\Models\CompanyInfo;
use App\Modules\Dashboard\Models\MenuList;
use App\Modules\ProcessPath\Models\ProcessList;
use App\Modules\ProcessPath\Models\ProcessType;
use Illuminate\Support\Facades\Log;

class SearchingController extends Controller
{
    public $limit = 5;
    public $userType;
    public $working_company_id;
    public $user_desk_idsk;

    public function __construct()
    {
        $this->middleware('auth');
        $this->userType = CommonFunction::getUserType();
        $this->working_company_id = CommonFunction::getUserWorkingCompany();
        $this->user_desk_idsk = CommonFunction::getUserDeskIds();
    }
    public function index(Request $request)
    {
        $response = [
            'tracking_no' => [],
            'company' => [],
            'menu' => [],
        ];
        $search_value = $request->get('find') ? $request->get('find') : '';

        if($this->userType != '10x112'){
            // tracking_no id data response
            $response['tracking_no'] = $this->getTrackingIdData($search_value);
            // menu data response
            $response['menu'] = $this->getMenuData($search_value);

            if ($this->userType == '5x505' && $this->working_company_id) {
                return $response;
            }
            
            // company data response
            $response['company'] = $this->getCompanyData($search_value);
        }
        else{
            // menu data response
            $response['menu'] = $this->getMenuData($search_value);
        }



        return $response;
    }

    public function getTrackingIdData($search_value)
    {
        // get tracking id data from process list table
        $query = ProcessList::where('tracking_no', 'like', "%$search_value%");
        if ($this->userType == '5x505') {
            $query->where('process_list.company_id', $this->working_company_id);
        }
        return $query->orderBy('id', 'desc')->take($this->limit)->get()->pluck('tracking_no');
    }

    public function getCompanyData($search_value)
    {
        // get company data from company info table
        return CompanyInfo::where('company_name', 'like', "%$search_value%")->take($this->limit)->get()->pluck('company_name');
    }

    // public function getMenuData($search_value)
    // {
    //     // get menu data from menu lists table
    //     $menu_list = MenuList::where('name', 'like', "%$search_value%")->take($this->limit)->select('name', 'link as url')->get();

    //     // get menu data from process type
    //     // $process_type = ProcessType::where('name', 'like', "%$search_value%")->take($this->limit)->select('id', 'name', 'form_url as url')->get();
    //     // $process_type_list = $process_type->map(function ($item) {
    //     //     return [
    //     //             'name' => $item->name . ' List', 
    //     //             'url' => $item->url . '/list/' . Encryption::encodeId($item->id), 
    //     //         ];
    //     //     });
    //     // $process_type_add = $process_type->map(function ($item) {
    //     //     return [
    //     //             'name' => $item->name . ' Add', 
    //     //             'url' => 'process/'. $item->url . '/add/' . Encryption::encodeId($item->id), 
    //     //         ];

    //     //     });


    //     $process_type = ProcessType::where('name', 'like', "%$search_value%")
    //         ->take($this->limit)
    //         ->select('id', 'name', 'form_url as url')
    //         ->get();

    //     $process_type_list = [];
    //     $process_type_add = [];
    //     $accessible_process = \Illuminate\Support\Facades\Session::get('accessible_process');
    //     foreach ($process_type as $item) {
    //         if (in_array($item->id, $accessible_process)) {
    //             $process_type_list[] = [
    //                 'name' => $item->name . ' List', 
    //                 'url' => $item->url . '/list/' . Encryption::encodeId($item->id), 
    //             ];

    //             if(Auth::user()->user_type == '5x505')
    //             {
    //                 $process_type_add[] = [
    //                     'name' => $item->name . ' Add', 
    //                     'url' => 'process/'. $item->url . '/add/' . Encryption::encodeId($item->id), 
    //                 ];

    //             }
    //         }
    //     }

    //     // unique menu list
    //     return collect($menu_list)->merge($process_type_list)->merge($process_type_add)->unique();
    // }

    public function getMenuData($search_value)
    {
        // Get menu data from menu lists table
        $menu_list = MenuList::where('name', 'like', "%$search_value%")
            ->take($this->limit)
            ->select('name', 'link as url', 'active_menu_for','desk_training_id','accessed_desk', 'not_accessed_desk')
            ->get();

        // Get process types
        $process_types = ProcessType::where('name', 'like', "%$search_value%")
            ->take($this->limit)
            ->select('id', 'name', 'form_url as url')
            ->get();

        $filteredMenuItems = [];
        $process_type_list = [];
        $process_type_add = [];
        $accessible_process = \Illuminate\Support\Facades\Session::get('accessible_process');
        $user_type = $this->userType;

        foreach ($menu_list as $menu_item) {
            $activeMenuForArray = array_map('trim', explode(',', $menu_item->active_menu_for));
            $activeDeskForArray = array_map('trim', explode(',', $menu_item->not_accessed_desk));

            $userHasAccess = (
                is_null($menu_item->not_accessed_desk) ||
                (
                    in_array(Auth::user()->user_type, $activeMenuForArray) && 
                    !empty($menu_item->not_accessed_desk) &&
                    !array_intersect($activeDeskForArray, $this->user_desk_idsk)
                ) || (Auth::user()->user_type != '4x404' && in_array(Auth::user()->user_type, $activeMenuForArray))
            );

            $deskAccessCheck = (
                is_null($menu_item->accessed_desk) ||
                (!is_null($menu_item->accessed_desk) && in_array($menu_item->accessed_desk, $this->user_desk_idsk))
            );

            $trainingAccessCheck = (
                is_null($menu_item->desk_training_id) ||
                (
                    in_array(Auth::user()->user_type, $activeMenuForArray) && 
                    !is_null($menu_item->desk_training_id) && 
                    $menu_item->desk_training_id == Auth::user()->desk_training_id
                )
            );

            if (
                (in_array($user_type, $activeMenuForArray) || is_null($menu_item->active_menu_for)) &&
                $userHasAccess &&
                $deskAccessCheck &&
                $trainingAccessCheck
            ) {
                $filteredMenuItems[] = [
                    'name' => htmlspecialchars($menu_item->name, ENT_QUOTES, 'UTF-8'),
                    'url' => htmlspecialchars($menu_item->url, ENT_QUOTES, 'UTF-8'),
                ];
            }
        }

        foreach ($process_types as $process_type) {
            if (in_array($process_type->id, $accessible_process)) {
                $process_type_list[] = [
                    'name' => $process_type->name . ' List',
                    'url' => $process_type->url . '/list/' . Encryption::encodeId($process_type->id),
                ];

                if ($user_type == '5x505') {
                    $process_type_add[] = [
                        'name' => $process_type->name . ' Add',
                        'url' => 'process/' . $process_type->url . '/add/' . Encryption::encodeId($process_type->id),
                    ];
                }
            }
        }

        // Merge and return unique menu list
        return collect($filteredMenuItems)->merge($process_type_list)->merge($process_type_add)->unique();
    }
    public function logError(Request $request)
    {
        $error = $request->input('error');
        $status = $request->input('status');
        $response = $request->input('response');

        Log::error('AJAX Error', [
            'error' => $error,
            'status' => $status,
            'response' => $response
        ]);

        return response()->json(['success' => true]);
    }
}
