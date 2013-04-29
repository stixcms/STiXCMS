<?
	require_once("../common/globals.php");

	begin_console_session();	
	if($ses_userid<=0) header("Location: force_login.html");
	
	if(!is_allowed(2,$BIT_MOD)) SendError("Not allowed!");
	
	if(!allowed2do($ouid,$ogid,$rperms,4)) SendError("Not allowed!");	
		
	require_once("../common/connect.php");
	require_once("../classes/articles.php");
	
	$ar=new ArticleDBRecord($dbconn);
	$ar->art_id=$aid;
			
	$success=$ar->delete();
	
	if(!$success) SendError("DB error!"); else 
?>
<HTML>
<HEAD>	
<TITLE>Delete Article</TITLE>
<?=getStyleSheet()?>
</HEAD>
<BODY>

<CENTER>
	<h1>Article has been deleted!</h1>
	<BR><BR>

</CENTER>

</BODY>
</HTML>