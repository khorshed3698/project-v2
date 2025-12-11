<?php
require_once 'dbCon.php';
$process_type_id = 2;
$app_id =  213;
$user_list = $mysqli->query("SELECT pl.status_id,pp.status_to from process_list as pl 
LEFT JOIN(select IFNULL(cat_id,1) as p_cat_id,department_id as pcm_department_id from process_path_cat_mapping where process_path_cat_mapping.process_type_id = $process_type_id) as ppcm 
ON ppcm.pcm_department_id = pl.department_id 
LEFT JOIN process_path as pp ON pp.process_type_id = pl.process_type_id
  and pp.desk_from = pl.desk_id and pl.status_id = pp.status_from and ppcm.p_cat_id = pp.cat_id 
   where pl.process_type_id = $process_type_id and pl.ref_id = $app_id");
$to_status ="";
while ($row = $user_list->fetch_assoc()){
    $to_status .= $row['status_to'].',';
}
$status_to_accept =['9'];
$status_to_arr = explode(',',rtrim($to_status,',')) ;

$result = array_intersect_key($status_to_accept,$status_to_arr);
$to_status_confirm = $result[0];
$status_data =$mysqli->query("select status_name from process_status where id=$to_status_confirm and 
process_type_id = $process_type_id");
$abc = $status_data->fetch_assoc();
echo $abc['status_name'];
?>