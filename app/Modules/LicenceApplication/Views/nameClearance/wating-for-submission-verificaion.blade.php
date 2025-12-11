<?php
$accessMode = ACL::getAccsessRight('NameClearance');
if (!ACL::isAllowed($accessMode, $mode)) {
    die('You have no access right! Please contact with system admin if you have any query.');
}
?>


    <style>
        #loding-msg{
            position: absolute;
            top: 37%;
            left: 340px;
            font-size: 24px;
            font-weight: bold;
            width: 30%;
            z-index: 600;
            padding: 20px 10px 20px 10px;
        }
    </style>
    <section class="content">
        <div id="loading">
            <?php $userPic = URL::to('/assets/images/loading.gif'); ?>
                <Span class="alert alert-success"  id="loding-msg"><i class="fa fa-spinner fa-spin"></i>  Waiting for response form RJSC.</Span>
        </div>

        <div class="col-md-12">

        </div>
    </section>
    <script  type="text/javascript">
        $(document).ready(function() {
            checkgenerator();
            function checkgenerator()
            {
                var url = '<?php echo url();?>';

                $.ajax({
                    url: '/licence-applications/name-clearance/check-submission-verification',
                    type: "POST",
                    data: {
                        verification_id: '{{$verifyid}}'
                    },
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        if (response.responseCode == 1) {
                            if(response.status == -2) {
                                alert(response.message);
                                window.location.reload();
                            }else if (response.status == 1) {
                                // alert(response.message);
                                window.location=(url+"/licence-applications/name-clearance/check-rjsc-status/"+response.app_id+'/'+response.payment_id);
                            }else if (response.status == 0 || response.status == -1) {
//                            $('.msg1').html('In Progress');
                                myVar = setTimeout(checkgenerator, 5000);
                            }else{
                                alert(response.message);
                            }
                        } else {
                            alert('Whoops there was some problem please contact with system admin.');
                            window.location.reload();
                        }
                    },error: function(jqXHR, textStatus, errorThrown) {
                        alert(errorThrown);
                        console.log(errorThrown);
                    }
                });
                return false; // keeps the page from not refreshing
            }
        });


    </script>
