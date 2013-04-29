<?
	require_once("../common/globals.php");
	
	start_site_session();
	
	$ses_locale=$setlocale;
	
	header("Location: ".$HTTP_REFERER);
?>	