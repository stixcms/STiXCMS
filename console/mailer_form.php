<?
	require_once("../common/globals.php");

	begin_console_session();	
	if($ses_userid<=0) header("Location: force_login.html");

	if(!is_allowed(11,$BIT_ADD) && $mid<=0) SendError("Not allowed!");
 	if(!is_allowed(11,$BIT_MOD) && $mid>0) SendError("Not allowed!");
			
	require_once("../common/connect.php");
	require_once("../classes/mailer.php");

	
	$med=new MailerDBRecord($dbconn);
	if($mid>0) {
		$med->mailer_id=$mid;
		$med->get();
	} else {
		$med->OwnerUserID=$ses_userid;	
		$med->OwnerGroupID=$ses_groupid;
		$med->RowPerms=$DEFAULT_MASK;		
	}

	if(!allowed2do($med->OwnerUserID,$med->OwnerGroupID,$med->RowPerms,1)) SendError("Not allowed!");	
	if(!allowed2do($med->OwnerUserID,$med->OwnerGroupID,$med->RowPerms,2)) $readonly="disabled"; else $readonly="";	

?>
<HTML>
<HEAD>	
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=ISO-8859-7">
<TITLE>Mailer</TITLE>
<?=getStyleSheet()?>
<SCRIPT Language=Javascript1.2 SRC="../common/common.js"></SCRIPT>
</HEAD>
<BODY>

<form name=medf action=mailer_save.php method=post>
<input type=hidden name=mid value="<?=$mid?>">
<input type=hidden name=clastsent value="<?=$med->lastsent+0?>">
<input type=hidden name=clastsentcount value="<?=$med->lastsentcount+0?>">
<input type=hidden name=ccreated value="<?=$med->createdate?>">
<input type=hidden name=ccreateu value="<?=$med->createdby?>">
<input type=hidden name=ouid value="<?=$med->OwnerUserID?>">
<input type=hidden name=ogid value="<?=$med->OwnerGroupID?>">
<input type=hidden name=rperms value="<?=$med->RowPerms?>">

<table border=0 cellspacing=0 cellpadding=2 class=tab>
<tr>
<th colspan=4>Newsletter details form</td>
</tr>
<tr>
<td>From:</td>
<td colspan=3><input <?=$readonly?> type=text name=mfrom size=50 value="<?=$med->fromaddr?>" onChange=check4quotes(this); class=form></td>
</tr>
<tr>
<td>Subject:</td>
<td colspan=3><input <?=$readonly?> type=text name=msubj size=50 value="<?=$med->subject?>" onChange=check4quotes(this); class=form></td>
</tr>
<tr>
<td>Body:</td>
<td colspan=3><textarea <?=$readonly?> name=mbody cols=50 rows=12><?=$med->body?></textarea></td>
</tr>
<?	if($readonly=="") {	?>
<tr>
<td>Security:</td>
<td colspan=3><a href=Javascript:openPermWindow('medf');>[edit settings ...]</a></td>
</tr>
<?	}	?>
<tr>
<td colspan=2><? if(allowed2do($med->OwnerUserID,$med->OwnerGroupID,$med->RowPerms,2)) { ?><input type=button class=button value="save" onClick="if(document.medf.msubj.value.length>0) document.medf.submit(); else alert('Empty Subject!');"><? } ?></td>
<td><input type=button class=button value="cancel" onClick="location.href='mailers.php';"></td>
<td align=right><? if(allowed2do($med->OwnerUserID,$med->OwnerGroupID,$med->RowPerms,4)) { if($mid>0) { ?><input type=button class=button value="delete" onClick="if(confirm('Are you sure?')) { document.medf.action='mailer_delete.php'; document.medf.submit(); }"><? } } ?></td>
</tr>
</table>

</form>

<? if($mid>0) showRecProps($med->createdate,$med->lastupdated); ?>

</BODY>
</HTML>