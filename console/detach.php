<?
	require_once("../common/globals.php");

	begin_console_session();	
	if($ses_userid<=0) header("Location: force_login.html");
	
	if(!is_allowed(2,$BIT_MOD)) SendError("Not allowed!");
	
		
	require_once("../common/connect.php");
	require_once("../classes/articles.php");
	
	$ar=new ArticleDBRecord($dbconn);
	$ar->art_id=$pid;
	
	$success=$ar->detach_related($rid,$rt);
	
	
	if(!$success) SendError("DB error!"); else 
?>
<HTML>
<HEAD>	
<TITLE>Modify Article</TITLE>
<?=getStyleSheet()?>
</HEAD>
<BODY>

<CENTER>
	<h1>related material has been detached successfully!</h1>
	<BR><BR>
	<a href=art_form.php?aid=<?=$pid?>&nid=<?=$nid?>>[return to article]</a>	
</CENTER>

</BODY>
</HTML>