@if(Auth::user()->user_type == '5x505')
    <?php
    $update_data = CommonFunction::checkBusinessClassBackdatedData(102); // 102 = service type
    ?>
    @if(count($update_data) > 0)
        <?php
        // flag for modal open
        Session::put('update_business_class_modal', 'backdated');
        Session::put('update_business_class_app_url', url('process/bida-registration/view-app/' . Encryption::encodeId($update_data->ref_id) . '/' . Encryption::encodeId(102)));
        ?>
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-danger text-center" role="alert">
                    <a href="{{ Session::get('update_business_class_app_url') }}">
                        A vital change is immediately needed in your BIDA Registration information based on the
                        decision from legal authority.
                        <br>
                        Please click here to see your application.
                    </a>
                    <br>
                    <a href="{{ url('articles/support') }}">Contact with support help desk.</a>
                </div>
            </div>
        </div>
    @endif
@endif