@extends('layouts.admin')
@section('content')
    <?php
    $moduleName = Request::segment(1);
    $user_type = CommonFunction::getUserType();
    $accessMode = "V";
    if (!ACL::isAllowed($accessMode, 'V'))
        die('no access right!');

    ?>
    <style>
        .tabFont{
            font-size: 13px;
            padding: 8px 8px 12px;
        }
    </style>
    <section class="content">
        <div class="box">
            <div class="box-body">
                <div class="col-lg-12">
                    {!! Session::has('success') ? '<div class="alert alert-success alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("success") .'</div>' : '' !!}
                    {!! Session::has('error') ? '<div class="alert alert-danger alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("error") .'</div>' : '' !!}
                </div>
                <div class="col-lg-12">
                    <div class="panel panel-info" style="">
                        <div class="panel-heading">
                            <div class="pull-left">
                                <h5><i class="fa fa-list"></i> <b>Licence Application Home</b></h5>
                            </div>
                            <div class="clearfix"></div>
                        </div>

                        <div class="panel-body">
                            <div class="col-md-12">
                                <fieldset id="VisaRecommendationForm-p-0" role="tabpanel"
                                          aria-labelledby="VisaRecommendationForm-h-0" class="body current"
                                          aria-hidden="false">
                                    <div class="visa_type_box">

                                        <div  id="tab" class="nav nav-tabs" data-toggle="buttons">
                                            <a href="#tab1" class="tabFont showInPreview active btn btn-lg btn-info" data-toggle="tab">
                                                <input class="badgebox required active" onchange="setVisaType2(this.value, 2)"
                                                       name="app_type_mapping_id" type="radio" checked value="3"> Name Clearance
                                                <span class="badge">✓</span>
                                            </a>
                                            <a href="#tab2" class="tabFont showInPreview btn btn-lg btn-info" data-toggle="tab">
                                                <input class="badgebox required" onchange="setVisaType2(this.value, 4)"
                                                       name="app_type_mapping_id" type="radio" value="4">Bank Account
                                                <span class="badge">✓</span>
                                            </a>
                                            <a href="#tab3" class="tabFont showInPreview btn btn-lg btn-info" data-toggle="tab">
                                                <input class="badgebox required" onchange="setVisaType2(this.value, 3)"
                                                       name="app_type_mapping_id" type="radio" value="10">Company Registration
                                                <span class="badge">✓</span>
                                            </a>
                                            <a href="#tab4" class="tabFont showInPreview btn btn-lg btn-info" data-toggle="tab">
                                                <input class="badgebox required" onchange="setVisaType2(this.value, 5)"
                                                       name="app_type_mapping_id" type="radio" value="38"> E-TIN
                                                <span class="badge">✓</span>
                                            </a>
                                            <a href="#tab5" class="tabFont showInPreview btn btn-lg btn-info" data-toggle="tab">
                                                <input class="badgebox required" onchange="setVisaType2(this.value, 6)"
                                                       name="app_type_mapping_id" type="radio" value="38"> Trade License
                                                <span class="badge">✓</span>
                                            </a>
                                            <a href="#tab6" class="tabFont showInPreview btn btn-lg btn-info" data-toggle="tab">
                                                <input class="badgebox required" onchange="setVisaType2(this.value, 7)"
                                                       name="app_type_mapping_id" type="radio" value="38"> Attachment
                                                <span class="badge">✓</span>
                                            </a>
                                            <a href="#tab7" class="tabFont showInPreview btn btn-lg btn-info" data-toggle="tab">
                                                <input class="badgebox required" onchange="setVisaType2(this.value, 7)"
                                                       name="app_type_mapping_id" type="radio" value="38"> Payment
                                                <span class="badge">✓</span>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="tab-content visaTypeTabContent">
                                        <div class="tab-pane visaTypeTabPane fade in active" id="tab1">
                                            <div class="col-sm-12">
                                                <div class="visa_type_box">
                                                    <h4 class="page-header" style="color: #000066; margin: 25px 0 20px 0px">Application for Name Clearance:  <a href="/single-licence/name-clearance/add" class="btn btn-md btn-primary">Apply </a></h4>

                                                </div>
                                                <div class="btn btn-info pull-right">Save & Next</div>

                                            </div>
                                        </div>
                                        <div class="tab-pane visaTypeTabPane fade in" id="tab2"><br>
                                            @include("SingleLicence::bankAccount.application-form-edit")
                                        </div>
                                        <div class="tab-pane visaTypeTabPane fade in" id="tab3"><br>
                                            @include("SingleLicence::company-registration.application-form-edit")
                                        </div>
                                        <div class="tab-pane visaTypeTabPane fade in" id="tab4"><br>
                                            @include("SingleLicence::etin.application-form-edit")
                                        </div>
                                        <div class="tab-pane visaTypeTabPane fade in" id="tab5">
{{--                                            {{dd($document)}}--}}<br>
                                            @if($appInfoTradeLi == null)
                                               @include("SingleLicence::trade-licence.application-form")
                                            @else
                                               @include("SingleLicence::trade-licence.application-form-edit")
                                            @endif

                                        </div>
                                        <div class="tab-pane visaTypeTabPane fade in" id="tab6">
                                            {!! Form::open(array('url' => 'single-licence/attachment','method' => 'post','id' => 'attachment','role'=>'form','enctype'=>'multipart/form-data')) !!}
                                            <fieldset><br>
                                                <div id="docListDiv">
                                                    <div class="panel panel-info">
                                                        <div class="panel-heading"><strong>Necessary documents to be attached here (Only PDF file to be attach here)</strong>
                                                        </div>
                                                        <div class="panel-body">
                                                            <div class="table-responsive">
                                                                <table class="table table-striped table-bordered table-hover">
                                                                    <thead>
                                                                    <tr>
                                                                        <th>No.</th>
                                                                        <th colspan="6">Required attachments</th>
                                                                        <th colspan="2">Attached PDF file (Each File Max. size 2MB)
                                                                            {{--<span>--}}
                                                                            {{--<i title="Attached PDF file (Each File Maximum size 1MB)!" data-toggle="tooltip" data-placement="right" class="fa fa-question-circle" aria-hidden="true"></i>--}}
                                                                            {{--</span>--}}
                                                                        </th>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    <?php $i = 1; ?>
                                                                    @if(count($document) > 0)
                                                                        @foreach($documentSingleLic as $row)
                                                                            <tr>
                                                                                <td>
                                                                                    <div align="left">{!! $i !!}<?php echo $row->doc_priority == "1" ? "<span class='required-star'></span>" : ""; ?></div>
                                                                                </td>
                                                                                <td colspan="6">{!!  $row->doc_name !!}</td>
                                                                                <td colspan="2">
                                                                                    <input name="document_id_<?php echo $row->id; ?>" type="hidden"
                                                                                           value="{{(!empty($row->document_id) ? $row->document_id : '')}}">
                                                                                    <input type="hidden" value="{!!  $row->doc_name !!}"
                                                                                           id="doc_name_<?php echo $row->id; ?>"
                                                                                           name="doc_name_<?php echo $row->id; ?>"/>


                                                                                    <input name="is_old_file_<?php echo $row->id; ?>" value="{{$row->is_old_file}}" type="hidden" id="is_old_file_<?php echo $row->id; ?>"/>
                                                                                    @if($row->is_old_file == 0 && $viewMode != 'on')
                                                                                        <input name="file<?php echo $row->id; ?>"
                                                                                               <?php if (empty($row->doc_file_path) && empty($allRequestVal["file$row->id"]) && $row->doc_priority == "1") {
                                                                                                   echo "class='required'";
                                                                                               } ?>
                                                                                               id="file<?php echo $row->id; ?>" type="file" size="20"
                                                                                               onchange="uploadDocument('preview_<?php echo $row->id; ?>', this.id, 'validate_field_<?php echo $row->id; ?>', '<?php echo $row->doc_priority; ?>')"/>
                                                                                    @endif

                                                                                    {{--additional field area--}}
                                                                                    @if($row->additional_field == 1)
                                                                                        <table>
                                                                                            <tr>
                                                                                                <td>Other file Name :</td>
                                                                                                <td><input maxlength="64"
                                                                                                           class="form-control input-sm <?php if ($row->doc_priority == "1") {
                                                                                                               echo 'required';
                                                                                                           } ?>"
                                                                                                           name="other_doc_name_<?php echo $row->id; ?>"
                                                                                                           type="text"
                                                                                                           value="{{(!empty($row->doc_name) ? $row->doc_name : '')}}">
                                                                                                </td>
                                                                                            </tr>
                                                                                        </table>
                                                                                    @endif


                                                                                    {{-- if this document hav attachment then show it --}}
                                                                                    @if(!empty($row->doc_file_path))
                                                                                        <div class="save_file saved_file_{{$row->id}}">
                                                                                            <a target="_blank" class="documentUrl" href="{{URL::to('/uploads/'.(!empty($row->doc_file_path) ? $row->doc_file_path : ''))}}"
                                                                                               title="{{$row->doc_name}}">
                                                                                                <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                                                                                <?php $file_name = explode('/', $row->doc_file_path); echo end($file_name); ?>
                                                                                            </a>
                                                                                            @if($row->is_old_file == 1)
                                                                                                <small class="text-danger" style="display: block"><i class="fa fa-info-circle"></i> The document has come from Basic information and is not changeable.</small>
                                                                                            @endif

                                                                                            <?php if($viewMode != 'on' && $row->is_old_file == 0) {?>
                                                                                            <a href="javascript:void(0)" onclick="removeAttachedFile({!! $row->id !!}, {!! $row->doc_priority !!})"><span class="btn btn-xs btn-danger"><i class="fa fa-times"></i></span> </a>
                                                                                            <?php } ?>
                                                                                        </div>
                                                                                    @endif

                                                                                    <div id="preview_<?php echo $row->id; ?>">
                                                                                        <input type="hidden"
                                                                                               value="<?php echo !empty($row->doc_file_path) ?
                                                                                                   $row->doc_file_path : ''?>"
                                                                                               id="validate_field_<?php echo $row->id; ?>"
                                                                                               name="validate_field_<?php echo $row->id; ?>"
                                                                                               class="<?php echo $row->doc_priority == "1" ? "required" : '';  ?>"/>
                                                                                    </div>

                                                                                    @if(!empty($allRequestVal["file$row->id"]))
                                                                                        <label id="label_file{{$row->id}}"><b>File: {{$allRequestVal["file$row->id"]}}</b></label>
                                                                                        <input type="hidden" class="required"
                                                                                               value="{{$allRequestVal["validate_field_".$row->id]}}"
                                                                                               id="validate_field_{{$row->id}}"
                                                                                               name="validate_field_{{$row->id}}">
                                                                                    @endif

                                                                                </td>
                                                                            </tr>
                                                                            <?php $i++; ?>
                                                                        @endforeach
                                                                    @else
                                                                        <tr>
                                                                            <td colspan="9" style="text-align: center"><span class="label label-info">No Required Documents!</span></td>
                                                                        </tr>
                                                                    @endif
                                                                    <tr>
                                                                        <td>N.B</td>
                                                                        <td colspan="6">All documents shall have to be attested by the Chairman/ CEO / Managing dirctor/ Country Manager/ Chief executive of the Company/ firms.</td>
                                                                        <td colspan="2">Document's must be submitted by an authorized person of the organization including the letter of authorization.</td>
                                                                    </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>


                                                    {{--<script>--}}
                                                    {{--$('[data-toggle="tooltip"]').tooltip();--}}
                                                    {{--</script>--}}

                                                </div>

                                                <div class="form-group">
                                                    <div class="checkbox">
                                                        <label>
                                                            {!! Form::checkbox('accept_terms',1,null, array('id'=>'accept_terms', 'class'=>'required')) !!}
                                                            I do here by declare that the information given above is true to the best of my knowledge and I shall be liable for any false information/system is given
                                                        </label>
                                                    </div>
                                                </div>
                                                <input type="submit" class="btn btn-info pull-right" value="Save & Next">
                                            </fieldset>

                                        </div>
                                        <div class="tab-pane visaTypeTabPane fade in" id="tab7">
                                            <div class="col-sm-12">
                                                <div class="visa_type_box">
                                                    <h3 class="page-header">You have selected Visa On Arrival. Please
                                                        read the following instructions:</h3>
                                                    <p>asdfsdf</p>

                                                    <div class="form-group">
                                                        <div class="checkbox">
                                                            <label>
                                                                <input id="eTypeChecked" class="required"
                                                                       name="agree_with_instruction" type="checkbox"
                                                                       value="1">
                                                                I have read the above information and the relevant
                                                                guidance notes.
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <h4 id="selected_visa_type" style="margin-top: 0px; display: none;">Visa
                                                    type: Visa On Arrival</h4>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
@section('footer-script')
    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>"/>

    @include('partials.datatable-scripts')
    <script language="javascript">
        var url = document.location.toString();
        if (url.match('#')) {
            $('.nav-tabs a[href="#' + url.split('#')[1] + '"]').tab('show');
        }
        $('#attachment').validate();
    </script>
    <style>
        * {
            font-weight: normal;
        }

        .unreadMessage td {
            font-weight: bold;
        }
    </style>

@endsection