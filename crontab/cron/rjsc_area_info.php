<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set("asia/dhaka");
require 'dbCon.php'; /* return name of current default database */
require 'mail_setting.php';

        $url = 'http://192.168.152.132:8901/token/key';
        $ch = curl_init($url);

        $jsonData = array(
            'api_client_id' => 'PHP_DEV1',
            'secret_code' => '12345'
        );

        $jsonDataEncoded = json_encode($jsonData);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'client_id: PHP_DEV1'
        ));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  // RETURN THE CONTENTS OF THE CALL
        $Rec_Data = curl_exec($ch);
        $apiToken = json_decode($Rec_Data);

        //division data
            $url = 'http://192.168.152.132:8901/api/info/division';
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 150);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                "token_key: $apiToken->accessToken",
                "client_id: PHP_DEV1"
                    ));
            $Rec_Data = curl_exec($ch);
            $responseData = json_decode($Rec_Data);
            foreach ($responseData->_embedded->divisionDTOList as $row){
                $sql = "SELECT rjsc_id FROM rjsc_area_info WHERE rjsc_id= $row->id";
                $query = $mysqli->query($sql);
                $result = $query->num_rows;
                if($result >=1){
                    echo "conflict the id or already exist the id <br>";
                    continue;
                }
                $sql1 = "INSERT INTO rjsc_area_info
                         (rjsc_id, `name`, pare_id,area_type)
                         VALUES ('$row->id','$row->name',0,1)";
                $mysqli->query($sql1);
            }

            //district data
            $url = '192.168.152.132:8901/api/info/district';
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 150);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                "token_key: $apiToken->accessToken",
                "client_id: PHP_DEV1"
            ));
            $Rec_Data1 = curl_exec($ch);
            $responseDataDistrict = json_decode($Rec_Data1);
            foreach ($responseDataDistrict->_embedded->districtDTOList as $rowDistrict){
                $sql = "SELECT rjsc_id FROM rjsc_area_info WHERE rjsc_id= $rowDistrict->id";
                $query = $mysqli->query($sql);
                $result = $query->num_rows;
                if($result >=1){
                echo "conflict the id or already exist the id <br>";
                continue;
                }
                $sql1 = "INSERT INTO rjsc_area_info
                                     (rjsc_id, `name`, pare_id,area_type)
                                     VALUES ('$rowDistrict->id','$rowDistrict->name', $rowDistrict->div_id,2)";
                $mysqli->query($sql1);
            }
            echo "successfully Data insert";


/* End of Sending to PDF Server from pdf_print_requests table */

$mysqli->close();

die();