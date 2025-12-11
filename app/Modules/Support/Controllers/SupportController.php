<?php

namespace App\Modules\Support\Controllers;

use App\Http\Controllers\Controller;
use App\Libraries\ACL;
use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Libraries\NIDverification;
use App\Modules\Faq\Models\Faq;
use App\Modules\Faq\Models\FaqTypes;
use App\Modules\Settings\Models\Notice;
use App\Modules\Support\Models\Feedback;
use App\Modules\Support\Models\FeedbackTopics;
use App\Modules\Support\Models\NidSupport;
use App\Modules\Users\Models\UserTypes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use yajra\Datatables\Datatables;

class SupportController extends Controller {

    protected $aclName;

    public function __construct()
    {
        $this->aclName = 'support';
    }


    public function help($module = '') {
        $faqs = Faq::leftJoin('faq_multitypes', 'faq.id', '=', 'faq_multitypes.faq_id')
            ->leftJoin('faq_types', 'faq_multitypes.faq_type_id', '=', 'faq_types.id')
            ->where('status', 'public')
            ->where('faq_types.name', $module)
            ->get(['question', 'answer', 'status', 'faq_type_id as types', 'name as faq_type_name', 'faq.id as id']);

        $existedFaqType = FaqTypes::where('name', $module)->pluck('name');
        if (empty($existedFaqType)) {
            FaqTypes::create(
                array(
                    'name' => ucfirst($module),
                    'created_by' => CommonFunction::getUserId()
                ));
        }

        $logged_in_user_type = Auth::user()->user_type;
        $user_manual = UserTypes::where('id', $logged_in_user_type)
            ->pluck('user_manual_name');
        if ($faqs == null) {
            Session::flash('error', 'Sorry, there is no help available for this module! [SupC-NID-1060]');
        }

        return view("Support::help.index", compact('faqs', 'user_manual'));
    }

    /*
     * FEEDBACK list
     */
    public function feedback() {
        return view("Support::feedback.list");
    }

    /*
     * create a new feedback
     */
    public function createFeedback() {
        $topics = FeedbackTopics::lists('name', 'id');
        $sysAdmin_email = [(object) ['user_email' => 'prp@hajj.gov.bd']];

        return view("Support::feedback.create", compact('topics', 'sysAdmin_email'));
    }

    /*
     * get feedback details data
     */
    public function getFeedbackDetailsData() {
        $feedbacks = Feedback::leftJoin('feedback_topics', 'feedback.topic_id', '=', 'feedback_topics.id')
            ->where('created_by', Auth::user()->id)
            ->where('parent_id', 0)
            ->orderBy('feedback.created_at', 'desc')
            ->get(['feedback.id as feedback_id', 'feedback_topics.name as topic_name', 'description', 'status', 'priority',
                'feedback.created_by as feedbackCreator', 'feedback.created_at as created', 'feedback.updated_at as updated']);

        $functionUrl = '';

        return Datatables::of($feedbacks)
            ->addColumn('action', function ($feedbacks) {
                global $functionUrl;
                if ($feedbacks->status == 'draft' && $feedbacks->feedbackCreator == Auth::user()->id) {
                    $functionUrl = 'edit-feedback';
                } else {
                    $functionUrl = 'view-feedback';
                }
                return '<a href="/support/' . $functionUrl . '/' . Encryption::encodeId($feedbacks->feedback_id) .
                    '" class="btn btn-xs btn-primary"><i class="fa fa-folder-open-o"></i> Open</a>';
            })
            ->editColumn('status', function ($feedbacks) {
                return ucfirst($feedbacks->status);
            })
            ->editColumn('created', function ($feedbacks) {
                return CommonFunction::changeDateFormat(substr($feedbacks->created, 0, 10));
            })
            ->editColumn('updated', function ($feedbacks) {
                return CommonFunction::changeDateFormat(substr($feedbacks->updated, 0, 10)) . ' ' . substr($feedbacks->updated, -8);
            })
            ->editColumn('description', function ($feedbacks) {
                global $functionUrl;
                return mb_substr($feedbacks->description, 0, 100) . "... "
                    . "<a href='/support/" . $functionUrl . "/" . Encryption::encodeId($feedbacks->feedback_id) . "'>"
                    . "See more"
                    . "</a>";
            })
            ->removeColumn('feedback_id')
            ->make(true);
    }

    /*
     * Uncategorized details data
     */
    public function getUncategorizedFeedbackData($flag) {
        if ($flag == 'submitted_to') {
            $assigned_to = Auth::user()->id;
        } elseif ($flag == 'unassigned') {
            $assigned_to = 0;
        }
        $feedbacks = Feedback::leftJoin('feedback_topics', 'feedback.topic_id', '=', 'feedback_topics.id')
            ->where('assigned_to', $assigned_to)
            ->where('parent_id', 0)
            ->where('status', '!=', 'draft')
            ->where('status', '!=', 'closed')
            ->where('status', '!=', 'rejected')
            ->where('status', '!=', 'canceled')
            ->orderBy('feedback.created_at', 'desc')
            ->get(['feedback.id as feedback_id', 'feedback_topics.name as topic_name', 'description', 'status', 'priority',
                'feedback.created_by as feedbackCreator', 'feedback.created_at as created', 'feedback.updated_at as updated']);

        $functionUrl = '';

        return Datatables::of($feedbacks)
            ->addColumn('action', function ($feedbacks) {
                global $functionUrl;
                if ($feedbacks->status == 'draft' AND $feedbacks->feedbackCreator == Auth::user()->id) {
                    $functionUrl = 'edit-feedback';
                } else {
                    $functionUrl = 'view-feedback';
                }
                return '<a href="/support/' . $functionUrl . '/' . Encryption::encodeId($feedbacks->feedback_id) .
                    '" class="btn btn-xs btn-primary"><i class="fa fa-folder-open-o"></i> Open</a>';
            })
            ->editColumn('status', function ($feedbacks) {
                return ucfirst($feedbacks->status);
            })
            ->editColumn('created', function ($feedbacks) {
                return CommonFunction::changeDateFormat(substr($feedbacks->created, 0, 10));
            })
            ->editColumn('updated', function ($feedbacks) {
                return CommonFunction::changeDateFormat(substr($feedbacks->updated, 0, 10)) . ' ' . substr($feedbacks->updated, -8);
            })
            ->editColumn('description', function ($feedbacks) {
                global $functionUrl;
                return mb_substr($feedbacks->description, 0, 100) . "... "
                    . "<a href='/support/" . $functionUrl . "/" . Encryption::encodeId($feedbacks->feedback_id) . "'>"
                    . "See more"
                    . "</a>";
            })
            ->removeColumn('feedback_id')
            ->make(true);
    }

    /* Start of Notice related functions */

    public function viewNotice($encrypted_id) {
        $id = Encryption::decodeId($encrypted_id);
        $data = Notice::where('id', $id)->first();
        $notice = CommonFunction::getNotice(1);
        return view("Support::notice.view", compact('data', 'encrypted_id', 'notice'));
    }

    /* End of Notice related functions */


    /* Start of NID support*/

    public function nidList()
    {
        if (!ACL::getAccsessRight($this->aclName, '-V-')) {
            abort('400', 'You have no access right! Contact with system admin for more information. [SupC-971]');
        }

        // set auth key in session for NID connectivity with mongo
        $nid_verification = new NIDverification();
        $nid_auth_token = $nid_verification->getAuthToken();
        Session::put('nidAuthToke', Encryption::encode($nid_auth_token));

        if (empty($nid_auth_token)) {
            Session::flash('error', 'NID auth token not found! Please try again. [SupC-1041]');
            return Redirect::back()->withInput();
        }

        return view("Support::nid-support.nid-list");
    }

    public function getNIDList()
    {
        if (!ACL::getAccsessRight($this->aclName, '-V-')) {
            abort('400', 'You have no access right! Contact with system admin for more information. [SupC-972]');
        }
        $userNidList = NidSupport::getUserNidListFromMongo();
        $mode = ACL::getAccsessRight($this->aclName, '-E-');
        return Datatables::of(collect($userNidList))
            ->addColumn('action', function ($userNidList) use ($mode) {
                $actionContent = '';
                if ($mode && (in_array($userNidList->verification_flag, ['-9', '-5', '0']) || ($userNidList->verification_flag == -1 && $userNidList->no_of_try > 2))) {
                    $actionContent .= '<a href="'.url('support/re_submit_nid_status/'.Encryption::encode($userNidList->nid).'/'.Encryption::encode($userNidList->dob)).'" class="btn btn-xs btn-danger" onclick="return confirm(\'Are you sure you re-submit it ?\');">Re-submit</a> &nbsp;';
                }
                if ($userNidList->verification_flag == 1) {
                    $actionContent .= '<a href="javascript:void(0);" id="n_'.Encryption::encodeId($userNidList->nid).'" nid="'.Encryption::encodeId($userNidList->nid).'" birthdate="'.Encryption::encodeId($userNidList->dob).'" class="btn btn-xs btn-primary nidInfoView" ><i class="fa fa-eye"></i> View NID</a>';
                }
                if ($mode) {
                    $actionContent .= ' <a href="javascript:void(0);" id="n_'.Encryption::encodeId($userNidList->nid).'" nid="'.Encryption::encodeId($userNidList->nid).'" birthdate="'.Encryption::encodeId($userNidList->dob).'" class="btn btn-xs btn-info nidInfoEdit" ><b><i class="fa fa-edit"></i> Edit</b></a>';
                }
                return $actionContent;

            })
            ->addColumn('dob', function ($userNidList) {
                return $userNidList->dob;
            })
            ->addColumn('verification_flag', function ($userNidList) {
                $btn = '';
                if ($userNidList->verification_flag == 0 || $userNidList->verification_flag == -1) {
                    $btn .= '<button type="button" class="btn btn-warning btn-xs">In Process</button>';
                } elseif ($userNidList->verification_flag == 1) {
                    $btn .= '<button type="button" class="btn btn-success btn-xs">Success</button>';
                } elseif ($userNidList->verification_flag == -9) {
                    $btn .= '<button type="button" class="btn btn-danger btn-xs">Failed</button>';
                } elseif ($userNidList->verification_flag == -5) {
                    $btn .= '<button type="button" class="btn btn-danger btn-xs">Invalid NID</button>';
                } else {
                    return $userNidList->verification_flag;
                }
                return $btn;
            })
            ->addColumn('submitted_at', function ($userNidList) {
                return CommonFunction::updatedOn($userNidList->submitted_at);
            })
            ->removeColumn('id')
            ->make(true);
    }

    public function getNidDetails($nid, $birthdate)
    {
        if (!ACL::getAccsessRight($this->aclName, '-V-')) {
            abort('400', 'You have no access right! Contact with system admin for more information. [SupC-973]');
        }

        $nid = Encryption::decodeId($nid);
        $birthdate = Encryption::decodeId($birthdate);
        $NID = new NidSupport();
        $url = $NID->VERIFY_NID_URL($nid, $birthdate, 1, $flag = 'verify');
        $responses = @file_get_contents($url);
        $pecah = json_decode($responses);
        $responseCode = isset($pecah->mongoDBRequest->responseStatus->responseCode) ? intval($pecah->mongoDBRequest->responseStatus->responseCode) : 0;

        try {
            if ($responseCode == 101) {
                $nid = $pecah->mongoDBRequest->responseStatus->responseSubData->nid;
                $dob = $pecah->mongoDBRequest->responseStatus->responseSubData->dob;
                $pecah1 = json_decode($pecah->mongoDBRequest->responseStatus->responseData->data);

                return response()->json([
                    'success' => true,
                    'data' => $pecah1->return->voterInfo->voterInfo,
                    'nid' => $nid,
                    'dob' => $dob
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'data' => 'Something went wrong [SupC-NID-1010]'
                ]);
            }
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'data' => 'Something went wrong [SupC-NID-1020]'.$e->getMessage()
            ]);
        }
    }

    public function editNidSource($nid, $birthdate)
    {
        if (!ACL::getAccsessRight($this->aclName, '-E-')) {
            abort('400', 'You have no access right! Contact with system admin for more information. [SupC-974]');
        }

        $nid = Encryption::decodeId($nid);
        $birthdate = Encryption::decodeId($birthdate);

        $NID = new NidSupport();
        $url = $NID->VERIFY_NID_URL($nid, $birthdate, 'NA', $flag = 'list', 0, 0, 0, 1);

        $response = @file_get_contents($url);
        $response = str_replace('"_id"', '"id"', $response);
        $pecah = json_decode($response);
        $responseCode = isset($pecah->mongoDBRequest->responseStatus->responseCode) ? intval($pecah->mongoDBRequest->responseStatus->responseCode) : 0;
        $responsesData = $pecah->mongoDBRequest->responseStatus->responseData;
        $nidData = ($responseCode == 200) ? $responsesData : null;
        if ($nidData == null) {
            return 'NID Data not found';
        }

        $nid_id = $nidData->id;
        $nid = $nidData->nid;
        $dob = $nidData->dob;
        $verification_flag = $nidData->verification_flag;
        $no_of_try = $nidData->no_of_try;

        $encoded_nid_id = Encryption::encode($nid_id);

        try {
            if ($nidData) {
                return response()->json([
                    'success' => true,
                    'data' => [
                        'encoded_nid_id' => $encoded_nid_id,
                        'nid' => $nid,
                        'dob' => $dob,
                        'url' => 'support/nid-edit-store/'.Encryption::encode($nid).'/'.Encryption::encode($birthdate),
                        'verification_flag' => $verification_flag,
                        'no_of_try' => $no_of_try
                    ]
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'data' => 'Something went wrong [SupC-NID-1030]'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'data' => 'Something went wrong [SupC-NID-1040]'.$e->getMessage()
            ]);
        }
    }

    public function editNidStore(Request $request)
    {
        if (!ACL::getAccsessRight($this->aclName, '-E-')) {
            abort('400', 'You have no access right! Contact with system admin for more information. [SupC-975]');
        }

        $requested_nid = $request->get('nid');
        $requested_dob = date('Y-m-d', strtotime($request->get('dob')));

        $verification_flag = $request->get('verification_flag');
        $no_of_try = $request->get('no_of_try');
        try {
            $nid_id = Encryption::decode($request->get('nid_id'));

            $NID = new NidSupport();
            $url = $NID->VERIFY_NID_URL($requested_nid, $requested_dob, $verification_flag, $flag = 'manual_update', 0,
                $no_of_try, $nid_id);

            $responses = @file_get_contents($url);

            $response = json_decode($responses);
            $responseCode = isset($response->mongoDBRequest->responseStatus->responseCode) ? intval($response->mongoDBRequest->responseStatus->responseCode) : 0;

            if ($responseCode == 200) {
                Session::flash('success', 'NID status updated successfully.');
            } else {
                Session::flash('error', 'NID status is not updated unfortunately [SupC-NID-1050]');
            }

        } catch (\Exception $e) {
            Session::flash('error', 'Something went wrong '.$e->getMessage(). ' [SupC-NID-1060]');
        }

        return redirect('support/nid-list');
    }

    public function reSubmitNidStatus($nid, $dob)
    {
        if (!ACL::getAccsessRight($this->aclName, '-E-')) {
            abort('400', 'You have no access right! Contact with system admin for more information. [SupC-976]');
        }

        $nid = Encryption::decode($nid);
        $dob = Encryption::decode($dob);

        $VERIFICATION_FLAG = 0;
        $NID = new NidSupport();
        $url = $NID->VERIFY_NID_URL($nid, $dob, $VERIFICATION_FLAG, $flag = 'update');
        $responses = @file_get_contents($url);

        $pecah = json_decode($responses);
        $responseCode = isset($pecah->mongoDBRequest->responseStatus->responseCode) ? intval($pecah->mongoDBRequest->responseStatus->responseCode) : 0;
        $response = ($responseCode == 111) ? true : false;

        if ($response) {
            Session::flash('success', 'Successfully NID re-submitted.');
        } else {
            Session::flash('error', 'Sorry ! NID re-submit is failed. [SupC-NID-1070]');
        }
        return Redirect()->back();
    }

    public function searchNid(Request $request)
    {
        if (!ACL::getAccsessRight($this->aclName, '-V-')) {
            abort('400', 'You have no access right! Contact with system admin for more information. [SupC-977]');
        }

        $nid = trim($request->get('search_nid'));

        $NID = new NidSupport();
        $url = $NID->VERIFY_NID_URL($nid, 'NA', 'NA', 'list');
        $response = @file_get_contents($url);
        $response = str_replace('"_id"', '"id"', $response);
        $pecah = json_decode($response);
        $responseCode = isset($pecah->mongoDBRequest->responseStatus->responseCode) ? intval($pecah->mongoDBRequest->responseStatus->responseCode) : 0;
        $responsesData = $pecah->mongoDBRequest->responseStatus->responseData;
        $search_result = ($responseCode == 200) ? $responsesData : null;

        $mode = ACL::getAccsessRight($this->aclName, '-E-');
        return Datatables::of(collect($search_result))
            ->addColumn('action', function ($userNidList) use ($mode) {
                $actionContent = '';
                if ($mode && (in_array($userNidList->verification_flag, [
                            '-9', '-5', '0'
                        ]) || ($userNidList->verification_flag == -1 && $userNidList->no_of_try > 2))) {
                    $actionContent .= '<a href="'.url('support/re_submit_nid_status/'.Encryption::encode($userNidList->nid).'/'.Encryption::encode($userNidList->dob)).'" class="btn btn-xs btn-danger" onclick="return confirm(\'Are you sure you re-submit it ?\');">Re-submit</a> &nbsp;';
                }
                if ($userNidList->verification_flag == 1) {
                    $actionContent .= '<a href="javascript:void(0);" id="n_'.Encryption::encodeId($userNidList->nid).'"  nid="'.Encryption::encodeId($userNidList->nid).'" birthdate="'.Encryption::encodeId($userNidList->dob).'" class="btn btn-xs btn-primary nidInfoView" ><i class="fa fa-eye"></i> View NID</a>';
                }
                if ($mode) {
                    $actionContent .= ' <a href="javascript:void(0);" id="n_'.Encryption::encodeId($userNidList->nid).'"  nid="'.Encryption::encodeId($userNidList->nid).'" birthdate="'.Encryption::encodeId($userNidList->dob).'" class="btn btn-xs btn-info nidInfoEdit" ><b><i class="fa fa-edit"></i> Edit</b></a>';
                }
                return $actionContent;

            })
            ->addColumn('dob', function ($userNidList) {
                return $userNidList->dob;
            })
            ->addColumn('verification_flag', function ($userNidList) {
                $btn = '';
                if ($userNidList->verification_flag == 0 || $userNidList->verification_flag == -1) {
                    $btn .= '<button type="button" class="btn btn-warning btn-xs">In Process</button>';
                } elseif ($userNidList->verification_flag == 1) {
                    $btn .= '<button type="button" class="btn btn-success btn-xs">Success</button>';
                } elseif ($userNidList->verification_flag == -9) {
                    $btn .= '<button type="button" class="btn btn-danger btn-xs">Failed</button>';
                } elseif ($userNidList->verification_flag == -5) {
                    $btn .= '<button type="button" class="btn btn-danger btn-xs">Invalid NID</button>';
                } else {
                    return $userNidList->verification_flag;
                }
                return $btn;
            })
            ->addColumn('submitted_at', function ($userNidList) {
                return CommonFunction::updatedOn($userNidList->submitted_at);
            })
            ->removeColumn('id')
            ->make(true);
    }

    /* End of NID support */


















}
