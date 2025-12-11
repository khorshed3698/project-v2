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
use App\Modules\Training\Models\TrCourse;
use App\Modules\Training\Models\TrCategory;


class TrCourseController extends Controller
{
    protected $process_type_id = 2202;
    protected $aclName = 'Training';

    public function index(){
        if (!ACL::getAccsessRight($this->aclName, '-V-')) {
            die('You have no access right! Please contact system administration for more information');
        }
        return view('Training::course.index');
    }

    public function createCourse(){
        
        if (!ACL::getAccsessRight($this->aclName, '-A-')) {
            return response()->json([
                'responseCode' => 1,
                'html' => "<h4 class='custom-err-msg'>You have no access right! Please contact system administration for more information</h4>"
            ]);
        }
        $trCategory = TrCategory::where('is_active', 1)->lists('category_name', 'id');
        return view('Training::course.create', compact('trCategory'));
    }

    public function getData(){
        if (!ACL::getAccsessRight($this->aclName, '-V-')) {
            return response()->json([
                'responseCode' => 1,
                'html' => "<h4 class='custom-err-msg'>You have no access right! Please contact system administration for more information</h4>"
            ]);
        }
        $courseData = TrCourse::orderBy('id', 'desc')
        ->get(['id', 'course_title', 'course_fee', 'is_active']);
        return Datatables::of($courseData)
            ->editColumn('course_title', function ($course) {
                return $course->course_title;
            })
            ->editColumn('status', function ($course) {
                $activate = $course->is_active == 1 ? "btn-success" : "btn-danger" ;
                $status_name = $course->is_active == 1 ? 'Active' : 'Inactive';
                return '<span class="btn-xs '.$activate.'"><b>' . $status_name . '</b></span>';
            })
            ->addColumn('action', function ($course) {

                $button = '<a href="' . url('/training/edit-course/' . Encryption::encodeId($course->id)) . '"  class="btn btn-xs btn-primary "><i class="fa fa-pencil"></i> Edit </a> ';

                return $button;

            })
            ->removeColumn('id')
            ->make(true);
    }

    public function storeCourse(Request $request){
        // Set permission mode and check ACL
          $mode = (!empty($request->get('app_id')) ? '-E-' : '-A-');
          if (!ACL::getAccsessRight($this->aclName, $mode)) {
              abort('400', "You have no access right! Please contact system administration for more information");
          }
  
        $rules = [
            'course_title' => 'required',
            'course_title_bn' => 'required',
            'course_slug' => 'required',
            'course_description' => 'required',
            'category_id' => 'required',
            'is_active' => 'required|in:1,0',

        ];
        
        if (empty($request->get('app_id'))) {
            $rules['course_image'] = 'required|mimes:jpeg,png,jpg|max:2048';
        }
        $messages = [
            'course_title.required' => 'Course title is required',
            'category_id.required' => 'Course Category is required',
            'course_title_bn.required' => 'Course title (Bangla) is required',
            'course_slug.required' => 'Course slug is required',
            'course_description.required' => 'Course description is required',
            'course_fee.required' => 'Course fee is required',
            'course_image.required' => 'Course image is required',
            'course_image.image' => 'Course image must be an image',
            'course_image.mimes' => 'Course image must be a file of type: jpeg, png, jpg',
            'is_active.required' => 'Status is required',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try{
            if ($request->get('app_id')) {
                $appId = Encryption::decodeId($request->get('app_id'));
                $trCourse = TrCourse::find($appId);
            }else {
                $trCourse = new TrCourse();
            }
            $trCourse->course_title = $request->course_title;
            $trCourse->course_title_bn = $request->course_title_bn;
            $trCourse->course_slug = $request->course_slug;
            $trCourse->category_id = $request->category_id;
            $trCourse->course_description = $request->course_description;
            $trCourse->is_active = $request->is_active;


            if ($request->hasFile('course_image')) {
                $yearMonth = date("Y") . "/" . date("m") . "/";
                $path = 'uploads/training/course/' . $yearMonth;
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }
                $_file_path = $request->file('course_image');
                $file_path = trim(uniqid('TR_C01-' . time() . '-', true) . $_file_path->getClientOriginalName());
                $_file_path->move($path, $file_path);
                $trCourse->course_image = $yearMonth . $file_path;
            }
            if ($request->hasFile('course_image2')) {
                $yearMonth = date("Y") . "/" . date("m") . "/";
                $path = 'uploads/training/course/' . $yearMonth;
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }
                $_file_path = $request->file('course_image2');
                $file_path = trim(uniqid('TR_C02-' . time() . '-', true) . $_file_path->getClientOriginalName());
                $_file_path->move($path, $file_path);
                $trCourse->course_image2 = $yearMonth . $file_path;
            }
            if ($request->hasFile('course_image3')) {
                $yearMonth = date("Y") . "/" . date("m") . "/";
                $path = 'uploads/training/course/' . $yearMonth;
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }
                $_file_path = $request->file('course_image3');
                $file_path = trim(uniqid('TR_C03-' . time() . '-', true) . $_file_path->getClientOriginalName());
                $_file_path->move($path, $file_path);
                $trCourse->course_image3 = $yearMonth . $file_path;
            }
            
            $trCourse->save();
        }
        catch (\Exception $e) {
            Log::error('TrCourse : ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [TRC-127]');
            return response()->json([
                'responseCode' => 1,
                'html' => "<attachment_typeh4 class='custom-err-msg'>" . CommonFunction::showErrorPublic($e->getMessage()) . ' [TRC-127]' . "</attachment_typeh4>"
            ]);
        }
        if (!empty($request->get('app_id'))) {
            return redirect('training/course-list')->with('success', 'Course updated successfully');
        }
        return redirect('training/course-list')->with('success', 'Course created successfully');

    }

    public function editCourse($id){
        if (!ACL::getAccsessRight($this->aclName, '-E-')) {
            return response()->json([
                'responseCode' => 1,
                'html' => "<h4 class='custom-err-msg'>You have no access right! Please contact system administration for more information</h4>"
            ]);
        }
        $trCategory = TrCategory::where('is_active', 1)->lists('category_name', 'id');
        $tr_data = TrCourse::find(Encryption::decodeId($id));
        return view('Training::course.edit', compact('tr_data','id','trCategory'));
    }

}
