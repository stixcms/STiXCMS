<?
	require_once("../common/globals.php");

	begin_console_session();	
	if($ses_userid<=0) header("Location: force_login.html");
	
	if(!is_allowed(7,$BIT_MOD) && $aid>0) SendError("Not allowed!");
	if(!is_allowed(7,$BIT_ADD) && $aid<=0) SendError("Not allowed!");
	
	if(!allowed2do($ouid,$ogid,$rperms,2)) SendError("Not allowed!");	
		
	require_once("../common/connect.php");
	require_once("../classes/ads.php");
	
	$ar=new AdsDBRecord($dbconn);
	
	$ar->ad_id=$aid;
	$ar->name=$aname;
	$ar->att_id=$aattid;	
	$ar->adtext=$atext;
	$ar->hlink=$ahlink;
	$ar->status=$astatus;
	if($aorder!="") $ar->aorder=$aorder; else $ar->aorder=10;
	$ar->lastupdatedby=$ses_userid;
	$ar->lastupdated=time();
	$ar->createdate=($aid>0)?$ccreated:time();
	$ar->createdby=($aid>0)?$ccreateu:$ses_userid;
	$ar->OwnerUserID=$ouid;	
	$ar->OwnerGroupID=$ogid;
	$ar->RowPerms=$rperms;
			
	if($aid<=0) $success=$ar->insert(); else $success=$ar->update();
	if($success) {
		$ar->detachAll();
		for($i=0; $i<count($cats); $i++) $ar->attach2node($cats[$i]);
	}
	if(!$success) SendError("DB error!"); else 
?>
<HTML>
<HEAD>	
<TITLE>Modify Ad</TITLE>
<?=getStyleSheet()?>
</HEAD>
<BODY>

<CENTER>
	<h1>Ad has been saved successfully!</h1>
	<BR><BR>
	<a href=ad_form.php?aid=<?=$ar->ad_id?>&nid=<?=$nid?>>[click to return to ad]</a>	
</CENTER>

</BODY>
</HTML>