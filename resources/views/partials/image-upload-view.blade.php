<div class="{{$firstDiv}}">
    {!! Form::label($company_logo,$labelData, ['class'=>'text-left','style'=>'']) !!}
    <input type="file" class="form-control input-sm {{!empty($exitingData) ? '' : 'required'}}"
           name="{{$company_logo}}_input"
           id="{{$company_logo}}"
           onchange="{{$functionName}}(this, '{{$company_logo}}_preview', '{{$company_logo}}_base64')"
           size="300x300"/>
    <span class="text-success"
          style="font-size: 9px; font-weight: bold; display: block;">[File Format: *.jpg/ .jpeg/ .png | Width 300PX, Height 300PX]</span>
    {!! $errors->first('company_logo','<span class="help-block">:message</span>') !!}
</div>
<div class="{{$secoendDiv}}">
    <label class="center-block image-upload" for="company_logo">
        <figure>
            <img src="{{ (!empty($exitingData)? url('uploads/'.$exitingData) : url('assets/images/no-image.png')) }}" alt="no-image.png"
                 class="img-responsive img-thumbnail"
                 id="{{$company_logo}}_preview"/>
        </figure>
        <input type="hidden" id="{{$company_logo}}_base64"
               name="{{$company_logo}}_base64"/>

        @if(!empty($exitingData))
            <input type="hidden" id="applicant_pic_hidden" name="{{$exitinginput}}"
                   value="{{$exitingData}}"/>
        @endif
    </label>
</div>