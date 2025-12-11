<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="{{ asset("assets/stylesheets/styles.css") }}"/>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css"
          integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">
</head>
<body>

<section class="content" id="applicationForm">
    <div class="col-md-12">
        <div class="box">
            <div class="box-body">
                <div class="row">
                    <p>
                        We, the several persons, whose names addresses are subscribed bellow are desirous of being
                        formed into a Company in accordance with this <strong>MEMORANDUM OF ASSOCIATION /
                        ARTICLES OF ASSOCIATION</strong> and we respectively agree totake the number of shares
                        in the capital of the Company set opposite to our respective names.
                    </p>

                </div>
                <div id="directorlist">

                    <table id="agreementtable" class="table table-bordered">
                        <thead>
                        <tr>
                            <th width="7%"  style="text-align: center">SL. No.</th>
                            <th  style="text-align: center">Names, Addresses and description of subscribers</th>
                            <th  style="text-align: center">No. of shares taken by each subscriber</th>
                            <th  style="text-align: center">Photo of Subscriber</th>
                            <th  style="text-align: center">Signature of Subscriber</th>
                        </tr>
                        </thead>
                        <tbody>

                        {{--rakibul Hasan--}}
                        <?php $totalshare=0;

                        $sn_count = 1;
                        ?>
                        @if(count($subscribers)>0)
                        @foreach($subscribers as $subscriber)


                            <tr>
                                <td class="text-center" style="vertical-align: middle">{{$sn_count}}</td>

                                <td class="text-left">
                                    Name:@if($subscriber->corporation_body_name !="")
                                        {{$subscriber->corporation_body_name}}
                                             @endif
                                        <br>

                                    Address:@if($subscriber->usual_residential_address !="")
                                        {{$subscriber->usual_residential_address}}{{','.$districts[$subscriber->usual_residential_district_id]}}
                                        @endif
                                    <br>
                                    Father's Name:@if($subscriber->usual_residential_address !="")
                                        {{$subscriber->father_name}}
                                    @endif
                                    <br>

                                    Mother's Name:@if($subscriber->mother_name!="")
                                    {{$subscriber->mother_name}}
                                    @endif
                                    <br>
                                    Date of Birth:
                                    @if($subscriber->dob !="")
                                        {{date("d-M-Y", strtotime($subscriber->dob) )}}
                                    @endif
                                    <br>
                                    E-mail:
                                    @if($subscriber->email !="")
                                        {{$subscriber->email}}
                                    @endif
                                    <br>
                                    Phone:
                                    @if($subscriber->mobile !="")
                                        {{$subscriber->mobile}}
                                    @endif
                                    <br>
                                    Tin:@if($subscriber->tin_no !="")
                                            {{$subscriber->tin_no}}
                                        @endif
                                    <br>
                                    Nationality:@if($subscriber->present_nationality_id !="")
                                        {{$nationality[$subscriber->present_nationality_id]}}
                                    @endif
                                    <br>
                                    National Id:@if($subscriber->national_id_passport_no !="")
                                        {{$subscriber->national_id_passport_no}}
                                                    @endif
                                    <br>
                                </td>
                                <td class="text-center">
                                    {{$subscriber->no_of_subscribed_shares}}
                                    @if($subscriber->no_of_subscribed_shares!=0 && $subscriber->no_of_subscribed_shares!="")
                                        ({{\App\Libraries\CommonFunction::convert_number_to_words($subscriber->no_of_subscribed_shares)}})
                                    @endif
                                    Shares
                                </td>
                                <?php
                                $photopath="";
                                if ($subscriber->subscriber_photo !=""){
                                    $photopath='subscriber_photo/'.$subscriber->subscriber_photo;
                                }
                                ?>
                                <?php /*echo public_path().'/'.$photopath; */?>
                                <td class="text-center" style="vertical-align: middle;">
                                    @if($photopath !="")
                                        @if(file_exists(public_path().'/'.$photopath) )
                                            <img height="100px" width="90px" src="{{$photopath}}">
                                        @endif
                                    @endif
                                </td>

                                <?php
                                    $path="";
                                    if ($subscriber->digital_signature !=""){
                                        $path='rjsc_newreg_digital_signature/'.$subscriber->digital_signature;
                                    }
                                ?>

                                <td class="text-center" style="vertical-align: middle;">
                                    @if($path !="")
                                        @if(file_exists(public_path().'/'.$path) )
                                            <img height="50px" width="90px" src="{{$path}}">
                                        @endif
                                    @endif
                                </td>

                            </tr>
                            <?php $totalshare=$totalshare+$subscriber->no_of_subscribed_shares;
                            $sn_count++;
                            ?>
                        @endforeach
                        @else
                            <tr>
                                <td colspan="5" class="text-center">
                                    NO Data Found
                                </td>
                            </tr>
                        @endif
                        {{--Rakibul End--}}

                        </tbody>

                        <tfoot>
                        <tr>
                            <td></td>
                            <td>Total</td>
                            <td colspan="2" class="text-center">{{$totalshare}}
                                @if($totalshare!=0 && $totalshare!="")
                                    ({{\App\Libraries\CommonFunction::convert_number_to_words($totalshare)}})
                                @endif
                                Shares</td>
                            <td></td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

                    <div class="col-md-10" style="margin-top: 50px;">

                        <table width="100%">
                            <thead>
                                <tr>
                                    <th height="100px" width="50%">Witness1:</th>
                                    <th height="100px" width="50%">Witness2:</th>
                                </tr>
                            </thead>
                            <tbody>
                            @if(count($witnessDataFiled)==2)

                            @foreach($witnessDataFiled as $value)
                                @endforeach
                                <tr>
                                    <td>Name:{{$witnessDataFiled[0]['name']}}</td>
                                    <td>Name:{{$witnessDataFiled[1]['name']}}</td>
                                </tr>
                                <tr>
                                    <td>Address:{{$witnessDataFiled[0]['address']}}</td>
                                    <td>Address:{{$witnessDataFiled[1]['address']}}</td>
                                </tr>
                                <tr>
                                    <td>Phone:{{$witnessDataFiled[0]['phone']}}</td>
                                    <td>Phone:{{$witnessDataFiled[1]['phone']}}</td>
                                </tr>
                                <tr>
                                    <td>National Id:{{$witnessDataFiled[0]['national_id']}}</td>
                                    <td>National Id:{{$witnessDataFiled[1]['national_id']}}</td>
                                </tr>
                                @endif
                            </tbody>

                        </table>

                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

</body>
</html>