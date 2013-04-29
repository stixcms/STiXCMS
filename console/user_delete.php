<?
	require_once("../common/globals.php");
	
	begin_console_session();
	if($ses_userid<=0) header("Location: force_login.html");

 	if(!is_allowed(0,$BIT_MOD)) SendError("Not allowed!");

	 		
	require_once("../common/connect.php");
	require_once("../classes/users.php");
	
	$ur=new UserDBRecord($dbconn);
	$ur->get($uid);
	
	$success=$ur->delete();
	
	if(!$success) SendError("Unable to remove user record!"); else 
?>
<HTML>
<HEAD>	
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=ISO-8859-7">
<TITLE>Modify User</TITLE>
<?=getStyleSheet()?>
</HEAD>
<BODY>
	<CENTER>
		<h1>User has been deleted!</h1>
		<br>
		<a href=users.php>[return to user management]</a>
	</CENTER>
</BODY>
</HTML>