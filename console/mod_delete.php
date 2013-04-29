<?
	require_once("../common/globals.php");

	begin_console_session();	
	if($ses_userid<=0) header("Location: force_login.html");
	
	if(!is_allowed(5,$BIT_MOD)) SendError("Not allowed!");
	
	if(!allowed2do($ouid,$ogid,$rperms,4)) SendError("Not allowed!");	
		
	require_once("../common/connect.php");
	require_once("../classes/module.php");
	
	$ar=new ModuleDBRecord($dbconn);
	$ar->mod_id=$mid;
			
	$success=$ar->delete();
	
	if(!$success) SendError("DB error!"); else 
?>
<HTML>
<HEAD>	
<TITLE>Delete Module</TITLE>
<?=getStyleSheet()?>
</HEAD>
<BODY>

<CENTER>
	<h1>Το Module διεγράφη!</h1>
	<BR><BR>

</CENTER>

</BODY>
</HTML>