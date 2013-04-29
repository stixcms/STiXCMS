<?
	require_once("../common/globals.php");
	
	begin_console_session();	
	if($ses_userid<=0) header("Location: force_login.html");

 	if(!is_allowed(11,$BIT_READ)) SendError("Not allowed!");
 	
	require_once("../common/connect.php");


?>
<HTML>
<HEAD>	
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=ISO-8859-7">
<TITLE>Newsletter</TITLE>
<?=getStyleSheet()?>
</HEAD>
<BODY>

<table border=0 width=100%>
<tr>
<td valign=top>
	Not installed!
</td>
</tr>
</table>

</BODY>
</HTML>