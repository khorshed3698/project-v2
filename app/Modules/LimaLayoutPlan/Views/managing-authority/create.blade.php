<style>

</style>


{!! Form::open(array('url' => 'client/company-profile/store-verify-director-session', 'method' => 'post', 'class' => 'form-horizontal smart-form', 'id'=>'management-authority-information-form',
        'enctype'=> 'multipart/form-data', 'role' => 'form')) !!}
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title" id="largeModalLabel">Add Management Authority Information</h4>
</div>
<div class="modal-body">

    <div class="form-group">
        <div class="row">
            <div class="col-md-4 {{$errors->has('is_building_owned') ? 'has-error': ''}}">
                    {!! Form::label('','Residency Type',['class'=>'col-md-12 text-left required-star']) !!}
                    <div class="col-md-12">
                        <label class="radio-inline">  {!! Form::radio('residency_type', 'local',false, ['id' => 'residency_type_local']) !!} Local </label>
                        <label class="radio-inline">   {!! Form::radio('residency_type', 'foreigner',false,['id' => 'residency_type_foreigner']) !!} Foreigner </label>
                    </div>
            </div>
            <div class="col-md-4 {{$errors->has('factory_owners_name') ? 'has-error': ''}}">
                {!! Form::label('factory_owners_name', 'Name of the Person',['class'=>'col-md-12 text-left required-star']) !!}
                <div class="col-md-12">
                    {!! Form::text('factory_owners_name', '', ['class' => 'form-control input-md required','placeholder'=>'Name of Managing Authority']) !!}
                    {!! $errors->first('factory_owners_name','<span class="help-block">:message</span>') !!}
                </div>
            </div>
            <div class="col-md-4 {{$errors->has('factory_cc_owner_designation_id') ? 'has-error': ''}}">
                {!! Form::label('factory_cc_owner_designation_id','Designation of the Person',['class'=>'col-md-12 text-left required-star']) !!}
                <div class="col-md-12">
                    {!! Form::select('factory_cc_owner_designation_id', [], '', ['class' =>'form-control input-md required', 'id'=> 'owner_type']) !!}
                    {!! $errors->first('factory_cc_owner_designation_id','<span class="help-block">:message</span>') !!}
                </div>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
            <div class="col-md-4 {{$errors->has('factory_owners_father') ? 'has-error': ''}}">
                {!! Form::label('factory_owners_father', 'Father’s Name',['class'=>'col-md-12 text-left']) !!}
                <div class="col-md-12">
                    {!! Form::text('factory_owners_father', '', ['class' => 'form-control input-md required','placeholder'=>'Father’s Name of Managing Authority']) !!}
                    {!! $errors->first('factory_owners_father','<span class="help-block">:message</span>') !!}
                </div>
            </div>
            <div class="col-md-4 {{$errors->has('factory_owners_mother') ? 'has-error': ''}}">
                {!! Form::label('factory_owners_mother', 'Mother’s Name',['class'=>'col-md-12 text-left']) !!}
                <div class="col-md-12">
                    {!! Form::text('factory_owners_mother', '', ['class' => 'form-control input-md required','placeholder'=>'Mother’s Name of Managing Authority']) !!}
                    {!! $errors->first('factory_owners_mother','<span class="help-block">:message</span>') !!}
                </div>
            </div>
            <div class="col-md-4 {{$errors->has('factory_owners_phone') ? 'has-error': ''}}">
                {!! Form::label('factory_owners_phone', 'Mobile no.',['class'=>'col-md-12 text-left required-star']) !!}
                <div class="col-md-12">
                    {!! Form::text('factory_owners_phone', '', ['class' => 'form-control input-md required','placeholder'=>'Mobile No. of Managing Authority']) !!}
                    {!! $errors->first('factory_owners_phone','<span class="help-block">:message</span>') !!}
                </div>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
            <div class="col-md-12 {{$errors->has('factory_owners_address') ? 'has-error': ''}}">
                {!! Form::label('factory_owners_address', 'Permanent Address',['class'=>'col-md-5 text-left required-star']) !!}
                <div class="col-md-12">
                    {!! Form::textarea('factory_owners_address','',['class'=>'form-control input-md required','id'=>'factory_owners_address', 'placeholder'=>'Permanent Address of Managing Authority','maxlength' => 254, 'rows' => 2, 'cols' => 50]) !!}
                    {!! $errors->first('factory_owners_address','<span class="help-block">:message</span>') !!}
                </div>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
            <div class="col-md-6">
                {!! Form::label('photo','Signature (Image)', ['class'=>'col-md-12']) !!}
                <div class="col-md-12 {{$errors->has('photo') ? 'has-error': ''}}">
                    <input type="file" name="photo" id="photo" class="form-control" onchange="uploadDocument('preview_photo', this.id, 'validate_field_photo',1)"/>
                    {!! $errors->first('photo','<span class="help-block">:message</span>')!!}
                    <span class="text-muted small">[Accepted Image types: jpg, jpeg, gif, png. Max Image size: 100 MB, Max Dimension: w:300px X h:300px]</span>
                    <div id="preview_photo">
                        <input type="hidden" value="" id="validate_field_photo"
                               name="validate_field_photo" class="required">
                    </div>
                </div>
            </div>
            <div class="col-md-6 {{$errors->has('factory_owners_nid') ? 'has-error': ''}}" id="management-authority-nid-div">
                {!! Form::label('factory_owners_nid', 'National ID Number',['class'=>'col-md-12 text-left required-star']) !!}
                <div class="col-md-12">
                    {!! Form::text('factory_owners_nid', '', ['class' => 'form-control input-md required','placeholder'=>'National ID Number of Managing Authority']) !!}
                    {!! $errors->first('factory_owners_nid','<span class="help-block">:message</span>') !!}
                </div>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
            <div class="col-md-6" id="management-authority-passatt-div" style="display: none;">
                {!! Form::label('photo','Passport (Attachment) ', ['class'=>'col-md-12']) !!}
                <div class="col-md-12 {{$errors->has('photo') ? 'has-error': ''}}">
                    <input type="file" name="photo" id="photo" class="form-control">
                    {!! $errors->first('photo','<span class="help-block">:message</span>')!!}
                    <span class="text-muted small">[Accepted file types: jpg, jpeg, gif, png, pdf. Max file size: 100 MB]</span>
                    <div id="preview_photo">
                        <input type="hidden" value="" id="validate_field_photo"
                               name="validate_field_photo" class="required">
                    </div>
                </div>
            </div>
            <div class="col-md-6 {{$errors->has('factory_owners_passport') ? 'has-error': ''}}" id="management-authority-passpot-div" style="display: none;">
                {!! Form::label('factory_owners_passport', 'Passport Number',['class'=>'col-md-12 text-left required-star']) !!}
                <div class="col-md-12">
                    {!! Form::text('factory_owners_passport', '', ['class' => 'form-control input-md','placeholder'=>'Write your passport number']) !!}
                    {!! $errors->first('factory_owners_passport','<span class="help-block">:message</span>') !!}
                </div>
            </div>
        </div>
    </div>
</div><!-- .modal-body -->
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    <button type="button" class="btn btn-primary" onclick="managementAuthorityFormSubmit(this)">Save</button>
</div>
{!! Form::close() !!}

<script type="text/javascript">
    //onchange="uploadDocument('preview_photo', this.id, 'validate_field_photo',1)"
    $(document).ready(function () {
        $("input[name='residency_type']").click(function(e){
            let input = $(this).val();
            if(input == 'local'){
                $('#management-authority-nid-div').show();
                $('#management-authority-passatt-div').hide();
                $('#management-authority-passpot-div').hide();
            }else if(input == 'foreigner'){
                $('#management-authority-nid-div').hide();
                $('#management-authority-passatt-div').show();
                $('#management-authority-passpot-div').show();
            }
        });
        $(function () {
            token = "{{$modal_token}}";
            tokenUrl = '/mutation-land/get-refresh-token';
            $('#owner_type').keydown();
        });
        var apiHeaders = [
            {
                key: "Content-Type",
                value: 'application/json'
            },
            {
                key: "client-id",
                value: "OSS_BIDA"
            },
            {
                key: "agent-id",
                value: "{{ config('stakeholder.agent_id') }}"
            },
        ];
        function callbackResponse(response, [calling_id, selected_value, element_id, element_name]) {
            var option = '<option value="">Select One</option>';
            if (response.responseCode === 200) {
                $.each(response.data, function (key, row) {
                    let id = row[element_id] + '@' + row[element_name];
                    let value = row[element_name];
                    option += '<option value="' + id + '">' + value + '</option>';
                });
            }

            $("#" + calling_id).html(option)
            $("#" + calling_id).next().hide()
        }
        $('#owner_type').on('keydown', function (el) {
            let key = el.which
            if (typeof key !== "undefined") {
                return false
            }
            $(this).after('<span class="loading_data">Loading...</span>');
            let e = $(this);
            let api_url = "{{$modal_service_url}}owner-designations";
            let selected_value = ''; // for callback
            let calling_id = $(this).attr('id');// for callback
            let element_id = "id";//dynamic id for callback
            let element_name = "nameEn";//dynamic name for callback
            let element_calling_id = "";//dynamic name for callback
            let data = '';//Third option to make id
            let options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl};// for lib
            let arrays = [calling_id, selected_value, element_id, element_name, element_calling_id]; // for callback
            apiCallGet(e, options, apiHeaders, callbackResponse, arrays);
        });
    });// end -:- Documnent Ready
</script>