<html>
<head></head>
<body onload='document.forms[0].submit()'>
<form name='PostForm' method='POST' action='{{ $spg_config['web_portal_url'] }}'>
<textarea name='datarequest' id='datarequest' rows='15' style='width:100%; display:none;'>
<SpsRequestByeChallan>
<RequestInformation>
 <Authentication>
<ApiAccessUserId>{{ $spg_config['user_id'] }}</ApiAccessUserId>
<AuthenticationKey>{{$sessionToken}}</AuthenticationKey>
 </Authentication>
 <ReferenceInfo>
<RequestId>{{ $paymentInfo->request_id }}</RequestId>
<RefTranNo>{{ $paymentInfo->ref_tran_no }}</RefTranNo>
<RefTranDateTime>{{ $paymentInfo->ref_tran_date_time }}</RefTranDateTime>
<ReturnUrl>{{ $spg_config['return_url'] }}</ReturnUrl>
<ReturnMethod>POST</ReturnMethod>
<TranAmount>{{ $paymentInfo->pay_amount }}</TranAmount>
<ContactName>{{ $paymentInfo->contact_name }}</ContactName>
<ContactNo>{{ $paymentInfo->contact_no }}</ContactNo>
<PayerId>{{ $paymentInfo->id }}</PayerId>
<Address>{{ $paymentInfo->address }}</Address>
 </ReferenceInfo>
 <CreditInformation>
  <?php $sl=1; ?>
  @foreach($account_details as $data)
   <CreditInfo>
<SLNO>{{$sl}}</SLNO>
<CreditAccount>{{ $data->receiver_ac_no }}</CreditAccount>
<CrAmount>{{$data->pay_amount}}</CrAmount>
<Purpose>{{$data->ref_tran_no}}</Purpose>
<Onbehalf>{{$paymentInfo->contact_name }}</Onbehalf>
</CreditInfo>
   <?php $sl++; ?>
  @endforeach

 </CreditInformation>
</RequestInformation>
</SpsRequestByeChallan>
</textarea></form>
</body>
</html>