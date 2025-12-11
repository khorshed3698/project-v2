@extends('layouts.admin')

@section('page_heading',trans('messages.pdf-print-requests'))

@section('content')
    <style>
       .no-border-input{
           border:none;
           background-color: inherit;
           padding-left: 1px;
           padding-right: 1px;
       }
    </style>
<?php $accessMode=ACL::getAccsessRight('settings');
if(!ACL::isAllowed($accessMode,'V')) die('no access right!');
?>
<div class="col-lg-12">

    @include('partials.messages')

    <div class="panel panel-primary">
        <div class="panel-heading">

                <div class="pull-left">
                    <h5><strong><i class="fa fa-list"></i> <strong>{!!trans('messages.pdf-queue')!!}</strong></strong></h5>
                </div>

            <div class="clearfix"></div>
        </div>
        <!-- /.panel-heading -->
        <div class="panel-body">
            <div class="table-responsive">
                <form>
                <table aria-label="Detailed Report Data Table" id="list" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                    @if(count($getList)<=0)
                        No Information is found!
                    @else
                    <thead>
                        <tr>
                            <th>Tracking No.</th>
                            <th>PDF type</th>
                            <th>Secret Key</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($getList as $row)
                        <?php
                        $encryptedPdfId = Encryption::encodeId($row->id) ;
                        ?>
                    <tr>
                        <td>{{$row->tracking_no}}</td>
                        <td><input class='no-border-input' type="text" name="pdf_type_{{$encryptedPdfId}}" value="{{$row->pdf_type}}" size="{{strlen($row->pdf_type)}}"></td>
                        <td><input class='no-border-input' type="text" name="secret_key_{{$encryptedPdfId}}" value="{{$row->secret_key}}" size="{{strlen($row->secret_key)}}"></td>
                        <td><input class='no-border-input' type="text" name="status_{{$encryptedPdfId}}" value="{{$row->status}}" size="{{strlen($row->status)}}"></td>
                        <td>
                            @if(ACL::getAccsessRight('settings','E'))
                                <button type="button" value="{{$encryptedPdfId}}" class="btn btn-xs btn-primary update">
                                    <i class="fa fa-folder-open-o"></i> Update
                                </button>

                            @endif
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                    @endif
                </table>
                </form>
            </div><!-- /.table-responsive -->
        </div><!-- /.panel-body -->
    </div><!-- /.panel -->
</div><!-- /.col-lg-12 -->

@endsection

@section('footer-script')
@include('partials.datatable-scripts')

<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
<script>
    $(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });

        $('#list').DataTable({
           responsive:false
        });
        $('.update').on('click',function(){
            var self = $(this);
            var pdf_id = self.val();
            var pdf_type = "pdf_type_"+ pdf_id;
            var secret_key = "secret_key_"+ pdf_id;
            var status = "status_"+ pdf_id;
            var form_data = new FormData();
            form_data.append('pdf_id', pdf_id);
            form_data.append('pdf_type', $('input[name="'+pdf_type+'"]').val());
            form_data.append('secret_key', $('input[name="'+secret_key+'"]').val());
            form_data.append('status', $('input[name="'+status+'"]').val());
            form_data.append('_token', $('input[name="_token"]').val());
            console.log(form_data);
            $.ajax({
                type:'post',
                url:"{{url('settings/pdf-queue-update')}}",
                dataType: 'text', // what to expect back from the PHP script, if anything
                cache: false,
                contentType: false,
                processData: false,
                data:form_data,
                success:function(response){
                    alert(response);
                    location.reload();
                }
                });
        });

    });



</script>
@endsection
