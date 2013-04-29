<?
	require_once("../common/globals.php");
	
	begin_console_session();
	if($ses_userid<=0) header("Location: force_login.html");

 	if(!is_allowed(6,$BIT_MOD) && $lid>0) SendError("Not allowed!");
 	if(!is_allowed(6,$BIT_ADD) && $lid<=0) SendError("Not allowed!");

	 		
	require_once("../common/connect.php");
	require_once("../classes/locale.php");
	
	$lr=new LocaleDBRecord($dbconn);
	
	$lr->locale=$lid;
	$lr->name=$lname;
	$lr->iso=$liso;
			
	if($lid<=0) $success=$lr->insert(); else $success=$lr->update();
	
	if(!$success) SendError("DB error!"); else 
?>
<HTML>
<HEAD>	
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=ISO-8859-7">
<TITLE>Modify Locale</TITLE>
<?=getStyleSheet()?>
</HEAD>
<BODY>
	<CENTER>
		<h1>Locale details have been saved!</h1>
		<br>
		<a href=config.php>[return to Config]</a>
	</CENTER>
</BODY>
</HTML>