@extends('layouts.front')

@section('content')

    <div id="html">
        <style>
            #html, #body{margin:0;padding:0;border:0;font:inherit;vertical-align:baseline;}
            #img{max-width:100%;}
            #body{
                background: url(../images/bg1.png);
                font-family: "Century Gothic",Arial, Helvetica, sans-serif;
            }
            .content-404 p{
                margin: 18px 0px 45px 0px;
            }
            .content-404 p{
                font-family: "Century Gothic", sans-serif;
                font-size:2em;
                color:#666;
                text-align:center;
            }
            .content-404 p span{
                color:#e54040;
            }
            .content-404{
                text-align:center;
                padding:115px 0px 0px 0px;
            }

            /*------responive-design--------*/
            @media screen and (max-width: 1366px)	{
                .content-404 {
                    padding: 58px 0px 0px 0px;
                }
            }
            @media screen and (max-width:1280px)	{
                .content-404 {
                    padding: 58px 0px 0px 0px;
                }
            }
            @media screen and (max-width:1024px)	{
                .content-404 {
                    padding: 58px 0px 0px 0px;
                }
                .content-404 p {
                    font-size: 1.5em;
                }
            }
            @media screen and (max-width:640px)	{
                .content-404 {
                    padding: 58px 0px 0px 0px;
                }
                .content-404 p {
                    font-size: 1.3em;
                }
            }
            @media screen and (max-width:460px)	{
                .content-404 {
                    padding:20px 0px 0px 0px;
                    margin:0px 12px;
                }
                .content-404 p {
                    font-size:0.9em;
                }
            }
            @media screen and (max-width:320px)	{
                .content-404 {
                    padding:30px 0px 0px 0px;
                    margin:0px 12px;
                }
                .content-404 p {
                    margin: 18px 0px 22px 0px;
                }
            }
        </style>

        <div id="body">
            <div class="wrap">
                <div class="content-404">
                    <img src="{{url('assets/images/coming_soon.png') }}" title="error" id="img" alt="coming_soon.png"/>
{{--                    <p><br/>{{ $exception->getMessage() }}</p>--}}
                    <p><br/>Page not found [{{ $status }}]</p>
                    {{--<p> <span>Sorry!</span> The page you have requested is not available.</p>--}}
                </div> <!--End-Cotent------>
            </div><!--End-wrap--->
        </div><!--body-->
    </div><!--html-->
@endsection