<?php if(Auth::user()){
//            $user_id =  \App\Libraries\Encryption::encode(Auth::user()->id);
    $user_id =  Auth::user()->id;
}
else{
    $user_id = 0;
}

if (isset($exception)){
    $message="Invalid Id! 401";
}
else{
    $message='Ok';
}

?>


<script type="text/javascript">

    var ip_address = '<?php echo $_SERVER['REMOTE_ADDR'];?>';
    var user_id = '<?php echo $user_id;?>';
    var message = '<?php echo $message;?>';
    var project_name = '<?php echo env('project_code');?>';
    var MongoAuthToken = '<?php echo Session::get('MongoAuthToken');?>';
    var web_service_url = '<?php echo env('web_service_url');?>';
    var project_code = '<?php echo env('project_code');?>';;


</script>
<?php
if(Session::get('MongoAuthToken')){
$urlwebservicejs = url()."/url_webservice/url-webservice.js";
?>
<script src="<?php echo $urlwebservicejs;?>"></script>

<?php } ?>