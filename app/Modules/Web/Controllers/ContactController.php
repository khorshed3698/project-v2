<?php

namespace App\Modules\Web\Controllers;

use App\Http\Controllers\Controller;
use App\Libraries\CommonFunction;
use App\Libraries\UtilFunction;
use App\Libraries\Encryption;
use App\Modules\Web\Http\Requests\StoreContactRequest;
use App\Modules\Web\Models\Contact;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\View\View;
use yajra\Datatables\Datatables;
use Jenssegers\Agent\Agent;


class ContactController extends Controller
{

    protected $list_route = 'contact.list';

    const HTTP_STATUS_INTERNAL_SERVER_ERROR = 500;

    /**
     * @param Request $request
     * @return View|JsonResponse
     * @throws Exception
     */
    public function index(Request $request)
    {
        try {
            if ($request->ajax() && $request->isMethod('get')) {
                $list = Contact::query()
                    ->select('contacts.id','contacts.read_status', 'contacts.name', 'contacts.phone', 'contacts.updated_at', 'contacts.updated_by')
                    ->orderBy('contacts.id', 'DESC')
                    ->get();
                
                return Datatables::of($list)
                    ->editColumn('name', function ($row) {
                        $name = Str::limit($row->name, 50);
                        return $row->read_status == 0 ? "<b>$name</b>" : $name;
                    })
                    ->editColumn('email', function ($row) {
                        $email = Str::limit($row->phone, 50);
                        return $row->read_status == 0 ? "<b>$email</b>" : $email;
                    })
                    ->addColumn('action', function ($row) {
                        return '<a href="' . route('contact.view', ['id' => Encryption::encodeId($row->id)]) . '" class="btn btn-sm btn-outline-dark"> <i class="fa fa-folder-open"></i> Open</a><br>';
                    })
                    ->removeColumn('id')
                    ->make(true);
            }
            return view('Web::contact.list');
        } catch (Exception $e) {
            Log::error("Error occurred in ContactController@index ({$e->getFile()}:{$e->getLine()}): {$e->getMessage()}");
            Session::flash('error', "Something went wrong during application data load [Contact-101]");
            return Response::json(['error' => CommonFunction::showErrorPublic($e->getMessage())], self::HTTP_STATUS_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param StoreContactRequest $request
     * @return RedirectResponse
     */
    public function store(Request $request)
    {
        try {
            $contact = new Contact();
            $contact->name = $request->get('name');
            $contact->email = $request->get('email');
            $contact->phone = $request->get('phone');
            $contact->details = $request->get('details');
            $recaptchaResponse = $request->get('g-recaptcha-response');

            if (is_null($recaptchaResponse)) {
                return redirect()->back()->with('error', 'Please complete the reCAPTCHA to proceed.');
            }

            // $recaptchaVerificationResponse = UtilFunction::verifyGoogleReCaptcha($recaptchaResponse);
    
            // if(!$recaptchaVerificationResponse['data']->success){
            //     return redirect()->back()->with('error', 'Please complete the reCAPTCHA again to proceed.');
            // }

            // Validate reCAPTCHA
            $secretKey = config('recaptcha.private_key');
            $url = config('recaptcha.site_url');

            $validationResponse = file_get_contents($url . '?secret=' . $secretKey . '&response=' . $recaptchaResponse);
            $validationResponse = json_decode($validationResponse);

            if (!$validationResponse->success) {
                return response()->json(['responseCode' => 404, 'success' => false, 'message' => 'ReCAPTCHA validation failed. Please try again.'], 404);
            }

            $agent = new Agent();
            $os = $agent->platform();
            $ip = $_SERVER['REMOTE_ADDR'];
            $browser = $agent->browser();
            $geo = unserialize(file_get_contents("http://www.geoplugin.net/php.gp?ip=$ip"));
            $host = gethostbyaddr($_SERVER["REMOTE_ADDR"]);
            $agent_info = [
                'os' => $os,
                'browser' => $browser,
                'host' => $host,
                'ip' => $ip,
                'location' => $geo["geoplugin_regionName"],
            ];
            
            $contact->info = json_encode($agent_info);

            $contact->save();

            $message = 'Thank you for contacting us. We will get back to you soon.';

            // Return a JSON response with the success status and message
            return response()->json(['success' => true, 'message' => $message]);

        } catch (Exception $e) {
            Log::error("Error occurred in ContactController@Store ({$e->getFile()}:{$e->getLine()}): {$e->getMessage()}");
            Session::flash('error', "Something went wrong during application data load [Contact-102] ({$e->getFile()}:{$e->getLine()}): {$e->getMessage()}");
            return Redirect::back()->withInput();
        }
    }

    /**
     * @param $id
     * @return View|RedirectResponse
     */
    public function view($id)
    {
        try {
            $decode_id = Encryption::decodeId($id);
            $data['data'] = Contact::findOrFail($decode_id);
            if($data['data']-> read_status == 0){
                $data['data']-> read_status = 1;
                $data['data']->save();
            }
            $data['card_title'] = 'View Contact';
            $data['list_route'] = $this->list_route;

            return view('Web::contact.view', $data);

        } catch (Exception $e) {
            Log::error("Error occurred in ContactController@view ({$e->getFile()}:{$e->getLine()}): {$e->getMessage()}");
            Session::flash('error', "Something went wrong during application data view [Contact-103]");
            return redirect()->back();
        }
    }

    public function nextStepHtml()
    {
        return view('Web::home.partials.contact_us_next_step_html');
    }
}