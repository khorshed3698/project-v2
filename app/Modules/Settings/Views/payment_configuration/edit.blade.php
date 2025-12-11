@extends('layouts.admin')

@section('content')
    <?php
    $accessMode = ACL::getAccsessRight('settings');
    if (!ACL::isAllowed($accessMode, 'E'))
        die('no access right!');
    ?>
    <div class="col-lg-12">
        {{--Stakeholder modal--}}
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content load_modal"></div>
            </div>
        </div>

        {!! Session::has('success') ? '<div class="alert alert-success alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("success") .'</div>' : '' !!}
        {!! Session::has('error') ? '<div class="alert alert-danger alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("error") .'</div>' : '' !!}

        <div class="panel panel-primary">
            <div class="panel-heading">
                <h5><strong>Edit Payment Configuration</strong></h5>
            </div>

        {!! Form::open(array('url' => '/settings/update-payment-configuration/'.$encrypted_id,'method' => 'patch', 'class' => 'form-horizontal smart-form', 'id' => 'notice-info',
            'enctype' =>'multipart/form-data', 'files' => 'true', 'role' => 'form')) !!}
        <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="form-group col-md-12 {{$errors->has('title') ? 'has-error' : ''}}">
                    {!! Form::label('process_type_id','Process Type: ',['class'=>'col-md-3  required-star']) !!}
                    <div class="col-md-9">
                        {!! Form::select('process_type_id', $processTypes, $data->process_type_id, ['class'=>'form-control bnEng required']) !!}
                        {!! $errors->first('process_type_id','<span class="help-block">:message</span>') !!}
                    </div>
                </div>

                <div class="form-group col-md-12 {{$errors->has('title') ? 'has-error' : ''}}">
                    {!! Form::label('payment_category_id','Payment Category: ',['class'=>'col-md-3  required-star']) !!}
                    <div class="col-md-9">
                        {!! Form::select('payment_category_id', $paymentCategories,$data->payment_category_id, ['class'=>'form-control bnEng required']) !!}
                        {!! $errors->first('payment_category_id','<span class="help-block">:message</span>') !!}
                    </div>
                </div>

                <div class="form-group col-md-12 {{$errors->has('amount') ? 'has-error' : ''}}">
                    {!! Form::label('amount','Amount(Tk.): ',['class'=>'col-md-3  required-star']) !!}
                    <div class="col-md-9">
                        {!! Form::number('amount', $data->amount, ['class'=>'form-control required number']) !!}
                        <span class="text-warning">N.B: Payment amount can be changed or modified only in an inactive state</span>
                        {!! $errors->first('amount','<span class="help-block">:message</span>') !!}
                    </div>
                </div>

                {{--<div class="form-group col-md-12 {{$errors->has('vat_on_transaction_charge_percent') ? 'has-error' : ''}}">--}}
                    {{--{!! Form::label('vat_on_transaction_charge_percent','VAT on transaction charge(%): ',['class'=>'col-md-3 ']) !!}--}}
                    {{--<div class="col-md-9">--}}
                        {{--{!! Form::number('vat_on_transaction_charge_percent', $data->vat_on_transaction_charge_percent, ['class'=>'form-control number']) !!}--}}
                        {{--{!! $errors->first('vat_on_transaction_charge_percent','<span class="help-block">:message</span>') !!}--}}
                    {{--</div>--}}
                {{--</div>--}}

                {{--<div class="form-group col-md-12 {{$errors->has('trans_charge_percent') ? 'has-error' : ''}}">--}}
                    {{--{!! Form::label('trans_charge_percent','Trns Amount(%): ',['class'=>'col-md-3 ']) !!}--}}
                    {{--<div class="col-md-9">--}}
                        {{--{!! Form::number('trans_charge_percent', $data->trans_charge_percent, ['class'=>'form-control number']) !!}--}}
                        {{--{!! $errors->first('trans_charge_percent','<span class="help-block">:message</span>') !!}--}}
                    {{--</div>--}}
                {{--</div>--}}

                {{--<div class="form-group col-md-12 {{$errors->has('trans_charge_min_amount') ? 'has-error' : ''}}">--}}
                    {{--{!! Form::label('trans_charge_min_amount','Trns Charge Min Amount(%): ',['class'=>'col-md-3 ']) !!}--}}
                    {{--<div class="col-md-9">--}}
                        {{--{!! Form::number('trans_charge_min_amount', $data->trans_charge_min_amount, ['class'=>'form-control number']) !!}--}}
                        {{--{!! $errors->first('trans_charge_min_amount','<span class="help-block">:message</span>') !!}--}}
                    {{--</div>--}}
                {{--</div>--}}

                {{--<div class="form-group col-md-12 {{$errors->has('trans_charge_max_amount') ? 'has-error' : ''}}">--}}
                    {{--{!! Form::label('trans_charge_max_amount','Trns Charge Max Amount(%): ',['class'=>'col-md-3 ']) !!}--}}
                    {{--<div class="col-md-9">--}}
                        {{--{!! Form::number('trans_charge_max_amount', $data->trans_charge_max_amount, ['class'=>'form-control number']) !!}--}}
                        {{--{!! $errors->first('trans_charge_max_amount','<span class="help-block">:message</span>') !!}--}}
                    {{--</div>--}}
                {{--</div>--}}


                <div class="form-group col-md-12 {{$errors->has('is_active') ? 'has-error' : ''}}">
                    {!! Form::label('is_active','Status: ',['class'=>'col-md-3 required-star']) !!}
                    <div class="col-md-9">
                        @if(ACL::getAccsessRight('settings','E'))
                            &nbsp;&nbsp;
                            <label>{!! Form::radio('status', '1', $data->status  == '1', ['class'=>' required', 'id' => 'yes']) !!} Active</label>
                            &nbsp;&nbsp;
                            <label>{!! Form::radio('status', '0', $data->status == '0', ['class'=>'required', 'id' => 'no']) !!} Inactive</label>
                        @endif
                        {!! $errors->first('status','<span class="help-block">:message</span>') !!}
                    </div>
                </div>
            </div><!-- /.box -->

            <div class="panel-footer">
                <div class="pull-left">
                    {!! CommonFunction::showAuditLog($data->updated_at, $data->updated_by) !!}
                </div>
                <div class="pull-right">
                    <a href="{{ url('settings/payment-configuration') }}">
                        {!! Form::button('<i class="fa fa-times"></i> Close', array('type' => 'button', 'class' => 'btn btn-default')) !!}
                    </a>
                    @if(ACL::getAccsessRight('settings','E'))
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-chevron-circle-right"></i> Save</button>
                    @endif
                </div>
                <div class="clearfix"></div>
            </div>
        {!! Form::close() !!}<!-- /.form end -->
        </div>

        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="pull-left">
                    <h5><strong><i class="fa fa-list"></i> Payment Distribution</strong></h5>
                </div>
                <div class="pull-right">
                    @if(ACL::getAccsessRight('settings','A'))
                    <a class="addDistribution" data-toggle="modal" data-target="#myModal" onclick="openModal(this)" data-action="{{ url('/settings/stakeholder-distribution/'.Encryption::encodeId($data->id)) }}">
                    {!! Form::button('<i class="fa fa-plus"></i> <b>New stakeholder distribution </b>', array('type' => 'button', 'class' => 'pull-right btn btn-default')) !!}
                    </a>
                    @endif
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <table aria-label="Detailed Report Data Table" class="table table-bordered" id="list">
                            <thead>
                            <tr>
                                <th width="5%">SL</th>
                                <th width="15%">Account Name</th>
                                <th width="15%">Account Number</th>
{{--                                <th width="40%">Purpose</th>--}}
                                <th width="10%">Purpose(SBL)</th>
                                <th width="10%">Amount</th>
                                <th width="10%">Fixed/Unfixed</th>
                                <th width="15%">Distribution type</th>
                                <th width="10%">Status</th>
                                <th width="10%">Action</th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('footer-script')
    <script src="{{asset('vendor/tinymce/tinymce.min.js')}}" type="text/javascript"></script>
    <script>
        var _token = $('input[name="_token"]').val();

        var age = -1;
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        function companyLogo(input) {
            if (input.files && input.files[0]) {
                $("#company_logo_err").html('');
                var mime_type = input.files[0].type;
                if(!(mime_type=='image/jpeg' || mime_type=='image/jpg' || mime_type=='image/png')){
                    $("#company_logo_err").html("Image format is not valid. Only PNG or JPEG or JPG type images are allowed.");
                    return false;
                }
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#companyLogoViewer').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        $(document).ready(function () {
            $("#notice-info").validate({
                errorPlacement: function () {
                    return false;
                }
            });
        });
        tinymce.init({
            selector: '#description_editor',
            height: 150,
            theme: 'modern',
            plugins: [
                'autosave advlist autolink lists link image charmap print preview hr anchor pagebreak',
                'searchreplace wordcount visualblocks visualchars code fullscreen',
                'insertdatetime media nonbreaking save table contextmenu directionality',
                'emoticons template paste textcolor colorpicker textpattern imagetools'
            ],
            toolbar1: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
            toolbar2: 'print preview media | forecolor backcolor emoticons',
            image_advtab: true,
            content_css: [
                // '//fast.fonts.net/cssapi/e6dc9b99-64fe-4292-ad98-6974f93cd2a2.css',
                '//www.tinymce.com/css/codepen.min.css'
            ]
        });
    </script>

    @include('partials.datatable-scripts')
    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
    <script>
        $(function () {
            $('#list').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{url("settings/get-payment-distribution-data")}}',
                    method:'post',
                    data: function (d) {
                        d._token = $('input[name="_token"]').val();
                        d.pay_config_id = "{{ \App\Libraries\Encryption::encodeId($data->id) }}";
                    }
                },
                columns: [
                    {data: 'sl', name: 'sl'},
                    {data: 'stakeholder_ac_name', name: 'stakeholder_ac_name'},
                    {data: 'account_no', name: 'account_no'},
                    // {data: 'purpose', name: 'purpose'},
                    {data: 'purpose_sbl', name: 'purpose_sbl'},
                    {data: 'amount', name: 'amount'},
                    {data: 'fix_status', name: 'fix_status'},
                    {data: 'distribution_type_name', name: 'distribution_type_name'},
                    {data: 'status', name: 'status'},
                    {data: 'action', name: 'action', orderable: true, searchable: true}
                ],
                "aaSorting": []
            });
        });

        function openModal(btn) {
            var this_action = btn.getAttribute('data-action');
            if(this_action != ''){
                $.get(this_action, function(data, success) {
                    if(success === 'success'){
                        $('#myModal .load_modal').html(data);
                    }else{
                        $('#myModal .load_modal').html('Unknown Error!');
                    }
                    $('#myModal').modal('show', {backdrop: 'static'});
                });
            }
        }
    </script>
@endsection <!--- footer script--->