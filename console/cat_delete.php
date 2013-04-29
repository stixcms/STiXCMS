<?
	require_once("../common/globals.php");
	
	begin_console_session();	
	if($ses_userid<=0) header("Location: force_login.html");	

 	if(!is_allowed(1,$BIT_MOD)) SendError("Not allowed!");
 			
	require_once("../common/connect.php");
	require_once("../classes/category.php");
	
	$cr=new CategoryDBRecord($dbconn);
	
	$cr->cat_id=$id;
	$success=$cr->delete();
	
	if(!$success) SendError("Δεν είναι δυνατή η διαγραφή της κατηγορίας!"); else 
?>
<HTML>
<HEAD>	
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=ISO-8859-7">
<TITLE>Modify Category Form</TITLE>
<?=getStyleSheet()?>
</HEAD>
<BODY onLoad="opener.location.href=opener.location.href;">

<CENTER>
	<h1>Node has been removed!</h1>
	<BR><BR>
	<a href=# onClick="opener.focus(); self.close();">[click to continue]</a>
</CENTER>

</BODY>
</HTML>