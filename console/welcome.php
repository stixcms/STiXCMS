<?
	require_once("../common/globals.php");
	
	begin_console_session();	
	if($ses_userid<=0) header("Location: force_login.html");
?>
<HTML>
<HEAD>
<TITLE>Welcome</TITLE>
<?=getStyleSheet()?>
</HEAD>
<BODY>
	<center>
	<h1>Welcome <?=$ses_username?> !</h1>
	</center>
</BODY>
</HTML>
