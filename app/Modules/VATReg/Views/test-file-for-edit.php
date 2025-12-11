<div class="panel panel-primary">
    <div class="panel-heading"><strong>D. CONTACT INFORMATION</strong></div>
    <div class="panel-body">

        <div class="form-group" style="">
            <div class="row">
                <div class="col-md-6">
                    {!! Form::label('factory_address','D1. Factory/ Business Operations Address',
                    ['class'=>'col-md-5 required-star']) !!}
                    <div class="col-md-7 {{$errors->has('factory_address') ? 'has-error': ''}}">
                        {!! Form::textarea('factory_address', $appData->factory_address,
                        ['data-rule-maxlength'=>'240', 'class' => 'form-control bigInputField input-md
                        maxTextCountDown',
                        'size'=>'5x2','data-charcount-maxlength'=>'200','id'=>'factory_address']) !!}
                        {!! $errors->first('factory_address','<span class="help-block">:message</span>') !!}
                    </div>
                </div>

                <div class="col-md-6 {{$errors->has('district') ? 'has-error': ''}}">
                    {!! Form::label('district','D2. District',['class'=>'col-md-5 text-left']) !!}
                    <div class="col-md-7">
                        {!! Form::select('district',['1@Dhaka'=>'Dhaka',
                        '2@Rajshahi'=>'Rajshahi'],$appData->district,['class' => 'form-control
                        input-md','id'=>'district']) !!}

                        {{--{!!Form::select('district', [], '', ['placeholder' => 'Select One', --}}
                        {{--'class' => 'form-control input-md', 'id'=>'district']) !!}--}}
                        {!! $errors->first('district','<span class="help-block">:message</span>') !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group" style="">
            <div class="row">
                <div class="col-md-6 {{$errors->has('police_station') ? 'has-error': ''}}">
                    {!! Form::label('police_station','D3. Police Station',['class'=>'col-md-5 text-left'])
                    !!}
                    <div class="col-md-7">
                        {!! Form::select('police_station',['1@Dhaka'=>'Dhaka',
                        '2@Rajshahi'=>'Rajshahi'],$appData->police_station,['class' => 'form-control
                        input-md','id'=>'police_station']) !!}

                        {!! $errors->first('police_station','<span class="help-block">:message</span>') !!}
                    </div>
                </div>

                <div class="col-md-6 {{$errors->has('post_code') ? 'has-error': ''}}">
                    {!! Form::label('post_code','D4. Postal Code',['class'=>'col-md-5 text-left']) !!}
                    <div class="col-md-7">
                        {!! Form::select('post_code',['1@1216'=>'1216',
                        '2@5900'=>'5900'],$appData->post_code,['class' => 'form-control
                        input-md','id'=>'postCode']) !!}
                        {!! $errors->first('post_code','<span class="help-block">:message</span>') !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group" style="">
            <div class="row">
                <div class="col-md-6">
                    {!! Form::label('land_telephone','D5. Land Telephone Number ', ['class'=>'col-md-5
                    required-star']) !!}
                    <div class="col-md-7 {{$errors->has('land_telephone') ? 'has-error': ''}}">
                        {!! Form::text('land_telephone', $appData->land_telephone,['class' => 'form-control
                        input-sm required onlyNumber']) !!}
                        {!! $errors->first('land_telephone','<span class="help-block">:message</span>') !!}
                    </div>
                </div>

                <div class="col-md-6">
                    {!! Form::label('mobile_telephone','D6. Mobile Telephone Number', ['class'=>'col-md-5
                    required-star onlyNumber']) !!}
                    <div class="col-md-7 {{$errors->has('mobile_telephone') ? 'has-error': ''}}">
                        {!! Form::text('mobile_telephone', $appData->mobile_telephone,['class' =>
                        'form-control input-sm required']) !!}
                        {!! $errors->first('mobile_telephone','<span class="help-block">:message</span>')
                        !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group" style="">
            <div class="row">
                <div class="col-md-6">
                    {!! Form::label('email','D7. e-Mail', ['class'=>'col-md-5 required-star']) !!}
                    <div class="col-md-7 {{$errors->has('email') ? 'has-error': ''}}">
                        {!! Form::text('email', $appData->email,['class' => 'form-control input-sm required
                        email','placeholder'=>'example@gmail.com']) !!}
                        {!! $errors->first('email','<span class="help-block">:message</span>') !!}
                    </div>
                </div>

                <div class="col-md-6">
                    {!! Form::label('fax','D8. Fax Number', ['class'=>'col-md-5 required-star']) !!}
                    <div class="col-md-7 {{$errors->has('fax') ? 'has-error': ''}}">
                        {!! Form::text('fax', $appData->fax,['class' => 'form-control input-sm required
                        onlyNumber']) !!}
                        {!! $errors->first('fax','<span class="help-block">:message</span>') !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group" style="">
            <div class="row">
                <div class="col-md-6">
                    {!! Form::label('web_address','D9. Web Address', ['class'=>'col-md-5 required-star'])
                    !!}
                    <div class="col-md-7 {{$errors->has('web_address') ? 'has-error': ''}}">
                        {!! Form::text('web_address', $appData->web_address,['class' => 'form-control
                        input-sm required url','placeholder'=>'www.example.com']) !!}
                        {!! $errors->first('web_address','<span class="help-block">:message</span>') !!}
                    </div>
                </div>

                <div class="col-md-6">
                    {!! Form::label('headquarter_address','D10. Headquarter Address', ['class'=>'col-md-5
                    required-star']) !!}
                    <div class="col-md-7 {{$errors->has('headquarter_address') ? 'has-error': ''}}">
                        {!! Form::textarea('headquarter_address', $appData->headquarter_address,
                        ['data-rule-maxlength'=>'240', 'class' => 'form-control bigInputField input-md
                        maxTextCountDown',
                        'size'=>'5x2','data-charcount-maxlength'=>'200','id'=>'headquarter_address']) !!}
                        {!! $errors->first('headquarter_address','<span class="help-block">:message</span>')
                        !!}
                        {!! Form::checkbox('same_as_factory',1,(isset($appData->same_as_factory) &&
                        $appData->same_as_factory == 1 ? true : false), array('id'=>'same_as_factory',
                        'onclick'=>'samaAsFactoryFunction()','class'=>'required')) !!} Same as
                        "Factory/Business Operations Address"
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group" style="">
            <div class="row">
                <div class="col-md-6">
                    {!! Form::label('headquarter_address_outside','D11. Headquarter Address outside of
                    Bangladesh', ['class'=>'col-md-5 required-star']) !!}
                    <div class="col-md-7 {{$errors->has('headquarter_address_outside') ? 'has-error': ''}}">
                        {!! Form::textarea('headquarter_address_outside',
                        $appData->headquarter_address_outside, ['data-rule-maxlength'=>'240', 'class' =>
                        'form-control bigInputField input-md maxTextCountDown',
                        'size'=>'5x2','data-charcount-maxlength'=>'200']) !!}
                        {!! $errors->first('headquarter_address_outside','<span
                                class="help-block">:message</span>') !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="clearfix"></div>
    </div>
    <!--/panel-body-->
</div>
<!--/panel-->

<div class="panel panel-primary">
    @include('VATReg::branch_information_edit')
    <!--/panel-body-->
</div>
<!--/panel-->

<div class="panel panel-primary">
    <div class="panel-heading"><strong>F. MAJOR AREA OF ECONOMIC ACTIVITY</strong></div>
    <div class="panel-body">
        <div class="form-group" style="">
            <div class="row">
                <div class="col-md-12">
                    <label class="col-md-4">{!!
                        Form::checkbox('manufacturing',1,(isset($appData->manufacturing) == 1) ? true :
                        false, ['id'=>'manufacturing','onclick'=>'manufacturingFunction()']) !!} F1.
                        Manufacturing</label>
                    <label class="col-md-4">{!! Form::checkbox('service',2,(isset($appData->service) == 2) ?
                        true : false, ['id'=>'services','onclick'=>'ServicesFunction()']) !!} F2.
                        Services</label>
                    <label class="col-md-4">{!! Form::checkbox('retail',3,(isset($appData->retail) == 3) ?
                        true : false) !!} F3. Retail/Wholesale Trading</label>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <label class="col-md-4">{!! Form::checkbox('imports',4,(isset($appData->imports) &&
                        $appData->imports == 4) ?
                        true : false,['id'=>'imports','onclick'=>'importsFunction()']) !!} F4.
                        Imports</label>
                    <div id="imports_div"
                         style="{{(isset($appData->imports) && $appData->imports == 4) ? '' : 'display:none;'}}">
                        <div class="col-md-4">
                            {!! Form::label('support_document_imports','Supporting Document Number',
                            ['class'=>'col-md-5 required-star']) !!}
                            <div
                                    class="col-md-7 {{$errors->has('support_document_imports') ? 'has-error': ''}}">
                                {!! Form::text('support_document_imports',
                                (isset($appData->support_document_imports)) ?
                                $appData->support_document_imports:'',['class' => 'form-control
                                input-sm
                                required economic_imports']) !!}
                                {!! $errors->first('support_document_imports','<span
                                        class="help-block">:message</span>') !!}
                            </div>
                        </div>
                        <div class="col-md-4">
                            {!! Form::label('issue_date_imports','Issue Date', ['class'=>'col-md-5
                            required-star']) !!}
                            <div class="col-md-7 {{$errors->has('issue_date_imports') ? 'has-error': ''}}">
                                {!! Form::text('issue_date_imports', (isset($appData->issue_date_imports)) ?
                                $appData->issue_date_imports :'',['class'
                                => 'form-control input-sm required economic_imports']) !!}
                                {!! $errors->first('issue_date_imports','<span
                                        class="help-block">:message</span>') !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <label class="col-md-4">{!! Form::checkbox('exports',5,(isset($appData->exports) &&
                        $appData->exports == 5) ?
                        true : false, ['id'=>'exports','onclick'=>'exportsFunction()']) !!} F5.
                        Exports</label>
                    <div id="exports_div"
                         style="{{(isset($appData->exports) && $appData->exports == 5) ? '' : 'display:none;'}}">
                        <div class="col-md-4">
                            {!! Form::label('support_document_exports','Supporting Document Number',
                            ['class'=>'col-md-5 required-star']) !!}
                            <div
                                    class="col-md-7 {{$errors->has('support_document_exports') ? 'has-error': ''}}">
                                {!!
                                Form::text('support_document_exports',(isset($appData->support_document_exports))
                                ? $appData->support_document_exports : '',['class'
                                => 'form-control input-sm required economic_exports']) !!}
                                {!! $errors->first('support_document_exports','<span
                                        class="help-block">:message</span>') !!}
                            </div>
                        </div>
                        <div class="col-md-4">
                            {!! Form::label('issue_date_exports','Issue Date', ['class'=>'col-md-5
                            required-star']) !!}
                            <div class="col-md-7 {{$errors->has('issue_date_exports') ? 'has-error': ''}}">
                                {!! Form::text('issue_date_exports',
                                (isset($appData->issue_date_exports)) ?
                                $appData->issue_date_exports:'',['class'
                                => 'form-control input-sm required economic_exports']) !!}
                                {!! $errors->first('issue_date_exports','<span
                                        class="help-block">:message</span>') !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <label class="col-md-4">{!! Form::checkbox('other',6,(isset($appData->other) &&
                        $appData->other == 6 ? true : false), ['id'=>'others','onclick'=>'otherFunction()'])
                        !!} F6.
                        Other</label>
                    <div class="col-md-8" id="please_specify_data"
                         style="{{isset($appData->other) && $appData->other !== null ? '': 'display:none;'}}">
                        {!! Form::label('please_specify_f','Please Specify', ['class'=>'col-md-5
                        required-star']) !!}
                        <div class="col-md-7 {{$errors->has('please_specify_f') ? 'has-error': ''}}">
                            {!! Form::text('please_specify_f',(isset($appData->please_specify_f)) ?
                            $appData->please_specify_f:'' ,['class' =>
                            'form-control input-sm required please_specify_f']) !!}
                            {!! $errors->first('please_specify_f','<span
                                    class="help-block">:message</span>') !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
    <!--/panel-body-->
</div>
<!--/panel-->

<div class="panel panel-primary">
    <div class="panel-heading"><strong>G. AREAS OF MANUFACTURING</strong></div>
    <div class="panel-body">
        <div class="form-group" style="">
            <div class="row">
                <div class="col-md-12" id="toptext">
                    <p style="margin-left: 25px;">This section will be available only when "F1.
                        Manufacturing" is selected.</p>
                </div>
                <?php (isset($appData->economic_area)) ? $appData->economic_area : $appData->economic_area = []; ?>
                <div id="manufacturing_area"
                     style="{{(isset($appData->manufacturing)) ? '' : 'display:none;'}}">
                    <div class="col-md-12">
                        <label class="col-md-4">{!!
                            Form::checkbox('economic_area[]',1,
                            in_array(1,$appData->economic_area) ? true:false,['class'=>'economic_area']) !!}
                            G1.
                            Agriculture/Forestry/Fisheries</label>
                        <label class="col-md-4">{!!
                            Form::checkbox('economic_area[]',2,in_array(2,$appData->economic_area) ?
                            true:false,['class'=>'economic_area']) !!} G2.
                            Edible oil </label>
                        <label class="col-md-4">{!!
                            Form::checkbox('economic_area[]',3,in_array(3,$appData->economic_area) ?
                            true:false,['class'=>'economic_area']) !!} G3. Food
                            & Beverage</label>
                        <label class="col-md-4">{!!
                            Form::checkbox('economic_area[]',4,in_array(4,$appData->economic_area) ?
                            true:false,['class'=>'economic_area']) !!} G4.
                            Tobacco</label>
                        <label class="col-md-4">{!!
                            Form::checkbox('economic_area[]',5,in_array(5,$appData->economic_area) ?
                            true:false,['class'=>'economic_area']) !!} G5. Ores
                            & Minerals</label>
                        <label class="col-md-4">{!!
                            Form::checkbox('economic_area[]',6,in_array(6,$appData->economic_area) ?
                            true:false,['class'=>'economic_area']) !!} G6.
                            Chemical Products & Pharmaceutical Products</label>
                        <label class="col-md-4">{!!
                            Form::checkbox('economic_area[]',7,in_array(1,$appData->economic_area) ?
                            true:false,['class'=>'economic_area']) !!} G7.
                            Plastic & Rubber Products</label>
                        <label class="col-md-4">{!!
                            Form::checkbox('economic_area[]',8,in_array(8,$appData->economic_area) ?
                            true:false,['class'=>'economic_area']) !!} G8.
                            Leather, Leather Products & Footwear</label>
                        <label class="col-md-4">{!!
                            Form::checkbox('economic_area[]',9,in_array(9,$appData->economic_area) ?
                            true:false,['class'=>'economic_area']) !!} G9.
                            Wood, Wooden Product & Furniture</label>
                        <label class="col-md-4">{!!
                            Form::checkbox('economic_area[]',10,in_array(10,$appData->economic_area) ?
                            true:false,['class'=>'economic_area']) !!} G10.
                            Paper & Paper Products</label>
                        <label class="col-md-4">{!!
                            Form::checkbox('economic_area[]',11,in_array(11,$appData->economic_area) ?
                            true:false,['class'=>'economic_area']) !!} G11.
                            Textiles & Apparels</label>
                        <label class="col-md-4">{!!
                            Form::checkbox('economic_area[]',12,in_array(12,$appData->economic_area) ?
                            true:false,['class'=>'economic_area']) !!} G12.
                            Glass, Ceramic & Stone Articles</label>
                        <label class="col-md-4">{!!
                            Form::checkbox('economic_area[]',13,in_array(13,$appData->economic_area) ?
                            true:false,['class'=>'economic_area']) !!} G13.
                            Jewelry</label>
                        <label class="col-md-4">{!!
                            Form::checkbox('economic_area[]',14,in_array(14,$appData->economic_area) ?
                            true:false,['class'=>'economic_area']) !!} G14.
                            Iron, Steel & Other Metal Products</label>
                        <label class="col-md-4">{!!
                            Form::checkbox('economic_area[]',15,in_array(15,$appData->economic_area) ?
                            true:false,['class'=>'economic_area']) !!} G15.
                            Machinery & Equipment</label>
                        <label class="col-md-4">{!!
                            Form::checkbox('economic_area[]',16,in_array(16,$appData->economic_area) ?
                            true:false,['class'=>'economic_area']) !!} G16.
                            Electrical & Electronics</label>
                        <label class="col-md-4">{!!
                            Form::checkbox('economic_area[]',17,in_array(17,$appData->economic_area) ?
                            true:false,['class'=>'economic_area']) !!} G17.
                            Automobiles & Locomotives</label>
                        <label class="col-md-4">{!!
                            Form::checkbox('economic_area[]',18,in_array(18,$appData->economic_area) ?
                            true:false,['class'=>'economic_area']) !!} G18.
                            Cycles, Motorcycles & Baby Carriages</label>
                        <label class="col-md-4">{!!
                            Form::checkbox('economic_area[]',19,in_array(19,$appData->economic_area) ?
                            true:false,['class'=>'economic_area']) !!} G19.
                            Watercraft</label>
                        <label class="col-md-4">{!!
                            Form::checkbox('economic_area[]',20,in_array(20,$appData->economic_area) ?
                            true:false,['class'=>'economic_area']) !!} G20.
                            Aviation</label>
                        <label class="col-md-4">{!!
                            Form::checkbox('economic_area[]',21,in_array(21,$appData->economic_area) ?
                            true:false,['class'=>'economic_area']) !!} G21.
                            Optical Instruments (e.g. spectacles, camera)</label>
                    </div>
                    <div class="col-md-12">
                        <label class="col-md-4">{!!
                            Form::checkbox('economic_area[]',22,in_array(22,$appData->economic_area) ?
                            true:false,
                            ['id'=>'others_G','class'=>'economic_area','onclick'=>'otherGFunction()']) !!}
                            G22.
                            Other</label>


                        <div class="col-md-8" id="please_specify_G" style="{{in_array(22,$appData->economic_area) ?
                                        '':'display:none'}}">
                            {!! Form::label('please_specify_g','Please Specify', ['class'=>'col-md-5
                            required-star']) !!}
                            <div class="col-md-7 {{$errors->has('please_specify_g') ? 'has-error': ''}}">
                                {!! Form::text('please_specify_g',
                                (isset($appData->please_specify_g) ? $appData->please_specify_g :
                                ''),['class'
                                =>
                                'form-control input-sm required please_specify_g']) !!}
                                {!! $errors->first('please_specify_g','<span
                                        class="help-block">:message</span>') !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
    <!--/panel-body-->
</div>
<!--/panel-->

<div class="panel panel-primary">
    <div class="panel-heading"><strong>H. AREA OF SERVICE </strong></div>
    <div class="panel-body">
        <div class="form-group" style="">
            <div class="row">
                <div class="col-md-12" id="toptext_H">
                    <p style="margin-left: 25px;">This section will be available only when "F2. Services" is
                        selected.</p>
                </div>
                <div id="services_div"
                     style="{{(isset($appData->service) && $appData->service == 2) ? '' : 'display:none;'}}">
                    <div class="col-md-12">
                        <?php (isset($appData->area_service)) ? $appData->area_service : $appData->area_service = []; ?>
                        <label class="col-md-4">{!!
                            Form::checkbox('area_service[]',1,
                            in_array(1,$appData->area_service) ? true:false
                            ,['class'=>'area_service'])!!} H1.
                            Construction</label>
                        <label class="col-md-4">{!!
                            Form::checkbox('area_service[]',2,in_array(2,$appData->area_service) ?
                            true:false,['class'=>'area_service'])!!} H2.
                            Trading, including</label>
                        <label class="col-md-4">{!!
                            Form::checkbox('area_service[]',3,in_array(3,$appData->area_service) ?
                            true:false,['class'=>'area_service'])!!} H3. Real
                            State e-Commerce</label>
                        <label class="col-md-4">{!!
                            Form::checkbox('area_service[]',4,in_array(4,$appData->area_service)?
                            true:false,['class'=>'area_service'])!!} H4.
                            Transport</label>
                        <label class="col-md-4">{!!
                            Form::checkbox('area_service[]',5,in_array(5,$appData->area_service)?
                            true:false,['class'=>'area_service'])!!} H5.
                            Electricity/Gas/Water Supply</label>
                        <label class="col-md-4">{!!
                            Form::checkbox('area_service[]',6,in_array(6,$appData->area_service)?
                            true:false,['class'=>'area_service'])!!} H6.
                            Financial Services</label>
                        <label class="col-md-4">{!!
                            Form::checkbox('area_service[]',7,in_array(7,$appData->area_service)?
                            true:false,['class'=>'area_service'])!!} H7. Hotel
                            & Guest Houses</label>
                        <label class="col-md-4">{!!
                            Form::checkbox('area_service[]',8,in_array(8,$appData->area_service)?
                            true:false,['class'=>'area_service'])!!} H8.
                            Restaurants</label>
                        <label class="col-md-4">{!!
                            Form::checkbox('area_service[]',9,in_array(9,$appData->area_service)?
                            true:false,['class'=>'area_service'])!!} H9. Rental
                            & Leasing Service</label>
                        <label class="col-md-4">{!!
                            Form::checkbox('area_service[]',10,in_array(10,$appData->area_service)?
                            true:false,['class'=>'area_service']) !!} H10.
                            Research & Survey</label>
                        <label class="col-md-4">{!!
                            Form::checkbox('area_service[]',11,in_array(11,$appData->area_service)?
                            true:false,['class'=>'area_service']) !!} H11.
                            Healthcare</label>
                        <label class="col-md-4">{!!
                            Form::checkbox('area_service[]',12,in_array(12,$appData->area_service)?
                            true:false,['class'=>'area_service']) !!} H12.
                            Education & Training</label>
                        <label class="col-md-4">{!!
                            Form::checkbox('area_service[]',13,in_array(13,$appData->area_service)?
                            true:false,['class'=>'area_service']) !!} H13.
                            Telecommunication & Internet</label>
                        <label class="col-md-4">{!!
                            Form::checkbox('area_service[]',14,in_array(14,$appData->area_service)?
                            true:false,['class'=>'area_service']) !!} H14.
                            Software & IT Enabled Service (ITES)</label>
                        <label class="col-md-4">{!!
                            Form::checkbox('area_service[]',15,in_array(15,$appData->area_service)?
                            true:false,['class'=>'area_service']) !!} H15.
                            Sports & Entertainment</label>
                        <label class="col-md-4">{!!
                            Form::checkbox('area_service[]',16,in_array(16,$appData->area_service)?
                            true:false,['class'=>'area_service']) !!} H16.
                            Event Management, Maintenance & Catering</label>
                        <label class="col-md-4">{!!
                            Form::checkbox('area_service[]',17,in_array(17,$appData->area_service)?
                            true:false,['class'=>'area_service']) !!} H17.
                            Workshop & Engineering</label>
                        <label class="col-md-4">{!!
                            Form::checkbox('area_service[]',18,in_array(18,$appData->area_service)?
                            true:false,['class'=>'area_service']) !!} H18.
                            Tour Operator & Travel Agent</label>
                        <label class="col-md-4">{!!
                            Form::checkbox('area_service[]',19,in_array(19,$appData->area_service)?
                            true:false,['class'=>'area_service']) !!} H19.
                            Advertising & Promotion</label>
                        <label class="col-md-4">{!!
                            Form::checkbox('area_service[]',20,in_array(20,$appData->area_service)?
                            true:false,['class'=>'area_service']) !!} H20.
                            Customs Port, Warehousing, Brokerage & Freight Forwarding</label>
                        <label class="col-md-4">{!!
                            Form::checkbox('area_service[]',21,in_array(21,$appData->area_service)?
                            true:false,['class'=>'area_service']) !!} H21.
                            Radio & TV Operations</label>

                    </div>
                    <div class="col-md-12">
                        <label class="col-md-4">{!!
                            Form::checkbox('area_service[]',22,in_array(22,$appData->area_service)?
                            true:false,
                            ['class'=>'area_service','id'=>'others_H','onclick'=>'otherHFunction()']) !!}
                            H23.
                            Other</label>
                        <div class="col-md-8" id="please_specify_H"
                             style="{{in_array(22,$appData->area_service) ?'':'display:none;'}}">
                            {!! Form::label('please_specify_h','Please Specify', ['class'=>'col-md-5
                            required-star']) !!}
                            <div class="col-md-7 {{$errors->has('please_specify_h') ? 'has-error': ''}}">
                                {!! Form::text('please_specify_h', (isset($appData->please_specify_h)) ?
                                $appData->please_specify_h :'',['class' =>
                                'form-control input-sm required pls_spec_h']) !!}
                                {!! $errors->first('please_specify_h','<span
                                        class="help-block">:message</span>') !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
    <!--/panel-body-->
</div>
<!--/panel-->


<div class="panel panel-primary">
    @include('VATReg::business_classification_code_edit')
</div>
<!--/panel-->

<div class="panel panel-primary">
    @include('VATReg::bank_account_edit')
</div>
<!--/panel-->


<div class="panel panel-primary">
    @include('VATReg::owner_of_entity_edit')
</div>
<!--/panel-->


<div class="panel panel-primary">
    <div class="panel-heading"><strong>L. BUSINESS OPERATIONS</strong></div>
    <div class="panel-body">

        <div class="form-group" style="">
            <div class="row">
                <div class="col-md-6">
                    {!! Form::label('taxable_turnover','L1. Taxable Turnover in past 12 Months Period',
                    ['class'=>'col-md-5 required-star']) !!}
                    <div class="col-md-7 {{$errors->has('taxable_turnover') ? 'has-error': ''}}">
                        {!! Form::text('taxable_turnover', $appData->taxable_turnover,['class' =>
                        'form-control input-sm required']) !!}
                        {!! $errors->first('taxable_turnover','<span class="help-block">:message</span>')
                        !!}
                    </div>
                </div>

                <div class="col-md-6">
                    {!! Form::label('projected_turnover','L2. Projected Turnover in next 12 Months Period',
                    ['class'=>'col-md-5 required-star']) !!}
                    <div class="col-md-7 {{$errors->has('projected_turnover') ? 'has-error': ''}}">
                        {!! Form::text('projected_turnover', $appData->projected_turnover,['class' =>
                        'form-control input-sm required']) !!}
                        {!! $errors->first('projected_turnover','<span class="help-block">:message</span>')
                        !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group" style="">
            <div class="row">
                <div class="col-md-6">
                    {!! Form::label('employee_number','L3. Number of Employees', ['class'=>'col-md-5
                    required-star']) !!}
                    <div class="col-md-7 {{$errors->has('employee_number') ? 'has-error': ''}}">
                        {!! Form::text('employee_number', $appData->employee_number,['class' =>
                        'form-control input-sm required onlyNumber']) !!}
                        {!! $errors->first('employee_number','<span class="help-block">:message</span>') !!}
                    </div>
                </div>

                <div class="col-md-6">
                    {!! Form::label('zero_rated_supply','L4. Are you making any Zero Rated Supply?',
                    ['class'=>'col-md-5 required-star']) !!}
                    <div class="col-md-7 {{$errors->has('zero_rated_supply') ? 'has-error': ''}}">
                        <label class="radio-inline">{!! Form::radio('zero_rated_supply','yes',
                            (isset($appData->zero_rated_supply) && $appData->zero_rated_supply == 'yes' ?
                            true : false),
                            ['class'=>'zero_rated_supply', 'id' => 'zero_rated_supply_yes']) !!}
                            Yes</label>
                        <label class="radio-inline">{!! Form::radio('zero_rated_supply', 'no',
                            (isset($appData->zero_rated_supply) && $appData->zero_rated_supply == 'no' ?
                            true :
                            false),['class'=>'zero_rated_supply', 'id' => 'zero_rated_supply_no']) !!}
                            No</label>
                        {!! $errors->first('zero_rated_supply','<span class="help-block">:message</span>')
                        !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group" style="">
            <div class="row">

                <div class="col-md-6">
                    {!! Form::label('vat_extended_supply','L5. Are you making any VAT Exempted Supply?',
                    ['class'=>'col-md-5 required-star']) !!}
                    <div class="col-md-7 {{$errors->has('vat_extended_supply') ? 'has-error': ''}}">
                        <label class="radio-inline">{!! Form::radio('vat_extended_supply','yes',
                            (isset($appData->vat_extended_supply) && $appData->vat_extended_supply == 'yes'
                            ? true : false),
                            ['class'=>'vat_extended_supply', 'id' => 'vat_extended_supply_yes']) !!}
                            Yes</label>
                        <label class="radio-inline">{!! Form::radio('vat_extended_supply', 'no',
                            (isset($appData->vat_extended_supply) && $appData->vat_extended_supply == 'no' ?
                            true : false),
                            ['class'=>'vat_extended_supply', 'id' => 'vat_extended_supply_no']) !!}
                            No</label>
                        {!! $errors->first('vat_extended_supply','<span class="help-block">:message</span>')
                        !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group" style="">
            <div class="row">
                <div id="capital_machinery"
                     style="{{(isset($appData->manufacturing) == 1) || (isset($appData->service) == 2) ? '' : 'display:none;'}}">
                    <div class="col-md-6">
                        {!! Form::label('','L6. Major Capital Machinery', ['class'=>'col-md-5
                        required-star']) !!}
                        <div class="col-md-7">
                            <p id="MajorCapitalData">Please click <a>
                                    <button id="MajorCapital"
                                            type="button">here
                                    </button>
                                </a> to provide Major Capital Machinery
                                Information.
                            </p>
                        </div>
                    </div>
                    @include('VATReg::major_capital_machinery_edit')
                </div>
                <div id="input_output_data"
                     style="{{(isset($appData->manufacturing) == 1) ? '' : 'display:none;'}}">
                    <div class="col-md-6">
                        {!! Form::label('','L7. Input-Output Data', ['class'=>'col-md-5 required-star']) !!}
                        <div class="col-md-7">
                            <p id="InputOutputData">Please click <a>
                                    <button id="InputOutput"
                                            type="button">here
                                    </button>
                                </a> to provide Input-Output Data
                                Information.
                            </p>
                        </div>
                    </div>
                    @include('VATReg::input_output_data_edit')
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
    <!--/panel-body-->
</div>
<!--/panel-->
<div class="panel panel-primary">
    @include('VATReg::authorized_persons_online_edit')
</div>
<!--/panel-->