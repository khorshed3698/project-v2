<!DOCTYPE html>
<html lang="bn" xml:lang="bn">
<head>
  <title></title>
</head>
<body onload='document.forms[0].submit()'>
<form name='PostForm' method='POST' action='{{ config('payment.spg_settings.spg_web_portal_url') }}'>
<textarea name='datarequest' id='datarequest' rows='15' style='width:100%; display:none;'>
<SpsRequestByeChallan>
<RequestInformation>
 <Authentication>
<ApiAccessUserId>{{ config('payment.spg_settings.spg_user_id') }}</ApiAccessUserId>
<AuthenticationKey>{{$sessionToken}}</AuthenticationKey>
 </Authentication>
 <ReferenceInfo>
<RequestId>{{ $paymentInfo->request_id }}</RequestId>
<RefTranNo>{{ $paymentInfo->ref_tran_no }}</RefTranNo>
<RefTranDateTime>{{ $paymentInfo->ref_tran_date_time }}</RefTranDateTime>
<ReturnUrl>{{ config('payment.spg_settings.return_url') }}</ReturnUrl>
<ReturnMethod>POST</ReturnMethod>
<TranAmount>{{ $paymentInfo->pay_amount }}</TranAmount>
<ContactName>{{ $paymentInfo->contact_name }}</ContactName>
<ContactNo>{{ $paymentInfo->contact_no }}</ContactNo>
<PayerId>{{ $paymentInfo->id }}</PayerId>
<Address>{{ $paymentInfo->address }}</Address>
 </ReferenceInfo>
 <CreditInformation>
<CreditInfo>
<SLNO>{{ $paymentInfo->sl_no }}</SLNO>
<CreditAccount>{{ config('payment.spg_settings.spg_SBL_account') }}</CreditAccount>
<CrAmount>{{ $paymentInfo->pay_amount }}</CrAmount>
<Purpose>Application fee</Purpose>
<Onbehalf>{{ $paymentInfo->contact_name }}</Onbehalf>
</CreditInfo>
 </CreditInformation>
</RequestInformation>
</SpsRequestByeChallan>
</textarea></form>
</body>
</html>