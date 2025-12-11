<style>
    .panel-heading {
        padding: 2px 5px;
        overflow: hidden;
    }

    .row > .col-md-5,
    .row > .col-md-7,
    .row > .col-md-3,
    .row > .col-md-9,
    .row > .col-md-12 > strong:first-child {
        padding-bottom: 5px;
        display: block;
    }

    legend.scheduler-border {
        font-weight: normal !important;
    }

    .table {
        margin: 0;
    }

    .table > tbody > tr > td,
    .table > tbody > tr > th,
    .table > tfoot > tr > td,
    .table > tfoot > tr > th,
    .table > thead > tr > td,
    .table > thead > tr > th {
        padding: 5px;
    }

    .mb5 {
        margin-bottom: 5px;
    }

    .mb0 {
        margin-bottom: 0;
    }
</style>
<section class="content" id="applicationForm">
    @if(in_array($appInfo->status_id,[5,6,17,22]))
        @include('ProcessPath::remarks-modal')
    @endif

    <div class="col-md-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <div class="pull-left">
                    <strong style="line-height: 30px;">
                        Application For VAT Registration
                    </strong>
                </div>
                <div class="pull-right">

                    <a class="btn btn-md btn-success" data-toggle="collapse" href="#paymentInfo" role="button"
                       aria-expanded="false" aria-controls="collapseExample">
                        <i class="far fa-money-bill-alt"></i>
                        Payment Info
                    </a>

                    @if(in_array($appInfo->status_id,[5,6,17,22]))
                        <a data-toggle="modal" data-target="#remarksModal">
                            {!! Form::button('<i class="fa fa-eye"></i> Reason of '.$appInfo->status_name.'', array('type'
                            => 'button', 'class' => 'btn btn-md btn-danger')) !!}
                        </a>
                    @endif
                </div>

            </div>

            <div class="panel-body">

                <ol class="breadcrumb">
                    <li><strong>OSS Tracking no. : </strong>{{ $appInfo->tracking_no  }}</li>
                    @if($appInfo->vat_submission_id !="" && $appInfo->vat_submission_id !=null)
                        <li><strong>VAT Submission no. : </strong>{{ $appInfo->vat_submission_id  }}</li>
                        <li class="highttext"><strong>VAT Message ID. : {{ $appInfo->vat_tracking_no  }}</strong></li>
                    @endif
                    <li class="highttext"><strong> Date of Submission:
                            {{ \App\Libraries\CommonFunction::formateDate($appInfo->submitted_at) }}</strong>
                    </li>
                    <li><strong>Current Status : </strong> {{ $appInfo->status_name }}</li>
{{--                    <li><strong>Current Desk--}}
{{--                            :</strong>--}}
{{--                        {{ $appInfo->desk_id != 0 ? \App\Libraries\CommonFunction::getDeskName($appInfo->desk_id) : 'Applicant' }}--}}
{{--                    </li>--}}
                </ol>


                {{--                Payment information--}}
                {{--                @include('SonaliPaymentStackHolder::payment-information')--}}
                @include('SonaliPaymentStackHolder::payment-information')
                @if($appInfo->status_id ==25 &&  !empty($appInfo->vat_bin))
                    <div class="alert alert-warning alert-dismissible">
                        You application is approved. Please wait few minutes for BIN number and certificate. Please check your email for the approval copy.
                    </div>
                @elseif($appInfo->status_id ==25 && empty($appInfo->vat_bin))
                    <div class="alert alert-warning alert-dismissible">
                        You application is approved. Please wait few minutes for BIN number and certificate. Please check your email for the approval copy.
                    </div>
                @endif


                @if(!empty($appInfo->vat_bin))
                    <div class="panel panel-info">
                        <div class="panel-heading"><strong> Vat Certificate Information </strong></div>
                        <div class="panel-body">
                            <div class="form-group" style="">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Effective Data</span>
                                        </div>
                                        <div class="col-md-7">
                                            {{!empty($appInfo->vat_effective_data) ? \App\Libraries\CommonFunction::changeDateFormat($appInfo->vat_effective_data): ''}}
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Division</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7">
                                            {{!empty($appInfo->vat_division) ? $appInfo->vat_division : ''}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group" style="">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Commission Rate</span>
                                        </div>
                                        <div class="col-md-7">
                                            {{!empty($appInfo->vat_commission_rate) ? $appInfo->vat_commission_rate : ''}}
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Vat BIN</span>
                                        </div>
                                        <div class="col-md-7">
                                            {{!empty($appInfo->vat_bin) ? $appInfo->vat_bin : ''}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

            <!--Section A-->
                <div class="panel panel-primary">
                    <div class="panel-heading"><strong>A. REGISTRATION BASICS</strong></div>
                    <div class="panel-body">

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">Registration Category</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 col-xs-6">
                                        <?php
                                        $reg = '';
                                        if (!empty($appData->reg_category)) {
                                            echo(explode('@', $appData->reg_category)[1]);
                                            $reg = (explode('@', $appData->reg_category)[0]);
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group"
                             style="{{($reg == '2') ?  '' : 'display:none;'}}"
                             id="reRegHiddenDiv">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">Old 11-digit BIN</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <?php
                                    if(!empty($appData->old_bin)){ ?>
                                    <div class="col-md-7">
                                        {{$appData->old_bin}}
                                    </div>

                                    <?php }?>

                                </div>

                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">Name of Company</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <?php if(!empty($appData->company_name)){ ?>
                                    <div class="col-md-7">
                                        {{$appData->company_name}}
                                    </div>
                                    <?php  } ?>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group">
                            <div class="col-md-12">
                                {!! Form::label('reg_category','If you registered a 9-digit BIN before 01-Jul-2019, you must provide that BIN here. By providing the existing BIN, VAT Officer will review your Mushak-2.1 form and decide if you are entitled to link with that existing 9-digit BIN or not', ['class'=>'
                                                           required-star']) !!}
                            </div>
                            <div class="col-md-6">
                                <?php if (!empty($appData->registeredBin) && $appData->registeredBin == 1) { ?>
                                No, I haven't registered for any 9-digit BIN
                                <?php }else { ?>
                                Yes, I have registered a 9-digit BIN
                                <?php } ?>
                            </div>

                            <div class="col-md-8 row"
                                 style=" {{(!empty($appData->bin_number)) ? '' : 'display:none;'}}">
                                <hr>
                                <div class="col-md-6">
                                    Registered 9-digit BIN:
                                </div>
                                <div class="col-md-6">
                                    {{(!empty($appData->bin_number)) ? $appData->bin_number : ''}}
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="clearfix"></div>
                </div>
                <!-- A panel End-->
                <!--Section B-->
                <div class="panel panel-primary">
                    <div class="panel-heading"><strong>B. BUSINESS INFORMATION</strong></div>
                    <div class="panel-body">
                        <div class="form-group" style="">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-6 col-xs-6">
                                        <span class="v_label">B1. Ownership Type</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>

                                    <div class="col-md-6">
                                        <?php
                                        foreach ($appData->ownership_type as $value) {
                                        $whe = (explode('@', $value));
                                        ?>
                                        {{$whe[1]}} ,
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group" style="">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-6 col-xs-6">
                                        <span class="v_label">B2. Are you a Withholding Entity</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>

                                    <div class="col-md-6">
                                        <?php
                                        $whe = (explode('@', $appData->withholding_entity))
                                        ?>
                                        {{$whe[1]}}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="clearfix"></div>
                    </div>
                    <!--/panel-body-->
                </div>
                <!-- B panel End-->
                <!--Section C-->
                <div class="panel panel-primary">
                    <div class="panel-heading"><strong>C. GENERAL INFORMATION</strong></div>
                    <div class="panel-body">

                        <div class="form-group" style="">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">Trade License Number</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{$appData->tl_number}}
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">Issue Date</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{$appData->tl_issue_date}}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group" style="">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">C2. RJSC Incorporation Number</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{$appData->rjsc_inc_number}}
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">Issue Date</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{$appData->rjsc_inc_issue_date}}
                                    </div>

                                </div>
                            </div>
                        </div>


                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">C3. e-TIN</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{$appData->etin}}
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">C4. Name of the Entity (as in e-TIN)</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{$appData->etin_entity_name}}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group" style="">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">C5. Name of the Entity</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{$appData->entity_name}}
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">C6. Trading Brand Name</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{$appData->trading_brand_name}}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group" style="">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">C7. Registration Type</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        <?php
                                        $regType = explode('@', $appData->registration_type);
                                        ?>
                                        {{$regType[1]}}
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">C8. Equity Information</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        <?php
                                        if ($appData->equity_info == 1) {
                                            echo "100% Local";
                                        } elseif ($appData->equity_info == 2) {
                                            echo "100% Foreign";
                                        } elseif ($appData->equity_info == 3) {
                                            echo "Joint Venture";
                                        }

                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group"
                             style="{{(!empty($appData->equity_info) && $appData->equity_info == 3) ? '' : 'display:none;'}}"
                             id="local_share_div">
                            <div class="row">
                                <div class="col-md-offset-6 col-md-6">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">Local Share (%)</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->local_share) ? $appData->local_share : ''}}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group" style="">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">C9. BIDA Registration Numbere</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->bida_reg_number)?$appData->bida_reg_number:""}}
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">Issue Date</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->bida_reg_issue_date)?$appData->bida_reg_issue_date:""}}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="clearfix"></div>
                    </div>
                    <!--/panel-body-->
                </div>
                <!-- C panel End-->
                <!--Section D-->
                <div class="panel panel-primary">
                    <div class="panel-heading"><strong>D. CONTACT INFORMATION</strong></div>
                    <div class="panel-body">

                        <div class="form-group" style="">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">D1. Factory/ Business Operations Address</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{$appData->factory_address}}
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">D2. District</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        <?php
                                        $district = explode('@', $appData->district);
                                        $districtID = !empty($district[1]) ? $district[1] : '';
                                        ?>
                                        {{$districtID}}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group" style="">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">D3. Police Station</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        <?php
                                        $policeStation = explode('@', $appData->police_station);
                                        $policesationID = !empty($policeStation[1]) ? $policeStation[1] : '';
                                        ?>
                                        {{$policesationID}}
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">D4. Postal Code</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        <?php
                                        $postCode = explode('@', $appData->post_code);
                                        $postcodeID = !empty($postCode[1]) ? $postCode[1] : '';
                                        ?>
                                        {{$postcodeID}}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group" style="">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">D5. Land Telephone Number</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{$appData->land_telephone}}
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">D6. Mobile Telephone Number</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{$appData->mobile_telephone}}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group" style="">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">D7. e-Mail</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{$appData->email}}
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">D8. Fax Number</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{$appData->fax}}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group" style="">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">D9. Web Address</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{$appData->web_address}}
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">D10. Headquarter Address</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{$appData->headquarter_address}}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group" style="">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">D11. Headquarter Address outside of Bangladesh</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->headquarter_address_outside)?$appData->headquarter_address_outside:""}}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="clearfix"></div>
                    </div>
                    <!--/panel-body-->
                </div>
                <!-- D panel End-->
                <!--E panel-->
                <?php if(!empty($appData->branch_address)){?>
                <div class="panel panel-primary">
                    <div class="panel-heading"><strong>E. LIST OF BRANCH UNITS YOU WISH TO BRING UNDER CENTRAL
                            REGISTRATION</strong></div>
                    <div class="panel-body">
                        <div id="branchTable" style="{{!empty($appData->branch_address) ? '' : 'display:none;'}}">
                            <table id="branch" class="table table-bordered table-hover">
                                <thead>
                                <tr style="width: 100%;background: #f5f5f7">
                                    <th width="20%">Branch Address</th>
                                    <th width="20%">Branch Name</th>
                                    <th width="20%">Branch Category</th>
                                    <th width="15%">Annual Turnover</th>
                                    <th width="10%">BIN</th>
                                    <th width="10%">Branch ID</th>
                                </tr>
                                </thead>
                                <tbody id="branch_body">

                                @foreach($appData->branch_address as $key => $value)

                                    <tr id="branchInfoRow">
                                        <td>{{$appData->branch_address[$key]}}</td>
                                        <td>{{$appData->e_branch_name[$key]}}</td>
                                        <td>
                                            <?php
                                            $branchCat = explode('@', $appData->branch_category[$key]);
                                            $brachcatID = !empty($branchCat[1]) ? $branchCat[1] : '';
                                            ?>
                                            {{$brachcatID}}
                                        </td>
                                        <td><?php echo number_format($appData->annual_turnover[$key], 2);?></td>
                                        <td>{{$appData->bin[$key]}}</td>
                                        <td>{{$appData->branch_id[$key]}}</td>

                                @endforeach


                                </tbody>
                            </table>
                        </div>

                        <div class="clearfix">
                        </div>
                    </div>
                </div>
                <!--E panel End-->
                <?php }?>
            <!--Section F-->
                <?php if (!empty($appData->economic_activity)) { ?>
                <div class="panel panel-primary">
                    <div class="panel-heading"><strong>F. MAJOR AREA OF ECONOMIC ACTIVITY</strong></div>
                    <div class="panel-body">
                        <div class="form-group" style="">
                            <div class="row">
                                <div class="col-md-6">
                                    <?php
                                    foreach ($appData->economic_activity as $value) {
                                    $eco = (explode('@', $value));
                                    ?>
                                    {{$eco[1]}} ,
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="clearfix"></div>
                </div>
                <?php } ?>
            <!-- F panel End-->
                <!--Section G-->
                <?php if (!empty($appData->economic_area)) { ?>
                <div class="panel panel-primary">
                    <div class="panel-heading"><strong>G. AREAS OF MANUFACTURING</strong></div>
                    <div class="panel-body">
                        <div class="form-group" style="">
                            @if(!empty($appData->economic_area) && (!empty($appData->economic_area)))
                                <?php
                                foreach ($appData->economic_area as $value) {
                                $eco = (explode('@', $value));
                                ?>
                                {{$eco[1]}} ,
                                <?php } ?>
                            @else
                                Not Applicable
                            @endif
                        </div>
                    </div>

                    <div class="clearfix"></div>
                </div>
                <?php }?>
            <!-- G panel End-->
                <!--Section H-->
                <?php if (!empty($appData->area_service)) { ?>
                <div class="panel panel-primary">
                    <div class="panel-heading"><strong>H. AREA OF SERVICE</strong></div>
                    <div class="panel-body">
                        <div class="form-group" style="">
                            @if(!empty($appData->area_service) && (!empty($appData->area_service)))
                                <?php
                                foreach ($appData->area_service as $value) {
                                $eco = (explode('@', $value));
                                ?>
                                {{$eco[1]}} ,
                                <?php } ?>
                            @else
                                Not Applicable
                            @endif
                        </div>
                    </div>

                    <div class="clearfix"></div>
                </div>
                <?php } ?>
            <!-- H panel End-->
                <!--Section I-->
                <?php if (!empty($appData->commercial_description)) { ?>
                <div class="panel panel-primary">
                    <div class="panel-heading"><strong>I. BUSINESS CLASSIFICATION CODE</strong></div>
                    <div class="panel-body">
                        <table id="classification" class="table table-bordered table-hover">
                            <thead>
                            <tr style="width: 100%;background: #f5f5f7">
                                <th width="35%">Commercial Description of Supply</th>
                                <th width="30%">HS/Service Code</th>
                                <th width="30%">Description of HS/Service Code</th>
                            </tr>
                            </thead>
                            <tbody id="classification_body">
                            @foreach($appData->commercial_description as $key => $value)
                                <tr id="classificationRow">
                                    <td>{{$appData->commercial_description[$key]}}</td>
                                    <td>{{$appData->hs_code[$key]}}</td>
                                    <td>{{$appData->hs_description[$key]}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php } ?>
            <!-- I panel End-->
                <!--J panel-->
                <?php if (!empty($appData->account_name)) { ?>
                <div class="panel panel-primary">
                    <div class="panel-heading"><strong>J. BANK ACCOUNT DETAILS</strong></div>
                    <div class="panel-body">
                        <div id="bankaccountTable" style="{{!empty($appData->account_name) ? '' : 'display:none;'}}">
                            <table id="bankaccountInfo" class="table table-bordered table-hover">
                                <thead>
                                <tr style="width: 100%;background: #f5f5f7">
                                    <th width="25%">Account Name</th>
                                    <th width="25%">Account Number</th>
                                    <th width="25%">Bank Name</th>
                                    <th width="20%">Branch</th>
                                </tr>
                                </thead>
                                <tbody id="bankaccount_body">
                                @foreach($appData->account_name as $key => $value)

                                    <tr id="bankAccountRow">
                                        <td>{{$appData->account_name[$key]}}</td>
                                        <td>{{$appData->account_number[$key]}}</td>
                                        <td>
                                            <?php
                                            $bankname = explode('@', $appData->bank_name[$key]);
                                            $bankID = !empty($bankname[1]) ? $bankname[1] : '';
                                            ?>
                                            {{$bankID}}

                                        </td>
                                        <td>
                                            <?php
                                            $branchname = explode('@', $appData->branch_name[$key]);
                                            $branchID = !empty($branchname[1]) ? $branchname[1] : '';
                                            ?>
                                            {{$branchID}}
                                        </td>
                                    </tr>

                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="clearfix">
                        </div>
                    </div>
                </div>
                <?php } ?>
            <!--J panel End-->
                <!--K panel-->
                <?php if (!empty($appData->e_tin)) {?>
                <div class="panel panel-primary">
                    <div class="panel-heading"><strong>K. INFORMATION ABOUT OWNERS/DIRECTORS/HEAD OF ENTITY</strong>
                    </div>
                    <div class="panel-body">
                        <table id="ownerInfo" class="table table-bordered table-hover">
                            <thead>
                            <tr style="width: 100%;background: #f5f5f7">
                                <th width="12%">e-TIN</th>
                                <th width="12%">Full Name</th>
                                <th width="12%">Designation</th>
                                <th width="6%">Share (%)</th>
                                <th width="11%">Identity Category</th>
                                <th width="12%">NID</th>
                                <th width="10%">Passport No</th>
                                <th width="10%">Nationality</th>
                                <th width="10%">BIN</th>
                            </tr>
                            </thead>
                            <tbody id="owner_body">
                            @foreach($appData->e_tin as $key => $value)
                                <tr>
                                    <td>{{!empty($appData->e_tin[$key]) ? $appData->e_tin[$key] :''}}</td>
                                    <td>{{!empty($appData->owner_name[$key]) ? $appData->owner_name[$key] :''}}</td>
                                    <td><?php
                                        $des = explode('@', $appData->owner_designation[$key]);
                                        ?>
                                        {{$des[1]}}
                                    </td>
                                    <td>{{!empty($appData->share[$key]) ? $appData->share[$key] :''}} </td>
                                    <td><?php
                                        $idc = explode('@', $appData->identity_category_owner[$key]);
                                        ?>
                                        {{$idc[1]}}
                                    </td>
                                    <td>{{!empty($appData->owner_nid[$key]) ? $appData->owner_nid[$key] :''}}</td>
                                    <td>{{!empty($appData->owner_passport_no[$key]) ? $appData->owner_passport_no[$key] :''}}</td>
                                    <td>{{!empty($appData->owner_nationality[$key]) ?  explode('@',$appData->owner_nationality[$key])[1] :''}}</td>
                                    <td>{{!empty($appData->owner_bin[$key]) ? $appData->owner_bin[$key] :''}}</td>

                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                        <div class="clearfix">
                        </div>
                    </div>
                </div>
                <?php } ?>
            <!--K panel End-->
                <!--L panel-->
                <div class="panel panel-primary">
                    <div class="panel-heading"><strong>L. BUSINESS OPERATIONS</strong></div>
                    <div class="panel-body">
                        <div class="form-group" style="">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-7 col-xs-6">
                                        <span class="v_label">L1. Taxable Turnover in past 12 Months Period</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-5">
                                        {{$appData->taxable_turnover}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-7 col-xs-6">
                                        <span class="v_label">L2. Projected Turnover in next 12 Months Period</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-5">
                                        {{$appData->projected_turnover}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group" style="">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-7 col-xs-6">
                                        <span class="v_label">L3. Number of Employees</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-5">
                                        {{$appData->employee_number}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-7 col-xs-6">
                                        <span class="v_label">L4. Are you making any Zero Rated Supply?</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-5">
                                        {{!empty($appData->zero_rated_supply) ? $appData->zero_rated_supply :''}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group" style="">
                            <div class="row">
                                <div class="col-md-10">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">L5. Are you making any VAT Exempted Supply?</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-5">
                                        {{!empty($appData->vat_extended_supply) ? $appData->vat_extended_supply :''}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if(!empty($appData->physical_condition) && !empty($appData->physical_condition))
                            <div class="form-group" style="">
                                <div class="row col-md-10">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">L6. Major Capital Machinery</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                </div>

                                <table id="MajorCapitalInfo" class="table table-bordered table-hover">
                                    <thead>
                                    <tr style="width: 100%;background: #f5f5f7">
                                        <th width="25%">Description</th>
                                        <th width="20%">HS/Service Code</th>
                                        <th width="15%">Value in BDT</th>
                                        <th width="25%">Production Capacity</th>
                                        <th width="10%">Physical Condition</th>
                                    </tr>
                                    </thead>
                                    <tbody id="MajorCapital_body">

                                    @foreach($appData->physical_condition as $key => $value)
                                        <tr id="MajorCapital_row">
                                            <td>{{!empty($appData->description[$key]) ? $appData->description[$key] :''}}</td>
                                            <td>{{!empty($appData->hs_code_major[$key]) ? $appData->hs_code_major[$key] :''}}</td>
                                            <td>{{!empty($appData->value_bdt[$key]) ? $appData->value_bdt[$key] :''}}</td>
                                            <td>{{!empty($appData->production_capacity[$key]) ? $appData->production_capacity[$key] :''}}</td>
                                            <td>{{!empty($appData->physical_condition[$key]) ? explode('@',$appData->physical_condition[$key])[1] :''}} </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                        @if(!empty($appData->commercial_description_output))
                            <div class="form-group" style="">
                                <div class="row col-md-10">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">L7. Input-Output Data</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                </div>
                                <table id="InputOutputInfo" class="table table-bordered table-hover">
                                    <thead>
                                    <tr style="width: 100%;background: #f5f5f7">
                                        <th width="20%">Commercial Description of Output</th>
                                        <th width="13%">HS/Service Code Output</th>
                                        <th width="15%">Selling Unit</th>
                                        <th width="19%">Description of Major Inputs</th>
                                        <th width="13%">HS/Service Code Input</th>
                                        <th width="15%">Quantity of Input used in per Unit of Output</th>
                                    </tr>
                                    </thead>
                                    <tbody id="InputOutput_body">

                                    @foreach($appData->commercial_description_output as $key => $value)
                                        <tr>
                                            <td>{{!empty($appData->commercial_description_output[$key]) ? $appData->commercial_description_output[$key] :''}}</td>
                                            <td>{{!empty($appData->hs_code_output[$key]) ? $appData->hs_code_output[$key] :''}}</td>
                                            <td>{{!empty($appData->selling_unit[$key]) ? $appData->selling_unit[$key] :''}}</td>
                                            <td>{{!empty($appData->description_major_inputs[$key]) ? $appData->description_major_inputs[$key] :''}}</td>
                                            <td>{{!empty($appData->hs_code_input[$key]) ? $appData->hs_code_input[$key] :''}} </td>
                                            <td>{{!empty($appData->quantity_used[$key]) ? $appData->quantity_used[$key] :''}}</td>
                                        </tr>
                                    @endforeach

                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>

                    <div class="clearfix"></div>
                </div>
                <!--L panel End-->
                <!--M panel-->
                <?php if (!empty($appData->identity_category_authorized)) {?>
                <div class="panel panel-primary">
                    <div class="panel-heading"><strong>M. AUTHORISED PERSONS INFORMATION FOR ONLINE ACTIVITY</strong>
                    </div>
                    <div class="panel-body">
                        <div class="form-group" style="">
                            <table id="authorizedInfo" class="table table-bordered table-hover">
                                <thead>
                                <tr style="width: 100%;background: #f5f5f7">
                                    <th width="10%">Full Name</th>
                                    <th width="10%">Designation</th>
                                    <th width="10%">Identity Category</th>
                                    <th width="10%">NID</th>
                                    <th width="10%">Passport No</th>
                                    <th width="10%">Nationality</th>
                                    <th width="11%">Mobile</th>
                                    <th width="12%">Email</th>
                                    <th width="12%">purpose</th>
                                </tr>
                                </thead>
                                <tbody id="authorized_body">
                                @if(!empty($appData->identity_category_authorized))
                                    @foreach($appData->identity_category_authorized as $key => $value)
                                        <tr>
                                            <td>{{!empty($appData->full_name_authorized[$key]) ? $appData->full_name_authorized[$key] :''}}</td>
                                            <td>{{!empty($appData->authorized_designation[$key]) ? explode('@',$appData->authorized_designation[$key])[1] :''}}</td>
                                            <td>{{!empty($appData->identity_category_authorized[$key]) ?  explode('@',$appData->identity_category_authorized[$key])[1] :''}}</td>
                                            <td>{{!empty($appData->authorized_nid[$key]) ? $appData->authorized_nid[$key] :''}}</td>
                                            <td>{{!empty($appData->authorized_passport_no[$key]) ? $appData->authorized_passport_no[$key] :''}}</td>
                                            <td>{{!empty($appData->authorized_nationality[$key]) ?  explode('@',$appData->authorized_nationality[$key])[1] :''}}</td>
                                            <td>{{!empty($appData->authorized_mobile[$key]) ? $appData->authorized_mobile[$key] :''}}</td>
                                            <td>{{!empty($appData->authorized_email[$key]) ? $appData->authorized_email[$key] :''}}</td>
                                            <td>{{!empty($appData->purpose[$key]) ?  explode('@',$appData->purpose[$key])[1] :''}}</td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php } ?>
            <!--M panel End-->

                <!--Attachement Section-->
                <div class="panel panel-primary">
                    <div class="panel-heading"><strong>Attachments</strong></div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover ">
                                <thead>
                                <tr>
                                    <th>No.</th>
                                    <th colspan="6">Required Attachments</th>
                                    <th colspan="2">Attached file</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $i = 1; ?>
                                @foreach($document as $row)
                                    <tr>
                                        <td>{!! $i !!} .</td>
                                        <td colspan="6"> {{$row->doc_name}}</td>
                                        <td colspan="2">
                                            <?php
                                            if($row->doc_path !== ''){
                                            ?>
                                            <a target="_blank" class="btn btn-xs btn-primary"
                                               href="{{URL::to('/uploads/'.$row['doc_path'])}}"
                                               title="{{$row['document_name_en']}}">
                                                <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                                Open File
                                            </a>
                                            <?php }?>
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


        </div>

    </div>


</section>