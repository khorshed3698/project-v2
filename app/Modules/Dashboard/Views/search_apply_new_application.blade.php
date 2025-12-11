@section('style')
    <link rel="stylesheet" href="{{ asset("assets/plugins/select2.min.css") }}">
    <style>

       #search-heading{
        text-align: center;
        color: #004c99;
        font-weight: bold;
        font-size: 22px;
       }

       #search-bar{
        border-radius: 25px;
        height: 3px;
        padding: 3px;

        background-color: #9966ff;
  
 
 
       }
         
        /* Select a service search */
        .searchInput, .select2 {
            border:1px solid #dcdcdc;
            /* border-radius: 7px 0 0 7px; */
            height: 40px !important;
            border-left: none;
        }

        .search-application:hover{
            box-shadow: 1px 1px 8px 1px #dcdcdc;
            border-radius: 30px;
        }
        .search-btn {
            background-color: white;
            border:1px solid #dcdcdc;
            border-radius: 30px 0 0 30px;
            color: rgba(0,0,0,0.8);
            height: 40px;
            width: 40px;
            border-right: none;
        }
        .applyBtn {
            height: 40px;
            border-radius: 20px;
            width: 90px;
            font-size: 18px;
            line-height: 25px;
            color:aliceblue;
            font-weight: bold;
            background-color: #9966ff;
            
            
        }
        .select2-container--default .select2-selection--single {
            background-color: #fff;
            border: 0 solid rgba(0,0,0,0);
            border-radius: 7px 0 0 7px;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            top: 8px;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 39px;
            font-size: 16px;
        }
        .select2-selection__rendered {
            height: 39px;
        }
        .select2-search__field{
            box-shadow: 0px 0px 8px 0px #918e8e;
           
                        
            
        }
        .select2-container .select2-dropdown .select2-results ul {
            background: #fff;
            border: 1px solid #dcdcdc;
            box-shadow: 1px 1px 8px 1px #918e8e;
        }
        .select2-container .select2-dropdown .select2-search input {
            outline: none !important;
            border: 1px solid #dcdcdc !important;
            border-bottom: none !important;
            padding: 4px 6px !important;
            /* margin-top: 1px; */
        }
        .select2-container .select2-dropdown .select2-search {
            padding: 0;
        }

        .select2.select2-container .select2-selection {
            -webkit-border-radius: 3px;
            -moz-border-radius: 3px;
            border-radius: 3px;            
            height: 39px;
            margin-bottom: 15px;
            outline: none !important;
            transition: all .15s ease-in-out;
            
        }


        .select2-selection__rendered{
            max-width: 400px;
        }

        .search-content
        {
            display: flex;
            justify-content: center;
            padding: 0px 30px;
        }
        .search-application 
        {
            width: 80%;
            padding-left: 30px;
        }
        .select2
        {
            width: 100%!important;
            margin-bottom: 0.9px;
            
        }
        
    </style>
@endsection
<div class="row">
    <div class="col-md-12">
        <p id="search-heading" class="dash-box-heading">
           <strong> Select a service </strong> 
        </p>
    </div>
</div>

<div class="row">
    <div class=" col-md-12 col-sm-12 col-xs-12 search-content">
        
    <div id="search-bar" class="input-group search-application" style="margin-bottom: 25px;">
            <span class="input-group-btn">
                <button id="global-search-submit" class="btn search-btn" type="submit"
                    style="">
                    <i class="fas fa-search" style="margin-left: 8px;"></i>
                </button>
            </span>
            
            <select name="process_type_id" class="form-control required searchInput" id="process_type_id"
                        data-placeholder="Select a service" required onchange="serviceApply(this.value)">
                    <option value=""></option>
                    @foreach($all_services as $service)
                        <option value="{{ url('process/'.$service->form_url.'/add/'.Encryption::encodeId($service->id)) }}">
                            {{ $service->service_name }}
                        </option>
                    @endforeach
            </select>

            <div class="input-group-btn">
                <a class="btn applyBtn" type="button" role="button" id="applyBtn">Apply</a>
            </div>
        </div>
    </div>
</div>
@section('footer-script')
    <script src="{{ asset("assets/plugins/select2.min.js") }}"></script>
    <script>
        $(function () {
            $("#process_type_id").select2();
        });
        function serviceApply($url) {
            $("#applyBtn").attr('href', $url);
        }
    </script>
@endsection