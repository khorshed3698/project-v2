<?php


function cronAuditSave($time_index, $rowcount, $comment, $mysqli){
    $path = dirname(__FILE__);

    $link = $_SERVER['PHP_SELF'];
    $link_array = explode('/',$link);
    $page = end($link_array);

    $file_name =  $page;
    $full_address = rawurlencode($path.'\\'.$page);
    $record_index = $time_index;
    $no_of_record = $rowcount;
    $comments = $comment;

    $cronJobAudit = "INSERT INTO cron_job_audit (file_name, full_address, record_index, no_of_record, comments, cron_run_time)
         VALUES ('" . $file_name . "', '" . $full_address . "', '" . $record_index . "', '" . $no_of_record . "', '" . $comments . "', NOW()) 
         ON DUPLICATE KEY UPDATE    
        file_name='" . $file_name . "', full_address='" . $full_address . "', record_index='" . $record_index . "', 
        no_of_record='" . $no_of_record . "', comments='" . $comments . "',
         cron_run_time=NOW()";
    $mysqli->query($cronJobAudit);
    return true;
}

?>