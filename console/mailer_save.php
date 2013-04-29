<?
	require_once("../common/globals.php");
	
	begin_console_session();	
	if($ses_userid<=0) header("Location: force_login.html");
	
	if(!is_allowed(11,$BIT_ADD) && $mid<=0) SendError("Not allowed!");
	if(!is_allowed(11,$BIT_MOD) && $mid>0) SendError("Not allowed!");
	
	if(!allowed2do($ouid,$ogid,$rperms,2)) SendError("Not allowed!");	
	
	require_once("../common/connect.php");
	require_once("../classes/mailer.php");
	
	$ar=new MailerDBRecord($dbconn);
	
	$ar->mailer_id=$mid;
	$ar->fromaddr=$mfrom;
	$ar->subject=$msubj;
	$ar->body=$mbody;
	$ar->lastsent=$clastsent;
	$ar->lastsentcount=$clastsentcount;	
	$ar->lastupdatedby=$ses_userid;
	$ar->lastupdated=time();
	$ar->createdate=($mid>0)?$ccreated:time();
	$ar->createdby=($mid>0)?$ccreateu:$ses_userid;
	$ar->OwnerUserID=$ouid;	
	$ar->OwnerGroupID=$ogid;
	$ar->RowPerms=$rperms;
			
	if($mid<=0) $success=$ar->insert(); else $success=$ar->update();
	
	if(!$success) SendError("DB error!"); else 
?>
<HTML>
<HEAD>	
<TITLE>Modify Mailer</TITLE>
<?=getStyleSheet()?>
</HEAD>
<BODY>

<CENTER>
	<h3>Newsletter has been saved successfully!</h3>
	<BR><BR>
	<a href=mailer_form.php?mid=<?=$ar->mailer_id?>>[click to return to newsletter form]</a>	
</CENTER>

</BODY>
</HTML>