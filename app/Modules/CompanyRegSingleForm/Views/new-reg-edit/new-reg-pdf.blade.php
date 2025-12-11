<!DOCTYPE html>
<html lang="en">
<head>
    <title>Company Registration-Single Form (New Registration Edit)</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="{{ asset("assets/stylesheets/styles.css") }}"/>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css"
          integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">
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
                        Apply for Name Clearance to Bangladesh
                    </div>
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <span><i class="fa fa-list"></i>&nbsp;&nbsp;Application</span>
                        </div>
                        <div class="panel-body">
                            <div class="panel panel-info">
                                <div class="panel-heading">
                                    <span>Select Entity Type</span>
                                </div>
                                <div class="panel-body">
                                    <table width="100%" cellpadding="10">
                                        <tbody>
                                            <tr>
                                                <td class="text-center" width="100%" style=";padding: 5px;" >
                                                    Entity Type :
                                                    <span>{{ (!empty($appInfo->name)) ? $appInfo->name : 'N/A'  }}</span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="panel panel-info">
                                <div class="panel-heading">
                                    <span>Enter Name Clearance Information</span>
                                </div>
                                <div class="panel-body">
                                    <table width="100%" cellpadding="10">
                                        <tbody>
                                            <tr>
                                                <td width="50%" style="padding: 5px;" >
                                                    Submission No :
                                                    <span>{{ (!empty($appInfo->submission_no)) ? $appInfo->submission_no : 'N/A'  }}</span>
                                                </td>
                                                <td width="50%" style="padding: 5px;" >
                                                    Clearance Letter No :
                                                    <span>{{ (!empty($appInfo->clearence_letter_no)) ? $appInfo->clearence_letter_no : 'N/A'  }}</span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="panel panel-info">
                                <div class="panel-heading">
                                    <span>2. General Information</span>
                                </div>
                                <div class="panel-body">
                                    <table width="100%" cellpadding="10">
                                        <tbody>
                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                Name of the Entity :
                                                <span>{{ (!empty(Auth::user()->company_ids)) ? \App\Libraries\CommonFunction::getCompanyNameById(Auth::user()->company_ids) : 'N/A'  }}</span>
                                            </td>

                                            <td width="50%" style="padding: 5px;" >
                                                Entity Type :
                                                <span>{{ (!empty($entityType)) ? $entityType : 'N/A'  }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                Liability Type :
                                                <span>{{ (!empty($appInfo->name)) ? $appInfo->name : 'N/A'  }}</span>
                                            </td>

                                            <td width="50%" style="padding: 5px;" >
                                                Address of the Entity :
                                                <span>{{ (!empty($appInfo->address_entity)) ? $appInfo->address_entity : 'N/A'  }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                District :
                                                <span>{{ (!empty($appInfo->entity_district_id)) ? $districts[$appInfo->entity_district_id]:'N/A'  }}</span>
                                                {{--<span>{{ (!empty($appInfo->rjsc_name)) ? $appInfo->rjsc_name : 'N/A'  }}</span>--}}
                                            </td>

                                            <td width="50%" style="padding: 5px;" >
                                                Entity Email Address :
                                                <span>{{ (!empty($appInfo->entity_email_address)) ? $appInfo->entity_email_address : 'N/A'  }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                Main Business objective :
                                                <span>{{ (!empty($appInfo->main_business_objective)) ? $appInfo->main_business_objective : 'N/A'  }}</span>
                                            </td>

                                            <td width="50%" style="padding: 5px;" >
                                                Business Sector :
                                                <span>{{ (!empty($appInfo->bus_sec_name)) ? $appInfo->bus_sec_name : 'N/A'  }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                Business Sub-Sector :
                                                <span>{{ (!empty($appInfo->sus_seb_sec_name)) ? $appInfo->sus_seb_sec_name : 'N/A'  }}</span>
                                            </td>

                                            <td width="50%" style="padding: 5px;" >
                                                Authorized Capital (BDT) :
                                                <span>{{ (!empty($appInfo->authorize_capital)) ? $appInfo->authorize_capital : 'N/A'  }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                Number of shares :
                                                <span>{{ (!empty($appInfo->number_shares)) ? $appInfo->number_shares : 'N/A'  }}</span>
                                            </td>

                                            <td width="50%" style="padding: 5px;" >
                                                Value of each share(BDT) :
                                                <span>{{ (!empty($appInfo->value_of_each_share)) ? $appInfo->value_of_each_share : 'N/A'  }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                Minimum No of Directors :
                                                <span>{{ (!empty($appInfo->minimum_no_of_directors)) ? $appInfo->minimum_no_of_directors : 'N/A'  }}</span>
                                            </td>

                                            <td width="50%" style="padding: 5px;" >
                                                Maximum No of Directors :
                                                <span>{{ (!empty($appInfo->maximum_no_of_directors)) ? $appInfo->maximum_no_of_directors : 'N/A'  }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                Quorum of AGM/EGM :
                                                <span>{{ (!empty($appInfo->quorum_agm_egm_num)) ? $appInfo->quorum_agm_egm_num : 'N/A'  }}</span>
                                            </td>

                                            <td width="50%" style="padding: 5px;" >
                                                Maximum tow{2} :
                                                <span>{{ (!empty($appInfo->quorum_agm_egm_word)) ? $appInfo->quorum_agm_egm_word : 'N/A'  }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                Duration for Chairmanship(year) :
                                                <span>{{ (!empty($appInfo->duration_of_chairmanship)) ? $appInfo->duration_of_chairmanship : 'N/A'  }}</span>
                                            </td>

                                            <td width="50%" style="padding: 5px;" >
                                                Duration for Managing Directorship(year) :
                                                <span>{{ (!empty($appInfo->duration_managing_directorship)) ? $appInfo->duration_managing_directorship : 'N/A'  }}</span>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="panel panel-info">
                                <div class="panel-heading">
                                    <span>B. Particulars of Body Corporate Subscribers ( if any, as of Memorandum and Aricles of associatio )</span>
                                </div>
                                <div class="panel-body">
                                    <table class="table table-striped table-bordered" cellspacing="0" width="100%">
                                        <tr>
                                            <th width="20%" align="center">Name (of the corporation body)</th>
                                            <th width="20%" align="center">Represented By (name of the representative)</th>
                                            <th width="20%" align="center">Address (of the body corporate)</th>
                                            <th width="20%" align="center">District</th>
                                            <th width="20%" align="center">Number of Subscribed Shares</th>
                                        </tr>
                                        @foreach($particulars as $v_par)
                                        <tr>
                                            <td style="padding: 5px;" align="center">
                                                <span> {{ (!empty($v_par->name_corporation_body))? $v_par->name_corporation_body:'N/A'  }}</span>
                                            </td>
                                            <td style="padding: 5px;" align="center">
                                                <span> {{ (!empty($v_par->represented_by))? $v_par->represented_by:'N/A'  }}</span>
                                            </td>
                                            <td style="padding: 5px;" align="center">
                                                <span> {{ (!empty($v_par->address))? $v_par->address:'N/A'  }}</span>
                                            </td>
                                            <td style="padding: 5px;" align="center">
                                                <span>{{ (!empty($v_par->district_id)) ? $districts[$v_par->district_id]:'N/A'  }}</span>
                                                {{--<span> {{ (!empty($v_par->district_id))? $v_par->district_id:'N/A'  }}</span>--}}
                                            </td>
                                            <td style="padding: 5px;" align="center">
                                                <span> {{ (!empty($v_par->no_subscribed_shares))? $v_par->no_subscribed_shares:'N/A'  }}</span>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </table>
                                </div>
                            </div>

                            <div class="panel panel-info">
                                <div class="panel-heading">
                                    <span>B. Qualification Shares of Each Director (as of Articles of Association, Form-XI)</span>
                                </div>
                                <div class="panel-body">
                                    <table width="100%" cellpadding="10">
                                        <tbody>
                                            <tr>
                                                <td width="50%" style="padding: 5px;" >
                                                    Number of Qualification Shares :
                                                    <span>{{ (!empty($appInfo->no_of_qualification_share)) ? $appInfo->no_of_qualification_share : 'N/A'  }}</span>
                                                </td>

                                                <td width="50%" style="padding: 5px;" >
                                                    Value of each Share (BDT) :
                                                    <span>{{ (!empty($appInfo->value_of_qualification_share)) ? $appInfo->value_of_qualification_share : 'N/A'  }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td width="60%" style="padding: 5px;" >
                                                    <span>Witness to the agreement of taking qualification Shares :<br/></span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td width="50%" style="padding: 5px;" >
                                                    a.Name of the Witness :
                                                    <span>{{ (!empty($appInfo->agreement_witness_name)) ? $appInfo->agreement_witness_name : 'N/A'  }}</span>
                                                </td>

                                                <td width="50%" style="padding: 5px;" >
                                                    b.Address of Witness :
                                                    <span>{{ (!empty($appInfo->agreement_witness_address)) ? $appInfo->agreement_witness_address : 'N/A'  }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td width="50%" style="padding: 5px;" >
                                                    District :
                                                    <span>{{ (!empty($appInfo->agreement_witness_district_id)) ? $districts[$appInfo->agreement_witness_district_id]:'N/A' }}</span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table><br/>
                                </div>
                            </div>

                            <div class="panel panel-info">
                                <div class="panel-heading">
                                    <span>List of Subscriber</span>
                                </div>
                                <div class="panel-body">
                                    <span>(Directors: Minimum-two{2}, Maximum-fifty{50})<br/>
                                    {Subscribers/Directors: Minimum-2, Maximum-50}</span><br/>
                                    <table class="table table-striped table-bordered" cellspacing="0" width="100%">
                                        <tbody>
                                        <tr>
                                            <th width="8%" align="center">SI.</th>
                                            <th width="30%" align="center">Name</th>
                                            <th width="27%" align="center">Position</th>
                                            <th width="35%" align="center">Number of Subscribed Shares</th>
                                        </tr>
                                        <?php $count = 1; ?>
                                        @foreach($subscriber as $subscriber)
                                            <tr>
                                                <td align="center">{{$count++}}</td>
                                                <td style="padding: 5px;" align="center">
                                                    <span>{{ (!empty($subscriber->corporation_body_name)) ? $subscriber->corporation_body_name : 'N/A'  }}</span>
                                                </td>
                                                <td style="padding: 5px;" align="center">
                                                    <span>{{ (!empty($subscriber->title)) ? $subscriber->title : 'N/A'  }}</span>
                                                </td>
                                                <td style="padding: 5px;" align="center">
                                                    <span>{{ (!empty($subscriber->no_of_subscribed_shares)) ? $subscriber->no_of_subscribed_shares : 'N/A'  }}</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="panel panel-info">
                                <div class="panel-heading">
                                    <span>3. Witness</span>
                                </div>
                                <div class="panel-body">
                                    <table width="100%" cellpadding="10">
                                        <tbody>
                                            <tr>
                                                <td width="50%" style="padding: 5px;" >
                                                    Name :
                                                    <span>{{ (!empty($witnessData[0]['name'])) ? $witnessData[0]['name'] : 'N/A'  }}</span>
                                                </td>

                                                <td width="50%" style="padding: 5px;" >
                                                    Name :
                                                    <span>{{ (!empty($witnessData[1]['name'])) ? $witnessData[1]['name'] : 'N/A'  }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td width="50%" style="padding: 5px;" >
                                                    Address :
                                                    <span>{{ (!empty($witnessData[0]['address'])) ? $witnessData[0]['address'] : 'N/A'  }}</span>
                                                </td>

                                                <td width="50%" style="padding: 5px;" >
                                                    Address :
                                                    <span>{{ (!empty($witnessData[1]['address'])) ? $witnessData[1]['address'] : 'N/A'  }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td width="50%" style="padding: 5px;" >
                                                    Phone :
                                                    <span>{{ (!empty($witnessData[0]['phone'])) ? $witnessData[0]['phone'] : 'N/A'  }}</span>
                                                </td>

                                                <td width="50%" style="padding: 5px;" >
                                                    Phone :
                                                    <span>{{ (!empty($witnessData[1]['phone'])) ? $witnessData[1]['phone'] : 'N/A'  }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td width="50%" style="padding: 5px;" >
                                                    National ID :
                                                    <span>{{ (!empty($witnessData[0]['national_id'])) ? $witnessData[0]['national_id'] : 'N/A'  }}</span>
                                                </td>

                                                <td width="50%" style="padding: 5px;" >
                                                    National ID :
                                                    <span>{{ (!empty($witnessData[1]['national_id'])) ? $witnessData[1]['national_id'] : 'N/A'  }}</span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="panel panel-info">
                                <div class="panel-heading">
                                    <span>3. Document Presented for Filing By</span>
                                </div>
                                <div class="panel-body">
                                    <table width="100%" cellpadding="10">
                                        <tbody>
                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                Name :
                                                <span>{{ (!empty($witnessDataFiled->name)) ? $witnessDataFiled->name : 'N/A'  }}</span>
                                            </td>

                                            <td width="50%" style="padding: 5px;" >
                                                Position :
                                                <span>{{ (!empty($witnessDataFiled->position_id)) ? $rjscCompanyPosition[$witnessDataFiled->position_id] : 'N/A'  }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                Address :
                                                <span>{{ (!empty($witnessDataFiled->address)) ? $witnessDataFiled->address : 'N/A'  }}</span>
                                            </td>

                                            <td width="50%" style="padding: 5px;" >
                                                District :
                                                <span>{{ (!empty($witnessDataFiled->district_id)) ? $districts[$witnessDataFiled->district_id] : 'N/A'  }}</span>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="panel panel-info">
                                <div class="panel-heading">
                                    <span>E. declaration on Registration of the Company Signed By (as if Form-I)</span>
                                </div>
                                <div class="panel-body">
                                    <table width="100%" cellpadding="10">
                                        <tbody>
                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                Name :
                                                <span>{{ (!empty($appInfo->declaration_name)) ? $appInfo->declaration_name : 'N/A'  }}</span>
                                            </td>

                                            <td width="50%" style="padding: 5px;" >
                                                Position :
                                                <span>{{ (!empty($appInfo->declaration_position_id)) ? $rjscCompanyPosition[$appInfo->declaration_position_id] : 'N/A'  }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                Organization(applicable for advocate only) :
                                                <span>{{ (!empty($appInfo->declaration_organization)) ? $appInfo->declaration_organization : 'N/A'  }}</span>
                                            </td>

                                            <td width="50%" style="padding: 5px;" >
                                                Address :
                                                <span>{{ (!empty($appInfo->declaration_address)) ? $appInfo->declaration_address : 'N/A'  }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                District :
                                                <span>{{ (!empty($appInfo->declaration_district_id)) ? $districts[$appInfo->declaration_district_id] : 'N/A'  }}</span>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="panel panel-info">
                                <div class="panel-heading">
                                    <span>F. Upload Softcopy of Documents</span>
                                </div>
                                <div class="panel-body">
                                    <p style="padding: 5px;">1. Document Name :
                                        <span>{{ (!empty($appInfo->upload_doc_name_id)) ? $rjscNrDocList[$appInfo->upload_doc_name_id] : 'N/A'  }}</span>
                                    </p>
                                    <p style="padding: 5px;">2. Scaned Copy(.ZIP {max size 200 KB}) :
                                        <a target="_blank" rel="noopener" title="" href="{{URL::to('upload_scaned_copy/'.(!empty($appInfo->upload_scaned_copy) ?
                                                        $appInfo->upload_scaned_copy : ''))}}"> <img width="10" height="10" src="assets/images/pdf.png" alt="pdf" /> Open File
                                        </a>
                                    </p>
                                    <p style="padding: 5px;">* Steps :</p>
                                    <p style="padding: 5px;">1. Enter and save all the information of original registration application page</p>
                                    <P style="padding: 5px;">2. Enter memorandum of Association (MOA)</P>
                                    <P style="padding: 5px;">3. Enter Articles of association AOA a) First (part-1) b) Then Part-2</P>
                                    <P style="padding: 5px;">4. Print the subscriber page of MOA as directed and Form-IX and after signing, upload the signed scanned copy as .ZIP format.</P>
                                    <P style="padding: 5px;">5. Check and confirm MOA AND AOA by viewing your entered information.</P>
                                    <P style="padding: 5px;">6. Finally Submit the page and continue to get the acknowledgement of payment.</P>
                                    <P style="padding: 5px;">3. Memorandum of Association (include top cover) pages (no.) :
                                        <span>{{ (!empty($appInfo->memorandum_asso_no)) ? $appInfo->memorandum_asso_no : 'N/A'  }}</span>
                                    </P>
                                    <p style="padding: 5px;">4. Article of Association (include top cover) pages (no.) :
                                        <span>{{ (!empty($appInfo->article_asso_no)) ? $appInfo->article_asso_no : 'N/A'  }}</span>
                                    </p>
                                    <p class="text-center" style="padding: 5px;">Softcopy is not uploaded succenssfully, please reduce file size as recommended</p>

                                </div>
                            </div>

                            <div class="panel panel-info">
                                <div class="panel-heading">
                                    <span>B. Objectives</span>
                                </div>
                                <div class="panel-body">
                                    <span style="padding: 5px;">The Objects for which the company is established are all or any of the following (all the objects will be implemented after obtaining necessary permission form the Govemmment/concerned authority/competent authority before commencement of the business)</span>
                                    <table class="table table-striped table-bordered" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th width="8%" align="center">Id</th>
                                                <th width="92%" align="center">Objectives</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $i = 1; ?>
                                            @foreach($objectives as $key=>$value)
                                                <tr>
                                                    <td align="center"><span style="padding: 5px;">{{$i}}</span></td>
                                                    <td style="padding: 5px;">
                                                        <span style="padding: 5px;">{{ (!empty($value->objective)) ? $value->objective : 'N/A'  }}</span>
                                                    </td>
                                                </tr>
                                            <?php $i++; ?>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="panel panel-info">
                                <div class="panel-heading">
                                    <span>A. General information</span>
                                </div>
                                <div class="panel-body">
                                    <table class="table table-striped table-bordered" cellspacing="0" width="100%">
                                        <tbody id="">
                                        <tr>
                                            <td>
                                                1. Name of the Entity
                                            </td>
                                            <td>
                                                : {{ \App\Libraries\CommonFunction::getCompanyNameById(Auth::user()->company_ids)}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                2. Entity Type
                                            </td>
                                            <td>
                                                : {{$entityType}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                3. Registration No
                                            </td>
                                            <td>
                                                :
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                4. RJSC Office
                                            </td>
                                            <td>
                                                : Dhaka
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="panel panel-info">
                                <div class="panel-heading">
                                    <span>Articles of Association</span>
                                </div>
                                <div class="panel-body">
                                    <table class="table table-striped table-bordered" cellspacing="0" width="100%">
                                        <tbody>
                                        <?php $i = 1;
                                        $totalRec = count($nrAoaClause);
                                        ?>
                                        @if($totalRec > 0)
                                            @foreach($nrAoaClause as $key => $v_aoa)
                                                <tr>
                                                    <td style="text-align: center" colspan="2">
                                                        {{ $v_aoa->name }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 10px">
                                                        <p>{{$i}}</p>
                                                        <a href="#" row_id="{{ Encryption::encodeId($v_aoa->id) }}"
                                                           data-title-id="{{$v_aoa->clause_title_id}}"
                                                           data-cluase="{{$v_aoa->clause}}"
                                                           class="updateAoaClauseData"
                                                           data-toggle="modal" data-target="#articleModalEdit"
                                                           id="updateAoaClauseData"></a>
                                                    </td>
                                                    <td>
                                                        {!! $v_aoa->clause  !!}
                                                    </td>
                                                </tr>
                                                <?php $i++ ?>
                                            @endforeach
                                        @endif
                                        </tbody>
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