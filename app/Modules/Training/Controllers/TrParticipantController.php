<?php

namespace App\Modules\Training\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Libraries\Encryption;
use App\Libraries\UtilFunction;
use App\Libraries\CommonFunction;
use Illuminate\Support\Facades\DB;
use App\Modules\Users\Models\Users;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Libraries\ACL;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Modules\Training\Models\TrSchedule;
use App\Modules\Training\Models\TrParticipant;
use App\Modules\ProcessPath\Models\ProcessList;
use App\Modules\Training\Models\TrPaymentMaster;
use App\Modules\Training\Models\TrScheduleSession;
use App\Modules\SonaliPayment\Models\SonaliPayment;
use App\Modules\SonaliPayment\Models\PaymentDetails;
use App\Modules\SonaliPayment\Models\PaymentDistribution;
use App\Modules\SonaliPayment\Models\PaymentConfiguration;
use App\Modules\SonaliPayment\Controllers\SonaliPaymentController;
use App\Modules\Training\Models\TrEvaluation;
use yajra\Datatables\Datatables;

class TrParticipantController extends Controller
{
    protected $process_type_id;
    protected $aclName;

    public function __construct()
    {
        $this->process_type_id = 700;
        $this->aclName = 'Training-Desk';

    }

    public function enrollParticipants(Request $request, $id)
    {
        
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'father_name' => 'required',
            'mother_name' => 'required',
            'dob' => 'required',
            'mobile_no' => 'required',
            'office_address' => 'required',
            'profession' => 'required',
            'org_name' => 'required',
            'designation' => 'required',
            'email' => 'required',
        ]);
        if($validator->fails()){
            Session::flash('error', "Something went wrong!");
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try{

            // Checking the Service Fee Payment(SFP) configuration for this service
            $payment_config = PaymentConfiguration::leftJoin('sp_payment_category', 'sp_payment_category.id', '=',
            'sp_payment_configuration.payment_category_id')
            ->where([
                'sp_payment_configuration.process_type_id' => $this->process_type_id,
                'sp_payment_configuration.payment_category_id' => 1,  // Submission Payment
                'sp_payment_configuration.status' => 1,
                'sp_payment_configuration.is_archive' => 0,
            ])->first(['sp_payment_configuration.*', 'sp_payment_category.name']);
            if (!$payment_config) {
                Session::flash('error', "Payment configuration not found [TRC-100]");
                return redirect()->back()->withInput();
            }

            // Checking the payment distributor under payment configuration
            $stakeDistribution = PaymentDistribution::where('sp_pay_config_id', $payment_config->id)
                ->where('status', 1)
                ->where('is_archive', 0)
                ->get(['id', 'stakeholder_ac_no', 'pay_amount', 'fix_status', 'purpose', 'purpose_sbl', 'distribution_type']);
            if ($stakeDistribution->isEmpty()) {

                Session::flash('error', "Stakeholder not found [TRC-101]");
                return redirect()->back()->withInput();
            }


            DB::beginTransaction();

            $trSessionId = Encryption::decodeId($request->session_id);
            $trScheduleId = Encryption::decodeId($id);
            $trSchedule = TrSchedule::where('id', $trScheduleId)->first();

            $trParticipant = new TrParticipant();
            
            $trParticipant->schedule_id = $trScheduleId;
            $trParticipant->session_id = $trSessionId;
            $trParticipant->batch_id = $trSchedule->batch_id;
            $trParticipant->full_name = $request->name;
            $trParticipant->father_name = $request->father_name;
            $trParticipant->mother_name = $request->mother_name;
            $trParticipant->dob = $request->dob;
            $trParticipant->moblie_no = $request->mobile_no;
            $trParticipant->office_address = $request->office_address;
            $trParticipant->profession = $request->profession;
            $trParticipant->organization_name = $request->org_name;
            $trParticipant->designation = $request->designation;
            $trParticipant->email = $request->email;
            $trParticipant->image_path = $request->applicant_photo ? $request->applicant_photo : '';
            $trParticipant->attachment = $request->validate_field_attachment ? $request->validate_field_attachment : '';

            $trParticipant->save();


            //  tr payment master data store
            $appData = TrPaymentMaster::firstOrNew(['tr_session_id' => $trSessionId,
                'tr_schedule_id' => $trScheduleId,
                'tr_participant_id' => $trParticipant->id,
            ]);
            $appData->tr_session_id = $trSessionId;
            $appData->tr_schedule_id =  $trScheduleId; // check
            $appData->tr_participant_id =  $trParticipant->id;
            $appData->process_type_id =  $this->process_type_id;
            $appData->user_id = Auth::user()->id;
            $appData->save();

            //  process data store with tracking number
            $processData = ProcessList::firstOrNew(['process_type_id' => $this->process_type_id, 'ref_id' => $appData->id]);
            $processData->status_id = -1;
            $processData->desk_id = 0;
            $processData->ref_id = $appData->id;
            $processData->process_type_id = $this->process_type_id;
            $processData->read_status = 0;
            $processData->save();

            if(empty($processData->tracking_no)) {
                $prefix = 'TR-' . date("dMY") . '-';
                UtilFunction::generateTrackingNumber($this->process_type_id, $processData->id, $prefix);
            }
           

            // sp payment data store
            // payment details data store
            $paymentInfo = SonaliPayment::firstOrNew([
                'app_id' => $appData->id, 'process_type_id' => $this->process_type_id,
                'payment_config_id' => $payment_config->id
            ]);
            $paymentInfo->payment_config_id = $payment_config->id;
            $paymentInfo->app_id = $appData->id;
            $paymentInfo->process_type_id = $this->process_type_id;
            $paymentInfo->app_tracking_no = '';
            $paymentInfo->payment_category_id = $payment_config->payment_category_id;
            // Concat Account no of stakeholder
            $account_no = "";
            foreach ($stakeDistribution as $distribution) {
                $account_no .= $distribution->stakeholder_ac_no . "-";
            }
            $account_numbers = rtrim($account_no, '-');
            // Concat Account no of stakeholder End
            $paymentInfo->receiver_ac_no = $account_numbers;
            $unfixed_amount_array = $this->unfixedAmountsForPayment($payment_config);
            $paymentInfo->pay_amount = $unfixed_amount_array['total_unfixed_amount'] + $payment_config->amount;
            $paymentInfo->vat_on_pay_amount = $unfixed_amount_array['total_vat_on_pay_amount'];
            $paymentInfo->total_amount = ($paymentInfo->pay_amount + $paymentInfo->vat_on_pay_amount);
            $paymentInfo->contact_name = $trParticipant->full_name;
            $paymentInfo->contact_email = $trParticipant->email;
            $paymentInfo->contact_no = $trParticipant->moblie_no;
            $paymentInfo->address = $trParticipant->office_address;
            $paymentInfo->sl_no = 1; // Always 1
            $paymentInfo->save();

            $appData->sf_payment_id = $paymentInfo->id;
            $appData->save();

            // Payment Details By Stakeholders
            foreach ($stakeDistribution as $distribution) {
                $paymentDetails = PaymentDetails::firstOrNew([
                    'sp_payment_id' => $paymentInfo->id, 'payment_distribution_id' => $distribution->id
                ]);
                $paymentDetails->sp_payment_id = $paymentInfo->id;
                $paymentDetails->payment_distribution_id = $distribution->id;
                if ($distribution->fix_status == 1) {
                    $paymentDetails->pay_amount = $distribution->pay_amount;
                } else {
                    $paymentDetails->pay_amount = $unfixed_amount_array['amounts'][$distribution->distribution_type];
                }
                $paymentDetails->receiver_ac_no = $distribution->stakeholder_ac_no;
                $paymentDetails->purpose = $distribution->purpose;
                $paymentDetails->purpose_sbl = $distribution->purpose_sbl;
                $paymentDetails->fix_status = $distribution->fix_status;
                $paymentDetails->distribution_type = $distribution->distribution_type;
                $paymentDetails->save();
            }
            // Payment Details By Stakeholders End

            DB::commit();

            return redirect('spg/initiate-multiple/' . Encryption::encodeId($paymentInfo->id));

        }
        catch(\Exception $e){
            Log::error('TRSchedule : ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [TRS-472]');
            Session::flash('error', "Something went wrong!");
            return response()->json([
                'responseCode' => 1,
                'html' => "<attachment_typeh4 class='custom-err-msg'>" . CommonFunction::showErrorPublic($e->getMessage()) . ' [TRS-472]' . "</attachment_typeh4>"
            ]);
        }
        
        return redirect()->back()->with('success', 'Participant enrolled successfully');
        
    }

    public function afterPayment($payment_id)
    {

        try {

            $payment_id = Encryption::decodeId($payment_id);
            $paymentInfo = SonaliPayment::find($payment_id);

            $processData = ProcessList::leftJoin('process_type', 'process_type.id', '=', 'process_list.process_type_id')
                ->where('ref_id', $paymentInfo->app_id)
                ->where('process_type_id', $paymentInfo->process_type_id)
                ->first([
                    'process_type.name as process_type_name',
                    'process_type.process_supper_name',
                    'process_type.process_sub_name',
                    'process_type.form_id',
                    'process_list.*'
                ]);

            // get users email and phone
            $applicantEmailPhone =  Users::query()
                ->where('id', CommonFunction::getUserId())
                ->where('user_status', 'active')
                ->get(['user_email', 'user_phone']);

            $trPaymentMaster = TrPaymentMaster::find($paymentInfo->app_id);

            $appInfo = [
                'app_id' => $processData->ref_id,
                'status_id' => $processData->status_id,
                'process_type_id' => $processData->process_type_id,
                'tracking_no' => $processData->tracking_no,
                'process_type_name' => $processData->process_type_name,
                'process_supper_name' => $processData->process_supper_name,
                'process_sub_name' => $processData->process_sub_name,
                'remarks' => ''
            ];

            DB::beginTransaction();
            // 1 = Service Fee Payment
            // tracking no generate only when payment is Service Fee Payment
            if ($paymentInfo->payment_category_id == 1) {
                if ($processData->status_id != '-1') {
                    Session::flash('error', 'This is an invalid status, it\'s not possible to get the next status. [TRC-912]');
                    return redirect('training/upcoming-course');
                }

                TrScheduleSession::where('id', $trPaymentMaster->tr_session_id)->update([
                    'status' => 'Ongoing'
                ]);

                TrParticipant::where('id', $trPaymentMaster->tr_participant_id)->update([
                    'is_paid' => 1
                ]);

                $processData->status_id = 1;
                $processData->desk_id = 0;
                $processData->process_desc = 'Service and Govt. Fee Payment completed successfully.';
                $processData->submitted_at = Carbon::now(); // application submitted Date

                // application submission mail sending
                CommonFunction::sendEmailSMS('APP_SUBMIT', $appInfo, $applicantEmailPhone);

            } elseif ($paymentInfo->payment_category_id == 2) {
                

            }

            $processData->save();
            DB::commit();
            Session::flash('success', 'Your application has been successfully submitted after payment. You will receive a confirmation email soon.');
            return redirect('training/upcoming-course');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('WPNAfterPayment: ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [TRC-1081]');
            Session::flash('error', 'Something went wrong!, application not updated after payment. Error : ' . $e->getMessage() . '[TRC-1081]');
            return redirect('training/upcoming-course');
        }
    }

    public function unfixedAmountsForPayment($payment_config, $relevant_info_array = [])
    {
        /**
         * DB Table Name: sp_payment_category
         * Payment Categories:
         * 1 = Service Fee Payment
         * 2 = Government Fee Payment
         * 3 = Government & Service Fee Payment
         * 4 = Manual Service Fee Payment
         * 5 = Manual Government Fee Payment
         * 6 = Manual Government & Service Fee Payment
         */

        $unfixed_amount_array = [
            1 => 0, // Vendor-Service-Fee
            2 => 0, // Govt-Service-Fee
            3 => 0, // Govt. Application Fee
            4 => 0, // Vendor-Vat-Fee
            5 => 0, // Govt-Vat-Fee
            6 => 0, // Govt-Vendor-Vat-Fee
        ];


        if ($payment_config->payment_category_id === 1) {
            // Govt-Vendor-Vat-Fee
            $vat_percentage = SonaliPaymentController::getGovtVendorVatPercentage();
            if (empty($vat_percentage)) {
                abort('Please, configure the value for VAT.');
            }

            // $unfixed_amount_array[1] = 250; // distribution
            // $unfixed_amount_array[2] = 250; // distribution
            // $unfixed_amount_array[6] = (($unfixed_amount_array[1]+$unfixed_amount_array[2]) / 100) * $vat_percentage;

            $unfixed_amount_array[6] = ($payment_config->amount / 100) * $vat_percentage;

        } elseif ($payment_config->payment_category_id === 2) {


        } elseif ($payment_config->payment_category_id === 3) {

        }

        $unfixed_amount_total = 0;
        $vat_on_pay_amount_total = 0;
        foreach ($unfixed_amount_array as $key => $amount) {
            // 4 = Vendor-Vat-Fee, 5 = Govt-Vat-Fee, 6 = Govt-Vendor-Vat-Fee
            if (in_array($key, [4, 5, 6])) {
                $vat_on_pay_amount_total += $amount;
            } else {
                $unfixed_amount_total += $amount;
            }
        }

        return [
            'amounts' => $unfixed_amount_array,
            'total_unfixed_amount' => $unfixed_amount_total,
            'total_vat_on_pay_amount' => $vat_on_pay_amount_total,
        ];
    }


    // route checked
    public function updateParticipantsData(Request $request)
    {
        $id = Encryption::decodeId($request->participantsId);

        try {
            $name = $request->newName;
            $status = $request->status;
            $participant = TrParticipant::find($id);
            $participant->full_name = $name ? $name : $participant->full_name;
            $participant->status = $status ? $status : $participant->status;
            $participant->save();

            return response()->json(['responseCode' => 1, 'responseMessage' => 'Participant updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['responseCode' => 0, 'responseMessage' => 'Error: ' . $e->getMessage()]);
        }
    }

    // route checked
    public function participantInfo($course_id, $part_id)
    {
        if(!ACL::getAccsessRight($this->aclName, '-V-')){
            die('You have no access right! Please contact system administration for more information');
        }
        $part_id = Encryption::decodeId($part_id);
        $course_id = Encryption::decodeId($course_id);
        $participant = TrParticipant::where('id', $part_id)->first();
        $course = TrSchedule::where('id', $course_id)->first();
        $is_completed = TrEvaluation::where('participant_id', $part_id)->where('schedule_id', $course_id)->where('batch_id', $course->batch_id)->first();
        if ($is_completed) {
            $is_completed = 1;
        } else {
            $is_completed = 0;
        }
        return view('Training::tr_schedule.participant_info', compact('participant', 'course', 'is_completed'));
    }

    // route checked
    public function getUserData(Request $request)
    {

        $session_id = Encryption::decodeId($request->session_id);
        $participants = TrParticipant::where('schedule_id', $session_id)->where('is_paid', 1)->get();
        $serial = 1;
        return Datatables::of($participants)
            ->editColumn('sl', function () use (&$serial) {
                return $serial++;
            })
            ->editColumn('user_first_name', function ($participant) {
                return $participant->full_name;
            })
            ->editColumn('user_mobile', function ($participant) {
                return $participant->moblie_no;
            })
            ->editColumn('user_email', function ($participant) {
                return $participant->email;
            })

            ->addColumn('action', function ($list) {
                if (in_array(Auth::user()->user_type, ['4x404', '1x101']) && (ACL::getAccsessRight('Training-Desk','-E-') || ACL::getAccsessRight('Training-Desk','-V-'))) {
                    $html = '<a href="' . url('training/schedule-participant-info/' . Encryption::encodeId($list->schedule_id) . '/' . Encryption::encodeId($list->id)) . '" class="btn btn-sm btn-primary"> Open</a>
                        <a href="javascript:void(0)" class="btn-sm btn btn-danger actionActivates" data-id="' . Encryption::encodeId($list->id) . ' " data-action="Decline"> Decline </a> <a href="javascript:void(0)" class="btn btn-sm btn-success actionActivates" data-id="' . Encryption::encodeId($list->id) . ' " data-action="Confirm"> Confirm </a>';
                    // if ($list->status == 'Applied') {
                    //     $html .= '<a href="javascript:void(0)" class="btn btn-sm btn-info actionActivates" data-id="' . Encryption::encodeId($list->id) . ' " data-action="Shortlist"> Shortlist </a>
                    // <a href="javascript:void(0)" class="btn btn-sm btn-success actionActivates" data-id="' . Encryption::encodeId($list->id) . ' " data-action="Confirm"> Confirm </a>';
                    // } elseif ($list->status == 'Shortlisted') {
                    //     $html .= '<a href="javascript:void(0)" class="btn btn-sm btn-success actionActivates" data-id="' . Encryption::encodeId($list->id) . ' " data-action="Confirm"> Confirm </a>';
                    // }
                    if ($list->status == 'Declined') {
                        $html = '<a href="' . url('training/schedule-participant-info/' . Encryption::encodeId($list->schedule_id) . '/' . Encryption::encodeId($list->id)) . '" class="btn btn-sm btn-primary"> Open</a> <a href="javascript:void(0)" class="btn btn-sm btn-success actionActivates" data-id="' . Encryption::encodeId($list->id) . ' " data-action="Confirm"> Confirm </a>';
                    } else if ($list->status == 'Confirmed') {
                        $html = '<a href="' . url('training/schedule-participant-info/' . Encryption::encodeId($list->schedule_id) . '/' . Encryption::encodeId($list->id)) . '" class="btn btn-sm btn-primary"> Open</a>  <a href="javascript:void(0)" class="btn-sm btn btn-danger actionActivates" data-id="' . Encryption::encodeId($list->id) . ' " data-action="Decline"> Decline </a>';
                    }
                } else {
                    if(ACL::getAccsessRight('Training-Desk', '-V-'))
                    {
                        $html = '<a href="' . url('training/schedule-participant-info/' . Encryption::encodeId($list->schedule_id) . '/' . Encryption::encodeId($list->id)) . '" class="btn btn-sm btn-primary"> Open</a>';
                    }
                    
                }
                return isset($html) ? $html : '';
            })
            ->addColumn('status', function ($list) {
                if ($list->status == 'Declined') {
                    return $ht = '<label class="label label-danger">' . $list->status . ' </label>';
                } else {
                    return $ht = '<label class="label label-success">' . $list->status . ' </label>';
                }
            })
            ->addColumn('payment', function ($list) {
                if ($list->is_paid == 1) {
                    return $ht = '<label class="label label-info">Paid</label>';
                } else {
                    return $ht = '<label class="label label-primary">Not Paid</label>';
                }
            })
            ->make(true);
    }

    // route checked
    public function getstatusWiseTrainingUserData(Request $request)
    {
        $session_id = Encryption::decodeId($request->session_id);
        $participant_status = $request->participant_status;
        $participants = TrParticipant::where('schedule_id', $session_id)->where('status', $participant_status)->get();
        $serial = 1;

        return Datatables::of($participants)
            ->editColumn('sl', function () use (&$serial) {
                return $serial++;
            })
            ->editColumn('user_first_name', function ($participant) {
                return $participant->full_name;
            })
            ->editColumn('user_mobile', function ($participant) {
                return $participant->moblie_no;
            })
            ->editColumn('user_email', function ($participant) {
                return $participant->email;
            })

            ->addColumn('action', function ($list) {
                if (in_array(Auth::user()->user_type, ['4x404', '1x101']) && (ACL::getAccsessRight('Training-Desk','-E-') || ACL::getAccsessRight('Training-Desk','-V-'))) {
                    $html = '<a href="' . url('training/schedule-participant-info/' . Encryption::encodeId($list->schedule_id) . '/' . Encryption::encodeId($list->id)) . '" class="btn btn-sm btn-primary"> Open</a>
                        <a href="javascript:void(0)" class="btn-sm btn btn-danger actionActivates" data-id="' . Encryption::encodeId($list->id) . ' " data-action="Decline"> Decline </a> <a href="javascript:void(0)" class="btn btn-sm btn-success actionActivates" data-id="' . Encryption::encodeId($list->id) . ' " data-action="Confirm"> Confirm </a>';
                    if ($list->status == 'Declined') {
                        $html = '<a href="' . url('training/schedule-participant-info/' . Encryption::encodeId($list->schedule_id) . '/' . Encryption::encodeId($list->id)) . '" class="btn btn-sm btn-primary"> Open</a> <a href="javascript:void(0)" class="btn btn-sm btn-success actionActivates" data-id="' . Encryption::encodeId($list->id) . ' " data-action="Confirm"> Confirm </a>';
                    } else if ($list->status == 'Confirmed') {
                        $html = '<a href="' . url('training/schedule-participant-info/' . Encryption::encodeId($list->schedule_id) . '/' . Encryption::encodeId($list->id)) . '" class="btn btn-sm btn-primary"> Open</a>  <a href="javascript:void(0)" class="btn-sm btn btn-danger actionActivates" data-id="' . Encryption::encodeId($list->id) . ' " data-action="Decline"> Decline </a>';
                    }
                } else {
                    if(ACL::getAccsessRight('Training-Desk', '-V-'))
                    {
                        $html = '<a href="' . url('training/schedule-participant-info/' . Encryption::encodeId($list->schedule_id) . '/' . Encryption::encodeId($list->id)) . '" class="btn btn-sm btn-primary"> Open</a>';
                    }
                    
                }
                return isset($html) ? $html : '';
            })
            ->addColumn('status', function ($list) {
                if ($list->status == 'Declined') {
                    return $ht = '<label class="label label-danger">' . $list->status . ' </label>';
                } else {
                    return $ht = '<label class="label label-success">' . $list->status . ' </label>';
                }
            })
            ->addColumn('payment', function ($list) {
                if ($list->is_paid == 1) {
                    return $ht = '<label class="label label-info">Paid</label>';
                } else {
                    return $ht = '<label class="label label-primary">Not Paid</label>';
                }
            })
            ->make(true);
    }

    // route checked
    public function downloadParticipantsAll($id)
    {

        $session_id = Encryption::decodeId($id);
        DB::statement(DB::raw('set @rownum=0'));
        $list = TrParticipant::leftJoin('tr_schedules', 'tr_participants.schedule_id', '=', 'tr_schedules.id')
            ->leftJoin('tr_courses', 'tr_schedules.course_id', '=', 'tr_courses.id')
            ->where('tr_participants.schedule_id', $session_id)
            ->get([
                DB::raw('@rownum := @rownum+1 AS sl'),
                'tr_participants.full_name',
                'tr_participants.moblie_no',
                'tr_participants.email',
                'tr_participants.id',
                'tr_participants.status',
                'tr_courses.course_title',
            ]);

        $courseName = $list[0]->course_title ? $list[0]->course_title : 'course';

        $contents = view('Training::tr_schedule.participants-info-excel', compact("list"))->render();

        $headers = array(
            "Content-type" => "application/vnd.ms-excel",
            "Expires" => "0",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Content-Disposition" => "attachment;filename=$courseName-participant.xls",
        );

        return response()->make($contents, 200, $headers);
    }

    // route checked
    public function participantActivates(Request $request)
    {
        if(!ACL::getAccsessRight($this->aclName, '-E-')){
            die('You have no access right! Please contact system administration for more information');
        }
        $status = '';
        if ($request->data_action == 'Shortlist') {
            $status = 'Shortlisted';
        } elseif ($request->data_action == 'Confirm') {
            $status = 'Confirmed';
            $is_active = 1;
        } elseif ($request->data_action == 'Decline') {
            $status = 'Declined';
            $is_active = 0;
        }

        $participants = TrParticipant::where('id', Encryption::decodeId($request->participant_id))->first();
        $participants->status = $status;
        $participants->is_active = $is_active;
        $participants->save();

        return response()->json(['responseCode' => 1]);
    }

    public function courseDetails($id)
    {
        $decodeId = Encryption::decodeId($id);
        $course = TrSchedule::where('id', $decodeId)->first();
        $scheduleSession = TrScheduleSession::where('app_id', $decodeId)->get();
        $courseList = TrSchedule::where('is_active', 1)->get();
        $participantinfo = TrParticipant::where('schedule_id', $decodeId)
            ->where('batch_id', $course->batch_id)
            ->where('is_paid', 1)
            ->where('created_by', Auth::user()->id)
            ->first();
        
        if ($participantinfo) {
            $participant = $participantinfo->id;
            $is_evaluated = TrEvaluation::where('participant_id', $participant)->where('schedule_id', $decodeId)->where('batch_id', $course->batch_id)->where('evaluation_type', 'Final Evaluation')->count();
        }

        // session destroy
        Session::forget('training_course_url');

        return view('Training::course-details', compact('course', 'scheduleSession', 'courseList', 'is_evaluated', 'participant', 'participantinfo'));
    }

    // route checked
    public function checkSessionParticipant(Request $request)
    {
        if (!$request->ajax()) {
            return 'Sorry! this is a request without proper way [WP-1005]';
        }
        $session_id = $request->get('session_id');

        $receiverInfoCount = TrParticipant::leftJoin('tr_schedule_sessions', 'tr_schedule_sessions.id', '=', 'tr_participants.session_id')
            ->where('tr_participants.status', 'Confirmed')
            ->where('tr_participants.is_paid', 1)
            ->where('tr_participants.session_id', $session_id)
            ->count();

        if ($receiverInfoCount > 0) {
            $data = ['responseCode' => 1];
        } else {
            $data = ['responseCode' => 0];
        }

        return response()->json($data);
    }


}
