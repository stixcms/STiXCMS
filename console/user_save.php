<?
	require_once("../common/globals.php");
	
	begin_console_session();
	if($ses_userid<=0) header("Location: force_login.html");

 	if(!is_allowed(0,$BIT_MOD) || !is_allowed(0,$BIT_ADD)) SendError("Not allowed!");

	 		
	require_once("../common/connect.php");
	require_once("../classes/users.php");
	
	$ur=new UserDBRecord($dbconn);
	
	$ur->user_id=$uid;
	$ur->group_id=$ugid;	
	$ur->username=$uusername;
	$ur->password=$upassword;
	$ur->name=$uname;
	$ur->email=$uemail;
	$ur->status=$ustatus;
	$ur->maxartstatus=$umastat;
			
	if($uid<=0) $success=$ur->insert(); else $success=$ur->update();
	
	if(!$success) SendError("DB error!"); else 
?>
<HTML>
<HEAD>	
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=ISO-8859-7">
<TITLE>Modify User</TITLE>
<?=getStyleSheet()?>
</HEAD>
<BODY>
	<CENTER>
		<h1>User details have been saved!</h1>
		<br>
		<a href=users.php>[return to user management]</a>
	</CENTER>
</BODY>
</HTML>