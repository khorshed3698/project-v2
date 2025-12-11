<?php
$accessMode = ACL::getAccsessRight('CdaOc');
if (!ACL::isAllowed($accessMode, '-V-')) {
    die('You have no access right! Please contact with system admin if you have any query.');
}
?>
<link rel="stylesheet" href="{{ url('assets/css/jquery.steps.css') }}">
<style>

    .form-group {
        margin-bottom: 5px;
    }

    .intl-tel-input .country-list {
        z-index: 5;
    }

    textarea {
        height: 60px !important;
    }

    .col-md-7 {
        margin-bottom: 10px;
    }

    label {
        float: left !important;
    }

    form label {
        font-weight: normal;
        font-size: 16px;
    }

    .table {
        margin: 0;
    }

    .mb-5 {
        margin-bottom: 10px;
    }

    .table > tbody > tr > td,
    .table > tbody > tr > th,
    .table > tfoot > tr > td,
    .table > tfoot > tr > th,
    .table > thead > tr > td,
    .table > thead > tr > th {
        padding: 5px;
    }


    @media screen and (max-width: 550px) {
        .button_last {
            margin-top: 40px !important;
        }

        .siteDivLR {
            margin-top: 12px;
            margin-right: 8px;
        }

        .siteDivFB {
            margin-top: 12px;
            margin-right: 8px;
        }

        .pull-right {
            float: none !important;
        }

        .pull-left {
            float: none !important;
        }

        .text-right {
            text-align: left !important;
        }
    }

    @media screen and (min-width: 350px) {

        .siteDivLR {
            margin-top: 12px;
        }

        .siteDivFB {
            margin-top: 12px;
        }


    }
</style>

<div class="col-md-12">
    @include('message.message')
</div>

<div class="col-md-12"  id="applicationForm">
    <div class="panel panel-primary" id="inputForm">
        <div class="panel-heading">
            <div class="row">
                <div class="col-md-6">
                    বসবাস বা ব্যবহার সনদপত্রের আবেদন ফরম (Occupancy Certificate) - সম্পূর্ন সমাপ্ত
                </div>
                <div class="col-md-6" data-html2canvas-ignore="true" id="pdf_id">
                    <div class="pull-right">
                        <a class="btn btn-sm btn-success" data-toggle="collapse" href="#paymentInfo"
                           role="button"
                           aria-expanded="false" aria-controls="collapseExample">
                            <i class="far fa-money-bill-alt"></i>
                            Payment Info
                        </a>
                        @if(!in_array($app_info->status_id,[-1,5,6]))
                            <a href="javascript:void(0)" class="btn btn-danger btn-sm" title="Download Approval Copy" id="html2pdf">
                                <i class="fa fa-download"></i> Application Download as PDF
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="panel-body">
            <ol class="breadcrumb">
                <li><strong>OSS Tracking no. : </strong>{{ $app_info->tracking_no  }}</li>
                <li class="highttext"><strong>CDA Tracking no. : {{ $app_info->cdaOcTrackingNo  }}</strong></li>
                <li class="highttext"><strong> Date of Submission:
                        {{ \App\Libraries\CommonFunction::formateDate($app_info->submitted_at) }}</strong>
                </li>
                <li><strong>Current Status : </strong> {{ $app_info->status_name }}</li>
                @if (isset($app_info) && isset($app_info->certificate) && $app_info->certificate != '0')
                    <li>
                        <a href="{{ url($app_info->certificate) }}" class="btn show-in-view btn-md btn-info"
                           title="Download Certificate" target="_blank"> <i class="fa  fa-file-pdf-o"></i> Download
                            Certificate</a>
                    </li>
                @endif
                @if($app_info->sp_case_no != "" && $app_info->sp_case_no != null)
                    <li>
                        <strong>SP Case No :</strong>
                        {{ $app_info->sp_case_no}}
                    </li>
                @endif



                @if(($app_info->certificate != '')  && $app_info->status_id == 25)
                    <li>
                        <strong>Info :</strong>
                        This is a draft approval copy. To collect the final approval copy please contact to Service
                        Delivery Counter (SDC). Chittagong Development Authority, CDA building, Court Road, Kotowali
                        Circle, Chittagong-4000, Bangladesh
                    </li>
                @endif

                @if(($app_info->certificate == '')  && $app_info->status_id == 25) <strong>Info :</strong>
                <li>Your application is approved. waiting for preparing the letter and signature.</li>
                @endif
            </ol>

            <ol class="breadcrumb">
                @if(empty($app_info->sp_case_no) || $app_info->status_id == 2)
                    <li>
                        <strong>Info :</strong>
                        If required you may submit the necessary hard copy to Service Delivery Counter (SDC), Chittagong
                        Development Authority, CDA building, Court Road, Kotowali Circle, Chittagong-4000, Bangladesh.
                    </li>
                @endif
            </ol>

            @include('SonaliPaymentStackHolder::payment-information')

            <div class="form-goup" style="padding:10px;padding-bottom: 0px;">
                @if($app_info->status_id == 27 && Auth::user()->user_type == '5x505')
                    @include('OcCda::resubmit-add')
                @endif

                @if($app_info->status_id == 32)
                    @include('OcCda::resubmit-view')
                @endif
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-6">
                            <div class="text-left col-md-5">
                                <span class="v_label">অকুপেন্সির ধরন</span>
                                <span class="pull-right">&#58;</span>
                            </div>
                            <div class="col-md-7">
                                {{!empty($app_data->BuildingClassId) ? explode('@', $app_data->BuildingClassId)[1] : ''}}
                            </div>
                        </div>

                        <div class="col-md-6">
                            @foreach($app_data->buildingSubClassList as $buildingSubClass)
                                <input type="checkbox" checked disabled>{{explode('@',$buildingSubClass)[2]}}
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-md-12 mb-5">
                        <div class="col-md-6">
                            <input type="checkbox" {{ $app_data->IsApprovedRA == 1 ? 'checked' : '' }} disabled> অনুমোদিত আবাসিক এলাকা কিনা?
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-6">
                            <div class="text-left col-md-5">
                                <span class="v_label">আবেদনের তারিখ</span>
                                <span class="pull-right">&#58;</span>
                            </div>
                            <div class="col-md-7">
                                {{ !empty($app_data->ApplicationDate) ?  $app_data->ApplicationDate : ''}}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="text-left col-md-5">
                                <span class="v_label">নির্মাণ অনুমোদন নাম্বার</span>
                                <span class="pull-right">&#58;</span>
                            </div>
                            <div class="col-md-7">
                                {{ !empty($app_data->PermitNo) ?  $app_data->PermitNo : ''}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-6">
                            <div class="text-left col-md-5">
                                <span class="v_label">কাজ সমাপ্তের তারিখ</span>
                                <span class="pull-right">&#58;</span>
                            </div>
                            <div class="col-md-7">
                                {{ !empty($app_data->ConstructionCompletedDate) ?  $app_data->ConstructionCompletedDate : ''}}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="text-left col-md-5">
                                <span class="v_label">সিটি কর্পোরেশন/পৌরসভা/গ্রাম/মহল্লা</span>
                                <span class="pull-right">&#58;</span>
                            </div>
                            <div class="col-md-7">
                                {{!empty($app_data->CityCorporationId) ? $app_data->CityCorporationId: ''}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-6">
                            <div class="text-left col-md-5">
                                <span class="v_label">বি. এস. নং</span>
                                <span class="pull-right">&#58;</span>
                            </div>
                            <div class="col-md-7">
                                {{ !empty($app_data->BS) ?  $app_data->BS : ''}}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="text-left col-md-5">
                                <span class="v_label">আর. এস. নং</span>
                                <span class="pull-right">&#58;</span>
                            </div>
                            <div class="col-md-7">
                                {{!empty($app_data->RS) ? $app_data->RS: ''}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-6">
                            <div class="text-left col-md-5">
                                <span class="v_label">থানার নাম</span>
                                <span class="pull-right">&#58;</span>
                            </div>
                            <div class="col-md-7">
                                {{!empty($app_data->ThanaId) ? explode('@', $app_data->ThanaId)[1] : ''}}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="text-left col-md-5">
                                <span class="v_label">মৌজার নাম</span>
                                <span class="pull-right">&#58;</span>
                            </div>
                            <div class="col-md-7">
                                {{!empty($app_data->MoujaId) ? explode('@', $app_data->MoujaId)[2] : ''}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-6">
                            <div class="text-left col-md-5">
                                <span class="v_label">ব্লক নং</span>
                                <span class="pull-right">&#58;</span>
                            </div>
                            <div class="col-md-7">
                                {{!empty($app_data->BlockId) ? explode('@', $app_data->BlockId)[1] : ''}}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="text-left col-md-5">
                                <span class="v_label">সিট নং</span>
                                <span class="pull-right">&#58;</span>
                            </div>
                            <div class="col-md-7">
                                {{!empty($app_data->SeatId) ? explode('@', $app_data->SeatId)[1] : ''}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-6">
                            <div class="text-left col-md-5">
                                <span class="v_label">ওয়ার্ড নং</span>
                                <span class="pull-right">&#58;</span>
                            </div>
                            <div class="col-md-7">
                                {{!empty($app_data->WardId) ? explode('@', $app_data->WardId)[1] : ''}}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="text-left col-md-5">
                                <span class="v_label">সেক্টর নং</span>
                                <span class="pull-right">&#58;</span>
                            </div>
                            <div class="col-md-7">
                                {{!empty($app_data->SectorId) ? explode('@', $app_data->SectorId)[1] : ''}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-6">
                            <div class="text-left col-md-5">
                                <span class="v_label">রাস্তার নাম</span>
                                <span class="pull-right">&#58;</span>
                            </div>
                            <div class="col-md-7">
                                {{!empty($app_data->RoadName) ?  $app_data->RoadName : ''}}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="text-left col-md-5">
                                <span class="v_label">সবাহুর মাপ সহ জমি/প্লটের পরিমাণ</span>
                                <span class="pull-right">&#58;</span>
                            </div>
                            <div class="col-md-7">
                                {{!empty($app_data->PlotArea) ?  $app_data->PlotArea : ''}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-md-12 mb-5">
                        <div class="col-md-12">
                            <div class="text-left col-md-4">
                                <span class="v_label">জমি/প্লট এ বিদ্যমান বাড়ি/কাঠামোর বিবরণ</span>
                                <span class="pull-right">&#58;</span>
                            </div>
                            <div class="col-md-8">
                                {{!empty($app_data->PlotDesc) ?  $app_data->PlotDesc : ''}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel panel-info">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-md-12">
                            আংশিক সমাপ্তের ব্যবহারের ধরণ
                        </div>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="col-md-12 col-xs-12">
                        <table class="table table-bordered table-hover table-info">
                            <thead>
                            <tr class="info">
                                <th class="text-center">তলার ধরণ</th>
                                <th class="text-center">ব্যবহারের ধরণ</th>
                                <th class="text-center">ব্যবহার</th>
                                <th class="text-center">আংশিক(বর্গমিটার)</th>
                                <th class="text-center">পূর্ণ সমাপ্ত(বর্গমিটার)</th>
                                <th class="text-center">মোট ক্ষেত্রফল</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(count($app_data->FloorTypeId) > 0)
                                @foreach($app_data->FloorTypeId as $key => $value)
                                    <tr>
                                        <td width="20%"> {{!empty($app_data->FloorTypeId[$key]) ?  explode('@', $app_data->FloorTypeId[$key])[1] :''}}</td>
                                        <td width="20%"> {{!empty($app_data->FloorUseId[$key]) ? explode('@', $app_data->FloorUseId[$key])[1] :''}}</td>
                                        <td>{{!empty($app_data->FloorUse[$key]) ? $app_data->FloorUse[$key]:''}}</td>
                                        <td>{{!empty($app_data->PartialCompletion[$key]) ? $app_data->PartialCompletion[$key]:''}}</td>
                                        <td>{{!empty($app_data->FullCompletion[$key]) ? $app_data->FullCompletion[$key]:''}}</td>
                                        <td>{{!empty($app_data->FloorTotalArea[$key]) ? $app_data->FloorTotalArea[$key]:''}}</td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                            <tr>
                                <td colspan="4" class="text-right">
                                    ভবনের মেঝের মোট ক্ষেত্রফল
                                </td>
                                <td>
                                    {!! Form::text('building_FloorTotalArea_area', $app_data->building_FloorTotalArea_area,['readonly'=>'readonly', 'class' => 'form-control input-md','id'=>'building_FloorTotalArea_area']) !!}
                                    {!! $errors->first('building_FloorTotalArea_area','<span class="help-block">:message</span>') !!}
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <div class="panel panel-info">
                <div class="panel-heading">Attachments</div>
                <div class="panel-body">
                    <div class="form-group">
                        <div class="row">
                            <div class=" col-md-12">
                                <table class="table table-bordered table-hover" id="loadDetails">
                                    <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Document Name</th>
                                        {{-- <th>Number</th> --}}
                                        {{-- <th>Date</th> --}}
                                        <th>File</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(!empty($document))
                                        @foreach($document as $key => $value)
                                            <tr>
                                                <td>{{$key+1}} .</td>
                                                <td>{{$value->doc_name}}</td>
                                                {{-- <td>{{$value->doc_number}}</td> --}}
                                                {{-- <td>{{$value->date}}</td> --}}
                                                <td>
                                                    <a target="_blank" class="btn btn-xs btn-primary"
                                                       href="{{URL::to('/uploads/'.$value->doc_path)}}"
                                                       title="Other File {{$key+1}}">
                                                        <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                                        Open File
                                                    </a>
                                                </td>
                                            </tr>
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
<script src="{{ asset("vendor/html2pdf/html2pdf.bundle.js") }}"></script>

<script>
    var is_generate_pdf = false;
    document.getElementById("html2pdf").addEventListener("click", function(e) {
        if (!is_generate_pdf) {
            $('#html2pdf').children().removeClass('fa-download').addClass('fa-spinner fa-pulse');
            generatePDF();
        }
    });

    function generatePDF(){
        var element = $('#applicationForm').html();
        var downloadTime = 'Download time: ' + moment(new Date()).format('DD-MMM-YYYY h:mm a');
        var opt = {
            margin:       [0.80,0.50,0.80,0.50], //top, left, bottom, right
            // filename:     'myfile.pdf',
            image:        { type: 'jpeg', quality: 0.98 },
            html2canvas:  { scale: 2 },
            jsPDF:        { unit: 'in', format: 'letter', orientation: 'portrait' },
            enableLinks:  true,
        };

        var html = '<div style="margin-top: 60px">' + element + '</div>';

        html2pdf().from(html).set(opt).toPdf().get('pdf').then(function (pdf) {
            var pageCount = pdf.internal.getNumberOfPages();

            pdf.setPage(1);
            pageWidth = pdf.internal.pageSize.getWidth();
            pageHeight = pdf.internal.pageSize.getHeight();

            var image = "data:image/png;base64,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";
            pdf.addImage(image, 'PNG', pageWidth / 2 - 0.60, 0.50, 1.20, 0.40);

            pdf.setFontSize(14);
            pdf.text("Bangladesh Investment Development Authority (BIDA)", 1.80, 1.20);

            pdf.setFontType("italic");
            pdf.setFontSize(8);
            pdf.setTextColor(32, 32, 32);

            for (let j = 1; j < pageCount + 1 ; j++) {
                pdf.setPage(j);
                pdf.text(`${j} / ${pageCount}`, pageWidth - 1, pageHeight - 0.50);
                pdf.text(downloadTime, 0.60, pageHeight - 0.50);
            }

            //generated url
            var url = pdf.output('bloburl');
            $('#html2pdf').children().removeClass('fa-spinner fa-pulse').addClass('fa-download');
            $('#html2pdf').attr({href: url, target: "_blank"});
            is_generate_pdf = true;
            window.open(url, '_blank');
        });
    }
</script>