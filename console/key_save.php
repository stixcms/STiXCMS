<?
	require_once("../common/globals.php");
	
	begin_console_session();
	if($ses_userid<=0) header("Location: force_login.html");

 	if(!is_allowed(10,$BIT_MOD) && $kid>0) SendError("Not allowed!");
 	if(!is_allowed(10,$BIT_ADD) && $kid<=0) SendError("Not allowed!");

	 		
	require_once("../common/connect.php");
	require_once("../classes/keywords.php");
	
	$kr=new KeywordsDBRecord($dbconn);
	$kr->key_id=$kid;
	$kr->locale=$kloc;
	$kr->keyword=$kword;
	$kr->createdate=($kid>0)?$ccreated:time();
	$kr->createdby=($kid>0)?$ccreateu:$ses_userid;
	$kr->lastupdated=time();
	$kr->lastupdatedby=$ses_userid;
				
	if($kid<=0) $success=$kr->insert(); else $success=$kr->update();
	
	if(!$success) SendError("DB error!"); else 
?>
<HTML>
<HEAD>	
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=ISO-8859-7">
<TITLE>Modify Keyword</TITLE>
<?=getStyleSheet()?>
</HEAD>
<BODY>
	<CENTER>
		<h1>Keyword details have been saved!</h1>
		<br>
		<a href=keywords.php>[return to keywords]</a>
	</CENTER>
</BODY>
</HTML>