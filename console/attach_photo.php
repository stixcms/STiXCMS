<?
	require_once("../common/globals.php");

	begin_console_session();	
	if($ses_userid<=0) header("Location: force_login.html");
	
	if(!is_allowed(2,$BIT_MOD)) SendError("Not allowed!");
	
		
	require_once("../common/connect.php");
	require_once("../classes/category.php");
	
	$ar=new CategoryDBRecord($dbconn);
	$success=$ar->attach_related($pid,$lid,$rid,"");
	if(!$success) SendError("DB error!"); else 

?>

<HTML>
<HEAD>	
<TITLE>Modify Article</TITLE>
<?=getStyleSheet()?>
</HEAD>
<BODY>
 
<CENTER>
	<h1>related material has been attached successfully!</h1>
	<BR><BR>
	Select a category from the left menu or
	<a href=# onClick="parent.doClose();">close this window</a>	.
</CENTER>

</BODY>
</HTML>