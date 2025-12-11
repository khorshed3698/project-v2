<?php
@session_start();

//DATABASE DETAILS 
//SET HOSTNAME
$db_hostname = config('app.db_host');

//MYSQL USERNAME
$db_username = config('app.db_username');

//MYSQL PASSWORD
$db_password = config('app.db_password');

//MYSQL DATABASE NAME
$db_name = config('app.db_database');

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