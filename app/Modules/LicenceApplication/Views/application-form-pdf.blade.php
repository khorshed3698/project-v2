<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="{{ asset("assets/stylesheets/styles.css") }}" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">
</head>
<body>

<section class="content" id="applicationForm">
    <div class="col-md-12">
        <div class="box">
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12" style="text-align: center">
                        <img src="assets/images/bida_logo.png" style="width: 100px"/><br/>
                        <br>
                        Bangladesh Investment Development Authority (BIDA)<br/>
                        Application for Business Registration
                    </div>
                </div>
                <div class="panel panel-info"  id="inputForm">
                    <div class="panel-heading">Application for Business Registration</div>
                    <div class="panel-body">
                        <table width="100%">
                            <tr>
                                <td style="padding-top: 5px; padding-right: 5px; padding-left: 15px; padding-bottom: 5px;">Tracking no. : <span>{{ $appInfo->tracking_no  }}</span></td>
                                <td style="padding: 5px;">Date of Submission: <span> {{ \App\Libraries\CommonFunction::formateDate($appInfo->submitted_at)  }} </span></td>
                            </tr>
                            <tr>
                                <td style="padding-top: 5px; padding-right: 5px; padding-left: 15px; padding-bottom: 5px;">Current Status :  <span>{{$appInfo->status_name}}</span></td>
                                <td style="padding: 5px;">Current Desk :
                                    @if($appInfo->desk_id != 0)
                                    <span>  {{ \App\Libraries\CommonFunction::getDeskName($appInfo->desk_id)  }} </span>
                                    @else
                                        <span>Applicant</span>
                                    @endif

                                </td>
                            </tr>
                        </table>


                        <div class="panel panel-info">
                            <div class="panel-heading">Company Information</div>
                            <div class="panel-body">
                                <div class="col-md-12">
                                    <table width="100%" cellpadding="10">
                                        <tbody>
                                        <tr>
                                            <td colspan="2" style="padding: 5px;" >
                                                Name of Organization/ Company/ Industrial Project :


                                                <span> {{ (!empty($appInfo->company_id)) ? $userCompanyList[0]->company_name : 'N/A'  }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" style="padding: 5px;" >
                                                Name of Organization/ Company/ Industrial Project (বাংলা):

                                                <span> {{ (!empty($appInfo->company_id)) ? $userCompanyList[0]->company_name_bn : 'N/A'  }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" style="padding: 5px;" >
                                                Department :

                                                <span>{{ (!empty($appInfo->department_id)) ? $departmentList[$appInfo->department_id]:'N/A'  }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="50%" style="padding: 5px;" >

                                                Country of Origin :
                                                <span> {{ (!empty($appInfo->country_of_origin_id)) ? $countries[$appInfo->country_of_origin_id]:'N/A'  }}</span>
                                            </td>

                                            <td width="50%" style="padding: 5px;" >
                                                Type of the organization :
                                                <span>{{ (!empty($appInfo->organization_type_id)) ? $eaOrganizationType[$appInfo->organization_type_id]:'N/A'  }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                Status of the organization :
                                                <span> {{ (!empty($appInfo->organization_status_id)) ? $eaOrganizationStatus[$appInfo->organization_status_id]:'N/A'  }}</span>
                                            </td>

                                            <td width="50%" style="padding: 5px;" >
                                                Ownership status :
                                                <span>{{ (!empty($appInfo->ownership_status_id)) ? $eaOwnershipStatus[$appInfo->ownership_status_id]:'N/A'  }}</span>
                                            </td>


                                        </tr>
                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                Business sector :
                                                <span> {{ (!empty($appInfo->business_sector_id)) ? $sectors[$appInfo->business_sector_id]:'N/A'  }}</span>
                                            </td>

                                            <td width="50%" style="padding: 5px;" >

                                                Sub sector:
                                                <span>{{ (!empty($appInfo->business_sub_sector_id)) ? $sub_sectors[$appInfo->business_sub_sector_id] :'N/A'  }}</span>

                                            </td>

                                        </tr>
                                        <tr>

                                            <td colspan="2" style="padding: 5px;" >
                                                Major activities in brief :
                                                <span>{{ (!empty($appInfo->major_activities)) ? $appInfo->major_activities :'N/A'  }}</span>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-info">
                            <div class="panel-heading">B. Information of Principal Promoter/Chairman/Managing Director/CEO</div>
                            <div class="panel-body">
                                <div class="col-md-12">
                                    <table width="100%" cellpadding="10">
                                        <tbody>
                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                Country :
                                                <span> {{ (!empty($appInfo->ceo_country_id)) ? $countries[$appInfo->ceo_country_id]:'N/A'  }}</span>
                                            </td>
                                            <td width="50%" style="padding: 5px;" >
                                                Date of Birth :
                                                <span>{{ (!empty($appInfo->ceo_dob)) ? date('d-M-Y', strtotime($appInfo->ceo_dob)):'N/A'  }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            @if($appInfo->ceo_country_id == 18)
                                                <td width="50%" style="padding: 5px;">
                                                    NID No. :
                                                    <span>{{ (!empty($appInfo->ceo_nid)) ? $appInfo->ceo_nid:'N/A'  }}</span>
                                                </td>
                                            @else
                                                <td width="50%" style="padding: 5px;">
                                                    Passport No. :
                                                    <span>{{ (!empty($appInfo->ceo_passport_no)) ? $appInfo->ceo_passport_no:'N/A'  }}</span>
                                                </td>
                                            @endif

                                            <td width="50%" style="padding: 5px;" >
                                                Designation :
                                                <span>{{ (!empty($appInfo->ceo_designation)) ? $appInfo->ceo_designation:'N/A'  }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                Full Name :
                                                <span> {{ (!empty($appInfo->ceo_full_name)) ? $appInfo->ceo_full_name:'N/A'  }}</span>
                                            </td>
                                            @if($appInfo->ceo_country_id == 18)
                                                <td width="50%" style="padding: 5px;" >
                                                    District/City/State :
                                                    <span>{{ (!empty($appInfo->ceo_district_id)) ? $districts[$appInfo->ceo_district_id]:'N/A'  }}</span>
                                                </td>
                                            @else
                                                <td width="50%" style="padding: 5px;" >
                                                    City :
                                                    <span>{{ (!empty($appInfo->ceo_city)) ? $appInfo->ceo_city:'N/A'  }}</span>
                                                </td>
                                            @endif
                                        </tr>
                                        <tr>
                                            @if($appInfo->ceo_country_id == 18)
                                                <td width="50%" style="padding: 5px;" >
                                                    Police Station/Town :
                                                    <span> {{ (!empty($appInfo->ceo_thana_id)) ? $thana_eng[$appInfo->ceo_thana_id]:'N/A'  }}</span>
                                                </td>
                                            @else
                                                <td width="50%" style="padding: 5px;" >
                                                    State/Province :
                                                    <span>{{ (!empty($appInfo->ceo_state)) ? $appInfo->ceo_state:'N/A'  }}</span>
                                                </td>
                                            @endif

                                            <td width="50%" style="padding: 5px;" >
                                                Post/Zip Code :
                                                <span>{{ (!empty($appInfo->ceo_post_code)) ? $appInfo->ceo_post_code:'N/A'  }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                House,Flat/Apartment,Road :
                                                <span> {{ (!empty($appInfo->ceo_address)) ? $appInfo->ceo_address:'N/A'  }}</span>
                                            </td>
                                            <td width="50%" style="padding: 5px;" >
                                                Telephone No :
                                                <span>{{ (!empty($appInfo->ceo_telephone_no)) ? $appInfo->ceo_telephone_no:'N/A'  }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                Mobile No :
                                                <span> {{ (!empty($appInfo->ceo_mobile_no)) ? $appInfo->ceo_mobile_no:'N/A'  }}</span>
                                            </td>
                                            <td width="50%" style="padding: 5px;" >
                                                Father's Name :
                                                <span> {{ (!empty($appInfo->ceo_father_name)) ? $appInfo->ceo_father_name:'N/A'  }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="50%" style="padding: 5px;">
                                                Email :
                                                <span>{{ (!empty($appInfo->ceo_email)) ? $appInfo->ceo_email:'N/A'  }}</span>
                                            </td>
                                            <td width="50%" style="padding: 5px;" >
                                                Mother's Name :
                                                <span>{{ (!empty($appInfo->ceo_mother_name)) ? $appInfo->ceo_mother_name:'N/A'  }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                Fax No :
                                                <span>{{ (!empty($appInfo->ceo_fax_no)) ? $appInfo->ceo_fax_no:'N/A'  }}</span>
                                            </td>
                                            <td width="50%" style="padding: 5px;" >
                                                Spouse name :
                                                <span>{{ (!empty($appInfo->ceo_spouse_name)) ? $appInfo->ceo_spouse_name:'N/A'  }}</span>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>


                        <div class="panel panel-info">
                            <div class="panel-heading">Office Address</div>
                            <div class="panel-body">
                                <div class="col-md-12">
                                    <table width="100%" cellpadding="10">
                                        <tbody>
                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                Division :
                                                <span> {{ (!empty($appInfo->office_division_id)) ? $divisions[$appInfo->office_division_id]:'N/A'  }}</span>
                                            </td>
                                            <td width="50%" style="padding: 5px;" >
                                                District :
                                                <span> {{ (!empty($appInfo->office_district_id)) ? $districts[$appInfo->office_district_id]:'N/A'  }}</span>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                Police Station :
                                                <span> {{ (!empty($appInfo->office_thana_id)) ? $thana_eng[$appInfo->office_thana_id]:'N/A'  }}</span>
                                            </td>
                                            <td width="50%" style="padding: 5px;" >
                                                Post Office :
                                                <span> {{ (!empty($appInfo->office_post_office)) ? $appInfo->office_post_office:'N/A'  }}</span>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                Post Code :
                                                <span>{{ (!empty($appInfo->office_post_code)) ? $appInfo->office_post_code:'N/A'  }}</span>
                                            </td>
                                            <td width="50%" style="padding: 5px;" >
                                                House,Flat/Apartment,Road :
                                                <span> {{ (!empty($appInfo->office_address)) ? $appInfo->office_address:'N/A'  }}</span>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                Telephone No :
                                                <span>{{ (!empty($appInfo->office_telephone_no)) ? $appInfo->office_telephone_no:'N/A'  }}</span>
                                            </td>
                                            <td width="50%" style="padding: 5px;" >
                                                Mobile No :
                                                <span> {{ (!empty($appInfo->office_mobile_no)) ? $appInfo->office_mobile_no:'N/A'  }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="50%" style="padding: 5px;">
                                                Fax No :
                                                <span>{{ (!empty($appInfo->office_fax_no)) ? $appInfo->office_fax_no:'N/A'  }}</span>
                                            </td>
                                            <td width="50%" style="padding: 5px;">
                                                Email :
                                                <span>{{ (!empty($appInfo->office_email)) ? $appInfo->office_email:'N/A'  }}</span>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-info">
                            <div class="panel-heading">Factory Address (Optional)</div>
                            <div class="panel-body">
                                <div class="col-md-12">
                                    <table width="100%" cellpadding="10">
                                        <tbody>
                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                District :
                                                <span> {{ (!empty($appInfo->factory_district_id)) ? $districts[$appInfo->factory_district_id]:'N/A'  }}</span>
                                            </td>
                                            <td width="50%" style="padding: 5px;" >
                                                Police Station :
                                                <span> {{ (!empty($appInfo->factory_thana_id)) ? $thana_eng[$appInfo->factory_thana_id]:'N/A'  }}</span>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                Post Office :
                                                <span> {{ (!empty($appInfo->factory_post_office)) ? $appInfo->factory_post_office:'N/A'  }}</span>
                                            </td>
                                            <td width="50%" style="padding: 5px;" >
                                                Post Code :
                                                <span>{{ (!empty($appInfo->factory_post_code)) ? $appInfo->factory_post_code:'N/A'  }}</span>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                House,Flat/Apartment,Road :
                                                <span> {{ (!empty($appInfo->factory_address)) ? $appInfo->factory_address:'N/A'  }}</span>
                                            </td>
                                            <td width="50%" style="padding: 5px;" >
                                                Telephone No :
                                                <span>{{ (!empty($appInfo->factory_telephone_no)) ? $appInfo->factory_telephone_no:'N/A'  }}</span>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                Mobile No :
                                                <span> {{ (!empty($appInfo->factory_mobile_no)) ? $appInfo->factory_mobile_no:'N/A'  }}</span>
                                            </td>
                                            <td width="50%" style="padding: 5px;" >
                                                Fax No :
                                                <span>{{ (!empty($appInfo->factory_fax_no)) ? $appInfo->factory_fax_no:'N/A'  }}</span>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="padding: 5px;">
                                                Email :
                                                <span>{{ (!empty($appInfo->factory_email)) ? $appInfo->factory_email:'N/A'  }}</span>
                                            </td>
                                            <td width="50%" style="padding: 5px;" >
                                                Mouja No. :
                                                <span>{{ (!empty($appInfo->factory_mouja)) ? $appInfo->factory_mouja:'N/A'  }}</span>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <br/>
                            </div>
                        </div>

                        <div id="" class="panel panel-info">

                            <div class="panel-body">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <div>1. Annual Production Capacity</div>
                                        <label class="col-md-12 text-left"></label>
                                        <table id="productionCostTbl" class="table table-striped table-bordered dt-responsive" cellspacing="0" width="100%">
                                            <thead class="alert alert-info">
                                            <tr>
                                                <th valign="top" class="text-center valigh-middle">Name of Product
                                                    <span class="required-star"></span><br/>
                                                </th>

                                                <th valign="top" class="text-center valigh-middle">HS Code
                                                    <span class="required-star"></span><br/>
                                                </th>

                                                <th valign="top" class="text-center valigh-middle">Quantity
                                                    <span class="required-star"></span><br/>
                                                </th>

                                                <th valign="top" class="text-center valigh-middle">Price (USD)
                                                    <span class="required-star"></span><br/>
                                                </th>

                                                <th colspan='2' valign="top" class="text-center valigh-middle">Value Taka (in million)
                                                    <span class="required-star"></span><br/>
                                                </th>


                                            </tr>
                                            </thead>
                                            <tbody>
                                            @if(count($la_annual_production_capacity)>0)
                                            @foreach($la_annual_production_capacity as $value1)
                                                <tr>
                                                    <td>{{ (!empty($value1->product_name)) ? $value1->product_name:''  }}</td>
                                                    <td>{{ (!empty($value1->hs_code)) ? $value1->hs_code:''  }}</td>
                                                    <td>{{ (!empty($value1->quantity)) ? $value1->quantity:''  }}</td>
                                                    <td>{{ (!empty($value1->price_usd)) ? $value1->price_usd:''  }}</td>
                                                    <td colspan='2'>{{ (!empty($value1->price_taka)) ? $value1->price_taka:''  }}</td>
                                                </tr>

                                            @endforeach
                                            @endif
                                            </tbody>
                                        </table>

                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12 {{$errors->has('commercial_operation_date') ? 'has-error': ''}}">
                                            {!! Form::label('commercial_operation_date','2. Target Date of Commercial Operation: ',['class'=>'col-md-4 text-left']) !!}

                                            {{$appInfo->commercial_operation_date}}

                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-4">
                                            {!! Form::label('local_sales','3. Sales (in 100%): ',['class'=>'col-md-12 text-left']) !!}
                                        </div>
                                        <div class="col-md-3 {{$errors->has('local_sales') ? 'has-error': ''}}">
                                            {!! Form::label('local_sales','Local ',['class'=>'col-md-4 text-left']) !!}
                                            &nbsp;&nbsp;&nbsp;{{$appInfo->local_sales}}

                                        </div>
                                        <div class="col-md-3 {{$errors->has('foreign_sales') ? 'has-error': ''}}">
                                            {!! Form::label('foreign_sales','Foreign ',['class'=>'col-md-4 text-left']) !!}
                                            &nbsp;&nbsp;&nbsp;  {{$appInfo->foreign_sales}}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="table-responsive">
                                                <div>4. Manpower of the organization
                                                </div>
                                                <table class="table table-striped table-bordered" cellspacing="0" width="100%">
                                                    <thead class="alert alert-info">
                                                    <tr>
                                                        <th class="alert alert-info" colspan="3">Local (Bangladesh only)</th>
                                                        <th class="alert alert-info" colspan="3">Foreign (Abroad country)</th>
                                                        <th class="alert alert-info" colspan="1">Grand total</th>
                                                        <th class="alert alert-info" colspan="2">Ratio</th>
                                                    </tr>
                                                    <tr>
                                                        <th class="alert alert-info">Executive</th>
                                                        <th class="alert alert-info">Supporting staff</th>
                                                        <th class="alert alert-info">Total (a)</th>
                                                        <th class="alert alert-info">Executive</th>
                                                        <th class="alert alert-info">Supporting staff</th>
                                                        <th class="alert alert-info">Total (b)</th>
                                                        <th class="alert alert-info"> (a+b)</th>
                                                        <th class="alert alert-info">Local</th>
                                                        <th class="alert alert-info">Foreign</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody id="manpower">

                                                    <tr>
                                                        <td>
                                                            {{ (!empty($appInfo->local_executive)) ? $appInfo->local_executive:''  }}
                                                        </td>
                                                        <td>
                                                            {{ (!empty($appInfo->local_stuff)) ? $appInfo->local_stuff:''  }}
                                                        </td>
                                                        <td>
                                                            {{ (!empty($appInfo->local_total)) ? $appInfo->local_total:''  }}
                                                        </td>
                                                        <td>
                                                            {{ (!empty($appInfo->foreign_executive)) ? $appInfo->foreign_executive:''  }}
                                                        </td>
                                                        <td>
                                                            {{ (!empty($appInfo->foreign_stuff)) ? $appInfo->foreign_stuff:''  }}
                                                        </td>
                                                        <td>
                                                            {{ (!empty($appInfo->foreign_total)) ? $appInfo->foreign_total:''  }}
                                                        </td>
                                                        <td>
                                                            {{ (!empty($appInfo->manpower_total)) ? $appInfo->manpower_total:''  }}
                                                        </td>
                                                        <td>
                                                            {{ (!empty($appInfo->local_stuff)) ? $appInfo->local_stuff:''  }}
                                                        </td>
                                                        <td>
                                                            {{ (!empty($appInfo->manpower_foreign_ratio)) ? $appInfo->manpower_foreign_ratio:''  }}
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="table-responsive">
                                                <label class="col-md-12 text-left" ></label>
                                                <div>5. Investment</div>
                                                <table class="table table-striped table-bordered" cellspacing="0" width="100%">
                                                    <tbody id="investment_tbl">
                                                    <tr>
                                                        <th class="alert alert-info">Items</th>
                                                        <th class="alert alert-info">Local (Million Taka)</th>
                                                    </tr>

                                                    <tr>
                                                        <th>Fixed Investment</th>
                                                        <td>
                                                            <table style="width:100%;">
                                                                <tr>
                                                                    <td colspan = '2'></td>
                                                                </tr>
                                                            </table>
                                                        </td>

                                                    </tr>
                                                    <tr>
                                                        <td> &nbsp;&nbsp;&nbsp;&nbsp; Land</td>
                                                        <td>
                                                            <table style="width:100%;">
                                                                <tr>
                                                                    <td style="width:75%;">
                                                                        {{$appInfo->local_land_ivst}}
                                                                    </td>
                                                                    <td>
                                                                        {{isset($currencies[$appInfo->local_land_ivst_ccy]) ? $currencies[$appInfo->local_land_ivst_ccy]:"N/A"}}
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>

                                                    </tr>
                                                    <tr>
                                                        <td> &nbsp;&nbsp;&nbsp;&nbsp; Building</td>
                                                        <td>
                                                            <table style="width:100%;">
                                                                <tr>
                                                                    <td style="width:75%;">
                                                                        {{$appInfo->local_building_ivst}}
                                                                    </td>
                                                                    <td>
                                                                        {{isset($currencies[$appInfo->local_building_ivst_ccy]) ? $currencies[$appInfo->local_land_ivst_ccy]:"N/A"}}
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>

                                                    </tr>
                                                    <tr>
                                                        <td> &nbsp;&nbsp;&nbsp;&nbsp; Machinery & Equipment</td>
                                                        <td>
                                                            <table style="width:100%;">
                                                                <tr>
                                                                    <td style="width:75%;">
                                                                        {{$appInfo->local_machinery_ivst}}
                                                                    </td>
                                                                    <td>
                                                                        {{isset($currencies[$appInfo->local_machinery_ivst_ccy]) ? $currencies[$appInfo->local_machinery_ivst_ccy]:"N/A"}}

                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>

                                                    </tr>
                                                    <tr>
                                                        <td> &nbsp;&nbsp;&nbsp;&nbsp; Others</td>
                                                        <td>
                                                            <table style="width:100%;">
                                                                <tr>
                                                                    <td style="width:75%;">
                                                                        {{$appInfo->local_others_ivst}}
                                                                    </td>
                                                                    <td>
                                                                        {{isset($currencies[$appInfo->local_others_ivst_ccy]) ? $currencies[$appInfo->local_others_ivst_ccy]:"N/A"}}

                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>

                                                    </tr>
                                                    <tr>
                                                        <th>Working Capital</th>
                                                        <td>
                                                            <table style="width:100%;">
                                                                <tr>
                                                                    <td style="width:75%;">
                                                                        {{$appInfo->local_wc_ivst}}
                                                                    </td>
                                                                    <td>
                                                                        {{isset($currencies[$appInfo->local_wc_ivst_ccy]) ? $currencies[$appInfo->local_wc_ivst_ccy]:"N/A"}}
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>

                                                    </tr>
                                                    <tr>
                                                        <td> &nbsp;&nbsp;&nbsp;&nbsp; Total Investment (a+b)</td>
                                                        <td colspan="3">
                                                            {{$appInfo->total_fixed_ivst}}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td> &nbsp;&nbsp;&nbsp;&nbsp; Total Investment (a+b)</td>
                                                        <td colspan="3">
                                                            {{$appInfo->total_working_capital}}
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        </div>



                        <div class="panel-body">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <label class="col-md-12 text-left">6. Source of Finance</label>
                                            <table class="table table-striped table-bordered" cellspacing="0" width="100%">
                                                <tbody id="annual_production_capacity">
                                                <tr>
                                                    <td>
                                                        Local Equity (Taka Million)
                                                    </td>
                                                    <td>
                                                        {{$appInfo->finance_src_loc_equity_1}}
                                                    </td>
                                                    <td>
                                                        Local Equity (%)
                                                    </td>
                                                    <td>
                                                        {{$appInfo->finance_src_loc_equity_2}}
                                                    </td>

                                                </tr>
                                                <tr>
                                                    <td>
                                                        Foreign Equity (Taka Million)
                                                    </td>
                                                    <td>
                                                        {{$appInfo->finance_src_foreign_equity_1}}
                                                    </td>
                                                    <td>
                                                        Foreign Equity (%)
                                                    </td>
                                                    <td>
                                                        {{$appInfo->finance_src_foreign_equity_2}}
                                                    </td>

                                                </tr>
                                                <tr>
                                                    <th>
                                                        &nbsp;&nbsp;&nbsp;&nbsp; (a) Total Equity
                                                    </th>
                                                    <td colspan="3">
                                                        {{$appInfo->finance_src_loc_total_equity_1}}
                                                    </td>

                                                </tr>

                                                <tr>
                                                    <td>
                                                        Local Loan (Taka Million)
                                                    </td>
                                                    <td>
                                                        {{$appInfo->finance_src_loc_loan_1}}
                                                    </td>
                                                    <td rowspan="2" style="vertical-align: middle;text-align: center;">
                                                        (b) Total Loan<br/>
                                                        (Taka Million)
                                                    </td>
                                                    <td rowspan="2" style="vertical-align: middle;text-align: center;">
                                                        {{$appInfo->finance_src_total_loan}}
                                                    </td>

                                                </tr>
                                                <tr>
                                                    <td>
                                                        Foreign Loan (Taka Million)
                                                    </td>
                                                    <td>
                                                        {{$appInfo->finance_src_foreign_loan_1}}
                                                    </td>

                                                </tr>

                                                <tr>
                                                    <th>
                                                        &nbsp;&nbsp;&nbsp;&nbsp; Total Financing (a+b)
                                                    </th>
                                                    <td colspan="3">
                                                        {{$appInfo->finance_src_loc_total_financing_1}}
                                                    </td>

                                                </tr>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="col-md-12 text-left">7. Public Utility Service Required</label><br/>
                                        <div class="col-md-12">
                                        <label class="checkbox-inline">
                                             @if($appInfo->public_land == 1) <img src="assets/images/checked.png" width="10" height="10"/> Land @endif
                                        </label>
                                        <label class="checkbox-inline">
                                            @if($appInfo->public_electricity == 1) <img src="assets/images/checked.png" width="10" height="10"/> Electricity @endif
                                        </label>
                                        <label class="checkbox-inline">
                                            @if($appInfo->public_gas == 1) <img src="assets/images/checked.png" width="10" height="10"/> Gas @endif
                                        </label>
                                        <label class="checkbox-inline">
                                            @if($appInfo->public_telephone == 1) <img src="assets/images/checked.png" width="10" height="10"/> Telephone @endif
                                        </label>
                                        <label class="checkbox-inline">
                                            @if($appInfo->public_road == 1) <img src="assets/images/checked.png" width="10" height="10"/> Road @endif
                                        </label>
                                        <label class="checkbox-inline">
                                            @if($appInfo->public_water == 1) <img src="assets/images/checked.png" width="10" height="10"/> Water @endif
                                        </label>
                                        <label class="checkbox-inline">
                                            @if($appInfo->public_drainage == 1) <img src="assets/images/checked.png" width="10" height="10"/> Drainage @endif
                                        </label>
                                        <label class="checkbox-inline">
                                            @if($appInfo->public_others == 1) <img src="assets/images/checked.png" width="10" height="10"/> Others @endif
                                        </label>
                                        {{--<label class="checkbox-inline">--}}
                                            {{--{!! Form::text('other_utility_txt', '', ['data-rule-maxlength'=>'40','class' => 'other_utility_txt form-control input-md hide','id'=>'other_utility_txt']) !!}--}}
                                            {{--{!! $errors->first('other_utility_txt','<span class="help-block">:message</span>') !!}--}}
                                        {{--</label>--}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="ep_form" class="panel panel-info">
                                <div class="panel-heading">Necessary documents to be attached here (Only PDF file to be attach here)</div>
                                <div class="panel-body">
                                    <div class="col-md-12">
                                        <table class="table table-striped table-bordered table-hover ">
                                            <thead>
                                            <tr>
                                                <th style="padding: 5px;">No.</th>
                                                <th colspan="6" style="padding: 5px;">Required attachments</th>
                                                <th colspan="2" style="padding: 5px;">Attached PDF file (Each File Maximum size 2MB)
                                                    {{--<span>--}}
                                                    {{--<i title="Attached PDF file (Each File Maximum size 2MB)!" data-toggle="tooltip" data-placement="right" class="fa fa-question-circle" aria-hidden="true"></i>--}}
                                                    {{--</span>--}}
                                                </th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php $i = 1; ?>
                                            @foreach($document as $row)
                                                <tr>
                                                    <td style="padding: 5px;">
                                                        <div align="left">{!! $i !!}<?php echo $row->doc_priority == "1" ? "<span class='required-star'></span>" : ""; ?></div>
                                                    </td>
                                                    <td colspan="6" style="padding: 5px;">{!!  $row->doc_name !!}</td>
                                                    <td colspan="2" style="padding: 5px;">
                                                        @if(!empty($clrDocuments[$row->id]['doc_file_path']))

                                                            <div class="save_file">
                                                                <a target="_blank" title=""
                                                                   href="{{URL::to('/uploads/'.(!empty($clrDocuments[$row->id]['doc_file_path']) ?
                                                            $clrDocuments[$row->id]['doc_file_path'] : ''))}}"> <img width="10" height="10" src="assets/images/pdf.png" alt="pdf" /> Open File
                                                                </a>
                                                            </div>
                                                        @else
                                                            No file found
                                                        @endif
                                                    </td>
                                                </tr>
                                                <?php $i++; ?>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>


                        </div>


                        <div id="" class="panel panel-info">
                            <div class="panel-heading">Terms and Conditions</div>
                            <div class="panel-body">
                                <div class="col-md-12">
                                    <table width="100%" cellpadding="10">
                                        <tr>
                                            <td colspan="2" style="padding: 5px;">
                                                @if($appInfo->acceptTerms == 1)
                                                    <img src="assets/images/checked.png" width="10" height="10"/>
                                                    I do here by declare that the information given above is true to the best of my knowledge and I shall be liable for any false information/system is given.
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<link rel="stylesheet" href="{{ url('assets/css/jquery.steps.css') }}">
<script src="{{ asset("assets/scripts/jquery.steps.js") }}"></script>

</body>
</html>
