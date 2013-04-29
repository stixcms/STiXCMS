<?
	require_once("../common/globals.php");

	begin_console_session();
		
	if($ses_userid<=0) header("Location: force_login.html");
?>
<HTML>
<HEAD>
<TITLE>workflow management console</TITLE>
</HEAD>

<FRAMESET rows="70,*" border=0 frameborder=0>
<FRAME NAME=nav SRC="nav.php" >
<FRAME NAME=main SRC="welcome.php">
</FRAMESET>

</HTML>
