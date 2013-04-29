<?
	require_once("../classes/db_mysql.php");
	
	$dbconn=new stcmt_db(); 
	$dbconn->open($DB_SRV,$DB_USR,$DB_PAS,$DB_NAM);
?>