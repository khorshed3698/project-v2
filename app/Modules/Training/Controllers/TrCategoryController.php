<?php

namespace App\Modules\Training\Controllers;

use App\Libraries\ACL;
use Illuminate\Http\Request;
use App\Libraries\Encryption;
use yajra\Datatables\Datatables;
use App\Libraries\CommonFunction;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Modules\Training\Models\TrCategory;

class TrCategoryController extends Controller
{
    protected $aclName = 'Training';


    public function index()
    {
        if (!ACL::getAccsessRight($this->aclName, '-V-')) {
            die('You have no access right! Please contact system administration for more information');
        }
        return view('Training::category.index');
    }

    public function createCategory()
    {
        if (!ACL::getAccsessRight($this->aclName, '-A-')) {
            return response()->json([
                'responseCode' => 1,
                'html' => "<h4 class='custom-err-msg'>You have no access right! Please contact system administration for more information</h4>"
            ]);
        }
        return view('Training::category.create');
    }

    public function getData()
    {
        if (!ACL::getAccsessRight($this->aclName, '-V-')) {
            return response()->json([
                'responseCode' => 1,
                'html' => "<h4 class='custom-err-msg'>You have no access right! Please contact system administration for more information</h4>"
            ]);
        }
        $trainingData = TrCategory::orderBy('id', 'DESC')->get(['id', 'category_name', 'category_name_bn', 'is_active']);
        return Datatables::of($trainingData)
            ->editColumn('category_name', function ($training) {
                return $training->category_name;
            })
            ->editColumn('category_name_bn', function ($training) {
                return $training->category_name_bn;
            })
            ->editColumn('status', function ($category) {
                $activate = $category->is_active == 1 ? "btn-success" : "btn-danger" ;
                $status_name = $category->is_active == 1 ? 'Active' : 'Inactive';
                return '<span class="btn-xs '.$activate.'"><b>' . $status_name . '</b></span>';
            })
            ->addColumn('action', function ($category) {

                $button = '<a href="' . url('/training/edit-category/' . Encryption::encodeId($category->id)) . '"  class="btn btn-xs btn-primary "><i class="fa fa-pencil"></i> Edit </a> ';

                return $button;

            })
            ->removeColumn('id')
            ->make(true);
    }

    public function storeCategory(Request $request)
    {

        // Set permission mode and check ACL
        $mode = (!empty($request->get('app_id')) ? '-E-' : '-A-');
        if (!ACL::getAccsessRight($this->aclName, $mode)) {
            abort('400', "You have no access right! Please contact system administration for more information");
        }

        $rules = [
            'category_name' => 'required',
            'category_name_bn' => 'required',
            'is_active' => 'required|in:1,0',
        ];
        if (empty($request->get('app_id'))) {
            $rules['category_name'] = 'required|unique:tr_categories,category_name';
        }
        $messages = [
            'is_active.required' => 'Status is required',
            'category_name.required' => 'Category name is required',
            'category_name_bn.required' => 'Category name (Bangla) is required',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try{
            if ($request->get('app_id')) {
                $appId = Encryption::decodeId($request->get('app_id'));
                $trCategory = TrCategory::find($appId);
            }else {
                $trCategory = new TrCategory();
            }
            $trCategory->category_name = $request->category_name;
            $trCategory->category_name_bn = $request->category_name_bn;
            $trCategory->is_active = $request->is_active;
            $trCategory->save();
        }
        catch (\Exception $e) {
            Log::error('TrCategory : ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [TRS-73]');
            return response()->json([
                'responseCode' => 1,
                'html' => "<attachment_typeh4 class='custom-err-msg'>" . CommonFunction::showErrorPublic($e->getMessage()) . ' [TRC-90]' . "</attachment_typeh4>"
            ]);
        }
        if (!empty($request->get('app_id'))) {
            return redirect('training/category-list')->with('success', 'Category updated successfully');
        }
        return redirect('training/category-list')->with('success', 'Category created successfully');
    }

    public function editCategory($id)
    {
        if (!ACL::getAccsessRight($this->aclName, '-E-')) {
            return response()->json([
                'responseCode' => 1,
                'html' => "<h4 class='custom-err-msg'>You have no access right! Please contact system administration for more information</h4>"
            ]);
        }

        $tr_data = TrCategory::find(Encryption::decodeId($id));
        return view('Training::category.edit', compact('tr_data','id'));
    }

}
