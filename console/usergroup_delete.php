<?
	require_once("../common/globals.php");

	begin_console_session();
	if($ses_userid<=0) header("Location: force_login.html");

 	if(!is_allowed(0,$BIT_MOD)) SendError("Not allowed!");


	require_once("../common/connect.php");
	require_once("../classes/usergroup.php");
	
	$ur=new UsergroupDBRecord($dbconn);
	$ur->group_id=$ugid;	

	$success=$ur->delete();
	
	if(!$success) SendError("DB error!"); else 
?>
<HTML>
<HEAD>	
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=ISO-8859-7">
<TITLE>Modify UserGroup</TITLE>
<?=getStyleSheet()?>
</HEAD>
<BODY>
	<CENTER>
		<h1>Επιτυχής διαγραφή!</h1>
		<br>
		<a href=users.php>[Επιστροφή στη διαχείρηση χρηστών]</a>
	</CENTER>
</BODY>
</HTML>