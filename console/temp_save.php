<?
	require_once("../common/globals.php");

	begin_console_session();	
	if($ses_userid<=0) header("Location: force_login.html");
	
	if(!is_allowed(5,$BIT_MOD) && $tid>0) SendError("Not allowed!");
	if(!is_allowed(5,$BIT_ADD) && $tid<=0) SendError("Not allowed!");
	
	if(!allowed2do($ouid,$ogid,$rperms,2)) SendError("Not allowed!");	
		
	require_once("../common/connect.php");
	require_once("../classes/template.php");
	
	$tr=new TemplateDBRecord($dbconn);
	
	$tr->temp_id=$tid;
	$tr->title=$atitle;
	$tr->html=$ahtml;
	$tr->status=$astatus;
	$tr->lastupdatedby=$ses_userid;
	$tr->lastupdated=time();
	$tr->createdate=($aid>0)?$ccreated:time();
	$tr->createdby=($aid>0)?$ccreateu:$ses_userid;
	$tr->OwnerUserID=$ouid;	
	$tr->OwnerGroupID=$ogid;
	$tr->RowPerms=$rperms;
			
	if($tid<=0) $success=$tr->insert(); else $success=$tr->update();

	if(!$success) SendError("DB error!"); else 
?>
<HTML>
<HEAD>	
<TITLE>Modify Template</TITLE>
<?=getStyleSheet()?>
</HEAD>
<BODY>

<CENTER>
	<h1>Template has been saved successfully!</h1>
	<BR><BR>
	<a href=temp_form.php?tid=<?=$tr->temp_id?>>[click to return to Template form]</a>	
</CENTER>

</BODY>
</HTML>