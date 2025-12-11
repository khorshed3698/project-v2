@extends('layouts.admin')
@section('content')
    <div class="container">
        <div class="row">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-md-6 pull-left">
                            <strong>Individual File Upload</strong>
                        </div>

                        <div class="col-md-6">
                            <button type="button" class="btn btn-primary btn-xs pull-right meeting processListUp pull-right">
                                <strong><i class="fa fa-arrow-down" aria-hidden="true"></i> Individual File</strong>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="row" id="boardMeeting" style="display: none">
                        <div class="col-md-11">
                            {!! Form::open(array('url' => 'new-reg/store_individual_file', 'method' => 'post','enctype'=>'multipart/form-data', 'files' => true, 'role' => 'form', 'id'=> 'formId')) !!}
                            <table  class="table table-hover table-bordered small-text" id="tbBrief" width="100%">
                                <tr class="tr-header">
                                    <th width="5%" class="text-center">Id</th>
                                    <th width="40%" class="text-center">Form Name</th>
                                    <th width="40%" class="text-center">Individual File</th>
                                    <th width="5%" class="text-center">
                                        <a href="javascript:void(0);" style="font-size:18px;" id="addMore" title="Add More Person"><button class="btn btn-primary btn-sm"><span class="fa fa-plus plusBu"></span></button></a>
                                    </th>
                                </tr>
                                <tr>
                                    <td id="incriment" align="center">1</td>
                                    <td>
                                        {!! Form::text('form_name[]', '', ['class' => 'form-control input-md form_name1 required','placeholder' => 'Form Name','id'=>'form_name']) !!}
                                    </td>
                                    <td>
                                        {!! Form::file('file[]', ['class' => 'form-control input-md file1 required','id'=>'file']) !!}
                                    </td>
                                    <td align="center">
                                        <a href='javascript:void(0);'  class='remove'><button class="btn btn-danger btn-sm"><span class='fa fa-times crossBu text-light'></span></button></a>
                                    </td>
                                </tr>
                                <tfoot>
                                    <td colspan="4">
                                        <button type="submit" class="btn btn-primary btn-sm pull-right">Submit</button>
                                    </td>
                                </tfoot>
                            </table>
                            {!! Form::close() !!}
                        </div>
                        <div class="col-md-1"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('footer-script')
    <script type="text/javascript">
        $(function(){
            var row = 2;
            $('#addMore').on('click', function() {
                var incr = $("#tbBrief tr:eq(1)").clone(true);
                incr.find('#form_name').addClass('form_name'+row);
                incr.find("#form_name").removeClass('form_name1');
                incr.find('#file').addClass('file'+row);
                incr.find("#file").removeClass('file1');
                incr.find('td#incriment').html(row);
                incr.appendTo("#tbBrief");
                incr.find("input").val('');
                row++;
            });

            $(document).on('click', '.remove', function() {
                var trIndexIncr = $(this).closest("tr").index();
                if(trIndexIncr>1) {
                    $(this).closest("tr").remove();
                } else {
                    alert("Sorry!! Can't remove first row!");
                }
            });
        });


        $(document).ready(function () {
            $('#formId').validate();
        });


        $(document).ready(function () {
            $('.meeting').on('click', function (e) {
                if ($('#boardMeeting').is(":visible")) {

                    $('.meeting').find('i').removeClass("fa-arrow-up fa");
                    $('.meeting').find('i').addClass("fa fa-arrow-down");
                    $(".meeting").css("background-color", "");
                    $(".meeting").css("color", "");
                } else {
                    $(this).find('i').removeClass("fa fa-arrow-down");
                    $(this).find('i').addClass("fa fa-arrow-up");
                    $(".meeting").css("background-color", "#1abc9c");
                    $(".meeting").css("color", "white");
                }
                $('#boardMeeting').slideToggle();
            });
        });
    </script>
@endsection
