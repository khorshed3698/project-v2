<a href="{{ $redirect_url }}" class="btn btn-danger btn-block btn-lg login-cred-btn"
    style="background-color: #006848; border-color: #006848"><strong><i class="fa fa-chevron-circle-right"></i>
        Login</strong></a>

<a rel="noopener" target="_blank" href="{{ url(config('app.osspid_base_url') . '/user/create') }}"
    class="btn btn-success btn-block btn-lg login-cred-btn"
    style="background-color: #D92923; border-color: #D92923;"><b><i class="fa fa-user-plus"></i> Create OSSPID
        account</b></a>
<a rel="noopener" target="_blank" href="tel: +8809678771353" class="btn btn-success btn-block btn-lg login-cred-btn"
    style="background-color: #353C92; border-color: #353C92;">
    <img src="{{ url('assets/images/need_help/call.png') }}" width="8%" alt="Call Center">
    <b> Call Center No. : +8809678771353</b>
</a>

<div class="box-div box-div-img">
    <div class="text-center">
    </div>


    @if (count($errors))
        <ul class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <li>{!! $error !!}</li>
            @endforeach
        </ul>
    @endif
    {!! session()->has('success')
        ? '<div class="alert alert-success alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>' .
            session('success') .
            '</div>'
        : '' !!}
    {!! session()->has('error')
        ? '<div class="alert alert-danger alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>' .
            session('error') .
            '</div>'
        : '' !!}

    <div class="form-group clearfix">
        <div class="text-center">
            <br />
            <a href="{{ url('articles/support') }}" onclick="viewPageCount('needHelp')" id="needHelp" target="_blank" rel="noopener">
                {!! trans('messages.need_help?') !!}
                <strong>{!! trans('messages.contact_us') !!}</strong>
            </a>
            <div style="margin-top: 5px">
                <a href="{{ url('articles/support') }}">
                    <img style="background: none;" src="{{ url('/assets/images/bida-help-icon.png') }}"
                        alt="BIDA support call">
                </a>
                <a href="{{ url('articles/support') }}">
                    <img style="background: none;" src="{{ url('/assets/images/bida-msg-icon.png') }}"
                        alt="BIDA support message">
                </a>
                <a href="{{ url('articles/support') }}">
                    <img style="background: none;" src="{{ url('/assets/images/bida-messenger-icon.png') }}"
                        alt="BIDA facebook messenger">
                </a>
            </div>
        </div>
    </div>
    <br />
    <div>
        {{-- <div class="">
            <span style="font-size: smaller">{{ trans('messages.power_by') }}</span>
            <br />
            <a href="{{ config('app.managed_by_url') . '/product/ossp-one-stop-service-platform' }}" target="_blank"
                rel="noopener">
                <img style="background: none;" src="{{ url('assets/images/ossp.png') }}"
                    alt="One Stop Service Platform">
            </a>
        </div>

        <div class="pull-right">
            <span style="font-size: smaller">{{ trans('messages.manage_by') }}</span>
            <br />
            <a href="{{ config('app.managed_by_url') }}" target="_blank" rel="noopener">
                <img style="background: none;" src="{{ url('assets/images/business_automation_sm.png') }}"
                    alt="Business Automation Ltd.">
            </a>
        </div> --}}

        {{-- <div>
            <span style="font-size: smaller; margin-left: 15%;">Have Any <strong style="color: #006848;">Questions?</strong></span>
            <br/>
            <div style="margin-left: 29%;">
                <i class="fas fa-headset" style="color: #006848;"></i>
                <strong style="margin-left: 2%;">
                    <span style="color: #D92923;">+880</span><span style="color: #006848;">9678771353</span>
                </strong>
            </div>
        </div> --}}
        <div class="clearfix"></div>
    </div>
</div>

@include('Training::web.training_slider')

<div class="panel  panel-info">
    <div class="panel-heading">
        <p style="margin: 10px 0; font-weight: bold; font-size: 14px;">{!! trans('messages.whats_new') !!}?</p>
    </div>
    <div class="panel-body" style="height: auto; width: 100%">
        <div id="myCarousel1" class="carousel slide carousel-fade" data-ride="carousel" data-interval="15000">
            <ol class="carousel-indicators">
                <?php for($j = 0; $j < count($whatsNew); $j++){
                if($j == '0'){
                ?>
                <li data-target="#myCarousel1" data-slide-to="0" class="active"></li>
                <?php }else{  ?>
                <li data-target="#myCarousel1" data-slide-to="<?php echo $j; ?>"></li>
                <?php } } ?>
            </ol>
            <div class="carousel-inner">
                <?php
                $i = 0;
                ?>
                @foreach ($whatsNew as $whatsData)
                    @if ($i == '0')
                        <div class="item active">
                            <img src="{{ url($whatsData->image) }}" alt="{{ $whatsData->title }}"
                                style="width:350px; height: 200px;"
                                onerror="this.src=`{{ asset('/assets/images/photo_default.png') }}`">
                        </div>
                    @else
                        <div class="item">
                            <img src="{{ url($whatsData->image) }}" alt="{{ $whatsData->title }}"
                                style="width:350px; height: 200px;"
                                onerror="this.src=`{{ asset('/assets/images/photo_default.png') }}`">
                        </div>
                    @endif
                    <?php $i++; ?>
                @endforeach
            </div>
        </div>
    </div>
</div>
