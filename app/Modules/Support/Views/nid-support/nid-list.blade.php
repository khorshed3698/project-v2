@extends('layouts.admin')

@section('page_heading',trans('messages.pdf-print-requests'))

@section('content')
    <?php
    $accessMode = ACL::getAccsessRight('settings');
    if (!ACL::isAllowed($accessMode, 'PPR-ESQ')) {
        die('You have no access right! Please contact system admin for more information');
    }
    ?>

    @include('partials.messages')
    <div class="col-lg-12">
        <div class="panel panel-primary">
            <div class="panel-heading">

                <div class="pull-left">
                    <h5>
                        <strong>
                            <i class="fa fa-list"></i>
                            <strong>Nid List</strong>
                        </strong>
                    </h5>
                </div>

                <div class="clearfix"></div>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="nav-tabs-custom" style="margin-top: 15px;padding: 0px 5px;">
                    <ul class="nav nav-tabs">
                        <li id="tab1" class="active">
                            <a data-toggle="tab" href="#list_table" class="list" aria-expanded="true">
                                <b>List</b>
                            </a>
                        </li>

                        <li id="tab2" class="">
                            <a data-toggle="tab" href="#list_search" aria-expanded="false">
                                <b>Search</b>
                            </a>
                        </li>
                    </ul>
                </div>


                <div class="tab-content">

                    <div id="list_table" class="table-responsive tab-pane active" style="margin-top: 20px;">
                        <table aria-label="Detailed Report Nid List" id="list" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0"
                               width="100%">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>National Id</th>
                                <th>Date of Birth</th>
                                <th>Verification Flag</th>
                                <th>Submitted Date</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>

                        </table>

                    </div>

                    <div id="list_search" class="tab-pane" style="margin-top: 20px">
                        @include('Support::nid-support.search')
                    </div>
                </div>

            </div><!-- /.panel-body -->
        </div><!-- /.panel -->
    </div><!-- /.col-lg-12 -->

    <div class="col-md-12">

        <div class="modal fade" id="modalInfoView" role="dialog">
            @include('Support::nid-support.modal.modal_info_view')
        </div>

        <div class="modal fade" id="modalInfoEdit" role="dialog">
            @include('Support::nid-support.modal.modal_info_edit')
        </div>

    </div>

@endsection

@section('footer-script')
    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

    @include('partials.datatable-scripts')
    <script language="javascript">

        $(function () {

            $('#list').DataTable({
                iDisplayLength: 50,
                processing: true,
                serverSide: true,
                searching: true,
                ajax: {
                    url: '{{url("support/get-nid-list")}}',
                    method: 'post',
                    data: function (d) {
                        d._token = $('input[name="_token"]').val();
                    }
                },
                columns: [
                    {data: 'name', name: 'name'},
                    {data: 'nid', name: 'nid'},
                    {data: 'dob', name: 'dob'},
                    {data: 'verification_flag', name: 'verification_flag'},
                    {data: 'submitted_at', name: 'submitted_at'},
                    {data: 'action', name: 'action', orderable: true, searchable: true}
                ],
                "aaSorting": []
            });


            // load modal to view nid info
            $(document).on('click', '.nidInfoView', function (e) {

                var objectHtml = $(this);
                objectHtml.html('<i class="fa fa-spinner fa-spin loading"></i> &nbsp;Loading...');
                $.ajax({
                    type: "GET",
                    url: '/support/view-nid-details/' + objectHtml.attr('nid') + '/' + objectHtml.attr('birthdate'),
                    dataType: "json",
                    success: function (response) {
                        objectHtml.html('<i class="fa fa-eye"></i> View NID');
                        if (response.success === true) {
                            $('#modalInfoView').modal();

                            $('#photo').attr("src", "data:image/jpeg;base64," + response.data.photo);
                            $('#date_of_birth').text(response.dob);
                            $('#name_bn').text(response.data.nameBangla);
                            $('#name_en').text(response.data.nameEnglish);
                            $('#father_name').text(response.data.father);
                            $('#mother_name').text(response.data.mother);
                            $('#user_nid').text(response.nid);
                            // $('#gender').text(response.data.nameBangla);


                            var address = '';
                            if (response.data.permanentAddress.homeOrHoldingNo) {
                                address = response.data.permanentAddress.homeOrHoldingNo + ' ,';
                            }
                            address = address + response.data.permanentAddress.villageOrRoad + ' ,' + response.data.permanentAddress.upozila + ' ,' + response.data.permanentAddress.district;
                            if (response.data.permanentAddress.postalCode) {
                                address = address.concat(' - ', response.data.permanentAddress.postalCode);
                            }
                            $('#address').text(address);

                        } else if (response.success === false) {
                            alert(response.data);

                        } else {
                            alert('Sorry! Unknown error occurred');
                        }
                    }
                });
            });

            // load modal to edit nid info
            $(document).on('click', '.nidInfoEdit', function (e) {

                var objectHtml = $(this);
                objectHtml.html('<i class="fa fa-spinner fa-spin"></i> &nbsp;Loading...');
                $.ajax({
                    type: "GET",
                    url: '/support/nid-edit/' + objectHtml.attr('nid') + '/' + objectHtml.attr('birthdate'),
                    success: function (response) {
                        objectHtml.html('<i class="fa fa-edit"></i> Edit');
                        if (response != '') {
                            $('#modalInfoEdit').modal();
                            $('#verification_flag').val(response.data.verification_flag);
                            $('#no_of_try').val(response.data.no_of_try);
                            $('#nid').val(response.data.nid);
                            $('#dob').val(response.data.dob);
                            $('#encoded_nid_id').val(response.data.encoded_nid_id);

                            $('.datepicker').datetimepicker({
                                viewMode: 'years',
                                format: 'DD-MMM-YYYY',
                                maxDate: (new Date()),
                                minDate: '01/01/1905'
                            });

                            var action_url = '{{ URL::to('/') }}' + '/' + response.data.url;
                            $('#edit_nid').attr('action', action_url);
                        }
                    }
                });
            });

        });

    </script>
    @yield('footer-script2')
@endsection
