<?
	require_once("../common/globals.php");

	begin_console_session();	
	if($ses_userid<=0) header("Location: force_login.html");
	
	if(!is_allowed(7,$BIT_MOD)) SendError("Not allowed!");
	
	if(!allowed2do($ouid,$ogid,$rperms,4)) SendError("Not allowed!");	
		
	require_once("../common/connect.php");
	require_once("../classes/ads.php");
	
	$ar=new AdsDBRecord($dbconn);
	$ar->ad_id=$aid;
			
	$success=$ar->delete();
	
	if(!$success) SendError("DB error!"); else 
?>
<HTML>
<HEAD>	
<TITLE>Delete ad</TITLE>
<?=getStyleSheet()?>
</HEAD>
<BODY>

<CENTER>
	<h1>Ad has been deleted successfully!</h1>
	<BR><BR>

</CENTER>

</BODY>
</HTML>