<?php

$url = 'http://basis.org.bd/paymentAPI/get-session';



$paymentData = array(

    'success_url' => 'http://localhost:8000/cash-incentive/payment-success',
    'fail_url' => 'http://localhost/shahin/projects/basis/index.php/payment/fail',
    'cancel_url' => 'http://localhost/shahin/projects/basis/index.php/payment/cancel',

    'total_amount' => 3000,
    'emi_option' => 0,
    'value_a' => 'Type- SCB ERQ (Standard Chartered Bank)',
    'value_b' => 'Individual_Person',
    'value_c' => '10', //app_id
    'cus_name' => 'shahin',
    'cus_email' => 'shahin@batworld.com',
    'cus_add1' => 'Dhaka',
    'cus_add2' => '',
    'cus_city' => '',
    'cus_state' => '',
    'cus_postcode' => '',
    'cus_country' => '',
    'cus_phone' => '',
    'cus_fax' => '',
    'ship_name' => '',
    'ship_add1' => '',
    'ship_add2' => '',
    'ship_city' => '',
    'ship_state' => '',
    'ship_postcode' => '',
    'ship_country' => ''
);



   



$headers = array(
    'accept: application/json',
    'Apikey: VuG8Copi3+5knQfCGogUM7qpOlJDc6fTjD2u3GwGv3PmewNVzyl2any5IFAtSh1TgAastsfabAEmTjVMoyGMwtQhhjB2nNTfGxZKa+fU5ll1b2L+nLP\/JsmG0S4sJBFk'
);




$handle = curl_init();
curl_setopt($handle, CURLOPT_URL, $url);
curl_setopt($handle, CURLOPT_HTTPHEADER, $headers);
curl_setopt($handle, CURLOPT_TIMEOUT, 30);
curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 30);
curl_setopt($handle, CURLOPT_POST, 1);
curl_setopt($handle, CURLOPT_POSTFIELDS, $paymentData);
curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, FALSE); # KEEP IT FALSE IF YOU RUN FROM LOCAL PC


$content = curl_exec($handle);

print_r($content);
//$code = curl_getinfo($handle, CURLINFO_HTTP_CODE);
//
//if ($code == 200 && !(curl_errno($handle))) {
//    curl_close($handle);
//    return $content;
//} else {
//    curl_close($handle);
//    return false;
//}



?>