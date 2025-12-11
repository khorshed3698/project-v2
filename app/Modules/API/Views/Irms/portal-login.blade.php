<!DOCTYPE html>
<html lang="en" xml:lang="en">
<head>
  <title></title>
</head>
<body onload='document.forms[0].submit()'>
<form name='PostForm' method='POST' action="{{$data['login_url']}}">
<textarea name='datarequest' id='datarequest' rows='15' style='width:100%; display:none;'><?xml version='1.0' encoding='UTF-8'?><IrmsRequestFromBida><RequestInformation><Authentication><AccessToken>{{$data['access_token']}}</AccessToken></Authentication><ReferenceInfo><TrackingNumber>{{$data['tracking_no']}}</TrackingNumber><UserType>{{$data['user_type']}}</UserType><UserName>{{$data['user_name']}}</UserName><UserEmail>{{$data['user_email']}}</UserEmail><RequestId>{{$data['request_id']}}</RequestId><BackUrl>{{$data['back_url']}}</BackUrl></ReferenceInfo></RequestInformation></IrmsRequestFromBida></textarea>
</form>
</body>
</html>