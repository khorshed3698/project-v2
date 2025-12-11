<?php

namespace App\Modules\Web\Controllers;

use App\Http\Controllers\Controller;
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

class HomeArticlesController extends Controller
{
    public function aboutBida(Request $request)
    {
        $data['contents'] = HomePageArticle::where('page_name', 'about_bida')->pluck('page_content');

        $data['title'] = 'About BIDA';

        $data['id'] = 11;

        CommonFunction::createHomePageViewLog('aboutBIDA', $request);

        return view('Web::home.common_innerpage', $data);
    }

    public function aboutOneStopService(Request $request)
    {
        $data['contents'] = HomePageArticle::where('page_name', 'about_one_stop_service')->pluck('page_content');

        $data['title'] = 'About One Stop Service';

        $data['id'] = 10;

        CommonFunction::createHomePageViewLog('OneStopService', $request);

        return view('Web::home.common_innerpage', $data);
    }

    public function aboutOsspid(Request $request)
    {
        $data['contents'] = HomePageArticle::where('page_name', 'about_osspid')->pluck('page_content');

        $data['title'] = 'About OSSPID';

        $data['id'] = 6;

        CommonFunction::createHomePageViewLog('AboutOSSPID', $request);

        return view('Web::home.common_innerpage', $data);
    }

    public function aboutQuickServicePortal(Request $request)
    {
        $data['contents'] = HomePageArticle::where('page_name', 'about_bida_quick_service_portal')->pluck('page_content');

        $data['title'] = 'About Quick Service Portal';

        $data['id'] = 5;

        CommonFunction::createHomePageViewLog('AboutQuickServicePortal', $request);

        return view('Web::home.common_innerpage', $data);
    }

    public function availableOnlineServices(Request $request)
    {
        $queryResult = ServiceDetails::leftJoin('process_type as pt', 'pt.id', '=', 'service_details.process_type_id')
            ->where('service_details.status', 1)
            ->orderBy('pt.process_supper_name', 'asc')
            ->orderBy('pt.process_sub_name', 'desc')
            ->get(['pt.process_supper_name', 'pt.type_key', 'pt.process_sub_name', 'pt.id', 'service_details.id as sd_id', 'service_details.description']);

        $data['availableServices'] = [];
        $data['supperNameCount'] = 0;
        $data['subNameCount'] = 0;

        foreach ($queryResult as $row) {
            $supperName = $row->process_supper_name;
            $subName = $row->process_sub_name;

            if (empty($supperName) || empty($subName)) {
                continue;
            }

            if (!isset($data['availableServices'][$supperName])) {
                $data['availableServices'][$supperName] = [];
            }

            if (!isset($data['availableServices'][$supperName][$subName])) {
                $data['availableServices'][$supperName][$subName] = [
                    'sd_id' => $row->sd_id,
                    'description' => $row->description,
                ];
            }
        }

        CommonFunction::createHomePageViewLog('availableServiceInfo', $request);

        return view('Web::about.available_online_services', $data);
    }


    public function privacyStatement(Request $request)
    {
        $data['contents'] = HomePageArticle::where('page_name', 'privacy_statement')->pluck('page_content');

        $data['title'] = 'Privacy Statement';

        $data['id'] = 9;

        // for home page view log
        CommonFunction::createHomePageViewLog('PrivacyStatement', $request);

        return view('Web::home.common_innerpage', $data);
    }



    public function documentAndDownloads(Request $request)
    {
        $user_manuals = UserManual::where('status', 1)->orderBy('id', 'desc')->get();

        // for home page view log
        CommonFunction::createHomePageViewLog('documentAndDownloads', $request);

        return view('Web::home.document_and_downloads', compact('user_manuals'));
    }

    public function investmentPromotionAgencyBd(Request $request)
    {
        $regulatory_agencies = CommonFunction::getAgencyInfo('ipa');
        $title = 'Investment Promotion Agency (IPA)';

        // for home page view log
        CommonFunction::createHomePageViewLog('agencyInfo', $request);

        return view('Web::home.agency',  compact('regulatory_agencies', 'title'));
    }

    public function certificateIssuingAgencyBbd(Request $request)
    {
        $regulatory_agencies = CommonFunction::getAgencyInfo('clp');
        $title = 'Certificate/ License/ Permit Issuing Agency (CLPIA)';

        // for home page view log
        CommonFunction::createHomePageViewLog('agencyInfo', $request);

        return view('Web::home.agency',  compact('regulatory_agencies', 'title'));
    }

    public function utilityServiceProvider(Request $request)
    {
        $regulatory_agencies = CommonFunction::getAgencyInfo('utility');
        $title = 'Utility Service Provider';

        // for home page view log
        CommonFunction::createHomePageViewLog('agencyInfo', $request);

        return view('Web::home.agency',  compact('regulatory_agencies', 'title'));
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
        
        // for home page view log
        CommonFunction::createHomePageViewLog('sectorInfo', $request);

        return view('Web::about.business_sector', compact('business_sectors'));
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



    public function termsOfServices(Request $request)
    {
        $data['contents'] = HomePageArticle::where('page_name', 'terms_of_services')->pluck('page_content');

        $data['title'] = 'Terms of Services & Disclaimer';

        $data['id'] = 12;

        // for home page view log
        CommonFunction::createHomePageViewLog('termsOfServices', $request);

        return view('Web::home.common_innerpage', $data);

    }

}