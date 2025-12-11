<?php
@session_start();

//DATABASE DETAILS 
//SET HOSTNAME
$db_hostname = "103.219.147.9";

//MYSQL USERNAME
$db_username ="bidauatusr";

//MYSQL PASSWORD
$db_password="BNh#21%^h89Vc";

//MYSQL DATABASE NAME
$db_name="bida_uat_new";


/*SET THE DEFAULT PAGE PER RECORD LIMIT*/
if(!isset($_SESSION['pagerecords_limit']))
{
	$_SESSION['pagerecords_limit']=20;
}

/*DEFINE CONSTANT FOR THE SITE */

// TABLE PREFIX
define("TABLE_PREFIX","");	//DATABASE TABLE PREFIX IF YOU HAVE SET LIKE : hm_user_master. => "bh_" otherwise leave it blank.

// SECRET KEY FOR PASSWORD ENCRYPT
define("SECRET_KEY","!@#$1234%$#@!");	/*IMPORTANT*/
?>