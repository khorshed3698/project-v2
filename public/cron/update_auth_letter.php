<?php
require_once 'dbCon.php'; /* return name of current default database */
require_once 'mail_setting.php';

$count = 0;
$result = $mysqli->query("SELECT id,company_ids,authorization_file from users where company_ids != 0");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $company_ids_array = explode(',', $row['company_ids']);

        if(count($company_ids_array) == 1){
            $result1 = $mysqli->query("SELECT user_id from company_association_request where user_id = $row[id] and requested_company_id = $row[company_ids]");
            $row1 = $result1->fetch_assoc();
            if($row['id'] == $row1['user_id']){
                echo $row['id'].' success <br/>';
                $sql = $mysqli->query("UPDATE company_association_request SET authorization_letter='$row[authorization_file]' WHERE user_id = $row[id] AND requested_company_id = '$row[company_ids]'");
            }else{
                echo $row['id'].' company did not match <br/>';
            }
        }else{
            echo $row['id'].' has multiple companies. <br/>';
        }
    }
    $result->close();
}

$mysqli->close();
die();
