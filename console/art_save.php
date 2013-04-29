<?
	require_once("../common/globals.php");

	begin_console_session();	
	if($ses_userid<=0) header("Location: force_login.html");
	
	if(!is_allowed(2,$BIT_MOD) || !is_allowed(2,$BIT_ADD)) SendError("Not allowed!");
	
	if(!allowed2do($ouid,$ogid,$rperms,2)) SendError("Not allowed!");	
		
	require_once("../common/connect.php");
	require_once("../classes/articles.php");
	
	$ar=new ArticleDBRecord($dbconn);
	
	$ar->art_id=$aid;
	$ar->locale=$aloc;
	$ar->title=$atitle;
	$ar->shortdesc=$ashortdesc;
	$ar->fulltxt=$afulltxt;
	$ar->status=$astatus;
	$ar->startdate=date2timestamp($astartdate);
	$ar->enddate=date2timestamp($aenddate);
	if($aorder!="") $ar->art_order=$aorder; else $ar->art_order=10;
	$ar->lastupdatedby=$ses_userid;
	$ar->lastupdated=time();
	$ar->createdate=($aid>0)?$ccreated:time();
	$ar->createdby=($aid>0)?$ccreateu:$ses_userid;
	$ar->OwnerUserID=$ouid;	
	$ar->OwnerGroupID=$ogid;
	$ar->RowPerms=$rperms;
			
	if($aid<=0) $success=$ar->insert(); else $success=$ar->update();
	if($success && $aid<=0) $success2=$ar->attach2node($nid);
	
	if(!$success) SendError("DB error!"); else 
?>
<HTML>
<HEAD>	
<TITLE>Modify Article</TITLE>
<?=getStyleSheet()?>
</HEAD>
<BODY>

<CENTER>
	<h1>Article has been saved successfully!</h1>
	<BR><BR>
	<a href=art_form.php?aid=<?=$ar->art_id?>&nid=<?=$nid?>>[click to return to article]</a>	
</CENTER>

</BODY>
</HTML>