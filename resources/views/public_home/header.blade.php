<header>
    <div class="container">
        <div class="row" style="padding: 10px 0;">
            <div class="col-md-6 col-sm-12 col-xs-12">
                <div class="logo_area">
                    <div style="margin: 6px 0; width: 200px; float: left;">
                        <a href="{{ url('articles/bida') }}">
                            {{-- <img src="{{ asset(Session::get('logo')) }}" alt="BIDA Logo" style="max-width: 200px;"> --}}
                            <img src="{{ asset(Session::get('logo')) }}" alt="BIDA Logo" style="max-width: 200px;" onerror="this.onerror=null;this.src='{{asset('/assets/images/photo_default.png')}}'; this.style.width='200px';">

                        </a>
                    </div>

                    {{-- <div style="text-align: right;">
                        <a href="https://mujib100.gov.bd" target="_blank" rel="noopener">
                            <img class="mujib_borsho" src="https://oss.net.bd/sheikmujib_s.png" alt="Mujib Borsho">
                        </a>
                    </div> --}}
                </div>
            </div>
            <div class="col-md-6 hidden-sm hidden-xs">
                <div class="top-button" style="float: right;">
                    <a type="button" href="{{ $redirect_url }}" class="btn" style="background-color: #006749 !important; color: #fff;border-radius: 30px !important; min-width: 100px; padding: 4px 12px !important;">
                        <i class="fa fa-sign-in-alt"> </i>
                        login
                    </a>
                    &nbsp;&nbsp;
                    <a href="{{url('articles/support')}}" onclick="viewPageCount('needHelp')" style="color: #000; text-decoration: none; font-size: 12px" class="smoothScroll">
                        <i class="fa fa-question-circle" aria-hidden="true"></i>
                        Need Help
                    </a>
                </div>
                <div class="clearfix"></div>
                <div class="title_area" style="padding-top: 4px;">
                    <span class="less-padding" style="color: #003399; font-size: 16px">
                        {{Session::get('title')}}
{{--                        <b style="color:red;">{{env('project_mode')}}</b>--}}
                    </span><br>
                    <span style="color: #000080">{{Session::get('manage_by')}}</span>
                </div>
            </div>
        </div>
    </div>
</header>