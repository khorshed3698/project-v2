<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set("asia/dhaka");
require 'dbCon.php'; /* return name of current default database */
require 'mail_setting.php';

$path = public_path() . "/Address.json"; // ie: /var/www/laravel/app/storage/json/filename.json

$json = json_decode(file_get_contents($path), true);
//dd($json['address'][0]['next']);

//country
//foreach ($json['address'] as $row){
//    dd($row);
//    $fil=  $row['fieldValue'];
//    $filid=  $row['fieldId'];
//    $sql1 = "INSERT INTO etin_area_info
//                         (etin_area_id, `name`, pare_id,area_type)
//                         VALUES ('$filid','$fil',0,0)";
//    $mysqli->query($sql1);
//}

//district
$arrIdDistrict = [];
foreach ($json['address'][0]['next'] as $row){
    $fil=  $row['fieldValue'];
    $filid=  $row['fieldId'];
    $arrIdDistrict[] =$filid;
//    $sql1 = "INSERT INTO etin_district_info
//                         (etin_district_id, `name`, country_id)
//                         VALUES ('$filid','$fil',1)";
//    $mysqli->query($sql1);
}

//dd($arrIdDistrict);
//thana
//dd($json['address'][0]['next']);
//dd($arrIdDistrict);

foreach ($arrIdDistrict as $row44){
    foreach ($json['address'][0]['next'] as $key=>$row1){
        if($row44 == $row1['fieldId']){
            foreach ($row1['next'] as $rowTha){
                $fil=  $rowTha['fieldValue'];
                $filid=  $rowTha['fieldId'];
                $sql1 = "INSERT INTO etin_thana_info
                         (etin_thana_id, `name`, district_id)
                         VALUES ('$filid','$fil',$arrIdDistrict[$key])";
                $mysqli->query($sql1);
            }
        }

    }
}


dd($json['address'][0]['next']);

dd(44);
//dd($json);