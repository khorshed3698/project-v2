<?php

namespace App\Http\Controllers;

use App\HomePageArticle;
use App\Libraries\CommonFunction;
use App\Modules\Faq\Models\Faq;
use App\Modules\Settings\Models\ServiceDetails;
use App\Modules\Settings\Models\UserManual;
use App\Modules\Settings\Models\WhatsNew;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

class ArticlesController extends Controller
{
    public function __construct()
    {
        if (Session::has('lang')) {
            App::setLocale(Session::get('lang'));
        }
    }

    public function aboutBidaQuickServicePortal(Request $request)
    {
        $contents = HomePageArticle::where('page_name', 'about_bida_quick_service_portal')->pluck('page_content');

        $redirect_url = CommonFunction::getOssPidRedirectUrl();
        $whatsNew = WhatsNew::where('is_active', 1)->orderBy('id', 'DESC')->take(5)->get();
        $training_slider_image = training_slider_image();
        // for home page view log
        CommonFunction::createHomePageViewLog('AboutQuickServicePortal', $request);

        return view('articles.about_bida_quick_service_portal', compact('contents', 'redirect_url', 'whatsNew', 'training_slider_image'));
    }

    public function aboutOneStopService(Request $request)
    {
        $contents = HomePageArticle::where('page_name', 'about_one_stop_service')->pluck('page_content');

        $redirect_url = CommonFunction::getOssPidRedirectUrl();
        $whatsNew = WhatsNew::where('is_active', 1)->orderBy('id', 'DESC')->take(5)->get();
        $training_slider_image = training_slider_image();
        // for home page view log
        CommonFunction::createHomePageViewLog('OneStopService', $request);

        return view('articles.about_one_stop_service', compact('contents', 'redirect_url', 'whatsNew', 'training_slider_image'));
    }

    public function aboutOsspid(Request $request)
    {
        $contents = HomePageArticle::where('page_name', 'about_osspid')->pluck('page_content');

        $redirect_url = CommonFunction::getOssPidRedirectUrl();
        $whatsNew = WhatsNew::where('is_active', 1)->orderBy('id', 'DESC')->take(5)->get();
        $training_slider_image = training_slider_image();
        // for home page view log
        CommonFunction::createHomePageViewLog('AboutOSSPID', $request);

        return view('articles.about_osspid', compact('contents', 'redirect_url', 'whatsNew', 'training_slider_image'));
    }

    public function privacyStatement(Request $request)
    {
        $contents = HomePageArticle::where('page_name', 'privacy_statement')->pluck('page_content');

        $redirect_url = CommonFunction::getOssPidRedirectUrl();
        $whatsNew = WhatsNew::where('is_active', 1)->orderBy('id', 'DESC')->take(5)->get();
        $training_slider_image = training_slider_image();
        // for home page view log
        CommonFunction::createHomePageViewLog('PrivacyStatement', $request);

        return view('articles.privacy_statement', compact('contents', 'redirect_url', 'whatsNew', 'training_slider_image'));
    }

    public function availableOnlineServices(Request $request)
    {
        $dynamicSection = ServiceDetails::leftJoin('process_type as pt', 'pt.id', '=', 'service_details.process_type_id')
            ->where('service_details.status', 1)
            ->orderBy('pt.process_supper_name', 'asc')
            ->orderBy('pt.process_sub_name', 'desc')
            ->get(['pt.process_supper_name', 'pt.type_key', 'pt.process_sub_name', 'pt.id', 'service_details.id as sd_id', 'service_details.description']);

        $redirect_url = CommonFunction::getOssPidRedirectUrl();
        $whatsNew = WhatsNew::where('is_active', 1)->orderBy('id', 'DESC')->take(5)->get();
        $training_slider_image = training_slider_image();
        // for home page view log
        CommonFunction::createHomePageViewLog('availableServiceInfo', $request);

        return view('articles.available_online_services', compact('dynamicSection', 'redirect_url', 'whatsNew', 'training_slider_image'));
    }

    public function documentAndDownloads(Request $request)
    {
        $user_manuals = UserManual::where('status', 1)->orderBy('id', 'desc')->get();

        $redirect_url = CommonFunction::getOssPidRedirectUrl();
        $whatsNew = WhatsNew::where('is_active', 1)->orderBy('id', 'DESC')->take(5)->get();
        $training_slider_image = training_slider_image();
        // for home page view log
        CommonFunction::createHomePageViewLog('documentAndDownloads', $request);

        return view('articles.document_and_downloads', compact('user_manuals', 'redirect_url', 'whatsNew', 'training_slider_image'));
    }

    public function investmentPromotionAgencyBd(Request $request)
    {
        $regulatory_agencies = CommonFunction::getAgencyInfo('ipa');
        $title = 'Investment Promotion Agency (IPA)';

        $redirect_url = CommonFunction::getOssPidRedirectUrl();
        $whatsNew = WhatsNew::where('is_active', 1)->orderBy('id', 'DESC')->take(5)->get();
        $training_slider_image = training_slider_image();
        // for home page view log
        CommonFunction::createHomePageViewLog('agencyInfo', $request);

        return view('articles.agency', compact('regulatory_agencies', 'title', 'redirect_url', 'whatsNew', 'training_slider_image'));
    }

    public function certificateIssuingAgencyBbd(Request $request)
    {
        $regulatory_agencies = CommonFunction::getAgencyInfo('clp');
        $title = 'Certificate/ License/ Permit Issuing Agency (CLPIA)';

        $redirect_url = CommonFunction::getOssPidRedirectUrl();
        $whatsNew = WhatsNew::where('is_active', 1)->orderBy('id', 'DESC')->take(5)->get();
        $training_slider_image = training_slider_image();
        // for home page view log
        CommonFunction::createHomePageViewLog('agencyInfo', $request);

        return view('articles.agency', compact('regulatory_agencies', 'title', 'redirect_url', 'whatsNew', 'training_slider_image'));
    }

    public function utilityServiceProvider(Request $request)
    {
        $regulatory_agencies = CommonFunction::getAgencyInfo('utility');
        $title = 'Utility Service Provider';

        $redirect_url = CommonFunction::getOssPidRedirectUrl();
        $whatsNew = WhatsNew::where('is_active', 1)->orderBy('id', 'DESC')->take(5)->get();
        $training_slider_image = training_slider_image();
        // for home page view log
        CommonFunction::createHomePageViewLog('agencyInfo', $request);

        return view('articles.agency', compact('regulatory_agencies', 'title', 'redirect_url', 'whatsNew', 'training_slider_image'));
    }

    public function businessSector(Request $request)
    {
        $business_sectors = collect(DB::select("
            SELECT 
            sec_class.id, 
            sec_class.code,
            CONCAT('(',sec_section.code,') ',sec_section.name) AS section_name_code,
            CONCAT(CONCAT(sec_class.code,' - ',sec_class.name), '<p>',GROUP_CONCAT(CONCAT(subb_class.code,' - ',subb_class.name) SEPARATOR '<br />'),'</p>') class
            FROM (SELECT * FROM sector_info_bbs WHERE TYPE = 4) sec_class
            LEFT JOIN sector_info_bbs sec_group ON sec_class.pare_id = sec_group.id 
            LEFT JOIN sector_info_bbs sec_division ON sec_group.pare_id = sec_division.id 
            LEFT JOIN sector_info_bbs sec_section ON sec_division.pare_id = sec_section.id
            LEFT JOIN sector_info_bbs subb_class ON subb_class.pare_id = sec_class.id
            GROUP BY sec_class.id
            ORDER BY sec_section.code ASC;
        "));

        $redirect_url = CommonFunction::getOssPidRedirectUrl();
        $whatsNew = WhatsNew::where('is_active', 1)->orderBy('id', 'DESC')->take(5)->get();
        $training_slider_image = training_slider_image();
        // for home page view log
        CommonFunction::createHomePageViewLog('sectorInfo', $request);

        return view('articles.business_sector', compact('business_sectors', 'redirect_url', 'whatsNew', 'training_slider_image'));
    }

    /*
     * user support
     */
    public function support()
    {
        $faqs = Faq::leftJoin('faq_multitypes', 'faq.id', '=', 'faq_multitypes.faq_id')
            ->leftJoin('faq_types', 'faq_multitypes.faq_type_id', '=', 'faq_types.id')
            ->where('status', 'public')
            ->where('faq_types.name', 'login')
            ->get(['question', 'answer', 'status', 'faq_type_id as types', 'name as faq_type_name', 'faq.id as id']);

        return view("articles.support", compact('faqs'));
    }

    public function aboutBida(Request $request)
    {
        $contents = HomePageArticle::where('page_name', 'about_bida')->pluck('page_content');

        $redirect_url = CommonFunction::getOssPidRedirectUrl();
        $whatsNew = WhatsNew::where('is_active', 1)->orderBy('id', 'DESC')->take(5)->get();
        $training_slider_image = training_slider_image();
        // for home page view log
        CommonFunction::createHomePageViewLog('aboutBIDA', $request);

        return view('articles.about_bida', compact('contents', 'redirect_url', 'whatsNew', 'training_slider_image'));
    }

    public function termsOfServices(Request $request)
    {
        $contents = HomePageArticle::where('page_name', 'terms_of_services')->pluck('page_content');

        $redirect_url = CommonFunction::getOssPidRedirectUrl();
        $whatsNew = WhatsNew::where('is_active', 1)->orderBy('id', 'DESC')->take(5)->get();
        $training_slider_image = training_slider_image();
        // for home page view log
        CommonFunction::createHomePageViewLog('termsOfServices', $request);

        return view('articles.terms_of_services', compact('contents', 'redirect_url', 'whatsNew', 'training_slider_image'));
    }
    
}