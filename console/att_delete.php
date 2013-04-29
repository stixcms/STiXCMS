<?
	require_once("../common/globals.php");

	begin_console_session();	
	if($ses_userid<=0) header("Location: force_login.html");
	
	if(!is_allowed(3,$BIT_MOD)) SendError("Not allowed!");
	
	if(!allowed2do($ouid,$ogid,$rperms,4)) SendError("Not allowed!");	
		
	require_once("../common/connect.php");
	require_once("../classes/attachment.php");
	
	$ar=new AttachmentDBRecord($dbconn);
	$ar->att_id=$aid;
			
	$success=$ar->delete();
	
	if(!$success) SendError("DB error!"); else 
?>
<HTML>
<HEAD>	
<TITLE>Delete Attachment</TITLE>
<?=getStyleSheet()?>
</HEAD>
<BODY>

<CENTER>
	<h1>Media/File has been deleted!</h1>
	<BR><BR>

</CENTER>

</BODY>
</HTML>