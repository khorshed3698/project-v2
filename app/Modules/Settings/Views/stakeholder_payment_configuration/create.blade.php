@extends('layouts.admin')
@section('content')
    <?php
    $accessMode = ACL::getAccsessRight('settings');
    if (!ACL::isAllowed($accessMode, 'A'))
        die('no access right!');
    ?>
    <div class="col-lg-12">

        {!! Session::has('success') ? '<div class="alert alert-success alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("success") .'</div>' : '' !!}
        {!! Session::has('error') ? '<div class="alert alert-danger alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("error") .'</div>' : '' !!}

        <div class="panel panel-primary">
            <div class="panel-heading">
                <h5><strong>New stakeholder payment configuration </strong></h5>
            </div>

        {!! Form::open(array('url' => '/settings/store-stakeholder-payment-configuration','method' => 'post', 'class' => 'form-horizontal smart-form', 'id' => 'notice-info',
            'enctype' =>'multipart/form-data', 'files' => 'true', 'role' => 'form')) !!}
        <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="form-group col-md-12 {{$errors->has('title') ? 'has-error' : ''}}">
                    {!! Form::label('process_type_id','Process type: ',['class'=>'col-md-3  required-star']) !!}
                    <div class="col-md-9">
                        {!! Form::select('process_type_id', $processTypes,'', ['class'=>'form-control bnEng required']) !!}
                        {!! $errors->first('process_type_id','<span class="help-block">:message</span>') !!}
                    </div>
                </div>

                <div class="form-group col-md-12 {{$errors->has('title') ? 'has-error' : ''}}">
                    {!! Form::label('stakeholders_id','Stakeholders Name: ',['class'=>'col-md-3  required-star']) !!}
                    <div class="col-md-9">
                        {!! Form::select('stakeholder_id', $stakeholders, '', ['class'=>'form-control bnEng required']) !!}
                        {!! $errors->first('stakeholder_id','<span class="help-block">:message</span>') !!}
                    </div>
                </div>

                <div class="form-group col-md-12 {{$errors->has('title') ? 'has-error' : ''}}">
                    {!! Form::label('payment_category_id','Payment category: ',['class'=>'col-md-3  required-star']) !!}
                    <div class="col-md-9">
                        {!! Form::select('payment_category_id', $paymentCategories,'', ['class'=>'form-control bnEng required']) !!}
                        {!! $errors->first('payment_category_id','<span class="help-block">:message</span>') !!}
                    </div>
                </div>

                <div class="form-group col-md-12 {{$errors->has('amount') ? 'has-error' : ''}}">
                    {!! Form::label('amount','Amount(Tk.): ',['class'=>'col-md-3  required-star']) !!}
                    <div class="col-md-9">
                        {!! Form::number('amount', '', ['class'=>'form-control required number']) !!}
                        <span class="text-warning">N.B: Payment amount can be changed or modified only in an inactive state</span>
                        {!! $errors->first('amount','<span class="help-block">:message</span>') !!}
                    </div>
                </div>

                {{--<div class="form-group col-md-12 {{$errors->has('vat_on_transaction_charge_percent') ? 'has-error' : ''}}">--}}
                {{--{!! Form::label('vat_on_transaction_charge_percent','VAT on transaction charge(%): ',['class'=>'col-md-3 ']) !!}--}}
                {{--<div class="col-md-9">--}}
                {{--{!! Form::number('vat_on_transaction_charge_percent', '', ['class'=>'form-control number']) !!}--}}
                {{--{!! $errors->first('vat_on_transaction_charge_percent','<span class="help-block">:message</span>') !!}--}}
                {{--</div>--}}
                {{--</div>--}}

                {{--<div class="form-group col-md-12 {{$errors->has('trans_charge_percent') ? 'has-error' : ''}}">--}}
                {{--{!! Form::label('trans_charge_percent','Transaction charge(%): ',['class'=>'col-md-3 ']) !!}--}}
                {{--<div class="col-md-9">--}}
                {{--{!! Form::number('trans_charge_percent', '', ['class'=>'form-control number']) !!}--}}
                {{--{!! $errors->first('trans_charge_percent','<span class="help-block">:message</span>') !!}--}}
                {{--</div>--}}
                {{--</div>--}}

                {{--<div class="form-group col-md-12 {{$errors->has('trans_charge_min_amount') ? 'has-error' : ''}}">--}}
                {{--{!! Form::label('trans_charge_min_amount','Transaction charge Min amount(%): ',['class'=>'col-md-3 ']) !!}--}}
                {{--<div class="col-md-9">--}}
                {{--{!! Form::number('trans_charge_min_amount', '', ['class'=>'form-control number', 'id'=>'trans_charge_min_amount']) !!}--}}
                {{--{!! $errors->first('trans_charge_min_amount','<span class="help-block">:message</span>') !!}--}}
                {{--</div>--}}
                {{--</div>--}}

                {{--<div class="form-group col-md-12 {{$errors->has('trans_charge_max_amount') ? 'has-error' : ''}}">--}}
                {{--{!! Form::label('trans_charge_max_amount','Transaction charge Max amount(%): ',['class'=>'col-md-3 ']) !!}--}}
                {{--<div class="col-md-9">--}}
                {{--{!! Form::number('trans_charge_max_amount', '', ['class'=>'form-control number', 'id'=>'']) !!}--}}
                {{--{!! $errors->first('trans_charge_max_amount','<span class="help-block">:message</span>') !!}--}}
                {{--</div>--}}
                {{--</div>--}}


                <div class="form-group col-md-12 {{$errors->has('status') ? 'has-error' : ''}}">
                    {!! Form::label('status','Status: ',['class'=>'col-md-3  required-star']) !!}
                    <div class="col-md-9">
                        <label>{!! Form::radio('status', '1', false, ['class'=>'cursor required', 'id' => 'active']) !!}
                            Active</label>
                        &nbsp;&nbsp;
                        <label>{!! Form::radio('status', '0', true, ['class'=>'cursor required', 'id' => 'inactive']) !!}
                            Inactive</label>
                        &nbsp;&nbsp;
                        {!! $errors->first('status','<span class="help-block">:message</span>') !!}
                    </div>
                </div>
            </div><!-- /.box -->


            <div class="panel-footer">
                <div class="pull-left">
                    <a href="{{ url('settings/stakeholder-payment-configuration') }}">
                        {!! Form::button('<i class="fa fa-times"></i> Close', array('type' => 'button', 'class' => 'btn btn-default')) !!}
                    </a>
                </div>
                <div class="pull-right">
                    @if(ACL::getAccsessRight('settings','A'))
                        <button type="submit" class="btn btn-primary pull-right">
                            <i class="fa fa-chevron-circle-right"></i> Save
                        </button>
                    @endif
                </div>
                <div class="clearfix"></div>
            </div>
        {!! Form::close() !!}<!-- /.form end -->
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

        $(document).ready(function () {
            $("#notice-info").validate({
                errorPlacement: function () {
                    return false;
                }
            });
        });

        function companyLogo(input) {
            if (input.files && input.files[0]) {
                $("#company_logo_err").html('');
                var mime_type = input.files[0].type;
                if (!(mime_type == 'image/jpeg' || mime_type == 'image/jpg' || mime_type == 'image/png')) {
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

        //   tinymce.init({ selector:'#description_editor' });
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

@endsection <!--- footer script--->