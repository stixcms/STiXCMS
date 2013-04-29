<?
	require_once("../common/globals.php");
	
	begin_console_session();	
	if($ses_userid<=0) header("Location: force_login.html");
	
	if(!is_allowed(11,$BIT_MOD)) SendError("Not allowed!");
	
	if(!allowed2do($ouid,$ogid,$rperms,4)) SendError("Not allowed!");	
		
	require_once("../common/connect.php");
	require_once("../classes/mailer.php");
	
	$ar=new MailerDBRecord($dbconn);
	$ar->mailer_id=$mid;
			
	$success=$ar->delete();
	
	if(!$success) SendError("DB error!"); else 
?>
<HTML>
<HEAD>	
<TITLE>Delete Mailer</TITLE>
<?=getStyleSheet()?>
</HEAD>
<BODY>

<CENTER>
	<h3>Newsletter has been deleted!</h3>
	<BR><BR>

</CENTER>

</BODY>
</HTML>